// General Components
import '../public/components/directoristDropdown'
import '../public/components/directoristSelect';
import '../public/components/colorPicker';
import '../global/components/setup-select2';

/* eslint-disable */
const $ = jQuery;
const localized_data = directorist.add_listing_data;

/**
 * Join Query String
 *
 * @param string url
 * @param string queryString
 * @return string
 */
 function joinQueryString( url, queryString ) {
    return url.match( /[?]/ ) ? `${url}&${queryString}` : `${url}?${queryString}`;
}

/* Show and hide manual coordinate input field */
$(window).on('load', function () {
    if ($('input#manual_coordinate').length) {

        $('input#manual_coordinate').each((index, element) => {
            if (!$(element).is(':checked')) {
                $('#hide_if_no_manual_cor').hide();
                $('.directorist-map-coordinates').hide();
            }
        });
    }

    //initialize color picker
    if($('.directorist-color-field-js').length){
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
    });

    // SOCIAL SECTION
    // Rearrange the IDS and Add new social field
    $('body').on('click', '#addNewSocial', function (e) {
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
            social_wrap.append(data);
        });
    });
    document.addEventListener('directorist-reload-plupload', function(){
        if($('.directorist-color-field-js').length){
            $('.directorist-color-field-js').wpColorPicker().empty();
        }
    })

    // remove the social field and then reset the ids while maintaining position
    $('body').on('click', '.directorist-form-social-fields__remove', function (e) {
        const id = $(this).data('id');
        const elementToRemove = $(`div#socialID-${id}`);
        /* Act on the event */
        swal({
                title: localized_data.i18n_text.confirmation_text,
                text: localized_data.i18n_text.ask_conf_sl_lnk_del_txt,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: localized_data.i18n_text.confirm_delete,
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
                        title: localized_data.i18n_text.deleted,
                        // text: "Item has been deleted.",
                        type: 'success',
                        timer: 200,
                        showConfirmButton: false,
                    });
                }
            }
        );
    });

    /* This function handles all ajax request */
    function atbdp_do_ajax(ElementToShowLoadingIconAfter, ActionName, arg, CallBackHandler) {
        let data;
        if (ActionName) data = `action=${ActionName}`;
        if (arg) data = `${arg}&action=${ActionName}`;
        if (arg && !ActionName) data = arg;
        // data = data ;

        const n = data.search(localized_data.nonceName);

        if (n < 0) {
            const nonce = ( typeof directorist !== 'undefined' ) ? directorist.directorist_nonce : directorist_admin.directorist_nonce;
            data = `${data}&${'directorist_nonce'}=${nonce}`;
        }

        jQuery.ajax({
            type: 'post',
            url: localized_data.ajaxurl,
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

    // Select2 js code
    if (!localized_data.is_admin) {
        // Location
        const createLoc = $('#at_biz_dir-location').attr("data-allow_new");
        let maxLocationLength = $('#at_biz_dir-location').attr("data-max");
        if (createLoc) {
            $("#at_biz_dir-location").select2({
                tags: true,
                maximumSelectionLength: maxLocationLength,
                language: {
                    maximumSelected: function () {
                        return localized_data.i18n_text.max_location_msg;
                    }
                },
                tokenSeparators: [","],
            });
        } else {
            $("#at_biz_dir-location").select2({
                allowClear: true,
                tags: false,
                maximumSelectionLength: maxLocationLength,
                tokenSeparators: [","],
            });
        }

        // Tags
        const createTag = $('#at_biz_dir-tags').attr("data-allow_new");
        let maxTagLength = $('#at_biz_dir-tags').attr("data-max");
        if (createTag) {
            $('#at_biz_dir-tags').select2({
                tags: true,
                maximumSelectionLength: maxTagLength,
                tokenSeparators: [','],
            });
        } else {
            $('#at_biz_dir-tags').select2({
                allowClear: true,
                maximumSelectionLength: maxTagLength,
                tokenSeparators: [','],
            });
        }

        //Category
        const createCat = $('#at_biz_dir-categories').attr("data-allow_new");
        let maxCatLength = $('#at_biz_dir-categories').attr("data-max");
        if (createCat) {
            $('#at_biz_dir-categories').select2({
                allowClear: true,
                tags: true,
                maximumSelectionLength: maxCatLength,
                tokenSeparators: [','],
            });
        } else {
            $('#at_biz_dir-categories').select2({
                maximumSelectionLength: maxCatLength,
                allowClear: true,
            });
        }

    }

    // price range
    if ($('.directorist-form-pricing-field').hasClass('price-type-both')) {
        $('#price').show();
        $('#price_range').hide();
    }
    $('.directorist-form-pricing-field__options .directorist-checkbox__label').on('click', function () {
        const $this = $(this);
        if ($this.parent('.directorist-checkbox').children('input[type=checkbox]').prop('checked') === true) {
            $(`#${$this.data('option')}`).hide();
        } else {
            $(`#${$this.data('option')}`).show();
        }
        const $sibling = $this.parent().siblings('.directorist-checkbox');
        $sibling.children('input[type=checkbox]').prop('checked', false);
        $(`#${$sibling.children('.directorist-checkbox__label').data('option')}`).hide();
    });

    const has_tagline = $('#has_tagline').val();
    const has_excerpt = $('#has_excerpt').val();
    if (has_excerpt && has_tagline) {
        $('.atbd_tagline_moto_field').fadeIn();
    } else {
        $('.atbd_tagline_moto_field').fadeOut();
    }

    $('#atbd_optional_field_check').on('change', function () {
        $(this).is(':checked') ?
            $('.atbd_tagline_moto_field').fadeIn() :
            $('.atbd_tagline_moto_field').fadeOut();
    });

    // it shows the hidden term and conditions
    $('#listing_t_c').on('click', function (e) {
        e.preventDefault();
        $('#tc_container').toggleClass('active');
    });


    $('.directorist-form-categories-field').after('<div class="atbdp_category_custom_fields"></div>');
    // Load custom fields of the selected category in the custom post type "atbdp_listings"
    const qs = (function (a) {
        if (a == '') return {};
        const b = {};
        for (let i = 0; i < a.length; ++i) {
            const p = a[i].split('=', 2);
            if (p.length == 1) b[p[0]] = '';
            else b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, ' '));
        }
        return b;
    })(window.location.search.substr(1).split('&'));

    $('#at_biz_dir-categories').on('change', function () {
        var directory_type = qs.directory_type ? qs.directory_type : $('input[name="directory_type"]').val();
        const length = $('#at_biz_dir-categories option:selected');
        const id = [];
        length.each((el, index) => {
            id.push($(index).val());
        });

        const data = {
            action: 'atbdp_custom_fields_listings',
            directorist_nonce: ( typeof directorist !== 'undefined' ) ? directorist.directorist_nonce : directorist_admin.directorist_nonce,
            post_id: $('input[name="listing_id"]').val(),
            term_id: id,
            directory_type: directory_type,
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

    // Load custom fields of the selected category in the custom post type "atbdp_listings"
    var directory_type = qs.listing_type ? qs.listing_type : $('input[name="directory_type"]').val();
    const length = $('#at_biz_dir-categories option:selected');
    const id = [];
    length.each((el, index) => {
        id.push($(index).val());
    });
    const data = {
        action: 'atbdp_custom_fields_listings',
        post_id: $('input[name="listing_id"]').val(),
        term_id: id,
        directory_type: directory_type,
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

    function scrollToEl(selector) {
        document.querySelector(selector).scrollIntoView({
            block: 'start',
            behavior: 'smooth'
        })
    }

    function atbdp_element_value(element) {
        const field = $(element);
        if (field.length) {
            return field.val();
        }
        return '';
    }

    const uploaders = localized_data.media_uploader;
    let mediaUploaders = [];
    if (uploaders) {
        let i = 0;
        for (var uploader of uploaders) {
            if ($('.' + uploader['element_id']).length) {
                let media_uploader = new EzMediaUploader({
                    containerClass: uploader['element_id'],
                });
                mediaUploaders.push({
                    media_uploader: media_uploader,
                    uploaders_data: uploader,
                });
                mediaUploaders[i].media_uploader.init();
                i++;
            }
        }
    }

    let on_processing = false;
    let has_media = true;
    let quick_login_modal__success_callback = null;

    // -----------------------------
    // Submit The Form
    // -----------------------------
    $('body').on('submit', '#directorist-add-listing-form', function (e) {
        if (localized_data.is_admin) return;
        e.preventDefault();
        const $form = $(e.target);
        let error_count = 0;
        const err_log = {};
        if (on_processing) {
            // $('.directorist-form-submit__btn').attr('disabled', true);
            // return;
        }
        $('#listing_notifier')
            .empty()
            .show()
            .html(`<span class="atbdp_success">${'Processing your submission, plese wait..' }</span>`);


        // images
        let images = [];
        let image_ids = [];

        if (mediaUploaders.length) {
            for (var uploader of mediaUploaders) {
                if (uploader.media_uploader && has_media) {
                    var hasValidFiles = uploader.media_uploader.hasValidFiles();
                    if (hasValidFiles) {
                        // files
                        var files = uploader.media_uploader.getTheFiles();
                        images = files;
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
        }
        if( images.length > 1 ) {
            let counter = 0;

            function processMultiple(){
                let image_form_data = new FormData();

                image_form_data.append('action', 'directorist_process_listing_image');
                image_form_data.append('directorist_nonce', directorist.directorist_nonce);
                image_form_data.append('images', images[counter]);
                $.ajax({
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    url: localized_data.ajaxurl,
                    data: image_form_data,
                    success(response) {

                        image_ids.push( response.data.id );

                        console.log( response );
                        counter++;
                        $('#listing_notifier')
                                .empty()
                                .show()
                                .html(`<span class="atbdp_success">${'Uploading ' + counter + ' image out of ' + images.length }</span>`);
                        if(counter < images.length){
                            processMultiple();
                        }else{
                            handleListingForm( $form, image_ids );
                        }
                    }

                });

            }
            processMultiple();
        }else{
            handleListingForm( $form, image_ids );
        }

    });

    function handleListingForm( $form, image_ids = []) {

        var error_count = 0;
        var err_log = {};
        let form_data = new FormData();

        form_data.append('action', 'add_listing_action');
        form_data.append('directorist_nonce', directorist.directorist_nonce);

        form_data.append('image_ids', image_ids );

        $('.directorist-form-submit__btn').addClass('atbd_loading');

        const fieldValuePairs = $form.serializeArray();

        // Append Form Fields Values
        for ( const field of fieldValuePairs ) {

            if ( '' === field.value ) {
                continue;
            }

            form_data.append( field.name, field.value );
        }

        //images
        if (mediaUploaders.length) {
            for (var uploader of mediaUploaders) {
                if (uploader.media_uploader && has_media) {
                    var hasValidFiles = uploader.media_uploader.hasValidFiles();
                    if (hasValidFiles) {
                        // files
                        // var files = uploader.media_uploader.getTheFiles();
                        // if (files) {
                        //     for (var i = 0; i < files.length; i++) {
                        //         form_data.append(uploader.uploaders_data['meta_name'] + '[]', files[i]);
                        //     }
                        // }
                        var files_meta = uploader.media_uploader.getFilesMeta();
                        if (files_meta) {
                            for (var i = 0; i < files_meta.length; i++) {
                                var elm = files_meta[i];
                                for (var key in elm) {
                                    form_data.append(`${uploader.uploaders_data['files_meta_name']}[${i}][${key}]`, elm[key]);
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
        }

        // categories
        const categories = $('#at_biz_dir-categories').val();
        if (Array.isArray(categories) && categories.length) {
            for (var key in categories) {
                var value = categories[key];
                form_data.append('tax_input[at_biz_dir-category][]', value);
            }
        }

        if (typeof categories === 'string') {
            form_data.append('tax_input[at_biz_dir-category][]', categories);
        }

        if( form_data.has( 'admin_category_select[]') ) {
            form_data.delete( 'admin_category_select[]' );
        }

        if( form_data.has( 'directory_type') ) {
            form_data.delete( 'directory_type' );
        }

        var form_directory_type = $form.find("input[name='directory_type']");

        var form_directory_type_value = form_directory_type !== undefined ? form_directory_type.val() : '';
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
        }

        on_processing = true;

        // console.log( 'Form submission prevented!' );
        // return;
        $.ajax({
            method: 'POST',
            processData: false,
            contentType: false,
            url: localized_data.ajaxurl,
            data: form_data,
            success(response) {
                //console.log(response);
                // return;
                // show the error notice
                $('.directorist-form-submit__btn').attr('disabled', false);

                var redirect_url = ( response && response.redirect_url ) ? response.redirect_url : '';
                redirect_url = ( redirect_url && typeof redirect_url === 'string' ) ? response.redirect_url.replace( /:\/\//g, '%3A%2F%2F' ) : '';

                if (response.error === true) {
                    $('#listing_notifier').show().html(`<span>${response.error_msg}</span>`);
                    $('.directorist-form-submit__btn').removeClass('atbd_loading');
                    on_processing = false;

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

                        quick_login_modal__success_callback = function (args) {
                            $('#guest_user_email').prop('disabled', true);
                            $('#listing_notifier').hide().html('');

                            args.elements.submit_button.remove();

                            var form_actions = args.elements.form.find('.directorist-form-actions');
                            form_actions.find('.directorist-toggle-modal').removeClass('directorist-d-none');
                        }
                    }
                } else {
                    // preview on and no need to redirect to payment
                    if (response.preview_mode === true && response.need_payment !== true) {
                        if (response.edited_listing !== true) {
                            $('#listing_notifier')
                                .show()
                                .html(`<span class="atbdp_success">${response.success_msg}</span>`);

                            window.location.href = joinQueryString( response.preview_url, `preview=1&redirect=${redirect_url}` );

                        } else {
                            $('#listing_notifier')
                                .show()
                                .html(`<span class="atbdp_success">${response.success_msg}</span>`);
                            if (qs.redirect) {
                                window.location.href = joinQueryString( response.preview_url, `post_id=${response.id}&preview=1&payment=1&edited=1&redirect=${qs.redirect}` );
                            } else {
                                window.location.href = joinQueryString( response.preview_url, `preview=1&edited=1&redirect=${redirect_url}` );
                            }
                        }
                        // preview mode active and need payment
                    } else if (response.preview_mode === true && response.need_payment === true) {
                        window.location.href = joinQueryString( response.preview_url, `preview=1&payment=1&redirect=${redirect_url}` );
                    } else {
                        const is_edited = response.edited_listing ? `listing_id=${response.id}&edited=1` : '';

                        if (response.need_payment === true) {
                            $('#listing_notifier').show().html(`<span class="atbdp_success">${response.success_msg}</span>`);
                            window.location.href = decodeURIComponent(redirect_url);
                        } else {
                            $('#listing_notifier').show().html(`<span class="atbdp_success">${response.success_msg}</span>`);
                            window.location.href = joinQueryString( response.redirect_url, is_edited );
                        }
                    }
                }
            },
            error(error) {
                on_processing = false;
                $('.directorist-form-submit__btn').attr('disabled', false);

                $('.directorist-form-submit__btn').removeClass('atbd_loading');
                console.log(error);
            },
        });
    }

    // Custom Field Checkbox Button More
    function customFieldSeeMore() {
        if ($('.directorist-custom-field-btn-more').length) {
            $('.directorist-custom-field-btn-more').each((index, element) => {
                let fieldWrapper = $(element).closest('.directorist-custom-field-checkbox, .directorist-custom-field-radio');
                let customField = $(fieldWrapper).find('.directorist-checkbox, .directorist-radio');
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
        let fieldWrapper = $(this).closest('.directorist-custom-field-checkbox, .directorist-custom-field-radio');
        let customField = $(fieldWrapper).find('.directorist-checkbox, .directorist-radio');
        $(customField).slice(20, customField.length).slideUp();

        $(this).toggleClass('active');

        if ($(this).hasClass('active')) {
            $(this).text("See Less");
            $(customField).slice(20, customField.length).slideDown();
        } else {
            $(this).text("See More");
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

        var form_id = $(this).data('form');
        var modal_id = $(this).data('form');

        var modal = $(modal_id);
        var form = $(form_id);
        var form_feedback = form.find('.directorist-form-feedback');

        var email = $(form).find('input[name="email"]');
        var password = $(form).find('input[name="password"]');
        var security = $(form).find('input[name="directorist-quick-login-security"]');

        var form_data = {
            action: 'directorist_ajax_quick_login',
            username: email.val(),
            password: password.val(),
            rememberme: false,
            ['directorist-quick-login-security']: security.val(),
        };

        var submit_button = $(this);
        var submit_button_default_html = submit_button.html();

        $.ajax({
            method: 'POST',
            url: directorist.ajaxurl,
            data: form_data,
            beforeSend: function () {
                form_feedback.html('');
                submit_button.prop('disabled', true);
                submit_button.prepend('<i class="fas fa-circle-notch fa-spin"></i> ');
            },
            success: function (response) {
                submit_button.html(submit_button_default_html);

                if (response.loggedin) {
                    password.prop('disabled', true);
                    var message = 'Successfully logged in, please continue to the listing submission';
                    var msg = '<div class="directorist-alert directorist-alert-success directorist-text-center directorist-mb-20">' + message + '</div>';
                    form_feedback.html(msg);

                    if (quick_login_modal__success_callback) {
                        var args = {
                            elements: {
                                modal_id,
                                form,
                                email,
                                password,
                                submit_button
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
            error: function (error) {
                console.log({
                    error
                });
                submit_button.prop('disabled', false);
                submit_button.html(submit_button_default_html);
            },
        });
    });
})