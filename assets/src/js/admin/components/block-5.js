const $ = jQuery;

// Category icon selection
function selecWithIcon(selected) {
    if (!selected.id) {
        return selected.text;
    }
    const $elem = $(`<span><span class='${selected.element.value}'></span> ${selected.text}</span>`);
    return $elem;
}

$('#category_icon').select2({
    placeholder: atbdp_admin_data.i18n_text.icon_choose_text,
    allowClear: true,
    templateResult: selecWithIcon,
});

$('body').on('click', '.directorist_settings-trigger', function () {
    $('.setting-left-sibebar').toggleClass('active');
    $('.directorist_settings-panel-shade').toggleClass('active');
});
$('body').on('click', '.directorist_settings-panel-shade', function () {
    $('.setting-left-sibebar').removeClass('active');
    $(this).removeClass('active');
});

// Directorist More Dropdown
$('body').on('click', '.directorist_more-dropdown-toggle', function (e) {
    e.preventDefault();
    $(this).toggleClass('active');
    $('.directorist_more-dropdown-option').removeClass('active');
    $(this)
        .siblings('.directorist_more-dropdown-option')
        .removeClass('active');
    $(this)
        .next('.directorist_more-dropdown-option')
        .toggleClass('active');
    e.stopPropagation();
});
$(document).on('click', function (e) {
    if ($(e.target).is('.directorist_more-dropdown-toggle, .active') === false) {
        $('.directorist_more-dropdown-option').removeClass('active');
        $('.directorist_more-dropdown-toggle').removeClass('active');
    }
});

// Select Dropdown
$('body').on('click', '.directorist_dropdown .directorist_dropdown-toggle', function (e) {
    e.preventDefault();
    $(this).siblings('.directorist_dropdown-option').toggle();
});

// Select Option after click
$('body').on('click', '.directorist_dropdown .directorist_dropdown-option ul li a', function (e) {
    e.preventDefault();
    let optionText = $(this).html();
    $(this).children('.directorist_dropdown-toggle__text').html(optionText)
    $(this).closest('.directorist_dropdown-option').siblings('.directorist_dropdown-toggle').children('.directorist_dropdown-toggle__text').html(optionText);
    $('.directorist_dropdown-option').hide();
});

// Hide Clicked Anywhere
$(document).bind('click', function (e) {
    let clickedDom = $(e.target);
    if (!clickedDom.parents().hasClass('directorist_dropdown')) {
        $('.directorist_dropdown-option').hide();
    }
});

$('.directorist-type-slug-content').each(function (id, element) {
    // Store old slug value
    let oldSlugVal = $(element).children('.directorist_listing-slug-text').attr('data-value');

    // Slug Edit
    $(element).children('.directorist_listing-slug-text').on('input', function () {
        let slugText = $(this).text();
        $(this).attr('data-value', slugText);
    })

    // Edit Form Open
    $('body').on('click', '.directorist-listing-slug__edit', function (e) {
        e.preventDefault();
        var editableSlug = $(this).closest('.directorist-listing-slug-edit-wrap').siblings('.directorist_listing-slug-text');
        editableSlug.attr('contenteditable', true);
        editableSlug.addClass('directorist_listing-slug-text--editable');
        $(this).addClass('directorist_listing-slug-formText-add');
        $(this).siblings('.directorist_listing-slug-formText-remove').removeClass('directorist_listing-slug-formText-remove--hidden');
        editableSlug.focus();
    });

    // edit directory type slug
    $('body').on('click', '.directorist_listing-slug-formText-add', function (e) {
        e.preventDefault();
        _this = $(this);
        var type_id = $(this).data('type-id');
        update_slug = $('.directorist-slug-text-' + type_id).attr('data-value');
        oldSlugVal = $(element).children('.directorist_listing-slug-text').attr('data-value'); /* Update the slug values */
        const addSlug = $(this);
        addSlug
            .closest('.directorist-listing-slug-edit-wrap')
            .append(`<span class="directorist_loader"></span>`);
        $.ajax({
            type: 'post',
            url: atbdp_admin_data.ajaxurl,
            data: {
                action: 'directorist_type_slug_change',
                type_id: type_id,
                update_slug: update_slug
            },
            success(response) {
                addSlug.closest('.directorist-listing-slug-edit-wrap')
                    .children('.directorist_loader')
                    .remove();
                if (response) {
                    if (response.error) {

                        $('.directorist-slug-notice-' + type_id).removeClass('directorist-slug-notice-success');
                        $('.directorist-slug-notice-' + type_id).addClass('directorist-slug-notice-error');
                        $('.directorist-slug-notice-' + type_id).empty().html(response.error);
                        $('.directorist-slug-text-' + type_id).text(response.old_slug);
                        setTimeout(function () {
                            $('.directorist-slug-notice-' + type_id).empty().html("");
                        }, 3000);
                    } else {
                        $('.directorist-slug-notice-' + type_id).empty().html(response.success);
                        $('.directorist-slug-notice-' + type_id).removeClass('directorist-slug-notice-error');
                        $('.directorist-slug-notice-' + type_id).addClass('directorist-slug-notice-success');
                        setTimeout(function () {
                            addSlug
                                .closest('.directorist-listing-slug__form')
                                .css({
                                    "display": "none"
                                })
                            $('.directorist-slug-notice-' + type_id).html("");
                        }, 1500);
                    }
                }

                $(_this).removeClass('directorist_listing-slug-formText-add');
                $(_this).siblings('.directorist_listing-slug-formText-remove').addClass('directorist_listing-slug-formText-remove--hidden');
                $(_this).closest('.directorist-listing-slug-edit-wrap').siblings('.directorist_listing-slug-text').removeClass('directorist_listing-slug-text--editable');
                $(_this).closest('.directorist-listing-slug-edit-wrap').siblings('.directorist_listing-slug-text').attr('contenteditable', 'false');
            },
        });
    });

    // Edit Form Remove
    $(element).find('.directorist_listing-slug-formText-remove').on('click', function (e) {
        e.preventDefault()
        let thisClosestSibling = $(this).closest('.directorist-listing-slug-edit-wrap').siblings('.directorist_listing-slug-text');
        $(this).siblings('.directorist-listing-slug__edit').removeClass('directorist_listing-slug-formText-add');
        thisClosestSibling.removeClass('directorist_listing-slug-text--editable');
        thisClosestSibling.attr('contenteditable', 'false');
        $(this).addClass('directorist_listing-slug-formText-remove--hidden');
        thisClosestSibling.attr('data-value', oldSlugVal);
        thisClosestSibling.text(oldSlugVal);
    });

    // Hide Slug Form outside click
    $(document).on('click', function(e) {
        if(!e.target.closest('.directorist-type-slug')){
            $(element).find('.directorist_listing-slug-text').attr('data-value', oldSlugVal);
            $(element).find('.directorist_listing-slug-text').text(oldSlugVal);
            $(element).find('.directorist_listing-slug-text').attr('contenteditable', 'false');
            $(element).find('.directorist_listing-slug-text').removeClass('directorist_listing-slug-text--editable');
            $(element).find('.directorist_listing-slug-text').siblings('.directorist-listing-slug-edit-wrap').children('.directorist-listing-slug__edit').removeClass('directorist_listing-slug-formText-add');
            $(element).find('.directorist_listing-slug-text').siblings('.directorist-listing-slug-edit-wrap').children('.directorist_listing-slug-formText-remove').addClass('directorist_listing-slug-formText-remove--hidden');
        }
    });

})

// Tab Content
// ----------------------------------------------------------------------------------
// Modular, classes has no styling, so reusable
$('.atbdp-tab__nav-link').on('click', function (e) {
    e.preventDefault();

    const data_target = $(this).data('target');
    const current_item = $(this).parent();

    // Active Nav Item
    $('.atbdp-tab__nav-item').removeClass('active');
    current_item.addClass('active');

    // Active Tab Content
    $('.atbdp-tab__content').removeClass('active');
    $(data_target).addClass('active');
});

// Custom
$('.atbdp-tab-nav-menu__link').on('click', function (e) {
    e.preventDefault();

    const data_target = $(this).data('target');
    const current_item = $(this).parent();

    // Active Nav Item
    $('.atbdp-tab-nav-menu__item').removeClass('active');
    current_item.addClass('active');

    // Active Tab Content
    $('.atbdp-tab-content').removeClass('active');
    $(data_target).addClass('active');
});

// Section Toggle
$('.atbdp-section-toggle').on('click', function (e) {
    e.preventDefault();

    const data_target = $(this).data('target');
    $(data_target).slideToggle();
});

// Accordion Toggle
$('.atbdp-accordion-toggle').on('click', function (e) {
    e.preventDefault();

    const data_parent = $(this).data('parent');
    const data_target = $(this).data('target');

    if ($(data_target).hasClass('active')) {
        $(data_target).removeClass('active');
        $(data_target).slideUp();
    } else {
        $(data_parent)
            .find('.atbdp-accordion-content')
            .removeClass('active');
        $(data_target).toggleClass('active');

        $(data_parent)
            .find('.atbdp-accordion-content')
            .slideUp();
        $(data_target).slideToggle();
    }
});