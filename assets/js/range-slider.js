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
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/public/range-slider.js":
/*!**********************************************!*\
  !*** ./assets/src/js/public/range-slider.js ***!
  \**********************************************/
/*! exports provided: directorist_range_slider, directorist_callingSlider */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "directorist_range_slider", function() { return directorist_range_slider; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "directorist_callingSlider", function() { return directorist_callingSlider; });
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/typeof.js");
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__);


/* range slider */
var directorist_range_slider = function directorist_range_slider(selector, obj) {
  var isDraging = false,
      max = obj.maxValue,
      min = obj.minValue,
      down = 'mousedown',
      up = 'mouseup',
      move = 'mousemove',
      div = "\n            <div class=\"directorist-range-slider1\" draggable=\"true\"></div>\n            <input type='hidden' class=\"directorist-range-slider-minimum\" name=\"minimum\" value=".concat(min, " />\n            <div class=\"directorist-range-slider-child\"></div>\n\t\t");
  var touch = ("ontouchstart" in document.documentElement);

  if (touch) {
    down = 'touchstart';
    up = 'touchend';
    move = 'touchmove';
  } //RTL


  var isRTL = directorist.rtl === 'true';
  var direction;

  if (isRTL) {
    direction = 'right';
  } else {
    direction = 'left';
  }

  var slider = document.querySelectorAll(selector);
  slider.forEach(function (id, index) {
    var sliderDataMin = min;
    var sliderDataUnit = id.getAttribute('data-slider-unit');
    id.setAttribute('style', "max-width: ".concat(obj.maxWidth, "; border: ").concat(obj.barBorder, "; width: 100%; height: 4px; background: ").concat(obj.barColor, "; position: relative; border-radius: 2px;"));
    id.innerHTML = div;
    var slide1 = id.querySelector('.directorist-range-slider1'),
        width = id.clientWidth;
    slide1.style.background = obj.pointerColor;
    slide1.style.border = obj.pointerBorder;
    id.closest('.directorist-range-slider-wrap').querySelector('.directorist-range-slider-current-value').innerHTML = "<span>".concat(min, "</span> ").concat(sliderDataUnit);
    var x = null,
        count = 0,
        slid1_val = 0,
        slid1_val2 = sliderDataMin,
        count2 = width;

    if (window.outerWidth < 600) {
      id.classList.add('m-device');
      slide1.classList.add('m-device2');
    }

    slide1.addEventListener(down, function (event) {
      if (!touch) {
        event.preventDefault();
        event.stopPropagation();
      }

      x = event.clientX;

      if (touch) {
        x = event.touches[0].clientX;
      }

      isDraging = true;
      event.target.classList.add('directorist-rs-active');
    });
    document.body.addEventListener(up, function (event2) {
      if (!touch) {
        event2.preventDefault();
        event2.stopPropagation();
      }

      isDraging = false;
      slid1_val2 = slid1_val;
      slide1.classList.remove('directorist-rs-active');
    });
    slide1.classList.add('directorist-rs-active1');
    count = width / max;

    if (slide1.classList.contains('directorist-rs-active1')) {
      var onLoadValue = count * min;
      id.closest('.directorist-range-slider-wrap').querySelector('.directorist-range-slider-current-value span').innerHTML = sliderDataMin;
      id.querySelector('.directorist-range-slider-minimum').value = sliderDataMin;
      id.querySelector('.directorist-rs-active1').style[direction] = onLoadValue <= 0 ? 0 : onLoadValue + 'px';
      id.querySelector('.directorist-range-slider-child').style.width = onLoadValue <= 0 ? 0 : onLoadValue + 'px';
    }

    document.body.addEventListener(move, function (e) {
      if (isDraging) {
        count = !isRTL ? e.clientX + slid1_val2 * width / max - x : -e.clientX + slid1_val2 * width / max + x;

        if (touch) {
          count = !isRTL ? e.touches[0].clientX + slid1_val2 * width / max - x : -e.touches[0].clientX + slid1_val2 * width / max + x;
        }

        if (count < 0) {
          count = 0;
        } else if (count > count2 - 18) {
          count = count2 - 18;
        }
      }

      if (slide1.classList.contains('directorist-rs-active')) {
        slid1_val = Math.floor(max / (width - 18) * count);
        id.closest('.directorist-range-slider-wrap').querySelector('.directorist-range-slider-current-value').innerHTML = "<span>".concat(slid1_val, "</span> ").concat(sliderDataUnit);
        id.querySelector('.directorist-range-slider-minimum').value = slid1_val;
        id.closest('.directorist-range-slider-wrap').querySelector('.directorist-range-slider-value').value = slid1_val;
        id.querySelector('.directorist-rs-active').style[direction] = count + 'px';
        id.querySelector('.directorist-range-slider-child').style.width = count + 'px';
      }
    });
  });
};
function directorist_callingSlider() {
  var minValueWrapper = document.querySelector('.directorist-range-slider-value');
  var default_args = {
    maxValue: 1000,
    minValue: parseInt(minValueWrapper && minValueWrapper.value),
    maxWidth: '100%',
    barColor: '#d4d5d9',
    barBorder: 'none',
    pointerColor: '#fff',
    pointerBorder: '4px solid #444752'
  };
  var config = directorist.slider_config && _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default()(directorist.slider_config) === 'object' ? Object.assign(default_args, directorist.slider_config) : default_args;
  directorist_range_slider('.directorist-range-slider', config);
}
window.addEventListener("load", function () {
  directorist_callingSlider();
});

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/typeof.js":
/*!*******************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/typeof.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(obj) {
  "@babel/helpers - typeof";

  return (module.exports = _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) {
    return typeof obj;
  } : function (obj) {
    return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
  }, module.exports.__esModule = true, module.exports["default"] = module.exports), _typeof(obj);
}

module.exports = _typeof, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ 3:
/*!****************************************************!*\
  !*** multi ./assets/src/js/public/range-slider.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/public/range-slider.js */"./assets/src/js/public/range-slider.js");


/***/ })

/******/ });
//# sourceMappingURL=range-slider.js.map