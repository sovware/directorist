const $ = jQuery;
const content = '';

// Category icon selection
function selecWithIcon(selected) {
    if (!selected.id) {
        return selected.text;
    }
    const $elem = $(
        `<span><span class='${atbdp_admin_data.icon_type} ${selected.element.value}'></span> ${selected.text
        }</span>`
    );
    return $elem;
}

$("[data-toggle='tooltip']").tooltip();

// price range
const pricerange = $('#pricerange_val').val();
if (pricerange) {
    $('#pricerange').fadeIn(100);
}
$('#price_range_option').on('click', function () {
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
/* $('body').on('click', '#addNewSocial', function () {
    const social_wrap = $('#social_info_sortable_container'); // cache it
    const currentItems = $('.directorist-form-social-fields').length;
    const ID = `id=${currentItems}`; // eg. 'id=3'
    const iconBindingElement = jQuery('#addNewSocial');
    // arrange names ID in order before adding new elements
    $('.directorist-form-social-fields').each(function (index, element) {
        const e = $(element);
        e.attr('id', `socialID-${index}`);
        e.find('select').attr('name', `social[${index}][id]`);
        e.find('.atbdp_social_input').attr('name', `social[${index}][url]`);
        e.find('.directorist-form-social-fields__remove').attr('data-id', index);
    });
    // now add the new elements. we could do it here without using ajax but it would require more markup here.
    atbdp_do_ajax(iconBindingElement, 'atbdp_social_info_handler', ID, function (data) {
        //social_wrap.append(data);
    });
}); */

// remove the social field and then reset the ids while maintaining position
$(document).on('click', '.directorist-form-social-fields__remove', function (e) {
    const id = $(this).data('id');
    const elementToRemove = $(`div#socialID-${id}`);
    e.preventDefault();
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
        function (isConfirm) {
            if (isConfirm) {
                // user has confirmed, no remove the item and reset the ids
                elementToRemove.slideUp('fast', function () {
                    elementToRemove.remove();
                    // reorder the index
                    $('.directorist-form-social-fields').each(function (index, element) {
                        const e = $(element);
                        e.attr('id', `socialID-${index}`);
                        e.find('select').attr('name', `social[${index}][id]`);
                        e.find('.atbdp_social_input').attr(
                            'name',
                            `social[${index}][url]`
                        );
                        e.find('.directorist-form-social-fields__remove').attr('data-id', index);
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
$('#upgrade_directorist').on('click', function (event) {
    event.preventDefault();
    const $this = $(this);
    // display a notice to user to wait
    // send an ajax request to the back end
    atbdp_do_ajax($this, 'atbdp_upgrade_old_listings', null, function (response) {
        if (response.success) {
            $this.after(`<p>${response.data}</p>`);
        }
    });
});

// upgrade old pages
$('#shortcode-updated input[name="shortcode-updated"]').on('change', function (event) {
    event.preventDefault();
    $('#success_msg').hide();

    const $this = $(this);
    // display a notice to user to wait
    // send an ajax request to the back end
    atbdp_do_ajax($this, 'atbdp_upgrade_old_pages', null, function (response) {
        if (response.success) {
            $('#shortcode-updated').after(`<p id="success_msg">${response.data}</p>`);
        }
    });

    $('.atbdp_ajax_loading').css({
        display: 'none',
    });
});

// redirect to import import_page_link
$('#csv_import input[name="csv_import"]').on('change', function (event) {
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