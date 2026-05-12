(function ($) {
	window.addEventListener('load', () => {
		$('#atbdp-checkout-form').on('submit', async function (e) {
			e.preventDefault();

			// Show loading state
			const submitBtn = $('#atbdp_checkout_submit_btn');
			const btnText = submitBtn.find('.directorist-btn-text');
			const btnSpinner = submitBtn.find('.directorist-btn-spinner');
			const originalText = btnText.text();

			submitBtn.prop('disabled', true);
			btnText.text(submitBtn.data('loading-text'));
			btnSpinner.show();

			const formData = new FormData(this);
			const data = Object.fromEntries(formData);

			try {
				const response = await wp.apiFetch({
					path: '/directorist/v1/checkout',
					method: 'POST',
					data: data,
				});

				if (response.redirect_url) {
					window.location.href = response.redirect_url;
				}
			} catch (error) {
				const message = get_error_message(error);

				alert(message);
				console.log(error);

				// Reset loading state on error
				submitBtn.prop('disabled', false);
				btnText.text(originalText);
				btnSpinner.hide();
			}
		});
	});
})(jQuery);
