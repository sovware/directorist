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
/******/ 	return __webpack_require__(__webpack_require__.s = 18);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/components/login.js":
/*!*******************************************!*\
  !*** ./assets/src/js/components/login.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  // Perform AJAX login on form submit
  $('form#login').on('submit', function (e) {
    e.preventDefault();
    $('p.status').show().html(ajax_login_object.loading_message);
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: ajax_login_object.ajax_url,
      data: {
        'action': 'ajaxlogin',
        //calls wp_ajax_nopriv_ajaxlogin
        'username': $('form#login #username').val(),
        'password': $('form#login #password').val(),
        'rememberme': $('form#login #keep_signed_in').is(':checked') ? 1 : 0,
        'security': $('#security').val()
      },
      success: function success(data) {
        if ('nonce_faild' in data && data.nonce_faild) {
          $('p.status').html('<span class="status-success">' + data.message + '</span>');
        }

        if (data.loggedin == true) {
          $('p.status').html('<span class="status-success">' + data.message + '</span>');
          document.location.href = ajax_login_object.redirect_url;
        } else {
          $('p.status').html('<span class="status-failed">' + data.message + '</span>');
        }
      },
      error: function error(data) {
        if ('nonce_faild' in data && data.nonce_faild) {
          $('p.status').html('<span class="status-success">' + data.message + '</span>');
        }

        $('p.status').show().html('<span class="status-failed">' + ajax_login_object.login_error_message + '</span>');
      }
    });
    e.preventDefault();
  }); // Alert users to login (only if applicable)

  $('.atbdp-require-login').on('click', function (e) {
    e.preventDefault();
    alert(atbdp_public_data.login_alert_message);
  });
})(jQuery);

/***/ }),

/***/ 18:
/*!*************************************************!*\
  !*** multi ./assets/src/js/components/login.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/components/login.js */"./assets/src/js/components/login.js");


/***/ })

/******/ });
//# sourceMappingURL=login.js.map