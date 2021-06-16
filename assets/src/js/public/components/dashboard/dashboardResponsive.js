; (function ($) {

  //dashboard content responsive fix

  let tabContentWidth = $(".directorist-user-dashboard .directorist-user-dashboard__contents").innerWidth();

  if (tabContentWidth < 1399) {
    $(".directorist-user-dashboard .directorist-user-dashboard__contents").addClass("directorist-tab-content-grid-fix");
  }

  $(window)
    .bind("resize", function () {
      if ($(this).width() <= 1199) {
        $(".directorist-user-dashboard__nav").addClass("directorist-dashboard-nav-collapsed");
        $(".directorist-shade").removeClass("directorist-active");
      }
    })
    .trigger("resize");

  $('.directorist-dashboard__nav--close, .directorist-shade').on('click', function () {

    $(".directorist-user-dashboard__nav").addClass('directorist-dashboard-nav-collapsed');
    $(".directorist-shade").removeClass("directorist-active");

  })

  // Profile Responsive
  if ($('#user_profile_form').width() < 800) {
    $('#user_profile_form').addClass('directorist-profile-responsive');
  }

})(jQuery);