/* Initialize wpColorPicker */
(function ($) {
	// Make sure the codes in this file runs only once, even if enqueued twice
	if (typeof window.directorist_colorPicker_executed === 'undefined') {
		window.directorist_colorPicker_executed = true;
	} else {
		return;
	}
	window.addEventListener('load', () => {
		/* Initialize wp color picker */
		function colorPickerInit() {
			const wpColorPickers = document.querySelectorAll('.directorist-color-picker-wrap');

			wpColorPickers.forEach((wrap) => {
				const $pickerInput = $(wrap).find('.directorist-color-picker');

				if ($pickerInput) {
					if ($.fn.wpColorPicker) {
						$pickerInput.wpColorPicker({
							change: function (event, ui) {
								const color = ui.color.toString();

								// Dispatch custom event
								const colorChangeEvent = new CustomEvent('directorist-color-changed', {
									detail: {
										color,
										input: event.target,
										form: event.target.closest('form'),
									},
								});

								window.dispatchEvent(colorChangeEvent);
							},
						});
					} else {
						console.warn('wpColorPicker is NOT available!');
					}
				}
			});
		}
		colorPickerInit();

		/* Initialize on Directory type change */
		window.addEventListener(
			'directorist-instant-search-reloaded',
			colorPickerInit
		);
	});
})(jQuery);
