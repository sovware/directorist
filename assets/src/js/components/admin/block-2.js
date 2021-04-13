const $ = jQuery;

// Set all variables to be used in scope
const has_tagline = $('#has_tagline').val();
const has_excerpt = $('#has_excerpt').val();

if (has_excerpt && has_tagline) {
    $('.atbd_tagline_moto_field').fadeIn();
} else {
    $('.atbd_tagline_moto_field').fadeOut();
}

$('#atbd_optional_field_check').on('change', function () {
    $(this).is(':checked')
        ? $('.atbd_tagline_moto_field').fadeIn()
        : $('.atbd_tagline_moto_field').fadeOut();
});

// Load custom fields of the selected category in the custom post type "atbdp_listings"
$('#at_biz_dir-categorychecklist, #at_biz_dir-categorychecklist-pop').on('change', function (event) {
    const length = $('#at_biz_dir-categorychecklist input:checked');
    const length2 = $('#at_biz_dir-categorychecklist-pop input:checked');
    const id = [];
    const directory_type = $('select[name="directory_type"]').val();
    const from_single_directory = $('input[name="directory_type"]').val();
    if( length ){
        length.each((el, index) => {
            id.push($(index).val());
        });
    }
    
    if( length2 ){
        length2.each((el, index) => {
            id.push($(index).val());
        });
    }
    
    const data = {
        action: 'atbdp_custom_fields_listings',
        post_id: $('#post_ID').val(),
        term_id: id,
        directory_type: directory_type ? directory_type : from_single_directory,
    };
    $.post(atbdp_admin_data.ajaxurl, data, function (response) {
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
                atbd_tooltip.forEach(function (el) {
                    if (el.getAttribute('aria-label') !== ' ') {
                        document.body.addEventListener(
                            'mouseover',
                            function (e) {
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
$(document).ready(function () {
    const length = $('#at_biz_dir-categorychecklist input:checked');
    const length2 = $('#at_biz_dir-categorychecklist-pop input:checked');
    const id = [];
    const directory_type = $('select[name="directory_type"]').val();
    const from_single_directory = $('input[name="directory_type"]').val();
    if( length ){
        length.each((el, index) => {
            id.push($(index).val());
        });
    }
    
    if( length2 ){
        length2.each((el, index) => {
            id.push($(index).val());
        });
    }
    const data = {
        action: 'atbdp_custom_fields_listings',
        post_id: $('#post_ID').val(),
        term_id: id,
        directory_type: directory_type ? directory_type : from_single_directory,
    };
    $.post(atbdp_admin_data.ajaxurl, data, function (response) {
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
                atbd_tooltip.forEach(function (el) {
                    if (el.getAttribute('aria-label') !== ' ') {
                        document.body.addEventListener(
                            'mouseover',
                            function (e) {
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
$('#listing_popular_by select[name="listing_popular_by"]').on('change', function () {
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

/* Show and hide manual coordinate input field */
if (!$('input#manual_coordinate').is(':checked')) {
    $('.directorist-map-coordinates').hide();
}
$('#manual_coordinate').on('click', function (e) {
    if ($('input#manual_coordinate').is(':checked')) {
        $('.directorist-map-coordinates').show();
    } else {
        $('.directorist-map-coordinates').hide();
    }
});

// send system info to admin
$('#atbdp-send-system-info-submit').on('click', function (event) {
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
                $('.system_info_success').html('Successfully sent');
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
$('#generate-url').on('click', function (e) {
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

$('#revoke-url').on('click', function (e) {
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