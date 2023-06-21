window.addEventListener('DOMContentLoaded', () => {
    // Make sure the codes in this file runs only once, even if enqueued twice
    if ( typeof window.directorist_catloc_executed === 'undefined' ) {
        window.directorist_catloc_executed = true;
    } else {
        return;
    }
    (function ($) {
        /* Multi level hierarchy content */
        /* Category */
        $('.atbdp_child_category').hide();
        $('.atbd_category_wrapper .expander').on('click', function () {
            $(this).siblings('.atbdp_child_category').slideToggle();
            $(this).toggleClass('opened'); 
            if ($(this).hasClass('opened')) {
                $(this).html('-');
            } else {
                $(this).html('+');
            }
        });

        /* Location */
        $('.atbdp_child_location').hide();
        $('.atbd_location_wrapper .expander').on('click', function () {
            $(this).siblings('.atbdp_child_location').slideToggle();
            $(this).toggleClass('opened'); 
            if ($(this).hasClass('opened')) {
                $(this).html('-');
            } else {
                $(this).html('+');
            }
        });
    })(jQuery);
});