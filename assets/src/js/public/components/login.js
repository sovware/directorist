;
(function ($) {
    window.addEventListener('DOMContentLoaded', () => {
        // Perform AJAX login on form submit
        $('form#login').on('submit', function (e) {
            e.preventDefault();
            $('p.status').show().html(directorist.loading_message);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: directorist.ajax_url,
                data: {
                    'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                    'username': $('form#login #username').val(),
                    'password': $('form#login #password').val(),
                    'rememberme': ($('form#login #keep_signed_in').is(':checked')) ? 1 : 0,
                    'security': $('#security').val()
                },
                success: function (data) {
                    if ('nonce_faild' in data && data.nonce_faild) {
                        $('p.status').html('<span class="status-success">' + data.message + '</span>');
                    }
                    if (data.loggedin == true) {
                        $('p.status').html('<span class="status-success">' + data.message + '</span>');
                        document.location.href = directorist.redirect_url;
                    } else {
                        $('p.status').html('<span class="status-failed">' + data.message + '</span>');
                    }
                },
                error: function (data) {
                    if ('nonce_faild' in data && data.nonce_faild) {
                        $('p.status').html('<span class="status-success">' + data.message + '</span>');
                    }
                    $('p.status').show().html('<span class="status-failed">' + directorist.login_error_message + '</span>');
                }
            });
            e.preventDefault();
        });

        // Alert users to login (only if applicable)
        $('.atbdp-require-login, .directorist-action-report-not-loggedin').on('click', function (e) {
            e.preventDefault();
            alert(directorist.login_alert_message);
            return false;
        });

        //password recovery
        let on_processing = false;

        $('body').on('submit', '#directorist-password-recovery', function (e) {
            e.preventDefault();

            if (on_processing) {
                $('.directorist_password_recovery_btn').attr('disabled', true);
                return;
            }

            $(' .directorist_password_recovery_bnt ').append('<span class="directorist_form_submited">' + directorist.loading_message + '</span>');

            const $form = $(e.target);

            let form_data = new FormData();
            const fieldValuePairs = $form.serializeArray();

            form_data.append('action', 'directorist_password_recovery');
            form_data.append('directorist_nonce', directorist.directorist_nonce);

            for ( const field of fieldValuePairs ) {

                if ( '' === field.value ) {
                    continue;
                }
    
                form_data.append( field.name, field.value );
            }
            
            on_processing = true;

            $.ajax({
                method: 'POST',
                processData: false,
                contentType: false,
                url: directorist.ajax_url,
                data: form_data,
                success: function ( response ) {

                    $(' .directorist_form_submited ').remove();

                    if( response['error_msg'] ) {
                        on_processing = false;
                        $(' .directorist_password_recovery_bnt ').append('<span class="status-failed">' + response['error_msg'] + '</span>');
                    }

                    if( response['success_msg'] ) {
                        $(' .directorist_password_recovery_bnt ').append('<span class="status-failed">' + response['success_msg'] + '</span>');
                    }
                    
                },
                error: function (response) {

                    $(' .directorist_form_submited ').remove();

                    $(' .directorist_password_recovery_bnt ').append('<span class="status-failed">' + directorist.login_error_message + '</span>');
                }
            });
        });
    });
})(jQuery);