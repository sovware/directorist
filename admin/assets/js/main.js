(function($) {
        const content = '';
        // Category icon selection
        function selecWithIcon(selected) {
                if (!selected.id) {
                        return selected.text;
                }
                const $elem = $(
                        `<span><span class='${atbdp_admin_data.icon_type} ${selected.element.value}'></span> ${
                                selected.text
                        }</span>`
                );
                return $elem;
        }

        $('#category_icon').select2({
                placeholder: atbdp_admin_data.i18n_text.icon_choose_text,
                allowClear: true,
                templateResult: selecWithIcon,
        });

        $("[data-toggle='tooltip']").tooltip();

        // price range
        const pricerange = $('#pricerange_val').val();
        if (pricerange) {
                $('#pricerange').fadeIn(100);
        }
        $('#price_range_option').on('click', function() {
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
        $('body').on('click', '#addNewSocial', function() {
                const social_wrap = $('#social_info_sortable_container'); // cache it
                const currentItems = $('.atbdp_social_field_wrapper').length;
                const ID = `id=${currentItems}`; // eg. 'id=3'
                const iconBindingElement = jQuery('#addNewSocial');
                // arrange names ID in order before adding new elements
                $('.atbdp_social_field_wrapper').each(function(index, element) {
                        const e = $(element);
                        e.attr('id', `socialID-${index}`);
                        e.find('select').attr('name', `social[${index}][id]`);
                        e.find('.atbdp_social_input').attr('name', `social[${index}][url]`);
                        e.find('.removeSocialField').attr('data-id', index);
                });
                // now add the new elements. we could do it here without using ajax but it would require more markup here.
                atbdp_do_ajax(iconBindingElement, 'atbdp_social_info_handler', ID, function(data) {
                        social_wrap.append(data);
                });
        });

        // remove the social field and then reset the ids while maintaining position
        $(document).on('click', '.removeSocialField', function(e) {
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
                        function(isConfirm) {
                                if (isConfirm) {
                                        // user has confirmed, no remove the item and reset the ids
                                        elementToRemove.slideUp('fast', function() {
                                                elementToRemove.remove();
                                                // reorder the index
                                                $('.atbdp_social_field_wrapper').each(function(index, element) {
                                                        const e = $(element);
                                                        e.attr('id', `socialID-${index}`);
                                                        e.find('select').attr('name', `social[${index}][id]`);
                                                        e.find('.atbdp_social_input').attr(
                                                                'name',
                                                                `social[${index}][url]`
                                                        );
                                                        e.find('.removeSocialField').attr('data-id', index);
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
        $('#upgrade_directorist').on('click', function(event) {
                event.preventDefault();
                const $this = $(this);
                // display a notice to user to wait
                // send an ajax request to the back end
                atbdp_do_ajax($this, 'atbdp_upgrade_old_listings', null, function(response) {
                        if (response.success) {
                                $this.after(`<p>${response.data}</p>`);
                        }
                });
        });

        // upgrade old pages
        $('#shortcode-updated input[name="shortcode-updated"]').on('change', function(event) {
                event.preventDefault();
                $('#success_msg').hide();

                const $this = $(this);
                // display a notice to user to wait
                // send an ajax request to the back end
                atbdp_do_ajax($this, 'atbdp_upgrade_old_pages', null, function(response) {
                        if (response.success) {
                                $('#shortcode-updated').after(`<p id="success_msg">${response.data}</p>`);
                        }
                });

                $('.atbdp_ajax_loading').css({
                        display: 'none',
                });
        });

        // redirect to import import_page_link
        $('#csv_import input[name="csv_import"]').on('change', function(event) {
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
})(jQuery);

// Custom Image uploader for listing image
(function($) {
        // Set all variables to be used in scope

        const has_tagline = $('#has_tagline').val();
        const has_excerpt = $('#has_excerpt').val();
        if (has_excerpt && has_tagline) {
                $('.atbd_tagline_moto_field').fadeIn();
        } else {
                $('.atbd_tagline_moto_field').fadeOut();
        }

        $('#atbd_optional_field_check').on('change', function() {
                $(this).is(':checked')
                        ? $('.atbd_tagline_moto_field').fadeIn()
                        : $('.atbd_tagline_moto_field').fadeOut();
        });

        // price range
        $('#price_range').hide();
        const is_checked = $('#atbd_listing_pricing').val();
        if (is_checked === 'range') {
                $('#price').hide();
                $('#price_range').show();
        }
        $('.atbd_pricing_options label').on('click', function() {
                const $this = $(this);
                $this.children('input[type=checkbox]').prop('checked') == true
                        ? $(`#${$this.data('option')}`).show()
                        : $(`#${$this.data('option')}`).hide();
                const $sibling = $this.siblings('label');
                $sibling.children('input[type=checkbox]').prop('checked', false);
                $(`#${$sibling.data('option')}`).hide();
        });

        // Load custom fields of the selected category in the custom post type "atbdp_listings"
        $('#at_biz_dir-categorychecklist').on('change', function(event) {
                const length = $('#at_biz_dir-categorychecklist input:checked');
                const id = [];
                const directory_type = $('select[name="directory_type"]').val();
                length.each((el, index) => {
                        id.push($(index).val());
                });
                const data = {
                        action: 'atbdp_custom_fields_listings',
                        post_id: $('#post_ID').val(),
                        term_id: id,
                        directory_type,
                };
                $.post(ajaxurl, data, function(response) {
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
                                        atbd_tooltip.forEach(function(el) {
                                                if (el.getAttribute('aria-label') !== ' ') {
                                                        document.body.addEventListener(
                                                                'mouseover',
                                                                function(e) {
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
        $(document).ready(function() {
                const length = $('#at_biz_dir-categorychecklist input:checked');
                const id = [];
                const directory_type = $('select[name="directory_type"]').val();
                length.each((el, index) => {
                        id.push($(index).val());
                });
                const data = {
                        action: 'atbdp_custom_fields_listings',
                        post_id: $('#post_ID').val(),
                        term_id: id,
                        directory_type,
                };
                $.post(ajaxurl, data, function(response) {
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
                                        atbd_tooltip.forEach(function(el) {
                                                if (el.getAttribute('aria-label') !== ' ') {
                                                        document.body.addEventListener(
                                                                'mouseover',
                                                                function(e) {
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
        $('#listing_popular_by select[name="listing_popular_by"]').on('change', function() {
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
                $('#hide_if_no_manual_cor').hide();
        }
        $('#manual_coordinate').on('click', function(e) {
                if ($('input#manual_coordinate').is(':checked')) {
                        $('#hide_if_no_manual_cor').show();
                } else {
                        $('#hide_if_no_manual_cor').hide();
                }
        });

        $("[data-toggle='tooltip']").tooltip();

        // price range
        const pricerange = $('#pricerange_val').val();
        if (pricerange) {
                $('#pricerange').fadeIn(100);
        }
        $('#price_range_option').on('click', function() {
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
        $('body').on('click', '#addNewSocial', function() {
                const currentItems = $('.atbdp_social_field_wrapper').length;
                const ID = `id=${currentItems}`; // eg. 'id=3'
                const iconBindingElement = jQuery('#addNewSocial');
                // arrange names ID in order before adding new elements
                $('.atbdp_social_field_wrapper').each(function(index, element) {
                        const e = $(element);
                        e.attr('id', `socialID-${index}`);
                        e.find('select').attr('name', `social[${index}][id]`);
                        e.find('.atbdp_social_input').attr('name', `social[${index}][url]`);
                        e.find('.removeSocialField').attr('data-id', index);
                });
                // now add the new elements. we could do it here without using ajax but it would require more markup here.
                atbdp_do_ajax(iconBindingElement, 'atbdp_social_info_handler', ID, function(data) {
                        $s_wrap.append(data);
                });
        });

        // remove the social field and then reset the ids while maintaining position
        $(document).on('click', '.removeSocialField', function(e) {
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
                        function(isConfirm) {
                                if (isConfirm) {
                                        // user has confirmed, no remove the item and reset the ids
                                        elementToRemove.slideUp('fast', function() {
                                                elementToRemove.remove();
                                                // reorder the index
                                                $('.atbdp_social_field_wrapper').each(function(index, element) {
                                                        const e = $(element);
                                                        e.attr('id', `socialID-${index}`);
                                                        e.find('select').attr('name', `social[${index}][id]`);
                                                        e.find('.atbdp_social_input').attr(
                                                                'name',
                                                                `social[${index}][url]`
                                                        );
                                                        e.find('.removeSocialField').attr('data-id', index);
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
        $('#upgrade_directorist').on('click', function(event) {
                event.preventDefault();
                const $this = $(this);
                // display a notice to user to wait
                // send an ajax request to the back end
                atbdp_do_ajax($this, 'atbdp_upgrade_old_listings', null, function(response) {
                        if (response.success) {
                                $this.after(`<p>${response.data}</p>`);
                        }
                });
        });

        // upgrade old pages
        $('#shortcode-updated input[name="shortcode-updated"]').on('change', function(event) {
                event.preventDefault();
                $('#success_msg').hide();

                const $this = $(this);
                // display a notice to user to wait
                // send an ajax request to the back end
                atbdp_do_ajax($this, 'atbdp_upgrade_old_pages', null, function(response) {
                        if (response.success) {
                                $('#shortcode-updated').after(`<p id="success_msg">${response.data}</p>`);
                        }
                });

                $('.atbdp_ajax_loading').css({
                        display: 'none',
                });
        });

        // send system info to admin
        $('#atbdp-send-system-info-submit').on('click', function(event) {
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
                                        $('.system_info_success').html('Successfully send');
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
        $('#generate-url').on('click', function(e) {
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

        $('#revoke-url').on('click', function(e) {
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
        $('#csv_import input[name="csv_import"]').on('change', function(event) {
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
})(jQuery);

// Custom Image uploader for listing image
(function($) {
        // Set all variables to be used in scope
        let frame;
        let selection;
        const multiple_image = true;
        const metaBox = $('#_listing_gallery'); // meta box id here
        const addImgLink = metaBox.find('#listing_image_btn');
        const delImgLink = metaBox.find('#delete-custom-img');
        const imgContainer = metaBox.find('.listing-img-container');

        // ADD IMAGE LINK
        addImgLink.on('click', function(event) {
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
                                text: atbdp_admin_data.i18n_text.choose_image,
                        },
                        library: { type: 'image' }, // only allow image upload only
                        multiple: multiple_image, // Set to true to allow multiple files to be selected. it will be set based on the availability of Multiple Image extension
                });

                // When an image is selected in the media frame...
                frame.on('select', function() {
                        /* get the image collection array if the MI extension is active */
                        /* One little hints: a constant can not be defined inside the if block */
                        if (multiple_image) {
                                selection = frame
                                        .state()
                                        .get('selection')
                                        .toJSON();
                        } else {
                                selection = frame
                                        .state()
                                        .get('selection')
                                        .first()
                                        .toJSON();
                        }
                        let data = ''; // create a placeholder to save all our image from the selection of media uploader

                        // if no image exist then remove the place holder image before appending new image
                        if ($('.single_attachment').length === 0) {
                                imgContainer.html('');
                        }

                        // handle multiple image uploading.......
                        if (multiple_image) {
                                $(selection).each(function() {
                                        // here el === this
                                        // append the selected element if it is an image
                                        if (this.type === 'image') {
                                                // we have got an image attachment so lets proceed.
                                                // target the input field and then assign the current id of the attachment to an array.
                                                data += '<div class="single_attachment">';
                                                data += `<input class="listing_image_attachment" name="listing_img[]" type="hidden" value="${
                                                        this.id
                                                }">`;
                                                data += `<img style="width: 100%; height: 100%;" src="${
                                                        this.url
                                                }" alt="Listing Image" /> <span class="remove_image fa fa-times" title="Remove it"></span></div>`;
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
                                        data += `<input class="listing_image_attachment" name="listing_img[]" type="hidden" value="${
                                                selection.id
                                        }">`;
                                        data += `<img style="width: 100%; height: 100%;" src="${
                                                selection.url
                                        }" alt="Listing Image" /> <span class="remove_image  fa fa-times" title="Remove it"></span></div>`;
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
        delImgLink.on('click', function(event) {
                event.preventDefault();
                // Clear out the preview image and set no image as placeholder
                imgContainer.html(
                        `<img src="${atbdp_admin_data.AdminAssetPath}images/no-image.png" alt="Listing Image" />`
                );
                // Hide the delete image link
                delImgLink.addClass('hidden');
        });

        /* REMOVE SINGLE IMAGE */
        $(document).on('click', '.remove_image', function(e) {
                e.preventDefault();
                $(this)
                        .parent()
                        .remove();
                // if no image exist then add placeholder and hide remove image button
                if ($('.single_attachment').length === 0) {
                        imgContainer.html(
                                `<img src="${
                                        atbdp_admin_data.AdminAssetPath
                                }images/no-image.png" alt="Listing Image" /><p>No images</p> ` +
                                        `<small>(allowed formats jpeg. png. gif)</small>`
                        );
                        delImgLink.addClass('hidden');
                }
        });

        const has_tagline = $('#has_tagline').val();
        const has_excerpt = $('#has_excerpt').val();
        if (has_excerpt && has_tagline) {
                $('.atbd_tagline_moto_field').fadeIn();
        } else {
                $('.atbd_tagline_moto_field').fadeOut();
        }

        $('#atbd_optional_field_check').on('change', function() {
                $(this).is(':checked')
                        ? $('.atbd_tagline_moto_field').fadeIn()
                        : $('.atbd_tagline_moto_field').fadeOut();
        });

        let imageUpload;
        if (imageUpload) {
                imageUpload.open();
                return;
        }

        $('.upload-header').on('click', function(element) {
                element.preventDefault();

                imageUpload = wp.media.frames.file_frame = wp.media({
                        title: atbdp_admin_data.i18n_text.select_prv_img,
                        button: {
                                text: atbdp_admin_data.i18n_text.insert_prv_img,
                        },
                });
                imageUpload.open();

                imageUpload.on('select', function() {
                        prv_image = imageUpload
                                .state()
                                .get('selection')
                                .first()
                                .toJSON();
                        prv_url = prv_image.id;
                        prv_img_url = prv_image.url;

                        $('.listing_prv_img').val(prv_url);
                        $('.change_listing_prv_img').attr('src', prv_img_url);
                        $('.upload-header').html('Change Preview Image');
                        $('.remove_prev_img').show();
                });

                imageUpload.open();
        });

        $('.remove_prev_img').on('click', function(e) {
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

        // price range
        $('#price_range').hide();
        const is_checked = $('#atbd_listing_pricing').val();
        if (is_checked === 'range') {
                $('#price').hide();
                $('#price_range').show();
        }
        $('.atbd_pricing_options label').on('click', function() {
                const $this = $(this);
                $this.children('input[type=checkbox]').prop('checked') == true
                        ? $(`#${$this.data('option')}`).show()
                        : $(`#${$this.data('option')}`).hide();
                const $sibling = $this.siblings('label');
                $sibling.children('input[type=checkbox]').prop('checked', false);
                $(`#${$sibling.data('option')}`).hide();
        });

        // Load custom fields of the selected category in the custom post type "atbdp_listings"

        // ekhane to apni ul e click event add korecen. eita add howa uchit checkbox e!  Ohh !
        $('#at_biz_dir-categorychecklist').on('change', function(event) {
                $('#atbdp-custom-fields-list').append('<div class="spinner"></div>');

                const length = $('#at_biz_dir-categorychecklist input:checked');
                const id = [];
                length.each((el, index) => {
                        id.push($(index).val());
                });
                const data = {
                        action: 'atbdp_custom_fields_listings',
                        post_id: $('#atbdp-custom-fields-list').data('post_id'),
                        term_id: id,
                };
                $.post(ajaxurl, data, function(response) {
                        if (response == ' 0') {
                                $('#atbdp-custom-fields-list').hide();
                        } else {
                                $('#atbdp-custom-fields-list').show();
                        }
                        $('#atbdp-custom-fields-list').html(response);
                });
                $('#atbdp-custom-fields-list-selected').hide();
        });

        var length = $('#at_biz_dir-categorychecklist input:checked');
        if (length) {
                $('#atbdp-custom-fields-list-selected').html('<div class="spinner"></div>');

                var length = $('#at_biz_dir-categorychecklist input:checked');
                const id = [];
                length.each((el, index) => {
                        id.push($(index).val());
                });
                const data = {
                        action: 'atbdp_custom_fields_listings_selected',
                        post_id: $('#atbdp-custom-fields-list-selected').data('post_id'),
                        term_id: id,
                };

                $.post(ajaxurl, data, function(response) {
                        $('#atbdp-custom-fields-list-selected').html(response);
                });
        }

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
        $('#listing_popular_by select[name="listing_popular_by"]').on('change', function() {
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
                let file_frame;
                let image_data;
                let json;

                // If an instance of file_frame already exists, then we can open it rather than creating a new instance
                if (undefined !== file_frame) {
                        file_frame.open();
                        return;
                }
                // Here, use the wp.media library to define the settings of the media uploader
                file_frame = wp.media.frames.file_frame = wp.media({
                        frame: 'post',
                        state: 'insert',
                        multiple: false,
                });

                // Setup an event handler for what to do when an image has been selected
                file_frame.on('insert', function() {
                        // Read the JSON data returned from the media uploader
                        json = file_frame
                                .state()
                                .get('selection')
                                .first()
                                .toJSON();

                        // First, make sure that we have the URL of an image to display
                        if ($.trim(json.url.length) < 0) {
                                return;
                        }
                        // After that, set the properties of the image and display it
                        if (page == 'listings') {
                                const html =
                                        `${'<tr class="atbdp-image-row">' +
                                                '<td class="atbdp-handle"><span class="dashicons dashicons-screenoptions"></span></td>' +
                                                '<td class="atbdp-image">' +
                                                '<img src="'}${json.url}" />` +
                                        `<input type="hidden" name="images[]" value="${json.id}" />` +
                                        `</td>` +
                                        `<td>${json.url}<br />` +
                                        `<a href="post.php?post=${json.id}&action=edit" target="_blank">${
                                                atbdp.edit
                                        }</a> | ` +
                                        `<a href="javascript:;" class="atbdp-delete-image" data-attachment_id="${
                                                json.id
                                        }">${atbdp.delete_permanently}</a>` +
                                        `</td>` +
                                        `</tr>`;

                                $('#atbdp-images').append(html);
                        } else {
                                $('#atbdp-categories-image-id').val(json.id);
                                $('#atbdp-categories-image-wrapper').html(
                                        `<img src="${
                                                json.url
                                        }" /><a href="" class="remove_cat_img"><span class="fa fa-times" title="Remove it"></span></a>`
                                );
                        }
                });

                // Now display the actual file_frame
                file_frame.open();
        }

        // Display the media uploader when "Upload Image" button clicked in the custom taxonomy "atbdp_categories"
        $('#atbdp-categories-upload-image').on('click', function(e) {
                e.preventDefault();

                atbdp_render_media_uploader('categories');
        });

        $('#submit').on('click', function() {
                $('#atbdp-categories-image-wrapper img').attr('src', '');
                $('.remove_cat_img').remove();
        });

        $(document).on('click', '.remove_cat_img', function(e) {
                e.preventDefault();
                $(this).hide();
                $(this)
                        .prev('img')
                        .remove();
                $('#atbdp-categories-image-id').attr('value', '');
        });

        // Announcement
        // ----------------------------------------------------------------------------------
        // Display Announcement Recepents
        const announcement_to = $('select[name="announcement_to"]');
        const announcement_recepents_section = $('#announcement_recepents');
        toggle_section('selected_user', announcement_to, announcement_recepents_section);
        announcement_to.on('change', function() {
                toggle_section('selected_user', $(this), announcement_recepents_section);
        });

        const submit_button = $('#announcement_submit .vp-input ~ span');
        const form_feedback = $('#announcement_submit .field');
        form_feedback.prepend('<div class="announcement-feedback"></div>');

        let announcement_is_sending = false;

        // Send Announcement
        submit_button.on('click', function() {
                if (announcement_is_sending) {
                        console.log('Please wait...');
                        return;
                }

                const to = $('select[name="announcement_to"]');
                const recepents = $('select[name="announcement_recepents"]');
                const subject = $('input[name="announcement_subject"]');
                const message = $('textarea[name="announcement_message"]');
                const expiration = $('input[name="announcement_expiration"]');
                const send_to_email = $('input[name="announcement_send_to_email"]');

                const fields_elm = {
                        to: { elm: to, value: to.val(), default: 'all_user' },
                        recepents: { elm: recepents, value: recepents.val(), default: null },
                        subject: { elm: subject, value: subject.val(), default: '' },
                        message: { elm: message, value: message.val(), default: '' },
                        expiration: { elm: expiration, value: expiration.val(), default: 3 },
                        send_to_email: { elm: send_to_email.val(), value: send_to_email.val(), default: 1 },
                };

                // Send the form
                const form_data = new FormData();

                // Fillup the form
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
                        beforeSend() {
                                // console.log( 'Sending...' );
                                form_feedback
                                        .find('.announcement-feedback')
                                        .html('<div class="form-alert">Sending the announcement, please wait..</div>');
                        },
                        success(response) {
                                // console.log( {response} );
                                announcement_is_sending = false;

                                if (response.message) {
                                        form_feedback
                                                .find('.announcement-feedback')
                                                .html(`<div class="form-alert">${response.message}</div>`);
                                }
                        },
                        error(error) {
                                console.log({ error });
                                announcement_is_sending = false;
                        },
                });

                // Reset Form
                /* for ( var field in fields_elm  ) {
          $( fields_elm[ field ].elm ).val( fields_elm[ field ].default );
      } */
        });

        // ----------------------------------------------------------------------------------

        // Custom Tab Support Status
        $('.atbds_wrapper a.nav-link').on('click', function(e) {
                e.preventDefault();

                console.log($(this).data('tabarea'));
                const atbds_tabParent = $(this)
                        .parent()
                        .parent()
                        .find('a.nav-link');
                const $href = $(this).attr('href');
                $(atbds_tabParent).removeClass('active');
                $(this).addClass('active');
                console.log($(".tab-content[data-tabarea='atbds_system-info-tab']"));

                switch ($(this).data('tabarea')) {
                        case 'atbds_system-status-tab':
                                $(".tab-content[data-tabarea='atbds_system-status-tab'] >.tab-pane").removeClass(
                                        'active show'
                                );
                                $(`.tab-content[data-tabarea='atbds_system-status-tab'] ${$href}`).addClass(
                                        'active show'
                                );
                                break;
                        case 'atbds_system-info-tab':
                                $(".tab-content[data-tabarea='atbds_system-info-tab'] >.tab-pane").removeClass(
                                        'active show'
                                );
                                $(`.tab-content[data-tabarea='atbds_system-info-tab'] ${$href}`).addClass(
                                        'active show'
                                );
                                break;
                        default:
                                break;
                }
        });

        // Custom Tooltip Support Added
        $('.atbds_tooltip').on('hover', function() {
                const toolTipLabel = $(this).data('label');
                console.log(toolTipLabel);
                $(this)
                        .find('.atbds_tooltip__text')
                        .text(toolTipLabel);
                $(this)
                        .find('.atbds_tooltip__text')
                        .addClass('show');
        });

        $('.atbds_tooltip').on('mouseleave', function() {
                $('.atbds_tooltip__text').removeClass('show');
        });

        // load admin add listing form
        const directory_type = $('select[name="directory_type"]').val();
        if (directory_type) {
                admin_listing_form(directory_type);
        }
        $('body').on('change', 'select[name="directory_type"]', function() {
                admin_listing_form($(this).val());
        });

        function admin_listing_form(directory_type) {
                $.ajax({
                        type: 'post',
                        url: atbdp_admin_data.ajaxurl,
                        data: {
                                action: 'atbdp_dynamic_admin_listing_form',
                                directory_type,
                                listing_id: $('#directiost-listing-fields_wrapper').data('id'),
                        },
                        success(response) {
                                $('#directiost-listing-fields_wrapper')
                                        .empty()
                                        .append(response);
                                assetsNeedToWorkInVirtualDom();
                        },
                });
        }

        // default directory type
        $('body').on('click', '.submitdefault', function(e) {
                e.preventDefault();
                $('.submitDefaultCheckbox').prop('checked', true);
                $.ajax({
                        type: 'post',
                        url: atbdp_admin_data.ajaxurl,
                        data: {
                                action: 'atbdp_listing_default_type',
                                type_id: $(this).data('type-id'),
                        },
                        success(response) {
                                $('.submitdefault').append(
                                        `<span class="atbd-listing-type-active-status">${response}</span>`
                                );
                                setTimeout(function() {
                                        location.reload();
                                }, 500);
                        },
                });
        });

        function assetsNeedToWorkInVirtualDom() {
                // price range
                $('#price_range').hide();
                const pricing = $('#atbd_listing_pricing').val();
                if (pricing === 'range') {
                        $('#price').hide();
                        $('#price_range').show();
                }
                $('.atbd_pricing_options label').on('click', function() {
                        const $this = $(this);
                        $this.children('input[type=checkbox]').prop('checked') == true
                                ? $(`#${$this.data('option')}`).show()
                                : $(`#${$this.data('option')}`).hide();
                        const $sibling = $this.siblings('label');
                        $sibling.children('input[type=checkbox]').prop('checked', false);
                        $(`#${$sibling.data('option')}`).hide();
                });

                let frame;
                let selection;
                const multiple_image = true;
                const metaBox = $('#_listing_gallery'); // meta box id here
                const addImgLink = metaBox.find('#listing_image_btn');
                const delImgLink = metaBox.find('#delete-custom-img');
                const imgContainer = metaBox.find('.listing-img-container');

                // ADD IMAGE LINK
                addImgLink.on('click', function(event) {
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
                                        text: atbdp_admin_data.i18n_text.choose_image,
                                },
                                library: { type: 'image' }, // only allow image upload only
                                multiple: multiple_image, // Set to true to allow multiple files to be selected. it will be set based on the availability of Multiple Image extension
                        });

                        // When an image is selected in the media frame...
                        frame.on('select', function() {
                                /* get the image collection array if the MI extension is active */
                                /* One little hints: a constant can not be defined inside the if block */
                                if (multiple_image) {
                                        selection = frame
                                                .state()
                                                .get('selection')
                                                .toJSON();
                                } else {
                                        selection = frame
                                                .state()
                                                .get('selection')
                                                .first()
                                                .toJSON();
                                }
                                let data = ''; // create a placeholder to save all our image from the selection of media uploader

                                // if no image exist then remove the place holder image before appending new image
                                if ($('.single_attachment').length === 0) {
                                        imgContainer.html('');
                                }

                                // handle multiple image uploading.......
                                if (multiple_image) {
                                        $(selection).each(function() {
                                                // here el === this
                                                // append the selected element if it is an image
                                                if (this.type === 'image') {
                                                        // we have got an image attachment so lets proceed.
                                                        // target the input field and then assign the current id of the attachment to an array.
                                                        data += '<div class="single_attachment">';
                                                        data += `<input class="listing_image_attachment" name="listing_img[]" type="hidden" value="${
                                                                this.id
                                                        }">`;
                                                        data += `<img style="width: 100%; height: 100%;" src="${
                                                                this.url
                                                        }" alt="Listing Image" /> <span class="remove_image fa fa-times" title="Remove it"></span></div>`;
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
                                                data += `<input class="listing_image_attachment" name="listing_img[]" type="hidden" value="${
                                                        selection.id
                                                }">`;
                                                data += `<img style="width: 100%; height: 100%;" src="${
                                                        selection.url
                                                }" alt="Listing Image" /> <span class="remove_image  fa fa-times" title="Remove it"></span></div>`;
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
                delImgLink.on('click', function(event) {
                        event.preventDefault();
                        // Clear out the preview image and set no image as placeholder
                        imgContainer.html(
                                `<img src="${
                                        atbdp_admin_data.AdminAssetPath
                                }images/no-image.png" alt="Listing Image" />`
                        );
                        // Hide the delete image link
                        delImgLink.addClass('hidden');
                });

                /* REMOVE SINGLE IMAGE */
                $(document).on('click', '.remove_image', function(e) {
                        e.preventDefault();
                        $(this)
                                .parent()
                                .remove();
                        // if no image exist then add placeholder and hide remove image button
                        if ($('.single_attachment').length === 0) {
                                imgContainer.html(
                                        `<img src="${
                                                atbdp_admin_data.AdminAssetPath
                                        }images/no-image.png" alt="Listing Image" /><p>No images</p> ` +
                                                `<small>(allowed formats jpeg. png. gif)</small>`
                                );
                                delImgLink.addClass('hidden');
                        }
                });

                let imageUpload;
                if (imageUpload) {
                        imageUpload.open();
                        return;
                }

                $('.upload-header').on('click', function(element) {
                        element.preventDefault();

                        imageUpload = wp.media.frames.file_frame = wp.media({
                                title: atbdp_admin_data.i18n_text.select_prv_img,
                                button: {
                                        text: atbdp_admin_data.i18n_text.insert_prv_img,
                                },
                        });
                        imageUpload.open();

                        imageUpload.on('select', function() {
                                prv_image = imageUpload
                                        .state()
                                        .get('selection')
                                        .first()
                                        .toJSON();
                                prv_url = prv_image.id;
                                prv_img_url = prv_image.url;

                                $('.listing_prv_img').val(prv_url);
                                $('.change_listing_prv_img').attr('src', prv_img_url);
                                $('.upload-header').html('Change Preview Image');
                                $('.remove_prev_img').show();
                        });

                        imageUpload.open();
                });

                $('.remove_prev_img').on('click', function(e) {
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
                        $('#hide_if_no_manual_cor').hide();
                }
                $('#manual_coordinate').on('click', function(e) {
                        if ($('input#manual_coordinate').is(':checked')) {
                                $('#hide_if_no_manual_cor').show();
                        } else {
                                $('#hide_if_no_manual_cor').hide();
                        }
                });
        }
})(jQuery);

// toggle_section
function toggle_section(show_if_value, subject_elm, terget_elm) {
        if (show_if_value === subject_elm.val()) {
                terget_elm.show();
        } else {
                terget_elm.hide();
        }
}

// Helpers
// -----------------------------------
/*
    Plugin: PureScriptTab
    Version: 1.0.0
    License: MIT
*/
(function($) {
        pureScriptTab = selector1 => {
                const selector = document.querySelectorAll(selector1);
                selector.forEach((el, index) => {
                        a = el.querySelectorAll('.atbd_tn_link');

                        a.forEach((element, index) => {
                                element.style.cursor = 'pointer';
                                element.addEventListener('click', event => {
                                        event.preventDefault();
                                        event.stopPropagation();

                                        const ul = event.target.closest('.atbd_tab_nav');
                                        const main = ul.nextElementSibling;
                                        const item_a = ul.querySelectorAll('.atbd_tn_link');
                                        const section = main.querySelectorAll('.atbd_tab_inner');

                                        item_a.forEach((ela, ind) => {
                                                ela.classList.remove('tabItemActive');
                                        });
                                        event.target.classList.add('tabItemActive');

                                        section.forEach((element1, index) => {
                                                // console.log(element1);
                                                element1.classList.remove('tabContentActive');
                                        });
                                        const { target } = event.target;
                                        document.getElementById(target).classList.add('tabContentActive');
                                });
                        });
                });
        };
})(jQuery);

pureScriptTab('.directorist_builder--tab');

// Dropdown
(function($) {
        $('body').on('click', '.directorist_settings-trigger', function() {
                $('.setting-left-sibebar').toggleClass('active');
                $('.directorist_settings-panel-shade').toggleClass('active');
        });
        $('body').on('click', '.directorist_settings-panel-shade', function() {
                $('.setting-left-sibebar').removeClass('active');
                $(this).removeClass('active');
        });

        // $('body').on('click', '.directorist_dropdown .directorist_dropdown-toggle', function(){
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
})(jQuery);
