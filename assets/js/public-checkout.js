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
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/public/checkout.js":
/*!******************************************!*\
  !*** ./assets/src/js/public/checkout.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function ($) {
  // Update checkout pricing on product item change
  var checkout_price_item = $('.atbdp-checkout-price-item');
  checkout_price_item.on('change', function () {
    var checkout_net_price_area = $('#atbdp_checkout_total_amount');
    var checkout_net_hidden_price_area = $('#atbdp_checkout_total_amount_hidden');
    var pricing_statement = get_pricing_statement(checkout_price_item);
    checkout_net_price_area.html(get_currency_format(pricing_statement.total_price));
    checkout_net_hidden_price_area.val(pricing_statement.total_price);
    update_payment_methods(pricing_statement);
  }); // get_pricing_statement

  function get_pricing_statement(price_item_elm) {
    var total_price = 0;
    var total_product = 0;
    price_item_elm.each(function (index) {
      var price_item = price_item_elm[index];
      var price = price_item.value;
      price = isNaN(price_item.value) ? 0 : Number(price);

      if ($(price_item).is(':checked')) {
        total_price = total_price + price;
        total_product++;
      }
    });
    return {
      total_product: total_product,
      total_price: total_price
    };
  } // update_payment_methods


  function update_payment_methods(pricing_statement) {
    if (!pricing_statement.total_product) {
      $('#directorist_payment_gateways, #atbdp_checkout_submit_btn').hide();
      return;
    }

    if (pricing_statement.total_price > 0) {
      $('#directorist_payment_gateways').show();
      $('#atbdp_checkout_submit_btn').val(atbdp_checkout.payNow).show();
      $('#atbdp_checkout_submit_btn_label').val(atbdp_checkout.payNow);
    } else {
      $('#directorist_payment_gateways').hide();
      $('#atbdp_checkout_submit_btn').val(atbdp_checkout.completeSubmission).show();
      $('#atbdp_checkout_submit_btn_label').val(atbdp_checkout.completeSubmission);
    }
  } // Helpers
  // --------------------
  // get_currency_format


  function get_currency_format(number) {
    number = number.toFixed(2);
    number = number_with_commas(number);
    return number;
  } // number_with_commas


  function number_with_commas(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }
})(jQuery);

/***/ }),

/***/ 1:
/*!************************************************!*\
  !*** multi ./assets/src/js/public/checkout.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/public/checkout.js */"./assets/src/js/public/checkout.js");


/***/ })

/******/ });
//# sourceMappingURL=public-checkout.js.map