/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js ***!
  \*********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _arrayLikeToArray; }
/* harmony export */ });
function _arrayLikeToArray(r, a) {
  (null == a || a > r.length) && (a = r.length);
  for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e];
  return n;
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayWithHoles.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayWithHoles.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _arrayWithHoles; }
/* harmony export */ });
function _arrayWithHoles(r) {
  if (Array.isArray(r)) return r;
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js":
/*!*************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js ***!
  \*************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _iterableToArrayLimit; }
/* harmony export */ });
function _iterableToArrayLimit(r, l) {
  var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"];
  if (null != t) {
    var e,
      n,
      i,
      u,
      a = [],
      f = !0,
      o = !1;
    try {
      if (i = (t = t.call(r)).next, 0 === l) {
        if (Object(t) !== t) return;
        f = !1;
      } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0);
    } catch (r) {
      o = !0, n = r;
    } finally {
      try {
        if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return;
      } finally {
        if (o) throw n;
      }
    }
    return a;
  }
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/nonIterableRest.js":
/*!********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/nonIterableRest.js ***!
  \********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _nonIterableRest; }
/* harmony export */ });
function _nonIterableRest() {
  throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/slicedToArray.js ***!
  \******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _slicedToArray; }
/* harmony export */ });
/* harmony import */ var _arrayWithHoles_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayWithHoles.js */ "./node_modules/@babel/runtime/helpers/esm/arrayWithHoles.js");
/* harmony import */ var _iterableToArrayLimit_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./iterableToArrayLimit.js */ "./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js");
/* harmony import */ var _unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./unsupportedIterableToArray.js */ "./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js");
/* harmony import */ var _nonIterableRest_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./nonIterableRest.js */ "./node_modules/@babel/runtime/helpers/esm/nonIterableRest.js");




function _slicedToArray(r, e) {
  return (0,_arrayWithHoles_js__WEBPACK_IMPORTED_MODULE_0__["default"])(r) || (0,_iterableToArrayLimit_js__WEBPACK_IMPORTED_MODULE_1__["default"])(r, e) || (0,_unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__["default"])(r, e) || (0,_nonIterableRest_js__WEBPACK_IMPORTED_MODULE_3__["default"])();
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js":
/*!*******************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js ***!
  \*******************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _unsupportedIterableToArray; }
/* harmony export */ });
/* harmony import */ var _arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayLikeToArray.js */ "./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js");

function _unsupportedIterableToArray(r, a) {
  if (r) {
    if ("string" == typeof r) return (0,_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(r, a);
    var t = {}.toString.call(r).slice(8, -1);
    return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? (0,_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(r, a) : void 0;
  }
}


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
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
!function() {
/*!************************************************!*\
  !*** ./assets/src/js/public/listing-slider.js ***!
  \************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");

function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t.return || t.return(); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
/***
    All Listing Slider
***/
(function ($) {
  // All Listing Slider
  function allListingSlider() {
    /* Check Slider Data */
    var checkData = function checkData(data, value) {
      return typeof data === 'undefined' ? value : data;
    };

    /* Swiper Slider Listing */
    var swiperCarouselListing = document.querySelectorAll('.directorist-swiper-listing');
    swiperCarouselListing.forEach(function (el, i) {
      var navBtnPrev = document.querySelectorAll('.directorist-swiper-listing .directorist-swiper__nav--prev-listing');
      var navBtnNext = document.querySelectorAll('.directorist-swiper-listing .directorist-swiper__nav--next-listing');
      var swiperPagination = document.querySelectorAll('.directorist-swiper-listing .directorist-swiper__pagination--listing');
      navBtnPrev.forEach(function (el, i) {
        el.classList.add("directorist-swiper__nav--prev-listing-".concat(i));
      });
      navBtnNext.forEach(function (el, i) {
        el.classList.add("directorist-swiper__nav--next-listing-".concat(i));
      });
      swiperPagination.forEach(function (el, i) {
        el.classList.add("directorist-swiper__pagination--listing-".concat(i));
      });
      el.classList.add("directorist-swiper-listing-".concat(i));
      var swiperConfig = {
        slidesPerView: checkData(parseInt(el.dataset.swItems), 4),
        spaceBetween: checkData(parseInt(el.dataset.swMargin), 30),
        loop: checkData(el.dataset.swLoop, true),
        slidesPerGroup: checkData(parseInt(el.dataset.swPerslide), 1),
        speed: checkData(parseInt(el.dataset.swSpeed), 300),
        navigation: {
          nextEl: ".directorist-swiper__nav--next-listing-".concat(i),
          prevEl: ".directorist-swiper__nav--prev-listing-".concat(i)
        },
        pagination: {
          el: ".directorist-swiper__pagination--listing-".concat(i),
          type: 'bullets',
          clickable: true
        },
        breakpoints: checkData(el.dataset.swResponsive ? JSON.parse(el.dataset.swResponsive) : undefined, {})
      };

      // Conditionally add autoplay property
      var enableAutoplay = checkData(el.dataset.swAutoplay, 'false');

      // Conditionally add autoplay property
      if (enableAutoplay === "true") {
        swiperConfig.autoplay = {
          delay: checkData(parseInt(el.dataset.swSpeed), 500),
          disableOnInteraction: false
        };
      }
      var swiper = new Swiper(".directorist-swiper-listing-".concat(i), swiperConfig);
    });

    /* Swiper Slider Related Listing */
    var swiperCarouselRelated = document.querySelectorAll('.directorist-swiper-related-listing');
    swiperCarouselRelated.forEach(function (el, i) {
      var navBtnPrev = document.querySelectorAll('.directorist-swiper-related-listing .directorist-swiper__nav--prev-related');
      var navBtnNext = document.querySelectorAll('.directorist-swiper-related-listing .directorist-swiper__nav--next-related');
      var swiperPagination = document.querySelectorAll('.directorist-swiper-related-listing .directorist-swiper__pagination--related');
      navBtnPrev.forEach(function (el, i) {
        el.classList.add("directorist-swiper__nav--prev-related-".concat(i));
      });
      navBtnNext.forEach(function (el, i) {
        el.classList.add("directorist-swiper__nav--next-related-".concat(i));
      });
      swiperPagination.forEach(function (el, i) {
        el.classList.add("directorist-swiper__pagination--related-".concat(i));
      });
      el.classList.add("directorist-swiper-related-listing-".concat(i));
      var swiperRelatedConfig = {
        slidesPerView: checkData(parseInt(el.dataset.swItems), 4),
        spaceBetween: checkData(parseInt(el.dataset.swMargin), 30),
        loop: checkData(el.dataset.swLoop, false),
        slidesPerGroup: checkData(parseInt(el.dataset.swPerslide), 1),
        navigation: {
          nextEl: ".directorist-swiper__nav--next-related-".concat(i),
          prevEl: ".directorist-swiper__nav--prev-related-".concat(i)
        },
        pagination: {
          el: ".directorist-swiper__pagination--related-".concat(i),
          type: 'bullets',
          clickable: true
        },
        breakpoints: checkData(el.dataset.swResponsive ? JSON.parse(el.dataset.swResponsive) : undefined, {})
      };
      var enableRelatedAutoplay = checkData(el.dataset.swAutoplay, 'false');

      // Conditionally add autoplay property
      if (enableRelatedAutoplay === "true") {
        swiperRelatedConfig.autoplay = {
          delay: checkData(parseInt(el.dataset.swSpeed), 500),
          disableOnInteraction: false,
          pauseOnMouseEnter: true
        };
      }
      var swiper = new Swiper(".directorist-swiper-related-listing-".concat(i), swiperRelatedConfig);

      // Destroy Swiper Slider When Slider Image Are Less Than Minimum Required Image
      function destroySwiperSlider() {
        var windowScreen = screen.width;
        var breakpoints = JSON.parse(el.dataset.swResponsive);
        var breakpointKeys = Object.keys(breakpoints);
        var legalBreakpointKeys = breakpointKeys.filter(function (breakpointKey) {
          return breakpointKey <= windowScreen;
        });
        var currentBreakpointKey = legalBreakpointKeys.reduce(function (prev, acc) {
          return Math.abs(acc - windowScreen) < Math.abs(prev - windowScreen) ? acc : prev;
        });
        var breakpointValues = Object.entries(breakpoints);
        var currentBreakpoint = breakpointValues.filter(function (_ref) {
          var _ref2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_ref, 1),
            key = _ref2[0];
          return key == currentBreakpointKey;
        });
        var sliderItemsCount = document.querySelectorAll(".directorist-swiper-related-listing-".concat(i, " .directorist-swiper__pagination--related-").concat(i, " .swiper-pagination-bullet"));
        if (sliderItemsCount.length == '1') {
          swiper.loopDestroy();
          swiper.update();
          var relatedListingSlider = document.querySelector('.directorist-swiper-related-listing');
          relatedListingSlider.classList.add('slider-has-one-item');
        }
        currentBreakpoint[0].forEach(function (elm, ind) {
          var relatedListingSlider = document.querySelector('.directorist-swiper-related-listing');
          if (swiper.loopedSlides < elm.slidesPerView) {
            swiper.loopDestroy();
            swiper.update();
            relatedListingSlider.classList.add('slider-has-less-items');
          } else {
            if (relatedListingSlider && relatedListingSlider.classList.contains('slider-has-less-items')) {
              relatedListingSlider.classList.remove('slider-has-less-items');
            }
          }
        });
      }
      window.addEventListener('resize', function () {
        destroySwiperSlider();
      });
      destroySwiperSlider();
    });

    /* Swiper Slider Single Listing */
    var singleListingSlider = document.querySelectorAll('.directorist-single-listing-slider-wrap');
    singleListingSlider.forEach(function (el, i) {
      // Get Data Attribute
      var dataWidth = el.getAttribute('data-width');
      var dataHeight = el.getAttribute('data-height');
      var dataRTL = el.getAttribute('data-rtl');
      var dataBackgroundColor = el.getAttribute('data-background-color');
      var dataBackgroundSize = el.getAttribute('data-background-size');
      var dataBackgroundBlur = el.getAttribute('data-blur-background');
      var dataShowThumbnails = el.getAttribute('data-show-thumbnails');
      var dataThumbnailsBackground = el.getAttribute('data-thumbnail-background-color');

      // Find Sliders
      var swiperCarouselSingleListingThumb = el.querySelector('.directorist-single-listing-slider-thumb');
      var swiperCarouselSingleListing = el.querySelector('.directorist-single-listing-slider');

      // Single Listing Thumb Init
      var swiperSingleListingThumb = new Swiper(swiperCarouselSingleListingThumb, {
        slidesPerView: 6,
        spaceBetween: 10,
        loop: false,
        freeMode: true,
        navigation: {
          nextEl: ".directorist-swiper__nav--next-single-listing-thumb",
          prevEl: ".directorist-swiper__nav--prev-single-listing-thumb"
        },
        pagination: {
          el: ".directorist-swiper__pagination--single-listing-thumb",
          type: 'bullets',
          clickable: true
        },
        breakpoints: {
          0: {
            slidesPerView: 1,
            spaceBetween: 0
          },
          480: {
            slidesPerView: 2
          },
          767: {
            slidesPerView: 3
          },
          1200: {
            slidesPerView: 4
          },
          1440: {
            slidesPerView: 5
          },
          1600: {
            slidesPerView: 6
          }
        }
      });

      // Single Listing Slider Config
      var swiperSingleListingConfig = {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        slidesPerGroup: 1,
        observer: true,
        observeParents: true,
        navigation: {
          nextEl: ".directorist-swiper__nav--next-single-listing",
          prevEl: ".directorist-swiper__nav--prev-single-listing"
        },
        pagination: {
          el: ".directorist-swiper__pagination--single-listing",
          type: 'bullets',
          clickable: true
        }
      };

      // Single Slider Thumb Config
      if (swiperCarouselSingleListingThumb) {
        swiperSingleListingConfig.thumbs = {
          swiper: swiperSingleListingThumb
        };
      }

      // Initialize Swiper
      var swiperSingleListing = new Swiper(swiperCarouselSingleListing, swiperSingleListingConfig);

      // Function to update blurred background
      var updateBlurredBackground = function updateBlurredBackground() {
        // Check if the blurred background element exists
        var blurredBackground = swiperCarouselSingleListing.querySelector('.blurred-background');

        // If it doesn't exist, create it
        if (!blurredBackground) {
          blurredBackground = document.createElement('div'); // Create a new div
          blurredBackground.classList.add('blurred-background'); // Add the class
          swiperCarouselSingleListing.appendChild(blurredBackground); // Append it to the section
        }

        // Get the active slide image
        var activeSlide = swiperCarouselSingleListing.querySelector('.swiper-slide-active img');
        if (activeSlide) {
          var activeImageSrc = activeSlide.src; // Get the source of the active image
          swiperCarouselSingleListing.style.backgroundColor = 'transparent'; // Remove background color
          blurredBackground.style.backgroundImage = "url(".concat(activeImageSrc, ")"); // Set as background image
          blurredBackground.style.backgroundSize = 'cover'; // Ensure it covers the div
          blurredBackground.style.filter = 'blur(10px)'; // Apply blur
          blurredBackground.style.position = 'absolute'; // Position it behind other content
          blurredBackground.style.top = '0';
          blurredBackground.style.left = '0';
          blurredBackground.style.right = '0';
          blurredBackground.style.bottom = '0';
          blurredBackground.style.transform = 'scale(1.5)';
        }
      };

      // Attach the slideChangeTransitionEnd event listener
      if (dataBackgroundBlur === '1') {
        swiperSingleListing.on('slideChangeTransitionEnd', updateBlurredBackground); // Use slideChangeTransitionEnd here
      }

      // Loop Destroy on Single Slider Item
      var sliderItemsCount = swiperCarouselSingleListing.querySelectorAll('.directorist-swiper__pagination .swiper-pagination-bullet');
      var swiperListingThumb = swiperCarouselSingleListing.parentElement.querySelector('.directorist-single-listing-slider-thumb');
      if (sliderItemsCount.length <= '1') {
        swiperSingleListing.loopDestroy();
        swiperCarouselSingleListing.classList.add('slider-has-one-item');
        if (swiperListingThumb) {
          swiperListingThumb.style.display = 'none';
        }
      }

      // Add Styles
      if (swiperCarouselSingleListing) {
        swiperCarouselSingleListing.dir = dataRTL !== '0' ? 'rtl' : 'ltr';
        swiperCarouselSingleListing.style.width = dataWidth ? dataWidth + 'px' : '100%';
        swiperCarouselSingleListing.style.height = dataHeight ? dataHeight + 'px' : 'auto';
        swiperCarouselSingleListing.style.backgroundSize = dataBackgroundSize ? dataBackgroundSize : '';

        // Initial setup
        if (dataBackgroundSize === "contain") {
          swiperCarouselSingleListing.style.backgroundColor = dataBackgroundColor ? dataBackgroundColor : 'transparent';

          // Call the update function for initial setup if blur is active
          if (dataBackgroundBlur === '1') {
            updateBlurredBackground(); // Set initial blurred background
          } else {
            // If blur is not active, remove the blurred background if it exists
            var blurredBackground = swiperCarouselSingleListing.querySelector('.blurred-background');
            if (blurredBackground) {
              swiperCarouselSingleListing.removeChild(blurredBackground);
            }
          }
        }
      }
      if (swiperCarouselSingleListingThumb) {
        // swiperCarouselSingleListingThumb.style.display = dataShowThumbnails == '0' ? 'none' : '';
        swiperCarouselSingleListingThumb.style.width = dataWidth ? dataWidth + 'px' : '100%';
        swiperCarouselSingleListingThumb.style.backgroundColor = dataThumbnailsBackground ? dataThumbnailsBackground : 'transparent';
      }
    });
  }

  // Slider Call on Page Load
  window.addEventListener('load', function () {
    allListingSlider();
    $('body').on('click', '.directorist-viewas__item, .directorist-type-nav__link, .directorist-pagination .page-numbers, .directorist-instant-search .directorist-search-field__btn--clear, .directorist-instant-search .directorist-btn-reset-js', function (e) {
      setTimeout(function () {
        if ($('.directorist-archive-items .directorist-swiper-listing')) {
          allListingSlider();
        }
      }, 1000);
    });
    $('body').on('input keyup change', '.directorist-archive-contents form', function (e) {
      if (e.target.classList.contains('directorist-location-js')) {
        sliderObserver();
      }
      setTimeout(function () {
        if ($('.directorist-archive-items .directorist-swiper-listing')) {
          allListingSlider();
        }
      }, 1000);
    });
  });

  // Mutation Observer on Range Slider
  function sliderObserver() {
    var rangeSliders = document.querySelectorAll('.directorist-custom-range-slider__value input');
    rangeSliders.forEach(function (rangeSlider) {
      if (rangeSlider) {
        var timeout;
        var observerCallback = function observerCallback(mutationList, observer) {
          var _iterator = _createForOfIteratorHelper(mutationList),
            _step;
          try {
            for (_iterator.s(); !(_step = _iterator.n()).done;) {
              var mutation = _step.value;
              if (mutation.attributeName == 'value') {
                clearTimeout(timeout);
                timeout = setTimeout(function () {
                  allListingSlider();
                }, 1000);
              }
            }
          } catch (err) {
            _iterator.e(err);
          } finally {
            _iterator.f();
          }
        };
        var observer = new MutationObserver(observerCallback);
        observer.observe(rangeSlider, {
          attributes: true,
          childList: true,
          subtree: true
        });
      }
    });
  }

  /* Slider Call on Elementor EditMode */
  $(window).on('elementor/frontend/init', function () {
    setTimeout(function () {
      if ($('body').hasClass('elementor-editor-active')) {
        allListingSlider();
      }
      if ($('body').hasClass('elementor-editor-active')) {
        allListingSlider();
      }
    }, 3000);
  });
  $('body').on('click', function (e) {
    if ($('body').hasClass('elementor-editor-active') && e.target.nodeName !== 'A' && e.target.nodeName !== 'BUTTON') {
      allListingSlider();
    }
  });
})(jQuery);
}();
/******/ })()
;
//# sourceMappingURL=listing-slider.js.map