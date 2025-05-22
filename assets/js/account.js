/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/src/js/global/components/modal.js":
/*!**************************************************!*\
  !*** ./assets/src/js/global/components/modal.js ***!
  \**************************************************/
/***/ (function() {

var $ = jQuery;
$(document).ready(function () {
  modalToggle();
});
function modalToggle() {
  $(".atbdp_recovery_pass").on("click", function (e) {
    e.preventDefault();
    $("#recover-pass-modal").slideToggle().show();
  });

  // Contact form [on modal closed]
  $('#atbdp-contact-modal').on('hidden.bs.modal', function (e) {
    $('#atbdp-contact-message').val('');
    $('#atbdp-contact-message-display').html('');
  });

  // Template Restructured
  // Modal
  var directoristModal = document.querySelector('.directorist-modal-js');
  $('body').on('click', '.directorist-btn-modal-js', function (e) {
    e.preventDefault();
    var data_target = $(this).attr("data-directorist_target");
    document.querySelector(".".concat(data_target)).classList.add('directorist-show');
  });
  $('body').on('click', '.directorist-modal-close-js', function (e) {
    e.preventDefault();
    $(this).closest('.directorist-modal-js').removeClass('directorist-show');
  });
  $(document).bind('click', function (e) {
    if (e.target == directoristModal) {
      directoristModal.classList.remove('directorist-show');
    }
  });
}

/***/ }),

/***/ "./assets/src/js/public/components/directoristAlert.js":
/*!*************************************************************!*\
  !*** ./assets/src/js/public/components/directoristAlert.js ***!
  \*************************************************************/
/***/ (function() {

;
(function ($) {
  // Make sure the codes in this file runs only once, even if enqueued twice
  if (typeof window.directorist_alert_executed === 'undefined') {
    window.directorist_alert_executed = true;
  } else {
    return;
  }
  window.addEventListener('load', function () {
    /* Directorist alert dismiss */
    var getUrl = window.location.href;
    var newUrl = getUrl.replace('notice=1', '');
    if ($('.directorist-alert__close') !== null) {
      $('.directorist-alert__close').each(function (i, e) {
        $(e).on('click', function (e) {
          e.preventDefault();
          history.pushState({}, null, newUrl);
          $(this).closest('.directorist-alert').remove();
        });
      });
    }
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/login.js":
/*!**************************************************!*\
  !*** ./assets/src/js/public/components/login.js ***!
  \**************************************************/
/***/ (function() {

;
(function ($) {
  // Make sure the codes in this file runs only once, even if enqueued twice
  if (typeof window.directorist_loginjs_executed === 'undefined') {
    window.directorist_loginjs_executed = true;
  } else {
    return;
  }

  // Trigger reset on form change
  $('.directorist-authentication__btn').on('click', function () {
    // Reset all forms with the specified class
    $('.directorist__authentication__signin').each(function () {
      this.reset(); // Reset the individual form
    });

    // Reset error and warning messages
    $('#directorist__authentication__login p.status').hide().empty();
  });
  window.addEventListener('load', function () {
    // Perform AJAX login on form submit
    $('form#directorist__authentication__login').on('submit', function (e) {
      e.preventDefault();
      var $this = $(this);
      var $button = $(this).find('.directorist-authentication__form__btn');
      $button.addClass('directorist-btn-loading'); // Added loading class

      $('#directorist__authentication__login p.status').show().html('<div class="directorist-alert directorist-alert-info"><span>' + directorist.loading_message + '</span></div>');
      var form_data = {
        'action': 'ajaxlogin',
        'username': $this.find('#username').val(),
        'password': $this.find('#password').val(),
        'rememberme': $this.find('#keep_signed_in').is(':checked') ? 1 : 0,
        'security': $this.find('#security').val()
      };
      $.ajax({
        type: 'POST',
        dataType: 'json',
        url: directorist.ajax_url,
        data: form_data,
        success: function success(data) {
          // Removed loading class
          setTimeout(function () {
            return $button.removeClass('directorist-btn-loading');
          }, 1000);
          if ('nonce_faild' in data && data.nonce_faild) {
            $('p.status').html('<div class="directorist-alert directorist-alert-success"><span>' + data.message + '</span></div>');
          }
          if (data.loggedin == true) {
            $('p.status').html('<div class="directorist-alert directorist-alert-success"><span>' + data.message + '</span></div>');
            document.location.href = directorist.redirect_url;
          } else {
            $('p.status').html('<div class="directorist-alert directorist-alert-danger"><span>' + data.message + '</span></div>');
          }
        },
        error: function error(data) {
          if ('nonce_faild' in data && data.nonce_faild) {
            $('p.status').html('<div class="directorist-alert directorist-alert-success"><span>' + data.message + '</span></div>');
          }
          $('p.status').show().html('<div class="directorist-alert directorist-alert-danger"><span>' + directorist.login_error_message + '</span></div>');
        }
      });
      e.preventDefault();
    });
    $('form#directorist__authentication__login .status').on('click', 'a', function (e) {
      e.preventDefault();
      if ($(this).attr('href') === '#atbdp_recovery_pass') {
        $("#recover-pass-modal").slideDown().show();
        window.scrollTo({
          top: $("#recover-pass-modal").offset().top - 100,
          behavior: 'smooth'
        });
      } else {
        location.href = $(this).attr('href');
      }
    });

    // Alert users to login (only if applicable)
    $('.atbdp-require-login, .directorist-action-report-not-loggedin').on('click', function (e) {
      e.preventDefault();
      alert(directorist.login_alert_message);
      return false;
    });

    // Remove URL params to avoid show message again and again
    var current_url = location.href;
    var url = new URL(current_url);
    url.searchParams.delete('registration_status');
    url.searchParams.delete('errors');
    // url.searchParams.delete('key');
    url.searchParams.delete('password_reset');
    url.searchParams.delete('confirm_mail');
    // url.searchParams.delete('user');
    url.searchParams.delete('verification');
    url.searchParams.delete('send_verification_email');
    window.history.pushState(null, null, url.toString());

    // Authentication Form Toggle
    $('body').on('click', '.directorist-authentication__btn, .directorist-authentication__toggle', function (e) {
      e.preventDefault();
      $('.directorist-login-wrapper').toggleClass('active');
      $('.directorist-registration-wrapper').toggleClass('active');
    });
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/register-form.js":
/*!**********************************************************!*\
  !*** ./assets/src/js/public/components/register-form.js ***!
  \**********************************************************/
/***/ (function() {

jQuery(function ($) {
  // Trigger reset on form change
  $('.directorist-authentication__btn').on('click', function () {
    // Reset the form values
    $('.directorist__authentication__signup').each(function () {
      this.reset(); // Reset the individual form
    });

    // Reset error and warning messages
    $('.directorist-alert ').hide().empty();
    $('.directorist-register-error').hide().empty();
  });
  $('.directorist__authentication__signup .directorist-authentication__form__btn').on('click', function (e) {
    e.preventDefault();
    $this = $(this);
    $this.addClass('directorist-btn-loading'); // Added loading class
    var form = $this.closest('.directorist__authentication__signup')[0];

    // Trigger native validation
    if (!form.checkValidity()) {
      form.reportValidity(); // Display browser-native warnings for invalid fields
      $this.removeClass('directorist-btn-loading'); // Removed loading class
      return; // Stop submission if validation fails
    }
    var formData = new FormData(form);
    formData.append('action', 'directorist_register_form');
    formData.append('params', JSON.stringify(directorist_signin_signup_params));
    $.ajax({
      url: directorist.ajaxurl,
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      cache: false
    }).done(function (_ref) {
      var data = _ref.data,
        success = _ref.success;
      // Removed loading class
      setTimeout(function () {
        return $this.removeClass('directorist-btn-loading');
      }, 1000);
      if (!success) {
        $('.directorist-register-error').empty().show().append(data.error);
        return;
      }
      $('.directorist-register-error').hide();
      if (data.message) {
        $('.directorist-register-error').empty().show().append(data.message).css({
          'color': '#009114',
          'background-color': '#d9efdc'
        });
      }
      if (data.redirect_url) {
        setTimeout(function () {
          return window.location.href = data.redirect_url;
        }, 500);
      }
    });
  });
});

/***/ }),

/***/ "./assets/src/js/public/components/reset-password.js":
/*!***********************************************************!*\
  !*** ./assets/src/js/public/components/reset-password.js ***!
  \***********************************************************/
/***/ (function() {

jQuery(function ($) {
  $('.directorist-ResetPassword').on('submit', function () {
    var form = $(this);
    if (form.find('#password_1').val() != form.find('#password_2').val()) {
      form.find('.password-not-match').show();
      return false;
    }
    form.find('.password-not-match').hide();
    return true;
  });
});

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be in strict mode.
!function() {
"use strict";
/*!*************************************************!*\
  !*** ./assets/src/js/public/modules/account.js ***!
  \*************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_directoristAlert__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/directoristAlert */ "./assets/src/js/public/components/directoristAlert.js");
/* harmony import */ var _components_directoristAlert__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_components_directoristAlert__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_login__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/login */ "./assets/src/js/public/components/login.js");
/* harmony import */ var _components_login__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_components_login__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _components_reset_password__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../components/reset-password */ "./assets/src/js/public/components/reset-password.js");
/* harmony import */ var _components_reset_password__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_components_reset_password__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _components_register_form__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../components/register-form */ "./assets/src/js/public/components/register-form.js");
/* harmony import */ var _components_register_form__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_components_register_form__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _global_components_modal__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../global/components/modal */ "./assets/src/js/global/components/modal.js");
/* harmony import */ var _global_components_modal__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_global_components_modal__WEBPACK_IMPORTED_MODULE_4__);
// General Components





}();
/******/ })()
;
//# sourceMappingURL=account.js.map