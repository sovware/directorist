;(function ($) {
    var profileMediaUploader = null;
    if ($("#user_profile_pic").length) {
        profileMediaUploader = new EzMediaUploader({
            containerID: "user_profile_pic",
        });
        profileMediaUploader.init();
    }
    var is_processing = false;

    $('#user_profile_form').on('submit', function (e) {
        // submit the form to the ajax handler and then send a response from the database and then work accordingly and then after finishing the update profile then work on remove listing and also remove the review and rating form the custom table once the listing is deleted successfully.
        e.preventDefault();

        var submit_button = $('#update_user_profile');
        submit_button.attr('disabled', true);
        submit_button.addClass("directorist-loader");

        if (is_processing) { submit_button.removeAttr('disabled'); return; }

        var form_data = new FormData();
        var err_log = {};
        var error_count;

        // ajax action
        form_data.append('action', 'update_user_profile');
        if ( profileMediaUploader ) {
            var hasValidFiles = profileMediaUploader.hasValidFiles();
            if (hasValidFiles) {
                //files
                var files = profileMediaUploader.getTheFiles();
                var filesMeta = profileMediaUploader.getFilesMeta();

                if (files.length) {
                    for (var i = 0; i < files.length; i++) {
                        form_data.append('profile_picture', files[i]);
                    }
                }

                if (filesMeta.length) {
                    for (var i = 0; i < filesMeta.length; i++) {
                        var elm = filesMeta[i];
                        for (var key in elm) {
                            form_data.append('profile_picture_meta[' + i + '][' + key + ']', elm[key]);
                        }
                    }
                }

            } else {
                $(".directorist-form-submit__btn").removeClass("atbd_loading");
                err_log.user_profile_avater = { msg: 'Listing gallery has invalid files' };
                error_count++;
            }
        }
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
            url: atbdp_public_data.ajaxurl,
            data: form_data,
            success: function (response) {
                submit_button.removeAttr('disabled');
                submit_button.removeClass("directorist-loader");

                console.log( response );

                if (response.success) {
                    $('#directorist-prifile-notice').html('<span class="directorist-alert directorist-alert-success">' + response.data + '</span>');
                } else {
                    $('#directorist-prifile-notice').html('<span class="directorist-alert directorist-alert-danger">' + response.data + '</span>');
                }
            },
            error: function (response) {
                submit_button.removeAttr('disabled');
                console.log(response);
            }
        });

        // prevent the from submitting
        return false;
    });

})(jQuery);