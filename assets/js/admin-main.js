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
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets/src/js/admin/admin.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/admin/admin.js":
/*!**************************************!*\
  !*** ./assets/src/js/admin/admin.js ***!
  \**************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _scss_layout_admin_admin_style_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../scss/layout/admin/admin-style.scss */ "./assets/src/scss/layout/admin/admin-style.scss");
/* harmony import */ var _scss_layout_admin_admin_style_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_scss_layout_admin_admin_style_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _modules_pureScriptSearchSelect__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../modules/pureScriptSearchSelect */ "./assets/src/js/modules/pureScriptSearchSelect.js");
/* harmony import */ var _modules_utility__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../modules/utility */ "./assets/src/js/modules/utility.js");
/* harmony import */ var _modules_utility__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_modules_utility__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _components_admin_block_1__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./../components/admin/block-1 */ "./assets/src/js/components/admin/block-1.js");
/* harmony import */ var _components_admin_block_1__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_components_admin_block_1__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _components_admin_block_2__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./../components/admin/block-2 */ "./assets/src/js/components/admin/block-2.js");
/* harmony import */ var _components_admin_block_2__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_components_admin_block_2__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _components_admin_block_3__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./../components/admin/block-3 */ "./assets/src/js/components/admin/block-3.js");
/* harmony import */ var _components_admin_block_3__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_components_admin_block_3__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _components_admin_block_4__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./../components/admin/block-4 */ "./assets/src/js/components/admin/block-4.js");
/* harmony import */ var _components_admin_block_4__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_components_admin_block_4__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _components_admin_block_5__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./../components/admin/block-5 */ "./assets/src/js/components/admin/block-5.js");
/* harmony import */ var _components_admin_block_5__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_components_admin_block_5__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _components_admin_admin_user__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./../components/admin/admin-user */ "./assets/src/js/components/admin/admin-user.js");
/* harmony import */ var _components_admin_admin_user__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_components_admin_admin_user__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var _components_admin_subscriptionManagement__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./../components/admin/subscriptionManagement */ "./assets/src/js/components/admin/subscriptionManagement.js");
/* harmony import */ var _components_admin_subscriptionManagement__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_components_admin_subscriptionManagement__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var _components_modal__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../components/modal */ "./assets/src/js/components/modal.js");
 // Modules


 // Blocks






 // subscriptionManagement

 // Modal Components



/***/ }),

/***/ "./assets/src/js/components/admin/admin-user.js":
/*!******************************************************!*\
  !*** ./assets/src/js/components/admin/admin-user.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// user type change on user dashboard
(function ($) {
  $('#atbdp-user-type-approve').on('click', function (event) {
    event.preventDefault();
    var userId = $(this).attr('data-userId');
    var nonce = $(this).attr('data-nonce');
    $.ajax({
      type: 'post',
      url: atbdp_admin_data.ajaxurl,
      data: {
        action: 'atbdp_user_type_approved',
        _nonce: nonce,
        userId: userId
      },
      success: function success(response) {
        if (response.user_type) {
          $('#user-type-' + userId).html(response.user_type);
        }
      },
      error: function error(response) {// $('#atbdp-remote-response').val(response.data.error);
      }
    });
    return false;
  });
  $('#atbdp-user-type-deny').on('click', function (event) {
    event.preventDefault();
    var userId = $(this).attr('data-userId');
    var nonce = $(this).attr('data-nonce');
    $.ajax({
      type: 'post',
      url: atbdp_admin_data.ajaxurl,
      data: {
        action: 'atbdp_user_type_deny',
        _nonce: nonce,
        userId: userId
      },
      success: function success(response) {
        if (response.user_type) {
          $('#user-type-' + userId).html(response.user_type);
        }
      },
      error: function error(response) {// $('#atbdp-remote-response').val(response.data.error);
      }
    });
    return false;
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/components/admin/block-1.js":
/*!***************************************************!*\
  !*** ./assets/src/js/components/admin/block-1.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var $ = jQuery;
var content = ''; // Category icon selection

function selecWithIcon(selected) {
  if (!selected.id) {
    return selected.text;
  }

  var $elem = $("<span><span class='".concat(atbdp_admin_data.icon_type, " ").concat(selected.element.value, "'></span> ").concat(selected.text, "</span>"));
  return $elem;
}

$("[data-toggle='tooltip']").tooltip(); // price range

var pricerange = $('#pricerange_val').val();

if (pricerange) {
  $('#pricerange').fadeIn(100);
}

$('#price_range_option').on('click', function () {
  $('#pricerange').fadeIn(500);
}); // enable sorting if only the container has any social or skill field

var $s_wrap = $('#social_info_sortable_container'); // cache it

if (window.outerWidth > 1700) {
  if ($s_wrap.length) {
    $s_wrap.sortable({
      axis: 'y',
      opacity: '0.7'
    });
  }
} // SOCIAL SECTION
// Rearrange the IDS and Add new social field

/* $('body').on('click', '#addNewSocial', function () {
    const social_wrap = $('#social_info_sortable_container'); // cache it
    const currentItems = $('.directorist-form-social-fields').length;
    const ID = `id=${currentItems}`; // eg. 'id=3'
    const iconBindingElement = jQuery('#addNewSocial');
    // arrange names ID in order before adding new elements
    $('.directorist-form-social-fields').each(function (index, element) {
        const e = $(element);
        e.attr('id', `socialID-${index}`);
        e.find('select').attr('name', `social[${index}][id]`);
        e.find('.atbdp_social_input').attr('name', `social[${index}][url]`);
        e.find('.directorist-form-social-fields__remove').attr('data-id', index);
    });
    // now add the new elements. we could do it here without using ajax but it would require more markup here.
    atbdp_do_ajax(iconBindingElement, 'atbdp_social_info_handler', ID, function (data) {
        //social_wrap.append(data);
    });
}); */
// remove the social field and then reset the ids while maintaining position


$(document).on('click', '.directorist-form-social-fields__remove', function (e) {
  var id = $(this).data('id');
  var elementToRemove = $("div#socialID-".concat(id));
  e.preventDefault();
  /* Act on the event */

  swal({
    title: atbdp_admin_data.i18n_text.confirmation_text,
    text: atbdp_admin_data.i18n_text.ask_conf_sl_lnk_del_txt,
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#DD6B55',
    confirmButtonText: atbdp_admin_data.i18n_text.confirm_delete,
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
        title: atbdp_admin_data.i18n_text.deleted,
        // text: "Item has been deleted.",
        type: 'success',
        timer: 200,
        showConfirmButton: false
      });
    }
  });
}); // upgrade old listing

$('#upgrade_directorist').on('click', function (event) {
  event.preventDefault();
  var $this = $(this); // display a notice to user to wait
  // send an ajax request to the back end

  atbdp_do_ajax($this, 'atbdp_upgrade_old_listings', null, function (response) {
    if (response.success) {
      $this.after("<p>".concat(response.data, "</p>"));
    }
  });
}); // upgrade old pages

$('#shortcode-updated input[name="shortcode-updated"]').on('change', function (event) {
  event.preventDefault();
  $('#success_msg').hide();
  var $this = $(this); // display a notice to user to wait
  // send an ajax request to the back end

  atbdp_do_ajax($this, 'atbdp_upgrade_old_pages', null, function (response) {
    if (response.success) {
      $('#shortcode-updated').after("<p id=\"success_msg\">".concat(response.data, "</p>"));
    }
  });
  $('.atbdp_ajax_loading').css({
    display: 'none'
  });
}); // redirect to import import_page_link

$('#csv_import input[name="csv_import"]').on('change', function (event) {
  event.preventDefault();
  window.location = atbdp_admin_data.import_page_link;
});
/* This function handles all ajax request */

function atbdp_do_ajax(ElementToShowLoadingIconAfter, ActionName, arg, CallBackHandler) {
  var data;
  if (ActionName) data = "action=".concat(ActionName);
  if (arg) data = "".concat(arg, "&action=").concat(ActionName);
  if (arg && !ActionName) data = arg; // data = data ;

  var n = data.search(atbdp_admin_data.nonceName);

  if (n < 0) {
    data = "".concat(data, "&").concat(atbdp_admin_data.nonceName, "=").concat(atbdp_admin_data.nonce);
  }

  jQuery.ajax({
    type: 'post',
    url: atbdp_admin_data.ajaxurl,
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

/***/ }),

/***/ "./assets/src/js/components/admin/block-2.js":
/*!***************************************************!*\
  !*** ./assets/src/js/components/admin/block-2.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var $ = jQuery; // Set all variables to be used in scope

var has_tagline = $('#has_tagline').val();
var has_excerpt = $('#has_excerpt').val();

if (has_excerpt && has_tagline) {
  $('.atbd_tagline_moto_field').fadeIn();
} else {
  $('.atbd_tagline_moto_field').fadeOut();
}

$('#atbd_optional_field_check').on('change', function () {
  $(this).is(':checked') ? $('.atbd_tagline_moto_field').fadeIn() : $('.atbd_tagline_moto_field').fadeOut();
}); // Load custom fields of the selected category in the custom post type "atbdp_listings"

$('#at_biz_dir-categorychecklist, #at_biz_dir-categorychecklist-pop').on('change', function (event) {
  var length = $('#at_biz_dir-categorychecklist input:checked');
  var length2 = $('#at_biz_dir-categorychecklist-pop input:checked');
  var id = [];
  var directory_type = $('select[name="directory_type"]').val();
  var from_single_directory = $('input[name="directory_type"]').val();

  if (length) {
    length.each(function (el, index) {
      id.push($(index).val());
    });
  }

  if (length2) {
    length2.each(function (el, index) {
      id.push($(index).val());
    });
  }

  var data = {
    action: 'atbdp_custom_fields_listings',
    post_id: $('#post_ID').val(),
    term_id: id,
    directory_type: directory_type ? directory_type : from_single_directory
  };
  $.post(atbdp_admin_data.ajaxurl, data, function (response) {
    if (response) {
      var response = "<div class=\"form-group atbd_content_module\">\n                        <div class=\"atbdb_content_module_contents\">\n                          ".concat(response, "\n                        </div>\n                      </div>");
      $('.atbdp_category_custom_fields').empty().append(response);

      function atbdp_tooltip() {
        var atbd_tooltip = document.querySelectorAll('.atbd_tooltip');
        atbd_tooltip.forEach(function (el) {
          if (el.getAttribute('aria-label') !== ' ') {
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
    } else {
      $('.atbdp_category_custom_fields').empty();
    }
  });
}); // Load custom fields of the selected category in the custom post type "atbdp_listings"

$(document).ready(function () {
  var length = $('#at_biz_dir-categorychecklist input:checked');
  var length2 = $('#at_biz_dir-categorychecklist-pop input:checked');
  var id = [];
  var directory_type = $('select[name="directory_type"]').val();
  var from_single_directory = $('input[name="directory_type"]').val();

  if (length) {
    length.each(function (el, index) {
      id.push($(index).val());
    });
  }

  if (length2) {
    length2.each(function (el, index) {
      id.push($(index).val());
    });
  }

  var data = {
    action: 'atbdp_custom_fields_listings',
    post_id: $('#post_ID').val(),
    term_id: id,
    directory_type: directory_type ? directory_type : from_single_directory
  };
  $.post(atbdp_admin_data.ajaxurl, data, function (response) {
    if (response) {
      var response = "<div class=\"form-group atbd_content_module\">\n                          <div class=\"atbdb_content_module_contents\">\n                            ".concat(response, "\n                          </div>\n                        </div>");
      $('.atbdp_category_custom_fields').empty().append(response);

      function atbdp_tooltip() {
        var atbd_tooltip = document.querySelectorAll('.atbd_tooltip');
        atbd_tooltip.forEach(function (el) {
          if (el.getAttribute('aria-label') !== ' ') {
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
var avg_review = $('#average_review_for_popular').hide();
var logged_count = $('#views_for_popular').hide();

if ($('#listing_popular_by select[name="listing_popular_by"]').val() === 'average_rating') {
  avg_review.show();
  logged_count.hide();
} else if ($('#listing_popular_by select[name="listing_popular_by"]').val() === 'view_count') {
  logged_count.show();
  avg_review.hide();
} else if ($('#listing_popular_by select[name="listing_popular_by"]').val() === 'both_view_rating') {
  avg_review.show();
  logged_count.show();
}

$('#listing_popular_by select[name="listing_popular_by"]').on('change', function () {
  if ($(this).val() === 'average_rating') {
    avg_review.show();
    logged_count.hide();
  } else if ($(this).val() === 'view_count') {
    logged_count.show();
    avg_review.hide();
  } else if ($(this).val() === 'both_view_rating') {
    avg_review.show();
    logged_count.show();
  }
});
/* // Display the media uploader when "Upload Image" button clicked in the custom taxonomy "atbdp_categories"
(function ($) {
"use strict";
var content = "";
// Category icon selection
function selecWithIcon(selected) {
if (!selected.id) {
return selected.text;
}
var $elem = $(
"<span><span class='la " +
selected.element.value +
"'></span> " +
selected.text +
"</span>"
);
return $elem;
}

$("#category_icon").select2({
placeholder: atbdp_admin_data.i18n_text.icon_choose_text,
allowClear: true,
templateResult: selecWithIcon,
});

/* Show and hide manual coordinate input field */

if (!$('input#manual_coordinate').is(':checked')) {
  $('.directorist-map-coordinates').hide();
}

$('#manual_coordinate').on('click', function (e) {
  if ($('input#manual_coordinate').is(':checked')) {
    $('.directorist-map-coordinates').show();
  } else {
    $('.directorist-map-coordinates').hide();
  }
});
$("[data-toggle='tooltip']").tooltip(); // price range

var pricerange = $('#pricerange_val').val();

if (pricerange) {
  $('#pricerange').fadeIn(100);
}

$('#price_range_option').on('click', function () {
  $('#pricerange').fadeIn(500);
}); // enable sorting if only the container has any social or skill field

var $s_wrap = $('#social_info_sortable_container'); // cache it

if (window.outerWidth > 1700) {
  if ($s_wrap.length) {
    $s_wrap.sortable({
      axis: 'y',
      opacity: '0.7'
    });
  }
} // SOCIAL SECTION
// Rearrange the IDS and Add new social field

/* $('body').on('click', '#addNewSocial', function () {
    const currentItems = $('.directorist-form-social-fields').length;
    const ID = `id=${currentItems}`; // eg. 'id=3'
    const iconBindingElement = jQuery('#addNewSocial');
    // arrange names ID in order before adding new elements
    $('.directorist-form-social-fields').each(function (index, element) {
        const e = $(element);
        e.attr('id', `socialID-${index}`);
        e.find('select').attr('name', `social[${index}][id]`);
        e.find('.atbdp_social_input').attr('name', `social[${index}][url]`);
        e.find('.directorist-form-social-fields__remove').attr('data-id', index);
    });
    // now add the new elements. we could do it here without using ajax but it would require more markup here.
    atbdp_do_ajax(iconBindingElement, 'atbdp_social_info_handler', ID, function (data) {
        //$s_wrap.append(data);
    });
}); */
// remove the social field and then reset the ids while maintaining position


$(document).on('click', '.directorist-form-social-fields__remove', function (e) {
  var id = $(this).data('id');
  var elementToRemove = $("div#socialID-".concat(id));
  event.preventDefault();
  /* Act on the event */

  swal({
    title: atbdp_admin_data.i18n_text.confirmation_text,
    text: atbdp_admin_data.i18n_text.ask_conf_sl_lnk_del_txt,
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#DD6B55',
    confirmButtonText: atbdp_admin_data.i18n_text.confirm_delete,
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
        title: atbdp_admin_data.i18n_text.deleted,
        // text: "Item has been deleted.",
        type: 'success',
        timer: 200,
        showConfirmButton: false
      });
    }
  });
}); // upgrade old listing

$('#upgrade_directorist').on('click', function (event) {
  event.preventDefault();
  var $this = $(this); // display a notice to user to wait
  // send an ajax request to the back end

  atbdp_do_ajax($this, 'atbdp_upgrade_old_listings', null, function (response) {
    if (response.success) {
      $this.after("<p>".concat(response.data, "</p>"));
    }
  });
}); // upgrade old pages

$('#shortcode-updated input[name="shortcode-updated"]').on('change', function (event) {
  event.preventDefault();
  $('#success_msg').hide();
  var $this = $(this); // display a notice to user to wait
  // send an ajax request to the back end

  atbdp_do_ajax($this, 'atbdp_upgrade_old_pages', null, function (response) {
    if (response.success) {
      $('#shortcode-updated').after("<p id=\"success_msg\">".concat(response.data, "</p>"));
    }
  });
  $('.atbdp_ajax_loading').css({
    display: 'none'
  });
}); // send system info to admin

$('#atbdp-send-system-info-submit').on('click', function (event) {
  event.preventDefault();

  if (!$('#atbdp-email-subject').val()) {
    alert('The Subject field is required');
    return;
  }

  if (!$('#atbdp-email-address').val()) {
    alert('The Email field is required');
    return;
  }

  if (!$('#atbdp-email-message').val()) {
    alert('The Message field is required');
    return;
  }

  $.ajax({
    type: 'post',
    url: atbdp_admin_data.ajaxurl,
    data: {
      action: 'send_system_info',
      // calls wp_ajax_nopriv_ajaxlogin
      _nonce: $('#atbdp_email_nonce').val(),
      email: $('#atbdp-email-address').val(),
      sender_email: $('#atbdp-sender-address').val(),
      subject: $('#atbdp-email-subject').val(),
      message: $('#atbdp-email-message').val(),
      system_info_url: $('#atbdp-system-info-url').val()
    },
    beforeSend: function beforeSend() {
      $('#atbdp-send-system-info-submit').html('Sending');
    },
    success: function success(data) {
      if (data.success) {
        $('#atbdp-send-system-info-submit').html('Send Email');
        $('.system_info_success').html('Successfully sent');
      }
    },
    error: function error(data) {
      console.log(data);
    }
  });
});
/**
 * Generate new Remote View URL and display it on the admin page
 */

$('#generate-url').on('click', function (e) {
  e.preventDefault();
  $.ajax({
    type: 'post',
    url: atbdp_admin_data.ajaxurl,
    data: {
      action: 'generate_url',
      // calls wp_ajax_nopriv_ajaxlogin nonce: ()
      _nonce: $(this).attr('data-nonce')
    },
    success: function success(response) {
      $('#atbdp-remote-response').html(response.data.message);
      $('#system-info-url, #atbdp-system-info-url').val(response.data.url);
      $('#system-info-url-text-link').attr('href', response.data.url).css('display', 'inline-block');
    },
    error: function error(response) {// $('#atbdp-remote-response').val(response.data.error);
    }
  });
  return false;
});
$('#revoke-url').on('click', function (e) {
  e.preventDefault();
  $.ajax({
    type: 'post',
    url: atbdp_admin_data.ajaxurl,
    data: {
      action: 'revoke_url',
      // calls wp_ajax_nopriv_ajaxlogin
      _nonce: $(this).attr('data-nonce')
    },
    success: function success(response) {
      $('#atbdp-remote-response').html(response.data);
      $('#system-info-url, #atbdp-system-info-url').val('');
      $('#system-info-url-text-link').attr('href', '#').css('display', 'none');
    },
    error: function error(response) {// $('#atbdp-remote-response').val(response.data.error);
    }
  });
  return false;
}); // redirect to import import_page_link

$('#csv_import input[name="csv_import"]').on('change', function (event) {
  event.preventDefault();
  window.location = atbdp_admin_data.import_page_link;
});
/* This function handles all ajax request */

function atbdp_do_ajax(ElementToShowLoadingIconAfter, ActionName, arg, CallBackHandler) {
  var data;
  if (ActionName) data = "action=".concat(ActionName);
  if (arg) data = "".concat(arg, "&action=").concat(ActionName);
  if (arg && !ActionName) data = arg; // data = data ;

  var n = data.search(atbdp_admin_data.nonceName);

  if (n < 0) {
    data = "".concat(data, "&").concat(atbdp_admin_data.nonceName, "=").concat(atbdp_admin_data.nonce);
  }

  jQuery.ajax({
    type: 'post',
    url: atbdp_admin_data.ajaxurl,
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

/***/ }),

/***/ "./assets/src/js/components/admin/block-3.js":
/*!***************************************************!*\
  !*** ./assets/src/js/components/admin/block-3.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var $ = jQuery; // Custom Image uploader for listing image
// Set all variables to be used in scope

var frame;
var selection;
var multiple_image = true;
var metaBox = $('#gallery_upload'); // meta box id here

var addImgLink = metaBox.find('#listing_image_btn');
var delImgLink = metaBox.find('#delete-custom-img');
var imgContainer = metaBox.find('.listing-img-container'); // toggle_section

function toggle_section(show_if_value, subject_elm, terget_elm) {
  if (show_if_value === subject_elm.val()) {
    terget_elm.show();
  } else {
    terget_elm.hide();
  }
} // ADD IMAGE LINK


$('body').on('click', '#listing_image_btn', function (event) {
  event.preventDefault(); // If the media frame already exists, reopen it.

  if (frame) {
    frame.open();
    return;
  } // Create a new media frame


  frame = wp.media({
    title: atbdp_admin_data.i18n_text.upload_image,
    button: {
      text: atbdp_admin_data.i18n_text.choose_image
    },
    library: {
      type: 'image'
    },
    // only allow image upload only
    multiple: multiple_image // Set to true to allow multiple files to be selected. it will be set based on the availability of Multiple Image extension

  }); // When an image is selected in the media frame...

  frame.on('select', function () {
    /* get the image collection array if the MI extension is active */

    /* One little hints: a constant can not be defined inside the if block */
    if (multiple_image) {
      selection = frame.state().get('selection').toJSON();
    } else {
      selection = frame.state().get('selection').first().toJSON();
    }

    var data = ''; // create a placeholder to save all our image from the selection of media uploader
    // if no image exist then remove the place holder image before appending new image

    if ($('.single_attachment').length === 0) {
      $('.listing-img-container').html('');
    } // handle multiple image uploading.......


    if (multiple_image) {
      $(selection).each(function () {
        // here el === this
        // append the selected element if it is an image
        if (this.type === 'image') {
          // we have got an image attachment so lets proceed.
          // target the input field and then assign the current id of the attachment to an array.
          data += '<div class="single_attachment">';
          data += "<input class=\"listing_image_attachment\" name=\"listing_img[]\" type=\"hidden\" value=\"".concat(this.id, "\">");
          data += "<img style=\"width: 100%; height: 100%;\" src=\"".concat(this.url, "\" alt=\"Listing Image\" /> <span class=\"remove_image fa fa-times\" title=\"Remove it\"></span></div>");
        }
      });
    } else {
      // Handle single image uploading
      // add the id to the input field of the image uploader and then save the ids in the database as a post meta
      // so check if the attachment is really an image and reject other types
      if (selection.type === 'image') {
        // we have got an image attachment so lets proceed.
        // target the input field and then assign the current id of the attachment to an array.
        data += '<div class="single_attachment">';
        data += "<input class=\"listing_image_attachment\" name=\"listing_img[]\" type=\"hidden\" value=\"".concat(selection.id, "\">");
        data += "<img style=\"width: 100%; height: 100%;\" src=\"".concat(selection.url, "\" alt=\"Listing Image\" /> <span class=\"remove_image  fa fa-times\" title=\"Remove it\"></span></div>");
      }
    } // If MI extension is active then append images to the listing, else only add one image replacing previous upload


    if (multiple_image) {
      $('.listing-img-container').append(data);
    } else {
      $('.listing-img-container').html(data);
    } // Un-hide the remove image link


    delImgLink.removeClass('hidden');
  }); // Finally, open the modal on click

  frame.open();
}); // DELETE ALL IMAGES LINK

delImgLink.on('click', function (event) {
  event.preventDefault(); // Clear out the preview image and set no image as placeholder

  $('.listing-img-container').html("<img src=\"".concat(atbdp_admin_data.AdminAssetPath, "images/no-image.png\" alt=\"Listing Image\" />")); // Hide the delete image link

  delImgLink.addClass('hidden');
});
/* REMOVE SINGLE IMAGE */

$(document).on('click', '.remove_image', function (e) {
  e.preventDefault();
  $(this).parent().remove(); // if no image exist then add placeholder and hide remove image button

  if ($('.single_attachment').length === 0) {
    $('.listing-img-container').html("<img src=\"".concat(atbdp_admin_data.AdminAssetPath, "images/no-image.png\" alt=\"Listing Image\" /><p>No images</p> ") + "<small>(allowed formats jpeg. png. gif)</small>");
    delImgLink.addClass('hidden');
  }
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
});
var imageUpload;

if (imageUpload) {
  imageUpload.open();
}

$('.upload-header').on('click', function (element) {
  element.preventDefault();
  imageUpload = wp.media.frames.file_frame = wp.media({
    title: atbdp_admin_data.i18n_text.select_prv_img,
    button: {
      text: atbdp_admin_data.i18n_text.insert_prv_img
    }
  });
  imageUpload.open();
  imageUpload.on('select', function () {
    prv_image = imageUpload.state().get('selection').first().toJSON();
    prv_url = prv_image.id;
    prv_img_url = prv_image.url;
    $('.listing_prv_img').val(prv_url);
    $('.change_listing_prv_img').attr('src', prv_img_url);
    $('.upload-header').html('Change Preview Image');
    $('.remove_prev_img').show();
  });
  imageUpload.open();
});
$('.remove_prev_img').on('click', function (e) {
  $(this).hide();
  $('.listing_prv_img').attr('value', '');
  $('.change_listing_prv_img').attr('src', '');
  e.preventDefault();
});

if ($('.change_listing_prv_img').attr('src') === '') {
  $('.remove_prev_img').hide();
} else if ($('.change_listing_prv_img').attr('src') !== '') {
  $('.remove_prev_img').show();
} // price range

/* $('#price_range').hide();
const is_checked = $('#atbd_listing_pricing').val();
if (is_checked === 'range') {
    $('#price').hide();
    $('#price_range').show();
}
$('.atbd_pricing_options label').on('click', function () {
    const $this = $(this);
    $this.children('input[type=checkbox]').prop('checked') == true
        ? $(`#${$this.data('option')}`).show()
        : $(`#${$this.data('option')}`).hide();
    const $sibling = $this.siblings('label');
    $sibling.children('input[type=checkbox]').prop('checked', false);
    $(`#${$sibling.data('option')}`).hide();
}); */


var avg_review = $('#average_review_for_popular').hide();
var logged_count = $('#views_for_popular').hide();

if ($('#listing_popular_by select[name="listing_popular_by"]').val() === 'average_rating') {
  avg_review.show();
  logged_count.hide();
} else if ($('#listing_popular_by select[name="listing_popular_by"]').val() === 'view_count') {
  logged_count.show();
  avg_review.hide();
} else if ($('#listing_popular_by select[name="listing_popular_by"]').val() === 'both_view_rating') {
  avg_review.show();
  logged_count.show();
}

$('#listing_popular_by select[name="listing_popular_by"]').on('change', function () {
  if ($(this).val() === 'average_rating') {
    avg_review.show();
    logged_count.hide();
  } else if ($(this).val() === 'view_count') {
    logged_count.show();
    avg_review.hide();
  } else if ($(this).val() === 'both_view_rating') {
    avg_review.show();
    logged_count.show();
  }
});
/* // Display the media uploader when "Upload Image" button clicked in the custom taxonomy "atbdp_categories"
$( '#atbdp-categories-upload-image' ).on( 'click', function( e ) {

if (frame) {
 frame.open();
 return;
}

// Create a new media frame
frame = wp.media({
 title: atbdp_admin_data.i18n_text.upload_cat_image,
 button: {
     text: atbdp_admin_data.i18n_text.choose_image
 },
 library: {type: 'image'}, // only allow image upload only
 multiple: multiple_image  // Set to true to allow multiple files to be selected. it will be set based on the availability of Multiple Image extension
});
frame.open();
}); */

/**
 * Display the media uploader for selecting an image.
 *
 * @since    1.0.0
 */

function atbdp_render_media_uploader(page) {
  var file_frame;
  var image_data;
  var json; // If an instance of file_frame already exists, then we can open it rather than creating a new instance

  if (undefined !== file_frame) {
    file_frame.open();
    return;
  } // Here, use the wp.media library to define the settings of the media uploader


  file_frame = wp.media.frames.file_frame = wp.media({
    frame: 'post',
    state: 'insert',
    multiple: false
  }); // Setup an event handler for what to do when an image has been selected

  file_frame.on('insert', function () {
    // Read the JSON data returned from the media uploader
    json = file_frame.state().get('selection').first().toJSON(); // First, make sure that we have the URL of an image to display

    if ($.trim(json.url.length) < 0) {
      return;
    } // After that, set the properties of the image and display it


    if (page == 'listings') {
      var html = "".concat('<tr class="atbdp-image-row">' + '<td class="atbdp-handle"><span class="dashicons dashicons-screenoptions"></span></td>' + '<td class="atbdp-image">' + '<img src="').concat(json.url, "\" />") + "<input type=\"hidden\" name=\"images[]\" value=\"".concat(json.id, "\" />") + "</td>" + "<td>".concat(json.url, "<br />") + "<a href=\"post.php?post=".concat(json.id, "&action=edit\" target=\"_blank\">").concat(atbdp.edit, "</a> | ") + "<a href=\"javascript:;\" class=\"atbdp-delete-image\" data-attachment_id=\"".concat(json.id, "\">").concat(atbdp.delete_permanently, "</a>") + "</td>" + "</tr>";
      $('#atbdp-images').append(html);
    } else {
      $('#atbdp-categories-image-id').val(json.id);
      $('#atbdp-categories-image-wrapper').html("<img src=\"".concat(json.url, "\" /><a href=\"\" class=\"remove_cat_img\"><span class=\"fa fa-times\" title=\"Remove it\"></span></a>"));
    }
  }); // Now display the actual file_frame

  file_frame.open();
} // Display the media uploader when "Upload Image" button clicked in the custom taxonomy "atbdp_categories"


$('#atbdp-categories-upload-image').on('click', function (e) {
  e.preventDefault();
  atbdp_render_media_uploader('categories');
});
$('#submit').on('click', function () {
  $('#atbdp-categories-image-wrapper img').attr('src', '');
  $('.remove_cat_img').remove();
});
$(document).on('click', '.remove_cat_img', function (e) {
  e.preventDefault();
  $(this).hide();
  $(this).prev('img').remove();
  $('#atbdp-categories-image-id').attr('value', '');
}); // Announcement
// ----------------------------------------------------------------------------------
// Display Announcement Recepents

var announcement_to = $('select[name="announcement_to"]');
var announcement_recepents_section = $('#announcement_recepents');
toggle_section('selected_user', announcement_to, announcement_recepents_section);
announcement_to.on('change', function () {
  toggle_section('selected_user', $(this), announcement_recepents_section);
});
var submit_button = $('#announcement_submit .vp-input ~ span');
var form_feedback = $('#announcement_submit .field');
form_feedback.prepend('<div class="announcement-feedback"></div>');
var announcement_is_sending = false; // Send Announcement

submit_button.on('click', function () {
  if (announcement_is_sending) {
    console.log('Please wait...');
    return;
  }

  var to = $('select[name="announcement_to"]');
  var recepents = $('select[name="announcement_recepents"]');
  var subject = $('input[name="announcement_subject"]');
  var message = $('textarea[name="announcement_message"]');
  var expiration = $('input[name="announcement_expiration"]');
  var send_to_email = $('input[name="announcement_send_to_email"]');
  var fields_elm = {
    to: {
      elm: to,
      value: to.val(),
      default: 'all_user'
    },
    recepents: {
      elm: recepents,
      value: recepents.val(),
      default: null
    },
    subject: {
      elm: subject,
      value: subject.val(),
      default: ''
    },
    message: {
      elm: message,
      value: message.val(),
      default: ''
    },
    expiration: {
      elm: expiration,
      value: expiration.val(),
      default: 3
    },
    send_to_email: {
      elm: send_to_email.val(),
      value: send_to_email.val(),
      default: 1
    }
  }; // Send the form

  var form_data = new FormData(); // Fillup the form

  form_data.append('action', 'atbdp_send_announcement');

  for (field in fields_elm) {
    form_data.append(field, fields_elm[field].value);
  }

  announcement_is_sending = true;
  jQuery.ajax({
    type: 'post',
    url: atbdp_admin_data.ajaxurl,
    data: form_data,
    processData: false,
    contentType: false,
    beforeSend: function beforeSend() {
      // console.log( 'Sending...' );
      form_feedback.find('.announcement-feedback').html('<div class="form-alert">Sending the announcement, please wait..</div>');
    },
    success: function success(response) {
      // console.log( {response} );
      announcement_is_sending = false;

      if (response.message) {
        form_feedback.find('.announcement-feedback').html("<div class=\"form-alert\">".concat(response.message, "</div>"));
      }
    },
    error: function error(_error) {
      console.log({
        error: _error
      });
      announcement_is_sending = false;
    }
  }); // Reset Form

  /* for ( var field in fields_elm  ) {
  $( fields_elm[ field ].elm ).val( fields_elm[ field ].default );
  } */
}); // ----------------------------------------------------------------------------------
// Custom Tab Support Status

$('.atbds_wrapper a.nav-link').on('click', function (e) {
  e.preventDefault();
  console.log($(this).data('tabarea'));
  var atbds_tabParent = $(this).parent().parent().find('a.nav-link');
  var $href = $(this).attr('href');
  $(atbds_tabParent).removeClass('active');
  $(this).addClass('active');
  console.log($(".tab-content[data-tabarea='atbds_system-info-tab']"));

  switch ($(this).data('tabarea')) {
    case 'atbds_system-status-tab':
      $(".tab-content[data-tabarea='atbds_system-status-tab'] >.tab-pane").removeClass('active show');
      $(".tab-content[data-tabarea='atbds_system-status-tab'] ".concat($href)).addClass('active show');
      break;

    case 'atbds_system-info-tab':
      $(".tab-content[data-tabarea='atbds_system-info-tab'] >.tab-pane").removeClass('active show');
      $(".tab-content[data-tabarea='atbds_system-info-tab'] ".concat($href)).addClass('active show');
      break;

    default:
      break;
  }
}); // Custom Tooltip Support Added

$('.atbds_tooltip').on('hover', function () {
  var toolTipLabel = $(this).data('label');
  console.log(toolTipLabel);
  $(this).find('.atbds_tooltip__text').text(toolTipLabel);
  $(this).find('.atbds_tooltip__text').addClass('show');
});
$('.atbds_tooltip').on('mouseleave', function () {
  $('.atbds_tooltip__text').removeClass('show');
});
var directory_type = $('select[name="directory_type"]').val();

if (directory_type) {
  admin_listing_form(directory_type);
}

var localized_data = atbdp_public_data.add_listing_data;
$('body').on('change', 'select[name="directory_type"]', function () {
  $(this).parent('.inside').append("<span class=\"directorist_loader\"></span>");
  admin_listing_form($(this).val());
  $(this).closest('#poststuff').find('#publishing-action').addClass('directorist_disable');

  if (!localized_data.is_admin) {
    if ($('#directorist-select-st-s-js').length) {
      pureScriptSelect('#directorist-select-st-s-js');
    }

    if ($('#directorist-select-st-e-js').length) {
      pureScriptSelect('#directorist-select-st-e-js');
    }

    if ($('#directorist-select-sn-s-js').length) {
      pureScriptSelect('#directorist-select-sn-s-js');
    }

    if ($('#directorist-select-mn-e-js').length) {
      pureScriptSelect('#directorist-select-sn-e-js');
    }

    if ($('#directorist-select-mn-s-js').length) {
      pureScriptSelect('#directorist-select-mn-s-js');
    }

    if ($('#directorist-select-mn-e-js').length) {
      pureScriptSelect('#directorist-select-mn-e-js');
    }

    if ($('#directorist-select-tu-s-js').length) {
      pureScriptSelect('#directorist-select-tu-s-js');
    }

    if ($('#directorist-select-tu-e-js').length) {
      pureScriptSelect('#directorist-select-tu-e-js');
    }

    if ($('#directorist-select-wd-s-js').length) {
      pureScriptSelect('#directorist-select-wd-s-js');
    }

    if ($('#directorist-select-wd-e-js').length) {
      pureScriptSelect('#directorist-select-wd-e-js');
    }

    if ($('#directorist-select-th-s-js').length) {
      pureScriptSelect('#directorist-select-th-s-js');
    }

    if ($('#directorist-select-th-e-js').length) {
      pureScriptSelect('#directorist-select-th-e-js');
    }

    if ($('#directorist-select-fr-s-js').length) {
      pureScriptSelect('#directorist-select-fr-s-js');
    }

    if ($('#directorist-select-fr-e-js').length) {
      pureScriptSelect('#directorist-select-fr-e-js');
    }
  }
});

function admin_listing_form(directory_type) {
  $.ajax({
    type: 'post',
    url: atbdp_admin_data.ajaxurl,
    data: {
      action: 'atbdp_dynamic_admin_listing_form',
      directory_type: directory_type,
      listing_id: $('#directiost-listing-fields_wrapper').data('id')
    },
    success: function success(response) {
      // console.log( response );
      $('#directiost-listing-fields_wrapper').empty().append(response.data['listing_meta_fields']);
      assetsNeedToWorkInVirtualDom();
      $('#at_biz_dir-locationchecklist').empty().html(response.data['listing_locations']);
      $('#at_biz_dir-categorychecklist').empty().html(response.data['listing_categories']);
      $('#at_biz_dir-categorychecklist-pop').empty().html(response.data['listing_pop_categories']);
      $('#at_biz_dir-locationchecklist-pop').empty().html(response.data['listing_pop_locations']);
      $('.misc-pub-atbdp-expiration-time').empty().html(response.data['listing_expiration']);
      $('#listing_form_info').find('.directorist_loader').remove();
      $('select[name="directory_type"]').closest('#poststuff').find('#publishing-action').removeClass('directorist_disable');
      window.dispatchEvent(new CustomEvent('directorist-reload-plupload'));

      if (response.data['required_js_scripts']) {
        var scripts = response.data['required_js_scripts'];

        for (var script_id in scripts) {
          var old_script = document.getElementById(script_id);

          if (old_script) {
            old_script.remove();
          }

          var script = document.createElement('script');
          script.id = script_id;
          script.src = scripts[script_id];
          document.body.appendChild(script);
        }
      }
    },
    error: function error(_error2) {
      console.log({
        error: _error2
      });
    }
  });
} // default directory type


$('body').on('click', '.submitdefault', function (e) {
  e.preventDefault();
  $(this).children('.submitDefaultCheckbox').prop('checked', true);
  var defaultSubmitDom = $(this);
  defaultSubmitDom.closest('.directorist_listing-actions').append("<span class=\"directorist_loader\"></span>");
  $.ajax({
    type: 'post',
    url: atbdp_admin_data.ajaxurl,
    data: {
      action: 'atbdp_listing_default_type',
      type_id: $(this).data('type-id')
    },
    success: function success(response) {
      defaultSubmitDom.closest('.directorist_listing-actions').siblings('.directorist_notifier').append("<span class=\"atbd-listing-type-active-status\">".concat(response, "</span>"));
      defaultSubmitDom.closest('.directorist_listing-actions').children('.directorist_loader').remove();
      setTimeout(function () {
        location.reload();
      }, 500);
    }
  });
});

function assetsNeedToWorkInVirtualDom() {
  // price range
  $('#price_range').hide();
  var pricing = $('#atbd_listing_pricing').val();

  if (pricing === 'range') {
    $('#price').hide();
    $('#price_range').show();
  }

  $('.atbd_pricing_options label').on('click', function () {
    var $this = $(this);
    $this.children('input[type=checkbox]').prop('checked') == true ? $("#".concat($this.data('option'))).show() : $("#".concat($this.data('option'))).hide();
    var $sibling = $this.siblings('label');
    $sibling.children('input[type=checkbox]').prop('checked', false);
    $("#".concat($sibling.data('option'))).hide();
  });
  $('.directorist_pricing_options label').on('click', function () {
    var self = $(this);
    var current_input = self.attr('for');
    var current_field = "#".concat(self.data('option'));
    $('.directorist_pricing_options input[type=checkbox]').prop('checked', false);
    $('.directorist_pricing_options input[id=' + current_input + ']').attr('checked', true);
    $('.directory_pricing_field').hide();
    $(current_field).show();
  });
  var imageUpload;

  if (imageUpload) {
    imageUpload.open();
    return;
  }

  $('.upload-header').on('click', function (element) {
    element.preventDefault();
    imageUpload = wp.media.frames.file_frame = wp.media({
      title: atbdp_admin_data.i18n_text.select_prv_img,
      button: {
        text: atbdp_admin_data.i18n_text.insert_prv_img
      }
    });
    imageUpload.open();
    imageUpload.on('select', function () {
      prv_image = imageUpload.state().get('selection').first().toJSON();
      prv_url = prv_image.id;
      prv_img_url = prv_image.url;
      $('.listing_prv_img').val(prv_url);
      $('.change_listing_prv_img').attr('src', prv_img_url);
      $('.upload-header').html('Change Preview Image');
      $('.remove_prev_img').show();
    });
    imageUpload.open();
  });
  $('.remove_prev_img').on('click', function (e) {
    $(this).hide();
    $('.listing_prv_img').attr('value', '');
    $('.change_listing_prv_img').attr('src', '');
    e.preventDefault();
  });

  if ($('.change_listing_prv_img').attr('src') === '') {
    $('.remove_prev_img').hide();
  } else if ($('.change_listing_prv_img').attr('src') !== '') {
    $('.remove_prev_img').show();
  }
  /* Show and hide manual coordinate input field */


  if (!$('input#manual_coordinate').is(':checked')) {
    $('.directorist-map-coordinates').hide();
  }

  $('#manual_coordinate').on('click', function (e) {
    if ($('input#manual_coordinate').is(':checked')) {
      $('.directorist-map-coordinates').show();
    } else {
      $('.directorist-map-coordinates').hide();
    }
  });
}

/***/ }),

/***/ "./assets/src/js/components/admin/block-4.js":
/*!***************************************************!*\
  !*** ./assets/src/js/components/admin/block-4.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var $ = jQuery;
/*
    Plugin: PureScriptTab
    Version: 1.0.0
    License: MIT
*/

var pureScriptTab = function pureScriptTab(selector1) {
  var selector = document.querySelectorAll(selector1);
  selector.forEach(function (el, index) {
    a = el.querySelectorAll('.directorist-tab__nav__link');
    a.forEach(function (element, index) {
      element.style.cursor = 'pointer';
      element.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        var ul = event.target.closest('.directorist-tab__nav');
        var main = ul.nextElementSibling;
        var item_a = ul.querySelectorAll('.directorist-tab__nav__link');
        var section = main.querySelectorAll('.directorist-tab__pane');
        item_a.forEach(function (ela, ind) {
          ela.classList.remove('directorist-tab__nav__active');
        });
        event.target.classList.add('directorist-tab__nav__active');
        section.forEach(function (element1, index) {
          // console.log(element1);
          element1.classList.remove('directorist-tab__pane--active');
        });
        var target = event.target.target;
        document.getElementById(target).classList.add('directorist-tab__pane--active');
      });
    });
  });
};

pureScriptTab('.directorist_builder--tab');
/* Copy shortcodes on click */

$('body').on('click', '.atbdp_shortcodes', function () {
  var $this = $(this);
  var $temp = $('<input>');
  $('body').append($temp);
  $temp.val($(this).text()).select();
  document.execCommand('copy');
  $temp.remove();
  $(this).after("<p class='copy-notify' style='color: #32cc6f; margin-top: 5px;'>Copied to clipboard!</p>");
  setTimeout(function () {
    $this.siblings('.copy-notify').fadeOut(300, function () {
      $(this).remove();
    });
  }, 3000);
});

/***/ }),

/***/ "./assets/src/js/components/admin/block-5.js":
/*!***************************************************!*\
  !*** ./assets/src/js/components/admin/block-5.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var $ = jQuery; // Category icon selection

function selecWithIcon(selected) {
  if (!selected.id) {
    return selected.text;
  }

  var $elem = $("<span><span class='".concat(selected.element.value, "'></span> ").concat(selected.text, "</span>"));
  return $elem;
}

$('#category_icon').select2({
  placeholder: atbdp_admin_data.i18n_text.icon_choose_text,
  allowClear: true,
  templateResult: selecWithIcon
});
$('body').on('click', '.directorist_settings-trigger', function () {
  $('.setting-left-sibebar').toggleClass('active');
  $('.directorist_settings-panel-shade').toggleClass('active');
});
$('body').on('click', '.directorist_settings-panel-shade', function () {
  $('.setting-left-sibebar').removeClass('active');
  $(this).removeClass('active');
}); // $('body').on('click', '.directorist_dropdown .directorist_dropdown-toggle', function(){
//   $('.directorist_dropdown-option').toggle();
// });
// // Select Option after click
// $('body').on('click','.directorist_dropdown .directorist_dropdown-option ul li a', function(){
//   console.log("works");
//   let optionText = $(this).html();
//   $('.directorist_dropdown .directorist_dropdown-toggle .directorist_dropdown-toggle__text').html(optionText);
//   $('.directorist_dropdown-option').hide();
// });
// // Hide Clicked Anywhere
// $(document).bind('click', function(e) {
//   let clickedDom = $(e.target);
//   if(!clickedDom.parents().hasClass('directorist_dropdown'))
//   $('.directorist_dropdown-option').hide();
// });
// Directorist More Dropdown

$('body').on('click', '.directorist_more-dropdown-toggle', function (e) {
  e.preventDefault();
  $(this).toggleClass('active');
  $('.directorist_more-dropdown-option').removeClass('active');
  $(this).siblings('.directorist_more-dropdown-option').removeClass('active');
  $(this).next('.directorist_more-dropdown-option').toggleClass('active');
  e.stopPropagation();
});
$(document).on('click', function (e) {
  if ($(e.target).is('.directorist_more-dropdown-toggle, .active') === false) {
    $('.directorist_more-dropdown-option').removeClass('active');
    $('.directorist_more-dropdown-toggle').removeClass('active');
  }
}); // Select Dropdown 

$('body').on('click', '.directorist_dropdown .directorist_dropdown-toggle', function (e) {
  e.preventDefault();
  $(this).siblings('.directorist_dropdown-option').toggle();
}); // Select Option after click

$('body').on('click', '.directorist_dropdown .directorist_dropdown-option ul li a', function (e) {
  e.preventDefault();
  var optionText = $(this).html();
  $(this).children('.directorist_dropdown-toggle__text').html(optionText);
  $(this).closest('.directorist_dropdown-option').siblings('.directorist_dropdown-toggle').children('.directorist_dropdown-toggle__text').html(optionText);
  $('.directorist_dropdown-option').hide();
}); // Hide Clicked Anywhere

$(document).bind('click', function (e) {
  var clickedDom = $(e.target);

  if (!clickedDom.parents().hasClass('directorist_dropdown')) {
    $('.directorist_dropdown-option').hide();
  }
}); // Tab Content
// ----------------------------------------------------------------------------------
// Modular, classes has no styling, so reusable

$('.atbdp-tab__nav-link').on('click', function (e) {
  e.preventDefault();
  var data_target = $(this).data('target');
  var current_item = $(this).parent(); // Active Nav Item

  $('.atbdp-tab__nav-item').removeClass('active');
  current_item.addClass('active'); // Active Tab Content

  $('.atbdp-tab__content').removeClass('active');
  $(data_target).addClass('active');
}); // Custom

$('.atbdp-tab-nav-menu__link').on('click', function (e) {
  e.preventDefault();
  var data_target = $(this).data('target');
  var current_item = $(this).parent(); // Active Nav Item

  $('.atbdp-tab-nav-menu__item').removeClass('active');
  current_item.addClass('active'); // Active Tab Content

  $('.atbdp-tab-content').removeClass('active');
  $(data_target).addClass('active');
}); // Section Toggle

$('.atbdp-section-toggle').on('click', function (e) {
  e.preventDefault();
  var data_target = $(this).data('target');
  $(data_target).slideToggle();
}); // Accordion Toggle

$('.atbdp-accordion-toggle').on('click', function (e) {
  e.preventDefault();
  var data_parent = $(this).data('parent');
  var data_target = $(this).data('target');

  if ($(data_target).hasClass('active')) {
    $(data_target).removeClass('active');
    $(data_target).slideUp();
  } else {
    $(data_parent).find('.atbdp-accordion-content').removeClass('active');
    $(data_target).toggleClass('active');
    $(data_parent).find('.atbdp-accordion-content').slideUp();
    $(data_target).slideToggle();
  }
});

/***/ }),

/***/ "./assets/src/js/components/admin/subscriptionManagement.js":
/*!******************************************************************!*\
  !*** ./assets/src/js/components/admin/subscriptionManagement.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

var $ = jQuery; // License Authentication
// ----------------------------------------------------------
// atbdp_get_license_authentication

var is_sending = false;
$('#atbdp-directorist-license-login-form').on('submit', function (e) {
  e.preventDefault();

  if (is_sending) {
    return;
  }

  var form = $(this);
  var submit_button = form.find('button[type="submit"]');
  var form_data = {
    action: 'atbdp_authenticate_the_customer',
    username: form.find('input[name="username"]').val(),
    password: form.find('input[name="password"]').val()
  };
  $('.atbdp-form-feedback').html('');
  is_sending = true;
  jQuery.ajax({
    type: 'post',
    url: atbdp_admin_data.ajaxurl,
    data: form_data,
    beforeSend: function beforeSend() {
      submit_button.prepend('<span class="atbdp-loading"><span class="fas fa-spinner fa-spin"></span></span>');
      submit_button.attr('disabled', true);
    },
    success: function success(response) {
      var _response$status, _response$status2;

      console.log({
        response: response
      });

      if (response.has_previous_subscriptions) {
        location.reload();
        return;
      }

      is_sending = false;
      submit_button.attr('disabled', false);
      submit_button.find('.atbdp-loading').remove();

      if (response !== null && response !== void 0 && (_response$status = response.status) !== null && _response$status !== void 0 && _response$status.log) {
        for (var feedback in response.status.log) {
          var alert_type = response.status.log[feedback].type;
          var _alert = "<div class=\"atbdp-form-alert\"";
          var alert_message = response.status.log[feedback].message;
          _alert = "<div class=\"atbdp-form-alert atbdp-form-alert-".concat(alert_type, "\">").concat(alert_message, "<div>");
          $('.atbdp-form-feedback').append(_alert);
        }
      }

      if (response !== null && response !== void 0 && (_response$status2 = response.status) !== null && _response$status2 !== void 0 && _response$status2.success) {
        location.reload();
        return;
        form.attr('id', 'atbdp-product-download-form');
        form.find('.atbdp-form-page').remove();
        var form_response_page = form.find('.atbdp-form-response-page');
        form_response_page.removeClass('atbdp-d-none'); // Append Response

        form_response_page.append('<div class="atbdp-form-feedback"></div>');
        var themes = response.license_data && response.license_data.themes ? response.license_data.themes : [];
        var plugins = response.license_data && response.license_data.plugins ? response.license_data.plugins : [];
        var total_theme = themes.length;
        var total_plugin = plugins.length; // console.log( { plugins, themes } );

        if (!plugins.length && !themes.length) {
          var title = '<h3 class="h3 form-header-title">There is no product in your purchase, redirecting...</h3>';
          form_response_page.find('.atbdp-form-feedback').append(title);
          location.reload();
          return;
        }

        var title = '<h3 class="h3 form-header-title">Activate your products</h3>';
        form_response_page.find('.atbdp-form-feedback').append(title); // Show Log - Themes

        if (total_theme) {
          var theme_section = '<div class="atbdp-checklist-section atbdp-themes-list-section"></div>';
          form_response_page.find('.atbdp-form-feedback').append(theme_section);
          var theme_title = "<h4 class=\"atbdp-theme-title\">Themes <span class=\"atbdp-count\">(".concat(themes.length, ")</span></h4>");
          var theme_check_lists = '<ul class="atbdp-check-lists atbdp-themes-list"></ul>';
          form_response_page.find('.atbdp-themes-list-section').append(theme_title);
          form_response_page.find('.atbdp-themes-list-section').append(theme_check_lists);
          var counter = 0;

          var _iterator = _createForOfIteratorHelper(themes),
              _step;

          try {
            for (_iterator.s(); !(_step = _iterator.n()).done;) {
              var theme = _step.value;
              // console.log( theme );
              var checkbox = "<input type=\"checkbox\" class=\"atbdp-checkbox atbdp-theme-checkbox-item-".concat(theme.item_id, "\" value=\"").concat(theme.item_id, "\" id=\"").concat(theme.item_id, "\">");
              var label = "<label for=\"".concat(theme.item_id, "\">").concat(theme.title, "</label>");
              var list_action = "<span class=\"atbdp-list-action\">".concat(checkbox, "</span> ");
              var li = "<li class=\"atbdp-check-list-item atbdp-theme-checklist-item check-list-item-".concat(theme.item_id, "\">").concat(list_action).concat(label, "</li>");
              form_response_page.find('.atbdp-themes-list').append(li);
              counter++;
            }
          } catch (err) {
            _iterator.e(err);
          } finally {
            _iterator.f();
          }
        } // Show Log - Extensions


        if (total_plugin) {
          var plugin_section = '<div class="atbdp-checklist-section atbdp-extensions-list-section"></div>';
          form_response_page.find('.atbdp-form-feedback').append(plugin_section);
          var plugin_title = "<h4 class=\"atbdp-extension-title\">Extensions <span class=\"atbdp-count\">(".concat(plugins.length, ")</span></h4>");
          var plugin_check_lists = '<ul class="atbdp-check-lists atbdp-extensions-list"></ul>';
          form_response_page.find('.atbdp-extensions-list-section').append(plugin_title);
          form_response_page.find('.atbdp-extensions-list-section').append(plugin_check_lists);
          var counter = 0;

          var _iterator2 = _createForOfIteratorHelper(plugins),
              _step2;

          try {
            for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
              var extension = _step2.value;
              // console.log( extension );
              var checkbox = "<input type=\"checkbox\" class=\"atbdp-checkbox atbdp-plugin-checkbox-item-".concat(extension.item_id, "\" value=\"").concat(extension.item_id, "\" id=\"").concat(extension.item_id, "\">");
              var list_action = "<span class=\"atbdp-list-action\">".concat(checkbox, "</span> ");
              var label = "<label for=\"".concat(extension.item_id, "\">").concat(extension.title, "</label>");
              var li = "<li class=\"atbdp-check-list-item atbdp-plugin-checklist-item check-list-item-".concat(extension.item_id, "\">").concat(list_action).concat(label, "</li>");
              form_response_page.find('.atbdp-extensions-list').append(li);
              counter++;
            }
          } catch (err) {
            _iterator2.e(err);
          } finally {
            _iterator2.f();
          }
        }

        var continue_button = '<div class="account-connect__form-btn"><button type="button" class="account-connect__btn atbdp-download-products-btn">Continue <span class="la la-arrow-right"></span></button></div>';
        var skip_button = '<a href="#" class="atbdp-link atbdp-link-secondery reload">Skip</a>';
        form_response_page.append(continue_button);
        form_response_page.append(skip_button);
        $('.atbdp-download-products-btn').on('click', function (e) {
          $(this).prop('disabled', true);
          var skiped_themes = 0;
          $('.atbdp-theme-checklist-item .atbdp-list-action .atbdp-checkbox').each(function (i, e) {
            var is_checked = $(e).is(':checked');

            if (!is_checked) {
              var id = $(e).attr('id');
              var list_item = $(".check-list-item-".concat(id));
              list_item.remove();
              skiped_themes++;
            }
          });
          var skiped_plugins = 0;
          $('.atbdp-plugin-checklist-item .atbdp-list-action .atbdp-checkbox').each(function (i, e) {
            var is_checked = $(e).is(':checked');

            if (!is_checked) {
              var id = $(e).attr('id');
              var list_item = $(".check-list-item-".concat(id));
              list_item.remove();
              skiped_plugins++;
            }
          });
          var new_theme_count = total_theme - skiped_themes;
          var new_plugin_count = total_plugin - skiped_plugins;
          $('.atbdp-theme-title').find('.atbdp-count').html("(".concat(new_theme_count, ")"));
          $('.atbdp-extension-title').find('.atbdp-count').html("(".concat(new_plugin_count, ")"));
          $('.atbdp-check-list-item .atbdp-list-action .atbdp-checkbox').css('display', 'none');
          $('.atbdp-check-list-item .atbdp-list-action').prepend('<span class="atbdp-icon atbdp-text-danger"><span class="fas fa-times"></span></span> ');
          var files_download_states = {
            succeeded_plugin_downloads: [],
            failed_plugin_downloads: [],
            succeeded_theme_downloads: [],
            failed_theme_downloads: []
          }; // Download Files

          var download_files = function download_files(file_list, counter, callback) {
            if (counter > file_list.length - 1) {
              if (callback) {
                callback();
              }

              return;
            }

            var next_index = counter + 1;
            var file_item = file_list[counter];
            var file = file_item.file;
            var file_type = file_item.type;
            var list_item = $(".check-list-item-".concat(file.item_id));
            var icon_elm = list_item.find('.atbdp-list-action .atbdp-icon');
            var list_checkbox = $(".atbdp-".concat(file_type, "-checkbox-item-").concat(file.item_id));
            var is_checked = list_checkbox.is(':checked');

            if (!is_checked) {
              download_files(file_list, next_index, callback);
              return;
            }

            var form_data = {
              action: 'atbdp_download_file',
              download_item: file,
              type: file_type
            };
            jQuery.ajax({
              type: 'post',
              url: atbdp_admin_data.ajaxurl,
              data: form_data,
              beforeSend: function beforeSend() {
                icon_elm.removeClass('atbdp-text-danger');
                icon_elm.html('<span class="fas fa-circle-notch fa-spin"></span>');
              },
              success: function success(response) {
                console.log('success', counter, response);

                if (response.status.success) {
                  icon_elm.addClass('atbdp-text-success');
                  icon_elm.html('<span class="fas fa-check"></span>');

                  if (file_type == 'plugin') {
                    files_download_states.succeeded_plugin_downloads.push(file);
                  }

                  if (file_type == 'theme') {
                    files_download_states.succeeded_theme_downloads.push(file);
                  }
                } else {
                  var msg = "<span class=\"atbdp-list-feedback atbdp-text-danger\">".concat(response.status.message, "</span>");
                  list_item.append(msg);
                  icon_elm.addClass('atbdp-text-danger');
                  icon_elm.html('<span class="fas fa-times"></span>');

                  if (file_type == 'plugin') {
                    files_download_states.failed_plugin_downloads.push(file);
                  }

                  if (file_type == 'theme') {
                    files_download_states.failed_theme_downloads.push(file);
                  }
                }

                download_files(file_list, next_index, callback);
              },
              error: function error(_error) {
                console.log(_error);
                icon_elm.addClass('atbdp-text-danger');
                icon_elm.html('<span class="fas fa-times"></span>');
              }
            });
          }; // Remove Unnecessary Sections


          if (!new_theme_count) {
            $('.atbdp-themes-list-section').remove();
          }

          if (!new_plugin_count) {
            $('.atbdp-extensions-list-section').remove();
          }

          if (new_theme_count || new_plugin_count) {
            var form_header_title = 'Activating your products';
            form_response_page.find('.atbdp-form-feedback .form-header-title').html(form_header_title);
          }

          var downloading_files = []; // Download Themes

          if (new_theme_count) {
            var _iterator3 = _createForOfIteratorHelper(themes),
                _step3;

            try {
              for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
                var _theme = _step3.value;
                downloading_files.push({
                  file: _theme,
                  type: 'theme'
                });
              }
            } catch (err) {
              _iterator3.e(err);
            } finally {
              _iterator3.f();
            }
          } // Download Plugins


          if (new_plugin_count) {
            var _iterator4 = _createForOfIteratorHelper(plugins),
                _step4;

            try {
              for (_iterator4.s(); !(_step4 = _iterator4.n()).done;) {
                var plugin = _step4.value;
                downloading_files.push({
                  file: plugin,
                  type: 'plugin'
                });
              }
            } catch (err) {
              _iterator4.e(err);
            } finally {
              _iterator4.f();
            }
          }

          var self = this;

          var after_download_callback = function after_download_callback() {
            // Check invalid themes
            var all_thmes_are_invalid = false;
            var failed_download_themes_count = files_download_states.failed_theme_downloads.length;

            if (new_theme_count && failed_download_themes_count === new_theme_count) {
              all_thmes_are_invalid = true;
            } // Check invalid plugin


            var all_plugins_are_invalid = false;
            var failed_download_plugins_count = files_download_states.failed_plugin_downloads.length;

            if (new_plugin_count && failed_download_plugins_count === new_plugin_count) {
              all_plugins_are_invalid = true;
            }

            var all_products_are_invalid = false;

            if (all_thmes_are_invalid && all_plugins_are_invalid) {
              all_products_are_invalid = true;
            }

            $(form_response_page).find('.account-connect__form-btn .account-connect__btn').remove();
            var finish_btn_label = all_products_are_invalid ? 'Close' : 'Finish';
            var finish_btn = "<button type=\"button\" class=\"account-connect__btn reload\">".concat(finish_btn_label, "</button>");
            $(form_response_page).find('.account-connect__form-btn').append(finish_btn);
          };

          if (downloading_files.length) {
            download_files(downloading_files, 0, after_download_callback);
          }
        });
      }
    },
    error: function error(_error2) {
      console.log(_error2);
      is_sending = false;
      submit_button.attr('disabled', false);
      submit_button.find('.atbdp-loading').remove();
    }
  });
}); // Reload Button

$('body').on('click', '.reload', function (e) {
  e.preventDefault();
  console.log('reloading...');
  location.reload();
}); // Extension Update Button

$('.ext-update-btn').on('click', function (e) {
  e.preventDefault();
  $(this).prop('disabled', true);
  var plugin_key = $(this).data('key');
  var button_default_html = $(this).html();
  var form_data = {
    action: 'atbdp_update_plugins'
  };

  if (plugin_key) {
    form_data.plugin_key = plugin_key;
  }

  console.log({
    plugin_key: plugin_key
  });
  var self = this;
  jQuery.ajax({
    type: 'post',
    url: atbdp_admin_data.ajaxurl,
    data: form_data,
    beforeSend: function beforeSend() {
      var icon = '<i class="fas fa-circle-notch fa-spin"></i> Updating';
      $(self).html(icon);
    },
    success: function success(response) {
      console.log({
        response: response
      });

      if (response.status.success) {
        $(self).html('Updated');
        location.reload();
      } else {
        $(self).html(button_default_html);
        alert(response.status.massage);
      }
    },
    error: function error(_error3) {
      console.log(_error3);
      $(self).html(button_default_html);
      $(this).prop('disabled', false);
    }
  });
}); // Install Button

$('.file-install-btn').on('click', function (e) {
  e.preventDefault();

  if ($(this).hasClass('in-progress')) {
    console.log('Wait...');
    return;
  }

  var data_key = $(this).data('key');
  var data_type = $(this).data('type');
  var form_data = {
    action: 'atbdp_install_file_from_subscriptions',
    item_key: data_key,
    type: data_type
  };
  var btn_default_html = $(this).html();
  ext_is_installing = true;
  var self = this;
  $(this).prop('disabled', true);
  $(this).addClass('in-progress');
  jQuery.ajax({
    type: 'post',
    url: atbdp_admin_data.ajaxurl,
    data: form_data,
    beforeSend: function beforeSend() {
      $(self).html('Installing');
      var icon = '<i class="fas fa-circle-notch fa-spin"></i> ';
      $(self).prepend(icon);
    },
    success: function success(response) {
      console.log(response);

      if (response.status && !response.status.success && response.status.message) {
        alert(response.status.message);
      }

      if (response.status && response.status.success) {
        $(self).html('Installed');
        location.reload();
      } else {
        $(self).html('Failed');
      }
    },
    error: function error(_error4) {
      console.log(_error4);
      $(this).prop('disabled', false);
      $(this).removeClass('in-progress');
      $(self).html(btn_default_html);
    }
  });
}); // Plugin Active Button

$('.plugin-active-btn').on('click', function (e) {
  e.preventDefault();

  if ($(this).hasClass('in-progress')) {
    console.log('Wait...');
    return;
  }

  var data_key = $(this).data('key');
  var form_data = {
    action: 'atbdp_activate_plugin',
    item_key: data_key
  };
  var btn_default_html = $(this).html();
  var self = this;
  $(this).prop('disabled', true);
  $(this).addClass('in-progress');
  jQuery.ajax({
    type: 'post',
    url: atbdp_admin_data.ajaxurl,
    data: form_data,
    beforeSend: function beforeSend() {
      $(self).html('Activating');
      var icon = '<i class="fas fa-circle-notch fa-spin"></i> ';
      $(self).prepend(icon);
    },
    success: function success(response) {
      console.log(response); // return;

      if (response.status && !response.status.success && response.status.message) {
        alert(response.status.message);
      }

      if (response.status && response.status.success) {
        $(self).html('Activated');
      } else {
        $(self).html('Failed');
      }

      location.reload();
    },
    error: function error(_error5) {
      console.log(_error5);
      $(this).prop('disabled', false);
      $(this).removeClass('in-progress');
      $(self).html(btn_default_html);
    }
  });
}); // Purchase refresh btn

$('.purchase-refresh-btn').on('click', function (e) {
  e.preventDefault();
  var purchase_refresh_btn_wrapper = $(this).parent();
  var auth_section = $('.et-auth-section');
  $(purchase_refresh_btn_wrapper).animate({
    width: 0
  }, 500);
  $(auth_section).animate({
    width: 330
  }, 500);
}); // et-close-auth-btn

$('.et-close-auth-btn').on('click', function (e) {
  e.preventDefault();
  var auth_section = $('.et-auth-section');
  var purchase_refresh_btn_wrapper = $('.purchase-refresh-btn-wrapper');
  $(purchase_refresh_btn_wrapper).animate({
    width: 182
  }, 500);
  $(auth_section).animate({
    width: 0
  }, 500);
}); // purchase-refresh-form

$('#purchase-refresh-form').on('submit', function (e) {
  e.preventDefault(); // console.log( 'purchase-refresh-form' );

  var submit_btn = $(this).find('button[type="submit"]');
  var btn_default_html = submit_btn.html();
  var close_btn = $(this).find('.et-close-auth-btn');
  var form_feedback = $(this).find('.atbdp-form-feedback');
  $(submit_btn).prop('disabled', true);
  $(close_btn).addClass('atbdp-d-none');
  var password = $(this).find('input[name="password"]').val();
  var form_data = {
    action: 'atbdp_refresh_purchase_status',
    password: password
  };
  form_feedback.html('');
  jQuery.ajax({
    type: 'post',
    url: atbdp_admin_data.ajaxurl,
    data: form_data,
    beforeSend: function beforeSend() {
      $(submit_btn).html('<i class="fas fa-circle-notch fa-spin"></i>');
    },
    success: function success(response) {
      console.log(response);

      if (response.status.message) {
        var feedback_type = response.status.success ? 'success' : 'danger';
        var message = "<span class=\"atbdp-text-".concat(feedback_type, "\">").concat(response.status.message, "</span>");
        form_feedback.html(message);
      }

      if (!response.status.success) {
        $(submit_btn).html(btn_default_html);
        $(submit_btn).prop('disabled', false);
        $(close_btn).removeClass('atbdp-d-none');

        if (response.status.reload) {
          location.reload();
        }
      } else {
        location.reload();
      }
    },
    error: function error(_error6) {
      console.log(_error6);
      $(submit_btn).prop('disabled', false);
      $(submit_btn).html(btn_default_html);
      $(close_btn).removeClass('atbdp-d-none');
    }
  });
}); // Logout

$('.subscriptions-logout-btn').on('click', function (e) {
  e.preventDefault();
  var hard_logout = $(this).data('hard-logout');
  var form_data = {
    action: 'atbdp_close_subscriptions_sassion',
    hard_logout: hard_logout
  };
  var self = this;
  jQuery.ajax({
    type: 'post',
    url: atbdp_admin_data.ajaxurl,
    data: form_data,
    beforeSend: function beforeSend() {
      $(self).html('<i class="fas fa-circle-notch fa-spin"></i> Logging out');
    },
    success: function success(response) {
      console.log(response);
      location.reload();
    },
    error: function error(_error7) {
      console.log(_error7);
      $(this).prop('disabled', false);
      $(this).removeClass('in-progress');
      $(self).html(btn_default_html);
    }
  }); // atbdp_close_subscriptions_sassion
}); // Form Actions
// Bulk Actions - My extensions form

var is_bulk_processing = false;
$('#atbdp-my-extensions-form').on('submit', function (e) {
  e.preventDefault();

  if (is_bulk_processing) {
    return;
  }

  var task = $(this).find('select[name="bulk-actions"]').val();
  var plugins_items = [];
  $(this).find('.extension-name-checkbox').each(function (i, e) {
    var is_checked = $(e).is(':checked');
    var id = $(e).attr('id');

    if (is_checked) {
      plugins_items.push(id);
    }
  });

  if (!task.length || !plugins_items.length) {
    return;
  }

  var self = this;
  is_bulk_processing = true;
  form_data = {
    action: 'atbdp_plugins_bulk_action',
    task: task,
    plugin_items: plugins_items
  };
  jQuery.ajax({
    type: 'post',
    url: atbdp_admin_data.ajaxurl,
    data: form_data,
    beforeSend: function beforeSend() {
      $(self).find('button[type="submit"]').prepend('<span class="atbdp-icon"><span class="fas fa-circle-notch fa-spin"></span></span> ');
    },
    success: function success(response) {
      console.log(response);
      $(self).find('button[type="submit"] .atbdp-icon').remove();
      location.reload();
    },
    error: function error(_error8) {
      console.log(_error8);
      uninstalling = false;
    }
  }); // console.log( task, plugins_items );
}); // Bulk Actions - My extensions form

var is_bulk_processing = false;
$('#atbdp-my-subscribed-extensions-form').on('submit', function (e) {
  e.preventDefault();

  if (is_bulk_processing) {
    return;
  }

  var self = this;
  var task = $(this).find('select[name="bulk-actions"]').val();
  var plugins_items = [];
  var tergeted_items_elm = '.extension-name-checkbox';
  $(self).find(tergeted_items_elm).each(function (i, e) {
    var is_checked = $(e).is(':checked');
    var key = $(e).attr('name');

    if (is_checked) {
      plugins_items.push(key);
    }
  });

  if (!task.length || !plugins_items.length) {
    return;
  } // Before Install


  $(this).find('.file-install-btn').prop('disabled', true).addClass('in-progress');
  var loading_icon = '<span class="atbdp-icon"><span class="fas fa-circle-notch fa-spin"></span></span> ';
  $(this).find('button[type="submit"]').prop('disabled', true).prepend(loading_icon);
  is_bulk_processing = true;

  var after_bulk_process = function after_bulk_process() {
    is_bulk_processing = false;
    $(self).find('button[type="submit"]').find('.atbdp-icon').remove();
    $(self).find('button[type="submit"]').prop('disabled', false);
    location.reload();
  };

  plugins_bulk_actions('install', plugins_items, after_bulk_process);
}); // Bulk Actions - Required extensions form

var is_bulk_processing = false;
$('#atbdp-required-extensions-form').on('submit', function (e) {
  e.preventDefault();

  if (is_bulk_processing) {
    return;
  }

  var self = this;
  var task = $(this).find('select[name="bulk-actions"]').val();
  var plugins_items = [];
  var tergeted_items_elm = 'install' === task ? '.extension-install-checkbox' : '.extension-activate-checkbox';
  $(self).find(tergeted_items_elm).each(function (i, e) {
    var is_checked = $(e).is(':checked');
    var key = $(e).attr('value');

    if (is_checked) {
      plugins_items.push(key);
    }
  });

  if (!task.length || !plugins_items.length) {
    return;
  } // Before Install


  $(this).find('.file-install-btn').prop('disabled', true).addClass('in-progress');
  $(this).find('.plugin-active-btn').prop('disabled', true).addClass('in-progress');
  var loading_icon = '<span class="atbdp-icon"><span class="fas fa-circle-notch fa-spin"></span></span> ';
  $(this).find('button[type="submit"]').prop('disabled', true).prepend(loading_icon);
  is_bulk_processing = true;

  var after_bulk_process = function after_bulk_process() {
    is_bulk_processing = false;
    $(self).find('button[type="submit"]').find('.atbdp-icon').remove();
    $(self).find('button[type="submit"]').prop('disabled', false);
    location.reload();
  };

  var available_task_list = ['install', 'activate'];

  if (available_task_list.includes(task)) {
    plugins_bulk_actions(task, plugins_items, after_bulk_process);
  }
}); // plugins_bulk__actions

function plugins_bulk_actions(task, plugins_items, after_plugins_install) {
  var action = {
    install: 'atbdp_install_file_from_subscriptions',
    activate: 'atbdp_activate_plugin'
  };
  var btnLabelOnProgress = {
    install: 'Installing',
    activate: 'Activating'
  };
  var btnLabelOnSuccess = {
    install: 'Installed',
    activate: 'Activated'
  };
  var processStartBtn = {
    install: '.file-install-btn',
    activate: '.plugin-active-btn'
  };

  var bulk_task = function bulk_task(plugins, counter, callback) {
    if (counter > plugins.length - 1) {
      if (callback) {
        callback();
      }

      return;
    }

    var current_item = plugins[counter];
    var action_wrapper_key = 'install' === task ? plugins[counter] : plugins[counter].replace(/\/.+$/g, '');
    var action_wrapper = $(".ext-action-".concat(action_wrapper_key));
    var action_btn = action_wrapper.find(processStartBtn[task]);
    var next_index = counter + 1;
    var form_action = action[task] ? action[task] : '';
    form_data = {
      action: form_action,
      item_key: current_item,
      type: 'plugin'
    };
    jQuery.ajax({
      type: 'post',
      url: atbdp_admin_data.ajaxurl,
      data: form_data,
      beforeSend: function beforeSend() {
        action_btn.html("<span class=\"atbdp-icon\">\n                        <span class=\"fas fa-circle-notch fa-spin\"></span>\n                    </span> ".concat(btnLabelOnProgress[task]));
      },
      success: function success(response) {
        console.log({
          response: response
        });

        if (response.status.success) {
          action_btn.html(btnLabelOnSuccess[task]);
        } else {
          action_btn.html('Failed');
        }

        bulk_task(plugins, next_index, callback);
      },
      error: function error(_error9) {
        console.log(_error9);
      }
    });
  };

  bulk_task(plugins_items, 0, after_plugins_install);
} // Ext Actions | Uninstall


var uninstalling = false;
$('.ext-action-uninstall').on('click', function (e) {
  e.preventDefault();

  if (uninstalling) {
    return;
  }

  var data_target = $(this).data('target');
  var form_data = {
    action: 'atbdp_plugins_bulk_action',
    task: 'uninstall',
    plugin_items: [data_target]
  };
  var self = this;
  uninstalling = true;
  jQuery.ajax({
    type: 'post',
    url: atbdp_admin_data.ajaxurl,
    data: form_data,
    beforeSend: function beforeSend() {
      $(self).prepend('<span class="atbdp-icon"><span class="fas fa-circle-notch fa-spin"></span></span> ');
    },
    success: function success(response) {
      console.log(response);
      $(self).closest('.ext-action').find('.ext-action-drop').removeClass('active');
      location.reload();
    },
    error: function error(_error10) {
      console.log(_error10);
      uninstalling = false;
    }
  });
}); // Bulk checkbox toggle

$('#select-all-installed').on('change', function (e) {
  var is_checked = $(this).is(':checked');

  if (is_checked) {
    $('#atbdp-my-extensions-form').find('.extension-name-checkbox').prop('checked', true);
  } else {
    $('#atbdp-my-extensions-form').find('.extension-name-checkbox').prop('checked', false);
  }
});
$('#select-all-subscription').on('change', function (e) {
  var is_checked = $(this).is(':checked');

  if (is_checked) {
    $('#atbdp-my-subscribed-extensions-form').find('.extension-name-checkbox').prop('checked', true);
  } else {
    $('#atbdp-my-subscribed-extensions-form').find('.extension-name-checkbox').prop('checked', false);
  }
});
$('#select-all-required-extensions').on('change', function (e) {
  var is_checked = $(this).is(':checked');

  if (is_checked) {
    $('#atbdp-required-extensions-form').find('.extension-name-checkbox').prop('checked', true);
  } else {
    $('#atbdp-required-extensions-form').find('.extension-name-checkbox').prop('checked', false);
  }
}); //

$('.ext-action-drop').each(function (i, e) {
  $(e).on('click', function (elm) {
    elm.preventDefault();

    if ($(this).hasClass('active')) {
      $(this).removeClass('active');
    } else {
      $('.ext-action-drop').removeClass('active');
      $(this).addClass('active');
    }
  });
}); // Theme Activation

var theme_is_activating = false;
$('.theme-activate-btn').on('click', function (e) {
  e.preventDefault();

  if (theme_is_activating) {
    return;
  }

  var data_target = $(this).data('target');

  if (!data_target) {
    return;
  }

  if (!data_target.length) {
    return;
  }

  var form_data = {
    action: 'atbdp_activate_theme',
    theme_stylesheet: data_target
  };
  var self = this;
  theme_is_activating = true;
  $.ajax({
    type: 'post',
    url: atbdp_admin_data.ajaxurl,
    data: form_data,
    beforeSend: function beforeSend() {
      $(self).prepend('<span class="atbdp-icon"><span class="fas fa-circle-notch fa-spin"></span></span> ');
    },
    success: function success(response) {
      console.log({
        response: response
      });
      $(self).find('.atbdp-icon').remove();

      if (response.status && response.status.success) {
        location.reload();
      }
    },
    error: function error(_error11) {
      console.log({
        error: _error11
      });
      theme_is_activating = false;
      $(self).find('.atbdp-icon').remove();
    }
  });
}); // Theme Update

$('.theme-update-btn').on('click', function (e) {
  e.preventDefault();
  $(this).prop('disabled', true);

  if ($(this).hasClass('in-progress')) {
    return;
  }

  var theme_stylesheet = $(this).data('target');
  var button_default_html = $(this).html();
  var form_data = {
    action: 'atbdp_update_theme'
  };

  if (theme_stylesheet) {
    form_data.theme_stylesheet = theme_stylesheet;
  }

  var self = this;
  $(this).addClass('in-progress');
  $.ajax({
    type: 'post',
    url: atbdp_admin_data.ajaxurl,
    data: form_data,
    beforeSend: function beforeSend() {
      $(self).html('<span class="atbdp-icon"><span class="fas fa-circle-notch fa-spin"></span></span> Updating');
    },
    success: function success(response) {
      console.log({
        response: response
      });

      if (response.status && response.status.success) {
        $(self).html('Updated');
        location.reload();
      } else {
        $(self).removeClass('in-progress');
        $(self).html(button_default_html);
        $(self).prop('disabled', false);
        alert(response.status.message);
      }
    },
    error: function error(_error12) {
      console.log({
        error: _error12
      });
      $(self).removeClass('in-progress');
      $(self).html(button_default_html);
      $(self).prop('disabled', false);
    }
  });
});

/***/ }),

/***/ "./assets/src/js/components/modal.js":
/*!*******************************************!*\
  !*** ./assets/src/js/components/modal.js ***!
  \*******************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _scss_component_modal_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../scss/component/_modal.scss */ "./assets/src/scss/component/_modal.scss");
/* harmony import */ var _scss_component_modal_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_scss_component_modal_scss__WEBPACK_IMPORTED_MODULE_0__);

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
    $('.' + data_target).toggleClass('directorist-show');
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

/***/ "./assets/src/js/modules/pureScriptSearchSelect.js":
/*!*********************************************************!*\
  !*** ./assets/src/js/modules/pureScriptSearchSelect.js ***!
  \*********************************************************/
/*! exports provided: pureScriptSelect */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "pureScriptSelect", function() { return pureScriptSelect; });
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _scss_component_pureSearchSelect_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../scss/component/pureSearchSelect.scss */ "./assets/src/scss/component/pureSearchSelect.scss");
/* harmony import */ var _scss_component_pureSearchSelect_scss__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_scss_component_pureSearchSelect_scss__WEBPACK_IMPORTED_MODULE_1__);


/*  Plugin: PureScriptSearchSelect
    Author: SovWare
    URI: https://github.com/woadudakand/pureScriptSelect
*/

function pureScriptSelect(selector) {
  var selectors = document.querySelectorAll(selector);

  function eventDelegation(event, psSelector, program) {
    document.body.addEventListener(event, function (e) {
      document.querySelectorAll(psSelector).forEach(function (elem) {
        if (e.target === elem) {
          program(e);
        }
      });
    });
  }

  var optionValues = _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default()({}, document.querySelector(selector).getAttribute('id'), eval(document.querySelector(selector).getAttribute('data-multiSelect')));

  var isMax = _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default()({}, document.querySelector(selector).getAttribute('id'), eval(document.querySelector(selector).getAttribute('data-max')));

  selectors.forEach(function (item, index) {
    var multiSelect = item.getAttribute('data-multiSelect');
    var isSearch = item.getAttribute('data-isSearch');

    function singleSelect() {
      var defaultValues = _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default()({}, document.querySelector(selector).getAttribute('id'), document.querySelector(selector).getAttribute('data-default'));

      var arraySelector = item.getAttribute('id');
      var virtualSelect = document.createElement('div');
      virtualSelect.classList.add('directorist-select__container');
      item.append(virtualSelect);
      item.style.position = 'relative';
      item.style.zIndex = '2';
      var select = item.querySelectorAll('select'),
          sibling = item.querySelector('.directorist-select__container'),
          option = '';
      select.forEach(function (sel) {
        option = sel.querySelectorAll('option');
      });
      var html = "\n            <div class=\"directorist-select__label\">\n                <div class=\"directorist-select__label--text\">".concat(option[0].text, "</div>\n                <span class=\"directorist-select__label--icon\"><i class=\"la la-angle-down\"></i></span>\n            </div>\n            <div class=\"directorist-select__dropdown\">\n                <input class='directorist-select__search ").concat(isSearch ? 'directorist-select__search--show' : 'directorist-select__search--hide', "' type='text' class='value' placeholder='Filter Options....' />\n                <div class=\"directorist-select__dropdown--inner\"></div>\n            </div>");
      sibling.innerHTML = html;
      var arry = [],
          arryEl = [],
          selectTrigger = sibling.querySelector('.directorist-select__label');
      option.forEach(function (el, index) {
        arry.push(el.innerHTML);
        arryEl.push(el);
        el.style.display = 'none';

        if (el.value === defaultValues[arraySelector]) {
          el.setAttribute('selected', 'selected');
        }

        if (el.hasAttribute('selected')) {
          selectTrigger.innerHTML = el.innerHTML + '<i class="la la-angle-down"></i>';
        }

        ;
      });
      var input = item.querySelector('.directorist-select__dropdown input');
      document.body.addEventListener('click', function (event) {
        if (event.target == selectTrigger || event.target == input) {
          return;
        } else {
          sibling.querySelector('.directorist-select__dropdown').classList.remove('directorist-select__dropdown-open');
          sibling.querySelector('.directorist-select__label').closest('.directorist-select').classList.remove('directorist-select-active-js');
        }

        input.value = '';
      });
      selectTrigger.addEventListener('click', function (e) {
        e.preventDefault();
        e.target.closest('.directorist-select').classList.add('directorist-select-active-js');
        sibling.querySelector('.directorist-select__dropdown').classList.toggle('directorist-select__dropdown-open');
        var elem = [];
        arryEl.forEach(function (el, index) {
          if (index !== 0 || el.value !== '') {
            elem.push(el);
            el.style.display = 'block';
          }
        });
        var item2 = '<ul>';
        elem.forEach(function (el, key) {
          el.removeAttribute('selected');
          var attribute = '';
          var attribute2 = '';

          if (el.hasAttribute('img')) {
            attribute = el.getAttribute('img');
          }

          if (el.hasAttribute('icon')) {
            attribute2 = el.getAttribute('icon');
          }

          item2 += "<li><span class=\"directorist-select-dropdown-text\">".concat(el.text, "</span> <span class=\"directorist-select-dropdown-item-icon\"><img src=\"").concat(attribute, "\" style=\"").concat(attribute == null && {
            display: 'none'
          }, " \" /><b class=\"").concat(attribute2, "\"></b></b></span></li>");
        });
        item2 += '</ul>';
        var popUp = item.querySelector('.directorist-select__dropdown--inner');
        popUp.innerHTML = item2;
        var li = item.querySelectorAll('li');
        li.forEach(function (el, index) {
          el.addEventListener('click', function (event) {
            elem[index].setAttribute('selected', 'selected');
            sibling.querySelector('.directorist-select__dropdown').classList.remove('directorist-select__dropdown-open');
            item.querySelector('.directorist-select__label').innerHTML = el.querySelector('.directorist-select-dropdown-text').textContent + '<i class="la la-angle-down"></i>';
          });
        });
      });
      var value = item.querySelector('input');
      value && value.addEventListener('keyup', function (event) {
        var itemValue = event.target.value.toLowerCase();
        var filter = arry.filter(function (el, index) {
          return el.toLowerCase().startsWith(itemValue);
        });
        var elem = [];
        arryEl.forEach(function (el, index) {
          filter.forEach(function (e) {
            if (el.text.toLowerCase() == e.toLowerCase()) {
              elem.push(el);
              el.style.display = 'block';
            }
          });
        });
        var item2 = '<ul>';
        elem.forEach(function (el, key) {
          var attribute = '';
          var attribute2 = '';

          if (el.hasAttribute('img')) {
            attribute = el.getAttribute('img');
          }

          if (el.hasAttribute('icon')) {
            attribute2 = el.getAttribute('icon');
          }

          item2 += "<li><span class=\"directorist-select-dropdown-text\">".concat(el.text, "</span><span class=\"directorist-select-dropdown-item-icon\"><img src=\"").concat(attribute, "\" style=\"").concat(attribute == null && {
            display: 'none'
          }, " \" /><b class=\"").concat(attribute2, "\"></b></b></span></li>");
        });
        item2 += '</ul>';
        var popUp = item.querySelector('.directorist-select__dropdown--inner');
        popUp.innerHTML = item2;
        var li = item.querySelectorAll('li');
        li.forEach(function (el, index) {
          el.addEventListener('click', function (event) {
            elem[index].setAttribute('selected', 'selected');
            sibling.querySelector('.directorist-select__dropdown').classList.remove('directorist-select__dropdown-open');
            item.querySelector('.directorist-select__label').innerHTML = el.querySelector('.directorist-select-dropdown-text').textContent + '<i class="la la-angle-down"></i>';
          });
        });
      });
    }

    function multiSelects() {
      var defaultValues = _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default()({}, document.querySelector(selector).getAttribute('id'), document.querySelector(selector).getAttribute('data-default') ? eval(document.querySelector(selector).getAttribute('data-default')) : []);

      var arraySelector = item.getAttribute('id');
      var hiddenInput = item.querySelector('input[type="hidden"]');
      var virtualSelect = document.createElement('div');
      virtualSelect.classList.add('directorist-select__container');
      item.append(virtualSelect);
      item.style.position = 'relative';
      item.style.zIndex = '0';
      var sibling = item.querySelector('.directorist-select__container'),
          option = optionValues[arraySelector];
      var html = "\n            <div class=\"directorist-select__label\">\n                <div class=\"directorist-select__selected-list\"></div>\n                <input type=\"text\" class='directorist-select__search ".concat(isSearch ? 'directorist-select__search--show' : 'directorist-select__search--hide', "' type='text' class='value' placeholder='Filter Options....' />\n            </div>\n            <div class=\"directorist-select__dropdown\">\n                <div class=\"directorist-select__dropdown--inner\"></div>\n            </div>\n            <span class=\"directorist-error__msg\"></span>");

      function insertSearchItem() {
        item.querySelector('.directorist-select__selected-list').innerHTML = defaultValues[arraySelector].map(function (item) {
          return "<span class=\"directorist-select__selected-list--item\">".concat(item, "&nbsp;&nbsp;<a href=\"#\" data-key=\"").concat(item, "\" class=\"directorist-item-remove\"><i class=\"fa fa-times\"></i></a></span>");
        }).join("");
      }

      sibling.innerHTML = html;
      var button = sibling.querySelector('.directorist-select__label');
      insertSearchItem();
      document.body.addEventListener('click', function (event) {
        if (event.target == button || event.target.closest('.directorist-select__container')) {
          return;
        } else {
          sibling.querySelector('.directorist-select__dropdown').classList.remove('directorist-select__dropdown-open');
        }
      });
      button.addEventListener('click', function (e) {
        e.preventDefault();
        var value = item.querySelector('input[type="text"]');
        value.focus();
        document.querySelectorAll('.directorist-select__dropdown').forEach(function (el) {
          return el.classList.remove('directorist-select__dropdown-open');
        });
        e.target.closest('.directorist-select__container').querySelector('.directorist-select__dropdown').classList.add('directorist-select__dropdown-open');
        var popUp = item.querySelector('.directorist-select__dropdown--inner');
        var item2 = '<ul>';
        option.forEach(function (el, key) {
          item2 += "<li data-key=\"".concat(el, "\" class=\"directorist-select-item-hide\">").concat(el, "</li>");
        });
        item2 += '</ul>';
        popUp.innerHTML = item2;
        var li = item.querySelectorAll('li');
        li.forEach(function (element, index) {
          element.classList.remove('directorist-select-item-show');
          element.classList.add('directorist-select-item-hide');

          if (defaultValues[arraySelector].includes(element.getAttribute('data-key'))) {
            element.classList.add('directorist-select-item-show');
            element.classList.remove('directorist-select-item-hide');
          }
        });
        value && value.addEventListener('keyup', function (event) {
          var itemValue = event.target.value.toLowerCase();
          var filter = option.filter(function (el, index) {
            return el.toString().toLowerCase().startsWith(itemValue);
          });

          if (event.keyCode === 13) {
            if (isMax[arraySelector]) {
              if (defaultValues[arraySelector].length < parseInt(isMax[arraySelector])) {
                if (!defaultValues[arraySelector].includes(event.target.value) && event.target.value !== '') {
                  defaultValues[arraySelector].push(event.target.value);
                  optionValues[arraySelector].push(event.target.value);
                  insertSearchItem();
                  hiddenInput.value = JSON.stringify(defaultValues[arraySelector]);
                  value.value = '';
                  document.querySelectorAll('.directorist-select__dropdown').forEach(function (el) {
                    return el.classList.remove('directorist-select__dropdown-open');
                  });
                }
              } else {
                item.querySelector('.directorist-select__dropdown').classList.remove('directorist-select__dropdown-open');

                if (e.target.closest('.directorist-select')) {
                  e.target.closest('.directorist-select').querySelector('.directorist-select__container').classList.add('directorist-error');
                  e.target.closest('.directorist-select').querySelector('.directorist-error__msg').innerHTML = "Max ".concat(isMax[arraySelector], " Items Added ");
                }
              }
            } else {
              if (!defaultValues[arraySelector].includes(event.target.value) && event.target.value !== '') {
                defaultValues[arraySelector].push(event.target.value);
                optionValues[arraySelector].push(event.target.value);
                insertSearchItem();
                hiddenInput.value = JSON.stringify(defaultValues[arraySelector]);
                value.value = '';
                document.querySelectorAll('.directorist-select__dropdown').forEach(function (el) {
                  return el.classList.remove('directorist-select__dropdown-open');
                });
              }
            }
          }

          var elem = [];
          optionValues[arraySelector].forEach(function (el, index) {
            filter.forEach(function (e) {
              if (el.toLowerCase() == e.toLowerCase()) {
                elem.push(el);
              }
            });
          });
          var item2 = '<ul>';
          elem.forEach(function (el) {
            item2 += "<li data-key=\"".concat(el, "\" class=\"directorist-select-item-hide\">").concat(el, "</li>");
          });
          item2 += '</ul>';
          var popUp = item.querySelector('.directorist-select__dropdown--inner');
          popUp.innerHTML = item2;
          var li = item.querySelectorAll('li');
          li.forEach(function (element, index) {
            element.classList.remove('directorist-select-item-show');
            element.classList.add('directorist-select-item-hide');

            if (defaultValues[arraySelector].includes(element.getAttribute('data-key'))) {
              element.classList.add('directorist-select-item-show');
              element.classList.remove('directorist-select-item-hide');
            }

            element.addEventListener('click', function (event) {
              sibling.querySelector('.directorist-select__dropdown--inner').classList.remove('directorist-select__dropdown.open');
            });
          });
        });
        eventDelegation('click', 'li', function (e) {
          var index = e.target.getAttribute('data-key');
          var closestId = e.target.closest('.directorist-select').getAttribute('id');
          document.querySelectorAll('.directorist-select__dropdown').forEach(function (el) {
            return el.classList.remove('directorist-select__dropdown-open');
          });

          if (isMax[closestId] === null && defaultValues[closestId]) {
            defaultValues[closestId].filter(function (item) {
              return item == index;
            }).length === 0 && defaultValues[closestId].push(index);
            hiddenInput.value = JSON.stringify(defaultValues[closestId]);
            e.target.classList.remove('directorist-select-item-hide');
            e.target.classList.add('directorist-select-item-show');
            insertSearchItem();
          } else {
            if (defaultValues[closestId]) if (defaultValues[closestId].length < parseInt(isMax[closestId])) {
              defaultValues[closestId].filter(function (item) {
                return item == index;
              }).length === 0 && defaultValues[closestId].push(index);
              hiddenInput.value = JSON.stringify(defaultValues[closestId]);
              e.target.classList.remove('directorist-select-item-hide');
              e.target.classList.add('directorist-select-item-show');
              insertSearchItem();
            } else {
              item.querySelector('.directorist-select__dropdown').classList.remove('directorist-select__dropdown-open');
              e.target.closest('.directorist-select').querySelector('.directorist-select__container').classList.add('directorist-error');
              e.target.closest('.directorist-select').querySelector('.directorist-error__msg').innerHTML = "Max ".concat(isMax[arraySelector], " Items Added ");
            }
          }
        });
      });
      eventDelegation('click', '.directorist-item-remove', function (e) {
        var li = item.querySelectorAll('li');
        var closestId = e.target.closest('.directorist-select').getAttribute('id');
        defaultValues[closestId] = defaultValues[closestId] && defaultValues[closestId].filter(function (item) {
          return item != e.target.getAttribute('data-key');
        });

        if ((defaultValues[closestId] && defaultValues[closestId].length) < (isMax[closestId] && parseInt(isMax[closestId]))) {
          e.target.closest('.directorist-select').querySelector('.directorist-select__container').classList.remove('directorist-error');
          e.target.closest('.directorist-select').querySelector('.directorist-error__msg').innerHTML = '';
        }

        li.forEach(function (element, index) {
          element.classList.remove('directorist-select-item-show');
          element.classList.add('directorist-select-item-hide');

          if (defaultValues[closestId].includes(element.getAttribute('data-key'))) {
            element.classList.add('directorist-select-item-show');
            element.classList.remove('directorist-select-item-hide');
          }
        });
        insertSearchItem();
        hiddenInput.value = JSON.stringify(defaultValues[closestId]);
      });
    }

    multiSelect ? multiSelects() : singleSelect();
  });
}

;

(function ($) {
  window.addEventListener('load', initPureScriptSelect);
  document.body.addEventListener('directorist-search-form-nav-tab-reloaded', initPureScriptSelect);

  function initPureScriptSelect() {
    if ($('#directorist-select-js').length) {
      pureScriptSelect('#directorist-select-js');
    }

    if ($('#directorist-review-select-js').length) {
      pureScriptSelect('#directorist-review-select-js');
    }

    if ($('#directorist-search-category-js').length) {
      pureScriptSelect('#directorist-search-category-js');
    }

    if ($('#directorist-search-location-js').length) {
      pureScriptSelect('#directorist-search-location-js');
    }

    if ($('#directorist-search-select-js').length) {
      pureScriptSelect('#directorist-search-select-js');
    }

    if ($('#directorist-select-st-s-js').length) {
      pureScriptSelect('#directorist-select-st-s-js');
    }

    if ($('#directorist-select-st-e-js').length) {
      pureScriptSelect('#directorist-select-st-e-js');
    }

    if ($('#directorist-select-sn-s-js').length) {
      pureScriptSelect('#directorist-select-sn-s-js');
    }

    if ($('#directorist-select-mn-e-js').length) {
      pureScriptSelect('#directorist-select-sn-e-js');
    }

    if ($('#directorist-select-mn-s-js').length) {
      pureScriptSelect('#directorist-select-mn-s-js');
    }

    if ($('#directorist-select-mn-e-js').length) {
      pureScriptSelect('#directorist-select-mn-e-js');
    }

    if ($('#directorist-select-tu-s-js').length) {
      pureScriptSelect('#directorist-select-tu-s-js');
    }

    if ($('#directorist-select-tu-e-js').length) {
      pureScriptSelect('#directorist-select-tu-e-js');
    }

    if ($('#directorist-select-wd-s-js').length) {
      pureScriptSelect('#directorist-select-wd-s-js');
    }

    if ($('#directorist-select-wd-e-js').length) {
      pureScriptSelect('#directorist-select-wd-e-js');
    }

    if ($('#directorist-select-th-s-js').length) {
      pureScriptSelect('#directorist-select-th-s-js');
    }

    if ($('#directorist-select-th-e-js').length) {
      pureScriptSelect('#directorist-select-th-e-js');
    }

    if ($('#directorist-select-fr-s-js').length) {
      pureScriptSelect('#directorist-select-fr-s-js');
    }

    if ($('#directorist-select-fr-e-js').length) {
      pureScriptSelect('#directorist-select-fr-e-js');
    }

    if ($('#directorist-location-select').length) {
      pureScriptSelect('#directorist-location-select');
    }

    if ($('#directorist-tag-select').length) {
      pureScriptSelect('#directorist-tag-select');
    }

    if ($('#directorist-category-select').length) {
      pureScriptSelect('#directorist-category-select');
    }
  }
})(jQuery);



/***/ }),

/***/ "./assets/src/js/modules/utility.js":
/*!******************************************!*\
  !*** ./assets/src/js/modules/utility.js ***!
  \******************************************/
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

/***/ "./assets/src/scss/component/_modal.scss":
/*!***********************************************!*\
  !*** ./assets/src/scss/component/_modal.scss ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "./assets/src/scss/component/pureSearchSelect.scss":
/*!*********************************************************!*\
  !*** ./assets/src/scss/component/pureSearchSelect.scss ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "./assets/src/scss/layout/admin/admin-style.scss":
/*!*******************************************************!*\
  !*** ./assets/src/scss/layout/admin/admin-style.scss ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

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

/***/ })

/******/ });
//# sourceMappingURL=admin-main.js.map