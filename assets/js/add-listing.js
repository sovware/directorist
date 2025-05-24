/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/src/js/global/components/debounce.js":
/*!*****************************************************!*\
  !*** ./assets/src/js/global/components/debounce.js ***!
  \*****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ debounce; }
/* harmony export */ });
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
/***/ (function() {

function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t.return || t.return(); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
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
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./../../lib/helper */ "./assets/src/js/lib/helper.js");
/* harmony import */ var _select2_custom_control__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./select2-custom-control */ "./assets/src/js/global/components/select2-custom-control.js");
/* harmony import */ var _select2_custom_control__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_select2_custom_control__WEBPACK_IMPORTED_MODULE_2__);

function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }


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
    return (0,_lib_helper__WEBPACK_IMPORTED_MODULE_1__.convertToSelect2)(selector);
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
    },
    templateResult: function templateResult(data) {
      if (!data.id) {
        return data.text;
      }

      // Fetch the data-icon attribute
      var iconURI = $(data.element).attr('data-icon');

      // Get the original text
      var originalText = data.text;

      // Match and count leading spaces
      var leadingSpaces = originalText.match(/^\s+/);
      var spaceCount = leadingSpaces ? leadingSpaces[0].length : 0;

      // Trim leading spaces from the original text
      originalText = originalText.trim();

      // Construct the icon element
      var iconElm = iconURI ? "<i class=\"directorist-icon-mask\" aria-hidden=\"true\" style=\"--directorist-icon: url('".concat(iconURI, "')\"></i>") : '';

      // Prepare the combined text (icon + text)
      var combinedText = iconElm + originalText;

      // Create the state container
      var $state = $('<div class="directorist-select2-contents"></div>');

      // Determine the level based on space count
      var level = Math.floor(spaceCount / 8) + 1; // 8 spaces = level 2, 16 spaces = level 3, etc.
      if (level > 1) {
        $state.addClass('item-level-' + level); // Add class for the level (e.g., level-1, level-2, etc.)
      }
      $state.html(combinedText); // Set the combined content (icon + text)

      return $state;
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

/***/ "./assets/src/js/helper.js":
/*!*********************************!*\
  !*** ./assets/src/js/helper.js ***!
  \*********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   directoristRequestHeaders: function() { return /* binding */ directoristRequestHeaders; },
/* harmony export */   findObjectItem: function() { return /* binding */ findObjectItem; },
/* harmony export */   isObject: function() { return /* binding */ isObject; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");

function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t.return || t.return(); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
var isObject = function isObject(value) {
  return value && (0,_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__["default"])(value) === 'object' && !Array.isArray(value);
};
function findObjectItem(path, data, defaultValue) {
  if (typeof path !== 'string') {
    return defaultValue;
  }
  if (!isObject(data)) {
    return defaultValue;
  }
  var pathItems = path.split('.');
  var targetItem = data;
  var _iterator = _createForOfIteratorHelper(pathItems),
    _step;
  try {
    for (_iterator.s(); !(_step = _iterator.n()).done;) {
      var key = _step.value;
      if (!isObject(targetItem)) {
        return defaultValue;
      }
      if (!targetItem.hasOwnProperty(key)) {
        return defaultValue;
      }
      targetItem = targetItem[key];
    }
  } catch (err) {
    _iterator.e(err);
  } finally {
    _iterator.f();
  }
  return targetItem;
}
function directoristRequestHeaders() {
  if (window.directorist && window.directorist.request_headers && (0,_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__["default"])(window.directorist.request_headers) === 'object' && !Array.isArray(window.directorist.request_headers)) {
    var headers = {};
    for (var key in window.directorist.request_headers) {
      headers["Directorist-".concat(key)] = window.directorist.request_headers[key];
    }
    return headers;
  }
  return {};
}

/***/ }),

/***/ "./assets/src/js/lib/helper.js":
/*!*************************************!*\
  !*** ./assets/src/js/lib/helper.js ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   convertToSelect2: function() { return /* binding */ convertToSelect2; },
/* harmony export */   get_dom_data: function() { return /* binding */ get_dom_data; }
/* harmony export */ });
var $ = jQuery;
function get_dom_data(selector, parent) {
  selector = '.directorist-dom-data-' + selector;
  if (!parent) {
    parent = document;
  }
  var el = parent.querySelector(selector);
  if (!el || !el.dataset.value) {
    return {};
  }
  var IS_SCRIPT_DEBUGGING = directorist && directorist.script_debugging && directorist.script_debugging == '1';
  try {
    var value = atob(el.dataset.value);
    return JSON.parse(value);
  } catch (error) {
    if (IS_SCRIPT_DEBUGGING) {
      console.log(el, error);
    }
    return {};
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

/***/ "./assets/src/js/public/components/colorPicker.js":
/*!********************************************************!*\
  !*** ./assets/src/js/public/components/colorPicker.js ***!
  \********************************************************/
/***/ (function() {

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
/***/ (function() {

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
/***/ (function() {

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

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js ***!
  \*********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
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

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _arrayWithHoles; }
/* harmony export */ });
function _arrayWithHoles(r) {
  if (Array.isArray(r)) return r;
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/defineProperty.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _defineProperty; }
/* harmony export */ });
/* harmony import */ var _toPropertyKey_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./toPropertyKey.js */ "./node_modules/@babel/runtime/helpers/esm/toPropertyKey.js");

function _defineProperty(e, r, t) {
  return (r = (0,_toPropertyKey_js__WEBPACK_IMPORTED_MODULE_0__["default"])(r)) in e ? Object.defineProperty(e, r, {
    value: t,
    enumerable: !0,
    configurable: !0,
    writable: !0
  }) : e[r] = t, e;
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js":
/*!*************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js ***!
  \*************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
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

"use strict";
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

"use strict";
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

/***/ "./node_modules/@babel/runtime/helpers/esm/toPrimitive.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/toPrimitive.js ***!
  \****************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ toPrimitive; }
/* harmony export */ });
/* harmony import */ var _typeof_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./typeof.js */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");

function toPrimitive(t, r) {
  if ("object" != (0,_typeof_js__WEBPACK_IMPORTED_MODULE_0__["default"])(t) || !t) return t;
  var e = t[Symbol.toPrimitive];
  if (void 0 !== e) {
    var i = e.call(t, r || "default");
    if ("object" != (0,_typeof_js__WEBPACK_IMPORTED_MODULE_0__["default"])(i)) return i;
    throw new TypeError("@@toPrimitive must return a primitive value.");
  }
  return ("string" === r ? String : Number)(t);
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/toPropertyKey.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/toPropertyKey.js ***!
  \******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ toPropertyKey; }
/* harmony export */ });
/* harmony import */ var _typeof_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./typeof.js */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");
/* harmony import */ var _toPrimitive_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./toPrimitive.js */ "./node_modules/@babel/runtime/helpers/esm/toPrimitive.js");


function toPropertyKey(t) {
  var i = (0,_toPrimitive_js__WEBPACK_IMPORTED_MODULE_1__["default"])(t, "string");
  return "symbol" == (0,_typeof_js__WEBPACK_IMPORTED_MODULE_0__["default"])(i) ? i : i + "";
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/typeof.js":
/*!***********************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/typeof.js ***!
  \***********************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _typeof; }
/* harmony export */ });
function _typeof(o) {
  "@babel/helpers - typeof";

  return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) {
    return typeof o;
  } : function (o) {
    return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o;
  }, _typeof(o);
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js":
/*!*******************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js ***!
  \*******************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
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
/*!*********************************************!*\
  !*** ./assets/src/js/global/add-listing.js ***!
  \*********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");
/* harmony import */ var _global_components_setup_select2__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../global/components/setup-select2 */ "./assets/src/js/global/components/setup-select2.js");
/* harmony import */ var _helper__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../helper */ "./assets/src/js/helper.js");
/* harmony import */ var _public_components_colorPicker__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../public/components/colorPicker */ "./assets/src/js/public/components/colorPicker.js");
/* harmony import */ var _public_components_colorPicker__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_public_components_colorPicker__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _public_components_directoristDropdown__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../public/components/directoristDropdown */ "./assets/src/js/public/components/directoristDropdown.js");
/* harmony import */ var _public_components_directoristDropdown__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_public_components_directoristDropdown__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _public_components_directoristSelect__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../public/components/directoristSelect */ "./assets/src/js/public/components/directoristSelect.js");
/* harmony import */ var _public_components_directoristSelect__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_public_components_directoristSelect__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _components_debounce__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./components/debounce */ "./assets/src/js/global/components/debounce.js");


function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t.return || t.return(); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
// General Components
// import { cacheCategoryCustomFields, getCategoryCustomFieldsCache } from '../global/components/cache-category-custom-fields';
// import loadCategoryCustomFields from '../global/components/load-category-custom-fields';







/* eslint-disable */
var $ = jQuery;
var localized_data = directorist.add_listing_data;
function getWrapper() {
  return localized_data.is_admin ? '#post' : '#directorist-add-listing-form';
}
function initColorField() {
  var $colorField = $('.directorist-color-field-js', getWrapper());
  if ($colorField.length) {
    $colorField.wpColorPicker();
  }
}
function scrollToEl(selector) {
  document.querySelector(selector).scrollIntoView({
    block: 'start',
    behavior: 'smooth'
  });
}

/**
 * Join Query String
 *
 * @param string url
 * @param string queryString
 * @return string
 */
function joinQueryString(url, queryString) {
  return url.match(/[?]/) ? "".concat(url, "&").concat(queryString) : "".concat(url, "?").concat(queryString);
}
function scrollTo(selector) {
  var _document$querySelect;
  (_document$querySelect = document.querySelector(selector)) === null || _document$querySelect === void 0 || _document$querySelect.scrollIntoView({
    block: 'start',
    behavior: 'smooth'
  });
}

/* Show and hide manual coordinate input field */
$(window).on('load', function () {
  if ($('input#manual_coordinate').length) {
    $('input#manual_coordinate').each(function (index, element) {
      if (!$(element).is(':checked')) {
        $('#hide_if_no_manual_cor').hide();
        $('.directorist-map-coordinates').hide();
      }
    });
  }

  //initialize color picker
  initColorField();
});
$(function () {
  $('body').on("click", "#manual_coordinate", function (e) {
    if ($('input#manual_coordinate').is(':checked')) {
      $('.directorist-map-coordinates').show();
      $('#hide_if_no_manual_cor').show();
    } else {
      $('.directorist-map-coordinates').hide();
      $('#hide_if_no_manual_cor').hide();
    }
  });

  // SOCIAL SECTION
  // Rearrange the IDS and Add new social field
  $('body').on('click', '#addNewSocial', function (e) {
    var _this = this;
    var social_wrap = $('#social_info_sortable_container'); // cache it
    var currentItems = $('.directorist-form-social-fields').length;
    var ID = "id=".concat(currentItems); // eg. 'id=3'
    var iconBindingElement = jQuery('#addNewSocial');

    // arrange names ID in order before adding new elements
    $('.directorist-form-social-fields').each(function (index, element) {
      var e = $(element);
      e.attr('id', "socialID-".concat(index));
      e.find('select').attr('name', "social[".concat(index, "][id]"));
      e.find('.atbdp_social_input').attr('name', "social[".concat(index, "][url]"));
      e.find('.directorist-form-social-fields__remove').attr('data-id', index);
    });

    // now add the new elements. we could do it here without using ajax but it would require more markup here.
    atbdp_do_ajax(iconBindingElement, 'atbdp_social_info_handler', ID, function (data) {
      social_wrap.append(data);
    });
    setTimeout(function () {
      var socialSelect = _this.parentElement.querySelectorAll('.directorist-form-social-fields select');
      socialSelect.forEach(function (item) {
        if (item.value !== '') {
          item.classList.remove('placeholder-item');
        }
        item.addEventListener('change', function () {
          if (this.value !== '' && this.classList.contains('placeholder-item')) {
            this.classList.remove('placeholder-item');
          } else if (this.value === '') {
            this.classList.add('placeholder-item');
          }
        });
      });
    }, 300);
  });
  document.addEventListener('directorist-reload-plupload', function () {
    initColorField();
  });

  // remove the social field and then reset the ids while maintaining position
  $('body').on('click', '.directorist-form-social-fields__remove', function (e) {
    var id = $(this).data('id');
    var elementToRemove = $("div#socialID-".concat(id));
    /* Act on the event */
    swal({
      title: localized_data.i18n_text.confirmation_text,
      text: localized_data.i18n_text.ask_conf_sl_lnk_del_txt,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: localized_data.i18n_text.confirm_delete,
      closeOnConfirm: false
    }, function (isConfirm) {
      if (isConfirm) {
        // user has confirmed, no remove the item and reset the ids
        elementToRemove.slideUp('fast', function () {
          elementToRemove.remove();
          // reorder the index
          $('.directorist-form-social-fields').each(function (index, element) {
            var e = $(element);
            e.attr('id', "socialID-".concat(index));
            e.find('select').attr('name', "social[".concat(index, "][id]"));
            e.find('.atbdp_social_input').attr('name', "social[".concat(index, "][url]"));
            e.find('.directorist-form-social-fields__remove').attr('data-id', index);
          });
        });

        // show success message
        swal({
          title: localized_data.i18n_text.deleted,
          // text: "Item has been deleted.",
          type: 'success',
          timer: 200,
          showConfirmButton: false
        });
      }
    });
  });

  /* This function handles all ajax request */
  function atbdp_do_ajax(ElementToShowLoadingIconAfter, ActionName, arg, CallBackHandler) {
    var data;
    if (ActionName) data = "action=".concat(ActionName);
    if (arg) data = "".concat(arg, "&action=").concat(ActionName);
    if (arg && !ActionName) data = arg;
    // data = data ;

    var n = data.search(localized_data.nonceName);
    if (n < 0) {
      var nonce = typeof directorist !== 'undefined' ? directorist.directorist_nonce : directorist_admin.directorist_nonce;
      data = "".concat(data, "&", 'directorist_nonce', "=").concat(nonce);
    }
    jQuery.ajax({
      type: 'post',
      url: localized_data.ajaxurl,
      data: data,
      beforeSend: function beforeSend() {
        jQuery("<span class='atbdp_ajax_loading'></span>").insertAfter(ElementToShowLoadingIconAfter);
      },
      success: function success(data) {
        jQuery('.atbdp_ajax_loading').remove();
        CallBackHandler(data);
      }
    });
  }

  // Select2 js code
  // if (!localized_data.is_admin) {
  // Location
  // const createLoc = $('#at_biz_dir-location').attr("data-allow_new");
  // let maxLocationLength = $('#at_biz_dir-location').attr("data-max");
  // if (createLoc) {
  //     $("#at_biz_dir-location").select2({
  //         tags: true,
  //         maximumSelectionLength: maxLocationLength,
  //         language: {
  //             maximumSelected: function () {
  //                 return localized_data.i18n_text.max_location_msg;
  //             }
  //         },
  //         tokenSeparators: [","],
  //     });
  // } else {
  //     $("#at_biz_dir-location").select2({
  //         allowClear: true,
  //         tags: false,
  //         maximumSelectionLength: maxLocationLength,
  //         tokenSeparators: [","],
  //     });
  // }

  // // Tags
  // const createTag = $('#at_biz_dir-tags').attr("data-allow_new");
  // let maxTagLength = $('#at_biz_dir-tags').attr("data-max");
  // if (createTag) {
  //     $('#at_biz_dir-tags').select2({
  //         tags: true,
  //         maximumSelectionLength: maxTagLength,
  //         tokenSeparators: [','],
  //     });
  // } else {
  //     $('#at_biz_dir-tags').select2({
  //         allowClear: true,
  //         maximumSelectionLength: maxTagLength,
  //         tokenSeparators: [','],
  //     });
  // }

  // //Category
  // const createCat = $('#at_biz_dir-categories').attr("data-allow_new");
  // let maxCatLength = $('#at_biz_dir-categories').attr("data-max");
  // if (createCat) {
  //     $('#at_biz_dir-categories').select2({
  //         allowClear: true,
  //         tags: true,
  //         maximumSelectionLength: maxCatLength,
  //         tokenSeparators: [','],
  //     });
  // } else {
  //     $('#at_biz_dir-categories').select2({
  //         maximumSelectionLength: maxCatLength,
  //         allowClear: true,
  //     });
  // }
  // }

  /**
   * Price field.
   */
  function getPriceTypeInput(typeId) {
    return $("#".concat($("[for=\"".concat(typeId, "\"]")).data('option')));
  }
  $('.directorist-form-pricing-field__options').on('change', 'input', function () {
    var $otherOptions = $(this).parent().siblings('.directorist-checkbox').find('input');
    $otherOptions.prop('checked', false);
    getPriceTypeInput($otherOptions.attr('id')).hide();
    if (this.checked) {
      getPriceTypeInput(this.id).show();
    } else {
      getPriceTypeInput(this.id).hide();
    }
  });
  if ($('.directorist-form-pricing-field').hasClass('price-type-both')) {
    $('#price_range, #price').hide();
    var $selectedPriceType = $('.directorist-form-pricing-field__options input:checked');
    if ($selectedPriceType.length) {
      getPriceTypeInput($selectedPriceType.attr('id')).show();
    } else {
      $($('.directorist-form-pricing-field__options input').get(0)).prop('checked', true).trigger('change');
    }
  }
  var has_tagline = $('#has_tagline').val();
  var has_excerpt = $('#has_excerpt').val();
  if (has_excerpt && has_tagline) {
    $('.atbd_tagline_moto_field').fadeIn();
  } else {
    $('.atbd_tagline_moto_field').fadeOut();
  }
  $('#atbd_optional_field_check').on('change', function () {
    $(this).is(':checked') ? $('.atbd_tagline_moto_field').fadeIn() : $('.atbd_tagline_moto_field').fadeOut();
  });

  // it shows the hidden term and conditions
  $('#listing_t_c').on('click', function (e) {
    e.preventDefault();
    $('#tc_container').toggleClass('active');
  });

  // Load custom fields of the selected category in the custom post type "atbdp_listings"
  var qs = function (a) {
    if (a == '') return {};
    var b = {};
    for (var i = 0; i < a.length; ++i) {
      var p = a[i].split('=', 2);
      if (p.length == 1) b[p[0]] = '';else b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, ' '));
    }
    return b;
  }(window.location.search.substr(1).split('&'));
  function renderCategoryCustomFields() {
    if ((0,_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_1__["default"])(localized_data === null || localized_data === void 0 ? void 0 : localized_data.category_custom_field_relations) !== 'object') {
      return;
    }
    var categoryIds = [];
    var directoryId = 0;
    var fieldsMap = localized_data.category_custom_field_relations;
    var categoryInputSelector = directorist.is_admin ? '#at_biz_dir-categorychecklist input:checked' : '#at_biz_dir-categories option:selected';
    directoryId = $('select[name="directory_type"]', getWrapper()).val();
    if (!directoryId) {
      directoryId = $('input[name="directory_type"]', getWrapper()).val();
    }
    if (typeof fieldsMap[directoryId] === 'undefined' || fieldsMap[directoryId].length === 0) {
      return;
    }
    var $selectedCategories = $(categoryInputSelector);
    if ($selectedCategories.length) {
      categoryIds = $selectedCategories.toArray().map(function (el) {
        return Number(el.value);
      });
    }
    var $watchableSections = {
      hide: new Set(),
      show: new Set()
    };
    categoryIds = new Set(categoryIds);
    for (var _i = 0, _Object$entries = Object.entries(fieldsMap[directoryId]); _i < _Object$entries.length; _i++) {
      var _Object$entries$_i = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_Object$entries[_i], 2),
        fieldKey = _Object$entries$_i[0],
        categoryId = _Object$entries$_i[1];
      var $input = $(fieldKey.includes('checkbox') ? "[name=\"".concat(fieldKey, "[]\"]") : "[name=\"".concat(fieldKey, "\"]"));
      var $wrapper = $input.closest('.directorist-form-group');
      if (categoryIds.has(categoryId)) {
        $input.removeAttr('disabled');
        $wrapper.show();
        $watchableSections.show.add($wrapper.closest('.directorist-form-section').get(0));
      } else {
        $input.attr('disabled', true);
        $wrapper.hide();
        $watchableSections.hide.add($wrapper.closest('.directorist-form-section').get(0));
      }
    }
    if ($watchableSections.show.size) {
      var _iterator = _createForOfIteratorHelper($watchableSections.show),
        _step;
      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var visible = _step.value;
          var $visible = $(visible);
          $visible.removeAttr('style');
          $visible.find('.directorist-content-module__title').show();
          $visible.find('.directorist-content-module__contents').show();
          $("a[href=\"#".concat($visible.attr('id'), "\"]")).show();
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }
    }
    if ($watchableSections.hide.size) {
      var _iterator2 = _createForOfIteratorHelper($watchableSections.hide),
        _step2;
      try {
        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          var hidable = _step2.value;
          var $hidable = $(hidable);
          if ($hidable.find('.directorist-form-group:visible').length) {
            $hidable.removeAttr('style');
            $hidable.find('.directorist-content-module__title').show();
            $hidable.find('.directorist-content-module__contents').show();
            $("a[href=\"#".concat($hidable.attr('id'), "\"]")).show();
          } else {
            $hidable.css({
              display: 'none',
              height: 0,
              padding: 0,
              margin: 0,
              border: 0,
              overflow: 'hidden'
            });
            $hidable.find('.directorist-content-module__title').hide();
            $hidable.find('.directorist-content-module__contents').hide();
            $("a[href=\"#".concat($hidable.attr('id'), "\"]")).hide();
          }
        }
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }
    }
  }
  window.addEventListener('load', function () {
    renderCategoryCustomFields();
    // cacheCategoryCustomFields();
  });
  window.addEventListener('directorist-type-change', function () {
    renderCategoryCustomFields();
    // cacheCategoryCustomFields();
  });

  // Render category based fields on category change (frontend)
  $('#at_biz_dir-categories').on('change', function () {
    renderCategoryCustomFields();
    // cacheCategoryCustomFields();
  });

  // Render category based fields on category change (backend)
  $('#at_biz_dir-categorychecklist').on('change', function () {
    renderCategoryCustomFields();
    // cacheCategoryCustomFields();
  });

  // Make sure to place the following event trigger after the event bindings.
  if (!directorist.is_admin) {
    if (directorist.lazy_load_taxonomy_fields) {
      $('#at_biz_dir-categories').on('select2:select', function () {
        $('#at_biz_dir-categories').trigger('change');
      });
    } else {
      $('#at_biz_dir-categories').trigger('change');
    }
  }
  function atbdp_element_value(element) {
    var field = $(element);
    if (field.length) {
      return field.val();
    }
  }
  var mediaUploaders = [];
  if (localized_data.media_uploader) {
    var _iterator3 = _createForOfIteratorHelper(localized_data.media_uploader),
      _step3;
    try {
      for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
        var uploader = _step3.value;
        if ($('.' + uploader.element_id).length) {
          var EzUploader = new EzMediaUploader({
            containerClass: uploader.element_id
          });
          mediaUploaders.push({
            media_uploader: EzUploader,
            uploaders_data: uploader
          });
          EzUploader.init();
          // mediaUploaders[i].media_uploader.init();
        }
      }
    } catch (err) {
      _iterator3.e(err);
    } finally {
      _iterator3.f();
    }
  }
  var on_processing = false;
  var has_media = true;
  var quickLoginModalSuccessCallback = null;
  var $notification = $('#listing_notifier');

  // -----------------------------
  // Submit The Form
  // -----------------------------

  $('body').on('submit', '#directorist-add-listing-form', function (e) {
    e.preventDefault();
    var $form = $(e.target);
    var error_count = 0;
    var err_log = {};
    var $submitButton = $('.directorist-form-submit__btn');
    if (on_processing) {
      return;
    }
    function disableSubmitButton() {
      on_processing = true;
      $submitButton.addClass('atbd_loading').attr('disabled', true);
    }
    function enableSubmitButton() {
      on_processing = false;
      $submitButton.removeClass('atbd_loading').attr('disabled', false);
    }

    // images
    var selectedImages = [];
    var uploadedImages = [];
    if (mediaUploaders.length) {
      for (var _i2 = 0, _mediaUploaders = mediaUploaders; _i2 < _mediaUploaders.length; _i2++) {
        var uploader = _mediaUploaders[_i2];
        if (!uploader.media_uploader || $(uploader.media_uploader.container).parents('form').get(0) !== $form.get(0)) {
          continue;
        }
        if (!uploader.media_uploader.hasValidFiles()) {
          $submitButton.removeClass('atbd_loading');
          err_log.listing_gallery = {
            msg: uploader.uploaders_data['error_msg']
          };
          error_count++;
          scrollTo('.' + uploader.uploaders_data.element_id);
          break;
        }
        uploader.media_uploader.getTheFiles().forEach(function (file) {
          selectedImages.push({
            field: uploader.uploaders_data.meta_name,
            file: file
          });
        });
      }
    }
    if (selectedImages.length) {
      var counter = 0;
      function uploadImage() {
        var formData = new FormData();
        formData.append('action', 'directorist_upload_listing_image');
        formData.append('directorist_nonce', directorist.directorist_nonce);
        formData.append('image', selectedImages[counter]);
        formData.append('image', selectedImages[counter].file);
        formData.append('field', selectedImages[counter].field);
        $.ajax({
          method: 'POST',
          processData: false,
          contentType: false,
          url: localized_data.ajaxurl,
          data: formData,
          beforeSend: function beforeSend() {
            disableSubmitButton();
            var totalImages = selectedImages.length;
            if (totalImages === 1) {
              $notification.show().html("<span class=\"atbdp_success\">".concat(localized_data.i18n_text.image_uploading_msg, "</span>"));
            } else {
              var completedPercent = Math.ceil((counter === 0 ? 1 : counter) * 100 / totalImages);
              $notification.show().html("<span class=\"atbdp_success\">".concat(localized_data.i18n_text.image_uploading_msg, " (").concat(completedPercent, "%)</span>"));
            }
          },
          success: function success(response) {
            if (!response.success) {
              enableSubmitButton();
              $notification.show().html("<span class=\"atbdp_error\">".concat(response.data, "</span>"));
              return;
            }
            uploadedImages.push({
              field: selectedImages[counter].field,
              file: response.data
            });
            counter++;
            if (counter < selectedImages.length) {
              uploadImage();
            } else {
              submitForm($form, uploadedImages);
            }
          },
          error: function error(response) {
            enableSubmitButton();
            $notification.html("<span class=\"atbdp_error\">".concat(response.responseJSON.data, "</span>"));
          }
        });
      }
      if (uploadedImages.length === selectedImages.length) {
        submitForm($form, uploadedImages);
      } else {
        uploadImage();
      }
    } else {
      submitForm($form);
    }
    function submitForm($form) {
      var uploadedImages = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
      var error_count = 0;
      var err_log = {};
      var form_data = new FormData();
      form_data.append('action', 'add_listing_action');
      form_data.append('directorist_nonce', directorist.directorist_nonce);
      disableSubmitButton();
      var fieldValuePairs = $form.serializeArray();

      // Append Form Fields Values
      var _iterator4 = _createForOfIteratorHelper(fieldValuePairs),
        _step4;
      try {
        for (_iterator4.s(); !(_step4 = _iterator4.n()).done;) {
          var field = _step4.value;
          form_data.append(field.name, field.value);
        }

        // Upload existing image
      } catch (err) {
        _iterator4.e(err);
      } finally {
        _iterator4.f();
      }
      if (mediaUploaders.length) {
        var _loop = function _loop() {
          var uploader = _mediaUploaders2[_i3];
          if (!uploader.media_uploader || $(uploader.media_uploader.container).parents('form').get(0) !== $form.get(0)) {
            return 1; // continue
          }
          if (uploader.media_uploader.hasValidFiles()) {
            uploader.media_uploader.getFilesMeta().forEach(function (file_meta) {
              if (file_meta.attachmentID) {
                form_data.append("".concat(uploader.uploaders_data.meta_name, "_old[]"), file_meta.attachmentID);
              }
            });
          } else {
            err_log.listing_gallery = {
              msg: uploader.uploaders_data['error_msg']
            };
            error_count++;
            if ($('.' + uploader.uploaders_data.element_id).length) {
              scrollTo('.' + uploader.uploaders_data.element_id);
            }
          }
        };
        for (var _i3 = 0, _mediaUploaders2 = mediaUploaders; _i3 < _mediaUploaders2.length; _i3++) {
          if (_loop()) continue;
        }
      }

      // Upload new image
      if (uploadedImages.length) {
        uploadedImages.forEach(function (image) {
          form_data.append("".concat(image.field, "[]"), image.file);
        });
      }

      // categories
      var categories = $form.find('#at_biz_dir-categories').val();
      if (Array.isArray(categories) && categories.length) {
        for (var key in categories) {
          form_data.append('tax_input[at_biz_dir-category][]', categories[key]);
        }
      }
      if (typeof categories === 'string') {
        form_data.append('tax_input[at_biz_dir-category][]', categories);
      }
      if (form_data.has('admin_category_select[]')) {
        form_data.delete('admin_category_select[]');
      }
      if (form_data.has('directory_type')) {
        form_data.delete('directory_type');
      }
      var form_directory_type = $form.find("input[name='directory_type']");
      var form_directory_type_value = form_directory_type !== undefined ? form_directory_type.val() : '';
      var directory_type = qs.directory_type ? qs.directory_type : form_directory_type_value;
      form_data.append('directory_type', directory_type);
      if (qs.plan) {
        form_data.append('plan_id', qs.plan);
      }
      if (qs.order) {
        form_data.append('order_id', qs.order);
      }
      if (error_count) {
        enableSubmitButton();
        console.log('Form has invalid data');
        console.log(error_count, err_log);
        return;
      }
      $.ajax({
        method: 'POST',
        processData: false,
        contentType: false,
        url: localized_data.ajaxurl,
        data: form_data,
        headers: (0,_helper__WEBPACK_IMPORTED_MODULE_3__.directoristRequestHeaders)(),
        beforeSend: function beforeSend() {
          disableSubmitButton();
          $notification.show().html("<span class=\"atbdp_success\">".concat(localized_data.i18n_text.submission_wait_msg, "</span>"));
        },
        success: function success(response) {
          var redirect_url = response && response.redirect_url ? response.redirect_url : '';
          redirect_url = redirect_url && typeof redirect_url === 'string' ? response.redirect_url.replace(/:\/\//g, '%3A%2F%2F') : '';
          if ((response === null || response === void 0 ? void 0 : response.nonce_expired) === true) {
            updateLocalNonce();
          }
          if (response.error === true) {
            enableSubmitButton();
            $notification.show().html("<span>".concat(response.error_msg, "</span>"));
            if (response.quick_login_required) {
              var modal = $('#directorist-quick-login');
              var email = response.email;

              // Prepare fields
              modal.find('input[name="email"]').val(email);
              modal.find('input[name="email"]').prop('disabled', true);

              // Show alert
              var alert = '<div class="directorist-alert directorist-alert-info directorist-mb-10 atbd-text-center directorist-mb-10">' + response.error_msg + '</div>';
              modal.find('.directorist-modal-alerts-area').html(alert);

              // Show the modal
              modal.addClass('show');
              quickLoginModalSuccessCallback = function quickLoginModalSuccessCallback($form, $submitButton) {
                $('#guest_user_email').prop('disabled', true);
                $notification.hide().html('');
                $submitButton.remove();
                $form.find('.directorist-form-actions').find('.directorist-toggle-modal').removeClass('directorist-d-none');
              };
            }
          } else {
            // preview on and no need to redirect to payment
            if (response.preview_mode === true && response.need_payment !== true) {
              if (response.edited_listing !== true) {
                $notification.show().html("<span class=\"atbdp_success\">".concat(response.success_msg, "</span>"));
                window.location.href = joinQueryString(response.preview_url, "preview=1&redirect=".concat(redirect_url));
              } else {
                $notification.show().html("<span class=\"atbdp_success\">".concat(response.success_msg, "</span>"));
                if (qs.redirect) {
                  window.location.href = joinQueryString(response.preview_url, "post_id=".concat(response.id, "&preview=1&payment=1&edited=1&redirect=").concat(qs.redirect));
                } else {
                  window.location.href = joinQueryString(response.preview_url, "preview=1&edited=1&redirect=".concat(redirect_url));
                }
              }
              // preview mode active and need payment
            } else if (response.preview_mode === true && response.need_payment === true) {
              window.location.href = joinQueryString(response.preview_url, "preview=1&payment=1&redirect=".concat(redirect_url));
            } else {
              var is_edited = response.edited_listing ? "listing_id=".concat(response.id, "&edited=1") : '';
              if (response.need_payment === true) {
                $notification.show().html("<span class=\"atbdp_success\">".concat(response.success_msg, "</span>"));
                window.location.href = decodeURIComponent(redirect_url);
              } else {
                $notification.show().html("<span class=\"atbdp_success\">".concat(response.success_msg, "</span>"));
                window.location.href = joinQueryString(decodeURIComponent(response.redirect_url), is_edited);
              }
            }
          }
        },
        error: function error(_error) {
          enableSubmitButton();
          console.log(_error);
        }
      });
    }
  });

  // Custom Field Checkbox Button More
  function customFieldSeeMore() {
    if ($('.directorist-custom-field-btn-more').length) {
      $('.directorist-custom-field-btn-more').each(function (index, element) {
        var fieldWrapper = $(element).closest('.directorist-custom-field-checkbox, .directorist-custom-field-radio');
        var customField = $(fieldWrapper).find('.directorist-checkbox, .directorist-radio');
        $(customField).slice(20, customField.length).hide();
        if (customField.length <= 20) {
          $(element).hide();
        }
      });
    }
  }
  $(window).on('load', function () {
    customFieldSeeMore();
  });
  $('body').on('click', '.directorist-custom-field-btn-more', function (event) {
    event.preventDefault();
    var fieldWrapper = $(this).closest('.directorist-custom-field-checkbox, .directorist-custom-field-radio');
    var customField = $(fieldWrapper).find('.directorist-checkbox, .directorist-radio');
    $(customField).slice(20, customField.length).slideUp();
    $(this).toggleClass('active');
    if ($(this).hasClass('active')) {
      $(this).text(localized_data.i18n_text.see_less_text);
      $(customField).slice(20, customField.length).slideDown();
    } else {
      $(this).text(localized_data.i18n_text.see_more_text);
      $(customField).slice(20, customField.length).slideUp();
    }
  });

  // ------------------------------
  // Quick Login
  // ------------------------------
  $('#directorist-quick-login .directorist-toggle-modal').on("click", function (e) {
    e.preventDefault();
    $("#directorist-quick-login").removeClass("show");
  });
  $('#quick-login-from-submit-btn').on('click', function (e) {
    e.preventDefault();
    var $form = $($(this).data('form'));
    var $feedback = $form.find('.directorist-modal-alerts-area');
    $feedback = $feedback.length ? $feedback : $form.find('.directorist-form-feedback');
    var $email = $form.find('input[name="email"]');
    var $password = $form.find('input[name="password"]');
    var $token = $form.find('input[name="directorist-quick-login-security"]');
    var $submit_button = $(this);
    var submit_button_html = $submit_button.html();
    var form_data = {
      action: 'directorist_ajax_quick_login',
      username: $email.val(),
      password: $password.val(),
      rememberme: false,
      token: $token.val()
    };
    $.ajax({
      method: 'POST',
      url: directorist.ajaxurl,
      data: form_data,
      beforeSend: function beforeSend() {
        $feedback.html('');
        $submit_button.prop('disabled', true);
        $submit_button.prepend('<i class="fas fa-circle-notch fa-spin"></i> ');
      },
      success: function success(response) {
        $submit_button.html(submit_button_html);
        if (response.loggedin) {
          $password.prop('disabled', true);
          var message = 'Successfully logged in, please continue to the listing submission';
          var msg = '<div class="directorist-alert directorist-alert-success directorist-text-center directorist-mb-20">' + message + '</div>';
          $feedback.html(msg);
          if (quickLoginModalSuccessCallback) {
            quickLoginModalSuccessCallback($form, $submit_button);
          }
          updateLocalNonce();
        } else {
          var msg = '<div class="directorist-alert directorist-alert-danger directorist-text-center directorist-mb-20">' + response.message + '</div>';
          $feedback.html(msg);
          $submit_button.prop('disabled', false);
        }
      },
      error: function error(_error2) {
        console.log({
          error: _error2
        });
        $submit_button.prop('disabled', false);
        $submit_button.html(submit_button_html);
      }
    });
  });
  function addSticky() {
    $(window).scroll((0,_components_debounce__WEBPACK_IMPORTED_MODULE_7__["default"])(function () {
      var windowWidth = $(window).width();
      var sidebarWidth = $(".multistep-wizard__nav").width();
      var sidebarHeight = $(".multistep-wizard__nav").height();
      var multiStepWizardOffset = $(".multistep-wizard").offset() && $(".multistep-wizard").offset().top;
      var multiStepWizardHeight = $(".multistep-wizard").outerHeight();
      if (windowWidth > 991) {
        var scrollPos = $(window).scrollTop();

        // Check if the user has scrolled down to the container position
        if (scrollPos >= multiStepWizardOffset) {
          $(".multistep-wizard__nav").addClass("sticky");
          $(".multistep-wizard__content").css("padding-inline-start", sidebarWidth + 30 + 'px');
          // Check if the user has fully scrolled the container
          if (scrollPos >= multiStepWizardOffset + multiStepWizardHeight - sidebarHeight) {
            $(".multistep-wizard__nav").removeClass("sticky");
            $(".multistep-wizard__content").css("padding-inline-start", '0px');
          } else {
            $(".multistep-wizard__nav").addClass("sticky");
            $(".multistep-wizard__content").css("padding-inline-start", sidebarWidth + 30 + 'px');
          }
        } else {
          $(".multistep-wizard__nav").removeClass("sticky");
          $(".multistep-wizard__content").css("padding-inline-start", '0px');
        }
      } else {
        $(".multistep-wizard__nav").removeClass("sticky");
        $(".multistep-wizard__content").css("padding-inline-start", '0px');
      }
    }, 100));
  }
  addSticky();
  multiStepWizard();
  defaultAddListing();
});

// MultiStep Wizard
function multiStepWizard() {
  var defaultAddListing = document.querySelector('.multistep-wizard.default-add-listing');
  if (!defaultAddListing) {
    var totalStep = document.querySelectorAll('.multistep-wizard .multistep-wizard__nav__btn');
    var totalWizard = document.querySelectorAll('.multistep-wizard .multistep-wizard__single');
    var totalWizardCount = document.querySelector('.multistep-wizard .multistep-wizard__count__total');
    var currentWizardCount = document.querySelector('.multistep-wizard .multistep-wizard__count__current');
    var progressWidth = document.querySelector('.multistep-wizard .multistep-wizard__progressbar__width');
    var stepCount = 1;
    var progressPerStep = 100 / totalWizard.length;

    // Initialize Wizard Count & Progressbar
    if (currentWizardCount) {
      currentWizardCount.innerHTML = stepCount;
    }
    if (totalWizardCount) {
      totalWizardCount.innerHTML = totalWizard.length;
    }
    if (progressWidth) {
      progressWidth.style.width = progressPerStep + '%';
    }

    // Set data-id on Wizards
    totalWizard.forEach(function (item, index) {
      item.setAttribute('data-id', index);
      item.style.display = 'none';
      if (index === 0) {
        item.style.display = 'block';
        item.classList.add('active');
      }
    });

    // Set data-step on Nav Items
    totalStep.forEach(function (item, index) {
      item.setAttribute('data-step', index);
      if (index === 0) {
        item.classList.add('active');
      }
    });

    // Go Previous Step
    $('.multistep-wizard__btn--prev').on('click', function (e) {
      e.preventDefault();
      if (stepCount > 1) {
        stepCount--;
        activeWizard(stepCount);
        if (stepCount <= 1) {
          this.setAttribute('disabled', true);
        }
      }
    });

    // Go Next Step
    $('.multistep-wizard__btn--next').on('click', function (e) {
      e.preventDefault();
      if (stepCount < totalWizard.length) {
        stepCount++;
        activeWizard(stepCount);
      }
    });

    // Go Random Step
    $('.multistep-wizard__nav__btn').on('click', function (e) {
      e.preventDefault();
      if (this.classList.contains('completed')) {
        var currentStep = Number(this.attributes[3].value) + 1;
        stepCount = currentStep;
        activeWizard(stepCount);
      }
      if (stepCount <= 1) {
        $('.multistep-wizard__btn--prev').attr('disabled', true);
      }
    });

    // Active Wizard
    function activeWizard(value) {
      // Add Active Class
      totalWizard.forEach(function (item, index) {
        if (item.classList.contains('active')) {
          item.classList.remove('active');
          item.style.display = 'none';
        } else if (value - 1 === index) {
          item.classList.add('active');
          item.style.display = 'block';
        }
      });

      // Add Completed Class
      totalStep.forEach(function (item, index) {
        if (index + 1 < value) {
          item.classList.add('completed');
        } else {
          item.classList.remove('completed');
        }
        if (item.classList.contains('active')) {
          item.classList.remove('active');
        } else if (value - 1 === index) {
          item.classList.add('active');
        }
      });

      // Enable Previous Button
      if (value > 1) {
        $('.multistep-wizard__btn--prev').removeAttr('disabled');
      }

      // Change Button Text on Last Step
      var nextBtn = document.querySelector('.multistep-wizard__btn--next');
      var previewBtn = document.querySelector('.multistep-wizard__btn--save-preview');
      var submitBtn = document.querySelector('.multistep-wizard__btn--skip-preview');
      if (value === totalWizard.length) {
        nextBtn.style.cssText = "display:none; width: 0; height: 0; opacity: 0; visibility: hidden;";
        previewBtn.style.cssText = "height: 54px; flex: unset; opacity: 1; visibility: visible;";
        submitBtn.style.cssText = "height: 54px; opacity: 1; visibility: visible;";
      } else {
        nextBtn.style.cssText = "display:inline-flex; width: 200px; height: 54px; opacity: 1; visibility: visible;";
        previewBtn.style.cssText = "height: 0; flex: 0 0 100%; opacity: 0; visibility: hidden;";
        submitBtn.style.cssText = "height: 0; opacity: 0; visibility: hidden;";
      }

      // Update Wizard Count & Progressbar
      currentWizardCount.innerHTML = value;
      progressWidth.style.width = progressPerStep * value + '%';
      progressWidth.style.transition = "0.5s ease";
    }
  }
}

// Default Add Listing
function defaultAddListing() {
  var navLinks = document.querySelectorAll(".default-add-listing .multistep-wizard__nav .multistep-wizard__nav__btn");

  // Add 'active' class to the first navigation item on page load
  window.addEventListener("load", function () {
    if (navLinks.length > 0) {
      navLinks[0].classList.add("active");
    }
  });

  // Function to determine which section is currently in view
  function getCurrentSectionInView() {
    var currentSection = null;
    var sections = document.querySelectorAll(".default-add-listing .multistep-wizard__content .multistep-wizard__single");
    if (sections) {
      sections.forEach(function (section) {
        var rect = section.getBoundingClientRect();
        if (rect.top <= 50 && rect.bottom >= 50) {
          currentSection = section.getAttribute("id");
        }
      });
    }
    return currentSection;
  }

  // Function to update active class on navigation items
  function updateActiveNav() {
    var currentSection = getCurrentSectionInView();
    if (currentSection == null) {
      navLinks[0].classList.add("active");
    } else {
      if (navLinks[0].classList.contains("active")) {
        navLinks[0].classList.remove("active");
      }
      navLinks.forEach(function (link) {
        if (link.getAttribute("href") === "#".concat(currentSection)) {
          link.classList.add("active");
        } else {
          link.classList.remove("active");
        }
      });
    }
  }

  // Function to scroll smoothly to the target section
  function smoothScroll(targetSection) {
    var scrollDuration = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 1000;
    var targetElement = document.getElementById(targetSection);
    if (!targetElement) return;
    var targetPosition = targetElement.getBoundingClientRect().top + window.scrollY;
    var startPosition = window.scrollY;
    var scrollDistance = targetPosition - startPosition;
    var startTime = null;
    function scrollAnimation(currentTime) {
      if (startTime === null) startTime = currentTime;
      var timeElapsed = currentTime - startTime;
      var run = easeInOutQuad(timeElapsed, startPosition, scrollDistance, scrollDuration);
      window.scrollTo(0, run);
      if (timeElapsed < scrollDuration) {
        requestAnimationFrame(scrollAnimation); // Continue the scrollAnimation
      }
    }
    function easeInOutQuad(t, b, c, d) {
      t /= d / 2;
      if (t < 1) return c / 2 * t * t + b;
      t--;
      return -c / 2 * (t * (t - 2) - 1) + b;
    }
    requestAnimationFrame(scrollAnimation); // Start the scrollAnimation
  }

  // Initial update and update on scroll
  if (navLinks.length > 0) {
    updateActiveNav();
    window.addEventListener("scroll", updateActiveNav);
  }

  // Add smooth scroll to navigation links
  navLinks.forEach(function (link) {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      var targetSection = this.getAttribute("href").substring(1);
      smoothScroll(targetSection, 1250);
    });
  });
}

// Add Listing Accordion
function addListingAccordion() {
  $('body').on('click', '.directorist-add-listing-form .directorist-content-module__title', function (e) {
    e.preventDefault();
    var windowScreen = window.innerWidth;
    if (windowScreen <= 991) {
      $(this).toggleClass('opened');
      $(this).next('.directorist-content-module__contents').toggleClass('active');
    }
  });
}
addListingAccordion();

// Multistep Add Listing on Elementor EditMode
$(window).on('elementor/frontend/init', function () {
  setTimeout(function () {
    if ($('body').hasClass('elementor-editor-active')) {
      multiStepWizard();
    }
  }, 3000);
});
$('body').on('click', function (e) {
  if ($('body').hasClass('elementor-editor-active') && e.target.nodeName !== 'A' && e.target.nodeName !== 'BUTTON') {
    multiStepWizard();
  }
});
function updateLocalNonce() {
  $.ajax({
    type: 'POST',
    url: localized_data.ajaxurl,
    data: {
      action: 'directorist_generate_nonce'
    },
    success: function success(response) {
      if (response.success) {
        window.directorist.directorist_nonce = response.data.directorist_nonce;
      }
    }
  });
}
}();
/******/ })()
;
//# sourceMappingURL=add-listing.js.map