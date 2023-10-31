baseUrl = window.location.origin;
$('#trash_msg').hide();
$(document).ready(function () {
    table = $('#dataTable').DataTable({
        // processing: true,
        serverSide: true,
        responsive: true,
        ajax: baseUrl + '/trash',
        columns: [
            { data: 'sr_no', name: 'sr_no' },
            { data: 'img', name: 'img' },
            { data: 'app_name', name: 'app_name' },
            { data: 'account_name', name: 'account_name' },
            { data: 'status', name: 'status', orderable: false, searchable: true },
            { data: 'action', name: 'action' }
        ],
        columnDefs: [
            {
                targets: 1,
                render: function (data, type, full, meta) {
                    return data;
                },
            },
            {
                targets: 2,
                render: function (data, type, full, meta) {
                    return data;
                },
            },
            {
                targets: 4,
                render: function (data, type, full, meta) {
                    return data;
                },
            },

        ]
    });
});
function status_change(status) {
    table.search(status).draw();
}

function delete_app(app_id) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary file!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, Delete it!",
        cancelButtonText: "No, cancel plx!",
        closeOnConfirm: false,
        closeOnCancel: false
    },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: baseUrl + '/app-delete/' + app_id,
                    success: function (res) {
                        swal("Deleted!", res.success, "success");
                        // $('#trash_msg').text(res.success).show();
                        table.ajax.reload();
                    },
                    error: function () { },
                })
            } else {
                swal("Cancelled", "Your imaginary file is safe :)", "error");
            }
        });
}
function restore_app(app_id) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary file!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, Restore it!",
        cancelButtonText: "No, cancel plx!",
        closeOnConfirm: false,
        closeOnCancel: false
    },
        function (isConfirm) {
            if (isConfirm) {
            $.ajax({
                url: baseUrl + '/restore/' + app_id,
                success: function (res) {
                    swal("Restored!", res.success, "success");
                    // $('#trash_msg').text(res.success).show();
                    table.ajax.reload();
                },
                error: function () { },
            })
        } else {
            swal("Cancelled", "Your imaginary file is safe :)", "error");
        }
    });
}
