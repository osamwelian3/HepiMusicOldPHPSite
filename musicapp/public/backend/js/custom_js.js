function showMessage(e = "success", a = "") {
    toastr.options = {
        closeButton: !0,
        debug: !1,
        newestOnTop: !1,
        progressBar: !1,
        positionClass: "toast-top-right",
        preventDuplicates: !1,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: "4000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut"
    }, "success" != e && "Success" != e || toastr.success(a), "warning" != e && "Warning" != e || toastr.warning(a), "info" != e && "Info" != e || toastr.info(a), "error" != e && "Error" != e || toastr.error(a)
}
function pagereload(){
    var interval=setInterval(function() {
        location.reload(true);
        clearInterval(interval);
    }, 1500);
}
$(document).ready(function(){	
    var intervalAlert=setInterval(function() {
	    $('.alert').hide();
	    clearInterval(intervalAlert);
	}, 6000);
    $("input").attr("autocomplete", "off");       
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': _token
        }
    });  
});