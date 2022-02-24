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
  var lazy_load_taxonomy_fields = directorist.lazy_load_taxonomy_fields;

  if (lazy_load_taxonomy_fields) {
    // Init Select2 Ajax Fields
    initSelect2AjaxFields();
  }
} // Init Select2 Ajax Fields


function initSelect2AjaxFields() {
  var rest_base_url = "".concat(directorist.rest_url, "directorist/v1"); // Init Select2 Ajax Category Field

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

  var is_script_debugging = directorist && directorist.script_debugging && directorist.script_debugging == '1' ? true : false;

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

/***/ "./assets/src/js/public/components/atbdSelect.js":
/*!*******************************************************!*\
  !*** ./assets/src/js/public/components/atbdSelect.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

//custom select
var atbdSelect = document.querySelectorAll('.atbd-drop-select');

if (atbdSelect !== null) {
  atbdSelect.forEach(function (el) {
    el.querySelectorAll('.atbd-dropdown-item').forEach(function (item) {
      item.addEventListener('click', function (e) {
        e.preventDefault();
        el.querySelector('.atbd-dropdown-toggle').textContent = item.textContent;
        el.querySelectorAll('.atbd-dropdown-item').forEach(function (elm) {
          elm.classList.remove('atbd-active');
        });
        item.classList.add('atbd-active');
      });
    });
  });
} // select data-status


var atbdSelectData = document.querySelectorAll('.atbd-drop-select.with-sort');
atbdSelectData.forEach(function (el) {
  el.querySelectorAll('.atbd-dropdown-item').forEach(function (item) {
    var ds = el.querySelector('.atbd-dropdown-toggle');
    var itemds = item.getAttribute('data-status');
    item.addEventListener('click', function (e) {
      ds.setAttribute('data-status', "".concat(itemds));
    });
  });
});

/***/ }),

/***/ "./assets/src/js/public/components/colorPicker.js":
/*!********************************************************!*\
  !*** ./assets/src/js/public/components/colorPicker.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/* Initialize wpColorPicker */
(function ($) {
  $(document).ready(function () {
    /* Initialize wp color picker */
    function colorPickerInit() {
      var wpColorPicker = document.querySelectorAll('.directorist-color-picker-wrap');
      wpColorPicker.forEach(function (elm) {
        if (elm !== null) {
          var dColorPicker = $('.directorist-color-picker');
          dColorPicker.value !== '' ? dColorPicker.wpColorPicker() : dColorPicker.wpColorPicker().empty();
        }
      });
    }

    colorPickerInit();
    /* Initialize on Directory type change */

    document.body.addEventListener('directorist-search-form-nav-tab-reloaded', colorPickerInit);
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/search-form.js":
/*!*********************************************!*\
  !*** ./assets/src/js/public/search-form.js ***!
  \*********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_atbdSelect__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/atbdSelect */ "./assets/src/js/public/components/atbdSelect.js");
/* harmony import */ var _components_atbdSelect__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_components_atbdSelect__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_colorPicker__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/colorPicker */ "./assets/src/js/public/components/colorPicker.js");
/* harmony import */ var _components_colorPicker__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_components_colorPicker__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _global_components_setup_select2__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./../global/components/setup-select2 */ "./assets/src/js/global/components/setup-select2.js");
/* harmony import */ var _global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./../global/components/select2-custom-control */ "./assets/src/js/global/components/select2-custom-control.js");
/* harmony import */ var _global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_3__);
//import './components/atbdDropdown';





(function ($) {
  /* ----------------
  Search Listings
  ------------------ */
  //ad search js
  $(".bads-custom-checks").parent(".form-group").addClass("ads-filter-tags");

  function defaultTags() {
    $('.directorist-btn-ml').each(function (index, element) {
      var item = $(element).siblings('.atbdp_cf_checkbox, .direcorist-search-field-tag, .directorist-search-tags');
      var abc2 = $(item).find('.directorist-checkbox');
      $(abc2).slice(4, abc2.length).fadeOut();
    });
  }

  $(window).on('load', defaultTags);
  window.addEventListener('triggerSlice', defaultTags);
  $('body').on('click', '.directorist-btn-ml', function (event) {
    event.preventDefault();
    var item = $(this).siblings('.atbdp_cf_checkbox, .direcorist-search-field-tag, .directorist-search-tags');
    var abc2 = $(item).find('.directorist-checkbox ');
    $(abc2).slice(4, abc2.length).fadeOut();
    $(this).toggleClass('active');

    if ($(this).hasClass('active')) {
      $(this).text(directorist.i18n_text.show_less);
      $(abc2).slice(4, abc2.length).fadeIn();
    } else {
      $(this).text(directorist.i18n_text.show_more);
      $(abc2).slice(4, abc2.length).fadeOut();
    }
  });
  /* Advanced search */

  var ad = $(".directorist-search-float .directorist-advanced-filter");
  ad.css({
    visibility: 'hidden',
    height: '0'
  });

  var adsFilterHeight = function adsFilterHeight() {
    return $('.directorist-advanced-filter .directorist-advanced-filter__action').innerHeight();
  };

  var adsItemsHeight;

  function getItemsHeight(selector) {
    var advElmHeight;
    var basicElmHeight;

    var adsAdvItemHeight = function adsAdvItemHeight() {
      return $(selector).closest('.directorist-search-form-box, .directorist-archive-contents, .directorist-search-form').find('.directorist-advanced-filter__advanced--element');
    };

    var adsBasicItemHeight = function adsBasicItemHeight() {
      return $(selector).closest('.directorist-search-form-box, .directorist-archive-contents').find('.directorist-advanced-filter__basic');
    };

    for (var i = 0; i <= adsAdvItemHeight().length; i++) {
      adsAdvItemHeight().length <= 1 ? advElmHeight = adsAdvItemHeight().innerHeight() : advElmHeight = adsAdvItemHeight().innerHeight() * i;
    }

    if (isNaN(advElmHeight)) {
      advElmHeight = 0;
    }

    var basicElmHeights = adsBasicItemHeight().innerHeight();
    basicElmHeights === undefined ? basicElmHeight = 0 : basicElmHeight = basicElmHeights;
    return adsItemsHeight = advElmHeight + basicElmHeight;
  }

  getItemsHeight('.directorist-filter-btn');
  var count = 0;
  $('body').on('click', '.directorist-listing-type-selection .search_listing_types, .directorist-type-nav .directorist-type-nav__link', function () {
    count = 0;
  });
  /* Toggle overlapped advanced filter wrapper */

  $('body').on("click", '.directorist-filter-btn', function (e) {
    count++;
    e.preventDefault();

    var _this = $(this);

    getItemsHeight(_this);

    _this.toggleClass('directorist-filter-btn--active');

    var currentPos = e.clientY,
        displayPos = window.innerHeight,
        height = displayPos - currentPos;
    var dafwrap = $(e.currentTarget).closest('.directorist-search-form, .directorist-archive-contents').find('.directorist-search-float').find('.directorist-advanced-filter');

    if (count % 2 === 0) {
      $(dafwrap).css({
        visibility: 'hidden',
        opacity: '0',
        height: '0',
        transition: '.3s ease'
      });
    } else {
      $(dafwrap).css({
        visibility: 'visible',
        height: adsItemsHeight + adsFilterHeight() + 50 + 'px',
        transition: '0.3s ease',
        opacity: '1'
      });
    }
  });
  /* Hide overlapped advanced filter */

  var daf = function daf() {
    return $('.directorist-search-float .directorist-advanced-filter');
  };

  $(document).on('click', function (e) {
    if (!e.target.closest('.directorist-search-form-top, .directorist-listings-header, .directorist-search-form') && !e.target.closest('.directorist-search-float .directorist-advanced-filter')) {
      count = 0;
      daf().css({
        visibility: 'hidden',
        opacity: '0',
        height: '0',
        transition: '.3s ease'
      });
    }
  });
  var ad_slide = $(".directorist-search-slide .directorist-advanced-filter");
  ad_slide.hide().slideUp();
  $('body').on("click", '.directorist-filter-btn', function (e) {
    e.preventDefault();
    $(this).closest('.directorist-search-form, .directorist-archive-contents').find('.directorist-search-slide').find('.directorist-advanced-filter').slideToggle().show();
    $(this).closest('.directorist-search-form, .directorist-archive-contents').find('.directorist-search-slide').find('.directorist-advanced-filter').toggleClass("directorist-advanced-filter--show");
    directorist_callingSlider();
  });
  $(".directorist-advanced-filter").parents("div").css("overflow", "visible"); //remove preload after window load

  $(window).on('load', function () {
    $("body").removeClass("directorist-preload");
    $('.button.wp-color-result').attr('style', ' ');
  });
  $('.directorist-mark-as-favorite__btn').each(function () {
    $(this).on('click', function (event) {
      event.preventDefault();
      var data = {
        'action': 'atbdp-favourites-all-listing',
        'post_id': $(this).data('listing_id')
      };
      var fav_tooltip_success = '<span>' + directorist.i18n_text.added_favourite + '</span>';
      var fav_tooltip_warning = '<span>' + directorist.i18n_text.please_login + '</span>';
      $(".directorist-favorite-tooltip").hide();
      $.post(directorist.ajax_url, data, function (response) {
        var post_id = data['post_id'].toString();
        var staElement = $('.directorist-fav_' + post_id);
        var data_id = staElement.attr('data-listing_id');

        if (response === "login_required") {
          staElement.children(".directorist-favorite-tooltip").append(fav_tooltip_warning);
          staElement.children(".directorist-favorite-tooltip").fadeIn();
          setTimeout(function () {
            staElement.children(".directorist-favorite-tooltip").children("span").remove();
          }, 3000);
        } else if ('false' === response) {
          staElement.removeClass('directorist-added-to-favorite');
          $(".directorist-favorite-tooltip span").remove();
        } else {
          if (data_id === post_id) {
            staElement.addClass('directorist-added-to-favorite');
            staElement.children(".directorist-favorite-tooltip").append(fav_tooltip_success);
            staElement.children(".directorist-favorite-tooltip").fadeIn();
            setTimeout(function () {
              staElement.children(".directorist-favorite-tooltip").children("span").remove();
            }, 3000);
          }
        }
      });
    });
  }); //reset fields

  function resetFields() {
    var inputArray = document.querySelectorAll('.search-area input');
    inputArray.forEach(function (input) {
      if (input.getAttribute("type") !== "hidden" || input.getAttribute("id") === "atbd_rs_value") {
        input.value = "";
      }
    });
    var textAreaArray = document.querySelectorAll('.search-area textArea');
    textAreaArray.forEach(function (textArea) {
      textArea.innerHTML = "";
    });
    var range = document.querySelector(".atbdpr-range .ui-slider-horizontal .ui-slider-range");
    var rangePos = document.querySelector(".atbdpr-range .ui-slider-horizontal .ui-slider-handle");
    var rangeAmount = document.querySelector(".atbdpr_amount");

    if (range) {
      range.setAttribute("style", "width: 0;");
    }

    if (rangePos) {
      rangePos.setAttribute("style", "left: 0;");
    }

    if (rangeAmount) {
      rangeAmount.innerText = "0 Mile";
    }

    var checkBoxes = document.querySelectorAll('.directorist-advanced-filter input[type="checkbox"]');
    checkBoxes.forEach(function (el, ind) {
      el.checked = false;
    });
    var radios = document.querySelectorAll('.directorist-advanced-filter input[type="radio"]');
    radios.forEach(function (el, ind) {
      el.checked = false;
    });
    $('.search-area select').prop('selectedIndex', 0);
    $(".bdas-location-search, .bdas-category-search").val('').trigger('change');
  }

  $("body").on("click", ".atbd_widget .directorist-advanced-filter #atbdp_reset", function (e) {
    e.preventDefault();
    resetFields();
  });
  /* advanced search form reset */

  function adsFormReset(searchForm) {
    searchForm.querySelectorAll("input[type='text']").forEach(function (el) {
      el.value = "";
    });
    searchForm.querySelectorAll("input[type='date']").forEach(function (el) {
      el.value = "";
    });
    searchForm.querySelectorAll("input[type='time']").forEach(function (el) {
      el.value = "";
    });
    searchForm.querySelectorAll("input[type='url']").forEach(function (el) {
      el.value = "";
    });
    searchForm.querySelectorAll("input[type='number']").forEach(function (el) {
      el.value = "";
    });
    searchForm.querySelectorAll("input[type='radio']").forEach(function (el) {
      el.checked = false;
    });
    searchForm.querySelectorAll("input[type='checkbox']").forEach(function (el) {
      el.checked = false;
    });
    searchForm.querySelectorAll("select").forEach(function (el) {
      el.selectedIndex = 0;
      $(el).val('').trigger('change');
    });
    var irisPicker = searchForm.querySelector("input.wp-picker-clear");

    if (irisPicker !== null) {
      irisPicker.click();
    }

    var rangeValue = searchForm.querySelector(".directorist-range-slider-current-value span");

    if (rangeValue !== null) {
      rangeValue.innerHTML = "0";
    }
  }
  /* Advance Search Filter For Search Home Short Code */


  if ($(".directorist-search-form .directorist-btn-reset-js") !== null) {
    $("body").on("click", ".directorist-search-form .directorist-btn-reset-js", function (e) {
      e.preventDefault();

      if (this.closest('.directorist-search-contents')) {
        var searchForm = this.closest('.directorist-search-contents').querySelector('.directorist-search-form');

        if (searchForm) {
          adsFormReset(searchForm);
        }
      }

      directorist_callingSlider(0);
    });
  }
  /* All Listing Advance Filter */


  if ($(".directorist-advanced-filter__form .directorist-btn-reset-js") !== null) {
    $("body").on("click", ".directorist-advanced-filter__form .directorist-btn-reset-js", function (e) {
      e.preventDefault();

      if (this.closest('.directorist-advanced-filter')) {
        var searchForm = this.closest('.directorist-advanced-filter').querySelector('.directorist-advanced-filter__form');

        if (searchForm) {
          adsFormReset(searchForm);
        }
      }

      directorist_callingSlider(0);
    });
  }

  if ($("#bdlm-search-area #atbdp_reset") !== null) {
    $("body").on("click", "#bdlm-search-area #atbdp_reset", function (e) {
      e.preventDefault();

      if (this.closest('.directorist-search-contents')) {
        var searchForm = this.closest('.directorist-search-contents').querySelector('.directorist-search-form');

        if (searchForm) {
          adsFormReset(searchForm);
        }
      }

      if (this.closest('.directorist-advanced-filter')) {
        var _searchForm = this.closest('.directorist-advanced-filter').querySelector('.directorist-advanced-filter__form');

        if (_searchForm) {
          adsFormReset(_searchForm);
        }
      }

      directorist_callingSlider(0);
    });
  }
  /* Map Listing Search Form */


  if ($("#directorist-search-area .directorist-btn-reset-js") !== null) {
    $("body").on("click", "#directorist-search-area .directorist-btn-reset-js", function (e) {
      e.preventDefault();

      if (this.closest('#directorist-search-area')) {
        var searchForm = this.closest('#directorist-search-area').querySelector('#directorist-search-area-form');

        if (searchForm) {
          adsFormReset(searchForm);
        }
      }

      directorist_callingSlider(0);
    });
  }
  /* Single Listing widget Form */


  if ($(".atbd_widget .search-area .directorist-btn-reset-js") !== null) {
    $("body").on("click", ".atbd_widget .search-area .directorist-btn-reset-js", function (e) {
      e.preventDefault();

      if (this.closest('.search-area')) {
        var searchForm = this.closest('.search-area').querySelector('.directorist-advanced-filter__form');

        if (searchForm) {
          adsFormReset(searchForm);
        }
      }

      directorist_callingSlider(0);
    });
  }
  /* ----------------
  Search-form-listing
  ------------------- */


  $('body').on('click', '.search_listing_types', function (event) {
    // console.log($('.directorist-search-contents'));
    event.preventDefault();
    var parent = $(this).closest('.directorist-search-contents');
    var listing_type = $(this).attr('data-listing_type');
    var type_current = parent.find('.directorist-listing-type-selection__link--current');

    if (type_current.length) {
      type_current.removeClass('directorist-listing-type-selection__link--current');
      $(this).addClass('directorist-listing-type-selection__link--current');
    }

    parent.find('.listing_type').val(listing_type);
    var form_data = new FormData();
    form_data.append('action', 'atbdp_listing_types_form');
    form_data.append('listing_type', listing_type);
    var atts = parent.attr('data-atts');
    var atts_decoded = btoa(atts);
    form_data.append('atts', atts_decoded);
    parent.find('.directorist-search-form-box').addClass('atbdp-form-fade');
    $.ajax({
      method: 'POST',
      processData: false,
      contentType: false,
      url: directorist.ajax_url,
      data: form_data,
      success: function success(response) {
        if (response) {
          // Add Temp Element
          var new_inserted_elm = '<div class="directorist_search_temp"><div>';
          parent.before(new_inserted_elm); // Remove Old Parent

          parent.remove(); // Insert New Parent

          $('.directorist_search_temp').after(response['search_form']);
          var newParent = $('.directorist_search_temp').next(); // Toggle Active Class

          newParent.find('.directorist-listing-type-selection__link--current').removeClass('directorist-listing-type-selection__link--current');
          newParent.find("[data-listing_type='" + listing_type + "']").addClass('directorist-listing-type-selection__link--current'); // Remove Temp Element

          $('.directorist_search_temp').remove();
          var events = [new CustomEvent('directorist-search-form-nav-tab-reloaded'), new CustomEvent('directorist-reload-select2-fields'), new CustomEvent('directorist-reload-map-api-field')];
          events.forEach(function (event) {
            document.body.dispatchEvent(event);
            window.dispatchEvent(event);
          });
        }

        parent.find('.directorist-search-form-box').removeClass('atbdp-form-fade');
        directorist_callingSlider();
      },
      error: function error(_error) {
        console.log(_error);
      }
    });
  }); // Advance search
  // Populate atbdp child terms dropdown

  $('.bdas-terms').on('change', 'select', function (e) {
    e.preventDefault();
    var $this = $(this);
    var taxonomy = $this.data('taxonomy');
    var parent = $this.data('parent');
    var value = $this.val();
    var classes = $this.attr('class');
    $this.closest('.bdas-terms').find('input.bdas-term-hidden').val(value);
    $this.parent().find('div:first').remove();

    if (parent != value) {
      $this.parent().append('<div class="bdas-spinner"></div>');
      var data = {
        action: 'bdas_public_dropdown_terms',
        taxonomy: taxonomy,
        parent: value,
        class: classes,
        security: directorist.ajaxnonce
      };
      $.post(directorist.ajax_url, data, function (response) {
        $this.parent().find('div:first').remove();
        $this.parent().append(response);
      });
    }
  }); // load custom fields of the selected category in the search form

  $('body').on('change', '.bdas-category-search, .directorist-category-select', function () {
    var $search_elem = $(this).closest('form').find('.atbdp-custom-fields-search');

    if ($search_elem.length) {
      $search_elem.html('<div class="atbdp-spinner"></div>');
      var data = {
        action: 'atbdp_custom_fields_search',
        term_id: $(this).val(),
        security: directorist.ajaxnonce
      };
      $.post(directorist.ajax_url, data, function (response) {
        $search_elem.html(response);
        var item = $('.custom-control').closest('.bads-custom-checks');
        item.each(function (index, el) {
          var count = 0;
          var abc = $(el)[0];
          var abc2 = $(abc).children('.custom-control');

          if (abc2.length <= 4) {
            $(abc2).closest('.bads-custom-checks').next('a.more-or-less').hide();
          }

          $(abc2).slice(4, abc2.length).hide();
        });
      });
    }
  });
  $('.address_result').hide();
  window.addEventListener('load', init_map_api_field);
  document.body.addEventListener('directorist-reload-map-api-field', init_map_api_field);

  function init_map_api_field() {
    if (directorist.i18n_text.select_listing_map === 'google') {
      function initialize() {
        var opt = {
          types: ['geocode'],
          componentRestrictions: {
            country: directorist.restricted_countries
          }
        };
        var options = directorist.countryRestriction ? opt : '';
        var input_fields = [{
          input_class: '.directorist-location-js',
          lat_id: 'cityLat',
          lng_id: 'cityLng',
          options: options
        }, {
          input_id: 'address_widget',
          lat_id: 'cityLat',
          lng_id: 'cityLng',
          options: options
        }];

        var setupAutocomplete = function setupAutocomplete(field) {
          var input = document.querySelectorAll(field.input_class);
          input.forEach(function (elm) {
            if (!elm) {
              return;
            }

            var autocomplete = new google.maps.places.Autocomplete(elm, field.options);
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
              var place = autocomplete.getPlace();
              document.getElementById(field.lat_id).value = place.geometry.location.lat();
              document.getElementById(field.lng_id).value = place.geometry.location.lng();
            });
          });
        };

        input_fields.forEach(function (field) {
          setupAutocomplete(field);
        });
      }

      initialize();
    } else if (directorist.i18n_text.select_listing_map === 'openstreet') {
      var getResultContainer = function getResultContainer(context, field) {
        return $(context).next(field.search_result_elm);
      };

      var getWidgetResultContainer = function getWidgetResultContainer(context, field) {
        return $(context).parent().next(field.search_result_elm);
      };

      var input_fields = [{
        input_elm: '.directorist-location-js',
        search_result_elm: '.address_result',
        getResultContainer: getResultContainer
      }, {
        input_elm: '#q_addressss',
        search_result_elm: '.address_result',
        getResultContainer: getResultContainer
      }, {
        input_elm: '.atbdp-search-address',
        search_result_elm: '.address_result',
        getResultContainer: getResultContainer
      }, {
        input_elm: '#address_widget',
        search_result_elm: '#address_widget_result',
        getResultContainer: getWidgetResultContainer
      }];
      input_fields.forEach(function (field) {
        if (!$(field.input_elm).length) {
          return;
        }

        $(field.input_elm).on('keyup', function (event) {
          event.preventDefault();
          var search = $(this).val();
          var result_container = field.getResultContainer(this, field);
          result_container.css({
            display: 'block'
          });

          if (search === '') {
            result_container.css({
              display: 'none'
            });
          }

          var res = '';
          $.ajax({
            url: "https://nominatim.openstreetmap.org/?q=%27+".concat(search, "+%27&format=json"),
            type: 'POST',
            data: {},
            success: function success(data) {
              for (var i = 0; i < data.length; i++) {
                res += "<li><a href=\"#\" data-lat=".concat(data[i].lat, " data-lon=").concat(data[i].lon, ">").concat(data[i].display_name, "</a></li>");
              }

              result_container.html("<ul>".concat(res, "</ul>"));

              if (res.length) {
                result_container.show();
              } else {
                result_container.hide();
              }
            },
            error: function error(_error2) {
              console.log({
                error: _error2
              });
            }
          });
        });
      }); // hide address result when click outside the input field

      $(document).on('click', function (e) {
        if (!$(e.target).closest('.directorist-location-js, #q_addressss, .atbdp-search-address').length) {
          $('.address_result').hide();
        }
      });

      var syncLatLngData = function syncLatLngData(context, event, args) {
        event.preventDefault();
        var text = $(context).text();
        var lat = $(context).data('lat');
        var lon = $(context).data('lon');
        $('#cityLat').val(lat);
        $('#cityLng').val(lon);
        var inp = $(context).closest(args.result_list_container).parent().find('.directorist-location-js, #address_widget, #q_addressss, .atbdp-search-address');
        inp.val(text);
        $(args.result_list_container).hide();
      };

      $('body').on('click', '.address_result ul li a', function (event) {
        syncLatLngData(this, event, {
          result_list_container: '.address_result'
        });
      });
      $('body').on('click', '#address_widget_result ul li a', function (event) {
        syncLatLngData(this, event, {
          result_list_container: '#address_widget_result'
        });
      });
    }

    if ($('.directorist-location-js, #q_addressss,.atbdp-search-address').val() === '') {
      $(this).parent().next('.address_result').css({
        display: 'none'
      });
    }
  }
})(jQuery);

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

/***/ 2:
/*!***************************************************!*\
  !*** multi ./assets/src/js/public/search-form.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/public/search-form.js */"./assets/src/js/public/search-form.js");


/***/ })

/******/ });
//# sourceMappingURL=search-form.js.map