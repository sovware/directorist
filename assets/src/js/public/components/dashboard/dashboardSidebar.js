;
(function ($) {
    window.addEventListener('load', () => {
        //dashboard sidebar nav toggler
        $(".directorist-user-dashboard__toggle__link").on("click", function (e) {
            e.preventDefault();
            $(".directorist-user-dashboard__nav").toggleClass("directorist-dashboard-nav-collapsed");
            // $(".directorist-shade").toggleClass("directorist-active");
        });

        if ($(window).innerWidth() < 767) {
            $(".directorist-user-dashboard__nav").addClass("directorist-dashboard-nav-collapsed");
            $(".directorist-user-dashboard__nav").addClass("directorist-dashboard-nav-collapsed--fixed");
        }

        //dashboard nav dropdown
        $(".directorist-tab__nav__link").on("click", function (e) {
            e.preventDefault();
            if ($(this).hasClass("atbd-dash-nav-dropdown")) {
                // Slide toggle the sibling ul element
                $(this).siblings("ul").slideToggle();
            } else if(!$(this).parents(".atbdp_tab_nav--has-child").length > 0) {
                // Slide up all the dropdown contents while clicked item is not inside dropdown
                $(".atbd-dash-nav-dropdown").siblings("ul").slideUp();
            }
        });
        
        if ($(window).innerWidth() < 1199) {
            $(".directorist-tab__nav__link:not(.atbd-dash-nav-dropdown)").on("click", function () {
                $(".directorist-user-dashboard__nav").addClass('directorist-dashboard-nav-collapsed');
                $(".directorist-shade").removeClass("directorist-active");
            });

            $(".directorist-user-dashboard__toggle__link").on("click", function (e) {
                e.preventDefault();
                $(".directorist-shade").toggleClass("directorist-active");
            });
        }
    });
})(jQuery);