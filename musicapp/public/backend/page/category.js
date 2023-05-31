$(document).ready(function() {
	var listTable = $('#listTable').DataTable({
        order: [[1, 'desc']],
        searching: true,
        pageLength: 10,
        processing: true,
        serverSide: true,
        ajax: {
            url: apiUrl,
            type: 'GET',
            headers: {
                'X-XSRF-TOKEN': $('meta[name=csrf-token]').attr('content'),
            },
        },
        columns: [
            { data: 'category_name' },
            { data: 'created_at', name: 'created_at' },
            {
                data: null,
                name: 'status',
                render: function (data, type, full, meta) {
                    let html = '<input class="status_switch" type="checkbox" ' + ((data.status) ? 'checked' : '') + ' data-on-color="success" data-id="' + data.id + '" data-size="small">';
                    return html;
                },
                orderable: false,
                searchable: false
            },
            {
                sortable: false,
                render: function(_,_, full) {
                    var contactId = full['id'];

                    if (contactId) {
                        var actions = '';
                        actions += ' <a href="javascript:void(0)" data-id="'+ contactId +'" class="btn-sm btn-primary edit-row">Edit</a>';
                        actions += ' <a href="javascript:void(0)" data-id="'+ contactId +'" class="btn-sm btn-danger delete-row">Delete</a>';
                        
                        return actions;
                    }
                    return '';
                },
            },
        ],
        "drawCallback": function( settings ) {
            $(".status_switch").bootstrapSwitch({
                // onSwitchChange: function(e, state) {
                //     changeStatus(state, e.currentTarget.dataset.id);
                // }
            });
        }
    });

    $(document).on('switchChange.bootstrapSwitch', '.status_switch', function (e) {
        e.preventDefault();
        if ($(this).prop("checked") == true) {
            var status = 1;
        } else {
            var status = 0;
        }

        $.ajax({
            url: changeStatusUrl,
            method: 'POST',
            data: {
                id: $(this).data('id'),
                status: status,
            },
            dataType: 'json',
            success: function (result) {
                if (result.status) {
                    showMessage("success", result.message);
                } else {
                    $('#listTable').DataTable().ajax.reload();
                    showMessage("error", result.message);
                }
            },
            error: function (error) {
                
            }
        })
    });

    $('.add-new').click(function(event) {
        $('#edit-id').val("");
        $('.modal-lable-class').html('Add');
        $('.invalid-feedback strong').html("");

        $('#add-form')[0].reset();        
        $('#add-modal').modal('show');
    });

    $('#add-form').submit(function(event) {
        event.preventDefault();
        var $this = $(this);
        var dataString = new FormData($('#add-form')[0]);
        $('.invalid-feedback strong').html('');

        $.ajax({
            url: addUrl,
            type: 'POST',
            data: dataString,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $($this).find('button[type="submit"]').prop('disabled', true);
            },
            success: function(result) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                if (result.status) {
                    $this[0].reset();
                    $('#edit-id').val(0);
                    showMessage("success", result.message);

                    $('#listTable').DataTable().ajax.reload();
                    
                    setTimeout(function() {
                        $('#add-modal').modal('hide');
                    }, 1000);
                } else if (!result.status && result.message){
                    showMessage("error", result.message);
                } else {
                    first_input = "";
                    $('.error').html("");
                    $.each(result.error, function(key) {
                        if(first_input=="") first_input=key;
                        $('#add-form .error-'+key).find('strong').html(result.error[key]);                        
                    });
                    $('#add-form').find("."+first_input).focus();
                }
            },
            error: function(error) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                alert('Something went wrong!', 'error');
                // location.reload();
            }
        });
    });

    $('body').on('click','.edit-row',function(event) {
        var id = $(this).attr('data-id');
        $('.invalid-feedback strong').html('');
        $('#add-form')[0].reset();

        $.ajax({
            url: detailUrl+'?id='+id,
            type: 'GET',
            dataType: 'json',
            success: function(result) {
                if (result.status) {
                    $('#edit-id').val(id);
                    
                    $('.modal-lable-class').html('Edit');
                    $('#add-modal').modal('show');
                    
                    $('#add-form').find('#category_name').val(result.data.category_name);
                }
            }
        });    
    });

    $('body').on('click','.delete-row',function(event) {
        var id = $(this).attr('data-id');

        if (confirm('Are you sure you want to delete ?')) {
            $.ajax({
                url: deleteUrl+'?id='+id,
                type: 'POST',
                dataType: 'json',
                success: function(result) {
                    if (result.status) {
                        showMessage("success", result.message);
                    } else {
                        showMessage("error", result.message);
                    }
                    $('#listTable').DataTable().ajax.reload();
                }
            });    
        }
    });
});