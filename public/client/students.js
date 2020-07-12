$(document).ready(function () {

    var table = $("#students").DataTable({
        ajax: {
            url: "/students",
            method: "GET",
            cache: true
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
                window.location = "/client/student.html#" + data.id;
            });
        }
    });

});
