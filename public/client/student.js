var editIconTag = '<span class="glyphicon glyphicon-edit" aria-hidden="true" title="Edit..."></span>';
var addIconTag = '<span class="glyphicon glyphicon-plus-sign" aria-hidden="true" title="Create new..."></span>';

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
                {render: getFieldLabel},
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
                {
                    title: getFieldLabel("audits.0.created_at"),
                    data: "audits.0.created_at",
                    render: function (data, type, row) {
                        return data.substr(0, 10);
                    }
                },
                {title: getFieldLabel("title"), data: "title"},
                {
                    title: getFieldLabel("content"),
                    data: "content",
                    render: renderMultiLineData
                },
                {
                    render: function (data, type, row) {
                        return '<a href="form.html#Annotation,' + row.id + '">' + editIconTag + '</a>';
                    }
                },
            ]
        }
    );
    var internshipsSettings = Object.assign(
        getBaseDataTableSettings("#internships", "../students/" + studentId + "/internships"),
        {
            columns: [
                {
                    render: function (data, type, row) {
                        return '<a href="form.html#Internship,' + row.id + '">' + editIconTag + '</a>';
                    }
                },
                {title: getFieldLabel("id"), data: "id", visible: false},
                {title: getFieldLabel("start_date"), data: "start_date"},
                {title: getFieldLabel("end_date"), data: "end_date"},
                {title: getFieldLabel("location"), data: "location"},
                {
                    title: getFieldLabel("sub_location"),
                    data: "sub_location",
                    render: renderOptionalData
                },
                {title: getFieldLabel("hour_amount"), data: "hour_amount"},
                {title: getFieldLabel("other_amount"), data: "other_amount"},
                {
                    title: getFieldLabel("is_optional"),
                    data: "is_optional",
                    render: renderBooleanData
                },
                {
                    title: getFieldLabel("is_interrupted"),
                    data: "is_interrupted",
                    render: renderBooleanData
                },
                {
                    render: function (data, type, row) {
                        var output = '<div class="text-right">evaluation ';
                        if (row.evaluation) {
                            output += '<a href="form.html#Evaluation,' + row.evaluation.id + '">' + editIconTag + '</a>';
                        } else {
                            output += '<a href="form.html#NewEvaluation,' + row.id + '">' + addIconTag + '</a>';
                        }
                        if (row.is_interrupted === true) {
                            output += '<br />interruption report ';
                            if (row.interruption_report) {
                                output += '<a href="form.html#InterruptionReport,' + row.interruption_report.id + '">' + editIconTag + '</a>';
                            } else {
                                output += '<a href="form.html#NewInterruptionReport,' + row.id + '">' + addIconTag + '</a>';
                            }
                        }
                        output += '</div>';
                        return output;
                    }
                },
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
                {
                    title: getFieldLabel("is_eligible"),
                    data: "is_eligible",
                    render: renderBooleanData
                },
                {
                    title: getFieldLabel("notes"),
                    data: "notes",
                    render: renderOptionalData
                },
                {
                    render: function (data, type, row) {
                        return '<a href="form.html#Eligibility,' + row.id + '">' + editIconTag + '</a>';
                    }
                },
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
                        return '<a href="form.html#OshCourseAttendance,' + row.id + '">' + editIconTag + '</a>';
                    }
                },
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
                    render: renderOptionalData
                },
                {title: getFieldLabel("educational_activity"), data: "educational_activity"},
                {title: getFieldLabel("credits"), data: "credits"},
                {
                    render: function (data, type, row) {
                        return '<a href="form.html#EducationalActivityAttendance,' + row.id + '">' + editIconTag + '</a>';
                    }
                },
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
