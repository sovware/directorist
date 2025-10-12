/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/src/js/public/components/categoryLocation.js":
/*!*************************************************************!*\
  !*** ./assets/src/js/public/components/categoryLocation.js ***!
  \*************************************************************/
/***/ (function() {

window.addEventListener('load', function () {
  var $ = jQuery;

  /* Make sure the codes in this file runs only once, even if enqueued twice */
  if (typeof window.directorist_catloc_executed === 'undefined') {
    window.directorist_catloc_executed = true;
  } else {
    return;
  }

  /* Category card grid three width/height adjustment */
  var categoryCard = document.querySelectorAll('.directorist-categories__single--style-three');
  if (categoryCard) {
    categoryCard.forEach(function (elm) {
      var categoryCardWidth = elm.offsetWidth;
      elm.style.setProperty('--directorist-category-box-width', "".concat(categoryCardWidth, "px"));
    });
  }

  /* Taxonomy list dropdown */
  function categoryDropdown(selector, parent) {
    var categoryListToggle = document.querySelectorAll(selector);
    categoryListToggle.forEach(function (item) {
      item.addEventListener('click', function (e) {
        var categoryName = item.querySelector('.directorist-taxonomy-list__name');
        if (e.target !== categoryName) {
          e.preventDefault();
          this.classList.toggle('directorist-taxonomy-list__toggle--open');
        }
      });
    });
  }
  categoryDropdown('.directorist-taxonomy-list-one .directorist-taxonomy-list__toggle', '.directorist-taxonomy-list-one .directorist-taxonomy-list');
  categoryDropdown('.directorist-taxonomy-list-one .directorist-taxonomy-list__sub-item-toggle', '.directorist-taxonomy-list-one .directorist-taxonomy-list');

  // Taxonomy Ajax
  $(document).on('click', '.directorist-categories .directorist-pagination a', function (e) {
    taxonomyPagination(e, $(this), '.directorist-categories');
  });
  $(document).on('click', '.directorist-location .directorist-pagination a', function (e) {
    taxonomyPagination(e, $(this), '.directorist-location');
  });
  function taxonomyPagination(event, clickedElement, containerSelector) {
    event.preventDefault();
    var pageNumber = (clickedElement === null || clickedElement === void 0 ? void 0 : clickedElement.attr('data-page')) || 1;
    var container = clickedElement.closest(containerSelector);
    var containerAttributes = container ? $(container).data('attrs') : {};
    $.ajax({
      url: directorist.ajax_url,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'directorist_taxonomy_pagination',
        nonce: directorist.directorist_nonce,
        page: parseInt(pageNumber),
        attrs: containerAttributes
      },
      beforeSend: function beforeSend() {
        $(containerSelector).addClass('atbdp-form-fade');
      },
      success: function success(response) {
        var _tempContainer$queryS, _tempContainer$queryS2;
        if (!(response !== null && response !== void 0 && response.success)) {
          console.error('Failed to load taxonomy content');
          return;
        }
        var tempContainer = document.createElement('div');
        tempContainer.innerHTML = response.data.content;
        // Handle both category and location wrappers
        var taxonomyWrapper = document.querySelector('.taxonomy-category-wrapper');
        var locationWrapper = document.querySelector('.taxonomy-location-wrapper');
        var updatedCategoryContent = (_tempContainer$queryS = tempContainer.querySelector('.taxonomy-category-wrapper')) === null || _tempContainer$queryS === void 0 ? void 0 : _tempContainer$queryS.innerHTML;
        var updatedLocationContent = (_tempContainer$queryS2 = tempContainer.querySelector('.taxonomy-location-wrapper')) === null || _tempContainer$queryS2 === void 0 ? void 0 : _tempContainer$queryS2.innerHTML;
        if (taxonomyWrapper && updatedCategoryContent) {
          taxonomyWrapper.innerHTML = updatedCategoryContent;
        }
        if (locationWrapper && updatedLocationContent) {
          locationWrapper.innerHTML = updatedLocationContent;
        }
        if (!taxonomyWrapper && !locationWrapper) {
          console.error('Required elements not found in response');
          return;
        }
      },
      complete: function complete() {
        $(containerSelector).removeClass('atbdp-form-fade');
      }
    });
  }
});

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
/*!*************************************************!*\
  !*** ./assets/src/js/public/modules/widgets.js ***!
  \*************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_categoryLocation__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/categoryLocation */ "./assets/src/js/public/components/categoryLocation.js");
/* harmony import */ var _components_categoryLocation__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_components_categoryLocation__WEBPACK_IMPORTED_MODULE_0__);
/* window.addEventListener('load', () => {
    (function ($) {


    })(jQuery);
}); */


}();
/******/ })()
;
//# sourceMappingURL=widgets.js.map