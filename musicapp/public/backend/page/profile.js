$(document).ready(function() {
    // if($('.phone').length){
    //     $(".phone").inputmask("(999) 999-9999", {"placeholder": ""});
    // }

    $('body').on('keyup','#profile_form input',function(event) {            
        $(this).closest('.mb-3').find('.invalid-feedback strong').html('');        
    });

    $('#profile_form').submit(function(event) {         
        event.preventDefault();
        var $this = $(this);
        var dataString = new FormData($('#profile_form')[0]);
        $('.invalid-feedback strong').html('');

        $.ajax({
            url: updateProfileUrl,
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
                    showMessage("success", result.message);
                    getData();
                }else if(!result.status && result.message){
                    showMessage("error", result.message);
                } else {
                    first_input = "";
                    $('.error').html("");
                    $.each(result.error, function(key) {
                        if(first_input=="") first_input=key;
                        $('#profile_form .error-'+key).find('strong').html(result.error[key]);
                    });
                    $('#profile_form').find("."+first_input).focus();
                }
            },
            error: function(error) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                showMessage("error","Something went wrong!");
            }
        });            
    });

    getData();

    function getData(){
        $.ajax({
            url: profileDetailUrl,
            type: 'GET',
            dataType: 'json',
            success: function(result) {
                if (result.status)
                {
                    $('#profile_form').find('#first_name').val(result.data.first_name);
                    $('#profile_form').find('#last_name').val(result.data.last_name);
                    $('#profile_form').find('#email').val(result.data.email);
                    $('#profile_form').find('#phone').val(result.data.phone);
                }
            },
            error: function(error) {
                $($this).find('button[type="submit"]').prop('disabled', false);                    
                showMessage("error","Something went wrong!");
            }
        });
    } 
});