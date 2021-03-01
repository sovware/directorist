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
/******/ 	return __webpack_require__(__webpack_require__.s = 7);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/modules/atmodal.js":
/*!******************************************!*\
  !*** ./assets/src/js/modules/atmodal.js ***!
  \******************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _scss_layout_public_atmodal_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../../scss/layout/public/atmodal.scss */ \"./assets/src/scss/layout/public/atmodal.scss\");\n/* harmony import */ var _scss_layout_public_atmodal_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_scss_layout_public_atmodal_scss__WEBPACK_IMPORTED_MODULE_0__);\n/*\n\t\tName:  ATModal\n\t\tVersion: 1.0\n\t\tAuthor: AazzTech\n\t\tAuthor URI: http://aazztech.com\n\t*/\n\n/* disable-eslint */\n\n\nvar aazztechModal1 = function aazztechModal1(selector) {\n  var element = document.querySelectorAll(selector);\n  element.forEach(function (el, index) {\n    el.style.display = 'none';\n    document.addEventListener('click', function (event) {\n      var current_elm = event.target;\n      var target_id = current_elm.getAttribute('data-target');\n      var el_id = el.getAttribute('id');\n\n      if (target_id === el_id) {\n        event.preventDefault();\n        el.style.display = 'block';\n        document.body.classList.add('atm-open');\n        setTimeout(function () {\n          el.classList.add('atm-show');\n        }, 100);\n        document.querySelector('html').style.overflow = 'hidden';\n      }\n    }, false);\n    el.querySelector('a.at-modal-close').addEventListener('click', function (e) {\n      e.preventDefault();\n      el.classList.remove('atm-show');\n      document.body.classList.remove('atm-open');\n      setTimeout(function () {\n        el.style.display = 'none';\n      }, 100);\n      document.querySelector('html').removeAttribute('style');\n    });\n    el.addEventListener('click', function (e) {\n      if (e.target.closest('.atm-contents-inner')) return;\n      el.classList.remove('atm-show');\n      document.body.classList.remove('atm-open');\n      setTimeout(function () {\n        el.style.display = 'none';\n      }, 100);\n      document.querySelector('html').removeAttribute('style');\n    });\n  });\n};\n\nfunction initModal() {\n  aazztechModal1('#dcl-claim-modal, #atbdp-report-abuse-modal, #atpp-plan-change-modal, #pyn-plan-change-modal');\n}\n\nwindow.addEventListener('load', function () {\n  setTimeout(function () {\n    initModal();\n  }, 500);\n});\n\n//# sourceURL=webpack:///./assets/src/js/modules/atmodal.js?");

/***/ }),

/***/ "./assets/src/scss/layout/public/atmodal.scss":
/*!****************************************************!*\
  !*** ./assets/src/scss/layout/public/atmodal.scss ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./assets/src/scss/layout/public/atmodal.scss?");

/***/ }),

/***/ 7:
/*!************************************************!*\
  !*** multi ./assets/src/js/modules/atmodal.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("module.exports = __webpack_require__(/*! ./assets/src/js/modules/atmodal.js */\"./assets/src/js/modules/atmodal.js\");\n\n\n//# sourceURL=webpack:///multi_./assets/src/js/modules/atmodal.js?");

/***/ })

/******/ });