window.addEventListener('DOMContentLoaded', () => {
    (function ($) {
        $('.atbdp-widget-categories .atbdp_parent_category >li >span').on('click', function () {
            $(this).siblings('.atbdp_child_category').slideToggle();
        });
        $('.atbdp-widget-categories .atbdp_parent_location >li >span').on('click', function () {
            $(this).siblings('.atbdp_child_location').slideToggle();
        });
    })(jQuery);
});