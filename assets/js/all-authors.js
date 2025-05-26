/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/src/js/public/components/author.js":
/*!***************************************************!*\
  !*** ./assets/src/js/public/components/author.js ***!
  \***************************************************/
/***/ (function() {

// author sorting
(function ($) {
  window.addEventListener('load', function () {
    /* Masonry layout */
    function authorsMasonry() {
      var authorsCard = $('.directorist-authors__cards');
      $(authorsCard).each(function (id, elm) {
        var authorsCardRow = $(elm).find('.directorist-row');
        var authorMasonryInit = $(authorsCardRow).imagesLoaded(function () {
          $(authorMasonryInit).masonry({
            percentPosition: true,
            horizontalOrder: true
          });
        });
      });
    }
    authorsMasonry();

    /* alphabet data value */
    var alphabetValue;

    /* authors nav default active item */
    if ($('.directorist-authors__nav').length) {
      $('.directorist-authors__nav ul li:first-child').addClass('active');
    }
    /* authors nav item */
    $('body').on('click', '.directorist-alphabet', function (e) {
      e.preventDefault();
      var _this = $(this);
      var alphabet = $(this).attr("data-alphabet");
      $('body').addClass('atbdp-form-fade');
      $.ajax({
        method: 'POST',
        url: directorist.ajaxurl,
        data: {
          action: 'directorist_author_alpha_sorting',
          _nonce: $(this).attr("data-nonce"),
          alphabet: $(this).attr("data-alphabet")
        },
        success: function success(response) {
          $('#directorist-all-authors').empty().append(response);
          $('body').removeClass('atbdp-form-fade');
          $('.' + alphabet).parent().addClass('active');
          alphabetValue = $(_this).attr('data-alphabet');
          authorsMasonry();
        },
        error: function error(_error) {
          //console.log(error);
        }
      });
    });

    /* authors pagination */
    $('body').on('click', '.directorist-authors-pagination a', function (e) {
      e.preventDefault();
      var paged = $(this).text();
      if ($(this).hasClass('prev')) {
        paged = parseInt($('.directorist-authors-pagination .current').text()) - 1;
      }
      if ($(this).hasClass('next')) {
        paged = parseInt($('.directorist-authors-pagination .current').text()) + 1;
      }
      $('body').addClass('atbdp-form-fade');
      var getAlphabetValue = alphabetValue;
      $.ajax({
        method: 'POST',
        url: directorist.ajaxurl,
        data: {
          action: 'directorist_author_pagination',
          paged: paged
        },
        success: function success(response) {
          $('body').removeClass('atbdp-form-fade');
          $('#directorist-all-authors').empty().append(response);
          authorsMasonry();
          if (document.querySelector('.' + getAlphabetValue) !== null) {
            document.querySelector('.' + getAlphabetValue).closest('li').classList.add('active');
          } else if ($('.directorist-authors__nav').length) {
            $('.directorist-authors__nav ul li:first-child').addClass('active');
          }
          ;
        },
        error: function error(_error2) {
          //console.log(error);
        }
      });
    });
  });
})(jQuery);

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
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
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
// This entry needs to be wrapped in an IIFE because it needs to be in strict mode.
!function() {
"use strict";
/*!*****************************************************!*\
  !*** ./assets/src/js/public/modules/all-authors.js ***!
  \*****************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_author__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/author */ "./assets/src/js/public/components/author.js");
/* harmony import */ var _components_author__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_components_author__WEBPACK_IMPORTED_MODULE_0__);
//General Components

}();
/******/ })()
;
//# sourceMappingURL=all-authors.js.map