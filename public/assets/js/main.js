/*
    File: Main.js
    Plugin: Directorist - Business Directory Plugin
    Author: Aazztech
    Author URI: www.aazztech.com
*/
/* eslint-disable */
;(function ($) {
    $('.atbdp_sorting_item').click( function() {
        var href = $(this).attr('data');
        $('#atbdp_sort').attr('action', href);
        $('#atbdp_sort').submit();
    });
    //sorting toggle
    $('.sorting span').on('click', function () {
        $(this).toggleClass('fa-sort-amount-asc fa-sort-amount-desc');
    });

    /* Externel Library init
     ------------------------*/
    //Star rating
    if ($('.stars').length) {
        $(".stars").barrating({
            theme: 'fontawesome-stars'
        });
    }

    function handleFiles(files) {
        var preview = document.getElementById('atbd_up_preview');
        for (var i = 0; i < files.length; i++) {
            var file = files[i];

            if (!file.type.startsWith('image/')) {
                continue
            }

            var img = document.createElement("img");
            img.classList.add("atbd_review_thumb");

            var imgWrap = document.createElement('div');
            imgWrap.classList.add('atbd_up_prev');

            preview.appendChild(imgWrap); // Assuming that "preview" is the div output where the content will be displayed.
            imgWrap.appendChild(img);
            $(imgWrap).append('<span class="rmrf">x</span>');


            var reader = new FileReader();
            reader.onload = (function (aImg) {
                return function (e) {
                    aImg.src = e.target.result;
                };
            })(img);
            reader.readAsDataURL(file);
        }
    }

    $('#atbd_review_attachment').on('change', function (e) {
        handleFiles(this.files);
    });

    // 	prepear_form_data
  function prepear_form_data ( form, field_map, data ) {
    if ( ! data || typeof data !== 'object' ) {
      var data = {};
    }

    for ( var key in field_map) {
      var field_item = field_map[ key ];
      var field_key = field_item.field_key;
      var field_type = field_item.type;

      if ( 'name' === field_type ) {
        var field = form.find( '[name="'+ field_key +'"]' );
      } else {
        var field = form.find( field_key );
      }

      if ( field.length ) {
        var data_key = ( 'name' === field_type ) ? field_key : field.attr('name') ;
        var data_value = ( field.val() ) ? field.val() : '';

        data[data_key] = data_value;
      }
    }

    return data;
  }

    /* Add review to the database using ajax*/
    var submit_count = 1;
    $("#atbdp_review_form").on("submit", function () {
        if (submit_count > 1) {
            // show error message
            swal({
                title: atbdp_public_data.warning,
                text: atbdp_public_data.not_add_more_than_one,
                type: "warning",
                timer: 2000,
                showConfirmButton: false
            });
            return false; // if user try to submit the form more than once on a page load then return false and get out
        }
        var $form = $(this);
        var $data = $form.serialize();

        var field_field_map = [
      { type: 'name', field_key: 'post_id' },
      { type: 'id', field_key: '#atbdp_review_nonce_form' },
      { type: 'id', field_key: '#guest_user_email' },
      { type: 'id', field_key: '#reviewer_name' },
      { type: 'id', field_key: '#review_content' },
      { type: 'id', field_key: '#review_rating' },
      { type: 'id', field_key: '#review_duplicate' },
    ];

    var _data = { action: 'save_listing_review' };
    _data = prepear_form_data( $form, field_field_map, _data );

        // atbdp_do_ajax($form, 'save_listing_review', _data, function (response) {

        jQuery.post(atbdp_public_data.ajaxurl, _data, function(response) {
            var output = '';
            var deleteBtn = '';
            var d;
            var name = $form.find("#reviewer_name").val();
            var content = $form.find("#review_content").val();
            var rating = $form.find("#review_rating").val();
            var ava_img = $form.find("#reviewer_img").val();
            var approve_immediately = $form.find("#approve_immediately").val();
            var review_duplicate = $form.find("#review_duplicate").val();
            if (approve_immediately === 'no') {
                if(content === '') {
                    // show error message
                    swal({
                        title: "ERROR!!",
                        text: atbdp_public_data.review_error,
                        type: "error",
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    if (submit_count === 1) {
                        $('#client_review_list').prepend(output); // add the review if it's the first review of the user
                        $('.atbdp_static').remove();
                    }
                    submit_count++;
                    if (review_duplicate === 'yes') {
                        swal({
                            title: atbdp_public_data.warning,
                            text: atbdp_public_data.duplicate_review_error,
                            type: "warning",
                            timer: 3000,
                            showConfirmButton: false
                        });
                    } else {
                        swal({
                            title: atbdp_public_data.success,
                            text: atbdp_public_data.review_approval_text,
                            type: "success",
                            timer: 4000,
                            showConfirmButton: false
                        });
                    }
                }


            } else if (response.success) {
                output +=
                    '<div class="atbd_single_review" id="single_review_' + response.data.id + '">' +
                    '<input type="hidden" value="1" id="has_ajax">' +
                    '<div class="atbd_review_top"> ' +
                    '<div class="atbd_avatar_wrapper"> ' +
                    '<div class="atbd_review_avatar">' + ava_img + '</div> ' +
                    '<div class="atbd_name_time"> ' +
                    '<p>' + name + '</p>' +
                    '<span class="review_time">' + response.data.date + '</span> ' + '</div> ' + '</div> ' +
                    '<div class="atbd_rated_stars">' + print_static_rating(rating) + '</div> ' +
                    '</div> ';
                if( atbdp_public_data.enable_reviewer_content ) {
                output +=
                    '<div class="review_content"> ' +
                    '<p>' + content + '</p> ' +
                    //'<a href="#"><span class="fa fa-mail-reply-all"></span>Reply</a> ' +
                    '</div> ';
                }
                output +=
                    '</div>';

                // output += '<div class="single_review"  id="single_review_'+response.data.id+'">' +
                //     '<div class="review_top">' +
                //     '<div class="reviewer"><i class="fa fa-user" aria-hidden="true"></i><p>'+name+'</p></div>' +
                //     '<span class="review_time">'+d+'</span>' +
                //     '<div class="br-theme-css-stars-static">' + print_static_rating(rating)+'</div>' +
                //     '</div>' +
                //     '<div class="review_content">' +
                //     '<p> '+ content+ '</p>' +
                //     '</div>' +
                //     '</div>';

                // as we have saved a review lets add a delete button so that user cann delete the review he has just added.
                deleteBtn += '<button class="directory_btn btn btn-danger" type="button" id="atbdp_review_remove" data-review_id="' + response.data.id + '">Remove</button>';
                $form.append(deleteBtn);
                if (submit_count === 1) {
                    $('#client_review_list').prepend(output); // add the review if it's the first review of the user
                    $('.atbdp_static').remove();
                }
                var sectionToShow = $("#has_ajax").val();
                var sectionToHide = $(".atbdp_static");
                var sectionToHide2 = $(".directory_btn");
                if (sectionToShow) {
                    // $(sectionToHide).hide();
                    $(sectionToHide2).hide();
                }
                submit_count++;
                // show success message
                swal({
                    title: atbdp_public_data.review_success,
                    type: "success",
                    timer: 800,
                    showConfirmButton: false
                });

                //reset the form
                $form[0].reset();
                // remove the notice if there was any
               var $r_notice = $('#review_notice');
                if ($r_notice) {
                    $r_notice.remove();
                }
            } else {
                // show error message
                swal({
                    title: "ERROR!!",
                    text: atbdp_public_data.review_error,
                    type: "error",
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });

        return false;
    });


    function atbdp_load_all_posts(page) {
        // Start the transition
        //$(".atbdp_pag_loading").fadeIn().css('background','#ccc');
        var listing_id = $('#review_post_id').attr('data-post-id');
        // Data to receive from our server
        // the value in 'action' is the key that will be identified by the 'wp_ajax_' hook
        var data = {
            page: page,
            listing_id: listing_id,
            action: "atbdp_review_pagination"
        };

        // Send the data
        $.post(atbdp_public_data.ajaxurl, data, function (response) {
            // If successful Append the data into our html container
            $('#client_review_list').empty().append(response);
            // End the transition
            //$(".atbdp_pag_loading").css({'background':'none', 'transition':'all 1s ease-out'});
        });
    }

    // Load page 1 as the default
    if ($('#client_review_list').length) {
        atbdp_load_all_posts(1);
    }

    // Handle the clicks
    $('body').on('click', '.atbdp-universal-pagination li.atbd-active', function () {
        var page = $(this).attr('data-page');
        atbdp_load_all_posts(page);

    });

    var delete_count = 1;
    // remove the review of a user
    $(document).on('click', '#atbdp_review_remove', function (e) {
        e.preventDefault();
        if (delete_count > 1) {
            // show error message
            swal({
                title: "WARNING!!",
                text: atbdp_public_data.review_have_not_for_delete,
                type: "warning",
                timer: 2000,
                showConfirmButton: false
            });
            return false; // if user try to submit the form more than once on a page load then return false and get out
        }
        var $this = $(this);
        var id = $this.data('review_id');
        var data = 'review_id=' + id;

        swal({
            title: atbdp_public_data.review_sure_msg,
            text: atbdp_public_data.review_want_to_remove,
            type: "warning",
            cancelButtonText: atbdp_public_data.review_cancel_btn_text,
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: atbdp_public_data.review_delete_msg,
            showLoaderOnConfirm: true,
            closeOnConfirm: false
        },
            function (isConfirm) {
                if (isConfirm) {
                    // user has confirmed, now remove the review
                    atbdp_do_ajax($this, 'remove_listing_review', data, function (response) {
                        if ('success' === response) {
                            // show success message
                            swal({
                                title: "Deleted!!",
                                type: "success",
                                timer: 200,
                                showConfirmButton: false
                            });
                            $("#single_review_" + id).slideUp();
                            $this.remove();
                            $('#review_content').empty();
                            $("#atbdp_review_form_submit").remove();
                            $(".atbd_review_rating_area").remove();
                            $("#reviewCounter").hide();
                            delete_count++; // increase the delete counter so that we do not need to delete the review more than once.
                        } else {
                            // show error message
                            swal({
                                title: "ERROR!!",
                                text: atbdp_public_data.review_wrong_msg,
                                type: "error",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });

        // send an ajax request to the ajax-handler.php and then delete the review of the given id

    });

    /*USER DASHBOARD RELATED SCRIPTS*/
    $(document).on('click', '#remove_listing', function (e) {
        e.preventDefault();

        var $this = $(this);
        var id = $this.data('listing_id');
        var data = 'listing_id=' + id;
        swal({
            title: atbdp_public_data.listing_remove_title,
            text: atbdp_public_data.listing_remove_text,
            type: "warning",
            cancelButtonText: atbdp_public_data.review_cancel_btn_text,
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: atbdp_public_data.listing_remove_confirm_text,
            showLoaderOnConfirm: true,
            closeOnConfirm: false
        },
            function (isConfirm) {
                if (isConfirm) {
                    // user has confirmed, now remove the listing
                    atbdp_do_ajax($this, 'remove_listing', data, function (response) {
                        $('body').append(response);
                        if ('success' === response) {
                            // show success message
                            swal({
                                title: atbdp_public_data.listing_delete,
                                type: "success",
                                timer: 200,
                                showConfirmButton: false
                            });
                            $("#listing_id_" + id).remove();
                            $this.remove();
                        } else {
                            // show error message
                            swal({
                                title: atbdp_public_data.listing_error_title,
                                text: atbdp_public_data.listing_error_text,
                                type: "error",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });


                }

            });

        // send an ajax request to the ajax-handler.php and then delete the review of the given id

    });


    // user dashboard image uploader
    var profileMediaUploader = null;
    if ($("#user_profile_pic").length) {
        profileMediaUploader = new EzMediaUploader({
            containerID: "user_profile_pic",
        });
        profileMediaUploader.init();
    }


    var is_processing = false;
    $('#user_profile_form').on('submit', function (e) {
        // submit the form to the ajax handler and then send a response from the database and then work accordingly and then after finishing the update profile then work on remove listing and also remove the review and rating form the custom table once the listing is deleted successfully.
        e.preventDefault();

        var submit_button = $('#update_user_profile');
        submit_button.attr('disabled', true);
        submit_button.addClass("loading");

        if (is_processing) { submit_button.removeAttr('disabled'); return; }

        var form_data = new FormData();
        var err_log = {};
        var error_count;

         // ajax action
         form_data.append('action', 'update_user_profile');
        if ( profileMediaUploader ) {
            var hasValidFiles = profileMediaUploader.hasValidFiles();
            if (hasValidFiles) {
                //files
                var files = profileMediaUploader.getTheFiles();
                var filesMeta = profileMediaUploader.getFilesMeta();

                if (files.length) {
                    for (var i = 0; i < files.length; i++) {
                        form_data.append('profile_picture', files[i]);
                    }
                }

                if (filesMeta.length) {
                    for (var i = 0; i < filesMeta.length; i++) {
                        var elm = filesMeta[i];
                        for (var key in elm) {
                            form_data.append('profile_picture_meta[' + i + '][' + key + ']', elm[key]);
                        }
                    }
                }

            } else {
                $(".listing_submit_btn").removeClass("atbd_loading");
                err_log.user_profile_avater = { msg: 'Listing gallery has invalid files' };
                error_count++;
            }
        }
        var $form = $(this);
        var arrData = $form.serializeArray();
        $.each(arrData, function (index, elem) {
            var name = elem.name;
            var value = elem.value;
            form_data.append(name, value);
        });
        $.ajax({
            method: 'POST',
            processData: false,
            contentType: false,
            url: atbdp_public_data.ajaxurl,
            data: form_data,
            success: function (response) {
                submit_button.removeAttr('disabled');
                submit_button.removeClass("loading");
                if (response.success) {
                    $('#pro_notice').html('<p style="padding: 22px;" class="alert-success">' + response.data + '</p>');
                } else {
                    $('#pro_notice').html('<p style="padding: 22px;" class="alert-danger">' + response.data + '</p>');
                }
            },
            error: function (response) {
                submit_button.removeAttr('disabled');
                console.log(response);
            }
        });


        // atbdp_do_ajax($form, 'update_user_profile', form_data, function (response) {
        //     console.log(response);
        //     return;
        //     if (response.success) {
        //         $('#pro_notice').html('<p style="padding: 22px;" class="alert-success">' + response.data + '</p>');
        //     } else {
        //         $('#pro_notice').html('<p style="padding: 22px;" class="alert-danger">' + response.data + '</p>');
        //     }
        // });

        // prevent the from submitting
        return false;
    });

    /*HELPERS*/
    function print_static_rating($star_number) {
        var v;
        if ($star_number) {
            v = '<ul>';
            for (var i = 1; i <= 5; i++) {
                v += (i <= $star_number)
                    ? "<li><span class='rate_active'></span></li>"
                    : "<li><span class='rate_disable'></span></li>";
            }
            v += '</ul>';
        }

        return v;
    }

    // helper function to convert the mysql date
    Date.createFromMysql = function (mysql_string) {
        var t, result = null;

        if (typeof mysql_string === 'string') {
            t = mysql_string.split(/[- :]/);

            //when t[3], t[4] and t[5] are missing they defaults to zero
            result = new Date(t[0], t[1] - 1, t[2], t[3] || 0, t[4] || 0, t[5] || 0);
        }

        return result;
    };


    /*This function handles all ajax request*/
    function atbdp_do_ajax(ElementToShowLoadingIconAfter, ActionName, arg, CallBackHandler) {
        var data;
        if (ActionName) data = "action=" + ActionName;
        if (arg) data = arg + "&action=" + ActionName;
        if (arg && !ActionName) data = arg;
        //data = data ;

        var n = data.search(atbdp_public_data.nonceName);
        if (n < 0) {
            data = data + "&" + atbdp_public_data.nonceName + "=" + atbdp_public_data.nonce;
        }

        jQuery.ajax({
            type: "post",
            url: atbdp_public_data.ajaxurl,
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

    /* Responsive grid control */
    $(document).ready(function () {
        var d_wrapper = $("#directorist.atbd_wrapper");
        var columnLeft = $(".atbd_col_left.col-lg-8");
        var columnRight = $(".directorist.col-lg-4");
        var tabColumn = $(".atbd_dashboard_wrapper .tab-content .tab-pane .col-lg-4");
        var w_size = d_wrapper.width();
        if (w_size >= 500 && w_size <= 735) {
            columnLeft.toggleClass("col-lg-8");
            columnRight.toggleClass("col-lg-4");
        }
        if (w_size <= 600) {
            d_wrapper.addClass("size-xs");
            tabColumn.toggleClass("col-lg-4");
        }

        var listing_size = $(".atbd_dashboard_wrapper .atbd_single_listing").width();
        if (listing_size < 200) {
            $(".atbd_single_listing .db_btn_area").addClass("db_btn_area--sm");
        }


    });

    /*
        Plugin: PureScriptTab
        Version: 1.0.0
        License: MIT
    */
    (function () {
        pureScriptTab = (selector1) => {
            var selector = document.querySelectorAll(selector1);
            selector.forEach((el, index) => {
                a = el.querySelectorAll('.atbd_tn_link');


                a.forEach((element, index) => {

                    element.style.cursor = 'pointer';
                    element.addEventListener('click', (event) => {
                        event.preventDefault();
                        event.stopPropagation();

                        var ul = event.target.closest('.atbd_tab_nav'),
                            main = ul.nextElementSibling,
                            item_a = ul.querySelectorAll('.atbd_tn_link'),
                            section = main.querySelectorAll('.atbd_tab_inner');

                        item_a.forEach((ela, ind) => {
                            ela.classList.remove('tabItemActive');
                        });
                        event.target.classList.add('tabItemActive');


                        section.forEach((element1, index) => {
                            //console.log(element1);
                            element1.classList.remove('tabContentActive');
                        });
                        var target = event.target.target;
                        document.getElementById(target).classList.add('tabContentActive');
                    });
                });
            });
        };

        pureScriptTabChild = (selector1) => {
            var selector = document.querySelectorAll(selector1);
            selector.forEach((el, index) => {
                a = el.querySelectorAll('.pst_tn_link');


                a.forEach((element, index) => {

                    element.style.cursor = 'pointer';
                    element.addEventListener('click', (event) => {
                        event.preventDefault();
                        event.stopPropagation();

                        var ul = event.target.closest('.pst_tab_nav'),
                            main = ul.nextElementSibling,
                            item_a = ul.querySelectorAll('.pst_tn_link'),
                            section = main.querySelectorAll('.pst_tab_inner');

                        item_a.forEach((ela, ind) => {
                            ela.classList.remove('pstItemActive');
                        });
                        event.target.classList.add('pstItemActive');


                        section.forEach((element1, index) => {
                            //console.log(element1);
                            element1.classList.remove('pstContentActive');
                        });
                        var target = event.target.target;
                        document.getElementById(target).classList.add('pstContentActive');
                    });
                });
            });
        };

        pureScriptTabChild2 = (selector1) => {
            var selector = document.querySelectorAll(selector1);
            selector.forEach((el, index) => {
                a = el.querySelectorAll('.pst_tn_link-2');


                a.forEach((element, index) => {

                    element.style.cursor = 'pointer';
                    element.addEventListener('click', (event) => {
                        event.preventDefault();
                        event.stopPropagation();

                        var ul = event.target.closest('.pst_tab_nav-2'),
                            main = ul.nextElementSibling,
                            item_a = ul.querySelectorAll('.pst_tn_link-2'),
                            section = main.querySelectorAll('.pst_tab_inner-2');

                        item_a.forEach((ela, ind) => {
                            ela.classList.remove('pstItemActive2');
                        });
                        event.target.classList.add('pstItemActive2');


                        section.forEach((element1, index) => {
                            //console.log(element1);
                            element1.classList.remove('pstContentActive2');
                        });
                        var target = event.target.target;
                        document.getElementById(target).classList.add('pstContentActive2');
                    });
                });
            });
        };

    })();
    pureScriptTab('.atbd_tab');
    pureScriptTab('.directorist_userDashboard-tab');
    pureScriptTabChild('.atbdp-bookings-tab');
    pureScriptTabChild2('.atbdp-bookings-tab-inner');


    // Validate forms
    if ($.fn.validator) {

        // Validate report abuse form
        var atbdp_report_abuse_submitted = false;

        $('#atbdp-report-abuse-form').validator({
            disable: false
        }).on('submit', function (e) {

            if (atbdp_report_abuse_submitted) return false;
            atbdp_report_abuse_submitted = true;
            // Check for errors
            if (!e.isDefaultPrevented()) {
                e.preventDefault();

                // Post via AJAX
                var data = {
                    'action': 'atbdp_public_report_abuse',
                    'post_id': $('#atbdp-post-id').val(),
                    'message': $('#atbdp-report-abuse-message').val()
                };

                $.post(atbdp_public_data.ajaxurl, data, function (response) {
                    if (1 == response.error) {
                        $('#atbdp-report-abuse-message-display').addClass('text-danger').html(response.message);
                    } else {
                        $('#atbdp-report-abuse-message').val('');
                        $('#atbdp-report-abuse-message-display').addClass('text-success').html(response.message);
                    }


                    atbdp_report_abuse_submitted = false;  // Re-enable the submit event
                }, 'json');

            }
        });

        $('#atbdp-report-abuse-form').removeAttr('novalidate');

        // Validate contact form
        $('.contact_listing_owner_form').on('submit', function (e) {
            e.preventDefault();

            var submit_button = $(this).find('button[type="submit"]');
            var status_area = $(this).find('.atbdp-contact-message-display');

            // Show loading message
            var msg = '<div class="atbdp-alert"><i class="fas fa-circle-notch fa-spin"></i> ' + atbdp_public_data.waiting_msg + ' </div>';
            status_area.html(msg);

            var name = $(this).find('input[name="atbdp-contact-name"]');
            var contact_email = $(this).find('input[name="atbdp-contact-email"]');
            var message = $(this).find('textarea[name="atbdp-contact-message"]');
            var post_id = $(this).find('input[name="atbdp-post-id"]');
            var listing_email = $(this).find('input[name="atbdp-listing-email"]');

            // Post via AJAX
            var data = {
                'action': 'atbdp_public_send_contact_email',
                'post_id': post_id.val(),
                'name': name.val(),
                'email': contact_email.val(),
                'listing_email': listing_email.val(),
                'message': message.val(),
            };

            submit_button.prop('disabled', true);
            $.post(atbdp_public_data.ajaxurl, data, function (response) {
                submit_button.prop('disabled', false);

                if ( 1 == response.error ) {
                    atbdp_contact_submitted = false;

                    // Show error message
                    var msg = '<div class="atbdp-alert alert-danger-light"><i class="fas fa-exclamation-triangle"></i> ' + response.message + '</div>';
                    status_area.html(msg);

                } else {
                    name.val('');
                    message.val('');
                    contact_email.val('');

                    // Show success message
                    var msg = '<div class="atbdp-alert alert-success-light"><i class="fas fa-check-circle"></i> ' + response.message + '</div>';
                    status_area.html(msg);
                }

                setTimeout(function () {
                    status_area.html('');
                }, 5000);

            }, 'json');

        });

        $('#atbdp-contact-form,#atbdp-contact-form-widget').removeAttr('novalidate');
    }

    // Report abuse [on modal closed]
    $('#atbdp-report-abuse-modal').on('hidden.bs.modal', function (e) {

        $('#atbdp-report-abuse-message').val('');
        $('#atbdp-report-abuse-message-display').html('');

    });

    // Contact form [on modal closed]
    $('#atbdp-contact-modal').on('hidden.bs.modal', function (e) {

        $('#atbdp-contact-message').val('');
        $('#atbdp-contact-message-display').html('');

    });


    // Alert users to login (only if applicable)
    $('.atbdp-require-login').on('click', function (e) {
        console.log("res")
        e.preventDefault();
        alert(atbdp_public_data.login_alert_message);

        return false;

    });

    // Add or Remove from favourites
    $('#atbdp-favourites').on('click', function (e) {

        //e.preventDefault();
        var data = {
            'action': 'atbdp_public_add_remove_favorites',
            'post_id': $("a.atbdp-favourites").data('post_id')
        };
        $.post(atbdp_public_data.ajaxurl, data, function (response) {
            $('#atbdp-favourites').html(response);
        });

    });

    /*$('#atbdp_tabs a').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
    });*/

    $("#recover-pass-modal").hide();
    $(".atbdp_recovery_pass").on("click", function (e) {
        e.preventDefault();
        $("#recover-pass-modal").slideToggle().show();
    });

    /* atbd tooltip */
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

    // User Dashboard Table More Button
    $('.directorist-dashboard-listings-tbody').on("click", '.directorist_btn-more', function(e){
        e.preventDefault();
        $(this).toggleClass('active');
        $(".directorist_dropdown-menu").removeClass("active");
        // $(this).siblings(".directorist_dropdown-menu").removeClass("active");
        $(this).next(".directorist_dropdown-menu").toggleClass("active");
        e.stopPropagation();
    });

    $(document).bind("click", function (e) {
        // console.log($(e.target).parents().hasClass('directorist_dropdown-menu__list'))
        if(!$(e.target).parents().hasClass('directorist_dropdown-menu__list')){
            $(".directorist_dropdown-menu").removeClass("active");
            $(".directorist_btn-more").removeClass("active");
        }
    });

    /* User Dashboard tab */
    $(function () {
        var hash = window.location.hash;
        var selectedTab = $('.navbar .menu li a [target= "' + hash + '"]');
    });


    // store the currently selected tab in the hash value
    $("ul.atbd-dashboard-nav > li > a.atbd_tn_link").on("click", function (e) {
        var id = $(e.target).attr("target").substr();
        window.location.hash = "#active_" + id;
        e.stopPropagation();
    });

    /* $(window).on("load", function () {
        UIkit.grid(".data-uk-masonry");
    }); */

    /* $(".atbdp_tab_nav_wrapper > ul > li:first-child > a").one("click", function () {
        (function () {
            if (window.localStorage) {
                if (!localStorage.getItem('firstLoad')) {
                    localStorage['firstLoad'] = true;
                    window.location.reload();
                } else
                    localStorage.removeItem('firstLoad');
            }
        })();
    }); */

    // Perform AJAX login on form submit
    $('form#login').on('submit', function (e) {
        e.preventDefault();
        $('p.status').show().html(ajax_login_object.loading_message);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajax_url,
            data: {
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#login p #username').val(),
                'password': $('form#login p #password').val(),
                'rememberme': ($('form#login #keep_signed_in').is(':checked')) ? 1 : 0,
                'security': $('#security').val()
            },
            success: function (data) {
                if ('nonce_faild' in data && data.nonce_faild) {
                    $('p.status').html('<span class="status-success">' + data.message + '</span>');
                }
                if (data.loggedin == true) {
                    $('p.status').html('<span class="status-success">' + data.message + '</span>');
                    document.location.href = ajax_login_object.redirect_url;
                } else {
                    $('p.status').html('<span class="status-failed">' + data.message + '</span>');
                }
            },
            error: function (data) {
                if ('nonce_faild' in data && data.nonce_faild) {
                    $('p.status').html('<span class="status-success">' + data.message + '</span>');
                }
                $('p.status').show().html('<span class="status-failed">' + ajax_login_object.login_error_message + '</span>');
            }
        });
        e.preventDefault();
    });

    var single_listing_slider = new PlasmaSlider({
        containerID: "single-listing-slider",
    });
    single_listing_slider.init();

    //adding temporary css class to post draft page
    if ($(".edit_btn_wrap .atbdp_float_active").length) {
        $("body").addClass("atbd_post_draft");
    }

    //atbd_dropdown
    $(".atbd_dropdown").on("click", function (e) {
        if ($(this).attr("class") === "atbd_dropdown") {
            e.preventDefault();
            $(this).siblings(".atbd_dropdown").removeClass("atbd_drop--active");
            $(this).toggleClass("atbd_drop--active");
            e.stopPropagation();
        }
    });
    $(document).on("click", function (e) {
        if ($(e.target).is(".atbd_dropdown, .atbd_drop--active") === false) {
            $(".atbd_dropdown").removeClass("atbd_drop--active");
        }
    });
    $(".atbd_dropdown-toggle").on("click", function (e) {
        e.preventDefault();
    });

    //
    $('.atbd_listing_no_image .atbd_lower_badge').each(function(i, elm){
        if( !$.trim( $(elm).html() ).length ) {
            $(this).addClass('atbd-no-spacing');
        }
    });

    //dashboard sidebar nav toggler
    $(".atbd-dashboard-nav-toggler").on("click", function(e){
        e.preventDefault();
        $(".atbd_user_dashboard_nav").toggleClass("atbd-dashboard-nav-collapsed");
    });
    if($(window).innerWidth() < 767){
      $(".atbd_user_dashboard_nav").addClass("atbd-dashboard-nav-collapsed");
      $(".atbd_user_dashboard_nav").addClass("atbd-dashboard-nav-collapsed--fixed");
      $("body").on("click", function(e){
            if($(e.target).is(".atbd_user_dashboard_nav, .atbdp_all_booking_nav-link, .atbd-dashboard-nav-toggler, .atbd-dashboard-nav-toggler i, .atbdp_tab_nav--content-link") === false) {
                $(".atbd_user_dashboard_nav").addClass("atbd-dashboard-nav-collapsed");
            }
        });
    }

    //dashboard nav dropdown
    $(".atbdp_tab_nav--has-child .atbd-dash-nav-dropdown").on("click", function(e){
      e.preventDefault();
      $(this).siblings("ul").slideToggle();
    });

     // Modal
    $( '.atbdp-toggle-modal' ).on( 'click', function( e ) {
        e.preventDefault();

        var data_target = $( this ).data( 'target' );

        $( data_target ).toggleClass( 'show' );
    });

    // Announcement
    // --------------------------------------------
    // Cleare seen announcements
    var cleared_seen_announcements = false;
    $( '.atbd_tn_link' ).on( 'click', function() {
        if ( cleared_seen_announcements ) { return; }
        var terget = $( this ).attr( 'target' );

        if ( 'announcement' === terget ) {
            // console.log( terget, 'clear seen announcements' );

            $.ajax({
                type: "post",
                url: atbdp_public_data.ajaxurl,
                data: { action: 'atbdp_clear_seen_announcements' },
                success: function( response ) {
                    // console.log( response );

                    if ( response.success ) {
                        cleared_seen_announcements = true;
                        $( '.new-announcement-count' ).removeClass( 'show' );
                        $( '.new-announcement-count' ).html( '' );
                    }
                },
                error: function( error ) {
                    console.log( { error } );
                },
            })
        }
    });

    // Closing the announcement
    var closing_announcement = false;
    $('.close-announcement').on('click', function ( e ) {
        e.preventDefault();

        if ( closing_announcement ) { console.log( 'Please wait...' ); return; }

        var post_id = $( this ).closest('.atbdp-announcement').data( 'post-id' );
        var form_data = {
            action: 'atbdp_close_announcement',
            post_id: post_id,
        }

        var button_default_html = $( self ).html();
        closing_announcement = true;
        var self = this;

        $.ajax({
            type: "post",
            url: atbdp_public_data.ajaxurl,
            data: form_data,
            beforeSend() {
                $( self ).html( '<span class="fas fa-spinner fa-spin"></span> ' );
                $( self ).addClass( 'disable' );
                $( self ).attr( 'disable', true );
            },
            success: function( response ) {
                // console.log( { response } );
                closing_announcement = false;

                $( self ).removeClass( 'disable' );
                $( self ).attr( 'disable', false );

                if ( response.success ) {
                    $( '.announcement-id-' + post_id ).remove();

                    if ( ! $( '.announcement-item' ).length ) {
                        location.reload();
                    }
                } else {
                    $( self ).html( 'Close' );
                }
            },
            error: function( error ) {
                console.log( { error } );

                $( self ).html( button_default_html );
                $( self ).removeClass( 'disable' );
                $( self ).attr( 'disable', false );

                closing_announcement = false;
            },
        })
    });

    // -------------------[ Announcement End ]-------------------------


    //dashboard content responsive fix
    var tabContentWidth = $(".atbd_dashboard_wrapper .atbd_tab-content").innerWidth();
    if(tabContentWidth < 650){
      $(".atbd_dashboard_wrapper .atbd_tab-content").addClass("atbd_tab-content--fix");
    }

    // Dashboard Listing Ajax
    function directorist_dashboard_listing_ajax($activeTab,paged=1,search='',task='',taskdata='') {
        var tab = $activeTab.data('tab');
        $.ajax({
            url: atbdp_public_data.ajaxurl,
            type: 'POST',
            dataType: 'json',
            data: {
                'action': 'directorist_dashboard_listing_tab',
                'tab': tab,
                'paged': paged,
                'search': search,
                'task': task,
                'taskdata': taskdata,
            },
			beforeSend: function () {
				$('#directorist-dashboard-preloader').show();
			},
            success: function success(response) {
                $('.directorist-dashboard-listings-tbody').html(response.data.content);
                $('.directorist-dashboard-pagination .nav-links').html(response.data.pagination);
                $('.directorist-dashboard-listing-nav-js a').removeClass('tabItemActive');
                $activeTab.addClass('tabItemActive');
                $('#my_listings').data('paged',paged);
            },
			complete: function () {
				$('#directorist-dashboard-preloader').hide();
			}
        });
    }

    // Dashboard Listing Tabs
    $('.directorist-dashboard-listing-nav-js a').on('click', function(event) {
        var $item = $(this);

    	if ($item.hasClass('tabItemActive')) {
    		return false;
    	}

        directorist_dashboard_listing_ajax($item);
        $('#directorist-dashboard-listing-searchform input[name=searchtext').val('');
        $('#my_listings').data('search','');

    	return false;
    });

    // Dashboard pagination
    $('.directorist-dashboard-pagination .nav-links').on('click', 'a', function(event) {
        var $link = $(this);
        var paged = $link.attr('href');
        paged = paged.split('/page/')[1];
        paged = parseInt(paged);

        var search = $('#my_listings').data('search');

        $activeTab = $('.directorist-dashboard-listing-nav-js a.tabItemActive');
        directorist_dashboard_listing_ajax($activeTab,paged,search);

    	return false;
    });

    // Dashboard become an author
    $('.directorist-become-author').on('click', function(e){
        e.preventDefault();
        $(".directorist-become-author-modal").addClass("directorist-become-author-modal__show");
    });
    $('.directorist-become-author-modal__cancel').on('click', function(e){
        e.preventDefault();
        $(".directorist-become-author-modal").removeClass("directorist-become-author-modal__show");
    });
    $('.directorist-become-author-modal__approve').on('click', function(e){
        e.preventDefault();
        var userId = $(this).attr('data-userId');
        var nonce = $(this).attr('data-nonce');
        var data = {
            userId : userId,
            nonce  : nonce,
            action : "atbdp_become_author"
        };

        // Send the data
        $.post(atbdp_public_data.ajaxurl, data, function (response) {
            $('.directorist-become-author__loader').addClass('active');
            $('#directorist-become-author-success').html(response);
            $('.directorist-become-author').hide();
            $(".directorist-become-author-modal").removeClass("directorist-become-author-modal__show");
        });
    });

    // Dashboard Tasks eg. delete
    $('.directorist-dashboard-listings-tbody').on('click', '.directorist-dashboard-listing-actions a[data-task]', function(event) {
    	var task       = $(this).data('task');
    	var postid     = $(this).closest('tr').data('id');
    	var $activeTab = $('.directorist-dashboard-listing-nav-js a.tabItemActive');
    	var paged      = $('#my_listings').data('paged');
    	var search     = $('#my_listings').data('search');

		if (task=='delete') {
	        swal({
	            title: atbdp_public_data.listing_remove_title,
	            text: atbdp_public_data.listing_remove_text,
	            type: "warning",
	            cancelButtonText: atbdp_public_data.review_cancel_btn_text,
	            showCancelButton: true,
	            confirmButtonColor: "#DD6B55",
	            confirmButtonText: atbdp_public_data.listing_remove_confirm_text,
	            showLoaderOnConfirm: true,
	            closeOnConfirm: false
	        },

	        function (isConfirm) {
	            if (isConfirm) {
	            	directorist_dashboard_listing_ajax($activeTab,paged,search,task,postid);

                    swal({
                        title: atbdp_public_data.listing_delete,
                        type: "success",
                        timer: 200,
                        showConfirmButton: false
                    });
	            }
	        });
		}

    	return false;
    });

    // Dashboard Search
    $('#directorist-dashboard-listing-searchform input[name=searchtext').val(''); //onready
    $('#directorist-dashboard-listing-searchform').on('submit', function(event) {
    	var $activeTab = $('.directorist-dashboard-listing-nav-js a.tabItemActive');
    	var search = $(this).find('input[name=searchtext]').val();
    	directorist_dashboard_listing_ajax($activeTab,1,search);
    	$('#my_listings').data('search',search);
    	return false;
    });

    /* atbd alert dismiss */
    if($('.atbd-alert-close') !== null){
        $('.atbd-alert-close').each(function(i,e){
            $(e).on('click', function(e){
                e.preventDefault();
                $(this).parent('.atbd-alert').remove();
            });
        });
    }

    // Dropdown
    $('body').on('click', '.directorist_dropdown .directorist_dropdown-toggle', function(e){
        e.preventDefault();
        $(this).siblings('.directorist_dropdown-option').toggle();
    });

    // Select Option after click
    $('body').on('click','.directorist_dropdown .directorist_dropdown-option ul li a', function(e){
        e.preventDefault();
        let optionText = $(this).html();
        $(this).children('.directorist_dropdown-toggle__text').html(optionText)
        $(this).closest('.directorist_dropdown-option').siblings('.directorist_dropdown-toggle').children('.directorist_dropdown-toggle__text').html(optionText);
        $('.directorist_dropdown-option').hide();
    });

    // Hide Clicked Anywhere
    $(document).bind('click', function(e) {
        let clickedDom = $(e.target);
        if(!clickedDom.parents().hasClass('directorist_dropdown'))
        $('.directorist_dropdown-option').hide();
    });

    // Legacy Mode Check

    // Modal
    let directoristModal = document.querySelector('.directorist-modal-js');
    $( 'body' ).on( 'click', '.directorist-btn-modal-js', function( e ) {
        e.preventDefault();
        console.log("yes")
        let data_target = $(this).attr("data-directorist_target");
        $( '.'+data_target ).toggleClass( 'directorist-show' );
    });

    $('body').on('click', '.directorist-modal-close-js', function(e){
        e.preventDefault();
        console.log($(this).closest('.directorist-modal-js'));
        $(this).closest('.directorist-modal-js').removeClass('directorist-show');
    });

    $(document).bind('click', function(e) {
        if(e.target == directoristModal){
            directoristModal.classList.remove('directorist-show');
        }
    });

    $('.atbd_tab_inner:first-child').addClass('tabContentActive');

    // Directorist Dropdown
    $('body').on('click', '.directorist-dropdown-js .directorist-dropdown__toggle-js', function(e){
        e.preventDefault();
        $(this).siblings('.directorist-dropdown__links-js').toggle();
    });

    $(document).bind('click', function(e) {
        let clickedDom = $(e.target);
        if ( ! clickedDom.parents().hasClass('directorist-dropdown-js') )
        $('.directorist-dropdown__links-js').hide();
    });

})(jQuery);
  // on load of the page: switch to the currently selected tab
  var tab_url = window.location.href.split("/").pop();
  if (tab_url.startsWith("#active_")) {
    var urlId = tab_url.split("#").pop().split("active_").pop();
    if (urlId !== 'my_listings') {
        document.querySelector(`a[target=${urlId}]`).click();
    }
  }


  /* custom dropdown */
  var atbdDropdown = document.querySelectorAll('.atbd-dropdown');

  // toggle dropdown
  var clickCount = 0;
  if (atbdDropdown !== null) {
    atbdDropdown.forEach(function (el) {
        el.querySelector('.atbd-dropdown-toggle').addEventListener('click', function (e) {
            e.preventDefault();
            clickCount++;
            if (clickCount % 2 === 1) {
                document.querySelectorAll('.atbd-dropdown-items').forEach(function (elem) {
                    elem.classList.remove('atbd-show');
                });
                el.querySelector('.atbd-dropdown-items').classList.add('atbd-show');
            } else {
                document.querySelectorAll('.atbd-dropdown-items').forEach(function (elem) {
                    elem.classList.remove('atbd-show');
                });
            }
        });
    });
  }

  // remvoe toggle when click outside
  document.body.addEventListener('click', function (e) {
    if (e.target.getAttribute('data-drop-toggle') !== 'atbd-toggle') {
        clickCount = 0;
        document.querySelectorAll('.atbd-dropdown-items').forEach(function (el) {
            el.classList.remove('atbd-show');
        });
    }
  });

  //custom select
  var atbdSelect = document.querySelectorAll('.atbd-drop-select');
  if (atbdSelect !== null) {
    atbdSelect.forEach(function (el) {
        el.querySelectorAll('.atbd-dropdown-item').forEach(function (item) {
            item.addEventListener('click', function (e) {
                e.preventDefault();
                el.querySelector('.atbd-dropdown-toggle').textContent = item.textContent;
                el.querySelectorAll('.atbd-dropdown-item').forEach(function (elm) {
                    elm.classList.remove('atbd-active');
                });
                item.classList.add('atbd-active');
            });
        });
    });
  }

  // select data-status
  var atbdSelectData = document.querySelectorAll('.atbd-drop-select.with-sort');
  atbdSelectData.forEach(function (el) {
    el.querySelectorAll('.atbd-dropdown-item').forEach(function (item) {
        let ds = el.querySelector('.atbd-dropdown-toggle');
        let itemds = item.getAttribute('data-status');
        item.addEventListener('click', function (e) {
            ds.setAttribute('data-status', `${itemds}`);
        });
    });
  });

  var flatWrapper = document.querySelector(".flatpickr-calendar");
  var fAvailableTime = document.querySelector(".bdb-available-time-wrapper");
  if (flatWrapper != null && fAvailableTime != null) {
    flatWrapper.insertAdjacentElement("beforeend", fAvailableTime);
  }