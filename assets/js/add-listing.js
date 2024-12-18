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
/******/ 	return __webpack_require__(__webpack_require__.s = 16);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/global/add-listing.js":
/*!*********************************************!*\
  !*** ./assets/src/js/global/add-listing.js ***!
  \*********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _global_components_cache_category_custom_fields__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../global/components/cache-category-custom-fields */ "./assets/src/js/global/components/cache-category-custom-fields.js");
/* harmony import */ var _global_components_load_category_custom_fields__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../global/components/load-category-custom-fields */ "./assets/src/js/global/components/load-category-custom-fields.js");
/* harmony import */ var _global_components_setup_select2__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../global/components/setup-select2 */ "./assets/src/js/global/components/setup-select2.js");
/* harmony import */ var _public_components_colorPicker__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../public/components/colorPicker */ "./assets/src/js/public/components/colorPicker.js");
/* harmony import */ var _public_components_colorPicker__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_public_components_colorPicker__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _public_components_directoristDropdown__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../public/components/directoristDropdown */ "./assets/src/js/public/components/directoristDropdown.js");
/* harmony import */ var _public_components_directoristDropdown__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_public_components_directoristDropdown__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _public_components_directoristSelect__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../public/components/directoristSelect */ "./assets/src/js/public/components/directoristSelect.js");
/* harmony import */ var _public_components_directoristSelect__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_public_components_directoristSelect__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _components_debounce__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./components/debounce */ "./assets/src/js/global/components/debounce.js");
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
// General Components








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
    var categoryIds = [];
    var listingId = 0;
    var directoryId = 0;
    if (directorist.is_admin) {
      listingId = Number($('#post_ID').val());
      directoryId = $('select[name="directory_type"]', getWrapper()).val();
      if (!directoryId) {
        directoryId = $('input[name="directory_type"]', getWrapper()).val();
      }
      var $selectedCategories = $('#at_biz_dir-categorychecklist input:checked');
      if ($selectedCategories.length) {
        categoryIds = $selectedCategories.toArray().map(function (el) {
          return Number(el.value);
        });
      }
    } else {
      listingId = Number($('input[name="listing_id"]', getWrapper()).val());
      directoryId = $('input[name="directory_type"]', getWrapper()).val();
      var _$selectedCategories = $('#at_biz_dir-categories option:selected');
      if (_$selectedCategories.length) {
        categoryIds = _$selectedCategories.toArray().map(function (el) {
          return Number(el.value);
        });
      }
    }
    Object(_global_components_load_category_custom_fields__WEBPACK_IMPORTED_MODULE_1__["default"])({
      categoryIds: categoryIds,
      listingId: listingId,
      directoryId: directoryId,
      onBeforeSend: function onBeforeSend() {
        console.log('before send!');
      },
      onSuccess: function onSuccess(response) {
        if (!response.success) {
          $('.atbdp_category_custom_fields', getWrapper()).empty();
          $('.atbdp_category_custom_fields-wrapper', getWrapper()).hide();
          return;
        }
        $('.atbdp_category_custom_fields', getWrapper()).empty();
        $.each(response.data, function (fieldId, fieldMarkup) {
          var $newMarkup = $(fieldMarkup);
          if ($newMarkup.find('.directorist-form-element')[0] !== undefined) {
            $newMarkup.find('.directorist-form-element')[0].setAttribute('data-id', "".concat(fieldId));
          }
          if ($($newMarkup[0]).find('.directorist-radio input, .directorist-checkbox input').length) {
            $($newMarkup[0]).find('.directorist-radio input, .directorist-checkbox input').each(function (i, item) {
              $(item).attr('id', "directorist-cf-".concat(fieldId, "-").concat(i));
              $(item).attr('data-id', "directorist-cf-".concat(fieldId, "-").concat(i));
              $(item).addClass('directorist-form-checks');
            });
            $($newMarkup[0]).find('.directorist-radio label, .directorist-checkbox label').each(function (i, item) {
              $(item).attr('for', "directorist-cf-".concat(fieldId, "-").concat(i));
            });
          }
          $('.atbdp_category_custom_fields', getWrapper()).append($newMarkup);
        });
        $('.atbdp_category_custom_fields-wrapper', getWrapper()).show();
        customFieldSeeMore();
        var fieldsCache = Object(_global_components_cache_category_custom_fields__WEBPACK_IMPORTED_MODULE_0__["getCategoryCustomFieldsCache"])();
        Object.keys(fieldsCache).forEach(function (key) {
          var el = document.querySelector("[data-id=\"".concat(key, "\"]"));
          if (el === null) {
            return;
          }
          if (el.classList.contains('directorist-form-element')) {
            el.value = fieldsCache[key];
          } else {
            el.checked = fieldsCache[key];
          }
        });
        initColorField();
      }
    });
  }

  // Create container div after category (in frontend)
  $('.directorist-form-categories-field').after('<div class="directorist-form-group  atbdp_category_custom_fields"></div>');
  window.addEventListener('directorist-type-change', function () {
    renderCategoryCustomFields();
    Object(_global_components_cache_category_custom_fields__WEBPACK_IMPORTED_MODULE_0__["cacheCategoryCustomFields"])();
  });

  // Render category based fields on category change (frontend)
  $('#at_biz_dir-categories').on('change', Object(_components_debounce__WEBPACK_IMPORTED_MODULE_6__["default"])(function () {
    renderCategoryCustomFields();
    Object(_global_components_cache_category_custom_fields__WEBPACK_IMPORTED_MODULE_0__["cacheCategoryCustomFields"])();
  }, 270));

  // Render category based fields on category change (backend)
  $('#at_biz_dir-categorychecklist').on('change', Object(_components_debounce__WEBPACK_IMPORTED_MODULE_6__["default"])(function () {
    renderCategoryCustomFields();
    Object(_global_components_cache_category_custom_fields__WEBPACK_IMPORTED_MODULE_0__["cacheCategoryCustomFields"])();
  }, 270));

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
    var _iterator = _createForOfIteratorHelper(localized_data.media_uploader),
      _step;
    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var uploader = _step.value;
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
      _iterator.e(err);
    } finally {
      _iterator.f();
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
      for (var _i = 0, _mediaUploaders = mediaUploaders; _i < _mediaUploaders.length; _i++) {
        var uploader = _mediaUploaders[_i];
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
      var _iterator2 = _createForOfIteratorHelper(fieldValuePairs),
        _step2;
      try {
        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          var field = _step2.value;
          form_data.append(field.name, field.value);
        }

        // Upload existing image
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }
      if (mediaUploaders.length) {
        var _loop = function _loop() {
          var uploader = _mediaUploaders2[_i2];
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
        for (var _i2 = 0, _mediaUploaders2 = mediaUploaders; _i2 < _mediaUploaders2.length; _i2++) {
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
        beforeSend: function beforeSend() {
          disableSubmitButton();
          $notification.show().html("<span class=\"atbdp_success\">".concat(localized_data.i18n_text.submission_wait_msg, "</span>"));
        },
        success: function success(response) {
          var redirect_url = response && response.redirect_url ? response.redirect_url : '';
          redirect_url = redirect_url && typeof redirect_url === 'string' ? response.redirect_url.replace(/:\/\//g, '%3A%2F%2F') : '';
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
          regenerate_and_update_nonce();
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
    $(window).scroll(Object(_components_debounce__WEBPACK_IMPORTED_MODULE_6__["default"])(function () {
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
    navLinks.forEach(function (link) {
      if (link.getAttribute("href") === "#".concat(currentSection)) {
        link.classList.add("active");
      } else {
        link.classList.remove("active");
      }
    });
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
      // Scroll to an element with a custom scrollDuration of 1500ms
      smoothScroll(targetSection, 1250);
    });
  });
}

// Add Listing Accordion
function addListingAccordion() {
  $('body').on('click', '.directorist-add-listing-form .directorist-content-module__title', function (e) {
    e.preventDefault();
    var windowScreen = window.innerWidth;
    if (windowScreen <= 480) {
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
function regenerate_and_update_nonce() {
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

/***/ }),

/***/ "./assets/src/js/global/components/cache-category-custom-fields.js":
/*!*************************************************************************!*\
  !*** ./assets/src/js/global/components/cache-category-custom-fields.js ***!
  \*************************************************************************/
/*! exports provided: cacheCategoryCustomFields, getCategoryCustomFieldsCache */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "cacheCategoryCustomFields", function() { return cacheCategoryCustomFields; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getCategoryCustomFieldsCache", function() { return getCategoryCustomFieldsCache; });
var cache = {};
function cacheCategoryCustomFields() {
  var customFields = document.querySelectorAll('.atbdp_category_custom_fields .directorist-form-element');
  var checksField = document.querySelectorAll('.atbdp_category_custom_fields .directorist-form-checks');
  if (customFields.length) {
    customFields.forEach(function (el) {
      return cache[el.getAttribute('data-id')] = el.value;
    });
  }
  if (checksField.length) {
    checksField.forEach(function (el) {
      return cache[el.getAttribute('data-id')] = el.checked;
    });
  }
}
function getCategoryCustomFieldsCache() {
  return cache;
}

/***/ }),

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

/***/ "./assets/src/js/global/components/load-category-custom-fields.js":
/*!************************************************************************!*\
  !*** ./assets/src/js/global/components/load-category-custom-fields.js ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return loadCategoryCustomFields; });
function loadCategoryCustomFields() {
  var args = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {
    directoryId: null,
    listingId: null,
    categoryIds: null,
    onBeforeSending: null,
    onSuccess: null
  };
  var directory_id = args.directoryId,
    listing_id = args.listingId,
    category_ids = args.categoryIds,
    onBeforeSending = args.onBeforeSending,
    onSuccess = args.onSuccess;
  var payload = {
    action: 'directorist_load_category_custom_fields',
    directorist_nonce: directorist.directorist_nonce,
    listing_id: listing_id,
    category_ids: category_ids,
    directory_id: directory_id
  };
  return jQuery.ajax({
    method: 'POST',
    url: directorist.add_listing_data.ajaxurl,
    data: payload,
    beforeSend: onBeforeSending
  }).done(onSuccess);
}

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

/***/ 16:
/*!***************************************************!*\
  !*** multi ./assets/src/js/global/add-listing.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/global/add-listing.js */"./assets/src/js/global/add-listing.js");


/***/ })

/******/ });
//# sourceMappingURL=add-listing.js.map