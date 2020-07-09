var modelSelector = $("#model-selector");
var _form = $("form");
var result = $("#result");

var baseUrl = window.location.protocol + "//" + window.location.host;
var modelFromHash = window.location.hash.substr(1).replace(/,.*$/, "");
var idFromHash = window.location.hash.substr(1).replace(modelFromHash, "").replace(",", "");
var modelsMethodsUrls = {
    NewStudent: {
        method: "POST",
        url: baseUrl + "/students",
    },
    Student: {
        method: "PUT",
        url: baseUrl + "/students/" + idFromHash,
    }
};

/**
 * Populate a form with the content of a JSON object.
 *
 * @param {object} frm jQuery object wrapping a form
 * @param {object} data plain JSON object
 *
 * @see https://stackoverflow.com/questions/7298364/using-jquery-and-json-to-populate-forms
 */
function populateForm(frm, data) {
    $.each(data, function (key, value) {
        var $ctrl = $('[name=' + key + ']', frm);
        if ($ctrl.is('select')) {
            $("option", $ctrl).each(function () {
                if (this.value == value) {
                    this.selected = true;
                }
            });
        } else {
            switch ($ctrl.attr("type")) {
                case "text" :
                case "number" :
                case "hidden":
                case "textarea":
                    $ctrl.val(value);
                    break;
                case "radio" :
                case "checkbox":
                    $ctrl.each(function () {
                        if ($(this).attr('value') == value) {
                            $(this).attr("checked", value);
                        }
                    });
                    break;
            }
        }
    });


}

(async () => {

    // Fetch all JSON schemas.
    var allJsonSchemas = await lib.SchemExtractor.fromFile(baseUrl + "/openapi.yaml");

    // Filter relevant JSON schemas.
    var jsonSchemas = {};
    Object.keys(allJsonSchemas)
        .filter(lib.isRelevantJsonSchema)
        .forEach(key => {
            jsonSchemas[key] = lib.adaptJsonSchemaToDraft03(allJsonSchemas[key]);
            jsonSchemas[key] = lib.removeJsonSchemaProperty(jsonSchemas[key], "id");
            jsonSchemas[key] = lib.removeJsonSchemaProperty(jsonSchemas[key], "audits");
            jsonSchemas[key] = lib.removeJsonSchemaProperty(jsonSchemas[key], "student");
            jsonSchemas[key] = lib.removeJsonSchemaProperty(jsonSchemas[key], "internship");
        });

    // List relevant JSON schemas within select box.
    Object.keys(jsonSchemas).forEach(key => modelSelector.append(new Option(key, key)));

    modelSelector.change(() => {

        result.html("")
            .removeClass("alert-danger")
            .removeClass("alert-success");

        _form.html("")
            .removeClass("jsonform-hasrequired");

        if (!modelSelector.val()) {
            return;
        }

        var model = modelSelector.val();

        _form.jsonForm({
            schema: jsonSchemas[model],
            /* If onSubmit() is provided, onSubmitValid() is not executed
             * onSubmit: (errors, values) => {},
             */
            onSubmitValid: function (values) {
                $.ajax({
                    method: modelsMethodsUrls[model].method,
                    url: modelsMethodsUrls[model].url,
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

    modelSelector.val(modelFromHash).change();
    // If it's not a resource creations, resource data is retrieved.
    if (modelFromHash.substr(0, 3) !== "New") {
        $.ajax({
            method: "GET",
            url: modelsMethodsUrls[modelFromHash].url,
            contentType: "application/json",
            processData: false
        })
            .done(response => {
                populateForm(_form, response.data);
            })
            .fail(response => {
                alert("ERROR");
            });
    }

})();
