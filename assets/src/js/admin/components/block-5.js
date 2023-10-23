window.addEventListener('DOMContentLoaded', () => {
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
        let findElmSlug = $(element).find('.directorist_listing-slug-text');

        // Store old slug value
        let slugWrapper = $(element).children('.directorist_listing-slug-text');
        let oldSlugVal = slugWrapper.attr('data-value');

        // Slug Edit
        slugWrapper.on('input keypress', function (e) {
            let slugText = $(this).text();
            $(this).attr('data-value', slugText);
            var setSlugBtn = $(this).siblings('.directorist-listing-slug-edit-wrap').children('.directorist_listing-slug-formText-add');
            $(this).attr('data-value') === '' ? setSlugBtn.addClass('disabled') : setSlugBtn.removeClass('disabled');
            if (e.key === 'Enter' && $(this).attr('data-value') !== '') {
                e.preventDefault();
                setSlugBtn.click();
            }
            if ($(this).attr('data-value') === '' && e.key === 'Enter') {
                e.preventDefault();
            }
        })

        // Edit Form Open
        $('body').on('click', '.directorist-listing-slug__edit', function (e) {
            e.preventDefault();
            $('.directorist_listing-slug-formText-remove').click()
            var editableSlug = $(this).closest('.directorist-listing-slug-edit-wrap').siblings('.directorist_listing-slug-text');
            editableSlug.attr('contenteditable', true);
            editableSlug.addClass('directorist_listing-slug-text--editable');
            $(this).hide();
            $(this).siblings('.directorist_listing-slug-formText-add').addClass('active');
            $(this).siblings('.directorist_listing-slug-formText-remove').removeClass('directorist_listing-slug-formText-remove--hidden');
            editableSlug.focus();
        });

        // edit directory type slug
        $(element).find('.directorist_listing-slug-formText-add').on('click', function (e) {
            e.preventDefault();
            var _this = $(this);
            var type_id = $(this).data('type-id');
            var update_slug = $('.directorist-slug-text-' + type_id).attr('data-value');
            oldSlugVal = slugWrapper.attr('data-value'); /* Update the slug values */
            const addSlug = $(this);
            let slugId = $('.directorist-slug-notice-' + type_id);
            let thisSiblings = $(_this).closest('.directorist-listing-slug-edit-wrap').siblings('.directorist_listing-slug-text');
            addSlug.closest('.directorist-listing-slug-edit-wrap').append(`<span class="directorist_loader"></span>`);

            $.ajax({
                type: 'post',
                url: directorist_admin.ajaxurl,
                data: {
                    action: 'directorist_type_slug_change',
                    directorist_nonce: directorist_admin.directorist_nonce,
                    type_id: type_id,
                    update_slug: update_slug
                },
                success(response) {
                    addSlug.closest('.directorist-listing-slug-edit-wrap')
                        .children('.directorist_loader')
                        .remove();
                    if (response) {
                        if (response.error) {
                            slugId.removeClass('directorist-slug-notice-success');
                            slugId.addClass('directorist-slug-notice-error');
                            slugId.empty().html(response.error);

                            if ( response.old_slug ) {
                                $('.directorist-slug-text-' + type_id).text(response.old_slug);
                            }

                            _this.siblings('.directorist-listing-slug__edit').show();
                            setTimeout(function () {
                                slugId.empty().html("");
                            }, 3000);
                        } else {
                            slugId.empty().html(response.success);
                            slugId.removeClass('directorist-slug-notice-error');
                            slugId.addClass('directorist-slug-notice-success');
                            _this.siblings('.directorist-listing-slug__edit').show();
                            setTimeout(function () {
                                addSlug
                                    .closest('.directorist-listing-slug__form')
                                    .css({
                                        "display": "none"
                                    })
                                slugId.html("");
                            }, 1500);
                        }
                    }

                    $(_this).removeClass('active');
                    $(_this).siblings('.directorist_listing-slug-formText-remove').addClass('directorist_listing-slug-formText-remove--hidden');
                    thisSiblings.removeClass('directorist_listing-slug-text--editable');
                    thisSiblings.attr('contenteditable', 'false');
                },
            });
        });

        // Edit Form Remove
        $(element).find('.directorist_listing-slug-formText-remove').on('click', function (e) {
            e.preventDefault()
            let thisClosestSibling = $(this).closest('.directorist-listing-slug-edit-wrap').siblings('.directorist_listing-slug-text');
            $(this).siblings('.directorist-listing-slug__edit').show();
            $(this).siblings('.directorist_listing-slug-formText-add').removeClass('active disabled');
            thisClosestSibling.removeClass('directorist_listing-slug-text--editable');
            thisClosestSibling.attr('contenteditable', 'false');
            $(this).addClass('directorist_listing-slug-formText-remove--hidden');
            thisClosestSibling.attr('data-value', oldSlugVal);
            thisClosestSibling.text(oldSlugVal);
        });

        // Hide Slug Form outside click
        $(document).on('click', function (e) {
            if (!e.target.closest('.directorist-type-slug')) {
                findElmSlug.attr('data-value', oldSlugVal);
                findElmSlug.text(oldSlugVal);
                findElmSlug.attr('contenteditable', 'false');
                findElmSlug.removeClass('directorist_listing-slug-text--editable');
                $(element).find('.directorist-listing-slug__edit').show();
                findElmSlug.siblings('.directorist-listing-slug-edit-wrap').children('.directorist_listing-slug-formText-add').removeClass('active disabled');
                findElmSlug.siblings('.directorist-listing-slug-edit-wrap').children('.directorist_listing-slug-formText-remove').addClass('directorist_listing-slug-formText-remove--hidden');
            }
        });

    })

    // View Count Changes
    $('.type-at_biz_dir').each(function (id, element) {
        let findElmCount = $(element).find('.directorist_listing-count-text');

        // Store old count value
        let countWrapper = $(element).find('.directorist_listing-count-text');
        let oldCountVal = countWrapper.attr('data-value');
        let validCount = !isNaN(countWrapper.attr('data-value')) ? countWrapper.attr('data-value') : '';

        // Count Edit
        countWrapper.on('input keypress keydown', function (e) {
            let countText = $(this).text();
            validCount = !isNaN(countText) ? countText : '';

            validCount && $(this).attr('data-value', countText);
            
            let setCountBtn = $(this).siblings('.directorist-listing-count-edit-wrap').children('.directorist_listing-count-formText-add');
            $(this).attr('data-value') === '' ? setCountBtn.addClass('disabled') : setCountBtn.removeClass('disabled');
            if (e.key === 'Enter' && $(this).attr('data-value') !== '' && validCount) {
                e.preventDefault();
                setCountBtn.click();
            }
            if ($(this).attr('data-value') === '' && e.key === 'Enter') {
                e.preventDefault();
            }
        })

        // Edit Form Open
        $('body').on('click', '.directorist-listing-count__edit', function (e) {
            e.preventDefault();
            $('.directorist_listing-count-formText-remove').click()
            let editableCount = $(this).closest('.directorist-listing-count-edit-wrap').siblings('.directorist_listing-count-text');
            editableCount.attr('contenteditable', true);
            editableCount.addClass('directorist_listing-count-text--editable');
            $(this).hide();
            $(this).siblings('.directorist_listing-count-formText-add').addClass('active');
            $(this).siblings('.directorist_listing-count-formText-remove').addClass('active');
            editableCount.focus();
        });

        // edit directory type count
        $(element).find('.directorist_listing-count-formText-add').on('click', function (e) {
            e.preventDefault();
            let addCount     = $(this);
            let count_id     = addCount.data('type-id');
            let update_count = $('.directorist-count-text-' + count_id).text();
                oldCountVal  = countWrapper.attr('data-value'); /* Update the slug values */
                validCount = !isNaN(update_count) ? update_count : '';

            let countId      = $('.directorist-count-notice-' + count_id);
            let thisSiblings = addCount.closest('.directorist-listing-count-edit-wrap').siblings('.directorist_listing-count-text');

            validCount && addCount.closest('.directorist-listing-count-edit-wrap').append(`<span class="directorist_loader"></span>`);
            validCount && $.ajax({
                type: 'post',
                url : directorist_admin.ajaxurl,
                data: {
                    action           : 'directorist_view_count_change',
                    directorist_nonce: directorist_admin.directorist_nonce,
                    listing_id       : count_id,
                    view_count       : update_count
                },
                success(response) {
                    addCount.closest('.directorist-listing-count-edit-wrap')
                        .children('.directorist_loader')
                        .remove();
                    if (response) {
                        if (response.error) {
                            countId.removeClass('directorist-count-notice-success');
                            countId.addClass('directorist-count-notice-error');
                            countId.empty().html(response.error);

                            if ( response.old_slug ) {
                                $('.directorist-count-text-' + listing_id).text(response.old_slug);
                            }

                            addCount.siblings('.directorist-listing-count__edit').show();
                            setTimeout(function () {
                                countId.empty().html("");
                            }, 3000);
                        } else {
                            countId.empty().html(response.success ? response.data.success : '');
                            countId.removeClass('directorist-count-notice-error');
                            countId.addClass('directorist-count-notice-success');
                            addCount.siblings('.directorist-listing-count__edit').show();
                            setTimeout(function () {
                                addCount
                                    .closest('.directorist-listing-count__form')
                                    .css({
                                        "display": "none"
                                    })
                                countId.html("");
                            }, 1500);
                        }
                    }

                    $(addCount).removeClass('active');
                    $(addCount).siblings('.directorist_listing-count-formText-remove').removeClass('active');
                    thisSiblings.removeClass('directorist_listing-count-text--editable');
                    thisSiblings.attr('contenteditable', 'false');
                },
            });
        });

        // Edit Form Remove
        $(element).find('.directorist_listing-count-formText-remove').on('click', function (e) {
            e.preventDefault()
            let thisClosestSibling = $(this).closest('.directorist-listing-count-edit-wrap').siblings('.directorist_listing-count-text');
            $(this).siblings('.directorist-listing-count__edit').show();
            $(this).siblings('.directorist_listing-count-formText-add').removeClass('active disabled');
            thisClosestSibling.removeClass('directorist_listing-count-text--editable');
            thisClosestSibling.attr('contenteditable', 'false');
            $(this).removeClass('active');
            thisClosestSibling.attr('data-value', oldCountVal ? oldCountVal : '0');
            thisClosestSibling.text(oldCountVal ? oldCountVal : '0');
        });

        // Hide Slug Form outside click
        $(document).on('click', function (e) {
            if (!e.target.closest('.directorist-view-count')) {
                findElmCount.attr('data-value', oldCountVal);
                findElmCount.text(oldCountVal);
                findElmCount.attr('contenteditable', 'false');
                findElmCount.removeClass('directorist_listing-count-text--editable');
                $(element).find('.directorist-listing-count__edit').show();
                findElmCount.siblings('.directorist-listing-count-edit-wrap').children('.directorist_listing-count-formText-add').removeClass('active disabled');
                findElmCount.siblings('.directorist-listing-count-edit-wrap').children('.directorist_listing-count-formText-remove').removeClass('active');
            }
        });

    })

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

    $('body').on('click', '.directorist-view-count-change', function (e) {
        e.preventDefault();
        var _this = $(this);
        var listing_id = _this.data('listing-id');
        var view_count = _this.data('view-count');

        $.ajax({
            type: 'post',
            url : directorist_admin.ajaxurl,
            data: {
                action           : 'directorist_view_count_change',
                directorist_nonce: directorist_admin.directorist_nonce,
                listing_id       : listing_id,
                view_count       : view_count,
            },
            success( response ) {
                if( response.data ) {
                    $('.directorist-view-count span').empty().html( response.data.view_count );
                    console.log( response.data.success );
                }
            },
        });
    });
});