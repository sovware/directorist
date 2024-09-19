'use strict';
document.addEventListener( 'DOMContentLoaded', function () {
	const searchButtons = document.querySelectorAll(
		'.directorist-search-popup-block__button'
	);
	let searchOverlay = document.querySelector(
		'.directorist-search-popup-block__overlay'
	);
	const searchPopup = document.querySelector(
		'.directorist-search-popup-block__popup'
	);
	const closeSearchButton = document.querySelector(
		'.directorist-search-popup-block__form-close'
	);
	const responsiveSearchButtons = document.querySelectorAll(
		'.directorist-search-popup-block .directorist-search-form-action__modal .directorist-modal-btn'
	);
	const responsiveSearchOverlays = document.querySelectorAll(
		'.directorist-search-popup-block .directorist-search-modal__overlay'
	);
	const closeResponsiveSearchButton = document.querySelector(
		'.directorist-search-popup-block .directorist-search-modal__contents__btn--close'
	);

	function toggleSearchPopup( e ) {
		e.preventDefault();
		searchPopup.classList.toggle( 'show' );
		searchOverlay.classList.toggle( 'show' );
		document.body.classList.toggle(
			'directorist-search-popup-block-hidden'
		);
	}

	function hideSearchPopup() {
		searchPopup.classList.remove( 'show' );
		searchOverlay.classList.remove( 'show' );
		document.body.classList.remove(
			'directorist-search-popup-block-hidden'
		);
	}

	function closeResponsiveSearch() {
		searchPopup.classList.toggle( 'responsive-true' );
		searchOverlay.classList.remove( 'show' );
		document.body.classList.remove(
			'directorist-search-popup-block-hidden'
		);
	}

	function resetResponsiveSearch() {
		searchOverlay.classList.add( 'show' );
		searchPopup.classList.remove( 'responsive-true' );
		document.body.classList.add( 'directorist-search-popup-block-hidden' );
	}

	if ( ! searchOverlay ) {
		searchOverlay = document.createElement( 'div' );
		searchOverlay.className = 'directorist-search-popup-block__overlay';
		document.body.appendChild( searchOverlay );
	}

	searchButtons.forEach( ( button ) =>
		button.addEventListener( 'click', toggleSearchPopup )
	);

	if ( searchOverlay && closeSearchButton ) {
		searchOverlay.addEventListener( 'click', hideSearchPopup );
		closeSearchButton.addEventListener( 'click', hideSearchPopup );
	}

	responsiveSearchButtons.forEach( ( button ) =>
		button.addEventListener( 'click', closeResponsiveSearch )
	);
	responsiveSearchOverlays.forEach( ( overlay ) =>
		overlay.addEventListener( 'click', resetResponsiveSearch )
	);
	if ( closeResponsiveSearchButton )
		closeResponsiveSearchButton.addEventListener(
			'click',
			resetResponsiveSearch
		);
} );
