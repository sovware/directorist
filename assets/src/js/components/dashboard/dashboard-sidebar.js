;(function ($) {

    //dashboard sidebar nav toggler
    $(".directorist-user-dashboard__toggle__link").on("click", function(e){
        e.preventDefault();
        $(".directorist-user-dashboard__nav").toggleClass("directorist-dashboard-nav-collapsed");
    });
    
    if($(window).innerWidth() < 767){
      $(".directorist-user-dashboard__nav").addClass("directorist-dashboard-nav-collapsed");
      $(".directorist-user-dashboard__nav").addClass("directorist-dashboard-nav-collapsed--fixed");
    }

    //dashboard nav dropdown
    $(".atbdp_tab_nav--has-child .atbd-dash-nav-dropdown").on("click", function(e){
      e.preventDefault();
      $(this).siblings("ul").slideToggle();
    });

    if($(window).innerWidth() < 1199){
        $(".directorist-tab__nav__link").on("click", function(){
            $(".directorist-user-dashboard__nav").addClass('directorist-dashboard-nav-collapsed');
            $(".directorist-shade").removeClass("directorist-active");
        });

        $(".directorist-user-dashboard__toggle__link").on("click", function(e){
            e.preventDefault();
            $(".directorist-shade").toggleClass("directorist-active");
        });
    }
    

})(jQuery);