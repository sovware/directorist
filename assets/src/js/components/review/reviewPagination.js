;(function ($) {

    // Review Pagination Control 
    function atbdp_load_all_posts(page) {

        var listing_id = $('#review_post_id').attr('data-post-id');
        // Data to receive from our server
        // the value in 'action' is the key that will be identified by the 'wp_ajax_' hook
        var data = {
            page: page,
            listing_id: listing_id,
            action: "atbdp_review_pagination"
        };

        // Send the data
        $.post(atbdp_public_data.ajaxurl, data, function (response) {
            // If successful Append the data into our html container
            $('#directorist-client-review-list').empty().append(response);
        });
    }

    // Load page 1 as the default
    if ($('#directorist-client-review-list').length) {
        atbdp_load_all_posts(1);
    }

    // Handle the clicks
    $('body').on('click', '.atbdp-universal-pagination li.atbd-active', function () {
        var page = $(this).attr('data-page');
        atbdp_load_all_posts(page);
    });
    
})(jQuery);