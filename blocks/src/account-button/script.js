'use strict';

document.addEventListener( 'DOMContentLoaded', function () {
	let activeDialog = null;

	function focusableElements( container ) {
		return Array.from(
			container.querySelectorAll(
				'a[href], button:not([disabled]), input:not([disabled]):not([type="hidden"]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
			)
		).filter( ( element ) => element.getClientRects().length );
	}

	function closeDialog( restoreFocus = true ) {
		if ( ! activeDialog ) {
			return;
		}

		const { dialog, trigger } = activeDialog;
		dialog.style.display = 'none';
		dialog.setAttribute( 'aria-hidden', 'true' );
		trigger.setAttribute( 'aria-expanded', 'false' );
		document.body.classList.remove( 'directorist-account-modal-open' );
		activeDialog = null;

		if ( restoreFocus && document.contains( trigger ) ) {
			trigger.focus( { preventScroll: true } );
		}
	}

	function openDialog( dialog, trigger ) {
		if ( activeDialog ) {
			closeDialog( false );
		}

		activeDialog = { dialog, trigger };
		dialog.style.display = 'block';
		dialog.setAttribute( 'aria-hidden', 'false' );
		trigger.setAttribute( 'aria-expanded', 'true' );
		document.body.classList.add( 'directorist-account-modal-open' );
		window.requestAnimationFrame( () => {
			focusableElements( dialog )[ 0 ]?.focus( { preventScroll: true } );
		} );
	}

	document
		.querySelectorAll( '.directorist-account-block-logout-mode' )
		.forEach( ( block ) => {
			const trigger = block.querySelector(
				'.directorist-account-block__trigger'
			);
			const dialog = document.getElementById(
				trigger?.getAttribute( 'aria-controls' )
			);
			const closeButton = dialog?.querySelector(
				'.directorist-account-block-close'
			);

			if ( ! trigger || ! dialog || ! closeButton ) {
				return;
			}

			trigger.addEventListener( 'click', () =>
				openDialog( dialog, trigger )
			);
			closeButton.addEventListener( 'click', () => closeDialog() );
			dialog.addEventListener( 'click', ( event ) => {
				if ( event.target === dialog ) {
					closeDialog();
				}
			} );
		} );

	document
		.querySelectorAll( '.directorist-account-block-logged-mode' )
		.forEach( ( block ) => {
			const trigger = block.querySelector(
				'.directorist-account-block__trigger[aria-controls]'
			);
			const navigation = block.querySelector(
				'.directorist-account-block-logged-mode__navigation'
			);

			if ( ! trigger || ! navigation ) {
				return;
			}

			let shade = block.querySelector(
				'.directorist-account-block-logged-mode__overlay'
			);

			if ( ! shade ) {
				shade = document.createElement( 'div' );
				shade.className =
					'directorist-account-block-logged-mode__overlay';
				block.appendChild( shade );
			}

			function closeNavigation() {
				navigation.classList.remove( 'show' );
				navigation.setAttribute( 'aria-hidden', 'true' );
				shade.classList.remove( 'show' );
				trigger.setAttribute( 'aria-expanded', 'false' );
			}

			trigger.addEventListener( 'click', () => {
				const willOpen = ! navigation.classList.contains( 'show' );
				navigation.classList.toggle( 'show', willOpen );
				navigation.setAttribute( 'aria-hidden', String( ! willOpen ) );
				shade.classList.toggle( 'show', willOpen );
				trigger.setAttribute( 'aria-expanded', String( willOpen ) );
			} );
			shade.addEventListener( 'click', closeNavigation );
			block.addEventListener( 'keydown', ( event ) => {
				if (
					'Escape' === event.key &&
					navigation.classList.contains( 'show' )
				) {
					closeNavigation();
					trigger.focus();
				}
			} );
		} );

	document.addEventListener( 'keydown', ( event ) => {
		if ( ! activeDialog ) {
			return;
		}

		if ( 'Escape' === event.key ) {
			event.preventDefault();
			closeDialog();
			return;
		}

		if ( 'Tab' !== event.key ) {
			return;
		}

		const focusable = focusableElements( activeDialog.dialog );
		const first = focusable[ 0 ];
		const last = focusable[ focusable.length - 1 ];

		if (
			event.shiftKey &&
			activeDialog.dialog.ownerDocument.activeElement === first
		) {
			event.preventDefault();
			last?.focus();
		} else if (
			! event.shiftKey &&
			activeDialog.dialog.ownerDocument.activeElement === last
		) {
			event.preventDefault();
			first?.focus();
		}
	} );
} );
