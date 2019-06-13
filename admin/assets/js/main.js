;(function ($) {
    "use strict";
    // html element create here
    var element = $('#at_biz_dir-categorychecklist li input[type="checkbox"]');
    var container = $("<div>").addClass("container3").appendTo('#atbdp-custom-fields-list');
    var blockDiv;

    $.each(element, function (key, el) {
        var value = $(el).attr('value');
        blockDiv = $("<div>").addClass("block" + value.toString()).appendTo(container);
        $('<span>').appendTo(blockDiv);
    });

    // html element create end here

    // html element create here for selected category
    var element_selected = $('#at_biz_dir-categorychecklist li input[type="checkbox"]');
    var container_selected = $("<div>").addClass("container3").appendTo('#atbdp-custom-fields-list-selected');
    var blockDiv_selected;

    $.each(element_selected, function (key, el) {
        var value = $(el).attr('value');
        blockDiv_selected = $("<div>").addClass("block" + value.toString()).appendTo(container_selected);
        $('<span>').appendTo(blockDiv_selected);

    });
    // html element create end here for selected category

    var content = "";
    // Category icon selection
    $('#category_icon').select2({
        placeholder: atbdp_admin_data.i18n_text.icon_choose_text,
        allowClear: true
    });

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

    $("[data-toggle='tooltip']").tooltip();

    //price range
    var pricerange = $('#pricerange_val').val();
    if (pricerange) {
        $('#pricerange').fadeIn(100);
    }
    $('#price_range_option').on('click', function () {
        $('#pricerange').fadeIn(500);
    });

    // enable sorting if only the container has any social or skill field
    const $s_wrap = $("#social_info_sortable_container"); // cache it
    if(window.outerWidth > 600) {
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
    $("body").on('click', '#addNewSocial', function () {
        const currentItems = $('.atbdp_social_field_wrapper').length;
        const ID = "id=" + currentItems; // eg. 'id=3'
        const iconBindingElement = jQuery('#addNewSocial');
        // arrange names ID in order before adding new elements
        $('.atbdp_social_field_wrapper').each(function (index, element) {
            const e = $(element);
            e.attr('id', 'socialID-' + index);
            e.find('select').attr('name', 'social[' + index + '][id]');
            e.find('.atbdp_social_input').attr('name', 'social[' + index + '][url]');
            e.find('.removeSocialField').attr('data-id', index);
        });
        // now add the new elements. we could do it here without using ajax but it would require more markup here.
        atbdp_do_ajax(iconBindingElement, 'atbdp_social_info_handler', ID, function (data) {
            $s_wrap.append(data);
        });
    });


    // remove the social field and then reset the ids while maintaining position
    $(document).on('click', '.removeSocialField', function (e) {
        const id = $(this).data("id"),
            elementToRemove = $('div#socialID-' + id);
        event.preventDefault();
        /* Act on the event */
        swal({
                title: atbdp_admin_data.i18n_text.confirmation_text,
                text: atbdp_admin_data.i18n_text.ask_conf_sl_lnk_del_txt,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: atbdp_admin_data.i18n_text.confirm_delete,
                closeOnConfirm: false
            },
            function (isConfirm) {
                if (isConfirm) {
                    // user has confirmed, no remove the item and reset the ids
                    elementToRemove.slideUp("fast", function () {
                        elementToRemove.remove();
                        // reorder the index
                        $('.atbdp_social_field_wrapper').each(function (index, element) {
                            const e = $(element);
                            e.attr('id', 'socialID-' + index);
                            e.find('select').attr('name', 'social[' + index + '][id]');
                            e.find('.atbdp_social_input').attr('name', 'social[' + index + '][url]');
                            e.find('.removeSocialField').attr('data-id', index);
                        });
                    });

                    // show success message
                    swal({
                        title: atbdp_admin_data.i18n_text.deleted,
                        //text: "Item has been deleted.",
                        type: "success",
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
                $this.after('<p>' + response.data + '</p>');
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
                $('#shortcode-updated').after('<p id="success_msg">' + response.data + '</p>');

            }
        });

        $('.atbdp_ajax_loading').css({
            display: 'none'
        });

    });


    /*This function handles all ajax request*/
    function atbdp_do_ajax(ElementToShowLoadingIconAfter, ActionName, arg, CallBackHandler) {
        var data;
        if (ActionName) data = "action=" + ActionName;
        if (arg) data = arg + "&action=" + ActionName;
        if (arg && !ActionName) data = arg;
        //data = data ;

        var n = data.search(atbdp_admin_data.nonceName);
        if (n < 0) {
            data = data + "&" + atbdp_admin_data.nonceName + "=" + atbdp_admin_data.nonce;
        }

        jQuery.ajax({
            type: "post",
            url: atbdp_admin_data.ajaxurl,
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
})(jQuery);


// Custom Image uploader for listing image
jQuery(function ($) {
    // Set all variables to be used in scope
    var frame,
        selection,
        multiple_image = true,
        metaBox = $('#_listing_gallery'), // meta box id here
        addImgLink = metaBox.find('#listing_image_btn'),
        delImgLink = metaBox.find('#delete-custom-img'),
        imgContainer = metaBox.find('.listing-img-container'),
        active_mi_ext = atbdp_admin_data.active_mi_ext;

    /*if the multiple image extension is active then set the multiple image parameter to true*/
    if (1 === active_mi_ext) {
        multiple_image = true
    }

    // ADD IMAGE LINK
    addImgLink.on('click', function (event) {

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if (frame) {
            frame.open();
            return;
        }

        // Create a new media frame
        frame = wp.media({
            title: atbdp_admin_data.i18n_text.upload_image,
            button: {
                text: atbdp_admin_data.i18n_text.choose_image
            },
            library: {type: 'image'}, // only allow image upload only
            multiple: multiple_image  // Set to true to allow multiple files to be selected. it will be set based on the availability of Multiple Image extension
        });


        // When an image is selected in the media frame...
        frame.on('select', function () {
            /*get the image collection array if the MI extension is active*/
            /*One little hints: a constant can not be defined inside the if block*/
            if (multiple_image) {
                selection = frame.state().get('selection').toJSON();
            } else {
                selection = frame.state().get('selection').first().toJSON();
            }
            var data = ''; // create a placeholder to save all our image from the selection of media uploader

            // if no image exist then remove the place holder image before appending new image
            if ($('.single_attachment').length === 0) {
                imgContainer.html('');
            }


            //handle multiple image uploading.......
            if (multiple_image) {
                $(selection).each(function () {
                    // here el === this
                    // append the selected element if it is an image
                    if ('image' === this.type) {
                        // we have got an image attachment so lets proceed.
                        // target the input field and then assign the current id of the attachment to an array.
                        data += '<div class="single_attachment">';
                        data += '<input class="listing_image_attachment" name="listing_img[]" type="hidden" value="' + this.id + '">';
                        data += '<img style="width: 100%; height: 100%;" src="' + this.url + '" alt="Listing Image" /> <span class="remove_image fa fa-times" title="Remove it"></span></div>';
                    }

                });
            } else {
                // Handle single image uploading

                // add the id to the input field of the image uploader and then save the ids in the database as a post meta
                // so check if the attachment is really an image and reject other types
                if ('image' === selection.type) {
                    // we have got an image attachment so lets proceed.
                    // target the input field and then assign the current id of the attachment to an array.
                    data += '<div class="single_attachment">';
                    data += '<input class="listing_image_attachment" name="listing_img[]" type="hidden" value="' + selection.id + '">';
                    data += '<img style="width: 100%; height: 100%;" src="' + selection.url + '" alt="Listing Image" /> <span class="remove_image  fa fa-times" title="Remove it"></span></div>';
                }
            }

            // If MI extension is active then append images to the listing, else only add one image replacing previous upload
            if (multiple_image) {
                imgContainer.append(data);
            } else {
                imgContainer.html(data);
            }

            // Un-hide the remove image link
            delImgLink.removeClass('hidden');
        });
        // Finally, open the modal on click
        frame.open();
    });


    // DELETE ALL IMAGES LINK
    delImgLink.on('click', function (event) {
        event.preventDefault();
        // Clear out the preview image and set no image as placeholder
        imgContainer.html('<img src="' + atbdp_admin_data.AdminAssetPath + 'images/no-image.png" alt="Listing Image" />');
        // Hide the delete image link
        delImgLink.addClass('hidden');


    });

    /*REMOVE SINGLE IMAGE*/
    $(document).on('click', '.remove_image', function (e) {
        e.preventDefault();
        $(this).parent().remove();
        // if no image exist then add placeholder and hide remove image button
        if ($('.single_attachment').length === 0) {

            imgContainer.html('<img src="' + atbdp_admin_data.AdminAssetPath + 'images/no-image.png" alt="Listing Image" /><p>No images</p> ' +
                '<small>(allowed formats jpeg. png. gif)</small>');
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
        return;
    }

    $('.upload-header').on('click', function (element) {
        element.preventDefault();

        imageUpload = wp.media.frames.file_frame = wp.media({
            'title': atbdp_admin_data.i18n_text.select_prv_img,
            'button': {
                'text': atbdp_admin_data.i18n_text.insert_prv_img
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

    $('.remove_prev_img').on('click', function(e){
        $(this).hide();
        $('.listing_prv_img').attr('value', '');
        $('.change_listing_prv_img').attr('src', '');
        e.preventDefault();
    })
    if($('.change_listing_prv_img').attr('src') === ''){
        $('.remove_prev_img').hide();
    }else if($('.change_listing_prv_img').attr('src') !== ''){
        $('.remove_prev_img').show();
    }

    $('.remove_cat_img').on('click', function (e) {
        e.preventDefault();
        $(this).hide();
        $(this).prev("img").remove();
        $('#atbdp-categories-image-id').attr("value",'');
    })

    //price range
    $("#price_range").hide();
    var is_checked = $('#atbd_listing_pricing').val();
    if ('range' === is_checked){
        $('#price').hide();
        $("#price_range").show();
    }
    $('.atbd_pricing_options label').on('click', function () {
        var $this = $(this);
        $this.children('input[type=checkbox]').prop('checked')==true ? $('#'+$this.data('option')).show(): $('#'+$this.data('option')).hide();
        var $sibling= $this.siblings('label');
        $sibling.children('input[type=checkbox]').prop('checked', false);
        $('#'+$sibling.data('option')).hide();
    });

    // Load custom fields of the selected category in the custom post type "atbdp_listings"


    // ekhane to apni ul e click event add korecen. eita add howa uchit checkbox e!  Ohh !
    $('#at_biz_dir-categorychecklist').on('click', function (event) {
        //event.stopPropagation();
        //event.preventDefault();
        //event.stopImmediatePropagation();


        $('#atbdp-custom-fields-list').append('<div class="spinner"></div>');
        var termID = $('#at_biz_dir-categorychecklist input:checked').map(function () {
            return this.value
        }).get();

        //console.log(termID);

        // This is use for condition match
        var getid = $('#at_biz_dir-categorychecklist input:checked').last().val();
        var matchid = $('#at_biz_dir-categorychecklist input:checked').last().val();
        // This is use for condition match

        var i;
        var numberOfTermSelected = termID.length;

        for (i = 0; i < numberOfTermSelected; i++) {
            var substr = termID;
        }


        var data = {
            'action': 'atbdp_custom_fields_listings',
            'post_id': $('#atbdp-custom-fields-list').data('post_id'),
            'term_id': termID
        };


        $.post(ajaxurl, data, function (response) {

            var substr = termID;


            $('#at_biz_dir-categorychecklist input[type="checkbox"]').on('click', function () {
                if ($(this).is(':checked')) {
                    var val = $(this).val();
                    $('.block' + val).css({display: "block"});
                } else {
                    var val = $(this).val();
                    $('.block' + val).html(" ");
                    $('.block' + val).css({display: "none"});
                }
            });

            if (matchid === getid) {
                if (response != 0) {
                    $('.block' + getid).html(response);
                    $('[data-toggle="tooltip"]').tooltip();
                } else {
                    $('.block' + getid).html(" ");
                    $('[data-toggle="tooltip"]').tooltip();
                }
            }
        });

        //$('#atbdp-custom-fields-list-selected').hide();

    });

    var selected_cat = $('#at_biz_dir-categorychecklist input:checked').map(function () {
        return this.value
    }).get();
    if (!selected_cat) {

    } else {
        $(document).ready(function () {

            var getid = $('#at_biz_dir-categorychecklist input:checked').last().val();
            var matchid = $('#at_biz_dir-categorychecklist input:checked').last().val();

            $('#atbdp-custom-fields-list-selected').html('<div class="spinner"></div>');
            var data = {
                'action': 'atbdp_custom_fields_listings_selected',
                'post_id': $('#atbdp-custom-fields-list-selected').data('post_id'),
                'term_id': selected_cat
            };

            $.post(ajaxurl, data, function (response) {
                if (response != 0) {
                    $('.block' + getid).html(response);
                    $('[data-toggle="tooltip"]').tooltip();
                } else {
                    $('.block' + getid).html(" ");
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        });
    }

    var avg_review = $("#average_review_for_popular").hide();
    var logged_count = $("#views_for_popular").hide();
    if ($('#listing_popular_by select[name="listing_popular_by"]').val() === "average_rating") {
        avg_review.show();
        logged_count.hide();
    } else if ($('#listing_popular_by select[name="listing_popular_by"]').val() === "view_count") {
        logged_count.show();
        avg_review.hide();
    } else if ($('#listing_popular_by select[name="listing_popular_by"]').val() === "both_view_rating") {
        avg_review.show();
        logged_count.show();
    }
    $('#listing_popular_by select[name="listing_popular_by"]').on("change", function () {
        if ($(this).val() === "average_rating") {
            avg_review.show();
            logged_count.hide();
        } else if ($(this).val() === "view_count") {
            logged_count.show();
            avg_review.hide();
        } else if ($(this).val() === "both_view_rating") {
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
     });*/
    /**
     * Display the media uploader for selecting an image.
     *
     * @since    1.0.0
     */
    function atbdp_render_media_uploader(page) {

        var file_frame, image_data, json;

        // If an instance of file_frame already exists, then we can open it rather than creating a new instance
        if (undefined !== file_frame) {

            file_frame.open();
            return;

        }
        ;

        // Here, use the wp.media library to define the settings of the media uploader
        file_frame = wp.media.frames.file_frame = wp.media({
            frame: 'post',
            state: 'insert',
            multiple: false
        });

        // Setup an event handler for what to do when an image has been selected
        file_frame.on('insert', function () {

            // Read the JSON data returned from the media uploader
            json = file_frame.state().get('selection').first().toJSON();

            // First, make sure that we have the URL of an image to display
            if (0 > $.trim(json.url.length)) {
                return;
            }
            ;

            // After that, set the properties of the image and display it
            if ('listings' == page) {

                var html = '<tr class="atbdp-image-row">' +
                    '<td class="atbdp-handle"><span class="dashicons dashicons-screenoptions"></span></td>' +
                    '<td class="atbdp-image">' +
                    '<img src="' + json.url + '" />' +
                    '<input type="hidden" name="images[]" value="' + json.id + '" />' +
                    '</td>' +
                    '<td>' +
                    json.url + '<br />' +
                    '<a href="post.php?post=' + json.id + '&action=edit" target="_blank">' + atbdp.edit + '</a> | ' +
                    '<a href="javascript:;" class="atbdp-delete-image" data-attachment_id="' + json.id + '">' + atbdp.delete_permanently + '</a>' +
                    '</td>' +
                    '</tr>';

                $('#atbdp-images').append(html);
            } else {

                $('#atbdp-categories-image-id').val(json.id);
                $('#atbdp-categories-image-wrapper').html('<img src="' + json.url + '" />');

            }

        });

        // Now display the actual file_frame
        file_frame.open();

    };

    // Display the media uploader when "Upload Image" button clicked in the custom taxonomy "atbdp_categories"
    $('#atbdp-categories-upload-image').on('click', function (e) {

        e.preventDefault();

        atbdp_render_media_uploader('categories');

    });

    $('#submit').on('click', function () {
        $('#atbdp-categories-image-wrapper img').attr('src', '');
    });
    


    //
    var lf_opt2 = $("#search_result_filter_button_text, #search_result_filters_fields, #search_result_search_text_placeholder, #search_result_category_placeholder, #search_result_location_placeholder,#search_result_display_filter,#search_result_filters_button,#sresult_reset_text,#sresult_apply_text");
    lf_opt2.hide();
    $('input[name="search_result_filters_button_display"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt2.show();
        }else{
            lf_opt2.hide();
        }
    });
    if($('input[name="search_result_filters_button_display"]').is(":checked") === true){
        lf_opt2.show();
    };

    //Display more filters - option
    var lf_opt3 = $("#search_more_filters_fields, #search_filters, #search_more_filters, #search_reset_filters, #search_apply_filters,#home_display_filter,#search_reset_text,#search_apply_filter");
    lf_opt3.hide();
    $('input[name="search_more_filter"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt3.show();
        }else{
            lf_opt3.hide();
        }
    });
    if($('input[name="search_more_filter"]').is(":checked") === true){
        lf_opt3.show();
    };

    //Display more filters - option
    var lf_opt4 = $("#search_result_meta_title");
    lf_opt4.hide();
    $('#meta_title_for_search_result select[name="meta_title_for_search_result"]').on("change", function () {
        if($(this).val() === "custom"){
            lf_opt4.show();
        }else{
            lf_opt4.hide();
        }
    });
    if ($('#meta_title_for_search_result select[name="meta_title_for_search_result"]').val() === "custom") {
        lf_opt4.show();
    }

    //Display Header - option
    var lf_opt5 = $("#all_listing_title");
    lf_opt5.hide();
    $('input[name="display_listings_header"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt5.show();
        }else{
            lf_opt5.hide();
        }
    });
    if($('input[name="display_listings_header"]').is(":checked") === true){
        lf_opt5.show();
    };

    //Display filter button
    var lf_opt6 = $("#listings_filter_button_text, #listings_display_filter, #listing_filters_fields, #listings_filters_button, #listings_reset_text, #listings_apply_text, #listings_search_text_placeholder, #listings_category_placeholder, #listings_location_placeholder");
    lf_opt6.hide();
    $('input[name="listing_filters_button"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt6.show();
        }else{
            lf_opt6.hide();
        }
    });
    if($('input[name="listing_filters_button"]').is(":checked") === true){
        lf_opt6.show();
    }



    //Display filter button
    var lf_opt7 = $("#default_preview_image,#thumbnail_cropping,#crop_width,#crop_height");
    lf_opt7.hide();
    $('input[name="display_preview_image"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt7.show();
        }else{
            lf_opt7.hide();
        }
    });
    if($('input[name="display_preview_image"]').is(":checked") === true){
        lf_opt7.show();
    }

    //
    var lf_opt8 = $("#crop_width, #crop_height");
    lf_opt8.hide();
    $('input[name="thumbnail_cropping"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt8.show();
        }else{
            lf_opt8.hide();
        }
    });
    if($('input[name="thumbnail_cropping"]').is(":checked") === true){
        lf_opt8.show();
    }

    //
    var lf_opt9 = $("#address_location");
    lf_opt9.hide();
    $('input[name="display_contact_info"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt9.show();
        }else{
            lf_opt9.hide();
        }
    });
    if($('input[name="display_contact_info"]').is(":checked") === true){
        lf_opt9.show();
    }

    //
    var lf_opt10 = $("#delete_expired_listings_after, #deletion_mode");
    lf_opt10.hide();
    $('input[name="delete_expired_listing"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt10.show();
        }else{
            lf_opt10.hide();
        }
    });
    if($('input[name="delete_expired_listing"]').is(":checked") === true){
        lf_opt10.show();
    }

    //
    var lf_opt11 = $("#atbdp_listing_slug, #new_listing_status, #edit_listing_status, #edit_listing_redirect, #listing_details_text, #custom_section_lable, #listing_location_text, #contact_info_text, #contact_listing_owner, #atbd_video_title, #dsiplay_prv_single_page, #dsiplay_slider_single_page, #gallery_cropping, #gallery_crop_width, #gallery_crop_height, #enable_social_share, #enable_favourite, #enable_report_abuse, #disable_list_price, #disable_contact_info, #disable_contact_owner, #use_nofollow, #disable_map, #atbd_video_url, #enable_rel_listing, #rel_listing_num, #rel_listing_column");
    lf_opt11.show();
    $('input[name="disable_single_listing"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt11.hide();
        }else{
            lf_opt11.show();
        }
    });
    if($('input[name="disable_single_listing"]').is(":checked") === true){
        lf_opt11.hide();
    }

    //Display all listings sort by dropdown
    var lf_opt12 = $("#listings_sort_by_items");
    lf_opt12.hide();
    $('input[name="display_sort_by"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt12.show();
        }else{
            lf_opt12.hide();
        }
    });
    if($('input[name="display_sort_by"]').is(":checked") === true){
        lf_opt12.show();
    }

    //Display all listings view as dropdown
    var lf_opt13 = $("#listings_view_as_items");
    lf_opt13.hide();
    $('input[name="display_view_as"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt13.show();
        }else{
            lf_opt13.hide();
        }
    });
    if($('input[name="display_view_as"]').is(":checked") === true){
        lf_opt13.show();
    }

    //Display new badge
    var lf_opt14 = $("#new_badge_text,#new_listing_day");
    lf_opt14.hide();
    $('input[name="display_new_badge_cart"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt14.show();
        }else{
            lf_opt14.hide();
        }
    });
    if($('input[name="display_new_badge_cart"]').is(":checked") === true){
        lf_opt14.show();
    }

    //Display featured badge
    var lf_opt15 = $("#feature_badge_text");
    lf_opt15.hide();
    $('input[name="display_feature_badge_cart"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt15.show();
        }else{
            lf_opt15.hide();
        }
    });
    if($('input[name="display_feature_badge_cart"]').is(":checked") === true){
        lf_opt15.show();
    }

    //Display featured badge
    var lf_opt16 = $("#popular_badge_text");
    lf_opt16.hide();
    $('input[name="display_popular_badge_cart"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt16.show();
        }else{
            lf_opt16.hide();
        }
    });
    if($('input[name="display_popular_badge_cart"]').is(":checked") === true){
        lf_opt16.show();
    }

    //Display review rating
    var lf_opt17 = $("#enable_owner_review,#review_num");
    lf_opt17.hide();
    $('input[name="enable_review"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt17.show();
        }else{
            lf_opt17.hide();
        }
    });
    if($('input[name="enable_review"]').is(":checked") === true){
        lf_opt17.show();
    }

    //Display form tagline
    var lf_opt18 = $("#tagline_label,#display_tagline_for");
    lf_opt18.hide();
    $('input[name="display_tagline_field"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt18.show();
        }else{
            lf_opt18.hide();
        }
    });
    if($('input[name="display_tagline_field"]').is(":checked") === true){
        lf_opt18.show();
    }

    //Display form pricing
    var lf_opt19 = $("#price_label,#require_price,#require_price_range,#display_price_for");
    lf_opt19.hide();
    $('input[name="display_pricing_field"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt19.show();
        }else{
            lf_opt19.hide();
        }
    });
    if($('input[name="display_pricing_field"]').is(":checked") === true){
        lf_opt19.show();
    }

    //Display form excerpt
    var lf_opt20 = $("#excerpt_label,#require_excerpt,#display_short_desc_for");
    lf_opt20.hide();
    $('input[name="display_excerpt_field"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt20.show();
        }else{
            lf_opt20.hide();
        }
    });
    if($('input[name="display_excerpt_field"]').is(":checked") === true){
        lf_opt20.show();
    }

    //Display form address
    var lf_opt21 = $("#address_label,#require_address,#display_address_for");
    lf_opt21.hide();
    $('input[name="display_address_field"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt21.show();
        }else{
            lf_opt21.hide();
        }
    });
    if($('input[name="display_address_field"]').is(":checked") === true){
        lf_opt21.show();
    }

    //Display form phone number
    var lf_opt22 = $("#phone_label,#require_phone_number,#display_phone_for");
    lf_opt22.hide();
    $('input[name="display_phone_field"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt22.show();
        }else{
            lf_opt22.hide();
        }
    });
    if($('input[name="display_phone_field"]').is(":checked") === true){
        lf_opt22.show();
    }

    //Display form email
    var lf_opt23 = $("#email_label,#require_email,#display_email_for");
    lf_opt23.hide();
    $('input[name="display_email_field"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt23.show();
        }else{
            lf_opt23.hide();
        }
    });
    if($('input[name="display_email_field"]').is(":checked") === true){
        lf_opt23.show();
    }

    //Display form website
    var lf_opt24 = $("#website_label,#require_website,#display_website_for");
    lf_opt24.hide();
    $('input[name="display_website_field"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt24.show();
        }else{
            lf_opt24.hide();
        }
    });
    if($('input[name="display_website_field"]').is(":checked") === true){
        lf_opt24.show();
    }

    //Display zip/post code
    var lf_opt25 = $("#zip_label,#require_zip,#display_zip_for");
    lf_opt25.hide();
    $('input[name="display_zip_field"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt25.show();
        }else{
            lf_opt25.hide();
        }
    });
    if($('input[name="display_zip_field"]').is(":checked") === true){
        lf_opt25.show();
    }

    //Display social info
    var lf_opt26 = $("#social_label,#require_social_info,#display_social_info_for");
    lf_opt26.hide();
    $('input[name="display_social_info_field"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt26.show();
        }else{
            lf_opt26.hide();
        }
    });
    if($('input[name="display_social_info_field"]').is(":checked") === true){
        lf_opt26.show();
    }

    //Display map
    var lf_opt27 = $("#display_map_for");
    lf_opt27.hide();
    $('input[name="display_map_field"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt27.show();
        }else{
            lf_opt27.hide();
        }
    });
    if($('input[name="display_map_field"]').is(":checked") === true){
        lf_opt27.show();
    }

    //Display form preview image
    var lf_opt28 = $("#preview_label,#require_preview_img,#display_prv_img_for");
    lf_opt28.hide();
    $('input[name="display_prv_field"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt28.show();
        }else{
            lf_opt28.hide();
        }
    });
    if($('input[name="display_prv_field"]').is(":checked") === true){
        lf_opt28.show();
    }

    //Display gallery image
    var lf_opt29 = $("#gellery_label,#require_gallery_img,#display_glr_img_for");
    lf_opt29.hide();
    $('input[name="display_gellery_field"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt29.show();
        }else{
            lf_opt29.hide();
        }
    });
    if($('input[name="display_gellery_field"]').is(":checked") === true){
        lf_opt29.show();
    }

    //Display form video
    var lf_opt30 = $("#video_label,#require_video,#display_video_for");
    lf_opt30.hide();
    $('input[name="display_video_field"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt30.show();
        }else{
            lf_opt30.hide();
        }
    });
    if($('input[name="display_video_field"]').is(":checked") === true){
        lf_opt30.show();
    }

    //Display terms and condition
    var lf_opt31 = $("#require_terms_conditions,#listing_terms_condition_text");
    lf_opt31.hide();
    $('input[name="listing_terms_condition"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt31.show();
        }else{
            lf_opt31.hide();
        }
    });
    if($('input[name="listing_terms_condition"]').is(":checked") === true){
        lf_opt31.show();
    }

    //Display search header
    var lf_opt32 = $("#search_header_title");
    lf_opt32.hide();
    $('input[name="search_header"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt32.show();
        }else{
            lf_opt32.hide();
        }
    });
    if($('input[name="search_header"]').is(":checked") === true){
        lf_opt32.show();
    }

    //Display search view as
    var lf_opt33 = $("#search_view_as_items");
    lf_opt33.hide();
    $('input[name="search_view_as"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt33.show();
        }else{
            lf_opt33.hide();
        }
    });
    if($('input[name="search_view_as"]').is(":checked") === true){
        lf_opt33.show();
    }

    //Display search sort by
    var lf_opt34 = $("#search_sort_by_items");
    lf_opt34.hide();
    $('input[name="search_sort_by"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt34.show();
        }else{
            lf_opt34.hide();
        }
    });
    if($('input[name="search_sort_by"]').is(":checked") === true){
        lf_opt34.show();
    }

    //Display decimal
    var lf_opt35 = $("#g_decimal_separator");
    lf_opt35.hide();
    $('input[name="allow_decimal"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt35.show();
        }else{
            lf_opt35.hide();
        }
    });
    if($('input[name="allow_decimal"]').is(":checked") === true){
        lf_opt35.show();
    }

    //Display email notification
    var lf_opt36 = $("#email_from_name,#email_from_email,#admin_email_lists,#notify_admin,#notify_user");
    lf_opt36.show();
    $('input[name="disable_email_notification"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt36.hide();
        }else{
            lf_opt36.show();
        }
    });
    if($('input[name="disable_email_notification"]').is(":checked") === true){
        lf_opt36.hide();
    }

    //Display email notification
    var lf_opt36 = $("#enable_featured_listing,#featured_listing_title,#featured_listing_desc,#featured_listing_price,#show_featured_ribbon");
    lf_opt36.hide();
    $('input[name="enable_monetization"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt36.show();
        }else{
            lf_opt36.hide();
        }
    });
    if($('input[name="enable_monetization"]').is(":checked") === true){
        lf_opt36.show();
    }

    //Map setting options
    var g_map_api = $("#map_api_key");
    g_map_api.hide();
    $('select[name="select_listing_map"]').on("change", function () {
        if($(this).val() === "google"){
            g_map_api.show();
        }else{
            g_map_api.hide();
        }
    });
    if($('select[name="select_listing_map"]').val() === "google"){
        g_map_api.show();
    }

    //Category & Location default settings js
    var subc_depth = $("#categories_depth_number");
    subc_depth.hide();
    $('select[name="display_categories_as"]').on("change", function () {
        if($(this).val() === "list"){
            subc_depth.show();
        }else{
            subc_depth.hide();
        }
    });
    if($('select[name="display_categories_as"]').val() === "list"){
        subc_depth.show();
    }

    var subl_depth = $("#locations_depth_number");
    subl_depth.hide();
    $('select[name="display_locations_as"]').on("change", function () {
        if($(this).val() === "list"){
            subl_depth.show();
        }else{
            subl_depth.hide();
        }
    });
    if($('select[name="display_locations_as"]').val() === "list"){
        subl_depth.show();
    }

    //Display website in registration form
    var lf_opt37 = $("#reg_website,#require_website_reg");
    lf_opt37.hide();
    $('input[name="display_website_reg"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt37.show();
        }else{
            lf_opt37.hide();
        }
    });
    if($('input[name="display_website_reg"]').is(":checked") === true){
        lf_opt37.show();
    }

    //Display first name in registration form
    var lf_opt38 = $("#reg_fname,#require_fname_reg");
    lf_opt38.hide();
    $('input[name="display_fname_reg"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt38.show();
        }else{
            lf_opt38.hide();
        }
    });
    if($('input[name="display_fname_reg"]').is(":checked") === true){
        lf_opt38.show();
    }

    //Display last name in registration form
    var lf_opt39 = $("#reg_lname,#require_lname_reg");
    lf_opt39.hide();
    $('input[name="display_lname_reg"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt39.show();
        }else{
            lf_opt39.hide();
        }
    });
    if($('input[name="display_lname_reg"]').is(":checked") === true){
        lf_opt39.show();
    }

    //Display bio in registration form
    var lf_opt40 = $("#reg_bio,#require_bio_reg");
    lf_opt40.hide();
    $('input[name="display_bio_reg"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt40.show();
        }else{
            lf_opt40.hide();
        }
    });
    if($('input[name="display_bio_reg"]').is(":checked") === true){
        lf_opt40.show();
    }

    //Display login message in registration form
    var lf_opt41 = $("#reg_login");
    lf_opt41.hide();
    $('input[name="display_login"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt41.show();
        }else{
            lf_opt41.hide();
        }
    });
    if($('input[name="display_login"]').is(":checked") === true){
        lf_opt41.show();
    }

    //Display remember login information in login form
    var lf_opt42 = $("#log_rememberme");
    lf_opt42.hide();
    $('input[name="display_rememberme"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt42.show();
        }else{
            lf_opt42.hide();
        }
    });
    if($('input[name="display_rememberme"]').is(":checked") === true){
        lf_opt42.show();
    }

    //Display sign up login message in login form
    var lf_opt43 = $("#log_signup");
    lf_opt43.hide();
    $('input[name="display_signup"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt43.show();
        }else{
            lf_opt43.hide();
        }
    });
    if($('input[name="display_signup"]').is(":checked") === true){
        lf_opt43.show();
    }

    //Display recover message in login form
    var lf_opt44 = $("#recpass_text,#recpass_desc,#recpass_username,#recpass_placeholder,#recpass_button");
    lf_opt44.hide();
    $('input[name="display_recpass"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt44.show();
        }else{
            lf_opt44.hide();
        }
    });
    if($('input[name="display_recpass"]').is(":checked") === true){
        lf_opt44.show();
    }

    //Display recover message in login form
    var lf_opt45 = $("#reg_password,#require_password_reg");
    lf_opt45.hide();
    $('input[name="display_password_reg"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt45.show();
        }else{
            lf_opt45.hide();
        }
    });
    if($('input[name="display_password_reg"]').is(":checked") === true){
        lf_opt45.show();
    }

    //Display excerpt
    var lf_opt46 = $("#excerpt_limit,#display_readmore,#excerpt_limit,#readmore_text");
    lf_opt46.hide();
    $('input[name="enable_excerpt"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt46.show();
        }else{
            lf_opt46.hide();
        }
    });
    if($('input[name="enable_excerpt"]').is(":checked") === true){
        lf_opt46.show();
    }

    //Display readmore
    var lf_opt47 = $("#readmore_text");
    lf_opt47.hide();
    $('input[name="display_readmore"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt47.show();
        }else{
            lf_opt47.hide();
        }
    });
    if($('input[name="display_readmore"]').is(":checked") === true){
        lf_opt47.show();
    }

});


