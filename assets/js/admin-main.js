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
/* harmony import */ var _scss_layout_admin_admin_style_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../../scss/layout/admin/admin-style.scss */ "./assets/src/scss/layout/admin/admin-style.scss");
/* harmony import */ var _scss_layout_admin_admin_style_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_scss_layout_admin_admin_style_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _global_global__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./../global/global */ "./assets/src/js/global/global.js");
/* harmony import */ var _components_block_1__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/block-1 */ "./assets/src/js/admin/components/block-1.js");
/* harmony import */ var _components_block_1__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_components_block_1__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _components_block_2__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/block-2 */ "./assets/src/js/admin/components/block-2.js");
/* harmony import */ var _components_block_2__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_components_block_2__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _components_block_3__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./components/block-3 */ "./assets/src/js/admin/components/block-3.js");
/* harmony import */ var _components_block_3__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_components_block_3__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _components_block_4__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./components/block-4 */ "./assets/src/js/admin/components/block-4.js");
/* harmony import */ var _components_block_4__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_components_block_4__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _components_block_5__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./components/block-5 */ "./assets/src/js/admin/components/block-5.js");
/* harmony import */ var _components_admin_user__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./components/admin-user */ "./assets/src/js/admin/components/admin-user.js");
/* harmony import */ var _components_admin_user__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_components_admin_user__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _components_subscriptionManagement__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./components/subscriptionManagement */ "./assets/src/js/admin/components/subscriptionManagement.js");
/* harmony import */ var _components_subscriptionManagement__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_components_subscriptionManagement__WEBPACK_IMPORTED_MODULE_8__);
 // Global

 // Blocks






 // subscriptionManagement



/***/ }),

/***/ "./assets/src/js/admin/components/admin-user.js":
/*!******************************************************!*\
  !*** ./assets/src/js/admin/components/admin-user.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// user type change on user dashboard
(function ($) {
  window.addEventListener('DOMContentLoaded', function () {
    $('#atbdp-user-type-approve').on('click', function (event) {
      event.preventDefault();
      var userId = $(this).attr('data-userId');
      var nonce = $(this).attr('data-nonce');
      $.ajax({
        type: 'post',
        url: directorist_admin.ajaxurl,
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
        url: directorist_admin.ajaxurl,
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
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/admin/components/block-1.js":
/*!***************************************************!*\
  !*** ./assets/src/js/admin/components/block-1.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

window.addEventListener('DOMContentLoaded', function () {
  var $ = jQuery;
  var content = ''; // Category icon selection

  function selecWithIcon(selected) {
    if (!selected.id) {
      return selected.text;
    }

    var $elem = $("<span><span class='".concat(directorist_admin.icon_type, " ").concat(selected.element.value, "'></span> ").concat(selected.text, "</span>"));
    return $elem;
  }

  if ($("[data-toggle='tooltip']").length) {
    $("[data-toggle='tooltip']").tooltip();
  } // price range


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
      title: directorist_admin.i18n_text.confirmation_text,
      text: directorist_admin.i18n_text.ask_conf_sl_lnk_del_txt,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: directorist_admin.i18n_text.confirm_delete,
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
          title: directorist_admin.i18n_text.deleted,
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
    window.location = directorist_admin.import_page_link;
  });
  /* This function handles all ajax request */

  function atbdp_do_ajax(ElementToShowLoadingIconAfter, ActionName, arg, CallBackHandler) {
    var data;
    if (ActionName) data = "action=".concat(ActionName);
    if (arg) data = "".concat(arg, "&action=").concat(ActionName);
    if (arg && !ActionName) data = arg; // data = data ;

    var n = data.search(directorist_admin.nonceName);

    if (n < 0) {
      data = "".concat(data, "&").concat(directorist_admin.nonceName, "=").concat(directorist_admin.nonce);
    }

    jQuery.ajax({
      type: 'post',
      url: directorist_admin.ajaxurl,
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
});

/***/ }),

/***/ "./assets/src/js/admin/components/block-2.js":
/*!***************************************************!*\
  !*** ./assets/src/js/admin/components/block-2.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

window.addEventListener('DOMContentLoaded', function () {
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
      directorist_nonce: directorist_admin.directorist_nonce,
      post_id: $('#post_ID').val(),
      term_id: id,
      directory_type: directory_type ? directory_type : from_single_directory
    };
    $.post(directorist_admin.ajaxurl, data, function (response) {
      if (response) {
        var response = "<div class=\"form-group atbd_content_module\">\n                                <div class=\"atbdb_content_module_contents\">\n                                  ".concat(response, "\n                                </div>\n                              </div>");
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
  $.post(directorist_admin.ajaxurl, data, function (response) {
    if (response) {
      var response = "<div class=\"form-group atbd_content_module\">\n                              <div class=\"atbdb_content_module_contents\">\n                                ".concat(response, "\n                              </div>\n                            </div>");
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
  placeholder: directorist_admin.i18n_text.icon_choose_text,
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

  if ($("[data-toggle='tooltip']").length) {
    $("[data-toggle='tooltip']").tooltip();
  } // price range


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
      title: directorist_admin.i18n_text.confirmation_text,
      text: directorist_admin.i18n_text.ask_conf_sl_lnk_del_txt,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: directorist_admin.i18n_text.confirm_delete,
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
          title: directorist_admin.i18n_text.deleted,
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
      url: directorist_admin.ajaxurl,
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
      url: directorist_admin.ajaxurl,
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
      url: directorist_admin.ajaxurl,
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
    window.location = directorist_admin.import_page_link;
  });
  /* This function handles all ajax request */

  function atbdp_do_ajax(ElementToShowLoadingIconAfter, ActionName, arg, CallBackHandler) {
    var data;
    if (ActionName) data = "action=".concat(ActionName);
    if (arg) data = "".concat(arg, "&action=").concat(ActionName);
    if (arg && !ActionName) data = arg; // data = data ;

    var n = data.search(directorist_admin.nonceName);

    if (n < 0) {
      data = "".concat(data, "&").concat(directorist_admin.nonceName, "=").concat(directorist_admin.nonce);
    }

    jQuery.ajax({
      type: 'post',
      url: directorist_admin.ajaxurl,
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
});

/***/ }),

/***/ "./assets/src/js/admin/components/block-3.js":
/*!***************************************************!*\
  !*** ./assets/src/js/admin/components/block-3.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

window.addEventListener('DOMContentLoaded', function () {
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
      title: directorist_admin.i18n_text.upload_image,
      button: {
        text: directorist_admin.i18n_text.choose_image
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

    $('.listing-img-container').html("<img src=\"".concat(directorist_admin.AdminAssetPath, "images/no-image.png\" alt=\"Listing Image\" />")); // Hide the delete image link

    delImgLink.addClass('hidden');
  });
  /* REMOVE SINGLE IMAGE */

  $(document).on('click', '.remove_image', function (e) {
    e.preventDefault();
    $(this).parent().remove(); // if no image exist then add placeholder and hide remove image button

    if ($('.single_attachment').length === 0) {
      $('.listing-img-container').html("<img src=\"".concat(directorist_admin.AdminAssetPath, "images/no-image.png\" alt=\"Listing Image\" /><p>No images</p> ") + "<small>(allowed formats jpeg. png. gif)</small>");
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

  if ($('.directorist-form-pricing-field').hasClass('price-type-both')) {
    $('#price').show();
    $('#price_range').hide();
  }

  $('.directorist_pricing_options label').on('click', function () {
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
      title: directorist_admin.i18n_text.select_prv_img,
      button: {
        text: directorist_admin.i18n_text.insert_prv_img
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
          /? $(`#${$this.data('option')}`).show()
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
   title: directorist_admin.i18n_text.upload_cat_image,
   button: {
       text: directorist_admin.i18n_text.choose_image
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
      url: directorist_admin.ajaxurl,
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
    e.preventDefault(); //console.log($(this).data('tabarea'));

    var atbds_tabParent = $(this).parent().parent().find('a.nav-link');
    var $href = $(this).attr('href');
    $(atbds_tabParent).removeClass('active');
    $(this).addClass('active'); //console.log($(".tab-content[data-tabarea='atbds_system-info-tab']"));

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
    var toolTipLabel = $(this).data('label'); //console.log(toolTipLabel);

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

  var localized_data = directorist_admin.add_listing_data;
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

  function admin_listing_form(directory_type) {
    $.ajax({
      type: 'post',
      url: directorist_admin.ajaxurl,
      data: {
        action: 'atbdp_dynamic_admin_listing_form',
        directory_type: directory_type,
        listing_id: $('#directiost-listing-fields_wrapper').data('id'),
        directorist_nonce: directorist_admin.directorist_nonce
      },
      success: function success(response) {
        if (response.error) {
          console.log({
            response: response
          });
          return;
        }

        $('#directiost-listing-fields_wrapper').empty().append(response.data['listing_meta_fields']);
        assetsNeedToWorkInVirtualDom();
        $('#at_biz_dir-locationchecklist').empty().html(response.data['listing_locations']);
        $('#at_biz_dir-categorychecklist').empty().html(response.data['listing_categories']);
        $('#at_biz_dir-categorychecklist-pop').empty().html(response.data['listing_pop_categories']);
        $('#at_biz_dir-locationchecklist-pop').empty().html(response.data['listing_pop_locations']);
        $('.misc-pub-atbdp-expiration-time').empty().html(response.data['listing_expiration']);
        $('#listing_form_info').find('.directorist_loader').remove();
        $('select[name="directory_type"]').closest('#poststuff').find('#publishing-action').removeClass('directorist_disable');

        if ($('.directorist-color-field-js').length) {
          $('.directorist-color-field-js').wpColorPicker().empty();
        }

        window.dispatchEvent(new CustomEvent('directorist-reload-plupload'));
        window.dispatchEvent(new CustomEvent('directorist-type-change'));

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

        customFieldSeeMore();
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
      url: directorist_admin.ajaxurl,
      data: {
        action: 'atbdp_listing_default_type',
        type_id: $(this).data('type-id'),
        nonce: directorist_admin.nonce
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

    /* $('#price_range').hide();
    const pricing = $('#atbd_listing_pricing').val();
    if (pricing === 'range') {
        $('#price').hide();
        $('#price_range').show();
    } */
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
        title: directorist_admin.i18n_text.select_prv_img,
        button: {
          text: directorist_admin.i18n_text.insert_prv_img
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
});

/***/ }),

/***/ "./assets/src/js/admin/components/block-4.js":
/*!***************************************************!*\
  !*** ./assets/src/js/admin/components/block-4.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

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

/***/ }),

/***/ "./assets/src/js/admin/components/block-5.js":
/*!***************************************************!*\
  !*** ./assets/src/js/admin/components/block-5.js ***!
  \***************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _lib_icon_picker__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../lib/icon-picker */ "./assets/src/js/lib/icon-picker.js");
/* harmony import */ var _lib_font_awesome_json__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./../../lib/font-awesome.json */ "./assets/src/js/lib/font-awesome.json");
var _lib_font_awesome_json__WEBPACK_IMPORTED_MODULE_1___namespace = /*#__PURE__*/__webpack_require__.t(/*! ./../../lib/font-awesome.json */ "./assets/src/js/lib/font-awesome.json", 1);
/* harmony import */ var _lib_line_awesome_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./../../lib/line-awesome.json */ "./assets/src/js/lib/line-awesome.json");
var _lib_line_awesome_json__WEBPACK_IMPORTED_MODULE_2___namespace = /*#__PURE__*/__webpack_require__.t(/*! ./../../lib/line-awesome.json */ "./assets/src/js/lib/line-awesome.json", 1);



window.addEventListener('DOMContentLoaded', function () {
  var $ = jQuery; // Init Category Icon Picker

  function initCategoryIconPicker() {
    var iconPickerContainer = document.querySelector('.directorist-category-icon-picker');

    if (!iconPickerContainer) {
      return;
    }

    var iconValueElm = document.querySelector('.category_icon_value');
    var iconValue = iconValueElm ? iconValueElm.value : '';

    var onSelectIcon = function onSelectIcon(value) {
      iconValueElm.setAttribute('value', value);
    };

    var args = {};
    args.container = iconPickerContainer;
    args.onSelect = onSelectIcon;
    args.icons = {
      fontAwesome: _lib_font_awesome_json__WEBPACK_IMPORTED_MODULE_1__,
      lineAwesome: _lib_line_awesome_json__WEBPACK_IMPORTED_MODULE_2__
    };
    args.value = iconValue;
    var iconPicker = new _lib_icon_picker__WEBPACK_IMPORTED_MODULE_0__["IconPicker"](args);
    iconPicker.init();
  }

  initCategoryIconPicker(); // Category icon selection

  function selecWithIcon(selected) {
    if (!selected.id) {
      return selected.text;
    }

    var $elem = $("<span><span class='".concat(selected.element.value, "'></span> ").concat(selected.text, "</span>"));
    return $elem;
  }

  if ($('#category_icon').length) {
    $('#category_icon').select2({
      placeholder: directorist_admin.i18n_text.icon_choose_text,
      allowClear: true,
      templateResult: selecWithIcon
    });
  }

  $('body').on('click', '.directorist_settings-trigger', function () {
    $('.setting-left-sibebar').toggleClass('active');
    $('.directorist_settings-panel-shade').toggleClass('active');
  });
  $('body').on('click', '.directorist_settings-panel-shade', function () {
    $('.setting-left-sibebar').removeClass('active');
    $(this).removeClass('active');
  }); // Directorist More Dropdown

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
  });
  $('.directorist-type-slug-content').each(function (id, element) {
    var findElmSlug = $(element).find('.directorist_listing-slug-text'); // Store old slug value

    var slugWrapper = $(element).children('.directorist_listing-slug-text');
    var oldSlugVal = slugWrapper.attr('data-value'); // Slug Edit

    slugWrapper.on('input keypress', function (e) {
      var slugText = $(this).text();
      $(this).attr('data-value', slugText);
      var setSlugBtn = $(this).siblings('.directorist-listing-slug-edit-wrap').children('.directorist_listing-slug-formText-add');
      $(this).attr('data-value') === '' ? setSlugBtn.addClass('disabled') : setSlugBtn.removeClass('disabled');

      if (e.key === 'Enter' && $(this).attr('data-value') !== '') {
        e.preventDefault();
        setSlugBtn.click();
      }

      if ($(this).attr('data-value') === '' && e.key === 'Enter') {
        e.preventDefault();
      }
    }); // Edit Form Open

    $('body').on('click', '.directorist-listing-slug__edit', function (e) {
      e.preventDefault();
      $('.directorist_listing-slug-formText-remove').click();
      var editableSlug = $(this).closest('.directorist-listing-slug-edit-wrap').siblings('.directorist_listing-slug-text');
      editableSlug.attr('contenteditable', true);
      editableSlug.addClass('directorist_listing-slug-text--editable');
      $(this).hide();
      $(this).siblings('.directorist_listing-slug-formText-add').addClass('active');
      $(this).siblings('.directorist_listing-slug-formText-remove').removeClass('directorist_listing-slug-formText-remove--hidden');
      editableSlug.focus();
    }); // edit directory type slug

    $(element).find('.directorist_listing-slug-formText-add').on('click', function (e) {
      e.preventDefault();

      var _this = $(this);

      var type_id = $(this).data('type-id');
      var update_slug = $('.directorist-slug-text-' + type_id).attr('data-value');
      oldSlugVal = slugWrapper.attr('data-value');
      /* Update the slug values */

      var addSlug = $(this);
      var slugId = $('.directorist-slug-notice-' + type_id);
      var thisSiblings = $(_this).closest('.directorist-listing-slug-edit-wrap').siblings('.directorist_listing-slug-text');
      addSlug.closest('.directorist-listing-slug-edit-wrap').append("<span class=\"directorist_loader\"></span>");
      $.ajax({
        type: 'post',
        url: directorist_admin.ajaxurl,
        data: {
          action: 'directorist_type_slug_change',
          directorist_nonce: directorist_admin.directorist_nonce,
          type_id: type_id,
          update_slug: update_slug
        },
        success: function success(response) {
          addSlug.closest('.directorist-listing-slug-edit-wrap').children('.directorist_loader').remove();

          if (response) {
            if (response.error) {
              slugId.removeClass('directorist-slug-notice-success');
              slugId.addClass('directorist-slug-notice-error');
              slugId.empty().html(response.error);

              if (response.old_slug) {
                $('.directorist-slug-text-' + type_id).text(response.old_slug);
              }

              _this.siblings('.directorist-listing-slug__edit').show();

              setTimeout(function () {
                slugId.empty().html("");
              }, 3000);
            } else {
              slugId.empty().html(response.success);
              slugId.removeClass('directorist-slug-notice-error');
              slugId.addClass('directorist-slug-notice-success');

              _this.siblings('.directorist-listing-slug__edit').show();

              setTimeout(function () {
                addSlug.closest('.directorist-listing-slug__form').css({
                  "display": "none"
                });
                slugId.html("");
              }, 1500);
            }
          }

          $(_this).removeClass('active');
          $(_this).siblings('.directorist_listing-slug-formText-remove').addClass('directorist_listing-slug-formText-remove--hidden');
          thisSiblings.removeClass('directorist_listing-slug-text--editable');
          thisSiblings.attr('contenteditable', 'false');
        }
      });
    }); // Edit Form Remove

    $(element).find('.directorist_listing-slug-formText-remove').on('click', function (e) {
      e.preventDefault();
      var thisClosestSibling = $(this).closest('.directorist-listing-slug-edit-wrap').siblings('.directorist_listing-slug-text');
      $(this).siblings('.directorist-listing-slug__edit').show();
      $(this).siblings('.directorist_listing-slug-formText-add').removeClass('active disabled');
      thisClosestSibling.removeClass('directorist_listing-slug-text--editable');
      thisClosestSibling.attr('contenteditable', 'false');
      $(this).addClass('directorist_listing-slug-formText-remove--hidden');
      thisClosestSibling.attr('data-value', oldSlugVal);
      thisClosestSibling.text(oldSlugVal);
    }); // Hide Slug Form outside click

    $(document).on('click', function (e) {
      if (!e.target.closest('.directorist-type-slug')) {
        findElmSlug.attr('data-value', oldSlugVal);
        findElmSlug.text(oldSlugVal);
        findElmSlug.attr('contenteditable', 'false');
        findElmSlug.removeClass('directorist_listing-slug-text--editable');
        $(element).find('.directorist-listing-slug__edit').show();
        findElmSlug.siblings('.directorist-listing-slug-edit-wrap').children('.directorist_listing-slug-formText-add').removeClass('active disabled');
        findElmSlug.siblings('.directorist-listing-slug-edit-wrap').children('.directorist_listing-slug-formText-remove').addClass('directorist_listing-slug-formText-remove--hidden');
      }
    });
  }); // Tab Content
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
});

/***/ }),

/***/ "./assets/src/js/admin/components/subscriptionManagement.js":
/*!******************************************************************!*\
  !*** ./assets/src/js/admin/components/subscriptionManagement.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

window.addEventListener('DOMContentLoaded', function () {
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
      password: form.find('input[name="password"]').val(),
      nonce: directorist_admin.nonce
    };
    $('.atbdp-form-feedback').html('');
    is_sending = true;
    jQuery.ajax({
      type: 'post',
      url: directorist_admin.ajaxurl,
      data: form_data,
      beforeSend: function beforeSend() {
        submit_button.prepend('<span class="atbdp-loading"><span class="fas fa-spinner fa-spin"></span></span>');
        submit_button.attr('disabled', true);
      },
      success: function success(response) {
        var _response$status, _response$status2;

        // console.log({response});
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
                type: file_type,
                nonce: directorist_admin.nonce
              };
              jQuery.ajax({
                type: 'post',
                url: directorist_admin.ajaxurl,
                data: form_data,
                beforeSend: function beforeSend() {
                  icon_elm.removeClass('atbdp-text-danger');
                  icon_elm.html('<span class="fas fa-circle-notch fa-spin"></span>');
                },
                success: function success(response) {
                  // console.log('success', counter, response);
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
    e.preventDefault(); // console.log('reloading...');

    location.reload();
  }); // Extension Update Button

  $('.ext-update-btn').on('click', function (e) {
    e.preventDefault();
    $(this).prop('disabled', true);
    var plugin_key = $(this).data('key');
    var button_default_html = $(this).html();
    var form_data = {
      action: 'atbdp_update_plugins',
      nonce: directorist_admin.nonce
    };

    if (plugin_key) {
      form_data.plugin_key = plugin_key;
    } // console.log( { plugin_key } );


    var self = this;
    jQuery.ajax({
      type: 'post',
      url: directorist_admin.ajaxurl,
      data: form_data,
      beforeSend: function beforeSend() {
        var icon = '<i class="fas fa-circle-notch fa-spin"></i> Updating';
        $(self).html(icon);
      },
      success: function success(response) {
        // console.log( { response } );
        if (response.status.success) {
          $(self).html('Updated');
          location.reload();
        } else {
          $(self).html(button_default_html);
          alert(response.status.message);
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
      // console.log('Wait...');
      return;
    }

    var data_key = $(this).data('key');
    var data_type = $(this).data('type');
    var form_data = {
      action: 'atbdp_install_file_from_subscriptions',
      item_key: data_key,
      type: data_type,
      nonce: directorist_admin.nonce
    };
    var btn_default_html = $(this).html();
    ext_is_installing = true;
    var self = this;
    $(this).prop('disabled', true);
    $(this).addClass('in-progress');
    jQuery.ajax({
      type: 'post',
      url: directorist_admin.ajaxurl,
      data: form_data,
      beforeSend: function beforeSend() {
        $(self).html('Installing');
        var icon = '<i class="fas fa-circle-notch fa-spin"></i> ';
        $(self).prepend(icon);
      },
      success: function success(response) {
        // console.log(response);
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
      // console.log('Wait...');
      return;
    }

    var data_key = $(this).data('key');
    var form_data = {
      action: 'atbdp_activate_plugin',
      item_key: data_key,
      nonce: directorist_admin.nonce
    };
    var btn_default_html = $(this).html();
    var self = this;
    $(this).prop('disabled', true);
    $(this).addClass('in-progress');
    jQuery.ajax({
      type: 'post',
      url: directorist_admin.ajaxurl,
      data: form_data,
      beforeSend: function beforeSend() {
        $(self).html('Activating');
        var icon = '<i class="fas fa-circle-notch fa-spin"></i> ';
        $(self).prepend(icon);
      },
      success: function success(response) {
        // console.log(response);
        // return;
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
      password: password,
      nonce: directorist_admin.nonce
    };
    form_feedback.html('');
    jQuery.ajax({
      type: 'post',
      url: directorist_admin.ajaxurl,
      data: form_data,
      beforeSend: function beforeSend() {
        $(submit_btn).html('<i class="fas fa-circle-notch fa-spin"></i>');
      },
      success: function success(response) {
        // console.log(response);
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
      hard_logout: hard_logout,
      nonce: directorist_admin.nonce
    };
    var self = this;
    jQuery.ajax({
      type: 'post',
      url: directorist_admin.ajaxurl,
      data: form_data,
      beforeSend: function beforeSend() {
        $(self).html('<i class="fas fa-circle-notch fa-spin"></i> Logging out');
      },
      success: function success(response) {
        // console.log( response );
        location.reload();
      },
      error: function error(_error7) {
        // console.log(error);
        $(this).prop('disabled', false);
        $(this).removeClass('in-progress');
        $(self).html(btn_default_html);
      }
    }); // atbdp_close_subscriptions_sassion
  }); // Form Actions
  // Apply button active status - My extension form

  var extFormCheckboxes = document.querySelectorAll('#atbdp-extensions-tab input[type="checkbox"]');
  var extFormActionSelect = document.querySelectorAll('#atbdp-extensions-tab select[name="bulk-actions"]'); //console.log(extFormActionSelect);

  extFormCheckboxes.forEach(function (elm) {
    var thisClosest = elm.closest('form');
    var bulkAction = thisClosest.querySelector('.ei-action-dropdown select');
    var actionBtn = thisClosest.querySelector('.ei-action-btn');
    elm.addEventListener('change', function () {
      this.checked === true && bulkAction.value !== '' ? actionBtn.classList.add('ei-action-active') : this.checked === false ? actionBtn.classList.remove('ei-action-active') : '';
    });
  });
  extFormActionSelect.forEach(function (elm) {
    var thisClosest = elm.closest('form');
    var checkboxes = thisClosest.querySelectorAll('input[type="checkbox"]');
    var actionBtn = thisClosest.querySelector('.ei-action-btn');
    elm.addEventListener('change', function () {
      checkboxes.forEach(function (checkbox) {
        if (checkbox.checked === true && this.value !== '') {
          actionBtn.classList.add('ei-action-active');
        }
      });

      if (this.value === '') {
        actionBtn.classList.remove('ei-action-active');
      }
    });
  }); // Bulk Actions - My extensions form

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
      plugin_items: plugins_items,
      directorist_nonce: directorist_admin.directorist_nonce
    };
    jQuery.ajax({
      type: 'post',
      url: directorist_admin.ajaxurl,
      data: form_data,
      beforeSend: function beforeSend() {
        $(self).find('button[type="submit"]').prepend('<span class="atbdp-icon"><span class="fas fa-circle-notch fa-spin"></span></span> ');
      },
      success: function success(response) {
        $(self).find('button[type="submit"] .atbdp-icon').remove();
        location.reload();
      },
      error: function error(_error8) {
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
        type: 'plugin',
        nonce: directorist_admin.nonce
      };
      jQuery.ajax({
        type: 'post',
        url: directorist_admin.ajaxurl,
        data: form_data,
        beforeSend: function beforeSend() {
          action_btn.html("<span class=\"atbdp-icon\">\n                        <span class=\"fas fa-circle-notch fa-spin\"></span>\n                    </span> ".concat(btnLabelOnProgress[task]));
        },
        success: function success(response) {
          // console.log( { response } );
          if (response.status.success) {
            action_btn.html(btnLabelOnSuccess[task]);
          } else {
            action_btn.html('Failed');
          }

          bulk_task(plugins, next_index, callback);
        },
        error: function error(_error9) {// console.log(error);
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
      plugin_items: [data_target],
      nonce: directorist_admin.nonce
    };
    var self = this;
    uninstalling = true;
    jQuery.ajax({
      type: 'post',
      url: directorist_admin.ajaxurl,
      data: form_data,
      beforeSend: function beforeSend() {
        $(self).prepend('<span class="atbdp-icon"><span class="fas fa-circle-notch fa-spin"></span></span> ');
      },
      success: function success(response) {
        // console.log( response );
        $(self).closest('.ext-action').find('.ext-action-drop').removeClass('active');
        location.reload();
      },
      error: function error(_error10) {
        // console.log(error);
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
      theme_stylesheet: data_target,
      nonce: directorist_admin.nonce
    };
    var self = this;
    theme_is_activating = true;
    $.ajax({
      type: 'post',
      url: directorist_admin.ajaxurl,
      data: form_data,
      beforeSend: function beforeSend() {
        $(self).prepend('<span class="atbdp-icon"><span class="fas fa-circle-notch fa-spin"></span></span> ');
      },
      success: function success(response) {
        // console.log({ response });
        $(self).find('.atbdp-icon').remove();

        if (response.status && response.status.success) {
          location.reload();
        }
      },
      error: function error(_error11) {
        // console.log({ error });
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
      action: 'atbdp_update_theme',
      nonce: directorist_admin.nonce
    };

    if (theme_stylesheet) {
      form_data.theme_stylesheet = theme_stylesheet;
    }

    var self = this;
    $(this).addClass('in-progress');
    $.ajax({
      type: 'post',
      url: directorist_admin.ajaxurl,
      data: form_data,
      beforeSend: function beforeSend() {
        $(self).html('<span class="atbdp-icon"><span class="fas fa-circle-notch fa-spin"></span></span> Updating');
      },
      success: function success(response) {
        // console.log({ response });
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
        // console.log({ error });
        $(self).removeClass('in-progress');
        $(self).html(button_default_html);
        $(self).prop('disabled', false);
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

function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

var $ = jQuery;
window.addEventListener('load', waitAndInit);
window.addEventListener('directorist-search-form-nav-tab-reloaded', waitAndInit);
window.addEventListener('directorist-type-change', waitAndInit);
window.addEventListener('directorist-instant-search-reloaded', waitAndInit);

function waitAndInit() {
  setTimeout(init, 0);
} // Initialize


function init() {
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
    var dropdownHTML = '<span class="directorist-select2-addon directorist-select2-dropdown-toggle"><i class="fas fa-chevron-down"></i></span>';
    addon_container.append(dropdownHTML);
  }

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
  } // Remove if already exists


  addon_container.find('.directorist-select2-dropdown-close').remove(); // Add

  addon_container.prepend('<span class="directorist-select2-addon directorist-select2-dropdown-close"><i class="fas fa-times"></i></span>');
  var selec2_custom_close = addon_container.find('.directorist-select2-dropdown-close');
  selec2_custom_close.on('click', function (e) {
    var field = $(this).closest('.select2-container').siblings('select:enabled');
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



function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }



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
    Object(_lib_helper__WEBPACK_IMPORTED_MODULE_2__["convertToSelect2"])(field);
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

  if (!args.selector.length) {
    return;
  }

  _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(args.selector).forEach(function (item, index) {
    var directory_type_id = 0;
    var search_form_parent = $(item).closest('.directorist-search-form');
    var archive_page_parent = $(item).closest('.directorist-archive-contents');
    var nav_list_item = []; // If search page

    if (search_form_parent.length) {
      nav_list_item = search_form_parent.find('.directorist-listing-type-selection__link--current');
    } // If archive page


    if (archive_page_parent.length) {
      nav_list_item = archive_page_parent.find('.directorist-type-nav__list li.current .directorist-type-nav__link');
    } // If has nav item


    if (nav_list_item.length) {
      directory_type_id = nav_list_item ? nav_list_item.data('listing_type_id') : 0;
    }

    var currentPage = 1;
    $(item).select2({
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
    }); // Setup Preselected Option

    var selected_item_id = $(item).data('selected-id');
    var selected_item_label = $(item).data('selected-label');

    if (selected_item_id) {
      var option = new Option(selected_item_label, selected_item_id, true, true);
      $(item).append(option);
      $(item).trigger({
        type: 'select2:select',
        params: {
          data: {
            id: selected_item_id,
            text: selected_item_label
          }
        }
      });
    }
  });
}

/***/ }),

/***/ "./assets/src/js/global/components/tabs.js":
/*!*************************************************!*\
  !*** ./assets/src/js/global/components/tabs.js ***!
  \*************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ "./node_modules/@babel/runtime/helpers/toConsumableArray.js");
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__);

document.addEventListener('DOMContentLoaded', init, false);

function Tasks() {
  return {
    init: function init() {
      this.initToggleTabLinks();
    },
    initToggleTabLinks: function initToggleTabLinks() {
      var links = document.querySelectorAll('.directorist-toggle-tab');

      if (!links) {
        return;
      }

      var self = this;

      _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(links).forEach(function (item) {
        item.addEventListener('click', function (event) {
          self.handleToggleTabLinksEvent(item, event);
        });
      });
    },
    handleToggleTabLinksEvent: function handleToggleTabLinksEvent(item, event) {
      event.preventDefault();
      var navContainerClass = item.getAttribute('data-nav-container');
      var tabContainerClass = item.getAttribute('data-tab-container');
      var tabClass = item.getAttribute('data-tab');

      if (!navContainerClass || !tabContainerClass || !tabClass) {
        return;
      }

      var navContainer = item.closest('.' + navContainerClass);
      var tabContainer = document.querySelector('.' + tabContainerClass);

      if (!navContainer || !tabContainer) {
        return;
      }

      var tab = tabContainer.querySelector('.' + tabClass);

      if (!tab) {
        return;
      } // Remove Active Class


      var removeActiveClass = function removeActiveClass(item) {
        item.classList.remove('--is-active');
      }; // Toggle Nav


      var activeNavItems = navContainer.querySelectorAll('.--is-active');

      if (activeNavItems) {
        _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(activeNavItems).forEach(removeActiveClass);
      }

      item.classList.add('--is-active'); // Toggle Tab

      var activeTabItems = tabContainer.querySelectorAll('.--is-active');

      if (activeTabItems) {
        _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(activeTabItems).forEach(removeActiveClass);
      }

      tab.classList.add('--is-active'); // Update Query Var

      var queryVarKey = item.getAttribute('data-query-var-key');
      var queryVarValue = item.getAttribute('data-query-var-value');

      if (!queryVarKey || !queryVarValue) {
        return;
      }

      this.addQueryParam(queryVarKey, queryVarValue);
    },
    addQueryParam: function addQueryParam(key, value) {
      var url = new URL(window.location.href);
      url.searchParams.set(key, value);
      window.history.pushState({}, '', url.toString());
    }
  };
}

function init() {
  var tasks = new Tasks();
  tasks.init();
}

/***/ }),

/***/ "./assets/src/js/global/components/utility.js":
/*!****************************************************!*\
  !*** ./assets/src/js/global/components/utility.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

window.addEventListener('DOMContentLoaded', function () {
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
});

/***/ }),

/***/ "./assets/src/js/global/global.js":
/*!****************************************!*\
  !*** ./assets/src/js/global/global.js ***!
  \****************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_utility__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/utility */ "./assets/src/js/global/components/utility.js");
/* harmony import */ var _components_utility__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_components_utility__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_tabs__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/tabs */ "./assets/src/js/global/components/tabs.js");
/* harmony import */ var _components_modal__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/modal */ "./assets/src/js/global/components/modal.js");
/* harmony import */ var _components_modal__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_components_modal__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _components_setup_select2__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/setup-select2 */ "./assets/src/js/global/components/setup-select2.js");
/* harmony import */ var _components_select2_custom_control__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./components/select2-custom-control */ "./assets/src/js/global/components/select2-custom-control.js");
/* harmony import */ var _components_select2_custom_control__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_4__);






/***/ }),

/***/ "./assets/src/js/lib/font-awesome.json":
/*!*********************************************!*\
  !*** ./assets/src/js/lib/font-awesome.json ***!
  \*********************************************/
/*! exports provided: label, iconTypes, icons, default */
/***/ (function(module) {

module.exports = JSON.parse("{\"label\":\"Font Awesome\",\"iconTypes\":{\"solid\":{\"label\":\"Solid\",\"key\":\"fas\"},\"regular\":{\"label\":\"Regular\",\"key\":\"far\"},\"brands\":{\"label\":\"Brand\",\"key\":\"fab\"}},\"icons\":[{\"key\":\"fa-address-book\",\"types\":[\"regular\"]},{\"key\":\"fa-address-card\",\"types\":[\"regular\"]},{\"key\":\"fa-angry\",\"types\":[\"regular\"]},{\"key\":\"fa-arrow-alt-circle-down\",\"types\":[\"regular\"]},{\"key\":\"fa-arrow-alt-circle-left\",\"types\":[\"regular\"]},{\"key\":\"fa-arrow-alt-circle-right\",\"types\":[\"regular\"]},{\"key\":\"fa-arrow-alt-circle-up\",\"types\":[\"regular\"]},{\"key\":\"fa-bell\",\"types\":[\"regular\"]},{\"key\":\"fa-bell-slash\",\"types\":[\"regular\"]},{\"key\":\"fa-bookmark\",\"types\":[\"regular\"]},{\"key\":\"fa-building\",\"types\":[\"regular\"]},{\"key\":\"fa-calendar\",\"types\":[\"regular\"]},{\"key\":\"fa-calendar-alt\",\"types\":[\"regular\"]},{\"key\":\"fa-calendar-check\",\"types\":[\"regular\"]},{\"key\":\"fa-calendar-minus\",\"types\":[\"regular\"]},{\"key\":\"fa-calendar-plus\",\"types\":[\"regular\"]},{\"key\":\"fa-calendar-times\",\"types\":[\"regular\"]},{\"key\":\"fa-caret-square-down\",\"types\":[\"regular\"]},{\"key\":\"fa-caret-square-left\",\"types\":[\"regular\"]},{\"key\":\"fa-caret-square-right\",\"types\":[\"regular\"]},{\"key\":\"fa-caret-square-up\",\"types\":[\"regular\"]},{\"key\":\"fa-chart-bar\",\"types\":[\"regular\"]},{\"key\":\"fa-check-circle\",\"types\":[\"regular\"]},{\"key\":\"fa-check-square\",\"types\":[\"regular\"]},{\"key\":\"fa-circle\",\"types\":[\"regular\"]},{\"key\":\"fa-clipboard\",\"types\":[\"regular\"]},{\"key\":\"fa-clock\",\"types\":[\"regular\"]},{\"key\":\"fa-clone\",\"types\":[\"regular\"]},{\"key\":\"fa-closed-captioning\",\"types\":[\"regular\"]},{\"key\":\"fa-comment\",\"types\":[\"regular\"]},{\"key\":\"fa-comment-alt\",\"types\":[\"regular\"]},{\"key\":\"fa-comment-dots\",\"types\":[\"regular\"]},{\"key\":\"fa-comments\",\"types\":[\"regular\"]},{\"key\":\"fa-compass\",\"types\":[\"regular\"]},{\"key\":\"fa-copy\",\"types\":[\"regular\"]},{\"key\":\"fa-copyright\",\"types\":[\"regular\"]},{\"key\":\"fa-credit-card\",\"types\":[\"regular\"]},{\"key\":\"fa-dizzy\",\"types\":[\"regular\"]},{\"key\":\"fa-dot-circle\",\"types\":[\"regular\"]},{\"key\":\"fa-edit\",\"types\":[\"regular\"]},{\"key\":\"fa-envelope\",\"types\":[\"regular\"]},{\"key\":\"fa-envelope-open\",\"types\":[\"regular\"]},{\"key\":\"fa-eye\",\"types\":[\"regular\"]},{\"key\":\"fa-eye-slash\",\"types\":[\"regular\"]},{\"key\":\"fa-file\",\"types\":[\"regular\"]},{\"key\":\"fa-file-alt\",\"types\":[\"regular\"]},{\"key\":\"fa-file-archive\",\"types\":[\"regular\"]},{\"key\":\"fa-file-audio\",\"types\":[\"regular\"]},{\"key\":\"fa-file-code\",\"types\":[\"regular\"]},{\"key\":\"fa-file-excel\",\"types\":[\"regular\"]},{\"key\":\"fa-file-image\",\"types\":[\"regular\"]},{\"key\":\"fa-file-pdf\",\"types\":[\"regular\"]},{\"key\":\"fa-file-powerpoint\",\"types\":[\"regular\"]},{\"key\":\"fa-file-video\",\"types\":[\"regular\"]},{\"key\":\"fa-file-word\",\"types\":[\"regular\"]},{\"key\":\"fa-flag\",\"types\":[\"regular\"]},{\"key\":\"fa-flushed\",\"types\":[\"regular\"]},{\"key\":\"fa-folder\",\"types\":[\"regular\"]},{\"key\":\"fa-folder-open\",\"types\":[\"regular\"]},{\"key\":\"fa-frown\",\"types\":[\"regular\"]},{\"key\":\"fa-frown-open\",\"types\":[\"regular\"]},{\"key\":\"fa-futbol\",\"types\":[\"regular\"]},{\"key\":\"fa-gem\",\"types\":[\"regular\"]},{\"key\":\"fa-grimace\",\"types\":[\"regular\"]},{\"key\":\"fa-grin\",\"types\":[\"regular\"]},{\"key\":\"fa-grin-alt\",\"types\":[\"regular\"]},{\"key\":\"fa-grin-beam\",\"types\":[\"regular\"]},{\"key\":\"fa-grin-beam-sweat\",\"types\":[\"regular\"]},{\"key\":\"fa-grin-hearts\",\"types\":[\"regular\"]},{\"key\":\"fa-grin-squint\",\"types\":[\"regular\"]},{\"key\":\"fa-grin-squint-tears\",\"types\":[\"regular\"]},{\"key\":\"fa-grin-stars\",\"types\":[\"regular\"]},{\"key\":\"fa-grin-tears\",\"types\":[\"regular\"]},{\"key\":\"fa-grin-tongue\",\"types\":[\"regular\"]},{\"key\":\"fa-grin-tongue-squint\",\"types\":[\"regular\"]},{\"key\":\"fa-grin-tongue-wink\",\"types\":[\"regular\"]},{\"key\":\"fa-grin-wink\",\"types\":[\"regular\"]},{\"key\":\"fa-hand-lizard\",\"types\":[\"regular\"]},{\"key\":\"fa-hand-paper\",\"types\":[\"regular\"]},{\"key\":\"fa-hand-peace\",\"types\":[\"regular\"]},{\"key\":\"fa-hand-point-down\",\"types\":[\"regular\"]},{\"key\":\"fa-hand-point-left\",\"types\":[\"regular\"]},{\"key\":\"fa-hand-point-right\",\"types\":[\"regular\"]},{\"key\":\"fa-hand-point-up\",\"types\":[\"regular\"]},{\"key\":\"fa-hand-pointer\",\"types\":[\"regular\"]},{\"key\":\"fa-hand-rock\",\"types\":[\"regular\"]},{\"key\":\"fa-hand-scissors\",\"types\":[\"regular\"]},{\"key\":\"fa-hand-spock\",\"types\":[\"regular\"]},{\"key\":\"fa-handshake\",\"types\":[\"regular\"]},{\"key\":\"fa-hdd\",\"types\":[\"regular\"]},{\"key\":\"fa-heart\",\"types\":[\"regular\"]},{\"key\":\"fa-hospital\",\"types\":[\"regular\"]},{\"key\":\"fa-hourglass\",\"types\":[\"regular\"]},{\"key\":\"fa-id-badge\",\"types\":[\"regular\"]},{\"key\":\"fa-id-card\",\"types\":[\"regular\"]},{\"key\":\"fa-image\",\"types\":[\"regular\"]},{\"key\":\"fa-images\",\"types\":[\"regular\"]},{\"key\":\"fa-keyboard\",\"types\":[\"regular\"]},{\"key\":\"fa-kiss\",\"types\":[\"regular\"]},{\"key\":\"fa-kiss-beam\",\"types\":[\"regular\"]},{\"key\":\"fa-kiss-wink-heart\",\"types\":[\"regular\"]},{\"key\":\"fa-laugh\",\"types\":[\"regular\"]},{\"key\":\"fa-laugh-beam\",\"types\":[\"regular\"]},{\"key\":\"fa-laugh-squint\",\"types\":[\"regular\"]},{\"key\":\"fa-laugh-wink\",\"types\":[\"regular\"]},{\"key\":\"fa-lemon\",\"types\":[\"regular\"]},{\"key\":\"fa-life-ring\",\"types\":[\"regular\"]},{\"key\":\"fa-lightbulb\",\"types\":[\"regular\"]},{\"key\":\"fa-list-alt\",\"types\":[\"regular\"]},{\"key\":\"fa-map\",\"types\":[\"regular\"]},{\"key\":\"fa-meh\",\"types\":[\"regular\"]},{\"key\":\"fa-meh-blank\",\"types\":[\"regular\"]},{\"key\":\"fa-meh-rolling-eyes\",\"types\":[\"regular\"]},{\"key\":\"fa-minus-square\",\"types\":[\"regular\"]},{\"key\":\"fa-money-bill-alt\",\"types\":[\"regular\"]},{\"key\":\"fa-moon\",\"types\":[\"regular\"]},{\"key\":\"fa-newspaper\",\"types\":[\"regular\"]},{\"key\":\"fa-object-group\",\"types\":[\"regular\"]},{\"key\":\"fa-object-ungroup\",\"types\":[\"regular\"]},{\"key\":\"fa-paper-plane\",\"types\":[\"regular\"]},{\"key\":\"fa-pause-circle\",\"types\":[\"regular\"]},{\"key\":\"fa-play-circle\",\"types\":[\"regular\"]},{\"key\":\"fa-plus-square\",\"types\":[\"regular\"]},{\"key\":\"fa-question-circle\",\"types\":[\"regular\"]},{\"key\":\"fa-registered\",\"types\":[\"regular\"]},{\"key\":\"fa-sad-cry\",\"types\":[\"regular\"]},{\"key\":\"fa-sad-tear\",\"types\":[\"regular\"]},{\"key\":\"fa-save\",\"types\":[\"regular\"]},{\"key\":\"fa-share-square\",\"types\":[\"regular\"]},{\"key\":\"fa-smile\",\"types\":[\"regular\"]},{\"key\":\"fa-smile-beam\",\"types\":[\"regular\"]},{\"key\":\"fa-smile-wink\",\"types\":[\"regular\"]},{\"key\":\"fa-snowflake\",\"types\":[\"regular\"]},{\"key\":\"fa-square\",\"types\":[\"regular\"]},{\"key\":\"fa-star\",\"types\":[\"regular\"]},{\"key\":\"fa-star-half\",\"types\":[\"regular\"]},{\"key\":\"fa-sticky-note\",\"types\":[\"regular\"]},{\"key\":\"fa-stop-circle\",\"types\":[\"regular\"]},{\"key\":\"fa-sun\",\"types\":[\"regular\"]},{\"key\":\"fa-surprise\",\"types\":[\"regular\"]},{\"key\":\"fa-thumbs-down\",\"types\":[\"regular\"]},{\"key\":\"fa-thumbs-up\",\"types\":[\"regular\"]},{\"key\":\"fa-times-circle\",\"types\":[\"regular\"]},{\"key\":\"fa-tired\",\"types\":[\"regular\"]},{\"key\":\"fa-trash-alt\",\"types\":[\"regular\"]},{\"key\":\"fa-user\",\"types\":[\"regular\"]},{\"key\":\"fa-user-circle\",\"types\":[\"regular\"]},{\"key\":\"fa-window-close\",\"types\":[\"regular\"]},{\"key\":\"fa-window-maximize\",\"types\":[\"regular\"]},{\"key\":\"fa-window-minimize\",\"types\":[\"regular\"]},{\"key\":\"fa-window-restore\",\"types\":[\"regular\"]},{\"key\":\"fa-ad\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-address-book\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-address-card\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-adjust\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-air-freshener\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-align-center\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-align-justify\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-align-left\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-align-right\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-allergies\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-ambulance\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-american-sign-language-interpreting\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-anchor\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-angle-double-down\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-angle-double-left\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-angle-double-right\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-angle-double-up\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-angle-down\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-angle-left\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-angle-right\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-angle-up\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-angry\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-ankh\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-apple-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-archive\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-archway\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-arrow-alt-circle-down\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-arrow-alt-circle-left\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-arrow-alt-circle-right\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-arrow-alt-circle-up\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-arrow-circle-down\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-arrow-circle-left\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-arrow-circle-right\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-arrow-circle-up\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-arrow-down\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-arrow-left\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-arrow-right\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-arrow-up\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-arrows-alt-h\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-arrows-alt-v\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-arrows-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-assistive-listening-systems\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-asterisk\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-at\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-atlas\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-atom\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-audio-description\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-award\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-baby-carriage\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-baby\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-backspace\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-backward\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bacon\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bacteria\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bacterium\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bahai\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-balance-scale-left\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-balance-scale-right\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-balance-scale\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-ban\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-band-aid\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-barcode\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bars\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-baseball-ball\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-basketball-ball\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bath\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-battery-empty\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-battery-full\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-battery-half\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-battery-quarter\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-battery-three-quarters\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bed\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-beer\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bell-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bell\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bezier-curve\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bible\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bicycle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-biking\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-binoculars\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-biohazard\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-birthday-cake\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-blender-phone\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-blender\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-blind\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-blog\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bold\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bolt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bomb\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bone\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bong\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-book-dead\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-book-medical\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-book-open\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-book-reader\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-book\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bookmark\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-border-all\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-border-none\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-border-style\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bowling-ball\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-box-open\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-box-tissue\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-box\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-boxes\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-braille\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-brain\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bread-slice\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-briefcase-medical\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-briefcase\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-broadcast-tower\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-broom\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-brush\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bug\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-building\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bullhorn\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bullseye\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-burn\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bus-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-bus\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-business-time\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-calculator\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-calendar-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-calendar-check\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-calendar-day\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-calendar-minus\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-calendar-plus\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-calendar-times\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-calendar-week\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-calendar\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-camera-retro\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-camera\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-campground\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-candy-cane\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cannabis\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-capsules\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-car-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-car-battery\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-car-crash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-car-side\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-car\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-caravan\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-caret-down\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-caret-left\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-caret-right\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-caret-square-down\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-caret-square-left\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-caret-square-right\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-caret-square-up\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-caret-up\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-carrot\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cart-arrow-down\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cart-plus\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cash-register\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cat\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-certificate\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chair\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chalkboard-teacher\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chalkboard\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-charging-station\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chart-area\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chart-bar\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chart-line\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chart-pie\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-check-circle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-check-double\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-check-square\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-check\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cheese\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chess-bishop\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chess-board\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chess-king\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chess-knight\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chess-pawn\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chess-queen\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chess-rook\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chess\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chevron-circle-down\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chevron-circle-left\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chevron-circle-right\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chevron-circle-up\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chevron-down\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chevron-left\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chevron-right\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-chevron-up\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-child\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-church\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-circle-notch\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-circle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-city\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-clinic-medical\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-clipboard-check\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-clipboard-list\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-clipboard\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-clock\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-clone\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-closed-captioning\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cloud-download-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cloud-meatball\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cloud-moon-rain\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cloud-moon\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cloud-rain\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cloud-showers-heavy\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cloud-sun-rain\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cloud-sun\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cloud-upload-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cloud\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cocktail\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-code-branch\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-code\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-coffee\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cog\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cogs\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-coins\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-columns\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-comment-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-comment-dollar\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-comment-dots\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-comment-medical\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-comment-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-comment\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-comments-dollar\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-comments\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-compact-disc\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-compass\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-compress-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-compress-arrows-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-compress\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-concierge-bell\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cookie-bite\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cookie\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-copy\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-copyright\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-couch\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-credit-card\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-crop-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-crop\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cross\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-crosshairs\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-crow\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-crown\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-crutch\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cube\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cubes\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-cut\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-database\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-deaf\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-democrat\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-desktop\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dharmachakra\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-diagnoses\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dice-d20\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dice-d6\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dice-five\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dice-four\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dice-one\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dice-six\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dice-three\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dice-two\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dice\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-digital-tachograph\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-directions\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-disease\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-divide\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dizzy\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dna\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dog\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dollar-sign\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dolly-flatbed\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dolly\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-donate\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-door-closed\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-door-open\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dot-circle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dove\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-download\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-drafting-compass\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dragon\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-draw-polygon\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-drum-steelpan\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-drum\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-drumstick-bite\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dumbbell\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dumpster-fire\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dumpster\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-dungeon\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-edit\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-egg\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-eject\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-ellipsis-h\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-ellipsis-v\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-envelope-open-text\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-envelope-open\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-envelope-square\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-envelope\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-equals\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-eraser\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-ethernet\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-euro-sign\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-exchange-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-exclamation-circle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-exclamation-triangle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-exclamation\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-expand-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-expand-arrows-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-expand\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-external-link-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-external-link-square-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-eye-dropper\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-eye-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-eye\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-fan\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-fast-backward\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-fast-forward\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-faucet\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-fax\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-feather-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-feather\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-female\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-fighter-jet\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-archive\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-audio\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-code\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-contract\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-csv\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-download\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-excel\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-export\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-image\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-import\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-invoice-dollar\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-invoice\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-medical-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-medical\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-pdf\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-powerpoint\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-prescription\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-signature\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-upload\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-video\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file-word\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-file\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-fill-drip\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-fill\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-film\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-filter\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-fingerprint\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-fire-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-fire-extinguisher\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-fire\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-first-aid\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-fish\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-fist-raised\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-flag-checkered\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-flag-usa\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-flag\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-flask\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-flushed\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-folder-minus\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-folder-open\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-folder-plus\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-folder\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-font-awesome-logo-full\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-font\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-football-ball\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-forward\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-frog\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-frown-open\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-frown\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-funnel-dollar\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-futbol\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-gamepad\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-gas-pump\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-gavel\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-gem\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-genderless\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-ghost\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-gift\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-gifts\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-glass-cheers\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-glass-martini-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-glass-martini\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-glass-whiskey\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-glasses\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-globe-africa\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-globe-americas\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-globe-asia\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-globe-europe\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-globe\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-golf-ball\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-gopuram\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-graduation-cap\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-greater-than-equal\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-greater-than\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-grimace\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-grin-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-grin-beam-sweat\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-grin-beam\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-grin-hearts\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-grin-squint-tears\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-grin-squint\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-grin-stars\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-grin-tears\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-grin-tongue-squint\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-grin-tongue-wink\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-grin-tongue\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-grin-wink\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-grin\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-grip-horizontal\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-grip-lines-vertical\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-grip-lines\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-grip-vertical\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-guitar\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-h-square\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hamburger\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hammer\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hamsa\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hand-holding-heart\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hand-holding-medical\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hand-holding-usd\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hand-holding-water\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hand-holding\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hand-lizard\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hand-middle-finger\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hand-paper\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hand-peace\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hand-point-down\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hand-point-left\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hand-point-right\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hand-point-up\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hand-pointer\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hand-rock\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hand-scissors\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hand-sparkles\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hand-spock\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hands-helping\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hands-wash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hands\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-handshake-alt-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-handshake-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-handshake\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hanukiah\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hard-hat\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hashtag\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hat-cowboy-side\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hat-cowboy\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hat-wizard\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hdd\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-head-side-cough-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-head-side-cough\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-head-side-mask\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-head-side-virus\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-heading\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-headphones-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-headphones\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-headset\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-heart-broken\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-heart\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-heartbeat\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-helicopter\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-highlighter\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hiking\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hippo\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-history\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hockey-puck\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-holly-berry\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-home\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-horse-head\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-horse\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hospital-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hospital-symbol\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hospital-user\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hospital\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hot-tub\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hotdog\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hotel\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hourglass-end\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hourglass-half\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hourglass-start\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hourglass\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-house-damage\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-house-user\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-hryvnia\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-i-cursor\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-ice-cream\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-icicles\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-icons\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-id-badge\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-id-card-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-id-card\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-igloo\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-image\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-images\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-inbox\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-indent\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-industry\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-infinity\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-info-circle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-info\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-italic\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-jedi\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-joint\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-journal-whills\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-kaaba\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-key\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-keyboard\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-khanda\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-kiss-beam\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-kiss-wink-heart\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-kiss\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-kiwi-bird\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-landmark\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-language\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-laptop-code\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-laptop-house\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-laptop-medical\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-laptop\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-laugh-beam\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-laugh-squint\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-laugh-wink\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-laugh\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-layer-group\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-leaf\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-lemon\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-less-than-equal\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-less-than\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-level-down-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-level-up-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-life-ring\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-lightbulb\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-link\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-lira-sign\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-list-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-list-ol\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-list-ul\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-list\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-location-arrow\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-lock-open\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-lock\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-long-arrow-alt-down\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-long-arrow-alt-left\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-long-arrow-alt-right\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-long-arrow-alt-up\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-low-vision\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-luggage-cart\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-lungs-virus\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-lungs\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-magic\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-magnet\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-mail-bulk\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-male\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-map-marked-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-map-marked\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-map-marker-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-map-marker\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-map-pin\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-map-signs\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-map\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-marker\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-mars-double\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-mars-stroke-h\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-mars-stroke-v\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-mars-stroke\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-mars\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-mask\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-medal\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-medkit\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-meh-blank\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-meh-rolling-eyes\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-meh\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-memory\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-menorah\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-mercury\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-meteor\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-microchip\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-microphone-alt-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-microphone-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-microphone-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-microphone\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-microscope\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-minus-circle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-minus-square\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-minus\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-mitten\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-mobile-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-mobile\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-money-bill-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-money-bill-wave-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-money-bill-wave\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-money-bill\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-money-check-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-money-check\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-monument\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-moon\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-mortar-pestle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-mosque\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-motorcycle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-mountain\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-mouse-pointer\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-mouse\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-mug-hot\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-music\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-network-wired\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-neuter\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-newspaper\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-not-equal\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-notes-medical\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-object-group\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-object-ungroup\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-oil-can\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-om\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-otter\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-outdent\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-pager\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-paint-brush\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-paint-roller\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-palette\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-pallet\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-paper-plane\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-paperclip\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-parachute-box\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-paragraph\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-parking\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-passport\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-pastafarianism\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-paste\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-pause-circle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-pause\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-paw\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-peace\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-pen-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-pen-fancy\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-pen-nib\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-pen-square\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-pen\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-pencil-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-pencil-ruler\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-people-arrows\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-people-carry\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-pepper-hot\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-percent\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-percentage\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-person-booth\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-phone-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-phone-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-phone-square-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-phone-square\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-phone-volume\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-phone\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-photo-video\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-piggy-bank\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-pills\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-pizza-slice\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-place-of-worship\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-plane-arrival\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-plane-departure\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-plane-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-plane\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-play-circle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-play\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-plug\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-plus-circle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-plus-square\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-plus\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-podcast\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-poll-h\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-poll\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-poo-storm\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-poo\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-poop\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-portrait\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-pound-sign\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-power-off\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-pray\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-praying-hands\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-prescription-bottle-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-prescription-bottle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-prescription\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-print\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-procedures\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-project-diagram\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-pump-medical\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-pump-soap\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-puzzle-piece\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-qrcode\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-question-circle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-question\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-quidditch\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-quote-left\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-quote-right\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-quran\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-radiation-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-radiation\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-rainbow\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-random\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-receipt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-record-vinyl\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-recycle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-redo-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-redo\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-registered\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-remove-format\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-reply-all\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-reply\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-republican\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-restroom\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-retweet\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-ribbon\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-ring\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-road\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-robot\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-rocket\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-route\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-rss-square\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-rss\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-ruble-sign\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-ruler-combined\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-ruler-horizontal\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-ruler-vertical\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-ruler\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-running\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-rupee-sign\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sad-cry\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sad-tear\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-satellite-dish\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-satellite\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-save\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-school\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-screwdriver\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-scroll\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sd-card\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-search-dollar\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-search-location\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-search-minus\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-search-plus\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-search\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-seedling\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-server\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-shapes\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-share-alt-square\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-share-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-share-square\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-share\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-shekel-sign\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-shield-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-shield-virus\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-ship\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-shipping-fast\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-shoe-prints\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-shopping-bag\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-shopping-basket\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-shopping-cart\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-shower\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-shuttle-van\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sign-in-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sign-language\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sign-out-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sign\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-signal\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-signature\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sim-card\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sink\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sitemap\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-skating\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-skiing-nordic\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-skiing\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-skull-crossbones\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-skull\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sleigh\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sliders-h\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-smile-beam\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-smile-wink\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-smile\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-smog\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-smoking-ban\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-smoking\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sms\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-snowboarding\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-snowflake\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-snowman\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-snowplow\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-soap\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-socks\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-solar-panel\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sort-alpha-down-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sort-alpha-down\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sort-alpha-up-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sort-alpha-up\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sort-amount-down-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sort-amount-down\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sort-amount-up-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sort-amount-up\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sort-down\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sort-numeric-down-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sort-numeric-down\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sort-numeric-up-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sort-numeric-up\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sort-up\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sort\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-spa\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-space-shuttle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-spell-check\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-spider\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-spinner\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-splotch\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-spray-can\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-square-full\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-square-root-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-square\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-stamp\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-star-and-crescent\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-star-half-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-star-half\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-star-of-david\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-star-of-life\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-star\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-step-backward\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-step-forward\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-stethoscope\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sticky-note\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-stop-circle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-stop\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-stopwatch-20\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-stopwatch\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-store-alt-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-store-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-store-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-store\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-stream\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-street-view\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-strikethrough\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-stroopwafel\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-subscript\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-subway\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-suitcase-rolling\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-suitcase\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sun\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-superscript\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-surprise\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-swatchbook\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-swimmer\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-swimming-pool\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-synagogue\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sync-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-sync\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-syringe\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-table-tennis\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-table\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tablet-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tablet\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tablets\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tachometer-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tag\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tags\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tape\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tasks\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-taxi\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-teeth-open\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-teeth\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-temperature-high\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-temperature-low\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tenge\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-terminal\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-text-height\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-text-width\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-th-large\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-th-list\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-th\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-theater-masks\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-thermometer-empty\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-thermometer-full\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-thermometer-half\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-thermometer-quarter\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-thermometer-three-quarters\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-thermometer\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-thumbs-down\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-thumbs-up\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-thumbtack\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-ticket-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-times-circle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-times\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tint-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tint\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tired\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-toggle-off\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-toggle-on\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-toilet-paper-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-toilet-paper\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-toilet\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-toolbox\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tools\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tooth\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-torah\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-torii-gate\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tractor\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-trademark\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-traffic-light\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-trailer\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-train\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tram\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-transgender-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-transgender\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-trash-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-trash-restore-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-trash-restore\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-trash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tree\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-trophy\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-truck-loading\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-truck-monster\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-truck-moving\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-truck-pickup\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-truck\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tshirt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tty\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-tv\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-umbrella-beach\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-umbrella\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-underline\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-undo-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-undo\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-universal-access\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-university\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-unlink\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-unlock-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-unlock\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-upload\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-alt-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-astronaut\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-check\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-circle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-clock\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-cog\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-edit\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-friends\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-graduate\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-injured\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-lock\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-md\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-minus\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-ninja\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-nurse\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-plus\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-secret\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-shield\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-tag\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-tie\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user-times\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-user\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-users-cog\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-users-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-users\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-utensil-spoon\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-utensils\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-vector-square\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-venus-double\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-venus-mars\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-venus\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-vest-patches\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-vest\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-vial\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-vials\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-video-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-video\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-vihara\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-virus-slash\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-virus\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-viruses\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-voicemail\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-volleyball-ball\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-volume-down\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-volume-mute\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-volume-off\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-volume-up\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-vote-yea\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-vr-cardboard\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-walking\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-wallet\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-warehouse\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-water\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-wave-square\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-weight-hanging\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-weight\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-wheelchair\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-wifi\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-wind\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-window-close\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-window-maximize\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-window-minimize\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-window-restore\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-wine-bottle\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-wine-glass-alt\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-wine-glass\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-won-sign\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-wrench\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-x-ray\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-yen-sign\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-yin-yang\",\"types\":[\"solid\",\"regular\"]},{\"key\":\"fa-500px\",\"types\":[\"brands\"]},{\"key\":\"fa-accessible-icon\",\"types\":[\"brands\"]},{\"key\":\"fa-accusoft\",\"types\":[\"brands\"]},{\"key\":\"fa-acquisitions-incorporated\",\"types\":[\"brands\"]},{\"key\":\"fa-adn\",\"types\":[\"brands\"]},{\"key\":\"fa-adversal\",\"types\":[\"brands\"]},{\"key\":\"fa-affiliatetheme\",\"types\":[\"brands\"]},{\"key\":\"fa-airbnb\",\"types\":[\"brands\"]},{\"key\":\"fa-algolia\",\"types\":[\"brands\"]},{\"key\":\"fa-alipay\",\"types\":[\"brands\"]},{\"key\":\"fa-amazon-pay\",\"types\":[\"brands\"]},{\"key\":\"fa-amazon\",\"types\":[\"brands\"]},{\"key\":\"fa-amilia\",\"types\":[\"brands\"]},{\"key\":\"fa-android\",\"types\":[\"brands\"]},{\"key\":\"fa-angellist\",\"types\":[\"brands\"]},{\"key\":\"fa-angrycreative\",\"types\":[\"brands\"]},{\"key\":\"fa-angular\",\"types\":[\"brands\"]},{\"key\":\"fa-app-store-ios\",\"types\":[\"brands\"]},{\"key\":\"fa-app-store\",\"types\":[\"brands\"]},{\"key\":\"fa-apper\",\"types\":[\"brands\"]},{\"key\":\"fa-apple-pay\",\"types\":[\"brands\"]},{\"key\":\"fa-apple\",\"types\":[\"brands\"]},{\"key\":\"fa-artstation\",\"types\":[\"brands\"]},{\"key\":\"fa-asymmetrik\",\"types\":[\"brands\"]},{\"key\":\"fa-atlassian\",\"types\":[\"brands\"]},{\"key\":\"fa-audible\",\"types\":[\"brands\"]},{\"key\":\"fa-autoprefixer\",\"types\":[\"brands\"]},{\"key\":\"fa-avianex\",\"types\":[\"brands\"]},{\"key\":\"fa-aviato\",\"types\":[\"brands\"]},{\"key\":\"fa-aws\",\"types\":[\"brands\"]},{\"key\":\"fa-bandcamp\",\"types\":[\"brands\"]},{\"key\":\"fa-battle-net\",\"types\":[\"brands\"]},{\"key\":\"fa-behance-square\",\"types\":[\"brands\"]},{\"key\":\"fa-behance\",\"types\":[\"brands\"]},{\"key\":\"fa-bimobject\",\"types\":[\"brands\"]},{\"key\":\"fa-bitbucket\",\"types\":[\"brands\"]},{\"key\":\"fa-bitcoin\",\"types\":[\"brands\"]},{\"key\":\"fa-bity\",\"types\":[\"brands\"]},{\"key\":\"fa-black-tie\",\"types\":[\"brands\"]},{\"key\":\"fa-blackberry\",\"types\":[\"brands\"]},{\"key\":\"fa-blogger-b\",\"types\":[\"brands\"]},{\"key\":\"fa-blogger\",\"types\":[\"brands\"]},{\"key\":\"fa-bluetooth-b\",\"types\":[\"brands\"]},{\"key\":\"fa-bluetooth\",\"types\":[\"brands\"]},{\"key\":\"fa-bootstrap\",\"types\":[\"brands\"]},{\"key\":\"fa-btc\",\"types\":[\"brands\"]},{\"key\":\"fa-buffer\",\"types\":[\"brands\"]},{\"key\":\"fa-buromobelexperte\",\"types\":[\"brands\"]},{\"key\":\"fa-buy-n-large\",\"types\":[\"brands\"]},{\"key\":\"fa-buysellads\",\"types\":[\"brands\"]},{\"key\":\"fa-canadian-maple-leaf\",\"types\":[\"brands\"]},{\"key\":\"fa-cc-amazon-pay\",\"types\":[\"brands\"]},{\"key\":\"fa-cc-amex\",\"types\":[\"brands\"]},{\"key\":\"fa-cc-apple-pay\",\"types\":[\"brands\"]},{\"key\":\"fa-cc-diners-club\",\"types\":[\"brands\"]},{\"key\":\"fa-cc-discover\",\"types\":[\"brands\"]},{\"key\":\"fa-cc-jcb\",\"types\":[\"brands\"]},{\"key\":\"fa-cc-mastercard\",\"types\":[\"brands\"]},{\"key\":\"fa-cc-paypal\",\"types\":[\"brands\"]},{\"key\":\"fa-cc-stripe\",\"types\":[\"brands\"]},{\"key\":\"fa-cc-visa\",\"types\":[\"brands\"]},{\"key\":\"fa-centercode\",\"types\":[\"brands\"]},{\"key\":\"fa-centos\",\"types\":[\"brands\"]},{\"key\":\"fa-chrome\",\"types\":[\"brands\"]},{\"key\":\"fa-chromecast\",\"types\":[\"brands\"]},{\"key\":\"fa-cloudflare\",\"types\":[\"brands\"]},{\"key\":\"fa-cloudscale\",\"types\":[\"brands\"]},{\"key\":\"fa-cloudsmith\",\"types\":[\"brands\"]},{\"key\":\"fa-cloudversify\",\"types\":[\"brands\"]},{\"key\":\"fa-codepen\",\"types\":[\"brands\"]},{\"key\":\"fa-codiepie\",\"types\":[\"brands\"]},{\"key\":\"fa-confluence\",\"types\":[\"brands\"]},{\"key\":\"fa-connectdevelop\",\"types\":[\"brands\"]},{\"key\":\"fa-contao\",\"types\":[\"brands\"]},{\"key\":\"fa-cotton-bureau\",\"types\":[\"brands\"]},{\"key\":\"fa-cpanel\",\"types\":[\"brands\"]},{\"key\":\"fa-creative-commons-by\",\"types\":[\"brands\"]},{\"key\":\"fa-creative-commons-nc-eu\",\"types\":[\"brands\"]},{\"key\":\"fa-creative-commons-nc-jp\",\"types\":[\"brands\"]},{\"key\":\"fa-creative-commons-nc\",\"types\":[\"brands\"]},{\"key\":\"fa-creative-commons-nd\",\"types\":[\"brands\"]},{\"key\":\"fa-creative-commons-pd-alt\",\"types\":[\"brands\"]},{\"key\":\"fa-creative-commons-pd\",\"types\":[\"brands\"]},{\"key\":\"fa-creative-commons-remix\",\"types\":[\"brands\"]},{\"key\":\"fa-creative-commons-sa\",\"types\":[\"brands\"]},{\"key\":\"fa-creative-commons-sampling-plus\",\"types\":[\"brands\"]},{\"key\":\"fa-creative-commons-sampling\",\"types\":[\"brands\"]},{\"key\":\"fa-creative-commons-share\",\"types\":[\"brands\"]},{\"key\":\"fa-creative-commons-zero\",\"types\":[\"brands\"]},{\"key\":\"fa-creative-commons\",\"types\":[\"brands\"]},{\"key\":\"fa-critical-role\",\"types\":[\"brands\"]},{\"key\":\"fa-css3-alt\",\"types\":[\"brands\"]},{\"key\":\"fa-css3\",\"types\":[\"brands\"]},{\"key\":\"fa-cuttlefish\",\"types\":[\"brands\"]},{\"key\":\"fa-d-and-d-beyond\",\"types\":[\"brands\"]},{\"key\":\"fa-d-and-d\",\"types\":[\"brands\"]},{\"key\":\"fa-dailymotion\",\"types\":[\"brands\"]},{\"key\":\"fa-dashcube\",\"types\":[\"brands\"]},{\"key\":\"fa-deezer\",\"types\":[\"brands\"]},{\"key\":\"fa-delicious\",\"types\":[\"brands\"]},{\"key\":\"fa-deploydog\",\"types\":[\"brands\"]},{\"key\":\"fa-deskpro\",\"types\":[\"brands\"]},{\"key\":\"fa-dev\",\"types\":[\"brands\"]},{\"key\":\"fa-deviantart\",\"types\":[\"brands\"]},{\"key\":\"fa-dhl\",\"types\":[\"brands\"]},{\"key\":\"fa-diaspora\",\"types\":[\"brands\"]},{\"key\":\"fa-digg\",\"types\":[\"brands\"]},{\"key\":\"fa-digital-ocean\",\"types\":[\"brands\"]},{\"key\":\"fa-discord\",\"types\":[\"brands\"]},{\"key\":\"fa-discourse\",\"types\":[\"brands\"]},{\"key\":\"fa-dochub\",\"types\":[\"brands\"]},{\"key\":\"fa-docker\",\"types\":[\"brands\"]},{\"key\":\"fa-draft2digital\",\"types\":[\"brands\"]},{\"key\":\"fa-dribbble-square\",\"types\":[\"brands\"]},{\"key\":\"fa-dribbble\",\"types\":[\"brands\"]},{\"key\":\"fa-dropbox\",\"types\":[\"brands\"]},{\"key\":\"fa-drupal\",\"types\":[\"brands\"]},{\"key\":\"fa-dyalog\",\"types\":[\"brands\"]},{\"key\":\"fa-earlybirds\",\"types\":[\"brands\"]},{\"key\":\"fa-ebay\",\"types\":[\"brands\"]},{\"key\":\"fa-edge-legacy\",\"types\":[\"brands\"]},{\"key\":\"fa-edge\",\"types\":[\"brands\"]},{\"key\":\"fa-elementor\",\"types\":[\"brands\"]},{\"key\":\"fa-ello\",\"types\":[\"brands\"]},{\"key\":\"fa-ember\",\"types\":[\"brands\"]},{\"key\":\"fa-empire\",\"types\":[\"brands\"]},{\"key\":\"fa-envira\",\"types\":[\"brands\"]},{\"key\":\"fa-erlang\",\"types\":[\"brands\"]},{\"key\":\"fa-ethereum\",\"types\":[\"brands\"]},{\"key\":\"fa-etsy\",\"types\":[\"brands\"]},{\"key\":\"fa-evernote\",\"types\":[\"brands\"]},{\"key\":\"fa-expeditedssl\",\"types\":[\"brands\"]},{\"key\":\"fa-facebook-f\",\"types\":[\"brands\"]},{\"key\":\"fa-facebook-messenger\",\"types\":[\"brands\"]},{\"key\":\"fa-facebook-square\",\"types\":[\"brands\"]},{\"key\":\"fa-facebook\",\"types\":[\"brands\"]},{\"key\":\"fa-fantasy-flight-games\",\"types\":[\"brands\"]},{\"key\":\"fa-fedex\",\"types\":[\"brands\"]},{\"key\":\"fa-fedora\",\"types\":[\"brands\"]},{\"key\":\"fa-figma\",\"types\":[\"brands\"]},{\"key\":\"fa-firefox-browser\",\"types\":[\"brands\"]},{\"key\":\"fa-firefox\",\"types\":[\"brands\"]},{\"key\":\"fa-first-order-alt\",\"types\":[\"brands\"]},{\"key\":\"fa-first-order\",\"types\":[\"brands\"]},{\"key\":\"fa-firstdraft\",\"types\":[\"brands\"]},{\"key\":\"fa-flickr\",\"types\":[\"brands\"]},{\"key\":\"fa-flipboard\",\"types\":[\"brands\"]},{\"key\":\"fa-fly\",\"types\":[\"brands\"]},{\"key\":\"fa-font-awesome-alt\",\"types\":[\"brands\"]},{\"key\":\"fa-font-awesome-flag\",\"types\":[\"brands\"]},{\"key\":\"fa-font-awesome-logo-full\",\"types\":[\"brands\"]},{\"key\":\"fa-font-awesome\",\"types\":[\"brands\"]},{\"key\":\"fa-fonticons-fi\",\"types\":[\"brands\"]},{\"key\":\"fa-fonticons\",\"types\":[\"brands\"]},{\"key\":\"fa-fort-awesome-alt\",\"types\":[\"brands\"]},{\"key\":\"fa-fort-awesome\",\"types\":[\"brands\"]},{\"key\":\"fa-forumbee\",\"types\":[\"brands\"]},{\"key\":\"fa-foursquare\",\"types\":[\"brands\"]},{\"key\":\"fa-free-code-camp\",\"types\":[\"brands\"]},{\"key\":\"fa-freebsd\",\"types\":[\"brands\"]},{\"key\":\"fa-fulcrum\",\"types\":[\"brands\"]},{\"key\":\"fa-galactic-republic\",\"types\":[\"brands\"]},{\"key\":\"fa-galactic-senate\",\"types\":[\"brands\"]},{\"key\":\"fa-get-pocket\",\"types\":[\"brands\"]},{\"key\":\"fa-gg-circle\",\"types\":[\"brands\"]},{\"key\":\"fa-gg\",\"types\":[\"brands\"]},{\"key\":\"fa-git-alt\",\"types\":[\"brands\"]},{\"key\":\"fa-git-square\",\"types\":[\"brands\"]},{\"key\":\"fa-git\",\"types\":[\"brands\"]},{\"key\":\"fa-github-alt\",\"types\":[\"brands\"]},{\"key\":\"fa-github-square\",\"types\":[\"brands\"]},{\"key\":\"fa-github\",\"types\":[\"brands\"]},{\"key\":\"fa-gitkraken\",\"types\":[\"brands\"]},{\"key\":\"fa-gitlab\",\"types\":[\"brands\"]},{\"key\":\"fa-gitter\",\"types\":[\"brands\"]},{\"key\":\"fa-glide-g\",\"types\":[\"brands\"]},{\"key\":\"fa-glide\",\"types\":[\"brands\"]},{\"key\":\"fa-gofore\",\"types\":[\"brands\"]},{\"key\":\"fa-goodreads-g\",\"types\":[\"brands\"]},{\"key\":\"fa-goodreads\",\"types\":[\"brands\"]},{\"key\":\"fa-google-drive\",\"types\":[\"brands\"]},{\"key\":\"fa-google-pay\",\"types\":[\"brands\"]},{\"key\":\"fa-google-play\",\"types\":[\"brands\"]},{\"key\":\"fa-google-plus-g\",\"types\":[\"brands\"]},{\"key\":\"fa-google-plus-square\",\"types\":[\"brands\"]},{\"key\":\"fa-google-plus\",\"types\":[\"brands\"]},{\"key\":\"fa-google-wallet\",\"types\":[\"brands\"]},{\"key\":\"fa-google\",\"types\":[\"brands\"]},{\"key\":\"fa-gratipay\",\"types\":[\"brands\"]},{\"key\":\"fa-grav\",\"types\":[\"brands\"]},{\"key\":\"fa-gripfire\",\"types\":[\"brands\"]},{\"key\":\"fa-grunt\",\"types\":[\"brands\"]},{\"key\":\"fa-guilded\",\"types\":[\"brands\"]},{\"key\":\"fa-gulp\",\"types\":[\"brands\"]},{\"key\":\"fa-hacker-news-square\",\"types\":[\"brands\"]},{\"key\":\"fa-hacker-news\",\"types\":[\"brands\"]},{\"key\":\"fa-hackerrank\",\"types\":[\"brands\"]},{\"key\":\"fa-hips\",\"types\":[\"brands\"]},{\"key\":\"fa-hire-a-helper\",\"types\":[\"brands\"]},{\"key\":\"fa-hive\",\"types\":[\"brands\"]},{\"key\":\"fa-hooli\",\"types\":[\"brands\"]},{\"key\":\"fa-hornbill\",\"types\":[\"brands\"]},{\"key\":\"fa-hotjar\",\"types\":[\"brands\"]},{\"key\":\"fa-houzz\",\"types\":[\"brands\"]},{\"key\":\"fa-html5\",\"types\":[\"brands\"]},{\"key\":\"fa-hubspot\",\"types\":[\"brands\"]},{\"key\":\"fa-ideal\",\"types\":[\"brands\"]},{\"key\":\"fa-imdb\",\"types\":[\"brands\"]},{\"key\":\"fa-innosoft\",\"types\":[\"brands\"]},{\"key\":\"fa-instagram-square\",\"types\":[\"brands\"]},{\"key\":\"fa-instagram\",\"types\":[\"brands\"]},{\"key\":\"fa-instalod\",\"types\":[\"brands\"]},{\"key\":\"fa-intercom\",\"types\":[\"brands\"]},{\"key\":\"fa-internet-explorer\",\"types\":[\"brands\"]},{\"key\":\"fa-invision\",\"types\":[\"brands\"]},{\"key\":\"fa-ioxhost\",\"types\":[\"brands\"]},{\"key\":\"fa-itch-io\",\"types\":[\"brands\"]},{\"key\":\"fa-itunes-note\",\"types\":[\"brands\"]},{\"key\":\"fa-itunes\",\"types\":[\"brands\"]},{\"key\":\"fa-java\",\"types\":[\"brands\"]},{\"key\":\"fa-jedi-order\",\"types\":[\"brands\"]},{\"key\":\"fa-jenkins\",\"types\":[\"brands\"]},{\"key\":\"fa-jira\",\"types\":[\"brands\"]},{\"key\":\"fa-joget\",\"types\":[\"brands\"]},{\"key\":\"fa-joomla\",\"types\":[\"brands\"]},{\"key\":\"fa-js-square\",\"types\":[\"brands\"]},{\"key\":\"fa-js\",\"types\":[\"brands\"]},{\"key\":\"fa-jsfiddle\",\"types\":[\"brands\"]},{\"key\":\"fa-kaggle\",\"types\":[\"brands\"]},{\"key\":\"fa-keybase\",\"types\":[\"brands\"]},{\"key\":\"fa-keycdn\",\"types\":[\"brands\"]},{\"key\":\"fa-kickstarter-k\",\"types\":[\"brands\"]},{\"key\":\"fa-kickstarter\",\"types\":[\"brands\"]},{\"key\":\"fa-korvue\",\"types\":[\"brands\"]},{\"key\":\"fa-laravel\",\"types\":[\"brands\"]},{\"key\":\"fa-lastfm-square\",\"types\":[\"brands\"]},{\"key\":\"fa-lastfm\",\"types\":[\"brands\"]},{\"key\":\"fa-leanpub\",\"types\":[\"brands\"]},{\"key\":\"fa-less\",\"types\":[\"brands\"]},{\"key\":\"fa-line\",\"types\":[\"brands\"]},{\"key\":\"fa-linkedin-in\",\"types\":[\"brands\"]},{\"key\":\"fa-linkedin\",\"types\":[\"brands\"]},{\"key\":\"fa-linode\",\"types\":[\"brands\"]},{\"key\":\"fa-linux\",\"types\":[\"brands\"]},{\"key\":\"fa-lyft\",\"types\":[\"brands\"]},{\"key\":\"fa-magento\",\"types\":[\"brands\"]},{\"key\":\"fa-mailchimp\",\"types\":[\"brands\"]},{\"key\":\"fa-mandalorian\",\"types\":[\"brands\"]},{\"key\":\"fa-markdown\",\"types\":[\"brands\"]},{\"key\":\"fa-mastodon\",\"types\":[\"brands\"]},{\"key\":\"fa-maxcdn\",\"types\":[\"brands\"]},{\"key\":\"fa-mdb\",\"types\":[\"brands\"]},{\"key\":\"fa-medapps\",\"types\":[\"brands\"]},{\"key\":\"fa-medium-m\",\"types\":[\"brands\"]},{\"key\":\"fa-medium\",\"types\":[\"brands\"]},{\"key\":\"fa-medrt\",\"types\":[\"brands\"]},{\"key\":\"fa-meetup\",\"types\":[\"brands\"]},{\"key\":\"fa-megaport\",\"types\":[\"brands\"]},{\"key\":\"fa-mendeley\",\"types\":[\"brands\"]},{\"key\":\"fa-microblog\",\"types\":[\"brands\"]},{\"key\":\"fa-microsoft\",\"types\":[\"brands\"]},{\"key\":\"fa-mix\",\"types\":[\"brands\"]},{\"key\":\"fa-mixcloud\",\"types\":[\"brands\"]},{\"key\":\"fa-mixer\",\"types\":[\"brands\"]},{\"key\":\"fa-mizuni\",\"types\":[\"brands\"]},{\"key\":\"fa-modx\",\"types\":[\"brands\"]},{\"key\":\"fa-monero\",\"types\":[\"brands\"]},{\"key\":\"fa-napster\",\"types\":[\"brands\"]},{\"key\":\"fa-neos\",\"types\":[\"brands\"]},{\"key\":\"fa-nimblr\",\"types\":[\"brands\"]},{\"key\":\"fa-node-js\",\"types\":[\"brands\"]},{\"key\":\"fa-node\",\"types\":[\"brands\"]},{\"key\":\"fa-npm\",\"types\":[\"brands\"]},{\"key\":\"fa-ns8\",\"types\":[\"brands\"]},{\"key\":\"fa-nutritionix\",\"types\":[\"brands\"]},{\"key\":\"fa-octopus-deploy\",\"types\":[\"brands\"]},{\"key\":\"fa-odnoklassniki-square\",\"types\":[\"brands\"]},{\"key\":\"fa-odnoklassniki\",\"types\":[\"brands\"]},{\"key\":\"fa-old-republic\",\"types\":[\"brands\"]},{\"key\":\"fa-opencart\",\"types\":[\"brands\"]},{\"key\":\"fa-openid\",\"types\":[\"brands\"]},{\"key\":\"fa-opera\",\"types\":[\"brands\"]},{\"key\":\"fa-optin-monster\",\"types\":[\"brands\"]},{\"key\":\"fa-orcid\",\"types\":[\"brands\"]},{\"key\":\"fa-osi\",\"types\":[\"brands\"]},{\"key\":\"fa-page4\",\"types\":[\"brands\"]},{\"key\":\"fa-pagelines\",\"types\":[\"brands\"]},{\"key\":\"fa-palfed\",\"types\":[\"brands\"]},{\"key\":\"fa-patreon\",\"types\":[\"brands\"]},{\"key\":\"fa-paypal\",\"types\":[\"brands\"]},{\"key\":\"fa-penny-arcade\",\"types\":[\"brands\"]},{\"key\":\"fa-perbyte\",\"types\":[\"brands\"]},{\"key\":\"fa-periscope\",\"types\":[\"brands\"]},{\"key\":\"fa-phabricator\",\"types\":[\"brands\"]},{\"key\":\"fa-phoenix-framework\",\"types\":[\"brands\"]},{\"key\":\"fa-phoenix-squadron\",\"types\":[\"brands\"]},{\"key\":\"fa-php\",\"types\":[\"brands\"]},{\"key\":\"fa-pied-piper-alt\",\"types\":[\"brands\"]},{\"key\":\"fa-pied-piper-hat\",\"types\":[\"brands\"]},{\"key\":\"fa-pied-piper-pp\",\"types\":[\"brands\"]},{\"key\":\"fa-pied-piper-square\",\"types\":[\"brands\"]},{\"key\":\"fa-pied-piper\",\"types\":[\"brands\"]},{\"key\":\"fa-pinterest-p\",\"types\":[\"brands\"]},{\"key\":\"fa-pinterest-square\",\"types\":[\"brands\"]},{\"key\":\"fa-pinterest\",\"types\":[\"brands\"]},{\"key\":\"fa-playstation\",\"types\":[\"brands\"]},{\"key\":\"fa-product-hunt\",\"types\":[\"brands\"]},{\"key\":\"fa-pushed\",\"types\":[\"brands\"]},{\"key\":\"fa-python\",\"types\":[\"brands\"]},{\"key\":\"fa-qq\",\"types\":[\"brands\"]},{\"key\":\"fa-quinscape\",\"types\":[\"brands\"]},{\"key\":\"fa-quora\",\"types\":[\"brands\"]},{\"key\":\"fa-r-project\",\"types\":[\"brands\"]},{\"key\":\"fa-raspberry-pi\",\"types\":[\"brands\"]},{\"key\":\"fa-ravelry\",\"types\":[\"brands\"]},{\"key\":\"fa-react\",\"types\":[\"brands\"]},{\"key\":\"fa-reacteurope\",\"types\":[\"brands\"]},{\"key\":\"fa-readme\",\"types\":[\"brands\"]},{\"key\":\"fa-rebel\",\"types\":[\"brands\"]},{\"key\":\"fa-red-river\",\"types\":[\"brands\"]},{\"key\":\"fa-reddit-alien\",\"types\":[\"brands\"]},{\"key\":\"fa-reddit-square\",\"types\":[\"brands\"]},{\"key\":\"fa-reddit\",\"types\":[\"brands\"]},{\"key\":\"fa-redhat\",\"types\":[\"brands\"]},{\"key\":\"fa-renren\",\"types\":[\"brands\"]},{\"key\":\"fa-replyd\",\"types\":[\"brands\"]},{\"key\":\"fa-researchgate\",\"types\":[\"brands\"]},{\"key\":\"fa-resolving\",\"types\":[\"brands\"]},{\"key\":\"fa-rev\",\"types\":[\"brands\"]},{\"key\":\"fa-rocketchat\",\"types\":[\"brands\"]},{\"key\":\"fa-rockrms\",\"types\":[\"brands\"]},{\"key\":\"fa-rust\",\"types\":[\"brands\"]},{\"key\":\"fa-safari\",\"types\":[\"brands\"]},{\"key\":\"fa-salesforce\",\"types\":[\"brands\"]},{\"key\":\"fa-sass\",\"types\":[\"brands\"]},{\"key\":\"fa-schlix\",\"types\":[\"brands\"]},{\"key\":\"fa-scribd\",\"types\":[\"brands\"]},{\"key\":\"fa-searchengin\",\"types\":[\"brands\"]},{\"key\":\"fa-sellcast\",\"types\":[\"brands\"]},{\"key\":\"fa-sellsy\",\"types\":[\"brands\"]},{\"key\":\"fa-servicestack\",\"types\":[\"brands\"]},{\"key\":\"fa-shirtsinbulk\",\"types\":[\"brands\"]},{\"key\":\"fa-shopify\",\"types\":[\"brands\"]},{\"key\":\"fa-shopware\",\"types\":[\"brands\"]},{\"key\":\"fa-simplybuilt\",\"types\":[\"brands\"]},{\"key\":\"fa-sistrix\",\"types\":[\"brands\"]},{\"key\":\"fa-sith\",\"types\":[\"brands\"]},{\"key\":\"fa-sketch\",\"types\":[\"brands\"]},{\"key\":\"fa-skyatlas\",\"types\":[\"brands\"]},{\"key\":\"fa-skype\",\"types\":[\"brands\"]},{\"key\":\"fa-slack-hash\",\"types\":[\"brands\"]},{\"key\":\"fa-slack\",\"types\":[\"brands\"]},{\"key\":\"fa-slideshare\",\"types\":[\"brands\"]},{\"key\":\"fa-snapchat-ghost\",\"types\":[\"brands\"]},{\"key\":\"fa-snapchat-square\",\"types\":[\"brands\"]},{\"key\":\"fa-snapchat\",\"types\":[\"brands\"]},{\"key\":\"fa-soundcloud\",\"types\":[\"brands\"]},{\"key\":\"fa-sourcetree\",\"types\":[\"brands\"]},{\"key\":\"fa-speakap\",\"types\":[\"brands\"]},{\"key\":\"fa-speaker-deck\",\"types\":[\"brands\"]},{\"key\":\"fa-spotify\",\"types\":[\"brands\"]},{\"key\":\"fa-squarespace\",\"types\":[\"brands\"]},{\"key\":\"fa-stack-exchange\",\"types\":[\"brands\"]},{\"key\":\"fa-stack-overflow\",\"types\":[\"brands\"]},{\"key\":\"fa-stackpath\",\"types\":[\"brands\"]},{\"key\":\"fa-staylinked\",\"types\":[\"brands\"]},{\"key\":\"fa-steam-square\",\"types\":[\"brands\"]},{\"key\":\"fa-steam-symbol\",\"types\":[\"brands\"]},{\"key\":\"fa-steam\",\"types\":[\"brands\"]},{\"key\":\"fa-sticker-mule\",\"types\":[\"brands\"]},{\"key\":\"fa-strava\",\"types\":[\"brands\"]},{\"key\":\"fa-stripe-s\",\"types\":[\"brands\"]},{\"key\":\"fa-stripe\",\"types\":[\"brands\"]},{\"key\":\"fa-studiovinari\",\"types\":[\"brands\"]},{\"key\":\"fa-stumbleupon-circle\",\"types\":[\"brands\"]},{\"key\":\"fa-stumbleupon\",\"types\":[\"brands\"]},{\"key\":\"fa-superpowers\",\"types\":[\"brands\"]},{\"key\":\"fa-supple\",\"types\":[\"brands\"]},{\"key\":\"fa-suse\",\"types\":[\"brands\"]},{\"key\":\"fa-swift\",\"types\":[\"brands\"]},{\"key\":\"fa-symfony\",\"types\":[\"brands\"]},{\"key\":\"fa-teamspeak\",\"types\":[\"brands\"]},{\"key\":\"fa-telegram-plane\",\"types\":[\"brands\"]},{\"key\":\"fa-telegram\",\"types\":[\"brands\"]},{\"key\":\"fa-tencent-weibo\",\"types\":[\"brands\"]},{\"key\":\"fa-the-red-yeti\",\"types\":[\"brands\"]},{\"key\":\"fa-themeco\",\"types\":[\"brands\"]},{\"key\":\"fa-themeisle\",\"types\":[\"brands\"]},{\"key\":\"fa-think-peaks\",\"types\":[\"brands\"]},{\"key\":\"fa-tiktok\",\"types\":[\"brands\"]},{\"key\":\"fa-trade-federation\",\"types\":[\"brands\"]},{\"key\":\"fa-trello\",\"types\":[\"brands\"]},{\"key\":\"fa-tumblr-square\",\"types\":[\"brands\"]},{\"key\":\"fa-tumblr\",\"types\":[\"brands\"]},{\"key\":\"fa-twitch\",\"types\":[\"brands\"]},{\"key\":\"fa-twitter-square\",\"types\":[\"brands\"]},{\"key\":\"fa-twitter\",\"types\":[\"brands\"]},{\"key\":\"fa-typo3\",\"types\":[\"brands\"]},{\"key\":\"fa-uber\",\"types\":[\"brands\"]},{\"key\":\"fa-ubuntu\",\"types\":[\"brands\"]},{\"key\":\"fa-uikit\",\"types\":[\"brands\"]},{\"key\":\"fa-umbraco\",\"types\":[\"brands\"]},{\"key\":\"fa-uncharted\",\"types\":[\"brands\"]},{\"key\":\"fa-uniregistry\",\"types\":[\"brands\"]},{\"key\":\"fa-unity\",\"types\":[\"brands\"]},{\"key\":\"fa-unsplash\",\"types\":[\"brands\"]},{\"key\":\"fa-untappd\",\"types\":[\"brands\"]},{\"key\":\"fa-ups\",\"types\":[\"brands\"]},{\"key\":\"fa-usb\",\"types\":[\"brands\"]},{\"key\":\"fa-usps\",\"types\":[\"brands\"]},{\"key\":\"fa-ussunnah\",\"types\":[\"brands\"]},{\"key\":\"fa-vaadin\",\"types\":[\"brands\"]},{\"key\":\"fa-viacoin\",\"types\":[\"brands\"]},{\"key\":\"fa-viadeo-square\",\"types\":[\"brands\"]},{\"key\":\"fa-viadeo\",\"types\":[\"brands\"]},{\"key\":\"fa-viber\",\"types\":[\"brands\"]},{\"key\":\"fa-vimeo-square\",\"types\":[\"brands\"]},{\"key\":\"fa-vimeo-v\",\"types\":[\"brands\"]},{\"key\":\"fa-vimeo\",\"types\":[\"brands\"]},{\"key\":\"fa-vine\",\"types\":[\"brands\"]},{\"key\":\"fa-vk\",\"types\":[\"brands\"]},{\"key\":\"fa-vnv\",\"types\":[\"brands\"]},{\"key\":\"fa-vuejs\",\"types\":[\"brands\"]},{\"key\":\"fa-watchman-monitoring\",\"types\":[\"brands\"]},{\"key\":\"fa-waze\",\"types\":[\"brands\"]},{\"key\":\"fa-weebly\",\"types\":[\"brands\"]},{\"key\":\"fa-weibo\",\"types\":[\"brands\"]},{\"key\":\"fa-weixin\",\"types\":[\"brands\"]},{\"key\":\"fa-whatsapp-square\",\"types\":[\"brands\"]},{\"key\":\"fa-whatsapp\",\"types\":[\"brands\"]},{\"key\":\"fa-whmcs\",\"types\":[\"brands\"]},{\"key\":\"fa-wikipedia-w\",\"types\":[\"brands\"]},{\"key\":\"fa-windows\",\"types\":[\"brands\"]},{\"key\":\"fa-wix\",\"types\":[\"brands\"]},{\"key\":\"fa-wizards-of-the-coast\",\"types\":[\"brands\"]},{\"key\":\"fa-wodu\",\"types\":[\"brands\"]},{\"key\":\"fa-wolf-pack-battalion\",\"types\":[\"brands\"]},{\"key\":\"fa-wordpress-simple\",\"types\":[\"brands\"]},{\"key\":\"fa-wordpress\",\"types\":[\"brands\"]},{\"key\":\"fa-wpbeginner\",\"types\":[\"brands\"]},{\"key\":\"fa-wpexplorer\",\"types\":[\"brands\"]},{\"key\":\"fa-wpforms\",\"types\":[\"brands\"]},{\"key\":\"fa-wpressr\",\"types\":[\"brands\"]},{\"key\":\"fa-xbox\",\"types\":[\"brands\"]},{\"key\":\"fa-xing-square\",\"types\":[\"brands\"]},{\"key\":\"fa-xing\",\"types\":[\"brands\"]},{\"key\":\"fa-y-combinator\",\"types\":[\"brands\"]},{\"key\":\"fa-yahoo\",\"types\":[\"brands\"]},{\"key\":\"fa-yammer\",\"types\":[\"brands\"]},{\"key\":\"fa-yandex-international\",\"types\":[\"brands\"]},{\"key\":\"fa-yandex\",\"types\":[\"brands\"]},{\"key\":\"fa-yarn\",\"types\":[\"brands\"]},{\"key\":\"fa-yelp\",\"types\":[\"brands\"]},{\"key\":\"fa-yoast\",\"types\":[\"brands\"]},{\"key\":\"fa-youtube-square\",\"types\":[\"brands\"]},{\"key\":\"fa-youtube\",\"types\":[\"brands\"]},{\"key\":\"fa-zhihu\",\"types\":[\"brands\"]}]}");

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
    var options = $(item).find('option');
    var placeholder = options.length ? options[0].innerHTML : '';

    if (placeholder.length) {
      args.placeholder = placeholder;
    }

    $(item).select2(args);
  });
}



/***/ }),

/***/ "./assets/src/js/lib/icon-picker.js":
/*!******************************************!*\
  !*** ./assets/src/js/lib/icon-picker.js ***!
  \******************************************/
/*! exports provided: IconPicker */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "IconPicker", function() { return IconPicker; });
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

var IconPicker = function IconPicker(args) {
  return {
    value: '',
    iconType: 'solid',
    container: null,
    onSelect: null,
    icons: null,
    init: function init() {
      var _this = this;

      this.container = typeof args.container !== 'undefined' ? args.container : this.container;
      this.onSelect = typeof args.onSelect !== 'undefined' ? args.onSelect : this.onSelect;
      this.icons = typeof args.icons !== 'undefined' ? args.icons : this.icons;
      this.value = typeof args.value === 'string' ? args.value : this.value;

      if (!this.container) {
        return;
      } // console.log( 'chk-1', { container: this.container } );


      _this.renderMarkup();

      _this.renderIcon();

      _this.attachEvents();
    },
    renderIcon: function renderIcon() {
      var markup = '';

      for (var _i = 0, _Object$keys = Object.keys(this.icons); _i < _Object$keys.length; _i++) {
        var iconGroupKey = _Object$keys[_i];
        markup += "<div class=\"icons-group ".concat(iconGroupKey, "\">");
        markup += "<h4>".concat(this.icons[iconGroupKey].label, "</h4>");
        markup += "<div class=\"icons-group-icons\">";

        var _iterator = _createForOfIteratorHelper(this.icons[iconGroupKey].icons),
            _step;

        try {
          for (_iterator.s(); !(_step = _iterator.n()).done;) {
            var icon = _step.value;
            var fullIcon = this.getFullIcon(icon.key, iconGroupKey, icon.types[0]);
            var buttonClass = this.value === fullIcon ? 'cptm-btn-primary' : 'cptm-btn-secondery';
            markup += "\n                        <button class=\"font-icon-btn cptm-btn ".concat(buttonClass, " ").concat(fullIcon, "\" data-group-key=\"").concat(iconGroupKey, "\" data-icon-key=\"").concat(icon.key, "\" data-icon-type=\"").concat([icon.types], "\"></button>\n                    ");
          }
        } catch (err) {
          _iterator.e(err);
        } finally {
          _iterator.f();
        }

        markup += "</div></div>";
      }

      this.container.closest('body').querySelector('#iconsWrapperElm').innerHTML = markup;
    },
    renderMarkup: function renderMarkup() {
      var selectedIcon = this.value ? this.value.split(" ") : ['', 'icon-name'];
      var markup = '';
      markup += "\n            <div class=\"icon-picker-selector\">\n                <div class=\"icon-picker-selector__icon\">\n                    <span class=\"directorist-selected-icon ".concat(this.value, "\"></span>\n                    <input\n                    type=\"text\"\n                    placeholder=\"Click to select icon\"\n                    class=\"cptm-form-control\"\n                    value=\"").concat(this.value, "\" style=\"").concat(this.value ? 'padding-left: 38px' : '', "\"\n                    />\n                </div>\n                <button class=\"icon-picker-selector__btn\">Change Icon</button>\n            </div>\n                ");
      this.container.innerHTML = markup;
      var iconPickerWrap = "\n            <div class=\"icon-picker\">\n            <div class=\"icon-picker__inner\">\n                <a href=\"#\" class=\"icon-picker__close\"\n                    ><span class=\"fas fa-times\"></span\n                ></a>\n                <div class=\"icon-picker__sidebar\">\n                    <div class=\"icon-picker__filter\">\n                        <label for=\"\">Filter By Name</label>\n                        <input type=\"text\" placeholder=\"Search\" />\n                    </div>\n                    <div class=\"icon-picker__filter\">\n                        <label for=\"\">Filter By Icon Pack</label>\n                        <select>\n                            <option value=\"fontAwesome\">Font Awesome</option>\n                            <option value=\"lineAwesome\">Line Awesome</option>\n                        </select>\n                    </div>\n                    <div class=\"icon-picker__preview\">\n                        <span class=\"icon-picker__preview-icon ".concat(this.value, "\"></span>\n                        <span class=\"icon-picker__preview-info\">\n                            <span class=\"icon-picker__icon-name\">").concat(selectedIcon[1], "</span>\n                        </span>\n                    </div>\n                    <button class=\"cptm-btn cptm-btn-primary icon-picker__done-btn\">Done</button>\n                </div>\n                <div class=\"icon-picker__content\">\n                <div id=\"iconsWrapperElm\" class=\"iconsWrapperElm\">\n\n                </div>\n            </div>\n        </div>\n            ");
      this.container.closest('body').insertAdjacentHTML('beforeend', iconPickerWrap);
    },
    attachEvents: function attachEvents() {
      var iconButtons = document.querySelectorAll('.font-icon-btn');
      var self = this;
      var icon; //remove active status

      function removeActiveStatus() {
        iconButtons.forEach(function (elm) {
          if (elm.classList.contains('cptm-btn-primary')) {
            elm.classList.remove('cptm-btn-primary');
          }
        });
      }

      iconButtons.forEach(function (elm) {
        elm.addEventListener('click', function (event) {
          event.preventDefault();
          var iconGroupKey = event.target.getAttribute('data-group-key');
          var iconKey = event.target.getAttribute('data-icon-key');
          var iconType = event.target.getAttribute('data-icon-type').split(',');
          icon = self.getFullIcon(iconKey, iconGroupKey, iconType[0]);
          removeActiveStatus();
          elm.classList.add('cptm-btn-primary');
          self.container.closest('body').querySelector('.icon-picker__preview-icon').setAttribute('class', "icon-picker__preview-icon ".concat(icon));
          self.container.closest('body').querySelector('.icon-picker__icon-name').innerHTML = iconKey;
          searchIcon();
        });
      });
      /* Icon picker modal */

      var iconPicker = document.querySelector('.icon-picker');

      function openModal() {
        iconPicker.classList.add('icon-picker-visible');
      }

      function closeModal() {
        iconPicker.classList.remove('icon-picker-visible');
      }

      var selectIconButtons = document.querySelectorAll('.icon-picker-selector .icon-picker-selector__btn');

      if (selectIconButtons.length) {
        var _iterator2 = _createForOfIteratorHelper(selectIconButtons),
            _step2;

        try {
          for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
            var selectIconButton = _step2.value;
            selectIconButton.addEventListener('click', function (e) {
              e.preventDefault();
              openModal();
            });
          }
        } catch (err) {
          _iterator2.e(err);
        } finally {
          _iterator2.f();
        }
      } // document.querySelector('.icon-picker-selector .icon-picker-selector__btn').addEventListener('click', (e) => {
      //     e.preventDefault();
      //     openModal();
      // });


      document.querySelector('.icon-picker__done-btn').addEventListener('click', function (e) {
        e.preventDefault();
        closeModal();

        if (typeof icon !== 'undefined') {
          self.value = icon;

          if (typeof self.onSelect === 'function') {
            self.onSelect(icon);
          }

          document.querySelector('.icon-picker-selector input').style.paddingLeft = '38px';
        } //self.renderIcon();
        //self.attachEvents();


        self.container.closest('body').querySelector('.icon-picker-selector input').value = self.value;
        self.container.closest('body').querySelector('.directorist-selected-icon').setAttribute('class', "directorist-selected-icon ".concat(self.value));
      });
      document.querySelector('.icon-picker__close').addEventListener('click', closeModal);
      document.body.addEventListener('click', function (e) {
        if (!e.target.closest('.icon-picker__inner') && !e.target.closest('.icon-picker-selector') && !e.target.closest('.icons-group-icons')) {
          closeModal();
        }
      });
      /* Searchable input */

      var searchInput = document.querySelector('.icon-picker__filter input');

      function searchIcon() {
        var filter = searchInput.value.toUpperCase();
        var iconBtn = document.querySelectorAll('.font-icon-btn');
        iconBtn.forEach(function (elm) {
          var textValue = elm.getAttribute('data-icon-key');

          if (textValue.toUpperCase().indexOf(filter) > -1) {
            elm.style.display = "";
          } else {
            elm.style.display = "none";
          }
        });
      }

      searchInput.addEventListener('keyup', searchIcon);
      /* Default icons pack */

      var iconFilter = document.querySelector('.icon-picker__filter select');
      var faPack = document.querySelector('.icons-group.fontAwesome');
      var laPack = document.querySelector('.icons-group.lineAwesome');

      function filterIconPack(sel) {
        if (sel.value === 'fontAwesome') {
          faPack.style.display = 'block';
          laPack.style.display = 'none';
        } else if (sel.value === 'lineAwesome') {
          laPack.style.display = 'block';
          faPack.style.display = 'none';
        }
      }

      iconFilter.addEventListener('change', function () {
        filterIconPack(this);
      });
      filterIconPack(iconFilter);
    },
    getFullIcon: function getFullIcon(iconKey, iconGroupKey, iconType) {
      var prefix = '';

      if (typeof this.icons[iconGroupKey].iconTypes[iconType] !== 'undefined') {
        prefix = this.icons[iconGroupKey].iconTypes[iconType].key;
      }

      return "".concat(prefix, " ").concat(iconKey);
    }
  };
};



/***/ }),

/***/ "./assets/src/js/lib/line-awesome.json":
/*!*********************************************!*\
  !*** ./assets/src/js/lib/line-awesome.json ***!
  \*********************************************/
/*! exports provided: label, iconTypes, icons, default */
/***/ (function(module) {

module.exports = JSON.parse("{\"label\":\"Line Awesome\",\"iconTypes\":{\"solid\":{\"label\":\"Solid\",\"key\":\"las\"},\"regular\":{\"label\":\"Regular\",\"key\":\"lar\"},\"brands\":{\"label\":\"Brand\",\"key\":\"lab\"}},\"icons\":[{\"key\":\"la-500px\",\"types\":[\"brands\"]},{\"key\":\"la-accusoft\",\"types\":[\"brands\"]},{\"key\":\"la-adn\",\"types\":[\"brands\"]},{\"key\":\"la-adobe\",\"types\":[\"brands\"]},{\"key\":\"la-adversal\",\"types\":[\"brands\"]},{\"key\":\"la-affiliatetheme\",\"types\":[\"brands\"]},{\"key\":\"la-airbnb\",\"types\":[\"brands\"]},{\"key\":\"la-algolia\",\"types\":[\"brands\"]},{\"key\":\"la-amazon\",\"types\":[\"brands\"]},{\"key\":\"la-amilia\",\"types\":[\"brands\"]},{\"key\":\"la-android\",\"types\":[\"brands\"]},{\"key\":\"la-angellist\",\"types\":[\"brands\"]},{\"key\":\"la-angrycreative\",\"types\":[\"brands\"]},{\"key\":\"la-angular\",\"types\":[\"brands\"]},{\"key\":\"la-app-store\",\"types\":[\"brands\"]},{\"key\":\"la-app-store-ios\",\"types\":[\"brands\"]},{\"key\":\"la-apper\",\"types\":[\"brands\"]},{\"key\":\"la-apple\",\"types\":[\"brands\"]},{\"key\":\"la-artstation\",\"types\":[\"brands\"]},{\"key\":\"la-asymmetrik\",\"types\":[\"brands\"]},{\"key\":\"la-atlassian\",\"types\":[\"brands\"]},{\"key\":\"la-audible\",\"types\":[\"brands\"]},{\"key\":\"la-autoprefixer\",\"types\":[\"brands\"]},{\"key\":\"la-avianex\",\"types\":[\"brands\"]},{\"key\":\"la-aviato\",\"types\":[\"brands\"]},{\"key\":\"la-aws\",\"types\":[\"brands\"]},{\"key\":\"la-bandcamp\",\"types\":[\"brands\"]},{\"key\":\"la-battle-net\",\"types\":[\"brands\"]},{\"key\":\"la-behance\",\"types\":[\"brands\"]},{\"key\":\"la-behance-square\",\"types\":[\"brands\"]},{\"key\":\"la-bimobject\",\"types\":[\"brands\"]},{\"key\":\"la-bitbucket\",\"types\":[\"brands\"]},{\"key\":\"la-bity\",\"types\":[\"brands\"]},{\"key\":\"la-black-tie\",\"types\":[\"brands\"]},{\"key\":\"la-blackberry\",\"types\":[\"brands\"]},{\"key\":\"la-blogger\",\"types\":[\"brands\"]},{\"key\":\"la-blogger-b\",\"types\":[\"brands\"]},{\"key\":\"la-bootstrap\",\"types\":[\"brands\"]},{\"key\":\"la-buffer\",\"types\":[\"brands\"]},{\"key\":\"la-buromobelexperte\",\"types\":[\"brands\"]},{\"key\":\"la-buy-n-large\",\"types\":[\"brands\"]},{\"key\":\"la-buysellads\",\"types\":[\"brands\"]},{\"key\":\"la-canadian-maple-leaf\",\"types\":[\"brands\"]},{\"key\":\"la-centercode\",\"types\":[\"brands\"]},{\"key\":\"la-centos\",\"types\":[\"brands\"]},{\"key\":\"la-chrome\",\"types\":[\"brands\"]},{\"key\":\"la-chromecast\",\"types\":[\"brands\"]},{\"key\":\"la-cloudscale\",\"types\":[\"brands\"]},{\"key\":\"la-cloudsmith\",\"types\":[\"brands\"]},{\"key\":\"la-cloudversify\",\"types\":[\"brands\"]},{\"key\":\"la-codepen\",\"types\":[\"brands\"]},{\"key\":\"la-codiepie\",\"types\":[\"brands\"]},{\"key\":\"la-confluence\",\"types\":[\"brands\"]},{\"key\":\"la-connectdevelop\",\"types\":[\"brands\"]},{\"key\":\"la-contao\",\"types\":[\"brands\"]},{\"key\":\"la-cotton-bureau\",\"types\":[\"brands\"]},{\"key\":\"la-cpanel\",\"types\":[\"brands\"]},{\"key\":\"la-creative-commons\",\"types\":[\"brands\"]},{\"key\":\"la-creative-commons-by\",\"types\":[\"brands\"]},{\"key\":\"la-creative-commons-nc\",\"types\":[\"brands\"]},{\"key\":\"la-creative-commons-nc-eu\",\"types\":[\"brands\"]},{\"key\":\"la-creative-commons-nc-jp\",\"types\":[\"brands\"]},{\"key\":\"la-creative-commons-nd\",\"types\":[\"brands\"]},{\"key\":\"la-creative-commons-pd\",\"types\":[\"brands\"]},{\"key\":\"la-creative-commons-pd-alt\",\"types\":[\"brands\"]},{\"key\":\"la-creative-commons-remix\",\"types\":[\"brands\"]},{\"key\":\"la-creative-commons-sa\",\"types\":[\"brands\"]},{\"key\":\"la-creative-commons-sampling\",\"types\":[\"brands\"]},{\"key\":\"la-creative-commons-sampling-plus\",\"types\":[\"brands\"]},{\"key\":\"la-creative-commons-share\",\"types\":[\"brands\"]},{\"key\":\"la-creative-commons-zero\",\"types\":[\"brands\"]},{\"key\":\"la-css3\",\"types\":[\"brands\"]},{\"key\":\"la-css3-alt\",\"types\":[\"brands\"]},{\"key\":\"la-cuttlefish\",\"types\":[\"brands\"]},{\"key\":\"la-dashcube\",\"types\":[\"brands\"]},{\"key\":\"la-delicious\",\"types\":[\"brands\"]},{\"key\":\"la-deploydog\",\"types\":[\"brands\"]},{\"key\":\"la-deskpro\",\"types\":[\"brands\"]},{\"key\":\"la-dev\",\"types\":[\"brands\"]},{\"key\":\"la-deviantart\",\"types\":[\"brands\"]},{\"key\":\"la-dhl\",\"types\":[\"brands\"]},{\"key\":\"la-diaspora\",\"types\":[\"brands\"]},{\"key\":\"la-digg\",\"types\":[\"brands\"]},{\"key\":\"la-digital-ocean\",\"types\":[\"brands\"]},{\"key\":\"la-discord\",\"types\":[\"brands\"]},{\"key\":\"la-discourse\",\"types\":[\"brands\"]},{\"key\":\"la-dochub\",\"types\":[\"brands\"]},{\"key\":\"la-docker\",\"types\":[\"brands\"]},{\"key\":\"la-draft2digital\",\"types\":[\"brands\"]},{\"key\":\"la-dribbble\",\"types\":[\"brands\"]},{\"key\":\"la-dribbble-square\",\"types\":[\"brands\"]},{\"key\":\"la-dropbox\",\"types\":[\"brands\"]},{\"key\":\"la-drupal\",\"types\":[\"brands\"]},{\"key\":\"la-dyalog\",\"types\":[\"brands\"]},{\"key\":\"la-earlybirds\",\"types\":[\"brands\"]},{\"key\":\"la-ebay\",\"types\":[\"brands\"]},{\"key\":\"la-edge\",\"types\":[\"brands\"]},{\"key\":\"la-elementor\",\"types\":[\"brands\"]},{\"key\":\"la-ello\",\"types\":[\"brands\"]},{\"key\":\"la-ember\",\"types\":[\"brands\"]},{\"key\":\"la-empire\",\"types\":[\"brands\"]},{\"key\":\"la-envira\",\"types\":[\"brands\"]},{\"key\":\"la-erlang\",\"types\":[\"brands\"]},{\"key\":\"la-etsy\",\"types\":[\"brands\"]},{\"key\":\"la-evernote\",\"types\":[\"brands\"]},{\"key\":\"la-expeditedssl\",\"types\":[\"brands\"]},{\"key\":\"la-facebook\",\"types\":[\"brands\"]},{\"key\":\"la-facebook-f\",\"types\":[\"brands\"]},{\"key\":\"la-facebook-messenger\",\"types\":[\"brands\"]},{\"key\":\"la-facebook-square\",\"types\":[\"brands\"]},{\"key\":\"la-fedex\",\"types\":[\"brands\"]},{\"key\":\"la-fedora\",\"types\":[\"brands\"]},{\"key\":\"la-figma\",\"types\":[\"brands\"]},{\"key\":\"la-firefox\",\"types\":[\"brands\"]},{\"key\":\"la-first-order\",\"types\":[\"brands\"]},{\"key\":\"la-first-order-alt\",\"types\":[\"brands\"]},{\"key\":\"la-firstdraft\",\"types\":[\"brands\"]},{\"key\":\"la-flickr\",\"types\":[\"brands\"]},{\"key\":\"la-flipboard\",\"types\":[\"brands\"]},{\"key\":\"la-fly\",\"types\":[\"brands\"]},{\"key\":\"la-font-awesome\",\"types\":[\"brands\"]},{\"key\":\"la-font-awesome-alt\",\"types\":[\"brands\"]},{\"key\":\"la-font-awesome-flag\",\"types\":[\"brands\"]},{\"key\":\"la-fonticons\",\"types\":[\"brands\"]},{\"key\":\"la-fonticons-fi\",\"types\":[\"brands\"]},{\"key\":\"la-fort-awesome\",\"types\":[\"brands\"]},{\"key\":\"la-fort-awesome-alt\",\"types\":[\"brands\"]},{\"key\":\"la-forumbee\",\"types\":[\"brands\"]},{\"key\":\"la-foursquare\",\"types\":[\"brands\"]},{\"key\":\"la-free-code-camp\",\"types\":[\"brands\"]},{\"key\":\"la-freebsd\",\"types\":[\"brands\"]},{\"key\":\"la-fulcrum\",\"types\":[\"brands\"]},{\"key\":\"la-get-pocket\",\"types\":[\"brands\"]},{\"key\":\"la-git\",\"types\":[\"brands\"]},{\"key\":\"la-git-alt\",\"types\":[\"brands\"]},{\"key\":\"la-git-square\",\"types\":[\"brands\"]},{\"key\":\"la-github\",\"types\":[\"brands\"]},{\"key\":\"la-github-alt\",\"types\":[\"brands\"]},{\"key\":\"la-github-square\",\"types\":[\"brands\"]},{\"key\":\"la-gitkraken\",\"types\":[\"brands\"]},{\"key\":\"la-gitlab\",\"types\":[\"brands\"]},{\"key\":\"la-gitter\",\"types\":[\"brands\"]},{\"key\":\"la-glide\",\"types\":[\"brands\"]},{\"key\":\"la-glide-g\",\"types\":[\"brands\"]},{\"key\":\"la-gofore\",\"types\":[\"brands\"]},{\"key\":\"la-goodreads\",\"types\":[\"brands\"]},{\"key\":\"la-goodreads-g\",\"types\":[\"brands\"]},{\"key\":\"la-google\",\"types\":[\"brands\"]},{\"key\":\"la-google-drive\",\"types\":[\"brands\"]},{\"key\":\"la-google-play\",\"types\":[\"brands\"]},{\"key\":\"la-google-plus\",\"types\":[\"brands\"]},{\"key\":\"la-google-plus-g\",\"types\":[\"brands\"]},{\"key\":\"la-google-plus-square\",\"types\":[\"brands\"]},{\"key\":\"la-gratipay\",\"types\":[\"brands\"]},{\"key\":\"la-grav\",\"types\":[\"brands\"]},{\"key\":\"la-gripfire\",\"types\":[\"brands\"]},{\"key\":\"la-grunt\",\"types\":[\"brands\"]},{\"key\":\"la-gulp\",\"types\":[\"brands\"]},{\"key\":\"la-hacker-news\",\"types\":[\"brands\"]},{\"key\":\"la-hacker-news-square\",\"types\":[\"brands\"]},{\"key\":\"la-hackerrank\",\"types\":[\"brands\"]},{\"key\":\"la-hips\",\"types\":[\"brands\"]},{\"key\":\"la-hire-a-helper\",\"types\":[\"brands\"]},{\"key\":\"la-hooli\",\"types\":[\"brands\"]},{\"key\":\"la-hornbill\",\"types\":[\"brands\"]},{\"key\":\"la-hotjar\",\"types\":[\"brands\"]},{\"key\":\"la-houzz\",\"types\":[\"brands\"]},{\"key\":\"la-html5\",\"types\":[\"brands\"]},{\"key\":\"la-hubspot\",\"types\":[\"brands\"]},{\"key\":\"la-imdb\",\"types\":[\"brands\"]},{\"key\":\"la-instagram\",\"types\":[\"brands\"]},{\"key\":\"la-intercom\",\"types\":[\"brands\"]},{\"key\":\"la-internet-explorer\",\"types\":[\"brands\"]},{\"key\":\"la-invision\",\"types\":[\"brands\"]},{\"key\":\"la-ioxhost\",\"types\":[\"brands\"]},{\"key\":\"la-itch-io\",\"types\":[\"brands\"]},{\"key\":\"la-itunes\",\"types\":[\"brands\"]},{\"key\":\"la-itunes-note\",\"types\":[\"brands\"]},{\"key\":\"la-java\",\"types\":[\"brands\"]},{\"key\":\"la-jenkins\",\"types\":[\"brands\"]},{\"key\":\"la-jira\",\"types\":[\"brands\"]},{\"key\":\"la-joget\",\"types\":[\"brands\"]},{\"key\":\"la-joomla\",\"types\":[\"brands\"]},{\"key\":\"la-js\",\"types\":[\"brands\"]},{\"key\":\"la-js-square\",\"types\":[\"brands\"]},{\"key\":\"la-jsfiddle\",\"types\":[\"brands\"]},{\"key\":\"la-kaggle\",\"types\":[\"brands\"]},{\"key\":\"la-keybase\",\"types\":[\"brands\"]},{\"key\":\"la-keycdn\",\"types\":[\"brands\"]},{\"key\":\"la-kickstarter\",\"types\":[\"brands\"]},{\"key\":\"la-kickstarter-k\",\"types\":[\"brands\"]},{\"key\":\"la-korvue\",\"types\":[\"brands\"]},{\"key\":\"la-laravel\",\"types\":[\"brands\"]},{\"key\":\"la-lastfm\",\"types\":[\"brands\"]},{\"key\":\"la-lastfm-square\",\"types\":[\"brands\"]},{\"key\":\"la-leanpub\",\"types\":[\"brands\"]},{\"key\":\"la-less\",\"types\":[\"brands\"]},{\"key\":\"la-line\",\"types\":[\"brands\"]},{\"key\":\"la-linkedin\",\"types\":[\"brands\"]},{\"key\":\"la-linkedin-in\",\"types\":[\"brands\"]},{\"key\":\"la-linode\",\"types\":[\"brands\"]},{\"key\":\"la-linux\",\"types\":[\"brands\"]},{\"key\":\"la-lyft\",\"types\":[\"brands\"]},{\"key\":\"la-magento\",\"types\":[\"brands\"]},{\"key\":\"la-mailchimp\",\"types\":[\"brands\"]},{\"key\":\"la-mandalorian\",\"types\":[\"brands\"]},{\"key\":\"la-markdown\",\"types\":[\"brands\"]},{\"key\":\"la-mastodon\",\"types\":[\"brands\"]},{\"key\":\"la-maxcdn\",\"types\":[\"brands\"]},{\"key\":\"la-mdb\",\"types\":[\"brands\"]},{\"key\":\"la-medapps\",\"types\":[\"brands\"]},{\"key\":\"la-medium\",\"types\":[\"brands\"]},{\"key\":\"la-medium-m\",\"types\":[\"brands\"]},{\"key\":\"la-medrt\",\"types\":[\"brands\"]},{\"key\":\"la-meetup\",\"types\":[\"brands\"]},{\"key\":\"la-megaport\",\"types\":[\"brands\"]},{\"key\":\"la-mendeley\",\"types\":[\"brands\"]},{\"key\":\"la-microsoft\",\"types\":[\"brands\"]},{\"key\":\"la-mix\",\"types\":[\"brands\"]},{\"key\":\"la-mixcloud\",\"types\":[\"brands\"]},{\"key\":\"la-mizuni\",\"types\":[\"brands\"]},{\"key\":\"la-modx\",\"types\":[\"brands\"]},{\"key\":\"la-monero\",\"types\":[\"brands\"]},{\"key\":\"la-neos\",\"types\":[\"brands\"]},{\"key\":\"la-nimblr\",\"types\":[\"brands\"]},{\"key\":\"la-node\",\"types\":[\"brands\"]},{\"key\":\"la-node-js\",\"types\":[\"brands\"]},{\"key\":\"la-npm\",\"types\":[\"brands\"]},{\"key\":\"la-ns8\",\"types\":[\"brands\"]},{\"key\":\"la-nutritionix\",\"types\":[\"brands\"]},{\"key\":\"la-odnoklassniki\",\"types\":[\"brands\"]},{\"key\":\"la-odnoklassniki-square\",\"types\":[\"brands\"]},{\"key\":\"la-opencart\",\"types\":[\"brands\"]},{\"key\":\"la-openid\",\"types\":[\"brands\"]},{\"key\":\"la-opera\",\"types\":[\"brands\"]},{\"key\":\"la-optin-monster\",\"types\":[\"brands\"]},{\"key\":\"la-orcid\",\"types\":[\"brands\"]},{\"key\":\"la-osi\",\"types\":[\"brands\"]},{\"key\":\"la-page4\",\"types\":[\"brands\"]},{\"key\":\"la-pagelines\",\"types\":[\"brands\"]},{\"key\":\"la-palfed\",\"types\":[\"brands\"]},{\"key\":\"la-patreon\",\"types\":[\"brands\"]},{\"key\":\"la-periscope\",\"types\":[\"brands\"]},{\"key\":\"la-phabricator\",\"types\":[\"brands\"]},{\"key\":\"la-phoenix-framework\",\"types\":[\"brands\"]},{\"key\":\"la-phoenix-squadron\",\"types\":[\"brands\"]},{\"key\":\"la-php\",\"types\":[\"brands\"]},{\"key\":\"la-pied-piper\",\"types\":[\"brands\"]},{\"key\":\"la-pied-piper-alt\",\"types\":[\"brands\"]},{\"key\":\"la-pied-piper-hat\",\"types\":[\"brands\"]},{\"key\":\"la-pied-piper-pp\",\"types\":[\"brands\"]},{\"key\":\"la-pinterest\",\"types\":[\"brands\"]},{\"key\":\"la-pinterest-p\",\"types\":[\"brands\"]},{\"key\":\"la-pinterest-square\",\"types\":[\"brands\"]},{\"key\":\"la-product-hunt\",\"types\":[\"brands\"]},{\"key\":\"la-pushed\",\"types\":[\"brands\"]},{\"key\":\"la-python\",\"types\":[\"brands\"]},{\"key\":\"la-qq\",\"types\":[\"brands\"]},{\"key\":\"la-quinscape\",\"types\":[\"brands\"]},{\"key\":\"la-quora\",\"types\":[\"brands\"]},{\"key\":\"la-r-project\",\"types\":[\"brands\"]},{\"key\":\"la-raspberry-pi\",\"types\":[\"brands\"]},{\"key\":\"la-ravelry\",\"types\":[\"brands\"]},{\"key\":\"la-react\",\"types\":[\"brands\"]},{\"key\":\"la-reacteurope\",\"types\":[\"brands\"]},{\"key\":\"la-readme\",\"types\":[\"brands\"]},{\"key\":\"la-rebel\",\"types\":[\"brands\"]},{\"key\":\"la-red-river\",\"types\":[\"brands\"]},{\"key\":\"la-reddit\",\"types\":[\"brands\"]},{\"key\":\"la-reddit-alien\",\"types\":[\"brands\"]},{\"key\":\"la-reddit-square\",\"types\":[\"brands\"]},{\"key\":\"la-redhat\",\"types\":[\"brands\"]},{\"key\":\"la-renren\",\"types\":[\"brands\"]},{\"key\":\"la-replyd\",\"types\":[\"brands\"]},{\"key\":\"la-researchgate\",\"types\":[\"brands\"]},{\"key\":\"la-resolving\",\"types\":[\"brands\"]},{\"key\":\"la-rev\",\"types\":[\"brands\"]},{\"key\":\"la-rocketchat\",\"types\":[\"brands\"]},{\"key\":\"la-rockrms\",\"types\":[\"brands\"]},{\"key\":\"la-safari\",\"types\":[\"brands\"]},{\"key\":\"la-salesforce\",\"types\":[\"brands\"]},{\"key\":\"la-sass\",\"types\":[\"brands\"]},{\"key\":\"la-schlix\",\"types\":[\"brands\"]},{\"key\":\"la-scribd\",\"types\":[\"brands\"]},{\"key\":\"la-searchengin\",\"types\":[\"brands\"]},{\"key\":\"la-sellcast\",\"types\":[\"brands\"]},{\"key\":\"la-sellsy\",\"types\":[\"brands\"]},{\"key\":\"la-servicestack\",\"types\":[\"brands\"]},{\"key\":\"la-shirtsinbulk\",\"types\":[\"brands\"]},{\"key\":\"la-shopware\",\"types\":[\"brands\"]},{\"key\":\"la-simplybuilt\",\"types\":[\"brands\"]},{\"key\":\"la-sistrix\",\"types\":[\"brands\"]},{\"key\":\"la-sith\",\"types\":[\"brands\"]},{\"key\":\"la-sketch\",\"types\":[\"brands\"]},{\"key\":\"la-skyatlas\",\"types\":[\"brands\"]},{\"key\":\"la-skype\",\"types\":[\"brands\"]},{\"key\":\"la-slack\",\"types\":[\"brands\"]},{\"key\":\"la-slack-hash\",\"types\":[\"brands\"]},{\"key\":\"la-slideshare\",\"types\":[\"brands\"]},{\"key\":\"la-snapchat\",\"types\":[\"brands\"]},{\"key\":\"la-snapchat-ghost\",\"types\":[\"brands\"]},{\"key\":\"la-snapchat-square\",\"types\":[\"brands\"]},{\"key\":\"la-sourcetree\",\"types\":[\"brands\"]},{\"key\":\"la-speakap\",\"types\":[\"brands\"]},{\"key\":\"la-speaker-deck\",\"types\":[\"brands\"]},{\"key\":\"la-squarespace\",\"types\":[\"brands\"]},{\"key\":\"la-stack-exchange\",\"types\":[\"brands\"]},{\"key\":\"la-stack-overflow\",\"types\":[\"brands\"]},{\"key\":\"la-stackpath\",\"types\":[\"brands\"]},{\"key\":\"la-staylinked\",\"types\":[\"brands\"]},{\"key\":\"la-sticker-mule\",\"types\":[\"brands\"]},{\"key\":\"la-strava\",\"types\":[\"brands\"]},{\"key\":\"la-studiovinari\",\"types\":[\"brands\"]},{\"key\":\"la-stumbleupon\",\"types\":[\"brands\"]},{\"key\":\"la-stumbleupon-circle\",\"types\":[\"brands\"]},{\"key\":\"la-superpowers\",\"types\":[\"brands\"]},{\"key\":\"la-supple\",\"types\":[\"brands\"]},{\"key\":\"la-suse\",\"types\":[\"brands\"]},{\"key\":\"la-swift\",\"types\":[\"brands\"]},{\"key\":\"la-symfony\",\"types\":[\"brands\"]},{\"key\":\"la-teamspeak\",\"types\":[\"brands\"]},{\"key\":\"la-telegram\",\"types\":[\"brands\"]},{\"key\":\"la-telegram-plane\",\"types\":[\"brands\"]},{\"key\":\"la-tencent-weibo\",\"types\":[\"brands\"]},{\"key\":\"la-the-red-yeti\",\"types\":[\"brands\"]},{\"key\":\"la-themeco\",\"types\":[\"brands\"]},{\"key\":\"la-themeisle\",\"types\":[\"brands\"]},{\"key\":\"la-think-peaks\",\"types\":[\"brands\"]},{\"key\":\"la-trade-federation\",\"types\":[\"brands\"]},{\"key\":\"la-trello\",\"types\":[\"brands\"]},{\"key\":\"la-tripadvisor\",\"types\":[\"brands\"]},{\"key\":\"la-tumblr\",\"types\":[\"brands\"]},{\"key\":\"la-tumblr-square\",\"types\":[\"brands\"]},{\"key\":\"la-twitter\",\"types\":[\"brands\"]},{\"key\":\"la-twitter-square\",\"types\":[\"brands\"]},{\"key\":\"la-typo3\",\"types\":[\"brands\"]},{\"key\":\"la-uber\",\"types\":[\"brands\"]},{\"key\":\"la-ubuntu\",\"types\":[\"brands\"]},{\"key\":\"la-uikit\",\"types\":[\"brands\"]},{\"key\":\"la-umbraco\",\"types\":[\"brands\"]},{\"key\":\"la-uniregistry\",\"types\":[\"brands\"]},{\"key\":\"la-untappd\",\"types\":[\"brands\"]},{\"key\":\"la-ups\",\"types\":[\"brands\"]},{\"key\":\"la-usb\",\"types\":[\"brands\"]},{\"key\":\"la-usps\",\"types\":[\"brands\"]},{\"key\":\"la-ussunnah\",\"types\":[\"brands\"]},{\"key\":\"la-vaadin\",\"types\":[\"brands\"]},{\"key\":\"la-viacoin\",\"types\":[\"brands\"]},{\"key\":\"la-viadeo\",\"types\":[\"brands\"]},{\"key\":\"la-viadeo-square\",\"types\":[\"brands\"]},{\"key\":\"la-viber\",\"types\":[\"brands\"]},{\"key\":\"la-vimeo\",\"types\":[\"brands\"]},{\"key\":\"la-vimeo-square\",\"types\":[\"brands\"]},{\"key\":\"la-vimeo-v\",\"types\":[\"brands\"]},{\"key\":\"la-vine\",\"types\":[\"brands\"]},{\"key\":\"la-vk\",\"types\":[\"brands\"]},{\"key\":\"la-vnv\",\"types\":[\"brands\"]},{\"key\":\"la-vuejs\",\"types\":[\"brands\"]},{\"key\":\"la-waze\",\"types\":[\"brands\"]},{\"key\":\"la-weebly\",\"types\":[\"brands\"]},{\"key\":\"la-weibo\",\"types\":[\"brands\"]},{\"key\":\"la-weixin\",\"types\":[\"brands\"]},{\"key\":\"la-whatsapp\",\"types\":[\"brands\"]},{\"key\":\"la-whatsapp-square\",\"types\":[\"brands\"]},{\"key\":\"la-whmcs\",\"types\":[\"brands\"]},{\"key\":\"la-wikipedia-w\",\"types\":[\"brands\"]},{\"key\":\"la-windows\",\"types\":[\"brands\"]},{\"key\":\"la-wix\",\"types\":[\"brands\"]},{\"key\":\"la-wolf-pack-battalion\",\"types\":[\"brands\"]},{\"key\":\"la-wordpress\",\"types\":[\"brands\"]},{\"key\":\"la-wordpress-simple\",\"types\":[\"brands\"]},{\"key\":\"la-wpbeginner\",\"types\":[\"brands\"]},{\"key\":\"la-wpexplorer\",\"types\":[\"brands\"]},{\"key\":\"la-wpforms\",\"types\":[\"brands\"]},{\"key\":\"la-wpressr\",\"types\":[\"brands\"]},{\"key\":\"la-xing\",\"types\":[\"brands\"]},{\"key\":\"la-xing-square\",\"types\":[\"brands\"]},{\"key\":\"la-y-combinator\",\"types\":[\"brands\"]},{\"key\":\"la-yahoo\",\"types\":[\"brands\"]},{\"key\":\"la-yammer\",\"types\":[\"brands\"]},{\"key\":\"la-yandex\",\"types\":[\"brands\"]},{\"key\":\"la-yandex-international\",\"types\":[\"brands\"]},{\"key\":\"la-yarn\",\"types\":[\"brands\"]},{\"key\":\"la-yelp\",\"types\":[\"brands\"]},{\"key\":\"la-yoast\",\"types\":[\"brands\"]},{\"key\":\"la-youtube-square\",\"types\":[\"brands\"]},{\"key\":\"la-zhihu\",\"types\":[\"brands\"]},{\"key\":\"la-american-sign-language-interpreting\",\"types\":[\"solid\"]},{\"key\":\"la-assistive-listening-systems\",\"types\":[\"solid\"]},{\"key\":\"la-audio-description\",\"types\":[\"solid\"]},{\"key\":\"la-blind\",\"types\":[\"solid\"]},{\"key\":\"la-braille\",\"types\":[\"solid\"]},{\"key\":\"la-closed-captioning\",\"types\":[\"solid\"]},{\"key\":\"la-deaf\",\"types\":[\"solid\"]},{\"key\":\"la-low-vision\",\"types\":[\"solid\"]},{\"key\":\"la-phone-volume\",\"types\":[\"solid\"]},{\"key\":\"la-question-circle\",\"types\":[\"solid\"]},{\"key\":\"la-sign-language\",\"types\":[\"solid\"]},{\"key\":\"la-tty\",\"types\":[\"solid\"]},{\"key\":\"la-universal-access\",\"types\":[\"solid\"]},{\"key\":\"la-wheelchair\",\"types\":[\"solid\"]},{\"key\":\"la-bell\",\"types\":[\"solid\"]},{\"key\":\"la-bell-slash\",\"types\":[\"solid\"]},{\"key\":\"la-exclamation\",\"types\":[\"solid\"]},{\"key\":\"la-exclamation-circle\",\"types\":[\"solid\"]},{\"key\":\"la-exclamation-triangle\",\"types\":[\"solid\"]},{\"key\":\"la-radiation\",\"types\":[\"solid\"]},{\"key\":\"la-radiation-alt\",\"types\":[\"solid\"]},{\"key\":\"la-skull-crossbones\",\"types\":[\"solid\"]},{\"key\":\"la-cat\",\"types\":[\"solid\"]},{\"key\":\"la-crow\",\"types\":[\"solid\"]},{\"key\":\"la-dog\",\"types\":[\"solid\"]},{\"key\":\"la-dove\",\"types\":[\"solid\"]},{\"key\":\"la-dragon\",\"types\":[\"solid\"]},{\"key\":\"la-feather\",\"types\":[\"solid\"]},{\"key\":\"la-feather-alt\",\"types\":[\"solid\"]},{\"key\":\"la-fish\",\"types\":[\"solid\"]},{\"key\":\"la-frog\",\"types\":[\"solid\"]},{\"key\":\"la-hippo\",\"types\":[\"solid\"]},{\"key\":\"la-horse\",\"types\":[\"solid\"]},{\"key\":\"la-horse-head\",\"types\":[\"solid\"]},{\"key\":\"la-kiwi-bird\",\"types\":[\"solid\"]},{\"key\":\"la-otter\",\"types\":[\"solid\"]},{\"key\":\"la-paw\",\"types\":[\"solid\"]},{\"key\":\"la-spider\",\"types\":[\"solid\"]},{\"key\":\"la-angle-double-down\",\"types\":[\"solid\"]},{\"key\":\"la-angle-double-left\",\"types\":[\"solid\"]},{\"key\":\"la-angle-double-right\",\"types\":[\"solid\"]},{\"key\":\"la-angle-double-up\",\"types\":[\"solid\"]},{\"key\":\"la-angle-down\",\"types\":[\"solid\"]},{\"key\":\"la-angle-left\",\"types\":[\"solid\"]},{\"key\":\"la-angle-right\",\"types\":[\"solid\"]},{\"key\":\"la-angle-up\",\"types\":[\"solid\"]},{\"key\":\"la-arrow-alt-circle-down\",\"types\":[\"solid\"]},{\"key\":\"la-arrow-alt-circle-left\",\"types\":[\"solid\"]},{\"key\":\"la-arrow-alt-circle-right\",\"types\":[\"solid\"]},{\"key\":\"la-arrow-alt-circle-up\",\"types\":[\"solid\"]},{\"key\":\"la-arrow-circle-down\",\"types\":[\"solid\"]},{\"key\":\"la-arrow-circle-left\",\"types\":[\"solid\"]},{\"key\":\"la-arrow-circle-right\",\"types\":[\"solid\"]},{\"key\":\"la-arrow-circle-up\",\"types\":[\"solid\"]},{\"key\":\"la-arrow-down\",\"types\":[\"solid\"]},{\"key\":\"la-arrow-left\",\"types\":[\"solid\"]},{\"key\":\"la-arrow-right\",\"types\":[\"solid\"]},{\"key\":\"la-arrow-up\",\"types\":[\"solid\"]},{\"key\":\"la-arrows-alt\",\"types\":[\"solid\"]},{\"key\":\"la-arrows-alt-h\",\"types\":[\"solid\"]},{\"key\":\"la-arrows-alt-v\",\"types\":[\"solid\"]},{\"key\":\"la-caret-down\",\"types\":[\"solid\"]},{\"key\":\"la-caret-left\",\"types\":[\"solid\"]},{\"key\":\"la-caret-right\",\"types\":[\"solid\"]},{\"key\":\"la-caret-square-down\",\"types\":[\"solid\"]},{\"key\":\"la-caret-square-left\",\"types\":[\"solid\"]},{\"key\":\"la-caret-square-right\",\"types\":[\"solid\"]},{\"key\":\"la-caret-square-up\",\"types\":[\"solid\"]},{\"key\":\"la-caret-up\",\"types\":[\"solid\"]},{\"key\":\"la-cart-arrow-down\",\"types\":[\"solid\"]},{\"key\":\"la-chart-line\",\"types\":[\"solid\"]},{\"key\":\"la-chevron-circle-down\",\"types\":[\"solid\"]},{\"key\":\"la-chevron-circle-left\",\"types\":[\"solid\"]},{\"key\":\"la-chevron-circle-right\",\"types\":[\"solid\"]},{\"key\":\"la-chevron-circle-up\",\"types\":[\"solid\"]},{\"key\":\"la-chevron-down\",\"types\":[\"solid\"]},{\"key\":\"la-chevron-left\",\"types\":[\"solid\"]},{\"key\":\"la-chevron-right\",\"types\":[\"solid\"]},{\"key\":\"la-chevron-up\",\"types\":[\"solid\"]},{\"key\":\"la-cloud-download-alt\",\"types\":[\"solid\"]},{\"key\":\"la-cloud-upload-alt\",\"types\":[\"solid\"]},{\"key\":\"la-compress-arrows-alt\",\"types\":[\"solid\"]},{\"key\":\"la-download\",\"types\":[\"solid\"]},{\"key\":\"la-exchange-alt\",\"types\":[\"solid\"]},{\"key\":\"la-expand-arrows-alt\",\"types\":[\"solid\"]},{\"key\":\"la-external-link-alt\",\"types\":[\"solid\"]},{\"key\":\"la-external-link-square-alt\",\"types\":[\"solid\"]},{\"key\":\"la-hand-point-down\",\"types\":[\"solid\"]},{\"key\":\"la-hand-point-left\",\"types\":[\"solid\"]},{\"key\":\"la-hand-point-right\",\"types\":[\"solid\"]},{\"key\":\"la-hand-point-up\",\"types\":[\"solid\"]},{\"key\":\"la-hand-pointer\",\"types\":[\"solid\"]},{\"key\":\"la-history\",\"types\":[\"solid\"]},{\"key\":\"la-level-down-alt\",\"types\":[\"solid\"]},{\"key\":\"la-level-up-alt\",\"types\":[\"solid\"]},{\"key\":\"la-location-arrow\",\"types\":[\"solid\"]},{\"key\":\"la-long-arrow-alt-down\",\"types\":[\"solid\"]},{\"key\":\"la-long-arrow-alt-left\",\"types\":[\"solid\"]},{\"key\":\"la-long-arrow-alt-right\",\"types\":[\"solid\"]},{\"key\":\"la-long-arrow-alt-up\",\"types\":[\"solid\"]},{\"key\":\"la-mouse-pointer\",\"types\":[\"solid\"]},{\"key\":\"la-play\",\"types\":[\"solid\"]},{\"key\":\"la-random\",\"types\":[\"solid\"]},{\"key\":\"la-recycle\",\"types\":[\"solid\"]},{\"key\":\"la-redo\",\"types\":[\"solid\"]},{\"key\":\"la-redo-alt\",\"types\":[\"solid\"]},{\"key\":\"la-reply\",\"types\":[\"solid\"]},{\"key\":\"la-reply-all\",\"types\":[\"solid\"]},{\"key\":\"la-retweet\",\"types\":[\"solid\"]},{\"key\":\"la-share\",\"types\":[\"solid\"]},{\"key\":\"la-share-square\",\"types\":[\"solid\"]},{\"key\":\"la-sign-in-alt\",\"types\":[\"solid\"]},{\"key\":\"la-sign-out-alt\",\"types\":[\"solid\"]},{\"key\":\"la-sort\",\"types\":[\"solid\"]},{\"key\":\"la-sort-alpha-down\",\"types\":[\"solid\"]},{\"key\":\"la-sort-alpha-down-alt\",\"types\":[\"solid\"]},{\"key\":\"la-sort-alpha-up\",\"types\":[\"solid\"]},{\"key\":\"la-sort-alpha-up-alt\",\"types\":[\"solid\"]},{\"key\":\"la-sort-amount-down\",\"types\":[\"solid\"]},{\"key\":\"la-sort-amount-down-alt\",\"types\":[\"solid\"]},{\"key\":\"la-sort-amount-up\",\"types\":[\"solid\"]},{\"key\":\"la-sort-amount-up-alt\",\"types\":[\"solid\"]},{\"key\":\"la-sort-down\",\"types\":[\"solid\"]},{\"key\":\"la-sort-numeric-down\",\"types\":[\"solid\"]},{\"key\":\"la-sort-numeric-down-alt\",\"types\":[\"solid\"]},{\"key\":\"la-sort-numeric-up\",\"types\":[\"solid\"]},{\"key\":\"la-sort-numeric-up-alt\",\"types\":[\"solid\"]},{\"key\":\"la-sort-up\",\"types\":[\"solid\"]},{\"key\":\"la-sync\",\"types\":[\"solid\"]},{\"key\":\"la-sync-alt\",\"types\":[\"solid\"]},{\"key\":\"la-text-height\",\"types\":[\"solid\"]},{\"key\":\"la-text-width\",\"types\":[\"solid\"]},{\"key\":\"la-undo\",\"types\":[\"solid\"]},{\"key\":\"la-undo-alt\",\"types\":[\"solid\"]},{\"key\":\"la-upload\",\"types\":[\"solid\"]},{\"key\":\"la-backward\",\"types\":[\"solid\"]},{\"key\":\"la-broadcast-tower\",\"types\":[\"solid\"]},{\"key\":\"la-circle\",\"types\":[\"solid\"]},{\"key\":\"la-compress\",\"types\":[\"solid\"]},{\"key\":\"la-eject\",\"types\":[\"solid\"]},{\"key\":\"la-expand\",\"types\":[\"solid\"]},{\"key\":\"la-fast-backward\",\"types\":[\"solid\"]},{\"key\":\"la-fast-forward\",\"types\":[\"solid\"]},{\"key\":\"la-file-audio\",\"types\":[\"solid\"]},{\"key\":\"la-file-video\",\"types\":[\"solid\"]},{\"key\":\"la-film\",\"types\":[\"solid\"]},{\"key\":\"la-forward\",\"types\":[\"solid\"]},{\"key\":\"la-headphones\",\"types\":[\"solid\"]},{\"key\":\"la-microphone\",\"types\":[\"solid\"]},{\"key\":\"la-microphone-alt\",\"types\":[\"solid\"]},{\"key\":\"la-microphone-alt-slash\",\"types\":[\"solid\"]},{\"key\":\"la-microphone-slash\",\"types\":[\"solid\"]},{\"key\":\"la-music\",\"types\":[\"solid\"]},{\"key\":\"la-pause\",\"types\":[\"solid\"]},{\"key\":\"la-pause-circle\",\"types\":[\"solid\"]},{\"key\":\"la-photo-video\",\"types\":[\"solid\"]},{\"key\":\"la-play-circle\",\"types\":[\"solid\"]},{\"key\":\"la-podcast\",\"types\":[\"solid\"]},{\"key\":\"la-rss\",\"types\":[\"solid\"]},{\"key\":\"la-rss-square\",\"types\":[\"solid\"]},{\"key\":\"la-step-backward\",\"types\":[\"solid\"]},{\"key\":\"la-step-forward\",\"types\":[\"solid\"]},{\"key\":\"la-stop\",\"types\":[\"solid\"]},{\"key\":\"la-stop-circle\",\"types\":[\"solid\"]},{\"key\":\"la-tv\",\"types\":[\"solid\"]},{\"key\":\"la-video\",\"types\":[\"solid\"]},{\"key\":\"la-volume-down\",\"types\":[\"solid\"]},{\"key\":\"la-volume-mute\",\"types\":[\"solid\"]},{\"key\":\"la-volume-off\",\"types\":[\"solid\"]},{\"key\":\"la-volume-up\",\"types\":[\"solid\"]},{\"key\":\"la-air-freshener\",\"types\":[\"solid\"]},{\"key\":\"la-ambulance\",\"types\":[\"solid\"]},{\"key\":\"la-bus\",\"types\":[\"solid\"]},{\"key\":\"la-bus-alt\",\"types\":[\"solid\"]},{\"key\":\"la-car\",\"types\":[\"solid\"]},{\"key\":\"la-car-alt\",\"types\":[\"solid\"]},{\"key\":\"la-car-battery\",\"types\":[\"solid\"]},{\"key\":\"la-car-crash\",\"types\":[\"solid\"]},{\"key\":\"la-car-side\",\"types\":[\"solid\"]},{\"key\":\"la-charging-station\",\"types\":[\"solid\"]},{\"key\":\"la-gas-pump\",\"types\":[\"solid\"]},{\"key\":\"la-motorcycle\",\"types\":[\"solid\"]},{\"key\":\"la-oil-can\",\"types\":[\"solid\"]},{\"key\":\"la-shuttle-van\",\"types\":[\"solid\"]},{\"key\":\"la-tachometer-alt\",\"types\":[\"solid\"]},{\"key\":\"la-taxi\",\"types\":[\"solid\"]},{\"key\":\"la-truck\",\"types\":[\"solid\"]},{\"key\":\"la-truck-monster\",\"types\":[\"solid\"]},{\"key\":\"la-truck-pickup\",\"types\":[\"solid\"]},{\"key\":\"la-apple-alt\",\"types\":[\"solid\"]},{\"key\":\"la-campground\",\"types\":[\"solid\"]},{\"key\":\"la-cloud-sun\",\"types\":[\"solid\"]},{\"key\":\"la-drumstick-bite\",\"types\":[\"solid\"]},{\"key\":\"la-football-ball\",\"types\":[\"solid\"]},{\"key\":\"la-hiking\",\"types\":[\"solid\"]},{\"key\":\"la-mountain\",\"types\":[\"solid\"]},{\"key\":\"la-tractor\",\"types\":[\"solid\"]},{\"key\":\"la-tree\",\"types\":[\"solid\"]},{\"key\":\"la-wind\",\"types\":[\"solid\"]},{\"key\":\"la-wine-bottle\",\"types\":[\"solid\"]},{\"key\":\"la-beer\",\"types\":[\"solid\"]},{\"key\":\"la-blender\",\"types\":[\"solid\"]},{\"key\":\"la-cocktail\",\"types\":[\"solid\"]},{\"key\":\"la-coffee\",\"types\":[\"solid\"]},{\"key\":\"la-flask\",\"types\":[\"solid\"]},{\"key\":\"la-glass-cheers\",\"types\":[\"solid\"]},{\"key\":\"la-glass-martini\",\"types\":[\"solid\"]},{\"key\":\"la-glass-martini-alt\",\"types\":[\"solid\"]},{\"key\":\"la-glass-whiskey\",\"types\":[\"solid\"]},{\"key\":\"la-mug-hot\",\"types\":[\"solid\"]},{\"key\":\"la-wine-glass\",\"types\":[\"solid\"]},{\"key\":\"la-wine-glass-alt\",\"types\":[\"solid\"]},{\"key\":\"la-archway\",\"types\":[\"solid\"]},{\"key\":\"la-building\",\"types\":[\"solid\"]},{\"key\":\"la-church\",\"types\":[\"solid\"]},{\"key\":\"la-city\",\"types\":[\"solid\"]},{\"key\":\"la-clinic-medical\",\"types\":[\"solid\"]},{\"key\":\"la-dungeon\",\"types\":[\"solid\"]},{\"key\":\"la-gopuram\",\"types\":[\"solid\"]},{\"key\":\"la-home\",\"types\":[\"solid\"]},{\"key\":\"la-hospital\",\"types\":[\"solid\"]},{\"key\":\"la-hospital-alt\",\"types\":[\"solid\"]},{\"key\":\"la-hotel\",\"types\":[\"solid\"]},{\"key\":\"la-house-damage\",\"types\":[\"solid\"]},{\"key\":\"la-igloo\",\"types\":[\"solid\"]},{\"key\":\"la-industry\",\"types\":[\"solid\"]},{\"key\":\"la-kaaba\",\"types\":[\"solid\"]},{\"key\":\"la-landmark\",\"types\":[\"solid\"]},{\"key\":\"la-monument\",\"types\":[\"solid\"]},{\"key\":\"la-mosque\",\"types\":[\"solid\"]},{\"key\":\"la-place-of-worship\",\"types\":[\"solid\"]},{\"key\":\"la-school\",\"types\":[\"solid\"]},{\"key\":\"la-store\",\"types\":[\"solid\"]},{\"key\":\"la-store-alt\",\"types\":[\"solid\"]},{\"key\":\"la-synagogue\",\"types\":[\"solid\"]},{\"key\":\"la-torii-gate\",\"types\":[\"solid\"]},{\"key\":\"la-university\",\"types\":[\"solid\"]},{\"key\":\"la-vihara\",\"types\":[\"solid\"]},{\"key\":\"la-warehouse\",\"types\":[\"solid\"]},{\"key\":\"la-address-book\",\"types\":[\"solid\"]},{\"key\":\"la-address-card\",\"types\":[\"solid\"]},{\"key\":\"la-archive\",\"types\":[\"solid\"]},{\"key\":\"la-balance-scale\",\"types\":[\"solid\"]},{\"key\":\"la-balance-scale-left\",\"types\":[\"solid\"]},{\"key\":\"la-balance-scale-right\",\"types\":[\"solid\"]},{\"key\":\"la-birthday-cake\",\"types\":[\"solid\"]},{\"key\":\"la-book\",\"types\":[\"solid\"]},{\"key\":\"la-briefcase\",\"types\":[\"solid\"]},{\"key\":\"la-bullhorn\",\"types\":[\"solid\"]},{\"key\":\"la-bullseye\",\"types\":[\"solid\"]},{\"key\":\"la-business-time\",\"types\":[\"solid\"]},{\"key\":\"la-calculator\",\"types\":[\"solid\"]},{\"key\":\"la-calendar\",\"types\":[\"solid\"]},{\"key\":\"la-calendar-alt\",\"types\":[\"solid\"]},{\"key\":\"la-certificate\",\"types\":[\"solid\"]},{\"key\":\"la-chart-area\",\"types\":[\"solid\"]},{\"key\":\"la-chart-bar\",\"types\":[\"solid\"]},{\"key\":\"la-chart-pie\",\"types\":[\"solid\"]},{\"key\":\"la-clipboard\",\"types\":[\"solid\"]},{\"key\":\"la-columns\",\"types\":[\"solid\"]},{\"key\":\"la-compass\",\"types\":[\"solid\"]},{\"key\":\"la-copy\",\"types\":[\"solid\"]},{\"key\":\"la-copyright\",\"types\":[\"solid\"]},{\"key\":\"la-cut\",\"types\":[\"solid\"]},{\"key\":\"la-edit\",\"types\":[\"solid\"]},{\"key\":\"la-envelope\",\"types\":[\"solid\"]},{\"key\":\"la-envelope-open\",\"types\":[\"solid\"]},{\"key\":\"la-envelope-square\",\"types\":[\"solid\"]},{\"key\":\"la-eraser\",\"types\":[\"solid\"]},{\"key\":\"la-fax\",\"types\":[\"solid\"]},{\"key\":\"la-file\",\"types\":[\"solid\"]},{\"key\":\"la-file-alt\",\"types\":[\"solid\"]},{\"key\":\"la-folder\",\"types\":[\"solid\"]},{\"key\":\"la-folder-minus\",\"types\":[\"solid\"]},{\"key\":\"la-folder-open\",\"types\":[\"solid\"]},{\"key\":\"la-folder-plus\",\"types\":[\"solid\"]},{\"key\":\"la-glasses\",\"types\":[\"solid\"]},{\"key\":\"la-globe\",\"types\":[\"solid\"]},{\"key\":\"la-highlighter\",\"types\":[\"solid\"]},{\"key\":\"la-marker\",\"types\":[\"solid\"]},{\"key\":\"la-paperclip\",\"types\":[\"solid\"]},{\"key\":\"la-paste\",\"types\":[\"solid\"]},{\"key\":\"la-pen\",\"types\":[\"solid\"]},{\"key\":\"la-pen-alt\",\"types\":[\"solid\"]},{\"key\":\"la-pen-fancy\",\"types\":[\"solid\"]},{\"key\":\"la-pen-nib\",\"types\":[\"solid\"]},{\"key\":\"la-pen-square\",\"types\":[\"solid\"]},{\"key\":\"la-pencil-alt\",\"types\":[\"solid\"]},{\"key\":\"la-percent\",\"types\":[\"solid\"]},{\"key\":\"la-phone\",\"types\":[\"solid\"]},{\"key\":\"la-phone-alt\",\"types\":[\"solid\"]},{\"key\":\"la-phone-slash\",\"types\":[\"solid\"]},{\"key\":\"la-phone-square\",\"types\":[\"solid\"]},{\"key\":\"la-phone-square-alt\",\"types\":[\"solid\"]},{\"key\":\"la-print\",\"types\":[\"solid\"]},{\"key\":\"la-project-diagram\",\"types\":[\"solid\"]},{\"key\":\"la-registered\",\"types\":[\"solid\"]},{\"key\":\"la-save\",\"types\":[\"solid\"]},{\"key\":\"la-sitemap\",\"types\":[\"solid\"]},{\"key\":\"la-socks\",\"types\":[\"solid\"]},{\"key\":\"la-sticky-note\",\"types\":[\"solid\"]},{\"key\":\"la-stream\",\"types\":[\"solid\"]},{\"key\":\"la-table\",\"types\":[\"solid\"]},{\"key\":\"la-tag\",\"types\":[\"solid\"]},{\"key\":\"la-tags\",\"types\":[\"solid\"]},{\"key\":\"la-tasks\",\"types\":[\"solid\"]},{\"key\":\"la-thumbtack\",\"types\":[\"solid\"]},{\"key\":\"la-trademark\",\"types\":[\"solid\"]},{\"key\":\"la-wallet\",\"types\":[\"solid\"]},{\"key\":\"la-binoculars\",\"types\":[\"solid\"]},{\"key\":\"la-fire\",\"types\":[\"solid\"]},{\"key\":\"la-fire-alt\",\"types\":[\"solid\"]},{\"key\":\"la-first-aid\",\"types\":[\"solid\"]},{\"key\":\"la-map\",\"types\":[\"solid\"]},{\"key\":\"la-map-marked\",\"types\":[\"solid\"]},{\"key\":\"la-map-marked-alt\",\"types\":[\"solid\"]},{\"key\":\"la-map-signs\",\"types\":[\"solid\"]},{\"key\":\"la-route\",\"types\":[\"solid\"]},{\"key\":\"la-toilet-paper\",\"types\":[\"solid\"]},{\"key\":\"la-dollar-sign\",\"types\":[\"solid\"]},{\"key\":\"la-donate\",\"types\":[\"solid\"]},{\"key\":\"la-gift\",\"types\":[\"solid\"]},{\"key\":\"la-hand-holding-heart\",\"types\":[\"solid\"]},{\"key\":\"la-hand-holding-usd\",\"types\":[\"solid\"]},{\"key\":\"la-hands-helping\",\"types\":[\"solid\"]},{\"key\":\"la-handshake\",\"types\":[\"solid\"]},{\"key\":\"la-heart\",\"types\":[\"solid\"]},{\"key\":\"la-leaf\",\"types\":[\"solid\"]},{\"key\":\"la-parachute-box\",\"types\":[\"solid\"]},{\"key\":\"la-piggy-bank\",\"types\":[\"solid\"]},{\"key\":\"la-ribbon\",\"types\":[\"solid\"]},{\"key\":\"la-seedling\",\"types\":[\"solid\"]},{\"key\":\"la-comment\",\"types\":[\"solid\"]},{\"key\":\"la-comment-alt\",\"types\":[\"solid\"]},{\"key\":\"la-comment-dots\",\"types\":[\"solid\"]},{\"key\":\"la-comment-medical\",\"types\":[\"solid\"]},{\"key\":\"la-comment-slash\",\"types\":[\"solid\"]},{\"key\":\"la-comments\",\"types\":[\"solid\"]},{\"key\":\"la-frown\",\"types\":[\"solid\"]},{\"key\":\"la-icons\",\"types\":[\"solid\"]},{\"key\":\"la-meh\",\"types\":[\"solid\"]},{\"key\":\"la-poo\",\"types\":[\"solid\"]},{\"key\":\"la-quote-left\",\"types\":[\"solid\"]},{\"key\":\"la-quote-right\",\"types\":[\"solid\"]},{\"key\":\"la-smile\",\"types\":[\"solid\"]},{\"key\":\"la-sms\",\"types\":[\"solid\"]},{\"key\":\"la-video-slash\",\"types\":[\"solid\"]},{\"key\":\"la-chess\",\"types\":[\"solid\"]},{\"key\":\"la-chess-bishop\",\"types\":[\"solid\"]},{\"key\":\"la-chess-board\",\"types\":[\"solid\"]},{\"key\":\"la-chess-king\",\"types\":[\"solid\"]},{\"key\":\"la-chess-knight\",\"types\":[\"solid\"]},{\"key\":\"la-chess-pawn\",\"types\":[\"solid\"]},{\"key\":\"la-chess-queen\",\"types\":[\"solid\"]},{\"key\":\"la-chess-rook\",\"types\":[\"solid\"]},{\"key\":\"la-square-full\",\"types\":[\"solid\"]},{\"key\":\"la-baby\",\"types\":[\"solid\"]},{\"key\":\"la-baby-carriage\",\"types\":[\"solid\"]},{\"key\":\"la-bath\",\"types\":[\"solid\"]},{\"key\":\"la-biking\",\"types\":[\"solid\"]},{\"key\":\"la-cookie\",\"types\":[\"solid\"]},{\"key\":\"la-cookie-bite\",\"types\":[\"solid\"]},{\"key\":\"la-gamepad\",\"types\":[\"solid\"]},{\"key\":\"la-ice-cream\",\"types\":[\"solid\"]},{\"key\":\"la-mitten\",\"types\":[\"solid\"]},{\"key\":\"la-robot\",\"types\":[\"solid\"]},{\"key\":\"la-shapes\",\"types\":[\"solid\"]},{\"key\":\"la-snowman\",\"types\":[\"solid\"]},{\"key\":\"la-graduation-cap\",\"types\":[\"solid\"]},{\"key\":\"la-hat-cowboy\",\"types\":[\"solid\"]},{\"key\":\"la-hat-cowboy-side\",\"types\":[\"solid\"]},{\"key\":\"la-hat-wizard\",\"types\":[\"solid\"]},{\"key\":\"la-shoe-prints\",\"types\":[\"solid\"]},{\"key\":\"la-tshirt\",\"types\":[\"solid\"]},{\"key\":\"la-user-tie\",\"types\":[\"solid\"]},{\"key\":\"la-barcode\",\"types\":[\"solid\"]},{\"key\":\"la-bug\",\"types\":[\"solid\"]},{\"key\":\"la-code\",\"types\":[\"solid\"]},{\"key\":\"la-code-branch\",\"types\":[\"solid\"]},{\"key\":\"la-file-code\",\"types\":[\"solid\"]},{\"key\":\"la-filter\",\"types\":[\"solid\"]},{\"key\":\"la-fire-extinguisher\",\"types\":[\"solid\"]},{\"key\":\"la-keyboard\",\"types\":[\"solid\"]},{\"key\":\"la-laptop-code\",\"types\":[\"solid\"]},{\"key\":\"la-microchip\",\"types\":[\"solid\"]},{\"key\":\"la-qrcode\",\"types\":[\"solid\"]},{\"key\":\"la-shield-alt\",\"types\":[\"solid\"]},{\"key\":\"la-terminal\",\"types\":[\"solid\"]},{\"key\":\"la-user-secret\",\"types\":[\"solid\"]},{\"key\":\"la-window-close\",\"types\":[\"solid\"]},{\"key\":\"la-window-maximize\",\"types\":[\"solid\"]},{\"key\":\"la-window-minimize\",\"types\":[\"solid\"]},{\"key\":\"la-window-restore\",\"types\":[\"solid\"]},{\"key\":\"la-at\",\"types\":[\"solid\"]},{\"key\":\"la-chalkboard\",\"types\":[\"solid\"]},{\"key\":\"la-inbox\",\"types\":[\"solid\"]},{\"key\":\"la-language\",\"types\":[\"solid\"]},{\"key\":\"la-mobile\",\"types\":[\"solid\"]},{\"key\":\"la-mobile-alt\",\"types\":[\"solid\"]},{\"key\":\"la-paper-plane\",\"types\":[\"solid\"]},{\"key\":\"la-voicemail\",\"types\":[\"solid\"]},{\"key\":\"la-wifi\",\"types\":[\"solid\"]},{\"key\":\"la-database\",\"types\":[\"solid\"]},{\"key\":\"la-desktop\",\"types\":[\"solid\"]},{\"key\":\"la-ethernet\",\"types\":[\"solid\"]},{\"key\":\"la-hdd\",\"types\":[\"solid\"]},{\"key\":\"la-laptop\",\"types\":[\"solid\"]},{\"key\":\"la-memory\",\"types\":[\"solid\"]},{\"key\":\"la-mouse\",\"types\":[\"solid\"]},{\"key\":\"la-plug\",\"types\":[\"solid\"]},{\"key\":\"la-power-off\",\"types\":[\"solid\"]},{\"key\":\"la-satellite\",\"types\":[\"solid\"]},{\"key\":\"la-satellite-dish\",\"types\":[\"solid\"]},{\"key\":\"la-sd-card\",\"types\":[\"solid\"]},{\"key\":\"la-server\",\"types\":[\"solid\"]},{\"key\":\"la-sim-card\",\"types\":[\"solid\"]},{\"key\":\"la-tablet\",\"types\":[\"solid\"]},{\"key\":\"la-tablet-alt\",\"types\":[\"solid\"]},{\"key\":\"la-brush\",\"types\":[\"solid\"]},{\"key\":\"la-drafting-compass\",\"types\":[\"solid\"]},{\"key\":\"la-dumpster\",\"types\":[\"solid\"]},{\"key\":\"la-hammer\",\"types\":[\"solid\"]},{\"key\":\"la-hard-hat\",\"types\":[\"solid\"]},{\"key\":\"la-paint-roller\",\"types\":[\"solid\"]},{\"key\":\"la-pencil-ruler\",\"types\":[\"solid\"]},{\"key\":\"la-ruler\",\"types\":[\"solid\"]},{\"key\":\"la-ruler-combined\",\"types\":[\"solid\"]},{\"key\":\"la-ruler-horizontal\",\"types\":[\"solid\"]},{\"key\":\"la-ruler-vertical\",\"types\":[\"solid\"]},{\"key\":\"la-screwdriver\",\"types\":[\"solid\"]},{\"key\":\"la-toolbox\",\"types\":[\"solid\"]},{\"key\":\"la-tools\",\"types\":[\"solid\"]},{\"key\":\"la-wrench\",\"types\":[\"solid\"]},{\"key\":\"la-euro-sign\",\"types\":[\"solid\"]},{\"key\":\"la-hryvnia\",\"types\":[\"solid\"]},{\"key\":\"la-lira-sign\",\"types\":[\"solid\"]},{\"key\":\"la-money-bill\",\"types\":[\"solid\"]},{\"key\":\"la-money-bill-alt\",\"types\":[\"solid\"]},{\"key\":\"la-money-bill-wave\",\"types\":[\"solid\"]},{\"key\":\"la-money-bill-wave-alt\",\"types\":[\"solid\"]},{\"key\":\"la-money-check\",\"types\":[\"solid\"]},{\"key\":\"la-money-check-alt\",\"types\":[\"solid\"]},{\"key\":\"la-pound-sign\",\"types\":[\"solid\"]},{\"key\":\"la-ruble-sign\",\"types\":[\"solid\"]},{\"key\":\"la-rupee-sign\",\"types\":[\"solid\"]},{\"key\":\"la-shekel-sign\",\"types\":[\"solid\"]},{\"key\":\"la-tenge\",\"types\":[\"solid\"]},{\"key\":\"la-won-sign\",\"types\":[\"solid\"]},{\"key\":\"la-yen-sign\",\"types\":[\"solid\"]},{\"key\":\"la-calendar-check\",\"types\":[\"solid\"]},{\"key\":\"la-calendar-minus\",\"types\":[\"solid\"]},{\"key\":\"la-calendar-plus\",\"types\":[\"solid\"]},{\"key\":\"la-calendar-times\",\"types\":[\"solid\"]},{\"key\":\"la-clock\",\"types\":[\"solid\"]},{\"key\":\"la-hourglass\",\"types\":[\"solid\"]},{\"key\":\"la-hourglass-end\",\"types\":[\"solid\"]},{\"key\":\"la-hourglass-half\",\"types\":[\"solid\"]},{\"key\":\"la-hourglass-start\",\"types\":[\"solid\"]},{\"key\":\"la-stopwatch\",\"types\":[\"solid\"]},{\"key\":\"la-adjust\",\"types\":[\"solid\"]},{\"key\":\"la-bezier-curve\",\"types\":[\"solid\"]},{\"key\":\"la-clone\",\"types\":[\"solid\"]},{\"key\":\"la-crop\",\"types\":[\"solid\"]},{\"key\":\"la-crop-alt\",\"types\":[\"solid\"]},{\"key\":\"la-crosshairs\",\"types\":[\"solid\"]},{\"key\":\"la-draw-polygon\",\"types\":[\"solid\"]},{\"key\":\"la-eye\",\"types\":[\"solid\"]},{\"key\":\"la-eye-dropper\",\"types\":[\"solid\"]},{\"key\":\"la-eye-slash\",\"types\":[\"solid\"]},{\"key\":\"la-fill\",\"types\":[\"solid\"]},{\"key\":\"la-fill-drip\",\"types\":[\"solid\"]},{\"key\":\"la-layer-group\",\"types\":[\"solid\"]},{\"key\":\"la-magic\",\"types\":[\"solid\"]},{\"key\":\"la-object-group\",\"types\":[\"solid\"]},{\"key\":\"la-object-ungroup\",\"types\":[\"solid\"]},{\"key\":\"la-paint-brush\",\"types\":[\"solid\"]},{\"key\":\"la-palette\",\"types\":[\"solid\"]},{\"key\":\"la-splotch\",\"types\":[\"solid\"]},{\"key\":\"la-spray-can\",\"types\":[\"solid\"]},{\"key\":\"la-stamp\",\"types\":[\"solid\"]},{\"key\":\"la-swatchbook\",\"types\":[\"solid\"]},{\"key\":\"la-tint\",\"types\":[\"solid\"]},{\"key\":\"la-tint-slash\",\"types\":[\"solid\"]},{\"key\":\"la-vector-square\",\"types\":[\"solid\"]},{\"key\":\"la-align-center\",\"types\":[\"solid\"]},{\"key\":\"la-align-justify\",\"types\":[\"solid\"]},{\"key\":\"la-align-left\",\"types\":[\"solid\"]},{\"key\":\"la-align-right\",\"types\":[\"solid\"]},{\"key\":\"la-bold\",\"types\":[\"solid\"]},{\"key\":\"la-border-all\",\"types\":[\"solid\"]},{\"key\":\"la-border-none\",\"types\":[\"solid\"]},{\"key\":\"la-border-style\",\"types\":[\"solid\"]},{\"key\":\"la-font\",\"types\":[\"solid\"]},{\"key\":\"la-heading\",\"types\":[\"solid\"]},{\"key\":\"la-i-cursor\",\"types\":[\"solid\"]},{\"key\":\"la-indent\",\"types\":[\"solid\"]},{\"key\":\"la-italic\",\"types\":[\"solid\"]},{\"key\":\"la-link\",\"types\":[\"solid\"]},{\"key\":\"la-list\",\"types\":[\"solid\"]},{\"key\":\"la-list-alt\",\"types\":[\"solid\"]},{\"key\":\"la-list-ol\",\"types\":[\"solid\"]},{\"key\":\"la-list-ul\",\"types\":[\"solid\"]},{\"key\":\"la-outdent\",\"types\":[\"solid\"]},{\"key\":\"la-paragraph\",\"types\":[\"solid\"]},{\"key\":\"la-remove-format\",\"types\":[\"solid\"]},{\"key\":\"la-spell-check\",\"types\":[\"solid\"]},{\"key\":\"la-strikethrough\",\"types\":[\"solid\"]},{\"key\":\"la-subscript\",\"types\":[\"solid\"]},{\"key\":\"la-superscript\",\"types\":[\"solid\"]},{\"key\":\"la-th\",\"types\":[\"solid\"]},{\"key\":\"la-th-large\",\"types\":[\"solid\"]},{\"key\":\"la-th-list\",\"types\":[\"solid\"]},{\"key\":\"la-trash\",\"types\":[\"solid\"]},{\"key\":\"la-trash-alt\",\"types\":[\"solid\"]},{\"key\":\"la-trash-restore\",\"types\":[\"solid\"]},{\"key\":\"la-trash-restore-alt\",\"types\":[\"solid\"]},{\"key\":\"la-underline\",\"types\":[\"solid\"]},{\"key\":\"la-unlink\",\"types\":[\"solid\"]},{\"key\":\"la-atom\",\"types\":[\"solid\"]},{\"key\":\"la-award\",\"types\":[\"solid\"]},{\"key\":\"la-book-open\",\"types\":[\"solid\"]},{\"key\":\"la-book-reader\",\"types\":[\"solid\"]},{\"key\":\"la-chalkboard-teacher\",\"types\":[\"solid\"]},{\"key\":\"la-microscope\",\"types\":[\"solid\"]},{\"key\":\"la-theater-masks\",\"types\":[\"solid\"]},{\"key\":\"la-user-graduate\",\"types\":[\"solid\"]},{\"key\":\"la-angry\",\"types\":[\"solid\"]},{\"key\":\"la-dizzy\",\"types\":[\"solid\"]},{\"key\":\"la-flushed\",\"types\":[\"solid\"]},{\"key\":\"la-frown-open\",\"types\":[\"solid\"]},{\"key\":\"la-grimace\",\"types\":[\"solid\"]},{\"key\":\"la-grin\",\"types\":[\"solid\"]},{\"key\":\"la-grin-alt\",\"types\":[\"solid\"]},{\"key\":\"la-grin-beam\",\"types\":[\"solid\"]},{\"key\":\"la-grin-beam-sweat\",\"types\":[\"solid\"]},{\"key\":\"la-grin-hearts\",\"types\":[\"solid\"]},{\"key\":\"la-grin-squint\",\"types\":[\"solid\"]},{\"key\":\"la-grin-squint-tears\",\"types\":[\"solid\"]},{\"key\":\"la-grin-stars\",\"types\":[\"solid\"]},{\"key\":\"la-grin-tears\",\"types\":[\"solid\"]},{\"key\":\"la-grin-tongue\",\"types\":[\"solid\"]},{\"key\":\"la-grin-tongue-squint\",\"types\":[\"solid\"]},{\"key\":\"la-grin-tongue-wink\",\"types\":[\"solid\"]},{\"key\":\"la-grin-wink\",\"types\":[\"solid\"]},{\"key\":\"la-kiss\",\"types\":[\"solid\"]},{\"key\":\"la-kiss-beam\",\"types\":[\"solid\"]},{\"key\":\"la-kiss-wink-heart\",\"types\":[\"solid\"]},{\"key\":\"la-laugh\",\"types\":[\"solid\"]},{\"key\":\"la-laugh-beam\",\"types\":[\"solid\"]},{\"key\":\"la-laugh-squint\",\"types\":[\"solid\"]},{\"key\":\"la-laugh-wink\",\"types\":[\"solid\"]},{\"key\":\"la-meh-blank\",\"types\":[\"solid\"]},{\"key\":\"la-meh-rolling-eyes\",\"types\":[\"solid\"]},{\"key\":\"la-sad-cry\",\"types\":[\"solid\"]},{\"key\":\"la-sad-tear\",\"types\":[\"solid\"]},{\"key\":\"la-smile-beam\",\"types\":[\"solid\"]},{\"key\":\"la-smile-wink\",\"types\":[\"solid\"]},{\"key\":\"la-surprise\",\"types\":[\"solid\"]},{\"key\":\"la-tired\",\"types\":[\"solid\"]},{\"key\":\"la-battery-empty\",\"types\":[\"solid\"]},{\"key\":\"la-battery-full\",\"types\":[\"solid\"]},{\"key\":\"la-battery-half\",\"types\":[\"solid\"]},{\"key\":\"la-battery-quarter\",\"types\":[\"solid\"]},{\"key\":\"la-battery-three-quarters\",\"types\":[\"solid\"]},{\"key\":\"la-burn\",\"types\":[\"solid\"]},{\"key\":\"la-lightbulb\",\"types\":[\"solid\"]},{\"key\":\"la-poop\",\"types\":[\"solid\"]},{\"key\":\"la-solar-panel\",\"types\":[\"solid\"]},{\"key\":\"la-sun\",\"types\":[\"solid\"]},{\"key\":\"la-water\",\"types\":[\"solid\"]},{\"key\":\"la-file-archive\",\"types\":[\"solid\"]},{\"key\":\"la-file-excel\",\"types\":[\"solid\"]},{\"key\":\"la-file-image\",\"types\":[\"solid\"]},{\"key\":\"la-file-pdf\",\"types\":[\"solid\"]},{\"key\":\"la-file-powerpoint\",\"types\":[\"solid\"]},{\"key\":\"la-file-word\",\"types\":[\"solid\"]},{\"key\":\"la-cash-register\",\"types\":[\"solid\"]},{\"key\":\"la-coins\",\"types\":[\"solid\"]},{\"key\":\"la-comment-dollar\",\"types\":[\"solid\"]},{\"key\":\"la-comments-dollar\",\"types\":[\"solid\"]},{\"key\":\"la-credit-card\",\"types\":[\"solid\"]},{\"key\":\"la-file-invoice\",\"types\":[\"solid\"]},{\"key\":\"la-file-invoice-dollar\",\"types\":[\"solid\"]},{\"key\":\"la-percentage\",\"types\":[\"solid\"]},{\"key\":\"la-receipt\",\"types\":[\"solid\"]},{\"key\":\"la-bicycle\",\"types\":[\"solid\"]},{\"key\":\"la-heartbeat\",\"types\":[\"solid\"]},{\"key\":\"la-running\",\"types\":[\"solid\"]},{\"key\":\"la-skating\",\"types\":[\"solid\"]},{\"key\":\"la-skiing\",\"types\":[\"solid\"]},{\"key\":\"la-skiing-nordic\",\"types\":[\"solid\"]},{\"key\":\"la-snowboarding\",\"types\":[\"solid\"]},{\"key\":\"la-spa\",\"types\":[\"solid\"]},{\"key\":\"la-swimmer\",\"types\":[\"solid\"]},{\"key\":\"la-walking\",\"types\":[\"solid\"]},{\"key\":\"la-bacon\",\"types\":[\"solid\"]},{\"key\":\"la-bone\",\"types\":[\"solid\"]},{\"key\":\"la-bread-slice\",\"types\":[\"solid\"]},{\"key\":\"la-candy-cane\",\"types\":[\"solid\"]},{\"key\":\"la-carrot\",\"types\":[\"solid\"]},{\"key\":\"la-cheese\",\"types\":[\"solid\"]},{\"key\":\"la-cloud-meatball\",\"types\":[\"solid\"]},{\"key\":\"la-egg\",\"types\":[\"solid\"]},{\"key\":\"la-hamburger\",\"types\":[\"solid\"]},{\"key\":\"la-hotdog\",\"types\":[\"solid\"]},{\"key\":\"la-lemon\",\"types\":[\"solid\"]},{\"key\":\"la-pepper-hot\",\"types\":[\"solid\"]},{\"key\":\"la-pizza-slice\",\"types\":[\"solid\"]},{\"key\":\"la-stroopwafel\",\"types\":[\"solid\"]},{\"key\":\"la-dice\",\"types\":[\"solid\"]},{\"key\":\"la-dice-d20\",\"types\":[\"solid\"]},{\"key\":\"la-dice-d6\",\"types\":[\"solid\"]},{\"key\":\"la-dice-five\",\"types\":[\"solid\"]},{\"key\":\"la-dice-four\",\"types\":[\"solid\"]},{\"key\":\"la-dice-one\",\"types\":[\"solid\"]},{\"key\":\"la-dice-six\",\"types\":[\"solid\"]},{\"key\":\"la-dice-three\",\"types\":[\"solid\"]},{\"key\":\"la-dice-two\",\"types\":[\"solid\"]},{\"key\":\"la-ghost\",\"types\":[\"solid\"]},{\"key\":\"la-headset\",\"types\":[\"solid\"]},{\"key\":\"la-puzzle-piece\",\"types\":[\"solid\"]},{\"key\":\"la-genderless\",\"types\":[\"solid\"]},{\"key\":\"la-mars\",\"types\":[\"solid\"]},{\"key\":\"la-mars-double\",\"types\":[\"solid\"]},{\"key\":\"la-mars-stroke\",\"types\":[\"solid\"]},{\"key\":\"la-mars-stroke-h\",\"types\":[\"solid\"]},{\"key\":\"la-mars-stroke-v\",\"types\":[\"solid\"]},{\"key\":\"la-mercury\",\"types\":[\"solid\"]},{\"key\":\"la-neuter\",\"types\":[\"solid\"]},{\"key\":\"la-transgender\",\"types\":[\"solid\"]},{\"key\":\"la-transgender-alt\",\"types\":[\"solid\"]},{\"key\":\"la-venus\",\"types\":[\"solid\"]},{\"key\":\"la-venus-double\",\"types\":[\"solid\"]},{\"key\":\"la-venus-mars\",\"types\":[\"solid\"]},{\"key\":\"la-book-dead\",\"types\":[\"solid\"]},{\"key\":\"la-broom\",\"types\":[\"solid\"]},{\"key\":\"la-cloud-moon\",\"types\":[\"solid\"]},{\"key\":\"la-mask\",\"types\":[\"solid\"]},{\"key\":\"la-allergies\",\"types\":[\"solid\"]},{\"key\":\"la-fist-raised\",\"types\":[\"solid\"]},{\"key\":\"la-hand-holding\",\"types\":[\"solid\"]},{\"key\":\"la-hand-lizard\",\"types\":[\"solid\"]},{\"key\":\"la-hand-middle-finger\",\"types\":[\"solid\"]},{\"key\":\"la-hand-paper\",\"types\":[\"solid\"]},{\"key\":\"la-hand-peace\",\"types\":[\"solid\"]},{\"key\":\"la-hand-rock\",\"types\":[\"solid\"]},{\"key\":\"la-hand-scissors\",\"types\":[\"solid\"]},{\"key\":\"la-hand-spock\",\"types\":[\"solid\"]},{\"key\":\"la-hands\",\"types\":[\"solid\"]},{\"key\":\"la-praying-hands\",\"types\":[\"solid\"]},{\"key\":\"la-thumbs-down\",\"types\":[\"solid\"]},{\"key\":\"la-thumbs-up\",\"types\":[\"solid\"]},{\"key\":\"la-h-square\",\"types\":[\"solid\"]},{\"key\":\"la-medkit\",\"types\":[\"solid\"]},{\"key\":\"la-plus-square\",\"types\":[\"solid\"]},{\"key\":\"la-prescription\",\"types\":[\"solid\"]},{\"key\":\"la-stethoscope\",\"types\":[\"solid\"]},{\"key\":\"la-user-md\",\"types\":[\"solid\"]},{\"key\":\"la-gifts\",\"types\":[\"solid\"]},{\"key\":\"la-holly-berry\",\"types\":[\"solid\"]},{\"key\":\"la-sleigh\",\"types\":[\"solid\"]},{\"key\":\"la-bed\",\"types\":[\"solid\"]},{\"key\":\"la-concierge-bell\",\"types\":[\"solid\"]},{\"key\":\"la-door-closed\",\"types\":[\"solid\"]},{\"key\":\"la-door-open\",\"types\":[\"solid\"]},{\"key\":\"la-dumbbell\",\"types\":[\"solid\"]},{\"key\":\"la-hot-tub\",\"types\":[\"solid\"]},{\"key\":\"la-infinity\",\"types\":[\"solid\"]},{\"key\":\"la-key\",\"types\":[\"solid\"]},{\"key\":\"la-luggage-cart\",\"types\":[\"solid\"]},{\"key\":\"la-shower\",\"types\":[\"solid\"]},{\"key\":\"la-smoking\",\"types\":[\"solid\"]},{\"key\":\"la-smoking-ban\",\"types\":[\"solid\"]},{\"key\":\"la-snowflake\",\"types\":[\"solid\"]},{\"key\":\"la-suitcase\",\"types\":[\"solid\"]},{\"key\":\"la-suitcase-rolling\",\"types\":[\"solid\"]},{\"key\":\"la-swimming-pool\",\"types\":[\"solid\"]},{\"key\":\"la-umbrella-beach\",\"types\":[\"solid\"]},{\"key\":\"la-utensils\",\"types\":[\"solid\"]},{\"key\":\"la-chair\",\"types\":[\"solid\"]},{\"key\":\"la-couch\",\"types\":[\"solid\"]},{\"key\":\"la-fan\",\"types\":[\"solid\"]},{\"key\":\"la-bolt\",\"types\":[\"solid\"]},{\"key\":\"la-camera\",\"types\":[\"solid\"]},{\"key\":\"la-camera-retro\",\"types\":[\"solid\"]},{\"key\":\"la-id-badge\",\"types\":[\"solid\"]},{\"key\":\"la-id-card\",\"types\":[\"solid\"]},{\"key\":\"la-image\",\"types\":[\"solid\"]},{\"key\":\"la-images\",\"types\":[\"solid\"]},{\"key\":\"la-portrait\",\"types\":[\"solid\"]},{\"key\":\"la-sliders-h\",\"types\":[\"solid\"]},{\"key\":\"la-ban\",\"types\":[\"solid\"]},{\"key\":\"la-bars\",\"types\":[\"solid\"]},{\"key\":\"la-blog\",\"types\":[\"solid\"]},{\"key\":\"la-check\",\"types\":[\"solid\"]},{\"key\":\"la-check-circle\",\"types\":[\"solid\"]},{\"key\":\"la-check-double\",\"types\":[\"solid\"]},{\"key\":\"la-check-square\",\"types\":[\"solid\"]},{\"key\":\"la-cloud\",\"types\":[\"solid\"]},{\"key\":\"la-cog\",\"types\":[\"solid\"]},{\"key\":\"la-cogs\",\"types\":[\"solid\"]},{\"key\":\"la-dot-circle\",\"types\":[\"solid\"]},{\"key\":\"la-ellipsis-h\",\"types\":[\"solid\"]},{\"key\":\"la-ellipsis-v\",\"types\":[\"solid\"]},{\"key\":\"la-file-download\",\"types\":[\"solid\"]},{\"key\":\"la-file-export\",\"types\":[\"solid\"]},{\"key\":\"la-file-import\",\"types\":[\"solid\"]},{\"key\":\"la-file-upload\",\"types\":[\"solid\"]},{\"key\":\"la-fingerprint\",\"types\":[\"solid\"]},{\"key\":\"la-flag\",\"types\":[\"solid\"]},{\"key\":\"la-flag-checkered\",\"types\":[\"solid\"]},{\"key\":\"la-grip-horizontal\",\"types\":[\"solid\"]},{\"key\":\"la-grip-lines\",\"types\":[\"solid\"]},{\"key\":\"la-grip-lines-vertical\",\"types\":[\"solid\"]},{\"key\":\"la-grip-vertical\",\"types\":[\"solid\"]},{\"key\":\"la-hashtag\",\"types\":[\"solid\"]},{\"key\":\"la-info\",\"types\":[\"solid\"]},{\"key\":\"la-info-circle\",\"types\":[\"solid\"]},{\"key\":\"la-medal\",\"types\":[\"solid\"]},{\"key\":\"la-minus\",\"types\":[\"solid\"]},{\"key\":\"la-minus-circle\",\"types\":[\"solid\"]},{\"key\":\"la-minus-square\",\"types\":[\"solid\"]},{\"key\":\"la-plus\",\"types\":[\"solid\"]},{\"key\":\"la-plus-circle\",\"types\":[\"solid\"]},{\"key\":\"la-question\",\"types\":[\"solid\"]},{\"key\":\"la-search\",\"types\":[\"solid\"]},{\"key\":\"la-search-minus\",\"types\":[\"solid\"]},{\"key\":\"la-search-plus\",\"types\":[\"solid\"]},{\"key\":\"la-share-alt\",\"types\":[\"solid\"]},{\"key\":\"la-share-alt-square\",\"types\":[\"solid\"]},{\"key\":\"la-signal\",\"types\":[\"solid\"]},{\"key\":\"la-star\",\"types\":[\"solid\"]},{\"key\":\"la-star-half\",\"types\":[\"solid\"]},{\"key\":\"la-times\",\"types\":[\"solid\"]},{\"key\":\"la-times-circle\",\"types\":[\"solid\"]},{\"key\":\"la-toggle-off\",\"types\":[\"solid\"]},{\"key\":\"la-toggle-on\",\"types\":[\"solid\"]},{\"key\":\"la-trophy\",\"types\":[\"solid\"]},{\"key\":\"la-user\",\"types\":[\"solid\"]},{\"key\":\"la-user-alt\",\"types\":[\"solid\"]},{\"key\":\"la-user-circle\",\"types\":[\"solid\"]},{\"key\":\"la-box\",\"types\":[\"solid\"]},{\"key\":\"la-boxes\",\"types\":[\"solid\"]},{\"key\":\"la-clipboard-check\",\"types\":[\"solid\"]},{\"key\":\"la-clipboard-list\",\"types\":[\"solid\"]},{\"key\":\"la-dolly\",\"types\":[\"solid\"]},{\"key\":\"la-dolly-flatbed\",\"types\":[\"solid\"]},{\"key\":\"la-pallet\",\"types\":[\"solid\"]},{\"key\":\"la-shipping-fast\",\"types\":[\"solid\"]},{\"key\":\"la-anchor\",\"types\":[\"solid\"]},{\"key\":\"la-bomb\",\"types\":[\"solid\"]},{\"key\":\"la-bookmark\",\"types\":[\"solid\"]},{\"key\":\"la-directions\",\"types\":[\"solid\"]},{\"key\":\"la-fighter-jet\",\"types\":[\"solid\"]},{\"key\":\"la-gavel\",\"types\":[\"solid\"]},{\"key\":\"la-helicopter\",\"types\":[\"solid\"]},{\"key\":\"la-life-ring\",\"types\":[\"solid\"]},{\"key\":\"la-magnet\",\"types\":[\"solid\"]},{\"key\":\"la-male\",\"types\":[\"solid\"]},{\"key\":\"la-map-marker\",\"types\":[\"solid\"]},{\"key\":\"la-map-marker-alt\",\"types\":[\"solid\"]},{\"key\":\"la-map-pin\",\"types\":[\"solid\"]},{\"key\":\"la-newspaper\",\"types\":[\"solid\"]},{\"key\":\"la-parking\",\"types\":[\"solid\"]},{\"key\":\"la-plane\",\"types\":[\"solid\"]},{\"key\":\"la-restroom\",\"types\":[\"solid\"]},{\"key\":\"la-road\",\"types\":[\"solid\"]},{\"key\":\"la-rocket\",\"types\":[\"solid\"]},{\"key\":\"la-ship\",\"types\":[\"solid\"]},{\"key\":\"la-shopping-bag\",\"types\":[\"solid\"]},{\"key\":\"la-shopping-basket\",\"types\":[\"solid\"]},{\"key\":\"la-shopping-cart\",\"types\":[\"solid\"]},{\"key\":\"la-snowplow\",\"types\":[\"solid\"]},{\"key\":\"la-street-view\",\"types\":[\"solid\"]},{\"key\":\"la-subway\",\"types\":[\"solid\"]},{\"key\":\"la-ticket-alt\",\"types\":[\"solid\"]},{\"key\":\"la-traffic-light\",\"types\":[\"solid\"]},{\"key\":\"la-train\",\"types\":[\"solid\"]},{\"key\":\"la-tram\",\"types\":[\"solid\"]},{\"key\":\"la-umbrella\",\"types\":[\"solid\"]},{\"key\":\"la-utensil-spoon\",\"types\":[\"solid\"]},{\"key\":\"la-dharmachakra\",\"types\":[\"solid\"]},{\"key\":\"la-ad\",\"types\":[\"solid\"]},{\"key\":\"la-envelope-open-text\",\"types\":[\"solid\"]},{\"key\":\"la-funnel-dollar\",\"types\":[\"solid\"]},{\"key\":\"la-mail-bulk\",\"types\":[\"solid\"]},{\"key\":\"la-poll\",\"types\":[\"solid\"]},{\"key\":\"la-poll-h\",\"types\":[\"solid\"]},{\"key\":\"la-search-dollar\",\"types\":[\"solid\"]},{\"key\":\"la-search-location\",\"types\":[\"solid\"]},{\"key\":\"la-divide\",\"types\":[\"solid\"]},{\"key\":\"la-equals\",\"types\":[\"solid\"]},{\"key\":\"la-greater-than\",\"types\":[\"solid\"]},{\"key\":\"la-greater-than-equal\",\"types\":[\"solid\"]},{\"key\":\"la-less-than\",\"types\":[\"solid\"]},{\"key\":\"la-less-than-equal\",\"types\":[\"solid\"]},{\"key\":\"la-not-equal\",\"types\":[\"solid\"]},{\"key\":\"la-square-root-alt\",\"types\":[\"solid\"]},{\"key\":\"la-wave-square\",\"types\":[\"solid\"]},{\"key\":\"la-band-aid\",\"types\":[\"solid\"]},{\"key\":\"la-biohazard\",\"types\":[\"solid\"]},{\"key\":\"la-bong\",\"types\":[\"solid\"]},{\"key\":\"la-book-medical\",\"types\":[\"solid\"]},{\"key\":\"la-brain\",\"types\":[\"solid\"]},{\"key\":\"la-briefcase-medical\",\"types\":[\"solid\"]},{\"key\":\"la-cannabis\",\"types\":[\"solid\"]},{\"key\":\"la-capsules\",\"types\":[\"solid\"]},{\"key\":\"la-crutch\",\"types\":[\"solid\"]},{\"key\":\"la-diagnoses\",\"types\":[\"solid\"]},{\"key\":\"la-dna\",\"types\":[\"solid\"]},{\"key\":\"la-file-medical\",\"types\":[\"solid\"]},{\"key\":\"la-file-medical-alt\",\"types\":[\"solid\"]},{\"key\":\"la-file-prescription\",\"types\":[\"solid\"]},{\"key\":\"la-hospital-symbol\",\"types\":[\"solid\"]},{\"key\":\"la-id-card-alt\",\"types\":[\"solid\"]},{\"key\":\"la-joint\",\"types\":[\"solid\"]},{\"key\":\"la-laptop-medical\",\"types\":[\"solid\"]},{\"key\":\"la-mortar-pestle\",\"types\":[\"solid\"]},{\"key\":\"la-notes-medical\",\"types\":[\"solid\"]},{\"key\":\"la-pager\",\"types\":[\"solid\"]},{\"key\":\"la-pills\",\"types\":[\"solid\"]},{\"key\":\"la-prescription-bottle\",\"types\":[\"solid\"]},{\"key\":\"la-prescription-bottle-alt\",\"types\":[\"solid\"]},{\"key\":\"la-procedures\",\"types\":[\"solid\"]},{\"key\":\"la-star-of-life\",\"types\":[\"solid\"]},{\"key\":\"la-syringe\",\"types\":[\"solid\"]},{\"key\":\"la-tablets\",\"types\":[\"solid\"]},{\"key\":\"la-teeth\",\"types\":[\"solid\"]},{\"key\":\"la-teeth-open\",\"types\":[\"solid\"]},{\"key\":\"la-thermometer\",\"types\":[\"solid\"]},{\"key\":\"la-tooth\",\"types\":[\"solid\"]},{\"key\":\"la-user-nurse\",\"types\":[\"solid\"]},{\"key\":\"la-vial\",\"types\":[\"solid\"]},{\"key\":\"la-vials\",\"types\":[\"solid\"]},{\"key\":\"la-weight\",\"types\":[\"solid\"]},{\"key\":\"la-x-ray\",\"types\":[\"solid\"]},{\"key\":\"la-box-open\",\"types\":[\"solid\"]},{\"key\":\"la-people-carry\",\"types\":[\"solid\"]},{\"key\":\"la-sign\",\"types\":[\"solid\"]},{\"key\":\"la-tape\",\"types\":[\"solid\"]},{\"key\":\"la-truck-loading\",\"types\":[\"solid\"]},{\"key\":\"la-truck-moving\",\"types\":[\"solid\"]},{\"key\":\"la-drum\",\"types\":[\"solid\"]},{\"key\":\"la-drum-steelpan\",\"types\":[\"solid\"]},{\"key\":\"la-guitar\",\"types\":[\"solid\"]},{\"key\":\"la-headphones-alt\",\"types\":[\"solid\"]},{\"key\":\"la-record-vinyl\",\"types\":[\"solid\"]},{\"key\":\"la-cube\",\"types\":[\"solid\"]},{\"key\":\"la-cubes\",\"types\":[\"solid\"]},{\"key\":\"la-digital-tachograph\",\"types\":[\"solid\"]},{\"key\":\"la-futbol\",\"types\":[\"solid\"]},{\"key\":\"la-gem\",\"types\":[\"solid\"]},{\"key\":\"la-heart-broken\",\"types\":[\"solid\"]},{\"key\":\"la-lock\",\"types\":[\"solid\"]},{\"key\":\"la-lock-open\",\"types\":[\"solid\"]},{\"key\":\"la-moon\",\"types\":[\"solid\"]},{\"key\":\"la-ring\",\"types\":[\"solid\"]},{\"key\":\"la-scroll\",\"types\":[\"solid\"]},{\"key\":\"la-space-shuttle\",\"types\":[\"solid\"]},{\"key\":\"la-toilet\",\"types\":[\"solid\"]},{\"key\":\"la-unlock\",\"types\":[\"solid\"]},{\"key\":\"la-unlock-alt\",\"types\":[\"solid\"]},{\"key\":\"la-backspace\",\"types\":[\"solid\"]},{\"key\":\"la-blender-phone\",\"types\":[\"solid\"]},{\"key\":\"la-crown\",\"types\":[\"solid\"]},{\"key\":\"la-dumpster-fire\",\"types\":[\"solid\"]},{\"key\":\"la-file-csv\",\"types\":[\"solid\"]},{\"key\":\"la-network-wired\",\"types\":[\"solid\"]},{\"key\":\"la-signature\",\"types\":[\"solid\"]},{\"key\":\"la-skull\",\"types\":[\"solid\"]},{\"key\":\"la-vr-cardboard\",\"types\":[\"solid\"]},{\"key\":\"la-weight-hanging\",\"types\":[\"solid\"]},{\"key\":\"la-cart-plus\",\"types\":[\"solid\"]},{\"key\":\"la-democrat\",\"types\":[\"solid\"]},{\"key\":\"la-flag-usa\",\"types\":[\"solid\"]},{\"key\":\"la-person-booth\",\"types\":[\"solid\"]},{\"key\":\"la-republican\",\"types\":[\"solid\"]},{\"key\":\"la-vote-yea\",\"types\":[\"solid\"]},{\"key\":\"la-ankh\",\"types\":[\"solid\"]},{\"key\":\"la-bible\",\"types\":[\"solid\"]},{\"key\":\"la-cross\",\"types\":[\"solid\"]},{\"key\":\"la-hamsa\",\"types\":[\"solid\"]},{\"key\":\"la-hanukiah\",\"types\":[\"solid\"]},{\"key\":\"la-haykal\",\"types\":[\"solid\"]},{\"key\":\"la-jedi\",\"types\":[\"solid\"]},{\"key\":\"la-journal-whills\",\"types\":[\"solid\"]},{\"key\":\"la-khanda\",\"types\":[\"solid\"]},{\"key\":\"la-menorah\",\"types\":[\"solid\"]},{\"key\":\"la-om\",\"types\":[\"solid\"]},{\"key\":\"la-pastafarianism\",\"types\":[\"solid\"]},{\"key\":\"la-peace\",\"types\":[\"solid\"]},{\"key\":\"la-pray\",\"types\":[\"solid\"]},{\"key\":\"la-quran\",\"types\":[\"solid\"]},{\"key\":\"la-star-and-crescent\",\"types\":[\"solid\"]},{\"key\":\"la-star-of-david\",\"types\":[\"solid\"]},{\"key\":\"la-torah\",\"types\":[\"solid\"]},{\"key\":\"la-yin-yang\",\"types\":[\"solid\"]},{\"key\":\"la-temperature-high\",\"types\":[\"solid\"]},{\"key\":\"la-temperature-low\",\"types\":[\"solid\"]},{\"key\":\"la-meteor\",\"types\":[\"solid\"]},{\"key\":\"la-user-astronaut\",\"types\":[\"solid\"]},{\"key\":\"la-file-contract\",\"types\":[\"solid\"]},{\"key\":\"la-file-signature\",\"types\":[\"solid\"]},{\"key\":\"la-passport\",\"types\":[\"solid\"]},{\"key\":\"la-user-lock\",\"types\":[\"solid\"]},{\"key\":\"la-user-shield\",\"types\":[\"solid\"]},{\"key\":\"la-square\",\"types\":[\"solid\"]},{\"key\":\"la-user-friends\",\"types\":[\"solid\"]},{\"key\":\"la-user-plus\",\"types\":[\"solid\"]},{\"key\":\"la-users\",\"types\":[\"solid\"]},{\"key\":\"la-asterisk\",\"types\":[\"solid\"]},{\"key\":\"la-circle-notch\",\"types\":[\"solid\"]},{\"key\":\"la-compact-disc\",\"types\":[\"solid\"]},{\"key\":\"la-slash\",\"types\":[\"solid\"]},{\"key\":\"la-spinner\",\"types\":[\"solid\"]},{\"key\":\"la-baseball-ball\",\"types\":[\"solid\"]},{\"key\":\"la-basketball-ball\",\"types\":[\"solid\"]},{\"key\":\"la-bowling-ball\",\"types\":[\"solid\"]},{\"key\":\"la-golf-ball\",\"types\":[\"solid\"]},{\"key\":\"la-hockey-puck\",\"types\":[\"solid\"]},{\"key\":\"la-quidditch\",\"types\":[\"solid\"]},{\"key\":\"la-table-tennis\",\"types\":[\"solid\"]},{\"key\":\"la-volleyball-ball\",\"types\":[\"solid\"]},{\"key\":\"la-cloud-sun-rain\",\"types\":[\"solid\"]},{\"key\":\"la-rainbow\",\"types\":[\"solid\"]},{\"key\":\"la-calendar-day\",\"types\":[\"solid\"]},{\"key\":\"la-calendar-week\",\"types\":[\"solid\"]},{\"key\":\"la-star-half-alt\",\"types\":[\"solid\"]},{\"key\":\"la-thermometer-empty\",\"types\":[\"solid\"]},{\"key\":\"la-thermometer-full\",\"types\":[\"solid\"]},{\"key\":\"la-thermometer-half\",\"types\":[\"solid\"]},{\"key\":\"la-thermometer-quarter\",\"types\":[\"solid\"]},{\"key\":\"la-thermometer-three-quarters\",\"types\":[\"solid\"]},{\"key\":\"la-user-alt-slash\",\"types\":[\"solid\"]},{\"key\":\"la-user-slash\",\"types\":[\"solid\"]},{\"key\":\"la-atlas\",\"types\":[\"solid\"]},{\"key\":\"la-globe-africa\",\"types\":[\"solid\"]},{\"key\":\"la-globe-americas\",\"types\":[\"solid\"]},{\"key\":\"la-globe-asia\",\"types\":[\"solid\"]},{\"key\":\"la-globe-europe\",\"types\":[\"solid\"]},{\"key\":\"la-plane-arrival\",\"types\":[\"solid\"]},{\"key\":\"la-plane-departure\",\"types\":[\"solid\"]},{\"key\":\"la-child\",\"types\":[\"solid\"]},{\"key\":\"la-female\",\"types\":[\"solid\"]},{\"key\":\"la-user-check\",\"types\":[\"solid\"]},{\"key\":\"la-user-clock\",\"types\":[\"solid\"]},{\"key\":\"la-user-cog\",\"types\":[\"solid\"]},{\"key\":\"la-user-edit\",\"types\":[\"solid\"]},{\"key\":\"la-user-injured\",\"types\":[\"solid\"]},{\"key\":\"la-user-minus\",\"types\":[\"solid\"]},{\"key\":\"la-user-ninja\",\"types\":[\"solid\"]},{\"key\":\"la-user-tag\",\"types\":[\"solid\"]},{\"key\":\"la-user-times\",\"types\":[\"solid\"]},{\"key\":\"la-users-cog\",\"types\":[\"solid\"]},{\"key\":\"la-cloud-moon-rain\",\"types\":[\"solid\"]},{\"key\":\"la-cloud-rain\",\"types\":[\"solid\"]},{\"key\":\"la-cloud-showers-heavy\",\"types\":[\"solid\"]},{\"key\":\"la-poo-storm\",\"types\":[\"solid\"]},{\"key\":\"la-smog\",\"types\":[\"solid\"]},{\"key\":\"la-icicles\",\"types\":[\"solid\"]}]}");

/***/ }),

/***/ "./assets/src/scss/layout/admin/admin-style.scss":
/*!*******************************************************!*\
  !*** ./assets/src/scss/layout/admin/admin-style.scss ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayLikeToArray.js ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;

  for (var i = 0, arr2 = new Array(len); i < len; i++) {
    arr2[i] = arr[i];
  }

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

/***/ "./node_modules/@babel/runtime/helpers/typeof.js":
/*!*******************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/typeof.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(obj) {
  "@babel/helpers - typeof";

  return (module.exports = _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) {
    return typeof obj;
  } : function (obj) {
    return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
  }, module.exports.__esModule = true, module.exports["default"] = module.exports), _typeof(obj);
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

/***/ })

/******/ });
//# sourceMappingURL=admin-main.js.map