$(document).ready(function () {

    var studentId = window.location.hash.substr(1);

    // Hash of student details link.
    var h = $(".row a.student").attr("href");
    $(".row a.student").attr("href", h + "#Student," + studentId);

    // Hashes of student attribute links.
    $(".row a.student-attribute").each(function () {
        $(this).attr("href", $(this).attr("href") + "," + studentId);
    });

    var studentSettings = Object.assign(
        getBaseDataTableSettings("#student", "../students/" + studentId),
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
    studentSettings.ajax.dataSrc = function (json) {
        return Object.entries(json.data)
            .filter(function (entry) {
                // ID is not displayed
                return entry[0] !== "id"
            });
    };
    var annotationsSettings = Object.assign(
        getBaseDataTableSettings("#annotations", "../students/" + studentId + "/annotations?with_audits=true"),
        {
            columns: [
                {title: "ID", data: "id", visible: false},
                {title: "Date/time", data: "audits.0.created_at"},
                {title: "Title", data: "title"},
                {title: "Content", data: "content"},
                {
                    render: function (data, type, row) {
                        return '<a href="form.html#Annotation,' + row.id + '">edit</a>';
                    }
                }
            ]
        }
    );
    var internshipsSettings = Object.assign(
        getBaseDataTableSettings("#internships", "../students/" + studentId + "/internships"),
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
                        return '<a href="form.html#Internship,' + row.id + '">edit</a>';
                    }
                },
                {
                    title: "Evaluation",
                    render: function (data, type, row) {
                        if (row.evaluation) {
                            return '<a href="form.html#Evaluation,' + row.evaluation.id + '">edit</a>';
                        } else {
                            return '<a href="form.html#NewEvaluation,' + row.id + '">add</a>';
                        }
                    }
                },
                {
                    title: "Interruption Report",
                    render: function (data, type, row) {
                        if (row.is_interrupted !== true) {
                            return '';
                        }
                        if (row.interruption_report) {
                            return '<a href="form.html#InterruptionReport,' + row.interruption_report.id + '">edit</a>';
                        } else {
                            return '<a href="form.html#NewInterruptionReport,' + row.id + '">add</a>';
                        }
                    }
                }
            ]
        }
    );
    var eligibilitiesSettings = Object.assign(
        getBaseDataTableSettings("#eligibilities", "../students/" + studentId + "/eligibilities"),
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
                        return '<a href="form.html#Eligibility,' + row.id + '">edit</a>';
                    }
                }
            ]
        }
    );
    var oshCourseAttendancesSettings = Object.assign(
        getBaseDataTableSettings("#osh_course_attendances", "../students/" + studentId + "/osh_course_attendances"),
        {
            columns: [
                {title: "ID", data: "id", visible: false},
                {title: "Start", data: "start_date"},
                {title: "End", data: "end_date"},
                {
                    render: function (data, type, row) {
                        return '<a href="form.html#OshCourseAttendance,' + row.id + '">edit</a>';
                    }
                }
            ]
        }
    );
    var educationalActivityAttendancesSettings = Object.assign(
        getBaseDataTableSettings("#educational_activity_attendances", "../students/" + studentId + "/educational_activity_attendances"),
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
                        return '<a href="form.html#EducationalActivityAttendance,' + row.id + '">edit</a>';
                    }
                }
            ]
        }
    );

    $("#student").DataTable(studentSettings);
    $("#annotations").DataTable(annotationsSettings);
    $("#internships").DataTable(internshipsSettings);
    $("#eligibilities").DataTable(eligibilitiesSettings);
    $("#osh_course_attendances").DataTable(oshCourseAttendancesSettings);
    $("#educational_activity_attendances").DataTable(educationalActivityAttendancesSettings);

});
