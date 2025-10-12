jQuery(($) => {
	$('.directorist-ResetPassword').on('submit', function () {
		let form = $(this);

		if (form.find('#password_1').val() != form.find('#password_2').val()) {
			form.find('.password-not-match').show();
			return false;
		}

		form.find('.password-not-match').hide();
		return true;
	});
});
