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
/******/ 	return __webpack_require__(__webpack_require__.s = 11);
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
  $(function () {
    $('.directorist-color-field-js').wpColorPicker().empty();
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
    var form_directory_type_value = form_directory_type.length ? form_directory_type.value : '';
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
        // show the error notice
        $('.directorist-form-submit__btn').attr('disabled', false);
        var is_pending = response && response.pending ? '&' : '?';

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
              window.location.href = "".concat(response.preview_url, "?preview=1&redirect=").concat(response.redirect_url);
            } else {
              $('#listing_notifier').show().html("<span class=\"atbdp_success\">".concat(response.success_msg, "</span>"));

              if (qs.redirect) {
                var is_pending = '?';
                window.location.href = "".concat(response.preview_url + is_pending, "post_id=").concat(response.id, "&preview=1&payment=1&edited=1&redirect=").concat(qs.redirect);
              } else {
                window.location.href = "".concat(response.preview_url, "?preview=1&edited=1&redirect=").concat(response.redirect_url);
              }
            } // preview mode active and need payment

          } else if (response.preview_mode === true && response.need_payment === true) {
            window.location.href = "".concat(response.preview_url, "?preview=1&payment=1&redirect=").concat(response.redirect_url);
          } else {
            var is_edited = response.edited_listing ? "".concat(is_pending, "listing_id=").concat(response.id, "&edited=1") : '';

            if (response.need_payment === true) {
              $('#listing_notifier').show().html("<span class=\"atbdp_success\">".concat(response.success_msg, "</span>"));
              window.location.href = response.redirect_url;
            } else {
              $('#listing_notifier').show().html("<span class=\"atbdp_success\">".concat(response.success_msg, "</span>"));
              window.location.href = response.redirect_url + is_edited;
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

/***/ "./assets/src/js/global/components/modal.js":
/*!**************************************************!*\
  !*** ./assets/src/js/global/components/modal.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
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

  $('.select2-hidden-accessible').on('select2:open', function (e) {
    var dropdown_btn = $(this).next().find('.directorist-select2-dropdown-toggle');
    dropdown_btn.addClass('--is-open');
  });
  $('.select2-hidden-accessible').on('select2:close', function (e) {
    var dropdown_btn = $(this).next().find('.directorist-select2-dropdown-toggle');
    dropdown_btn.removeClass('--is-open');
  }); // Toggle Dropdown

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

/***/ "./assets/src/js/global/map-scripts/add-listing/google-map.js":
/*!********************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/add-listing/google-map.js ***!
  \********************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../../../lib/helper */ "./assets/src/js/lib/helper.js");
/* Add listing google map */


(function ($) {
  $(document).ready(function () {
    if ($('#gmap').length) {
      var localized_data = Object(_lib_helper__WEBPACK_IMPORTED_MODULE_0__["get_dom_data"])('map_data'); // initialize all vars here to avoid hoisting related misunderstanding.

      var placeSearch;
      var map;
      var autocomplete;
      var address_input;
      var markers;
      var info_window;
      var $manual_lat;
      var $manual_lng;
      var saved_lat_lng;
      var info_content; // Localized Data

      var loc_default_latitude = parseFloat(localized_data.default_latitude);
      var loc_default_longitude = parseFloat(localized_data.default_longitude);
      var loc_manual_lat = parseFloat(localized_data.manual_lat);
      var loc_manual_lng = parseFloat(localized_data.manual_lng);
      var loc_map_zoom_level = parseInt(localized_data.map_zoom_level);
      loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;
      loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;
      $manual_lat = $('#manual_lat');
      $manual_lng = $('#manual_lng');
      saved_lat_lng = {
        lat: loc_manual_lat,
        lng: loc_manual_lng
      }; // default is London city

      info_content = localized_data.info_content, markers = [], // initialize the array to keep track all the marker
      info_window = new google.maps.InfoWindow({
        content: info_content,
        maxWidth: 400
      }); // if(address_input){
      //         address_input = document.getElementById('address');
      //         address_input.addEventListener('focus', geolocate);
      // }

      address_input = document.getElementById('address');

      if (address_input !== null) {
        address_input.addEventListener('focus', geolocate);
      } // this function will work on sites that uses SSL, it applies to Chrome especially, other browsers may allow location sharing without securing.


      function geolocate() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function (position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
      }

      function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(address_input, {
          types: []
        }); // When the user selects an address from the dropdown, populate the necessary input fields and draw a marker

        autocomplete.addListener('place_changed', fillInAddress);
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace(); // set the value of input field to save them to the database

        $manual_lat.val(place.geometry.location.lat());
        $manual_lng.val(place.geometry.location.lng());
        map.setCenter(place.geometry.location);
        var marker = new google.maps.Marker({
          map: map,
          position: place.geometry.location
        }); // marker.addListener('click', function () {
        //     info_window.open(map, marker);
        // });
        // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.

        markers.push(marker);
      }

      initAutocomplete(); // start google map place auto complete API call

      function initMap() {
        /* Create new map instance */
        map = new google.maps.Map(document.getElementById('gmap'), {
          zoom: loc_map_zoom_level,
          center: saved_lat_lng
        });
        var marker = new google.maps.Marker({
          map: map,
          position: saved_lat_lng,
          draggable: true,
          title: localized_data.marker_title
        }); // marker.addListener('click', function () {
        //     info_window.open(map, marker);
        // });
        // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.

        markers.push(marker); // create a Geocode instance

        var geocoder = new google.maps.Geocoder();
        document.getElementById('generate_admin_map').addEventListener('click', function (e) {
          e.preventDefault();
          geocodeAddress(geocoder, map);
        }); // This event listener calls addMarker() when the map is clicked.

        google.maps.event.addListener(map, 'click', function (event) {
          deleteMarker(); // at first remove previous marker and then set new marker;
          // set the value of input field to save them to the database

          $manual_lat.val(event.latLng.lat());
          $manual_lng.val(event.latLng.lng()); // add the marker to the given map.

          addMarker(event.latLng, map);
        }); // This event listener update the lat long field of the form so that we can add the lat long to the database when the MARKER is drag.

        google.maps.event.addListener(marker, 'dragend', function (event) {
          // set the value of input field to save them to the database
          $manual_lat.val(event.latLng.lat());
          $manual_lng.val(event.latLng.lng());
        });
      }
      /*
       * Geocode and address using google map javascript api and then populate the input fields for storing lat and long
       * */


      function geocodeAddress(geocoder, resultsMap) {
        var address = address_input.value;
        var lat = parseFloat(document.getElementById('manual_lat').value);
        var lng = parseFloat(document.getElementById('manual_lng').value);
        var latLng = new google.maps.LatLng(lat, lng);
        var opt = {
          location: latLng,
          address: address
        };
        geocoder.geocode(opt, function (results, status) {
          if (status === 'OK') {
            // set the value of input field to save them to the database
            $manual_lat.val(results[0].geometry.location.lat());
            $manual_lng.val(results[0].geometry.location.lng());
            resultsMap.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
              map: resultsMap,
              position: results[0].geometry.location
            }); // marker.addListener('click', function () {
            //     info_window.open(map, marker);
            // });

            deleteMarker(); // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.

            markers.push(marker);
          } else {
            alert(localized_data.geocode_error_msg + status);
          }
        });
      }

      initMap(); // adding features of creating marker manually on the map on add listing page.

      /* var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      var labelIndex = 0; */
      // Adds a marker to the map.

      function addMarker(location, map) {
        // Add the marker at the clicked location, and add the next-available label
        // from the array of alphabetical characters.
        var marker = new google.maps.Marker({
          position: location,

          /* label: labels[labelIndex++ % labels.length], */
          draggable: true,
          title: localized_data.marker_title,
          map: map
        }); // marker.addListener('click', function () {
        //     info_window.open(map, marker);
        // });
        // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.

        markers.push(marker);
      } // Delete Marker


      $('#delete_marker').on('click', function (e) {
        e.preventDefault();
        deleteMarker();
      });

      function deleteMarker() {
        for (var i = 0; i < markers.length; i++) {
          markers[i].setMap(null);
        }

        markers = [];
      }
    }
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/global/map-scripts/add-listing/openstreet-map.js":
/*!************************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/add-listing/openstreet-map.js ***!
  \************************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../../../lib/helper */ "./assets/src/js/lib/helper.js");
/* Add listing OSMap */

;

(function ($) {
  $(document).ready(function () {
    var mapData = Object(_lib_helper__WEBPACK_IMPORTED_MODULE_0__["get_dom_data"])('map_data'); // Localized Data

    var loc_default_latitude = parseFloat(mapData.default_latitude);
    var loc_default_longitude = parseFloat(mapData.default_longitude);
    var loc_manual_lat = parseFloat(mapData.manual_lat);
    var loc_manual_lng = parseFloat(mapData.manual_lng);
    var loc_map_zoom_level = parseInt(mapData.map_zoom_level);
    var loc_map_icon = mapData.map_icon;
    loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;
    loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;

    function mapLeaflet(lat, lon) {
      // @todo @kowsar / remove later. fix js error
      if ($("#gmap").length == 0) {
        return;
      }

      var fontAwesomeIcon = L.icon({
        iconUrl: loc_map_icon,
        iconSize: [20, 25]
      });
      var mymap = L.map('gmap').setView([lat, lon], loc_map_zoom_level);
      L.marker([lat, lon], {
        icon: fontAwesomeIcon,
        draggable: true
      }).addTo(mymap).addTo(mymap).on("drag", function (e) {
        var marker = e.target;
        var position = marker.getLatLng();
        $('#manual_lat').val(position.lat);
        $('#manual_lng').val(position.lng);
        $.ajax({
          url: "https://nominatim.openstreetmap.org/reverse?format=json&lon=".concat(position.lng, "&lat=").concat(position.lat),
          type: 'POST',
          data: {},
          success: function success(data) {
            $('.directorist-location-js').val(data.display_name);
          }
        });
      });
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(mymap);
    }

    $('.directorist-location-js').each(function (id, elm) {
      $(elm).on('keyup', function (event) {
        event.preventDefault();

        if (event.keyCode !== 40 && event.keyCode !== 38) {
          var search = $(elm).val();
          $(elm).siblings('.address_result').css({
            'display': 'block'
          });

          if (search === "") {
            $(elm).siblings('.address_result').css({
              'display': 'none'
            });
          }

          var res = "";
          $.ajax({
            url: "https://nominatim.openstreetmap.org/?q=%27+".concat(search, "+%27&format=json"),
            type: 'POST',
            data: {},
            success: function success(data) {
              for (var i = 0; i < data.length; i++) {
                res += "<li><a href=\"#\" data-lat=".concat(data[i].lat, " data-lon=").concat(data[i].lon, ">").concat(data[i].display_name, "</a></li>");
              }

              $(elm).siblings('.address_result').find('ul').html(res);
            }
          });
        }
      });
    });
    var lat = loc_manual_lat,
        lon = loc_manual_lng;
    mapLeaflet(lat, lon);
    $('body').on('click', '.directorist-form-address-field .address_result ul li a', function (event) {
      if (document.getElementById('osm')) {
        document.getElementById('osm').innerHTML = "<div id='gmap'></div>";
      }

      event.preventDefault();
      var text = $(this).text(),
          lat = $(this).data('lat'),
          lon = $(this).data('lon');
      $('#manual_lat').val(lat);
      $('#manual_lng').val(lon);
      $(this).closest('.address_result').siblings('.directorist-location-js').val(text);
      $('.address_result').css({
        'display': 'none'
      });
      mapLeaflet(lat, lon);
    });
    $('body').on('click', '.location-names ul li a', function (event) {
      event.preventDefault();
      var text = $(this).text();
      $(this).closest('.address_result').siblings('.directorist-location-js').val(text);
      $('.address_result').css({
        'display': 'none'
      });
    });
    $('body').on('click', '#generate_admin_map', function (event) {
      event.preventDefault();
      document.getElementById('osm').innerHTML = "<div id='gmap'></div>";
      mapLeaflet($('#manual_lat').val(), $('#manual_lng').val());
    }); // Popup controller by keyboard

    var index = 0;
    $('.directorist-location-js').on('keyup', function (event) {
      event.preventDefault();
      var length = $('#directorist.atbd_wrapper .address_result ul li a').length;

      if (event.keyCode === 40) {
        index++;

        if (index > length) {
          index = 0;
        }
      } else if (event.keyCode === 38) {
        index--;

        if (index < 0) {
          index = length;
        }

        ;
      }

      if ($('#directorist.atbd_wrapper .address_result ul li a').length > 0) {
        $('#directorist.atbd_wrapper .address_result ul li a').removeClass('active');
        $($('#directorist.atbd_wrapper .address_result ul li a')[index]).addClass('active');

        if (event.keyCode === 13) {
          $($('#directorist.atbd_wrapper .address_result ul li a')[index]).click();
          event.preventDefault();
          index = 0;
          return false;
        }
      }

      ;
    }); // $('#post').on('submit', function (event) {
    //     event.preventDefault();
    //     return false;
    // });
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/global/map-scripts/geolocation.js":
/*!*********************************************************!*\
  !*** ./assets/src/js/global/map-scripts/geolocation.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    /* $("button[type='reset']").on("click", function (){
    $("#atbd_rs_value").val(0);
    $(".atbdpr_amount").text(0 + miles);
    slider_range.each(function () {
        $(this).slider({
            range: "min",
            min: 0,
            max: 1000,
            value: 0,
            slide: function (event, ui) {
                $(".atbdpr_amount").text(ui.value + miles);
                $("#atbd_rs_value").val(ui.value);
            }
        });
    });
    $("#at_biz_dir-location, #at_biz_dir-category").val('').trigger('change');
    }); */

    /* get current location */
    setTimeout(function () {
      if (directorist.select_listing_map === 'google') {
        (function () {
          var locationInput = document.querySelector('.location-name');
          var get_lat = document.querySelector('#cityLat');
          var get_lng = document.querySelector('#cityLng');

          function getLocation() {
            if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
              locationInput.value = 'Geolocation is not supported by this browser.';
            }
          }

          function showPosition(position) {
            lat = position.coords.latitude;
            lon = position.coords.longitude;
            displayCurrentLocation(lat, lon);
            get_lat.value = lat;
            get_lng.value = lon;
          }

          function showError(error) {
            switch (error.code) {
              case error.PERMISSION_DENIED:
                locationInput.value = 'User denied the request for Geolocation.';
                break;

              case error.POSITION_UNAVAILABLE:
                locationInput.value = 'Location information is unavailable.';
                break;

              case error.TIMEOUT:
                locationInput.value = 'The request to get user location timed out.';
                break;

              case error.UNKNOWN_ERROR:
                locationInput.value = 'An unknown error occurred.';
                break;
            }
          }

          function displayLocation(latitude, longitude) {
            var geocoder;
            geocoder = new google.maps.Geocoder();
            var latlng = new google.maps.LatLng(latitude, longitude);
            geocoder.geocode({
              latLng: latlng,
              componentRestrictions: {
                country: 'GB'
              }
            }, function (results, status) {
              if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                  var add = results[0].formatted_address;
                  var value = add.split(',');
                  count = value.length;
                  country = value[count - 1];
                  state = value[count - 2];
                  city = value[count - 3];
                  locationInput.value = city;
                } else {
                  locationInput.value = 'address not found';
                }
              } else {
                locationInput.value = "Geocoder failed due to: ".concat(status);
              }
            });
          }

          function displayCurrentLocation(latitude, longitude) {
            var geocoder;
            geocoder = new google.maps.Geocoder();
            var latlng = new google.maps.LatLng(latitude, longitude);
            geocoder.geocode({
              latLng: latlng
            }, function (results, status) {
              if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                  var add = results[0].formatted_address;
                  var value = add.split(',');
                  count = value.length;
                  country = value[count - 1];
                  state = value[count - 2];
                  city = value[count - 3];
                  locationInput.value = city;
                } else {
                  locationInput.value = 'address not found';
                }
              } else {
                locationInput.value = "Geocoder failed due to: ".concat(status);
              }
            });
          }

          var get_loc_btn = $('.directorist-filter-location-icon');
          get_loc_btn.on('click', function () {
            getLocation();
          });
        })();
      } else if (directorist.select_listing_map === 'openstreet') {
        function displayLocation(position) {
          var lat = position.coords.latitude;
          var lng = position.coords.longitude;
          $.ajax({
            url: "https://nominatim.openstreetmap.org/reverse?format=json&lon=".concat(lng, "&lat=").concat(lat),
            type: 'POST',
            data: {},
            success: function success(data) {
              $('.directorist-location-js, .atbdp-search-address').val(data.display_name);
              $('#cityLat').val(lat);
              $('#cityLng').val(lng);
            }
          });
        }

        $('.directorist-filter-location-icon').on('click', function () {
          navigator.geolocation.getCurrentPosition(displayLocation);
        });
      }
    }, 1000);
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/global/map-scripts/map-view.js":
/*!******************************************************!*\
  !*** ./assets/src/js/global/map-scripts/map-view.js ***!
  \******************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../../lib/helper */ "./assets/src/js/lib/helper.js");
/* harmony import */ var _add_listing_google_map__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./add-listing/google-map */ "./assets/src/js/global/map-scripts/add-listing/google-map.js");
/* harmony import */ var _single_listing_google_map__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./single-listing/google-map */ "./assets/src/js/global/map-scripts/single-listing/google-map.js");
/* harmony import */ var _single_listing_google_map__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_single_listing_google_map__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _single_listing_google_map_widget__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./single-listing/google-map-widget */ "./assets/src/js/global/map-scripts/single-listing/google-map-widget.js");
/* harmony import */ var _single_listing_google_map_widget__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_single_listing_google_map_widget__WEBPACK_IMPORTED_MODULE_3__);

;

(function () {
  window.addEventListener('DOMContentLoaded', function () {
    var mapData = Object(_lib_helper__WEBPACK_IMPORTED_MODULE_0__["get_dom_data"])('atbdp_map'); // Define Marker Shapes

    var MAP_PIN = 'M0-48c-9.8 0-17.7 7.8-17.7 17.4 0 15.5 17.7 30.6 17.7 30.6s17.7-15.4 17.7-30.6c0-9.6-7.9-17.4-17.7-17.4z';

    var inherits = function inherits(childCtor, parentCtor) {
      /** @constructor */
      function tempCtor() {}

      tempCtor.prototype = parentCtor.prototype;
      childCtor.superClass_ = parentCtor.prototype;
      childCtor.prototype = new tempCtor();
      childCtor.prototype.constructor = childCtor;
    };

    function Marker(options) {
      google.maps.Marker.apply(this, arguments);

      if (options.map_icon_label) {
        this.MarkerLabel = new MarkerLabel({
          map: this.map,
          marker: this,
          text: options.map_icon_label
        });
        this.MarkerLabel.bindTo('position', this, 'position');
      }
    } // Apply the inheritance


    inherits(Marker, google.maps.Marker); // Custom Marker SetMap

    Marker.prototype.setMap = function () {
      google.maps.Marker.prototype.setMap.apply(this, arguments);
      this.MarkerLabel && this.MarkerLabel.setMap.apply(this.MarkerLabel, arguments);
    }; // Marker Label Overlay


    var MarkerLabel = function MarkerLabel(options) {
      var self = this;
      this.setValues(options); // Create the label container

      this.div = document.createElement('div');
      this.div.className = 'map-icon-label'; // Trigger the marker click handler if clicking on the label

      google.maps.event.addDomListener(this.div, 'click', function (e) {
        e.stopPropagation && e.stopPropagation();
        google.maps.event.trigger(self.marker, 'click');
      });
    }; // Create MarkerLabel Object


    MarkerLabel.prototype = new google.maps.OverlayView(); // Marker Label onAdd

    MarkerLabel.prototype.onAdd = function () {
      var pane = this.getPanes().overlayImage.appendChild(this.div);
      var self = this;
      this.listeners = [google.maps.event.addListener(this, 'position_changed', function () {
        self.draw();
      }), google.maps.event.addListener(this, 'text_changed', function () {
        self.draw();
      }), google.maps.event.addListener(this, 'zindex_changed', function () {
        self.draw();
      })];
    }; // Marker Label onRemove


    MarkerLabel.prototype.onRemove = function () {
      this.div.parentNode.removeChild(this.div);

      for (var i = 0, I = this.listeners.length; i < I; ++i) {
        google.maps.event.removeListener(this.listeners[i]);
      }
    }; // Implement draw


    MarkerLabel.prototype.draw = function () {
      var projection = this.getProjection();
      var position = projection.fromLatLngToDivPixel(this.get('position'));
      var div = this.div;
      this.div.innerHTML = this.get('text').toString();
      div.style.zIndex = this.get('zIndex'); // Allow label to overlay marker

      div.style.position = 'absolute';
      div.style.display = 'block';
      div.style.left = "".concat(position.x - div.offsetWidth / 2, "px");
      div.style.top = "".concat(position.y - div.offsetHeight, "px");
    };

    (function ($) {
      // map view

      /**
       *  Render a Google Map onto the selected jQuery element.
       *
       *  @since    5.0.0
       */
      var at_icon = [];
      /* Use Default lat/lng in listings map view */

      var defCordEnabled = mapData.use_def_lat_long;

      function atbdp_rander_map($el) {
        $el.addClass('atbdp-map-loaded'); // var

        var $markers = $el.find('.marker'); // vars

        var args = {
          zoom: parseInt(mapData.zoom),
          center: new google.maps.LatLng(0, 0),
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          zoomControl: true,
          scrollwheel: false,
          gestureHandling: 'cooperative',
          averageCenter: true,
          scrollWheelZoom: 'center'
        }; // create map

        var map = new google.maps.Map($el[0], args); // add a markers reference

        map.markers = []; // set map type

        map.type = $el.data('type');
        var infowindow = new google.maps.InfoWindow({
          content: ''
        }); // add markers

        $markers.each(function () {
          atbdp_add_marker($(this), map, infowindow);
        });
        var cord = {
          lat: Number(mapData.default_latitude) ? Number(mapData.default_latitude) : 40.7127753 ? defCordEnabled : undefined,
          lng: Number(mapData.default_longitude) ? Number(mapData.default_longitude) : -74.0059728 ? defCordEnabled : Number(mapData.default_longitude)
        };

        if ($markers.length) {
          cord.lat = defCordEnabled ? Number(mapData.default_latitude) : Number($markers[0].getAttribute('data-latitude'));
          cord.lng = defCordEnabled ? Number(mapData.default_longitude) : Number($markers[0].getAttribute('data-longitude'));
        } // center map


        atbdp_center_map(map, cord);
        var mcOptions = new MarkerClusterer(map, [], {
          imagePath: mapData.plugin_url + 'assets/images/m'
        });
        mcOptions.setStyles(mcOptions.getStyles().map(function (style) {
          style.textColor = '#fff';
          return style;
        }));

        if (map.type === 'markerclusterer') {
          //const markerCluster = new MarkerClusterer(map, map.markers, mcOptions);
          mcOptions.addMarkers(map.markers);
        }
      }
      /**
       *  Add a marker to the selected Google Map.
       *
       *  @since    1.0.0
       */


      function atbdp_add_marker($marker, map, infowindow) {
        // var
        var latlng = new google.maps.LatLng($marker.data('latitude'), $marker.data('longitude')); // check to see if any of the existing markers match the latlng of the new marker

        if (map.markers.length) {
          for (var i = 0; i < map.markers.length; i++) {
            var existing_marker = map.markers[i];
            var pos = existing_marker.getPosition(); // if a marker already exists in the same position as this marker

            if (latlng.equals(pos)) {
              // update the position of the coincident marker by applying a small multipler to its coordinates
              var latitude = latlng.lat() + (Math.random() - 0.5) / 1500; // * (Math.random() * (max - min) + min);

              var longitude = latlng.lng() + (Math.random() - 0.5) / 1500; // * (Math.random() * (max - min) + min);

              latlng = new google.maps.LatLng(latitude, longitude);
            }
          }
        }

        var icon = $marker.data('icon');
        var marker = new Marker({
          position: latlng,
          map: map,
          icon: {
            path: MAP_PIN,
            fillColor: 'transparent',
            fillOpacity: 1,
            strokeColor: '',
            strokeWeight: 0
          },
          map_icon_label: icon !== undefined && "<div class=\"atbd_map_shape\"><i class=\"".concat(icon, "\"></i></div>")
        }); // add to array

        map.markers.push(marker); // if marker contains HTML, add it to an infoWindow

        if ($marker.html()) {
          // map info window close button
          google.maps.event.addListener(infowindow, 'domready', function () {
            var closeBtn = $('.iw-close-btn').get();
            google.maps.event.addDomListener(closeBtn[0], 'click', function () {
              infowindow.close();
            });
          }); // show info window when marker is clicked

          google.maps.event.addListener(marker, 'click', function () {
            if (mapData.disable_info_window === 'no') {
              var marker_childrens = $($marker).children();

              if (marker_childrens.length) {
                var marker_content = marker_childrens[0];
                $(marker_content).addClass('map-info-wrapper--show');
              }

              infowindow.setContent($marker.html());
              infowindow.open(map, marker);
            }
          });
        }
      }
      /**
       *  Center the map, showing all markers attached to this map.
       *
       *  @since    1.0.0
       */


      function atbdp_center_map(map, cord) {
        map.setCenter(cord);
        map.setZoom(parseInt(mapData.zoom));
      }

      function setup_info_window() {
        var abc = document.querySelectorAll('div');
        abc.forEach(function (el, index) {
          if (el.innerText === 'atgm_marker') {
            // console.log(at_icon)
            el.innerText = ' ';
            el.innerHTML = "<i class=\"la ".concat(at_icon, " atbd_map_marker_icon\"></i>");
          } // ${$marker.data('icon')}

        });
        document.querySelectorAll('div').forEach(function (el1, index) {
          if (el1.style.backgroundImage.split('/').pop() === 'm1.png")') {
            el1.addEventListener('click', function () {
              setInterval(function () {
                var abc = document.querySelectorAll('div');
                abc.forEach(function (el, index) {
                  if (el.innerText === 'atgm_marker') {
                    el.innerText = ' ';
                    el.innerHTML = "<i class=\"la ".concat(at_icon, " atbd_map_marker_icon\"></i>");
                  }
                });
              }, 100);
            });
          }
        });
      }

      function setup_map() {
        // render map in the custom post
        $('.atbdp-map').each(function () {
          atbdp_rander_map($(this));
        });
      }

      window.addEventListener('load', setup_map);
      window.addEventListener('load', setup_info_window);
      window.addEventListener('directorist-reload-listings-map-archive', setup_map);
      window.addEventListener('directorist-reload-listings-map-archive', setup_info_window);
      $(document).ready(function () {
        $('body').find('.map-info-wrapper').addClass('map-info-wrapper--show');
      });
    })(jQuery);
  });
})();
/* Add listing google map */



/* Single listing google map */


/* Widget google map */



/***/ }),

/***/ "./assets/src/js/global/map-scripts/markerclusterer.js":
/*!*************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/markerclusterer.js ***!
  \*************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/typeof.js");
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__);


// ==ClosureCompiler==
// @compilation_level ADVANCED_OPTIMIZATIONS
// @externs_url http://closure-compiler.googlecode.com/svn/trunk/contrib/externs/maps/google_maps_api_v3_3.js
// ==/ClosureCompiler==

/**
 * @name MarkerClusterer for Google Maps v3
 * @version version 1.0
 * @author Luke Mahe
 * @fileoverview
 * The library creates and manages per-zoom-level clusters for large amounts of
 * markers.
 * <br/>
 * This is a v3 implementation of the
 * <a href="http://gmaps-utility-library-dev.googlecode.com/svn/tags/markerclusterer/"
 * >v2 MarkerClusterer</a>.
 */

/**
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * A Marker Clusterer that clusters markers.
 *
 * @param {google.maps.Map} map The Google map to attach to.
 * @param {Array.<google.maps.Marker>=} opt_markers Optional markers to add to
 *   the cluster.
 * @param {Object=} opt_options support the following options:
 *     'gridSize': (number) The grid size of a cluster in pixels.
 *     'maxZoom': (number) The maximum zoom level that a marker can be part of a
 *                cluster.
 *     'zoomOnClick': (boolean) Whether the default behaviour of clicking on a
 *                    cluster is to zoom into it.
 *     'averageCenter': (boolean) Wether the center of each cluster should be
 *                      the average of all markers in the cluster.
 *     'minimumClusterSize': (number) The minimum number of markers to be in a
 *                           cluster before the markers are hidden and a count
 *                           is shown.
 *     'styles': (object) An object that has style properties:
 *       'url': (string) The image url.
 *       'height': (number) The image height.
 *       'width': (number) The image width.
 *       'anchor': (Array) The anchor position of the label text.
 *       'textColor': (string) The text color.
 *       'textSize': (number) The text size.
 *       'backgroundPosition': (string) The position of the background x, y.
 *     'cssClass': (string) One or more CSS class for styling this marker.
 * @constructor
 * @extends google.maps.OverlayView
 */
function MarkerClusterer(map, opt_markers, opt_options) {
  // MarkerClusterer implements google.maps.OverlayView interface. We use the
  // extend function to extend MarkerClusterer with google.maps.OverlayView
  // because it might not always be available when the code is defined so we
  // look for it at the last possible moment. If it doesn't exist now then
  // there is no point going ahead :)
  this.extend(MarkerClusterer, google.maps.OverlayView);
  this.map_ = map;
  /**
   * @type {Array.<google.maps.Marker>}
   * @private
   */

  this.markers_ = [];
  /**
   *  @type {Array.<Cluster>}
   */

  this.clusters_ = [];
  this.sizes = [53, 56, 66, 78, 90];
  /**
   * @private
   */

  this.styles_ = [];
  /**
   * @private
   */

  this.cssClass_ = '';
  /**
   * @type {boolean}
   * @private
   */

  this.ready_ = false;
  var options = opt_options || {};
  /**
   * @type {number}
   * @private
   */

  this.gridSize_ = options['gridSize'] || 60;
  /**
   * @private
   */

  this.minClusterSize_ = options['minimumClusterSize'] || 2;
  /**
   * @type {?number}
   * @private
   */

  this.maxZoom_ = options['maxZoom'] || null;
  this.styles_ = options['styles'] || [];
  this.cssClass_ = options['cssClass'] || null;
  /**
   * @type {string}
   * @private
   */

  this.imagePath_ = options['imagePath'] || this.MARKER_CLUSTER_IMAGE_PATH_;
  /**
   * @type {string}
   * @private
   */

  this.imageExtension_ = options['imageExtension'] || this.MARKER_CLUSTER_IMAGE_EXTENSION_;
  /**
   * @type {boolean}
   * @private
   */

  this.zoomOnClick_ = true;

  if (options['zoomOnClick'] != undefined) {
    this.zoomOnClick_ = options['zoomOnClick'];
  }
  /**
   * @type {boolean}
   * @private
   */


  this.averageCenter_ = false;

  if (options['averageCenter'] != undefined) {
    this.averageCenter_ = options['averageCenter'];
  }

  this.setupStyles_();
  this.setMap(map);
  /**
   * @type {number}
   * @private
   */

  this.prevZoom_ = this.map_.getZoom(); // Add the map event listeners

  var that = this;
  google.maps.event.addListener(this.map_, 'zoom_changed', function () {
    var zoom = that.map_.getZoom();

    if (that.prevZoom_ != zoom) {
      that.prevZoom_ = zoom;
      that.resetViewport();
    }
  });
  google.maps.event.addListener(this.map_, 'idle', function () {
    that.redraw();
  }); // Finally, add the markers

  if (opt_markers && opt_markers.length) {
    this.addMarkers(opt_markers, false);
  }
}
/**
 * The marker cluster image path.
 *
 * @type {string}
 * @private
 */


MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_PATH_ = 'http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/' + 'images/m';
/**
 * The marker cluster image path.
 *
 * @type {string}
 * @private
 */

MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_EXTENSION_ = 'png';
/**
 * Extends a objects prototype by anothers.
 *
 * @param {Object} obj1 The object to be extended.
 * @param {Object} obj2 The object to extend with.
 * @return {Object} The new extended object.
 * @ignore
 */

MarkerClusterer.prototype.extend = function (obj1, obj2) {
  return function (object) {
    for (var property in object.prototype) {
      this.prototype[property] = object.prototype[property];
    }

    return this;
  }.apply(obj1, [obj2]);
};
/**
 * Implementaion of the interface method.
 * @ignore
 */


MarkerClusterer.prototype.onAdd = function () {
  this.setReady_(true);
};
/**
 * Implementaion of the interface method.
 * @ignore
 */


MarkerClusterer.prototype.draw = function () {};
/**
 * Sets up the styles object.
 *
 * @private
 */


MarkerClusterer.prototype.setupStyles_ = function () {
  if (this.styles_.length) {
    return;
  }

  for (var i = 0, size; size = this.sizes[i]; i++) {
    this.styles_.push({
      url: this.imagePath_ + (i + 1) + '.' + this.imageExtension_,
      height: size,
      width: size
    });
  }
};
/**
 *  Fit the map to the bounds of the markers in the clusterer.
 */


MarkerClusterer.prototype.fitMapToMarkers = function () {
  var markers = this.getMarkers();
  var bounds = new google.maps.LatLngBounds();

  for (var i = 0, marker; marker = markers[i]; i++) {
    bounds.extend(marker.getPosition());
  }

  this.map_.fitBounds(bounds);
};
/**
 *  Sets the styles.
 *
 *  @param {Object} styles The style to set.
 */


MarkerClusterer.prototype.setStyles = function (styles) {
  this.styles_ = styles;
};
/**
 *  Gets the styles.
 *
 *  @return {Object} The styles object.
 */


MarkerClusterer.prototype.getStyles = function () {
  return this.styles_;
};
/**
 * Whether zoom on click is set.
 *
 * @return {boolean} True if zoomOnClick_ is set.
 */


MarkerClusterer.prototype.isZoomOnClick = function () {
  return this.zoomOnClick_;
};
/**
 * Whether average center is set.
 *
 * @return {boolean} True if averageCenter_ is set.
 */


MarkerClusterer.prototype.isAverageCenter = function () {
  return this.averageCenter_;
};
/**
 *  Returns the array of markers in the clusterer.
 *
 *  @return {Array.<google.maps.Marker>} The markers.
 */


MarkerClusterer.prototype.getMarkers = function () {
  return this.markers_;
};
/**
 *  Returns the number of markers in the clusterer
 *
 *  @return {Number} The number of markers.
 */


MarkerClusterer.prototype.getTotalMarkers = function () {
  return this.markers_.length;
};
/**
 *  Sets the max zoom for the clusterer.
 *
 *  @param {number} maxZoom The max zoom level.
 */


MarkerClusterer.prototype.setMaxZoom = function (maxZoom) {
  this.maxZoom_ = maxZoom;
};
/**
 *  Gets the max zoom for the clusterer.
 *
 *  @return {number} The max zoom level.
 */


MarkerClusterer.prototype.getMaxZoom = function () {
  return this.maxZoom_;
};
/**
 *  The function for calculating the cluster icon image.
 *
 *  @param {Array.<google.maps.Marker>} markers The markers in the clusterer.
 *  @param {number} numStyles The number of styles available.
 *  @return {Object} A object properties: 'text' (string) and 'index' (number).
 *  @private
 */


MarkerClusterer.prototype.calculator_ = function (markers, numStyles) {
  var index = 0;
  var count = markers.length;
  var dv = count;

  while (dv !== 0) {
    dv = parseInt(dv / 10, 10);
    index++;
  }

  index = Math.min(index, numStyles);
  return {
    text: count,
    index: index
  };
};
/**
 * Set the calculator function.
 *
 * @param {function(Array, number)} calculator The function to set as the
 *     calculator. The function should return a object properties:
 *     'text' (string) and 'index' (number).
 *
 */


MarkerClusterer.prototype.setCalculator = function (calculator) {
  this.calculator_ = calculator;
};
/**
 * Get the calculator function.
 *
 * @return {function(Array, number)} the calculator function.
 */


MarkerClusterer.prototype.getCalculator = function () {
  return this.calculator_;
};
/**
 * Add an array of markers to the clusterer.
 *
 * @param {Array.<google.maps.Marker>} markers The markers to add.
 * @param {boolean=} opt_nodraw Whether to redraw the clusters.
 */


MarkerClusterer.prototype.addMarkers = function (markers, opt_nodraw) {
  for (var i = 0, marker; marker = markers[i]; i++) {
    this.pushMarkerTo_(marker);
  }

  if (!opt_nodraw) {
    this.redraw();
  }
};
/**
 * Pushes a marker to the clusterer.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @private
 */


MarkerClusterer.prototype.pushMarkerTo_ = function (marker) {
  marker.isAdded = false;

  if (marker['draggable']) {
    // If the marker is draggable add a listener so we update the clusters on
    // the drag end.
    var that = this;
    google.maps.event.addListener(marker, 'dragend', function () {
      marker.isAdded = false;
      that.repaint();
    });
  }

  this.markers_.push(marker);
};
/**
 * Adds a marker to the clusterer and redraws if needed.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @param {boolean=} opt_nodraw Whether to redraw the clusters.
 */


MarkerClusterer.prototype.addMarker = function (marker, opt_nodraw) {
  this.pushMarkerTo_(marker);

  if (!opt_nodraw) {
    this.redraw();
  }
};
/**
 * Removes a marker and returns true if removed, false if not
 *
 * @param {google.maps.Marker} marker The marker to remove
 * @return {boolean} Whether the marker was removed or not
 * @private
 */


MarkerClusterer.prototype.removeMarker_ = function (marker) {
  var index = -1;

  if (this.markers_.indexOf) {
    index = this.markers_.indexOf(marker);
  } else {
    for (var i = 0, m; m = this.markers_[i]; i++) {
      if (m == marker) {
        index = i;
        break;
      }
    }
  }

  if (index == -1) {
    // Marker is not in our list of markers.
    return false;
  }

  marker.setMap(null);
  this.markers_.splice(index, 1);
  return true;
};
/**
 * Remove a marker from the cluster.
 *
 * @param {google.maps.Marker} marker The marker to remove.
 * @param {boolean=} opt_nodraw Optional boolean to force no redraw.
 * @return {boolean} True if the marker was removed.
 */


MarkerClusterer.prototype.removeMarker = function (marker, opt_nodraw) {
  var removed = this.removeMarker_(marker);

  if (!opt_nodraw && removed) {
    this.resetViewport();
    this.redraw();
    return true;
  } else {
    return false;
  }
};
/**
 * Removes an array of markers from the cluster.
 *
 * @param {Array.<google.maps.Marker>} markers The markers to remove.
 * @param {boolean=} opt_nodraw Optional boolean to force no redraw.
 */


MarkerClusterer.prototype.removeMarkers = function (markers, opt_nodraw) {
  var removed = false;

  for (var i = 0, marker; marker = markers[i]; i++) {
    var r = this.removeMarker_(marker);
    removed = removed || r;
  }

  if (!opt_nodraw && removed) {
    this.resetViewport();
    this.redraw();
    return true;
  }
};
/**
 * Sets the clusterer's ready state.
 *
 * @param {boolean} ready The state.
 * @private
 */


MarkerClusterer.prototype.setReady_ = function (ready) {
  if (!this.ready_) {
    this.ready_ = ready;
    this.createClusters_();
  }
};
/**
 * Returns the number of clusters in the clusterer.
 *
 * @return {number} The number of clusters.
 */


MarkerClusterer.prototype.getTotalClusters = function () {
  return this.clusters_.length;
};
/**
 * Returns the google map that the clusterer is associated with.
 *
 * @return {google.maps.Map} The map.
 */


MarkerClusterer.prototype.getMap = function () {
  return this.map_;
};
/**
 * Sets the google map that the clusterer is associated with.
 *
 * @param {google.maps.Map} map The map.
 */


MarkerClusterer.prototype.setMap = function (map) {
  this.map_ = map;
};
/**
 * Returns the size of the grid.
 *
 * @return {number} The grid size.
 */


MarkerClusterer.prototype.getGridSize = function () {
  return this.gridSize_;
};
/**
 * Sets the size of the grid.
 *
 * @param {number} size The grid size.
 */


MarkerClusterer.prototype.setGridSize = function (size) {
  this.gridSize_ = size;
};
/**
 * Returns the min cluster size.
 *
 * @return {number} The grid size.
 */


MarkerClusterer.prototype.getMinClusterSize = function () {
  return this.minClusterSize_;
};
/**
 * Sets the min cluster size.
 *
 * @param {number} size The grid size.
 */


MarkerClusterer.prototype.setMinClusterSize = function (size) {
  this.minClusterSize_ = size;
};
/**
 * Extends a bounds object by the grid size.
 *
 * @param {google.maps.LatLngBounds} bounds The bounds to extend.
 * @return {google.maps.LatLngBounds} The extended bounds.
 */


MarkerClusterer.prototype.getExtendedBounds = function (bounds) {
  var projection = this.getProjection(); // Turn the bounds into latlng.

  var tr = new google.maps.LatLng(bounds.getNorthEast().lat(), bounds.getNorthEast().lng());
  var bl = new google.maps.LatLng(bounds.getSouthWest().lat(), bounds.getSouthWest().lng()); // Convert the points to pixels and the extend out by the grid size.

  var trPix = projection.fromLatLngToDivPixel(tr);
  trPix.x += this.gridSize_;
  trPix.y -= this.gridSize_;
  var blPix = projection.fromLatLngToDivPixel(bl);
  blPix.x -= this.gridSize_;
  blPix.y += this.gridSize_; // Convert the pixel points back to LatLng

  var ne = projection.fromDivPixelToLatLng(trPix);
  var sw = projection.fromDivPixelToLatLng(blPix); // Extend the bounds to contain the new bounds.

  bounds.extend(ne);
  bounds.extend(sw);
  return bounds;
};
/**
 * Determins if a marker is contained in a bounds.
 *
 * @param {google.maps.Marker} marker The marker to check.
 * @param {google.maps.LatLngBounds} bounds The bounds to check against.
 * @return {boolean} True if the marker is in the bounds.
 * @private
 */


MarkerClusterer.prototype.isMarkerInBounds_ = function (marker, bounds) {
  return bounds.contains(marker.getPosition());
};
/**
 * Clears all clusters and markers from the clusterer.
 */


MarkerClusterer.prototype.clearMarkers = function () {
  this.resetViewport(true); // Set the markers a empty array.

  this.markers_ = [];
};
/**
 * Clears all existing clusters and recreates them.
 * @param {boolean} opt_hide To also hide the marker.
 */


MarkerClusterer.prototype.resetViewport = function (opt_hide) {
  // Remove all the clusters
  for (var i = 0, cluster; cluster = this.clusters_[i]; i++) {
    cluster.remove();
  } // Reset the markers to not be added and to be invisible.


  for (var i = 0, marker; marker = this.markers_[i]; i++) {
    marker.isAdded = false;

    if (opt_hide) {
      marker.setMap(null);
    }
  }

  this.clusters_ = [];
};
/**
 *
 */


MarkerClusterer.prototype.repaint = function () {
  var oldClusters = this.clusters_.slice();
  this.clusters_.length = 0;
  this.resetViewport();
  this.redraw(); // Remove the old clusters.
  // Do it in a timeout so the other clusters have been drawn first.

  window.setTimeout(function () {
    for (var i = 0, cluster; cluster = oldClusters[i]; i++) {
      cluster.remove();
    }
  }, 0);
};
/**
 * Redraws the clusters.
 */


MarkerClusterer.prototype.redraw = function () {
  this.createClusters_();
};
/**
 * Calculates the distance between two latlng locations in km.
 * @see http://www.movable-type.co.uk/scripts/latlong.html
 *
 * @param {google.maps.LatLng} p1 The first lat lng point.
 * @param {google.maps.LatLng} p2 The second lat lng point.
 * @return {number} The distance between the two points in km.
 * @private
 */


MarkerClusterer.prototype.distanceBetweenPoints_ = function (p1, p2) {
  if (!p1 || !p2) {
    return 0;
  }

  var R = 6371; // Radius of the Earth in km

  var dLat = (p2.lat() - p1.lat()) * Math.PI / 180;
  var dLon = (p2.lng() - p1.lng()) * Math.PI / 180;
  var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(p1.lat() * Math.PI / 180) * Math.cos(p2.lat() * Math.PI / 180) * Math.sin(dLon / 2) * Math.sin(dLon / 2);
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  var d = R * c;
  return d;
};
/**
 * Add a marker to a cluster, or creates a new cluster.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @private
 */


MarkerClusterer.prototype.addToClosestCluster_ = function (marker) {
  var distance = 40000; // Some large number

  var clusterToAddTo = null;
  var pos = marker.getPosition();

  for (var i = 0, cluster; cluster = this.clusters_[i]; i++) {
    var center = cluster.getCenter();

    if (center) {
      var d = this.distanceBetweenPoints_(center, marker.getPosition());

      if (d < distance) {
        distance = d;
        clusterToAddTo = cluster;
      }
    }
  }

  if (clusterToAddTo && clusterToAddTo.isMarkerInClusterBounds(marker)) {
    clusterToAddTo.addMarker(marker);
  } else {
    var cluster = new Cluster(this);
    cluster.addMarker(marker);
    this.clusters_.push(cluster);
  }
};
/**
 * Creates the clusters.
 *
 * @private
 */


MarkerClusterer.prototype.createClusters_ = function () {
  if (!this.ready_) {
    return;
  } // Get our current map view bounds.
  // Create a new bounds object so we don't affect the map.


  var mapBounds = new google.maps.LatLngBounds(this.map_.getBounds().getSouthWest(), this.map_.getBounds().getNorthEast());
  var bounds = this.getExtendedBounds(mapBounds);

  for (var i = 0, marker; marker = this.markers_[i]; i++) {
    if (!marker.isAdded && this.isMarkerInBounds_(marker, bounds)) {
      this.addToClosestCluster_(marker);
    }
  }
};
/**
 * A cluster that contains markers.
 *
 * @param {MarkerClusterer} markerClusterer The markerclusterer that this
 *     cluster is associated with.
 * @constructor
 * @ignore
 */


function Cluster(markerClusterer) {
  this.markerClusterer_ = markerClusterer;
  this.map_ = markerClusterer.getMap();
  this.gridSize_ = markerClusterer.getGridSize();
  this.minClusterSize_ = markerClusterer.getMinClusterSize();
  this.averageCenter_ = markerClusterer.isAverageCenter();
  this.center_ = null;
  this.markers_ = [];
  this.bounds_ = null;
  this.clusterIcon_ = new ClusterIcon(this, markerClusterer.getStyles(), markerClusterer.getGridSize());
}
/**
 * Determins if a marker is already added to the cluster.
 *
 * @param {google.maps.Marker} marker The marker to check.
 * @return {boolean} True if the marker is already added.
 */


Cluster.prototype.isMarkerAlreadyAdded = function (marker) {
  if (this.markers_.indexOf) {
    return this.markers_.indexOf(marker) != -1;
  } else {
    for (var i = 0, m; m = this.markers_[i]; i++) {
      if (m == marker) {
        return true;
      }
    }
  }

  return false;
};
/**
 * Add a marker the cluster.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @return {boolean} True if the marker was added.
 */


Cluster.prototype.addMarker = function (marker) {
  if (this.isMarkerAlreadyAdded(marker)) {
    return false;
  }

  if (!this.center_) {
    this.center_ = marker.getPosition();
    this.calculateBounds_();
  } else {
    if (this.averageCenter_) {
      var l = this.markers_.length + 1;
      var lat = (this.center_.lat() * (l - 1) + marker.getPosition().lat()) / l;
      var lng = (this.center_.lng() * (l - 1) + marker.getPosition().lng()) / l;
      this.center_ = new google.maps.LatLng(lat, lng);
      this.calculateBounds_();
    }
  }

  marker.isAdded = true;
  this.markers_.push(marker);
  var len = this.markers_.length;

  if (len < this.minClusterSize_ && marker.getMap() != this.map_) {
    // Min cluster size not reached so show the marker.
    marker.setMap(this.map_);
  }

  if (len == this.minClusterSize_) {
    // Hide the markers that were showing.
    for (var i = 0; i < len; i++) {
      this.markers_[i].setMap(null);
    }
  }

  if (len >= this.minClusterSize_) {
    marker.setMap(null);
  }

  this.updateIcon();
  return true;
};
/**
 * Returns the marker clusterer that the cluster is associated with.
 *
 * @return {MarkerClusterer} The associated marker clusterer.
 */


Cluster.prototype.getMarkerClusterer = function () {
  return this.markerClusterer_;
};
/**
 * Returns the bounds of the cluster.
 *
 * @return {google.maps.LatLngBounds} the cluster bounds.
 */


Cluster.prototype.getBounds = function () {
  var bounds = new google.maps.LatLngBounds(this.center_, this.center_);
  var markers = this.getMarkers();

  for (var i = 0, marker; marker = markers[i]; i++) {
    bounds.extend(marker.getPosition());
  }

  return bounds;
};
/**
 * Removes the cluster
 */


Cluster.prototype.remove = function () {
  this.clusterIcon_.remove();
  this.markers_.length = 0;
  delete this.markers_;
};
/**
 * Returns the center of the cluster.
 *
 * @return {number} The cluster center.
 */


Cluster.prototype.getSize = function () {
  return this.markers_.length;
};
/**
 * Returns the center of the cluster.
 *
 * @return {Array.<google.maps.Marker>} The cluster center.
 */


Cluster.prototype.getMarkers = function () {
  return this.markers_;
};
/**
 * Returns the center of the cluster.
 *
 * @return {google.maps.LatLng} The cluster center.
 */


Cluster.prototype.getCenter = function () {
  return this.center_;
};
/**
 * Calculated the extended bounds of the cluster with the grid.
 *
 * @private
 */


Cluster.prototype.calculateBounds_ = function () {
  var bounds = new google.maps.LatLngBounds(this.center_, this.center_);
  this.bounds_ = this.markerClusterer_.getExtendedBounds(bounds);
};
/**
 * Determines if a marker lies in the clusters bounds.
 *
 * @param {google.maps.Marker} marker The marker to check.
 * @return {boolean} True if the marker lies in the bounds.
 */


Cluster.prototype.isMarkerInClusterBounds = function (marker) {
  return this.bounds_.contains(marker.getPosition());
};
/**
 * Returns the map that the cluster is associated with.
 *
 * @return {google.maps.Map} The map.
 */


Cluster.prototype.getMap = function () {
  return this.map_;
};
/**
 * Updates the cluster icon
 */


Cluster.prototype.updateIcon = function () {
  var zoom = this.map_.getZoom();
  var mz = this.markerClusterer_.getMaxZoom();

  if (mz && zoom > mz) {
    // The zoom is greater than our max zoom so show all the markers in cluster.
    for (var i = 0, marker; marker = this.markers_[i]; i++) {
      marker.setMap(this.map_);
    }

    return;
  }

  if (this.markers_.length < this.minClusterSize_) {
    // Min cluster size not yet reached.
    this.clusterIcon_.hide();
    return;
  }

  var numStyles = this.markerClusterer_.getStyles().length;
  var sums = this.markerClusterer_.getCalculator()(this.markers_, numStyles);
  this.clusterIcon_.setCenter(this.center_);
  this.clusterIcon_.setSums(sums);
  this.clusterIcon_.show();
};
/**
 * A cluster icon
 *
 * @param {Cluster} cluster The cluster to be associated with.
 * @param {Object} styles An object that has style properties:
 *     'url': (string) The image url.
 *     'height': (number) The image height.
 *     'width': (number) The image width.
 *     'anchor': (Array) The anchor position of the label text.
 *     'textColor': (string) The text color.
 *     'textSize': (number) The text size.
 *     'backgroundPosition: (string) The background postition x, y.
 * @param {number=} opt_padding Optional padding to apply to the cluster icon.
 * @constructor
 * @extends google.maps.OverlayView
 * @ignore
 */


function ClusterIcon(cluster, styles, opt_padding) {
  cluster.getMarkerClusterer().extend(ClusterIcon, google.maps.OverlayView);
  this.styles_ = styles;
  this.padding_ = opt_padding || 0;
  this.cluster_ = cluster;
  this.center_ = null;
  this.map_ = cluster.getMap();
  this.div_ = null;
  this.sums_ = null;
  this.visible_ = false;
  this.setMap(this.map_);
}
/**
 * Triggers the clusterclick event and zoom's if the option is set.
 */


ClusterIcon.prototype.triggerClusterClick = function () {
  var markerClusterer = this.cluster_.getMarkerClusterer(); // Trigger the clusterclick event.

  google.maps.event.trigger(markerClusterer, 'clusterclick', this.cluster_);

  if (markerClusterer.isZoomOnClick()) {
    // Zoom into the cluster.
    this.map_.fitBounds(this.cluster_.getBounds());
  }
};
/**
 * Adding the cluster icon to the dom.
 * @ignore
 */


ClusterIcon.prototype.onAdd = function () {
  this.div_ = document.createElement('DIV');

  if (this.visible_) {
    var pos = this.getPosFromLatLng_(this.center_);
    this.div_.style.cssText = this.createCss(pos);
    this.div_.innerHTML = this.sums_.text;
    var markerClusterer = this.cluster_.getMarkerClusterer();

    if (markerClusterer.cssClass_) {
      this.div_.className = markerClusterer.cssClass_;
    }
  }

  var panes = this.getPanes();
  panes.overlayMouseTarget.appendChild(this.div_);
  var that = this;
  google.maps.event.addDomListener(this.div_, 'click', function () {
    that.triggerClusterClick();
  });
};
/**
 * Returns the position to place the div dending on the latlng.
 *
 * @param {google.maps.LatLng} latlng The position in latlng.
 * @return {google.maps.Point} The position in pixels.
 * @private
 */


ClusterIcon.prototype.getPosFromLatLng_ = function (latlng) {
  var pos = this.getProjection().fromLatLngToDivPixel(latlng);
  pos.x -= parseInt(this.width_ / 2, 10);
  pos.y -= parseInt(this.height_ / 2, 10);
  return pos;
};
/**
 * Draw the icon.
 * @ignore
 */


ClusterIcon.prototype.draw = function () {
  if (this.visible_) {
    var pos = this.getPosFromLatLng_(this.center_);
    this.div_.style.top = pos.y + 'px';
    this.div_.style.left = pos.x + 'px';
  }
};
/**
 * Hide the icon.
 */


ClusterIcon.prototype.hide = function () {
  if (this.div_) {
    this.div_.style.display = 'none';
  }

  this.visible_ = false;
};
/**
 * Position and show the icon.
 */


ClusterIcon.prototype.show = function () {
  if (this.div_) {
    var pos = this.getPosFromLatLng_(this.center_);
    this.div_.style.cssText = this.createCss(pos);
    this.div_.style.display = '';
  }

  this.visible_ = true;
};
/**
 * Remove the icon from the map
 */


ClusterIcon.prototype.remove = function () {
  this.setMap(null);
};
/**
 * Implementation of the onRemove interface.
 * @ignore
 */


ClusterIcon.prototype.onRemove = function () {
  if (this.div_ && this.div_.parentNode) {
    this.hide();
    this.div_.parentNode.removeChild(this.div_);
    this.div_ = null;
  }
};
/**
 * Set the sums of the icon.
 *
 * @param {Object} sums The sums containing:
 *   'text': (string) The text to display in the icon.
 *   'index': (number) The style index of the icon.
 */


ClusterIcon.prototype.setSums = function (sums) {
  this.sums_ = sums;
  this.text_ = sums.text;
  this.index_ = sums.index;

  if (this.div_) {
    this.div_.innerHTML = sums.text;
  }

  this.useStyle();
};
/**
 * Sets the icon to the the styles.
 */


ClusterIcon.prototype.useStyle = function () {
  var index = Math.max(0, this.sums_.index - 1);
  index = Math.min(this.styles_.length - 1, index);
  var style = this.styles_[index];
  this.url_ = style['url'];
  this.height_ = style['height'];
  this.width_ = style['width'];
  this.textColor_ = style['textColor'];
  this.anchor_ = style['anchor'];
  this.textSize_ = style['textSize'];
  this.backgroundPosition_ = style['backgroundPosition'];
};
/**
 * Sets the center of the icon.
 *
 * @param {google.maps.LatLng} center The latlng to set as the center.
 */


ClusterIcon.prototype.setCenter = function (center) {
  this.center_ = center;
};
/**
 * Create the css text based on the position of the icon.
 *
 * @param {google.maps.Point} pos The position.
 * @return {string} The css style text.
 */


ClusterIcon.prototype.createCss = function (pos) {
  var style = [];
  var markerClusterer = this.cluster_.getMarkerClusterer();

  if (!markerClusterer.cssClass_) {
    style.push('background-image:url(' + this.url_ + ');');
    var backgroundPosition = this.backgroundPosition_ ? this.backgroundPosition_ : '0 0';
    style.push('background-position:' + backgroundPosition + ';');

    if (_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default()(this.anchor_) === 'object') {
      if (typeof this.anchor_[0] === 'number' && this.anchor_[0] > 0 && this.anchor_[0] < this.height_) {
        style.push('height:' + (this.height_ - this.anchor_[0]) + 'px; padding-top:' + this.anchor_[0] + 'px;');
      } else {
        style.push('height:' + this.height_ + 'px; line-height:' + this.height_ + 'px;');
      }

      if (typeof this.anchor_[1] === 'number' && this.anchor_[1] > 0 && this.anchor_[1] < this.width_) {
        style.push('width:' + (this.width_ - this.anchor_[1]) + 'px; padding-left:' + this.anchor_[1] + 'px;');
      } else {
        style.push('width:' + this.width_ + 'px; text-align:center;');
      }
    } else {
      style.push('height:' + this.height_ + 'px; line-height:' + this.height_ + 'px; width:' + this.width_ + 'px; text-align:center;');
    }

    var txtColor = this.textColor_ ? this.textColor_ : 'black';
    var txtSize = this.textSize_ ? this.textSize_ : 11;
    style.push('cursor:pointer; color:' + txtColor + '; position:absolute; font-size:' + txtSize + 'px; font-family:Arial,sans-serif; font-weight:bold');
  } else {
    style.push('top:' + pos.y + 'px; left:' + pos.x + 'px;');
  }

  return style.join('');
}; // Export Symbols for Closure
// If you are not going to compile with closure then you can remove the
// code below.


window['MarkerClusterer'] = MarkerClusterer;
MarkerClusterer.prototype['addMarker'] = MarkerClusterer.prototype.addMarker;
MarkerClusterer.prototype['addMarkers'] = MarkerClusterer.prototype.addMarkers;
MarkerClusterer.prototype['clearMarkers'] = MarkerClusterer.prototype.clearMarkers;
MarkerClusterer.prototype['fitMapToMarkers'] = MarkerClusterer.prototype.fitMapToMarkers;
MarkerClusterer.prototype['getCalculator'] = MarkerClusterer.prototype.getCalculator;
MarkerClusterer.prototype['getGridSize'] = MarkerClusterer.prototype.getGridSize;
MarkerClusterer.prototype['getExtendedBounds'] = MarkerClusterer.prototype.getExtendedBounds;
MarkerClusterer.prototype['getMap'] = MarkerClusterer.prototype.getMap;
MarkerClusterer.prototype['getMarkers'] = MarkerClusterer.prototype.getMarkers;
MarkerClusterer.prototype['getMaxZoom'] = MarkerClusterer.prototype.getMaxZoom;
MarkerClusterer.prototype['getStyles'] = MarkerClusterer.prototype.getStyles;
MarkerClusterer.prototype['getTotalClusters'] = MarkerClusterer.prototype.getTotalClusters;
MarkerClusterer.prototype['getTotalMarkers'] = MarkerClusterer.prototype.getTotalMarkers;
MarkerClusterer.prototype['redraw'] = MarkerClusterer.prototype.redraw;
MarkerClusterer.prototype['removeMarker'] = MarkerClusterer.prototype.removeMarker;
MarkerClusterer.prototype['removeMarkers'] = MarkerClusterer.prototype.removeMarkers;
MarkerClusterer.prototype['resetViewport'] = MarkerClusterer.prototype.resetViewport;
MarkerClusterer.prototype['repaint'] = MarkerClusterer.prototype.repaint;
MarkerClusterer.prototype['setCalculator'] = MarkerClusterer.prototype.setCalculator;
MarkerClusterer.prototype['setGridSize'] = MarkerClusterer.prototype.setGridSize;
MarkerClusterer.prototype['setMaxZoom'] = MarkerClusterer.prototype.setMaxZoom;
MarkerClusterer.prototype['onAdd'] = MarkerClusterer.prototype.onAdd;
MarkerClusterer.prototype['draw'] = MarkerClusterer.prototype.draw;
Cluster.prototype['getCenter'] = Cluster.prototype.getCenter;
Cluster.prototype['getSize'] = Cluster.prototype.getSize;
Cluster.prototype['getMarkers'] = Cluster.prototype.getMarkers;
ClusterIcon.prototype['onAdd'] = ClusterIcon.prototype.onAdd;
ClusterIcon.prototype['draw'] = ClusterIcon.prototype.draw;
ClusterIcon.prototype['onRemove'] = ClusterIcon.prototype.onRemove;

/***/ }),

/***/ "./assets/src/js/global/map-scripts/openstreet-map.js":
/*!************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/openstreet-map.js ***!
  \************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _add_listing_openstreet_map__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./add-listing/openstreet-map */ "./assets/src/js/global/map-scripts/add-listing/openstreet-map.js");
/* harmony import */ var _single_listing_openstreet_map__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./single-listing/openstreet-map */ "./assets/src/js/global/map-scripts/single-listing/openstreet-map.js");
/* harmony import */ var _single_listing_openstreet_map__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_single_listing_openstreet_map__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _single_listing_openstreet_map_widget__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./single-listing/openstreet-map-widget */ "./assets/src/js/global/map-scripts/single-listing/openstreet-map-widget.js");
/* harmony import */ var _single_listing_openstreet_map_widget__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_single_listing_openstreet_map_widget__WEBPACK_IMPORTED_MODULE_2__);
;

(function () {
  window.addEventListener('DOMContentLoaded', function () {
    var $ = jQuery;
    var mapData;
    $('#map').length ? mapData = JSON.parse($('#map').attr('data-options')) : '';
    window.addEventListener('load', setup_map);
    window.addEventListener('directorist-reload-listings-map-archive', setup_map);

    function setup_map() {
      bundle1.fillPlaceholders();
      var localVersion = bundle1.getLibVersion('leaflet.featuregroup.subgroup', 'local');

      if (localVersion) {
        localVersion.checkAssetsAvailability(true).then(function () {
          mapData !== undefined ? load() : '';
        }).catch(function () {
          var version102 = bundle1.getLibVersion('leaflet.featuregroup.subgroup', '1.0.2');

          if (version102) {
            version102.defaultVersion = true;
          }

          mapData !== undefined ? load() : '';
        });
      } else {
        mapData !== undefined ? load() : '';
      }
    }

    function load() {
      var url = window.location.href;
      var urlParts = URI.parse(url);
      var queryStringParts = URI.parseQuery(urlParts.query);
      var list = bundle1.getAndSelectVersionsAssetsList(queryStringParts);
      list.push({
        type: 'script',
        path: mapData.openstreet_script
      });
      loadJsCss.list(list, {
        delayScripts: 500 // Load scripts after stylesheets, delayed by this duration (in ms).

      });
    }
  });
})();
/* Add listing OSMap */



/* Single listing OSMap */


/* Widget OSMap */



/***/ }),

/***/ "./assets/src/js/global/map-scripts/single-listing/google-map-widget.js":
/*!******************************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/single-listing/google-map-widget.js ***!
  \******************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/* Widget google map */
window.addEventListener('DOMContentLoaded', function () {
  ;

  (function ($) {
    if ($('#gmap-widget').length) {
      var MAP_PIN = 'M0-48c-9.8 0-17.7 7.8-17.7 17.4 0 15.5 17.7 30.6 17.7 30.6s17.7-15.4 17.7-30.6c0-9.6-7.9-17.4-17.7-17.4z';

      var inherits = function inherits(childCtor, parentCtor) {
        /** @constructor */
        function tempCtor() {}

        tempCtor.prototype = parentCtor.prototype;
        childCtor.superClass_ = parentCtor.prototype;
        childCtor.prototype = new tempCtor();
        childCtor.prototype.constructor = childCtor;
      };

      function Marker(options) {
        google.maps.Marker.apply(this, arguments);

        if (options.map_icon_label) {
          this.MarkerLabel = new MarkerLabel({
            map: this.map,
            marker: this,
            text: options.map_icon_label
          });
          this.MarkerLabel.bindTo('position', this, 'position');
        }
      } // Apply the inheritance


      inherits(Marker, google.maps.Marker); // Custom Marker SetMap

      Marker.prototype.setMap = function () {
        google.maps.Marker.prototype.setMap.apply(this, arguments);
        this.MarkerLabel && this.MarkerLabel.setMap.apply(this.MarkerLabel, arguments);
      }; // Marker Label Overlay


      var MarkerLabel = function MarkerLabel(options) {
        var self = this;
        this.setValues(options); // Create the label container

        this.div = document.createElement('div');
        this.div.className = 'map-icon-label'; // Trigger the marker click handler if clicking on the label

        google.maps.event.addDomListener(this.div, 'click', function (e) {
          e.stopPropagation && e.stopPropagation();
          google.maps.event.trigger(self.marker, 'click');
        });
      }; // Create MarkerLabel Object


      MarkerLabel.prototype = new google.maps.OverlayView(); // Marker Label onAdd

      MarkerLabel.prototype.onAdd = function () {
        var pane = this.getPanes().overlayImage.appendChild(this.div);
        var self = this;
        this.listeners = [google.maps.event.addListener(this, 'position_changed', function () {
          self.draw();
        }), google.maps.event.addListener(this, 'text_changed', function () {
          self.draw();
        }), google.maps.event.addListener(this, 'zindex_changed', function () {
          self.draw();
        })];
      }; // Marker Label onRemove


      MarkerLabel.prototype.onRemove = function () {
        this.div.parentNode.removeChild(this.div);

        for (var i = 0, I = this.listeners.length; i < I; ++i) {
          google.maps.event.removeListener(this.listeners[i]);
        }
      }; // Implement draw


      MarkerLabel.prototype.draw = function () {
        var projection = this.getProjection();
        var position = projection.fromLatLngToDivPixel(this.get('position'));
        var div = this.div;
        this.div.innerHTML = this.get('text').toString();
        div.style.zIndex = this.get('zIndex'); // Allow label to overlay marker

        div.style.position = 'absolute';
        div.style.display = 'block';
        div.style.left = position.x - div.offsetWidth / 2 + 'px';
        div.style.top = position.y - div.offsetHeight + 'px';
      };

      $(document).ready(function () {
        // initialize all vars here to avoid hoisting related misunderstanding.
        var map, info_window, saved_lat_lng, info_content; // Localized Data

        var map_container = localized_data_widget.map_container_id ? localized_data_widget.map_container_id : 'gmap';
        var loc_default_latitude = parseFloat(localized_data_widget.default_latitude);
        var loc_default_longitude = parseFloat(localized_data_widget.default_longitude);
        var loc_manual_lat = parseFloat(localized_data_widget.manual_lat);
        var loc_manual_lng = parseFloat(localized_data_widget.manual_lng);
        var loc_map_zoom_level = parseInt(localized_data_widget.map_zoom_level);
        var display_map_info = localized_data_widget.display_map_info;
        var cat_icon = localized_data_widget.cat_icon;
        var info_content = localized_data_widget.info_content;
        loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;
        loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;
        $manual_lat = $('#manual_lat');
        $manual_lng = $('#manual_lng');
        saved_lat_lng = {
          lat: loc_manual_lat,
          lng: loc_manual_lng
        }; // create an info window for map

        if (display_map_info) {
          info_window = new google.maps.InfoWindow({
            content: info_content,
            maxWidth: 400
            /*Add configuration for max width*/

          });
        }

        function initMap() {
          /* Create new map instance*/
          map = new google.maps.Map(document.getElementById(map_container), {
            zoom: loc_map_zoom_level,
            center: saved_lat_lng
          });
          /*var marker = new google.maps.Marker({
              map: map,
              position: saved_lat_lng
          });*/

          var marker = new Marker({
            position: saved_lat_lng,
            map: map,
            icon: {
              path: MAP_PIN,
              fillColor: 'transparent',
              fillOpacity: 1,
              strokeColor: '',
              strokeWeight: 0
            },
            map_icon_label: '<div class="atbd_map_shape"><i class="' + cat_icon + '"></i></div>'
          });

          if (display_map_info) {
            marker.addListener('click', function () {
              info_window.open(map, marker);
            });
            google.maps.event.addListener(info_window, 'domready', function () {
              var closeBtn = $('.iw-close-btn').get();
              google.maps.event.addDomListener(closeBtn[0], 'click', function () {
                info_window.close();
              });
            });
          }
        }

        initMap(); //Convert address tags to google map links -

        $('address').each(function () {
          var link = "<a href='http://maps.google.com/maps?q=" + encodeURIComponent($(this).text()) + "' target='_blank'>" + $(this).text() + "</a>";
          $(this).html(link);
        });
      });
    }
  })(jQuery);
});

/***/ }),

/***/ "./assets/src/js/global/map-scripts/single-listing/google-map.js":
/*!***********************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/single-listing/google-map.js ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/* Single listing google map */
window.addEventListener('DOMContentLoaded', function () {
  ;

  (function ($) {
    if ($('.directorist-details-info-wrap #directorist-single-map').length) {
      var MAP_PIN = 'M0-48c-9.8 0-17.7 7.8-17.7 17.4 0 15.5 17.7 30.6 17.7 30.6s17.7-15.4 17.7-30.6c0-9.6-7.9-17.4-17.7-17.4z';

      var inherits = function inherits(childCtor, parentCtor) {
        /** @constructor */
        function tempCtor() {}

        tempCtor.prototype = parentCtor.prototype;
        childCtor.superClass_ = parentCtor.prototype;
        childCtor.prototype = new tempCtor();
        childCtor.prototype.constructor = childCtor;
      };

      function Marker(options) {
        google.maps.Marker.apply(this, arguments);

        if (options.map_icon_label) {
          this.MarkerLabel = new MarkerLabel({
            map: this.map,
            marker: this,
            text: options.map_icon_label
          });
          this.MarkerLabel.bindTo('position', this, 'position');
        }
      } // Apply the inheritance


      inherits(Marker, google.maps.Marker); // Custom Marker SetMap

      Marker.prototype.setMap = function () {
        google.maps.Marker.prototype.setMap.apply(this, arguments);
        this.MarkerLabel && this.MarkerLabel.setMap.apply(this.MarkerLabel, arguments);
      }; // Marker Label Overlay


      var MarkerLabel = function MarkerLabel(options) {
        var self = this;
        this.setValues(options); // Create the label container

        this.div = document.createElement('div');
        this.div.className = 'map-icon-label'; // Trigger the marker click handler if clicking on the label

        google.maps.event.addDomListener(this.div, 'click', function (e) {
          e.stopPropagation && e.stopPropagation();
          google.maps.event.trigger(self.marker, 'click');
        });
      }; // Create MarkerLabel Object


      MarkerLabel.prototype = new google.maps.OverlayView(); // Marker Label onAdd

      MarkerLabel.prototype.onAdd = function () {
        var pane = this.getPanes().overlayImage.appendChild(this.div);
        var self = this;
        this.listeners = [google.maps.event.addListener(this, 'position_changed', function () {
          self.draw();
        }), google.maps.event.addListener(this, 'text_changed', function () {
          self.draw();
        }), google.maps.event.addListener(this, 'zindex_changed', function () {
          self.draw();
        })];
      }; // Marker Label onRemove


      MarkerLabel.prototype.onRemove = function () {
        this.div.parentNode.removeChild(this.div);

        for (var i = 0, I = this.listeners.length; i < I; ++i) {
          google.maps.event.removeListener(this.listeners[i]);
        }
      }; // Implement draw


      MarkerLabel.prototype.draw = function () {
        var projection = this.getProjection();
        var position = projection.fromLatLngToDivPixel(this.get('position'));
        var div = this.div;
        this.div.innerHTML = this.get('text').toString();
        div.style.zIndex = this.get('zIndex'); // Allow label to overlay marker

        div.style.position = 'absolute';
        div.style.display = 'block';
        div.style.left = position.x - div.offsetWidth / 2 + 'px';
        div.style.top = position.y - div.offsetHeight + 'px';
      };

      $(document).ready(function () {
        // initialize all vars here to avoid hoisting related misunderstanding.
        var map, info_window, saved_lat_lng, info_content; // Localized Data

        var mapWrapper = document.querySelector('#directorist-single-map');
        var mapData = JSON.parse(mapWrapper.getAttribute('data-map'));
        var map_container = mapData.map_container_id ? mapData.map_container_id : 'directorist-single-map';
        var loc_default_latitude = parseFloat(mapData.default_latitude);
        var loc_default_longitude = parseFloat(mapData.default_longitude);
        var loc_manual_lat = parseFloat(mapData.manual_lat);
        var loc_manual_lng = parseFloat(mapData.manual_lng);
        var loc_map_zoom_level = parseInt(mapData.map_zoom_level);
        var display_map_info = mapData.display_map_info;
        var cat_icon = mapData.cat_icon;
        var info_content = mapData.info_content;
        loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;
        loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;
        $manual_lat = $('#manual_lat');
        $manual_lng = $('#manual_lng');
        saved_lat_lng = {
          lat: loc_manual_lat,
          lng: loc_manual_lng
        }; // create an info window for map

        if (display_map_info) {
          info_window = new google.maps.InfoWindow({
            content: info_content,
            maxWidth: 400
            /*Add configuration for max width*/

          });
        }

        function initMap() {
          /* Create new map instance*/
          map = new google.maps.Map(document.getElementById(map_container), {
            zoom: loc_map_zoom_level,
            center: saved_lat_lng
          });
          /*var marker = new google.maps.Marker({
              map: map,
              position: saved_lat_lng
          });*/

          var marker = new Marker({
            position: saved_lat_lng,
            map: map,
            icon: {
              path: MAP_PIN,
              fillColor: 'transparent',
              fillOpacity: 1,
              strokeColor: '',
              strokeWeight: 0
            },
            map_icon_label: '<div class="atbd_map_shape"><i class="' + cat_icon + '"></i></div>'
          });

          if (display_map_info) {
            marker.addListener('click', function () {
              info_window.open(map, marker);
            });
            google.maps.event.addListener(info_window, 'domready', function () {
              var closeBtn = $('.iw-close-btn').get();
              google.maps.event.addDomListener(closeBtn[0], 'click', function () {
                info_window.close();
              });
            });
          }
        }

        initMap(); //Convert address tags to google map links -

        $('address').each(function () {
          var link = "<a href='http://maps.google.com/maps?q=" + encodeURIComponent($(this).text()) + "' target='_blank'>" + $(this).text() + "</a>";
          $(this).html(link);
        });
      });
    }
  })(jQuery);
});

/***/ }),

/***/ "./assets/src/js/global/map-scripts/single-listing/openstreet-map-widget.js":
/*!**********************************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/single-listing/openstreet-map-widget.js ***!
  \**********************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/* Widget OSMap */
;

(function ($) {
  jQuery(document).ready(function () {
    // Localized Data
    if ($('#gmap-widget').length) {
      var map_container = localized_data_widget.map_container_id ? localized_data_widget.map_container_id : 'gmap';
      var loc_default_latitude = parseFloat(localized_data_widget.default_latitude);
      var loc_default_longitude = parseFloat(localized_data_widget.default_longitude);
      var loc_manual_lat = parseFloat(localized_data_widget.manual_lat);
      var loc_manual_lng = parseFloat(localized_data_widget.manual_lng);
      var loc_map_zoom_level = parseInt(localized_data_widget.map_zoom_level);
      var _localized_data_widge = localized_data_widget,
          display_map_info = _localized_data_widge.display_map_info;
      var _localized_data_widge2 = localized_data_widget,
          cat_icon = _localized_data_widge2.cat_icon;
      var _localized_data_widge3 = localized_data_widget,
          info_content = _localized_data_widge3.info_content;
      loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;
      loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;
      $manual_lat = $('#manual_lat');
      $manual_lng = $('#manual_lng');
      saved_lat_lng = {
        lat: loc_manual_lat,
        lng: loc_manual_lng
      };

      function mapLeaflet(lat, lon) {
        var fontAwesomeIcon = L.divIcon({
          html: "<div class=\"atbd_map_shape\"><span class=\"".concat(cat_icon, "\"></span></div>"),
          iconSize: [20, 20],
          className: 'myDivIcon'
        });
        var mymap = L.map(map_container).setView([lat, lon], loc_map_zoom_level);

        if (display_map_info) {
          L.marker([lat, lon], {
            icon: fontAwesomeIcon
          }).addTo(mymap).bindPopup(info_content);
        } else {
          L.marker([lat, lon], {
            icon: fontAwesomeIcon
          }).addTo(mymap);
        }

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mymap);
      }

      mapLeaflet(loc_manual_lat, loc_manual_lng);
    }
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/global/map-scripts/single-listing/openstreet-map.js":
/*!***************************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/single-listing/openstreet-map.js ***!
  \***************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/* Single listing OSMap */
(function ($) {
  jQuery(document).ready(function () {
    // Localized Data
    if ($('.directorist-details-info-wrap .directorist-single-map').length) {
      var mapData = JSON.parse($('.directorist-single-map').attr('data-map'));
      var map_container = mapData.map_container_id ? mapData.map_container_id : 'directorist-single-map';
      var loc_default_latitude = parseFloat(mapData.default_latitude);
      var loc_default_longitude = parseFloat(mapData.default_longitude);
      var loc_manual_lat = parseFloat(mapData.manual_lat);
      var loc_manual_lng = parseFloat(mapData.manual_lng);
      var loc_map_zoom_level = parseInt(mapData.map_zoom_level);
      var display_map_info = mapData.display_map_info;
      var cat_icon = mapData.cat_icon;
      var info_content = mapData.info_content;
      loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;
      loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;
      $manual_lat = $('#manual_lat');
      $manual_lng = $('#manual_lng');
      saved_lat_lng = {
        lat: loc_manual_lat,
        lng: loc_manual_lng
      };

      function mapLeaflet(lat, lon) {
        var fontAwesomeIcon = L.divIcon({
          html: "<div class=\"atbd_map_shape\"><span class=\"".concat(cat_icon, "\"></span></div>"),
          iconSize: [20, 20],
          className: 'myDivIcon'
        });
        var mymap = L.map(map_container).setView([lat, lon], loc_map_zoom_level);

        if (display_map_info) {
          L.marker([lat, lon], {
            icon: fontAwesomeIcon
          }).addTo(mymap).bindPopup(info_content);
        } else {
          L.marker([lat, lon], {
            icon: fontAwesomeIcon
          }).addTo(mymap);
        }

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mymap);
      }

      mapLeaflet(loc_manual_lat, loc_manual_lng);
    }
  });
})(jQuery);

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

/***/ "./assets/src/js/public/components/author.js":
/*!***************************************************!*\
  !*** ./assets/src/js/public/components/author.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// author sorting
(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
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
      _this = $(this);
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
          console.log(_error);
        }
      });
    });
    /* authors pagination */

    $('body').on('click', '.directorist-authors-pagination a', function (e) {
      e.preventDefault();
      var paged = $(this).attr('href');
      paged = paged.split('/page/')[1];
      paged = parseInt(paged);
      paged = paged !== undefined ? paged : 1;
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
        },
        error: function error(_error2) {
          console.log(_error2);
        }
      });
    });
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/categoryLocation.js":
/*!*************************************************************!*\
  !*** ./assets/src/js/public/components/categoryLocation.js ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    /* multi level hierarchy content */
    $('.atbdp_child_category').hide();
    $('.atbd_category_wrapper > .expander').on('click', function () {
      $(this).siblings('.atbdp_child_category').slideToggle();
    });
    $('.atbdp_child_category li .expander').on('click', function () {
      $(this).siblings('.atbdp_child_category').slideToggle();
      $(this).parent('li').siblings('li').children('.atbdp_child_category').slideUp();
    });
    $('.atbdp_parent_category >li >span').on('click', function () {
      $(this).siblings('.atbdp_child_category').slideToggle();
    }); //

    $('.atbdp_child_location').hide();
    $('.atbd_location_wrapper > .expander').on('click', function () {
      $(this).siblings('.atbdp_child_location').slideToggle();
    });
    $('.atbdp_child_location li .expander').on('click', function () {
      $(this).siblings('.atbdp_child_location').slideToggle();
      $(this).parent('li').siblings('li').children('.atbdp_child_location').slideUp();
    });
    $('.atbdp_parent_location >li >span').on('click', function () {
      $(this).siblings('.atbdp_child_location').slideToggle();
    });
  });
})(jQuery);

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

/***/ "./assets/src/js/public/components/dashboard/dashBoardMoreBtn.js":
/*!***********************************************************************!*\
  !*** ./assets/src/js/public/components/dashboard/dashBoardMoreBtn.js ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    // User Dashboard Table More Button
    $('.directorist-dashboard-listings-tbody').on("click", '.directorist-btn-more', function (e) {
      e.preventDefault();
      $(this).toggleClass('active');
      $(".directorist-dropdown-menu").removeClass("active");
      $(this).next(".directorist-dropdown-menu").toggleClass("active");
      e.stopPropagation();
    });
    $(document).bind("click", function (e) {
      if (!$(e.target).parents().hasClass('directorist-dropdown-menu__list')) {
        $(".directorist-dropdown-menu").removeClass("active");
        $(".directorist-btn-more").removeClass("active");
      }
    });
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/dashboard/dashboardAnnouncement.js":
/*!****************************************************************************!*\
  !*** ./assets/src/js/public/components/dashboard/dashboardAnnouncement.js ***!
  \****************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    // Clear seen Announcements
    var cleared_seen_announcements = false;
    $('.directorist-tab__nav__link').on('click', function () {
      if (cleared_seen_announcements) {
        return;
      }

      var target = $(this).attr('target');

      if ('dashboard_announcement' === target) {
        // console.log( target, 'clear seen announcements' );
        $.ajax({
          type: "post",
          url: directorist.ajaxurl,
          data: {
            action: 'atbdp_clear_seen_announcements'
          },
          success: function success(response) {
            // console.log( response );
            if (response.success) {
              cleared_seen_announcements = true;
              $('.directorist-announcement-count').removeClass('show');
              $('.directorist-announcement-count').html('');
            }
          },
          error: function error(_error) {
            console.log({
              error: _error
            });
          }
        });
      }
    }); // Closing the Announcement

    var closing_announcement = false;
    $('.close-announcement').on('click', function (e) {
      e.preventDefault();

      if (closing_announcement) {
        console.log('Please wait...');
        return;
      }

      var post_id = $(this).closest('.directorist-announcement').data('post-id');
      var form_data = {
        action: 'atbdp_close_announcement',
        post_id: post_id
      };
      var button_default_html = $(self).html();
      closing_announcement = true;
      var self = this;
      $.ajax({
        type: "post",
        url: directorist.ajaxurl,
        data: form_data,
        beforeSend: function beforeSend() {
          $(self).html('<span class="fas fa-spinner fa-spin"></span> ');
          $(self).addClass('disable');
          $(self).attr('disable', true);
        },
        success: function success(response) {
          // console.log( { response } );
          closing_announcement = false;
          $(self).removeClass('disable');
          $(self).attr('disable', false);

          if (response.success) {
            $('.announcement-id-' + post_id).remove();

            if (!$('.announcement-item').length) {
              location.reload();
            }
          } else {
            $(self).html('Close');
          }
        },
        error: function error(_error2) {
          console.log({
            error: _error2
          });
          $(self).html(button_default_html);
          $(self).removeClass('disable');
          $(self).attr('disable', false);
          closing_announcement = false;
        }
      });
    });
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/dashboard/dashboardBecomeAuthor.js":
/*!****************************************************************************!*\
  !*** ./assets/src/js/public/components/dashboard/dashboardBecomeAuthor.js ***!
  \****************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    // Dashboard become an author
    $('.directorist-become-author').on('click', function (e) {
      e.preventDefault();
      $(".directorist-become-author-modal").addClass("directorist-become-author-modal__show");
    });
    $('.directorist-become-author-modal__cancel').on('click', function (e) {
      e.preventDefault();
      $(".directorist-become-author-modal").removeClass("directorist-become-author-modal__show");
    });
    $('.directorist-become-author-modal__approve').on('click', function (e) {
      e.preventDefault();
      var userId = $(this).attr('data-userId');
      var nonce = $(this).attr('data-nonce');
      var data = {
        userId: userId,
        nonce: nonce,
        action: "atbdp_become_author"
      }; // Send the data

      $.post(directorist.ajaxurl, data, function (response) {
        $('.directorist-become-author__loader').addClass('active');
        $('#directorist-become-author-success').html(response);
        $('.directorist-become-author').hide();
        $(".directorist-become-author-modal").removeClass("directorist-become-author-modal__show");
      });
    });
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/dashboard/dashboardListing.js":
/*!***********************************************************************!*\
  !*** ./assets/src/js/public/components/dashboard/dashboardListing.js ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    // Dashboard Listing Ajax
    function directorist_dashboard_listing_ajax($activeTab) {
      var paged = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 1;
      var search = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : '';
      var task = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : '';
      var taskdata = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : '';
      var tab = $activeTab.data('tab');
      $.ajax({
        url: directorist.ajaxurl,
        type: 'POST',
        dataType: 'json',
        data: {
          'action': 'directorist_dashboard_listing_tab',
          'tab': tab,
          'paged': paged,
          'search': search,
          'task': task,
          'taskdata': taskdata
        },
        beforeSend: function beforeSend() {
          $('#directorist-dashboard-preloader').show();
        },
        success: function success(response) {
          $('.directorist-dashboard-listings-tbody').html(response.data.content);
          $('.directorist-dashboard-pagination').html(response.data.pagination);
          $('.directorist-dashboard-listing-nav-js a').removeClass('directorist-tab__nav__active');
          $activeTab.addClass('directorist-tab__nav__active');
          $('#directorist-dashboard-mylistings-js').data('paged', paged);
        },
        complete: function complete() {
          $('#directorist-dashboard-preloader').hide();
        }
      });
    } // Dashboard Listing Tabs


    $('.directorist-dashboard-listing-nav-js a').on('click', function (event) {
      var $item = $(this);

      if ($item.hasClass('directorist-tab__nav__active')) {
        return false;
      }

      directorist_dashboard_listing_ajax($item);
      $('#directorist-dashboard-listing-searchform input[name=searchtext').val('');
      $('#directorist-dashboard-mylistings-js').data('search', '');
      return false;
    }); // Dashboard Tasks eg. delete

    $('.directorist-dashboard-listings-tbody').on('click', '.directorist-dashboard-listing-actions a[data-task]', function (event) {
      var task = $(this).data('task');
      var postid = $(this).closest('tr').data('id');
      var $activeTab = $('.directorist-dashboard-listing-nav-js a.directorist-tab__nav__active');
      var paged = $('#directorist-dashboard-mylistings-js').data('paged');
      var search = $('#directorist-dashboard-mylistings-js').data('search');

      if (task == 'delete') {
        swal({
          title: directorist.listing_remove_title,
          text: directorist.listing_remove_text,
          type: "warning",
          cancelButtonText: directorist.review_cancel_btn_text,
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: directorist.listing_remove_confirm_text,
          showLoaderOnConfirm: true,
          closeOnConfirm: false
        }, function (isConfirm) {
          if (isConfirm) {
            directorist_dashboard_listing_ajax($activeTab, paged, search, task, postid);
            swal({
              title: directorist.listing_delete,
              type: "success",
              timer: 200,
              showConfirmButton: false
            });
          }
        });
      }

      return false;
    }); // Remove Listing

    $(document).on('click', '#remove_listing', function (e) {
      e.preventDefault();
      var $this = $(this);
      var id = $this.data('listing_id');
      var data = 'listing_id=' + id;
      swal({
        title: directorist.listing_remove_title,
        text: directorist.listing_remove_text,
        type: "warning",
        cancelButtonText: directorist.review_cancel_btn_text,
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: directorist.listing_remove_confirm_text,
        showLoaderOnConfirm: true,
        closeOnConfirm: false
      }, function (isConfirm) {
        if (isConfirm) {
          // user has confirmed, now remove the listing
          atbdp_do_ajax($this, 'remove_listing', data, function (response) {
            $('body').append(response);

            if ('success' === response) {
              // show success message
              swal({
                title: directorist.listing_delete,
                type: "success",
                timer: 200,
                showConfirmButton: false
              });
              $("#listing_id_" + id).remove();
              $this.remove();
            } else {
              // show error message
              swal({
                title: directorist.listing_error_title,
                text: directorist.listing_error_text,
                type: "error",
                timer: 2000,
                showConfirmButton: false
              });
            }
          });
        }
      }); // send an ajax request to the ajax-handler.php and then delete the review of the given id
    }); // Dashboard pagination

    $('.directorist-dashboard-pagination').on('click', 'a', function (event) {
      var $link = $(this);
      var paged = $link.attr('href');
      paged = paged.split('/page/')[1];
      paged = parseInt(paged);
      var search = $('#directorist-dashboard-mylistings-js').data('search');
      $activeTab = $('.directorist-dashboard-listing-nav-js a.directorist-tab__nav__active');
      directorist_dashboard_listing_ajax($activeTab, paged, search);
      return false;
    }); // Dashboard Search

    $('#directorist-dashboard-listing-searchform input[name=searchtext').val(''); //onready

    $('#directorist-dashboard-listing-searchform').on('submit', function (event) {
      var $activeTab = $('.directorist-dashboard-listing-nav-js a.directorist-tab__nav__active');
      var search = $(this).find('input[name=searchtext]').val();
      directorist_dashboard_listing_ajax($activeTab, 1, search);
      $('#directorist-dashboard-mylistings-js').data('search', search);
      return false;
    });
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/dashboard/dashboardResponsive.js":
/*!**************************************************************************!*\
  !*** ./assets/src/js/public/components/dashboard/dashboardResponsive.js ***!
  \**************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    //dashboard content responsive fix
    var tabContentWidth = $(".directorist-user-dashboard .directorist-user-dashboard__contents").innerWidth();

    if (tabContentWidth < 1399) {
      $(".directorist-user-dashboard .directorist-user-dashboard__contents").addClass("directorist-tab-content-grid-fix");
    }

    $(window).bind("resize", function () {
      if ($(this).width() <= 1199) {
        $(".directorist-user-dashboard__nav").addClass("directorist-dashboard-nav-collapsed");
        $(".directorist-shade").removeClass("directorist-active");
      }
    }).trigger("resize");
    $('.directorist-dashboard__nav--close, .directorist-shade').on('click', function () {
      $(".directorist-user-dashboard__nav").addClass('directorist-dashboard-nav-collapsed');
      $(".directorist-shade").removeClass("directorist-active");
    }); // Profile Responsive

    $('.directorist-tab__nav__link').on('click', function () {
      if ($('#user_profile_form').width() < 800 && $('#user_profile_form').width() !== 0) {
        $('#user_profile_form').addClass('directorist-profile-responsive');
      }
    });
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/dashboard/dashboardSidebar.js":
/*!***********************************************************************!*\
  !*** ./assets/src/js/public/components/dashboard/dashboardSidebar.js ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    //dashboard sidebar nav toggler
    $(".directorist-user-dashboard__toggle__link").on("click", function (e) {
      e.preventDefault();
      $(".directorist-user-dashboard__nav").toggleClass("directorist-dashboard-nav-collapsed"); // $(".directorist-shade").toggleClass("directorist-active");
    });

    if ($(window).innerWidth() < 767) {
      $(".directorist-user-dashboard__nav").addClass("directorist-dashboard-nav-collapsed");
      $(".directorist-user-dashboard__nav").addClass("directorist-dashboard-nav-collapsed--fixed");
    } //dashboard nav dropdown


    $(".atbdp_tab_nav--has-child .atbd-dash-nav-dropdown").on("click", function (e) {
      e.preventDefault();
      $(this).siblings("ul").slideToggle();
    });

    if ($(window).innerWidth() < 1199) {
      $(".directorist-tab__nav__link").on("click", function () {
        $(".directorist-user-dashboard__nav").addClass('directorist-dashboard-nav-collapsed');
        $(".directorist-shade").removeClass("directorist-active");
      });
      $(".directorist-user-dashboard__toggle__link").on("click", function (e) {
        e.preventDefault();
        $(".directorist-shade").toggleClass("directorist-active");
      });
    }
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/dashboard/dashboardTab.js":
/*!*******************************************************************!*\
  !*** ./assets/src/js/public/components/dashboard/dashboardTab.js ***!
  \*******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    // User Dashboard Tab
    $(function () {
      var hash = window.location.hash;
      var selectedTab = $('.navbar .menu li a [target= "' + hash + '"]');
    }); // store the currently selected tab in the hash value

    $("ul.directorist-tab__nav__items > li > a.directorist-tab__nav__link").on("click", function (e) {
      var id = $(e.target).attr("target").substr();
      window.location.hash = "#active_" + id;
      e.stopPropagation();
    });
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/directoristAlert.js":
/*!*************************************************************!*\
  !*** ./assets/src/js/public/components/directoristAlert.js ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    /* Directorist alert dismiss */
    if ($('.directorist-alert__close') !== null) {
      $('.directorist-alert__close').each(function (i, e) {
        $(e).on('click', function (e) {
          e.preventDefault();
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

/***/ "./assets/src/js/public/components/directoristFavorite.js":
/*!****************************************************************!*\
  !*** ./assets/src/js/public/components/directoristFavorite.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    // Add or Remove from favourites
    $('#atbdp-favourites').on('click', function (e) {
      var data = {
        'action': 'atbdp_public_add_remove_favorites',
        'post_id': $("a.atbdp-favourites").data('post_id')
      };
      $.post(directorist.ajaxurl, data, function (response) {
        $('#atbdp-favourites').html(response);
      });
    });
    $('.directorist-favourite-remove-btn').each(function () {
      $(this).on('click', function (event) {
        event.preventDefault();
        var data = {
          'action': 'atbdp-favourites-all-listing',
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

/***/ "./assets/src/js/public/components/directoristSorting.js":
/*!***************************************************************!*\
  !*** ./assets/src/js/public/components/directoristSorting.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    // Sorting Js
    $('.directorist-dropdown__links--single-js').click(function () {
      var href = $(this).attr('data-link');
      $('#directorsit-listing-sort').attr('action', href);
      $('#directorsit-listing-sort').submit();
    }); //sorting toggle

    $('.sorting span').on('click', function () {
      $(this).toggleClass('fa-sort-amount-asc fa-sort-amount-desc');
    });
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/formValidation.js":
/*!***********************************************************!*\
  !*** ./assets/src/js/public/components/formValidation.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    $('#directorist-report-abuse-form').on('submit', function (e) {
      $('.directorist-report-abuse-modal button[type=submit]').addClass('directorist-btn-loading'); // Check for errors

      if (!e.isDefaultPrevented()) {
        e.preventDefault(); // Post via AJAX

        var data = {
          'action': 'atbdp_public_report_abuse',
          'directorist_nonce': directorist.directorist_nonce,
          'post_id': $('#atbdp-post-id').val(),
          'message': $('#directorist-report-message').val()
        };
        $.post(directorist.ajaxurl, data, function (response) {
          if (1 == response.error) {
            $('#directorist-report-abuse-message-display').addClass('text-danger').html(response.message);
          } else {
            $('#directorist-report-message').val('');
            $('#directorist-report-abuse-message-display').addClass('text-success').html(response.message);
          }

          $('.directorist-report-abuse-modal button[type=submit]').removeClass('directorist-btn-loading');
        }, 'json');
      }
    });
    $('#atbdp-report-abuse-form').removeAttr('novalidate'); // Validate contact form

    $('.directorist-contact-owner-form').on('submit', function (e) {
      e.preventDefault();
      var submit_button = $(this).find('button[type="submit"]');
      var status_area = $(this).find('.directorist-contact-message-display'); // Show loading message

      var msg = '<div class="directorist-alert"><i class="fas fa-circle-notch fa-spin"></i> ' + directorist.waiting_msg + ' </div>';
      status_area.html(msg);
      var name = $(this).find('input[name="atbdp-contact-name"]');
      var contact_email = $(this).find('input[name="atbdp-contact-email"]');
      var message = $(this).find('textarea[name="atbdp-contact-message"]');
      var post_id = $(this).find('input[name="atbdp-post-id"]');
      var listing_email = $(this).find('input[name="atbdp-listing-email"]'); // Post via AJAX

      var data = {
        'action': 'atbdp_public_send_contact_email',
        'post_id': post_id.val(),
        'name': name.val(),
        'email': contact_email.val(),
        'listing_email': listing_email.val(),
        'message': message.val(),
        'directorist_nonce': directorist.directorist_nonce
      };
      submit_button.prop('disabled', true);
      $.post(directorist.ajaxurl, data, function (response) {
        submit_button.prop('disabled', false);

        if (1 == response.error) {
          atbdp_contact_submitted = false; // Show error message

          var msg = '<div class="atbdp-alert alert-danger-light"><i class="fas fa-exclamation-triangle"></i> ' + response.message + '</div>';
          status_area.html(msg);
        } else {
          name.val('');
          message.val('');
          contact_email.val(''); // Show success message

          var msg = '<div class="atbdp-alert alert-success-light"><i class="fas fa-check-circle"></i> ' + response.message + '</div>';
          status_area.html(msg);
        }

        setTimeout(function () {
          status_area.html('');
        }, 5000);
      }, 'json');
    });
    $('#atbdp-contact-form,#atbdp-contact-form-widget').removeAttr('novalidate');
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/general.js":
/*!****************************************************!*\
  !*** ./assets/src/js/public/components/general.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// Fix listing with no thumb if card width is less than 220px
(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    if ($('.directorist-listing-no-thumb').innerWidth() <= 220) {
      $('.directorist-listing-no-thumb').addClass('directorist-listing-no-thumb--fix');
    } // Auhtor Profile Listing responsive fix


    if ($('.directorist-author-listing-content').innerWidth() <= 750) {
      $('.directorist-author-listing-content').addClass('directorist-author-listing-grid--fix');
    } // Directorist Archive responsive fix


    if ($('.directorist-archive-grid-view').innerWidth() <= 500) {
      $('.directorist-archive-grid-view').addClass('directorist-archive-grid--fix');
    }
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/gridResponsive.js":
/*!***********************************************************!*\
  !*** ./assets/src/js/public/components/gridResponsive.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

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
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

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
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  // Helper function to convert the mysql date
  Date.createFromMysql = function (mysql_string) {
    var t,
        result = null;

    if (typeof mysql_string === 'string') {
      t = mysql_string.split(/[- :]/); //when t[3], t[4] and t[5] are missing they defaults to zero

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
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  /*This function handles all ajax request*/
  function atbdp_do_ajax(ElementToShowLoadingIconAfter, ActionName, arg, CallBackHandler) {
    var data;
    if (ActionName) data = "action=" + ActionName;
    if (arg) data = arg + "&action=" + ActionName;
    if (arg && !ActionName) data = arg; //data = data ;

    var n = data.search(atbdp_public_data.nonceName);

    if (n < 0) {
      data = data + "&" + atbdp_public_data.nonceName + "=" + atbdp_public_data.nonce;
    }

    jQuery.ajax({
      type: "post",
      url: atbdp_public_data.ajaxurl,
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
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/helpers/noImageController.js":
/*!**********************************************************************!*\
  !*** ./assets/src/js/public/components/helpers/noImageController.js ***!
  \**********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

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
/*! no static exports found */
/***/ (function(module, exports) {

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
/*! no static exports found */
/***/ (function(module, exports) {

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

/***/ "./assets/src/js/public/components/legacy-support.js":
/*!***********************************************************!*\
  !*** ./assets/src/js/public/components/legacy-support.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

window.addEventListener('DOMContentLoaded', function () {
  /* custom dropdown */
  var atbdDropdown = document.querySelectorAll('.atbd-dropdown'); // toggle dropdown

  var clickCount = 0;

  if (atbdDropdown !== null) {
    atbdDropdown.forEach(function (el) {
      el.querySelector('.atbd-dropdown-toggle').addEventListener('click', function (e) {
        e.preventDefault();
        clickCount++;

        if (clickCount % 2 === 1) {
          document.querySelectorAll('.atbd-dropdown-items').forEach(function (elem) {
            elem.classList.remove('atbd-show');
          });
          el.querySelector('.atbd-dropdown-items').classList.add('atbd-show');
        } else {
          document.querySelectorAll('.atbd-dropdown-items').forEach(function (elem) {
            elem.classList.remove('atbd-show');
          });
        }
      });
    });
  } // remvoe toggle when click outside


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
/*! no static exports found */
/***/ (function(module, exports) {

/* All listings Masonry layout */
(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    function authorsMasonry(selector) {
      var authorsCard = $(selector);
      $(authorsCard).each(function (id, elm) {
        var authorsCardRow = $(elm).find('.directorist-masonary');
        var authorMasonryInit = $(authorsCardRow).imagesLoaded(function () {
          $(authorMasonryInit).masonry({
            percentPosition: true,
            horizontalOrder: true
          });
        });
      });
    }

    authorsMasonry('.directorist-archive-grid-view');
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/profileForm.js":
/*!********************************************************!*\
  !*** ./assets/src/js/public/components/profileForm.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    var profileMediaUploader = null;

    if ($(".directorist-profile-uploader").length) {
      profileMediaUploader = new EzMediaUploader({
        containerClass: "directorist-profile-uploader"
      });
      profileMediaUploader.init();
    }

    var is_processing = false;
    $('#user_profile_form').on('submit', function (e) {
      // submit the form to the ajax handler and then send a response from the database and then work accordingly and then after finishing the update profile then work on remove listing and also remove the review and rating form the custom table once the listing is deleted successfully.
      e.preventDefault();
      var submit_button = $('#update_user_profile');
      submit_button.attr('disabled', true);
      submit_button.addClass("directorist-loader");

      if (is_processing) {
        submit_button.removeAttr('disabled');
        return;
      }

      var form_data = new FormData();
      var err_log = {};
      var error_count; // ajax action

      form_data.append('action', 'update_user_profile');
      form_data.append('directorist_nonce', directorist.directorist_nonce);

      if (profileMediaUploader) {
        var hasValidFiles = profileMediaUploader.hasValidFiles();

        if (hasValidFiles) {
          //files
          var files = profileMediaUploader.getTheFiles();
          var filesMeta = profileMediaUploader.getFilesMeta();

          if (files.length) {
            for (var i = 0; i < files.length; i++) {
              form_data.append('profile_picture', files[i]);
            }
          }

          if (filesMeta.length) {
            for (var i = 0; i < filesMeta.length; i++) {
              var elm = filesMeta[i];

              for (var key in elm) {
                form_data.append('profile_picture_meta[' + i + '][' + key + ']', elm[key]);
              }
            }
          }
        } else {
          $(".directorist-form-submit__btn").removeClass("atbd_loading");
          err_log.user_profile_avater = {
            msg: 'Listing gallery has invalid files'
          };
          error_count++;
        }
      }

      var $form = $(this);
      var arrData = $form.serializeArray();
      $.each(arrData, function (index, elem) {
        var name = elem.name;
        var value = elem.value;
        form_data.append(name, value);
      });
      $.ajax({
        method: 'POST',
        processData: false,
        contentType: false,
        url: directorist.ajaxurl,
        data: form_data,
        success: function success(response) {
          submit_button.removeAttr('disabled');
          submit_button.removeClass("directorist-loader");
          console.log(response);

          if (response.success) {
            $('#directorist-prifile-notice').html('<span class="directorist-alert directorist-alert-success">' + response.data + '</span>');
          } else {
            $('#directorist-prifile-notice').html('<span class="directorist-alert directorist-alert-danger">' + response.data + '</span>');
          }
        },
        error: function error(response) {
          submit_button.removeAttr('disabled');
          console.log(response);
        }
      }); // remove notice after five second

      setTimeout(function () {
        $("#directorist-prifile-notice .directorist-alert").remove();
      }, 5000); // prevent the from submitting

      return false;
    });
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/pureScriptTab.js":
/*!**********************************************************!*\
  !*** ./assets/src/js/public/components/pureScriptTab.js ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*
    Plugin: PureScriptTab
    Version: 1.0.0
    License: MIT
*/
var $ = jQuery;

pureScriptTab = function pureScriptTab(selector1) {
  var selector = document.querySelectorAll(selector1);
  selector.forEach(function (el, index) {
    a = el.querySelectorAll('.directorist-tab__nav__link');
    a.forEach(function (element, index) {
      element.style.cursor = 'pointer';
      element.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        var ul = event.target.closest('.directorist-tab__nav'),
            main = ul.nextElementSibling,
            item_a = ul.querySelectorAll('.directorist-tab__nav__link'),
            section = main.querySelectorAll('.directorist-tab__pane');
        item_a.forEach(function (ela, ind) {
          ela.classList.remove('directorist-tab__nav__active');
        });
        event.target.classList.add('directorist-tab__nav__active');
        section.forEach(function (element1, index) {
          //console.log(element1);
          element1.classList.remove('directorist-tab__pane--active');
        });
        var target = event.target.target;
        document.getElementById(target).classList.add('directorist-tab__pane--active');
      });
    });
  });
};
/* pureScriptTabChild = (selector1) => {
    var selector = document.querySelectorAll(selector1);
    selector.forEach((el, index) => {
        a = el.querySelectorAll('.pst_tn_link');


        a.forEach((element, index) => {

            element.style.cursor = 'pointer';
            element.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();

                var ul = event.target.closest('.pst_tab_nav'),
                    main = ul.nextElementSibling,
                    item_a = ul.querySelectorAll('.pst_tn_link'),
                    section = main.querySelectorAll('.pst_tab_inner');

                item_a.forEach((ela, ind) => {
                    ela.classList.remove('pstItemActive');
                });
                event.target.classList.add('pstItemActive');


                section.forEach((element1, index) => {
                    //console.log(element1);
                    element1.classList.remove('pstContentActive');
                });
                var target = event.target.target;
                document.getElementById(target).classList.add('pstContentActive');
            });
        });
    });
};

pureScriptTabChild2 = (selector1) => {
    var selector = document.querySelectorAll(selector1);
    selector.forEach((el, index) => {
        a = el.querySelectorAll('.pst_tn_link-2');


        a.forEach((element, index) => {

            element.style.cursor = 'pointer';
            element.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();

                var ul = event.target.closest('.pst_tab_nav-2'),
                    main = ul.nextElementSibling,
                    item_a = ul.querySelectorAll('.pst_tn_link-2'),
                    section = main.querySelectorAll('.pst_tab_inner-2');

                item_a.forEach((ela, ind) => {
                    ela.classList.remove('pstItemActive2');
                });
                event.target.classList.add('pstItemActive2');


                section.forEach((element1, index) => {
                    //console.log(element1);
                    element1.classList.remove('pstContentActive2');
                });
                var target = event.target.target;
                document.getElementById(target).classList.add('pstContentActive2');
            });
        });
    });
}; */


if ($('.directorist-tab')) {
  pureScriptTab('.directorist-tab');
}
/* pureScriptTab('.directorist-user-dashboard-tab');
pureScriptTabChild('.atbdp-bookings-tab');
pureScriptTabChild2('.atbdp-bookings-tab-inner'); */

/***/ }),

/***/ "./assets/src/js/public/components/review.js":
/*!***************************************************!*\
  !*** ./assets/src/js/public/components/review.js ***!
  \***************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

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
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/createClass.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__);



function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

window.addEventListener('DOMContentLoaded', function () {
  ;

  (function ($) {
    'use strict';

    var ReplyFormObserver = /*#__PURE__*/function () {
      function ReplyFormObserver() {
        var _this = this;

        _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default()(this, ReplyFormObserver);

        this.init();
        $(document).on('directorist_review_updated', function () {
          return _this.init();
        });
      }

      _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default()(ReplyFormObserver, [{
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
                    node.querySelector('#comment').setAttribute('placeholder', 'Leave a review'); //console.log(node.querySelector('#comment'))
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

      return ReplyFormObserver;
    }();

    var CommentEditHandler = /*#__PURE__*/function () {
      function CommentEditHandler() {
        _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default()(this, CommentEditHandler);

        this.init();
      }

      _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default()(CommentEditHandler, [{
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
          var updateComment = $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            contentType: false,
            cache: false,
            processData: false,
            data: new FormData($form[0])
          });
          $form.find('#comment').prop('disabled', true);
          $form.find('[type="submit"]').prop('disabled', true).val('loading');
          var commentID = $form.find('input[name="comment_id"]').val();
          var $wrap = $('#div-comment-' + commentID);
          $wrap.addClass('directorist-comment-edit-request');
          updateComment.success(function (data, status, request) {
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
            } // scroll to comment


            if (commentID) {
              $("body, html").animate({
                scrollTop: commentTop
              }, 600);
            }
          });
          updateComment.fail(function (data) {
            console.log(data);
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

      return CommentEditHandler;
    }();

    var CommentAddReplyHandler = /*#__PURE__*/function () {
      function CommentAddReplyHandler() {
        _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default()(this, CommentAddReplyHandler);

        this.init();
      }

      _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default()(CommentAddReplyHandler, [{
        key: "init",
        value: function init() {
          $(document).on('submit', '.directorist-review-container #commentform', this.onSubmit);
        }
      }, {
        key: "onSubmit",
        value: function onSubmit(event) {
          event.preventDefault();
          var form = $('.directorist-review-container #commentform');
          var originalButtonLabel = form.find('[type="submit"]').val();
          $(document).trigger('directorist_review_before_submit', form);
          var do_comment = $.ajax({
            url: form.attr('action'),
            type: 'POST',
            contentType: false,
            cache: false,
            processData: false,
            data: new FormData(form[0])
          });
          $('#comment').prop('disabled', true);
          form.find('[type="submit"]').prop('disabled', true).val('loading');
          do_comment.success(function (data, status, request) {
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
            var newCommentId = newComment.attr('id'); // // catch the new comment id by comparing to old dom.
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
            } // scroll to comment


            if (newCommentId) {
              $("body, html").animate({
                scrollTop: commentTop
              }, 600);
            }
          });
          do_comment.fail(function (data) {
            var body = $('<div></div>');
            body.append(data.responseText);
            CommentAddReplyHandler.showError(form, body.find('.wp-die-message'));
            $(document).trigger('directorist_review_update_failed');
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

      return CommentAddReplyHandler;
    }();

    var CommentsManager = /*#__PURE__*/function () {
      function CommentsManager() {
        _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default()(this, CommentsManager);

        this.$doc = $(document);
        this.setupComponents();
        this.addEventListeners();
      }

      _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default()(CommentsManager, [{
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
          var _this2 = this;

          var self = this;
          this.$doc.on('directorist_review_updated', function (event) {
            _this2.initStarRating();
          });
          this.$doc.on('directorist_comment_edit_form_loaded', function (event) {
            _this2.initStarRating();
          });
          this.$doc.on('click', 'a[href="#respond"]', function (event) {
            // First cancle the reply form then scroll to review form. Order matters.
            _this2.cancelReplyMode();

            _this2.onWriteReivewClick(event);
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
                $target.parents('#div-comment-' + $target.data('commentid')).find('.directorist-review-single__contents-wrap').append(response.data.html);
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

      return CommentsManager;
    }();

    var commentsManager = new CommentsManager();
  })(jQuery);
});

/***/ }),

/***/ "./assets/src/js/public/components/review/starRating.js":
/*!**************************************************************!*\
  !*** ./assets/src/js/public/components/review/starRating.js ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

window.addEventListener('DOMContentLoaded', function () {
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

/***/ "./assets/src/js/public/components/single-listing-page/slider.js":
/*!***********************************************************************!*\
  !*** ./assets/src/js/public/components/single-listing-page/slider.js ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

document.addEventListener('DOMContentLoaded', function () {
  var $ = jQuery; // Plasma Slider Initialization

  var single_listing_slider = new PlasmaSlider({
    containerID: "directorist-single-listing-slider"
  });
  single_listing_slider.init();
  /* Related listings slider */

  var rtl = directorist.rtl;
  var relLisCol = document.querySelector('.directorist-related-carousel').getAttribute('data-columns');
  $('.directorist-related-carousel').slick({
    dots: false,
    arrows: true,
    prevArrow: '<a class="directorist-slc__nav directorist-slc__nav--left"><span class="las la-angle-left"></span></a>',
    nextArrow: '<a class="directorist-slc__nav directorist-slc__nav--right"><span class="las la-angle-right"></span></a>',
    infinite: true,
    speed: 300,
    slidesToShow: relLisCol,
    slidesToScroll: 1,
    autoplay: false,
    rtl: rtl,
    responsive: [{
      breakpoint: 1024,
      settings: {
        slidesToShow: relLisCol,
        slidesToScroll: 1,
        infinite: true,
        dots: false
      }
    }, {
      breakpoint: 991,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    }, {
      breakpoint: 575,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }]
  });
});

/***/ }),

/***/ "./assets/src/js/public/components/tab.js":
/*!************************************************!*\
  !*** ./assets/src/js/public/components/tab.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

window.addEventListener('DOMContentLoaded', function () {
  // on load of the page: switch to the currently selected tab
  var tab_url = window.location.href.split("/").pop();

  if (tab_url.startsWith("#active_")) {
    var urlId = tab_url.split("#").pop().split("active_").pop();

    if (urlId !== 'my_listings') {
      document.querySelector("a[target=".concat(urlId, "]")).click();
    }
  }
});

/***/ }),

/***/ "./assets/src/js/public/modules/combined.js":
/*!**************************************************!*\
  !*** ./assets/src/js/public/modules/combined.js ***!
  \**************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_author__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/author */ "./assets/src/js/public/components/author.js");
/* harmony import */ var _components_author__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_components_author__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _scss_layout_public_main_style_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../scss/layout/public/main-style.scss */ "./assets/src/scss/layout/public/main-style.scss");
/* harmony import */ var _scss_layout_public_main_style_scss__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_scss_layout_public_main_style_scss__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _components_general__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../components/general */ "./assets/src/js/public/components/general.js");
/* harmony import */ var _components_general__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_components_general__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _components_helpers__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../components/helpers */ "./assets/src/js/public/components/helpers.js");
/* harmony import */ var _components_review__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../components/review */ "./assets/src/js/public/components/review.js");
/* harmony import */ var _components_directoristSorting__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../components/directoristSorting */ "./assets/src/js/public/components/directoristSorting.js");
/* harmony import */ var _components_directoristSorting__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_components_directoristSorting__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _components_directoristAlert__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../components/directoristAlert */ "./assets/src/js/public/components/directoristAlert.js");
/* harmony import */ var _components_directoristAlert__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_components_directoristAlert__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _components_gridResponsive__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../components/gridResponsive */ "./assets/src/js/public/components/gridResponsive.js");
/* harmony import */ var _components_gridResponsive__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_components_gridResponsive__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _components_directoristFavorite__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../components/directoristFavorite */ "./assets/src/js/public/components/directoristFavorite.js");
/* harmony import */ var _components_directoristFavorite__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_components_directoristFavorite__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var _components_directoristDropdown__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../components/directoristDropdown */ "./assets/src/js/public/components/directoristDropdown.js");
/* harmony import */ var _components_directoristDropdown__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_components_directoristDropdown__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var _components_directoristSelect__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../components/directoristSelect */ "./assets/src/js/public/components/directoristSelect.js");
/* harmony import */ var _components_directoristSelect__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_components_directoristSelect__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var _components_categoryLocation__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ../components/categoryLocation */ "./assets/src/js/public/components/categoryLocation.js");
/* harmony import */ var _components_categoryLocation__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(_components_categoryLocation__WEBPACK_IMPORTED_MODULE_11__);
/* harmony import */ var _components_colorPicker__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ../components/colorPicker */ "./assets/src/js/public/components/colorPicker.js");
/* harmony import */ var _components_colorPicker__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(_components_colorPicker__WEBPACK_IMPORTED_MODULE_12__);
/* harmony import */ var _components_legacy_support__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ../components/legacy-support */ "./assets/src/js/public/components/legacy-support.js");
/* harmony import */ var _components_legacy_support__WEBPACK_IMPORTED_MODULE_13___default = /*#__PURE__*/__webpack_require__.n(_components_legacy_support__WEBPACK_IMPORTED_MODULE_13__);
/* harmony import */ var _components_masonry__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ../components/masonry */ "./assets/src/js/public/components/masonry.js");
/* harmony import */ var _components_masonry__WEBPACK_IMPORTED_MODULE_14___default = /*#__PURE__*/__webpack_require__.n(_components_masonry__WEBPACK_IMPORTED_MODULE_14__);
/* harmony import */ var _global_components_setup_select2__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ../../global/components/setup-select2 */ "./assets/src/js/global/components/setup-select2.js");
/* harmony import */ var _global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ../../global/components/select2-custom-control */ "./assets/src/js/global/components/select2-custom-control.js");
/* harmony import */ var _global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_16___default = /*#__PURE__*/__webpack_require__.n(_global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_16__);
/* harmony import */ var _components_review_starRating__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ../components/review/starRating */ "./assets/src/js/public/components/review/starRating.js");
/* harmony import */ var _components_review_starRating__WEBPACK_IMPORTED_MODULE_17___default = /*#__PURE__*/__webpack_require__.n(_components_review_starRating__WEBPACK_IMPORTED_MODULE_17__);
/* harmony import */ var _components_dashboard_dashboardSidebar__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! ../components/dashboard/dashboardSidebar */ "./assets/src/js/public/components/dashboard/dashboardSidebar.js");
/* harmony import */ var _components_dashboard_dashboardSidebar__WEBPACK_IMPORTED_MODULE_18___default = /*#__PURE__*/__webpack_require__.n(_components_dashboard_dashboardSidebar__WEBPACK_IMPORTED_MODULE_18__);
/* harmony import */ var _components_dashboard_dashboardTab__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__(/*! ../components/dashboard/dashboardTab */ "./assets/src/js/public/components/dashboard/dashboardTab.js");
/* harmony import */ var _components_dashboard_dashboardTab__WEBPACK_IMPORTED_MODULE_19___default = /*#__PURE__*/__webpack_require__.n(_components_dashboard_dashboardTab__WEBPACK_IMPORTED_MODULE_19__);
/* harmony import */ var _components_dashboard_dashboardListing__WEBPACK_IMPORTED_MODULE_20__ = __webpack_require__(/*! ../components/dashboard/dashboardListing */ "./assets/src/js/public/components/dashboard/dashboardListing.js");
/* harmony import */ var _components_dashboard_dashboardListing__WEBPACK_IMPORTED_MODULE_20___default = /*#__PURE__*/__webpack_require__.n(_components_dashboard_dashboardListing__WEBPACK_IMPORTED_MODULE_20__);
/* harmony import */ var _components_dashboard_dashBoardMoreBtn__WEBPACK_IMPORTED_MODULE_21__ = __webpack_require__(/*! ../components/dashboard/dashBoardMoreBtn */ "./assets/src/js/public/components/dashboard/dashBoardMoreBtn.js");
/* harmony import */ var _components_dashboard_dashBoardMoreBtn__WEBPACK_IMPORTED_MODULE_21___default = /*#__PURE__*/__webpack_require__.n(_components_dashboard_dashBoardMoreBtn__WEBPACK_IMPORTED_MODULE_21__);
/* harmony import */ var _components_dashboard_dashboardResponsive__WEBPACK_IMPORTED_MODULE_22__ = __webpack_require__(/*! ../components/dashboard/dashboardResponsive */ "./assets/src/js/public/components/dashboard/dashboardResponsive.js");
/* harmony import */ var _components_dashboard_dashboardResponsive__WEBPACK_IMPORTED_MODULE_22___default = /*#__PURE__*/__webpack_require__.n(_components_dashboard_dashboardResponsive__WEBPACK_IMPORTED_MODULE_22__);
/* harmony import */ var _components_dashboard_dashboardAnnouncement__WEBPACK_IMPORTED_MODULE_23__ = __webpack_require__(/*! ../components/dashboard/dashboardAnnouncement */ "./assets/src/js/public/components/dashboard/dashboardAnnouncement.js");
/* harmony import */ var _components_dashboard_dashboardAnnouncement__WEBPACK_IMPORTED_MODULE_23___default = /*#__PURE__*/__webpack_require__.n(_components_dashboard_dashboardAnnouncement__WEBPACK_IMPORTED_MODULE_23__);
/* harmony import */ var _components_dashboard_dashboardBecomeAuthor__WEBPACK_IMPORTED_MODULE_24__ = __webpack_require__(/*! ../components/dashboard/dashboardBecomeAuthor */ "./assets/src/js/public/components/dashboard/dashboardBecomeAuthor.js");
/* harmony import */ var _components_dashboard_dashboardBecomeAuthor__WEBPACK_IMPORTED_MODULE_24___default = /*#__PURE__*/__webpack_require__.n(_components_dashboard_dashboardBecomeAuthor__WEBPACK_IMPORTED_MODULE_24__);
/* harmony import */ var _components_pureScriptTab__WEBPACK_IMPORTED_MODULE_25__ = __webpack_require__(/*! ../components/pureScriptTab */ "./assets/src/js/public/components/pureScriptTab.js");
/* harmony import */ var _components_pureScriptTab__WEBPACK_IMPORTED_MODULE_25___default = /*#__PURE__*/__webpack_require__.n(_components_pureScriptTab__WEBPACK_IMPORTED_MODULE_25__);
/* harmony import */ var _components_profileForm__WEBPACK_IMPORTED_MODULE_26__ = __webpack_require__(/*! ../components/profileForm */ "./assets/src/js/public/components/profileForm.js");
/* harmony import */ var _components_profileForm__WEBPACK_IMPORTED_MODULE_26___default = /*#__PURE__*/__webpack_require__.n(_components_profileForm__WEBPACK_IMPORTED_MODULE_26__);
/* harmony import */ var _components_tab__WEBPACK_IMPORTED_MODULE_27__ = __webpack_require__(/*! ../components/tab */ "./assets/src/js/public/components/tab.js");
/* harmony import */ var _components_tab__WEBPACK_IMPORTED_MODULE_27___default = /*#__PURE__*/__webpack_require__.n(_components_tab__WEBPACK_IMPORTED_MODULE_27__);
/* harmony import */ var _components_formValidation__WEBPACK_IMPORTED_MODULE_28__ = __webpack_require__(/*! ../components/formValidation */ "./assets/src/js/public/components/formValidation.js");
/* harmony import */ var _components_formValidation__WEBPACK_IMPORTED_MODULE_28___default = /*#__PURE__*/__webpack_require__.n(_components_formValidation__WEBPACK_IMPORTED_MODULE_28__);
/* harmony import */ var _global_components_modal__WEBPACK_IMPORTED_MODULE_29__ = __webpack_require__(/*! ../../global/components/modal */ "./assets/src/js/global/components/modal.js");
/* harmony import */ var _global_components_modal__WEBPACK_IMPORTED_MODULE_29___default = /*#__PURE__*/__webpack_require__.n(_global_components_modal__WEBPACK_IMPORTED_MODULE_29__);
/* harmony import */ var _components_single_listing_page_slider__WEBPACK_IMPORTED_MODULE_30__ = __webpack_require__(/*! ../components/single-listing-page/slider */ "./assets/src/js/public/components/single-listing-page/slider.js");
/* harmony import */ var _components_single_listing_page_slider__WEBPACK_IMPORTED_MODULE_30___default = /*#__PURE__*/__webpack_require__.n(_components_single_listing_page_slider__WEBPACK_IMPORTED_MODULE_30__);
/* harmony import */ var _search_form__WEBPACK_IMPORTED_MODULE_31__ = __webpack_require__(/*! ../search-form */ "./assets/src/js/public/search-form.js");
/* harmony import */ var _global_add_listing__WEBPACK_IMPORTED_MODULE_32__ = __webpack_require__(/*! ../../global/add-listing */ "./assets/src/js/global/add-listing.js");
/* harmony import */ var _global_map_scripts_map_view__WEBPACK_IMPORTED_MODULE_33__ = __webpack_require__(/*! ../../global/map-scripts/map-view */ "./assets/src/js/global/map-scripts/map-view.js");
/* harmony import */ var _global_map_scripts_markerclusterer__WEBPACK_IMPORTED_MODULE_34__ = __webpack_require__(/*! ../../global/map-scripts/markerclusterer */ "./assets/src/js/global/map-scripts/markerclusterer.js");
/* harmony import */ var _global_map_scripts_geolocation__WEBPACK_IMPORTED_MODULE_35__ = __webpack_require__(/*! ../../global/map-scripts/geolocation */ "./assets/src/js/global/map-scripts/geolocation.js");
/* harmony import */ var _global_map_scripts_geolocation__WEBPACK_IMPORTED_MODULE_35___default = /*#__PURE__*/__webpack_require__.n(_global_map_scripts_geolocation__WEBPACK_IMPORTED_MODULE_35__);
/* harmony import */ var _global_map_scripts_openstreet_map__WEBPACK_IMPORTED_MODULE_36__ = __webpack_require__(/*! ../../global/map-scripts/openstreet-map */ "./assets/src/js/global/map-scripts/openstreet-map.js");
//General Components

 // General Components















 // General Components

 // Dashboard Js







 // General Components



 // General Components


 // Single Listing Page









/***/ }),

/***/ "./assets/src/js/public/search-form.js":
/*!*********************************************!*\
  !*** ./assets/src/js/public/search-form.js ***!
  \*********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_directoristSelect__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/directoristSelect */ "./assets/src/js/public/components/directoristSelect.js");
/* harmony import */ var _components_directoristSelect__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_components_directoristSelect__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_colorPicker__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/colorPicker */ "./assets/src/js/public/components/colorPicker.js");
/* harmony import */ var _components_colorPicker__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_components_colorPicker__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _global_components_setup_select2__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./../global/components/setup-select2 */ "./assets/src/js/global/components/setup-select2.js");
/* harmony import */ var _global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./../global/components/select2-custom-control */ "./assets/src/js/global/components/select2-custom-control.js");
/* harmony import */ var _global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_3__);
//import './components/directoristDropdown';





(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
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
      searchForm.querySelectorAll("input[type='hidden']:not(.listing_type)").forEach(function (el) {
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
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/scss/layout/public/main-style.scss":
/*!*******************************************************!*\
  !*** ./assets/src/scss/layout/public/main-style.scss ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/classCallCheck.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/classCallCheck.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _classCallCheck(instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
}

module.exports = _classCallCheck;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/createClass.js":
/*!************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/createClass.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _defineProperties(target, props) {
  for (var i = 0; i < props.length; i++) {
    var descriptor = props[i];
    descriptor.enumerable = descriptor.enumerable || false;
    descriptor.configurable = true;
    if ("value" in descriptor) descriptor.writable = true;
    Object.defineProperty(target, descriptor.key, descriptor);
  }
}

function _createClass(Constructor, protoProps, staticProps) {
  if (protoProps) _defineProperties(Constructor.prototype, protoProps);
  if (staticProps) _defineProperties(Constructor, staticProps);
  return Constructor;
}

module.exports = _createClass;
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

/***/ 11:
/*!********************************************************!*\
  !*** multi ./assets/src/js/public/modules/combined.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/public/modules/combined.js */"./assets/src/js/public/modules/combined.js");


/***/ })

/******/ });
//# sourceMappingURL=combined.js.map