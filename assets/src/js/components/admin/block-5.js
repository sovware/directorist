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