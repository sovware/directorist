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
            tokenSeparators: [',', ' ']
        });
    } else {
        $('#at_biz_dir-tags').select2({
            placeholder: atbdp_add_listing.i18n_text.tag_selection,
            allowClear: true
        });
    }
    $('#at_biz_dir-categories').select2({
        placeholder: atbdp_add_listing.cat_placeholder,
        tags: true,
        tokenSeparators: [',', ' ']
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

        $.post(ajaxurl, data, function (response) {
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

    var ezMediaUploader = new EzMediaUploader({
        containerID: "_listing_gallery",
    });
    ezMediaUploader.init();

    var formID = $('#add-listing-form');
    $('body').on('submit', formID, function (e) {
        e.preventDefault();
        var form_data = new FormData();
        function atbdp_multi_select(field, name) {
            var field = $('' + field + '[name^="' + name + '"]');
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

        // ajax action
        form_data.append('action', 'add_listing_action');
        //files
        var files = ezMediaUploader.getTheFiles();
        for (var i = 0; i < files.length; i++) {
            form_data.append('listing_img[]', files[i]);
        }
        var files_meta = ezMediaUploader.getFilesMeta();

        for (var i = 0; i < files_meta.length; i++) {
            var elm = files_meta[i];
            for ( var key in elm ) {
                form_data.append('files_meta['+ i +']['+ key +']', elm[key]);
            }
        }
        var hasValidFiles = ezMediaUploader.hasValidFiles();
        if (!hasValidFiles){
            return;
        }
        var iframe = $('#listing_content_ifr');
        var content = $('#tinymce[data-id="listing_content"]', iframe.contents()).text();

        var excerpt = $("textarea#atbdp_excerpt").val();
        form_data.append('add_listing_nonce', atbdp_add_listing.nonce);
        //form_data.append('add_listing_form', $('input[name="add_listing_form"]').val());
        form_data.append('listing_id', $('input[name="listing_id"]').val());
        form_data.append('listing_title', $('input[name="listing_title"]').val());
        form_data.append('listing_content', content);
        form_data.append('price', $('input[name="price"]').val());
        form_data.append('atbdp_post_views_count', $('input[name="atbdp_post_views_count"]').val());
        form_data.append('excerpt', excerpt);
        form_data.append('atbd_listing_pricing', $('input[name="atbd_listing_pricing"]:checked').val());
        form_data.append('price_range', $('select[name="price_range"]').val());
        //post your need
        form_data.append('need_post', $('input[name="need_post"]:checked').val());
        form_data.append('pyn_deadline', $('input[name="pyn_deadline"]').val());
        form_data.append('is_hourly', atbdp_is_checked('is_hourly'));
        //plans
        form_data.append('listing_type', $('input[name="listing_type"]:checked').val());
        form_data.append('plan', qs['plan']);
        // contact info
        form_data.append('zip', $('input[name="zip"]').val());
        form_data.append('hide_contact_info', atbdp_is_checked('hide_contact_info'));
        form_data.append('address', $('input[name="address"]').val());
        form_data.append('phone', $('input[name="phone"]').val());
        form_data.append('phone2', $('input[name="phone2"]').val());
        form_data.append('fax', $('input[name="fax"]').val());
        form_data.append('email', $('input[name="email"]').val());
        form_data.append('website', $('input[name="website"]').val());
        form_data.append('manual_lat', $('input[name="manual_lat"]').val());
        form_data.append('manual_lng', $('input[name="manual_lng"]').val());
        form_data.append('manual_coordinate', $('input[name="manual_coordinate"]').val());
        form_data.append('hide_map', atbdp_is_checked('hide_map'));
        form_data.append('videourl', $('input[name="videourl"]').val());
        form_data.append('guest_user_email', $('input[name="guest_user_email"]').val());
        form_data.append('privacy_policy', $('input[name="privacy_policy"]:checked').val());
        form_data.append('t_c_check', $('input[name="t_c_check"]:checked').val());
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
        if (locaitons) {
            for (var key in locaitons) {
                var value = locaitons[key];
                form_data.append("tax_input[at_biz_dir-location][]", value);
            }
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
        if (categories) {
            for (var key in categories) {
                var value = categories[key];
                form_data.append("admin_category_select[]", value);
            }
        }
        // social
        $('select[name^="social"]').each(function () {
            var name = $(this).attr("name");
            var value = $(this).val();
            form_data.append(name, value);
        });

        $('input[name^="social"]').each(function () {
            var name = $(this).attr("name");
            var value = $(this).val();
            form_data.append(name, value);
        });
        // faqs
        $('input[name^="faqs"]').each(function () {
            var name = $(this).attr("name");
            var value = $(this).val();
            form_data.append(name, value);
        });
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
                if (type === "checkbox") {
                    var name = $(this).attr("name");
                    var value = atbdp_is_checked(name);
                    form_data.append(name, value);
                }else{
                    var name = $(this).attr("name");
                    var value = $(this).val();
                    form_data.append(name, value);
                }
            });
        } else {
            var name = bh_field.attr("name");
            var value = bh_field.val();
            form_data.append(name, value);
        }
        form_data.append('timezone', $('select[name="timezone"]').val());

        $('#listing_notifier').show().html('Sending information, Please wait..');
        $.ajax({
            method: 'POST',
            processData: false,
            contentType: false,
            url: ajaxurl,
            data: form_data,
            success: function (response) {
                // var data = JSON.parse(response);
                console.log(response);
                if ((response.success === true) || (response.need_payment === true)) {
                    $('#listing_notifier').show().html(response.success_msg);
                    window.location.href = response.redirect_url;
                }
                if (response.error === true) {
                    $('#listing_notifier').show().html(response.error_msg);
                    //window.location.href = response.redirect_url;
                }
            },
            error: function (error) {
                console.log(error);
            }
        });

    });

});