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
/******/ 	return __webpack_require__(__webpack_require__.s = 4);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/public/listing-swiper-slider.js":
/*!*******************************************************!*\
  !*** ./assets/src/js/public/listing-swiper-slider.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// All Listing Slider
(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    /* Check Slider Data */
    var checkData = function checkData(data, value) {
      return typeof data === 'undefined' ? value : data;
    };
    /* Swiper Slider Listing */


    var swiperCarouselListing = document.querySelectorAll('.directorist-swiper-listing');
    swiperCarouselListing.forEach(function (el, i) {
      var navBtnPrev = document.querySelectorAll('.directorist-swiper-listing .directorist-swiper__nav--prev-listing');
      var navBtnNext = document.querySelectorAll('.directorist-swiper-listing .directorist-swiper__nav--next-listing');
      var swiperPagination = document.querySelectorAll('.directorist-swiper-listing .directorist-swiper__pagination-listing');
      navBtnPrev.forEach(function (el, i) {
        el.classList.add("directorist-swiper__nav--prev-listing-".concat(i));
      });
      navBtnNext.forEach(function (el, i) {
        el.classList.add("directorist-swiper__nav--next-listing-".concat(i));
      });
      swiperPagination.forEach(function (el, i) {
        el.classList.add("directorist-swiper__pagination-listing-".concat(i));
      });
      el.classList.add("directorist-swiper-listing-".concat(i));
      var swiper = new Swiper(".directorist-swiper-listing-".concat(i), {
        slidesPerView: checkData(parseInt(el.dataset.swItems), 4),
        spaceBetween: checkData(parseInt(el.dataset.swMargin), 30),
        loop: checkData(el.dataset.swLoop, true),
        slidesPerGroup: checkData(parseInt(el.dataset.swPerslide), 1),
        speed: checkData(parseInt(el.dataset.swSpeed), 3000),
        autoplay: checkData(el.dataset.swAutoplay, {}),
        observer: true,
        observeParents: true,
        navigation: {
          nextEl: ".directorist-swiper__nav--next-listing-".concat(i),
          prevEl: ".directorist-swiper__nav--prev-listing-".concat(i)
        },
        pagination: {
          el: ".directorist-swiper__pagination-listing-".concat(i),
          type: 'bullets',
          clickable: true
        },
        breakpoints: checkData(el.dataset.swResponsive ? JSON.parse(el.dataset.swResponsive) : undefined, {})
      });
    });
    /* Swiper Slider Related Listing */

    var swiperCarouselRelated = document.querySelectorAll('.directorist-swiper-related');
    swiperCarouselRelated.forEach(function (el, i) {
      var navBtnPrev = document.querySelectorAll('.directorist-swiper-related .directorist-swiper__nav--prev-related');
      var navBtnNext = document.querySelectorAll('.directorist-swiper-related .directorist-swiper__nav--next-related');
      var swiperPagination = document.querySelectorAll('.directorist-swiper-related .directorist-swiper__pagination-related');
      navBtnPrev.forEach(function (el, i) {
        el.classList.add("directorist-swiper__nav--prev-related-".concat(i));
      });
      navBtnNext.forEach(function (el, i) {
        el.classList.add("directorist-swiper__nav--next-related-".concat(i));
      });
      swiperPagination.forEach(function (el, i) {
        el.classList.add("directorist-swiper__pagination-related-".concat(i));
      });
      el.classList.add("directorist-swiper-related-".concat(i));
      var swiper = new Swiper(".directorist-swiper-related-".concat(i), {
        slidesPerView: checkData(parseInt(el.dataset.swItems), 4),
        spaceBetween: checkData(parseInt(el.dataset.swMargin), 30),
        loop: checkData(el.dataset.swLoop, false),
        slidesPerGroup: checkData(parseInt(el.dataset.swPerslide), 1),
        speed: checkData(parseInt(el.dataset.swSpeed), 3000),
        autoplay: checkData(el.dataset.swAutoplay, {}),
        observer: true,
        observeParents: true,
        navigation: {
          nextEl: ".directorist-swiper__nav--next-related-".concat(i),
          prevEl: ".directorist-swiper__nav--prev-related-".concat(i)
        },
        pagination: {
          el: ".directorist-swiper__pagination-related-".concat(i),
          type: 'bullets',
          clickable: true
        },
        breakpoints: checkData(el.dataset.swResponsive ? JSON.parse(el.dataset.swResponsive) : undefined, {})
      });
    });
    /* Swiper Slider All in One */
    // let swiperCarousel = document.querySelectorAll('.directorist-swiper');
    // swiperCarousel.forEach(function (el, i) {
    //     let navBtnPrev = document.querySelectorAll('.directorist-swiper__nav--prev');
    //     let navBtnNext = document.querySelectorAll('.directorist-swiper__nav--next');
    //     let swiperPagination = document.querySelectorAll('.directorist-swiper__pagination');
    //     navBtnPrev.forEach((el, i) => {
    //         el.classList.add(`directorist-swiper__nav--prev-${i}`);
    //     });
    //     navBtnNext.forEach((el, i) => {
    //         el.classList.add(`directorist-swiper__nav--next-${i}`);
    //     });
    //     swiperPagination.forEach((el, i) => {
    //         el.classList.add(`directorist-swiper__pagination-${i}`);
    //     });
    //     el.classList.add(`directorist-swiper-${i}`);
    //     let swiper = new Swiper(`.directorist-swiper-${i}`, {
    //         slidesPerView: checkData(parseInt(el.dataset.swItems), 4),
    //         spaceBetween: checkData(parseInt(el.dataset.swMargin), 30),
    //         loop: checkData(el.dataset.swLoop, true),
    //         slidesPerGroup: checkData(parseInt(el.dataset.swPerslide), 1),
    //         speed: checkData(parseInt(el.dataset.swSpeed), 3000),
    //         autoplay: checkData(el.dataset.swAutoplay, {}),
    //         observer: true,
    //         observeParents: true,
    //         navigation: {
    //             nextEl: `.directorist-swiper__nav--next-${i}`,
    //             prevEl: `.directorist-swiper__nav--prev-${i}`,
    //         },
    //         pagination: {
    //             el: `.directorist-swiper__pagination-${i}`,
    //             type: 'bullets',
    //             clickable: true,
    //         },
    //         breakpoints: checkData(el.dataset.swResponsive ? JSON.parse(el.dataset.swResponsive) : undefined, {})
    //     });
    // });
  });
})(jQuery);

/***/ }),

/***/ 4:
/*!*************************************************************!*\
  !*** multi ./assets/src/js/public/listing-swiper-slider.js ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/public/listing-swiper-slider.js */"./assets/src/js/public/listing-swiper-slider.js");


/***/ })

/******/ });
//# sourceMappingURL=listing-swiper-slider.js.map