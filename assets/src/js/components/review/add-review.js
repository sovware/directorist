import { directoristModalAlert } from "../directorist-modal-alert";
;(function ($) {
    // 	prepear_form_data
    function prepear_form_data ( form, field_map, data ) {
        if ( ! data || typeof data !== 'object' ) {
            var data = {};
        }

        for ( var key in field_map) {
            var field_item = field_map[ key ];
            var field_key = field_item.field_key;
            var field_type = field_item.type;

            if ( 'name' === field_type ) {
                var field = form.find( '[name="'+ field_key +'"]' );
            } else {
                var field = form.find( field_key );
            }

            if ( field.length ) {
                var data_key = ( 'name' === field_type ) ? field_key : field.attr('name') ;
                var data_value = ( field.val() ) ? field.val() : '';

                data[data_key] = data_value;
            }
        }

        return data;
    }

     /*HELPERS*/
     function print_static_rating($star_number) {
        var v;
        if ($star_number) {
            v = '<ul>';
            for (var i = 1; i <= 5; i++) {
                v += (i <= $star_number)
                    ? "<li><span class='directorist-rate-active'></span></li>"
                    : "<li><span class='directoristrate-disable'></span></li>";
            }
            v += '</ul>';
        }

        return v;
    }

    /* Add review to the database using ajax*/
    var submit_count = 1;

    $("#directorist-review-form").on("submit", function (e) {
        e.preventDefault();
        if (submit_count > 1) {
            // show error message
            directoristModalAlert({
                title: atbdp_public_data.warning,
                text: atbdp_public_data.not_add_more_than_one,
                type: 'warning',
                timeout: 2000,
                action: false
            });
            return false; // if user try to submit the form more than once on a page load then return false and get out
        }
        var $form = $(this);
        var $data = $form.serialize();

        var field_field_map = [
            { type: 'name', field_key: 'post_id' },
            { type: 'id', field_key: '#atbdp_review_nonce_form' },
            { type: 'id', field_key: '#guest_user_email' },
            { type: 'id', field_key: '#reviewer_name' },
            { type: 'id', field_key: '#review_content' },
            { type: 'id', field_key: '#directorist-review-rating' },
            { type: 'id', field_key: '#review_duplicate' },
        ];

        var _data = { action: 'save_listing_review' };
        _data = prepear_form_data( $form, field_field_map, _data );
        
        // atbdp_do_ajax($form, 'save_listing_review', _data, function (response) {

        jQuery.post(atbdp_public_data.ajaxurl, _data, function(response) {
            var output = '';
            var deleteBtn = '';
            var d;
            var name = $form.find("#reviewer_name").val();
            var content = $form.find("#review_content").val();
            var rating = $form.find("#directorist-review-rating").val();
            var ava_img = $form.find("#reviewer_img").val();
            var approve_immediately = $form.find("#approve_immediately").val();
            var review_duplicate = $form.find("#review_duplicate").val();
            if (approve_immediately === 'no') {
                if(content === '') {
                    // show error message
                    directoristModalAlert({
                        title: "ERROR!!",
                        text: atbdp_public_data.review_error,
                        type: 'error',
                        timeout: 2000,
                        action: false
                    });
                } else {
                    if (submit_count === 1) {
                        $('#directorist-client-review-list').prepend(output); // add the review if it's the first review of the user
                        $('.atbdp_static').remove();
                    }
                    submit_count++;
                    if (review_duplicate === 'yes') {

                        directoristModalAlert({
                            title: atbdp_public_data.warning,
                            text: atbdp_public_data.duplicate_review_error,
                            type: 'warning',
                            timeout: 3000,
                            action: false
                        });

                    } else {

                        directoristModalAlert({
                            title: atbdp_public_data.success,
                            text: atbdp_public_data.review_approval_text,
                            type: 'success',
                            timeout: 4000,
                            action: false
                        });

                    }
                }


            } else if (response.success) {
                output +=
                    '<div class="directorist-signle-review" id="directorist-single-review-' + response.data.id + '">' +
                    '<input type="hidden" value="1" id="has_ajax">' +
                    '<div class="directorist-signle-review__top"> ' +
                    '<div class="directorist-signle-review-avatar-wrap"> ' +
                    '<div class="directorist-signle-review-avatar">' + ava_img + '</div> ' +
                    '<div class="directorist-signle-review-avatar__info"> ' +
                    '<p>' + name + '</p>' +
                    '<span class="directorist-signle-review-time">' + response.data.date + '</span> ' + '</div> ' + '</div> ' +
                    '<div class="directorist-rated-stars">' + print_static_rating(rating) + '</div> ' +
                    '</div> ';
                if( atbdp_public_data.enable_reviewer_content ) {
                output +=
                    '<div class="directorist-signle-review__content"> ' +
                    '<p>' + content + '</p> ' +
                    //'<a href="#"><span class="fa fa-mail-reply-all"></span>Reply</a> ' +
                    '</div> ';
                }
                output +=
                    '</div>';

                // as we have saved a review lets add a delete button so that user cann delete the review he has just added.
                deleteBtn += '<button class="directory_btn btn btn-danger" type="button" id="atbdp_review_remove" data-review_id="' + response.data.id + '">Remove</button>';
                $form.append(deleteBtn);
                if (submit_count === 1) {
                    $('#directorist-client-review-list').prepend(output); // add the review if it's the first review of the user
                    $('.atbdp_static').remove();
                }
                var sectionToShow = $("#has_ajax").val();
                var sectionToHide = $(".atbdp_static");
                var sectionToHide2 = $(".directory_btn");
                if (sectionToShow) {
                    // $(sectionToHide).hide();
                    $(sectionToHide2).hide();
                }
                submit_count++;
                
                // show success message
                directoristModalAlert({
                    title: atbdp_public_data.review_success,
                    type: 'success',
                    timeout: 1000,
                    action: false
                });

                //reset the form
                $form[0].reset();
                // remove the notice if there was any
                var $r_notice = $('#review_notice');
                if ($r_notice) {
                    $r_notice.remove();
                }
            } else {
                // show error message
                directoristModalAlert({
                    title: "ERROR!!",
                    text: atbdp_public_data.review_error,
                    type: "error",
                    timeout: 2000,
                    action: false
                });
            }
        });

        return false;
    });
    
})(jQuery);