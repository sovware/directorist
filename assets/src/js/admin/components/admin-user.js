// user type change on user dashboard
(function ($) {
    window.addEventListener('load', () => {
        $('#atbdp-user-type-approve').on('click', function (event) {
            event.preventDefault();
            var userId = $(this).attr('data-userId');
            var nonce = $(this).attr('data-nonce');
            $.ajax({
                type: 'post',
                url: directorist_admin.ajaxurl,
                data: {
                    action: 'atbdp_user_type_approved',
                    _nonce: nonce,
                    userId: userId
                },
                success: function success(response) {
                    if (response.user_type) {
                        $('#user-type-' + userId).html(response.user_type);
                    }
                },
                error: function error(response) { // $('#atbdp-remote-response').val(response.data.error);
                }
            });
            return false;
        });

        $('#atbdp-user-type-deny').on('click', function (event) {
            event.preventDefault();
            var userId = $(this).attr('data-userId');
            var nonce = $(this).attr('data-nonce');
            $.ajax({
                type: 'post',
                url: directorist_admin.ajaxurl,
                data: {
                    action: 'atbdp_user_type_deny',
                    _nonce: nonce,
                    userId: userId
                },
                success: function success(response) {
                    if (response.user_type) {
                        $('#user-type-' + userId).html(response.user_type);
                    }
                },
                error: function error(response) { // $('#atbdp-remote-response').val(response.data.error);
                }
            });
            return false;
        });
    });
})(jQuery);