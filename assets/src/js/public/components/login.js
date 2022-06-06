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
    });
})(jQuery);