$(document).ready(function() {
    $(".phone").inputmask("(999) 999-9999", {"placeholder": ""});
    $('body').on('change','.role_type',function(event) {
        showDiv();
    });
    showDiv();
    function showDiv(){
        var role_type = $('input[name=role_type]:checked').val();
        if(role_type == "2"){
            $('.dealer_field').show();
        }else{
            $('.dealer_field').hide();
        }
    }
    if($('#shop_start_time').length){
        /* https://timepicker.co/ */
        $('#shop_start_time').timepicker({
            timeFormat: 'HH:mm',        
            maxHour: 24,
            maxMinutes: 30,        
            interval: 30,            
        });
        $('#shop_end_time').timepicker({
            timeFormat: 'HH:mm',        
            maxHour: 24,
            maxMinutes: 30,        
            interval: 30,
        });
        $(".shop_start_time").inputmask("99:99", {"placeholder": ""});        
        $(".shop_end_time").inputmask("99:99", {"placeholder": ""});        
    }
    $('body').on("change", "#document_file", function(event){
        event.preventDefault();
        
        $(".document_file_err").html("");            
        var file = event.target.files[0]; 
        var allowedFiles = ["pdf",'PDF'];
        var fileUpload = document.getElementById("document_file");        
        if(fileUpload){
            var fileName = file.name;
            var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);                
            if ($.inArray(fileNameExt, allowedFiles) == -1){
                fileUpload.value = ''; 
                var msg = "Please upload a valid pdf file";
                $(".document_file_err").html(msg);               
                return false;
            }
        }
        return true;
    });
     var fileSizeLimit = 2 * 1000 * 1000;
    var allowedFiles = ["jpg", "jpeg", "png"];
    $('body').on("change", "#company_logo", function(event){
        event.preventDefault();        
        $(".company_logo_err").html("");            
        var file = event.target.files[0];         
        var fileUpload = document.getElementById("company_logo");        
        if(fileUpload){
            var file_size = event.target.files[0].size;
            if(file_size<fileSizeLimit){
                var fileName = file.name;
                var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);                
                if ($.inArray(fileNameExt, allowedFiles) == -1){
                    fileUpload.value = ''; 
                    var msg = "Please upload valid: 'jpg,jpeg,png' only.";
                    $(".company_logo_err").html(msg);               
                    return false;
                }
            }else{
                $('.company_logo_err').text("File size cannot exceed 2 MB");
                $('#company_logo').val('');
                return false;
            }     
        }
        return true;
    });
});