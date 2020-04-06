$(function() {
    var userId = $('#user_id').val();

    $("#editProfile").click(function(e) {
        let firstName = $('input[name="first-name"]').val();
        let lastName = $('input[name="last-name"]').val();
        let sex = $('input[name="sex"]:checked').val();
        let birthday = $('input[name="birthday"]').val();

        let loadingIcon = $(this).siblings('.main-loading').find('.spinner-border');
        let message = $(this).siblings('.main-loading').find('.message');
        loadingIcon.show();

        $.ajax({
            type: 'POST',
            url: 'includes/handlers/ajax_edit_profile.php',
            dataType: "json",
            data: {
                action: 'edit_profile',
                first_name: firstName,
                last_name: lastName,
                sex: sex,
                birthday: birthday,
                user_id: userId
            },
            success: function(response){
                loadingIcon.hide();
                if(response.success){							 							     
                    message.show().addClass('badge-success').html('Success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
                else {
                    message.show().addClass('badge-danger').html('Error');
                }
            },
            error: function() {
                message.show().addClass('badge-danger').html('Error');
            }
        });
    });

    $('input[name="password"], input[name="re-password"]').on('keyup',function() {
        let password = $('input[name="password"]').val();
        let rePassword = $('input[name="re-password"]').val();

        if(password && rePassword) {
            $('#btnSavePassword').attr('disabled', false);
        }
        else {
            $('#btnSavePassword').attr('disabled', true);
        }
    });

    $('#btnSavePassword').on('click', function(e) {
        let password = $('input[name="password"]').val();
        let rePassword = $('input[name="re-password"]').val();

        let loadingIcon = $(this).siblings('.loading').find('.spinner-border');
        let message = $(this).siblings('.loading').find('.message');
        loadingIcon.show();

        if(password === rePassword) {
            $.ajax({
                type: 'POST',
                url: 'includes/handlers/ajax_edit_profile.php',
                dataType: "json",
                data: {
                    action: 'change_password',
                    password: password,
                    user_id: userId
                },
                success: function(response){
                    loadingIcon.hide();
                    if(response.success){							 							     
                        message.show().addClass('badge-success').html('Success');
                        setTimeout(function() {
                            $('.close').trigger('click');
                        }, 1000);
                    }
                    else {
                        message.show().addClass('badge-danger').html('Error');
                    }
                },
                error: function() {
                    message.show().addClass('badge-danger').html('Error');
                }
            });
        }
        else {
            loadingIcon.hide();
            message.show().addClass('badge-danger').html('Password and re-password did not match');
        }
    });

    $('#btnCloseAccount').on('click', function(e) {
        let loadingIcon = $(this).siblings('.loading').find('.spinner-border');
        let message = $(this).siblings('.loading').find('.message');
        loadingIcon.show();

        $.ajax({
            type: 'POST',
            url: 'includes/handlers/ajax_edit_profile.php',
            dataType: "json",
            data: {
                action: 'close_account',
                user_id: userId
            },
            success: function(response){
                loadingIcon.hide();
                if(response.success){							 							     
                    message.show().addClass('badge-success').html('Success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
                else {
                    message.show().addClass('badge-danger').html('Error');
                }
            },
            error: function() {
                message.show().addClass('badge-danger').html('Error');
            }
        });
    });

    $('#btnOpenAccount').on('click', function(e) {
        let loadingIcon = $(this).siblings('.loading').find('.spinner-border');
        let message = $(this).siblings('.loading').find('.message');
        loadingIcon.show();

        $.ajax({
            type: 'POST',
            url: 'includes/handlers/ajax_edit_profile.php',
            dataType: "json",
            data: {
                action: 'open_account',
                user_id: userId
            },
            success: function(response){
                loadingIcon.hide();
                if(response.success){							 							     
                    message.show().addClass('badge-success').html('Success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
                else {
                    message.show().addClass('badge-danger').html('Error');
                }
            },
            error: function() {
                message.show().addClass('badge-danger').html('Error');
            }
        });
    });

});