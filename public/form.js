const modelSelector = $("#model-selector");
const form = $("form");
const result = $("#result");

const baseUrl = window.location.protocol + "//" + window.location.host;

(async () => {

    // Fetch all JSON schemas.
    const allJsonSchemas = await lib.SchemExtractor.fromFile(baseUrl + "/openapi.yaml");

    // Filter relevant JSON schemas.
    const jsonSchemas = {};
    Object.keys(allJsonSchemas)
        .filter(lib.isRelevantJsonSchema)
        .forEach(key => {
            jsonSchemas[key] = lib.adaptJsonSchemaToDraft03(allJsonSchemas[key]);
            jsonSchemas[key] = lib.removeJsonSchemaProperty(jsonSchemas[key], "audits");
        });

    // List relevant JSON schemas within select box.
    Object.keys(jsonSchemas).forEach(key => modelSelector.append(new Option(key, key)));

    modelSelector.change(() => {

        result.html("")
            .removeClass("alert-danger")
            .removeClass("alert-success");

        form.html("")
            .removeClass("jsonform-hasrequired");

        if (!modelSelector.val()) {
            return;
        }

        form.jsonForm({
            schema: jsonSchemas[modelSelector.val()],
            /* If onSubmit() is provided, onSubmitValid() is not executed
            onSubmit: (errors, values) => {},
            */
            onSubmitValid: function (values) {
                $.ajax({
                    method: "POST",
                    url: baseUrl + "/students",
                    data: JSON.stringify(values),
                    contentType: "application/json",
                    dataType: "json",
                    processData: false
                })
                    .done(response => {
                        result.removeClass("alert-danger")
                            .addClass("alert-success");
                        result.html(JSON.stringify(response, null, 2));
                    })
                    .fail(response => {
                        result.removeClass("alert-success")
                            .addClass("alert-danger");
                        result.html(JSON.stringify(response.responseJSON, null, 2));
                    });
            }
        });

    });

})();