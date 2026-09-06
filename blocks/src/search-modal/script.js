'use strict';

document.addEventListener( 'DOMContentLoaded', function () {
	let active = null;

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

	function closeSearchPopup( restoreFocus = true ) {
		if ( ! active ) {
			return;
		}

		const { popup, overlay, trigger } = active;
		popup.classList.remove( 'show', 'responsive-true' );
		popup.setAttribute( 'aria-hidden', 'true' );
		overlay.classList.remove( 'show' );
		trigger.setAttribute( 'aria-expanded', 'false' );
		document.documentElement.classList.remove(
			'directorist-search-popup-block-hidden'
		);
		document.body.classList.remove(
			'directorist-search-popup-block-hidden'
		);
		active = null;

		if ( restoreFocus && document.contains( trigger ) ) {
			trigger.focus( { preventScroll: true } );
		}
	}

	function openSearchPopup( popup, overlay, trigger ) {
		if ( active ) {
			closeSearchPopup( false );
		}

		active = { popup, overlay, trigger };
		popup.classList.add( 'show' );
		popup.setAttribute( 'aria-hidden', 'false' );
		overlay.classList.add( 'show' );
		trigger.setAttribute( 'aria-expanded', 'true' );
		document.documentElement.classList.add(
			'directorist-search-popup-block-hidden'
		);
		document.body.classList.add( 'directorist-search-popup-block-hidden' );

		const focusTarget = popup.querySelector(
			'.directorist-search-popup-block__form-close'
		);
		const focusPopup = () => {
			if ( active?.popup === popup ) {
				focusTarget?.focus( { preventScroll: true } );
			}
		};

		window.requestAnimationFrame( focusPopup );
		window.setTimeout( focusPopup, 350 );
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

			const overlay = document.createElement( 'div' );
			overlay.className = 'directorist-search-popup-block__overlay';
			overlay.setAttribute( 'aria-hidden', 'true' );
			document.body.appendChild( overlay );

			trigger.addEventListener( 'click', () => {
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
