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
        var field = $("[name=" + key + "]", form);
        if (field.is("select")) {
            $("option", field).each(function () {
                this.selected = (this.value === value);
            });
        } else {
            switch (field.prop("type")) {
                case "radio" :
                    field.each(function () {
                        $(this).prop("checked", $(this).prop("value") === value);
                    });
                    break;
                case "checkbox":
                    field.prop("checked", [true, 1, "1", "Y", "y", "Yes", "yes", "true", "TRUE"].indexOf(value) !== -1);
                    break;
                default:
                    field.val(value);
            }
        }
    });

}

/**
 * @param {string} tableSelector CSS selector of table DOM element
 * @param {string} url URL to be invoked via AJAX to retrieve data
 *
 * @returns {object} e.g. {
 *                          ajax: {
 *                            url: "../students/1",
 *                            method: "GET",
 *                            cache: true,
 *                            error: function (jqXHR, textStatus, errorThrown) {
 *                                [...]
 *                            }
 *                          },
 *                          paging: false,
 *                          searching: false,
 *                          ordering: false,
 *                          info: false
 *                        }
 */
function getBaseDataTableSettings(tableSelector, url) {
    return {
        ajax: {
            url: url,
            method: "GET",
            cache: true,
            // https://stackoverflow.com/questions/32814120/jquery-datatables-show-no-results-message-on-404-ajax-response
            error: function (jqXHR, textStatus, errorThrown) {
                // In case of no data found, no error must be reported.
                if (jqXHR.responseText === '{"status_code":404,"status":"Not Found","message":"Resource(s) not found"}') {
                    $(tableSelector).DataTable().clear().draw();
                } else {
                    throw errorThrown
                }
            }
        },
        paging: false,
        searching: false,
        ordering: false,
        info: false
    };
}

/**
 * @param {string} field
 *
 * @returns {string}
 */
function getFieldLabel(field) {
    var fieldsAndLabels = {
        "content": "Content",
        "credits": "Credits",
        "audits.0.created_at": "Date/time",
        "educational_activity": "Educational activity",
        "end_date": "End",
        "hour_amount": "Hour amount",
        "id": "ID",
        "is_eligible": "Is eligible",
        "is_interrupted": "Is interrupted",
        "is_optional": "Is optional",
        "location": "Location",
        "sub_location": "Ward",
        "other_amount": "Other amount",
        "start_date": "Start",
        "title": "Title",
        "notes": "Notes",
        "first_name": "First name",
        "last_name": "Last name",
        "e_mail": "E-mail",
        "nationality": "Nationality",
        "phone": "Phone number",
    }
    return fieldsAndLabels[field] || field;
}

/**
 * @param {string} field
 *
 * @returns {string}
 */
function getFieldHelpText(field) {
    var fieldsAndHelpTexts = {
        "end_date": "YYYY-MM-DD format",
        "start_date": "YYYY-MM-DD format",
        "nationality": "2-letter ISO format",
    }
    return fieldsAndHelpTexts[field] || "";
}

window.onerror = function (msg, url, lineNo, columnNo, error) {
    alert(msg + " (" + url + ":" + lineNo + ")");
    return false;
}
