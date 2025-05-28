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

/***/ "./assets/src/js/public/components/archive-sidebar.js":
/*!************************************************************!*\
  !*** ./assets/src/js/public/components/archive-sidebar.js ***!
  \************************************************************/
/***/ (function() {

// Archive Sidebar
window.addEventListener('load', function () {
  var body = document.body;

  // Toggle sidebar and update toggle button's active state
  function toggleSidebar(toggleBtn, archiveSidebar) {
    archiveSidebar.classList.toggle('listing-with-sidebar__sidebar--open');
    toggleBtn.classList.toggle('directorist-archive-sidebar-toggle--active');
    body.classList.toggle('modal-overlay-enabled');
  }

  // Close sidebar and reset toggle button's active state
  function closeSidebar(toggleBtn, archiveSidebar) {
    archiveSidebar.classList.remove('listing-with-sidebar__sidebar--open');
    toggleBtn.classList.remove('directorist-archive-sidebar-toggle--active');
    body.classList.remove('modal-overlay-enabled');
  }

  // Toggle or close sidebar when toggle/close button is clicked
  body.addEventListener('click', function (e) {
    var targetElement = e.target;
    var toggleBtn = targetElement.closest('.directorist-archive-sidebar-toggle');
    var closeBtn = targetElement.closest('.directorist-advanced-filter__close');
    if (toggleBtn) {
      var sidebar = toggleBtn.closest('.listing-with-sidebar').querySelector('.listing-with-sidebar__sidebar');
      toggleSidebar(toggleBtn, sidebar);
    } else if (closeBtn) {
      var _sidebar = closeBtn.closest('.listing-with-sidebar').querySelector('.listing-with-sidebar__sidebar');
      var _toggleBtn = _sidebar.closest('.listing-with-sidebar').querySelector('.directorist-archive-sidebar-toggle');
      closeSidebar(_toggleBtn, _sidebar);
    } else if (body.classList.contains('modal-overlay-enabled') && !targetElement.closest('.listing-with-sidebar__sidebar')) {
      document.querySelectorAll('.listing-with-sidebar__sidebar--open').forEach(function (sidebar) {
        var toggleBtn = sidebar.closest('.listing-with-sidebar').querySelector('.directorist-archive-sidebar-toggle');
        closeSidebar(toggleBtn, sidebar);
      });
    }
  });
});

/***/ }),

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

/***/ "./assets/src/js/public/components/directoristAlert.js":
/*!*************************************************************!*\
  !*** ./assets/src/js/public/components/directoristAlert.js ***!
  \*************************************************************/
/***/ (function() {

;
(function ($) {
  // Make sure the codes in this file runs only once, even if enqueued twice
  if (typeof window.directorist_alert_executed === 'undefined') {
    window.directorist_alert_executed = true;
  } else {
    return;
  }
  window.addEventListener('load', function () {
    /* Directorist alert dismiss */
    var getUrl = window.location.href;
    var newUrl = getUrl.replace('notice=1', '');
    if ($('.directorist-alert__close') !== null) {
      $('.directorist-alert__close').each(function (i, e) {
        $(e).on('click', function (e) {
          e.preventDefault();
          history.pushState({}, null, newUrl);
          $(this).closest('.directorist-alert').remove();
        });
      });
    }
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

/***/ "./assets/src/js/public/components/directoristFavorite.js":
/*!****************************************************************!*\
  !*** ./assets/src/js/public/components/directoristFavorite.js ***!
  \****************************************************************/
/***/ (function() {

;
(function ($) {
  // Make sure the codes in this file runs only once, even if enqueued twice
  if (typeof window.directorist_favorite_executed === 'undefined') {
    window.directorist_favorite_executed = true;
  } else {
    return;
  }
  window.addEventListener('load', function () {
    // Add or Remove from favourites
    $('.directorist-action-bookmark').on('click', function (e) {
      e.preventDefault();
      var data = {
        'action': 'atbdp_public_add_remove_favorites',
        'directorist_nonce': directorist.directorist_nonce,
        'post_id': $(this).data('listing_id')
      };
      $.post(directorist.ajaxurl, data, function (response) {
        if (response) {
          $('.directorist-action-bookmark').html(response);
        }
      });
    });
    $('.directorist-favourite-remove-btn').each(function () {
      $(this).on('click', function (event) {
        event.preventDefault();
        var data = {
          'action': 'atbdp-favourites-all-listing',
          'directorist_nonce': directorist.directorist_nonce,
          'post_id': $(this).data('listing_id')
        };
        $(".directorist-favorite-tooltip").hide();
        $.post(directorist.ajaxurl, data, function (response) {
          var post_id = data['post_id'].toString();
          var staElement = $('.directorist_favourite_' + post_id);
          if ('false' === response) {
            staElement.remove();
          }
        });
      });
    });
    $('body').on("click", '.directorist-mark-as-favorite__btn', function (event) {
      event.preventDefault();
      var data = {
        'action': 'atbdp-favourites-all-listing',
        'directorist_nonce': directorist.directorist_nonce,
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

/***/ "./assets/src/js/public/components/directoristSorting.js":
/*!***************************************************************!*\
  !*** ./assets/src/js/public/components/directoristSorting.js ***!
  \***************************************************************/
/***/ (function() {

;
(function ($) {
  // Make sure the codes in this file runs only once, even if enqueued twice
  if (typeof window.directorist_sorting_executed === 'undefined') {
    window.directorist_sorting_executed = true;
  } else {
    return;
  }
  window.addEventListener('load', function () {
    // Sorting Js
    if (!$('.directorist-instant-search').length) {
      $('.directorist-dropdown__links__single-js').click(function (e) {
        e.preventDefault();
        var href = $(this).attr('data-link');
        $('#directorsit-listing-sort').attr('action', href);
        $('#directorsit-listing-sort').submit();
      });
    }

    //sorting toggle
    $('.sorting span').on('click', function () {
      $(this).toggleClass('fa-sort-amount-asc fa-sort-amount-desc');
    });
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/general.js":
/*!****************************************************!*\
  !*** ./assets/src/js/public/components/general.js ***!
  \****************************************************/
/***/ (function() {

// Fix listing with no thumb if card width is less than 220px
(function ($) {
  window.addEventListener('load', function () {
    if ($('.directorist-listing-no-thumb').innerWidth() <= 220) {
      $('.directorist-listing-no-thumb').addClass('directorist-listing-no-thumb--fix');
    }
    // Auhtor Profile Listing responsive fix
    if ($('.directorist-author-listing-content').innerWidth() <= 750) {
      $('.directorist-author-listing-content').addClass('directorist-author-listing-grid--fix');
    }
    // Directorist Archive responsive fix
    if ($('.directorist-archive-grid-view').innerWidth() <= 500) {
      $('.directorist-archive-grid-view').addClass('directorist-archive-grid--fix');
    }

    // Back Button to go back to the previous page
    $('body').on('click', '.directorist-btn__back', function (e) {
      window.history.back();
    });
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/gridResponsive.js":
/*!***********************************************************!*\
  !*** ./assets/src/js/public/components/gridResponsive.js ***!
  \***********************************************************/
/***/ (function() {

;
(function ($) {
  /* Responsive grid control */
  $(document).ready(function () {
    var d_wrapper = $("#directorist.atbd_wrapper");
    var columnLeft = $(".atbd_col_left.col-lg-8");
    var columnRight = $(".directorist.col-lg-4");
    var tabColumn = $(".atbd_dashboard_wrapper .tab-content .tab-pane .col-lg-4");
    var w_size = d_wrapper.width();
    if (w_size >= 500 && w_size <= 735) {
      columnLeft.toggleClass("col-lg-8");
      columnRight.toggleClass("col-lg-4");
    }
    if (w_size <= 600) {
      d_wrapper.addClass("size-xs");
      tabColumn.toggleClass("col-lg-4");
    }
    var listing_size = $(".atbd_dashboard_wrapper .atbd_single_listing").width();
    if (listing_size < 200) {
      $(".atbd_single_listing .db_btn_area").addClass("db_btn_area--sm");
    }
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/helpers.js":
/*!****************************************************!*\
  !*** ./assets/src/js/public/components/helpers.js ***!
  \****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _helpers_printRating__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./helpers/printRating */ "./assets/src/js/public/components/helpers/printRating.js");
/* harmony import */ var _helpers_printRating__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_helpers_printRating__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _helpers_createMysql__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./helpers/createMysql */ "./assets/src/js/public/components/helpers/createMysql.js");
/* harmony import */ var _helpers_createMysql__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_helpers_createMysql__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _helpers_postDraft__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./helpers/postDraft */ "./assets/src/js/public/components/helpers/postDraft.js");
/* harmony import */ var _helpers_postDraft__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_helpers_postDraft__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _helpers_handleAjaxRequest__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./helpers/handleAjaxRequest */ "./assets/src/js/public/components/helpers/handleAjaxRequest.js");
/* harmony import */ var _helpers_handleAjaxRequest__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_helpers_handleAjaxRequest__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _helpers_noImageController__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./helpers/noImageController */ "./assets/src/js/public/components/helpers/noImageController.js");
/* harmony import */ var _helpers_noImageController__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_helpers_noImageController__WEBPACK_IMPORTED_MODULE_4__);
// Helper Components






/***/ }),

/***/ "./assets/src/js/public/components/helpers/createMysql.js":
/*!****************************************************************!*\
  !*** ./assets/src/js/public/components/helpers/createMysql.js ***!
  \****************************************************************/
/***/ (function() {

;
(function ($) {
  // Helper function to convert the mysql date
  Date.createFromMysql = function (mysql_string) {
    var t,
      result = null;
    if (typeof mysql_string === 'string') {
      t = mysql_string.split(/[- :]/);

      //when t[3], t[4] and t[5] are missing they defaults to zero
      result = new Date(t[0], t[1] - 1, t[2], t[3] || 0, t[4] || 0, t[5] || 0);
    }
    return result;
  };
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/helpers/handleAjaxRequest.js":
/*!**********************************************************************!*\
  !*** ./assets/src/js/public/components/helpers/handleAjaxRequest.js ***!
  \**********************************************************************/
/***/ (function() {

;
(function ($) {
  /*This function handles all ajax request*/
  function atbdp_do_ajax(ElementToShowLoadingIconAfter, ActionName, arg, CallBackHandler) {
    var data;
    if (ActionName) data = "action=" + ActionName;
    if (arg) data = arg + "&action=" + ActionName;
    if (arg && !ActionName) data = arg;
    //data = data ;

    var n = data.search(directorist.nonceName);
    if (n < 0) {
      data = data + "&" + directorist.nonceName + "=" + directorist.nonce;
    }
    jQuery.ajax({
      type: "post",
      url: directorist.ajaxurl,
      data: data,
      beforeSend: function beforeSend() {
        jQuery("<span class='atbdp_ajax_loading'></span>").insertAfter(ElementToShowLoadingIconAfter);
      },
      success: function success(data) {
        jQuery(".atbdp_ajax_loading").remove();
        CallBackHandler(data);
      }
    });
  }
  window.atbdp_do_ajax = atbdp_do_ajax;
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/helpers/noImageController.js":
/*!**********************************************************************!*\
  !*** ./assets/src/js/public/components/helpers/noImageController.js ***!
  \**********************************************************************/
/***/ (function() {

;
(function ($) {
  /* Listing No Image Controller */
  $('.atbd_listing_no_image .atbd_lower_badge').each(function (i, elm) {
    if (!$.trim($(elm).html()).length) {
      $(this).addClass('atbd-no-spacing');
    }
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/helpers/postDraft.js":
/*!**************************************************************!*\
  !*** ./assets/src/js/public/components/helpers/postDraft.js ***!
  \**************************************************************/
/***/ (function() {

;
(function ($) {
  //adding temporary css class to post draft page
  if ($(".edit_btn_wrap .atbdp_float_active").length) {
    $("body").addClass("atbd_post_draft");
  }
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/helpers/printRating.js":
/*!****************************************************************!*\
  !*** ./assets/src/js/public/components/helpers/printRating.js ***!
  \****************************************************************/
/***/ (function() {

;
(function ($) {
  /* Helper Function for priting static rating */
  function print_static_rating($star_number) {
    var v;
    if ($star_number) {
      v = '<ul>';
      for (var i = 1; i <= 5; i++) {
        v += i <= $star_number ? "<li><span class='directorist-rate-active'></span></li>" : "<li><span class='directorist-rate-disable'></span></li>";
      }
      v += '</ul>';
    }
    return v;
  }
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/instantSearch.js":
/*!**********************************************************!*\
  !*** ./assets/src/js/public/components/instantSearch.js ***!
  \**********************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _global_components_debounce__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../global/components/debounce */ "./assets/src/js/global/components/debounce.js");

function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t.return || t.return(); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }

;
(function ($) {
  var full_url = window.location.href;

  // Update search URL with form data
  function update_instant_search_url(form_data) {
    if (history.pushState) {
      var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;
      if (form_data.paged && form_data.paged.length) {
        var query = query && query.length ? query + '&paged=' + form_data.paged : '?paged=' + form_data.paged;
      }
      if (form_data.directory_type && form_data.directory_type.length) {
        var query = query && query.length ? query + '&directory_type=' + form_data.directory_type : '?directory_type=' + form_data.directory_type;
      }
      if (form_data.view && form_data.view.length) {
        var query = query && query.length ? query + '&view=' + form_data.view : '?view=' + form_data.view;
      }
      if (form_data.q && form_data.q.length) {
        var query = query && query.length ? query + '&q=' + form_data.q : '?q=' + form_data.q;
      }
      if (form_data.in_cat && form_data.in_cat.length) {
        var query = query && query.length ? query + '&in_cat=' + form_data.in_cat : '?in_cat=' + form_data.in_cat;
      }
      if (form_data.in_loc && form_data.in_loc.length) {
        var query = query && query.length ? query + '&in_loc=' + form_data.in_loc : '?in_loc=' + form_data.in_loc;
      }
      if (form_data.in_tag && form_data.in_tag.length) {
        var query = query && query.length ? query + '&in_tag=' + form_data.in_tag : '?in_tag=' + form_data.in_tag;
      }
      if (form_data.price && form_data.price[0] && form_data.price[0] > 0) {
        var query = query && query.length ? query + '&price%5B0%5D=' + form_data.price[0] : '?price%5B0%5D=' + form_data.price[0];
      }
      if (form_data.price && form_data.price[1] && form_data.price[1] > 0) {
        var query = query && query.length ? query + '&price%5B1%5D=' + form_data.price[1] : '?price%5B1%5D=' + form_data.price[1];
      }
      if (form_data.price_range && form_data.price_range.length) {
        var query = query && query.length ? query + '&price_range=' + form_data.price_range : '?price_range=' + form_data.price_range;
      }
      if (form_data.search_by_rating && form_data.search_by_rating.length) {
        var query = query && query.length ? query + '&search_by_rating=' + form_data.search_by_rating : '?search_by_rating=' + form_data.search_by_rating;
      }
      if (form_data.cityLat && form_data.cityLat.length && form_data.address && form_data.address.length) {
        var query = query && query.length ? query + '&cityLat=' + form_data.cityLat : '?cityLat=' + form_data.cityLat;
      }
      if (form_data.cityLng && form_data.cityLng.length && form_data.address && form_data.address.length) {
        var query = query && query.length ? query + '&cityLng=' + form_data.cityLng : '?cityLng=' + form_data.cityLng;
      }
      if (form_data.miles && form_data.miles.length) {
        var query = query && query.length ? query + '&miles=' + form_data.miles : '?miles=' + form_data.miles;
      }
      if (form_data.address && form_data.address.length) {
        var query = query && query.length ? query + '&address=' + form_data.address : '?address=' + form_data.address;
      }
      if (form_data.zip && form_data.zip.length) {
        var query = query && query.length ? query + '&zip=' + form_data.zip : '?zip=' + form_data.zip;
      }
      if (form_data.fax && form_data.fax.length) {
        var query = query && query.length ? query + '&fax=' + form_data.fax : '?fax=' + form_data.fax;
      }
      if (form_data.email && form_data.email.length) {
        var query = query && query.length ? query + '&email=' + form_data.email : '?email=' + form_data.email;
      }
      if (form_data.website && form_data.website.length) {
        var query = query && query.length ? query + '&website=' + form_data.website : '?website=' + form_data.website;
      }
      if (form_data.phone && form_data.phone.length) {
        var query = query && query.length ? query + '&phone=' + form_data.phone : '?phone=' + form_data.phone;
      }
      if (form_data.custom_field && Object.keys(form_data.custom_field).length) {
        Object.keys(form_data.custom_field).forEach(function (key) {
          query = query.length ? query + "&".concat(key, "=").concat(form_data.custom_field[key]) : "?".concat(key, "=").concat(form_data.custom_field[key]);
        });
      }
      if (form_data.open_now && form_data.open_now.length) {
        var query = query && query.length ? query + '&open_now=' + form_data.open_now : '?open_now=' + form_data.open_now;
      }
      var newurl = query ? newurl + query : newurl;
      window.history.pushState({
        path: newurl
      }, '', newurl);
    }
  }

  // Get URL Parameter
  function getURLParameter(url, name) {
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
    var results = regex.exec(url);
    if (!results || !results[2]) {
      return '';
    }
    return decodeURIComponent(results[2]);
  }

  // Close Search Modal
  function closeAllSearchModal() {
    var searchModalElement = document.querySelectorAll('.directorist-search-modal');
    searchModalElement.forEach(function (modal) {
      var modalOverlay = modal.querySelector('.directorist-search-modal__overlay');
      var modalContent = modal.querySelector('.directorist-search-modal__contents');
      var modalBodyOverlay = document.querySelector('.directorist-content-active');

      // Overlay Style
      if (modalOverlay) {
        modalOverlay.style.cssText = "opacity: 0; visibility: hidden; transition: 0.5s ease";
        // remove overlay class on body
        modalBodyOverlay.classList.remove('directorist-overlay-active');
      }

      // Modal Content Style
      if (modalContent) {
        modalContent.style.cssText = "opacity: 0; visibility: hidden; bottom: -200px;";
      }
    });
  }

  // Scrolling Pagination
  var page = 1;
  var infinitePaginationIsLoading = false;
  var infinitePaginationCompleted = false;
  function handleScroll() {
    var container = $('.directorist-infinite-scroll .directorist-container-fluid .directorist-row');
    if (!container.length || infinitePaginationIsLoading) return;
    var containerBottom = container.offset().top + container.outerHeight();
    var scrollBottom = window.scrollY + window.innerHeight;
    if (scrollBottom >= containerBottom) {
      infinitePaginationIsLoading = true;
      page++;
      var instantSearchElement = $('.directorist-instant-search');
      var activeForm = getActiveForm(instantSearchElement);
      var formData = buildFormData(activeForm, instantSearchElement);
      loadMoreListings(formData);
    }
  }
  ;
  window.addEventListener('scroll', function () {
    if (infinitePaginationCompleted) return;
    handleScroll();
  });

  /* Directorist instant search */
  $('body').on("submit", ".directorist-instant-search form", function (e) {
    e.preventDefault();
    // infinite pagination loading reset
    page = 1;
    infinitePaginationIsLoading = false;
    infinitePaginationCompleted = false;
    var instant_search_element = $(this).closest('.directorist-instant-search');
    var tag = [];
    var search_by_rating = [];
    var price = [];
    var custom_field = {};
    $(this).find('input[name^="in_tag["]:checked').each(function (index, el) {
      tag.push($(el).val());
    });
    $(this).find('input[name^="search_by_rating["]:checked').each(function (index, el) {
      search_by_rating.push($(el).val());
    });
    $(this).find('input[name^="price["]').each(function (index, el) {
      price.push($(el).val());
    });
    $(this).find('[name^="custom_field"]').each(function (index, el) {
      var name = $(el).attr('name');
      var type = $(el).attr('type');
      var post_id = name.replace(/(custom_field\[)/, '').replace(/\]/, '');
      if ('radio' === type) {
        $.each($("input[name='custom_field[" + post_id + "]']:checked"), function () {
          value = $(this).val();
          custom_field[post_id] = value;
        });
      } else if ('checkbox' === type) {
        post_id = post_id.split('[]')[0];
        if (!custom_field[post_id]) {
          custom_field[post_id] = [];
        }
        $.each($("input[name='custom_field[" + post_id + "][]']:checked"), function () {
          var value = $(this).val();
          custom_field[post_id].push(value);
        });
      } else {
        var value = $(el).val();
        custom_field[post_id] = value;
      }
    });
    var view_href = instant_search_element.find(".directorist-viewas .directorist-viewas__item.active").attr('href');
    var view_as = view_href && view_href.length ? view_href.match(/view=.+/) : '';
    var view = view_as && view_as.length ? view_as[0].replace(/view=/, '') : '';
    var type_href = instant_search_element.find('.directorist-type-nav__list .directorist-type-nav__list__current a').attr('href');
    var type = type_href && type_href.length ? type_href.match(/directory_type=.+/) : '';
    var directory_type = getURLParameter(type_href, 'directory_type');
    var data_atts = instant_search_element.attr('data-atts');
    var data = {
      action: 'directorist_instant_search',
      _nonce: directorist.ajax_nonce,
      current_page_id: directorist.current_page_id,
      in_tag: tag,
      price: price,
      search_by_rating: search_by_rating,
      custom_field: custom_field,
      data_atts: JSON.parse(data_atts)
    };
    var fields = {
      q: $(this).find('input[name="q"]').val(),
      in_cat: $(this).find('.directorist-category-select').val(),
      in_loc: $(this).find('.directorist-location-select').val(),
      price_range: $(this).find("input[name='price_range']:checked").val(),
      address: $(this).find('input[name="address"]').val(),
      zip: $(this).find('input[name="zip"]').val(),
      fax: $(this).find('input[name="fax"]').val(),
      email: $(this).find('input[name="email"]').val(),
      website: $(this).find('input[name="website"]').val(),
      phone: $(this).find('input[name="phone"]').val()
    };

    //business hours
    if ($('input[name="open_now"]').is(':checked')) {
      fields.open_now = $(this).find('input[name="open_now"]').val();
    }
    if (fields.address && fields.address.length) {
      fields.cityLat = $(this).find('#cityLat').val();
      fields.cityLng = $(this).find('#cityLng').val();
      fields.miles = $(this).find('input[name="miles"]').val();
    }
    if (fields.zip && fields.zip.length) {
      fields.zip_cityLat = $(this).find('.zip-cityLat').val();
      fields.zip_cityLng = $(this).find('.zip-cityLng').val();
      fields.miles = $(this).find('input[name="miles"]').val();
    }
    var form_data = _objectSpread(_objectSpread({}, data), fields);
    var allFieldsAreEmpty = Object.values(fields).every(function (item) {
      return !item;
    });
    var tagFieldEmpty = data.in_tag.every(function (item) {
      return !item;
    });
    var priceFieldEmpty = data.price.every(function (item) {
      return !item;
    });
    var ratingFieldEmpty = data.search_by_rating.every(function (item) {
      return !item;
    });
    var customFieldsAreEmpty = Object.values(data.custom_field).every(function (item) {
      return !item;
    });
    if (!allFieldsAreEmpty || !tagFieldEmpty || !priceFieldEmpty || !customFieldsAreEmpty || !ratingFieldEmpty) {
      if (view && view.length) {
        form_data.view = view;
      }
      if (directory_type && directory_type.length) {
        form_data.directory_type = directory_type;
      }
      update_instant_search_url(form_data);
      $.ajax({
        url: directorist.ajaxurl,
        type: "POST",
        data: form_data,
        beforeSend: function beforeSend() {
          instant_search_element.find('.directorist-advanced-filter__form .directorist-btn-sm').attr("disabled", true);
          instant_search_element.find('.directorist-archive-items').addClass('atbdp-form-fade');
          instant_search_element.find('.directorist-header-bar .directorist-advanced-filter').removeClass('directorist-advanced-filter--show');
          instant_search_element.find('.directorist-header-bar .directorist-advanced-filter').hide();
          $(document).scrollTop(instant_search_element.offset().top);
          closeAllSearchModal();
        },
        success: function success(html) {
          if (html.search_result) {
            instant_search_element.find('.directorist-header-found-title').remove();
            instant_search_element.find('.dsa-save-search-container').remove();
            instant_search_element.find('.directorist-listings-header__left').append(html.header_title);
            instant_search_element.find('.directorist-header-found-title span').text(html.count);
            instant_search_element.find('.directorist-archive-items').replaceWith(html.search_result);
            instant_search_element.find('.directorist-archive-items').removeClass('atbdp-form-fade');
            instant_search_element.find('.directorist-advanced-filter__form .directorist-btn-sm').attr("disabled", false);
            window.dispatchEvent(new CustomEvent('directorist-instant-search-reloaded'));
            window.dispatchEvent(new CustomEvent('directorist-reload-listings-map-archive'));
            var website_name = directorist.site_name; // This is dynamically set from WordPress

            // Construct the new meta title
            var new_meta_title = ''; // Start with an empty title
            // Check if the category is selected and append to the title
            if (String(html.category_name)) {
              new_meta_title += html.category_name;
            }

            // Check if location is selected and append with proper formatting
            if (String(html.location_name)) {
              if (String(html.category_name)) {
                new_meta_title += ' within ' + html.location_name; // If category exists, add with a comma
              } else {
                new_meta_title += html.location_name; // If no category, just add location
              }
            }

            // Check if address is selected and append with proper formatting
            if (fields.address) {
              if (fields.in_cat || fields.in_loc) {
                new_meta_title += ' near ' + fields.address; // If category or location exists, add "near"
              } else {
                new_meta_title += fields.address; // Default to just the address
              }
            }

            // Append website name to the meta title with a pipe separator
            if (new_meta_title) {
              new_meta_title += ' | ' + website_name; // Append the website name only if the title has content
            } else {
              new_meta_title = website_name; // Default to only the website name if no other title parts are present
            }

            // Update the meta title dynamically
            document.title = new_meta_title;
          }
        }
      });
    }
  });

  /* Directorist instant reset */
  $('body').on("click", ".directorist-instant-search .directorist-btn-reset-js", function (e) {
    e.preventDefault();
    var instant_search_element = $(this).closest('.directorist-instant-search');
    var tag = [];
    var search_by_rating = [];
    var price = [];
    var custom_field = {};
    $(this).find('input[name^="in_tag["]:checked').each(function (index, el) {
      tag.push($(el).val());
    });
    $(this).find('input[name^="search_by_rating["]:checked').each(function (index, el) {
      search_by_rating.push($(el).val());
    });
    $(this).find('input[name^="price["]').each(function (index, el) {
      price.push($(el).val());
    });
    $(this).find('[name^="custom_field"]').each(function (index, el) {
      var name = $(el).attr('name');
      var type = $(el).attr('type');
      var post_id = name.replace(/(custom_field\[)/, '').replace(/\]/, '');
      if ('radio' === type) {
        $.each($("input[name='custom_field[" + post_id + "]']:checked"), function () {
          value = $(this).val();
          custom_field[post_id] = value;
        });
      } else if ('checkbox' === type) {
        post_id = post_id.split('[]')[0];
        $.each($("input[name='custom_field[" + post_id + "][]']:checked"), function () {
          var checkValue = [];
          value = $(this).val();
          checkValue.push(value);
          custom_field[post_id] = checkValue;
        });
      } else {
        var value = $(el).val();
        custom_field[post_id] = value;
      }
    });
    var view_href = instant_search_element.find(".directorist-viewas .directorist-viewas__item.active").attr('href');
    var view_as = view_href && view_href.length ? view_href.match(/view=.+/) : '';
    var view = view_as && view_as.length ? view_as[0].replace(/view=/, '') : '';
    var type_href = instant_search_element.find('.directorist-type-nav__list .directorist-type-nav__list__current a').attr('href');
    var type = type_href && type_href.length ? type_href.match(/directory_type=.+/) : '';
    var directory_type = getURLParameter(type_href, 'directory_type');
    var data_atts = instant_search_element.attr('data-atts');
    var data = {
      action: 'directorist_instant_search',
      _nonce: directorist.ajax_nonce,
      current_page_id: directorist.current_page_id,
      data_atts: JSON.parse(data_atts)
    };
    var form_data = _objectSpread({}, data);
    if (view && view.length) {
      form_data.view = view;
    }
    if (directory_type && directory_type.length) {
      form_data.directory_type = directory_type;
    }
    update_instant_search_url(form_data);
    $.ajax({
      url: directorist.ajaxurl,
      type: "POST",
      data: form_data,
      beforeSend: function beforeSend() {
        instant_search_element.find('.directorist-advanced-filter__form .directorist-btn-sm').attr("disabled", true);
        instant_search_element.find('.directorist-archive-items').addClass('atbdp-form-fade');
        instant_search_element.find('.directorist-header-bar .directorist-advanced-filter').removeClass('directorist-advanced-filter--show');
        instant_search_element.find('.directorist-header-bar .directorist-advanced-filter').hide();
        $(document).scrollTop(instant_search_element.offset().top);
      },
      success: function success(html) {
        if (html.search_result) {
          instant_search_element.find('.directorist-header-found-title span').text(html.count);
          instant_search_element.find('.directorist-archive-items').replaceWith(html.search_result);
          instant_search_element.find('.directorist-archive-items').removeClass('atbdp-form-fade');
          instant_search_element.find('.directorist-advanced-filter__form .directorist-btn-sm').attr("disabled", false);
          window.dispatchEvent(new CustomEvent('directorist-instant-search-reloaded'));
          window.dispatchEvent(new CustomEvent('directorist-reload-listings-map-archive'));
        }
      }
    });
  });
  $('body').on("submit", ".widget .default-ad-search:not(.directorist_single) .directorist-advanced-filter__form", function (e) {
    if ($('.directorist-instant-search').length) {
      e.preventDefault();
      var _this = $(this);
      var tag = [];
      var price = [];
      var search_by_rating = [];
      var custom_field = {};
      $(this).find('input[name^="in_tag["]:checked').each(function (index, el) {
        tag.push($(el).val());
      });
      $(this).find('input[name^="search_by_rating["]:checked').each(function (index, el) {
        search_by_rating.push($(el).val());
      });
      $(this).find('input[name^="price["]').each(function (index, el) {
        price.push($(el).val());
      });
      $(this).find('[name^="custom_field"]').each(function (index, el) {
        var name = $(el).attr('name');
        var type = $(el).attr('type');
        var post_id = name.replace(/(custom_field\[)/, '').replace(/\]/, '');
        if ('radio' === type) {
          $.each($("input[name='custom_field[" + post_id + "]']:checked"), function () {
            value = $(this).val();
            custom_field[post_id] = value;
          });
        } else if ('checkbox' === type) {
          post_id = post_id.split('[]')[0];
          if (!custom_field[post_id]) {
            custom_field[post_id] = [];
          }
          $.each($("input[name='custom_field[" + post_id + "][]']:checked"), function () {
            var value = $(this).val();
            custom_field[post_id].push(value);
          });
        } else {
          var value = $(el).val();
          custom_field[post_id] = value;
        }
      });
      var view_href = $(".directorist-viewas .directorist-viewas__item.active").attr('href');
      var view_as = view_href && view_href.length ? view_href.match(/view=.+/) : '';
      var view = view_as && view_as.length ? view_as[0].replace(/view=/, '') : '';
      var type_href = $('.directorist-type-nav__list .directorist-type-nav__list__current a').attr('href');
      var type = type_href && type_href.length ? type_href.match(/directory_type=.+/) : '';
      var directory_type = getURLParameter(type_href, 'directory_type');
      var data_atts = $(this).closest('.directorist-instant-search').attr('data-atts');
      var data = {
        action: 'directorist_instant_search',
        _nonce: directorist.ajax_nonce,
        current_page_id: directorist.current_page_id,
        in_tag: tag,
        price: price,
        search_by_rating: search_by_rating,
        custom_field: custom_field,
        data_atts: JSON.parse(data_atts)
      };
      var fields = {
        q: $(this).find('input[name="q"]').val(),
        in_cat: $(this).find('.directorist-category-select').val(),
        in_loc: $(this).find('.directorist-location-select').val(),
        price_range: $(this).find("input[name='price_range']:checked").val(),
        address: $(this).find('input[name="address"]').val(),
        zip: $(this).find('input[name="zip"]').val(),
        fax: $(this).find('input[name="fax"]').val(),
        email: $(this).find('input[name="email"]').val(),
        website: $(this).find('input[name="website"]').val(),
        phone: $(this).find('input[name="phone"]').val()
      };
      if ($('input[name="open_now"]').is(':checked')) {
        fields.open_now = $(this).find('input[name="open_now"]').val();
      }
      if (fields.address && fields.address.length) {
        fields.cityLat = $(this).find('#cityLat').val();
        fields.cityLng = $(this).find('#cityLng').val();
        fields.miles = $(this).find('input[name="miles"]').val();
      }
      if (fields.zip && fields.zip.length) {
        fields.zip_cityLat = $(this).find('.zip-cityLat').val();
        fields.zip_cityLng = $(this).find('.zip-cityLng').val();
        fields.miles = $(this).find('input[name="miles"]').val();
      }
      if (fields.address && fields.address.length) {
        fields.cityLat = $(this).find('#cityLat').val();
        fields.cityLng = $(this).find('#cityLng').val();
        fields.miles = $(this).find('input[name="miles"]').val();
      }
      if (fields.zip && fields.zip.length) {
        fields.zip_cityLat = $(this).find('.zip-cityLat').val();
        fields.zip_cityLng = $(this).find('.zip-cityLng').val();
        fields.miles = $(this).find('.directorist-custom-range-slider__value input').val();
      }
      var form_data = _objectSpread(_objectSpread({}, data), fields);
      var allFieldsAreEmpty = Object.values(fields).every(function (item) {
        return !item;
      });
      var tagFieldEmpty = data.in_tag.every(function (item) {
        return !item;
      });
      var priceFieldEmpty = data.price.every(function (item) {
        return !item;
      });
      var ratingFieldEmpty = data.search_by_rating.every(function (item) {
        return !item;
      });
      var customFieldsAreEmpty = Object.values(data.custom_field).every(function (item) {
        return !item;
      });
      if (!allFieldsAreEmpty || !tagFieldEmpty || !priceFieldEmpty || !customFieldsAreEmpty || !ratingFieldEmpty) {
        if (view && view.length) {
          form_data.view = view;
        }
        if (directory_type && directory_type.length) {
          form_data.directory_type = directory_type;
        }
        update_instant_search_url(form_data);
        $.ajax({
          url: directorist.ajaxurl,
          type: "POST",
          data: form_data,
          beforeSend: function beforeSend() {
            $('.directorist-archive-contents').find('.directorist-archive-items').addClass('atbdp-form-fade');
            $('.directorist-archive-contents').find('.directorist-header-bar .directorist-advanced-filter').removeClass('directorist-advanced-filter--show');
            $('.directorist-archive-contents').find('.directorist-header-bar .directorist-advanced-filter').hide();
            $(document).scrollTop($(".directorist-archive-contents").offset().top);
          },
          success: function success(html) {
            if (html.search_result) {
              $('.directorist-archive-contents').find('.directorist-header-found-title span').text(html.count);
              $('.directorist-archive-contents').find('.directorist-archive-items').replaceWith(html.search_result);
              $('.directorist-archive-contents').find('.directorist-archive-items').removeClass('atbdp-form-fade');
              $('.directorist-archive-contents').find('.directorist-advanced-filter__form .directorist-btn-sm').attr("disabled", false);
              window.dispatchEvent(new CustomEvent('directorist-instant-search-reloaded'));
              window.dispatchEvent(new CustomEvent('directorist-reload-listings-map-archive'));
            }
          }
        });
      }
    }
  });

  // Directorist type changes
  $('body').on("click", ".directorist-instant-search .directorist-type-nav__link", function (e) {
    e.preventDefault();
    // infinite pagination loading reset
    page = 1;
    infinitePaginationIsLoading = false;
    infinitePaginationCompleted = false;
    var _this = $(this);
    var type_href = $(this).attr('href');
    var type = type_href.match(/directory_type=.+/);
    //let directory_type = ( type && type.length ) ? type[0].replace( /directory_type=/, '' ) : '';
    var directory_type = getURLParameter(type_href, 'directory_type');
    var data_atts = $(this).closest('.directorist-instant-search').attr('data-atts');
    var form_data = {
      action: 'directorist_instant_search',
      _nonce: directorist.ajax_nonce,
      current_page_id: directorist.current_page_id,
      directory_type: directory_type,
      data_atts: JSON.parse(data_atts)
    };
    update_instant_search_url(form_data);
    $.ajax({
      url: directorist.ajaxurl,
      type: "POST",
      data: form_data,
      beforeSend: function beforeSend() {
        $(_this).closest('.directorist-instant-search').addClass('atbdp-form-fade');
      },
      success: function success(html) {
        if (html.directory_type) {
          $(_this).closest('.directorist-instant-search').replaceWith(html.directory_type);
          $(_this).closest('.directorist-instant-search').find('.atbdp-form-fade').removeClass('atbdp-form-fade');
          window.dispatchEvent(new CustomEvent('directorist-instant-search-reloaded'));
          window.dispatchEvent(new CustomEvent('directorist-reload-listings-map-archive'));

          // SearchForm Item in Single Category Location Page Init
          singleCategoryLocationInit();
        }
        var events = [new CustomEvent('directorist-instant-search-reloaded'), new CustomEvent('directorist-search-form-nav-tab-reloaded'), new CustomEvent('directorist-reload-select2-fields'), new CustomEvent('directorist-reload-map-api-field')];
        events.forEach(function (event) {
          document.body.dispatchEvent(event);
          window.dispatchEvent(event);
        });
      }
    });
  });
  $('body').on("click", ".disabled-link", function (e) {
    e.preventDefault();
  });

  // Directorist view as changes
  $('body').on("click", ".directorist-instant-search .directorist-viewas .directorist-viewas__item", function (e) {
    e.preventDefault();
    // infinite pagination loading reset
    page = 1;
    infinitePaginationIsLoading = false;
    infinitePaginationCompleted = false;
    var instant_search_element = $(this).closest('.directorist-instant-search');
    var tag = [];
    var price = [];
    var custom_field = {};
    var sort_href = $(this).closest(".directorist-sortby-dropdown .directorist-dropdown__links__single.active").attr('data-link');
    var sort_by = sort_href && sort_href.length ? sort_href.match(/sort=.+/) : '';
    var sort = sort_by && sort_by.length ? sort_by[0].replace(/sort=/, '') : '';
    var view_href = $(this).closest(this).attr('href');
    var view = view_href && view_href.length ? view_href.match(/view=.+/) : '';
    var type_href = instant_search_element.find('.directorist-type-nav__list .directorist-type-nav__list__current a').attr('href');
    var type = type_href && type_href.length ? type_href.match(/directory_type=.+/) : '';
    var directory_type = getURLParameter(type_href, 'directory_type');
    var page_no = $(this).closest(".page-numbers.current").text();
    var data_atts = instant_search_element.attr('data-atts');

    // Select Active Form Based on Screen Size
    var advancedForm = instant_search_element.find('.directorist-advanced-filter__form');
    var searchForm = instant_search_element.find('.directorist-search-form');
    var sidebarListing = instant_search_element.find('.listing-with-sidebar');
    var activeForm = sidebarListing.length ? instant_search_element : screen.width > 575 ? advancedForm : searchForm;

    // Get Values from Active Form
    activeForm.find('input[name^="in_tag["]:checked').each(function (index, el) {
      tag.push($(el).val());
    });
    activeForm.find('input[name^="price["]').each(function (index, el) {
      price.push($(el).val());
    });
    activeForm.find('[name^="custom_field"]').each(function (index, el) {
      var name = $(el).attr('name');
      var type = $(el).attr('type');
      var post_id = name.replace(/(custom_field\[)/, '').replace(/\]/, '');
      if ('radio' === type) {
        $.each($("input[name='custom_field[" + post_id + "]']:checked"), function () {
          value = $(this).val();
          custom_field[post_id] = value;
        });
      } else if ('checkbox' === type) {
        post_id = post_id.split('[]')[0];
        if (!custom_field[post_id]) {
          custom_field[post_id] = [];
        }
        $.each($("input[name='custom_field[" + post_id + "][]']:checked"), function () {
          var value = $(this).val();
          custom_field[post_id].push(value);
        });
      } else {
        var value = $(el).val();
        custom_field[post_id] = value;
      }
    });
    var q = activeForm.find('input[name="q"]').val();
    var in_cat = activeForm.find('.directorist-category-select').val();
    var in_loc = activeForm.find('.directorist-location-select').val();
    var price_range = activeForm.find("input[name='price_range']:checked").val();
    var search_by_rating = activeForm.find('select[name=search_by_rating]').val();
    var cityLat = activeForm.find('#cityLat').val();
    var cityLng = activeForm.find('#cityLng').val();
    var miles = activeForm.find('input[name="miles"]').val();
    var address = activeForm.find('input[name="address"]').val();
    var zip = activeForm.find('input[name="zip"]').val();
    var fax = activeForm.find('input[name="fax"]').val();
    var email = activeForm.find('input[name="email"]').val();
    var website = activeForm.find('input[name="website"]').val();
    var phone = activeForm.find('input[name="phone"]').val();

    // Required fields Check
    var isQueryRequired = activeForm.find('input[name="q"]').prop("required");
    var isCategoryRequired = activeForm.find('.directorist-category-select').prop("required");
    var isLocationRequired = activeForm.find('.directorist-location-select').prop("required");

    // Validate: If a field is required but empty, return false
    var requiredFieldsAreValid = true;
    if (isQueryRequired && !q) requiredFieldsAreValid = false;
    if (isCategoryRequired && (!in_cat || in_cat.length === 0)) requiredFieldsAreValid = false;
    if (isLocationRequired && (!in_loc || in_loc.length === 0)) requiredFieldsAreValid = false;
    $(".directorist-viewas .directorist-viewas__item").removeClass('active');
    $(this).addClass("active");
    var form_data = {
      action: 'directorist_instant_search',
      _nonce: directorist.ajax_nonce,
      current_page_id: directorist.current_page_id,
      view: view && view.length ? view[0].replace(/view=/, '') : '',
      q: requiredFieldsAreValid && q || getURLParameter(full_url, 'q'),
      in_cat: requiredFieldsAreValid && in_cat || getURLParameter(full_url, 'in_cat'),
      in_loc: requiredFieldsAreValid && in_loc || getURLParameter(full_url, 'in_loc'),
      in_tag: requiredFieldsAreValid && tag || getURLParameter(full_url, 'in_tag'),
      price: requiredFieldsAreValid && price || getURLParameter(full_url, 'price'),
      price_range: requiredFieldsAreValid && price_range || getURLParameter(full_url, 'price_range'),
      search_by_rating: requiredFieldsAreValid && search_by_rating || getURLParameter(full_url, 'search_by_rating'),
      cityLat: requiredFieldsAreValid && cityLat || getURLParameter(full_url, 'cityLat'),
      cityLng: requiredFieldsAreValid && cityLng || getURLParameter(full_url, 'cityLng'),
      miles: requiredFieldsAreValid && miles || getURLParameter(full_url, 'miles'),
      address: requiredFieldsAreValid && address || getURLParameter(full_url, 'address'),
      zip: requiredFieldsAreValid && zip || getURLParameter(full_url, 'zip'),
      fax: requiredFieldsAreValid && fax || getURLParameter(full_url, 'fax'),
      email: requiredFieldsAreValid && email || getURLParameter(full_url, 'email'),
      website: requiredFieldsAreValid && website || getURLParameter(full_url, 'website'),
      phone: requiredFieldsAreValid && phone || getURLParameter(full_url, 'phone'),
      custom_field: custom_field || getURLParameter(full_url, 'custom_field'),
      data_atts: JSON.parse(data_atts)
    };

    //business hours
    if ($('input[name="open_now"]').is(':checked')) {
      form_data.open_now = activeForm.find('input[name="open_now"]').val();
    }
    if (form_data.address && form_data.address.length) {
      form_data.cityLat = activeForm.find('#cityLat').val();
      form_data.cityLng = activeForm.find('#cityLng').val();
      form_data.miles = activeForm.find('input[name="miles"]').val();
    }
    if (form_data.zip && form_data.zip.length) {
      form_data.zip_cityLat = activeForm.find('.zip-cityLat').val();
      form_data.zip_cityLng = activeForm.find('.zip-cityLng').val();
      form_data.miles = activeForm.find('input[name="miles"]').val();
    }
    if (page_no && page_no.length) {
      form_data.paged = page_no;
    }
    if (directory_type && directory_type.length) {
      form_data.directory_type = directory_type;
    }
    if (sort && sort.length) {
      form_data.sort = sort;
    }
    $.ajax({
      url: directorist.ajaxurl,
      type: "POST",
      data: form_data,
      beforeSend: function beforeSend() {
        instant_search_element.find('.directorist-archive-items').addClass('atbdp-form-fade');
        instant_search_element.find('.directorist-viewas-dropdown .directorist-dropdown__links__single').addClass("disabled-link");
        instant_search_element.find('.directorist-dropdown__links-js a').removeClass('directorist-dropdown__links__single');
        instant_search_element.find('.directorist-archive-items').addClass('atbdp-form-fade');
        instant_search_element.find('.directorist-dropdown__links').hide();
        instant_search_element.find('.directorist-header-bar .directorist-advanced-filter').removeClass('directorist-advanced-filter--show');
        instant_search_element.find('.directorist-header-bar .directorist-advanced-filter').css('visibility', 'hidden');
        //$(document).scrollTop( $(this).closest(".directorist-instant-search").offset().top );
      },
      success: function success(html) {
        if (html.view_as) {
          instant_search_element.find('.directorist-header-found-title span').text(html.count);
          instant_search_element.find('.directorist-archive-items').replaceWith(html.view_as);
          instant_search_element.find('.directorist-archive-items').removeClass('atbdp-form-fade');
          instant_search_element.find('.directorist-viewas-dropdown .directorist-dropdown__links__single').removeClass("disabled-link");
          instant_search_element.find('.directorist-dropdown__links-js a').addClass('directorist-dropdown__links__single');
          window.dispatchEvent(new CustomEvent('directorist-instant-search-reloaded'));
          window.dispatchEvent(new CustomEvent('directorist-reload-listings-map-archive'));
          instant_search_element.find('.directorist-header-bar .directorist-advanced-filter').css('visibility', 'visible');
        }
      }
    });
  });
  $('.directorist-instant-search .directorist-dropdown__links__single-js').off('click');

  // Directorist sort by changes
  $('body').on("click", ".directorist-instant-search .directorist-sortby-dropdown .directorist-dropdown__links__single-js", function (e) {
    e.preventDefault();
    // infinite pagination loading reset
    page = 1;
    infinitePaginationIsLoading = false;
    infinitePaginationCompleted = false;
    var instant_search_element = $(this).closest('.directorist-instant-search');
    var tag = [];
    var price = [];
    var custom_field = {};
    var view_href = instant_search_element.find(".directorist-viewas .directorist-viewas__item.active").attr('href');
    var view_as = view_href && view_href.length ? view_href.match(/view=.+/) : '';
    var view = view_as && view_as.length ? view_as[0].replace(/view=/, '') : '';
    var sort_href = $(this).closest(this).attr('data-link');
    var sort_by = sort_href.match(/sort=.+/);
    var type_href = instant_search_element.find('.directorist-type-nav__list .directorist-type-nav__list__current a').attr('href');
    var type = type_href && type_href.length ? type_href.match(/directory_type=.+/) : '';
    var directory_type = getURLParameter(type_href, 'directory_type');
    var data_atts = instant_search_element.attr('data-atts');
    instant_search_element.find(".directorist-sortby-dropdown .directorist-dropdown__links__single").removeClass('active');
    $(this).addClass("active");

    // Select Active Form Based on Screen Size
    var advancedForm = instant_search_element.find('.directorist-advanced-filter__form');
    var searchForm = instant_search_element.find('.directorist-search-form');
    var sidebarListing = instant_search_element.find('.listing-with-sidebar');
    var activeForm = sidebarListing.length ? instant_search_element : screen.width > 575 ? advancedForm : searchForm;

    // Get Values from Active Form
    activeForm.find('input[name^="in_tag["]:checked').each(function (index, el) {
      tag.push($(el).val());
    });
    activeForm.find('input[name^="price["]').each(function (index, el) {
      price.push($(el).val());
    });
    activeForm.find('[name^="custom_field"]').each(function (index, el) {
      var name = $(el).attr('name');
      var type = $(el).attr('type');
      var post_id = name.replace(/(custom_field\[)/, '').replace(/\]/, '');
      if ('radio' === type) {
        $.each($("input[name='custom_field[" + post_id + "]']:checked"), function () {
          value = $(this).val();
          custom_field[post_id] = value;
        });
      } else if ('checkbox' === type) {
        post_id = post_id.split('[]')[0];
        if (!custom_field[post_id]) {
          custom_field[post_id] = [];
        }
        $.each($("input[name='custom_field[" + post_id + "][]']:checked"), function () {
          var value = $(this).val();
          custom_field[post_id].push(value);
        });
      } else {
        var value = $(el).val();
        custom_field[post_id] = value;
      }
    });
    var q = activeForm.find('input[name="q"]').val();
    var in_cat = activeForm.find('.directorist-category-select').val();
    var in_loc = activeForm.find('.directorist-location-select').val();
    var price_range = activeForm.find("input[name='price_range']:checked").val();
    var search_by_rating = activeForm.find('select[name=search_by_rating]').val();
    var cityLat = activeForm.find('#cityLat').val();
    var cityLng = activeForm.find('#cityLng').val();
    var miles = activeForm.find('input[name="miles"]').val();
    var address = activeForm.find('input[name="address"]').val();
    var zip = activeForm.find('input[name="zip"]').val();
    var fax = activeForm.find('input[name="fax"]').val();
    var email = activeForm.find('input[name="email"]').val();
    var website = activeForm.find('input[name="website"]').val();
    var phone = activeForm.find('input[name="phone"]').val();
    var form_data = {
      action: 'directorist_instant_search',
      _nonce: directorist.ajax_nonce,
      current_page_id: directorist.current_page_id,
      sort: sort_by && sort_by.length ? sort_by[0].replace(/sort=/, '') : '',
      q: q || getURLParameter(full_url, 'q'),
      in_cat: in_cat || getURLParameter(full_url, 'in_cat'),
      in_loc: in_loc || getURLParameter(full_url, 'in_loc'),
      in_tag: tag || getURLParameter(full_url, 'in_tag'),
      price: price || getURLParameter(full_url, 'price'),
      price_range: price_range || getURLParameter(full_url, 'price_range'),
      search_by_rating: search_by_rating || getURLParameter(full_url, 'search_by_rating'),
      cityLat: cityLat || getURLParameter(full_url, 'cityLat'),
      cityLng: cityLng || getURLParameter(full_url, 'cityLng'),
      miles: miles || getURLParameter(full_url, 'miles'),
      address: address || getURLParameter(full_url, 'address'),
      zip: zip || getURLParameter(full_url, 'zip'),
      fax: fax || getURLParameter(full_url, 'fax'),
      email: email || getURLParameter(full_url, 'email'),
      website: website || getURLParameter(full_url, 'website'),
      phone: phone || getURLParameter(full_url, 'phone'),
      custom_field: custom_field || getURLParameter(full_url, 'custom_field'),
      view: view,
      data_atts: JSON.parse(data_atts)
    };

    //business hours
    if ($('input[name="open_now"]').is(':checked')) {
      form_data.open_now = activeForm.find('input[name="open_now"]').val();
    }
    if (form_data.address && form_data.address.length) {
      form_data.cityLat = activeForm.find('#cityLat').val();
      form_data.cityLng = activeForm.find('#cityLng').val();
      form_data.miles = activeForm.find('input[name="miles"]').val();
    }
    if (form_data.zip && form_data.zip.length) {
      form_data.zip_cityLat = activeForm.find('.zip-cityLat').val();
      form_data.zip_cityLng = activeForm.find('.zip-cityLng').val();
      form_data.miles = activeForm.find('input[name="miles"]').val();
    }
    if (directory_type && directory_type.length) {
      form_data.directory_type = directory_type;
    }
    $.ajax({
      url: directorist.ajaxurl,
      type: "POST",
      data: form_data,
      beforeSend: function beforeSend() {
        instant_search_element.find('.directorist-sortby-dropdown .directorist-dropdown__links__single-js').addClass("disabled-link");
        instant_search_element.find('.directorist-dropdown__links-js a').removeClass('directorist-dropdown__links__single-js');
        instant_search_element.find('.directorist-archive-items').addClass('atbdp-form-fade');
        instant_search_element.find('.directorist-dropdown__links').hide();
        var advance_filter = instant_search_element.find('.directorist-header-bar .directorist-advanced-filter')[0];
        $(advance_filter).removeClass('directorist-advanced-filter--show');
        $(advance_filter).hide();
        $(document).scrollTop(instant_search_element.offset().top);
      },
      success: function success(html) {
        if (html.view_as) {
          instant_search_element.find('.directorist-header-found-title span').text(html.count);
          instant_search_element.find('.directorist-archive-items').replaceWith(html.view_as);
          instant_search_element.find('.directorist-archive-items').removeClass('atbdp-form-fade');
          instant_search_element.find('.directorist-sortby-dropdown .directorist-dropdown__links__single-js').removeClass("disabled-link");
          instant_search_element.find('.directorist-dropdown__links-js a').addClass('directorist-dropdown__links__single-js');
        }
        window.dispatchEvent(new CustomEvent('directorist-instant-search-reloaded'));
        window.dispatchEvent(new CustomEvent('directorist-reload-listings-map-archive'));
      }
    });
  });

  // Directorist pagination
  $('body').on("click", ".directorist-instant-search .directorist-pagination .page-numbers", function (e) {
    e.preventDefault();
    var tag = [];
    var price = [];
    var custom_field = {};
    var $container = $(this).closest('.directorist-instant-search');
    var $directory_nav = $container.find('.directorist-type-nav__list');
    var sort_href = $container.find(".directorist-sortby-dropdown .directorist-dropdown__links__single.active").attr('data-link');
    var sort_by = sort_href && sort_href.length ? sort_href.match(/sort=.+/) : '';
    var sort = sort_by && sort_by.length ? sort_by[0].replace(/sort=/, '') : '';
    var view_href = $container.find(".directorist-viewas .directorist-viewas__item.active").attr('href');
    var view_as = view_href && view_href.length ? view_href.match(/view=.+/) : '';
    var view = view_as && view_as.length ? view_as[0].replace(/view=/, '') : '';
    var type_href = $directory_nav.find('.directorist-type-nav__list__current a').attr('href');
    var type = type_href && type_href.length ? type_href.match(/directory_type=.+/) : '';
    var directory_type = getURLParameter(type_href, 'directory_type');
    var data_atts = $container.attr('data-atts');

    // Select Active Form Based on Screen Size
    var advancedForm = $container.find('.directorist-advanced-filter__form');
    var searchForm = $container.find('.directorist-search-form');
    var sidebarListing = $container.find('.listing-with-sidebar');
    var activeForm = sidebarListing.length ? $container : screen.width > 575 ? advancedForm : searchForm;

    // Get Values from Active Form
    activeForm.find('input[name^="in_tag["]:checked').each(function (index, el) {
      tag.push($(el).val());
    });
    activeForm.find('input[name^="price["]').each(function (index, el) {
      price.push($(el).val());
    });
    activeForm.find('[name^="custom_field"]').each(function (index, el) {
      var name = $(el).attr('name');
      var type = $(el).attr('type');
      var post_id = name.replace(/(custom_field\[)/, '').replace(/\]/, '');
      if ('radio' === type) {
        $.each($("input[name='custom_field[" + post_id + "]']:checked"), function () {
          value = $(this).val();
          custom_field[post_id] = value;
        });
      } else if ('checkbox' === type) {
        post_id = post_id.split('[]')[0];
        if (!custom_field[post_id]) {
          custom_field[post_id] = [];
        }
        $.each($("input[name='custom_field[" + post_id + "][]']:checked"), function () {
          var value = $(this).val();
          custom_field[post_id].push(value);
        });
      } else {
        var value = $(el).val();
        custom_field[post_id] = value;
      }
    });
    var q = activeForm.find('input[name="q"]').val();
    var in_cat = activeForm.find('.directorist-category-select').val();
    var in_loc = activeForm.find('.directorist-location-select').val();
    var price_range = activeForm.find("input[name='price_range']:checked").val();
    var search_by_rating = activeForm.find('select[name=search_by_rating]').val();
    var cityLat = activeForm.find('#cityLat').val();
    var cityLng = activeForm.find('#cityLng').val();
    var address = activeForm.find('input[name="address"]').val();
    var zip = activeForm.find('input[name="zip"]').val();
    var miles = (address || zip) && activeForm.find('input[name="miles"]').val();
    var fax = activeForm.find('input[name="fax"]').val();
    var email = activeForm.find('input[name="email"]').val();
    var website = activeForm.find('input[name="website"]').val();
    var phone = activeForm.find('input[name="phone"]').val();
    $container.find(".directorist-pagination .page-numbers").removeClass('current');
    $(this).addClass("current");
    var paginate_link = $(this).attr('href');
    var page_no = '';
    if (paginate_link) {
      var pageMatch = paginate_link.match(/(?:page\/|paged=)(\d+)/);
      if (pageMatch) {
        page_no = pageMatch[1]; // Extracts only the numeric value
      }
    }
    console.log(page_no);
    var form_data = {
      action: 'directorist_instant_search',
      _nonce: directorist.ajax_nonce,
      current_page_id: directorist.current_page_id,
      q: q,
      in_cat: in_cat,
      in_loc: in_loc,
      in_tag: tag,
      price: price,
      price_range: price_range,
      search_by_rating: search_by_rating,
      cityLat: cityLat,
      cityLng: cityLng,
      address: address,
      zip: zip,
      fax: fax,
      email: email,
      website: website,
      phone: phone,
      custom_field: custom_field,
      miles: miles,
      view: view,
      paged: page_no,
      data_atts: JSON.parse(data_atts)
    };

    //business hours
    if ($('input[name="open_now"]').is(':checked')) {
      form_data.open_now = activeForm.find('input[name="open_now"]').val();
    }
    if (form_data.address && form_data.address.length) {
      form_data.cityLat = activeForm.find('#cityLat').val();
      form_data.cityLng = activeForm.find('#cityLng').val();
      form_data.miles = activeForm.find('input[name="miles"]').val();
    }
    if (form_data.zip && form_data.zip.length) {
      form_data.zip_cityLat = activeForm.find('.zip-cityLat').val();
      form_data.zip_cityLng = activeForm.find('.zip-cityLng').val();
      form_data.miles = activeForm.find('input[name="miles"]').val();
    }
    if (directory_type && directory_type.length) {
      form_data.directory_type = directory_type;
    }
    if (sort && sort.length) {
      form_data.sort = sort;
    }
    if ($directory_nav.is(':hidden')) {
      form_data.directory_nav = false;
    }
    update_instant_search_url(form_data);
    $.ajax({
      url: directorist.ajaxurl,
      type: "POST",
      data: form_data,
      beforeSend: function beforeSend() {
        $container.find('.directorist-archive-items').addClass('atbdp-form-fade');
      },
      success: function success(html) {
        if (html.view_as) {
          $container.find('.directorist-header-found-title span').text(html.count);
          $container.find('.directorist-archive-items').replaceWith(html.view_as);
          $container.find('.directorist-archive-items').removeClass('atbdp-form-fade');
          $(document).scrollTop($container.offset().top);
        }
        window.dispatchEvent(new CustomEvent('directorist-instant-search-reloaded'));
        window.dispatchEvent(new CustomEvent('directorist-reload-listings-map-archive'));
      }
    });
  });

  // Helper function to determine the active form
  function getActiveForm(instantSearchElement) {
    var sidebarListing = instantSearchElement.find('.listing-with-sidebar');
    var advancedForm = instantSearchElement.find('.directorist-advanced-filter__form');
    var searchForm = instantSearchElement.find('.directorist-search-form');
    return sidebarListing.length ? instantSearchElement : screen.width > 575 ? advancedForm : searchForm;
  }

  // Helper function to build form data
  function buildFormData(activeForm, instantSearchElement) {
    var tag = [];
    var price = [];
    var customField = {};
    var dataAtts = JSON.parse(instantSearchElement.attr('data-atts'));
    activeForm.find('input[name^="in_tag["]:checked').each(function (_, el) {
      return tag.push($(el).val());
    });
    activeForm.find('input[name^="price["]').each(function (_, el) {
      return price.push($(el).val());
    });
    activeForm.find('[name^="custom_field"]').each(function (_, el) {
      var name = $(el).attr('name');
      var type = $(el).attr('type');
      var postId = name.replace(/(custom_field\[)/, '').replace(/\]/, '').split('[]')[0];
      if (type === 'radio') {
        customField[postId] = activeForm.find("input[name='custom_field[".concat(postId, "]']:checked")).val();
      } else if (type === 'checkbox') {
        customField[postId] = activeForm.find("input[name='custom_field[".concat(postId, "][]']:checked")).map(function (_, e) {
          return $(e).val();
        }).get();
      } else {
        customField[postId] = $(el).val();
      }
    });
    var view_href = $(".directorist-viewas .directorist-viewas__item.active").attr('href');
    var view_as = view_href && view_href.length ? view_href.match(/view=.+/) : '';
    var view = view_as && view_as.length ? view_as[0].replace(/view=/, '') : '';
    var getValue = function getValue(selector, fallback) {
      return activeForm.find(selector).val() || fallback;
    };
    return {
      action: 'directorist_instant_search',
      _nonce: directorist.ajax_nonce,
      current_page_id: directorist.current_page_id,
      q: getValue('input[name="q"]', getURLParameter(full_url, 'q')),
      in_cat: getValue('.directorist-category-select', getURLParameter(full_url, 'in_cat')),
      in_loc: getValue('.directorist-location-select', getURLParameter(full_url, 'in_loc')),
      in_tag: tag || getURLParameter(full_url, 'in_tag'),
      price: price || getURLParameter(full_url, 'price'),
      price_range: getValue("input[name='price_range']:checked", getURLParameter(full_url, 'price_range')),
      search_by_rating: getValue('select[name=search_by_rating]', getURLParameter(full_url, 'search_by_rating')),
      cityLat: getValue('#cityLat', getURLParameter(full_url, 'cityLat')),
      cityLng: getValue('#cityLng', getURLParameter(full_url, 'cityLng')),
      miles: getValue('input[name="miles"]', getURLParameter(full_url, 'miles')),
      address: getValue('input[name="address"]', getURLParameter(full_url, 'address')),
      zip: getValue('input[name="zip"]', getURLParameter(full_url, 'zip')),
      fax: getValue('input[name="fax"]', getURLParameter(full_url, 'fax')),
      email: getValue('input[name="email"]', getURLParameter(full_url, 'email')),
      website: getValue('input[name="website"]', getURLParameter(full_url, 'website')),
      phone: getValue('input[name="phone"]', getURLParameter(full_url, 'phone')),
      custom_field: customField,
      view: view,
      paged: page,
      data_atts: dataAtts,
      sort: getSortValue(instantSearchElement),
      directory_type: getDirectoryType(instantSearchElement),
      open_now: activeForm.find('input[name="open_now"]:checked').val()
    };
  }

  // Helper function to get sort value
  function getSortValue(instantSearchElement) {
    var sortHref = instantSearchElement.find('.directorist-sortby-dropdown .directorist-dropdown__links__single.active').data('link');
    return sortHref ? sortHref.split('sort=')[1] : '';
  }

  // Helper function to get directory type
  function getDirectoryType(instantSearchElement) {
    var typeHref = instantSearchElement.find('.directorist-type-nav__list .directorist-type-nav__list__current a').attr('href');
    return typeHref ? getURLParameter(typeHref, 'directory_type') : '';
  }

  // AJAX call to load more listings
  function loadMoreListings(formData) {
    var loadingDiv;
    var container = $('.directorist-infinite-scroll .directorist-container-fluid .directorist-row');
    $.ajax({
      url: directorist.ajaxurl,
      type: 'POST',
      data: formData,
      beforeSend: function beforeSend() {
        loadingDiv = $('<div>', {
          class: 'directorist-on-scroll-loading'
        }).append($('<div>', {
          class: 'directorist-spinner'
        }), $('<span>').text('Loading more...'));
        container.append(loadingDiv);
      },
      success: function success(html) {
        if (loadingDiv) loadingDiv.remove();
        if (html.count > 0) {
          container.append(html.render_listings);
        } else {
          infinitePaginationCompleted = true;
        }
        triggerCustomEvents();
      },
      complete: function complete() {
        infinitePaginationIsLoading = false;
        if (loadingDiv) loadingDiv.remove();
      }
    });
  }

  // Helper function to trigger custom events
  function triggerCustomEvents() {
    window.dispatchEvent(new Event('directorist-instant-search-reloaded'));
    window.dispatchEvent(new Event('directorist-reload-listings-map-archive'));
  }

  // Filter on AJAX Search
  function filterListing(searchElm) {
    if (!searchElm) {
      return;
    }

    // infinite pagination loading reset
    page = 1;
    infinitePaginationIsLoading = false;
    infinitePaginationCompleted = false;
    var _this = searchElm;
    var tag = [];
    var price = [];
    var search_by_rating = [];
    var custom_field = {};
    searchElm.find('input[name^="in_tag[]"]:checked').each(function (index, el) {
      tag.push($(el).val());
    });
    searchElm.find('input[name^="search_by_rating[]"]:checked').each(function (index, el) {
      search_by_rating.push($(el).val());
    });
    searchElm.find('input[name^="price["]').each(function (index, el) {
      price.push($(el).val());
    });
    searchElm.find('[name^="custom_field"]').each(function (index, el) {
      var name = $(el).attr('name');
      var type = $(el).attr('type');
      var post_id = name.replace(/(custom_field\[)/, '').replace(/\]/, '');
      if ('radio' === type) {
        $.each($("input[name='custom_field[" + post_id + "]']:checked"), function () {
          value = $(this).val();
          ;
          custom_field[post_id] = value;
        });
      } else if ('checkbox' === type) {
        post_id = post_id.split('[]')[0];
        if (!custom_field[post_id]) {
          custom_field[post_id] = [];
        }
        $.each($("input[name='custom_field[" + post_id + "][]']:checked"), function () {
          var value = $(this).val();
          custom_field[post_id].push(value);
        });
      } else {
        var value = $(el).val();
        custom_field[post_id] = value;
      }
    });
    var view_href = $(".directorist-viewas .directorist-viewas__item.active").attr('href');
    var view_as = view_href && view_href.length ? view_href.match(/view=.+/) : '';
    var view = view_as && view_as.length ? view_as[0].replace(/view=/, '') : '';
    var type_href = $('.directorist-type-nav__list .directorist-type-nav__list__current a').attr('href');
    var type = type_href && type_href.length ? type_href.match(/directory_type=.+/) : '';
    var directory_type = getURLParameter(type_href, 'directory_type');
    var data_atts = $('.directorist-instant-search').attr('data-atts');
    var data = {
      action: 'directorist_instant_search',
      _nonce: directorist.ajax_nonce,
      current_page_id: directorist.current_page_id,
      in_tag: tag,
      price: price,
      search_by_rating: search_by_rating,
      custom_field: custom_field,
      data_atts: JSON.parse(data_atts)
    };
    var fields = {
      q: searchElm.find('input[name="q"]').val(),
      in_cat: searchElm.find('.directorist-category-select').val(),
      in_loc: searchElm.find('.directorist-location-select').val(),
      price_range: searchElm.find("input[name='price_range']:checked").val(),
      address: searchElm.find('input[name="address"]').val(),
      zip: searchElm.find('input[name="zip"]').val(),
      fax: searchElm.find('input[name="fax"]').val(),
      email: searchElm.find('input[name="email"]').val(),
      website: searchElm.find('input[name="website"]').val(),
      phone: searchElm.find('input[name="phone"]').val()
    };

    //business hours
    if ($('input[name="open_now"]').is(':checked')) {
      fields.open_now = searchElm.find('input[name="open_now"]').val();
    }
    if (fields.address && fields.address.length) {
      fields.cityLat = searchElm.find('#cityLat').val();
      fields.cityLng = searchElm.find('#cityLng').val();
      fields.miles = searchElm.find('input[name="miles"]').val();
    }
    if (fields.zip && fields.zip.length) {
      fields.zip_cityLat = searchElm.find('.zip-cityLat').val();
      fields.zip_cityLng = searchElm.find('.zip-cityLng').val();
      fields.miles = searchElm.find('input[name="miles"]').val();
    }
    var form_data = _objectSpread(_objectSpread({}, data), fields);
    if (view && view.length) {
      form_data.view = view;
    }
    if (directory_type && directory_type.length) {
      form_data.directory_type = directory_type;
    }
    update_instant_search_url(form_data);
    $.ajax({
      url: directorist.ajaxurl,
      type: "POST",
      data: form_data,
      beforeSend: function beforeSend() {
        $(_this).closest('.directorist-instant-search').find('.directorist-advanced-filter__form .directorist-btn-sm').attr("disabled", true);
        $(_this).closest('.directorist-instant-search').find('.directorist-archive-items').addClass('atbdp-form-fade');
        $(_this).closest('.directorist-instant-search').find('.directorist-header-bar .directorist-advanced-filter').removeClass('directorist-advanced-filter--show');
        $(_this).closest('.directorist-instant-search').find('.directorist-header-bar .directorist-advanced-filter').hide();
        if ($(".directorist-instant-search").offset() > 0) {
          $(document).scrollTop($(_this).closest(".directorist-instant-search").offset().top);
        }
      },
      success: function success(html) {
        if (html.search_result) {
          $(_this).closest('.directorist-instant-search').find('.directorist-header-found-title').remove();
          $(_this).closest('.directorist-instant-search').find('.dsa-save-search-container').remove();
          if (String(html.header_title)) {
            $(_this).closest('.directorist-instant-search').find('.directorist-listings-header__left').append(html.header_title);
            $(_this).closest('.directorist-instant-search').find('.directorist-header-found-title span').text(html.count);
          }
          $(_this).closest('.directorist-instant-search').find('.directorist-archive-items').replaceWith(html.search_result);
          $(_this).closest('.directorist-instant-search').find('.directorist-archive-items').removeClass('atbdp-form-fade');
          $(_this).closest('.directorist-instant-search').find('.directorist-advanced-filter__form .directorist-btn-sm').attr("disabled", false);
          window.dispatchEvent(new CustomEvent('directorist-instant-search-reloaded'));
          window.dispatchEvent(new CustomEvent('directorist-reload-listings-map-archive'));
          var website_name = directorist.site_name; // This is dynamically set from WordPress

          // Construct the new meta title
          var new_meta_title = ''; // Start with an empty title
          // Check if the category is selected and append to the title
          if (String(html.category_name)) {
            new_meta_title += html.category_name;
          }

          // Check if location is selected and append with proper formatting
          if (String(html.location_name)) {
            if (String(html.category_name)) {
              new_meta_title += ' within ' + html.location_name; // If category exists, add with a comma
            } else {
              new_meta_title += html.location_name; // If no category, just add location
            }
          }

          // Check if address is selected and append with proper formatting
          if (fields.address) {
            if (fields.in_cat || fields.in_loc) {
              new_meta_title += ' near ' + fields.address; // If category or location exists, add "near"
            } else {
              new_meta_title += fields.address; // Default to just the address
            }
          }

          // Append website name to the meta title with a pipe separator
          if (new_meta_title) {
            new_meta_title += ' | ' + website_name; // Append the website name only if the title has content
          } else {
            new_meta_title = website_name; // Default to only the website name if no other title parts are present
          }

          // Update the meta title dynamically
          document.title = new_meta_title;
        }
      }
    });
  }

  // Range Slider searching observer
  function initObserver() {
    var targetNodes = document.querySelectorAll('.directorist-instant-search .directorist-custom-range-slider__value input');
    targetNodes.forEach(function (targetNode) {
      var searchElm = $(targetNode.closest('form'));
      if (targetNode) {
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
                  filterListing(searchElm);
                }, 250);
              }
            }
          } catch (err) {
            _iterator.e(err);
          } finally {
            _iterator.f();
          }
        };
        var observer = new MutationObserver(observerCallback);
        observer.observe(targetNode, {
          attributes: true,
          childList: true,
          subtree: true
        });
      }
    });
  }

  // Single Location Category Page Search Form Item Disable
  function singleCategoryLocationInit() {
    var directoristArchiveContents = document.querySelector('.directorist-archive-contents');
    if (!directoristArchiveContents) {
      return;
    }
    var directoristDataAttributes = directoristArchiveContents.getAttribute('data-atts');
    var _JSON$parse = JSON.parse(directoristDataAttributes),
      shortcode = _JSON$parse.shortcode,
      location = _JSON$parse.location,
      category = _JSON$parse.category;
    if (shortcode === 'directorist_category' && category.trim() !== '') {
      var categorySelect = document.querySelector('.directorist-search-form .directorist-category-select');
      if (categorySelect) {
        categorySelect.closest('.directorist-search-category').classList.add('directorist-search-form__single-category');
      }
    }
    if (shortcode === 'directorist_location' && location.trim() !== '') {
      var locationSelect = document.querySelector('.directorist-search-form .directorist-location-select');
      if (locationSelect) {
        locationSelect.closest('.directorist-search-location').classList.add('directorist-search-form__single-location');
      }
    }
  }

  // sidebar on keyup searching
  $('body').on("keyup", ".directorist-instant-search .listing-with-sidebar form", (0,_global_components_debounce__WEBPACK_IMPORTED_MODULE_1__["default"])(function (e) {
    if ($(e.target).closest('.directorist-custom-range-slider__value').length > 0) {
      return; // Skip calling `filterListing` for this element
    }
    e.preventDefault();
    var searchElm = $(this).closest('.listing-with-sidebar');
    filterListing(searchElm);
  }, 250));

  // sidebar on change searching
  $('body').on("change", ".directorist-instant-search .listing-with-sidebar input[type='checkbox'],.directorist-instant-search .listing-with-sidebar input[type='radio'], .directorist-custom-range-slider__wrap .directorist-custom-range-slider__range, .directorist-search-location .location-name", (0,_global_components_debounce__WEBPACK_IMPORTED_MODULE_1__["default"])(function (e) {
    e.preventDefault();
    var searchElm = $(this).closest('.listing-with-sidebar');
    filterListing(searchElm);
  }, 250));

  // sidebar on change location, zipcode changing
  $('body').on("change", ".directorist-instant-search .listing-with-sidebar .directorist-search-location, .directorist-instant-search .listing-with-sidebar .directorist-zipcode-search", (0,_global_components_debounce__WEBPACK_IMPORTED_MODULE_1__["default"])(function (e) {
    e.preventDefault();
    var searchElm = $(this).closest('.listing-with-sidebar');

    // If it's a location field, ensure it has a value before triggering the filter
    if ($(this).hasClass('directorist-search-location')) {
      var locationField = $(this).find('input[name="address"]');
      if (!locationField.val()) {
        return;
      }
    }
    filterListing(searchElm);
  }, 250));

  // select on change with value - searching
  $('body').on("change", ".directorist-instant-search .listing-with-sidebar select", (0,_global_components_debounce__WEBPACK_IMPORTED_MODULE_1__["default"])(function (e) {
    e.preventDefault();
    var searchElm = $(this).val() && $(this).closest('.listing-with-sidebar');
    filterListing(searchElm);
  }, 250));

  // select on change with value - searching
  $('body').on("click", ".directorist-instant-search .listing-with-sidebar .directorist-filter-location-icon", (0,_global_components_debounce__WEBPACK_IMPORTED_MODULE_1__["default"])(function (e) {
    e.preventDefault();
    var searchElm = $(this).closest('.listing-with-sidebar');
    filterListing(searchElm);
  }, 1000));

  // Clear Input Value
  $('body').on("click", ".directorist-instant-search .directorist-search-field__btn--clear", function (e) {
    var inputValue = $(this).closest('.directorist-search-field').find('input:not([type="checkbox"]):not([type="radio"]), select').val('');
    if (inputValue) {
      var searchElm = $(document.querySelector('.directorist-instant-search .listing-with-sidebar form'));
      if (searchElm) {
        filterListing(searchElm);
      }
    }
  });
  if ($('.directorist-instant-search').length === 0) {
    $('body').on("submit", ".listing-with-sidebar .directorist-basic-search, .listing-with-sidebar .directorist-advanced-search", function (e) {
      e.preventDefault();
      var basic_data = $('.listing-with-sidebar .directorist-basic-search').serialize();
      var advanced_data = $('.listing-with-sidebar .directorist-advanced-search').serialize();
      var action_value = $('.directorist-advanced-search').attr('action');
      var url = action_value + '?' + basic_data + '&' + advanced_data;
      window.location.href = url;
    });
  }
  window.addEventListener('load', function () {
    (0,_global_components_debounce__WEBPACK_IMPORTED_MODULE_1__["default"])(initObserver(), 250);
    singleCategoryLocationInit();
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/legacy-support.js":
/*!***********************************************************!*\
  !*** ./assets/src/js/public/components/legacy-support.js ***!
  \***********************************************************/
/***/ (function() {

window.addEventListener('load', function () {
  /* custom dropdown */
  var atbdDropdown = document.querySelectorAll('.atbd-dropdown');

  // toggle dropdown
  var clickCount = 0;
  if (atbdDropdown !== null) {
    atbdDropdown.forEach(function (el) {
      el.querySelector('.atbd-dropdown-toggle').addEventListener('click', function (e) {
        e.preventDefault();
        clickCount++;
        if (clickCount % 2 === 1) {
          document.querySelectorAll('.atbd-dropdown-items').forEach(function (el) {
            el.classList.remove('atbd-show');
          });
          el.querySelector('.atbd-dropdown-items').classList.add('atbd-show');
        } else {
          document.querySelectorAll('.atbd-dropdown-items').forEach(function (el) {
            el.classList.remove('atbd-show');
          });
        }
      });
    });
  }

  // remvoe toggle when click outside
  document.body.addEventListener('click', function (e) {
    if (e.target.getAttribute('data-drop-toggle') !== 'atbd-toggle') {
      clickCount = 0;
      document.querySelectorAll('.atbd-dropdown-items').forEach(function (el) {
        el.classList.remove('atbd-show');
      });
    }
  });
});

/***/ }),

/***/ "./assets/src/js/public/components/masonry.js":
/*!****************************************************!*\
  !*** ./assets/src/js/public/components/masonry.js ***!
  \****************************************************/
/***/ (function() {

// DOM Mutation observer
function initObserver() {
  var targetNode = document.querySelector('.directorist-archive-contents');
  var observer = new MutationObserver(initMasonry);
  if (targetNode) {
    observer.observe(targetNode, {
      childList: true
    });
  }
}

// All listings Masonry layout
function initMasonry() {
  var $ = jQuery;
  function authorsMasonry(selector) {
    var authorsCard = $(selector);
    $(authorsCard).each(function (id, elm) {
      var authorsCardRow = $(elm).find('.directorist-masonry');
      var authorMasonryInit = $(authorsCardRow).imagesLoaded(function () {
        $(authorMasonryInit).masonry({
          percentPosition: true,
          horizontalOrder: true
        });
      });
    });
  }
  authorsMasonry('.directorist-archive-grid-view');
}
window.addEventListener('load', initObserver);
window.addEventListener('load', initMasonry);

/***/ }),

/***/ "./assets/src/js/public/components/review.js":
/*!***************************************************!*\
  !*** ./assets/src/js/public/components/review.js ***!
  \***************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _review_starRating__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./review/starRating */ "./assets/src/js/public/components/review/starRating.js");
/* harmony import */ var _review_starRating__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_review_starRating__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _review_advanced_review__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./review/advanced-review */ "./assets/src/js/public/components/review/advanced-review.js");
// Helper Components

// import './review/addReview'
// import './review/reviewAttatchment'
// import './review/deleteReview'
// import './review/reviewPagination'


/***/ }),

/***/ "./assets/src/js/public/components/review/advanced-review.js":
/*!*******************************************************************!*\
  !*** ./assets/src/js/public/components/review/advanced-review.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");


function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t.return || t.return(); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
window.addEventListener('load', function () {
  ;
  (function ($) {
    'use strict';

    var ReplyFormObserver = /*#__PURE__*/function () {
      function ReplyFormObserver() {
        var _this = this;
        (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, ReplyFormObserver);
        this.init();
        $(document).on('directorist_review_updated', function () {
          return _this.init();
        });
      }
      return (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(ReplyFormObserver, [{
        key: "init",
        value: function init() {
          var node = document.querySelector('.commentlist');
          if (node) {
            this.observe(node);
          }
        }
      }, {
        key: "observe",
        value: function observe(node) {
          var config = {
            childList: true,
            subtree: true
          };
          var observer = new MutationObserver(this.callback);
          observer.observe(node, config);
        }
      }, {
        key: "callback",
        value: function callback(mutationsList, observer) {
          var _iterator = _createForOfIteratorHelper(mutationsList),
            _step;
          try {
            for (_iterator.s(); !(_step = _iterator.n()).done;) {
              var mutation = _step.value;
              var target = mutation.target;
              if (mutation.removedNodes) {
                target.classList.remove('directorist-form-added');
                var _iterator2 = _createForOfIteratorHelper(mutation.removedNodes),
                  _step2;
                try {
                  for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
                    var node = _step2.value;
                    if (!node.id || node.id !== 'respond') {
                      continue;
                    }
                    var criteria = node.querySelector('.directorist-review-criteria');
                    if (criteria) {
                      criteria.style.display = '';
                    }
                    var ratings = node.querySelectorAll('.directorist-review-criteria-select');
                    var _iterator3 = _createForOfIteratorHelper(ratings),
                      _step3;
                    try {
                      for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
                        var rating = _step3.value;
                        rating.removeAttribute('disabled');
                      }
                    } catch (err) {
                      _iterator3.e(err);
                    } finally {
                      _iterator3.f();
                    }
                    node.querySelector('#submit').innerHTML = 'Submit Review';
                    node.querySelector('#comment').setAttribute('placeholder', 'Leave a review');
                    //console.log(node.querySelector('#comment'))
                  }
                } catch (err) {
                  _iterator2.e(err);
                } finally {
                  _iterator2.f();
                }
              }
              var form = target.querySelector('#commentform');
              if (form) {
                target.classList.add('directorist-form-added');
                var isReview = target.classList.contains('review');
                var isEditing = target.classList.contains('directorist-form-editing');
                if (!isReview || isReview && !isEditing) {
                  var _criteria = form.querySelector('.directorist-review-criteria');
                  if (_criteria) {
                    _criteria.style.display = 'none';
                  }
                  var _ratings = form.querySelectorAll('.directorist-review-criteria-select');
                  var _iterator4 = _createForOfIteratorHelper(_ratings),
                    _step4;
                  try {
                    for (_iterator4.s(); !(_step4 = _iterator4.n()).done;) {
                      var _rating = _step4.value;
                      _rating.setAttribute('disabled', 'disabled');
                    }
                  } catch (err) {
                    _iterator4.e(err);
                  } finally {
                    _iterator4.f();
                  }
                }
                var alert = form.querySelector('.directorist-alert');
                if (alert) {
                  alert.style.display = 'none';
                }
                form.querySelector('#submit').innerHTML = 'Submit Reply';
                form.querySelector('#comment').setAttribute('placeholder', 'Leave your reply');
              }
            }
          } catch (err) {
            _iterator.e(err);
          } finally {
            _iterator.f();
          }
        }
      }]);
    }();
    var CommentEditHandler = /*#__PURE__*/function () {
      function CommentEditHandler() {
        (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, CommentEditHandler);
        this.init();
      }
      return (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(CommentEditHandler, [{
        key: "init",
        value: function init() {
          $(document).on('submit', '#directorist-form-comment-edit', this.onSubmit);
        }
      }, {
        key: "onSubmit",
        value: function onSubmit(event) {
          event.preventDefault();
          var $form = $(event.target);
          var originalButtonLabel = $form.find('[type="submit"]').val();
          $(document).trigger('directorist_review_before_submit', $form);
          var formData = new FormData($form[0]);

          // Apply the filter
          formData = wp.hooks.applyFilters('directorist_add_review_form_data', formData, 'directorist-advanced-review');
          var updateComment = $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            contentType: false,
            cache: false,
            processData: false,
            data: formData
          });
          $form.find('#comment').prop('disabled', true);
          $form.find('[type="submit"]').prop('disabled', true).val('loading');
          var commentID = $form.find('input[name="comment_id"]').val();
          var $wrap = $('#div-comment-' + commentID);
          $wrap.addClass('directorist-comment-edit-request');
          updateComment.done(function (data, status, request) {
            if (typeof data !== 'string' && !data.success) {
              $wrap.removeClass('directorist-comment-edit-request');
              CommentEditHandler.showError($form, data.data.html);
              return;
            }
            var body = $('<div></div>');
            body.append(data);
            var comment_section = '.directorist-review-container';
            var comments = body.find(comment_section);
            $(comment_section).replaceWith(comments);
            $(document).trigger('directorist_review_updated', data);
            var commentTop = $("#comment-" + commentID).offset().top;
            if ($('body').hasClass('admin-bar')) {
              commentTop = commentTop - $('#wpadminbar').height();
            }

            // scroll to comment
            if (commentID) {
              $("body, html").animate({
                scrollTop: commentTop
              }, 600);
            }
          });
          updateComment.fail(function (data) {
            CommentEditHandler.showError($form, data.responseText);
          });
          updateComment.always(function () {
            $form.find('#comment').prop('disabled', false);
            $form.find('[type="submit"]').prop('disabled', false).val(originalButtonLabel);
          });
          $(document).trigger('directorist_review_after_submit', $form);
        }
      }], [{
        key: "showError",
        value: function showError($form, msg) {
          $form.find('.directorist-alert').remove();
          $form.prepend(msg);
        }
      }]);
    }();
    var CommentAddReplyHandler = /*#__PURE__*/function () {
      function CommentAddReplyHandler() {
        (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, CommentAddReplyHandler);
        this.init();
      }
      return (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(CommentAddReplyHandler, [{
        key: "init",
        value: function init() {
          var t = setTimeout(function () {
            if ($('.directorist-review-container').length) {
              $(document).off('submit', '#commentform');
            }
            clearTimeout(t);
          }, 2000);
          $(document).off('submit', '.directorist-review-container #commentform');
          $(document).on('submit', '.directorist-review-container #commentform', this.onSubmit);
        }
      }, {
        key: "onSubmit",
        value: function onSubmit(event) {
          var _this2 = this;
          event.preventDefault();
          console.log(wp.hooks);
          var form = $('.directorist-review-container #commentform');
          var originalButtonLabel = form.find('[type="submit"]').val();
          $(document).trigger('directorist_review_before_submit', form);
          var formData = new FormData(form[0]);

          // Apply the filter
          formData = wp.hooks.applyFilters('directorist_add_review_form_data', formData, 'directorist-advanced-review');
          var do_comment = $.ajax({
            url: form.attr('action'),
            type: 'POST',
            contentType: false,
            cache: false,
            processData: false,
            data: formData
          });
          $('#comment').prop('disabled', true);
          form.find('[type="submit"]').prop('disabled', true).val('loading');
          do_comment.done(function (data, status, request) {
            var body = $('<div></div>');
            body.append(data);
            var comment_section = '.directorist-review-container';
            var comments = body.find(comment_section);
            var errorMsg = body.find('.wp-die-message');
            if (errorMsg.length > 0) {
              CommentAddReplyHandler.showError(form, errorMsg);
              $(document).trigger('directorist_review_update_failed');
              return;
            }
            $(comment_section).replaceWith(comments);
            $(document).trigger('directorist_review_updated', data);
            var newComment = comments.find('.commentlist li:first-child');
            var newCommentId = newComment.attr('id');

            // // catch the new comment id by comparing to old dom.
            // commentsLists.each(
            //     function ( index ) {
            //         var _this = $( commentsLists[ index ] );
            //         if ( $( '#' + _this.attr( 'id' ) ).length == 0 ) {
            //             newCommentId = _this.attr( 'id' );
            //         }
            //     }
            // );

            // console.log(newComment, newCommentId)

            var commentTop = $("#" + newCommentId).offset().top;
            if ($('body').hasClass('admin-bar')) {
              commentTop = commentTop - $('#wpadminbar').height();
            }

            // scroll to comment
            if (newCommentId) {
              $('body, html').animate({
                scrollTop: commentTop
              }, 600);
            }
          });
          do_comment.fail(function (data) {
            var body = $('<div></div>');
            body.append(data.responseText);
            console.log(data);
            CommentAddReplyHandler.showError(form, body.find('.wp-die-message'));
            $(document).trigger('directorist_review_update_failed');
            if (data.status === 403 || data.status === 401) {
              $(document).off('submit', '.directorist-review-container #commentform', _this2.onSubmit);
              $('#comment').prop('disabled', false);
              form.find('[type="submit"]').prop('disabled', false).click();
            }
          });
          do_comment.always(function () {
            $('#comment').prop('disabled', false);
            $('#commentform').find('[type="submit"]').prop('disabled', false).val(originalButtonLabel);
          });
          $(document).trigger('directorist_review_after_submit', form);
        }
      }], [{
        key: "getErrorMsg",
        value: function getErrorMsg($dom) {
          if ($dom.find('p').length) {
            $dom = $dom.find('p');
          }
          var words = $dom.text().split(':');
          if (words.length > 1) {
            words.shift();
          }
          return words.join(' ').trim();
        }
      }, {
        key: "showError",
        value: function showError(form, $dom) {
          if (form.find('.directorist-alert').length) {
            form.find('.directorist-alert').remove();
          }
          var $error = $('<div />', {
            class: 'directorist-alert directorist-alert-danger'
          }).html(CommentAddReplyHandler.getErrorMsg($dom));
          form.prepend($error);
        }
      }]);
    }();
    var CommentsManager = /*#__PURE__*/function () {
      function CommentsManager() {
        (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, CommentsManager);
        this.$doc = $(document);
        this.setupComponents();
        this.addEventListeners();
      }
      return (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(CommentsManager, [{
        key: "initStarRating",
        value: function initStarRating() {
          $('.directorist-review-criteria-select').barrating({
            theme: 'fontawesome-stars'
          });
        }
      }, {
        key: "cancelOthersEditMode",
        value: function cancelOthersEditMode(currentCommentId) {
          $('.directorist-comment-editing').each(function (index, comment) {
            var $cancelButton = $(comment).find('.directorist-js-cancel-comment-edit');
            if ($cancelButton.data('commentid') != currentCommentId) {
              $cancelButton.click();
            }
          });
        }
      }, {
        key: "cancelReplyMode",
        value: function cancelReplyMode() {
          var replyLink = document.querySelector('.directorist-review-content #cancel-comment-reply-link');
          replyLink && replyLink.click();
        }
      }, {
        key: "addEventListeners",
        value: function addEventListeners() {
          var _this3 = this;
          var self = this;
          this.$doc.on('directorist_review_updated', function (event) {
            _this3.initStarRating();
          });
          this.$doc.on('directorist_comment_edit_form_loaded', function (event) {
            _this3.initStarRating();
          });
          this.$doc.on('click', 'a[href="#respond"]', function (event) {
            // First cancle the reply form then scroll to review form. Order matters.
            _this3.cancelReplyMode();
            _this3.onWriteReivewClick(event);
          });
          this.$doc.on('click', '.directorist-js-edit-comment', function (event) {
            event.preventDefault();
            var $target = $(event.target);
            var $wrap = $target.parents('#div-comment-' + $target.data('commentid'));
            $wrap.addClass('directorist-comment-edit-request');
            $.ajax({
              url: $target.attr('href'),
              data: {
                post_id: $target.data('postid'),
                comment_id: $target.data('commentid')
              },
              setContent: false,
              method: 'GET',
              reload: 'strict',
              success: function success(response) {
                $target.prop('disabled', true);
                $target.parents('#div-comment-' + $target.data('commentid')).find('.directorist-review-single__info').append(response.data.html);
                $wrap.removeClass('directorist-comment-edit-request').addClass('directorist-comment-editing');
                self.cancelOthersEditMode($target.data('commentid'));
                self.cancelReplyMode();
                var $editForm = $('#directorist-form-comment-edit');
                $editForm.find('textarea').focus();
                self.$doc.trigger('directorist_comment_edit_form_loaded', $target.data('commentid'));
              }
            });
          });
          this.$doc.on('click', '.directorist-js-cancel-comment-edit', function (event) {
            event.preventDefault();
            var $target = $(event.target);
            var $wrap = $target.parents('#div-comment-' + $target.data('commentid'));
            $wrap.removeClass(['directorist-comment-edit-request', 'directorist-comment-editing']).find('form').remove();
            $wrap.find('.directorist-js-edit-comment').prop('disabled', false);
          });
        }
      }, {
        key: "onWriteReivewClick",
        value: function onWriteReivewClick(event) {
          event.preventDefault();
          var scrollTop = $('#respond').offset().top;
          if ($('body').hasClass('admin-bar')) {
            scrollTop = scrollTop - $('#wpadminbar').height();
          }
          $('body, html').animate({
            scrollTop: scrollTop
          }, 600);
        }
      }, {
        key: "setupComponents",
        value: function setupComponents() {
          new ReplyFormObserver();
          new CommentAddReplyHandler();
          new CommentEditHandler();
        }
      }]);
    }();
    var commentsManager = new CommentsManager();
  })(jQuery);
});

/***/ }),

/***/ "./assets/src/js/public/components/review/starRating.js":
/*!**************************************************************!*\
  !*** ./assets/src/js/public/components/review/starRating.js ***!
  \**************************************************************/
/***/ (function() {

window.addEventListener('load', function () {
  ;
  (function ($) {
    //Star rating
    if ($('.directorist-review-criteria-select').length) {
      $('.directorist-review-criteria-select').barrating({
        theme: 'fontawesome-stars'
      });
    }
  })(jQuery);
});

/***/ }),

/***/ "./assets/src/js/public/components/update-view-count.js":
/*!**************************************************************!*\
  !*** ./assets/src/js/public/components/update-view-count.js ***!
  \**************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");


function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
/**
 * Update listings grid view count.
 */
jQuery(function ($) {
  var _window$directorist, _window$localStorage;
  var isDynamicViewCountCacheEnabled = Boolean((_window$directorist = window.directorist) === null || _window$directorist === void 0 ? void 0 : _window$directorist.dynamic_view_count_cache);
  if (!isDynamicViewCountCacheEnabled) {
    return;
  }
  var updateMarkup = function updateMarkup(viewCounts) {
    for (var _i = 0, _Object$entries = Object.entries(viewCounts); _i < _Object$entries.length; _i++) {
      var _Object$entries$_i = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_1__["default"])(_Object$entries[_i], 2),
        id = _Object$entries$_i[0],
        count = _Object$entries$_i[1];
      var $el = $(".directorist-view-count[data-id=\"".concat(id, "\"]"));
      var $elIcon = $el.find('.directorist-icon-mask');
      if ($elIcon.length) {
        $elIcon[0].nextSibling.textContent = count;
      } else {
        $el.text(count);
      }
    }
  };
  var ids = [];
  $('.directorist-view-count[data-id]').each(function (i, item) {
    ids.push(+item.dataset.id);
  });
  if (ids.length === 0) {
    return;
  }
  var CACHE_EXPIRATION = 1000 * 60 * 60 * 5; // 5 hours.
  var cache = (_window$localStorage = window.localStorage) === null || _window$localStorage === void 0 ? void 0 : _window$localStorage.getItem('directorist_view_count');
  var hasCache = false;
  if (cache) {
    var _cache, _cache2;
    cache = JSON.parse(cache);
    var cachedIds = ((_cache = cache) === null || _cache === void 0 ? void 0 : _cache.viewCount) || {};
    hasCache = Object.keys(cachedIds).length;
    ids = ids.filter(function (id) {
      return !(id in cachedIds);
    });
    if (hasCache && (_cache2 = cache) !== null && _cache2 !== void 0 && _cache2.lastUpdated && Date.now() - cache.lastUpdated < CACHE_EXPIRATION) {
      updateMarkup(cache.viewCount);
    }
    if (!ids.length) {
      return;
    }
  }
  $.post(directorist.ajax_url, {
    action: 'directorist_update_view_count',
    nonce: directorist.directorist_nonce,
    ids: ids
  }, function (response) {
    var _window$localStorage2;
    if (!response.success) {
      console.warn(response.data.message);
      return;
    }
    updateMarkup(response.data.view_count);
    if (hasCache) {
      response.data.view_count = _objectSpread(_objectSpread({}, cache.viewCount), response.data.view_count);
    }
    (_window$localStorage2 = window.localStorage) === null || _window$localStorage2 === void 0 || _window$localStorage2.setItem('directorist_view_count', JSON.stringify({
      lastUpdated: Date.now(),
      viewCount: response.data.view_count
    }));
  });
});

/***/ }),

/***/ "./assets/src/scss/layout/public/main-style.scss":
/*!*******************************************************!*\
  !*** ./assets/src/scss/layout/public/main-style.scss ***!
  \*******************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


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

/***/ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/classCallCheck.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _classCallCheck; }
/* harmony export */ });
function _classCallCheck(a, n) {
  if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function");
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/createClass.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/createClass.js ***!
  \****************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _createClass; }
/* harmony export */ });
/* harmony import */ var _toPropertyKey_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./toPropertyKey.js */ "./node_modules/@babel/runtime/helpers/esm/toPropertyKey.js");

function _defineProperties(e, r) {
  for (var t = 0; t < r.length; t++) {
    var o = r[t];
    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, (0,_toPropertyKey_js__WEBPACK_IMPORTED_MODULE_0__["default"])(o.key), o);
  }
}
function _createClass(e, r, t) {
  return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", {
    writable: !1
  }), e;
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
/*!******************************************************!*\
  !*** ./assets/src/js/public/modules/all-listings.js ***!
  \******************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _scss_layout_public_main_style_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../scss/layout/public/main-style.scss */ "./assets/src/scss/layout/public/main-style.scss");
/* harmony import */ var _components_general__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/general */ "./assets/src/js/public/components/general.js");
/* harmony import */ var _components_general__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_components_general__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _components_helpers__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../components/helpers */ "./assets/src/js/public/components/helpers.js");
/* harmony import */ var _components_review__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../components/review */ "./assets/src/js/public/components/review.js");
/* harmony import */ var _components_directoristSorting__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../components/directoristSorting */ "./assets/src/js/public/components/directoristSorting.js");
/* harmony import */ var _components_directoristSorting__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_components_directoristSorting__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _components_directoristAlert__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../components/directoristAlert */ "./assets/src/js/public/components/directoristAlert.js");
/* harmony import */ var _components_directoristAlert__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_components_directoristAlert__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _components_gridResponsive__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../components/gridResponsive */ "./assets/src/js/public/components/gridResponsive.js");
/* harmony import */ var _components_gridResponsive__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_components_gridResponsive__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _components_directoristFavorite__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../components/directoristFavorite */ "./assets/src/js/public/components/directoristFavorite.js");
/* harmony import */ var _components_directoristFavorite__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_components_directoristFavorite__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _components_directoristDropdown__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../components/directoristDropdown */ "./assets/src/js/public/components/directoristDropdown.js");
/* harmony import */ var _components_directoristDropdown__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_components_directoristDropdown__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var _components_directoristSelect__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../components/directoristSelect */ "./assets/src/js/public/components/directoristSelect.js");
/* harmony import */ var _components_directoristSelect__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_components_directoristSelect__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var _components_categoryLocation__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../components/categoryLocation */ "./assets/src/js/public/components/categoryLocation.js");
/* harmony import */ var _components_categoryLocation__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_components_categoryLocation__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var _components_colorPicker__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ../components/colorPicker */ "./assets/src/js/public/components/colorPicker.js");
/* harmony import */ var _components_colorPicker__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(_components_colorPicker__WEBPACK_IMPORTED_MODULE_11__);
/* harmony import */ var _components_legacy_support__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ../components/legacy-support */ "./assets/src/js/public/components/legacy-support.js");
/* harmony import */ var _components_legacy_support__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(_components_legacy_support__WEBPACK_IMPORTED_MODULE_12__);
/* harmony import */ var _components_masonry__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ../components/masonry */ "./assets/src/js/public/components/masonry.js");
/* harmony import */ var _components_masonry__WEBPACK_IMPORTED_MODULE_13___default = /*#__PURE__*/__webpack_require__.n(_components_masonry__WEBPACK_IMPORTED_MODULE_13__);
/* harmony import */ var _components_instantSearch__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ../components/instantSearch */ "./assets/src/js/public/components/instantSearch.js");
/* harmony import */ var _global_components_setup_select2__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ../../global/components/setup-select2 */ "./assets/src/js/global/components/setup-select2.js");
/* harmony import */ var _global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ../../global/components/select2-custom-control */ "./assets/src/js/global/components/select2-custom-control.js");
/* harmony import */ var _global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_16___default = /*#__PURE__*/__webpack_require__.n(_global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_16__);
/* harmony import */ var _components_archive_sidebar__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ../components/archive-sidebar */ "./assets/src/js/public/components/archive-sidebar.js");
/* harmony import */ var _components_archive_sidebar__WEBPACK_IMPORTED_MODULE_17___default = /*#__PURE__*/__webpack_require__.n(_components_archive_sidebar__WEBPACK_IMPORTED_MODULE_17__);
/* harmony import */ var _components_update_view_count__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! ../components/update-view-count */ "./assets/src/js/public/components/update-view-count.js");
/*
    File: all-listings.js
    Plugin: Directorist – Business Directory & Classified Listings WordPress Plugin
    Author: wpWax
    Author URI: www.wpwax.com
*/



// General Components


















}();
/******/ })()
;
//# sourceMappingURL=all-listings.js.map