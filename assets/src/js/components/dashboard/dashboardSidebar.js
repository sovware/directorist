;(function ($) {

    //dashboard sidebar nav toggler

    $(".atbd-dashboard-nav-toggler").on("click", function(e){
        e.preventDefault();
        $(".atbd_user_dashboard_nav").toggleClass("atbd-dashboard-nav-collapsed");
    });
    
    if($(window).innerWidth() < 767){
      $(".atbd_user_dashboard_nav").addClass("atbd-dashboard-nav-collapsed");
      $(".atbd_user_dashboard_nav").addClass("atbd-dashboard-nav-collapsed--fixed");
      $("body").on("click", function(e){
            if($(e.target).is(".atbd_user_dashboard_nav, .atbdp_all_booking_nav-link, .atbd-dashboard-nav-toggler, .atbd-dashboard-nav-toggler i, .atbdp_tab_nav--content-link") === false) {
                $(".atbd_user_dashboard_nav").addClass("atbd-dashboard-nav-collapsed");
            }
        });
    }

    //dashboard nav dropdown

    $(".atbdp_tab_nav--has-child .atbd-dash-nav-dropdown").on("click", function(e){
      e.preventDefault();
      $(this).siblings("ul").slideToggle();
    });

})(jQuery);