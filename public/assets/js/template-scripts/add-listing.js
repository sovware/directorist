/* eslint-disable */
(function($) {
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
        $('#addNewSocial').on('click', function(e) {
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
                        $s_wrap.after(data);
                });
        });

        // remove the social field and then reset the ids while maintaining position
        $(document).on('click', '.removeSocialField', function(e) {
                const id = $(this).data('id');
                const elementToRemove = $(`div#socialID-${id}`);
                /* Act on the event */
                swal(
                        {
                                title: atbdp_add_listing.i18n_text.confirmation_text,
                                text: atbdp_add_listing.i18n_text.ask_conf_sl_lnk_del_txt,
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#DD6B55',
                                confirmButtonText: atbdp_add_listing.i18n_text.confirm_delete,
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
                                                title: atbdp_add_listing.i18n_text.deleted,
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

                const n = data.search(atbdp_add_listing.nonceName);
                if (n < 0) {
                        data = `${data}&${atbdp_add_listing.nonceName}=${atbdp_add_listing.nonce}`;
                }

                jQuery.ajax({
                        type: 'post',
                        url: atbdp_add_listing.ajaxurl,
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
        // Location
        $('#at_biz_dir-location').select2({
                placeholder: atbdp_add_listing.i18n_text.location_selection,
                allowClear: true,
        });

        // Tags
        const createTag = atbdp_add_listing.create_new_tag;
        if (createTag) {
                $('#at_biz_dir-tags').select2({
                        placeholder: atbdp_add_listing.i18n_text.tag_selection,
                        tags: true,
                        tokenSeparators: [','],
                });
        } else {
                $('#at_biz_dir-tags').select2({
                        placeholder: atbdp_add_listing.i18n_text.tag_selection,
                        allowClear: true,
                        tokenSeparators: [','],
                });
        }
        $('#at_biz_dir-categories').select2({
                placeholder: atbdp_add_listing.i18n_text.cat_placeholder,
                allowClear: true,
        });
})(jQuery);

// Custom Image uploader for listing image (multiple)
jQuery(function($) {
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

        // it shows the hidden term and conditions
        $('#listing_t_c').on('click', function(e) {
                e.preventDefault();
                $('#tc_container').toggleClass('active');
        });

        $(function() {
                $('#color_code2')
                        .wpColorPicker()
                        .empty();
        });

        // Load custom fields of the selected category in the custom post type "atbdp_listings"
        $('#at_biz_dir-categories').on('change', function() {
                $('#atbdp-custom-fields-list').html('<div class="spinner"></div>');
                const length = $('#at_biz_dir-categories option:selected');
                const id = [];
                length.each((el, index) => {
                        id.push($(index).val());
                });
                const data = {
                        action: 'atbdp_custom_fields_listings_front',
                        post_id: $('#atbdp-custom-fields-list').data('post_id'),
                        term_id: id,
                };
                $.post(atbdp_add_listing.ajaxurl, data, function(response) {
                        if (response == ' 0') {
                                $('#atbdp-custom-fields-list').hide();
                        } else {
                                $('#atbdp-custom-fields-list').show();
                        }
                        $('#atbdp-custom-fields-list').html(response);
                        function atbdp_tooltip(){
                                var atbd_tooltip = document.querySelectorAll('.atbd_tooltip');
                                atbd_tooltip.forEach(function(el){
                                    if(el.getAttribute('aria-label') !== " "){
                                        document.body.addEventListener('mouseover', function(e) {
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
                });

                $('#atbdp-custom-fields-list-selected').hide();
        });

        var length = $('#at_biz_dir-categories option:selected');

        if (length) {
                $('#atbdp-custom-fields-list-selected').html('<div class="spinnedsr"></div>');

                var length = $('#at_biz_dir-categories option:selected');
                const id = [];
                length.each((el, index) => {
                        id.push($(index).val());
                });
                const data = {
                        action: 'atbdp_custom_fields_listings_front_selected',
                        post_id: $('#atbdp-custom-fields-list-selected').data('post_id'),
                        term_id: id,
                };

                $.post(atbdp_add_listing.ajaxurl, data, function(response) {
                        $('#atbdp-custom-fields-list-selected').html(response);
                });
        }

        function atbdp_is_checked(name) {
                const is_checked = $(`input[name="${name}"]`).is(':checked');
                if (is_checked) {
                        return '1';
                }
                return '';
        }

        function setup_form_data( form_data, type, field ){
                 //normal input
                 if( ( type === 'hidden' ) || ( type === 'text' ) || ( type === 'number' ) || ( type === 'tel' ) || ( type === 'email' ) || ( type === 'date' ) || ( type === 'time' ) || ( type === 'url' ) ){
                        form_data.append( field.name, field.value );   
                }
                //textarea
                if( 'textarea' === type ){
                        const value = $('#'+ field.name + '_ifr').length ? tinymce.get( field.name ).getContent() : atbdp_element_value( 'textarea[name="'+ field.name +'"]' );
                        form_data.append( field.name, value );     
                }
                //checkbox, radio
                if( ( 'checkbox' === type ) || ( 'radio' === type ) ){
                        form_data.append( field.name, atbdp_element_value( 'input[name="'+ field.name +'"]:checked' ) );
                }
                //select
                if( 'select-one' === type ){
                        form_data.append( field.name, atbdp_element_value( 'select[name="'+ field.name +'"]' ) );
                }
        }

        function atbdp_element_value(element) {
                const field = $(element);
                if (field.length) {
                        return field.val();
                }
                return '';
        }

        const qs = (function(a) {
                if (a == '') return {};
                const b = {};
                for (let i = 0; i < a.length; ++i) {
                        const p = a[i].split('=', 2);
                        if (p.length == 1) b[p[0]] = '';
                        else b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, ' '));
                }
                return b;
        })(window.location.search.substr(1).split('&'));
        const uploaders = atbdp_add_listing.media_uploader;
        let mediaUploaders = [];
        if( uploaders ){
                let i = 0;
                for( var uploader of uploaders ){
                        if( $('#' + uploader['element_id'] ).length ) {
                                let media_uploader = new EzMediaUploader({
                                        containerID: uploader['element_id'],
                                });
                                mediaUploaders.push( {
                                        media_uploader: media_uploader,
                                        uploaders_data: uploader,
                                } );
                                mediaUploaders[i].media_uploader.init();
                                i++;
                        } 
                }
        }
        
        const formID = $('#add-listing-form');
        let on_processing = false;
        let has_media = true;

        $('body').on('submit', formID, function(e) {
                e.preventDefault();
                let error_count = 0;
                const err_log = {};
                // if ($('#atbdp_front_media_wrap:visible').length == 0) {
                //         has_media = false;
                // }
                if (on_processing) {
                        $('.listing_submit_btn').attr('disabled', true);
                        return;
                }

                let form_data = new FormData();
                let field_list = [];
                let field_list2 = [];
                $('.listing_submit_btn').addClass('atbd_loading');
                form_data.append('action', 'add_listing_action');
                form_data.append('directory_type', qs.listing_type);
                const fieldValuePairs = $('#add-listing-form').serializeArray();
                $.each( fieldValuePairs, function( index, fieldValuePair ) {
                        const field = document.getElementsByName( fieldValuePair.name )[0];
                        const type = field.type;
                        field_list.push( { name: field.name, });   
                        //array fields
                        if ( field.name.indexOf('[') > -1 ){
                                const field_name = field.name.substr(0, field.name.indexOf("["));
                                const ele = $( "[name^='"+ field_name +"']" );
                                // process tax input
                                if( 'tax_input' !== field_name ){
                                        if ( ele.length && ( ele.length > 1 ) ) {
                                                ele.each(function(index, value) {
                                                        const field_type = $(this).attr('type');
                                                        var name = $(this).attr('name');
                                                        if (field_type === 'radio') {
                                                                if ($(this).is(':checked')) {
                                                                        form_data.append(name, $(this).val());
                                                                }
                                                        } else if (field_type === 'checkbox') {
                                                                const new_field = $('input[name^="'+ name +'"]:checked');
                                                                if (new_field.length > 1) {
                                                                        new_field.each(function() {
                                                                                const name = $(this).attr('name');
                                                                                const value = $(this).val();
                                                                                form_data.append(name, value);
                                                                        });
                                                                } else {
                                                                        var name = new_field.attr('name');
                                                                        var value = new_field.val();
                                                                        form_data.append(name, value);
                                                                }
                                                        } else {
                                                                var name = $(this).attr('name');
                                                                var value = $(this).val();
                                                                if (!value) {
                                                                        value = $(this).attr('data-time');
                                                                }
                                                                form_data.append(name, value);
                                                        }
                                                });
                                        } else {
                                                const name = ele.attr('name');
                                                const value = ele.val();
                                               
                                                form_data.append( name, value );
                                        } 
                                }       
                        }else{
                                //  field_list2.push({ nam: name, val: value, field: field, type: type})
                                setup_form_data( form_data, type, field );
                        }        
                });

                // console.log( field_list2 );
                // return;
                // images
                
                if( mediaUploaders.length ){
                        for ( var uploader of mediaUploaders ) {
                        if (uploader.media_uploader && has_media) {
                                var hasValidFiles = uploader.media_uploader.hasValidFiles();
                                if (hasValidFiles) {
                                        // files
                                        var files = uploader.media_uploader.getTheFiles();
                                        if (files) {
                                                for (var i = 0; i < files.length; i++) {
                                                        form_data.append( uploader.uploaders_data['meta_name']+'[]', files[i]);
                                                }
                                        }
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
                                        $('.listing_submit_btn').removeClass('atbd_loading');
                                        err_log.listing_gallery = { msg: uploader.uploaders_data['error_msg'] };
                                        error_count++;
                                        scrollToEl('#'+ uploader.uploaders_data['element_id']);
                                }
                         }
                        }
                }

                // locations
                const locaitons = $('#at_biz_dir-location').val();
                if (Array.isArray(locaitons) && locaitons.length) {
                        for (var key in locaitons) {
                                var value = locaitons[key];
                                form_data.append('tax_input[at_biz_dir-location][]', value);
                        }
                }

                if (typeof locaitons === 'string') {
                        form_data.append('tax_input[at_biz_dir-location][]', locaitons);
                }

                // tags
                const tags = $('#at_biz_dir-tags').val();
                if (tags) {
                        for (var key in tags) {
                                var value = tags[key];
                                form_data.append('tax_input[at_biz_dir-tags][]', value);
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

                if (error_count) {
                        on_processing = false;
                        $('.listing_submit_btn').attr('disabled', false);
                        console.log('Form has invalid data');
                        console.log(error_count, err_log);
                        return;
                }

                on_processing = true;
                $('.listing_submit_btn').attr('disabled', true);

                $.ajax({
                        method: 'POST',
                        processData: false,
                        contentType: false,
                        url: atbdp_add_listing.ajaxurl,
                        data: form_data,
                        success(response) {
                                // console.log( response );
                                // return;
                                // show the error notice
                                var is_pending = response.pending ? '&' : '?';
                                if (response.error === true) {
                                        if( response.error_msg.length > 1 ){
                                                $('#listing_notifier').show();
                                                for( var error in response.error_msg ){
                                                       // console.log( error );
                                                        $('#listing_notifier').append(`<span>${ response.error_msg[error] }</span>`);
                                                }
                                                $('.listing_submit_btn').removeClass('atbd_loading');
                                        }else{
                                                $('#listing_notifier')
                                                .show()
                                                .html(`<span>${ response.error_msg }</span>`);
                                                $('.listing_submit_btn').removeClass('atbd_loading');
                                        }
                                        
                                } else {
                                        // preview on and no need to redirect to payment
                                        if (response.preview_mode === true && response.need_payment !== true) {
                                                if (response.edited_listing !== true) {
                                                        $('#listing_notifier')
                                                                .show()
                                                                .html(`<span>${response.success_msg}</span>`);
                                                        window.location.href = `${
                                                                response.preview_url
                                                        }?preview=1&redirect=${response.redirect_url}`;
                                                } else {
                                                        $('#listing_notifier')
                                                                .show()
                                                                .html(`<span>${response.success_msg}</span>`);
                                                        if (qs.redirect) {
                                                                var is_pending = '?';
                                                                window.location.href = `${response.preview_url +
                                                                        is_pending}post_id=${
                                                                        response.id
                                                                }&preview=1&payment=1&edited=1&redirect=${qs.redirect}`;
                                                        } else {
                                                                window.location.href = `${
                                                                        response.preview_url
                                                                }?preview=1&edited=1&redirect=${response.redirect_url}`;
                                                        }
                                                }
                                                // preview mode active and need payment
                                        } else if (response.preview_mode === true && response.need_payment === true) {
                                                window.location.href = `${
                                                        response.preview_url
                                                }?preview=1&payment=1&redirect=${response.redirect_url}`;
                                        } else {
                                                const is_edited = response.edited_listing
                                                        ? `${is_pending}listing_id=${response.id}&edited=1`
                                                        : '';
                                                if (response.need_payment === true) {
                                                        $('#listing_notifier')
                                                                .show()
                                                                .html(`<span>${response.success_msg}</span>`);
                                                        window.location.href = response.redirect_url;
                                                } else {
                                                        $('#listing_notifier')
                                                                .show()
                                                                .html(`<span>${response.success_msg}</span>`);
                                                        window.location.href = response.redirect_url + is_edited;
                                                }
                                        }
                                }
                        },
                        error(error) {
                                on_processing = false;
                                $('.listing_submit_btn').attr('disabled', false);

                                $('.listing_submit_btn').removeClass('atbd_loading');
                                console.log(error);
                        },
                });
        });

        // scrollToEl
        function scrollToEl(el) {
                // const element = typeof el === 'string' ? el : '';
                // let scroll_top = $(element).offset().top - 50;
                // scroll_top = scroll_top < 0 ? 0 : scroll_top;

                // $('html, body').animate(
                //         {
                //                 scrollTop: scroll_top,
                //         },
                //         800
                // );
        }
});