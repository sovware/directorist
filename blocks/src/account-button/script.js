'use strict';

function authorDropdownActive() {
	const authorTriggers = document.querySelectorAll(
		'.directorist-account-block-logged-mode .avatar'
	);

	authorTriggers.forEach( ( authorTrigger ) => {
		const parentBlock = authorTrigger.closest(
			'.directorist-account-block-logged-mode'
		);
		let shade = parentBlock.querySelector(
			'.directorist-account-block-logged-mode__overlay'
		);
		const authorDropdown = parentBlock.querySelector(
			'.directorist-account-block-logged-mode__navigation'
		);

		function toggleAuthorDropdown() {
			if ( authorDropdown && shade ) {
				authorDropdown.classList.toggle( 'show' );
				shade.classList.toggle( 'show' );
			}
		}

		function removeDropdown() {
			if ( authorDropdown && shade ) {
				authorDropdown.classList.remove( 'show' );
				shade.classList.remove( 'show' );
			}
		}

		if ( ! shade ) {
			shade = document.createElement( 'div' );
			shade.className = 'directorist-account-block-logged-mode__overlay';
			parentBlock.appendChild( shade );
		}

		if ( authorTrigger && shade ) {
			authorTrigger.addEventListener( 'click', toggleAuthorDropdown );
			shade.addEventListener( 'click', removeDropdown );
		}
	} );
}

document.addEventListener( 'DOMContentLoaded', authorDropdownActive );

document.addEventListener( 'DOMContentLoaded', authorDropdownActive );

function login() {
	const elements = {
		clickBtns: document.querySelectorAll(
			'.directorist-account-block-logout-mode .wp-block-button__link'
		),

		loginInBtn: document.querySelector( '.directory_regi_btn button' ),
		popup: document.getElementById(
			'directorist-account-block-login-modal'
		),
		closeBtn: document.querySelector(
			'#directorist-account-block-login-modal .directorist-account-block-close'
		),
		signupBtn: document.querySelector( '.directory_login_btn button' ),
		signupPopup: document.getElementById(
			'directorist-account-block-register-modal'
		),
		signupCloseBtn: document.querySelector(
			'#directorist-account-block-register-modal .directorist-account-block-close'
		),
	};

	const showModal = ( modal ) => ( modal.style.display = 'block' );
	const hideModal = ( modal ) => ( modal.style.display = 'none' );
	const toggleModals = ( hide, show ) => {
		hideModal( hide );
		showModal( show );
	};

	elements.clickBtns.forEach( ( clickBtn, index ) => {
		clickBtn.addEventListener( 'click', () => showModal( elements.popup ) );
	} );

	if ( elements.closeBtn ) {
		elements.closeBtn.addEventListener( 'click', () =>
			hideModal( elements.popup )
		);
	}

	if ( elements.popup ) {
		elements.popup.addEventListener( 'click', ( event ) => {
			if ( event.target === elements.popup ) hideModal( elements.popup );
		} );
	}

	if ( elements.signupBtn ) {
		elements.signupBtn.addEventListener( 'click', ( event ) => {
			event.preventDefault();
			toggleModals( elements.popup, elements.signupPopup );
		} );
	}

	if ( elements.signupCloseBtn ) {
		elements.signupCloseBtn.addEventListener( 'click', () =>
			hideModal( elements.signupPopup )
		);
	}

	if ( elements.signupPopup ) {
		elements.signupPopup.addEventListener( 'click', ( event ) => {
			if ( event.target === elements.signupPopup )
				hideModal( elements.signupPopup );
		} );
	}

	if ( elements.loginInBtn ) {
		elements.loginInBtn.addEventListener( 'click', ( event ) => {
			event.preventDefault();
			toggleModals( elements.signupPopup, elements.popup );
		} );
	}
}

document.addEventListener( 'DOMContentLoaded', login );
