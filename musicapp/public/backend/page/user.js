$(document).ready(function() {
	var listTable = $('#listTable').DataTable({
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
            // data: function (d) {            
            //     d.search = $('input[type="search"]').val()
            // },
        },
        columns: [
            { data: 'first_name' },
            { data: 'last_name' },
            { data: 'email' },
            { data: 'phone' },
            // {
            //     sortable: false,
            //     render: function(_,_, full) {
            //         var contactId = full['id'];

            //         if(contactId) {
            //             var actions = '';
            //             actions += ' <a href="javascript:void(0)" data-id="'+ contactId +'" class="btn-sm btn-primary edit-row">Edit</a>';
            //             actions += ' <a href="javascript:void(0)" data-id="'+ contactId +'" class="btn-sm btn-danger delete-row">Delete</a>';
                        
            //             return actions;
            //         }
            //         return '';
            //     },
            // },
        ],
        "drawCallback": function( settings ) {}
    });

    var listTable = $('#referral-listTable').DataTable({
        searching: true,
        pageLength: 10,
        processing: true,
        serverSide: true,
        ajax: {
            url: referralUsersUrl,
            type: 'GET',
            headers: {
                'X-XSRF-TOKEN': $('meta[name=csrf-token]').attr('content'),
            },
        },
        columns: [
            { data: 'first_name' },
            { data: 'last_name' },
            { data: 'email' },
            { data: 'phone' },
        ],
        "drawCallback": function( settings ) {}
    });

    $('body').on('click','.delete-row',function(event) {
        var id = $(this).attr('data-id');
        if(confirm('Are you sure you want to delete ?')){
            $.ajax({
                url: deleteUrl+'?id='+id,
                type: 'POST',
                dataType: 'json',
                success: function(result) {
                    if(result.status) {
                        showMessage("success", result.message);
                    }else{
                        showMessage("error", result.message);
                    }
                    $('#listTable').DataTable().ajax.reload();
                }
            });    
        }
    });
});