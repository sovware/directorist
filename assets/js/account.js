/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 10);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/global/components/modal.js":
/*!**************************************************!*\
  !*** ./assets/src/js/global/components/modal.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;
(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    // Recovery Password Modal
    $("#recover-pass-modal").hide();
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
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/directoristAlert.js":
/*!*************************************************************!*\
  !*** ./assets/src/js/public/components/directoristAlert.js ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;
(function ($) {
  // Make sure the codes in this file runs only once, even if enqueued twice
  if (typeof window.directorist_alert_executed === 'undefined') {
    window.directorist_alert_executed = true;
  } else {
    return;
  }
  window.addEventListener('DOMContentLoaded', function () {
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
/*! no static exports found */
/***/ (function(module, exports) {

;
(function ($) {
  // Make sure the codes in this file runs only once, even if enqueued twice
  if (typeof window.directorist_loginjs_executed === 'undefined') {
    window.directorist_loginjs_executed = true;
  } else {
    return;
  }
  window.addEventListener('DOMContentLoaded', function () {
    // Perform AJAX login on form submit
    $('form#login').on('submit', function (e) {
      e.preventDefault();
      var $this = $(this);
      $('p.status').show().html('<div class="directorist-alert directorist-alert-info"><span>' + directorist.loading_message + '</span></div>');
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
    $('form#login .status').on('click', 'a', function (e) {
      e.preventDefault();
      if ($(this).attr('href') === '#atbdp_recovery_pass') {
        $("#recover-pass-modal").slideDown().show();
        window.scrollTo({
          top: $("#recover-pass-modal").offset().top - 100,
          behavior: 'smooth'
        });
      } else {
        location.href = href;
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
    $('body').on('click', '.directorist-authentication__btn', function (e) {
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
/*! no static exports found */
/***/ (function(module, exports) {

jQuery(function ($) {
  $('.directorist__authentication__signup').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('action', 'directorist_register_form');
    $.ajax({
      url: directorist.ajaxurl,
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      cache: false,
      success: function success(response) {
        console.log(response);
        if (response.success) {
          $('.directorist-register-error').hide();
          if (response.data.redirect_url) {
            if (response.data.redirect_message) {
              $('.directorist-register-error').empty().show().append(response.data.redirect_message).css({
                'color': '#009114',
                'background-color': '#d9efdc'
              });
            }
            setTimeout(function () {
              window.location.href = response.data.redirect_url;
            }, 500);
          }
        } else {
          $('.directorist-register-error').empty().show().append(response.data);
        }
      }
    });
  });
});

/***/ }),

/***/ "./assets/src/js/public/components/reset-password.js":
/*!***********************************************************!*\
  !*** ./assets/src/js/public/components/reset-password.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

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

/***/ }),

/***/ "./assets/src/js/public/modules/account.js":
/*!*************************************************!*\
  !*** ./assets/src/js/public/modules/account.js ***!
  \*************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
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






/***/ }),

/***/ 10:
/*!*******************************************************!*\
  !*** multi ./assets/src/js/public/modules/account.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/public/modules/account.js */"./assets/src/js/public/modules/account.js");


/***/ })

/******/ });
//# sourceMappingURL=account.js.map