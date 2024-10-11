/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/*!**************************************!*\
  !*** ./src/account-button/script.js ***!
  \**************************************/


function authorDropdownActive() {
  var authorTriggers = document.querySelectorAll('.directorist-account-block-logged-mode .avatar');
  var requiredElements = Object.values(authorTriggers);
  if (requiredElements.some(function (element) {
    return !element;
  })) {
    return;
  }
  authorTriggers.forEach(function (authorTrigger) {
    var parentBlock = authorTrigger.closest('.directorist-account-block-logged-mode');
    var shade = parentBlock.querySelector('.directorist-account-block-logged-mode__overlay');
    var authorDropdown = parentBlock.querySelector('.directorist-account-block-logged-mode__navigation');
    function toggleAuthorDropdown() {
      if (authorDropdown && shade) {
        authorDropdown.classList.toggle('show');
        shade.classList.toggle('show');
      }
    }
    function removeDropdown() {
      if (authorDropdown && shade) {
        authorDropdown.classList.remove('show');
        shade.classList.remove('show');
      }
    }
    if (!shade) {
      shade = document.createElement('div');
      shade.className = 'directorist-account-block-logged-mode__overlay';
      parentBlock.appendChild(shade);
    }
    if (authorTrigger && shade) {
      authorTrigger.addEventListener('click', toggleAuthorDropdown);
      shade.addEventListener('click', removeDropdown);
    }
  });
}
document.addEventListener('DOMContentLoaded', authorDropdownActive);
function login() {
  var elements = {
    clickBtns: document.querySelectorAll('.directorist-account-block-logout-mode .wp-block-button__link'),
    loginInBtn: document.querySelector('.directory_regi_btn button'),
    popup: document.getElementById('directorist-account-block-login-modal'),
    closeBtn: document.querySelector('#directorist-account-block-login-modal .directorist-account-block-close'),
    signupBtn: document.querySelector('.directory_login_btn button'),
    signupPopup: document.getElementById('directorist-account-block-register-modal'),
    signupCloseBtn: document.querySelector('#directorist-account-block-register-modal .directorist-account-block-close')
  };

  // Check if all required elements exist
  var requiredElements = Object.values(elements);
  if (requiredElements.some(function (element) {
    return !element;
  })) {
    return;
  }
  var showModal = function showModal(modal) {
    if (modal) {
      modal.style.display = 'block';
    }
  };
  var hideModal = function hideModal(modal) {
    if (modal) {
      modal.style.display = 'none';
    }
  };
  var toggleModals = function toggleModals(hide, show) {
    hideModal(hide);
    showModal(show);
  };
  elements.clickBtns.forEach(function (clickBtn, index) {
    clickBtn.addEventListener('click', function () {
      return showModal(elements.popup);
    });
  });
  if (elements.closeBtn) {
    elements.closeBtn.addEventListener('click', function () {
      return hideModal(elements.popup);
    });
  }
  if (elements.popup) {
    elements.popup.addEventListener('click', function (event) {
      if (event.target === elements.popup) hideModal(elements.popup);
    });
  }
  if (elements.signupBtn) {
    elements.signupBtn.addEventListener('click', function (event) {
      event.preventDefault();
      toggleModals(elements.popup, elements.signupPopup);
    });
  }
  if (elements.signupCloseBtn) {
    elements.signupCloseBtn.addEventListener('click', function () {
      return hideModal(elements.signupPopup);
    });
  }
  if (elements.signupPopup) {
    elements.signupPopup.addEventListener('click', function (event) {
      if (event.target === elements.signupPopup) hideModal(elements.signupPopup);
    });
  }
  if (elements.loginInBtn) {
    elements.loginInBtn.addEventListener('click', function (event) {
      event.preventDefault();
      toggleModals(elements.signupPopup, elements.popup);
    });
  }
}
document.addEventListener('DOMContentLoaded', login);
/******/ })()
;
//# sourceMappingURL=script.js.map