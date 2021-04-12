const $ = jQuery;

// Set all variables to be used in scope
const has_tagline = $('#has_tagline').val();
const has_excerpt = $('#has_excerpt').val();

if (has_excerpt && has_tagline) {
    $('.atbd_tagline_moto_field').fadeIn();
} else {
    $('.atbd_tagline_moto_field').fadeOut();
}

$('#atbd_optional_field_check').on('change', function () {
    $(this).is(':checked')
        ? $('.atbd_tagline_moto_field').fadeIn()
        : $('.atbd_tagline_moto_field').fadeOut();
});

// Load custom fields of the selected category in the custom post type "atbdp_listings"
$('#at_biz_dir-categorychecklist, #at_biz_dir-categorychecklist-pop').on('change', function (event) {
    const length = $('#at_biz_dir-categorychecklist input:checked');
    const length2 = $('#at_biz_dir-categorychecklist-pop input:checked');
    const id = [];
    const directory_type = $('select[name="directory_type"]').val();
    const from_single_directory = $('input[name="directory_type"]').val();
    if( length ){
        length.each((el, index) => {
            id.push($(index).val());
        });
    }
    
    if( length2 ){
        length2.each((el, index) => {
            id.push($(index).val());
        });
    }
    
    const data = {
        action: 'atbdp_custom_fields_listings',
        post_id: $('#post_ID').val(),
        term_id: id,
        directory_type: directory_type ? directory_type : from_single_directory,
    };
    $.post(atbdp_admin_data.ajaxurl, data, function (response) {
        if (response) {
            var response = `<div class="form-group atbd_content_module">
                        <div class="atbdb_content_module_contents">
                          ${response}
                        </div>
                      </div>`;
            $('.atbdp_category_custom_fields')
                .empty()
                .append(response);
            function atbdp_tooltip() {
                const atbd_tooltip = document.querySelectorAll('.atbd_tooltip');
                atbd_tooltip.forEach(function (el) {
                    if (el.getAttribute('aria-label') !== ' ') {
                        document.body.addEventListener(
                            'mouseover',
                            function (e) {
                                for (
                                    let { target } = e;
                                    target && target != this;
                                    target = target.parentNode
                                ) {
                                    if (target.matches('.atbd_tooltip')) {
                                        el.classList.add(
                                            'atbd_tooltip_active'
                                        );
                                    }
                                }
                            },
                            false
                        );
                    }
                });
            }
            atbdp_tooltip();
        } else {
            $('.atbdp_category_custom_fields').empty();
        }
    });
});

// Load custom fields of the selected category in the custom post type "atbdp_listings"
$(document).ready(function () {
    const length = $('#at_biz_dir-categorychecklist input:checked');
    const length2 = $('#at_biz_dir-categorychecklist-pop input:checked');
    const id = [];
    const directory_type = $('select[name="directory_type"]').val();
    const from_single_directory = $('input[name="directory_type"]').val();
    if( length ){
        length.each((el, index) => {
            id.push($(index).val());
        });
    }
    
    if( length2 ){
        length2.each((el, index) => {
            id.push($(index).val());
        });
    }
    const data = {
        action: 'atbdp_custom_fields_listings',
        post_id: $('#post_ID').val(),
        term_id: id,
        directory_type: directory_type ? directory_type : from_single_directory,
    };
    $.post(atbdp_admin_data.ajaxurl, data, function (response) {
        if (response) {
            var response = `<div class="form-group atbd_content_module">
                          <div class="atbdb_content_module_contents">
                            ${response}
                          </div>
                        </div>`;
            $('.atbdp_category_custom_fields')
                .empty()
                .append(response);
            function atbdp_tooltip() {
                const atbd_tooltip = document.querySelectorAll('.atbd_tooltip');
                atbd_tooltip.forEach(function (el) {
                    if (el.getAttribute('aria-label') !== ' ') {
                        document.body.addEventListener(
                            'mouseover',
                            function (e) {
                                for (
                                    let { target } = e;
                                    target && target != this;
                                    target = target.parentNode
                                ) {
                                    if (target.matches('.atbd_tooltip')) {
                                        el.classList.add(
                                            'atbd_tooltip_active'
                                        );
                                    }
                                }
                            },
                            false
                        );
                    }
                });
            }
            atbdp_tooltip();
        }
    });
});

const avg_review = $('#average_review_for_popular').hide();
const logged_count = $('#views_for_popular').hide();
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

$("[data-toggle='tooltip']").tooltip();

// price range
const pricerange = $('#pricerange_val').val();
if (pricerange) {
    $('#pricerange').fadeIn(100);
}
$('#price_range_option').on('click', function () {
    $('#pricerange').fadeIn(500);
});

// enable sorting if only the container has any social or skill field
const $s_wrap = $('#social_info_sortable_container'); // cache it
if (window.outerWidth > 1700) {
    if ($s_wrap.length) {
        $s_wrap.sortable({
            axis: 'y',
            opacity: '0.7',
        });
    }
}

// SOCIAL SECTION
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
    const id = $(this).data('id');
    const elementToRemove = $(`div#socialID-${id}`);
    event.preventDefault();
    /* Act on the event */
    swal(
        {
            title: atbdp_admin_data.i18n_text.confirmation_text,
            text: atbdp_admin_data.i18n_text.ask_conf_sl_lnk_del_txt,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: atbdp_admin_data.i18n_text.confirm_delete,
            closeOnConfirm: false,
        },
        function (isConfirm) {
            if (isConfirm) {
                // user has confirmed, no remove the item and reset the ids
                elementToRemove.slideUp('fast', function () {
                    elementToRemove.remove();
                    // reorder the index
                    $('.directorist-form-social-fields').each(function (index, element) {
                        const e = $(element);
                        e.attr('id', `socialID-${index}`);
                        e.find('select').attr('name', `social[${index}][id]`);
                        e.find('.atbdp_social_input').attr(
                            'name',
                            `social[${index}][url]`
                        );
                        e.find('.directorist-form-social-fields__remove').attr('data-id', index);
                    });
                });

                // show success message
                swal({
                    title: atbdp_admin_data.i18n_text.deleted,
                    // text: "Item has been deleted.",
                    type: 'success',
                    timer: 200,
                    showConfirmButton: false,
                });
            }
        }
    );
});

// upgrade old listing
$('#upgrade_directorist').on('click', function (event) {
    event.preventDefault();
    const $this = $(this);
    // display a notice to user to wait
    // send an ajax request to the back end
    atbdp_do_ajax($this, 'atbdp_upgrade_old_listings', null, function (response) {
        if (response.success) {
            $this.after(`<p>${response.data}</p>`);
        }
    });
});

// upgrade old pages
$('#shortcode-updated input[name="shortcode-updated"]').on('change', function (event) {
    event.preventDefault();
    $('#success_msg').hide();

    const $this = $(this);
    // display a notice to user to wait
    // send an ajax request to the back end
    atbdp_do_ajax($this, 'atbdp_upgrade_old_pages', null, function (response) {
        if (response.success) {
            $('#shortcode-updated').after(`<p id="success_msg">${response.data}</p>`);
        }
    });

    $('.atbdp_ajax_loading').css({
        display: 'none',
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
        url: atbdp_admin_data.ajaxurl,
        data: {
            action: 'send_system_info', // calls wp_ajax_nopriv_ajaxlogin
            _nonce: $('#atbdp_email_nonce').val(),
            email: $('#atbdp-email-address').val(),
            sender_email: $('#atbdp-sender-address').val(),
            subject: $('#atbdp-email-subject').val(),
            message: $('#atbdp-email-message').val(),
            system_info_url: $('#atbdp-system-info-url').val(),
        },
        beforeSend() {
            $('#atbdp-send-system-info-submit').html('Sending');
        },
        success(data) {
            if (data.success) {
                $('#atbdp-send-system-info-submit').html('Send Email');
                $('.system_info_success').html('Successfully sent');
            }
        },
        error(data) {
            console.log(data);
        },
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
            action: 'generate_url', // calls wp_ajax_nopriv_ajaxlogin nonce: ()
            _nonce: $(this).attr('data-nonce'),
        },
        success(response) {
            $('#atbdp-remote-response').html(response.data.message);
            $('#system-info-url, #atbdp-system-info-url').val(response.data.url);
            $('#system-info-url-text-link')
                .attr('href', response.data.url)
                .css('display', 'inline-block');
        },
        error(response) {
            // $('#atbdp-remote-response').val(response.data.error);
        },
    });

    return false;
});

$('#revoke-url').on('click', function (e) {
    e.preventDefault();
    $.ajax({
        type: 'post',
        url: atbdp_admin_data.ajaxurl,
        data: {
            action: 'revoke_url', // calls wp_ajax_nopriv_ajaxlogin
            _nonce: $(this).attr('data-nonce'),
        },
        success(response) {
            $('#atbdp-remote-response').html(response.data);
            $('#system-info-url, #atbdp-system-info-url').val('');
            $('#system-info-url-text-link')
                .attr('href', '#')
                .css('display', 'none');
        },
        error(response) {
            // $('#atbdp-remote-response').val(response.data.error);
        },
    });

    return false;
});

// redirect to import import_page_link
$('#csv_import input[name="csv_import"]').on('change', function (event) {
    event.preventDefault();
    window.location = atbdp_admin_data.import_page_link;
});

/* This function handles all ajax request */
function atbdp_do_ajax(ElementToShowLoadingIconAfter, ActionName, arg, CallBackHandler) {
    let data;
    if (ActionName) data = `action=${ActionName}`;
    if (arg) data = `${arg}&action=${ActionName}`;
    if (arg && !ActionName) data = arg;
    // data = data ;

    const n = data.search(atbdp_admin_data.nonceName);
    if (n < 0) {
        data = `${data}&${atbdp_admin_data.nonceName}=${atbdp_admin_data.nonce}`;
    }

    jQuery.ajax({
        type: 'post',
        url: atbdp_admin_data.ajaxurl,
        data,
        beforeSend() {
            jQuery("<span class='atbdp_ajax_loading'></span>").insertAfter(
                ElementToShowLoadingIconAfter
            );
        },
        success(data) {
            jQuery('.atbdp_ajax_loading').remove();
            CallBackHandler(data);
        },
    });
}