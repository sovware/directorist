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
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets/src/js/admin/setup-wizard.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/admin/setup-wizard.js":
/*!*********************************************!*\
  !*** ./assets/src/js/admin/setup-wizard.js ***!
  \*********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _scss_layout_admin_setup_wizard_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../scss/layout/admin/setup-wizard.scss */ \"./assets/src/scss/layout/admin/setup-wizard.scss\");\n/* harmony import */ var _scss_layout_admin_setup_wizard_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_scss_layout_admin_setup_wizard_scss__WEBPACK_IMPORTED_MODULE_0__);\n\n/* eslint-disable */\n\njQuery(document).ready(function ($) {\n  var import_dummy = $('#atbdp_dummy_form');\n  var position = 0;\n  var failed = 0;\n  var imported = 0;\n  var redirect_url = '';\n  $(import_dummy).on('submit', function (e) {\n    e.preventDefault();\n    $('.atbdp_dummy_body').fadeOut(300);\n    $('.atbdp-c-footer').fadeOut(300);\n    $('.directorist-importer__importing').fadeIn(300);\n    $(this).parent('.csv-fields').fadeOut(300);\n    $('.atbdp-mapping-step').removeClass('active').addClass('done');\n    $('.atbdp-progress-step').addClass('active');\n    var counter = 0;\n\n    var run_import = function run_import() {\n      var form_data = new FormData(); // ajax action\n\n      form_data.append('action', 'atbdp_dummy_data_import');\n      form_data.append('file', $('#dummy_csv_file').val());\n      form_data.append('limit', $('#atbdp-listings-to-import').val());\n      form_data.append('image', $('#atbdp-import-image').is(':checked') ? 1 : '');\n      form_data.append('delimiter', ',');\n      form_data.append('update_existing', '');\n      form_data.append('position', position);\n      form_data.append('wpnonce', $('input[name=\"_wpnonce\"]').val());\n      form_data.append('pre_mapped', true);\n      $.ajax({\n        method: 'POST',\n        processData: false,\n        contentType: false,\n        // async: false,\n        url: import_export_data.ajaxurl,\n        data: form_data,\n        success: function success(response) {\n          imported += response.imported;\n          failed += response.failed;\n          redirect_url = response.url;\n          $('.importer-details').html(\"Imported \".concat(response.next_position, \" out of \").concat(response.total));\n          $('.directorist-importer-progress').val(response.percentage);\n\n          if (response.percentage != '100' && counter < 150) {\n            position = response.next_position;\n            run_import();\n            counter++;\n          } else {\n            window.location = response.url;\n          }\n\n          $('input[name=\"save_step\"]').addClass(\"btn-hide\");\n          $('.directorist-importer-length').css('width', response.percentage + '%');\n        },\n        error: function error(response) {\n          window.location = redirect_url;\n        }\n      });\n    };\n\n    run_import();\n  }); //options\n\n  $('.atbdp-sw-gmap-key').hide();\n  $('#select_map').on('change', function (e) {\n    if ($(this).val() === 'google') {\n      $('.atbdp-sw-gmap-key').show();\n    } else {\n      $('.atbdp-sw-gmap-key').hide();\n    }\n  });\n\n  if ($('#select_map').val() === 'google') {\n    $('.atbdp-sw-gmap-key').show();\n  } else {\n    $('.atbdp-sw-gmap-key').hide();\n  }\n\n  $('.atbdp-sw-featured-listing').hide();\n  $('#enable_monetization').on('change', function () {\n    if ($(this).prop(\"checked\") === true) {\n      $('.atbdp-sw-featured-listing').show();\n    } else {\n      $('.atbdp-sw-featured-listing').hide();\n    }\n  });\n\n  if ($('#enable_monetization').prop(\"checked\") === true) {\n    $('.atbdp-sw-featured-listing').show();\n  } else {\n    $('.atbdp-sw-featured-listing').hide();\n  }\n\n  $('.atbdp-sw-listing-price').hide();\n  $('#enable_featured_listing').on('change', function () {\n    if ($(this).prop(\"checked\") === true) {\n      $('.atbdp-sw-listing-price').show();\n    } else {\n      $('.atbdp-sw-listing-price').hide();\n    }\n  });\n\n  if ($('#enable_monetization').prop(\"checked\") === true) {\n    $('.atbdp-sw-listing-price').show();\n  } else {\n    $('.atbdp-sw-listing-price').hide();\n  }\n  /* custom select */\n\n\n  $('#select_map').select2({\n    minimumResultsForSearch: -1\n  });\n  $('#atbdp-listings-to-import').select2({\n    minimumResultsForSearch: -1\n  });\n});\n\n//# sourceURL=webpack:///./assets/src/js/admin/setup-wizard.js?");

/***/ }),

/***/ "./assets/src/scss/layout/admin/setup-wizard.scss":
/*!********************************************************!*\
  !*** ./assets/src/scss/layout/admin/setup-wizard.scss ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./assets/src/scss/layout/admin/setup-wizard.scss?");

/***/ })

/******/ });