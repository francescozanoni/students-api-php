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
                        return getFieldLabel(data);
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
                {title: getFieldLabel("id"), data: "id", visible: false},
                {title: getFieldLabel("audits.0.created_at"), data: "audits.0.created_at"},
                {title: getFieldLabel("title"), data: "title"},
                {title: getFieldLabel("content"), data: "content"},
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
                {title: getFieldLabel("id"), data: "id", visible: false},
                {title: getFieldLabel("start_date"), data: "start_date"},
                {title: getFieldLabel("end_date"), data: "end_date"},
                {title: getFieldLabel("location"), data: "location"},
                {
                    title: getFieldLabel("sub_location"),
                    data: "sub_location",
                    // "sub_location" is optional
                    render: function (data) {
                        return data || "";
                    }
                },
                {title: getFieldLabel("hour_amount"), data: "hour_amount"},
                {title: getFieldLabel("other_amount"), data: "other_amount"},
                {title: getFieldLabel("is_optional"), data: "is_optional"},
                {title: getFieldLabel("is_interrupted"), data: "is_interrupted"},
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
                {title: getFieldLabel("id"), data: "id", visible: false},
                {title: getFieldLabel("start_date"), data: "start_date"},
                {title: getFieldLabel("end_date"), data: "end_date"},
                {title: getFieldLabel("is_eligible"), data: "is_eligible"},
                {
                    title: getFieldLabel("notes"),
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
                {title: getFieldLabel("id"), data: "id", visible: false},
                {title: getFieldLabel("start_date"), data: "start_date"},
                {title: getFieldLabel("end_date"), data: "end_date"},
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
                {title: getFieldLabel("id"), data: "id", visible: false},
                {title: getFieldLabel("start_date"), data: "start_date"},
                {
                    title: getFieldLabel("end_date"),
                    data: "end_date",
                    // "end_date" is optional
                    render: function (data) {
                        return data || "";
                    }
                },
                {title: getFieldLabel("educational_activity"), data: "educational_activity"},
                {title: getFieldLabel("credits"), data: "credits"},
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
