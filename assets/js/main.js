
(function ($) {
    "use strict";

    
    /*==================================================================
    [ Validate ]*/
    var input = $('.validate-input .input100');

    $('.validate-form').on('submit',function(){
        var check = true;

        for(var i=0; i<input.length; i++) {
            if(validate(input[i]) == false){
                showValidate(input[i]);
                check=false;
            }
            
            if($(input[i]).hasClass("alert-error")){
                check=false;
            }
        }

        return check;
    });

    $('.validate-form .input100').each(function(){
        $(this).focus(function(){
           hideValidate(this);
        });
    });
    
    $('input[name="confirm_pass"]').focusout(function() {
        let password = $('input[name="pass"]').val();
        let confirmPassword = $('input[name="confirm_pass"]').val();

        if(password.length > 0 && confirmPassword.length > 0) {
            let input = $('input[name="confirm_pass"]');
            if(password != confirmPassword) {
                showError(input);
            }
            else {
                hideError(input);
            }
        }
    });

    function checkError(inout) {
        
    }

    function validate (input) {
        if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
            if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                return false;
            }
        }
        else {
            if($(input).val().trim() == ''){
                return false;
            }
        }
    }

    function showValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).addClass('alert-validate');
    }

    function hideValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).removeClass('alert-validate');
    }

    function showError(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).addClass('alert-error');
    }

    function hideError(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).removeClass('alert-error');
    }
    
})(jQuery);