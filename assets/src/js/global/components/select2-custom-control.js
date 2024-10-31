const $ = jQuery;

window.addEventListener( 'load', waitAndInit );
window.addEventListener( 'directorist-search-form-nav-tab-reloaded', waitAndInit );
window.addEventListener( 'directorist-type-change', waitAndInit );
window.addEventListener( 'directorist-instant-search-reloaded', waitAndInit );

function waitAndInit() {
    setTimeout( init, 0 );
}

// Initialize
function init() {

    // Add custom dropdown toggle button
    selec2_add_custom_dropdown_toggle_button();

    // Add custom close button where needed
    selec2_add_custom_close_button_if_needed();

    // Add custom close button if field contains value on change
    $('.select2-hidden-accessible').on('change', function (e) {
        var value = $(this).children("option:selected").val();
        if (!value) {
            return;
        }
        selec2_add_custom_close_button($(this));

        let selectItems = this.parentElement.querySelectorAll('.select2-selection__choice');
        selectItems.forEach(item => { 
            item.childNodes && item.childNodes.forEach(node => {
                if (node.nodeType && node.nodeType === Node.TEXT_NODE) {
                    let originalString = node.textContent;
                    let modifiedString = originalString.replace(/^[\s\xa0]+/, '');
                    node.textContent = modifiedString;
                    item.title = modifiedString;
                }
            });
        })

        let customSelectItem = this.parentElement.querySelector('.select2-selection__rendered');
        customSelectItem.childNodes && customSelectItem.childNodes.forEach(node => {
            if (node.nodeType && node.nodeType === Node.TEXT_NODE) {
                let originalString = node.textContent;
                let modifiedString = originalString.replace(/^[\s\xa0]+/, '');
                node.textContent = modifiedString;
            }
        });
    });
}

function selec2_add_custom_dropdown_toggle_button() {
    // Remove Default
    $('.select2-selection__arrow').css({
        'display': 'none'
    });

    const addon_container = selec2_get_addon_container( '.select2-hidden-accessible' );

    if ( ! addon_container ) {
        return;
    }

    const dropdown = addon_container.find( '.directorist-select2-dropdown-toggle' );

    if ( ! dropdown.length ) {
        // Add Dropdown Toggle Button
        let iconURL = directorist.assets_url + 'icons/font-awesome/svgs/solid/chevron-down.svg';
        let iconHTML = directorist.icon_markup.replace( '##URL##', iconURL ).replace( '##CLASS##', '' );
        const dropdownHTML = `<span class="directorist-select2-addon directorist-select2-dropdown-toggle">${iconHTML}</span>`;
        addon_container.append( dropdownHTML );
    }

    const selec2_custom_dropdown = addon_container.find( '.directorist-select2-dropdown-toggle' );

    // Toggle --is-open class
    $('.select2-hidden-accessible').on('select2:open', function (e) {
        let dropdown_btn = $(this).next().find('.directorist-select2-dropdown-toggle');
        dropdown_btn.addClass('--is-open');
    });

    $('.select2-hidden-accessible').on('select2:close', function (e) {
        let dropdown_btn = $(this).next().find('.directorist-select2-dropdown-toggle');
        dropdown_btn.removeClass('--is-open');
        
        let dropdownParent = $(this).closest('.directorist-search-field');
        let renderTitle = $(this).next().find('.select2-selection__rendered').attr('title');
        
        // Check if renderTitle is empty and remove the focus class if so
        if (!renderTitle) {
            dropdownParent.removeClass('input-is-focused');
        } else {
            dropdownParent.addClass('input-has-value');
        }
    });
    

    // Toggle Dropdown
    selec2_custom_dropdown.on('click', function (e) {
        let isOpen = $(this).hasClass('--is-open');
        let field = $(this).closest(".select2-container").siblings('select:enabled');

        if (isOpen) {
            field.select2('close');
        } else {
            field.select2( 'open' );
        }
    });

    // Adjust space for addons
    selec2_adjust_space_for_addons();
}

function selec2_add_custom_close_button_if_needed () {
    var select2_fields = $( '.select2-hidden-accessible' );

    if (!select2_fields && !select2_fields.length) {
        return;
    }

    for ( var field of select2_fields ) {
        var value = $( field ).children( 'option:selected' ).val();

        if ( ! value ) { continue; }

        selec2_add_custom_close_button( field );
    }
}

function selec2_add_custom_close_button(field) {
    // Remove Default
    $( '.select2-selection__clear' ).css({ 'display': 'none' });

    const addon_container = selec2_get_addon_container(field);

    if (!(addon_container && addon_container.length)) {
        return;
    }

    // Remove if already exists
    addon_container.find('.directorist-select2-dropdown-close').remove();

    // Add
    let iconURL = directorist.assets_url + 'icons/font-awesome/svgs/solid/times.svg';
    let iconHTML = directorist.icon_markup.replace( '##URL##', iconURL ).replace( '##CLASS##', '' );
    addon_container.prepend( `<span class="directorist-select2-addon directorist-select2-dropdown-close">${iconHTML}</span>` );
    const selec2_custom_close = addon_container.find( '.directorist-select2-dropdown-close' );

    selec2_custom_close.on( 'click', function( e ) {
        const field = $( this ).closest( '.select2-container' ).siblings( 'select:enabled' );
        field.val( null ).trigger( 'change' );

        addon_container.find( '.directorist-select2-dropdown-close' ).remove();
        selec2_adjust_space_for_addons();
    });

    // Adjust space for addons
    selec2_adjust_space_for_addons();
}

function selec2_remove_custom_close_button(field) {
    const addon_container = selec2_get_addon_container(field);

    if (!(addon_container && addon_container.length)) {
        return;
    }

    // Remove
    addon_container.find('.directorist-select2-dropdown-close').remove();

    // Adjust space for addons
    selec2_adjust_space_for_addons();
}

function selec2_get_addon_container(field) {
    var container = (field) ? $(field).next('.select2-container') : $('.select2-container');
    container = $(container).find('.directorist-select2-addons-area');

    if (!container.length) {
        $('.select2-container').append('<span class="directorist-select2-addons-area"></span>');
        container = $('.select2-container').find('.directorist-select2-addons-area');
    }

    var container = ( field  ) ? $( field ).next( '.select2-container' ) : null;

    if ( ! container ) {
        return null;
    }

    const addonsArea = $( container ).find( '.directorist-select2-addons-area' );

    if ( ! addonsArea.length ) {
        container.append( '<span class="directorist-select2-addons-area"></span>' );
        return container.find( '.directorist-select2-addons-area' );
    }

    return addonsArea;
}

function selec2_adjust_space_for_addons() {
    let container = $( '.select2-container' ).find( '.directorist-select2-addons-area' );

    if (!container.length) {
        return;
    }

    let width = container.outerWidth();

    $( '.select2-container' ).find( '.select2-selection__rendered' ).css({
        'padding-right': width + 'px',
    });
}