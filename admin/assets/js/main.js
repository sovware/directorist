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
    if ($s_wrap.length) {
        $s_wrap.sortable(
            {
                axis: 'y',
                opacity: '0.7'
            }
        );
    }

    // SOCIAL SECTION
    // Rearrange the IDS and Add new social field
    $("#addNewSocial").on('click', function () {
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
    $('#shortcode-updated').on('click', function (event) {
        $('#success_msg').hide();
        event.preventDefault();
        var $this = $(this);
        // display a notice to user to wait
        // send an ajax request to the back end
        atbdp_do_ajax($this, 'atbdp_upgrade_old_pages', null, function (response) {
            if (response.success) {
                $this.after('<p id="success_msg">' + response.data + '</p>');
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

        });

        imageUpload.open();
    });

    $("#price_range").hide();
    $('.atbd_pricing_options label').on('click', function () {
        var $this = $(this);
        $this.children('input[type=checkbox]').prop('checked') == true ? $('#' + $this.data('option')).show() : $('#' + $this.data('option')).hide();
        var $sibling = $this.siblings('label');
        $sibling.children('input[type=checkbox]').prop('checked', false);
        $('#' + $sibling.data('option')).hide();
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
    var logged_count = $("#count_loggedin_user, #views_for_popular").hide();
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
    
    //Display Filter Button Option
    var lf_opt = $("#listings_filter_button_text, #listing_filters_fields, #listings_search_text_placeholder, #listings_category_placeholder, #listings_location_placeholder");
    lf_opt.hide();
    $('input[name="listing_filters_button"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt.show();
        }else{
            lf_opt.hide();
        }
    })
    if($('input[name="listing_filters_button"]').is(":checked") === true){
        lf_opt.show();
    }

    //
    var lf_opt2 = $("#search_result_filter_button_text, #search_result_filters_fields, #search_result_search_text_placeholder, #search_result_category_placeholder, #search_result_location_placeholder");
    lf_opt2.hide();
    $('input[name="search_result_filters_button"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt2.show();
        }else{
            lf_opt2.hide();
        }
    })
    if($('input[name="search_result_filters_button"]').is(":checked") === true){
        lf_opt2.show();
    }

    //Display more filters - option
    var lf_opt3 = $("#search_more_filters_fields, #search_filters, #search_more_filters, #search_reset_filters, #search_apply_filters");
    lf_opt3.hide();
    $('input[name="search_more_filter"]').on("change", function () {
        if($(this).is(":checked") === true){
            lf_opt3.show();
        }else{
            lf_opt3.hide();
        }
    })
    if($('input[name="search_more_filter"]').is(":checked") === true){
        lf_opt3.show();
    }

});


