;(function ($) {
    
    /* Listing No Image Controller */
    $('.atbd_listing_no_image .atbd_lower_badge').each(function(i, elm){
        if( !$.trim( $(elm).html() ).length ) {
            $(this).addClass('atbd-no-spacing');
        }
    });

})(jQuery);