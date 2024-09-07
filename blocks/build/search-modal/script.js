/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/*!************************************!*\
  !*** ./src/search-modal/script.js ***!
  \************************************/


document.addEventListener('DOMContentLoaded', function () {
  var searchButtons = document.querySelectorAll('.directorist-search-popup-block__button');
  var searchOverlay = document.querySelector('.directorist-search-popup-block__overlay');
  var searchPopup = document.querySelector('.directorist-search-popup-block__popup');
  var closeSearchButton = document.querySelector('.directorist-search-popup-block__form-close');
  var responsiveSearchButtons = document.querySelectorAll('.directorist-search-popup-block .directorist-search-form-action__modal .directorist-modal-btn');
  var responsiveSearchOverlays = document.querySelectorAll('.directorist-search-popup-block .directorist-search-modal__overlay');
  var closeResponsiveSearchButton = document.querySelector('.directorist-search-popup-block .directorist-search-modal__contents__btn--close');
  function toggleSearchPopup(e) {
    e.preventDefault();
    searchPopup.classList.toggle('show');
    searchOverlay.classList.toggle('show');
    document.body.classList.toggle('directorist-search-popup-block-hidden');
  }
  function hideSearchPopup() {
    searchPopup.classList.remove('show');
    searchOverlay.classList.remove('show');
    document.body.classList.remove('directorist-search-popup-block-hidden');
  }
  function closeResponsiveSearch() {
    searchPopup.classList.toggle('responsive-true');
    searchOverlay.classList.remove('show');
    document.body.classList.remove('directorist-search-popup-block-hidden');
  }
  function resetResponsiveSearch() {
    searchOverlay.classList.add('show');
    searchPopup.classList.remove('responsive-true');
    document.body.classList.add('directorist-search-popup-block-hidden');
  }
  if (!searchOverlay) {
    searchOverlay = document.createElement('div');
    searchOverlay.className = 'directorist-search-popup-block__overlay';
    document.body.appendChild(searchOverlay);
  }
  searchButtons.forEach(function (button) {
    return button.addEventListener('click', toggleSearchPopup);
  });
  if (searchOverlay && closeSearchButton) {
    searchOverlay.addEventListener('click', hideSearchPopup);
    closeSearchButton.addEventListener('click', hideSearchPopup);
  }
  responsiveSearchButtons.forEach(function (button) {
    return button.addEventListener('click', closeResponsiveSearch);
  });
  responsiveSearchOverlays.forEach(function (overlay) {
    return overlay.addEventListener('click', resetResponsiveSearch);
  });
  if (closeResponsiveSearchButton) closeResponsiveSearchButton.addEventListener('click', resetResponsiveSearch);
});
/******/ })()
;
//# sourceMappingURL=script.js.map