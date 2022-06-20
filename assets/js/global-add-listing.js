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
/******/ 	return __webpack_require__(__webpack_require__.s = 7);
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
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _public_components_directoristDropdown__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../public/components/directoristDropdown */ "./assets/src/js/public/components/directoristDropdown.js");
/* harmony import */ var _public_components_directoristDropdown__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_public_components_directoristDropdown__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _public_components_directoristSelect__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../public/components/directoristSelect */ "./assets/src/js/public/components/directoristSelect.js");
/* harmony import */ var _public_components_directoristSelect__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_public_components_directoristSelect__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _public_components_colorPicker__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../public/components/colorPicker */ "./assets/src/js/public/components/colorPicker.js");
/* harmony import */ var _public_components_colorPicker__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_public_components_colorPicker__WEBPACK_IMPORTED_MODULE_3__);


function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

// General Components



/* eslint-disable */

var $ = jQuery;
var localized_data = directorist.add_listing_data;
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
/* Show and hide manual coordinate input field */


$(window).on('load', function () {
  if ($('input#manual_coordinate').length) {
    $('input#manual_coordinate').each(function (index, element) {
      if (!$(element).is(':checked')) {
        $('#hide_if_no_manual_cor').hide();
        $('.directorist-map-coordinates').hide();
      }
    });
  } //initialize color picker


  if ($('.directorist-color-field-js').length) {
    $('.directorist-color-field-js').wpColorPicker().empty();
  }
});
$(document).ready(function () {
  $('body').on("click", "#manual_coordinate", function (e) {
    if ($('input#manual_coordinate').is(':checked')) {
      $('.directorist-map-coordinates').show();
      $('#hide_if_no_manual_cor').show();
    } else {
      $('.directorist-map-coordinates').hide();
      $('#hide_if_no_manual_cor').hide();
    }
  }); // SOCIAL SECTION
  // Rearrange the IDS and Add new social field

  $('body').on('click', '#addNewSocial', function (e) {
    var social_wrap = $('#social_info_sortable_container'); // cache it

    var currentItems = $('.directorist-form-social-fields').length;
    var ID = "id=".concat(currentItems); // eg. 'id=3'

    var iconBindingElement = jQuery('#addNewSocial'); // arrange names ID in order before adding new elements

    $('.directorist-form-social-fields').each(function (index, element) {
      var e = $(element);
      e.attr('id', "socialID-".concat(index));
      e.find('select').attr('name', "social[".concat(index, "][id]"));
      e.find('.atbdp_social_input').attr('name', "social[".concat(index, "][url]"));
      e.find('.directorist-form-social-fields__remove').attr('data-id', index);
    }); // now add the new elements. we could do it here without using ajax but it would require more markup here.

    atbdp_do_ajax(iconBindingElement, 'atbdp_social_info_handler', ID, function (data) {
      social_wrap.append(data);
    });
  });
  document.addEventListener('directorist-reload-plupload', function () {
    if ($('.directorist-color-field-js').length) {
      $('.directorist-color-field-js').wpColorPicker().empty();
    }
  }); // remove the social field and then reset the ids while maintaining position

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
          elementToRemove.remove(); // reorder the index

          $('.directorist-form-social-fields').each(function (index, element) {
            var e = $(element);
            e.attr('id', "socialID-".concat(index));
            e.find('select').attr('name', "social[".concat(index, "][id]"));
            e.find('.atbdp_social_input').attr('name', "social[".concat(index, "][url]"));
            e.find('.directorist-form-social-fields__remove').attr('data-id', index);
          });
        }); // show success message

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
    if (arg && !ActionName) data = arg; // data = data ;

    var n = data.search(localized_data.nonceName);

    if (n < 0) {
      data = "".concat(data, "&").concat(localized_data.nonceName, "=").concat(localized_data.nonce);
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
  } // Select2 js code


  if (!localized_data.is_admin) {
    // Location
    var createLoc = $('#at_biz_dir-location').attr("data-allow_new");
    var maxLocationLength = $('#at_biz_dir-location').attr("data-max");

    if (createLoc) {
      $("#at_biz_dir-location").select2({
        tags: true,
        maximumSelectionLength: maxLocationLength,
        language: {
          maximumSelected: function maximumSelected() {
            return localized_data.i18n_text.max_location_msg;
          }
        },
        tokenSeparators: [","]
      });
    } else {
      $("#at_biz_dir-location").select2({
        allowClear: true,
        tags: false,
        maximumSelectionLength: maxLocationLength,
        tokenSeparators: [","]
      });
    } // Tags


    var createTag = $('#at_biz_dir-tags').attr("data-allow_new");
    var maxTagLength = $('#at_biz_dir-tags').attr("data-max");

    if (createTag) {
      $('#at_biz_dir-tags').select2({
        tags: true,
        maximumSelectionLength: maxTagLength,
        tokenSeparators: [',']
      });
    } else {
      $('#at_biz_dir-tags').select2({
        allowClear: true,
        maximumSelectionLength: maxTagLength,
        tokenSeparators: [',']
      });
    } //Category


    var createCat = $('#at_biz_dir-categories').attr("data-allow_new");
    var maxCatLength = $('#at_biz_dir-categories').attr("data-max");

    if (createCat) {
      $('#at_biz_dir-categories').select2({
        allowClear: true,
        tags: true,
        maximumSelectionLength: maxCatLength,
        tokenSeparators: [',']
      });
    } else {
      $('#at_biz_dir-categories').select2({
        maximumSelectionLength: maxCatLength,
        allowClear: true
      });
    }
  } // price range


  $('#price_range').hide();
  var is_checked = $('#atbd_listing_pricing').val();

  if (is_checked === 'range') {
    $('#price').hide();
    $('#price_range').show();
  }

  $('.directorist-form-pricing-field__options .directorist-checkbox__label').on('click', function () {
    var $this = $(this);

    if ($this.parent('.directorist-checkbox').children('input[type=checkbox]').prop('checked') === true) {
      $("#".concat($this.data('option'))).hide();
    } else {
      $("#".concat($this.data('option'))).show();
    }

    var $sibling = $this.parent().siblings('.directorist-checkbox');
    $sibling.children('input[type=checkbox]').prop('checked', false);
    $("#".concat($sibling.children('.directorist-checkbox__label').data('option'))).hide();
  });
  var has_tagline = $('#has_tagline').val();
  var has_excerpt = $('#has_excerpt').val();

  if (has_excerpt && has_tagline) {
    $('.atbd_tagline_moto_field').fadeIn();
  } else {
    $('.atbd_tagline_moto_field').fadeOut();
  }

  $('#atbd_optional_field_check').on('change', function () {
    $(this).is(':checked') ? $('.atbd_tagline_moto_field').fadeIn() : $('.atbd_tagline_moto_field').fadeOut();
  }); // it shows the hidden term and conditions

  $('#listing_t_c').on('click', function (e) {
    e.preventDefault();
    $('#tc_container').toggleClass('active');
  });
  $('.directorist-form-categories-field').after('<div class="atbdp_category_custom_fields"></div>'); // Load custom fields of the selected category in the custom post type "atbdp_listings"

  var qs = function (a) {
    if (a == '') return {};
    var b = {};

    for (var i = 0; i < a.length; ++i) {
      var p = a[i].split('=', 2);
      if (p.length == 1) b[p[0]] = '';else b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, ' '));
    }

    return b;
  }(window.location.search.substr(1).split('&'));

  $('#at_biz_dir-categories').on('change', function () {
    var directory_type = qs.directory_type ? qs.directory_type : $('input[name="directory_type"]').val();
    var length = $('#at_biz_dir-categories option:selected');
    var id = [];
    length.each(function (el, index) {
      id.push($(index).val());
    });
    var data = {
      action: 'atbdp_custom_fields_listings',
      post_id: $('input[name="listing_id"]').val(),
      term_id: id,
      directory_type: directory_type
    };
    $.post(localized_data.ajaxurl, data, function (response) {
      if (response) {
        $('.atbdp_category_custom_fields').empty().append(response);

        function atbdp_tooltip() {
          var atbd_tooltip = document.querySelectorAll('.atbd_tooltip');
          atbd_tooltip.forEach(function (el) {
            if (el.getAttribute('aria-label') !== " ") {
              document.body.addEventListener('mouseover', function (e) {
                for (var target = e.target; target && target != this; target = target.parentNode) {
                  if (target.matches('.atbd_tooltip')) {
                    el.classList.add('atbd_tooltip_active');
                  }
                }
              }, false);
            }
          });
        }

        atbdp_tooltip();
        customFieldSeeMore();
      } else {
        $('.atbdp_category_custom_fields').empty();
      }
    });
  }); // Load custom fields of the selected category in the custom post type "atbdp_listings"

  var directory_type = qs.listing_type ? qs.listing_type : $('input[name="directory_type"]').val();
  var length = $('#at_biz_dir-categories option:selected');
  var id = [];
  length.each(function (el, index) {
    id.push($(index).val());
  });
  var data = {
    action: 'atbdp_custom_fields_listings',
    post_id: $('input[name="listing_id"]').val(),
    term_id: id,
    directory_type: directory_type
  };
  $.post(localized_data.ajaxurl, data, function (response) {
    if (response) {
      $('.atbdp_category_custom_fields').empty().append(response);

      function atbdp_tooltip() {
        var atbd_tooltip = document.querySelectorAll('.atbd_tooltip');
        atbd_tooltip.forEach(function (el) {
          if (el.getAttribute('aria-label') !== " ") {
            document.body.addEventListener('mouseover', function (e) {
              for (var target = e.target; target && target != this; target = target.parentNode) {
                if (target.matches('.atbd_tooltip')) {
                  el.classList.add('atbd_tooltip_active');
                }
              }
            }, false);
          }
        });
      }

      atbdp_tooltip();
    }
  });

  function setup_form_data(form_data, type, field) {
    //normal input
    if (type === 'hidden' || type === 'text' || type === 'number' || type === 'tel' || type === 'email' || type === 'date' || type === 'time' || type === 'url') {
      form_data.append(field.name, field.value);
    } //textarea


    if ('textarea' === type) {
      var value = $('#' + field.name + '_ifr').length ? tinymce.get(field.name).getContent() : atbdp_element_value('textarea[name="' + field.name + '"]');
      form_data.append(field.name, value);
    } //radio


    if ('radio' === type) {
      form_data.append(field.name, atbdp_element_value('input[name="' + field.name + '"]:checked'));
    } // checkbox


    if ('checkbox' === type) {
      var values = [];
      var new_field = $('input[name^="' + field.name + '"]:checked');

      if (new_field.length > 1) {
        new_field.each(function () {
          var value = $(this).val();
          values.push(value);
        });
        form_data.append(field.name, values);
      } else {
        form_data.append(field.name, atbdp_element_value('input[name="' + field.name + '"]:checked'));
      }
    } //select


    if ('select-one' === type) {
      form_data.append(field.name, atbdp_element_value('select[name="' + field.name + '"]'));
    }
  }

  function scrollToEl(selector) {
    document.querySelector(selector).scrollIntoView({
      block: 'start',
      behavior: 'smooth'
    });
  }

  function atbdp_element_value(element) {
    var field = $(element);

    if (field.length) {
      return field.val();
    }

    return '';
  }

  var uploaders = localized_data.media_uploader;
  var mediaUploaders = [];

  if (uploaders) {
    var i = 0;

    var _iterator = _createForOfIteratorHelper(uploaders),
        _step;

    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var uploader = _step.value;

        if ($('.' + uploader['element_id']).length) {
          var media_uploader = new EzMediaUploader({
            containerClass: uploader['element_id']
          });
          mediaUploaders.push({
            media_uploader: media_uploader,
            uploaders_data: uploader
          });
          mediaUploaders[i].media_uploader.init();
          i++;
        }
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
  }

  var formID = $('#directorist-add-listing-form');
  var on_processing = false;
  var has_media = true;
  var quick_login_modal__success_callback = null;
  $('body').on('submit', formID, function (e) {
    if (localized_data.is_admin) return;
    e.preventDefault();
    var error_count = 0;
    var err_log = {};

    if (on_processing) {
      $('.directorist-form-submit__btn').attr('disabled', true);
      return;
    }

    var form_data = new FormData();
    form_data.append('action', 'add_listing_action');
    form_data.append('directorist_nonce', directorist.directorist_nonce);
    var field_list = [];
    var field_list2 = [];
    $('.directorist-form-submit__btn').addClass('atbd_loading');
    var fieldValuePairs = $('#directorist-add-listing-form').serializeArray();
    var frm_element = document.getElementById('directorist-add-listing-form');
    $.each(fieldValuePairs, function (index, fieldValuePair) {
      var field__name = fieldValuePair.name;
      var field = frm_element.querySelector('[name="' + field__name + '"]');
      var type = field.type;
      field_list.push({
        name: field.name
      }); //array fields

      if (field.name.indexOf('[') > -1) {
        var field_name = field.name.substr(0, field.name.indexOf("["));
        var ele = $("[name^='" + field_name + "']"); // process tax input

        if ('tax_input' !== field_name) {
          if (ele.length && ele.length > 1) {
            ele.each(function (index, value) {
              var field_type = $(this).attr('type');
              var name = $(this).attr('name');

              if (field_type === 'radio') {
                if ($(this).is(':checked')) {
                  form_data.append(name, $(this).val());
                }
              } else if (field_type === 'checkbox') {
                var new_field = $('input[name^="' + name + '"]:checked');

                if (new_field.length > 1) {
                  new_field.each(function () {
                    var name = $(this).attr('name');
                    var value = $(this).val();
                    form_data.append(name, value);
                  });
                } else {
                  var name = new_field.attr('name');
                  var value = new_field.val();
                  form_data.append(name, value);
                }
              } else {
                var name = $(this).attr('name');
                var value = $(this).val();

                if (!value) {
                  value = $(this).attr('data-time');
                }

                form_data.append(name, value);
              }
            });
          } else {
            var name = ele.attr('name');

            var _value = ele.val();

            form_data.append(name, _value);
          }
        }
      } else {
        setup_form_data(form_data, type, field);
      }
    }); // images

    if (mediaUploaders.length) {
      var _iterator2 = _createForOfIteratorHelper(mediaUploaders),
          _step2;

      try {
        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          var uploader = _step2.value;

          if (uploader.media_uploader && has_media) {
            var hasValidFiles = uploader.media_uploader.hasValidFiles();

            if (hasValidFiles) {
              // files
              var files = uploader.media_uploader.getTheFiles();

              if (files) {
                for (var i = 0; i < files.length; i++) {
                  form_data.append(uploader.uploaders_data['meta_name'] + '[]', files[i]);
                }
              }

              var files_meta = uploader.media_uploader.getFilesMeta();

              if (files_meta) {
                for (var i = 0; i < files_meta.length; i++) {
                  var elm = files_meta[i];

                  for (var key in elm) {
                    form_data.append("".concat(uploader.uploaders_data['files_meta_name'], "[").concat(i, "][").concat(key, "]"), elm[key]);
                  }
                }
              }
            } else {
              $('.directorist-form-submit__btn').removeClass('atbd_loading');
              err_log.listing_gallery = {
                msg: uploader.uploaders_data['error_msg']
              };
              error_count++;

              if ($('#' + uploader.uploaders_data['element_id']).length) {
                scrollToEl('#' + uploader.uploaders_data['element_id']);
              }

              if ($('.' + uploader.uploaders_data['element_id']).length) {
                scrollToEl('.' + uploader.uploaders_data['element_id']);
              }
            }
          }
        }
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }
    } // locations


    var locaitons = $('#at_biz_dir-location').val();

    if (Array.isArray(locaitons) && locaitons.length) {
      for (var key in locaitons) {
        var value = locaitons[key];
        form_data.append('tax_input[at_biz_dir-location][]', value);
      }
    }

    if (typeof locaitons === 'string') {
      form_data.append('tax_input[at_biz_dir-location][]', locaitons);
    } // tags


    var tags = $('#at_biz_dir-tags').val();

    if (tags) {
      for (var key in tags) {
        var value = tags[key];
        form_data.append('tax_input[at_biz_dir-tags][]', value);
      }
    } // categories


    var categories = $('#at_biz_dir-categories').val();

    if (Array.isArray(categories) && categories.length) {
      for (var key in categories) {
        var value = categories[key];
        form_data.append('tax_input[at_biz_dir-category][]', value);
      }
    }

    if (typeof categories === 'string') {
      form_data.append('tax_input[at_biz_dir-category][]', categories);
    }

    var form_directory_type = frm_element.querySelector('[name="directory_type"]');
    var form_directory_type_value = form_directory_type !== undefined ? form_directory_type.value : '';
    var directory_type = qs.directory_type ? qs.directory_type : form_directory_type_value;
    form_data.append('directory_type', directory_type);

    if (qs.plan) {
      form_data.append('plan_id', qs.plan);
    }

    if (error_count) {
      on_processing = false;
      $('.directorist-form-submit__btn').attr('disabled', false);
      console.log('Form has invalid data');
      console.log(error_count, err_log);
      return;
    } // on_processing = true;


    $.ajax({
      method: 'POST',
      processData: false,
      contentType: false,
      url: localized_data.ajaxurl,
      data: form_data,
      success: function success(response) {
        //console.log(response);
        // return;
        // show the error notice
        $('.directorist-form-submit__btn').attr('disabled', false);
        var redirect_url = response && response.redirect_url ? response.redirect_url : '';
        redirect_url = redirect_url && typeof redirect_url === 'string' ? response.redirect_url.replace(/:\/\//g, '%3A%2F%2F') : '';

        if (response.error === true) {
          $('#listing_notifier').show().html("<span>".concat(response.error_msg, "</span>"));
          $('.directorist-form-submit__btn').removeClass('atbd_loading');
          on_processing = false;

          if (response.quick_login_required) {
            var modal = $('#directorist-quick-login');
            var email = response.email; // Prepare fields

            modal.find('input[name="email"]').val(email);
            modal.find('input[name="email"]').prop('disabled', true); // Show alert

            var alert = '<div class="directorist-alert directorist-alert-info directorist-mb-10 atbd-text-center directorist-mb-10">' + response.error_msg + '</div>';
            modal.find('.directorist-modal-alerts-area').html(alert); // Show the modal

            modal.addClass('show');

            quick_login_modal__success_callback = function quick_login_modal__success_callback(args) {
              $('#guest_user_email').prop('disabled', true);
              $('#listing_notifier').hide().html('');
              args.elements.submit_button.remove();
              var form_actions = args.elements.form.find('.directorist-form-actions');
              form_actions.find('.directorist-toggle-modal').removeClass('directorist-d-none');
            };
          }
        } else {
          // preview on and no need to redirect to payment
          if (response.preview_mode === true && response.need_payment !== true) {
            if (response.edited_listing !== true) {
              $('#listing_notifier').show().html("<span class=\"atbdp_success\">".concat(response.success_msg, "</span>"));
              window.location.href = joinQueryString(response.preview_url, "preview=1&redirect=".concat(redirect_url));
            } else {
              $('#listing_notifier').show().html("<span class=\"atbdp_success\">".concat(response.success_msg, "</span>"));

              if (qs.redirect) {
                window.location.href = joinQueryString(response.preview_url, "post_id=".concat(response.id, "&preview=1&payment=1&edited=1&redirect=").concat(qs.redirect));
              } else {
                window.location.href = joinQueryString(response.preview_url, "preview=1&edited=1&redirect=".concat(redirect_url));
              }
            } // preview mode active and need payment

          } else if (response.preview_mode === true && response.need_payment === true) {
            window.location.href = joinQueryString(response.preview_url, "preview=1&payment=1&redirect=".concat(redirect_url));
          } else {
            var is_edited = response.edited_listing ? "listing_id=".concat(response.id, "&edited=1") : '';

            if (response.need_payment === true) {
              $('#listing_notifier').show().html("<span class=\"atbdp_success\">".concat(response.success_msg, "</span>"));
              window.location.href = redirect_url;
            } else {
              $('#listing_notifier').show().html("<span class=\"atbdp_success\">".concat(response.success_msg, "</span>"));
              window.location.href = joinQueryString(response.redirect_url, is_edited);
            }
          }
        }
      },
      error: function error(_error) {
        on_processing = false;
        $('.directorist-form-submit__btn').attr('disabled', false);
        $('.directorist-form-submit__btn').removeClass('atbd_loading');
        console.log(_error);
      }
    });
  }); // Custom Field Checkbox Button More

  function customFieldSeeMore() {
    if ($('.directorist-custom-field-btn-more').length) {
      $('.directorist-custom-field-btn-more').each(function (index, element) {
        var fieldWrapper = $(element).closest('.directorist-custom-field-checkbox, .directorist-custom-field-radio');
        var customField = $(fieldWrapper).find('.directorist-checkbox, .directorist-radio');
        $(customField).slice(20, customField.length).slideUp();

        if (customField.length <= 20) {
          $(element).slideUp();
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
      $(this).text("See Less");
      $(customField).slice(20, customField.length).slideDown();
    } else {
      $(this).text("See More");
      $(customField).slice(20, customField.length).slideUp();
    }
  }); // ------------------------------
  // Quick Login
  // ------------------------------

  $('#directorist-quick-login .directorist-toggle-modal').on("click", function (e) {
    e.preventDefault();
    $("#directorist-quick-login").removeClass("show");
  });
  $('#quick-login-from-submit-btn').on('click', function (e) {
    e.preventDefault();
    var form_id = $(this).data('form');
    var modal_id = $(this).data('form');
    var modal = $(modal_id);
    var form = $(form_id);
    var form_feedback = form.find('.directorist-form-feedback');
    var email = $(form).find('input[name="email"]');
    var password = $(form).find('input[name="password"]');
    var security = $(form).find('input[name="directorist-quick-login-security"]');

    var form_data = _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default()({
      action: 'directorist_ajax_quick_login',
      username: email.val(),
      password: password.val(),
      rememberme: false
    }, 'directorist-quick-login-security', security.val());

    var submit_button = $(this);
    var submit_button_default_html = submit_button.html();
    $.ajax({
      method: 'POST',
      url: directorist.ajaxurl,
      data: form_data,
      beforeSend: function beforeSend() {
        form_feedback.html('');
        submit_button.prop('disabled', true);
        submit_button.prepend('<i class="fas fa-circle-notch fa-spin"></i> ');
      },
      success: function success(response) {
        submit_button.html(submit_button_default_html);

        if (response.loggedin) {
          password.prop('disabled', true);
          var message = 'Successfully logged in, please continue to the listing submission';
          var msg = '<div class="directorist-alert directorist-alert-success directorist-text-center directorist-mb-20">' + message + '</div>';
          form_feedback.html(msg);

          if (quick_login_modal__success_callback) {
            var args = {
              elements: {
                modal_id: modal_id,
                form: form,
                email: email,
                password: password,
                submit_button: submit_button
              }
            };
            quick_login_modal__success_callback(args);
          }
        } else {
          var msg = '<div class="directorist-alert directorist-alert-danger directorist-text-center directorist-mb-20">' + response.message + '</div>';
          form_feedback.html(msg);
          submit_button.prop('disabled', false);
        }
      },
      error: function error(_error2) {
        console.log({
          error: _error2
        });
        submit_button.prop('disabled', false);
        submit_button.html(submit_button_default_html);
      }
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

/***/ "./assets/src/js/public/components/directoristDropdown.js":
/*!****************************************************************!*\
  !*** ./assets/src/js/public/components/directoristDropdown.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    /* custom dropdown */
    var atbdDropdown = document.querySelectorAll('.directorist-dropdown-select'); // toggle dropdown

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
    } // remvoe toggle when click outside


    document.body.addEventListener('click', function (e) {
      if (e.target.getAttribute('data-drop-toggle') !== 'directorist-dropdown-select-toggle') {
        clickCount = 0;
        document.querySelectorAll('.directorist-dropdown-select-items').forEach(function (el) {
          el.classList.remove('directorist-dropdown-select-show');
        });
      }
    }); //custom select

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
    } // Dropdown


    $('body').on('click', '.directorist-dropdown .directorist-dropdown-toggle', function (e) {
      e.preventDefault();
      $(this).siblings('.directorist-dropdown-option').toggle();
    }); // Select Option after click

    $('body').on('click', '.directorist-dropdown .directorist-dropdown-option ul li a', function (e) {
      e.preventDefault();
      var optionText = $(this).html();
      $(this).children('.directorist-dropdown-toggle__text').html(optionText);
      $(this).closest('.directorist-dropdown-option').siblings('.directorist-dropdown-toggle').children('.directorist-dropdown-toggle__text').html(optionText);
      $('.directorist-dropdown-option').hide();
    }); // Hide Clicked Anywhere

    $(document).bind('click', function (e) {
      var clickedDom = $(e.target);
      if (!clickedDom.parents().hasClass('directorist-dropdown')) $('.directorist-dropdown-option').hide();
    }); //atbd_dropdown

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
    }); // Directorist Dropdown

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
});

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

/***/ 7:
/*!***************************************************!*\
  !*** multi ./assets/src/js/global/add-listing.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/global/add-listing.js */"./assets/src/js/global/add-listing.js");


/***/ })

/******/ });
//# sourceMappingURL=global-add-listing.js.map