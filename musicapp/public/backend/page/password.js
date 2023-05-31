$(document).ready(function() {
    $('#password_form').submit(function(event) {         
        event.preventDefault();
        var $this = $(this);
        var dataString = new FormData($('#password_form')[0]);
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
                    $this[0].reset();
                }else if(!result.status && result.message){
                    showMessage("error", result.message);
                } else {
                    first_input = "";
                    $('.error').html("");
                    $.each(result.error, function(key) {
                        if(first_input=="") first_input=key;
                        $('#password_form .error-'+key).find('strong').html(result.error[key]);
                    });
                    $('#password_form').find("."+first_input).focus();
                }
            },
            error: function(error) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                showMessage("error","Something went wrong!");
            }
        });            
    });
});