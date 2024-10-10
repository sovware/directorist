;
(function ($) {
    if ($('#display_author_email').length) {
        $('#display_author_email').select2();
    }
    window.addEventListener('load', () => {
        var is_processing = false;
        $('#user_preferences').on('submit', function (e) {
            // submit the form to the ajax handler and then send a response from the database and then work accordingly and then after finishing the update profile then work on remove listing and also remove the review and rating form the custom table once the listing is deleted successfully.
            e.preventDefault();

            var submit_button = $('#update_user_preferences');
            submit_button.attr('disabled', true);
            submit_button.addClass("directorist-loader");

            if (is_processing) {
                submit_button.removeAttr('disabled');
                return;
            }

            var form_data = new FormData();
            var err_log = {};

            // ajax action
            form_data.append('action', 'update_user_preferences');
            form_data.append('directorist_nonce', directorist.directorist_nonce);
            
            var $form = $(this);
            var arrData = $form.serializeArray();

            $.each(arrData, function (index, elem) {
                var name = elem.name;
                var value = elem.value;
                form_data.append(name, value);
            });

            $.ajax({
                method: 'POST',
                processData: false,
                contentType: false,
                url: directorist.ajaxurl,
                data: form_data,
                success: function (response) {
                    submit_button.removeAttr('disabled');
                    submit_button.removeClass("directorist-loader");

                    if (response.success) {
                        
                        $('#directorist-preference-notice').html('<span class="directorist-alert directorist-alert-success">' + response.data.message + '</span>');
                        

                    } else {
                        $('#directorist-preference-notice').html('<span class="directorist-alert directorist-alert-danger">' + response.data.message + '</span>');
                    }
                },
                error: function (response) {
                    submit_button.removeAttr('disabled');
                    console.log(response);
                }
            });
            // remove notice after five second
            setTimeout(() => {
                $("#directorist-preference-notice .directorist-alert").remove();
            }, 5000);

            // prevent the from submitting
            return false;
        });
    });
})(jQuery);