(function ($) {
    "use strict";
    // Category icon selection
    $('#category_icon').select2({
        placeholder: atbdp_admin_data.i18n_text.icon_choose_text,
        allowClear: true
    });

    /* Show and hide manual coordinate input field*/
    if( !$('input#manual_coordinate').is(':checked') ) {
        $('#hide_if_no_manual_cor').hide();
    }
    $('#manual_coordinate').on('click', function (e) {
        if( $('input#manual_coordinate').is(':checked') ) {
            $('#hide_if_no_manual_cor').show();
        }else {
            $('#hide_if_no_manual_cor').hide();
        }
    });




    // enable sorting if only the container has any social or skill field
    const $s_wrap = $("#social_info_sortable_container"); // cache it
    if( $s_wrap.length ) {
        $s_wrap.sortable(
            {
                axis: 'y',
                opacity: '0.7'
            }
        );
    }

    // SOCIAL SECTION
    // Rearrange the IDS and Add new social field
    $("#addNewSocial").on('click', function(){
        const currentItems = $('.atbdp_social_field_wrapper').length;
        const ID = "id="+currentItems; // eg. 'id=3'
        const iconBindingElement = jQuery('#addNewSocial');
        // arrange names ID in order before adding new elements
        $('.atbdp_social_field_wrapper').each(function( index , element) {
            const e = $(element);
            e.attr('id','socialID-'+index);
            e.find('select').attr('name', 'social['+index+'][id]');
            e.find('.atbdp_social_input').attr('name', 'social['+index+'][url]');
            e.find('.removeSocialField').attr('data-id',index);
        });
        // now add the new elements. we could do it here without using ajax but it would require more markup here.
        atbdp_do_ajax( iconBindingElement, 'atbdp_social_info_handler', ID, function(data){
            $s_wrap.append(data);
        });
    });


    // remove the social field and then reset the ids while maintaining position
    $(document).on('click', '.removeSocialField', function(e){
        const id = $(this).data("id"),
            elementToRemove = $('div#socialID-'+id);
        event.preventDefault();
        /* Act on the event */
        swal({
                title: atbdp_admin_data.i18n_text.confirmation_text,
                text: atbdp_admin_data.i18n_text.ask_conf_sl_lnk_del_txt,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: atbdp_admin_data.i18n_text.confirm_delete,
                closeOnConfirm: false },
            function(isConfirm) {
                if(isConfirm){
                    // user has confirmed, no remove the item and reset the ids
                    elementToRemove.slideUp( "fast", function(){
                        elementToRemove.remove();
                        // reorder the index
                        $('.atbdp_social_field_wrapper').each(function( index , element) {
                            const e = $(element);
                            e.attr('id','socialID-'+index);
                            e.find('select').attr('name', 'social['+index+'][id]');
                            e.find('.atbdp_social_input').attr('name', 'social['+index+'][url]');
                            e.find('.removeSocialField').attr('data-id',index);
                        });
                    });

                    // show success message
                    swal({
                        title: atbdp_admin_data.i18n_text.deleted,
                        //text: "Item has been deleted.",
                        type:"success",
                        timer: 200,
                        showConfirmButton: false });
                }

            });


    });


    // upgrade old listing
    $('#upgrade_directorist').on('click', function (event) {
        event.preventDefault();
        let $this = $(this);
        // display a notice to user to wait
        // send an ajax request to the back end
        atbdp_do_ajax($this, 'atbdp_upgrade_old_listings', null, function (response) {
            if (response.success){
                $this.after('<p>'+response.data+'</p>');
            }
        })

    });


    /*This function handles all ajax request*/
    function atbdp_do_ajax( ElementToShowLoadingIconAfter, ActionName, arg, CallBackHandler){
        let data;
        if(ActionName) data = "action=" + ActionName;
        if(arg)    data = arg + "&action=" + ActionName;
        if(arg && !ActionName) data = arg;
        //data = data ;

        let n = data.search(atbdp_admin_data.nonceName);
        if(n<0){
            data = data + "&"+atbdp_admin_data.nonceName+"=" + atbdp_admin_data.nonce;
        }

        jQuery.ajax({
            type: "post",
            url: atbdp_admin_data.ajaxurl,
            data: data,
            beforeSend: function() { jQuery("<span class='atbdp_ajax_loading'></span>").insertAfter(ElementToShowLoadingIconAfter); },
            success: function( data ) {
                jQuery(".atbdp_ajax_loading").remove();
                CallBackHandler(data);
            }
        });
    }


})(jQuery);




// Custom Image uploader for listing image
jQuery(function($){
    // Set all variables to be used in scope
    var frame,
        selection,
        multiple_image= false,
        metaBox = $('#_listing_gallery'), // meta box id here
        addImgLink = metaBox.find('#listing_image_btn'),
        delImgLink = metaBox.find( '#delete-custom-img'),
        imgContainer = metaBox.find( '.listing-img-container'),
        active_mi_ext = atbdp_admin_data.active_mi_ext;

    /*if the multiple image extension is active then set the multiple image parameter to true*/
    if(1 === active_mi_ext){ multiple_image = true }

    // ADD IMAGE LINK
    addImgLink.on( 'click', function( event ){

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( frame ) {
            frame.open();
            return;
        }

        // Create a new media frame
        frame = wp.media({
            title: atbdp_admin_data.i18n_text.upload_image,
            button: {
                text: atbdp_admin_data.i18n_text.choose_image
            },
            library : { type : 'image'}, // only allow image upload only
            multiple: multiple_image  // Set to true to allow multiple files to be selected. it will be set based on the availability of Multiple Image extension
        });


        // When an image is selected in the media frame...
        frame.on( 'select', function() {
            /*get the image collection array if the MI extension is active*/
            /*One little hints: a constant can not be defined inside the if block*/
            if (multiple_image){
                selection = frame.state().get( 'selection' ).toJSON();
            }else {
                selection = frame.state().get( 'selection' ).first().toJSON();
            }
            var data = ''; // create a placeholder to save all our image from the selection of media uploader

            // if no image exist then remove the place holder image before appending new image
            if ($('.single_attachment').length === 0) {
                imgContainer.html('');
            }


            //handle multiple image uploading.......
            if ( multiple_image ){
                $(selection).each(function () {
                    // here el === this
                    // append the selected element if it is an image
                    if ('image' === this.type) {
                        // we have got an image attachment so lets proceed.
                        // target the input field and then assign the current id of the attachment to an array.
                        data += '<div class="single_attachment">';
                        data += '<input class="listing_image_attachment" name="listing_img[]" type="hidden" value="'+this.id+'">';
                        data += '<img style="width: 100%; height: 100%;" src="'+this.url+'" alt="Listing Image" /> <span class="remove_image dashicons dashicons-dismiss" title="Remove it"></span></div>';
                    }

                });
            }else{
                // Handle single image uploading

                // add the id to the input field of the image uploader and then save the ids in the database as a post meta
                // so check if the attachment is really an image and reject other types
                if ('image' === selection.type){
                    // we have got an image attachment so lets proceed.
                    // target the input field and then assign the current id of the attachment to an array.
                    data += '<div class="single_attachment">';
                    data += '<input class="listing_image_attachment" name="listing_img[]" type="hidden" value="'+selection.id+'">';
                    data += '<img style="width: 100%; height: 100%;" src="' + selection.url + '" alt="Listing Image" /> <span class="remove_image  dashicons dashicons-dismiss" title="Remove it"></span></div>';
                }
            }

            // If MI extension is active then append images to the listing, else only add one image replacing previous upload
            if(multiple_image){
                imgContainer.append(data);
            }else {
                imgContainer.html(data);
            }

            // Un-hide the remove image link
            delImgLink.removeClass( 'hidden' );
        });
        // Finally, open the modal on click
        frame.open();
    });


    // DELETE ALL IMAGES LINK
    delImgLink.on( 'click', function( event ){
        event.preventDefault();
        // Clear out the preview image and set no image as placeholder
        imgContainer.html( '<img src="' + atbdp_admin_data.AdminAssetPath + 'images/no-image.jpg" alt="Listing Image" />' );
        // Hide the delete image link
        delImgLink.addClass( 'hidden' );


    });

    /*REMOVE SINGLE IMAGE*/
    $(document).on('click', '.remove_image', function (e) {
        e.preventDefault();
        $(this).parent().remove();
        // if no image exist then add placeholder and hide remove image button
        if ($('.single_attachment').length === 0) {

            imgContainer.html( '<img src="'+atbdp_admin_data.AdminAssetPath+'images/no-image.jpg" alt="Listing Image" />' );
            delImgLink.addClass( 'hidden' );

        }
    });
});


