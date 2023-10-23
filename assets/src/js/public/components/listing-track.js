(function ($) {
    window.addEventListener('DOMContentLoaded', () => {

        if ( $('.directorist-single-contents-area').length > 0) {
            var postID     = $('.directorist-single-contents-area').data('id');
            var cookieName = 'directorist_listing_view_' + postID;
            // Check if the user has a cookie for this post.
            if ( document.cookie.indexOf( cookieName ) === -1 ) {
                // Send an AJAX request to track the view.
                $.ajax({
                    type: 'POST',
                    url : directorist.ajaxurl,
                    data: {
                        action           : 'track_post_views',
                        post_id          : postID,
                        directorist_nonce: directorist.directorist_nonce,
                    },
                    success: function(response) {
                        if ( response.success ) {
                            // Set a non-expiring cookie to indicate that the user's view has been counted.
                            document.cookie = cookieName + '=1;path=/';
                        }
                    }
                });
            }
        }

    });
})(jQuery);
