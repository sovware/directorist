jQuery(($) => {
	// Trigger reset on form change
	$('.directorist-authentication__btn').on('click', function () {
		// Reset the form values
		$('.directorist__authentication__signup').each(function () {
			this.reset(); // Reset the individual form
		});

		// Reset error and warning messages
		$('.directorist-alert ').hide().empty();
		$('.directorist-register-error').hide().empty();
	});

	$(
		'.directorist__authentication__signup .directorist-authentication__form__btn'
	).on('click', function (e) {
		e.preventDefault();
		$this = $(this);
		$this.addClass('directorist-btn-loading'); // Added loading class
		const form = $this.closest('.directorist__authentication__signup')[0];

		// Trigger native validation
		if (!form.checkValidity()) {
			form.reportValidity(); // Display browser-native warnings for invalid fields
			$this.removeClass('directorist-btn-loading'); // Removed loading class
			return; // Stop submission if validation fails
		}

		var formData = new FormData(form);
		formData.append('action', 'directorist_register_form');
		formData.append(
			'params',
			JSON.stringify(directorist_signin_signup_params)
		);

		$.ajax({
			url: directorist.ajaxurl,
			type: 'POST',
			data: formData,
			contentType: false,
			processData: false,
			cache: false,
		}).done(function ({ data, success }) {
			// Removed loading class
			setTimeout(
				() => $this.removeClass('directorist-btn-loading'),
				1000
			);

			if (!success) {
				$('.directorist-register-error')
					.empty()
					.show()
					.append(data.error);

				return;
			}

			$('.directorist-register-error').hide();

			if (data.message) {
				$('.directorist-register-error')
					.empty()
					.show()
					.append(data.message)
					.css({
						color: '#009114',
						'background-color': '#d9efdc',
					});
			}

			if (data.redirect_url) {
				setTimeout(
					() => (window.location.href = data.redirect_url),
					500
				);
			}
		});
	});
});
