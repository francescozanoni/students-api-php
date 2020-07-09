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
        ]
    });

    $("#students tbody").on("click", "tr", function () {
        /**
         * @var {object} student e.g. {
         *                              "id": 1,
         *                              "first_name": "John",
         *                              "last_name": "Doe",
         *                              "e_mail": "john.doe@foo.com",
         *                              "phone": "1234-567890",
         *                              "nationality": "GB"
         *                            }
         */
        var student = table.row(this).data();
        window.location = "/client/student.html#" + student.id;
    });

});
