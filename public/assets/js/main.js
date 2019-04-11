(function ($) {
    //sorting toggle
    $('.sorting span').on('click', function () {
        $(this).toggleClass('fa-sort-amount-asc fa-sort-amount-desc');
    });



    /* Externel Library init
     ------------------------*/
    //Star rating
    $(".stars").barrating({
        theme: 'fontawesome-stars'
    });


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

    /* Add review to the database using ajax*/
    var submit_count = 1;
    $("#atbdp_review_form").on("submit", function () {
        if (submit_count > 1) {
            // show error message
            /*@todo; make all the strings on js file translatable*/
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
        atbdp_do_ajax($form, 'save_listing_review', $data, function (response) {
            var output = '';
            var deleteBtn = '';
            var d;
            var name= $form.find("#reviewer_name").val();
            var content= $form.find("#review_content").val();
            var rating= $form.find("#review_rating").val();
            var ava_img= $form.find("#reviewer_img").val();
            if (response.success) {
                d = new Date(); // parse mysql date string to js date object
                d = d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear(); // build the date string, month is 0 based so add 1 to that to get real month.

                output +=
                    '<div class="atbd_single_review" id="single_review_' + response.data.id + '">' +
                    '<input type="hidden" value="1" id="has_ajax">' +
                    '<div class="atbd_review_top"> ' +
                    '<div class="atbd_avatar_wrapper"> ' +
                    '<div class="atbd_review_avatar">' + ava_img + '</div> ' +
                    '<div class="atbd_name_time"> ' +
                    '<p>' + name + '</p>' +
                    '<span class="review_time">' + d + '</span> ' + '</div> ' + '</div> ' +
                    '<div class="atbd_rated_stars">' + print_static_rating(rating) + '</div> ' +
                    '</div> ' +
                    '<div class="review_content"> ' +
                    '<p>' + content + '</p> ' +
                    //'<a href="#"><span class="fa fa-mail-reply-all"></span>Reply</a> ' +
                    '</div> ' +
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
                }
                var sectionToShow= $("#has_ajax").val();
                var sectionToHide= $(".atbdp_static");
                var sectionToHide2= $(".directory_btn");
                if (sectionToShow){
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
                $r_notice = $('#review_notice');
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

    /* load more review when load more review button clicks*/
    var review_offset = 3;
    $("#load_more_review").on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        var post_id = $this.data('id');

        var data = 'offset=' + review_offset + '&post_id=' + post_id;
        atbdp_do_ajax($this, 'load_more_review', data, function ($data) {
            // if we get data then start processing it
            if ($data != 'false' && $data != 'error') {
                $data = jQuery.parseJSON($data); // parse the received json encoded data to JSON object
                var length = $data.length;
                var output = '';
                var d;

                for (var i = 0; i < length; i++) {
                    var review = $data[i];
                    d = new Date(Date.parse(review.date_created)); // parse mysql date string to js date object
                    d = d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear(); // build the date string, month is 0 based so add 1 to that to get real month.

                    output += '<div class="single_review">' +
                        '<div class="review_top">' +
                        '<div class="reviewer"><i class="fa fa-user" aria-hidden="true"></i><p>' + review.name + '</p></div>' +
                        '<p class="review_time">' + d + '</p>' +
                        '<div class="br-theme-css-stars-static">' + print_static_rating(review.rating) + '</div>' +
                        '</div>' +
                        '<div class="review_content">' +
                        '<p> ' + review.content + '</p>' +
                        '</div>' +
                        '</div>';
                }
                // if we have received less then 3 records then it means no records left in the database, so hide 'load more review' button.

                // show success message
                swal({
                    title: atbdp_public_data.review_loaded,
                    type: "success",
                    timer: 500,
                    showConfirmButton: false
                });



                if (length < 3) {
                    $this.remove();
                }
            } else {
                // no data found in the database.
                $this.remove();
                // show error message
                swal({
                    title: "ERORR!!",
                    text: atbdp_public_data.review_not_available,
                    type: "warning",
                    timer: 1000,
                    showConfirmButton: false
                });
            }


            $('#client_review_list').append(output);


        });
        review_offset += 3;
        return false;
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


    $("[data-toggle='tooltip']").tooltip();

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
                                text:  atbdp_public_data.listing_error_text,
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

    $('.atbdp_child_category').hide();
    $('.atbdp_parent_category >li >span').on('click', function () {
        $(this).siblings('.atbdp_child_category').slideToggle();
    });

    $('#user_profile_form').on('submit', function (e) {
        // submit the form to the ajax handler and then send a response from the database and then work accordingly and then after finishing the update profile then work on remove listing and also remove the review and rating form the custom table once the listing is deleted successfully.
        var $form = $(this);
        var $queryString = $form.serialize();
        atbdp_do_ajax($form, 'update_user_profile', $queryString, function (response) {
            if (response.success) {
                $('#pro_notice').html('<p style="padding: 22px;" class="alert-success">' + response.data + '</p>');
            } else {
                $('#pro_notice').html('<p style="padding: 22px;" class="alert-danger">' + response.data + '</p>');
            }
        });

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


})(jQuery);

// Custom Image uploader for user dashboard page
jQuery(function ($) {
    // Set all variables to be used in scope
    var frame,
        imgContainer = $('#profile_pic_container'), // profile picture container id here
        addImgLink = imgContainer.find('#upload_pro_pic'),
        delImgLink = imgContainer.find('#remove_pro_pic'),
        imgIdInput = imgContainer.find('#pro_pic'),
        imgTag = imgContainer.find('#pro_img');


    // ADD IMAGE LINK
    addImgLink.on('click', function (event) {
        event.preventDefault();

        // If the media frame already exists, reopen it.
        if (frame) {
            frame.open();
            return;
        }

        // Create a new media frame
        /*@todo; make the static help text translatable later*/
        frame = wp.media({
            title: atbdp_public_data.upload_pro_pic_title,
            button: {
                text: atbdp_public_data.upload_pro_pic_text
            },
            library: {type: 'image'}, // only
            multiple: false  // Set to true to allow multiple files to be selected
        });


        // When an image is selected in the media frame...
        frame.on('select', function () {
            const selection = frame.state().get('selection').first().toJSON();
            if (selection.type === 'image') {
                // we have got an image attachment so lets proceed.
                // target the input field and then assign the current id of the attachment to an array.
                imgTag.attr('src', selection.url); // set the preview image url
                imgIdInput.attr('value', selection.id); // set the value of input field
            }


        });

        // Finally, open the modal on click
        frame.open();
    });


    delImgLink.on('click', function (e) {
        e.preventDefault();
        // if no image exist then add placeholder and hide remove image button
        imgTag.attr('src', atbdp_public_data.PublicAssetPath + 'images/no-image.jpg');
        imgIdInput.attr('value', ''); // set the value of input field


    });


});

(function ($) {
    /**
     * Called when the page has loaded.
     *
     * @since    1.0.0
     */
    $(function () {

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
                        ;


                        atbdp_report_abuse_submitted = false; // Re-enable the submit event
                    }, 'json');

                }
                ;

            });

            // Validate contact form
            var atbdp_contact_submitted = false;

            $('#atbdp-contact-form').validator({
                disable: false
            }).on('submit', function (e) {

                if (atbdp_contact_submitted) return false;

                // Check for errors
                if (!e.isDefaultPrevented()) {

                    e.preventDefault();

                    atbdp_contact_submitted = true;


                    $('#atbdp-contact-message-display').append('<div class="atbdp-spinner"></div>');

                    // Post via AJAX
                    var data = {
                        'action': 'atbdp_public_send_contact_email',
                        'post_id': $('#atbdp-post-id').val(),
                        'name': $('#atbdp-contact-name').val(),
                        'email': $('#atbdp-contact-email').val(),
                        'message': $('#atbdp-contact-message').val(),
                    };

                    $.post(atbdp_public_data.ajaxurl, data, function (response) {
                        if (1 == response.error) {
                            $('#atbdp-contact-message-display').addClass('text-danger').html(response.message);
                        } else {
                            $('#atbdp-contact-message').val('');
                            $('#atbdp-contact-message-display').addClass('text-success').html(response.message);
                        }
                        ;

                    }, 'json');

                } else {
                    atbdp_contact_submitted = false;
                }
                ;

            });


        };

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

            e.preventDefault();
            alert(atbdp_public_data.login_alert_message);

        });

        // Add or Remove from favourites
        $('#atbdp-favourites').on('click', 'a.atbdp-favourites', function (e) {

            e.preventDefault();

            var $this = $(this);

            var data = {
                'action': 'atbdp_public_add_remove_favorites',
                'post_id': $this.data('post_id')
            };

            $.post(atbdp_public_data.ajaxurl, data, function (response) {
                $('#atbdp-favourites').html(response);
            });

        });

        /*$('#atbdp_tabs a').click(function(e) {
            e.preventDefault();
            $(this).tab('show');
        });*/

// store the currently selected tab in the hash value
        $("ul.nav-tabs > li > a").on("show.bs.tab", function(e) {
            var id = $(e.target).attr("href").substr(1);
            window.location.hash = id;
        });

// on load of the page: switch to the currently selected tab
        //console.log(window.location);
        var hash = window.location.hash;
        $('#atbdp_tabs a[href="' + hash + '"]').tab('show');
    });

    var $this = $("#directorist.atbd_wrapper");
    var columnLeft = $(".atbd_col_left.col-lg-8");
    var columnRight = $(".directorist.col-lg-4");
    var tabColumn = $(".atbd_dashboard_wrapper .tab-content .tab-pane .col-lg-4");
    var size = $this.width();
    if(size >= 500 && size <= 735){
        columnLeft.toggleClass("col-lg-8");
        columnRight.toggleClass("col-lg-4");
    }
    if(size <= 600){
        $this.addClass("size-xs");
        tabColumn.toggleClass("col-lg-4");
    }

    var listing_size = $(".atbd_dashboard_wrapper .atbd_single_listing").width();
    if(listing_size < 200){
        $(".atbd_single_listing .db_btn_area").addClass("db_btn_area--sm");
    }

})(jQuery);








