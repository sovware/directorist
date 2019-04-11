(function($){
    /* multi level hierarchy content */
    $('.atbdp_child_category').hide();
    $('.atbd_category_wrapper > .expander').on('click', function () {
        $(this).siblings('.atbdp_child_category').slideToggle();
    });

    $('.atbdp_child_category li .expander').on('click', function () {
        $(this).siblings('.atbdp_child_category').slideToggle();
        $(this).parent('li').siblings('li').children('.atbdp_child_category').slideUp();
    });
})(jQuery);