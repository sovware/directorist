(function ($) {
    "use strict";

    /* Show and hide manual coordinate input field*/
    if (!$('input#manual_coordinate').is(':checked')) {
        $('#hide_if_no_manual_cor').hide();
    }
    $('#manual_coordinate').on('click', function (e) {
        if ($('input#manual_coordinate').is(':checked')) {
            $('#hide_if_no_manual_cor').show();
        } else {
            $('#hide_if_no_manual_cor').hide();
        }
    });

    // enable sorting if only the container has any social or skill field
    var $s_wrap = $("#social_info_sortable_container");// cache it
    if (window.outerWidth > 1700) {
        if ($s_wrap.length) {
            $s_wrap.sortable(
                {
                    axis: 'y',
                    opacity: '0.7'
                }
            );
        }
    }

    // SOCIAL SECTION
    // Rearrange the IDS and Add new social field
    $("#addNewSocial").on('click', function (e) {
        var currentItems = $('.atbdp_social_field_wrapper').length;
        var ID = "id=" + currentItems; // eg. 'id=3'
        var iconBindingElement = jQuery('#addNewSocial');
        // arrange names ID in order before adding new elements
        $('.atbdp_social_field_wrapper').each(function (index, element) {
            var e = $(element);
            e.attr('id', 'socialID-' + index);
            e.find('select').attr('name', 'social[' + index + '][id]');
            e.find('.atbdp_social_input').attr('name', 'social[' + index + '][url]');
            e.find('.removeSocialField').attr('data-id', index);
        });
        // now add the new elements. we could do it here without using ajax but it would require more markup here.
        atbdp_do_ajax(iconBindingElement, 'atbdp_social_info_handler', ID, function (data) {
            $s_wrap.after(data);
        });
    });

    // remove the social field and then reset the ids while maintaining position
    $(document).on('click', '.removeSocialField', function (e) {
        var id = $(this).data("id"),
            elementToRemove = $('div#socialID-' + id);
        /* Act on the event */
        swal({
                title: atbdp_add_listing.i18n_text.confirmation_text,
                text: atbdp_add_listing.i18n_text.ask_conf_sl_lnk_del_txt,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: atbdp_add_listing.i18n_text.confirm_delete,
                closeOnConfirm: false
            },
            function (isConfirm) {
                if (isConfirm) {
                    // user has confirmed, no remove the item and reset the ids
                    elementToRemove.slideUp("fast", function () {
                        elementToRemove.remove();
                        // reorder the index
                        $('.atbdp_social_field_wrapper').each(function (index, element) {
                            var e = $(element);
                            e.attr('id', 'socialID-' + index);
                            e.find('select').attr('name', 'social[' + index + '][id]');
                            e.find('.atbdp_social_input').attr('name', 'social[' + index + '][url]');
                            e.find('.removeSocialField').attr('data-id', index);
                        });
                    });

                    // show success message
                    swal({
                        title: atbdp_add_listing.i18n_text.deleted,
                        //text: "Item has been deleted.",
                        type: "success",
                        timer: 200,
                        showConfirmButton: false
                    });
                }

            });


    });

    /*This function handles all ajax request*/
    function atbdp_do_ajax(ElementToShowLoadingIconAfter, ActionName, arg, CallBackHandler) {
        var data;
        if (ActionName) data = "action=" + ActionName;
        if (arg) data = arg + "&action=" + ActionName;
        if (arg && !ActionName) data = arg;
        //data = data ;

        var n = data.search(atbdp_add_listing.nonceName);
        if (n < 0) {
            data = data + "&" + atbdp_add_listing.nonceName + "=" + atbdp_add_listing.nonce;
        }

        jQuery.ajax({
            type: "post",
            url: atbdp_add_listing.ajaxurl,
            data: data,
            beforeSend: function () {
                jQuery("<span class='atbdp_ajax_loading'></span>").insertAfter(ElementToShowLoadingIconAfter);
            },
            success: function (data) {
                jQuery(".atbdp_ajax_loading").remove();
                CallBackHandler(data);
            }
        });
    }


    // Select2 js code
    // Location
    $('#at_biz_dir-location').select2({
        placeholder: atbdp_add_listing.i18n_text.location_selection,
        allowClear: true
    });

    // Tags
    var createTag = atbdp_add_listing.create_new_tag;
    if (createTag) {
        $('#at_biz_dir-tags').select2({
            placeholder: atbdp_add_listing.i18n_text.tag_selection,
            tags: true,
            tokenSeparators: [',']
        });
    } else {
        $('#at_biz_dir-tags').select2({
            placeholder: atbdp_add_listing.i18n_text.tag_selection,
            allowClear: true
        });
    }
    $('#at_biz_dir-categories').select2({
        placeholder: atbdp_add_listing.cat_placeholder,
        allowClear: true
    });


})(jQuery);


// Custom Image uploader for listing image (multiple)
jQuery(function ($) {

    //price range
    $("#price_range").hide();
    var is_checked = $('#atbd_listing_pricing').val();
    if ('range' === is_checked) {
        $('#price').hide();
        $("#price_range").show();
    }
    $('.atbd_pricing_options label').on('click', function () {
        var $this = $(this);
        $this.children('input[type=checkbox]').prop('checked') == true ? $('#' + $this.data('option')).show() : $('#' + $this.data('option')).hide();
        var $sibling = $this.siblings('label');
        $sibling.children('input[type=checkbox]').prop('checked', false);
        $('#' + $sibling.data('option')).hide();
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


    //it shows the hidden term and conditions
    $('#listing_t_c').on('click', function (e) {
        e.preventDefault();
        $('#tc_container').toggleClass("active");
    });

    $(function () {
        $('#color_code2').wpColorPicker().empty();
    });

    // Load custom fields of the selected category in the custom post type "atbdp_listings"
    $('#at_biz_dir-categories').on('change', function () {
        $('#atbdp-custom-fields-list').html('<div class="spinner"></div>');
        var length = $('#at_biz_dir-categories option:selected');
        var id = [];
        length.each((el, index) => {
            id.push($(index).val());
        });
        var data = {
            'action': 'atbdp_custom_fields_listings_front',
            'post_id': $('#atbdp-custom-fields-list').data('post_id'),
            'term_id': id
        };
        $.post(atbdp_add_listing.ajaxurl, data, function (response) {
            if (response == " 0") {
                $('#atbdp-custom-fields-list').hide();
            } else {
                $('#atbdp-custom-fields-list').show();
            }
            $('#atbdp-custom-fields-list').html(response);
        });

        $('#atbdp-custom-fields-list-selected').hide();

    });


    var length = $('#at_biz_dir-categories option:selected');

    if (length) {
        $('#atbdp-custom-fields-list-selected').html('<div class="spinnedsr"></div>');

        var length = $('#at_biz_dir-categories option:selected');
        var id = [];
        length.each((el, index) => {
            id.push($(index).val());
        });
        var data = {
            'action': 'atbdp_custom_fields_listings_front_selected',
            'post_id': $('#atbdp-custom-fields-list-selected').data('post_id'),
            'term_id': id
        };

        $.post(atbdp_add_listing.ajaxurl, data, function (response) {
            $('#atbdp-custom-fields-list-selected').html(response);
        });
    }


    function atbdp_is_checked(name) {
        var is_checked = $('input[name="' + name + '"]').is(':checked');
        if (is_checked) {
            return '1';
        } else {
            return '';
        }
    }

    function atbdp_element_value(element) {
        var field = $(element);
        if (field.length){
            return field.val();
        }else {
            return '';
        }
    }

    var qs = (function (a) {
        if (a == "") return {};
        var b = {};
        for (var i = 0; i < a.length; ++i) {
            var p = a[i].split('=', 2);
            if (p.length == 1)
                b[p[0]] = "";
            else
                b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
        }
        return b;
    })(window.location.search.substr(1).split('&'));

    var listingMediaUploader = new EzMediaUploader({
        containerID: "_listing_gallery",
    });
    listingMediaUploader.init();
    // gallery
    var listignsGalleryUploader = new EzMediaUploader({
        containerID: "listing_gallery_ext",
    });
    listignsGalleryUploader.init();

    var formID = $('#add-listing-form');
    var on_processing = false;


    $('body').on('submit', formID, function (e) {
        e.preventDefault();

        if ( on_processing ) {
            $('.listing_submit_btn').attr( 'disabled', true );
            return;
        }

        on_processing = true;

        var form_data = new FormData();
        $(".listing_submit_btn").addClass("atbd_loading");

        function atbdp_multi_select(field, name) {
            var field = $('' + field + '[name^="' + name + '"]');
            if (field.length){
                if (field.length > 1) {
                    field.each(function (index, value) {
                        var type = $(value).attr('type');
                        if (type !== "checkbox") {
                            var name = $(this).attr("name");
                            var value = $(this).val();
                            form_data.append(name, value);
                        }
                    });
                } else {
                    var name = field.attr("name");
                    var value = field.val();
                    form_data.append(name, value);
                }
            }
        }

        // ajax action
        form_data.append('action', 'add_listing_action');
        //files
        var files = listingMediaUploader.getTheFiles();
        if (files) {
            for (var i = 0; i < files.length; i++) {
                form_data.append('listing_img[]', files[i]);
            }
        }
        var files_meta = listingMediaUploader.getFilesMeta();
        if (files_meta) {
            for (var i = 0; i < files_meta.length; i++) {
                var elm = files_meta[i];
                for (var key in elm) {
                    form_data.append('files_meta[' + i + '][' + key + ']', elm[key]);
                }
            }
        }
        if ($('#_listing_gallery').length) {
            var hasValidFiles = listingMediaUploader.hasValidFiles();
            if (!hasValidFiles) {
                $(".listing_submit_btn").removeClass("atbd_loading");
                return;
            }
        }

        // gallery
        var files = listignsGalleryUploader.getTheFiles();
        if (files) {
            for (var i = 0; i < files.length; i++) {
                form_data.append('gallery_img[]', files[i]);
            }
        }
        var files_meta = listignsGalleryUploader.getFilesMeta();
        if (files_meta) {
            for (var i = 0; i < files_meta.length; i++) {
                var elm = files_meta[i];
                for (var key in elm) {
                    form_data.append('files_gallery_meta[' + i + '][' + key + ']', elm[key]);
                }
            }
        }

        if ($('#listing_gallery_ext').length) {
            var hasValidFiles = listingMediaUploader.hasValidFiles();
            if (!hasValidFiles) {
                $(".listing_submit_btn").removeClass("atbd_loading");
                return;
            }
        }
        var iframe = $('#listing_content_ifr');
        var serviceIframe = $('#service_offer_ifr');
        var content = iframe.length ? tinymce.get('listing_content').getContent() : atbdp_element_value('textarea[name="listing_content"]');
        var service_offer = serviceIframe.length ? tinymce.get('service_offer').getContent() : '';
        var excerpt = atbdp_element_value("textarea#atbdp_excerpt");
        
        form_data.append('add_listing_nonce', atbdp_add_listing.nonce);
        //form_data.append('add_listing_form', $('input[name="add_listing_form"]').val());
        form_data.append('listing_id', $('input[name="listing_id"]').val());
        form_data.append('listing_title', $('input[name="listing_title"]').val());
        form_data.append('listing_content', content);
        form_data.append('service_offer', service_offer);
        form_data.append('price', atbdp_element_value('input[name="price"]'));
        form_data.append('atbdp_post_views_count', atbdp_element_value('input[name="atbdp_post_views_count"]'));
        form_data.append('tagline', atbdp_element_value('input[name="tagline"]'));
        form_data.append('excerpt', excerpt);
        form_data.append('atbd_listing_pricing', atbdp_element_value('input[name="atbd_listing_pricing"]:checked'));
        form_data.append('price_range', atbdp_element_value('select[name="price_range"]'));
        //post your need
        form_data.append('need_post', atbdp_element_value('input[name="need_post"]:checked'));
        form_data.append('pyn_deadline', atbdp_element_value('input[name="pyn_deadline"]'));
        form_data.append('is_hourly', atbdp_is_checked('is_hourly'));
        //plans
        form_data.append('listing_type', atbdp_element_value('input[name="listing_type"]:checked'));
        form_data.append('plan', qs['plan']);
        // contact info
        form_data.append('zip', atbdp_element_value('input[name="zip"]'));
        form_data.append('hide_contact_info', atbdp_is_checked('hide_contact_info'));
        form_data.append('address', atbdp_element_value('input[name="address"]'));
        form_data.append('phone', atbdp_element_value('input[name="phone"]'));
        form_data.append('phone2', atbdp_element_value('input[name="phone2"]'));
        form_data.append('fax', atbdp_element_value('input[name="fax"]'));
        form_data.append('email', atbdp_element_value('input[name="email"]'));
        form_data.append('website', atbdp_element_value('input[name="website"]'));
        form_data.append('manual_lat', atbdp_element_value('input[name="manual_lat"]'));
        form_data.append('manual_lng', atbdp_element_value('input[name="manual_lng"]'));
        form_data.append('manual_coordinate', atbdp_element_value('input[name="manual_coordinate"]'));
        form_data.append('hide_map', atbdp_is_checked('hide_map'));
        form_data.append('videourl', atbdp_element_value('input[name="videourl"]'));
        form_data.append('guest_user_email', atbdp_element_value('input[name="guest_user_email"]'));
        form_data.append('privacy_policy', atbdp_element_value('input[name="privacy_policy"]:checked'));
        form_data.append('t_c_check', atbdp_element_value('input[name="t_c_check"]:checked'));
        // custom fields
        atbdp_multi_select('input', 'custom_field');
        atbdp_multi_select('textarea', 'custom_field');
        atbdp_multi_select('select', 'custom_field');
        var field_checked = $('input[name^="custom_field"]:checked');
        if (field_checked.length > 1) {
            field_checked.each(function () {
                var name = $(this).attr("name");
                var value = $(this).val();
                form_data.append(name, value);
            });
        } else {
            var name = field_checked.attr("name");
            var value = field_checked.val();
            form_data.append(name, value);
        }
        // locations
        var locaitons = $("#at_biz_dir-location").val();
        if ( Array.isArray( locaitons ) && locaitons.length ) {
            for (var key in locaitons) {
                var value = locaitons[key];
                form_data.append("tax_input[at_biz_dir-location][]", value);
            }
        }

        if ( typeof locaitons === 'string' ) {
            form_data.append("tax_input[at_biz_dir-location][]", locaitons);
        }

        // tags
        var tags = $("#at_biz_dir-tags").val();
        if (tags) {
            for (var key in tags) {
                var value = tags[key];
                form_data.append("tax_input[at_biz_dir-tags][]", value);
            }
        }

        // categories
        var categories = $("#at_biz_dir-categories").val();
        if ( Array.isArray( categories ) && categories.length ) {
            for (var key in categories) {
                var value = categories[key];
                form_data.append("tax_input[at_biz_dir-category][]", value);
            }
        }

        if ( typeof categories === 'string' ) {
            form_data.append("tax_input[at_biz_dir-category][]", categories);
        }

        // social
        if ($('select[name^="social"]').length){
            $('select[name^="social"]').each(function () {
                var name = $(this).attr("name");
                var value = $(this).val();
                form_data.append(name, value);
            });
        }
        if ($('input[name^="social"]').length){
            $('input[name^="social"]').each(function () {
                var name = $(this).attr("name");
                var value = $(this).val();
                form_data.append(name, value);
            });
        }

        // faqs
        if ($('input[name^="faqs"]').length){
            $('input[name^="faqs"]').each(function () {
                var name = $(this).attr("name");
                var value = $(this).val();
                form_data.append(name, value);
            });
        }

        // findbiz video
        if ($('input[name^="findbiz_video"]').length){
            $('input[name^="findbiz_video"]').each(function () {
                var name = $(this).attr("name");
                var value = $(this).val();
                form_data.append(name, value);
            });
        }

        atbdp_multi_select('textarea', 'faqs');
        // google recaptcha
        atbdp_multi_select('textarea', 'g-recaptcha-response');
        // business hours
        form_data.append('disable_bz_hour_listing', atbdp_is_checked('disable_bz_hour_listing'));
        form_data.append('enable247hour', atbdp_is_checked('enable247hour'));
        var bh_field = $('input[name^="bdbh"]');
        if (bh_field.length > 1) {
            bh_field.each(function (index, value) {
                var type = $(value).attr('type');
                if (type === "radio") {
                    var name = $(this).attr("name");
                    if ($(this).is(':checked')) {
                        form_data.append(name, $(this).val());
                    }
                } else if (type === "checkbox") {
                    var name = $(this).attr("name");
                    var value = atbdp_is_checked(name);
                    form_data.append(name, value);
                } else {
                    var name = $(this).attr("name");
                    var value = $(this).val();
                    if (!value) {
                        value = $(this).attr('data-time');
                    }
                    form_data.append(name, value);
                }
            });
        } else {
            var name = bh_field.attr("name");
            var value = bh_field.val();
            form_data.append(name, value);
        }
        form_data.append('timezone', atbdp_element_value('select[name="timezone"]'));
        // booking
        var booking_field = $('.atbdb-wrapper').find('input[name^="bdb"]');
        if (booking_field.length > 1) {
            booking_field.each(function (index, value) {
                    var type = $(value).attr('type');
                    if ((type === "checkbox") || (type === "radio") ) {
                        var name = $(this).attr("name");
                        var value = (type === "radio") ? atbdp_element_value('input[name="'+ name +'"]:checked') : atbdp_is_checked(name);
                        form_data.append(name, value);
                    }else {
                        var name = $(this).attr("name");
                        var value = $(this).val();
                        if (!value) {
                            value = $(this).attr('data-time');
                        }
                        form_data.append(name, value);
                    }
            });
        } else {
            var name = booking_field.attr("name");
            var value = booking_field.val();
            form_data.append(name, value);
        }

        $.ajax({
            method: 'POST',
            processData: false,
            contentType: false,
            url: atbdp_add_listing.ajaxurl,
            data: form_data,
            success: function (response) {
                on_processing = false;
                // show the error notice
                var is_pending = response.pending ? '&' : '?';
                if (response.error === true) {
                    $('#listing_notifier').show().html(`<span>${response.error_msg}</span>`);
                    $(".listing_submit_btn").removeClass("atbd_loading");
                    //window.location.href = response.redirect_url;
                } else {
                    // preview on and no need to redirect to payment
                    if ((response.preview_mode === true) && (response.need_payment !== true)) {
                        if (response.edited_listing !== true) {
                            $('#listing_notifier').show().html(`<span>${response.success_msg}</span>`);
                            window.location.href = response.preview_url + '?preview=1&redirect=' + response.redirect_url;
                        } else {
                            $('#listing_notifier').show().html(`<span>${response.success_msg}</span>`);
                            if (qs['redirect']) {
                                var is_pending = '?';
                                window.location.href = response.preview_url + is_pending + 'post_id=' + response.id + '&preview=1&payment=1&edited=1&redirect=' + qs['redirect'];
                            } else {
                                window.location.href = response.preview_url + '?preview=1&edited=1&redirect=' + response.redirect_url;
                            }
                        }
                        // preview mode active and need payment
                    } else if ((response.preview_mode === true) && (response.need_payment === true)) {
                        window.location.href = response.preview_url + '?preview=1&payment=1&redirect=' + response.redirect_url;
                    } else {
                        var is_edited = response.edited_listing ? is_pending + 'listing_id=' + response.id + '&edited=1' : '';
                        if(response.need_payment === true){
                            $('#listing_notifier').show().html(`<span>${response.success_msg}</span>`);
                            window.location.href = response.redirect_url;
                        }else{
                            $('#listing_notifier').show().html(`<span>${response.success_msg}</span>`);
                            window.location.href = response.redirect_url + is_edited;
                        }
                    }
                }
            },
            error: function (error) {
                on_processing = false;
                $('.listing_submit_btn').attr( 'disabled', false );

                $(".listing_submit_btn").removeClass("atbd_loading");
                console.log(error);
            }
        });

    });

});