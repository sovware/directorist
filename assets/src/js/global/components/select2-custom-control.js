const $ = jQuery;

window.addEventListener( 'load', function() {
    // Add custom dropdown toggle button
    selec2_add_custom_dropdown_toggle_button();

    // Add custom close button
    selec2_add_custom_close_button();

    // Add space for addons
    selec2_add_space_for_addons();
});

function selec2_add_custom_dropdown_toggle_button() {
    // Remove Default
    $('.select2-selection__arrow').css({ 'display': 'none' });

    const addon_container = selec2_get_addon_container();

    // Add Dropdown Toggle Button
    addon_container.append('<span class="directorist-select2-addon directorist-select2-dropdown-toggle"><i class="fas fa-chevron-down"></i></span>');
    let selec2_custom_dropdown = addon_container.find( '.directorist-select2-dropdown-toggle' );

    // Toggle --is-open class
    // -----------------------------
    $('.select2-hidden-accessible').on( 'select2:open', function( e ) {
        let dropdown_btn = $( this ).next().find( '.directorist-select2-dropdown-toggle' );
        dropdown_btn.addClass( '--is-open' );
    });

    $('.select2-hidden-accessible').on( 'select2:close', function( e ) {
        let dropdown_btn = $( this ).next().find( '.directorist-select2-dropdown-toggle' );
        dropdown_btn.removeClass( '--is-open' );
    });

    // Toggle Dropdown
    // -----------------------------
    selec2_custom_dropdown.on( 'click', function( e ) {
        let isOpen = $( this ).hasClass( '--is-open' );
        let field = $(this).closest(".select2-container").siblings('select:enabled');

        if ( isOpen ) {
            field.select2('close');
        } else {
            field.select2('open');
        }
    });
}

function selec2_add_custom_close_button() {
    // Remove Default
    $('.select2-selection__clear').css({ 'display': 'none' });

    const addon_container = selec2_get_addon_container();

    // Add Close
    addon_container.append('<span class="directorist-select2-addon directorist-select2-dropdown-close"><i class="fas fa-times"></i></span>');
    let selec2_custom_close = addon_container.find( '.directorist-select2-dropdown-close' );
    selec2_custom_close.on( 'click', function( e ) {
        let field = $(this).closest(".select2-container").siblings('select:enabled');
        field.val(null).trigger('change');
    });
}

function selec2_get_addon_container() {
    let container = $('.select2-container' ).find( '.directorist-select2-addons-area' );

    if ( ! container.length ) {
        $('.select2-container' ).append( '<span class="directorist-select2-addons-area"></span>' );
        container = $('.select2-container' ).find( '.directorist-select2-addons-area' );
    }

    return container;
}

function selec2_add_space_for_addons() {
    let container = $('.select2-container' ).find( '.directorist-select2-addons-area' );

    if ( ! container.length ) {
        return;
    }

    let width = container.outerWidth();

    $('.select2-container' ).find( '.select2-selection__rendered' ).css({
        'padding-right': width + 'px',
    });
}