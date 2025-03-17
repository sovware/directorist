window.addEventListener('load', () => {
    const $ = jQuery;
    const axios = require('axios').default;

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
        
        let $dropdown = $(this).next('.directorist_more-dropdown-option');
        
        // If the clicked dropdown is already active, just remove the active classes
        if ($dropdown.hasClass('active')) {
            $(this).removeClass('active');
            $dropdown.removeClass('active');
        } else {
            // Otherwise, remove active classes from all other dropdowns first
            $('.directorist_more-dropdown-toggle').removeClass('active');
            $('.directorist_more-dropdown-option').removeClass('active');
    
            // Then activate the clicked one
            $(this).addClass('active');
            $dropdown.addClass('active');
        }
    
        e.stopPropagation();
    });
    
    // Click outside to close
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.directorist_more-dropdown').length) {
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
                
                $(document).trigger('click'); // Click outside to save the previous slug
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
                if (oldSlugVal.trim() !== slugText.trim()) {
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

    // Builder Directory Types Drag and Drop
    const builderDragNDropWrapper = document.querySelector(".directorist_builder__list");
    let initialOrder = [];

    // Dragging Start
    builderDragNDropWrapper.addEventListener("dragstart", (e) => {
        const draggingItem = e.target.closest(".directorist_builder__list__item");
        if (!draggingItem) {
            e.preventDefault();
            return;
        }

        draggingItem.classList.add("dragging");

        // Clone the item for visibility
        const cloneItem = draggingItem.cloneNode(true);
        cloneItem.classList.add("drag-clone");
        Object.assign(cloneItem.style, {
            width: `${draggingItem.offsetWidth}px`,
            height: `${draggingItem.offsetHeight}px`,
            position: "absolute",
            top: "-100%",
            opacity: "1",
        });

        draggingItem.after(cloneItem);
        e.dataTransfer.setDragImage(cloneItem, 0, 0);

        // Save initial order
        initialOrder = [...builderDragNDropWrapper.children].map((item, index) => ({
            id: item.dataset.termId,
            order: index
        }));
    });

    // Drag Over
    builderDragNDropWrapper.addEventListener("dragover", (e) => {
        e.preventDefault();
        
        const draggingItem = document.querySelector(".dragging");
        if (!draggingItem) return;

        document.querySelectorAll(".directorist_builder__list__item").forEach(item => 
            item.classList.remove("drag-over")
        );

        const afterElement = getDragAfterElement(builderDragNDropWrapper, e.clientY);
        if (afterElement) afterElement.classList.add("drag-over");
    });

    // Drag End
    builderDragNDropWrapper.addEventListener("dragend", async () => {
        const draggingItem = document.querySelector(".dragging");
        if (!draggingItem) return;

        const afterElement = getDragAfterElement(builderDragNDropWrapper, event.clientY);
        afterElement ? afterElement.before(draggingItem) : builderDragNDropWrapper.appendChild(draggingItem);

        document.querySelector(".drag-clone")?.remove();
        document.querySelectorAll(".dragging, .drag-over").forEach(el => el.classList.remove("dragging", "drag-over"));

        // Update order
        const newOrder = [...builderDragNDropWrapper.children].map((item, index) => {
            item.dataset.order = index;
            return { id: item.dataset.termId, order: index };
        });

        const swappedItems = newOrder.filter(newItem => initialOrder.find(i => i.id === newItem.id && i.order !== newItem.order));

        if (swappedItems.length) {
            await updateDirectorySortingOrders(swappedItems);
            initialOrder = newOrder;
        }
    });

    // Get the closest element to the dragged item
    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll(".directorist_builder__list__item:not(.dragging)")];
        if (draggableElements.length === 0) return null;

        return draggableElements.reduce(
            (closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;

                return offset < 0 && offset > closest.offset ? { offset, element: child } : closest;
            },
            { offset: Number.NEGATIVE_INFINITY, element: null }
        ).element;
    }

    async function updateDirectorySortingOrders( sortingOrders ) {
        if ( ! Array.isArray( sortingOrders ) ) {
            return false;
        }
    
        const form_data = new FormData();
        
        form_data.append( 'action', 'update_directory_type_sorting_order' );
        form_data.append( 'directorist_nonce', directorist_admin.directorist_nonce );
        form_data.append( 'sorting_orders', JSON.stringify( sortingOrders ) );

        try {
            const response = await axios.post( directorist_admin.ajax_url, form_data );
    
            if ( response.data && response.data.status && response.data.status.success ) {
                return true;
            }
    
            return false;
        } catch ( error ) {
            console.error( error );
            return false;
        }
    }
});