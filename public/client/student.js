$(document).ready(function () {

    var studentId = window.location.hash.substr(1);

    var h = $(".row a").attr("href");
    $(".row a").attr("href", h + "#Student," + studentId);

    var baseDataTableSettings = function (tableSelector, url) {
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
    };

    var detailsSettings = Object.assign(
        baseDataTableSettings("#details", "/students/" + studentId),
        {
            columns: [
                {
                    render: function (data) {
                        return (data.substr(0, 1).toUpperCase() + data.substr(1))
                            .replace(/[^a-zA-Z]/, " ")
                            .replace(/E m/, "E-m");
                    }
                },
                {}
            ]
        }
    );
    detailsSettings.ajax.dataSrc = function (json) {
        return Object.entries(json.data)
            .filter(function (entry) {
                // ID is not displayed
                return entry[0] !== "id"
            });
    };
    var annotationsSettings = Object.assign(
        baseDataTableSettings("#annotations", "/students/" + studentId + "/annotations?with_audits=true"),
        {
            columns: [
                {title: "ID", data: "id", visible: false},
                {title: "Date/time", data: "audits.0.created_at"},
                {title: "Title", data: "title"},
                {title: "Content", data: "content"},
                {
                    render: function (data, type, row) {
                        return '<a href="edit.html#Annotation,' + row.id + '">edit</a>';
                    }
                }
            ]
        }
    );
    var internshipsSettings = Object.assign(
        baseDataTableSettings("#internships", "/students/" + studentId + "/internships"),
        {
            columns: [
                {title: "ID", data: "id", visible: false},
                {title: "Start", data: "start_date"},
                {title: "End", data: "end_date"},
                {title: "Location", data: "location"},
                {
                    title: "Ward",
                    data: "sub_location",
                    // "sub_location" is optional
                    render: function (data) {
                        return data || "";
                    }
                },
                {title: "Hour amount", data: "hour_amount"},
                {title: "Other amount", data: "other_amount"},
                {title: "Is optional", data: "is_optional"},
                {title: "Is interrupted", data: "is_interrupted"},
                {
                    render: function (data, type, row) {
                        return '<a href="edit.html#Internship,' + row.id + '">edit</a>';
                    }
                }
            ]
        }
    );
    var eligibilitiesSettings = Object.assign(
        baseDataTableSettings("#eligibilities", "/students/" + studentId + "/eligibilities"),
        {
            columns: [
                {title: "ID", data: "id", visible: false},
                {title: "Start", data: "start_date"},
                {title: "End", data: "end_date"},
                {title: "Is eligible", data: "is_eligible"},
                {
                    title: "Notes",
                    data: "notes",
                    // "notes" is optional
                    render: function (data) {
                        return data || "";
                    }
                },
                {
                    render: function (data, type, row) {
                        return '<a href="edit.html#Eligibility,' + row.id + '">edit</a>';
                    }
                }
            ]
        }
    );
    var oshCourseAttendancesSettings = Object.assign(
        baseDataTableSettings("#osh_course_attendances", "/students/" + studentId + "/osh_course_attendances"),
        {
            columns: [
                {title: "ID", data: "id", visible: false},
                {title: "Start", data: "start_date"},
                {title: "End", data: "end_date"},
                {
                    render: function (data, type, row) {
                        return '<a href="edit.html#OshCourseAttendance,' + row.id + '">edit</a>';
                    }
                }
            ]
        }
    );
    var educationalActivityAttendancesSettings = Object.assign(
        baseDataTableSettings("#educational_activity_attendances", "/students/" + studentId + "/educational_activity_attendances"),
        {
            columns: [
                {title: "ID", data: "id", visible: false},
                {title: "Start", data: "start_date"},
                {
                    title: "End",
                    data: "end_date",
                    // "end_date" is optional
                    render: function (data) {
                        return data || "";
                    }
                },
                {title: "Educational activity", data: "educational_activity"},
                {title: "Credits", data: "credits"},
                {
                    render: function (data, type, row) {
                        return '<a href="edit.html#EducationalActivityAttendance,' + row.id + '">edit</a>';
                    }
                }
            ]
        }
    );

    $("#details").DataTable(detailsSettings);
    $("#annotations").DataTable(annotationsSettings);
    $("#internships").DataTable(internshipsSettings);
    $("#eligibilities").DataTable(eligibilitiesSettings);
    $("#osh_course_attendances").DataTable(oshCourseAttendancesSettings);
    $("#educational_activity_attendances").DataTable(educationalActivityAttendancesSettings);

});
