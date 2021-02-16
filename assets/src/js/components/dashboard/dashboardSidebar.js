;(function ($) {

    //dashboard sidebar nav toggler

    $(".directorist-user-dashboard__toggle__link").on("click", function(e){
        e.preventDefault();
        $(".directorist-user-dashboard__nav").toggleClass("directorist-dashboard-nav-collapsed");
    });
    
    if($(window).innerWidth() < 767){
      $(".directorist-user-dashboard__nav").addClass("directorist-dashboard-nav-collapsed");
      $(".directorist-user-dashboard__nav").addClass("directorist-dashboard-nav-collapsed--fixed");
      $("body").on("click", function(e){
            if($(e.target).is(".directorist-user-dashboard__nav, .atbdp_all_booking_nav-link, .directorist-user-dashboard__toggle__link, .directorist-user-dashboard__toggle__link i, .directorist-tab__nav__item") === false) {
                $(".directorist-user-dashboard__nav").addClass("directorist-dashboard-nav-collapsed");
            }
        });
    }

    //dashboard nav dropdown

    $(".atbdp_tab_nav--has-child .atbd-dash-nav-dropdown").on("click", function(e){
      e.preventDefault();
      $(this).siblings("ul").slideToggle();
    });

})(jQuery);