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

/***/ "./assets/src/js/global/components/debounce.js":
/*!*****************************************************!*\
  !*** ./assets/src/js/global/components/debounce.js ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return debounce; });
function debounce(func, wait, immediate) {
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

/***/ }),

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
    var dropdownParent = $(this).closest('.directorist-search-field');
    var renderTitle = $(this).next().find('.select2-selection__rendered').attr('title');

    // Check if renderTitle is empty and remove the focus class if so
    if (!renderTitle) {
      dropdownParent.removeClass('input-is-focused');
    } else {
      dropdownParent.addClass('input-has-value');
    }
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
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./../../lib/helper */ "./assets/src/js/lib/helper.js");
/* harmony import */ var _select2_custom_control__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./select2-custom-control */ "./assets/src/js/global/components/select2-custom-control.js");
/* harmony import */ var _select2_custom_control__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_select2_custom_control__WEBPACK_IMPORTED_MODULE_2__);

function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default()(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }


var $ = jQuery;
window.addEventListener('load', initSelect2);
document.body.addEventListener('directorist-search-form-nav-tab-reloaded', initSelect2);
document.body.addEventListener('directorist-reload-select2-fields', initSelect2);

// Init Static Select 2 Fields
function initSelect2() {
  var selectors = ['.directorist-select select', '#directorist-select-js',
  // Not found in any template
  '#directorist-search-category-js',
  // Not found in any template
  // '#directorist-select-st-s-js',
  // '#directorist-select-sn-s-js',
  // '#directorist-select-mn-e-js',
  // '#directorist-select-tu-e-js',
  // '#directorist-select-wd-s-js',
  // '#directorist-select-wd-e-js',
  // '#directorist-select-th-e-js',
  // '#directorist-select-fr-s-js',
  // '#directorist-select-fr-e-js',
  '.select-basic',
  // Not found in any template
  '#loc-type', '#cat-type', '#at_biz_dir-category', '.bdas-location-search',
  // Not found in any template
  '.bdas-category-search' // Not found in any template
  ];

  selectors.forEach(function (selector) {
    return Object(_lib_helper__WEBPACK_IMPORTED_MODULE_1__["convertToSelect2"])(selector);
  });
  initMaybeLazyLoadedTaxonomySelect2();
}

// Init Select2 Ajax Fields
function initMaybeLazyLoadedTaxonomySelect2() {
  var restBase = "".concat(directorist.rest_url, "directorist/v1");
  maybeLazyLoadCategories({
    selector: '.directorist-search-category select',
    url: "".concat(restBase, "/listings/categories")
  });
  maybeLazyLoadCategories({
    selector: '.directorist-form-categories-field select',
    url: "".concat(restBase, "/listings/categories")
  });
  maybeLazyLoadLocations({
    selector: '.directorist-search-location select',
    url: "".concat(restBase, "/listings/locations")
  });
  maybeLazyLoadLocations({
    selector: '.directorist-form-location-field select',
    url: "".concat(restBase, "/listings/locations")
  });
  maybeLazyLoadTags({
    selector: '.directorist-form-tag-field select',
    url: "".concat(restBase, "/listings/tags")
  });
}
function maybeLazyLoadCategories(args) {
  maybeLazyLoadTaxonomyTermsSelect2(_objectSpread(_objectSpread({}, {
    taxonomy: 'categories'
  }), args));
}
function maybeLazyLoadLocations(args) {
  maybeLazyLoadTaxonomyTermsSelect2(_objectSpread(_objectSpread({}, {
    taxonomy: 'locations'
  }), args));
}
function maybeLazyLoadTags(args) {
  maybeLazyLoadTaxonomyTermsSelect2(_objectSpread(_objectSpread({}, {
    taxonomy: 'tags'
  }), args));
}

// maybeLazyLoadTaxonomyTermsSelect2
function maybeLazyLoadTaxonomyTermsSelect2(args) {
  var defaults = {
    selector: '',
    url: '',
    taxonomy: 'tags'
  };
  args = _objectSpread(_objectSpread({}, defaults), args);
  if (!args.selector) {
    return;
  }
  var $el = $(args.selector);
  var $addListing = $el.closest('.directorist-add-listing-form');
  var canCreate = $el.data('allow_new');
  var maxLength = $el.data('max');
  var directoryId = 0;
  if (args.taxonomy !== 'tags') {
    var $searchForm = $el.closest('.directorist-search-form');
    var $archivePage = $el.closest('.directorist-archive-contents');
    var $directory = $addListing.find('input[name="directory_type"]');
    var $navListItem = null;

    // If search page
    if ($searchForm.length) {
      $navListItem = $searchForm.find('.directorist-listing-type-selection__link--current');
    }
    if ($archivePage.length) {
      $navListItem = $archivePage.find('.directorist-type-nav__list li.directorist-type-nav__list__current .directorist-type-nav__link');
    }
    if ($navListItem && $navListItem.length) {
      directoryId = Number($navListItem.data('listing_type_id'));
    }
    if ($directory.length) {
      directoryId = $directory.val();
    }
    if (directoryId) {
      directoryId = Number(directoryId);
    }
  }
  var currentPage = 1;
  var select2Options = {
    allowClear: true,
    tags: canCreate,
    maximumSelectionLength: maxLength,
    width: '100%',
    escapeMarkup: function escapeMarkup(text) {
      return text;
    }
  };
  if (directorist.lazy_load_taxonomy_fields) {
    select2Options.ajax = {
      url: args.url,
      dataType: 'json',
      cache: true,
      delay: 250,
      data: function data(params) {
        currentPage = params.page || 1;
        var query = {
          page: currentPage,
          per_page: args.perPage,
          hide_empty: true
        };

        // Load empty terms on add listings.
        if ($addListing.length) {
          query.hide_empty = false;
        }
        if (params.term) {
          query.search = params.term;
          query.hide_empty = false;
        }
        if (directoryId) {
          query.directory = directoryId;
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
          var totalPage = Number(jqXHR.getResponseHeader('x-wp-totalpages'));
          var paginationMore = currentPage < totalPage;
          var items = data.map(function (item) {
            var text = item.name;
            if (!$addListing.length && params.data.search) {
              text = "".concat(item.name, " (").concat(item.count, ")");
            }
            return {
              id: item.id,
              text: text
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
    };
  }
  $el.length && $el.select2(select2Options);
  if (directorist.lazy_load_taxonomy_fields) {
    function setupSelectedItems($el, selectedId, selectedLabel) {
      if (!$el.length || !selectedId) {
        return;
      }
      var selectedIds = "".concat(selectedId).split(',');
      var selectedLabels = selectedLabel ? "".concat(selectedLabel).split(',') : [];
      selectedIds.forEach(function (id, index) {
        var label = selectedLabels.length >= index + 1 ? selectedLabels[index] : '';
        var option = new Option(label, id, true, true);
        $el.append(option);
        $el.trigger({
          type: 'select2:select',
          params: {
            data: {
              id: id,
              text: label
            }
          }
        });
      });
    }
    setupSelectedItems($el, $el.data('selected-id'), $el.data('selected-label'));
  }
}

/***/ }),

/***/ "./assets/src/js/lib/helper.js":
/*!*************************************!*\
  !*** ./assets/src/js/lib/helper.js ***!
  \*************************************/
/*! exports provided: convertToSelect2, get_dom_data */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "convertToSelect2", function() { return convertToSelect2; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "get_dom_data", function() { return get_dom_data; });
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
function convertToSelect2(selector) {
  var $selector = $(selector);
  var args = {
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
  var options = $selector.find('option');
  if (options.length && options[0].textContent.length) {
    args.placeholder = options[0].textContent;
  }
  $selector.length && $selector.select2(args);
}


/***/ }),

/***/ "./assets/src/js/public/components/category-custom-fields.js":
/*!*******************************************************************!*\
  !*** ./assets/src/js/public/components/category-custom-fields.js ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return initSearchCategoryCustomFields; });
// Search Category Change
function hideAllCustomFieldsExceptSelected(relations, category, $container) {
  var fields = Object.keys(relations);
  var wrappers = ['.directorist-advanced-filter__advanced__element', '.directorist-search-modal__input', '.directorist-search-field'];
  if (!fields.length) {
    return;
  }
  fields.forEach(function (field) {
    var fieldCategory = relations[field];
    var $field = $container.find("[name=\"custom_field[".concat(field, "]\"]"));
    if (category === fieldCategory) {
      $field.prop('disabled', false);
      wrappers.forEach(function (wrapper) {
        var $wrapper = $field.closest(wrapper);
        if ($wrapper.length) {
          $wrapper.show();
        }
      });
    } else {
      $field.prop('disabled', true);
      wrappers.forEach(function (wrapper) {
        var $wrapper = $field.closest(wrapper);
        if ($wrapper.length) {
          $wrapper.hide();
        }
      });
    }
  });
}
function initSearchCategoryCustomFields($) {
  var _$pageContainer;
  var $searchPageContainer = $('.directorist-search-contents');
  var $archivePageContainer = $('.directorist-archive-contents');
  var $pageContainer;
  if ($searchPageContainer.length) {
    $pageContainer = $searchPageContainer;
  } else if ($archivePageContainer.length) {
    $pageContainer = $archivePageContainer;
  }
  if ((_$pageContainer = $pageContainer) !== null && _$pageContainer !== void 0 && _$pageContainer.length) {
    // let $fieldsContainer = null;

    $pageContainer.on('change', '.directorist-category-select, .directorist-search-category select', function (event) {
      var $this = $(this);
      var $form = $this.parents('form');
      // const $advancedForm = $('.directorist-search-form');
      var category = Number($this.val());
      // const directory      = $pageContainer.find('[name="directory_type"]').val(); // Sidebar has multiple forms that's why it's safe to use page container
      // const formData       = new FormData();
      var attributes = $form.data('atts');
      // const hasCustomField = $this.find('option[value="'+category+'"]').data('custom-field');

      // if (!hasCustomField) {
      //     return;
      // }

      // formData.append('action', 'directorist_category_custom_field_search');
      // formData.append('nonce', directorist.directorist_nonce);
      // formData.append('directory', directory);
      // formData.append('cat_id', category);

      if (!attributes) {
        attributes = $pageContainer.data('atts');
      }
      if (!attributes.category_custom_fields_relations) {
        return;
      }
      hideAllCustomFieldsExceptSelected(attributes.category_custom_fields_relations, category, $(document.body));

      // console.log(, category);

      // formData.append('atts', JSON.stringify(atts));
      // $form.addClass('atbdp-form-fade');
      // $advancedForm.addClass('atbdp-form-fade');

      // $.ajax({
      //     method     : 'POST',
      //     processData: false,
      //     contentType: false,
      //     url        : directorist.ajax_url,
      //     data       : formData,
      //     success: function success(response) {
      //         if (response) {
      //             $fieldsContainer = $pageContainer.find(response['container']);

      //             $fieldsContainer.html(response['search_form']);

      //             // $form.find('.directorist-category-select option').data('custom-field', 1);
      //             // $this.find('option').data('custom-field', 1);
      //             $this.val(category);

      //             [
      //                 'directorist-search-form-nav-tab-reloaded',
      //                 'directorist-reload-select2-fields',
      //                 'directorist-reload-map-api-field',
      //                 'triggerSlice'
      //             ].forEach(function(event) {
      //                 event = new CustomEvent(event);
      //                 document.body.dispatchEvent(event);
      //                 window.dispatchEvent(event);
      //             });
      //         }

      //         onSuccessCallback();

      //         $form.removeClass('atbdp-form-fade');
      //         $advancedForm.removeClass('atbdp-form-fade');
      //     },
      //     error: function error(_error) {
      //         //console.log(_error);
      //     }
      // });
    });

    $pageContainer.find('.directorist-category-select, .directorist-search-category select').trigger('change');
  }
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

window.addEventListener('load', function () {
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

/***/ "./assets/src/js/public/range-slider.js":
/*!**********************************************!*\
  !*** ./assets/src/js/public/range-slider.js ***!
  \**********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/typeof.js");
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__);

// Directorist Custom Range Slider with Multiple Handlers
(function (global, factory) {
  if ((typeof exports === "undefined" ? "undefined" : _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default()(exports)) === 'object' && typeof module !== 'undefined') {
    factory(exports);
  } else if (typeof define === 'function' && __webpack_require__(/*! !webpack amd options */ "./node_modules/webpack/buildin/amd-options.js")) {
    define(['exports'], factory);
  } else {
    global = typeof globalThis !== 'undefined' ? globalThis : global || self;
    factory(global.directoristCustomRangeSlider = {});
  }
})(undefined, function (exports) {
  'use strict';

  exports.PipsMode = {
    Range: 'range',
    Steps: 'steps',
    Positions: 'positions',
    Count: 'count',
    Values: 'values'
  };
  exports.PipsType = {
    None: -1,
    NoValue: 0,
    LargeValue: 1,
    SmallValue: 2
  };

  //RTL
  var isRTL = directorist.rtl === 'true';
  var direction;
  if (isRTL) {
    direction = 'right';
  } else {
    direction = 'left';
  }
  function isValidFormatter(entry) {
    return isValidPartialFormatter(entry) && typeof entry.from === 'function';
  }
  function isValidPartialFormatter(entry) {
    // partial formatters only need a to function and not a from function
    return _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default()(entry) === "object" && typeof entry.to === "function";
  }
  function removeElement(el) {
    el.parentElement.removeChild(el);
  }
  function isSet(value) {
    return value !== null && value !== undefined;
  }
  // Bindable version
  function preventDefault(e) {
    e.preventDefault();
  }
  // Removes duplicates from an array.
  function unique(array) {
    return array.filter(function (a) {
      return !this[a] ? this[a] = true : false;
    }, {});
  }
  // Round a value to the closest 'to'.
  function closest(value, to) {
    return Math.round(value / to) * to;
  }
  // Current position of an element relative to the document.
  function offset(elem, orientation) {
    var rect = elem.getBoundingClientRect();
    var doc = elem.ownerDocument;
    var docElem = doc.documentElement;
    var pageOffset = getPageOffset(doc);
    // getBoundingClientRect contains left scroll in Chrome on Android.
    // I haven't found a feature detection that proves this. Worst case
    // scenario on mis-match: the 'tap' feature on horizontal sliders breaks.
    if (/webkit.*Chrome.*Mobile/i.test(navigator.userAgent)) {
      pageOffset.x = 0;
    }
    return orientation ? rect.top + pageOffset.y - docElem.clientTop : rect.left + pageOffset.x - docElem.clientLeft;
  }
  // Checks whether a value is numerical.
  function isNumeric(a) {
    return typeof a === "number" && !isNaN(a) && isFinite(a);
  }
  // Sets a class and removes it after [duration] ms.
  function addClassFor(element, className, duration) {
    if (duration > 0) {
      addClass(element, className);
      setTimeout(function () {
        removeClass(element, className);
      }, duration);
    }
  }
  // Limits a value to 0 - 100
  function limit(a) {
    return Math.max(Math.min(a, 100), 0);
  }
  // Wraps a variable as an array, if it isn't one yet.
  // Note that an input array is returned by reference!
  function asArray(a) {
    return Array.isArray(a) ? a : [a];
  }
  // Counts decimals
  function countDecimals(numStr) {
    numStr = String(numStr);
    var pieces = numStr.split(".");
    return pieces.length > 1 ? pieces[1].length : 0;
  }
  // add_class
  function addClass(el, className) {
    if (el.classList && !/\s/.test(className)) {
      el.classList.add(className);
    } else {
      el.className += " " + className;
    }
  }
  // remove_class
  function removeClass(el, className) {
    if (el.classList && !/\s/.test(className)) {
      el.classList.remove(className);
    } else {
      el.className = el.className.replace(new RegExp("(^|\\b)" + className.split(" ").join("|") + "(\\b|$)", "gi"), " ");
    }
  }
  // https://plainjs.com/javascript/attributes/adding-removing-and-testing-for-classes-9/
  function hasClass(el, className) {
    return el.classList ? el.classList.contains(className) : new RegExp("\\b" + className + "\\b").test(el.className);
  }
  // https://developer.mozilla.org/en-US/docs/Web/API/Window/scrollY#Notes
  function getPageOffset(doc) {
    var supportPageOffset = window.scrollX !== undefined;
    var isCSS1Compat = (doc.compatMode || "") === "CSS1Compat";
    var x = supportPageOffset ? window.scrollX : isCSS1Compat ? doc.documentElement.scrollLeft : doc.body.scrollLeft;
    var y = supportPageOffset ? window.scrollY : isCSS1Compat ? doc.documentElement.scrollTop : doc.body.scrollTop;
    return {
      x: x,
      y: y
    };
  }
  // we provide a function to compute constants instead
  // of accessing window.* as soon as the module needs it
  // so that we do not compute anything if not needed
  function getActions() {
    // Determine the events to bind. IE11 implements pointerEvents without
    // a prefix, which breaks compatibility with the IE10 implementation.
    return window.navigator.pointerEnabled ? {
      start: "pointerdown",
      move: "pointermove",
      end: "pointerup"
    } : window.navigator.msPointerEnabled ? {
      start: "MSPointerDown",
      move: "MSPointerMove",
      end: "MSPointerUp"
    } : {
      start: "mousedown touchstart",
      move: "mousemove touchmove",
      end: "mouseup touchend"
    };
  }
  // https://github.com/WICG/EventListenerOptions/blob/gh-pages/explainer.md
  // Issue #785
  function getSupportsPassive() {
    var supportsPassive = false;
    /* eslint-disable */
    try {
      var opts = Object.defineProperty({}, "passive", {
        get: function get() {
          supportsPassive = true;
        }
      });
      // @ts-ignore
      window.addEventListener("test", null, opts);
    } catch (e) {}
    /* eslint-enable */
    return supportsPassive;
  }
  function getSupportsTouchActionNone() {
    return window.CSS && CSS.supports && CSS.supports("touch-action", "none");
  }
  //endregion
  //region Range Calculation
  // Determine the size of a sub-range in relation to a full range.
  function subRangeRatio(pa, pb) {
    return 100 / (pb - pa);
  }
  // (percentage) How many percent is this value of this range?
  function fromPercentage(range, value, startRange) {
    return value * 100 / (range[startRange + 1] - range[startRange]);
  }
  // (percentage) Where is this value on this range?
  function toPercentage(range, value) {
    return fromPercentage(range, range[0] < 0 ? value + Math.abs(range[0]) : value - range[0], 0);
  }
  // (value) How much is this percentage on this range?
  function isPercentage(range, value) {
    return value * (range[1] - range[0]) / 100 + range[0];
  }
  function getJ(value, arr) {
    var j = 1;
    while (value >= arr[j]) {
      j += 1;
    }
    return j;
  }
  // (percentage) Input a value, find where, on a scale of 0-100, it applies.
  function toStepping(xVal, xPct, value) {
    if (value >= xVal.slice(-1)[0]) {
      return 100;
    }
    var j = getJ(value, xVal);
    var va = xVal[j - 1];
    var vb = xVal[j];
    var pa = xPct[j - 1];
    var pb = xPct[j];
    return pa + toPercentage([va, vb], value) / subRangeRatio(pa, pb);
  }
  // (value) Input a percentage, find where it is on the specified range.
  function fromStepping(xVal, xPct, value) {
    // There is no range group that fits 100
    if (value >= 100) {
      return xVal.slice(-1)[0];
    }
    var j = getJ(value, xPct);
    var va = xVal[j - 1];
    var vb = xVal[j];
    var pa = xPct[j - 1];
    var pb = xPct[j];
    return isPercentage([va, vb], (value - pa) * subRangeRatio(pa, pb));
  }
  // (percentage) Get the step that applies at a certain value.
  function getStep(xPct, xSteps, snap, value) {
    if (value === 100) {
      return value;
    }
    var j = getJ(value, xPct);
    var a = xPct[j - 1];
    var b = xPct[j];
    // If 'snap' is set, steps are used as fixed points on the slider.
    if (snap) {
      // Find the closest position, a or b.
      if (value - a > (b - a) / 2) {
        return b;
      }
      return a;
    }
    if (!xSteps[j - 1]) {
      return value;
    }
    return xPct[j - 1] + closest(value - xPct[j - 1], xSteps[j - 1]);
  }
  //endregion
  //region Spectrum
  var Spectrum = /** @class */function () {
    function Spectrum(entry, snap, singleStep) {
      this.xPct = [];
      this.xVal = [];
      this.xSteps = [];
      this.xNumSteps = [];
      this.xHighestCompleteStep = [];
      this.xSteps = [singleStep || false];
      this.xNumSteps = [false];
      this.snap = snap;
      var index;
      var ordered = [];
      // Map the object keys to an array.
      Object.keys(entry).forEach(function (index) {
        ordered.push([asArray(entry[index]), index]);
      });
      // Sort all entries by value (numeric sort).
      ordered.sort(function (a, b) {
        return a[0][0] - b[0][0];
      });
      // Convert all entries to subranges.
      for (index = 0; index < ordered.length; index++) {
        this.handleEntryPoint(ordered[index][1], ordered[index][0]);
      }
      // Store the actual step values.
      // xSteps is sorted in the same order as xPct and xVal.
      this.xNumSteps = this.xSteps.slice(0);
      // Convert all numeric steps to the percentage of the subrange they represent.
      for (index = 0; index < this.xNumSteps.length; index++) {
        this.handleStepPoint(index, this.xNumSteps[index]);
      }
    }
    Spectrum.prototype.getDistance = function (value) {
      var distances = [];
      for (var index = 0; index < this.xNumSteps.length - 1; index++) {
        distances[index] = fromPercentage(this.xVal, value, index);
      }
      return distances;
    };
    // Calculate the percentual distance over the whole scale of ranges.
    // direction: 0 = backwards / 1 = forwards
    Spectrum.prototype.getAbsoluteDistance = function (value, distances, direction) {
      var xPct_index = 0;
      // Calculate range where to start calculation
      if (value < this.xPct[this.xPct.length - 1]) {
        while (value > this.xPct[xPct_index + 1]) {
          xPct_index++;
        }
      } else if (value === this.xPct[this.xPct.length - 1]) {
        xPct_index = this.xPct.length - 2;
      }
      // If looking backwards and the value is exactly at a range separator then look one range further
      if (!direction && value === this.xPct[xPct_index + 1]) {
        xPct_index++;
      }
      if (distances === null) {
        distances = [];
      }
      var start_factor;
      var rest_factor = 1;
      var rest_rel_distance = distances[xPct_index];
      var range_pct = 0;
      var rel_range_distance = 0;
      var abs_distance_counter = 0;
      var range_counter = 0;
      // Calculate what part of the start range the value is
      if (direction) {
        start_factor = (value - this.xPct[xPct_index]) / (this.xPct[xPct_index + 1] - this.xPct[xPct_index]);
      } else {
        start_factor = (this.xPct[xPct_index + 1] - value) / (this.xPct[xPct_index + 1] - this.xPct[xPct_index]);
      }
      // Do until the complete distance across ranges is calculated
      while (rest_rel_distance > 0) {
        // Calculate the percentage of total range
        range_pct = this.xPct[xPct_index + 1 + range_counter] - this.xPct[xPct_index + range_counter];
        // Detect if the margin, padding or limit is larger then the current range and calculate
        if (distances[xPct_index + range_counter] * rest_factor + 100 - start_factor * 100 > 100) {
          // If larger then take the percentual distance of the whole range
          rel_range_distance = range_pct * start_factor;
          // Rest factor of relative percentual distance still to be calculated
          rest_factor = (rest_rel_distance - 100 * start_factor) / distances[xPct_index + range_counter];
          // Set start factor to 1 as for next range it does not apply.
          start_factor = 1;
        } else {
          // If smaller or equal then take the percentual distance of the calculate percentual part of that range
          rel_range_distance = distances[xPct_index + range_counter] * range_pct / 100 * rest_factor;
          // No rest left as the rest fits in current range
          rest_factor = 0;
        }
        if (direction) {
          abs_distance_counter = abs_distance_counter - rel_range_distance;
          // Limit range to first range when distance becomes outside of minimum range
          if (this.xPct.length + range_counter >= 1) {
            range_counter--;
          }
        } else {
          abs_distance_counter = abs_distance_counter + rel_range_distance;
          // Limit range to last range when distance becomes outside of maximum range
          if (this.xPct.length - range_counter >= 1) {
            range_counter++;
          }
        }
        // Rest of relative percentual distance still to be calculated
        rest_rel_distance = distances[xPct_index + range_counter] * rest_factor;
      }
      return value + abs_distance_counter;
    };
    Spectrum.prototype.toStepping = function (value) {
      value = toStepping(this.xVal, this.xPct, value);
      return value;
    };
    Spectrum.prototype.fromStepping = function (value) {
      return fromStepping(this.xVal, this.xPct, value);
    };
    Spectrum.prototype.getStep = function (value) {
      value = getStep(this.xPct, this.xSteps, this.snap, value);
      return value;
    };
    Spectrum.prototype.getDefaultStep = function (value, isDown, size) {
      var j = getJ(value, this.xPct);
      // When at the top or stepping down, look at the previous sub-range
      if (value === 100 || isDown && value === this.xPct[j - 1]) {
        j = Math.max(j - 1, 1);
      }
      return (this.xVal[j] - this.xVal[j - 1]) / size;
    };
    Spectrum.prototype.getNearbySteps = function (value) {
      var j = getJ(value, this.xPct);
      return {
        stepBefore: {
          startValue: this.xVal[j - 2],
          step: this.xNumSteps[j - 2],
          highestStep: this.xHighestCompleteStep[j - 2]
        },
        thisStep: {
          startValue: this.xVal[j - 1],
          step: this.xNumSteps[j - 1],
          highestStep: this.xHighestCompleteStep[j - 1]
        },
        stepAfter: {
          startValue: this.xVal[j],
          step: this.xNumSteps[j],
          highestStep: this.xHighestCompleteStep[j]
        }
      };
    };
    Spectrum.prototype.countStepDecimals = function () {
      var stepDecimals = this.xNumSteps.map(countDecimals);
      return Math.max.apply(null, stepDecimals);
    };
    Spectrum.prototype.hasNoSize = function () {
      return this.xVal[0] === this.xVal[this.xVal.length - 1];
    };
    // Outside testing
    Spectrum.prototype.convert = function (value) {
      return this.getStep(this.toStepping(value));
    };
    Spectrum.prototype.handleEntryPoint = function (index, value) {
      var percentage;
      // Covert min/max syntax to 0 and 100.
      if (index === "min") {
        percentage = 0;
      } else if (index === "max") {
        percentage = 100;
      } else {
        percentage = parseFloat(index);
      }
      // Check for correct input.
      if (!isNumeric(percentage) || !isNumeric(value[0])) {
        throw new Error("directoristCustomRangeSlider: 'range' value isn't numeric.");
      }
      // Store values.
      this.xPct.push(percentage);
      this.xVal.push(value[0]);
      var value1 = Number(value[1]);
      // NaN will evaluate to false too, but to keep
      // logging clear, set step explicitly. Make sure
      // not to override the 'step' setting with false.
      if (!percentage) {
        if (!isNaN(value1)) {
          this.xSteps[0] = value1;
        }
      } else {
        this.xSteps.push(isNaN(value1) ? false : value1);
      }
      this.xHighestCompleteStep.push(0);
    };
    Spectrum.prototype.handleStepPoint = function (i, n) {
      // Ignore 'false' stepping.
      if (!n) {
        return;
      }
      // Step over zero-length ranges (#948);
      if (this.xVal[i] === this.xVal[i + 1]) {
        this.xSteps[i] = this.xHighestCompleteStep[i] = this.xVal[i];
        return;
      }
      // Factor to range ratio
      this.xSteps[i] = fromPercentage([this.xVal[i], this.xVal[i + 1]], n, 0) / subRangeRatio(this.xPct[i], this.xPct[i + 1]);
      var totalSteps = (this.xVal[i + 1] - this.xVal[i]) / this.xNumSteps[i];
      var highestStep = Math.ceil(Number(totalSteps.toFixed(3)) - 1);
      var step = this.xVal[i] + this.xNumSteps[i] * highestStep;
      this.xHighestCompleteStep[i] = step;
    };
    return Spectrum;
  }();
  //endregion
  //region Options
  /*	Every input option is tested and parsed. This will prevent
      endless validation in internal methods. These tests are
      structured with an item for every option available. An
      option can be marked as required by setting the 'r' flag.
      The testing function is provided with three arguments:
          - The provided value for the option;
          - A reference to the options object;
          - The name for the option;
       The testing function returns false when an error is detected,
      or true when everything is OK. It can also modify the option
      object, to make sure all values can be correctly looped elsewhere. */
  //region Defaults
  var defaultFormatter = {
    to: function to(value) {
      return value === undefined ? "" : value.toFixed(2);
    },
    from: Number
  };
  var cssClasses = {
    target: "target",
    base: "base",
    origin: "origin",
    handle: "handle",
    handleLower: "handle-lower",
    handleUpper: "handle-upper",
    touchArea: "touch-area",
    horizontal: "horizontal",
    vertical: "vertical",
    background: "background",
    connect: "connect",
    connects: "connects",
    ltr: "ltr",
    rtl: "rtl",
    textDirectionLtr: "txt-dir-ltr",
    textDirectionRtl: "txt-dir-rtl",
    draggable: "draggable",
    drag: "state-drag",
    tap: "state-tap",
    active: "active",
    tooltip: "tooltip",
    pips: "pips",
    pipsHorizontal: "pips-horizontal",
    pipsVertical: "pips-vertical",
    marker: "marker",
    markerHorizontal: "marker-horizontal",
    markerVertical: "marker-vertical",
    markerNormal: "marker-normal",
    markerLarge: "marker-large",
    markerSub: "marker-sub",
    value: "value",
    valueHorizontal: "value-horizontal",
    valueVertical: "value-vertical",
    valueNormal: "value-normal",
    valueLarge: "value-large",
    valueSub: "value-sub"
  };
  // Namespaces of internal event listeners
  var INTERNAL_EVENT_NS = {
    tooltips: ".__tooltips",
    aria: ".__aria"
  };
  //endregion
  function customRangeStep(parsed, entry) {
    if (!isNumeric(entry)) {
      throw new Error("directoristCustomRangeSlider: 'step' is not numeric.");
    }
    // The step option can still be used to set stepping
    // for linear sliders. Overwritten if set in 'range'.
    parsed.singleStep = entry;
  }
  function customRangeKeyboardPageMultiplier(parsed, entry) {
    if (!isNumeric(entry)) {
      throw new Error("directoristCustomRangeSlider: 'keyboardPageMultiplier' is not numeric.");
    }
    parsed.keyboardPageMultiplier = entry;
  }
  function customRangeKeyboardMultiplier(parsed, entry) {
    if (!isNumeric(entry)) {
      throw new Error("directoristCustomRangeSlider: 'keyboardMultiplier' is not numeric.");
    }
    parsed.keyboardMultiplier = entry;
  }
  function customRangeKeyboardDefaultStep(parsed, entry) {
    if (!isNumeric(entry)) {
      throw new Error("directoristCustomRangeSlider: 'keyboardDefaultStep' is not numeric.");
    }
    parsed.keyboardDefaultStep = entry;
  }
  function customRangeRange(parsed, entry) {
    // Filter incorrect input.
    if (_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default()(entry) !== "object" || Array.isArray(entry)) {
      throw new Error("directoristCustomRangeSlider: 'range' is not an object.");
    }
    // Catch missing start or end.
    if (entry.min === undefined || entry.max === undefined) {
      throw new Error("directoristCustomRangeSlider: Missing 'min' or 'max' in 'range'.");
    }
    parsed.spectrum = new Spectrum(entry, parsed.snap || false, parsed.singleStep);
  }
  function customRangeStart(parsed, entry) {
    entry = asArray(entry);
    // Validate input. Values aren't tested, as the public .val method
    // will always provide a valid location.
    if (!Array.isArray(entry) || !entry.length) {
      throw new Error("directoristCustomRangeSlider: 'start' option is incorrect.");
    }
    // Store the number of handles.
    parsed.handles = entry.length;
    // When the slider is initialized, the .val method will
    // be called with the start options.
    parsed.start = entry;
  }
  function customRangeSnap(parsed, entry) {
    if (typeof entry !== "boolean") {
      throw new Error("directoristCustomRangeSlider: 'snap' option must be a boolean.");
    }
    // Enforce 100% stepping within subranges.
    parsed.snap = entry;
  }
  function customRangeAnimate(parsed, entry) {
    if (typeof entry !== "boolean") {
      throw new Error("directoristCustomRangeSlider: 'animate' option must be a boolean.");
    }
    // Enforce 100% stepping within subranges.
    parsed.animate = entry;
  }
  function customRangeAnimationDuration(parsed, entry) {
    if (typeof entry !== "number") {
      throw new Error("directoristCustomRangeSlider: 'animationDuration' option must be a number.");
    }
    parsed.animationDuration = entry;
  }
  function customRangeConnect(parsed, entry) {
    var connect = [false];
    var i;
    // Map legacy options
    if (entry === "lower") {
      entry = [true, false];
    } else if (entry === "upper") {
      entry = [false, true];
    }
    // Handle boolean options
    if (entry === true || entry === false) {
      for (i = 1; i < parsed.handles; i++) {
        connect.push(entry);
      }
      connect.push(false);
    }
    // Reject invalid input
    else if (!Array.isArray(entry) || !entry.length || entry.length !== parsed.handles + 1) {
      throw new Error("directoristCustomRangeSlider: 'connect' option doesn't match handle count.");
    } else {
      connect = entry;
    }
    parsed.connect = connect;
  }
  function customRangeOrientation(parsed, entry) {
    // Set orientation to an a numerical value for easy
    // array selection.
    switch (entry) {
      case "horizontal":
        parsed.ort = 0;
        break;
      case "vertical":
        parsed.ort = 1;
        break;
      default:
        throw new Error("directoristCustomRangeSlider: 'orientation' option is invalid.");
    }
  }
  function customRangeMargin(parsed, entry) {
    if (!isNumeric(entry)) {
      throw new Error("directoristCustomRangeSlider: 'margin' option must be numeric.");
    }
    // Issue #582
    if (entry === 0) {
      return;
    }
    parsed.margin = parsed.spectrum.getDistance(entry);
  }
  function customRangeLimit(parsed, entry) {
    if (!isNumeric(entry)) {
      throw new Error("directoristCustomRangeSlider: 'limit' option must be numeric.");
    }
    parsed.limit = parsed.spectrum.getDistance(entry);
    if (!parsed.limit || parsed.handles < 2) {
      throw new Error("directoristCustomRangeSlider: 'limit' option is only supported on linear sliders with 2 or more handles.");
    }
  }
  function customRangePadding(parsed, entry) {
    var index;
    if (!isNumeric(entry) && !Array.isArray(entry)) {
      throw new Error("directoristCustomRangeSlider: 'padding' option must be numeric or array of exactly 2 numbers.");
    }
    if (Array.isArray(entry) && !(entry.length === 2 || isNumeric(entry[0]) || isNumeric(entry[1]))) {
      throw new Error("directoristCustomRangeSlider: 'padding' option must be numeric or array of exactly 2 numbers.");
    }
    if (entry === 0) {
      return;
    }
    if (!Array.isArray(entry)) {
      entry = [entry, entry];
    }
    // 'getDistance' returns false for invalid values.
    parsed.padding = [parsed.spectrum.getDistance(entry[0]), parsed.spectrum.getDistance(entry[1])];
    for (index = 0; index < parsed.spectrum.xNumSteps.length - 1; index++) {
      // last "range" can't contain step size as it is purely an endpoint.
      if (parsed.padding[0][index] < 0 || parsed.padding[1][index] < 0) {
        throw new Error("directoristCustomRangeSlider: 'padding' option must be a positive number(s).");
      }
    }
    var totalPadding = entry[0] + entry[1];
    var firstValue = parsed.spectrum.xVal[0];
    var lastValue = parsed.spectrum.xVal[parsed.spectrum.xVal.length - 1];
    if (totalPadding / (lastValue - firstValue) > 1) {
      throw new Error("directoristCustomRangeSlider: 'padding' option must not exceed 100% of the range.");
    }
  }
  function customRangeDirection(parsed, entry) {
    // Set direction as a numerical value for easy parsing.
    // Invert connection for RTL sliders, so that the proper
    // handles get the connect/background classes.
    switch (entry) {
      case "ltr":
        parsed.dir = 0;
        break;
      case "rtl":
        parsed.dir = 1;
        break;
      default:
        throw new Error("directoristCustomRangeSlider: 'direction' option was not recognized.");
    }
  }
  function customRangeBehaviour(parsed, entry) {
    // Make sure the input is a string.
    if (typeof entry !== "string") {
      throw new Error("directoristCustomRangeSlider: 'behaviour' must be a string containing options.");
    }
    // Check if the string contains any keywords.
    // None are required.
    var tap = entry.indexOf("tap") >= 0;
    var drag = entry.indexOf("drag") >= 0;
    var fixed = entry.indexOf("fixed") >= 0;
    var snap = entry.indexOf("snap") >= 0;
    var hover = entry.indexOf("hover") >= 0;
    var unconstrained = entry.indexOf("unconstrained") >= 0;
    var dragAll = entry.indexOf("drag-all") >= 0;
    var smoothSteps = entry.indexOf("smooth-steps") >= 0;
    if (fixed) {
      if (parsed.handles !== 2) {
        throw new Error("directoristCustomRangeSlider: 'fixed' behaviour must be used with 2 handles");
      }
      // Use margin to enforce fixed state
      customRangeMargin(parsed, parsed.start[1] - parsed.start[0]);
    }
    if (unconstrained && (parsed.margin || parsed.limit)) {
      throw new Error("directoristCustomRangeSlider: 'unconstrained' behaviour cannot be used with margin or limit");
    }
    parsed.events = {
      tap: tap || snap,
      drag: drag,
      dragAll: dragAll,
      smoothSteps: smoothSteps,
      fixed: fixed,
      snap: snap,
      hover: hover,
      unconstrained: unconstrained
    };
  }
  function customRangeTooltips(parsed, entry) {
    if (entry === false) {
      return;
    }
    if (entry === true || isValidPartialFormatter(entry)) {
      parsed.tooltips = [];
      for (var i = 0; i < parsed.handles; i++) {
        parsed.tooltips.push(entry);
      }
    } else {
      entry = asArray(entry);
      if (entry.length !== parsed.handles) {
        throw new Error("directoristCustomRangeSlider: must pass a formatter for all handles.");
      }
      entry.forEach(function (formatter) {
        if (typeof formatter !== "boolean" && !isValidPartialFormatter(formatter)) {
          throw new Error("directoristCustomRangeSlider: 'tooltips' must be passed a formatter or 'false'.");
        }
      });
      parsed.tooltips = entry;
    }
  }
  function customRangeHandleAttributes(parsed, entry) {
    if (entry.length !== parsed.handles) {
      throw new Error("directoristCustomRangeSlider: must pass a attributes for all handles.");
    }
    parsed.handleAttributes = entry;
  }
  function customRangeAriaFormat(parsed, entry) {
    if (!isValidPartialFormatter(entry)) {
      throw new Error("directoristCustomRangeSlider: 'ariaFormat' requires 'to' method.");
    }
    parsed.ariaFormat = entry;
  }
  function customRangeFormat(parsed, entry) {
    if (!isValidFormatter(entry)) {
      throw new Error("directoristCustomRangeSlider: 'format' requires 'to' and 'from' methods.");
    }
    parsed.format = entry;
  }
  function customRangeKeyboardSupport(parsed, entry) {
    if (typeof entry !== "boolean") {
      throw new Error("directoristCustomRangeSlider: 'keyboardSupport' option must be a boolean.");
    }
    parsed.keyboardSupport = entry;
  }
  function customRangeDocumentElement(parsed, entry) {
    // This is an advanced option. Passed values are used without validation.
    parsed.documentElement = entry;
  }
  function customRangeCssPrefix(parsed, entry) {
    if (typeof entry !== "string" && entry !== false) {
      throw new Error("directoristCustomRangeSlider: 'cssPrefix' must be a string or `false`.");
    }
    parsed.cssPrefix = entry;
  }
  function customRangeCssClasses(parsed, entry) {
    if (_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default()(entry) !== "object") {
      throw new Error("directoristCustomRangeSlider: 'cssClasses' must be an object.");
    }
    if (typeof parsed.cssPrefix === "string") {
      parsed.cssClasses = {};
      Object.keys(entry).forEach(function (key) {
        parsed.cssClasses[key] = parsed.cssPrefix + entry[key];
      });
    } else {
      parsed.cssClasses = entry;
    }
  }
  // Test all developer settings and parse to assumption-safe values.
  function customRangeOptions(options) {
    // To prove a fix for #537, freeze options here.
    // If the object is modified, an error will be thrown.
    // Object.freeze(options);
    var parsed = {
      margin: null,
      limit: null,
      padding: null,
      animate: true,
      animationDuration: 300,
      ariaFormat: defaultFormatter,
      format: defaultFormatter
    };
    // Tests are executed in the order they are presented here.
    var customRanges = {
      step: {
        r: false,
        t: customRangeStep
      },
      keyboardPageMultiplier: {
        r: false,
        t: customRangeKeyboardPageMultiplier
      },
      keyboardMultiplier: {
        r: false,
        t: customRangeKeyboardMultiplier
      },
      keyboardDefaultStep: {
        r: false,
        t: customRangeKeyboardDefaultStep
      },
      start: {
        r: true,
        t: customRangeStart
      },
      connect: {
        r: true,
        t: customRangeConnect
      },
      direction: {
        r: true,
        t: customRangeDirection
      },
      snap: {
        r: false,
        t: customRangeSnap
      },
      animate: {
        r: false,
        t: customRangeAnimate
      },
      animationDuration: {
        r: false,
        t: customRangeAnimationDuration
      },
      range: {
        r: true,
        t: customRangeRange
      },
      orientation: {
        r: false,
        t: customRangeOrientation
      },
      margin: {
        r: false,
        t: customRangeMargin
      },
      limit: {
        r: false,
        t: customRangeLimit
      },
      padding: {
        r: false,
        t: customRangePadding
      },
      behaviour: {
        r: true,
        t: customRangeBehaviour
      },
      ariaFormat: {
        r: false,
        t: customRangeAriaFormat
      },
      format: {
        r: false,
        t: customRangeFormat
      },
      tooltips: {
        r: false,
        t: customRangeTooltips
      },
      keyboardSupport: {
        r: true,
        t: customRangeKeyboardSupport
      },
      documentElement: {
        r: false,
        t: customRangeDocumentElement
      },
      cssPrefix: {
        r: true,
        t: customRangeCssPrefix
      },
      cssClasses: {
        r: true,
        t: customRangeCssClasses
      },
      handleAttributes: {
        r: false,
        t: customRangeHandleAttributes
      }
    };
    var defaults = {
      connect: false,
      direction: "ltr",
      behaviour: "tap",
      orientation: "horizontal",
      keyboardSupport: true,
      cssPrefix: "directorist-custom-range-slider-",
      cssClasses: cssClasses,
      keyboardPageMultiplier: 5,
      keyboardMultiplier: 1,
      keyboardDefaultStep: 10
    };
    // AriaFormat defaults to regular format, if any.
    if (options.format && !options.ariaFormat) {
      options.ariaFormat = options.format;
    }
    // Run all options through a testing mechanism to ensure correct
    // input. It should be noted that options might get modified to
    // be handled properly. E.g. wrapping integers in arrays.
    Object.keys(customRanges).forEach(function (name) {
      // If the option isn't set, but it is required, throw an error.
      if (!isSet(options[name]) && defaults[name] === undefined) {
        if (customRanges[name].r) {
          throw new Error("directoristCustomRangeSlider: '" + name + "' is required.");
        }
        return;
      }
      customRanges[name].t(parsed, !isSet(options[name]) ? defaults[name] : options[name]);
    });
    // Forward pips options
    parsed.pips = options.pips;
    // All recent browsers accept unprefixed transform.
    // We need -ms- for IE9 and -webkit- for older Android;
    // Assume use of -webkit- if unprefixed and -ms- are not supported.
    // https://caniuse.com/#feat=transforms2d
    var d = document.createElement("div");
    var msPrefix = d.style.msTransform !== undefined;
    var noPrefix = d.style.transform !== undefined;
    parsed.transformRule = noPrefix ? "transform" : msPrefix ? "msTransform" : "webkitTransform";
    // Pips don't move, so we can place them using left/top.
    var styles = [["left", "top"], ["right", "bottom"]];
    parsed.style = styles[parsed.dir][parsed.ort];
    return parsed;
  }
  //endregion
  function scope(target, options, originalOptions) {
    var actions = getActions();
    var supportsTouchActionNone = getSupportsTouchActionNone();
    var supportsPassive = supportsTouchActionNone && getSupportsPassive();
    // All variables local to 'scope' are prefixed with 'scope_'
    // Slider DOM Nodes
    var scope_Target = target;
    var scope_Base;
    var scope_Handles;
    var scope_Connects;
    var scope_Pips;
    var scope_Tooltips;
    // Slider state values
    var scope_Spectrum = options.spectrum;
    var scope_Values = [];
    var scope_Locations = [];
    var scope_HandleNumbers = [];
    var scope_ActiveHandlesCount = 0;
    var scope_Events = {};
    // Document Nodes
    var scope_Document = target.ownerDocument;
    var scope_DocumentElement = options.documentElement || scope_Document.documentElement;
    var scope_Body = scope_Document.body;
    // For horizontal sliders in standard ltr documents,
    // make .directorist-custom-range-slider-origin overflow to the left so the document doesn't scroll.
    var scope_DirOffset = scope_Document.dir === "rtl" || options.ort === 1 ? 0 : 100;
    // Creates a node, adds it to target, returns the new node.
    function addNodeTo(addTarget, className) {
      var div = scope_Document.createElement("div");
      if (className) {
        addClass(div, className);
      }
      addTarget.appendChild(div);
      return div;
    }
    // Append a origin to the base
    function addOrigin(base, handleNumber) {
      var origin = addNodeTo(base, options.cssClasses.origin);
      var handle = addNodeTo(origin, options.cssClasses.handle);
      addNodeTo(handle, options.cssClasses.touchArea);
      handle.setAttribute("data-handle", String(handleNumber));
      if (options.keyboardSupport) {
        // https://developer.mozilla.org/en-US/docs/Web/HTML/Global_attributes/tabindex
        // 0 = focusable and reachable
        handle.setAttribute("tabindex", "0");
        handle.addEventListener("keydown", function (event) {
          return eventKeydown(event, handleNumber);
        });
      }
      if (options.handleAttributes !== undefined) {
        var attributes_1 = options.handleAttributes[handleNumber];
        Object.keys(attributes_1).forEach(function (attribute) {
          handle.setAttribute(attribute, attributes_1[attribute]);
        });
      }
      handle.setAttribute("role", "slider");
      handle.setAttribute("aria-orientation", options.ort ? "vertical" : "horizontal");
      if (handleNumber === 0) {
        addClass(handle, options.cssClasses.handleLower);
      } else if (handleNumber === options.handles - 1) {
        addClass(handle, options.cssClasses.handleUpper);
      }
      origin.handle = handle;
      return origin;
    }
    // Insert nodes for connect elements
    function addConnect(base, add) {
      if (!add) {
        return false;
      }
      return addNodeTo(base, options.cssClasses.connect);
    }
    // Add handles to the slider base.
    function addElements(connectOptions, base) {
      var connectBase = addNodeTo(base, options.cssClasses.connects);
      scope_Handles = [];
      scope_Connects = [];
      scope_Connects.push(addConnect(connectBase, connectOptions[0]));
      // [::::O====O====O====]
      // connectOptions = [0, 1, 1, 1]
      for (var i = 0; i < options.handles; i++) {
        // Keep a list of all added handles.
        scope_Handles.push(addOrigin(base, i));
        scope_HandleNumbers[i] = i;
        scope_Connects.push(addConnect(connectBase, connectOptions[i + 1]));
      }
    }
    // Initialize a single slider.
    function addSlider(addTarget) {
      // Apply classes and data to the target.
      addClass(addTarget, options.cssClasses.target);
      if (options.dir === 0) {
        addClass(addTarget, options.cssClasses.ltr);
      } else {
        addClass(addTarget, options.cssClasses.rtl);
      }
      if (options.ort === 0) {
        addClass(addTarget, options.cssClasses.horizontal);
      } else {
        addClass(addTarget, options.cssClasses.vertical);
      }
      var textDirection = getComputedStyle(addTarget).direction;
      if (textDirection === "rtl") {
        addClass(addTarget, options.cssClasses.textDirectionRtl);
      } else {
        addClass(addTarget, options.cssClasses.textDirectionLtr);
      }
      return addNodeTo(addTarget, options.cssClasses.base);
    }
    function addTooltip(handle, handleNumber) {
      if (!options.tooltips || !options.tooltips[handleNumber]) {
        return false;
      }
      return addNodeTo(handle.firstChild, options.cssClasses.tooltip);
    }
    function isSliderDisabled() {
      return scope_Target.hasAttribute("disabled");
    }
    // Disable the slider dragging if any handle is disabled
    function isHandleDisabled(handleNumber) {
      var handleOrigin = scope_Handles[handleNumber];
      return handleOrigin.hasAttribute("disabled");
    }
    function disable(handleNumber) {
      if (handleNumber !== null && handleNumber !== undefined) {
        scope_Handles[handleNumber].setAttribute("disabled", "");
        scope_Handles[handleNumber].handle.removeAttribute("tabindex");
      } else {
        scope_Target.setAttribute("disabled", "");
        scope_Handles.forEach(function (handle) {
          handle.handle.removeAttribute("tabindex");
        });
      }
    }
    function enable(handleNumber) {
      if (handleNumber !== null && handleNumber !== undefined) {
        scope_Handles[handleNumber].removeAttribute("disabled");
        scope_Handles[handleNumber].handle.setAttribute("tabindex", "0");
      } else {
        scope_Target.removeAttribute("disabled");
        scope_Handles.forEach(function (handle) {
          handle.removeAttribute("disabled");
          handle.handle.setAttribute("tabindex", "0");
        });
      }
    }
    function removeTooltips() {
      if (scope_Tooltips) {
        removeEvent("update" + INTERNAL_EVENT_NS.tooltips);
        scope_Tooltips.forEach(function (tooltip) {
          if (tooltip) {
            removeElement(tooltip);
          }
        });
        scope_Tooltips = null;
      }
    }
    // The tooltips option is a shorthand for using the 'update' event.
    function tooltips() {
      removeTooltips();
      // Tooltips are added with options.tooltips in original order.
      scope_Tooltips = scope_Handles.map(addTooltip);
      bindEvent("update" + INTERNAL_EVENT_NS.tooltips, function (values, handleNumber, unencoded) {
        if (!scope_Tooltips || !options.tooltips) {
          return;
        }
        if (scope_Tooltips[handleNumber] === false) {
          return;
        }
        var formattedValue = values[handleNumber];
        if (options.tooltips[handleNumber] !== true) {
          formattedValue = options.tooltips[handleNumber].to(unencoded[handleNumber]);
        }
        scope_Tooltips[handleNumber].innerHTML = formattedValue;
      });
    }
    function aria() {
      removeEvent("update" + INTERNAL_EVENT_NS.aria);
      bindEvent("update" + INTERNAL_EVENT_NS.aria, function (values, handleNumber, unencoded, tap, positions) {
        // Update Aria Values for all handles, as a change in one changes min and max values for the next.
        scope_HandleNumbers.forEach(function (index) {
          var handle = scope_Handles[index];
          var min = checkHandlePosition(scope_Locations, index, 0, true, true, true);
          var max = checkHandlePosition(scope_Locations, index, 100, true, true, true);
          var now = positions[index];
          // Formatted value for display
          var text = String(options.ariaFormat.to(unencoded[index]));
          // Map to slider range values
          min = scope_Spectrum.fromStepping(min).toFixed(1);
          max = scope_Spectrum.fromStepping(max).toFixed(1);
          now = scope_Spectrum.fromStepping(now).toFixed(1);
          handle.children[0].setAttribute("aria-valuemin", min);
          handle.children[0].setAttribute("aria-valuemax", max);
          handle.children[0].setAttribute("aria-valuenow", now);
          handle.children[0].setAttribute("aria-valuetext", text);
        });
      });
    }
    function getGroup(pips) {
      // Use the range.
      if (pips.mode === exports.PipsMode.Range || pips.mode === exports.PipsMode.Steps) {
        return scope_Spectrum.xVal;
      }
      if (pips.mode === exports.PipsMode.Count) {
        if (pips.values < 2) {
          throw new Error("directoristCustomRangeSlider: 'values' (>= 2) required for mode 'count'.");
        }
        // Divide 0 - 100 in 'count' parts.
        var interval = pips.values - 1;
        var spread = 100 / interval;
        var values = [];
        // List these parts and have them handled as 'positions'.
        while (interval--) {
          values[interval] = interval * spread;
        }
        values.push(100);
        return mapToRange(values, pips.stepped);
      }
      if (pips.mode === exports.PipsMode.Positions) {
        // Map all percentages to on-range values.
        return mapToRange(pips.values, pips.stepped);
      }
      if (pips.mode === exports.PipsMode.Values) {
        // If the value must be stepped, it needs to be converted to a percentage first.
        if (pips.stepped) {
          return pips.values.map(function (value) {
            // Convert to percentage, apply step, return to value.
            return scope_Spectrum.fromStepping(scope_Spectrum.getStep(scope_Spectrum.toStepping(value)));
          });
        }
        // Otherwise, we can simply use the values.
        return pips.values;
      }
      return []; // pips.mode = never
    }

    function mapToRange(values, stepped) {
      return values.map(function (value) {
        return scope_Spectrum.fromStepping(stepped ? scope_Spectrum.getStep(value) : value);
      });
    }
    function generateSpread(pips) {
      function safeIncrement(value, increment) {
        // Avoid floating point variance by dropping the smallest decimal places.
        return Number((value + increment).toFixed(7));
      }
      var group = getGroup(pips);
      var indexes = {};
      var firstInRange = scope_Spectrum.xVal[0];
      var lastInRange = scope_Spectrum.xVal[scope_Spectrum.xVal.length - 1];
      var ignoreFirst = false;
      var ignoreLast = false;
      var prevPct = 0;
      // Create a copy of the group, sort it and filter away all duplicates.
      group = unique(group.slice().sort(function (a, b) {
        return a - b;
      }));
      // Make sure the range starts with the first element.
      if (group[0] !== firstInRange) {
        group.unshift(firstInRange);
        ignoreFirst = true;
      }
      // Likewise for the last one.
      if (group[group.length - 1] !== lastInRange) {
        group.push(lastInRange);
        ignoreLast = true;
      }
      group.forEach(function (current, index) {
        // Get the current step and the lower + upper positions.
        var step;
        var i;
        var q;
        var low = current;
        var high = group[index + 1];
        var newPct;
        var pctDifference;
        var pctPos;
        var type;
        var steps;
        var realSteps;
        var stepSize;
        var isSteps = pips.mode === exports.PipsMode.Steps;
        // When using 'steps' mode, use the provided steps.
        // Otherwise, we'll step on to the next subrange.
        if (isSteps) {
          step = scope_Spectrum.xNumSteps[index];
        }
        // Default to a 'full' step.
        if (!step) {
          step = high - low;
        }
        // If high is undefined we are at the last subrange. Make sure it iterates once (#1088)
        if (high === undefined) {
          high = low;
        }
        // Make sure step isn't 0, which would cause an infinite loop (#654)
        step = Math.max(step, 0.0000001);
        // Find all steps in the subrange.
        for (i = low; i <= high; i = safeIncrement(i, step)) {
          // Get the percentage value for the current step,
          // calculate the size for the subrange.
          newPct = scope_Spectrum.toStepping(i);
          pctDifference = newPct - prevPct;
          steps = pctDifference / (pips.density || 1);
          realSteps = Math.round(steps);
          // This ratio represents the amount of percentage-space a point indicates.
          // For a density 1 the points/percentage = 1. For density 2, that percentage needs to be re-divided.
          // Round the percentage offset to an even number, then divide by two
          // to spread the offset on both sides of the range.
          stepSize = pctDifference / realSteps;
          // Divide all points evenly, adding the correct number to this subrange.
          // Run up to <= so that 100% gets a point, event if ignoreLast is set.
          for (q = 1; q <= realSteps; q += 1) {
            // The ratio between the rounded value and the actual size might be ~1% off.
            // Correct the percentage offset by the number of points
            // per subrange. density = 1 will result in 100 points on the
            // full range, 2 for 50, 4 for 25, etc.
            pctPos = prevPct + q * stepSize;
            indexes[pctPos.toFixed(5)] = [scope_Spectrum.fromStepping(pctPos), 0];
          }
          // Determine the point type.
          type = group.indexOf(i) > -1 ? exports.PipsType.LargeValue : isSteps ? exports.PipsType.SmallValue : exports.PipsType.NoValue;
          // Enforce the 'ignoreFirst' option by overwriting the type for 0.
          if (!index && ignoreFirst && i !== high) {
            type = 0;
          }
          if (!(i === high && ignoreLast)) {
            // Mark the 'type' of this point. 0 = plain, 1 = real value, 2 = step value.
            indexes[newPct.toFixed(5)] = [i, type];
          }
          // Update the percentage count.
          prevPct = newPct;
        }
      });
      return indexes;
    }
    function addMarking(spread, filterFunc, formatter) {
      var _a, _b;
      var element = scope_Document.createElement("div");
      var valueSizeClasses = (_a = {}, _a[exports.PipsType.None] = "", _a[exports.PipsType.NoValue] = options.cssClasses.valueNormal, _a[exports.PipsType.LargeValue] = options.cssClasses.valueLarge, _a[exports.PipsType.SmallValue] = options.cssClasses.valueSub, _a);
      var markerSizeClasses = (_b = {}, _b[exports.PipsType.None] = "", _b[exports.PipsType.NoValue] = options.cssClasses.markerNormal, _b[exports.PipsType.LargeValue] = options.cssClasses.markerLarge, _b[exports.PipsType.SmallValue] = options.cssClasses.markerSub, _b);
      var valueOrientationClasses = [options.cssClasses.valueHorizontal, options.cssClasses.valueVertical];
      var markerOrientationClasses = [options.cssClasses.markerHorizontal, options.cssClasses.markerVertical];
      addClass(element, options.cssClasses.pips);
      addClass(element, options.ort === 0 ? options.cssClasses.pipsHorizontal : options.cssClasses.pipsVertical);
      function getClasses(type, source) {
        var a = source === options.cssClasses.value;
        var orientationClasses = a ? valueOrientationClasses : markerOrientationClasses;
        var sizeClasses = a ? valueSizeClasses : markerSizeClasses;
        return source + " " + orientationClasses[options.ort] + " " + sizeClasses[type];
      }
      function addSpread(offset, value, type) {
        // Apply the filter function, if it is set.
        type = filterFunc ? filterFunc(value, type) : type;
        if (type === exports.PipsType.None) {
          return;
        }
        // Add a marker for every point
        var node = addNodeTo(element, false);
        node.className = getClasses(type, options.cssClasses.marker);
        node.style[options.style] = offset + "%";
        // Values are only appended for points marked '1' or '2'.
        if (type > exports.PipsType.NoValue) {
          node = addNodeTo(element, false);
          node.className = getClasses(type, options.cssClasses.value);
          node.setAttribute("data-value", String(value));
          node.style[options.style] = offset + "%";
          node.innerHTML = String(formatter.to(value));
        }
      }
      // Append all points.
      Object.keys(spread).forEach(function (offset) {
        addSpread(offset, spread[offset][0], spread[offset][1]);
      });
      return element;
    }
    function removePips() {
      if (scope_Pips) {
        removeElement(scope_Pips);
        scope_Pips = null;
      }
    }
    function pips(pips) {
      // Fix #669
      removePips();
      var spread = generateSpread(pips);
      var filter = pips.filter;
      var format = pips.format || {
        to: function to(value) {
          return String(Math.round(value));
        }
      };
      scope_Pips = scope_Target.appendChild(addMarking(spread, filter, format));
      return scope_Pips;
    }
    // Shorthand for base dimensions.
    function baseSize() {
      var rect = scope_Base.getBoundingClientRect();
      var alt = "offset" + ["Width", "Height"][options.ort];
      return options.ort === 0 ? rect.width || scope_Base[alt] : rect.height || scope_Base[alt];
    }
    // Handler for attaching events trough a proxy.
    function attachEvent(events, element, callback, data) {
      // This function can be used to 'filter' events to the slider.
      // element is a node, not a nodeList
      var method = function method(event) {
        var e = fixEvent(event, data.pageOffset, data.target || element);
        // fixEvent returns false if this event has a different target
        // when handling (multi-) touch events;
        if (!e) {
          return false;
        }
        // doNotReject is passed by all end events to make sure released touches
        // are not rejected, leaving the slider "stuck" to the cursor;
        if (isSliderDisabled() && !data.doNotReject) {
          return false;
        }
        // Stop if an active 'tap' transition is taking place.
        if (hasClass(scope_Target, options.cssClasses.tap) && !data.doNotReject) {
          return false;
        }
        // Ignore right or middle clicks on start #454
        if (events === actions.start && e.buttons !== undefined && e.buttons > 1) {
          return false;
        }
        // Ignore right or middle clicks on start #454
        if (data.hover && e.buttons) {
          return false;
        }
        // 'supportsPassive' is only true if a browser also supports touch-action: none in CSS.
        // iOS safari does not, so it doesn't get to benefit from passive scrolling. iOS does support
        // touch-action: manipulation, but that allows panning, which breaks
        // sliders after zooming/on non-responsive pages.
        // See: https://bugs.webkit.org/show_bug.cgi?id=133112
        if (!supportsPassive) {
          e.preventDefault();
        }
        e.calcPoint = e.points[options.ort];
        // Call the event handler with the event [ and additional data ].
        callback(e, data);
        return;
      };
      var methods = [];
      // Bind a closure on the target for every event type.
      events.split(" ").forEach(function (eventName) {
        element.addEventListener(eventName, method, supportsPassive ? {
          passive: true
        } : false);
        methods.push([eventName, method]);
      });
      return methods;
    }
    // Provide a clean event with standardized offset values.
    function fixEvent(e, pageOffset, eventTarget) {
      // Filter the event to register the type, which can be
      // touch, mouse or pointer. Offset changes need to be
      // made on an event specific basis.
      var touch = e.type.indexOf("touch") === 0;
      var mouse = e.type.indexOf("mouse") === 0;
      var pointer = e.type.indexOf("pointer") === 0;
      var x = 0;
      var y = 0;
      // IE10 implemented pointer events with a prefix;
      if (e.type.indexOf("MSPointer") === 0) {
        pointer = true;
      }
      // Erroneous events seem to be passed in occasionally on iOS/iPadOS after user finishes interacting with
      // the slider. They appear to be of type MouseEvent, yet they don't have usual properties set. Ignore
      // events that have no touches or buttons associated with them. (#1057, #1079, #1095)
      if (e.type === "mousedown" && !e.buttons && !e.touches) {
        return false;
      }
      // The only thing one handle should be concerned about is the touches that originated on top of it.
      if (touch) {
        // Returns true if a touch originated on the target.
        var isTouchOnTarget = function isTouchOnTarget(checkTouch) {
          var target = checkTouch.target;
          return target === eventTarget || eventTarget.contains(target) || e.composed && e.composedPath().shift() === eventTarget;
        };
        // In the case of touchstart events, we need to make sure there is still no more than one
        // touch on the target so we look amongst all touches.
        if (e.type === "touchstart") {
          var targetTouches = Array.prototype.filter.call(e.touches, isTouchOnTarget);
          // Do not support more than one touch per handle.
          if (targetTouches.length > 1) {
            return false;
          }
          x = targetTouches[0].pageX;
          y = targetTouches[0].pageY;
        } else {
          // In the other cases, find on changedTouches is enough.
          var targetTouch = Array.prototype.find.call(e.changedTouches, isTouchOnTarget);
          // Cancel if the target touch has not moved.
          if (!targetTouch) {
            return false;
          }
          x = targetTouch.pageX;
          y = targetTouch.pageY;
        }
      }
      pageOffset = pageOffset || getPageOffset(scope_Document);
      if (mouse || pointer) {
        x = e.clientX + pageOffset.x;
        y = e.clientY + pageOffset.y;
      }
      e.pageOffset = pageOffset;
      e.points = [x, y];
      e.cursor = mouse || pointer; // Fix #435
      return e;
    }
    // Translate a coordinate in the document to a percentage on the slider
    function calcPointToPercentage(calcPoint) {
      var location = calcPoint - offset(scope_Base, options.ort);
      var proposal = location * 100 / baseSize();
      // Clamp proposal between 0% and 100%
      // Out-of-bound coordinates may occur when .directorist-custom-range-slider-base pseudo-elements
      // are used (e.g. contained handles feature)
      proposal = limit(proposal);
      return options.dir ? 100 - proposal : proposal;
    }
    // Find handle closest to a certain percentage on the slider
    function getClosestHandle(clickedPosition) {
      var smallestDifference = 100;
      var handleNumber = false;
      scope_Handles.forEach(function (handle, index) {
        // Disabled handles are ignored
        if (isHandleDisabled(index)) {
          return;
        }
        var handlePosition = scope_Locations[index];
        var differenceWithThisHandle = Math.abs(handlePosition - clickedPosition);
        // Initial state
        var clickAtEdge = differenceWithThisHandle === 100 && smallestDifference === 100;
        // Difference with this handle is smaller than the previously checked handle
        var isCloser = differenceWithThisHandle < smallestDifference;
        var isCloserAfter = differenceWithThisHandle <= smallestDifference && clickedPosition > handlePosition;
        if (isCloser || isCloserAfter || clickAtEdge) {
          handleNumber = index;
          smallestDifference = differenceWithThisHandle;
        }
      });
      return handleNumber;
    }
    // Fire 'end' when a mouse or pen leaves the document.
    function documentLeave(event, data) {
      if (event.type === "mouseout" && event.target.nodeName === "HTML" && event.relatedTarget === null) {
        eventEnd(event, data);
      }
    }
    // Handle movement on document for handle and range drag.
    function eventMove(event, data) {
      // Fix #498
      // Check value of .buttons in 'start' to work around a bug in IE10 mobile (data.buttonsProperty).
      // https://connect.microsoft.com/IE/feedback/details/927005/mobile-ie10-windows-phone-buttons-property-of-pointermove-event-always-zero
      // IE9 has .buttons and .which zero on mousemove.
      // Firefox breaks the spec MDN defines.
      if (navigator.appVersion.indexOf("MSIE 9") === -1 && event.buttons === 0 && data.buttonsProperty !== 0) {
        return eventEnd(event, data);
      }
      // Check if we are moving up or down
      var movement = (options.dir ? -1 : 1) * (event.calcPoint - data.startCalcPoint);
      // Convert the movement into a percentage of the slider width/height
      var proposal = movement * 100 / data.baseSize;
      moveHandles(movement > 0, proposal, data.locations, data.handleNumbers, data.connect);
    }
    // Unbind move events on document, call callbacks.
    function eventEnd(event, data) {
      // The handle is no longer active, so remove the class.
      if (data.handle) {
        removeClass(data.handle, options.cssClasses.active);
        scope_ActiveHandlesCount -= 1;
      }
      // Unbind the move and end events, which are added on 'start'.
      data.listeners.forEach(function (c) {
        scope_DocumentElement.removeEventListener(c[0], c[1]);
      });
      if (scope_ActiveHandlesCount === 0) {
        // Remove dragging class.
        removeClass(scope_Target, options.cssClasses.drag);
        setZindex();
        // Remove cursor styles and text-selection events bound to the body.
        if (event.cursor) {
          scope_Body.style.cursor = "";
          scope_Body.removeEventListener("selectstart", preventDefault);
        }
      }
      if (options.events.smoothSteps) {
        data.handleNumbers.forEach(function (handleNumber) {
          setHandle(handleNumber, scope_Locations[handleNumber], true, true, false, false);
        });
        data.handleNumbers.forEach(function (handleNumber) {
          fireEvent("update", handleNumber);
        });
      }
      data.handleNumbers.forEach(function (handleNumber) {
        fireEvent("change", handleNumber);
        fireEvent("set", handleNumber);
        fireEvent("end", handleNumber);
      });
    }
    // Bind move events on document.
    function eventStart(event, data) {
      // Ignore event if any handle is disabled
      if (data.handleNumbers.some(isHandleDisabled)) {
        return;
      }
      var handle;
      if (data.handleNumbers.length === 1) {
        var handleOrigin = scope_Handles[data.handleNumbers[0]];
        handle = handleOrigin.children[0];
        scope_ActiveHandlesCount += 1;
        // Mark the handle as 'active' so it can be styled.
        addClass(handle, options.cssClasses.active);
      }
      // A drag should never propagate up to the 'tap' event.
      event.stopPropagation();
      // Record the event listeners.
      var listeners = [];
      // Attach the move and end events.
      var moveEvent = attachEvent(actions.move, scope_DocumentElement, eventMove, {
        // The event target has changed so we need to propagate the original one so that we keep
        // relying on it to extract target touches.
        target: event.target,
        handle: handle,
        connect: data.connect,
        listeners: listeners,
        startCalcPoint: event.calcPoint,
        baseSize: baseSize(),
        pageOffset: event.pageOffset,
        handleNumbers: data.handleNumbers,
        buttonsProperty: event.buttons,
        locations: scope_Locations.slice()
      });
      var endEvent = attachEvent(actions.end, scope_DocumentElement, eventEnd, {
        target: event.target,
        handle: handle,
        listeners: listeners,
        doNotReject: true,
        handleNumbers: data.handleNumbers
      });
      var outEvent = attachEvent("mouseout", scope_DocumentElement, documentLeave, {
        target: event.target,
        handle: handle,
        listeners: listeners,
        doNotReject: true,
        handleNumbers: data.handleNumbers
      });
      // We want to make sure we pushed the listeners in the listener list rather than creating
      // a new one as it has already been passed to the event handlers.
      listeners.push.apply(listeners, moveEvent.concat(endEvent, outEvent));
      // Text selection isn't an issue on touch devices,
      // so adding cursor styles can be skipped.
      if (event.cursor) {
        // Prevent the 'I' cursor and extend the range-drag cursor.
        scope_Body.style.cursor = getComputedStyle(event.target).cursor;
        // Mark the target with a dragging state.
        if (scope_Handles.length > 1) {
          addClass(scope_Target, options.cssClasses.drag);
        }
        // Prevent text selection when dragging the handles.
        // In directoristCustomRangeSlider <= 9.2.0, this was handled by calling preventDefault on mouse/touch start/move,
        // which is scroll blocking. The selectstart event is supported by FireFox starting from version 52,
        // meaning the only holdout is iOS Safari. This doesn't matter: text selection isn't triggered there.
        // The 'cursor' flag is false.
        // See: http://caniuse.com/#search=selectstart
        scope_Body.addEventListener("selectstart", preventDefault, false);
      }
      data.handleNumbers.forEach(function (handleNumber) {
        fireEvent("start", handleNumber);
      });
    }
    // Move closest handle to tapped location.
    function eventTap(event) {
      // The tap event shouldn't propagate up
      event.stopPropagation();
      var proposal = calcPointToPercentage(event.calcPoint);
      var handleNumber = getClosestHandle(proposal);
      // Tackle the case that all handles are 'disabled'.
      if (handleNumber === false) {
        return;
      }
      // Flag the slider as it is now in a transitional state.
      // Transition takes a configurable amount of ms (default 300). Re-enable the slider after that.
      if (!options.events.snap) {
        addClassFor(scope_Target, options.cssClasses.tap, options.animationDuration);
      }
      setHandle(handleNumber, proposal, true, true);
      setZindex();
      fireEvent("slide", handleNumber, true);
      fireEvent("update", handleNumber, true);
      if (!options.events.snap) {
        fireEvent("change", handleNumber, true);
        fireEvent("set", handleNumber, true);
      } else {
        eventStart(event, {
          handleNumbers: [handleNumber]
        });
      }
    }
    // Fires a 'hover' event for a hovered mouse/pen position.
    function eventHover(event) {
      var proposal = calcPointToPercentage(event.calcPoint);
      var to = scope_Spectrum.getStep(proposal);
      var value = scope_Spectrum.fromStepping(to);
      Object.keys(scope_Events).forEach(function (targetEvent) {
        if ("hover" === targetEvent.split(".")[0]) {
          scope_Events[targetEvent].forEach(function (callback) {
            callback.call(scope_Self, value);
          });
        }
      });
    }
    // Handles keydown on focused handles
    // Don't move the document when pressing arrow keys on focused handles
    function eventKeydown(event, handleNumber) {
      if (isSliderDisabled() || isHandleDisabled(handleNumber)) {
        return false;
      }
      var horizontalKeys = ["Left", "Right"];
      var verticalKeys = ["Down", "Up"];
      var largeStepKeys = ["PageDown", "PageUp"];
      var edgeKeys = ["Home", "End"];
      if (options.dir && !options.ort) {
        // On an right-to-left slider, the left and right keys act inverted
        horizontalKeys.reverse();
      } else if (options.ort && !options.dir) {
        // On a top-to-bottom slider, the up and down keys act inverted
        verticalKeys.reverse();
        largeStepKeys.reverse();
      }
      // Strip "Arrow" for IE compatibility. https://developer.mozilla.org/en-US/docs/Web/API/KeyboardEvent/key
      var key = event.key.replace("Arrow", "");
      var isLargeDown = key === largeStepKeys[0];
      var isLargeUp = key === largeStepKeys[1];
      var isDown = key === verticalKeys[0] || key === horizontalKeys[0] || isLargeDown;
      var isUp = key === verticalKeys[1] || key === horizontalKeys[1] || isLargeUp;
      var isMin = key === edgeKeys[0];
      var isMax = key === edgeKeys[1];
      if (!isDown && !isUp && !isMin && !isMax) {
        return true;
      }
      event.preventDefault();
      var to;
      if (isUp || isDown) {
        var direction = isDown ? 0 : 1;
        var steps = getNextStepsForHandle(handleNumber);
        var step = steps[direction];
        // At the edge of a slider, do nothing
        if (step === null) {
          return false;
        }
        // No step set, use the default of 10% of the sub-range
        if (step === false) {
          step = scope_Spectrum.getDefaultStep(scope_Locations[handleNumber], isDown, options.keyboardDefaultStep);
        }
        if (isLargeUp || isLargeDown) {
          step *= options.keyboardPageMultiplier;
        } else {
          step *= options.keyboardMultiplier;
        }
        // Step over zero-length ranges (#948);
        step = Math.max(step, 0.0000001);
        // Decrement for down steps
        step = (isDown ? -1 : 1) * step;
        to = scope_Values[handleNumber] + step;
      } else if (isMax) {
        // End key
        to = options.spectrum.xVal[options.spectrum.xVal.length - 1];
      } else {
        // Home key
        to = options.spectrum.xVal[0];
      }
      setHandle(handleNumber, scope_Spectrum.toStepping(to), true, true);
      fireEvent("slide", handleNumber);
      fireEvent("update", handleNumber);
      fireEvent("change", handleNumber);
      fireEvent("set", handleNumber);
      return false;
    }
    // Attach events to several slider parts.
    function bindSliderEvents(behaviour) {
      // Attach the standard drag event to the handles.
      if (!behaviour.fixed) {
        scope_Handles.forEach(function (handle, index) {
          // These events are only bound to the visual handle
          // element, not the 'real' origin element.
          attachEvent(actions.start, handle.children[0], eventStart, {
            handleNumbers: [index]
          });
        });
      }
      // Attach the tap event to the slider base.
      if (behaviour.tap) {
        attachEvent(actions.start, scope_Base, eventTap, {});
      }
      // Fire hover events
      if (behaviour.hover) {
        attachEvent(actions.move, scope_Base, eventHover, {
          hover: true
        });
      }
      // Make the range draggable.
      if (behaviour.drag) {
        scope_Connects.forEach(function (connect, index) {
          if (connect === false || index === 0 || index === scope_Connects.length - 1) {
            return;
          }
          var handleBefore = scope_Handles[index - 1];
          var handleAfter = scope_Handles[index];
          var eventHolders = [connect];
          var handlesToDrag = [handleBefore, handleAfter];
          var handleNumbersToDrag = [index - 1, index];
          addClass(connect, options.cssClasses.draggable);
          // When the range is fixed, the entire range can
          // be dragged by the handles. The handle in the first
          // origin will propagate the start event upward,
          // but it needs to be bound manually on the other.
          if (behaviour.fixed) {
            eventHolders.push(handleBefore.children[0]);
            eventHolders.push(handleAfter.children[0]);
          }
          if (behaviour.dragAll) {
            handlesToDrag = scope_Handles;
            handleNumbersToDrag = scope_HandleNumbers;
          }
          eventHolders.forEach(function (eventHolder) {
            attachEvent(actions.start, eventHolder, eventStart, {
              handles: handlesToDrag,
              handleNumbers: handleNumbersToDrag,
              connect: connect
            });
          });
        });
      }
    }
    // Attach an event to this slider, possibly including a namespace
    function bindEvent(namespacedEvent, callback) {
      scope_Events[namespacedEvent] = scope_Events[namespacedEvent] || [];
      scope_Events[namespacedEvent].push(callback);
      // If the event bound is 'update,' fire it immediately for all handles.
      if (namespacedEvent.split(".")[0] === "update") {
        scope_Handles.forEach(function (a, index) {
          fireEvent("update", index);
        });
      }
    }
    function isInternalNamespace(namespace) {
      return namespace === INTERNAL_EVENT_NS.aria || namespace === INTERNAL_EVENT_NS.tooltips;
    }
    // Undo attachment of event
    function removeEvent(namespacedEvent) {
      var event = namespacedEvent && namespacedEvent.split(".")[0];
      var namespace = event ? namespacedEvent.substring(event.length) : namespacedEvent;
      Object.keys(scope_Events).forEach(function (bind) {
        var tEvent = bind.split(".")[0];
        var tNamespace = bind.substring(tEvent.length);
        if ((!event || event === tEvent) && (!namespace || namespace === tNamespace)) {
          // only delete protected internal event if intentional
          if (!isInternalNamespace(tNamespace) || namespace === tNamespace) {
            delete scope_Events[bind];
          }
        }
      });
    }
    // External event handling
    function fireEvent(eventName, handleNumber, tap) {
      Object.keys(scope_Events).forEach(function (targetEvent) {
        var eventType = targetEvent.split(".")[0];
        if (eventName === eventType) {
          scope_Events[targetEvent].forEach(function (callback) {
            callback.call(
            // Use the slider public API as the scope ('this')
            scope_Self,
            // Return values as array, so arg_1[arg_2] is always valid.
            scope_Values.map(options.format.to),
            // Handle index, 0 or 1
            handleNumber,
            // Un-formatted slider values
            scope_Values.slice(),
            // Event is fired by tap, true or false
            tap || false,
            // Left offset of the handle, in relation to the slider
            scope_Locations.slice(),
            // add the slider public API to an accessible parameter when this is unavailable
            scope_Self);
          });
        }
      });
    }
    // Split out the handle positioning logic so the Move event can use it, too
    function checkHandlePosition(reference, handleNumber, to, lookBackward, lookForward, getValue, smoothSteps) {
      var distance;
      // For sliders with multiple handles, limit movement to the other handle.
      // Apply the margin option by adding it to the handle positions.
      if (scope_Handles.length > 1 && !options.events.unconstrained) {
        if (lookBackward && handleNumber > 0) {
          distance = scope_Spectrum.getAbsoluteDistance(reference[handleNumber - 1], options.margin, false);
          to = Math.max(to, distance);
        }
        if (lookForward && handleNumber < scope_Handles.length - 1) {
          distance = scope_Spectrum.getAbsoluteDistance(reference[handleNumber + 1], options.margin, true);
          to = Math.min(to, distance);
        }
      }
      // The limit option has the opposite effect, limiting handles to a
      // maximum distance from another. Limit must be > 0, as otherwise
      // handles would be unmovable.
      if (scope_Handles.length > 1 && options.limit) {
        if (lookBackward && handleNumber > 0) {
          distance = scope_Spectrum.getAbsoluteDistance(reference[handleNumber - 1], options.limit, false);
          to = Math.min(to, distance);
        }
        if (lookForward && handleNumber < scope_Handles.length - 1) {
          distance = scope_Spectrum.getAbsoluteDistance(reference[handleNumber + 1], options.limit, true);
          to = Math.max(to, distance);
        }
      }
      // The padding option keeps the handles a certain distance from the
      // edges of the slider. Padding must be > 0.
      if (options.padding) {
        if (handleNumber === 0) {
          distance = scope_Spectrum.getAbsoluteDistance(0, options.padding[0], false);
          to = Math.max(to, distance);
        }
        if (handleNumber === scope_Handles.length - 1) {
          distance = scope_Spectrum.getAbsoluteDistance(100, options.padding[1], true);
          to = Math.min(to, distance);
        }
      }
      if (!smoothSteps) {
        to = scope_Spectrum.getStep(to);
      }
      // Limit percentage to the 0 - 100 range
      to = limit(to);
      // Return false if handle can't move
      if (to === reference[handleNumber] && !getValue) {
        return false;
      }
      return to;
    }
    // Uses slider orientation to create CSS rules. a = base value;
    function inRuleOrder(v, a) {
      var o = options.ort;
      return (o ? a : v) + ", " + (o ? v : a);
    }
    // Moves handle(s) by a percentage
    // (bool, % to move, [% where handle started, ...], [index in scope_Handles, ...])
    function moveHandles(upward, proposal, locations, handleNumbers, connect) {
      var proposals = locations.slice();
      // Store first handle now, so we still have it in case handleNumbers is reversed
      var firstHandle = handleNumbers[0];
      var smoothSteps = options.events.smoothSteps;
      var b = [!upward, upward];
      var f = [upward, !upward];
      // Copy handleNumbers so we don't change the dataset
      handleNumbers = handleNumbers.slice();
      // Check to see which handle is 'leading'.
      // If that one can't move the second can't either.
      if (upward) {
        handleNumbers.reverse();
      }
      // Step 1: get the maximum percentage that any of the handles can move
      if (handleNumbers.length > 1) {
        handleNumbers.forEach(function (handleNumber, o) {
          var to = checkHandlePosition(proposals, handleNumber, proposals[handleNumber] + proposal, b[o], f[o], false, smoothSteps);
          // Stop if one of the handles can't move.
          if (to === false) {
            proposal = 0;
          } else {
            proposal = to - proposals[handleNumber];
            proposals[handleNumber] = to;
          }
        });
      }
      // If using one handle, check backward AND forward
      else {
        b = f = [true];
      }
      var state = false;
      // Step 2: Try to set the handles with the found percentage
      handleNumbers.forEach(function (handleNumber, o) {
        state = setHandle(handleNumber, locations[handleNumber] + proposal, b[o], f[o], false, smoothSteps) || state;
      });
      // Step 3: If a handle moved, fire events
      if (state) {
        handleNumbers.forEach(function (handleNumber) {
          fireEvent("update", handleNumber);
          fireEvent("slide", handleNumber);
        });
        // If target is a connect, then fire drag event
        if (connect != undefined) {
          fireEvent("drag", firstHandle);
        }
      }
    }
    // Takes a base value and an offset. This offset is used for the connect bar size.
    // In the initial design for this feature, the origin element was 1% wide.
    // Unfortunately, a rounding bug in Chrome makes it impossible to implement this feature
    // in this manner: https://bugs.chromium.org/p/chromium/issues/detail?id=798223
    function transformDirection(a, b) {
      return options.dir ? 100 - a - b : a;
    }
    // Updates scope_Locations and scope_Values, updates visual state
    function updateHandlePosition(handleNumber, to) {
      // Update locations.
      scope_Locations[handleNumber] = to;
      // Convert the value to the slider stepping/range.
      scope_Values[handleNumber] = scope_Spectrum.fromStepping(to);
      var translation = transformDirection(to, 0) - scope_DirOffset;
      var translateRule = "translate(" + inRuleOrder(translation + "%", "0") + ")";
      scope_Handles[handleNumber].style[options.transformRule] = translateRule;
      updateConnect(handleNumber);
      updateConnect(handleNumber + 1);
    }
    // Handles before the slider middle are stacked later = higher,
    // Handles after the middle later is lower
    // [[7] [8] .......... | .......... [5] [4]
    function setZindex() {
      scope_HandleNumbers.forEach(function (handleNumber) {
        var dir = scope_Locations[handleNumber] > 50 ? -1 : 1;
        var zIndex = 3 + (scope_Handles.length + dir * handleNumber);
        scope_Handles[handleNumber].style.zIndex = String(zIndex);
      });
    }
    // Test suggested values and apply margin, step.
    // if exactInput is true, don't run checkHandlePosition, then the handle can be placed in between steps (#436)
    function setHandle(handleNumber, to, lookBackward, lookForward, exactInput, smoothSteps) {
      if (!exactInput) {
        to = checkHandlePosition(scope_Locations, handleNumber, to, lookBackward, lookForward, false, smoothSteps);
      }
      if (to === false) {
        return false;
      }
      updateHandlePosition(handleNumber, to);
      return true;
    }
    // Updates style attribute for connect nodes
    function updateConnect(index) {
      // Skip connects set to false
      if (!scope_Connects[index]) {
        return;
      }
      var l = 0;
      var h = 100;
      if (index !== 0) {
        l = scope_Locations[index - 1];
      }
      if (index !== scope_Connects.length - 1) {
        h = scope_Locations[index];
      }
      // We use two rules:
      // 'translate' to change the left/top offset;
      // 'scale' to change the width of the element;
      // As the element has a width of 100%, a translation of 100% is equal to 100% of the parent (.directorist-custom-range-slider-base)
      var connectWidth = h - l;
      var translateRule = options.dir ? "translate(" + inRuleOrder(-l + "%", "0") + ")" // RTL
      : "translate(" + inRuleOrder(l + "%", "0") + ")"; // LTR
      var scaleRule = "scale(" + inRuleOrder(connectWidth / 100, "1") + ")";
      scope_Connects[index].style[options.transformRule] = translateRule + " " + scaleRule;
    }
    // Parses value passed to .set method. Returns current value if not parse-able.
    function resolveToValue(to, handleNumber) {
      // Setting with null indicates an 'ignore'.
      // Inputting 'false' is invalid.
      if (to === null || to === false || to === undefined) {
        return scope_Locations[handleNumber];
      }
      // If a formatted number was passed, attempt to decode it.
      if (typeof to === "number") {
        to = String(to);
      }
      to = options.format.from(to);
      if (to !== false) {
        to = scope_Spectrum.toStepping(to);
      }
      // If parsing the number failed, use the current value.
      if (to === false || isNaN(to)) {
        return scope_Locations[handleNumber];
      }
      return to;
    }
    // Set the slider value.
    function valueSet(input, fireSetEvent, exactInput) {
      var values = asArray(input);
      var isInit = scope_Locations[0] === undefined;
      // Event fires by default
      fireSetEvent = fireSetEvent === undefined ? true : fireSetEvent;
      // Animation is optional.
      // Make sure the initial values were set before using animated placement.
      if (options.animate && !isInit) {
        addClassFor(scope_Target, options.cssClasses.tap, options.animationDuration);
      }
      // First pass, without lookAhead but with lookBackward. Values are set from left to right.
      scope_HandleNumbers.forEach(function (handleNumber) {
        setHandle(handleNumber, resolveToValue(values[handleNumber], handleNumber), true, false, exactInput);
      });
      var i = scope_HandleNumbers.length === 1 ? 0 : 1;
      // Spread handles evenly across the slider if the range has no size (min=max)
      if (isInit && scope_Spectrum.hasNoSize()) {
        exactInput = true;
        scope_Locations[0] = 0;
        if (scope_HandleNumbers.length > 1) {
          var space_1 = 100 / (scope_HandleNumbers.length - 1);
          scope_HandleNumbers.forEach(function (handleNumber) {
            scope_Locations[handleNumber] = handleNumber * space_1;
          });
        }
      }
      // Secondary passes. Now that all base values are set, apply constraints.
      // Iterate all handles to ensure constraints are applied for the entire slider (Issue #1009)
      for (; i < scope_HandleNumbers.length; ++i) {
        scope_HandleNumbers.forEach(function (handleNumber) {
          setHandle(handleNumber, scope_Locations[handleNumber], true, true, exactInput);
        });
      }
      setZindex();
      scope_HandleNumbers.forEach(function (handleNumber) {
        fireEvent("update", handleNumber);
        // Fire the event only for handles that received a new value, as per #579
        if (values[handleNumber] !== null && fireSetEvent) {
          fireEvent("set", handleNumber);
        }
      });
    }
    // Reset slider to initial values
    function valueReset(fireSetEvent) {
      valueSet(options.start, fireSetEvent);
    }
    // Set value for a single handle
    function valueSetHandle(handleNumber, value, fireSetEvent, exactInput) {
      // Ensure numeric input
      handleNumber = Number(handleNumber);
      if (!(handleNumber >= 0 && handleNumber < scope_HandleNumbers.length)) {
        throw new Error("directoristCustomRangeSlider: invalid handle number, got: " + handleNumber);
      }
      // Look both backward and forward, since we don't want this handle to "push" other handles (#960);
      // The exactInput argument can be used to ignore slider stepping (#436)
      setHandle(handleNumber, resolveToValue(value, handleNumber), true, true, exactInput);
      fireEvent("update", handleNumber);
      if (fireSetEvent) {
        fireEvent("set", handleNumber);
      }
    }
    // Get the slider value.
    function valueGet(unencoded) {
      if (unencoded === void 0) {
        unencoded = false;
      }
      if (unencoded) {
        // return a copy of the raw values
        return scope_Values.length === 1 ? scope_Values[0] : scope_Values.slice(0);
      }
      var values = scope_Values.map(options.format.to);
      // If only one handle is used, return a single value.
      if (values.length === 1) {
        return values[0];
      }
      return values;
    }
    // Removes classes from the root and empties it.
    function destroy() {
      // remove protected internal listeners
      removeEvent(INTERNAL_EVENT_NS.aria);
      removeEvent(INTERNAL_EVENT_NS.tooltips);
      Object.keys(options.cssClasses).forEach(function (key) {
        removeClass(scope_Target, options.cssClasses[key]);
      });
      while (scope_Target.firstChild) {
        scope_Target.removeChild(scope_Target.firstChild);
      }
      delete scope_Target.directoristCustomRangeSlider;
    }
    function getNextStepsForHandle(handleNumber) {
      var location = scope_Locations[handleNumber];
      var nearbySteps = scope_Spectrum.getNearbySteps(location);
      var value = scope_Values[handleNumber];
      var increment = nearbySteps.thisStep.step;
      var decrement = null;
      // If snapped, directly use defined step value
      if (options.snap) {
        return [value - nearbySteps.stepBefore.startValue || null, nearbySteps.stepAfter.startValue - value || null];
      }
      // If the next value in this step moves into the next step,
      // the increment is the start of the next step - the current value
      if (increment !== false) {
        if (value + increment > nearbySteps.stepAfter.startValue) {
          increment = nearbySteps.stepAfter.startValue - value;
        }
      }
      // If the value is beyond the starting point
      if (value > nearbySteps.thisStep.startValue) {
        decrement = nearbySteps.thisStep.step;
      } else if (nearbySteps.stepBefore.step === false) {
        decrement = false;
      }
      // If a handle is at the start of a step, it always steps back into the previous step first
      else {
        decrement = value - nearbySteps.stepBefore.highestStep;
      }
      // Now, if at the slider edges, there is no in/decrement
      if (location === 100) {
        increment = null;
      } else if (location === 0) {
        decrement = null;
      }
      // As per #391, the comparison for the decrement step can have some rounding issues.
      var stepDecimals = scope_Spectrum.countStepDecimals();
      // Round per #391
      if (increment !== null && increment !== false) {
        increment = Number(increment.toFixed(stepDecimals));
      }
      if (decrement !== null && decrement !== false) {
        decrement = Number(decrement.toFixed(stepDecimals));
      }
      return [decrement, increment];
    }
    // Get the current step size for the slider.
    function getNextSteps() {
      return scope_HandleNumbers.map(getNextStepsForHandle);
    }
    // Updatable: margin, limit, padding, step, range, animate, snap
    function updateOptions(optionsToUpdate, fireSetEvent) {
      // Spectrum is created using the range, snap, direction and step options.
      // 'snap' and 'step' can be updated.
      // If 'snap' and 'step' are not passed, they should remain unchanged.
      var v = valueGet();
      var updateAble = ["margin", "limit", "padding", "range", "animate", "snap", "step", "format", "pips", "tooltips"];
      // Only change options that we're actually passed to update.
      updateAble.forEach(function (name) {
        // Check for undefined. null removes the value.
        if (optionsToUpdate[name] !== undefined) {
          originalOptions[name] = optionsToUpdate[name];
        }
      });
      var newOptions = customRangeOptions(originalOptions);
      // Load new options into the slider state
      updateAble.forEach(function (name) {
        if (optionsToUpdate[name] !== undefined) {
          options[name] = newOptions[name];
        }
      });
      scope_Spectrum = newOptions.spectrum;
      // Limit, margin and padding depend on the spectrum but are stored outside of it. (#677)
      options.margin = newOptions.margin;
      options.limit = newOptions.limit;
      options.padding = newOptions.padding;
      // Update pips, removes existing.
      if (options.pips) {
        pips(options.pips);
      } else {
        removePips();
      }
      // Update tooltips, removes existing.
      if (options.tooltips) {
        tooltips();
      } else {
        removeTooltips();
      }
      // Invalidate the current positioning so valueSet forces an update.
      scope_Locations = [];
      valueSet(isSet(optionsToUpdate.start) ? optionsToUpdate.start : v, fireSetEvent);
    }
    // Initialization steps
    function setupSlider() {
      // Create the base element, initialize HTML and set classes.
      // Add handles and connect elements.
      scope_Base = addSlider(scope_Target);
      addElements(options.connect, scope_Base);
      // Attach user events.
      bindSliderEvents(options.events);
      // Use the public value method to set the start values.
      valueSet(options.start);
      if (options.pips) {
        pips(options.pips);
      }
      if (options.tooltips) {
        tooltips();
      }
      aria();
    }
    setupSlider();
    var scope_Self = {
      destroy: destroy,
      steps: getNextSteps,
      on: bindEvent,
      off: removeEvent,
      get: valueGet,
      set: valueSet,
      setHandle: valueSetHandle,
      reset: valueReset,
      disable: disable,
      enable: enable,
      // Exposed for unit testing, don't use this in your application.
      __moveHandles: function __moveHandles(upward, proposal, handleNumbers) {
        moveHandles(upward, proposal, scope_Locations, handleNumbers);
      },
      options: originalOptions,
      updateOptions: updateOptions,
      target: scope_Target,
      removePips: removePips,
      removeTooltips: removeTooltips,
      getPositions: function getPositions() {
        return scope_Locations.slice();
      },
      getTooltips: function getTooltips() {
        return scope_Tooltips;
      },
      getOrigins: function getOrigins() {
        return scope_Handles;
      },
      pips: pips // Issue #594
    };

    return scope_Self;
  }
  // Run the standard initializer
  function initialize(target, originalOptions) {
    if (!target || !target.nodeName) {
      throw new Error("directoristCustomRangeSlider: create requires a single element, got: ".concat(target));
    }
    if (target.directoristCustomRangeSlider) {
      throw new Error('directoristCustomRangeSlider: Slider was already initialized.');
    }
    var options = customRangeOptions(originalOptions);
    var api = scope(target, options, originalOptions);
    target.directoristCustomRangeSlider = api;
    return api;
  }
  var directoristCustomRangeSlider = {
    __spectrum: Spectrum,
    cssClasses: cssClasses,
    create: initialize
  };
  exports.create = initialize;
  exports.cssClasses = cssClasses;
  exports.default = directoristCustomRangeSlider;
  Object.defineProperty(exports, '__esModule', {
    value: true
  });
});

/***/ }),

/***/ "./assets/src/js/public/search-form.js":
/*!*********************************************!*\
  !*** ./assets/src/js/public/search-form.js ***!
  \*********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _global_components_debounce__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../global/components/debounce */ "./assets/src/js/global/components/debounce.js");
/* harmony import */ var _global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./../global/components/select2-custom-control */ "./assets/src/js/global/components/select2-custom-control.js");
/* harmony import */ var _global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _global_components_setup_select2__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./../global/components/setup-select2 */ "./assets/src/js/global/components/setup-select2.js");
/* harmony import */ var _components_category_custom_fields__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/category-custom-fields */ "./assets/src/js/public/components/category-custom-fields.js");
/* harmony import */ var _components_colorPicker__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./components/colorPicker */ "./assets/src/js/public/components/colorPicker.js");
/* harmony import */ var _components_colorPicker__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_components_colorPicker__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _components_directoristDropdown__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./components/directoristDropdown */ "./assets/src/js/public/components/directoristDropdown.js");
/* harmony import */ var _components_directoristDropdown__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_components_directoristDropdown__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _components_directoristSelect__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./components/directoristSelect */ "./assets/src/js/public/components/directoristSelect.js");
/* harmony import */ var _components_directoristSelect__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_components_directoristSelect__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _range_slider__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./range-slider */ "./assets/src/js/public/range-slider.js");
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }








(function ($) {
  window.addEventListener('load', function () {
    //Remove Preload after Window Load
    $('body').removeClass("directorist-preload");
    $('.button.wp-color-result').attr('style', ' ');

    /* ----------------
    Search Form
    ------------------ */

    // Default Tags Slice
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
    defaultTags();
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

    // Check Empty Search Fields on Search Modal
    function initSearchFields() {
      var searchFields = document.querySelectorAll('.directorist-search-field__input:not(.directorist-search-basic-dropdown)');
      searchFields.forEach(function (searchField) {
        var inputFieldValue = searchField.value;
        if (searchField.classList.contains('directorist-select')) {
          inputFieldValue = searchField.querySelector('select').dataset.selectedId;
        }
        if (inputFieldValue !== '') {
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

    // Search Form Reset Button Initialize
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

    // Searchform Reset
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
      handleRadiusVisibility();
      initForm(searchForm);
    }

    // Searchform Reset Trigger
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

    // Search Modal Minimizer
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

    // Search Modal Open Trigger
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

    // Search Modal Close Trigger
    $('body').on('click', '.directorist-search-modal__contents__btn--close, .directorist-search-modal__overlay', function (e) {
      e.preventDefault();
      var searchModalElement = this.closest('.directorist-search-modal');
      searchModalClose(searchModalElement);
    });

    // Search Modal Minimizer Trigger
    $('body').on('click', '.directorist-search-modal__minimizer', function (e) {
      e.preventDefault();
      var searchModalElement = this.closest('.directorist-search-modal');
      searchModalMinimize(searchModalElement);
    });

    // Search Field Input Value Check
    function inputValueCheck(e, searchField) {
      searchField = searchField[0];
      var inputBox = searchField.querySelector('.directorist-search-field__input:not(.directorist-search-basic-dropdown)');
      var inputFieldValue = inputBox && inputBox.value;
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
        var parentWithClass = e.target.closest('.directorist-search-field__input:not(.directorist-search-basic-dropdown)');
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

    // Search Form Input Field Check Trigger
    $('body').on('input keyup change', '.directorist-search-field__input:not(.directorist-search-basic-dropdown)', function (e) {
      var searchField = $(this).closest('.directorist-search-field');
      inputValueCheck(e, searchField);
    });
    $('body').on('focus blur', '.directorist-search-field__input:not(.directorist-search-basic-dropdown)', function (e) {
      var searchField = $(this).closest('.directorist-search-field');
      inputEventCheck(e, searchField);
    });

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
        $(selectboxField).trigger('change');
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
      if (basicDropdown) {
        basicDropdown.forEach(function (dropdown) {
          $(dropdown).slideUp();
          $(dropdown).siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-count').text('');
          $(dropdown).siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-prefix').text('');
          $(dropdown).siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-item').text('');
        });
      }
      if (this.parentElement.classList.contains('input-has-value') || this.parentElement.classList.contains('input-is-focused')) {
        this.parentElement.classList.remove('input-has-value', 'input-is-focused');
      }
      handleRadiusVisibility();

      // Reset Button Disable
      var searchform = this.closest('form');
      var inputValue = $(this).parent('.directorist-search-field').find('.directorist-search-field__input:not(.directorist-search-basic-dropdown)').val();
      var selectValue = $(this).parent('.directorist-search-field').find('.directorist-search-field__input select:not(.directorist-search-basic-dropdown)').val();
      if (inputValue && inputValue !== 0 && inputValue !== undefined || selectValue && selectValue.selectedIndex === 0 || selectValue && selectValue.selectedIndex !== undefined) {
        enableResetButton(searchform);
      } else {
        setTimeout(function () {
          initForm(searchform);
        }, 100);
      }
    });

    // Search Form Input Field Back Button
    $('body').on('click', '.directorist-search-field__label:not(.directorist-search-basic-dropdown-label)', function (e) {
      var windowScreen = window.innerWidth;
      var parentField = this.closest('.directorist-search-field');
      if (windowScreen <= 575) {
        if (parentField.classList.contains('input-is-focused')) {
          parentField.classList.remove('input-is-focused');
        }
      }
    });

    // Listing Type Change
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
            initSearchFields();
            Object(_components_category_custom_fields__WEBPACK_IMPORTED_MODULE_3__["default"])($);
          }
          var parentAfterAjax = $(this).closest('.directorist-search-contents');
          parentAfterAjax.find('.directorist-search-form-box').removeClass('atbdp-form-fade');
          if (parentAfterAjax.find('.directorist-search-form-box').find('.directorist-search-field-radius_search').length) {
            handleRadiusVisibility();
            directorist_custom_range_slider();
          }
        },
        error: function error(_error) {
          // console.log(error);
        }
      });
    });
    Object(_components_category_custom_fields__WEBPACK_IMPORTED_MODULE_3__["default"])($);

    // Back Button to go back to the previous page
    $('body').on('click', '.directorist-btn__back', function (e) {
      e.preventDefault();
      window.history.back();
    });

    // Radius Search Field Hide on Empty Location Field
    function handleRadiusVisibility() {
      $('.directorist-range-slider-wrap').closest('.directorist-search-field').addClass('directorist-search-field-radius_search');
      $('.directorist-location-js').each(function (index, locationDOM) {
        if ($(locationDOM).val() === '') {
          $(locationDOM).closest('.directorist-contents-wrap').find('.directorist-search-field-radius_search, .directorist-radius-search').css({
            display: "none"
          });
        } else {
          $(locationDOM).closest('.directorist-contents-wrap').find('.directorist-search-field-radius_search, .directorist-radius-search').css({
            display: "block"
          });
        }
      });
    }

    // handleRadiusVisibility Trigger
    $('body').on('keyup keydown input change focus', '.directorist-location-js, .zip-radius-search', function (e) {
      handleRadiusVisibility();
    });

    // rangeSlider, defaultTags Trigger on directory type | page change
    $('body').on('click', '.directorist-type-nav__link, .directorist-pagination .page-numbers, .directorist-viewas .directorist-viewas__item', function (e) {
      setTimeout(function () {
        handleRadiusVisibility();
        directorist_custom_range_slider();
        defaultTags();
      }, 600);
    });

    // active class add on view as button
    $('body').on('click', '.directorist-viewas .directorist-viewas__item', function (e) {
      $(this).addClass('active').siblings().removeClass('active');
    });

    // Hide Country Result Click on Outside of Zipcode Field
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
    init_map_api_field();
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
          $(field.input_elm).on('keyup', Object(_global_components_debounce__WEBPACK_IMPORTED_MODULE_0__["default"])(function (event) {
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
                  console.log({
                    error: _error3
                  });
                  locationAddressField.removeClass('atbdp-form-fade');
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

    // Custom Range Slider
    function directorist_custom_range_slider() {
      var sliders = document.querySelectorAll('.directorist-custom-range-slider');
      sliders.forEach(function (sliderItem) {
        var _directoristCustomRan, _slider$directoristCu;
        var slider = sliderItem.querySelector('.directorist-custom-range-slider__slide');

        // Check if the slider is already initialized
        if (!slider || slider.directoristCustomRangeSlider) return;
        var sliderStep = parseInt(slider.getAttribute('step')) || 1;
        var sliderDefaultValue = parseInt(slider.getAttribute('value'));
        var minInput = sliderItem.querySelector('.directorist-custom-range-slider__value__min');
        var maxInput = sliderItem.querySelector('.directorist-custom-range-slider__value__max');
        var sliderRange = sliderItem.querySelector('.directorist-custom-range-slider__range');
        var sliderRangeShow = sliderItem.querySelector('.directorist-custom-range-slider__range__show');
        var sliderRangeValue = sliderItem.querySelector('.directorist-custom-range-slider__wrap .directorist-custom-range-slider__range');
        var isRTL = document.dir === 'rtl';

        // init rangeInitiLoad on initial Load
        var rangeInitLoad = true;
        (_directoristCustomRan = directoristCustomRangeSlider) === null || _directoristCustomRan === void 0 || _directoristCustomRan.create(slider, {
          start: [0, sliderDefaultValue ? sliderDefaultValue : 100],
          connect: true,
          direction: isRTL ? 'rtl' : 'ltr',
          step: sliderStep ? sliderStep : 1,
          range: {
            'min': Number(minInput.value ? minInput.value : 0),
            'max': Number(maxInput.value ? maxInput.value : 100)
          }
        });
        (_slider$directoristCu = slider.directoristCustomRangeSlider) === null || _slider$directoristCu === void 0 || _slider$directoristCu.on('update', function (values, handle) {
          var value = values[handle];
          handle === 0 ? minInput.value = Math.round(value) : maxInput.value = Math.round(value);
          var rangeValue = minInput.value + '-' + maxInput.value;
          sliderRange.value = rangeValue;
          sliderRangeShow && (sliderRangeShow.innerHTML = rangeValue);
          if (sliderRangeValue) {
            sliderRangeValue.setAttribute('value', rangeValue);
            if (!rangeInitLoad) {
              $(sliderRangeValue).trigger('change'); // Trigger change event
            }
          }
        });

        // false rangeInitLoad after call
        rangeInitLoad = false;
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
    $('body').on("keyup", '.zip-radius-search', directorist_debounce(function () {
      var zipcode = $(this).val();
      var zipcode_search = $(this).closest('.directorist-zipcode-search');
      var country_suggest = zipcode_search.find('.directorist-country');
      var zipcode_search = $(this).closest('.directorist-zipcode-search');
      if (zipcode) {
        zipcode_search.addClass('dir_loading');
      }
      if (directorist.i18n_text.select_listing_map === 'google') {
        var url = directorist.ajax_url;
      } else {
        url = "https://nominatim.openstreetmap.org/?postalcode=".concat(zipcode, "&format=json&addressdetails=1");
        $('.directorist-country').css({
          display: 'block'
        });
        if (zipcode === '') {
          $('.directorist-country').css({
            display: 'none'
          });
        }
      }
      var res = '';
      var google_data = {
        'nonce': directorist.directorist_nonce,
        'action': 'directorist_zipcode_search',
        'zipcode': zipcode
      };
      $.ajax({
        url: url,
        method: 'POST',
        data: directorist.i18n_text.select_listing_map === 'google' ? google_data : "",
        success: function success(data) {
          if (data.data && data.data.error_message) {
            zipcode_search.find('.error_message').remove();
            zipcode_search.find('.zip-cityLat').val('');
            zipcode_search.find('.zip-cityLng').val('');
            zipcode_search.append(data.data.error_message);
          }
          zipcode_search.removeClass('dir_loading');
          if (directorist.i18n_text.select_listing_map === 'google' && typeof data.lat !== 'undefined' && typeof data.lng !== 'undefined') {
            zipcode_search.find('.error_message').remove();
            zipcode_search.find('.zip-cityLat').val(data.lat);
            zipcode_search.find('.zip-cityLng').val(data.lng);
          } else {
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
        }
      });
    }, 250));

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

    // Custom Range Slider Value Check on Change
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

    // DOM Mutation Observer on Custom Range Slider
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
                if (targetNode.classList.contains('directorist-custom-range-slider-handle-upper')) {
                  sliderValueCheck(targetNode, parseInt(targetNode.ariaValueNow));
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

/***/ "./node_modules/webpack/buildin/amd-options.js":
/*!****************************************!*\
  !*** (webpack)/buildin/amd-options.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/* WEBPACK VAR INJECTION */(function(__webpack_amd_options__) {/* globals __webpack_amd_options__ */
module.exports = __webpack_amd_options__;

/* WEBPACK VAR INJECTION */}.call(this, {}))

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