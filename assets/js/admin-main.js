/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/src/js/admin/components/admin-user.js":
/*!******************************************************!*\
  !*** ./assets/src/js/admin/components/admin-user.js ***!
  \******************************************************/
/***/ (function() {

// user type change on user dashboard
(function ($) {
  window.addEventListener('load', function () {
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
        error: function error(response) {
          // $('#atbdp-remote-response').val(response.data.error);
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
        error: function error(response) {
          // $('#atbdp-remote-response').val(response.data.error);
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
/***/ (function() {

window.addEventListener('load', function () {
  var $ = jQuery;
  var content = '';

  // Category icon selection
  function selecWithIcon(selected) {
    if (!selected.id) {
      return selected.text;
    }
    var $elem = $("<span><span class='".concat(directorist_admin.icon_type, " ").concat(selected.element.value, "'></span> ").concat(selected.text, "</span>"));
    return $elem;
  }
  if ($("[data-toggle='tooltip']").length) {
    $("[data-toggle='tooltip']").tooltip();
  }

  // price range
  var pricerange = $('#pricerange_val').val();
  if (pricerange) {
    $('#pricerange').fadeIn(100);
  }
  $('#price_range_option').on('click', function () {
    $('#pricerange').fadeIn(500);
  });

  // enable sorting if only the container has any social or skill field
  var $s_wrap = $('#social_info_sortable_container'); // cache it
  if (window.outerWidth > 1700) {
    if ($s_wrap.length) {
      $s_wrap.sortable({
        axis: 'y',
        opacity: '0.7'
      });
    }
  }
  // SOCIAL SECTION
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
          title: directorist_admin.i18n_text.deleted,
          // text: "Item has been deleted.",
          type: 'success',
          timer: 200,
          showConfirmButton: false
        });
      }
    });
  });

  // upgrade old listing
  $('#upgrade_directorist').on('click', function (event) {
    event.preventDefault();
    var $this = $(this);
    // display a notice to user to wait
    // send an ajax request to the back end
    atbdp_do_ajax($this, 'atbdp_upgrade_old_listings', null, function (response) {
      if (response.success) {
        $this.after("<p>".concat(response.data, "</p>"));
      }
    });
  });

  // upgrade old pages
  $('#shortcode-updated input[name="shortcode-updated"]').on('change', function (event) {
    event.preventDefault();
    $('#success_msg').hide();
    var $this = $(this);
    // display a notice to user to wait
    // send an ajax request to the back end
    atbdp_do_ajax($this, 'atbdp_upgrade_old_pages', null, function (response) {
      if (response.success) {
        $('#shortcode-updated').after("<p id=\"success_msg\">".concat(response.data, "</p>"));
      }
    });
    $('.atbdp_ajax_loading').css({
      display: 'none'
    });
  });

  // redirect to import import_page_link
  $('#csv_import input[name="csv_import"]').on('change', function (event) {
    event.preventDefault();
    window.location = directorist_admin.import_page_link;
  });

  /* This function handles all ajax request */
  function atbdp_do_ajax(ElementToShowLoadingIconAfter, ActionName, arg, CallBackHandler) {
    var data;
    if (ActionName) data = "action=".concat(ActionName);
    if (arg) data = "".concat(arg, "&action=").concat(ActionName);
    if (arg && !ActionName) data = arg;
    // data = data ;

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
/***/ (function() {

window.addEventListener('load', function () {
  var $ = jQuery;
  // Set all variables to be used in scope
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
  }

  // price range
  var pricerange = $('#pricerange_val').val();
  if (pricerange) {
    $('#pricerange').fadeIn(100);
  }
  $('#price_range_option').on('click', function () {
    $('#pricerange').fadeIn(500);
  });

  // enable sorting if only the container has any social or skill field
  var $s_wrap = $('#social_info_sortable_container'); // cache it
  if (window.outerWidth > 1700) {
    if ($s_wrap.length) {
      $s_wrap.sortable({
        axis: 'y',
        opacity: '0.7'
      });
    }
  }

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
          title: directorist_admin.i18n_text.deleted,
          // text: "Item has been deleted.",
          type: 'success',
          timer: 200,
          showConfirmButton: false
        });
      }
    });
  });

  // upgrade old listing
  $('#upgrade_directorist').on('click', function (event) {
    event.preventDefault();
    var $this = $(this);
    // display a notice to user to wait
    // send an ajax request to the back end
    atbdp_do_ajax($this, 'atbdp_upgrade_old_listings', null, function (response) {
      if (response.success) {
        $this.after("<p>".concat(response.data, "</p>"));
      }
    });
  });

  // upgrade old pages
  $('#shortcode-updated input[name="shortcode-updated"]').on('change', function (event) {
    event.preventDefault();
    $('#success_msg').hide();
    var $this = $(this);
    // display a notice to user to wait
    // send an ajax request to the back end
    atbdp_do_ajax($this, 'atbdp_upgrade_old_pages', null, function (response) {
      if (response.success) {
        $('#shortcode-updated').after("<p id=\"success_msg\">".concat(response.data, "</p>"));
      }
    });
    $('.atbdp_ajax_loading').css({
      display: 'none'
    });
  });

  // send system info to admin
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
      error: function error(response) {
        // $('#atbdp-remote-response').val(response.data.error);
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
      error: function error(response) {
        // $('#atbdp-remote-response').val(response.data.error);
      }
    });
    return false;
  });

  // redirect to import import_page_link
  $('#csv_import input[name="csv_import"]').on('change', function (event) {
    event.preventDefault();
    window.location = directorist_admin.import_page_link;
  });

  /* This function handles all ajax request */
  function atbdp_do_ajax(ElementToShowLoadingIconAfter, ActionName, arg, CallBackHandler) {
    var data;
    if (ActionName) data = "action=".concat(ActionName);
    if (arg) data = "".concat(arg, "&action=").concat(ActionName);
    if (arg && !ActionName) data = arg;
    // data = data ;

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
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");
/* harmony import */ var _global_components_debounce__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../global/components/debounce */ "./assets/src/js/global/components/debounce.js");


document.addEventListener('DOMContentLoaded', function () {
  var $ = jQuery;

  // ─── Shared media upload state ────────────────────────────────────────────────
  var frame;
  var selection;
  var prv_image;
  var prv_url;
  var prv_img_url;
  var multiple_image = true;

  // ─── Utility ──────────────────────────────────────────────────────────────────
  function toggle_section(show_if_value, subject_elm, terget_elm) {
    if (show_if_value === subject_elm.val()) {
      terget_elm.show();
    } else {
      terget_elm.hide();
    }
  }

  // ─── Listing image uploader ───────────────────────────────────────────────────
  $('body').on('click', '#listing_image_btn', function (event) {
    event.preventDefault();
    if (frame) {
      frame.open();
      return;
    }
    frame = wp.media({
      title: directorist_admin.i18n_text.upload_image,
      button: {
        text: directorist_admin.i18n_text.choose_image
      },
      library: {
        type: 'image'
      },
      multiple: multiple_image
    });
    frame.on('select', function () {
      selection = multiple_image ? frame.state().get('selection').toJSON() : frame.state().get('selection').first().toJSON();
      var data = '';
      if ($('.single_attachment').length === 0) {
        $('.listing-img-container').html('');
      }
      if (multiple_image) {
        $(selection).each(function () {
          if (this.type === 'image') {
            data += '<div class="single_attachment">';
            data += "<input class=\"listing_image_attachment\" name=\"listing_img[]\" type=\"hidden\" value=\"".concat(this.id, "\">");
            data += "<img style=\"width:100%;height:100%;\" src=\"".concat(this.url, "\" alt=\"Listing Image\" />");
            data += "<span class=\"remove_image fa fa-times\" title=\"Remove it\"></span></div>";
          }
        });
      } else {
        if (selection.type === 'image') {
          data += '<div class="single_attachment">';
          data += "<input class=\"listing_image_attachment\" name=\"listing_img[]\" type=\"hidden\" value=\"".concat(selection.id, "\">");
          data += "<img style=\"width:100%;height:100%;\" src=\"".concat(selection.url, "\" alt=\"Listing Image\" />");
          data += "<span class=\"remove_image fa fa-times\" title=\"Remove it\"></span></div>";
        }
      }
      if (multiple_image) {
        $('.listing-img-container').append(data);
      } else {
        $('.listing-img-container').html(data);
      }
      $('#delete-custom-img').removeClass('hidden');
    });
    frame.open();
  });
  $('body').on('click', '#delete-custom-img', function (event) {
    event.preventDefault();
    $('.listing-img-container').html("<img src=\"".concat(directorist_admin.assets_path, "images/no-image.png\" alt=\"Listing Image\" />"));
    $(this).addClass('hidden');
  });
  $(document).on('click', '.remove_image', function (e) {
    e.preventDefault();
    $(this).parent().remove();
    if ($('.single_attachment').length === 0) {
      $('.listing-img-container').html("<img src=\"".concat(directorist_admin.assets_path, "images/no-image.png\" alt=\"Listing Image\" />") + "<p>No images</p><small>(allowed formats jpeg. png. gif)</small>");
      $('#delete-custom-img').addClass('hidden');
    }
  });

  // ─── Tagline / excerpt toggle ─────────────────────────────────────────────────
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

  // ─── Header preview image uploader (static, outside AJAX wrapper) ─────────────
  var imageUpload;
  $('.upload-header').on('click', function (element) {
    element.preventDefault();
    imageUpload = wp.media.frames.file_frame = wp.media({
      title: directorist_admin.i18n_text.select_prv_img,
      button: {
        text: directorist_admin.i18n_text.insert_prv_img
      }
    });
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
    e.preventDefault();
    $(this).hide();
    $('.listing_prv_img').attr('value', '');
    $('.change_listing_prv_img').attr('src', '');
  });
  if ($('.change_listing_prv_img').attr('src') === '') {
    $('.remove_prev_img').hide();
  } else {
    $('.remove_prev_img').show();
  }

  // ─── Popular listing controls ─────────────────────────────────────────────────
  var avg_review = $('#average_review_for_popular').hide();
  var logged_count = $('#views_for_popular').hide();
  function syncPopularControls(val) {
    if (val === 'average_rating') {
      avg_review.show();
      logged_count.hide();
    } else if (val === 'view_count') {
      logged_count.show();
      avg_review.hide();
    } else if (val === 'both_view_rating') {
      avg_review.show();
      logged_count.show();
    }
  }
  syncPopularControls($('#listing_popular_by select[name="listing_popular_by"]').val());
  $('#listing_popular_by select[name="listing_popular_by"]').on('change', function () {
    syncPopularControls($(this).val());
  });

  // ─── Category image uploader ──────────────────────────────────────────────────
  function atbdp_render_media_uploader(page) {
    var frame;
    if (frame) {
      frame.open();
      return;
    }
    frame = wp.media({
      title: directorist_admin.i18n_text.image_uploader_title,
      multiple: false,
      library: {
        type: 'image'
      },
      button: {
        text: directorist_admin.i18n_text.choose_image
      }
    });
    frame.on('select', function () {
      var image = frame.state().get('selection').first().toJSON();
      if (page === 'listings') {
        var html = '<tr class="atbdp-image-row">' + '<td class="atbdp-handle"><span class="dashicons dashicons-screenoptions"></span></td>' + "<td class=\"atbdp-image\"><img src=\"".concat(image.url, "\" />") + "<input type=\"hidden\" name=\"images[]\" value=\"".concat(image.id, "\" /></td>") + "<td>".concat(image.url, "<br />") + "<a href=\"post.php?post=".concat(image.id, "&action=edit\" target=\"_blank\">").concat(atbdp.edit, "</a> | ") + "<a href=\"javascript:;\" class=\"atbdp-delete-image\" data-attachment_id=\"".concat(image.id, "\">").concat(atbdp.delete_permanently, "</a>") + '</td></tr>';
        $('#atbdp-images').append(html);
      } else {
        $('#atbdp-categories-image-id').val(image.id);
        $('#atbdp-categories-image-wrapper').html("<img src=\"".concat(image.url, "\" /><a href=\"\" class=\"remove_cat_img\"><span class=\"fa fa-times\" title=\"Remove it\"></span></a>"));
      }
    });
    frame.open();
  }
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
  });

  // ─── Announcement ─────────────────────────────────────────────────────────────
  var announcement_to = $('select[name="announcement_to"]');
  var announcement_recepents_section = $('#announcement_recepents');
  toggle_section('selected_user', announcement_to, announcement_recepents_section);
  announcement_to.on('change', function () {
    toggle_section('selected_user', $(this), announcement_recepents_section);
  });
  var submit_button = $('#announcement_submit .vp-input ~ span');
  var form_feedback = $('#announcement_submit .field');
  form_feedback.prepend('<div class="announcement-feedback"></div>');
  var announcement_is_sending = false;
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
        value: to.val(),
        default: 'all_user'
      },
      recepents: {
        value: recepents.val(),
        default: null
      },
      subject: {
        value: subject.val(),
        default: ''
      },
      message: {
        value: message.val(),
        default: ''
      },
      expiration: {
        value: expiration.val(),
        default: 3
      },
      send_to_email: {
        value: send_to_email.val(),
        default: 1
      }
    };
    var form_data = new FormData();
    form_data.append('action', 'atbdp_send_announcement');
    for (var field in fields_elm) {
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
        form_feedback.find('.announcement-feedback').html('<div class="form-alert">Sending the announcement, please wait..</div>');
      },
      success: function success(response) {
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
    });
  });

  // ─── Custom Tab Support ───────────────────────────────────────────────────────
  $('.atbds_wrapper a.nav-link').on('click', function (e) {
    e.preventDefault();
    var atbds_tabParent = $(this).parent().parent().find('a.nav-link');
    var $href = $(this).attr('href');
    $(atbds_tabParent).removeClass('active');
    $(this).addClass('active');
    switch ($(this).data('tabarea')) {
      case 'atbds_system-status-tab':
        $(".tab-content[data-tabarea='atbds_system-status-tab'] >.tab-pane").removeClass('active show');
        $(".tab-content[data-tabarea='atbds_system-status-tab'] ".concat($href)).addClass('active show');
        break;
      case 'atbds_system-info-tab':
        $(".tab-content[data-tabarea='atbds_system-info-tab'] >.tab-pane").removeClass('active show');
        $(".tab-content[data-tabarea='atbds_system-info-tab'] ".concat($href)).addClass('active show');
        break;
    }
  });

  // ─── Custom Tooltip ───────────────────────────────────────────────────────────
  $('.atbds_tooltip').on('hover', function () {
    var toolTipLabel = $(this).data('label');
    $(this).find('.atbds_tooltip__text').text(toolTipLabel).addClass('show');
  });
  $('.atbds_tooltip').on('mouseleave', function () {
    $('.atbds_tooltip__text').removeClass('show');
  });

  // ─── Screen detection ─────────────────────────────────────────────────────────
  var directory_type = $('select[name="directory_type"]').val();
  var isEditListingScreen = $('body').hasClass('post-php') && $('body').hasClass('post-type-at_biz_dir');
  var hasRenderedListingFields = $('#directiost-listing-fields_wrapper .directorist-listing-fields').children().length > 0;
  var editorLifecycleDebugEnabled = false;
  function editorLifecycleLog() {
    if (!editorLifecycleDebugEnabled) {
      return;
    }
    var args = Array.prototype.slice.call(arguments);
    args.unshift('[Directorist][Listing Editor]');
    console.log.apply(console, args);
  }
  function logEditorSnapshot(stage, $container, meta) {
    if (!editorLifecycleDebugEnabled) {
      return;
    }
    var container = $container && $container.length ? $container : $(document.body);
    var editorIds = getEditorIdsInContainer(container);
    var tinyMCE = typeof window.tinymce !== 'undefined' && window.tinymce.editors ? window.tinymce.editors : [];
    var tinyMceEditorIds = (tinyMCE || []).map(function (ed) {
      return ed && ed.id ? ed.id : null;
    }).filter(Boolean);
    var qtagsInstances = typeof window.QTags !== 'undefined' && window.QTags && window.QTags.instances ? window.QTags.instances : {};
    var qtagsInstanceIds = Object.keys(qtagsInstances).filter(function (id) {
      return !!qtagsInstances[id];
    });
    var preInit = window.tinyMCEPreInit || {};
    editorLifecycleLog('editor-snapshot', stage, Object.assign({
      selectedDirectoryType: $('select[name="directory_type"]').val() || null,
      editorIds: editorIds,
      textareaCount: container.find('textarea.wp-editor-area[id]').length,
      wpEditorWrapCount: container.find('.wp-editor-wrap').length,
      tinyMceContainerCount: container.find('.mce-tinymce').length,
      tinyMceIframeCount: container.find('iframe[id$="_ifr"]').length,
      quicktagsToolbarCount: container.find('.quicktags-toolbar,[id^="qt_"][id$="_toolbar"]').length,
      tinyMceEditorIds: tinyMceEditorIds,
      qtagsButtonsInitDone: typeof window.QTags !== 'undefined' && window.QTags ? window.QTags.buttonsInitDone === true : null,
      qtagsInstanceIds: qtagsInstanceIds,
      mcePreinitKeys: Object.keys(preInit.mceInit || {}),
      qtPreinitKeys: Object.keys(preInit.qtInit || {})
    }, meta || {}));
  }
  function ensureQuicktagsForEditor(editorId, qtInit, $context) {
    if (!editorId || typeof editorId !== 'string') {
      return false;
    }
    var $scope = $context && $context.length ? $context : $(document.body);
    var $textarea = $scope.find('#' + editorId);
    if (!$textarea.length) {
      editorLifecycleLog('quicktags-skip', editorId, 'textarea-missing');
      return false;
    }
    var toolbarSelector = '#qt_' + editorId + '_toolbar';
    var hasToolbar = $scope.find(toolbarSelector).length > 0 || $(toolbarSelector).length > 0;
    var hasInstance = typeof window.QTags !== 'undefined' && window.QTags && window.QTags.instances && window.QTags.instances[editorId];
    if (hasToolbar && hasInstance) {
      editorLifecycleLog('quicktags-ready', editorId);
      return true;
    }
    if (typeof window.quicktags !== 'function') {
      editorLifecycleLog('quicktags-skip', editorId, 'quicktags-api-missing');
      return false;
    }
    if (hasInstance && typeof window.QTags.instances[editorId].remove === 'function') {
      try {
        window.QTags.instances[editorId].remove();
      } catch (e) {/* ignore */}
    }
    var quicktagsSettings = Object.assign({}, qtInit && (0,_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__["default"])(qtInit) === 'object' ? qtInit : {}, {
      id: editorId
    });
    try {
      window.quicktags(quicktagsSettings);
      editorLifecycleLog('quicktags-reinit', editorId);
    } catch (e) {
      editorLifecycleLog('quicktags-reinit-fail', editorId, e && e.message ? e.message : e);
    }
    if (typeof window.QTags !== 'undefined' && window.QTags && typeof window.QTags._buttonsInit === 'function') {
      try {
        window.QTags._buttonsInit(editorId);
      } catch (e) {/* ignore */}
    }
    var hasToolbarAfterInit = $(toolbarSelector).length > 0;
    editorLifecycleLog('quicktags-verify', editorId, hasToolbarAfterInit ? 'toolbar-ready' : 'toolbar-missing');
    return hasToolbarAfterInit;
  }
  function getEditorIdsInContainer($container) {
    if (!$container || !$container.length) {
      return [];
    }
    var idsMap = {};
    $container.find('textarea.wp-editor-area[id], .wp-editor-wrap textarea[id]').each(function () {
      var editorId = $(this).attr('id');
      if (editorId) {
        idsMap[editorId] = true;
      }
    });
    $container.find('.wp-editor-wrap[id$="-wrap"]').each(function () {
      var wrapId = $(this).attr('id');
      if (!wrapId) {
        return;
      }
      var editorId = '';
      var match = wrapId.match(/^wp-(.+)-wrap$/);
      if (match && match[1]) {
        // Native WP wrapper format: wp-{editorId}-wrap
        editorId = match[1];
      } else {
        editorId = wrapId.replace(/-wrap$/, '');
      }
      if (editorId) {
        idsMap[editorId] = true;
      }
    });
    return Object.keys(idsMap);
  }
  function unlockListingFormUi() {
    $('#listing_form_info').find('.directorist_loader').remove();
    $('select[name="directory_type"]').parent('.inside').find('.directorist_loader').remove();
    $('select[name="directory_type"]').closest('#poststuff').find('#publishing-action').removeClass('directorist_disable');
  }
  var directoryTypeReloadEventNames = ['directorist-search-form-nav-tab-reloaded', 'directorist-reload-select2-fields', 'directorist-reload-map-api-field', 'triggerSlice', 'directorist-reload-plupload', 'directorist-type-change'];
  function dispatchDirectoryTypeReloadEvents(context) {
    var detail = context || {};
    directoryTypeReloadEventNames.forEach(function (eventName) {
      window.dispatchEvent(new CustomEvent(eventName, {
        detail: detail
      }));
      if (document && document.body) {
        document.body.dispatchEvent(new CustomEvent(eventName, {
          detail: detail
        }));
      }
    });
    editorLifecycleLog('dispatched-events', directoryTypeReloadEventNames);
  }

  // ─── WP Editor — destroy ──────────────────────────────────────────────────────
  /**
   * Destroy all TinyMCE + Quicktags editor instances found inside $container.
   *
   * MUST be called synchronously BEFORE the container's innerHTML is wiped
   * (.empty()) so TinyMCE can detach its iframes from live DOM nodes without
   * throwing errors. After this call the container can be safely emptied.
   *
   * @param {jQuery} $container
   */
  function destroyEditorsInContainer($container) {
    if (!$container || !$container.length) {
      return;
    }
    var editorIds = getEditorIdsInContainer($container);
    if (!editorIds.length) {
      editorLifecycleLog('destroy-skip', 'no-editors-found');
      return;
    }
    editorLifecycleLog('destroy-start', editorIds);
    editorIds.forEach(function (editorId) {
      var hasQTagsInstance = typeof window.QTags !== 'undefined' && window.QTags && window.QTags.instances && window.QTags.instances[editorId];
      var isQTagsBooting = typeof window.QTags !== 'undefined' && window.QTags && window.QTags.buttonsInitDone === false;
      if (hasQTagsInstance && isQTagsBooting) {
        editorLifecycleLog('destroy-defer', editorId, 'qtags-buttons-pending');
        return;
      }

      // 1. wp.editor.remove() — the correct WP public API (WP >= 4.8).
      //    This handles both TinyMCE and Quicktags in one call.
      if (typeof window.wp !== 'undefined' && wp.editor && typeof wp.editor.remove === 'function') {
        try {
          wp.editor.remove(editorId);
        } catch (e) {/* ignore */}
      }

      // 2. Belt-and-suspenders: direct TinyMCE teardown in case wp.editor.remove
      //    was not enough (e.g. the editor was initialised outside WP's API).
      if (typeof window.tinymce !== 'undefined') {
        var ed = tinymce.get(editorId);
        if (ed) {
          try {
            ed.save();
          } catch (e) {/* ignore */}
          try {
            tinymce.execCommand('mceRemoveEditor', false, editorId);
          } catch (e) {/* ignore */}
          try {
            tinymce.remove('#' + editorId);
          } catch (e) {/* ignore */}
        }
      }

      // 3. Quicktags cleanup in case wp.editor.remove missed it.
      if (typeof window.QTags !== 'undefined' && window.QTags.instances && window.QTags.instances[editorId]) {
        try {
          $('#qt_' + editorId + '_toolbar').remove();
          delete window.QTags.instances[editorId];
        } catch (e) {/* ignore */}
      }
    });
  }

  // ─── WP Editor — reinit ───────────────────────────────────────────────────────
  /**
   * Re-initialise every WP editor found inside $container after AJAX injection.
   *
   * WordPress's wp_editor() outputs an inline <script> that calls
   * wp.editor.initialize(). jQuery's .append() runs those inline scripts
   * automatically, so in most cases the editor self-initialises and this
   * function is a no-op (tinymce.get(id) already returns the instance).
   *
   * The 100 ms delay gives jQuery's globalEval time to run first.
   * If the inline script did not run (server strips scripts, or timing edge
   * case), we fall back to calling wp.editor.initialize() ourselves using
   * whatever settings are registered in tinyMCEPreInit.
   *
   * @param {jQuery} $container
   */
  function reinitEditorsInContainer($container, attempt) {
    if (!$container || !$container.length) {
      return;
    }
    var retryAttempt = Number(attempt) || 0;
    var maxRetryAttempts = 8;
    setTimeout(function () {
      var editorIds = getEditorIdsInContainer($container);
      if (!editorIds.length) {
        editorLifecycleLog('reinit-skip', 'no-editors-found');
        return;
      }
      editorLifecycleLog('reinit-start', editorIds);
      var hasPendingEditorInit = false;
      editorIds.forEach(function (editorId) {
        var escapedEditorId = String(editorId).replace(/"/g, '\\"');
        var hasTextarea = $container.find("textarea[id=\"".concat(escapedEditorId, "\"]")).length > 0;
        if (!hasTextarea) {
          editorLifecycleLog('reinit-skip', editorId, 'textarea-not-found');
          return;
        }

        // Already initialised — nothing to do.
        if (typeof window.tinymce !== 'undefined') {
          var existingEditor = tinymce.get(editorId);
          if (existingEditor) {
            var editorContainer = existingEditor.getContainer ? existingEditor.getContainer() : null;
            var hasLiveEditorContainer = editorContainer && document.body && document.body.contains(editorContainer);
            if (hasLiveEditorContainer) {
              if (existingEditor.initialized) {
                editorLifecycleLog('reinit-skip', editorId, 'already-live');
                return;
              }

              // Editor exists but is still booting; wait and retry instead of double-init.
              hasPendingEditorInit = true;
              editorLifecycleLog('reinit-wait', editorId, 'booting');
              return;
            }

            // Stale instance references detached markup after a DOM swap.
            try {
              existingEditor.remove();
            } catch (e) {/* ignore */}
          }
        }
        if (typeof window.wp === 'undefined' || !wp.editor || typeof wp.editor.initialize !== 'function') {
          editorLifecycleLog('reinit-skip', editorId, 'wp-editor-unavailable');
          return;
        }
        var preinit = window.tinyMCEPreInit || {};
        var mceInit = (preinit.mceInit || {})[editorId];
        var qtInit = (preinit.qtInit || {})[editorId];

        // AJAX-injected wp_editor() output should register per-editor preinit.
        // If missing, wait briefly before falling back to defaults.
        if (!mceInit && retryAttempt < maxRetryAttempts) {
          hasPendingEditorInit = true;
          editorLifecycleLog('reinit-wait', editorId, 'preinit-missing');
          return;
        }
        var qtagsInstancePendingButtons = typeof window.QTags !== 'undefined' && window.QTags && window.QTags.buttonsInitDone === false && window.QTags.instances && window.QTags.instances[editorId];
        if (qtagsInstancePendingButtons && retryAttempt < maxRetryAttempts) {
          hasPendingEditorInit = true;
          editorLifecycleLog('reinit-wait', editorId, 'qtags-buttons-pending');
          return;
        }

        // Prefer per-editor preinit config. If missing, use WP defaults.
        var mceSettings = mceInit ? Object.assign({}, mceInit, {
          selector: '#' + editorId
        }) : true;
        wp.editor.initialize(editorId, {
          tinymce: mceSettings,
          quicktags: qtInit ? Object.assign({}, qtInit) : true,
          mediaButtons: true
        });
        editorLifecycleLog('reinit-done', editorId, mceInit ? 'preinit' : 'wp-default');
        setTimeout(function () {
          ensureQuicktagsForEditor(editorId, qtInit, $container);
        }, 0);
        var wantsTinyMce = mceSettings !== false;
        if (wantsTinyMce) {
          var _ensureTinyMceReady = function ensureTinyMceReady(verifyAttempt) {
            var tinyMceEditor = typeof window.tinymce !== 'undefined' && window.tinymce && typeof window.tinymce.get === 'function' ? window.tinymce.get(editorId) : null;
            var hasTinyMceEditor = !!(tinyMceEditor && tinyMceEditor.initialized);
            var hasTinyMceContainer = $container.find('#wp-' + editorId + '-wrap .mce-tinymce').length > 0;
            if (hasTinyMceEditor || hasTinyMceContainer) {
              editorLifecycleLog('reinit-verify', editorId, 'tinymce-ready', verifyAttempt);
              return;
            }
            if (verifyAttempt < maxRetryAttempts) {
              setTimeout(function () {
                _ensureTinyMceReady(verifyAttempt + 1);
              }, 120);
              return;
            }
            editorLifecycleLog('reinit-verify', editorId, 'tinymce-missing-after-init');
          };
          setTimeout(function () {
            if (typeof window.switchEditors !== 'undefined' && window.switchEditors && typeof window.switchEditors.go === 'function') {
              try {
                window.switchEditors.go(editorId, 'tmce');
                editorLifecycleLog('reinit-switch', editorId, 'tmce');
              } catch (e) {
                editorLifecycleLog('reinit-switch-fail', editorId, e && e.message ? e.message : e);
              }
            }
            _ensureTinyMceReady(0);
          }, 0);
        }
      });
      if (hasPendingEditorInit && retryAttempt < maxRetryAttempts) {
        editorLifecycleLog('reinit-retry', retryAttempt + 1);
        reinitEditorsInContainer($container, retryAttempt + 1);
      }
    }, 100); // 100 ms: enough for jQuery globalEval; avoid 0 which races on slow machines
  }

  // ─── Race-condition guard ─────────────────────────────────────────────────────
  var ajaxRequestSeq = 0;
  var activeListingFormRequest = null;

  // ─── Directory type change handler ────────────────────────────────────────────
  var localized_data = directorist_admin.add_listing_data;
  $('body').on('change', 'select[name="directory_type"]', (0,_global_components_debounce__WEBPACK_IMPORTED_MODULE_1__["default"])(function () {
    $(this).parent('.inside').append('<span class="directorist_loader"></span>');
    admin_listing_form($(this).val(), false);
    $(this).closest('#poststuff').find('#publishing-action').addClass('directorist_disable');
    if (!localized_data.is_admin) {
      ['#directorist-select-st-s-js', '#directorist-select-st-e-js', '#directorist-select-sn-s-js', '#directorist-select-sn-e-js', '#directorist-select-mn-s-js', '#directorist-select-mn-e-js', '#directorist-select-tu-s-js', '#directorist-select-tu-e-js', '#directorist-select-wd-s-js', '#directorist-select-wd-e-js', '#directorist-select-th-s-js', '#directorist-select-th-e-js', '#directorist-select-fr-s-js', '#directorist-select-fr-e-js'].forEach(function (sel) {
        if ($(sel).length) {
          pureScriptSelect(sel);
        }
      });
    }
  }, 270));
  $(document).off('click.directorist-switch-html', '.wp-switch-editor.switch-html').on('click.directorist-switch-html', '.wp-switch-editor.switch-html', function () {
    var editorId = $(this).attr('data-wp-editor-id');
    if (!editorId || editorId.indexOf('directorist_html_') !== 0) {
      return;
    }
    setTimeout(function () {
      var preinit = window.tinyMCEPreInit || {};
      var qtInit = (preinit.qtInit || {})[editorId];
      ensureQuicktagsForEditor(editorId, qtInit);
    }, 0);
  });

  // ─── Custom field "see more" ──────────────────────────────────────────────────
  function customFieldSeeMore() {
    if (!$('.directorist-custom-field-btn-more').length) {
      return;
    }
    $('.directorist-custom-field-btn-more').each(function (index, element) {
      var fieldWrapper = $(element).closest('.directorist-custom-field-checkbox, .directorist-custom-field-radio');
      var customField = fieldWrapper.find('.directorist-checkbox, .directorist-radio');
      $(customField).slice(20, customField.length).slideUp();
      if (customField.length <= 20) {
        $(element).slideUp();
      }
    });
  }

  // ─── assetsNeedToWorkInVirtualDom ─────────────────────────────────────────────
  /**
   * Initialise all dynamic UI behaviours that live inside the AJAX-replaced
   * fields wrapper.
   *
   * Uses NAMESPACED delegated events (.off(ns).on(ns)) so calling this function
   * multiple times (once per directory-type switch) is safe — old handlers are
   * replaced, never stacked.
   */
  function assetsNeedToWorkInVirtualDom() {
    // ── Price type toggles ────────────────────────────────────────────────
    function getPriceTypeInput(typeId) {
      return $("#".concat($("[for=\"".concat(typeId, "\"]")).data('option')));
    }
    $(document).off('change.directorist-price', '.directorist-form-pricing-field__options input').on('change.directorist-price', '.directorist-form-pricing-field__options input', function () {
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
        $($('.directorist-form-pricing-field__options input').get(0)).prop('checked', true).trigger('change.directorist-price');
      }
    }

    // ── Preview image uploader (delegated — survives DOM replacement) ─────
    $(document).off('click.directorist-upload-header', '.upload-header').on('click.directorist-upload-header', '.upload-header', function (element) {
      element.preventDefault();
      var uploadModal = wp.media.frames.file_frame = wp.media({
        title: directorist_admin.i18n_text.select_prv_img,
        button: {
          text: directorist_admin.i18n_text.insert_prv_img
        }
      });
      uploadModal.on('select', function () {
        prv_image = uploadModal.state().get('selection').first().toJSON();
        prv_url = prv_image.id;
        prv_img_url = prv_image.url;
        $('.listing_prv_img').val(prv_url);
        $('.change_listing_prv_img').attr('src', prv_img_url);
        $('.upload-header').html('Change Preview Image');
        $('.remove_prev_img').show();
      });
      uploadModal.open();
    });
    $(document).off('click.directorist-remove-prev', '.remove_prev_img').on('click.directorist-remove-prev', '.remove_prev_img', function (e) {
      e.preventDefault();
      $(this).hide();
      $('.listing_prv_img').attr('value', '');
      $('.change_listing_prv_img').attr('src', '');
    });
    if ($('.change_listing_prv_img').attr('src') === '') {
      $('.remove_prev_img').hide();
    } else {
      $('.remove_prev_img').show();
    }

    // ── Manual coordinate toggle ──────────────────────────────────────────
    if (!$('input#manual_coordinate').is(':checked')) {
      $('.directorist-map-coordinates').hide();
    }
    $(document).off('click.directorist-coord', '#manual_coordinate').on('click.directorist-coord', '#manual_coordinate', function () {
      if ($('input#manual_coordinate').is(':checked')) {
        $('.directorist-map-coordinates').show();
      } else {
        $('.directorist-map-coordinates').hide();
      }
    });
  }

  // ─── Core AJAX function ───────────────────────────────────────────────────────
  /**
   * Load listing form fields for the given directory type via AJAX.
   *
   * @param {string}  directory_type  Slug/ID of the selected directory type.
   * @param {boolean} [skipDomSwap]   Skip DOM replacement on edit-screen initial
   *                                  load (fields already rendered by PHP) but
   *                                  still run all post-AJAX initialisers.
   */
  function admin_listing_form(directory_type, skipDomSwap) {
    var thisSeq = ++ajaxRequestSeq;
    var $fieldsWrapper = $('#directiost-listing-fields_wrapper .directorist-listing-fields');
    logEditorSnapshot('ajax-start', $fieldsWrapper, {
      sequence: thisSeq,
      directory_type: directory_type,
      skipDomSwap: !!skipDomSwap
    });
    if (activeListingFormRequest && activeListingFormRequest.readyState !== 4) {
      activeListingFormRequest.abort();
      editorLifecycleLog('ajax-abort-previous', thisSeq);
    }
    editorLifecycleLog('ajax-start', {
      sequence: thisSeq,
      directory_type: directory_type,
      skipDomSwap: !!skipDomSwap
    });

    // Destroy before empty() so TinyMCE can detach from live nodes
    if (!skipDomSwap) {
      destroyEditorsInContainer($fieldsWrapper);
      logEditorSnapshot('post-destroy', $fieldsWrapper, {
        sequence: thisSeq
      });
    }
    activeListingFormRequest = $.ajax({
      type: 'post',
      url: directorist_admin.ajaxurl,
      data: {
        action: 'atbdp_dynamic_admin_listing_form',
        directory_type: directory_type,
        listing_id: $('#directiost-listing-fields_wrapper').data('id'),
        directorist_nonce: directorist_admin.directorist_nonce
      },
      success: function success(response) {
        // Discard stale response from a superseded request
        if (thisSeq !== ajaxRequestSeq) {
          editorLifecycleLog('ajax-stale-discarded', thisSeq);
          return;
        }
        if (response.error) {
          console.log({
            response: response
          });
          unlockListingFormUi();
          return;
        }
        if (!skipDomSwap) {
          // Inject new fields HTML.
          // jQuery's .append() runs inline <script> tags automatically,
          // so WP's own wp.editor.initialize() call fires here.
          // reinitEditorsInContainer() is the safety net for when it doesn't.
          $fieldsWrapper.empty().append(response.data['listing_meta_fields']);
          logEditorSnapshot('post-dom-append', $fieldsWrapper, {
            sequence: thisSeq
          });
          $('#at_biz_dir-locationchecklist').empty().html(response.data['listing_locations']);
          $('#at_biz_dir-categorychecklist').empty().html(response.data['listing_categories']);
          $('#at_biz_dir-categorychecklist-pop').empty().html(response.data['listing_pop_categories']);
          $('#at_biz_dir-locationchecklist-pop').empty().html(response.data['listing_pop_locations']);
          $('.misc-pub-atbdp-expiration-time').empty().html(response.data['listing_expiration']);

          // Re-inject required external scripts
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

          // Safety-net editor reinit (100 ms after jQuery's globalEval)
          reinitEditorsInContainer($fieldsWrapper);
          setTimeout(function () {
            logEditorSnapshot('post-reinit-delay', $fieldsWrapper, {
              sequence: thisSeq
            });
          }, 450);
        }

        // Always run — regardless of skipDomSwap
        assetsNeedToWorkInVirtualDom();
        logEditorSnapshot('post-virtual-dom-init', $fieldsWrapper, {
          sequence: thisSeq
        });
        unlockListingFormUi();
        if ($('.directorist-color-field-js').length) {
          $('.directorist-color-field-js').wpColorPicker().empty();
        }
        dispatchDirectoryTypeReloadEvents({
          directory_type: directory_type,
          sequence: thisSeq,
          skip_dom_swap: !!skipDomSwap
        });
        customFieldSeeMore();
      },
      error: function error(jqXHR, textStatus, errorThrown) {
        if (textStatus === 'abort') {
          editorLifecycleLog('ajax-aborted', thisSeq);
          return;
        }
        console.log({
          error: jqXHR,
          textStatus: textStatus,
          errorThrown: errorThrown
        });
        unlockListingFormUi();
      },
      complete: function complete() {
        if (thisSeq === ajaxRequestSeq) {
          editorLifecycleLog('ajax-complete', thisSeq);
        }
      }
    });
  }

  // ─── Default directory type button ────────────────────────────────────────────
  $('body').on('click', '.submitdefault', function (e) {
    e.preventDefault();
    $(this).children('.submitDefaultCheckbox').prop('checked', true);
    var defaultSubmitDom = $(this);
    defaultSubmitDom.closest('.directorist_listing-actions').append('<span class="directorist_loader"></span>');
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

  // ─── Initial page load ────────────────────────────────────────────────────────
  // WHY window "load" and not DOMContentLoaded:
  //   DOMContentLoaded fires before WordPress's enqueued scripts (tinymce,
  //   quicktags, wp-editor) finish executing. Calling admin_listing_form() there
  //   means destroyEditorsInContainer() / reinitEditorsInContainer() run before
  //   those globals exist, so editor management silently fails.
  //   window "load" fires after ALL scripts are ready — safe to touch TinyMCE.
  //
  // WHY the change handler is on DOMContentLoaded (above):
  //   Binding event listeners does not require TinyMCE to be ready; only the
  //   AJAX callback does. Binding on DOMContentLoaded ensures we never miss a
  //   change that happens before window "load" fires.
  window.addEventListener('load', function () {
    if (!directory_type) {
      return;
    }

    // Edit screen with already-rendered PHP fields: skip DOM replacement,
    // but still run assetsNeedToWorkInVirtualDom() and other initialisers
    // that were previously never called.
    var skipDomSwap = isEditListingScreen && hasRenderedListingFields;
    admin_listing_form(directory_type, skipDomSwap);
  });
});

/***/ }),

/***/ "./assets/src/js/admin/components/block-4.js":
/*!***************************************************!*\
  !*** ./assets/src/js/admin/components/block-4.js ***!
  \***************************************************/
/***/ (function() {

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
/***/ (function() {

window.addEventListener('load', function () {
  var $ = jQuery;

  // Init Category Icon Picker
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
      fontAwesome: directoriistFontAwesomeIcons,
      lineAwesome: directoriistLineAwesomeIcons
    };
    args.value = iconValue;
    args.labels = directorist_admin.icon_picker_labels;
    var iconPicker = new IconPicker(args);
    iconPicker.init();
  }
  initCategoryIconPicker();

  // Category icon selection
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
  });

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
  });

  // Select Dropdown
  $('body').on('click', '.directorist_dropdown .directorist_dropdown-toggle', function (e) {
    e.preventDefault();
    $(this).siblings('.directorist_dropdown-option').toggle();
  });

  // Select Option after click
  $('body').on('click', '.directorist_dropdown .directorist_dropdown-option ul li a', function (e) {
    e.preventDefault();
    var optionText = $(this).html();
    $(this).children('.directorist_dropdown-toggle__text').html(optionText);
    $(this).closest('.directorist_dropdown-option').siblings('.directorist_dropdown-toggle').children('.directorist_dropdown-toggle__text').html(optionText);
    $('.directorist_dropdown-option').hide();
  });

  // Hide Clicked Anywhere
  $(document).bind('click', function (e) {
    var clickedDom = $(e.target);
    if (!clickedDom.parents().hasClass('directorist_dropdown')) {
      $('.directorist_dropdown-option').hide();
    }
  });
  $('.directorist-type-slug-content').each(function (id, element) {
    var slugWrapper = $(element).children('.directorist_listing-slug-text');
    var oldSlugVal = slugWrapper.attr('data-value');

    // Edit Slug on Click
    slugWrapper.on('click', function (e) {
      e.preventDefault();
      // Check if any other slug is editable
      $('.directorist_listing-slug-text[contenteditable="true"]').each(function () {
        if ($(this).is(slugWrapper)) return; // Skip current slug

        $(document).trigger('click'); // Click outside to save the previous slug
      });

      // Set the current slug as editable
      $(this).attr('contenteditable', true);
      $(this).addClass('directorist_listing-slug-text--editable');
      $(this).focus();
    });

    // Slug Edit and Save on Enter Keypress
    slugWrapper.on('input keypress', function (e) {
      var slugText = $(this).text();
      $(this).attr('data-value', slugText);

      // Save on Enter Key
      if (e.key === 'Enter' && slugText.trim() !== '') {
        e.preventDefault();
        saveSlug(slugWrapper); // Trigger save function
      }

      // Prevent empty save on Enter key
      if (slugText.trim() === '' && e.key === 'Enter') {
        e.preventDefault();
      }
    });

    // Save Slug on Clicking Outside the Editable Field
    $(document).on('click', function (e) {
      if (slugWrapper.attr('contenteditable') === 'true' && !$(e.target).closest('.directorist_listing-slug-text').length) {
        var slugText = slugWrapper.text();

        // If the slug was changed, save the new value
        if (oldSlugVal.trim() !== slugText.trim()) {
          saveSlug(slugWrapper);
        }

        // Exit editing mode
        slugWrapper.attr('contenteditable', 'false').removeClass('directorist_listing-slug-text--editable');
      }
    });

    // Save slug function
    function saveSlug(slugWrapper) {
      var type_id = slugWrapper.data('type-id');
      var newSlugVal = slugWrapper.attr('data-value');
      var slugId = $('.directorist-slug-notice-' + type_id); // Use the correct slug notice element

      // Show loading indicator
      slugWrapper.after("<span class=\"directorist_loader\"></span>");

      // AJAX request to save the slug
      $.ajax({
        type: 'post',
        url: directorist_admin.ajaxurl,
        data: {
          action: 'directorist_type_slug_change',
          directorist_nonce: directorist_admin.directorist_nonce,
          type_id: type_id,
          update_slug: newSlugVal
        },
        success: function success(response) {
          // Remove loader
          slugWrapper.siblings('.directorist_loader').remove();
          if (response) {
            if (response.error) {
              // Handle error case
              slugId.removeClass('directorist-slug-notice-success');
              slugId.addClass('directorist-slug-notice-error');
              slugId.empty().html(response.error);

              // Revert to old slug on error
              if (response.old_slug) {
                slugWrapper.text(response.old_slug);
              }
              setTimeout(function () {
                slugId.empty().html('');
              }, 3000);
            } else {
              // Handle success case
              slugId.empty().html(response.success);
              slugId.removeClass('directorist-slug-notice-error');
              slugId.addClass('directorist-slug-notice-success');
              setTimeout(function () {
                slugWrapper.closest('.directorist-listing-slug__form').css({
                  display: 'none'
                });
                slugId.html(''); // Clear the success message
              }, 1500);

              // Update old slug value
              oldSlugVal = newSlugVal;
            }
          }

          // Reset editable state and classes
          slugWrapper.attr('contenteditable', 'false').removeClass('directorist_listing-slug-text--editable');
        }
      });
    }
  });

  // Tab Content
  // Modular, classes has no styling, so reusable
  $('.atbdp-tab__nav-link').on('click', function (e) {
    e.preventDefault();
    var data_target = $(this).data('target');
    var current_item = $(this).parent();
    // Active Nav Item
    $('.atbdp-tab__nav-item').removeClass('active');
    current_item.addClass('active');
    // Active Tab Content
    $('.atbdp-tab__content').removeClass('active');
    $(data_target).addClass('active');
  });

  // Custom
  $('.atbdp-tab-nav-menu__link').on('click', function (e) {
    e.preventDefault();
    var data_target = $(this).data('target');
    var current_item = $(this).parent();
    // Active Nav Item
    $('.atbdp-tab-nav-menu__item').removeClass('active');
    current_item.addClass('active');
    // Active Tab Content
    $('.atbdp-tab-content').removeClass('active');
    $(data_target).addClass('active');
  });

  // Section Toggle
  $('.atbdp-section-toggle').on('click', function (e) {
    e.preventDefault();
    var data_target = $(this).data('target');
    $(data_target).slideToggle();
  });

  // Accordion Toggle
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
/***/ (function() {

function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t.return || t.return(); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
window.addEventListener('load', function () {
  var $ = jQuery;

  // License Authentication
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
          // removed by dead control flow

          // removed by dead control flow

          // removed by dead control flow
 var form_response_page; 
          // removed by dead control flow


          // Append Response
          // removed by dead control flow

          // removed by dead control flow
 var themes; 
          // removed by dead control flow
 var plugins; 
          // removed by dead control flow
 var total_theme; 
          // removed by dead control flow
 var total_plugin; 

          // console.log( { plugins, themes } );

          // removed by dead control flow
 var title; 
          // removed by dead control flow
 var title; 
          // removed by dead control flow


          // Show Log - Themes
          // removed by dead control flow
 var li, list_action, label, checkbox, theme, _iterator, _step, counter, theme_check_lists, theme_title, theme_section; 

          // Show Log - Extensions
          // removed by dead control flow
 var li, label, list_action, checkbox, extension, _iterator2, _step2, counter, plugin_check_lists, plugin_title, plugin_section; 
          // removed by dead control flow
 var continue_button; 
          // removed by dead control flow
 var skip_button; 
          // removed by dead control flow

          // removed by dead control flow

          // removed by dead control flow

        }
      },
      error: function error(_error2) {
        console.log(_error2);
        is_sending = false;
        submit_button.attr('disabled', false);
        submit_button.find('.atbdp-loading').remove();
      }
    });
  });

  // Reload Button
  $('body').on('click', '.reload', function (e) {
    e.preventDefault();
    // console.log('reloading...');
    location.reload();
  });

  // Extension Update Button
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
    }

    // console.log( { plugin_key } );

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
  });

  // Install Button
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
  });

  // Plugin Active Button
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
  });

  // Purchase refresh btn
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
  });

  // et-close-auth-btn
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
  });

  // purchase-refresh-form
  $('#purchase-refresh-form').on('submit', function (e) {
    e.preventDefault();
    // console.log( 'purchase-refresh-form' );

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
  });

  // Logout
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
    });

    // atbdp_close_subscriptions_sassion
  });

  // Form Actions
  // Apply button active status - My extension form
  var extFormCheckboxes = document.querySelectorAll('#atbdp-extensions-tab input[type="checkbox"]');
  var extFormActionSelect = document.querySelectorAll('#atbdp-extensions-tab select[name="bulk-actions"]');
  //console.log(extFormActionSelect);
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
  });

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
    });

    // console.log( task, plugins_items );
  });

  // Bulk Actions - My extensions form
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
    }

    // Before Install
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
  });

  // Bulk Actions - Required extensions form
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
    }

    // Before Install
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
  });

  // plugins_bulk__actions
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
    var _bulk_task = function bulk_task(plugins, counter, callback) {
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
          _bulk_task(plugins, next_index, callback);
        },
        error: function error(_error9) {
          // console.log(error);
        }
      });
    };
    _bulk_task(plugins_items, 0, after_plugins_install);
  }

  // Ext Actions | Uninstall
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
      error: function error(_error0) {
        // console.log(error);
        uninstalling = false;
      }
    });
  });

  // Bulk checkbox toggle
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
  });

  //
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
  });

  // Theme Activation
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
      error: function error(_error1) {
        // console.log({ error });
        theme_is_activating = false;
        $(self).find('.atbdp-icon').remove();
      }
    });
  });

  // Theme Update
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
      error: function error(_error10) {
        // console.log({ error });
        $(self).removeClass('in-progress');
        $(self).html(button_default_html);
        $(self).prop('disabled', false);
      }
    });
  });
});

/***/ }),

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

/***/ }),

/***/ "./assets/src/js/global/components/modal.js":
/*!**************************************************!*\
  !*** ./assets/src/js/global/components/modal.js ***!
  \**************************************************/
/***/ (function() {

var $ = jQuery;
$(document).ready(function () {
  modalToggle();
});
function modalToggle() {
  $('.atbdp_recovery_pass').on('click', function (e) {
    e.preventDefault();
    $('#recover-pass-modal').slideToggle().show();
  });

  // Contact form [on modal closed]
  $('#atbdp-contact-modal').on('hidden.bs.modal', function (e) {
    $('#atbdp-contact-message').val('');
    $('#atbdp-contact-message-display').html('');
  });

  // Template Restructured
  // Modal
  // Store original parent references for modals
  var modalOriginalParents = new Map();

  // Function to restore modal to its original position
  function restoreModalToOriginalPosition(modalElement) {
    if (!modalElement || !modalOriginalParents.has(modalElement)) {
      return;
    }
    var originalData = modalOriginalParents.get(modalElement);
    var originalParent = originalData.parent;

    // Only restore if original parent still exists in the DOM
    if (originalParent && document.body.contains(originalParent)) {
      // Restore to original position using nextSibling if available
      if (originalData.nextSibling && originalParent.contains(originalData.nextSibling)) {
        originalParent.insertBefore(modalElement, originalData.nextSibling);
      } else {
        originalParent.appendChild(modalElement);
      }
    }
  }
  $('body').on('click', '.directorist-btn-modal-js', function (e) {
    e.preventDefault();
    var data_target = $(this).attr('data-directorist_target');
    var modalElement = document.querySelector(".".concat(data_target));
    if (!modalElement) {
      return;
    }

    // Move modal to body if not already a direct child of body
    if (modalElement.parentElement !== document.body) {
      // Store original parent if not already stored
      if (!modalOriginalParents.has(modalElement)) {
        modalOriginalParents.set(modalElement, {
          parent: modalElement.parentElement,
          nextSibling: modalElement.nextSibling
        });
      }

      // Move to body
      document.body.appendChild(modalElement);
    }

    // Show modal
    modalElement.classList.add('directorist-show');
  });
  $('body').on('click', '.directorist-modal-close-js', function (e) {
    e.preventDefault();
    var modalElement = $(this).closest('.directorist-modal-js');

    // Hide modal
    modalElement.removeClass('directorist-show');

    // Restore to original position
    restoreModalToOriginalPosition(modalElement[0]);
  });

  // Close modal when clicking backdrop (not content inside)
  $('body').on('click', '.directorist-modal-js', function (e) {
    // Only close if clicking the backdrop itself, not children
    if (e.target === this && $(this).hasClass('directorist-show')) {
      $(this).removeClass('directorist-show');
      restoreModalToOriginalPosition(this);
    }
  });
}

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
    var value = $(this).children('option:selected').val();
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
    display: 'none'
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
    var field = $(this).closest('.select2-container').siblings('select:enabled');
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
    display: 'none'
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
window.addEventListener('directorist-instant-search-reloaded', initSelect2);

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

/***/ "./assets/src/js/global/components/tabs.js":
/*!*************************************************!*\
  !*** ./assets/src/js/global/components/tabs.js ***!
  \*************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ "./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js");

document.addEventListener('load', init, false);
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
      (0,_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__["default"])(links).forEach(function (item) {
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
      }

      // Remove Active Class
      var removeActiveClass = function removeActiveClass(item) {
        item.classList.remove('--is-active');
      };

      // Toggle Nav
      var activeNavItems = navContainer.querySelectorAll('.--is-active');
      if (activeNavItems) {
        (0,_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__["default"])(activeNavItems).forEach(removeActiveClass);
      }
      item.classList.add('--is-active');

      // Toggle Tab
      var activeTabItems = tabContainer.querySelectorAll('.--is-active');
      if (activeTabItems) {
        (0,_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__["default"])(activeTabItems).forEach(removeActiveClass);
      }
      tab.classList.add('--is-active');

      // Update Query Var
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
/***/ (function() {

window.addEventListener('load', function () {
  var $ = jQuery;
  document.querySelectorAll('.la-icon i').forEach(function (item) {
    className.push(item.getAttribute('class'));
  });

  // Handle Disabled Link Action
  $('.atbdp-disabled').on('click', function (e) {
    e.preventDefault();
  });

  // Toggle Modal
  $('.cptm-modal-toggle').on('click', function (e) {
    e.preventDefault();
    var target_class = $(this).data('target');
    $('.' + target_class).toggleClass('active');
  });

  // Change label on file select/change
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
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_modal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/modal */ "./assets/src/js/global/components/modal.js");
/* harmony import */ var _components_modal__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_components_modal__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_select2_custom_control__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/select2-custom-control */ "./assets/src/js/global/components/select2-custom-control.js");
/* harmony import */ var _components_select2_custom_control__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _components_setup_select2__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/setup-select2 */ "./assets/src/js/global/components/setup-select2.js");
/* harmony import */ var _components_tabs__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/tabs */ "./assets/src/js/global/components/tabs.js");
/* harmony import */ var _components_utility__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./components/utility */ "./assets/src/js/global/components/utility.js");
/* harmony import */ var _components_utility__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_components_utility__WEBPACK_IMPORTED_MODULE_4__);






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
      var modifiedText = originalText.replace(/^(\s*)/, '$1' + iconElm);
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

/***/ "./assets/src/scss/layout/admin/admin-style.scss":
/*!*******************************************************!*\
  !*** ./assets/src/scss/layout/admin/admin-style.scss ***!
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

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js ***!
  \**********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _arrayWithoutHoles; }
/* harmony export */ });
/* harmony import */ var _arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayLikeToArray.js */ "./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js");

function _arrayWithoutHoles(r) {
  if (Array.isArray(r)) return (0,_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(r);
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

/***/ "./node_modules/@babel/runtime/helpers/esm/iterableToArray.js":
/*!********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/iterableToArray.js ***!
  \********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _iterableToArray; }
/* harmony export */ });
function _iterableToArray(r) {
  if ("undefined" != typeof Symbol && null != r[Symbol.iterator] || null != r["@@iterator"]) return Array.from(r);
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js ***!
  \**********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _nonIterableSpread; }
/* harmony export */ });
function _nonIterableSpread() {
  throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js ***!
  \**********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _toConsumableArray; }
/* harmony export */ });
/* harmony import */ var _arrayWithoutHoles_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayWithoutHoles.js */ "./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js");
/* harmony import */ var _iterableToArray_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./iterableToArray.js */ "./node_modules/@babel/runtime/helpers/esm/iterableToArray.js");
/* harmony import */ var _unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./unsupportedIterableToArray.js */ "./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js");
/* harmony import */ var _nonIterableSpread_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./nonIterableSpread.js */ "./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js");




function _toConsumableArray(r) {
  return (0,_arrayWithoutHoles_js__WEBPACK_IMPORTED_MODULE_0__["default"])(r) || (0,_iterableToArray_js__WEBPACK_IMPORTED_MODULE_1__["default"])(r) || (0,_unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__["default"])(r) || (0,_nonIterableSpread_js__WEBPACK_IMPORTED_MODULE_3__["default"])();
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
/******/ 		if (!(moduleId in __webpack_modules__)) {
/******/ 			delete __webpack_module_cache__[moduleId];
/******/ 			var e = new Error("Cannot find module '" + moduleId + "'");
/******/ 			e.code = 'MODULE_NOT_FOUND';
/******/ 			throw e;
/******/ 		}
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
/*!**************************************!*\
  !*** ./assets/src/js/admin/admin.js ***!
  \**************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _scss_layout_admin_admin_style_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../../scss/layout/admin/admin-style.scss */ "./assets/src/scss/layout/admin/admin-style.scss");
/* harmony import */ var _global_global__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./../global/global */ "./assets/src/js/global/global.js");
/* harmony import */ var _components_block_1__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/block-1 */ "./assets/src/js/admin/components/block-1.js");
/* harmony import */ var _components_block_1__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_components_block_1__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _components_block_2__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/block-2 */ "./assets/src/js/admin/components/block-2.js");
/* harmony import */ var _components_block_2__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_components_block_2__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _components_block_3__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./components/block-3 */ "./assets/src/js/admin/components/block-3.js");
/* harmony import */ var _components_block_4__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./components/block-4 */ "./assets/src/js/admin/components/block-4.js");
/* harmony import */ var _components_block_4__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_components_block_4__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _components_block_5__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./components/block-5 */ "./assets/src/js/admin/components/block-5.js");
/* harmony import */ var _components_block_5__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_components_block_5__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _components_admin_user__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./components/admin-user */ "./assets/src/js/admin/components/admin-user.js");
/* harmony import */ var _components_admin_user__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_components_admin_user__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _components_subscriptionManagement__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./components/subscriptionManagement */ "./assets/src/js/admin/components/subscriptionManagement.js");
/* harmony import */ var _components_subscriptionManagement__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_components_subscriptionManagement__WEBPACK_IMPORTED_MODULE_8__);


// Global


// Blocks







// subscriptionManagement

}();
/******/ })()
;
//# sourceMappingURL=admin-main.js.map