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
/* harmony import */ var _public_components_validator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./../public/components/validator */ "./assets/src/js/public/components/validator.js");
/* harmony import */ var _public_components_validator__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_public_components_validator__WEBPACK_IMPORTED_MODULE_1__);


function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }


/* eslint-disable */

var $ = jQuery;
var localized_data = atbdp_public_data.add_listing_data;
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
$('body').on("click", "#manual_coordinate", function (e) {
  if ($('input#manual_coordinate').is(':checked')) {
    $('.directorist-map-coordinates').show();
    $('#hide_if_no_manual_cor').show();
  } else {
    $('.directorist-map-coordinates').hide();
    $('#hide_if_no_manual_cor').hide();
  }
}); // enable sorting if only the container has any social or skill field

var $s_wrap = $('#social_info_sortable_container'); // cache it

/* if (window.outerWidth > 1700) {
        if ($s_wrap.length) {
                $s_wrap.sortable({
                        axis: 'y',
                        opacity: '0.7',
                });
        }
} */
// SOCIAL SECTION
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


  var createTag = $('#at_biz_dir-tags').attr("data-allow_new"); // console.log($('#at_biz_dir-tags').attr("data-max"));

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
} // Custom Image uploader for listing image (multiple)
// price range


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
});
var test_data = null; // Load custom fields of the selected category in the custom post type "atbdp_listings"

$(document).ready(function () {
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
});

function atbdp_is_checked(name) {
  var is_checked = $("input[name=\"".concat(name, "\"]")).is(':checked');

  if (is_checked) {
    return '1';
  }

  return '';
}

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

var qs = function (a) {
  if (a == '') return {};
  var b = {};

  for (var i = 0; i < a.length; ++i) {
    var p = a[i].split('=', 2);
    if (p.length == 1) b[p[0]] = '';else b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, ' '));
  }

  return b;
}(window.location.search.substr(1).split('&'));

var uploaders = localized_data.media_uploader;
var mediaUploaders = [];

if (uploaders) {
  var i = 0;

  var _iterator = _createForOfIteratorHelper(uploaders),
      _step;

  try {
    for (_iterator.s(); !(_step = _iterator.n()).done;) {
      var uploader = _step.value;

      if ($('#' + uploader['element_id']).length) {
        var media_uploader = new EzMediaUploader({
          containerID: uploader['element_id']
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
  var err_log = {}; // if ($('#atbdp_front_media_wrap:visible').length == 0) {
  //         has_media = false;
  // }

  if (on_processing) {
    $('.directorist-form-submit__btn').attr('disabled', true);
    return;
  }

  var form_data = new FormData();
  var field_list = [];
  var field_list2 = [];
  $('.directorist-form-submit__btn').addClass('atbd_loading');
  form_data.append('action', 'add_listing_action');
  var fieldValuePairs = $('#directorist-add-listing-form').serializeArray();
  $.each(fieldValuePairs, function (index, fieldValuePair) {
    var field = document.getElementsByName(fieldValuePair.name)[0];
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
      //  field_list2.push({ nam: name, val: value, field: field, type: type})
      setup_form_data(form_data, type, field);
    }
  }); // console.log( field_list2 );
  // return;
  // images

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
            scrollToEl('#' + uploader.uploaders_data['element_id']);
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

  var directory_type = qs.directory_type ? qs.directory_type : $('input[name="directory_type"]').val();
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
  // $('.directorist-form-submit__btn').attr('disabled', true);


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
      $('.directorist-form-submit__btn').attr('disabled', false); // var is_pending = response ? '&' : '?';

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
    url: atbdp_public_data.ajaxurl,
    data: form_data,
    beforeSend: function beforeSend() {
      form_feedback.html('');
      submit_button.prop('disabled', true);
      submit_button.prepend('<i class="fas fa-circle-notch fa-spin"></i> ');
    },
    success: function success(response) {
      //console.log({ response });
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

/***/ }),

/***/ "./assets/src/js/public/components/validator.js":
/*!******************************************************!*\
  !*** ./assets/src/js/public/components/validator.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

jQuery(document).ready(function ($) {
  function to_top(top) {
    $([document.documentElement, document.body]).animate({
      scrollTop: $(top).offset().top
    }, 1000);
  }

  need_post = false;

  if ($("input[name='need_post']").length > 0) {
    $("input[name='need_post']").on('change', function () {
      if ('yes' === this.value) {
        need_post = true;
      }
    });
  } // @kowsar @todo rebuild validation

  /*
  $('.directorist-form-submit__btn').on('click', function (e) {
      $('.atbdp_required').css({display: "none"});
      var w_icon = '<span class="fa fa-exclamation-triangle"></span> ';
      //title
      if ($("input[name='listing_title']").length > 0) {
          var title = $("input[name='listing_title']").val();
          var required_title = add_listing_validator.title;
          if ('' === title && '' !== required_title) {
              $("input[name='listing_title']").after('<span class="atbdp_required">' + w_icon + required_title + '</span>');
              to_top('#atbdp_listing_title');
              return false;
          }
      }
      //description
      if ($("#listing_content_ifr").length > 0) {
          var iframe = $('#listing_content_ifr');
          var description = $('#tinymce[data-id="listing_content"]', iframe.contents()).text();
          var required_description = add_listing_validator.description;
          if ('' === description && '' !== required_description) {
              $("#wp-listing_content-wrap").after('<span class="atbdp_required">' + w_icon + required_description + '</span>');
              to_top('#atbdp_listing_content');
              return false;
          }
      }
       //Price
      if ($("input[name='price']").length > 0) {
          var price = $("input[name='price']").val();
          var required_price = add_listing_validator.price;
          if ('' === price && '' !== required_price && !need_post) {
              $("#atbd_pricing").append('<span class="atbdp_required">' + w_icon + required_price + '</span>');
              to_top('#atbd_pricing');
               return false;
          }
      }
       //Price range
      if ($("select[name='price_range']").length > 0) {
          var price_range = $("select[name='price_range']").val();
          var required_price_range = add_listing_validator.price_range;
          if ('' === price_range && '' !== required_price_range) {
              $("#atbd_pricing").after('<span class="atbdp_required">' + w_icon + required_price_range + '</span>');
              to_top('#atbd_pricing');
              return false;
          }
      }
       //excerpt
      if ($('textarea#atbdp_excerpt').length > 0) {
          var excerpt = $("textarea#atbdp_excerpt").val();
          var required_excerpt = add_listing_validator.excerpt;
          if ('' === excerpt && '' !== required_excerpt && !need_post) {
              $("textarea#atbdp_excerpt").after('<span class="atbdp_required">' + w_icon + required_excerpt + '</span>');
              to_top('#atbdp_excerpt');
              return false;
          }
      }
       //location
      if ($("#at_biz_dir-location").length > 0) {
          var location = $("#at_biz_dir-location").val();
          var required_location = add_listing_validator.location;
          if (null === location && '' !== required_location && !need_post) {
              $("#atbdp_locations").append('<span class="atbdp_required">' + w_icon + required_location + '</span>');
              to_top('#atbdp_locations');
              return false;
          }
      }
      //tag
      if ($("#at_biz_dir-tags").length > 0) {
          var tag = $("#at_biz_dir-tags").val();
          var required_tag = add_listing_validator.tag;
          if (null === tag && '' !== required_tag) {
              $("#atbdp_tags").append('<span class="atbdp_required">' + w_icon + required_tag + '</span>');
              to_top('#atbdp_tags');
              return false;
          }
      }
       //category
      if ($("#at_biz_dir-categories").length > 0) {
          var category = $("#at_biz_dir-categories").val();
          var required_category = add_listing_validator.category;
          if (null === category && '' !== required_category) {
              $("#atbdp_categories").append('<span class="atbdp_required">' + w_icon + required_category + '</span>');
              to_top('#atbdp_categories');
              return false;
          }
      }
       //address
      if ($("input[name='address']").length > 0) {
          var address = $("input[name='address']").val();
          var required_address = add_listing_validator.address;
          if ('' === address && '' !== required_address) {
              $("input[name='address']").after('<span class="atbdp_required">' + w_icon + required_address + '</span>');
              to_top('#atbdp_address');
              return false;
          }
      }
      //phone
      if ($("input[name='phone']").length > 0) {
          var phone = $("input[name='phone']").val();
          var required_phone = add_listing_validator.phone;
          if ('' === phone && '' !== required_phone && !need_post) {
              $("#atbdp_phone").append('<span class="atbdp_required">' + w_icon + required_phone + '</span>');
              to_top('#atbdp_phone');
              return false;
          }
      }
      //phone2
      if ($("input[name='phone2']").length > 0) {
          var phone = $("input[name='phone2']").val();
          var required_phone2 = add_listing_validator.phone2;
          if ('' === phone && '' !== required_phone2 && !need_post) {
              $("#atbdp_phone2").append('<span class="atbdp_required">' + w_icon + required_phone2 + '</span>');
              to_top('#atbdp_phone2');
              return false;
          }
      }
      //fax
      if ($("input[name='fax']").length > 0) {
          var fax = $("input[name='fax']").val();
          var required_fax = add_listing_validator.fax;
          if ('' === fax && '' !== required_fax && !need_post) {
              $("#atbdp_fax_number").append('<span class="atbdp_required">' + w_icon + required_fax + '</span>');
              to_top('#atbdp_fax_number');
              return false;
          }
      }
      //email
      if ($("input[name='email']").length > 0) {
          var email = $("input[name='email']").val();
          var required_email = add_listing_validator.email;
          if ('' === email && '' !== required_email && !need_post) {
              $("#atbdp_emails").append('<span class="atbdp_required">' + w_icon + required_email + '</span>');
              to_top('#atbdp_emails');
              return false;
          }
      }
       //web
      if ($("input[name='website']").length > 0) {
          var web = $("input[name='website']").val();
          var required_web = add_listing_validator.web;
          if ('' === web && '' !== required_web && !need_post) {
              $("#atbdp_webs").append('<span class="atbdp_required">' + w_icon + required_web + '</span>');
              to_top('#atbdp_webs');
              return false;
          }
      }
       //zip
      if ($("input[name='zip']").length > 0) {
          var zip = $("input[name='zip']").val();
          var required_zip = add_listing_validator.zip;
          if ('' === zip && '' !== required_zip && !need_post) {
              $("#atbdp_zip_code").append('<span class="atbdp_required">' + w_icon + required_zip + '</span>');
              to_top('#atbdp_zip_code');
              return false;
          }
      }
       //Sinfo
      if ($("#atbdp_socialInFo").length > 0) {
          var Sinfo = $(".directorist-form-social-fields").length;
          var required_Sinfo = add_listing_validator.Sinfo;
          if (0 === Sinfo && '' !== required_Sinfo && !need_post) {
              $("#atbdp_socialInFo").append('<span class="atbdp_required">' + w_icon + required_Sinfo + '</span>');
              to_top('#atbdp_socialInFo');
              return false;
          }
      }
       //video
      if ($("input[name='videourl']").length > 0) {
          var video = $("input[name='videourl']").val();
          var required_video = add_listing_validator.video;
          if ('' === video && '' !== required_video && !need_post) {
              $("input[name='videourl']").after('<span class="atbdp_required">' + w_icon + required_video + '</span>');
              return false;
          }
      }
       //privacy
      if ($("#privacy_policy").length > 0) {
          var privacy = $("#privacy_policy").is(":checked");
          var required_privacy = add_listing_validator.require_privacy;
          if (false === privacy && '' !== required_privacy) {
              $(".directorist-form-privacy").append('<span class="atbdp_required" style="text-align: center;display: block; margin-bottom: 10px">' + w_icon + required_privacy + '</span>');
              return false;
          }
      }
       // terms and conditions
      if ($("#listing_t").length > 0) {
          var terms = $("#listing_t").is(":checked");
          var required_terms = add_listing_validator.terms;
          if (false === terms && '' !== required_terms) {
              $(".directorist-form-terms").append('<span class="atbdp_required" style="text-align: center;display: block; margin-bottom: 10px">' + w_icon + required_terms + '</span>');
              return false;
          }
      }
       //guest user
      if ($("#guest_user_email").length > 0) {
          var guest_user_email = $("input[name='guest_user_email']").val();
          var allow_guest = add_listing_validator.guest_user;
          if ('' === guest_user_email && '' !== allow_guest) {
              $("input[name='guest_user_email']").after('<span class="atbdp_required">' + w_icon + allow_guest + '</span>');
              return false;
          }
      }
  });
  */

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