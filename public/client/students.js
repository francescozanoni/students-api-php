$(document).ready(function () {

    var table = $("#students").DataTable({
        ajax: {
            url: "../students",
            method: "GET",
            cache: true,
                // https://stackoverflow.com/questions/32814120/jquery-datatables-show-no-results-message-on-404-ajax-response
                error: function (jqXHR, textStatus, errorThrown) {
                    // In case of no data found, no error must be reported.
                    if (jqXHR.responseText === '{"status_code":404,"status":"Not Found","message":"Resource(s) not found"}') {
                        $(tableSelector).DataTable().clear().draw();
                    } else {
                        throw errorThrown;
                    }
                }
        },
        columns: [
            {title: "ID", data: "id", visible: false, searchable: false},
            {title: "First name", data: "first_name"},
            {title: "Last name", data: "last_name"},
            {title: "Nationality", data: "nationality"},
            {title: "E-mail", data: "e_mail"},
            {
                title: "Phone number",
                data: "phone",
                // "phone" is optional
                render: function (data) {
                    return data || "";
                }
            }
        ],
        createdRow: function (row, data) {
            $(row).prop("title", "Click to access student's details");
            $(row).on("click", function () {
                window.location = "student.html#" + data.id;
            });
        }
    });

});
