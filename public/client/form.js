var modelSelector = $("#model-selector");
var pageForm = $("form");
var result = $("#result");

var baseUrl = "..";
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
    },

    NewAnnotation: {
        method: "POST",
        url: baseUrl + "/annotations",
    },
    Annotation: {
        method: "PUT",
        url: baseUrl + "/annotations/" + idFromHash,
    },

    NewInternship: {
        method: "POST",
        url: baseUrl + "/internships",
    },
    Internship: {
        method: "PUT",
        url: baseUrl + "/internships/" + idFromHash,
    },

    NewEligibility: {
        method: "POST",
        url: baseUrl + "/eligibilities",
    },
    Eligibility: {
        method: "PUT",
        url: baseUrl + "/eligibilities/" + idFromHash,
    },

    NewOshCourseAttendance: {
        method: "POST",
        url: baseUrl + "/osh_course_attendances",
    },
    OshCourseAttendance: {
        method: "PUT",
        url: baseUrl + "/osh_course_attendances/" + idFromHash,
    },

    NewEducationalActivityAttendance: {
        method: "POST",
        url: baseUrl + "/educational_activity_attendances",
    },
    EducationalActivityAttendance: {
        method: "PUT",
        url: baseUrl + "/educational_activity_attendances/" + idFromHash,
    }
};

/**
 * Populate a form with the content of a JSON object.
 *
 * @param {object} form jQuery object wrapping a form
 * @param {object} data plain JSON object
 *
 * @see https://stackoverflow.com/questions/7298364/using-jquery-and-json-to-populate-forms
 */
function populateForm(form, data) {

    $.each(data, function (key, value) {
        var $ctrl = $("[name=" + key + "]", form);
        if ($ctrl.is("select")) {
            $("option", $ctrl).each(function () {
                if (this.value === value) {
                    this.selected = true;
                }
            });
        } else if ($ctrl.is("textarea")) {
            $ctrl.val(value);
        } else {
            switch ($ctrl.prop("type")) {
                case "text" :
                case "number" :
                case "hidden":
                case "date":
                    $ctrl.val(value);
                    break;
                case "radio" :
                    $ctrl.each(function () {
                        if ($(this).prop("value") === value) {
                            $(this).prop("checked", true);
                        }
                    });
                    break;
                case "checkbox":
                    if (true === value) {
                        $ctrl.prop("checked", true);
                    }
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

        pageForm.html("")
            .removeClass("jsonform-hasrequired");

        if (!modelSelector.val()) {
            return;
        }

        var model = modelSelector.val();

        pageForm.jsonForm({
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
            },
            form: [
                ...(function () {
                    return Object.keys(jsonSchemas[model].properties);
                }()
                    .map(function (property) {
                        if (property === "notes" ||
                            property === "content") {
                            property = {
                                key: property,
                                type: "textarea",
                                fieldHtmlClass: "form-control" // Bootstrap CSS class
                            };
                        }
                        /*
                        if (property === "start_date" ||
                            property === "end_date") {
                            property = {
                                key: property,
                                type: "date"
                            };
                        }
                        */
                        return property;
                    })),
                {
                    type: "actions",
                    items: [
                        {
                            type: "submit",
                            title: "Submit"
                        },
                        {
                            type: "button",
                            title: "Go back",
                            onClick: function (evt) {
                                evt.preventDefault();
                                window.history.go(-1);
                            }
                        }
                    ]
                }
            ]
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
                populateForm(pageForm, response.data);
            })
            .fail(response => {
                alert("ERROR");
            });
    }

})();
