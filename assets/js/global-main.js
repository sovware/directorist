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
/******/ 	return __webpack_require__(__webpack_require__.s = 6);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/global/components/init-lazy-check.js":
/*!************************************************************!*\
  !*** ./assets/src/js/global/components/init-lazy-check.js ***!
  \************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/typeof.js");
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _lib_lazy_checks_lazy_check__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../lib/lazy-checks/lazy-check */ "./assets/src/js/lib/lazy-checks/lazy-check.js");



function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default()(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }



window.onload = function () {
  var defaultArgs = {
    modalTitle: "Tags",
    containerClass: "directorist-tags-lazy-checks",
    showMoreToggleClass: "directorist-link",
    ajax: {
      url: atbdp_public_data.rest_url + 'directorist/v1/listings/tags',
      maxInitItems: 4,
      getPreselectedItemsID: function getPreselectedItemsID() {
        var urlParams = new URLSearchParams(window.location.search);
        var in_tag = urlParams.getAll('in_tag[]');
        return in_tag instanceof Array ? in_tag : [];
      },
      data: function data(params) {
        if (params.isLoadingPreselectedItems && params.preselectedItemsID.length) {
          params.page = 1;
          params.include = params.preselectedItemsID;
        } else if (!params.isLoadingPreselectedItems && params.preselectedItemsID.length) {
          params.page = params.page || 1;
          params.per_page = 50;
          params.exclude = params.preselectedItemsID;
        } else {
          params.page = params.page || 1;
          params.per_page = 50;
        }

        ;
        return params;
      },
      processResults: function processResults(response) {
        var currentPage = response.params.page;
        var totalPage = parseInt(response.headers.get('X-WP-TotalPages'));
        response.hasNextPage = currentPage >= totalPage ? false : true;
        return response;
      },
      template: function template(item) {
        var urParams = new URLSearchParams(window.location.search);
        var in_tag = urParams.getAll('in_tag[]');
        var checked = in_tag instanceof Array && in_tag.includes("".concat(item.id)) ? 'checked' : '';
        return "\n        <div class=\"lazy-check-item-wrap directorist-checkbox directorist-checkbox-primary\">\n          <input type=\"checkbox\" name=\"in_tag[]\" value=\"".concat(item.id, "\" id=\"").concat(item.randomID, "\"").concat(checked, ">\n          <label for=\"").concat(item.randomID, "\" class=\"directorist-checkbox__label\">").concat(item.name, "</label>\n        </div>\n        ");
      }
    }
  };
  var filterArgs = window.directoristLazyTagArgs;
  var args = defaultArgs;

  if (filterArgs && _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_1___default()(filterArgs) == 'object') {
    args = _objectSpread(_objectSpread({}, defaultArgs), filterArgs);
    args.ajax = defaultArgs.ajax;

    if (filterArgs.ajax && _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_1___default()(filterArgs.ajax) == 'object') {
      args.ajax = _objectSpread(_objectSpread({}, defaultArgs.ajax), filterArgs.ajax);
    }
  }

  new _lib_lazy_checks_lazy_check__WEBPACK_IMPORTED_MODULE_2__["default"](args).init();
};

/***/ }),

/***/ "./assets/src/js/global/components/modal.js":
/*!**************************************************!*\
  !*** ./assets/src/js/global/components/modal.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  // Recovery Password Modal
  $("#recover-pass-modal").hide();
  $(".atbdp_recovery_pass").on("click", function (e) {
    e.preventDefault();
    $("#recover-pass-modal").slideToggle().show();
  }); // Contact form [on modal closed]

  $('#atbdp-contact-modal').on('hidden.bs.modal', function (e) {
    $('#atbdp-contact-message').val('');
    $('#atbdp-contact-message-display').html('');
  }); // Template Restructured
  // Modal

  var directoristModal = document.querySelector('.directorist-modal-js');
  $('body').on('click', '.directorist-btn-modal-js', function (e) {
    e.preventDefault();
    var data_target = $(this).attr("data-directorist_target");
    document.querySelector(".".concat(data_target)).classList.add('directorist-show');
  });
  $('body').on('click', '.directorist-modal-close-js', function (e) {
    e.preventDefault();
    $(this).closest('.directorist-modal-js').removeClass('directorist-show');
  });
  $(document).bind('click', function (e) {
    if (e.target == directoristModal) {
      directoristModal.classList.remove('directorist-show');
    }
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/global/components/select2-custom-control.js":
/*!*******************************************************************!*\
  !*** ./assets/src/js/global/components/select2-custom-control.js ***!
  \*******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

var $ = jQuery;
window.addEventListener('load', function () {
  // Add custom dropdown toggle button
  selec2_add_custom_dropdown_toggle_button(); // Add custom close button where needed

  selec2_add_custom_close_button_if_needed(); // Add custom close button if field contains value on change

  $('.select2-hidden-accessible').on('change', function (e) {
    var value = $(this).children("option:selected").val();

    if (!value) {
      return;
    }

    selec2_add_custom_close_button($(this));
  });
});

function selec2_add_custom_dropdown_toggle_button() {
  // Remove Default
  $('.select2-selection__arrow').css({
    'display': 'none'
  });
  var addon_container = selec2_get_addon_container(); // Add Dropdown Toggle Button

  addon_container.append('<span class="directorist-select2-addon directorist-select2-dropdown-toggle"><i class="fas fa-chevron-down"></i></span>');
  var selec2_custom_dropdown = addon_container.find('.directorist-select2-dropdown-toggle'); // Toggle --is-open class
  // -----------------------------

  $('.select2-hidden-accessible').on('select2:open', function (e) {
    var dropdown_btn = $(this).next().find('.directorist-select2-dropdown-toggle');
    dropdown_btn.addClass('--is-open');
  });
  $('.select2-hidden-accessible').on('select2:close', function (e) {
    var dropdown_btn = $(this).next().find('.directorist-select2-dropdown-toggle');
    dropdown_btn.removeClass('--is-open');
  }); // Toggle Dropdown
  // -----------------------------

  selec2_custom_dropdown.on('click', function (e) {
    var isOpen = $(this).hasClass('--is-open');
    var field = $(this).closest(".select2-container").siblings('select:enabled');

    if (isOpen) {
      field.select2('close');
    } else {
      field.select2('open');
    }
  }); // Adjust space for addons

  selec2_adjust_space_for_addons();
}

function selec2_add_custom_close_button_if_needed() {
  var select2_fields = $('.select2-hidden-accessible');

  if (!select2_fields && !select2_fields.length) {
    return;
  }

  var _iterator = _createForOfIteratorHelper(select2_fields),
      _step;

  try {
    for (_iterator.s(); !(_step = _iterator.n()).done;) {
      var field = _step.value;
      var value = $(field).children("option:selected").val();

      if (!value) {
        continue;
      }

      selec2_add_custom_close_button(field);
    }
  } catch (err) {
    _iterator.e(err);
  } finally {
    _iterator.f();
  }
}

function selec2_add_custom_close_button(field) {
  // Remove Default
  $('.select2-selection__clear').css({
    'display': 'none'
  });
  var addon_container = selec2_get_addon_container(field);

  if (!(addon_container && addon_container.length)) {
    return;
  } // Remove if already exists


  addon_container.find('.directorist-select2-dropdown-close').remove(); // Add

  addon_container.prepend('<span class="directorist-select2-addon directorist-select2-dropdown-close"><i class="fas fa-times"></i></span>');
  var selec2_custom_close = addon_container.find('.directorist-select2-dropdown-close');
  selec2_custom_close.on('click', function (e) {
    var field = $(this).closest(".select2-container").siblings('select:enabled');
    field.val(null).trigger('change');
    addon_container.find('.directorist-select2-dropdown-close').remove();
    selec2_adjust_space_for_addons();
  }); // Adjust space for addons

  selec2_adjust_space_for_addons();
}

function selec2_remove_custom_close_button(field) {
  var addon_container = selec2_get_addon_container(field);

  if (!(addon_container && addon_container.length)) {
    return;
  } // Remove


  addon_container.find('.directorist-select2-dropdown-close').remove(); // Adjust space for addons

  selec2_adjust_space_for_addons();
}

function selec2_get_addon_container(field) {
  if (field && !field.length) {
    return;
  }

  var container = field ? $(field).next('.select2-container') : $('.select2-container');
  container = $(container).find('.directorist-select2-addons-area');

  if (!container.length) {
    $('.select2-container').append('<span class="directorist-select2-addons-area"></span>');
    container = $('.select2-container').find('.directorist-select2-addons-area');
  }

  return container;
}

function selec2_adjust_space_for_addons() {
  var container = $('.select2-container').find('.directorist-select2-addons-area');

  if (!container.length) {
    return;
  }

  var width = container.outerWidth();
  $('.select2-container').find('.select2-selection__rendered').css({
    'padding-right': width + 'px'
  });
}

/***/ }),

/***/ "./assets/src/js/global/components/setup-select2.js":
/*!**********************************************************!*\
  !*** ./assets/src/js/global/components/setup-select2.js ***!
  \**********************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./../../lib/helper */ "./assets/src/js/lib/helper.js");


function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default()(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }


var $ = jQuery;
window.addEventListener('load', initSelect2);
document.body.addEventListener('directorist-search-form-nav-tab-reloaded', initSelect2);
document.body.addEventListener('directorist-reload-select2-fields', initSelect2); // Init Static Select 2 Fields

function initSelect2() {
  var select_fields = [{
    elm: $('.directorist-select').find('select')
  }, {
    elm: $('#directorist-select-js')
  }, {
    elm: $('#directorist-search-category-js')
  }, {
    elm: $('#directorist-select-st-s-js')
  }, {
    elm: $('#directorist-select-sn-s-js')
  }, {
    elm: $('#directorist-select-mn-e-js')
  }, {
    elm: $('#directorist-select-tu-e-js')
  }, {
    elm: $('#directorist-select-wd-s-js')
  }, {
    elm: $('#directorist-select-wd-e-js')
  }, {
    elm: $('#directorist-select-th-e-js')
  }, {
    elm: $('#directorist-select-fr-s-js')
  }, {
    elm: $('#directorist-select-fr-e-js')
  }, // { elm: $('#directorist-location-select') },
  // { elm: $('#directorist-category-select') },
  {
    elm: $('.select-basic')
  }, {
    elm: $('#loc-type')
  }, {
    elm: $('.bdas-location-search')
  }, // { elm: $('.directorist-location-select') },
  {
    elm: $('#at_biz_dir-category')
  }, {
    elm: $('#cat-type')
  }, {
    elm: $('.bdas-category-search')
  } // { elm: $('.directorist-category-select') },
  ];
  select_fields.forEach(function (field) {
    Object(_lib_helper__WEBPACK_IMPORTED_MODULE_1__["convertToSelect2"])(field);
  });
  var lazy_load_taxonomy_fields = atbdp_public_data.lazy_load_taxonomy_fields;

  if (lazy_load_taxonomy_fields) {
    // Init Select2 Ajax Fields
    initSelect2AjaxFields();
  }
} // Init Select2 Ajax Fields


function initSelect2AjaxFields() {
  var rest_base_url = "".concat(atbdp_public_data.rest_url, "directorist/v1"); // Init Select2 Ajax Category Field

  initSelect2AjaxTaxonomy({
    selector: $('.directorist-search-category').find('select'),
    url: "".concat(rest_base_url, "/listings/categories")
  }); // Init Select2 Ajax Category Field

  initSelect2AjaxTaxonomy({
    selector: $('.directorist-search-location').find('select'),
    url: "".concat(rest_base_url, "/listings/locations")
  });
} // initSelect2AjaxTaxonomy


function initSelect2AjaxTaxonomy(args) {
  var defaultArgs = {
    selector: '',
    url: '',
    perPage: 10
  };
  args = _objectSpread(_objectSpread({}, defaultArgs), args);
  var currentPage = 1;
  $(args.selector).select2({
    allowClear: true,
    width: '100%',
    escapeMarkup: function escapeMarkup(text) {
      return text;
    },
    ajax: {
      url: args.url,
      dataType: 'json',
      cache: true,
      data: function data(params) {
        currentPage = params.page || 1;
        var search_term = params.term ? params.term : '';
        var query = {
          search: search_term,
          page: currentPage,
          per_page: args.perPage
        };
        return query;
      },
      processResults: function processResults(data) {
        return {
          results: data.items,
          pagination: {
            more: data.paginationMore
          }
        };
      },
      transport: function transport(params, success, failure) {
        var $request = $.ajax(params);
        $request.then(function (data, textStatus, jqXHR) {
          var totalPage = parseInt(jqXHR.getResponseHeader('x-wp-totalpages'));
          var paginationMore = currentPage < totalPage;
          var items = data.map(function (item) {
            return {
              id: item.id,
              text: item.name
            };
          });
          return {
            items: items,
            paginationMore: paginationMore
          };
        }).then(success);
        $request.fail(failure);
        return $request;
      }
    }
  });
}

/***/ }),

/***/ "./assets/src/js/global/components/utility.js":
/*!****************************************************!*\
  !*** ./assets/src/js/global/components/utility.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var $ = jQuery;
document.querySelectorAll('.la-icon i').forEach(function (item) {
  className.push(item.getAttribute('class'));
}); // Handle Disabled Link Action

$('.atbdp-disabled').on('click', function (e) {
  e.preventDefault();
}); // Toggle Modal

$('.cptm-modal-toggle').on('click', function (e) {
  e.preventDefault();
  var target_class = $(this).data('target');
  $('.' + target_class).toggleClass('active');
}); // Change label on file select/change

$('.cptm-file-field').on('change', function (e) {
  var target_id = $(this).attr('id');
  $('label[for=' + target_id + ']').text('Change');
});

/***/ }),

/***/ "./assets/src/js/global/global.js":
/*!****************************************!*\
  !*** ./assets/src/js/global/global.js ***!
  \****************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_utility__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/utility */ "./assets/src/js/global/components/utility.js");
/* harmony import */ var _components_utility__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_components_utility__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_modal__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/modal */ "./assets/src/js/global/components/modal.js");
/* harmony import */ var _components_modal__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_components_modal__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _components_setup_select2__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/setup-select2 */ "./assets/src/js/global/components/setup-select2.js");
/* harmony import */ var _components_select2_custom_control__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/select2-custom-control */ "./assets/src/js/global/components/select2-custom-control.js");
/* harmony import */ var _components_select2_custom_control__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _components_init_lazy_check__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./components/init-lazy-check */ "./assets/src/js/global/components/init-lazy-check.js");






/***/ }),

/***/ "./assets/src/js/lib/helper.js":
/*!*************************************!*\
  !*** ./assets/src/js/lib/helper.js ***!
  \*************************************/
/*! exports provided: get_dom_data, convertToSelect2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "get_dom_data", function() { return get_dom_data; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "convertToSelect2", function() { return convertToSelect2; });
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/typeof.js");
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__);

var $ = jQuery;

function get_dom_data(key, parent) {
  var elmKey = 'directorist-dom-data-' + key;
  var dataElm = parent ? parent.getElementsByClassName(elmKey) : document.getElementsByClassName(elmKey);

  if (!dataElm) {
    return '';
  }

  var is_script_debugging = directorist_options && directorist_options.script_debugging && directorist_options.script_debugging == '1' ? true : false;

  try {
    var dataValue = atob(dataElm[0].dataset.value);
    dataValue = JSON.parse(dataValue);
    return dataValue;
  } catch (error) {
    if (is_script_debugging) {
      console.log({
        key: key,
        dataElm: dataElm,
        error: error
      });
    }

    return '';
  }
}

function convertToSelect2(field) {
  if (!field) {
    return;
  }

  if (!field.elm) {
    return;
  }

  if (!field.elm.length) {
    return;
  }

  var default_args = {
    allowClear: true,
    width: '100%',
    templateResult: function templateResult(data) {
      // We only really care if there is an field to pull classes from
      if (!data.field) {
        return data.text;
      }

      var $field = $(data.field);
      var $wrapper = $('<span></span>');
      $wrapper.addClass($field[0].className);
      $wrapper.text(data.text);
      return $wrapper;
    }
  };
  var args = field.args && _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default()(field.args) === 'object' ? Object.assign(default_args, field.args) : default_args;
  var options = field.elm.find('option');
  var placeholder = options.length ? options[0].innerHTML : '';

  if (placeholder.length) {
    args.placeholder = placeholder;
  }

  field.elm.select2(args);
}



/***/ }),

/***/ "./assets/src/js/lib/lazy-checks/lazy-check-core.js":
/*!**********************************************************!*\
  !*** ./assets/src/js/lib/lazy-checks/lazy-check-core.js ***!
  \**********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ "./node_modules/@babel/runtime/helpers/toConsumableArray.js");
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/asyncToGenerator */ "./node_modules/@babel/runtime/helpers/asyncToGenerator.js");
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/typeof.js");
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _utility__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./utility */ "./assets/src/js/lib/lazy-checks/utility.js");





function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }



function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_3___default()(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }


var lazyCheckCore = {
  args: {},
  parseArgs: function parseArgs(userArgs, defaultArgs) {
    var args = _objectSpread(_objectSpread({}, defaultArgs), userArgs); // Validate AJAX argument
    // --------------------------


    if (args.ajax && !Array.isArray(args.ajax) && _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_2___default()(args.ajax) === 'object') {
      args.ajax = _objectSpread(_objectSpread({}, defaultArgs.ajax), args.ajax);
    } else {
      args.ajax = defaultArgs.ajax;
    }

    if (typeof args.ajax.maxInitItems !== 'number') {
      args.ajax.maxInitItems = parseInt(defaultArgs.ajax.maxInitItems);
    }

    if (args.ajax.maxInitItems < 1) {
      args.ajax.maxInitItems = 1;
    }

    if (typeof args.ajax.getPreselectedItemsID !== 'function') {
      args.ajax.getPreselectedItemsID = defaultArgs.ajax.getPreselectedItemsID;
    }

    if (typeof args.ajax.data !== 'function') {
      args.ajax.data = defaultArgs.ajax.data;
    }

    if (typeof args.ajax.processResults !== 'function') {
      args.ajax.processResults = defaultArgs.ajax.processResults;
    }

    if (typeof args.ajax.template !== 'function') {
      args.ajax.template = defaultArgs.ajax.template;
    }

    if (typeof args.ajax.loadingIndicator !== 'string') {
      args.ajax.loadingIndicator = defaultArgs.ajax.loadingIndicator;
    }

    if (typeof args.ajax.loadMoreText !== 'string') {
      args.ajax.loadMoreText = defaultArgs.ajax.loadMoreText;
    }

    return args;
  },
  enableLazyChecks: function () {
    var _enableLazyChecks = _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_4___default.a.mark(function _callee(rootContainer, args) {
      var id, initData;
      return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_4___default.a.wrap(function _callee$(_context) {
        while (1) {
          switch (_context.prev = _context.next) {
            case 0:
              this.args = args; // Attach ID to root element

              id = _utility__WEBPACK_IMPORTED_MODULE_5__["default"].generateRandom(100, 999);
              rootContainer.setAttribute('data-lazy-check-root-element-id', id); // Attach current page number to root element

              rootContainer.setAttribute('data-lazy-check-current-page', 0); // Prepare Root Element

              this.prepareRootElement(id); // Add Loading Indicator To Root Container

              this.addLoadingIndicatorToRootContainer(rootContainer); // Load initial data

              _context.next = 8;
              return this.fetchData({
                id: id
              });

            case 8:
              initData = _context.sent;
              // Remove Loading Indicator From Root Container
              this.removeLoadingIndicatorFromRootContainer(rootContainer); // Insert items to the DOM

              if (initData.success) {
                this.insertInitItemsToDOM({
                  rootContainer: rootContainer,
                  items: initData.data.template
                });
              }

            case 11:
            case "end":
              return _context.stop();
          }
        }
      }, _callee, this);
    }));

    function enableLazyChecks(_x, _x2) {
      return _enableLazyChecks.apply(this, arguments);
    }

    return enableLazyChecks;
  }(),
  insertInitItemsToDOM: function insertInitItemsToDOM(_ref) {
    var _this = this;

    var rootContainer = _ref.rootContainer,
        items = _ref.items;

    if (!items.length) {
      return;
    }

    var maxInitItems = this.args.ajax.maxInitItems;
    var rootItems = items.slice(0, maxInitItems);
    var modalItems = items.slice(maxInitItems);

    if (rootItems.length) {
      var itemsContainer = rootContainer.querySelector('.lazy-check-items-container');
      rootItems.map(function (item) {
        var itemElementWrap = document.createElement('div');
        itemElementWrap.innerHTML = item;
        var itemElement = itemElementWrap.querySelector('.lazy-check-item-wrap');

        if (itemElement) {
          itemElement.classList.add('init-item');
          itemsContainer.appendChild(itemElement);
        }
      });
    }

    var hasNextPage = this.hasNextPage(rootContainer);

    if (modalItems.length || hasNextPage) {
      this.insertShowMoreLink(rootContainer);
    }

    if (modalItems.length) {
      var id = rootContainer.getAttribute('data-lazy-check-root-element-id');
      var modalContainer = document.querySelector("[data-lazy-check-modal-id='".concat(id, "']"));

      if (!modalContainer) {
        modalContainer = this.insertModal(id);
      }

      var _itemsContainer = modalContainer.querySelector('.lazy-check-modal-fields');

      modalItems.map(function (item) {
        var itemElementWrap = document.createElement('div');
        itemElementWrap.innerHTML = item;
        var itemElement = itemElementWrap.querySelector('.lazy-check-item-wrap');

        if (itemElement) {
          var migratedItemElement = _this.migrateInputIDsForModal(itemElement);

          _itemsContainer.appendChild(migratedItemElement);
        }
      });
    }
  },
  insertShowMoreLink: function insertShowMoreLink(rootContainer) {
    var _this2 = this;

    var showMoreArea = rootContainer.querySelector('.lazy-check-show-more-area');

    if (!showMoreArea) {
      return;
    } // Create Show More Link


    var showMoreLink = document.createElement('a');
    showMoreLink.setAttribute('href', '#');
    showMoreLink.classList = "lazy-check-show-more ".concat(this.args.showMoreLinkClass);
    showMoreLink.innerHTML = this.args.showMorelabel;
    showMoreArea.appendChild(showMoreLink); // Enable Show More Link

    showMoreLink.addEventListener('click', function (event) {
      return _this2.showModal(event, rootContainer);
    });
  },
  prepareRootElement: function prepareRootElement(id) {
    var rootContainer = this.getRootContainerByID(id);
    var itemsContainer = rootContainer.querySelector('.lazy-check-items-container');

    if (!itemsContainer) {
      itemsContainer = document.createElement('div');
      itemsContainer.classList = 'lazy-check-items-container';
      rootContainer.append(itemsContainer);
    } // Add Show More Area


    var showMoreArea = rootContainer.querySelector('.lazy-check-show-more-area');

    if (!showMoreArea) {
      showMoreArea = document.createElement('div');
      showMoreArea.classList = 'lazy-check-show-more-area';
      _utility__WEBPACK_IMPORTED_MODULE_5__["default"].insertAfter(itemsContainer, showMoreArea);
    }

    showMoreArea.innerHTML = ''; // Add Feedback Area

    var feedbackArea = rootContainer.querySelector('.lazy-check-feedback');

    if (!feedbackArea) {
      feedbackArea = document.createElement('div');
      feedbackArea.classList = 'lazy-check-feedback';
      itemsContainer.parentNode.insertBefore(feedbackArea, showMoreArea);
    }

    feedbackArea.innerHTML = '';
  },
  showModal: function showModal(event, rootContainer) {
    event.preventDefault();
    var id = rootContainer.getAttribute('data-lazy-check-root-element-id');
    var modalContainer = document.querySelector("[data-lazy-check-modal-id='".concat(id, "']"));

    if (!modalContainer) {
      modalContainer = this.insertModal(id);
    } else {
      this.updateModal(id);
    }

    var hasNextPage = this.hasNextPage(rootContainer);

    if (hasNextPage) {
      this.addLoadMoreButtonToModal(modalContainer);
    } else {
      this.removeLoadMoreButtonFromModal(modalContainer);
    }

    _utility__WEBPACK_IMPORTED_MODULE_5__["default"].toggleClass(document.querySelector('body'), 'lazy-check-no-scroll');
    _utility__WEBPACK_IMPORTED_MODULE_5__["default"].toggleClass(modalContainer, 'show');
  },
  migrateInputIDs: function migrateInputIDs(_ref2) {
    var field = _ref2.field,
        idConverter = _ref2.idConverter;
    var clonedField = field.cloneNode(true); // Input Fields

    var inputFields = clonedField.getElementsByTagName('input');

    if (inputFields.length) {
      var _iterator = _createForOfIteratorHelper(inputFields),
          _step;

      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var fieldItem = _step.value;
          var oldID = fieldItem.getAttribute('id');
          var newID = idConverter(oldID);
          fieldItem.setAttribute('id', newID);
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }
    } // Labels


    var labels = clonedField.getElementsByTagName('label');

    if (labels.length) {
      var _iterator2 = _createForOfIteratorHelper(labels),
          _step2;

      try {
        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          var label = _step2.value;

          var _oldID = label.getAttribute('for');

          var _newID = idConverter(_oldID);

          label.setAttribute('for', _newID);
        }
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }
    }

    return clonedField;
  },
  migrateInputIDsForModal: function migrateInputIDsForModal(field) {
    var idConverter = function idConverter(oldID) {
      return 'modal-id-' + oldID;
    };

    return this.migrateInputIDs({
      field: field,
      idConverter: idConverter
    });
  },
  migrateInputIDsForRootElement: function migrateInputIDsForRootElement(field) {
    var idConverter = function idConverter(oldID) {
      return oldID.replace('modal-id-', '');
    };

    return this.migrateInputIDs({
      field: field,
      idConverter: idConverter
    });
  },
  closeModel: function closeModel(_ref3) {
    var modalContainer = _ref3.modalContainer;
    _utility__WEBPACK_IMPORTED_MODULE_5__["default"].toggleClass(modalContainer, 'show');
    _utility__WEBPACK_IMPORTED_MODULE_5__["default"].toggleClass(document.querySelector('body'), 'lazy-check-no-scroll');
  },
  clearAllInputs: function clearAllInputs(_ref4) {
    var modalContainer = _ref4.modalContainer;
    var inputs = modalContainer.getElementsByTagName('input');

    if (!inputs.length) {
      return;
    }

    var _iterator3 = _createForOfIteratorHelper(inputs),
        _step3;

    try {
      for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
        var input = _step3.value;
        input.checked = false;
      }
    } catch (err) {
      _iterator3.e(err);
    } finally {
      _iterator3.f();
    }
  },
  applySelection: function applySelection(_ref5) {
    var id = _ref5.id,
        modalContainer = _ref5.modalContainer;
    var rootContainer = this.getRootContainerByID(id);
    var allCheckedItems = modalContainer.querySelectorAll('input:checked'); // Reset to init state if no item is checked

    if (!allCheckedItems.length) {
      this.resetToInitState({
        modalContainer: modalContainer,
        rootContainer: rootContainer
      });
      this.closeModel({
        modalContainer: modalContainer
      });
      return;
    } // Apply checked items to root element


    this.applyCheckedItemsToRootElement({
      modalContainer: modalContainer,
      rootContainer: rootContainer,
      allCheckedItems: allCheckedItems
    });
    this.closeModel({
      modalContainer: modalContainer
    });
  },
  insertModal: function insertModal(id) {
    var _this3 = this;

    var modalContainer = document.createElement('div');
    modalContainer.className = 'lazy-check-modal';
    modalContainer.setAttribute('data-lazy-check-modal-id', id);
    modalContainer.innerHTML = "\n           <div class='lazy-check-modal-content'>\n               <div class='lazy-check-modal-header'>\n                   <h4 class='lazy-check-modal-header-title'>".concat(this.args.modalTitle, " </h4>\n\n                   <span class='lazy-check-modal-close'>\n                       <i class='fas fa-times'></i>\n                   </span>\n               </div>\n\n               <div class='lazy-check-modal-body'>\n                   <div class='lazy-check-modal-fields'></div>\n\n                   <div class='lazy-check-modal-fields-controls'></div>\n\n                   <div class='lazy-check-modal-feedback'></div>\n               </div>\n\n               <div class='lazy-check-modal-footer'>\n                   <div class='lazy-check-modal-actions'>\n                       <button type='button' class='lazy-check-btn lazy-check-btn-secondary lazy-check-clear-btn'>Clear all</button>\n                       <button type='button' class='lazy-check-btn lazy-check-btn-primary lazy-check-apply-btn'>Apply</button>\n                   </div>\n               </div>\n           </div>\n       ");
    var rootContainer = document.querySelector("[data-lazy-check-root-element-id='".concat(id, "']"));
    var initFields = rootContainer.querySelectorAll('.lazy-check-items-container .lazy-check-item-wrap'); // Change ID's for input and label

    var _iterator4 = _createForOfIteratorHelper(initFields),
        _step4;

    try {
      for (_iterator4.s(); !(_step4 = _iterator4.n()).done;) {
        var field = _step4.value;
        var migratedField = this.migrateInputIDsForModal(field);
        modalContainer.querySelector('.lazy-check-modal-fields').append(migratedField);
      }
    } catch (err) {
      _iterator4.e(err);
    } finally {
      _iterator4.f();
    }

    document.body.append(modalContainer); // Attach Events
    // ---------------
    // Close Button

    var closeButton = modalContainer.querySelector('.lazy-check-modal-close');
    closeButton.addEventListener('click', function (event) {
      return _this3.closeModel({
        event: event,
        id: id,
        modalContainer: modalContainer
      });
    }); // Clear Button

    var clearButton = modalContainer.querySelector('.lazy-check-clear-btn');
    clearButton.addEventListener('click', function (event) {
      return _this3.clearAllInputs({
        event: event,
        id: id,
        modalContainer: modalContainer
      });
    }); // Apply Button

    var applyButton = modalContainer.querySelector('.lazy-check-apply-btn');
    applyButton.addEventListener('click', function (event) {
      return _this3.applySelection({
        event: event,
        id: id,
        modalContainer: modalContainer
      });
    });
    return modalContainer;
  },
  updateModal: function updateModal(id) {
    // Prepare data
    var rootContainer = this.getRootContainerByID(id);
    var modalContainer = this.getModalContainerByID(id); // Get checked items from root element

    var rootCheckedInputs = rootContainer.querySelectorAll('input:checked');
    var rootCheckedItems = [];

    if (rootCheckedInputs.length) {
      var _iterator5 = _createForOfIteratorHelper(rootCheckedInputs),
          _step5;

      try {
        for (_iterator5.s(); !(_step5 = _iterator5.n()).done;) {
          var input = _step5.value;
          rootCheckedItems.push(input.parentElement);
        }
      } catch (err) {
        _iterator5.e(err);
      } finally {
        _iterator5.f();
      }
    } // Sync migrated checked items to modal


    var rootlInputs = rootContainer.querySelectorAll('input');

    var _iterator6 = _createForOfIteratorHelper(rootlInputs),
        _step6;

    try {
      for (_iterator6.s(); !(_step6 = _iterator6.n()).done;) {
        var rootInput = _step6.value;

        var _id = rootInput.getAttribute('id');

        var modalInput = modalContainer.querySelector("#modal-id-".concat(_id));

        if (!modalInput) {
          return;
        }

        modalInput.checked = rootInput.checked;
      } // Sort checked items in modal

    } catch (err) {
      _iterator6.e(err);
    } finally {
      _iterator6.f();
    }

    var modalCheckedInputs = modalContainer.querySelectorAll('input:checked');
    modalCheckedInputs = _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(modalCheckedInputs).reverse();
    modalCheckedInputs.map(function (input) {
      modalContainer.querySelector('.lazy-check-modal-fields').prepend(input.parentElement);
    }); // Show hidden items

    var modalInputs = modalContainer.querySelectorAll('input');

    _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(modalInputs).map(function (item) {
      item.parentElement.classList.remove('lazy-check-hide'); // const newClasses = item.parentElement.className.replace( 'lazy-check-hide', '' );
      // item.parentElement.className = newClasses;
    });
  },
  addLoadMoreButtonToModal: function addLoadMoreButtonToModal(modalContainer, force_add) {
    var _this4 = this;

    if (!modalContainer) {
      return;
    }

    var controllArea = modalContainer.querySelector('.lazy-check-modal-fields-controls');

    if (!controllArea) {
      return;
    } // Remove existed on if has any


    var oldLoadMoreLink = controllArea.querySelector('.lazy-check-modal-load-more-link');

    if (oldLoadMoreLink && !force_add) {
      oldLoadMoreLink.remove();
      return;
    }

    controllArea.innerHTML += "\n    <a class='lazy-check-modal-load-more-link'>\n        <span class='lazy-check-modal-load-more-text'>\n            ".concat(this.args.ajax.loadMoreText, "\n        </span>\n    </a>\n    ");
    var loadMoreLink = modalContainer.querySelector('.lazy-check-modal-load-more-link');
    loadMoreLink.addEventListener('click', function (event) {
      return _this4.loadMoreFields({
        modalContainer: modalContainer
      });
    });
  },
  removeLoadMoreButtonFromModal: function removeLoadMoreButtonFromModal(modalContainer) {
    if (!modalContainer) {
      return;
    }

    var controllArea = modalContainer.querySelector('.lazy-check-modal-fields-controls');

    if (!controllArea) {
      return;
    }

    var loadMoreLink = controllArea.querySelector('.lazy-check-modal-load-more-link');

    if (!loadMoreLink) {
      return;
    }

    loadMoreLink.remove();
  },
  loadMoreFields: function () {
    var _loadMoreFields = _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_4___default.a.mark(function _callee2(_ref6) {
      var modalContainer, modelID, nextData, itemsContainer;
      return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_4___default.a.wrap(function _callee2$(_context2) {
        while (1) {
          switch (_context2.prev = _context2.next) {
            case 0:
              modalContainer = _ref6.modalContainer;
              modelID = this.getModalID(modalContainer); // Add Loading Indicator

              this.addLoadingIndicatorToModal(modalContainer);
              _context2.next = 5;
              return this.fetchData({
                id: modelID,
                itemIDPrefix: 'modal-id-'
              });

            case 5:
              nextData = _context2.sent;
              // Remove Loading Indicator
              this.removeLoadingIndicatorFromModal(modalContainer);

              if (!(!nextData || !nextData.success || !nextData.data.items.length)) {
                _context2.next = 9;
                break;
              }

              return _context2.abrupt("return");

            case 9:
              // Insert items to the DOM
              itemsContainer = modalContainer.querySelector('.lazy-check-modal-fields');
              nextData.data.template.map(function (item) {
                var itemElementWrap = document.createElement('div');
                itemElementWrap.innerHTML = item;
                var itemElement = itemElementWrap.querySelector('.lazy-check-item-wrap');

                if (itemElement) {
                  itemsContainer.appendChild(itemElement);
                }
              });

            case 11:
            case "end":
              return _context2.stop();
          }
        }
      }, _callee2, this);
    }));

    function loadMoreFields(_x3) {
      return _loadMoreFields.apply(this, arguments);
    }

    return loadMoreFields;
  }(),
  applyCheckedItemsToRootElement: function applyCheckedItemsToRootElement(_ref7) {
    var _this5 = this;

    var modalContainer = _ref7.modalContainer,
        rootContainer = _ref7.rootContainer,
        allCheckedItems = _ref7.allCheckedItems;
    var maxInitItemLength = this.args.ajax.maxInitItems;

    var _this$getCheckedItems = this.getCheckedItems({
      allCheckedItems: allCheckedItems,
      maxInitItemLength: maxInitItemLength
    }),
        checkedItems = _this$getCheckedItems.checkedItems,
        checkedIDs = _this$getCheckedItems.checkedIDs; // Add additional field if nessasary


    if (checkedItems.length < maxInitItemLength) {
      checkedItems = this.wrapWithAdditionalItems({
        modalContainer: modalContainer,
        maxInitItemLength: maxInitItemLength,
        checkedItems: checkedItems,
        checkedIDs: checkedIDs
      });
    }

    var migratedSelectedItems = checkedItems.map(function (field) {
      return _this5.migrateInputIDsForRootElement(field);
    });
    var itemsContainer = rootContainer.querySelector('.lazy-check-items-container');
    itemsContainer.innerHTML = '';

    var _iterator7 = _createForOfIteratorHelper(migratedSelectedItems),
        _step7;

    try {
      for (_iterator7.s(); !(_step7 = _iterator7.n()).done;) {
        var item = _step7.value;
        itemsContainer.append(item);
      }
    } catch (err) {
      _iterator7.e(err);
    } finally {
      _iterator7.f();
    }
  },
  getCheckedItems: function getCheckedItems(_ref8) {
    var allCheckedItems = _ref8.allCheckedItems,
        maxInitItemLength = _ref8.maxInitItemLength;
    var checkedItems = [];
    var checkedIDs = [];
    var count = 0;

    var _iterator8 = _createForOfIteratorHelper(allCheckedItems),
        _step8;

    try {
      for (_iterator8.s(); !(_step8 = _iterator8.n()).done;) {
        var item = _step8.value;
        var parent = item.parentElement;
        var id = item.getAttribute('id');

        if (count >= maxInitItemLength) {
          parent.classList += ' lazy-check-hide';
        }

        checkedIDs.push(id);
        checkedItems.push(parent);
        count++;
      }
    } catch (err) {
      _iterator8.e(err);
    } finally {
      _iterator8.f();
    }

    return {
      checkedItems: checkedItems,
      checkedIDs: checkedIDs
    };
  },
  wrapWithAdditionalItems: function wrapWithAdditionalItems(_ref9) {
    var modalContainer = _ref9.modalContainer,
        maxInitItemLength = _ref9.maxInitItemLength,
        checkedItems = _ref9.checkedItems,
        checkedIDs = _ref9.checkedIDs;
    var initItems = modalContainer.querySelectorAll('.init-item');

    if (!initItems.length) {
      return checkedItems;
    }

    var requiredItemLength = maxInitItemLength - checkedItems.length;
    var count = 0;

    var _iterator9 = _createForOfIteratorHelper(initItems),
        _step9;

    try {
      for (_iterator9.s(); !(_step9 = _iterator9.n()).done;) {
        var initItem = _step9.value;

        if (count === requiredItemLength) {
          break;
        }

        var input = initItem.querySelector('input');

        if (!input) {
          continue;
        }

        var id = input.getAttribute('id');

        if (checkedIDs.includes(id)) {
          continue;
        }

        checkedItems.push(initItem);
        count++;
      }
    } catch (err) {
      _iterator9.e(err);
    } finally {
      _iterator9.f();
    }

    return checkedItems;
  },
  resetToInitState: function resetToInitState(_ref10) {
    var modalContainer = _ref10.modalContainer,
        rootContainer = _ref10.rootContainer;
    var initItems = modalContainer.querySelectorAll('.init-item');

    if (!initItems.length) {
      return;
    }

    var itemsContainer = rootContainer.querySelector('.lazy-check-items-container');
    itemsContainer.innerHTML = '';

    var _iterator10 = _createForOfIteratorHelper(initItems),
        _step10;

    try {
      for (_iterator10.s(); !(_step10 = _iterator10.n()).done;) {
        var item = _step10.value;

        var idConverter = function idConverter(oldID) {
          return oldID.replace('modal-id-', '');
        };

        var migratedField = this.migrateInputIDs({
          field: item,
          idConverter: idConverter
        });
        itemsContainer.append(migratedField);
      }
    } catch (err) {
      _iterator10.e(err);
    } finally {
      _iterator10.f();
    }
  },
  getRootContainerByID: function getRootContainerByID(id) {
    return document.querySelector("[data-lazy-check-root-element-id='".concat(id, "']"));
  },
  getCurrentPage: function getCurrentPage(rootContainer) {
    if (!rootContainer) {
      return 1;
    }

    return parseInt(rootContainer.getAttribute('data-lazy-check-current-page'));
  },
  updateCurrentPage: function updateCurrentPage(rootContainer, newPageNumber) {
    if (!rootContainer) {
      return;
    }

    rootContainer.setAttribute('data-lazy-check-current-page', newPageNumber);
  },
  hasNextPage: function hasNextPage(rootContainer) {
    if (!rootContainer) {
      return false;
    }

    return parseInt(rootContainer.getAttribute('data-lazy-check-has-next-page')) ? true : false;
  },
  updateHasNextPageStatus: function updateHasNextPageStatus(rootContainer, status) {
    if (!rootContainer) {
      return;
    }

    rootContainer.setAttribute('data-lazy-check-has-next-page', status ? 1 : 0);
  },
  getIsLoadingStatus: function getIsLoadingStatus(rootContainer) {
    if (!rootContainer) {
      return false;
    }

    var isLoading = rootContainer.getAttribute('data-lazy-check-is-loading');
    return isLoading === '1' || isLoading === true ? true : false;
  },
  updateIsLoadingStatus: function updateIsLoadingStatus(rootContainer, status) {
    if (!rootContainer) {
      return;
    }

    rootContainer.setAttribute('data-lazy-check-is-loading', status ? 1 : 0);
  },
  getModalContainerByID: function getModalContainerByID(id) {
    return document.querySelector("[data-lazy-check-modal-id='".concat(id, "']"));
  },
  getModalID: function getModalID(modalContainer) {
    var id = modalContainer.getAttribute('data-lazy-check-modal-id');
    return parseInt(id);
  },
  addLoadingIndicatorToModal: function addLoadingIndicatorToModal(modalContainer) {
    if (!modalContainer) {
      return;
    }

    var feedbackArea = modalContainer.querySelector('.lazy-check-modal-feedback');

    if (!feedbackArea) {
      return;
    }

    feedbackArea.innerHTML = this.args.ajax.loadingIndicator;
  },
  removeLoadingIndicatorFromModal: function removeLoadingIndicatorFromModal(modalContainer) {
    if (!modalContainer) {
      return;
    }

    var feedbackArea = modalContainer.querySelector('.lazy-check-modal-feedback');

    if (!feedbackArea) {
      return;
    }

    feedbackArea.innerHTML = '';
  },
  addLoadingIndicatorToRootContainer: function addLoadingIndicatorToRootContainer(rootContainer) {
    if (!rootContainer) {
      return;
    }

    var feedbackArea = rootContainer.querySelector('.lazy-check-feedback');

    if (!feedbackArea) {
      return;
    }

    feedbackArea.innerHTML = this.args.ajax.loadingIndicator;
  },
  removeLoadingIndicatorFromRootContainer: function removeLoadingIndicatorFromRootContainer(rootContainer) {
    if (!rootContainer) {
      return;
    }

    var feedbackArea = rootContainer.querySelector('.lazy-check-feedback');

    if (!feedbackArea) {
      return;
    }

    feedbackArea.innerHTML = '';
  },
  isLoadedPreselectedItems: function isLoadedPreselectedItems(rootContainer) {
    var isLoadedPreselectedItems = rootContainer.getAttribute('data-lazy-check-is-loaded-preselected-items');
    return isLoadedPreselectedItems === '1' ? true : false;
  },
  updateIsLoadedPreselectedItemsStatus: function updateIsLoadedPreselectedItemsStatus(rootContainer, status) {
    var newStatus = status ? '1' : '0';
    rootContainer.setAttribute('data-lazy-check-is-loaded-preselected-items', newStatus);
  },
  fetchData: function () {
    var _fetchData = _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_4___default.a.mark(function _callee3(_ref11) {
      var _this6 = this;

      var id, itemIDPrefix, responseStatus, rootContainer, modalContainer, isLoading, currentPage, newPage, preselectedItemsID, isLoadedPreselectedItems, preselectedItems, _formData, _url, _response, _body, formData, url, response, headers, body, processResults, templateData, hasNextPage;

      return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_4___default.a.wrap(function _callee3$(_context3) {
        while (1) {
          switch (_context3.prev = _context3.next) {
            case 0:
              id = _ref11.id, itemIDPrefix = _ref11.itemIDPrefix;
              // Response Status
              responseStatus = new _utility__WEBPACK_IMPORTED_MODULE_5__["default"].responseStatus(); // Prepare Data

              rootContainer = this.getRootContainerByID(id);
              modalContainer = this.getModalContainerByID(id);

              if (!rootContainer) {
                responseStatus.success = false;
                responseStatus.message = 'Root Container not found';
              } // Stop if already loading


              isLoading = this.getIsLoadingStatus(rootContainer);

              if (!isLoading) {
                _context3.next = 10;
                break;
              }

              responseStatus.success = false;
              responseStatus.message = 'Please wait...';
              return _context3.abrupt("return", responseStatus);

            case 10:
              // Update Loading Status
              this.updateIsLoadingStatus(rootContainer, true); // Remove Load More Button

              this.removeLoadMoreButtonFromModal(modalContainer);
              currentPage = this.getCurrentPage(rootContainer);
              newPage = currentPage + 1; // Load Pre Selected Items

              preselectedItemsID = this.args.ajax.getPreselectedItemsID();
              preselectedItemsID = preselectedItemsID instanceof Array ? preselectedItemsID : [];
              isLoadedPreselectedItems = this.isLoadedPreselectedItems(rootContainer);
              preselectedItems = [];

              if (!(preselectedItemsID.length && !isLoadedPreselectedItems)) {
                _context3.next = 29;
                break;
              }

              _formData = this.args.ajax.data({
                isLoadingPreselectedItems: true,
                preselectedItemsID: preselectedItemsID
              });
              _url = this.args.ajax.url;
              _url = _url + _utility__WEBPACK_IMPORTED_MODULE_5__["default"].jsonToQueryString(_formData);
              _context3.next = 24;
              return fetch(_url);

            case 24:
              _response = _context3.sent;
              _context3.next = 27;
              return _response.json();

            case 27:
              _body = _context3.sent;

              if (_response.ok && _body instanceof Array) {
                preselectedItems = _body;
                this.updateIsLoadedPreselectedItemsStatus(rootContainer, true);
              }

            case 29:
              // Load General Items
              formData = this.args.ajax.data({
                page: newPage,
                isLoadingPreselectedItems: false,
                preselectedItemsID: preselectedItemsID
              });
              url = this.args.ajax.url;
              url = url + _utility__WEBPACK_IMPORTED_MODULE_5__["default"].jsonToQueryString(formData);
              _context3.next = 34;
              return fetch(url);

            case 34:
              response = _context3.sent;
              headers = response.headers;
              _context3.next = 38;
              return response.json();

            case 38:
              body = _context3.sent;

              if (response.ok) {
                _context3.next = 43;
                break;
              }

              responseStatus.success = false;
              responseStatus.message = 'Something went wrong';
              return _context3.abrupt("return", responseStatus);

            case 43:
              // Process Results
              processResults = this.args.ajax.processResults({
                body: body,
                headers: headers,
                params: formData,
                hasNextPage: false
              }); // Validate Process Results

              if (processResults && _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_2___default()(processResults) == 'object') {
                _context3.next = 48;
                break;
              }

              responseStatus.success = false;
              responseStatus.message = 'Something went wrong';
              return _context3.abrupt("return", responseStatus);

            case 48:
              if (processResults.body && processResults.body instanceof Array) {
                _context3.next = 52;
                break;
              }

              responseStatus.success = false;
              responseStatus.message = 'Response body must be array';
              return _context3.abrupt("return", responseStatus);

            case 52:
              // Merge Preselected Items
              if (preselectedItems.length) {
                processResults.body = [].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(preselectedItems), _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(processResults.body));
              }

              if (processResults.body.length) {
                _context3.next = 60;
                break;
              }

              responseStatus.success = false;
              responseStatus.message = 'No result found'; // Update Has Next Page Status

              this.updateHasNextPageStatus(rootContainer, false); // Remove Load More Button

              this.removeLoadMoreButtonFromModal(modalContainer); // Remove Loading Indicator

              this.removeLoadingIndicatorFromModal(modalContainer);
              return _context3.abrupt("return", responseStatus);

            case 60:
              // Process Results
              templateData = processResults.body.map(function (item) {
                try {
                  var idPrefix = itemIDPrefix ? itemIDPrefix : '';
                  item.randomID = idPrefix + _utility__WEBPACK_IMPORTED_MODULE_5__["default"].generateRandom(100000, 999999);
                  return _this6.args.ajax.template(item, headers);
                } catch (error) {
                  return '';
                }
              }); // Update Status

              responseStatus.success = true;
              responseStatus.data = {
                items: processResults.body,
                template: templateData
              }; // Update Current Page

              this.updateCurrentPage(rootContainer, newPage); // Update Has Next Page Status

              hasNextPage = processResults.hasNextPage ? true : false;
              this.updateHasNextPageStatus(rootContainer, hasNextPage); // Update Loading Status

              this.updateIsLoadingStatus(rootContainer, false); // Toggle Load More Button

              if (hasNextPage) {
                this.addLoadMoreButtonToModal(modalContainer);
              } else {
                this.removeLoadMoreButtonFromModal(modalContainer);
              }

              return _context3.abrupt("return", responseStatus);

            case 69:
            case "end":
              return _context3.stop();
          }
        }
      }, _callee3, this);
    }));

    function fetchData(_x4) {
      return _fetchData.apply(this, arguments);
    }

    return fetchData;
  }()
};
/* harmony default export */ __webpack_exports__["default"] = (lazyCheckCore);

/***/ }),

/***/ "./assets/src/js/lib/lazy-checks/lazy-check.js":
/*!*****************************************************!*\
  !*** ./assets/src/js/lib/lazy-checks/lazy-check.js ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _lazy_check_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./lazy-check-core */ "./assets/src/js/lib/lazy-checks/lazy-check-core.js");
/* harmony import */ var _utility__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./utility */ "./assets/src/js/lib/lazy-checks/utility.js");
function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }




var lazyCheck = function lazyCheck(userArgs) {
  var _this = this;

  var _defaultArgs = {
    modalTitle: '',
    containerClass: '',
    showMorelabel: 'Show More',
    showMoreLinkClass: '',
    ajax: {
      url: '',
      maxInitItems: 5,
      getPreselectedItemsID: function getPreselectedItemsID() {
        return [];
      },
      data: function data(params) {
        return params;
      },
      processResults: function processResults(response) {
        return response;
      },
      template: function template(item, headers) {
        return "<div class=\"lazy-check-item-wrap\">\n          <input type=\"checkbox\" name=\"field[]\" value=\"\" id=\"".concat(item.randomID, "\">\n          <label for=\"").concat(item.randomID, "\">").concat(item.name, "</label>\n        </div>");
      },
      loadingIndicator: 'Loading...',
      loadMoreText: 'Load more'
    }
  };
  this.args = _lazy_check_core__WEBPACK_IMPORTED_MODULE_0__["default"].parseArgs(userArgs, _defaultArgs);
  /**
   * Init
   *
   * @returns void
   */

  this.init = function () {
    if (!_this.args.containerClass) return;
    var rootElements = document.querySelectorAll("." + _this.args.containerClass);

    if (!rootElements.length) {
      _utility__WEBPACK_IMPORTED_MODULE_1__["default"].sendDebugLog("Container Found", rootElement);
      return;
    } // Enable Lazy Checks to each root element


    var _iterator = _createForOfIteratorHelper(rootElements),
        _step;

    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var elm = _step.value;
        _lazy_check_core__WEBPACK_IMPORTED_MODULE_0__["default"].enableLazyChecks(elm, _this.args);
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
  };
};

/* harmony default export */ __webpack_exports__["default"] = (lazyCheck);

/***/ }),

/***/ "./assets/src/js/lib/lazy-checks/utility.js":
/*!**************************************************!*\
  !*** ./assets/src/js/lib/lazy-checks/utility.js ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/typeof.js");
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__);

var utility = {};

utility.toggleClass = function (element, className) {
  if (element.classList.contains(className)) {
    element.classList.remove(className);
  } else {
    element.classList.add(className);
  }
};

utility.sendDebugLog = function (message, ref) {
  if (!args.debagMode) return;
  console.log({
    message: "LazyChecks: ".concat(message),
    ref: ref
  });
};

utility.generateRandom = function () {
  var min = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
  var max = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 100;
  // find diff
  var difference = max - min; // generate random number

  var rand = Math.random(); // multiply with difference

  rand = Math.floor(rand * difference); // add with min value

  rand = rand + min;
  return rand;
};

utility.insertAfter = function (targetElement, subject) {
  targetElement.parentNode.insertBefore(subject, targetElement.nextSibling);
};

utility.responseStatus = function () {
  this.success = false;
  this.message = '';
  this.data = null;
  this.errors = null;
  this.warnings = null;
  this.info = null; // Template
  // this.errors  = {
  //   key: 'Error Message',
  // };
  // this.warnings  = {
  //   key: 'Warning Message',
  // };
  // this.info  = {
  //   key: 'Info Message',
  // };
};

utility.jsonToQueryString = function (json) {
  var string = "?";

  if (!json || Array.isArray(json)) {
    return string;
  }

  if (_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default()(json) !== "object") {
    return string;
  }

  for (var _i = 0, _Object$keys = Object.keys(json); _i < _Object$keys.length; _i++) {
    var key = _Object$keys[_i];
    string += key + "=" + json[key] + "&";
  }

  return string;
};

/* harmony default export */ __webpack_exports__["default"] = (utility);

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayLikeToArray.js ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;

  for (var i = 0, arr2 = new Array(len); i < len; i++) {
    arr2[i] = arr[i];
  }

  return arr2;
}

module.exports = _arrayLikeToArray;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayLikeToArray = __webpack_require__(/*! ./arrayLikeToArray.js */ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js");

function _arrayWithoutHoles(arr) {
  if (Array.isArray(arr)) return arrayLikeToArray(arr);
}

module.exports = _arrayWithoutHoles;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/asyncToGenerator.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/asyncToGenerator.js ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) {
  try {
    var info = gen[key](arg);
    var value = info.value;
  } catch (error) {
    reject(error);
    return;
  }

  if (info.done) {
    resolve(value);
  } else {
    Promise.resolve(value).then(_next, _throw);
  }
}

function _asyncToGenerator(fn) {
  return function () {
    var self = this,
        args = arguments;
    return new Promise(function (resolve, reject) {
      var gen = fn.apply(self, args);

      function _next(value) {
        asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value);
      }

      function _throw(err) {
        asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err);
      }

      _next(undefined);
    });
  };
}

module.exports = _asyncToGenerator;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/defineProperty.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/defineProperty.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
}

module.exports = _defineProperty;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/iterableToArray.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/iterableToArray.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _iterableToArray(iter) {
  if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter);
}

module.exports = _iterableToArray;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/nonIterableSpread.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/nonIterableSpread.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _nonIterableSpread() {
  throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

module.exports = _nonIterableSpread;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/toConsumableArray.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/toConsumableArray.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayWithoutHoles = __webpack_require__(/*! ./arrayWithoutHoles.js */ "./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js");

var iterableToArray = __webpack_require__(/*! ./iterableToArray.js */ "./node_modules/@babel/runtime/helpers/iterableToArray.js");

var unsupportedIterableToArray = __webpack_require__(/*! ./unsupportedIterableToArray.js */ "./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js");

var nonIterableSpread = __webpack_require__(/*! ./nonIterableSpread.js */ "./node_modules/@babel/runtime/helpers/nonIterableSpread.js");

function _toConsumableArray(arr) {
  return arrayWithoutHoles(arr) || iterableToArray(arr) || unsupportedIterableToArray(arr) || nonIterableSpread();
}

module.exports = _toConsumableArray;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/typeof.js":
/*!*******************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/typeof.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(obj) {
  "@babel/helpers - typeof";

  if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
    module.exports = _typeof = function _typeof(obj) {
      return typeof obj;
    };

    module.exports["default"] = module.exports, module.exports.__esModule = true;
  } else {
    module.exports = _typeof = function _typeof(obj) {
      return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
    };

    module.exports["default"] = module.exports, module.exports.__esModule = true;
  }

  return _typeof(obj);
}

module.exports = _typeof;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js":
/*!***************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js ***!
  \***************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayLikeToArray = __webpack_require__(/*! ./arrayLikeToArray.js */ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js");

function _unsupportedIterableToArray(o, minLen) {
  if (!o) return;
  if (typeof o === "string") return arrayLikeToArray(o, minLen);
  var n = Object.prototype.toString.call(o).slice(8, -1);
  if (n === "Object" && o.constructor) n = o.constructor.name;
  if (n === "Map" || n === "Set") return Array.from(o);
  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return arrayLikeToArray(o, minLen);
}

module.exports = _unsupportedIterableToArray;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/regenerator/index.js":
/*!**********************************************************!*\
  !*** ./node_modules/@babel/runtime/regenerator/index.js ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! regenerator-runtime */ "./node_modules/regenerator-runtime/runtime.js");


/***/ }),

/***/ "./node_modules/regenerator-runtime/runtime.js":
/*!*****************************************************!*\
  !*** ./node_modules/regenerator-runtime/runtime.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/**
 * Copyright (c) 2014-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

var runtime = (function (exports) {
  "use strict";

  var Op = Object.prototype;
  var hasOwn = Op.hasOwnProperty;
  var undefined; // More compressible than void 0.
  var $Symbol = typeof Symbol === "function" ? Symbol : {};
  var iteratorSymbol = $Symbol.iterator || "@@iterator";
  var asyncIteratorSymbol = $Symbol.asyncIterator || "@@asyncIterator";
  var toStringTagSymbol = $Symbol.toStringTag || "@@toStringTag";

  function define(obj, key, value) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
    return obj[key];
  }
  try {
    // IE 8 has a broken Object.defineProperty that only works on DOM objects.
    define({}, "");
  } catch (err) {
    define = function(obj, key, value) {
      return obj[key] = value;
    };
  }

  function wrap(innerFn, outerFn, self, tryLocsList) {
    // If outerFn provided and outerFn.prototype is a Generator, then outerFn.prototype instanceof Generator.
    var protoGenerator = outerFn && outerFn.prototype instanceof Generator ? outerFn : Generator;
    var generator = Object.create(protoGenerator.prototype);
    var context = new Context(tryLocsList || []);

    // The ._invoke method unifies the implementations of the .next,
    // .throw, and .return methods.
    generator._invoke = makeInvokeMethod(innerFn, self, context);

    return generator;
  }
  exports.wrap = wrap;

  // Try/catch helper to minimize deoptimizations. Returns a completion
  // record like context.tryEntries[i].completion. This interface could
  // have been (and was previously) designed to take a closure to be
  // invoked without arguments, but in all the cases we care about we
  // already have an existing method we want to call, so there's no need
  // to create a new function object. We can even get away with assuming
  // the method takes exactly one argument, since that happens to be true
  // in every case, so we don't have to touch the arguments object. The
  // only additional allocation required is the completion record, which
  // has a stable shape and so hopefully should be cheap to allocate.
  function tryCatch(fn, obj, arg) {
    try {
      return { type: "normal", arg: fn.call(obj, arg) };
    } catch (err) {
      return { type: "throw", arg: err };
    }
  }

  var GenStateSuspendedStart = "suspendedStart";
  var GenStateSuspendedYield = "suspendedYield";
  var GenStateExecuting = "executing";
  var GenStateCompleted = "completed";

  // Returning this object from the innerFn has the same effect as
  // breaking out of the dispatch switch statement.
  var ContinueSentinel = {};

  // Dummy constructor functions that we use as the .constructor and
  // .constructor.prototype properties for functions that return Generator
  // objects. For full spec compliance, you may wish to configure your
  // minifier not to mangle the names of these two functions.
  function Generator() {}
  function GeneratorFunction() {}
  function GeneratorFunctionPrototype() {}

  // This is a polyfill for %IteratorPrototype% for environments that
  // don't natively support it.
  var IteratorPrototype = {};
  IteratorPrototype[iteratorSymbol] = function () {
    return this;
  };

  var getProto = Object.getPrototypeOf;
  var NativeIteratorPrototype = getProto && getProto(getProto(values([])));
  if (NativeIteratorPrototype &&
      NativeIteratorPrototype !== Op &&
      hasOwn.call(NativeIteratorPrototype, iteratorSymbol)) {
    // This environment has a native %IteratorPrototype%; use it instead
    // of the polyfill.
    IteratorPrototype = NativeIteratorPrototype;
  }

  var Gp = GeneratorFunctionPrototype.prototype =
    Generator.prototype = Object.create(IteratorPrototype);
  GeneratorFunction.prototype = Gp.constructor = GeneratorFunctionPrototype;
  GeneratorFunctionPrototype.constructor = GeneratorFunction;
  GeneratorFunction.displayName = define(
    GeneratorFunctionPrototype,
    toStringTagSymbol,
    "GeneratorFunction"
  );

  // Helper for defining the .next, .throw, and .return methods of the
  // Iterator interface in terms of a single ._invoke method.
  function defineIteratorMethods(prototype) {
    ["next", "throw", "return"].forEach(function(method) {
      define(prototype, method, function(arg) {
        return this._invoke(method, arg);
      });
    });
  }

  exports.isGeneratorFunction = function(genFun) {
    var ctor = typeof genFun === "function" && genFun.constructor;
    return ctor
      ? ctor === GeneratorFunction ||
        // For the native GeneratorFunction constructor, the best we can
        // do is to check its .name property.
        (ctor.displayName || ctor.name) === "GeneratorFunction"
      : false;
  };

  exports.mark = function(genFun) {
    if (Object.setPrototypeOf) {
      Object.setPrototypeOf(genFun, GeneratorFunctionPrototype);
    } else {
      genFun.__proto__ = GeneratorFunctionPrototype;
      define(genFun, toStringTagSymbol, "GeneratorFunction");
    }
    genFun.prototype = Object.create(Gp);
    return genFun;
  };

  // Within the body of any async function, `await x` is transformed to
  // `yield regeneratorRuntime.awrap(x)`, so that the runtime can test
  // `hasOwn.call(value, "__await")` to determine if the yielded value is
  // meant to be awaited.
  exports.awrap = function(arg) {
    return { __await: arg };
  };

  function AsyncIterator(generator, PromiseImpl) {
    function invoke(method, arg, resolve, reject) {
      var record = tryCatch(generator[method], generator, arg);
      if (record.type === "throw") {
        reject(record.arg);
      } else {
        var result = record.arg;
        var value = result.value;
        if (value &&
            typeof value === "object" &&
            hasOwn.call(value, "__await")) {
          return PromiseImpl.resolve(value.__await).then(function(value) {
            invoke("next", value, resolve, reject);
          }, function(err) {
            invoke("throw", err, resolve, reject);
          });
        }

        return PromiseImpl.resolve(value).then(function(unwrapped) {
          // When a yielded Promise is resolved, its final value becomes
          // the .value of the Promise<{value,done}> result for the
          // current iteration.
          result.value = unwrapped;
          resolve(result);
        }, function(error) {
          // If a rejected Promise was yielded, throw the rejection back
          // into the async generator function so it can be handled there.
          return invoke("throw", error, resolve, reject);
        });
      }
    }

    var previousPromise;

    function enqueue(method, arg) {
      function callInvokeWithMethodAndArg() {
        return new PromiseImpl(function(resolve, reject) {
          invoke(method, arg, resolve, reject);
        });
      }

      return previousPromise =
        // If enqueue has been called before, then we want to wait until
        // all previous Promises have been resolved before calling invoke,
        // so that results are always delivered in the correct order. If
        // enqueue has not been called before, then it is important to
        // call invoke immediately, without waiting on a callback to fire,
        // so that the async generator function has the opportunity to do
        // any necessary setup in a predictable way. This predictability
        // is why the Promise constructor synchronously invokes its
        // executor callback, and why async functions synchronously
        // execute code before the first await. Since we implement simple
        // async functions in terms of async generators, it is especially
        // important to get this right, even though it requires care.
        previousPromise ? previousPromise.then(
          callInvokeWithMethodAndArg,
          // Avoid propagating failures to Promises returned by later
          // invocations of the iterator.
          callInvokeWithMethodAndArg
        ) : callInvokeWithMethodAndArg();
    }

    // Define the unified helper method that is used to implement .next,
    // .throw, and .return (see defineIteratorMethods).
    this._invoke = enqueue;
  }

  defineIteratorMethods(AsyncIterator.prototype);
  AsyncIterator.prototype[asyncIteratorSymbol] = function () {
    return this;
  };
  exports.AsyncIterator = AsyncIterator;

  // Note that simple async functions are implemented on top of
  // AsyncIterator objects; they just return a Promise for the value of
  // the final result produced by the iterator.
  exports.async = function(innerFn, outerFn, self, tryLocsList, PromiseImpl) {
    if (PromiseImpl === void 0) PromiseImpl = Promise;

    var iter = new AsyncIterator(
      wrap(innerFn, outerFn, self, tryLocsList),
      PromiseImpl
    );

    return exports.isGeneratorFunction(outerFn)
      ? iter // If outerFn is a generator, return the full iterator.
      : iter.next().then(function(result) {
          return result.done ? result.value : iter.next();
        });
  };

  function makeInvokeMethod(innerFn, self, context) {
    var state = GenStateSuspendedStart;

    return function invoke(method, arg) {
      if (state === GenStateExecuting) {
        throw new Error("Generator is already running");
      }

      if (state === GenStateCompleted) {
        if (method === "throw") {
          throw arg;
        }

        // Be forgiving, per 25.3.3.3.3 of the spec:
        // https://people.mozilla.org/~jorendorff/es6-draft.html#sec-generatorresume
        return doneResult();
      }

      context.method = method;
      context.arg = arg;

      while (true) {
        var delegate = context.delegate;
        if (delegate) {
          var delegateResult = maybeInvokeDelegate(delegate, context);
          if (delegateResult) {
            if (delegateResult === ContinueSentinel) continue;
            return delegateResult;
          }
        }

        if (context.method === "next") {
          // Setting context._sent for legacy support of Babel's
          // function.sent implementation.
          context.sent = context._sent = context.arg;

        } else if (context.method === "throw") {
          if (state === GenStateSuspendedStart) {
            state = GenStateCompleted;
            throw context.arg;
          }

          context.dispatchException(context.arg);

        } else if (context.method === "return") {
          context.abrupt("return", context.arg);
        }

        state = GenStateExecuting;

        var record = tryCatch(innerFn, self, context);
        if (record.type === "normal") {
          // If an exception is thrown from innerFn, we leave state ===
          // GenStateExecuting and loop back for another invocation.
          state = context.done
            ? GenStateCompleted
            : GenStateSuspendedYield;

          if (record.arg === ContinueSentinel) {
            continue;
          }

          return {
            value: record.arg,
            done: context.done
          };

        } else if (record.type === "throw") {
          state = GenStateCompleted;
          // Dispatch the exception by looping back around to the
          // context.dispatchException(context.arg) call above.
          context.method = "throw";
          context.arg = record.arg;
        }
      }
    };
  }

  // Call delegate.iterator[context.method](context.arg) and handle the
  // result, either by returning a { value, done } result from the
  // delegate iterator, or by modifying context.method and context.arg,
  // setting context.delegate to null, and returning the ContinueSentinel.
  function maybeInvokeDelegate(delegate, context) {
    var method = delegate.iterator[context.method];
    if (method === undefined) {
      // A .throw or .return when the delegate iterator has no .throw
      // method always terminates the yield* loop.
      context.delegate = null;

      if (context.method === "throw") {
        // Note: ["return"] must be used for ES3 parsing compatibility.
        if (delegate.iterator["return"]) {
          // If the delegate iterator has a return method, give it a
          // chance to clean up.
          context.method = "return";
          context.arg = undefined;
          maybeInvokeDelegate(delegate, context);

          if (context.method === "throw") {
            // If maybeInvokeDelegate(context) changed context.method from
            // "return" to "throw", let that override the TypeError below.
            return ContinueSentinel;
          }
        }

        context.method = "throw";
        context.arg = new TypeError(
          "The iterator does not provide a 'throw' method");
      }

      return ContinueSentinel;
    }

    var record = tryCatch(method, delegate.iterator, context.arg);

    if (record.type === "throw") {
      context.method = "throw";
      context.arg = record.arg;
      context.delegate = null;
      return ContinueSentinel;
    }

    var info = record.arg;

    if (! info) {
      context.method = "throw";
      context.arg = new TypeError("iterator result is not an object");
      context.delegate = null;
      return ContinueSentinel;
    }

    if (info.done) {
      // Assign the result of the finished delegate to the temporary
      // variable specified by delegate.resultName (see delegateYield).
      context[delegate.resultName] = info.value;

      // Resume execution at the desired location (see delegateYield).
      context.next = delegate.nextLoc;

      // If context.method was "throw" but the delegate handled the
      // exception, let the outer generator proceed normally. If
      // context.method was "next", forget context.arg since it has been
      // "consumed" by the delegate iterator. If context.method was
      // "return", allow the original .return call to continue in the
      // outer generator.
      if (context.method !== "return") {
        context.method = "next";
        context.arg = undefined;
      }

    } else {
      // Re-yield the result returned by the delegate method.
      return info;
    }

    // The delegate iterator is finished, so forget it and continue with
    // the outer generator.
    context.delegate = null;
    return ContinueSentinel;
  }

  // Define Generator.prototype.{next,throw,return} in terms of the
  // unified ._invoke helper method.
  defineIteratorMethods(Gp);

  define(Gp, toStringTagSymbol, "Generator");

  // A Generator should always return itself as the iterator object when the
  // @@iterator function is called on it. Some browsers' implementations of the
  // iterator prototype chain incorrectly implement this, causing the Generator
  // object to not be returned from this call. This ensures that doesn't happen.
  // See https://github.com/facebook/regenerator/issues/274 for more details.
  Gp[iteratorSymbol] = function() {
    return this;
  };

  Gp.toString = function() {
    return "[object Generator]";
  };

  function pushTryEntry(locs) {
    var entry = { tryLoc: locs[0] };

    if (1 in locs) {
      entry.catchLoc = locs[1];
    }

    if (2 in locs) {
      entry.finallyLoc = locs[2];
      entry.afterLoc = locs[3];
    }

    this.tryEntries.push(entry);
  }

  function resetTryEntry(entry) {
    var record = entry.completion || {};
    record.type = "normal";
    delete record.arg;
    entry.completion = record;
  }

  function Context(tryLocsList) {
    // The root entry object (effectively a try statement without a catch
    // or a finally block) gives us a place to store values thrown from
    // locations where there is no enclosing try statement.
    this.tryEntries = [{ tryLoc: "root" }];
    tryLocsList.forEach(pushTryEntry, this);
    this.reset(true);
  }

  exports.keys = function(object) {
    var keys = [];
    for (var key in object) {
      keys.push(key);
    }
    keys.reverse();

    // Rather than returning an object with a next method, we keep
    // things simple and return the next function itself.
    return function next() {
      while (keys.length) {
        var key = keys.pop();
        if (key in object) {
          next.value = key;
          next.done = false;
          return next;
        }
      }

      // To avoid creating an additional object, we just hang the .value
      // and .done properties off the next function object itself. This
      // also ensures that the minifier will not anonymize the function.
      next.done = true;
      return next;
    };
  };

  function values(iterable) {
    if (iterable) {
      var iteratorMethod = iterable[iteratorSymbol];
      if (iteratorMethod) {
        return iteratorMethod.call(iterable);
      }

      if (typeof iterable.next === "function") {
        return iterable;
      }

      if (!isNaN(iterable.length)) {
        var i = -1, next = function next() {
          while (++i < iterable.length) {
            if (hasOwn.call(iterable, i)) {
              next.value = iterable[i];
              next.done = false;
              return next;
            }
          }

          next.value = undefined;
          next.done = true;

          return next;
        };

        return next.next = next;
      }
    }

    // Return an iterator with no values.
    return { next: doneResult };
  }
  exports.values = values;

  function doneResult() {
    return { value: undefined, done: true };
  }

  Context.prototype = {
    constructor: Context,

    reset: function(skipTempReset) {
      this.prev = 0;
      this.next = 0;
      // Resetting context._sent for legacy support of Babel's
      // function.sent implementation.
      this.sent = this._sent = undefined;
      this.done = false;
      this.delegate = null;

      this.method = "next";
      this.arg = undefined;

      this.tryEntries.forEach(resetTryEntry);

      if (!skipTempReset) {
        for (var name in this) {
          // Not sure about the optimal order of these conditions:
          if (name.charAt(0) === "t" &&
              hasOwn.call(this, name) &&
              !isNaN(+name.slice(1))) {
            this[name] = undefined;
          }
        }
      }
    },

    stop: function() {
      this.done = true;

      var rootEntry = this.tryEntries[0];
      var rootRecord = rootEntry.completion;
      if (rootRecord.type === "throw") {
        throw rootRecord.arg;
      }

      return this.rval;
    },

    dispatchException: function(exception) {
      if (this.done) {
        throw exception;
      }

      var context = this;
      function handle(loc, caught) {
        record.type = "throw";
        record.arg = exception;
        context.next = loc;

        if (caught) {
          // If the dispatched exception was caught by a catch block,
          // then let that catch block handle the exception normally.
          context.method = "next";
          context.arg = undefined;
        }

        return !! caught;
      }

      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        var record = entry.completion;

        if (entry.tryLoc === "root") {
          // Exception thrown outside of any try block that could handle
          // it, so set the completion value of the entire function to
          // throw the exception.
          return handle("end");
        }

        if (entry.tryLoc <= this.prev) {
          var hasCatch = hasOwn.call(entry, "catchLoc");
          var hasFinally = hasOwn.call(entry, "finallyLoc");

          if (hasCatch && hasFinally) {
            if (this.prev < entry.catchLoc) {
              return handle(entry.catchLoc, true);
            } else if (this.prev < entry.finallyLoc) {
              return handle(entry.finallyLoc);
            }

          } else if (hasCatch) {
            if (this.prev < entry.catchLoc) {
              return handle(entry.catchLoc, true);
            }

          } else if (hasFinally) {
            if (this.prev < entry.finallyLoc) {
              return handle(entry.finallyLoc);
            }

          } else {
            throw new Error("try statement without catch or finally");
          }
        }
      }
    },

    abrupt: function(type, arg) {
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        if (entry.tryLoc <= this.prev &&
            hasOwn.call(entry, "finallyLoc") &&
            this.prev < entry.finallyLoc) {
          var finallyEntry = entry;
          break;
        }
      }

      if (finallyEntry &&
          (type === "break" ||
           type === "continue") &&
          finallyEntry.tryLoc <= arg &&
          arg <= finallyEntry.finallyLoc) {
        // Ignore the finally entry if control is not jumping to a
        // location outside the try/catch block.
        finallyEntry = null;
      }

      var record = finallyEntry ? finallyEntry.completion : {};
      record.type = type;
      record.arg = arg;

      if (finallyEntry) {
        this.method = "next";
        this.next = finallyEntry.finallyLoc;
        return ContinueSentinel;
      }

      return this.complete(record);
    },

    complete: function(record, afterLoc) {
      if (record.type === "throw") {
        throw record.arg;
      }

      if (record.type === "break" ||
          record.type === "continue") {
        this.next = record.arg;
      } else if (record.type === "return") {
        this.rval = this.arg = record.arg;
        this.method = "return";
        this.next = "end";
      } else if (record.type === "normal" && afterLoc) {
        this.next = afterLoc;
      }

      return ContinueSentinel;
    },

    finish: function(finallyLoc) {
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        if (entry.finallyLoc === finallyLoc) {
          this.complete(entry.completion, entry.afterLoc);
          resetTryEntry(entry);
          return ContinueSentinel;
        }
      }
    },

    "catch": function(tryLoc) {
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        if (entry.tryLoc === tryLoc) {
          var record = entry.completion;
          if (record.type === "throw") {
            var thrown = record.arg;
            resetTryEntry(entry);
          }
          return thrown;
        }
      }

      // The context.catch method must only be called with a location
      // argument that corresponds to a known catch block.
      throw new Error("illegal catch attempt");
    },

    delegateYield: function(iterable, resultName, nextLoc) {
      this.delegate = {
        iterator: values(iterable),
        resultName: resultName,
        nextLoc: nextLoc
      };

      if (this.method === "next") {
        // Deliberately forget the last sent value so that we don't
        // accidentally pass it on to the delegate.
        this.arg = undefined;
      }

      return ContinueSentinel;
    }
  };

  // Regardless of whether this script is executing as a CommonJS module
  // or not, return the runtime object so that we can declare the variable
  // regeneratorRuntime in the outer scope, which allows this module to be
  // injected easily by `bin/regenerator --include-runtime script.js`.
  return exports;

}(
  // If this script is executing as a CommonJS module, use module.exports
  // as the regeneratorRuntime namespace. Otherwise create a new empty
  // object. Either way, the resulting object will be used to initialize
  // the regeneratorRuntime variable at the top of this file.
   true ? module.exports : undefined
));

try {
  regeneratorRuntime = runtime;
} catch (accidentalStrictMode) {
  // This module should not be running in strict mode, so the above
  // assignment should always work unless something is misconfigured. Just
  // in case runtime.js accidentally runs in strict mode, we can escape
  // strict mode using a global Function call. This could conceivably fail
  // if a Content Security Policy forbids using Function, but in that case
  // the proper solution is to fix the accidental strict mode problem. If
  // you've misconfigured your bundler to force strict mode and applied a
  // CSP to forbid Function, and you're not willing to fix either of those
  // problems, please detail your unique predicament in a GitHub issue.
  Function("r", "regeneratorRuntime = r")(runtime);
}


/***/ }),

/***/ 6:
/*!**********************************************!*\
  !*** multi ./assets/src/js/global/global.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/global/global.js */"./assets/src/js/global/global.js");


/***/ })

/******/ });
//# sourceMappingURL=global-main.js.map