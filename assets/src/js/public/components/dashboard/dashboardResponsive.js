;
(function ($) {
    window.addEventListener('load', () => {
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

        $('.directorist-dashboard__nav__close, .directorist-shade').on('click', function () {
            $(".directorist-user-dashboard__nav").addClass('directorist-dashboard-nav-collapsed');
            $(".directorist-shade").removeClass("directorist-active");
        })

        // Profile Responsive
        $('.directorist-tab__nav__link').on('click', function () {
            if ($('#user_profile_form').width() < 800 && $('#user_profile_form').width() !== 0) {
                $('#user_profile_form').addClass('directorist-profile-responsive');
            }
        });
    });
})(jQuery);