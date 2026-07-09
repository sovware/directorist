(function ($) {
	$(function () {
		//dashboard content responsive fix
		const $contents = $(
			'.directorist-user-dashboard .directorist-user-dashboard__contents'
		);
		const $profileForm = $('#user_profile_form');
		let tabContentWidth = $contents.innerWidth();

		if (tabContentWidth < 1399) {
			$contents.addClass('directorist-tab-content-grid-fix');
		}

		const updateProfileResponsive = () => {
			if (!$profileForm.length) {
				return;
			}

			const profileWidth = $profileForm.width();

			if (!profileWidth) {
				return;
			}

			$profileForm.toggleClass(
				'directorist-profile-responsive',
				profileWidth < 800
			);
		};

		updateProfileResponsive();

		$('.directorist-tab__nav__link').on('click', function () {
			setTimeout(updateProfileResponsive, 0);
		});

		$(window).on('resize', updateProfileResponsive);
	});
})(jQuery);
