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

function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
var $ = jQuery;
window.addEventListener('load', waitAndInit);
window.addEventListener('directorist-search-form-nav-tab-reloaded', waitAndInit);
window.addEventListener('directorist-type-change', waitAndInit);
window.addEventListener('directorist-instant-search-reloaded', waitAndInit);
function waitAndInit() {
  setTimeout(init, 0);
}

// Initialize
function init() {
  // Add custom dropdown toggle button
  selec2_add_custom_dropdown_toggle_button();

  // Add custom close button where needed
  selec2_add_custom_close_button_if_needed();

  // Add custom close button if field contains value on change
  $('.select2-hidden-accessible').on('change', function (e) {
    var value = $(this).children("option:selected").val();
    if (!value) {
      return;
    }
    selec2_add_custom_close_button($(this));
    var selectItems = this.parentElement.querySelectorAll('.select2-selection__choice');
    selectItems.forEach(function (item) {
      item.childNodes && item.childNodes.forEach(function (node) {
        if (node.nodeType && node.nodeType === Node.TEXT_NODE) {
          var originalString = node.textContent;
          var modifiedString = originalString.replace(/^[\s\xa0]+/, '');
          node.textContent = modifiedString;
          item.title = modifiedString;
        }
      });
    });
    var customSelectItem = this.parentElement.querySelector('.select2-selection__rendered');
    customSelectItem.childNodes && customSelectItem.childNodes.forEach(function (node) {
      if (node.nodeType && node.nodeType === Node.TEXT_NODE) {
        var originalString = node.textContent;
        var modifiedString = originalString.replace(/^[\s\xa0]+/, '');
        node.textContent = modifiedString;
      }
    });
  });
}
function selec2_add_custom_dropdown_toggle_button() {
  // Remove Default
  $('.select2-selection__arrow').css({
    'display': 'none'
  });
  var addon_container = selec2_get_addon_container('.select2-hidden-accessible');
  if (!addon_container) {
    return;
  }
  var dropdown = addon_container.find('.directorist-select2-dropdown-toggle');
  if (!dropdown.length) {
    // Add Dropdown Toggle Button
    var iconURL = directorist.assets_url + 'icons/font-awesome/svgs/solid/chevron-down.svg';
    var iconHTML = directorist.icon_markup.replace('##URL##', iconURL).replace('##CLASS##', '');
    var dropdownHTML = "<span class=\"directorist-select2-addon directorist-select2-dropdown-toggle\">".concat(iconHTML, "</span>");
    addon_container.append(dropdownHTML);
  }
  var selec2_custom_dropdown = addon_container.find('.directorist-select2-dropdown-toggle');

  // Toggle --is-open class
  $('.select2-hidden-accessible').on('select2:open', function (e) {
    var dropdown_btn = $(this).next().find('.directorist-select2-dropdown-toggle');
    dropdown_btn.addClass('--is-open');
  });
  $('.select2-hidden-accessible').on('select2:close', function (e) {
    var dropdown_btn = $(this).next().find('.directorist-select2-dropdown-toggle');
    dropdown_btn.removeClass('--is-open');
  });

  // Toggle Dropdown
  selec2_custom_dropdown.on('click', function (e) {
    var isOpen = $(this).hasClass('--is-open');
    var field = $(this).closest(".select2-container").siblings('select:enabled');
    if (isOpen) {
      field.select2('close');
    } else {
      field.select2('open');
    }
  });

  // Adjust space for addons
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
      var value = $(field).children('option:selected').val();
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
  }

  // Remove if already exists
  addon_container.find('.directorist-select2-dropdown-close').remove();

  // Add
  var iconURL = directorist.assets_url + 'icons/font-awesome/svgs/solid/times.svg';
  var iconHTML = directorist.icon_markup.replace('##URL##', iconURL).replace('##CLASS##', '');
  addon_container.prepend("<span class=\"directorist-select2-addon directorist-select2-dropdown-close\">".concat(iconHTML, "</span>"));
  var selec2_custom_close = addon_container.find('.directorist-select2-dropdown-close');
  selec2_custom_close.on('click', function (e) {
    var field = $(this).closest('.select2-container').siblings('select:enabled');
    field.val(null).trigger('change');
    addon_container.find('.directorist-select2-dropdown-close').remove();
    selec2_adjust_space_for_addons();
  });

  // Adjust space for addons
  selec2_adjust_space_for_addons();
}
function selec2_remove_custom_close_button(field) {
  var addon_container = selec2_get_addon_container(field);
  if (!(addon_container && addon_container.length)) {
    return;
  }

  // Remove
  addon_container.find('.directorist-select2-dropdown-close').remove();

  // Adjust space for addons
  selec2_adjust_space_for_addons();
}
function selec2_get_addon_container(field) {
  var container = field ? $(field).next('.select2-container') : $('.select2-container');
  container = $(container).find('.directorist-select2-addons-area');
  if (!container.length) {
    $('.select2-container').append('<span class="directorist-select2-addons-area"></span>');
    container = $('.select2-container').find('.directorist-select2-addons-area');
  }
  var container = field ? $(field).next('.select2-container') : null;
  if (!container) {
    return null;
  }
  var addonsArea = $(container).find('.directorist-select2-addons-area');
  if (!addonsArea.length) {
    container.append('<span class="directorist-select2-addons-area"></span>');
    return container.find('.directorist-select2-addons-area');
  }
  return addonsArea;
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
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ "./node_modules/@babel/runtime/helpers/toConsumableArray.js");
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./../../lib/helper */ "./assets/src/js/lib/helper.js");
/* harmony import */ var _select2_custom_control__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./select2-custom-control */ "./assets/src/js/global/components/select2-custom-control.js");
/* harmony import */ var _select2_custom_control__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_select2_custom_control__WEBPACK_IMPORTED_MODULE_3__);


function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }


var $ = jQuery;
window.addEventListener('load', initSelect2);
document.body.addEventListener('directorist-search-form-nav-tab-reloaded', initSelect2);
document.body.addEventListener('directorist-reload-select2-fields', initSelect2);

// Init Static Select 2 Fields
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
  },
  // { elm: $('#directorist-location-select') },
  // { elm: $('#directorist-category-select') },
  {
    elm: $('.select-basic')
  }, {
    elm: $('#loc-type')
  }, {
    elm: $('.bdas-location-search')
  },
  // { elm: $('.directorist-location-select') },
  {
    elm: $('#at_biz_dir-category')
  }, {
    elm: $('#cat-type')
  }, {
    elm: $('.bdas-category-search')
  }
  // { elm: $('.directorist-category-select') },
  ];

  select_fields.forEach(function (field) {
    Object(_lib_helper__WEBPACK_IMPORTED_MODULE_2__["convertToSelect2"])(field);
  });
  var lazy_load_taxonomy_fields = directorist.lazy_load_taxonomy_fields;
  if (lazy_load_taxonomy_fields) {
    // Init Select2 Ajax Fields
    initSelect2AjaxFields();
  }
}

// Init Select2 Ajax Fields
function initSelect2AjaxFields() {
  var rest_base_url = "".concat(directorist.rest_url, "directorist/v1");

  // Init Select2 Ajax Category Field
  initSelect2AjaxTaxonomy({
    selector: $('.directorist-search-category').find('select'),
    url: "".concat(rest_base_url, "/listings/categories")
  });
  initSelect2AjaxTaxonomy({
    selector: $('.directorist-form-categories-field').find('select'),
    url: "".concat(rest_base_url, "/listings/categories")
  });

  // Init Select2 Ajax Location Field
  initSelect2AjaxTaxonomy({
    selector: $('.directorist-search-location').find('select'),
    url: "".concat(rest_base_url, "/listings/locations")
  });
  initSelect2AjaxTaxonomy({
    selector: $('.directorist-form-location-field').find('select'),
    url: "".concat(rest_base_url, "/listings/locations")
  });

  // Init Select2 Ajax Tag Field
  initSelect2AjaxTaxonomy({
    selector: $('.directorist-form-tag-field').find('select'),
    url: "".concat(rest_base_url, "/listings/tags")
  }, {
    has_directory_type: false
  });
}

// initSelect2AjaxTaxonomy
function initSelect2AjaxTaxonomy(args, terms_options) {
  var defaultArgs = {
    selector: '',
    url: '',
    perPage: 10
  };
  args = _objectSpread(_objectSpread({}, defaultArgs), args);
  var default_terms_options = {
    has_directory_type: true
  };
  terms_options = terms_options ? _objectSpread(_objectSpread({}, default_terms_options), terms_options) : default_terms_options;
  if (!args.selector.length) {
    return;
  }
  _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(args.selector).forEach(function (item, index) {
    var directory_type_id = 0;
    var createNew = item.getAttribute("data-allow_new");
    var maxLength = item.getAttribute("data-max");
    if (terms_options.has_directory_type) {
      var search_form_parent = $(item).closest('.directorist-search-form');
      var archive_page_parent = $(item).closest('.directorist-archive-contents');
      var add_listing_form_hidden_input = $(item).closest('.directorist-add-listing-form').find('input[name="directory_type"]');
      var nav_list_item = [];

      // If search page
      if (search_form_parent.length) {
        nav_list_item = search_form_parent.find('.directorist-listing-type-selection__link--current');
      }

      // If archive page
      if (archive_page_parent.length) {
        nav_list_item = archive_page_parent.find('.directorist-type-nav__list li.current .directorist-type-nav__link');
      }

      // If has nav item
      if (nav_list_item.length) {
        directory_type_id = nav_list_item ? nav_list_item.data('listing_type_id') : 0;
      }

      // If has nav item
      if (add_listing_form_hidden_input.length) {
        directory_type_id = add_listing_form_hidden_input.val();
      }
      if (directory_type_id) {
        directory_type_id = parseInt(directory_type_id);
      }
    }
    var currentPage = 1;
    $(item).select2({
      allowClear: true,
      tags: createNew,
      maximumSelectionLength: maxLength,
      width: '100%',
      escapeMarkup: function escapeMarkup(text) {
        return text;
      },
      ajax: {
        url: args.url,
        dataType: 'json',
        cache: true,
        delay: 250,
        data: function data(params) {
          currentPage = params.page || 1;
          var search_term = params.term ? params.term : '';
          var query = {
            search: search_term,
            page: currentPage,
            per_page: args.perPage
          };
          if (directory_type_id) {
            query.directory = directory_type_id;
          }
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

    // Setup Preselected Option
    var selected_item_id = $(item).data('selected-id');
    var selected_item_label = $(item).data('selected-label');
    var setup_selected_items = function setup_selected_items(element, selected_id, selected_label) {
      if (!element || !selected_id) {
        return;
      }
      var selected_ids = "".concat(selected_id).split(',');
      var selected_labels = selected_label ? "".concat(selected_label).split(',') : [];
      selected_ids.forEach(function (id, index) {
        var label = selected_labels.length >= index + 1 ? selected_labels[index] : '';
        var option = new Option(label, id, true, true);
        $(element).append(option);
        $(element).trigger({
          type: 'select2:select',
          params: {
            data: {
              id: id,
              text: selected_item_label
            }
          }
        });
      });
    };
    setup_selected_items(item, selected_item_id, selected_item_label);
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
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ "./node_modules/@babel/runtime/helpers/toConsumableArray.js");
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__);


var $ = jQuery;
function get_dom_data(key, parent) {
  // var elmKey = 'directorist-dom-data-' + key;
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
      console.warn({
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
  _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1___default()(field.elm).forEach(function (item) {
    var default_args = {
      allowClear: true,
      width: '100%',
      templateResult: function templateResult(data) {
        if (!data.id) {
          return data.text;
        }
        var iconURI = $(data.element).data('icon');
        var iconElm = "<i class=\"directorist-icon-mask\" aria-hidden=\"true\" style=\"--directorist-icon: url(".concat(iconURI, ")\"></i>");
        var originalText = data.text;
        var modifiedText = originalText.replace(/^(\s*)/, "$1" + iconElm);
        var $state = $("<div class=\"directorist-select2-contents\">".concat(typeof iconURI !== 'undefined' && iconURI !== '' ? modifiedText : originalText, "</div>"));
        return $state;
      }
    };
    var args = field.args && _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default()(field.args) === 'object' ? Object.assign(default_args, field.args) : default_args;
    var options = $(item).find('option');
    var placeholder = options.length ? options[0].innerHTML : '';
    if (placeholder.length) {
      args.placeholder = placeholder;
    }
    $(item).select2(args);
  });
}


/***/ }),

/***/ "./assets/src/js/public/components/colorPicker.js":
/*!********************************************************!*\
  !*** ./assets/src/js/public/components/colorPicker.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/* Initialize wpColorPicker */
(function ($) {
  // Make sure the codes in this file runs only once, even if enqueued twice
  if (typeof window.directorist_colorPicker_executed === 'undefined') {
    window.directorist_colorPicker_executed = true;
  } else {
    return;
  }
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

/***/ "./assets/src/js/public/components/directoristDropdown.js":
/*!****************************************************************!*\
  !*** ./assets/src/js/public/components/directoristDropdown.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;
(function ($) {
  // Make sure the codes in this file runs only once, even if enqueued twice
  if (typeof window.directorist_dropdown_executed === 'undefined') {
    window.directorist_dropdown_executed = true;
  } else {
    return;
  }
  window.addEventListener('load', function () {
    /* custom dropdown */
    var atbdDropdown = document.querySelectorAll('.directorist-dropdown-select');

    // toggle dropdown
    var clickCount = 0;
    if (atbdDropdown !== null) {
      atbdDropdown.forEach(function (el) {
        el.querySelector('.directorist-dropdown-select-toggle').addEventListener('click', function (e) {
          e.preventDefault();
          clickCount++;
          if (clickCount % 2 === 1) {
            document.querySelectorAll('.directorist-dropdown-select-items').forEach(function (elem) {
              elem.classList.remove('directorist-dropdown-select-show');
            });
            el.querySelector('.directorist-dropdown-select-items').classList.add('directorist-dropdown-select-show');
          } else {
            document.querySelectorAll('.directorist-dropdown-select-items').forEach(function (elem) {
              elem.classList.remove('directorist-dropdown-select-show');
            });
          }
        });
      });
    }

    // remvoe toggle when click outside
    document.body.addEventListener('click', function (e) {
      if (e.target.getAttribute('data-drop-toggle') !== 'directorist-dropdown-select-toggle') {
        clickCount = 0;
        document.querySelectorAll('.directorist-dropdown-select-items').forEach(function (el) {
          el.classList.remove('directorist-dropdown-select-show');
        });
      }
    });

    //custom select
    var atbdSelect = document.querySelectorAll('.atbd-drop-select');
    if (atbdSelect !== null) {
      atbdSelect.forEach(function (el) {
        el.querySelectorAll('.directorist-dropdown-select-items').forEach(function (item) {
          item.addEventListener('click', function (e) {
            e.preventDefault();
            el.querySelector('.directorist-dropdown-select-toggle').textContent = e.target.textContent;
            el.querySelectorAll('.directorist-dropdown-select-items').forEach(function (elm) {
              elm.classList.remove('atbd-active');
            });
            item.classList.add('atbd-active');
          });
        });
      });
    }

    // Dropdown
    $('body').on('click', '.directorist-dropdown .directorist-dropdown-toggle', function (e) {
      e.preventDefault();
      $(this).siblings('.directorist-dropdown-option').toggle();
    });

    // Select Option after click
    $('body').on('click', '.directorist-dropdown .directorist-dropdown-option ul li a', function (e) {
      e.preventDefault();
      var optionText = $(this).html();
      $(this).children('.directorist-dropdown-toggle__text').html(optionText);
      $(this).closest('.directorist-dropdown-option').siblings('.directorist-dropdown-toggle').children('.directorist-dropdown-toggle__text').html(optionText);
      $('.directorist-dropdown-option').hide();
    });

    // Hide Clicked Anywhere
    $(document).bind('click', function (e) {
      var clickedDOM = $(e.target);
      if (!clickedDOM.parents().hasClass('directorist-dropdown')) $('.directorist-dropdown-option').hide();
    });

    //atbd_dropdown
    $(document).on("click", '.atbd_dropdown', function (e) {
      if ($(this).attr("class") === "atbd_dropdown") {
        e.preventDefault();
        $(this).siblings(".atbd_dropdown").removeClass("atbd_drop--active");
        $(this).toggleClass("atbd_drop--active");
        e.stopPropagation();
      }
    });
    $(document).on("click", function (e) {
      if ($(e.target).is(".atbd_dropdown, .atbd_drop--active") === false) {
        $(".atbd_dropdown").removeClass("atbd_drop--active");
      }
    });
    $('body').on('click', '.atbd_dropdown-toggle', function (e) {
      e.preventDefault();
    });

    // Directorist Dropdown
    $('body').on('click', '.directorist-dropdown-js .directorist-dropdown__toggle-js', function (e) {
      e.preventDefault();
      if (!$(this).siblings('.directorist-dropdown__links-js').is(':visible')) {
        $('.directorist-dropdown__links').hide();
      }
      $(this).siblings('.directorist-dropdown__links-js').toggle();
    });
    $('body').on('click', function (e) {
      if (!e.target.closest('.directorist-dropdown-js')) {
        $('.directorist-dropdown__links-js').hide();
      }
    });
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/directoristSelect.js":
/*!**************************************************************!*\
  !*** ./assets/src/js/public/components/directoristSelect.js ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

window.addEventListener('DOMContentLoaded', function () {
  // Make sure the codes in this file runs only once, even if enqueued twice
  if (typeof window.directorist_select_executed === 'undefined') {
    window.directorist_select_executed = true;
  } else {
    return;
  }
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
  }

  // select data-status
  var atbdSelectData = document.querySelectorAll('.atbd-drop-select.with-sort');
  atbdSelectData.forEach(function (el) {
    el.querySelectorAll('.atbd-dropdown-item').forEach(function (item) {
      var atbd_dropdown = el.querySelector('.atbd-dropdown-toggle');
      var dropdown_item = item.getAttribute('data-status');
      item.addEventListener('click', function (e) {
        atbd_dropdown.setAttribute('data-status', "".concat(dropdown_item));
      });
    });
  });
});

/***/ }),

<<<<<<< HEAD
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
  }

  //RTL
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
    var sliderValue = id.closest('.directorist-range-slider-wrap').querySelector('.directorist-range-slider-value').value;
    var x = null,
      count = 0,
      slid1_val = sliderValue,
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
    barColor: '#d9d9d9',
    barBorder: 'none',
    pointerColor: '#fff',
    pointerBorder: '4px solid #404040'
  };
  var config = directorist.slider_config && _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default()(directorist.slider_config) === 'object' ? Object.assign(default_args, directorist.slider_config) : default_args;
  directorist_range_slider('.directorist-range-slider', config);
}
window.addEventListener("load", function () {
  directorist_callingSlider();
});

/***/ }),

=======
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
/***/ "./assets/src/js/public/search-form.js":
/*!*********************************************!*\
  !*** ./assets/src/js/public/search-form.js ***!
  \*********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../global/components/select2-custom-control */ "./assets/src/js/global/components/select2-custom-control.js");
/* harmony import */ var _global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _global_components_setup_select2__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./../global/components/setup-select2 */ "./assets/src/js/global/components/setup-select2.js");
/* harmony import */ var _components_colorPicker__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/colorPicker */ "./assets/src/js/public/components/colorPicker.js");
/* harmony import */ var _components_colorPicker__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_components_colorPicker__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _components_directoristDropdown__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/directoristDropdown */ "./assets/src/js/public/components/directoristDropdown.js");
/* harmony import */ var _components_directoristDropdown__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_components_directoristDropdown__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _components_directoristSelect__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./components/directoristSelect */ "./assets/src/js/public/components/directoristSelect.js");
/* harmony import */ var _components_directoristSelect__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_components_directoristSelect__WEBPACK_IMPORTED_MODULE_4__);
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
<<<<<<< HEAD


=======
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c





(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    //Remove Preload after Window Load
    $(window).on('load', function () {
      $('body').removeClass("directorist-preload");
      $('.button.wp-color-result').attr('style', ' ');
    });

    /* ----------------
    Search Form
    ------------------ */

<<<<<<< HEAD
    //ad search js

=======
    // Default Tags Slice
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
    function defaultTags() {
      $('.directorist-btn-ml').each(function (index, element) {
        var item = $(element).siblings('.atbdp_cf_checkbox, .directorist-search-field-tag, .directorist-search-tags');
        var item_checkbox = $(item).find('.directorist-checkbox');
        $(item_checkbox).slice(4, item_checkbox.length).fadeOut();
        if (item_checkbox.length <= 4) {
          $(element).css('display', 'none');
        }
      });
    }
    $(window).on('load', defaultTags);
    window.addEventListener('triggerSlice', defaultTags);

    // See More Tags Button
    $('body').on('click', '.directorist-btn-ml', function (event) {
      event.preventDefault();
      var item = $(this).siblings('.directorist-search-tags');
      var item_checkbox = $(item).find('.directorist-checkbox');
      $(item_checkbox).slice(4, item_checkbox.length).fadeOut();
      $(this).toggleClass('active');
      if ($(this).hasClass('active')) {
        $(this).text(directorist.i18n_text.show_less);
        $(item_checkbox).slice(4, item_checkbox.length).fadeIn();
      } else {
        $(this).text(directorist.i18n_text.show_more);
        $(item_checkbox).slice(4, item_checkbox.length).fadeOut();
      }
    });
<<<<<<< HEAD

    //remove preload after window load
    $(window).on('load', function () {
      $("body").removeClass("directorist-preload");
      $('.button.wp-color-result').attr('style', ' ');
    });

    // Search Form

=======

    // Search Form

    // Count Checkbox Selected Items
    function selectedItemCount(item) {
      var dropdownParent = $(item).closest('.directorist-search-field');
      var dropDownContent = $(item).closest('.directorist-search-basic-dropdown-content');
      var selectedItemCount = dropDownContent.find('.directorist-checkbox input[type="checkbox"]:checked');
      var selectedPrefix = dropDownContent.siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-prefix');
      var selectedCounter = dropDownContent.siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-count');
      if (selectedItemCount.length > 0) {
        selectedCounter.text(selectedItemCount.length);
        selectedPrefix.text('Selected');
        dropdownParent.addClass('input-has-value');
      } else {
        // If no items are checked, clear the text
        selectedCounter.text('');
        selectedPrefix.text('');
        dropdownParent.removeClass('input-has-value');
      }
    }

    // Radio Selected Items
    function selectedRadioItem(item) {
      var dropdownParent = $(item).closest('.directorist-search-field');
      var dropDownLabel = dropdownParent.find('.directorist-search-basic-dropdown-selected-item');
      var selectedItem = dropdownParent.find('.directorist-radio input[type="radio"]:checked');
      var selectedItemLabel = selectedItem.siblings('.directorist-radio__label').text();
      if (selectedItem) {
        dropDownLabel.text(' - ' + selectedItemLabel);
        dropdownParent.addClass('input-has-value');
      } else {
        // If no items are checked, clear the text
        selectedItem.text('');
        dropdownParent.removeClass('input-has-value');
      }
    }

    // Checkbox Field Check
    $('body').on('change', '.directorist-search-form__top .directorist-search-basic-dropdown input[type="checkbox"], .directorist-search-modal .directorist-search-basic-dropdown input[type="checkbox"]', function (e) {
      e.preventDefault();
      selectedItemCount(this);
    });

    // Radio Field Check
    $('body').on('change', '.directorist-search-form__top .directorist-search-basic-dropdown input[type="radio"], .directorist-search-modal .directorist-search-basic-dropdown input[type="radio"]', function (e) {
      e.preventDefault();
      selectedRadioItem(this);
    });

    // Basic Search Dropdown Toggle
    $('body').on('click', '.directorist-search-form__top .directorist-search-basic-dropdown-label, .directorist-search-modal .directorist-search-basic-dropdown-label', function (e) {
      e.preventDefault();
      var dropDownParent = $(this).closest('.directorist-search-field');
      var dropDownContent = $(this).siblings('.directorist-search-basic-dropdown-content');
      dropDownContent.toggleClass('dropdown-content-show');
      dropDownContent.slideToggle().show();
      if (dropDownContent.hasClass('dropdown-content-show')) {
        dropDownParent.addClass('input-is-focused');
      } else {
        dropDownParent.removeClass('input-is-focused');
      }
      // Hide all other open contents
      $('.directorist-search-basic-dropdown-content.dropdown-content-show').not(dropDownContent).removeClass('dropdown-content-show').slideUp();
    });

    // Dropdown Content Hide on Outside Click
    $('body').on('click', function (e) {
      var dropDownRoot = $(e.target).closest('.directorist-search-form-dropdown');
      var dropDownParent = $('.directorist-search-form-dropdown.input-is-focused');
      var dropDownContent = $('.directorist-search-basic-dropdown-content.dropdown-content-show');
      if (!dropDownRoot.length) {
        dropDownParent.removeClass('input-is-focused');
        dropDownContent.removeClass('dropdown-content-show');
        dropDownContent.slideUp();
      }
    });

>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
    // Check Empty Search Fields on Search Modal
    function initSearchFields() {
      var inputFields = document.querySelectorAll('.directorist-search-modal__input');
      inputFields.forEach(function (inputField) {
        var searchField = inputField.querySelector('.directorist-search-field');
        if (!searchField) {
          inputField.style.display = 'none';
        }
      });
      var searchFields = document.querySelectorAll('.directorist-search-field__input:not(.directorist-search-basic-dropdown)');
      searchFields.forEach(function (searchField) {
        var inputFieldValue = searchField.value;
        if (searchField.classList.contains('directorist-select')) {
          inputFieldValue = searchField.querySelector('select').dataset.selectedId;
        }
        if (inputFieldValue != '') {
          searchField.parentElement.classList.add('input-has-value');
          if (!searchField.parentElement.classList.contains('input-is-focused')) {
            searchField.parentElement.classList.add('input-is-focused');
          }
        } else {
          inputFieldValue = '';
          if (searchField.parentElement.classList.contains('input-has-value')) {
            searchField.parentElement.classList.remove('input-has-value');
          }
        }
      });
    }
    initSearchFields();

<<<<<<< HEAD
    /* Search Form Reset Button Initialize */
=======
    // Search Form Reset Button Initialize
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
    function initForm(searchForm) {
      var value = false;
      searchForm.querySelectorAll("input:not([type='checkbox']):not([type='radio']):not([type='hidden'])").forEach(function (el) {
        if (el.value !== "") {
          value = true;
        }
      });
      searchForm.querySelectorAll("input[type='checkbox'], input[type='radio']").forEach(function (el) {
        if (el.checked) {
          value = true;
        }
      });
      searchForm.querySelectorAll("select").forEach(function (el) {
        if (el.value || el.selectedIndex !== 0) {
          value = true;
        }
      });
      searchForm.querySelectorAll(".directorist-custom-range-slider__value input").forEach(function (el) {
        if (el.value > 0) {
          value = true;
        }
      });
      if (!value) {
        var resetButtonWrapper = searchForm.querySelector('.directorist-advanced-filter__action');
        resetButtonWrapper && resetButtonWrapper.classList.add('reset-btn-disabled');
      }
    }

    // Enable Reset Button
    function enableResetButton(searchForm) {
      var resetButtonWrapper = searchForm.querySelector('.directorist-advanced-filter__action');
      resetButtonWrapper && resetButtonWrapper.classList.remove('reset-btn-disabled');
    }

    // Initialize Form Reset Button
    var searchForm = document.querySelectorAll('.directorist-contents-wrap form');
    searchForm.forEach(function (form) {
      setTimeout(function () {
        initForm(form);
      }, 100);
    });

    // Input Field Check
    $('body').on('keyup', '.directorist-contents-wrap form input:not([type="checkbox"]):not([type="radio"])', function (e) {
      var searchForm = this.closest('form');
      if (this.value && this.value !== 0 && this.value !== undefined) {
        enableResetButton(searchForm);
      } else {
        setTimeout(function () {
          initForm(searchForm);
        }, 100);
      }
    });
    $('body').on('change', '.directorist-contents-wrap form input[type="checkbox"], .directorist-contents-wrap form input[type="radio"]', function (e) {
      var searchForm = this.closest('form');
      if (this.checked) {
        enableResetButton(searchForm);
      } else {
        setTimeout(function () {
          initForm(searchForm);
        }, 100);
      }
    });
    $('body').on('change', '.directorist-contents-wrap form select', function (e) {
      var searchForm = this.closest('form');
      if (this.value !== undefined) {
        enableResetButton(searchForm);
      } else {
        setTimeout(function () {
          initForm(searchForm);
        }, 100);
      }
    });

<<<<<<< HEAD
    /* advanced search form reset */
=======
    // Searchform Reset
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
    function adsFormReset(searchForm) {
      searchForm.querySelectorAll("input[type='text']").forEach(function (el) {
        el.value = "";
        if (el.parentElement.classList.contains('input-has-value') || el.parentElement.classList.contains('input-is-focused')) {
          el.parentElement.classList.remove('input-has-value', 'input-is-focused');
        }
      });
      searchForm.querySelectorAll("input[type='date']").forEach(function (el) {
        el.value = "";
      });
      searchForm.querySelectorAll("input[type='time']").forEach(function (el) {
        el.value = "";
      });
      searchForm.querySelectorAll("input[type='url']").forEach(function (el) {
        el.value = "";
        if (el.parentElement.classList.contains('input-has-value') || el.parentElement.classList.contains('input-is-focused')) {
          el.parentElement.classList.remove('input-has-value', 'input-is-focused');
        }
      });
      searchForm.querySelectorAll("input[type='number']").forEach(function (el) {
        el.value = "";
        if (el.parentElement.classList.contains('input-has-value') || el.parentElement.classList.contains('input-is-focused')) {
          el.parentElement.classList.remove('input-has-value', 'input-is-focused');
        }
      });
      searchForm.querySelectorAll("input[type='hidden']:not(.listing_type)").forEach(function (el) {
        if (el.getAttribute('name') === "directory_type") return;
<<<<<<< HEAD
        if (el.getAttribute('name') === "miles") {
          var radiusDefaultValue = searchForm.querySelector('.directorist-range-slider').dataset.defaultRadius;
          el.value = radiusDefaultValue;
          return;
        }
=======
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
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
        $('.directorist-select2-dropdown-close').click();
<<<<<<< HEAD
        // $(el).val(null).trigger('change');

=======
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
        var parentElem = el.closest('.directorist-search-field');
        if (parentElem.classList.contains('input-has-value') || parentElem.classList.contains('input-is-focused')) {
          setTimeout(function () {
            parentElem.classList.remove('input-has-value', 'input-is-focused');
          }, 100);
        }
      });
      var customRangeSliders = document.querySelectorAll('.directorist-custom-range-slider');
      customRangeSliders.forEach(function (sliderItem) {
        resetCustomRangeSlider(sliderItem);
      });
      searchForm.querySelectorAll(".directorist-search-basic-dropdown-content").forEach(function (dropdown) {
        var dropDownParent = dropdown.closest('.directorist-search-field');
        $(dropdown).siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-count').text('');
        $(dropdown).siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-prefix').text('');
        if (dropDownParent.classList.contains('input-has-value') || dropDownParent.classList.contains('input-is-focused')) {
          dropDownParent.classList.remove('input-has-value', 'input-is-focused');
        }
      });
      var irisPicker = searchForm.querySelector("input.wp-picker-clear");
      if (irisPicker !== null) {
        irisPicker.click();
      }
<<<<<<< HEAD
      var rangeValue = searchForm.querySelector(".directorist-range-slider-current-value span");
      if (rangeValue !== null) {
        rangeValue.innerHTML = "0";
      }
=======
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
      handleRadiusVisibility();
      initForm(searchForm);
    }

<<<<<<< HEAD
    /* Advance Search Filter For Search Home Short Code */
=======
    // Searchform Reset Trigger
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
    if ($('.directorist-btn-reset-js') !== null) {
      $('body').on('click', '.directorist-btn-reset-js', function (e) {
        e.preventDefault();
        if (this.closest('.directorist-contents-wrap')) {
          var _searchForm = this.closest('.directorist-contents-wrap').querySelector('.directorist-search-form');
          if (_searchForm) {
            adsFormReset(_searchForm);
          }
          var advanceSearchForm = this.closest('.directorist-contents-wrap').querySelector('.directorist-advanced-filter__form');
          if (advanceSearchForm) {
            adsFormReset(advanceSearchForm);
          }
          var advanceSearchFilter = this.closest('.directorist-contents-wrap').querySelector('.directorist-advanced-filter__advanced');
          if (advanceSearchFilter) {
            adsFormReset(advanceSearchFilter);
          }
        }
<<<<<<< HEAD
        if ($(this).closest('.directorist-contents-wrap').find('.directorist-search-field-radius_search').length) {
          Object(_range_slider__WEBPACK_IMPORTED_MODULE_5__["directorist_callingSlider"])(0);
        }
=======
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
      });
    }

    // Search Modal Open
    function searchModalOpen(searchModalParent) {
      var modalOverlay = searchModalParent.querySelector('.directorist-search-modal__overlay');
      var modalContent = searchModalParent.querySelector('.directorist-search-modal__contents');

      // Overlay Style
      modalOverlay.style.cssText = "opacity: 1; visibility: visible; transition: 0.3s ease;";

      // Modal Content Style
      modalContent.style.cssText = "opacity: 1; visibility: visible; bottom:0;";
    }

    // Search Modal Close
    function searchModalClose(searchModalParent) {
      var modalOverlay = searchModalParent.querySelector('.directorist-search-modal__overlay');
      var modalContent = searchModalParent.querySelector('.directorist-search-modal__contents');

      // Overlay Style
      if (modalOverlay) {
        modalOverlay.style.cssText = "opacity: 0; visibility: hidden; transition: 0.5s ease";
      }

      // Modal Content Style
      if (modalContent) {
        modalContent.style.cssText = "opacity: 0; visibility: hidden; bottom: -200px;";
      }
    }

<<<<<<< HEAD
    // Modal Minimizer
=======
    // Search Modal Minimizer
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
    function searchModalMinimize(searchModalParent) {
      var modalContent = searchModalParent.querySelector('.directorist-search-modal__contents');
      var modalMinimizer = searchModalParent.querySelector('.directorist-search-modal__minimizer');
      if (modalMinimizer.classList.contains('minimized')) {
        modalMinimizer.classList.remove('minimized');
        modalContent.style.bottom = '0';
      } else {
        modalMinimizer.classList.add('minimized');
        modalContent.style.bottom = '-50%';
      }
    }

<<<<<<< HEAD
    // Search Modal Open
=======
    // Search Modal Open Trigger
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
    $('body').on('click', '.directorist-modal-btn', function (e) {
      e.preventDefault();
      var parentElement = this.closest('.directorist-contents-wrap');
      if (this.classList.contains('directorist-modal-btn--basic')) {
        var searchModalElement = parentElement.querySelector('.directorist-search-modal--basic');
        searchModalOpen(searchModalElement);
      }
      if (this.classList.contains('directorist-modal-btn--advanced')) {
        var _searchModalElement = parentElement.querySelector('.directorist-search-modal--advanced');
        searchModalOpen(_searchModalElement);
      }
      if (this.classList.contains('directorist-modal-btn--full')) {
        var _searchModalElement2 = parentElement.querySelector('.directorist-search-modal--full');
        searchModalOpen(_searchModalElement2);
      }
    });

<<<<<<< HEAD
    // Search Modal Close
=======
    // Search Modal Close Trigger
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
    $('body').on('click', '.directorist-search-modal__contents__btn--close, .directorist-search-modal__overlay', function (e) {
      e.preventDefault();
      var searchModalElement = this.closest('.directorist-search-modal');
      searchModalClose(searchModalElement);
    });

<<<<<<< HEAD
    // Search Modal Minimizer
=======
    // Search Modal Minimizer Trigger
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
    $('body').on('click', '.directorist-search-modal__minimizer', function (e) {
      e.preventDefault();
      var searchModalElement = this.closest('.directorist-search-modal');
      searchModalMinimize(searchModalElement);
<<<<<<< HEAD
    });

    // Search Form Input Field Check
    $('body').on('input keyup change', '.directorist-search-field__input', function (e) {
      var searchField = $(this).closest('.directorist-search-field');
      inputValueCheck(e, searchField);
    });
    $('body').on('focus blur', '.directorist-search-field__input', function (e) {
      var searchField = $(this).closest('.directorist-search-field');
      inputEventCheck(e, searchField);
    });
=======
    });
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c

    // Search Field Input Value Check
    function inputValueCheck(e, searchField) {
      searchField = searchField[0];
<<<<<<< HEAD
      var inputBox = searchField.querySelector('.directorist-search-field__input');
      var inputFieldValue = inputBox.value;
=======
      var inputBox = searchField.querySelector('.directorist-search-field__input:not(.directorist-search-basic-dropdown)');
      var inputFieldValue = inputBox && inputBox.value;
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
      if (inputFieldValue) {
        searchField.classList.add('input-has-value');
        if (!searchField.classList.contains('input-is-focused')) {
          searchField.classList.add('input-is-focused');
        }
      } else {
        inputFieldValue = '';
        if (searchField.classList.contains('input-has-value')) {
          searchField.classList.remove('input-has-value');
        }
      }
    }

    // Search Field Input Event Check
    function inputEventCheck(e, searchField) {
      searchField = searchField[0];
      var inputBox = searchField.querySelector('.directorist-search-field__input:not(.directorist-search-basic-dropdown)');
      var inputFieldValue = inputBox.value;
      if (e.type === 'focusin') {
        searchField.classList.add('input-is-focused');
      } else if (e.type === 'focusout') {
        if (inputBox.classList.contains('directorist-select')) {
          selectFocusOutCheck(searchField, inputBox);
        } else {
          if (inputFieldValue) {
            searchField.classList.add('input-has-value');
            if (!searchField.classList.contains('input-is-focused')) {
              searchField.classList.add('input-is-focused');
            }
          } else {
            searchField.classList.remove('input-is-focused');
          }
        }
      }
    }

    // Search Field Input Focusout Event Check
    function selectFocusOutCheck(searchField, inputBox) {
      searchField.classList.add('input-is-focused');
      var inputFieldValue = inputBox.querySelector('select').value;
      $('body').one('click', function (e) {
        inputFieldValue = inputBox.querySelector('select').value;
<<<<<<< HEAD
        var parentWithClass = e.target.closest('.directorist-search-field__input');
=======
        var parentWithClass = e.target.closest('.directorist-search-field__input:not(.directorist-search-basic-dropdown)');
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
        if (!parentWithClass) {
          if (inputFieldValue) {
            searchField.classList.add('input-has-value');
            if (!searchField.classList.contains('input-is-focused')) {
              searchField.classList.add('input-is-focused');
            }
          } else {
            searchField.classList.remove('input-is-focused');
          }
        }
      });
    }
<<<<<<< HEAD
=======

    // Search Form Input Field Check Trigger
    $('body').on('input keyup change', '.directorist-search-field__input:not(.directorist-search-basic-dropdown)', function (e) {
      var searchField = $(this).closest('.directorist-search-field');
      inputValueCheck(e, searchField);
    });
    $('body').on('focus blur', '.directorist-search-field__input:not(.directorist-search-basic-dropdown)', function (e) {
      var searchField = $(this).closest('.directorist-search-field');
      inputEventCheck(e, searchField);
    });
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c

    // Search Form Input Clear Button
    $('body').on('click', '.directorist-search-field__btn--clear', function (e) {
      var inputFields = this.parentElement.querySelectorAll('.directorist-form-element');
      var selectboxField = this.parentElement.querySelector('.directorist-select select');
      var basicDropdown = this.parentElement.querySelectorAll('.directorist-search-basic-dropdown-content');
      var radioFields = this.parentElement.querySelectorAll('input[type="radio"]');
      var checkboxFields = this.parentElement.querySelectorAll('input[type="checkbox"]');
      if (selectboxField) {
        selectboxField.selectedIndex = 0;
        selectboxField.dispatchEvent(new Event('change'));
      }
      if (inputFields) {
        inputFields.forEach(function (inputField) {
          inputField.value = '';
        });
      }
      if (radioFields) {
        radioFields.forEach(function (element) {
          element.checked = false;
        });
      }
      if (checkboxFields) {
        checkboxFields.forEach(function (element) {
          element.checked = false;
        });
      }
<<<<<<< HEAD
=======
      if (basicDropdown) {
        basicDropdown.forEach(function (dropdown) {
          $(dropdown).slideUp();
          $(dropdown).siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-count').text('');
          $(dropdown).siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-prefix').text('');
          $(dropdown).siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-item').text('');
        });
      }
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
      if (this.parentElement.classList.contains('input-has-value') || this.parentElement.classList.contains('input-is-focused')) {
        this.parentElement.classList.remove('input-has-value', 'input-is-focused');
      }
      handleRadiusVisibility();

      // Reset Button Disable
      var searchform = this.closest('form');
<<<<<<< HEAD
      var inputValue = $(this).parent('.directorist-search-field').find('.directorist-search-field__input').val();
      var selectValue = $(this).parent('.directorist-search-field').find('.directorist-search-field__input select').val();
=======
      var inputValue = $(this).parent('.directorist-search-field').find('.directorist-search-field__input:not(.directorist-search-basic-dropdown)').val();
      var selectValue = $(this).parent('.directorist-search-field').find('.directorist-search-field__input select:not(.directorist-search-basic-dropdown)').val();
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
      if (inputValue && inputValue !== 0 && inputValue !== undefined || selectValue && selectValue.selectedIndex === 0 || selectValue && selectValue.selectedIndex !== undefined) {
        enableResetButton(searchform);
      } else {
        setTimeout(function () {
          initForm(searchform);
        }, 100);
      }
    });

    // Search Form Input Field Back Button
<<<<<<< HEAD
    $('body').on('click', '.directorist-search-field__label', function (e) {
=======
    $('body').on('click', '.directorist-search-field__label:not(.directorist-search-basic-dropdown-label)', function (e) {
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
      var windowScreen = window.innerWidth;
      var parentField = this.closest('.directorist-search-field');
      if (windowScreen <= 575) {
        if (parentField.classList.contains('input-is-focused')) {
          parentField.classList.remove('input-is-focused');
        }
      }
    });

<<<<<<< HEAD
    /* ----------------
    Search-form-listing
    ------------------- */
=======
    // Listing Type Change
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
    $('body').on('click', '.search_listing_types', function (event) {
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
      form_data.append('nonce', directorist.directorist_nonce);
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
            parent.before(new_inserted_elm);

            // Remove Old Parent
            parent.remove();

            // Insert New Parent
            $('.directorist_search_temp').after(response['search_form']);
            var newParent = $('.directorist_search_temp').next();

            // Toggle Active Class
            newParent.find('.directorist-listing-type-selection__link--current').removeClass('directorist-listing-type-selection__link--current');
            newParent.find("[data-listing_type='" + listing_type + "']").addClass('directorist-listing-type-selection__link--current');

            // Remove Temp Element
            $('.directorist_search_temp').remove();
            var events = [new CustomEvent('directorist-search-form-nav-tab-reloaded'), new CustomEvent('directorist-reload-select2-fields'), new CustomEvent('directorist-reload-map-api-field'), new CustomEvent('triggerSlice')];
            events.forEach(function (event) {
              document.body.dispatchEvent(event);
              window.dispatchEvent(event);
            });
            handleRadiusVisibility();
            directorist_custom_range_slider();
          }
          var parentAfterAjax = $(this).closest('.directorist-search-contents');
          parentAfterAjax.find('.directorist-search-form-box').removeClass('atbdp-form-fade');
          if (parentAfterAjax.find('.directorist-search-form-box').find('.directorist-search-field-radius_search').length) {
            handleRadiusVisibility();
            directorist_custom_range_slider();
          }
        },
        error: function error(_error2) {
          // console.log(error);
        }
      });
    });
<<<<<<< HEAD

    // Search Category
=======
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c

    // Search Category Change
    if ($('.directorist-search-contents').length) {
      $('body').on('change', '.directorist-category-select', function (event) {
        var $this = $(this);
        var $container = $this.parents('form');
        var cat_id = $this.val();
        var directory_type = $container.find('.listing_type').val();
        var $search_form_box = $container.find('.directorist-search-form-box-wrap');
        var form_data = new FormData();
        form_data.append('action', 'directorist_category_custom_field_search');
        form_data.append('nonce', directorist.directorist_nonce);
        form_data.append('listing_type', directory_type);
        form_data.append('cat_id', cat_id);
        form_data.append('atts', JSON.stringify($container.data('atts')));
        $search_form_box.addClass('atbdp-form-fade');
        $.ajax({
          method: 'POST',
          processData: false,
          contentType: false,
          url: directorist.ajax_url,
          data: form_data,
          success: function success(response) {
            if (response) {
              $search_form_box.html(response['search_form']);
              $container.find('.directorist-category-select option').data('custom-field', 1);
              $container.find('.directorist-category-select').val(cat_id);
              [new CustomEvent('directorist-search-form-nav-tab-reloaded'), new CustomEvent('directorist-reload-select2-fields'), new CustomEvent('directorist-reload-map-api-field'), new CustomEvent('triggerSlice')].forEach(function (event) {
                document.body.dispatchEvent(event);
                window.dispatchEvent(event);
              });
            }
            $search_form_box.removeClass('atbdp-form-fade');
            initSearchFields();
          },
          error: function error(_error) {
            //console.log(_error);
          }
        });
      });
    }

    // Back Button to go back to the previous page
    $('body').on('click', '.directorist-btn__back', function (e) {
      e.preventDefault();
      window.history.back();
    });

<<<<<<< HEAD
    /* When location field is empty we need to hide Radius Search */
=======
    // Radius Search Field Hide on Empty Location Field
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
    function handleRadiusVisibility() {
      $('.directorist-range-slider-wrap').closest('.directorist-search-field').addClass('directorist-search-field-radius_search');
      $('.directorist-location-js').each(function (index, locationDOM) {
        if ($(locationDOM).val() === '') {
          $(locationDOM).closest('.directorist-contents-wrap').find('.directorist-search-field-radius_search').css({
            display: "none"
          });
        } else {
          $(locationDOM).closest('.directorist-contents-wrap').find('.directorist-search-field-radius_search').css({
            display: "block"
          });
        }
      });
    }
<<<<<<< HEAD
=======
    // handleRadiusVisibility Trigger
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
    $('body').on('keyup keydown input change focus', '.directorist-location-js, .zip-radius-search', function (e) {
      handleRadiusVisibility();
    });

<<<<<<< HEAD
    // hide country result when click outside the zipcode field
=======
    // Hide Country Result Click on Outside of Zipcode Field
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
    $(document).on('click', function (e) {
      if (!$(e.target).closest('.directorist-zip-code').length) {
        $('.directorist-country').hide();
      }
    });
    $('body').on('click', '.directorist-country ul li a', function (event) {
      event.preventDefault();
      var zipcode_search = $(this).closest('.directorist-zipcode-search');
      var lat = $(this).data('lat');
      var lon = $(this).data('lon');
      zipcode_search.find('.zip-cityLat').val(lat);
      zipcode_search.find('.zip-cityLng').val(lon);
      $('.directorist-country').hide();
    });
    $('.address_result').hide();

    // Init Location
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
                elm.closest('.directorist-search-field').querySelector("#".concat(field.lat_id)).value = place.geometry.location.lat();
                elm.closest('.directorist-search-field').querySelector("#".concat(field.lng_id)).value = place.geometry.location.lng();
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
          $(field.input_elm).on('keyup', directorist_debounce(function (event) {
            event.preventDefault();
            var blockedKeyCodes = [16, 17, 18, 19, 20, 27, 33, 34, 35, 36, 37, 38, 39, 40, 45, 91, 93, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 144, 145];

            // Return early when blocked key is pressed.
            if (blockedKeyCodes.includes(event.keyCode)) {
              return;
            }
            var locationAddressField = $(this).parent('.directorist-search-field');
            var result_container = field.getResultContainer(this, field);
            var search = $(this).val();
            if (search.length < 3) {
              result_container.css({
                display: 'none'
              });
            } else {
              locationAddressField.addClass('atbdp-form-fade');
              result_container.css({
                display: 'block'
              });
              $.ajax({
                url: "https://nominatim.openstreetmap.org/?q=%27+".concat(search, "+%27&format=json"),
                type: 'GET',
                data: {},
                success: function success(data) {
                  var res = '';
                  var currentIconURL = directorist.assets_url + 'icons/font-awesome/svgs/solid/paper-plane.svg';
                  var currentIconHTML = directorist.icon_markup.replace('##URL##', currentIconURL).replace('##CLASS##', '');
                  var currentLocationIconHTML = "<span class='location-icon'>" + currentIconHTML + "</span>";
                  var currentLocationAddressHTML = "<span class='location-address'></span>";
                  var iconURL = directorist.assets_url + 'icons/font-awesome/svgs/solid/map-marker-alt.svg';
                  var iconHTML = directorist.icon_markup.replace('##URL##', iconURL).replace('##CLASS##', '');
                  var locationIconHTML = "<span class='location-icon'>" + iconHTML + "</span>";
                  for (var i = 0, len = data.length > 5 ? 5 : data.length; i < len; i++) {
                    res += "<li><a href=\"#\" data-lat=" + data[i].lat + " data-lon=" + data[i].lon + ">" + locationIconHTML + "<span class='location-address'>" + data[i].display_name, +"</span></a></li>";
                  }
                  function displayLocation(position, event) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    $.ajax({
                      url: "https://nominatim.openstreetmap.org/reverse?format=json&lon=" + lng + "&lat=" + lat,
                      type: 'GET',
                      data: {},
                      success: function success(data) {
                        $('.directorist-location-js, .atbdp-search-address').val(data.display_name);
                        $('.directorist-location-js, .atbdp-search-address').attr("data-value", data.display_name);
                        $('#cityLat').val(lat);
                        $('#cityLng').val(lng);
                      }
                    });
                  }
                  result_container.html("<ul>" + "<li><a href='#' class='current-location'>" + currentLocationIconHTML + currentLocationAddressHTML + "</a></li>" + res + "</ul>");
                  if (res.length) {
                    result_container.show();
                  } else {
                    result_container.hide();
                  }
                  locationAddressField.removeClass('atbdp-form-fade');
                  $('body').on("click", '.address_result .current-location', function (e) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                      return displayLocation(position, e);
                    });
                  });
                },
                error: function error(_error3) {
                  // console.log({
                  //     error: _error3
                  // });
                }
              });
            }
          }, 750));
        });

        // hide address result when click outside the input field
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
          var _this = event.target;
          $(_this).closest('.address_result').siblings('input[name="cityLat"]').val(lat);
          $(_this).closest('.address_result').siblings('input[name="cityLng"]').val(lon);
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
      if ($('.directorist-location-js, #q_addressss, .atbdp-search-address').val() === '') {
        $(this).parent().next('.address_result').css({
          display: 'none'
        });
      }
    }
    $(".directorist-search-contents").each(function () {
      if ($(this).next().length === 0) {
        $(this).find(".directorist-search-country").css("max-height", "175px");
        $(this).find(".directorist-search-field .address_result").css("max-height", "175px");
      }
    });

<<<<<<< HEAD
    // DOM Mutation observer
=======
    // Custom Range Slider
    function directorist_custom_range_slider() {
      var sliders = document.querySelectorAll('.directorist-custom-range-slider');
      sliders.forEach(function (sliderItem) {
        var slider = sliderItem.querySelector('.directorist-custom-range-slider__slide');
        if (slider) {
          var sliderStep = parseInt(slider.getAttribute('step')) || 1;
          var sliderDefaultValue = parseInt(slider.getAttribute('value'));
          var minInput = sliderItem.querySelector('.directorist-custom-range-slider__value__min');
          var maxInput = sliderItem.querySelector('.directorist-custom-range-slider__value__max');
          var sliderRange = sliderItem.querySelector('.directorist-custom-range-slider__range');
          var sliderRangeShow = sliderItem.querySelector('.directorist-custom-range-slider__range__show');
          directoristCustomRangeSlider.create(slider, {
            start: [0, sliderDefaultValue ? sliderDefaultValue : 100],
            connect: true,
            step: sliderStep ? sliderStep : 1,
            range: {
              'min': Number(minInput.value ? minInput.value : 0),
              'max': Number(maxInput.value ? maxInput.value : 100)
            }
          });
          slider.directoristCustomRangeSlider.on('update', function (values, handle) {
            var value = values[handle];
            handle === 0 ? minInput.value = Math.round(value) : maxInput.value = Math.round(value);
            var rangeValue = minInput.value + '-' + maxInput.value;
            sliderRange.value = rangeValue;
            sliderRangeShow && (sliderRangeShow.innerHTML = rangeValue);
          });
          minInput.addEventListener('change', function () {
            var minValue = Math.round(parseInt(this.value, 10) / sliderStep) * sliderStep;
            var maxValue = Math.round(parseInt(maxInput.value, 10) / sliderStep) * sliderStep;
            if (minValue > maxValue) {
              this.value = maxValue;
              minValue = maxValue;
            }
            slider.directoristCustomRangeSlider.set([minValue, null]);
          });
          maxInput.addEventListener('change', function () {
            var minValue = Math.round(parseInt(minInput.value, 10) / sliderStep) * sliderStep;
            var maxValue = Math.round(parseInt(this.value, 10) / sliderStep) * sliderStep;
            if (maxValue < minValue) {
              this.value = minValue;
              maxValue = minValue;
            }
            slider.directoristCustomRangeSlider.set([null, maxValue]);
          });
        }
      });
    }
    directorist_custom_range_slider();

    // Reset Custom Range Slider
    function resetCustomRangeSlider(sliderItem) {
      var slider = sliderItem.querySelector('.directorist-custom-range-slider__slide');
      var minInput = sliderItem.querySelector('.directorist-custom-range-slider__value__min');
      var maxInput = sliderItem.querySelector('.directorist-custom-range-slider__value__max');
      var sliderParent = sliderItem.closest('.directorist-search-field-radius_search');
      var maxValue = slider.getAttribute('value') || 'none';
      if (sliderParent) {
        minInput.value = '0';
        maxInput.value = maxValue;
        slider.directoristCustomRangeSlider.set([0, maxValue]); // Set your initial values
      } else {
        // Reset values to their initial state
        slider.directoristCustomRangeSlider.set([0, 0]); // Set your initial values
        minInput.value = ''; // Set your initial min value
        maxInput.value = ''; // Set your initial max value
      }
    }

    // DOM Mutation Observer on Location Field
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
    function locationObserver() {
      var targetNode = document.querySelector('.directorist-location-js');
      if (targetNode) {
        var observer = new MutationObserver(handleRadiusVisibility);
        observer.observe(targetNode, {
          attributes: true
        });
      }
    }
    locationObserver();
    handleRadiusVisibility();

    // Returns a function, that, as long as it continues to be invoked, will not
    // be triggered. The function will be called after it stops being called for
    // N milliseconds. If `immediate` is passed, trigger the function on the
    // leading edge, instead of the trailing.
    function directorist_debounce(func, wait, immediate) {
      var timeout;
      return function () {
        var context = this,
          args = arguments;
        var later = function later() {
          timeout = null;
          if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
      };
    }
    ;
    $('body').on("keyup", '.zip-radius-search', directorist_debounce(function () {
      var zipcode = $(this).val();
      var zipcode_search = $(this).closest('.directorist-zipcode-search');
      var country_suggest = zipcode_search.find('.directorist-country');
      $('.directorist-country').css({
        display: 'block'
      });
      if (zipcode === '') {
        $('.directorist-country').css({
          display: 'none'
        });
      }
      var res = '';
      $.ajax({
        url: "https://nominatim.openstreetmap.org/?postalcode=+".concat(zipcode, "+&format=json&addressdetails=1"),
        type: "POST",
        data: {},
        success: function success(data) {
          if (data.length === 1) {
            var lat = data[0].lat;
            var lon = data[0].lon;
            zipcode_search.find('.zip-cityLat').val(lat);
            zipcode_search.find('.zip-cityLng').val(lon);
          } else {
            for (var i = 0; i < data.length; i++) {
              res += "<li><a href=\"#\" data-lat=".concat(data[i].lat, " data-lon=").concat(data[i].lon, ">").concat(data[i].address.country, "</a></li>");
            }
          }
          $(country_suggest).html("<ul>".concat(res, "</ul>"));
          if (res.length) {
            $('.directorist-country').show();
          } else {
            $('.directorist-country').hide();
          }
        }
      });
    }, 250));
<<<<<<< HEAD
=======

    // Custom Range Slider Value Check on Change
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
    function sliderValueCheck(targetNode, value) {
      var searchForm = targetNode.closest('form');
      if (value > 0) {
        var customSliderMin = targetNode.closest('.directorist-custom-range-slider').querySelector('.directorist-custom-range-slider__value__min');
        var customSliderRange = targetNode.closest('.directorist-custom-range-slider').querySelector('.directorist-custom-range-slider__range');
        customSliderMin.value = customSliderMin.value ? customSliderMin.value : 0;
        customSliderRange.value = customSliderMin.value + '-' + value;
        enableResetButton(searchForm);
      } else {
        initForm(searchForm);
      }
    }
<<<<<<< HEAD
=======

    // DOM Mutation Observer on Custom Range Slider
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
    function rangeSliderObserver() {
      var targetNodes = document.querySelectorAll('.directorist-search-field:not(.directorist-search-field-radius_search) .directorist-custom-range-slider-handle-upper');
      targetNodes.forEach(function (targetNode) {
        if (targetNode) {
          var observerCallback = function observerCallback(mutationList, observer) {
            var _iterator = _createForOfIteratorHelper(mutationList),
              _step;
            try {
              for (_iterator.s(); !(_step = _iterator.n()).done;) {
                var mutation = _step.value;
<<<<<<< HEAD
                if (mutation.attributeName == 'value') {
                  sliderValueCheck(targetNode, parseInt(targetNode.value));
=======
                if (targetNode.classList.contains('directorist-custom-range-slider-handle-upper')) {
                  sliderValueCheck(targetNode, parseInt(targetNode.ariaValueNow));
>>>>>>> f68aa25b2f21bf4499c8f8ee6439e94d0f7b623c
                }
              }
            } catch (err) {
              _iterator.e(err);
            } finally {
              _iterator.f();
            }
          };
          var sliderObserver = new MutationObserver(observerCallback);
          sliderObserver.observe(targetNode, {
            attributes: true
          });
        }
      });
    }
    rangeSliderObserver();
  });
})(jQuery);

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayLikeToArray.js ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;
  for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i];
  return arr2;
}
module.exports = _arrayLikeToArray, module.exports.__esModule = true, module.exports["default"] = module.exports;

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
module.exports = _arrayWithoutHoles, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/defineProperty.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/defineProperty.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var toPropertyKey = __webpack_require__(/*! ./toPropertyKey.js */ "./node_modules/@babel/runtime/helpers/toPropertyKey.js");
function _defineProperty(obj, key, value) {
  key = toPropertyKey(key);
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
module.exports = _defineProperty, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/iterableToArray.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/iterableToArray.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _iterableToArray(iter) {
  if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter);
}
module.exports = _iterableToArray, module.exports.__esModule = true, module.exports["default"] = module.exports;

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
module.exports = _nonIterableSpread, module.exports.__esModule = true, module.exports["default"] = module.exports;

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
module.exports = _toConsumableArray, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/toPrimitive.js":
/*!************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/toPrimitive.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var _typeof = __webpack_require__(/*! ./typeof.js */ "./node_modules/@babel/runtime/helpers/typeof.js")["default"];
function _toPrimitive(input, hint) {
  if (_typeof(input) !== "object" || input === null) return input;
  var prim = input[Symbol.toPrimitive];
  if (prim !== undefined) {
    var res = prim.call(input, hint || "default");
    if (_typeof(res) !== "object") return res;
    throw new TypeError("@@toPrimitive must return a primitive value.");
  }
  return (hint === "string" ? String : Number)(input);
}
module.exports = _toPrimitive, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/toPropertyKey.js":
/*!**************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/toPropertyKey.js ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var _typeof = __webpack_require__(/*! ./typeof.js */ "./node_modules/@babel/runtime/helpers/typeof.js")["default"];
var toPrimitive = __webpack_require__(/*! ./toPrimitive.js */ "./node_modules/@babel/runtime/helpers/toPrimitive.js");
function _toPropertyKey(arg) {
  var key = toPrimitive(arg, "string");
  return _typeof(key) === "symbol" ? key : String(key);
}
module.exports = _toPropertyKey, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/typeof.js":
/*!*******************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/typeof.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(o) {
  "@babel/helpers - typeof";

  return (module.exports = _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) {
    return typeof o;
  } : function (o) {
    return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o;
  }, module.exports.__esModule = true, module.exports["default"] = module.exports), _typeof(o);
}
module.exports = _typeof, module.exports.__esModule = true, module.exports["default"] = module.exports;

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
module.exports = _unsupportedIterableToArray, module.exports.__esModule = true, module.exports["default"] = module.exports;

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