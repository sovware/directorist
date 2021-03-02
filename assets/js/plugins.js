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
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets/src/js/admin/plugins.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/admin/plugins.js":
/*!****************************************!*\
  !*** ./assets/src/js/admin/plugins.js ***!
  \****************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _scss_layout_admin_plugins_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../scss/layout/admin/plugins.scss */ \"./assets/src/scss/layout/admin/plugins.scss\");\n/* harmony import */ var _scss_layout_admin_plugins_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_scss_layout_admin_plugins_scss__WEBPACK_IMPORTED_MODULE_0__);\n\njQuery(document).ready(function ($) {\n  var update = $('#directorist-update');\n  var main_div = $('[data-slug=\"directorist\"]');\n  var extensions_area = update.length ? update : main_div;\n  extensions_area.after('<tr class=\"directorist-extensions\"></tr>');\n  $('.directorist-extensions').append($('<td colspan=\"4\"><div class=\"ext-all-wrapper\"><input type=\"checkbox\" class=\"select_all\"> All Extensions<table class=\"atbdp_extensions\"><tbody class=\"de-list\"></tbody></table></div></td>'));\n  var tbody = $('.directorist-extensions').find('.de-list');\n  var extWrapper = $('.directorist-extensions').find('.ext-all-wrapper');\n  $(extWrapper).append('<div class=\"ext-more\"><a href=\"\" class=\"ext-more-link\">Click to view directorist all extensions</a></div>');\n  var moreLink = $('.directorist-extensions').find('.ext-more-link');\n  $(moreLink).hide();\n  $(tbody).append($('#the-list tr[data-slug^=\"directorist-\"]'));\n  $(\"body\").on('click', '.select_all', function (e) {\n    var table = $(e.target).closest('table');\n    $('td input:checkbox', table).prop('checked', this.checked);\n  });\n\n  if ($(extWrapper).innerHeight() > 250) {\n    $(extWrapper).addClass('ext-height-fix');\n    $(moreLink).show();\n    $(extWrapper).css('padding-bottom', '60px');\n  }\n\n  $(moreLink).on('click', function (e) {\n    var _this = this;\n\n    e.preventDefault();\n\n    if ($(extWrapper).hasClass('ext-height-fix')) {\n      $(extWrapper).animate({\n        height: '100%'\n      }, 'fast').removeClass('ext-height-fix');\n      $(this).html('Click to collapse');\n    } else {\n      $(extWrapper).animate({\n        height: '250px'\n      }, 'fast').addClass('ext-height-fix');\n      setTimeout(function () {\n        $(_this).html('Click to view directorist all extensions');\n      }, 1000);\n    }\n  });\n\n  if ($(tbody).html() === '') {\n    $('.directorist-extensions').hide();\n  }\n});\n\n//# sourceURL=webpack:///./assets/src/js/admin/plugins.js?");

/***/ }),

/***/ "./assets/src/scss/layout/admin/plugins.scss":
/*!***************************************************!*\
  !*** ./assets/src/scss/layout/admin/plugins.scss ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./assets/src/scss/layout/admin/plugins.scss?");

/***/ })

/******/ });