(function ($) {
    window.addEventListener('load', () => {

        if ($('.directorist-single-contents-area').length > 0) {
            var listing_id  = $('.directorist-single-contents-area').data('id');  // listing id
            var storage_key = 'directorist_listing_views';                        // Key for session storage
        
            // Check if the user has already viewed this listing during the session.
            var viewed_listings = JSON.parse( sessionStorage.getItem( storage_key ) ) || {};
        
            if ( !viewed_listings[listing_id] ) {
                // Send an AJAX request to track the view for this specific listing.
                $.ajax({
                    type: 'POST',
                    url : directorist.ajaxurl,
                    data: {
                        action           : 'directorist_track_listing_views',
                        listing_id       : listing_id,
                        directorist_nonce: directorist.directorist_nonce,
                    },
                    success: function ( response ) {
                        if ( response.success ) {
                            // Mark this listing as viewed in the session storage.
                            viewed_listings[listing_id] = true;
                            // Update the session storage.
                            sessionStorage.setItem( storage_key, JSON.stringify( viewed_listings ) );
                        }
                    }
                });
            }
        }

    });
})(jQuery);