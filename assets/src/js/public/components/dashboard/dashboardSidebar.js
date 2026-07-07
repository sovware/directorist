(function ($) {
	$(function () {
		const $nav = $('.directorist-user-dashboard__nav');
		const $shade = $('.directorist-shade');
		const $toggle = $('.directorist-user-dashboard__toggle__link');
		const $close = $('.directorist-dashboard__nav__close');
		const collapsedClass = 'directorist-dashboard-nav-collapsed';
		const fixedClass = 'directorist-dashboard-nav-collapsed--fixed';
		const shadeActiveClass = 'directorist-active';
		const compactWidth = 1199;

		if (!$nav.length) {
			return;
		}

		const isCompact = () => $(window).innerWidth() <= compactWidth;

		const setCompactNavState = (isOpen) => {
			$nav.addClass(fixedClass);
			$nav.toggleClass(collapsedClass, !isOpen);
			$shade.toggleClass(shadeActiveClass, isOpen);
			$toggle.attr('aria-expanded', isOpen ? 'true' : 'false');
			$nav.attr('aria-hidden', isOpen ? 'false' : 'true');
		};

		const closeCompactNav = () => {
			if (isCompact()) {
				setCompactNavState(false);
			}
		};

		const syncNavState = () => {
			if (isCompact()) {
				const isOpen =
					!$nav.hasClass(collapsedClass) &&
					$shade.hasClass(shadeActiveClass);

				setCompactNavState(isOpen);
				return;
			}

			$nav.removeClass(`${collapsedClass} ${fixedClass}`);
			$shade.removeClass(shadeActiveClass);
			$toggle.attr('aria-expanded', 'true');
			$nav.attr('aria-hidden', 'false');
		};

		$toggle.attr(
			'aria-controls',
			$nav.attr('id') || 'directorist-dashboard-nav'
		);

		if (!$nav.attr('id')) {
			$nav.attr('id', 'directorist-dashboard-nav');
		}

		$toggle.on('click', function (e) {
			e.preventDefault();

			if (isCompact()) {
				setCompactNavState($nav.hasClass(collapsedClass));
				return;
			}

			const shouldOpen = $nav.hasClass(collapsedClass);
			$nav.toggleClass(collapsedClass);
			$toggle.attr('aria-expanded', shouldOpen ? 'true' : 'false');
			$nav.attr('aria-hidden', shouldOpen ? 'false' : 'true');
		});

		//dashboard nav dropdown
		$('.directorist-tab__nav__link').on('click', function (e) {
			e.preventDefault();

			if ($(this).hasClass('atbd-dash-nav-dropdown')) {
				// Slide toggle the sibling ul element
				$(this).siblings('ul').slideToggle();
			} else if (
				!$(this).parents('.atbdp_tab_nav--has-child').length > 0
			) {
				// Slide up all the dropdown contents while clicked item is not inside dropdown
				$('.atbd-dash-nav-dropdown').siblings('ul').slideUp();
			}

			if (!$(this).hasClass('atbd-dash-nav-dropdown')) {
				closeCompactNav();
			}
		});

		$close.add($shade).on('click', function () {
			closeCompactNav();
		});

		$close.on('keydown', function (e) {
			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				closeCompactNav();
			}
		});

		$(window).on('resize', syncNavState);
		syncNavState();
	});
})(jQuery);
