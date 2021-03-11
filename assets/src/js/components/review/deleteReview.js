;(function ($) {

    // remove the review of a user
    var delete_count = 1;
    
    $(document).on('click', '#directorist-review-remove', function (e) {
        e.preventDefault();
        if (delete_count > 1) {
            // show error message
            swal({
                title: "WARNING!!",
                text: atbdp_public_data.review_have_not_for_delete,
                type: "warning",
                timer: 2000,
                showConfirmButton: false
            });
            return false; // if user try to submit the form more than once on a page load then return false and get out
        }
        var $this = $(this);
        var id = $this.data('review_id');
        var data = {
            review_id: id,
            action: "remove_listing_review"
        };
        
        swal({
            title: atbdp_public_data.review_sure_msg,
            text: atbdp_public_data.review_want_to_remove,
            type: "warning",
            cancelButtonText: atbdp_public_data.review_cancel_btn_text,
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: atbdp_public_data.review_delete_msg,
            showLoaderOnConfirm: true,
            closeOnConfirm: false
        },
            function (isConfirm) {
                if (isConfirm) {
                    // user has confirmed, now remove the review

                    $.post(atbdp_public_data.ajaxurl, data, function (response) {
                        if ('success' === response) {
                            // show success message
                            swal({
                                title: "Deleted!!",
                                type: "success",
                                timer: 200,
                                showConfirmButton: false
                            });
                            $("#single_review_" + id).slideUp();
                            $this.remove();
                            $('#review_content').empty();
                            $(".directorist-review-form-action").remove();
                            $("#directorist-client-review-list").remove();
                            $("#reviewCounter").hide();
                            delete_count++; // increase the delete counter so that we do not need to delete the review more than once.
                        } else {
                            // show error message
                            swal({
                                title: "ERROR!!",
                                text: atbdp_public_data.review_wrong_msg,
                                type: "error",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });

        // send an ajax request to the ajax-handler.php and then delete the review of the given id

    });
    
})(jQuery);