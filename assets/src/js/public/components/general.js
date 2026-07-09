// Fix listing with no thumb if card width is less than 220px
(function ($) {
	let isHandlingBackButton = false;
	let backButtonTouchStart = null;
	const touchMoveThreshold = 10;

	const getTouchPoint = (e) => {
		const touches =
			e.originalEvent.changedTouches || e.originalEvent.touches || [];

		if (!touches.length) {
			return null;
		}

		return {
			x: touches[0].clientX,
			y: touches[0].clientY,
		};
	};

	const didTouchMove = (touchEnd) => {
		return (
			Math.abs(touchEnd.x - backButtonTouchStart.x) > touchMoveThreshold ||
			Math.abs(touchEnd.y - backButtonTouchStart.y) > touchMoveThreshold
		);
	};

	const shouldIgnoreTouchEnd = (e, element) => {
		if (e.type !== 'touchend') {
			return false;
		}

		const touchEnd = getTouchPoint(e);

		if (
			!backButtonTouchStart ||
			!touchEnd ||
			backButtonTouchStart.element !== element
		) {
			backButtonTouchStart = null;
			return true;
		}

		const moved = didTouchMove(touchEnd);
		backButtonTouchStart = null;

		return moved;
	};

	const handleBackButton = function (e) {
		if (shouldIgnoreTouchEnd(e, this)) {
			return;
		}

		e.preventDefault();
		e.stopImmediatePropagation();

		if (isHandlingBackButton) {
			return;
		}

		isHandlingBackButton = true;
		const currentUrl = window.location.href;
		const fallbackUrl = this.href;

		window.history.back();

		if (fallbackUrl && fallbackUrl !== '#' && fallbackUrl !== currentUrl) {
			setTimeout(() => {
				if (window.location.href === currentUrl) {
					window.location.href = fallbackUrl;
				}
			}, 300);
		}

		setTimeout(() => {
			isHandlingBackButton = false;
		}, 1000);
	};

	// Back Button to go back to the previous page
	$('body').on('touchstart', '.directorist-btn__back', function (e) {
		const touchStart = getTouchPoint(e);

		if (!touchStart) {
			backButtonTouchStart = null;
			return;
		}

		backButtonTouchStart = {
			x: touchStart.x,
			y: touchStart.y,
			element: this,
		};
	});

	$('body').on('click touchend', '.directorist-btn__back', handleBackButton);

	window.addEventListener('load', () => {
		if ($('.directorist-listing-no-thumb').innerWidth() <= 220) {
			$('.directorist-listing-no-thumb').addClass(
				'directorist-listing-no-thumb--fix'
			);
		}
		// Auhtor Profile Listing responsive fix
		if ($('.directorist-author-listing-content').innerWidth() <= 750) {
			$('.directorist-author-listing-content').addClass(
				'directorist-author-listing-grid--fix'
			);
		}
		// Directorist Archive responsive fix
		if ($('.directorist-archive-grid-view').innerWidth() <= 500) {
			$('.directorist-archive-grid-view').addClass(
				'directorist-archive-grid--fix'
			);
		}
	});
})(jQuery);
