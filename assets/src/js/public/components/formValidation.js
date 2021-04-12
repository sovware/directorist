;(function ($) {

        $('#directorist-report-abuse-form').on('submit', function (e) {
            $('.directorist-report-abuse-modal button[type=submit]').addClass('directorist-btn-loading');
            // Check for errors
            if (!e.isDefaultPrevented()) {
                e.preventDefault();

                // Post via AJAX
                var data = {
                    'action': 'atbdp_public_report_abuse',
                    'post_id': $('#atbdp-post-id').val(),
                    'message': $('#directorist-report-message').val()
                };

                $.post(atbdp_public_data.ajaxurl, data, function (response) {
                    if (1 == response.error) {
                        $('#directorist-report-abuse-message-display').addClass('text-danger').html(response.message);
                    } else {
                        $('#directorist-report-message').val('');
                        $('#directorist-report-abuse-message-display').addClass('text-success').html(response.message);
                    }
                    $('.directorist-report-abuse-modal button[type=submit]').removeClass('directorist-btn-loading');

                }, 'json');

            }
        });
        $('#atbdp-report-abuse-form').removeAttr('novalidate');

        // Validate contact form
        $('.directorist-contact-owner-form').on('submit', function (e) {
            e.preventDefault();
            var submit_button = $(this).find('button[type="submit"]');
            var status_area = $(this).find('.directorist-contact-message-display');

            // Show loading message
            var msg = '<div class="directorist-alert"><i class="fas fa-circle-notch fa-spin"></i> ' + atbdp_public_data.waiting_msg + ' </div>';
            status_area.html(msg);

            var name = $(this).find('input[name="atbdp-contact-name"]');
            var contact_email = $(this).find('input[name="atbdp-contact-email"]');
            var message = $(this).find('textarea[name="atbdp-contact-message"]');
            var post_id = $(this).find('input[name="atbdp-post-id"]');
            var listing_email = $(this).find('input[name="atbdp-listing-email"]');

            // Post via AJAX
            var data = {
                'action': 'atbdp_public_send_contact_email',
                'post_id': post_id.val(),
                'name': name.val(),
                'email': contact_email.val(),
                'listing_email': listing_email.val(),
                'message': message.val(),
            };

            submit_button.prop('disabled', true);
            $.post(atbdp_public_data.ajaxurl, data, function (response) {
                submit_button.prop('disabled', false);

                if ( 1 == response.error ) {
                    atbdp_contact_submitted = false;

                    // Show error message
                    var msg = '<div class="atbdp-alert alert-danger-light"><i class="fas fa-exclamation-triangle"></i> ' + response.message + '</div>';
                    status_area.html(msg);

                } else {
                    name.val('');
                    message.val('');
                    contact_email.val('');

                    // Show success message
                    var msg = '<div class="atbdp-alert alert-success-light"><i class="fas fa-check-circle"></i> ' + response.message + '</div>';
                    status_area.html(msg);
                }

                setTimeout(function () {
                    status_area.html('');
                }, 5000);

            }, 'json');

        });

        $('#atbdp-contact-form,#atbdp-contact-form-widget').removeAttr('novalidate');

})(jQuery);