
baseUrl = window.location.origin;
$(document).ready(function () {
    table = $('#dataTable').DataTable({
        // processing: true,
        serverSide: true,
        responsive: true,
        ajax: baseUrl + '/apps',
        columns: [
            { data: 'check_box', name:'check_box', orderable: false },
            { data: 'sr_no', name: 'sr_no',orderable: false },
            { data: 'img', name: 'img',orderable: false },
            { data: 'app_name', name: 'app_name' },
            { data: 'account_name', name: 'account_name' },
            { data: 'status', name: 'status', orderable: false, searchable: true },
            { data: 'action', name: 'action',orderable: false}
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data, type, full, meta) {
                    return data;
                },
            },
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

function app_trash(app_id) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary file!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plx!",
        closeOnConfirm: false,
        closeOnCancel: false
    },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: baseUrl + '/app-trash/' + app_id,
                    success: function (res) {
                        swal("Deleted!", res.success, "success");
                        table.ajax.reload();
                    },
                    error: function () { },
                })
            } else {
                swal("Cancelled", "Your imaginary file is safe :)", "error");
            }
        });

}

function publish_app(app_id) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary file!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, Publish it!",
        cancelButtonText: "No, cancel plx!",
        closeOnConfirm: false,
        closeOnCancel: false
    },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: baseUrl + '/publish-app/' + app_id,
                    success: function (res) {
                        swal("Published!", res.success, "success");
                        // $('#trash_msg').text(res.success).removeClass('d-none');
                        table.ajax.reload();
                    },
                    error: function () { },
                })
            } else {
                swal("Cancelled", "Your imaginary file is safe :)", "error");
            }
        });
}
setInterval(() => {
    table.ajax.reload();
}, 900000);
function sync() {
    $('#sync_form').submit();
}
$(document).on('change', '.check-box', function() {
    if ($('.check-box:checked').length > 0) {
      $('#myButton').prop('disabled', false);
    } else {
      $('#myButton').prop('disabled', true);
    }
  });
$('#all_check').on('change', function() {
    if ($(this).prop('checked')) {
        $('.check-box').prop('checked', true);
        $('#myButton').prop('disabled', false);
    } else {
        $('.check-box').prop('checked', false);
        $('#myButton').prop('disabled', true);
    }
  });
