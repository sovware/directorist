window.addEventListener('load', () => {
    const $ = jQuery;

    // Init Category Icon Picker
    function initCategoryIconPicker() {
        const iconPickerContainer = document.querySelector( '.directorist-category-icon-picker' );

        if ( ! iconPickerContainer ) {
            return;
        }

        const iconValueElm = document.querySelector( '.category_icon_value' );
        const iconValue = ( iconValueElm ) ? iconValueElm.value : '';

        const onSelectIcon = function( value ) {
            iconValueElm.setAttribute( 'value', value );
        };

        let args = {};
        args.container = iconPickerContainer;
        args.onSelect = onSelectIcon;
        args.icons = {
            fontAwesome: directoriistFontAwesomeIcons,
            lineAwesome: directoriistLineAwesomeIcons,
        };
        args.value = iconValue;
        args.labels = directorist_admin.icon_picker_labels;

        const iconPicker = new IconPicker( args );
        iconPicker.init();
    }

    initCategoryIconPicker();

    // Category icon selection
    function selecWithIcon(selected) {
        if (!selected.id) {
            return selected.text;
        }
        const $elem = $(`<span><span class='${selected.element.value}'></span> ${selected.text}</span>`);
        return $elem;
    }

    if ($('#category_icon').length) {
        $('#category_icon').select2({
            placeholder: directorist_admin.i18n_text.icon_choose_text,
            allowClear: true,
            templateResult: selecWithIcon,
        });
    }

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
        let slugWrapper = $(element).children('.directorist_listing-slug-text');
        let oldSlugVal = slugWrapper.attr('data-value');
    
        // Edit Slug on Click
        slugWrapper.on('click', function (e) {
            e.preventDefault();
            // Check if any other slug is editable
            $('.directorist_listing-slug-text[contenteditable="true"]').each(function () {
                if ($(this).is(slugWrapper)) return; // Skip current slug
                let currentSlugWrapper = $(this);
                // Save the current slug before making another editable
                if (currentSlugWrapper.text().trim() !== oldSlugVal) {
                    saveSlug(currentSlugWrapper);
                }
                currentSlugWrapper.attr('contenteditable', 'false').removeClass('directorist_listing-slug-text--editable');
            });
            
            // Set the current slug as editable
            $(this).attr('contenteditable', true);
            $(this).addClass('directorist_listing-slug-text--editable');
            $(this).focus();
        });
    
        // Slug Edit and Save on Enter Keypress
        slugWrapper.on('input keypress', function (e) {
            let slugText = $(this).text();
            $(this).attr('data-value', slugText);
    
            // Save on Enter Key
            if (e.key === 'Enter' && slugText.trim() !== '') {
                e.preventDefault();
                saveSlug(slugWrapper);  // Trigger save function
            }
    
            // Prevent empty save on Enter key
            if (slugText.trim() === '' && e.key === 'Enter') {
                e.preventDefault();
            }
        });
    
        // Save Slug on Clicking Outside the Editable Field
        $(document).on('click', function (e) {
            if (slugWrapper.attr('contenteditable') === 'true' && !$(e.target).closest('.directorist_listing-slug-text').length) {

                let slugText = slugWrapper.text();
    
                // If the slug was changed, save the new value
                if (oldSlugVal !== slugText.trim()) {
                    saveSlug(slugWrapper);
                }
    
                // Exit editing mode
                slugWrapper.attr('contenteditable', 'false').removeClass('directorist_listing-slug-text--editable');
            }
        });
    
        // Save slug function
        function saveSlug(slugWrapper) {
            let type_id = slugWrapper.data('type-id');
            let newSlugVal = slugWrapper.attr('data-value');
            let slugId = $('.directorist-slug-notice-' + type_id); // Use the correct slug notice element
        
            // Show loading indicator
            slugWrapper.after(`<span class="directorist_loader"></span>`);
        
            // AJAX request to save the slug
            $.ajax({
                type: 'post',
                url: directorist_admin.ajaxurl,
                data: {
                    action: 'directorist_type_slug_change',
                    directorist_nonce: directorist_admin.directorist_nonce,
                    type_id: type_id,
                    update_slug: newSlugVal
                },
                success(response) {
                    // Remove loader
                    slugWrapper.siblings('.directorist_loader').remove();
        
                    if (response) {
                        if (response.error) {
                            // Handle error case
                            slugId.removeClass('directorist-slug-notice-success');
                            slugId.addClass('directorist-slug-notice-error');
                            slugId.empty().html(response.error);
        
                            // Revert to old slug on error
                            if (response.old_slug) {
                                slugWrapper.text(response.old_slug);
                            }
        
                            setTimeout(function () {
                                slugId.empty().html("");
                            }, 3000);
                        } else {
                            // Handle success case
                            slugId.empty().html(response.success);
                            slugId.removeClass('directorist-slug-notice-error');
                            slugId.addClass('directorist-slug-notice-success');
        
                            setTimeout(function () {
                                slugWrapper.closest('.directorist-listing-slug__form').css({
                                    "display": "none"
                                });
                                slugId.html(""); // Clear the success message
                            }, 1500);
        
                            // Update old slug value
                            oldSlugVal = newSlugVal;
                        }
                    }
        
                    // Reset editable state and classes
                    slugWrapper.attr('contenteditable', 'false').removeClass('directorist_listing-slug-text--editable');
                }
            });
        }
        
    });    

    // Tab Content
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
    
});