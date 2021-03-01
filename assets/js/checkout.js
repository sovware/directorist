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
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/checkout.js":
/*!***********************************!*\
  !*** ./assets/src/js/checkout.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function ($) {\n  // Update checkout pricing on product item change\n  var checkout_price_item = $('.atbdp-checkout-price-item');\n  checkout_price_item.on('change', function () {\n    var checkout_net_price_area = $('#atbdp_checkout_total_amount');\n    var checkout_net_hidden_price_area = $('#atbdp_checkout_total_amount_hidden');\n    var pricing_statement = get_pricing_statement(checkout_price_item);\n    checkout_net_price_area.html(get_currency_format(pricing_statement.total_price));\n    checkout_net_hidden_price_area.val(pricing_statement.total_price);\n    update_payment_methods(pricing_statement);\n  }); // get_pricing_statement\n\n  function get_pricing_statement(price_item_elm) {\n    var total_price = 0;\n    var total_product = 0;\n    price_item_elm.each(function (index) {\n      var price_item = price_item_elm[index];\n      var price = price_item.value;\n      price = isNaN(price_item.value) ? 0 : Number(price);\n\n      if ($(price_item).is(':checked')) {\n        total_price = total_price + price;\n        total_product++;\n      }\n    });\n    return {\n      total_product: total_product,\n      total_price: total_price\n    };\n  } // update_payment_methods\n\n\n  function update_payment_methods(pricing_statement) {\n    if (!pricing_statement.total_product) {\n      $('#directorist_payment_gateways, #atbdp_checkout_submit_btn').hide();\n      return;\n    }\n\n    if (pricing_statement.total_price > 0) {\n      $('#directorist_payment_gateways').show();\n      $('#atbdp_checkout_submit_btn').val(atbdp_checkout.payNow).show();\n      $('#atbdp_checkout_submit_btn_label').val(atbdp_checkout.payNow);\n    } else {\n      $('#directorist_payment_gateways').hide();\n      $('#atbdp_checkout_submit_btn').val(atbdp_checkout.completeSubmission).show();\n      $('#atbdp_checkout_submit_btn_label').val(atbdp_checkout.completeSubmission);\n    }\n  } // Helpers\n  // --------------------\n  // get_currency_format\n\n\n  function get_currency_format(number) {\n    number = number.toFixed(2);\n    number = number_with_commas(number);\n    return number;\n  } // number_with_commas\n\n\n  function number_with_commas(number) {\n    return number.toString().replace(/\\B(?=(\\d{3})+(?!\\d))/g, \",\");\n  }\n})(jQuery);\n\n//# sourceURL=webpack:///./assets/src/js/checkout.js?");

/***/ }),

/***/ 2:
/*!*****************************************!*\
  !*** multi ./assets/src/js/checkout.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("module.exports = __webpack_require__(/*! ./assets/src/js/checkout.js */\"./assets/src/js/checkout.js\");\n\n\n//# sourceURL=webpack:///multi_./assets/src/js/checkout.js?");

/***/ })

/******/ });