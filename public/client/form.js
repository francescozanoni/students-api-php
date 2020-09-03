var baseUrl = window.location.href.replace(/\/[^\/]+$/, "/..");
var modelFromHash = window.location.hash.substr(1).replace(/,.*$/, "");
var idFromHash = window.location.hash.substr(1).replace(modelFromHash, "").replace(",", "");

var modelSelector = $("#model-selector");
var pageForm = $("form");
var result = $("#result");

// @todo load all countries, locations and sublocations, to be used as enum values

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
        url: baseUrl + "/students/" + idFromHash + "/annotations",
    },
    Annotation: {
        method: "PUT",
        url: baseUrl + "/annotations/" + idFromHash,
    },

    NewInternship: {
        method: "POST",
        url: baseUrl + "/students/" + idFromHash + "/internships",
    },
    Internship: {
        method: "PUT",
        url: baseUrl + "/internships/" + idFromHash,
    },

    NewEligibility: {
        method: "POST",
        url: baseUrl + "/students/" + idFromHash + "/eligibilities",
    },
    Eligibility: {
        method: "PUT",
        url: baseUrl + "/eligibilities/" + idFromHash,
    },

    NewOshCourseAttendance: {
        method: "POST",
        url: baseUrl + "/students/" + idFromHash + "/osh_course_attendances",
    },
    OshCourseAttendance: {
        method: "PUT",
        url: baseUrl + "/osh_course_attendances/" + idFromHash,
    },

    NewEducationalActivityAttendance: {
        method: "POST",
        url: baseUrl + "/students/" + idFromHash + "/educational_activity_attendances",
    },
    EducationalActivityAttendance: {
        method: "PUT",
        url: baseUrl + "/educational_activity_attendances/" + idFromHash,
    },

    NewEvaluation: {
        method: "POST",
        url: baseUrl + "/internships/" + idFromHash + "/evaluation",
    },
    Evaluation: {
        method: "PUT",
        url: baseUrl + "/evaluations/" + idFromHash,
    },

    NewInterruptionReport: {
        method: "POST",
        url: baseUrl + "/internships/" + idFromHash + "/interruption_report",
    },
    InterruptionReport: {
        method: "PUT",
        url: baseUrl + "/interruption_reports/" + idFromHash,
    },
};

(async () => {

    // Fetch all JSON schemas.
    var allJsonSchemas = await lib.SchemExtractor.fromFile(baseUrl + "/openapi.yaml");

    // Filter relevant JSON schemas (this mainly removes schemas of related models).
    var jsonSchemas = {};
    Object.keys(allJsonSchemas)
        .filter(lib.isRelevantJsonSchema)
        .forEach(key => {
            jsonSchemas[key] = lib.adaptJsonSchemaToDraft03(allJsonSchemas[key]);
            jsonSchemas[key] = lib.removeJsonSchemaProperty(jsonSchemas[key], "id");
            jsonSchemas[key] = lib.removeJsonSchemaProperty(jsonSchemas[key], "audits");
            jsonSchemas[key] = lib.removeJsonSchemaProperty(jsonSchemas[key], "student");
            jsonSchemas[key] = lib.removeJsonSchemaProperty(jsonSchemas[key], "internship"),
            jsonSchemas[key] = lib.removeJsonSchemaProperty(jsonSchemas[key], "evaluation"),
            jsonSchemas[key] = lib.removeJsonSchemaProperty(jsonSchemas[key], "interruption_report");
        });

    // List relevant JSON schemas within select box.
    Object.keys(jsonSchemas)
        .forEach(key => modelSelector.append(new Option(key, key)));

    // "title" attribute is added to all properties, to be used as form field label.
    Object.keys(jsonSchemas)
        .forEach(key => Object.keys(jsonSchemas[key].properties).forEach(property => {
            jsonSchemas[key].properties[property].title = getFieldLabel(property);
            jsonSchemas[key].properties[property].description = getFieldHelpText(property);
        }));

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
                        if (property === "nationality" ||
                            property === "location" ||
                            property === "sub_location") {
                            // @todo switch to select box
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
