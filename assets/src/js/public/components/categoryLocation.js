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
        $('.atbd_category_wrapper > .expander').on('click', function () {
            $(this).siblings('.atbdp_child_category').slideToggle();
        });
        $('.atbdp_child_category li .expander').on('click', function () {
            $(this).siblings('.atbdp_child_category').slideToggle();
            $(this).parent('li').siblings('li').children('.atbdp_child_category').slideUp();
        });

        /* Location */
        $('.atbdp_child_location').hide();
        $('.atbd_location_wrapper > .expander').on('click', function () {
            $(this).siblings('.atbdp_child_location').slideToggle();
        });
        $('.atbdp_child_location li .expander').on('click', function () {
            $(this).siblings('.atbdp_child_location').slideToggle();
            $(this).parent('li').siblings('li').children('.atbdp_child_location').slideUp();
        });
    })(jQuery);

    /* Category Card */
    const categoryCard = document.querySelectorAll('.directorist-categories__single--style-three');
    if(categoryCard){
        categoryCard.forEach(elm =>{
            const categoryCardWidth = elm.offsetWidth;
            elm.style.setProperty('--directorist-category-box-width', `${categoryCardWidth}px`);
        })
    }
});