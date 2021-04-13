import { directoristModalAlert } from "../directorist-modal-alert";
;(function ($) {

    // remove the review of a user
    var delete_count = 1;
    
    $(document).on('click', '#directorist-review-remove', function (e) {
        e.preventDefault();
        if (delete_count > 1) {
            // show error message
            directoristModalAlert({
                title: "WARNING!!",
                text: atbdp_public_data.review_have_not_for_delete,
                type: 'warning',
                action: false,
                timeout: 2000,
            });
            return false; // if user try to submit the form more than once on a page load then return false and get out
        }
        var $this = $(this);
        var id = $this.data('review_id');
        var data = {
            review_id: id,
            action: "remove_listing_review"
        };
        
        directoristModalAlert({
            title: atbdp_public_data.review_sure_msg,
            text: atbdp_public_data.review_want_to_remove,
            type: 'warning',
            action: true,
            okButtonText: atbdp_public_data.review_delete_msg,
            okButtonUniqueId: 'directorist-remove-review-ok'
        });

        $('#directorist-remove-review-ok').on('click', function(){
            // user has confirmed, now remove the review
            $.post(atbdp_public_data.ajaxurl, data, function (response) {
                if ('success' === response) {

                    // show success message
                    directoristModalAlert({
                        title: "Deleted!!",
                        type: 'success',
                        action: false,
                        timeout: 1000,
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
                    directoristModalAlert({
                        title: "ERROR!!",
                        text: atbdp_public_data.review_wrong_msg,
                        type: 'error',
                        timeout: 2000,
                        action: false
                    });
                }
            });
        });

    });
    
})(jQuery);