'use strict';

document.addEventListener( 'DOMContentLoaded', function () {
	let active = null;
	let scrollPosition = 0;
	const touchEnabledPopups = new WeakSet();

	function focusableElements( container ) {
		return Array.from(
			container.querySelectorAll(
				'a[href], button:not([disabled]), input:not([disabled]):not([type="hidden"]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
			)
		).filter(
			( element ) =>
				element.getClientRects().length &&
				'hidden' !== window.getComputedStyle( element ).visibility
		);
	}

	function setPageState( isOpen ) {
		if ( isOpen ) {
			scrollPosition = window.scrollY;
			document.body.style.setProperty(
				'--directorist-search-popup-scroll-offset',
				`-${ scrollPosition }px`
			);
		}

		document.documentElement.classList.toggle(
			'directorist-search-popup-block-hidden',
			isOpen
		);
		document.body.classList.toggle(
			'directorist-search-popup-block-hidden',
			isOpen
		);

		if ( isOpen ) {
			return;
		}

		document.body.style.removeProperty(
			'--directorist-search-popup-scroll-offset'
		);
		window.scrollTo( 0, scrollPosition );
	}

	function closeSearchPopup( restoreFocus = true ) {
		if ( ! active ) {
			return;
		}

		const { popup, overlay, trigger } = active;
		popup.classList.remove( 'show', 'responsive-true' );
		popup.setAttribute( 'aria-hidden', 'true' );
		popup.removeAttribute( 'style' );
		overlay.classList.remove( 'show' );
		trigger.setAttribute( 'aria-expanded', 'false' );
		active = null;
		setPageState( false );

		if ( restoreFocus && document.contains( trigger ) ) {
			window.requestAnimationFrame( () =>
				trigger.focus( { preventScroll: true } )
			);
		}
	}

	function openSearchPopup( popup, overlay, trigger ) {
		if ( active ) {
			closeSearchPopup( false );
		}

		active = { popup, overlay, trigger };
		popup.classList.add( 'show' );
		popup.setAttribute( 'role', 'dialog' );
		popup.setAttribute( 'aria-modal', 'true' );
		popup.setAttribute( 'aria-hidden', 'false' );
		overlay.classList.add( 'show' );
		trigger.setAttribute( 'aria-expanded', 'true' );
		setPageState( true );

		const focusTarget =
			popup.querySelector(
				'input:not([type="hidden"]):not([disabled]), select:not([disabled]), textarea:not([disabled])'
			) || popup.querySelector( 'button:not([disabled]), a[href]' );
		const focusPopup = () => {
			if ( active?.popup === popup ) {
				focusTarget?.focus( { preventScroll: true } );
			}
		};

		window.requestAnimationFrame( focusPopup );
		window.setTimeout( focusPopup, 350 );
	}

	function enableTouchDismiss( popup ) {
		if ( touchEnabledPopups.has( popup ) ) {
			return;
		}

		touchEnabledPopups.add( popup );
		let touchStartY = null;
		let touchDistance = 0;

		popup.addEventListener(
			'touchstart',
			( event ) => {
				if (
					! window.matchMedia( '(max-width: 575px)' ).matches ||
					1 !== event.touches.length ||
					event.touches[ 0 ].clientY -
						popup.getBoundingClientRect().top >
						56
				) {
					return;
				}

				touchStartY = event.touches[ 0 ].clientY;
				touchDistance = 0;
				popup.style.transition = 'none';
			},
			{ passive: true }
		);

		popup.addEventListener(
			'touchmove',
			( event ) => {
				if ( null === touchStartY || 1 !== event.touches.length ) {
					return;
				}

				touchDistance = Math.max(
					0,
					event.touches[ 0 ].clientY - touchStartY
				);
				popup.style.transform = `translateY(${ touchDistance }px)`;
			},
			{ passive: true }
		);

		popup.addEventListener( 'touchend', () => {
			popup.style.removeProperty( 'transition' );
			popup.style.removeProperty( 'transform' );

			if ( touchDistance >= 80 && active?.popup === popup ) {
				closeSearchPopup();
			}

			touchStartY = null;
			touchDistance = 0;
		} );
	}

	document
		.querySelectorAll( '.directorist-search-popup-block' )
		.forEach( ( block ) => {
			const trigger = block.querySelector(
				'.directorist-search-popup-block__button'
			);
			const popup = document.getElementById(
				trigger?.getAttribute( 'aria-controls' )
			);
			const closeButton = popup?.querySelector(
				'.directorist-search-popup-block__form-close'
			);

			if ( ! trigger || ! popup || ! closeButton ) {
				return;
			}

			if ( popup.parentElement !== document.body ) {
				document.body.appendChild( popup );
			}

			popup.setAttribute( 'aria-hidden', 'true' );
			enableTouchDismiss( popup );

			const overlay = document.createElement( 'div' );
			overlay.className = 'directorist-search-popup-block__overlay';
			overlay.setAttribute( 'aria-hidden', 'true' );
			document.body.appendChild( overlay );

			trigger.addEventListener( 'click', ( event ) => {
				event.preventDefault();

				if ( active?.popup === popup ) {
					closeSearchPopup();
					return;
				}

				openSearchPopup( popup, overlay, trigger );
			} );
			overlay.addEventListener( 'click', () => closeSearchPopup() );
			closeButton.addEventListener( 'click', () => closeSearchPopup() );

			popup
				.querySelectorAll(
					'.directorist-search-form-action__modal .directorist-modal-btn'
				)
				.forEach( ( button ) =>
					button.addEventListener( 'click', () => {
						popup.classList.add( 'responsive-true' );
						overlay.classList.remove( 'show' );
					} )
				);
		} );

	document.addEventListener( 'keydown', ( event ) => {
		if ( ! active ) {
			return;
		}

		if ( 'Escape' === event.key ) {
			event.preventDefault();
			closeSearchPopup();
			return;
		}

		if ( 'Tab' !== event.key ) {
			return;
		}

		const focusable = focusableElements( active.popup );
		const first = focusable[ 0 ];
		const last = focusable[ focusable.length - 1 ];

		if (
			event.shiftKey &&
			active.popup.ownerDocument.activeElement === first
		) {
			event.preventDefault();
			last?.focus();
		} else if (
			! event.shiftKey &&
			active.popup.ownerDocument.activeElement === last
		) {
			event.preventDefault();
			first?.focus();
		}
	} );
} );
