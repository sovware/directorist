import debounce from '../global/components/debounce';
import './../global/components/select2-custom-control';
import './../global/components/setup-select2';
import initSearchCategoryCustomFields from './components/category-custom-fields';
import './components/colorPicker';
import './components/directoristDropdown';
import './components/directoristSelect';

(function ($) {
    window.addEventListener('load', () => {
        //Remove Preload after Window Load
        $(window).on('load', function () {
            $('body').removeClass("directorist-preload");
            $('.button.wp-color-result').attr('style', ' ');
        });

        /* ----------------
        Search Form
        ------------------ */

        // Default Tags Slice
        function defaultTags() {
            $('.directorist-btn-ml').each((index, element) => {
                let item = $(element).siblings('.atbdp_cf_checkbox, .directorist-search-field-tag, .directorist-search-tags');
                let item_checkbox = $(item).find('.directorist-checkbox');
                $(item_checkbox).slice(4, item_checkbox.length).fadeOut();
                if(item_checkbox.length <= 4){
                    $(element).css('display', 'none');
                }
            });
        }

        defaultTags();
        window.addEventListener('triggerSlice', defaultTags);

        // See More Tags Button
        $('body').on('click', '.directorist-btn-ml', function (event) {
            event.preventDefault();
            let item = $(this).siblings('.directorist-search-tags');
            let item_checkbox = $(item).find('.directorist-checkbox');
            $(item_checkbox).slice(4, item_checkbox.length).fadeOut();

            $(this).toggleClass('active');

            if ($(this).hasClass('active')) {
                $(this).text(directorist.i18n_text.show_less);
                $(item_checkbox).slice(4, item_checkbox.length).fadeIn();
            } else {
                $(this).text(directorist.i18n_text.show_more);
                $(item_checkbox).slice(4, item_checkbox.length).fadeOut();
            }

        });

        // Search Form

        // Count Checkbox Selected Items
        function selectedItemCount(item) {
            let dropdownParent = $(item).closest('.directorist-search-field');
            let dropDownContent = $(item).closest('.directorist-search-basic-dropdown-content');
            let selectedItemCount = dropDownContent.find('.directorist-checkbox input[type="checkbox"]:checked');
            let selectedPrefix = dropDownContent.siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-prefix');
            let selectedCounter = dropDownContent.siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-count');

            if (selectedItemCount.length > 0) {
                selectedCounter.text(selectedItemCount.length);
                selectedPrefix.text('Selected');
                dropdownParent.addClass('input-has-value');
            } else {
                // If no items are checked, clear the text
                selectedCounter.text('');
                selectedPrefix.text('');
                dropdownParent.removeClass('input-has-value');
            }
        }

        // Radio Selected Items
        function selectedRadioItem(item) {
            let dropdownParent = $(item).closest('.directorist-search-field');
            let dropDownLabel = dropdownParent.find('.directorist-search-basic-dropdown-selected-item');
            let selectedItem = dropdownParent.find('.directorist-radio input[type="radio"]:checked');
            let selectedItemLabel = selectedItem.siblings('.directorist-radio__label').text();

            if (selectedItem) {
                dropDownLabel.text(' - ' + selectedItemLabel);
                dropdownParent.addClass('input-has-value');
            } else {
                // If no items are checked, clear the text
                selectedItem.text('');
                dropdownParent.removeClass('input-has-value');
            }
        }

        // Checkbox Field Check
        $('body').on('change', '.directorist-search-form__top .directorist-search-basic-dropdown input[type="checkbox"], .directorist-search-modal .directorist-search-basic-dropdown input[type="checkbox"]', function (e) {
            e.preventDefault();

            selectedItemCount(this);
        });

        // Radio Field Check
        $('body').on('change', '.directorist-search-form__top .directorist-search-basic-dropdown input[type="radio"], .directorist-search-modal .directorist-search-basic-dropdown input[type="radio"]', function (e) {
            e.preventDefault();

            selectedRadioItem(this);
        });

        // Basic Search Dropdown Toggle
        $('body').on('click', '.directorist-search-form__top .directorist-search-basic-dropdown-label, .directorist-search-modal .directorist-search-basic-dropdown-label', function (e) {
            e.preventDefault();
            let dropDownParent = $(this).closest('.directorist-search-field');
            let dropDownContent = $(this).siblings('.directorist-search-basic-dropdown-content');

            dropDownContent.toggleClass('dropdown-content-show');
            dropDownContent.slideToggle().show();

            if (dropDownContent.hasClass('dropdown-content-show')) {
                dropDownParent.addClass('input-is-focused');
            } else {
                dropDownParent.removeClass('input-is-focused');
            }
            // Hide all other open contents
            $('.directorist-search-basic-dropdown-content.dropdown-content-show').not(dropDownContent).removeClass('dropdown-content-show').slideUp();
        });

        // Dropdown Content Hide on Outside Click
        $('body').on('click', function (e) {
            let dropDownRoot = $(e.target).closest('.directorist-search-form-dropdown');
            let dropDownParent = $('.directorist-search-form-dropdown.input-is-focused');
            let dropDownContent = $('.directorist-search-basic-dropdown-content.dropdown-content-show');

            if (!dropDownRoot.length) {
                dropDownParent.removeClass('input-is-focused');
                dropDownContent.removeClass('dropdown-content-show');
                dropDownContent.slideUp();
            }
        });

        // Check Empty Search Fields on Search Modal
        function initSearchFields() {
            // const inputFields = document.querySelectorAll('.directorist-search-modal__input');

            // if ( inputFields.length ) {
            //     inputFields.forEach( inputField => {
            //         if ( ! inputField.children.length ) {
            //             inputField.remove();
            //         }
            //     } );
            // }

            const searchFields = document.querySelectorAll('.directorist-search-field__input:not(.directorist-search-basic-dropdown)');

            searchFields.forEach( searchField => {
                let inputFieldValue = searchField.value;

                if ( searchField.classList.contains('directorist-select') ) {
                    inputFieldValue = searchField.querySelector('select').dataset.selectedId;
                }

                if ( inputFieldValue !== '' ) {
                    searchField.parentElement.classList.add('input-has-value');

                    if ( ! searchField.parentElement.classList.contains('input-is-focused') ) {
                        searchField.parentElement.classList.add('input-is-focused');
                    }
                } else {
                    inputFieldValue = ''

                    if ( searchField.parentElement.classList.contains('input-has-value') ) {
                        searchField.parentElement.classList.remove('input-has-value');
                    }
                }
            });
        }

        initSearchFields();

        // Search Form Reset Button Initialize
        function initForm(searchForm) {
            let value = false;

            searchForm.querySelectorAll("input:not([type='checkbox']):not([type='radio']):not([type='hidden'])").forEach(function (el) {
                if (el.value !== "") {
                    value = true;
                }
            });

            searchForm.querySelectorAll("input[type='checkbox'], input[type='radio']").forEach(function (el) {
                if (el.checked) {
                    value = true;
                }
            });

            searchForm.querySelectorAll("select").forEach(function (el) {
                if (el.value || el.selectedIndex !== 0 ) {
                    value = true;
                }
            });

            searchForm.querySelectorAll(".directorist-custom-range-slider__value input").forEach(function (el) {
                if (el.value > 0 ) {
                    value = true;
                }
            });

            if (!value) {
                let resetButtonWrapper = searchForm.querySelector('.directorist-advanced-filter__action');
                resetButtonWrapper && resetButtonWrapper.classList.add('reset-btn-disabled');
            }

        }

        // Enable Reset Button
        function enableResetButton(searchForm) {
            let resetButtonWrapper = searchForm.querySelector('.directorist-advanced-filter__action');
            resetButtonWrapper && resetButtonWrapper.classList.remove('reset-btn-disabled');
        }

        // Initialize Form Reset Button
        let searchForm = document.querySelectorAll('.directorist-contents-wrap form');
        searchForm.forEach((form) => {
            setTimeout(function(){
                initForm(form);
            }, 100)
        })

        // Input Field Check
        $('body').on('keyup', '.directorist-contents-wrap form input:not([type="checkbox"]):not([type="radio"])', function (e) {
            let searchForm = this.closest('form');

            if(this.value && this.value !== 0 && this.value !== undefined) {
                enableResetButton(searchForm);
            } else {
                setTimeout(function(){
                    initForm(searchForm)
                }, 100)
            }
        })

        $('body').on('change', '.directorist-contents-wrap form input[type="checkbox"], .directorist-contents-wrap form input[type="radio"]', function (e) {
            let searchForm = this.closest('form');

            if(this.checked) {
                enableResetButton(searchForm);
            } else {
                setTimeout(function(){
                    initForm(searchForm)
                }, 100)
            }
        })

        $('body').on('change', '.directorist-contents-wrap form select', function (e) {
            let searchForm = this.closest('form');

            if(this.value !== undefined) {
                enableResetButton(searchForm);
            } else {
                setTimeout(function(){
                    initForm(searchForm)
                }, 100)
            }
        })

        // Searchform Reset
        function adsFormReset(searchForm) {
            searchForm.querySelectorAll("input[type='text']").forEach(function (el) {
                el.value = "";

                if (el.parentElement.classList.contains('input-has-value') || el.parentElement.classList.contains('input-is-focused')) {
                    el.parentElement.classList.remove('input-has-value', 'input-is-focused');
                }
            });
            searchForm.querySelectorAll("input[type='date']").forEach(function (el) {
                el.value = "";
            });
            searchForm.querySelectorAll("input[type='time']").forEach(function (el) {
                el.value = "";
            });
            searchForm.querySelectorAll("input[type='url']").forEach(function (el) {
                el.value = "";

                if (el.parentElement.classList.contains('input-has-value') || el.parentElement.classList.contains('input-is-focused')) {
                    el.parentElement.classList.remove('input-has-value', 'input-is-focused');
                }
            });
            searchForm.querySelectorAll("input[type='number']").forEach(function (el) {
                el.value = "";

                if (el.parentElement.classList.contains('input-has-value') || el.parentElement.classList.contains('input-is-focused')) {
                    el.parentElement.classList.remove('input-has-value', 'input-is-focused');
                }
            });
            searchForm.querySelectorAll("input[type='hidden']:not(.listing_type)").forEach(function (el) {
                if(el.getAttribute('name') === "directory_type") return;
                el.value = "";
            });
            searchForm.querySelectorAll("input[type='radio']").forEach(function (el) {
                el.checked = false;
            });
            searchForm.querySelectorAll("input[type='checkbox']").forEach(function (el) {
                el.checked = false;
            });
            searchForm.querySelectorAll("select").forEach(function (el) {
                el.selectedIndex = 0;
                $('.directorist-select2-dropdown-close').click();

                let parentElem = el.closest('.directorist-search-field');

                if (parentElem.classList.contains('input-has-value') || parentElem.classList.contains('input-is-focused')) {
                    setTimeout(function(){
                        parentElem.classList.remove('input-has-value', 'input-is-focused');
                    }, 100)
                }
            });

            let customRangeSliders = document.querySelectorAll('.directorist-custom-range-slider');

            customRangeSliders.forEach(function (sliderItem) {
                resetCustomRangeSlider(sliderItem);
            });

            searchForm.querySelectorAll(".directorist-search-basic-dropdown-content").forEach((dropdown) => {
                let dropDownParent = dropdown.closest('.directorist-search-field');

                $(dropdown).siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-count').text('');
                $(dropdown).siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-prefix').text('');

                if (dropDownParent.classList.contains('input-has-value') || dropDownParent.classList.contains('input-is-focused')) {
                    dropDownParent.classList.remove('input-has-value', 'input-is-focused');
                }
            })



            let irisPicker = searchForm.querySelector("input.wp-picker-clear");
            if (irisPicker !== null) {
                irisPicker.click();
            }

            handleRadiusVisibility();

            initForm(searchForm);

        }

        // Searchform Reset Trigger
        if ($('.directorist-btn-reset-js') !== null) {
            $('body').on('click', '.directorist-btn-reset-js', function (e) {
                e.preventDefault();
                if (this.closest('.directorist-contents-wrap')) {
                    let searchForm = this.closest('.directorist-contents-wrap').querySelector('.directorist-search-form');
                    if (searchForm) {
                        adsFormReset(searchForm);
                    }
                    let advanceSearchForm = this.closest('.directorist-contents-wrap').querySelector('.directorist-advanced-filter__form');
                    if (advanceSearchForm) {
                        adsFormReset(advanceSearchForm);
                    }
                    let advanceSearchFilter = this.closest('.directorist-contents-wrap').querySelector('.directorist-advanced-filter__advanced');
                    if (advanceSearchFilter) {
                        adsFormReset(advanceSearchFilter);
                    }
                }
            });
        }

        // Search Modal Open
        function searchModalOpen(searchModalParent) {
            let modalOverlay = searchModalParent.querySelector('.directorist-search-modal__overlay');
            let modalContent = searchModalParent.querySelector('.directorist-search-modal__contents');

            // Overlay Style
            modalOverlay.style.cssText = "opacity: 1; visibility: visible; transition: 0.3s ease;";

            // Modal Content Style
            modalContent.style.cssText = "opacity: 1; visibility: visible; bottom:0;";
        }

        // Search Modal Close
        function searchModalClose(searchModalParent) {
            let modalOverlay = searchModalParent.querySelector('.directorist-search-modal__overlay');
            let modalContent = searchModalParent.querySelector('.directorist-search-modal__contents');

            // Overlay Style
            if(modalOverlay) {
                modalOverlay.style.cssText = "opacity: 0; visibility: hidden; transition: 0.5s ease";
            }

            // Modal Content Style
            if(modalContent) {
                modalContent.style.cssText = "opacity: 0; visibility: hidden; bottom: -200px;";
            }
        }

        // Search Modal Minimizer
        function searchModalMinimize(searchModalParent) {
            let modalContent = searchModalParent.querySelector('.directorist-search-modal__contents');
            let modalMinimizer = searchModalParent.querySelector('.directorist-search-modal__minimizer');

            if(modalMinimizer.classList.contains('minimized')) {
                modalMinimizer.classList.remove('minimized');
                modalContent.style.bottom = '0';
            } else {
                modalMinimizer.classList.add('minimized');
                modalContent.style.bottom = '-50%';
            }
        }

        // Search Modal Open Trigger
        $('body').on('click', '.directorist-modal-btn', function (e) {
            e.preventDefault();

            let parentElement = this.closest('.directorist-contents-wrap');

            if(this.classList.contains('directorist-modal-btn--basic')) {
                let searchModalElement = parentElement.querySelector('.directorist-search-modal--basic');

                searchModalOpen(searchModalElement)
            }
            if(this.classList.contains('directorist-modal-btn--advanced')) {
                let searchModalElement = parentElement.querySelector('.directorist-search-modal--advanced');

                searchModalOpen(searchModalElement)
            }
            if(this.classList.contains('directorist-modal-btn--full')) {
                let searchModalElement = parentElement.querySelector('.directorist-search-modal--full');

                searchModalOpen(searchModalElement)
            }

        });

        // Search Modal Close Trigger
        $('body').on('click', '.directorist-search-modal__contents__btn--close, .directorist-search-modal__overlay', function (e) {
            e.preventDefault();

            let searchModalElement = this.closest('.directorist-search-modal');

            searchModalClose(searchModalElement)
        });

        // Search Modal Minimizer Trigger
        $('body').on('click', '.directorist-search-modal__minimizer', function (e) {
            e.preventDefault();

            let searchModalElement = this.closest('.directorist-search-modal');

            searchModalMinimize(searchModalElement)
        });

        // Search Field Input Value Check
        function inputValueCheck(e, searchField) {
            searchField = searchField[0];

            let inputBox = searchField.querySelector('.directorist-search-field__input:not(.directorist-search-basic-dropdown)');
            let inputFieldValue = inputBox && inputBox.value;

            if (inputFieldValue) {
                searchField.classList.add('input-has-value');
                if(!searchField.classList.contains('input-is-focused')) {
                    searchField.classList.add('input-is-focused');
                }
            } else {
                inputFieldValue = ''
                if(searchField.classList.contains('input-has-value')) {
                    searchField.classList.remove('input-has-value');
                }
            }
        }

        // Search Field Input Event Check
        function inputEventCheck(e, searchField) {
            searchField = searchField[0];

            let inputBox = searchField.querySelector('.directorist-search-field__input:not(.directorist-search-basic-dropdown)');
            let inputFieldValue = inputBox.value;

            if (e.type === 'focusin') {
                searchField.classList.add('input-is-focused');
            } else if (e.type === 'focusout') {
                if(inputBox.classList.contains('directorist-select')) {
                    selectFocusOutCheck(searchField, inputBox);
                } else {
                    if(inputFieldValue) {
                        searchField.classList.add('input-has-value');
                        if (!searchField.classList.contains('input-is-focused')) {
                            searchField.classList.add('input-is-focused');
                        }
                    } else {
                        searchField.classList.remove('input-is-focused');
                    }
                }
            }

        }

        // Search Field Input Focusout Event Check
        function selectFocusOutCheck(searchField, inputBox) {
            searchField.classList.add('input-is-focused');
            let inputFieldValue = inputBox.querySelector('select').value;

            $('body').one('click', function(e) {
                inputFieldValue = inputBox.querySelector('select').value;
                let parentWithClass = e.target.closest('.directorist-search-field__input:not(.directorist-search-basic-dropdown)');

                if (!parentWithClass) {
                    if(inputFieldValue) {
                        searchField.classList.add('input-has-value');
                        if (!searchField.classList.contains('input-is-focused')) {
                            searchField.classList.add('input-is-focused');
                        }
                    } else {
                        searchField.classList.remove('input-is-focused');
                    }
                }

            });
        }

        // Search Form Input Field Check Trigger
        $('body').on('input keyup change', '.directorist-search-field__input:not(.directorist-search-basic-dropdown)', function(e) {
            let searchField = $(this).closest('.directorist-search-field');

            inputValueCheck(e, searchField);

        });

        $('body').on('focus blur', '.directorist-search-field__input:not(.directorist-search-basic-dropdown)', function(e) {
            let searchField = $(this).closest('.directorist-search-field');

            inputEventCheck(e, searchField);

        });

        // Search Form Input Clear Button
        $('body').on('click', '.directorist-search-field__btn--clear', function(e) {
            let inputFields = this.parentElement.querySelectorAll('.directorist-form-element');
            let selectboxField = this.parentElement.querySelector('.directorist-select select');
            let basicDropdown = this.parentElement.querySelectorAll('.directorist-search-basic-dropdown-content');
            let radioFields = this.parentElement.querySelectorAll('input[type="radio"]');
            let checkboxFields = this.parentElement.querySelectorAll('input[type="checkbox"]');

            if (selectboxField) {
                selectboxField.selectedIndex = 0;
                selectboxField.dispatchEvent(new Event('change'));

                $(selectboxField).trigger('change');
            }
            if (inputFields) {
                inputFields.forEach((inputField) => {
                    inputField.value = '';
                })
            }
            if(radioFields) {
                radioFields.forEach((element) => {
                    element.checked = false;
                })
            }
            if(checkboxFields) {
                checkboxFields.forEach((element) => {
                    element.checked = false;
                })
            }

            if (basicDropdown) {
                basicDropdown.forEach((dropdown) => {
                    $(dropdown).slideUp();
                    $(dropdown).siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-count').text('');
                    $(dropdown).siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-prefix').text('');
                    $(dropdown).siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-item').text('');
                })
            }

            if (this.parentElement.classList.contains('input-has-value') || this.parentElement.classList.contains('input-is-focused')) {
                this.parentElement.classList.remove('input-has-value', 'input-is-focused');
            }

            handleRadiusVisibility();

            // Reset Button Disable
            let searchform = this.closest('form');
            let inputValue = $(this).parent('.directorist-search-field').find('.directorist-search-field__input:not(.directorist-search-basic-dropdown)').val();
            let selectValue = $(this).parent('.directorist-search-field').find('.directorist-search-field__input select:not(.directorist-search-basic-dropdown)').val();

            if(inputValue && inputValue !== 0 && inputValue !== undefined || selectValue && selectValue.selectedIndex === 0 ||  selectValue && selectValue.selectedIndex !== undefined) {
                enableResetButton(searchform);
            } else {
                setTimeout(function(){
                    initForm(searchform)
                }, 100)
            }

        });

        // Search Form Input Field Back Button
        $('body').on('click', '.directorist-search-field__label:not(.directorist-search-basic-dropdown-label)', function(e) {
            let windowScreen = window.innerWidth;
            let parentField = this.closest('.directorist-search-field');

            if (windowScreen <= 575) {
                if(parentField.classList.contains('input-is-focused')) {
                    parentField.classList.remove('input-is-focused');
                }
            }
        })

        // Listing Type Change
        $('body').on('click', '.search_listing_types', function (event) {
            event.preventDefault();
            let parent = $(this).closest('.directorist-search-contents');
            let listing_type = $(this).attr('data-listing_type');
            let type_current = parent.find('.directorist-listing-type-selection__link--current');

            if (type_current.length) {
                type_current.removeClass('directorist-listing-type-selection__link--current');
                $(this).addClass('directorist-listing-type-selection__link--current');
            }

            parent.find('.listing_type').val(listing_type);

            let form_data = new FormData();
            form_data.append('action', 'atbdp_listing_types_form');
            form_data.append('nonce', directorist.directorist_nonce);
            form_data.append('listing_type', listing_type);

            let atts = parent.attr('data-atts');
            let atts_decoded = btoa(atts);

            form_data.append('atts', atts_decoded);

            parent.find('.directorist-search-form-box').addClass('atbdp-form-fade');

            $.ajax({
                method: 'POST',
                processData: false,
                contentType: false,
                url: directorist.ajax_url,
                data: form_data,
                success(response) {
                    if (response) {
                        // Add Temp Element
                        let new_inserted_elm = '<div class="directorist_search_temp"><div>';
                        parent.before(new_inserted_elm);

                        // Remove Old Parent
                        parent.remove();

                        // Insert New Parent
                        $('.directorist_search_temp').after(response['search_form']);
                        let newParent = $('.directorist_search_temp').next();


                        // Toggle Active Class
                        newParent.find('.directorist-listing-type-selection__link--current').removeClass('directorist-listing-type-selection__link--current');
                        newParent.find("[data-listing_type='" + listing_type + "']").addClass('directorist-listing-type-selection__link--current');

                        // Remove Temp Element
                        $('.directorist_search_temp').remove();

                        let events = [
                            new CustomEvent('directorist-search-form-nav-tab-reloaded'),
                            new CustomEvent('directorist-reload-select2-fields'),
                            new CustomEvent('directorist-reload-map-api-field'),
                            new CustomEvent('triggerSlice'),
                        ];

                        events.forEach(event => {
                            document.body.dispatchEvent(event);
                            window.dispatchEvent(event);
                        });

                        handleRadiusVisibility();
                        directorist_custom_range_slider();

                        initSearchFields();

                        initSearchCategoryCustomFields($);
                    }

                    let parentAfterAjax = $(this).closest('.directorist-search-contents');

                    parentAfterAjax.find('.directorist-search-form-box').removeClass('atbdp-form-fade');
                    if(parentAfterAjax.find('.directorist-search-form-box').find('.directorist-search-field-radius_search').length){
                        handleRadiusVisibility()
                        directorist_custom_range_slider();
                    }
                },
                error(error) {
                    // console.log(error);
                }
            });
        });

        initSearchCategoryCustomFields($);

        // Back Button to go back to the previous page
        $('body').on('click', '.directorist-btn__back', function(e) {
            e.preventDefault();

            window.history.back();
        });

        // Radius Search Field Hide on Empty Location Field
        function handleRadiusVisibility(){
            $('.directorist-range-slider-wrap').closest('.directorist-search-field').addClass('directorist-search-field-radius_search');
            $('.directorist-location-js').each((index,locationDOM)=>{
                if($(locationDOM).val() === ''){
                    $(locationDOM).closest('.directorist-contents-wrap').find('.directorist-search-field-radius_search, .directorist-radius-search').css({display: "none"});
                } else{
                    $(locationDOM).closest('.directorist-contents-wrap').find('.directorist-search-field-radius_search, .directorist-radius-search').css({display: "block"});
                }
            });
        }

        // handleRadiusVisibility Trigger
        $('body').on('keyup keydown input change focus', '.directorist-location-js, .zip-radius-search', function (e) {
            handleRadiusVisibility();
        });

        // Hide Country Result Click on Outside of Zipcode Field
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.directorist-zip-code').length) {
                $('.directorist-country').hide();
            }
        });

        $('body').on('click', '.directorist-country ul li a', function (event) {
            event.preventDefault();
            let zipcode_search  = $(this).closest('.directorist-zipcode-search');

            let lat = $(this).data('lat');
            let lon = $(this).data('lon');

            zipcode_search.find('.zip-cityLat').val(lat);
            zipcode_search.find('.zip-cityLng').val(lon);

            $('.directorist-country').hide();
        });

        $('.address_result').hide();

        // Init Location
        init_map_api_field();
        document.body.addEventListener('directorist-reload-map-api-field', init_map_api_field);

        function init_map_api_field() {

            if (directorist.i18n_text.select_listing_map === 'google') {

                function initialize() {
                    let opt = {
                        types: ['geocode'],
                        componentRestrictions: {
                            country: directorist.restricted_countries
                        },
                    };
                    let options = directorist.countryRestriction ? opt : '';

                    let input_fields = [{
                            input_class: '.directorist-location-js',
                            lat_id: 'cityLat',
                            lng_id: 'cityLng',
                            options
                        },
                        {
                            input_id: 'address_widget',
                            lat_id: 'cityLat',
                            lng_id: 'cityLng',
                            options
                        },
                    ];

                    let setupAutocomplete = function (field) {
                        let input = document.querySelectorAll(field.input_class);
                        input.forEach(elm => {
                            if (!elm) {
                                return;
                            }
                            let autocomplete = new google.maps.places.Autocomplete(elm, field.options);

                            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                                let place = autocomplete.getPlace();
                                elm.closest('.directorist-search-field').querySelector(`#${field.lat_id}`).value = place.geometry.location.lat();
                                elm.closest('.directorist-search-field').querySelector(`#${field.lng_id}`).value = place.geometry.location.lng();
                            });
                        })
                    };

                    input_fields.forEach(field => {
                        setupAutocomplete(field);
                    });
                }

                initialize();

            } else if (directorist.i18n_text.select_listing_map === 'openstreet') {

                let getResultContainer = function (context, field) {
                    return $(context).next(field.search_result_elm);
                };

                let getWidgetResultContainer = function (context, field) {
                    return $(context).parent().next(field.search_result_elm);
                };

                let input_fields = [{
                        input_elm: '.directorist-location-js',
                        search_result_elm: '.address_result',
                        getResultContainer
                    },
                    {
                        input_elm: '#q_addressss',
                        search_result_elm: '.address_result',
                        getResultContainer
                    },
                    {
                        input_elm: '.atbdp-search-address',
                        search_result_elm: '.address_result',
                        getResultContainer
                    },
                    {
                        input_elm: '#address_widget',
                        search_result_elm: '#address_widget_result',
                        getResultContainer: getWidgetResultContainer
                    },
                ];

                input_fields.forEach(field => {

                    if (!$(field.input_elm).length) {
                        return;
                    }

                    $(field.input_elm).on('keyup', debounce(function (event) {
                        event.preventDefault();

                        let blockedKeyCodes = [16, 17, 18, 19, 20, 27, 33, 34, 35, 36, 37, 38, 39, 40, 45, 91, 93, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 144, 145];

                        // Return early when blocked key is pressed.
                        if (blockedKeyCodes.includes(event.keyCode)) {
                            return;
                        }

                        let locationAddressField = $(this).parent('.directorist-search-field');
                        let result_container = field.getResultContainer(this, field);
                        let search = $(this).val();

                        if (search.length < 3) {
                            result_container.css({
                                display: 'none'
                            });
                        } else {
                            locationAddressField.addClass('atbdp-form-fade');
                            result_container.css({
                                display: 'block'
                            });

                            $.ajax({
                                url: "https://nominatim.openstreetmap.org/?q=%27+".concat(search, "+%27&format=json"),
                                type: 'GET',
                                data: {},
                                success: function success(data) {
                                    let res = '';

                                    let currentIconURL = directorist.assets_url + 'icons/font-awesome/svgs/solid/paper-plane.svg';
                                    let currentIconHTML = directorist.icon_markup.replace('##URL##', currentIconURL).replace('##CLASS##', '');
                                    let currentLocationIconHTML = "<span class='location-icon'>" + currentIconHTML + "</span>";
                                    let currentLocationAddressHTML = "<span class='location-address'></span>";

                                    let iconURL = directorist.assets_url + 'icons/font-awesome/svgs/solid/map-marker-alt.svg';
                                    let iconHTML = directorist.icon_markup.replace('##URL##', iconURL).replace('##CLASS##', '');
                                    let locationIconHTML = "<span class='location-icon'>"+ iconHTML +"</span>";

                                    for (let i = 0, len = data.length > 5 ? 5 : data.length; i < len; i++) {
                                        res += "<li><a href=\"#\" data-lat=" + data[i].lat +" data-lon=" + data[i].lon + ">" + locationIconHTML + "<span class='location-address'>" + data[i].display_name, + "</span></a></li>";
                                    }

                                    function displayLocation(position, event) {
                                        let lat = position.coords.latitude;
                                        let lng = position.coords.longitude;
                                        $.ajax({
                                          url: "https://nominatim.openstreetmap.org/reverse?format=json&lon="+lng+"&lat="+lat,
                                          type: 'GET',
                                          data: {},
                                          success: function success(data) {
                                            $('.directorist-location-js, .atbdp-search-address').val(data.display_name);
                                            $('.directorist-location-js, .atbdp-search-address').attr("data-value", data.display_name);
                                            $('#cityLat').val(lat);
                                            $('#cityLng').val(lng);
                                          }
                                        });
                                    }

                                    result_container.html("<ul>" +
                                        "<li><a href='#' class='current-location'>" +
                                        currentLocationIconHTML + currentLocationAddressHTML +
                                        "</a></li>" +
                                        res +
                                        "</ul>");
                                        if (res.length) {
                                            result_container.show();
                                        } else {
                                            result_container.hide();
                                    }

                                    locationAddressField.removeClass('atbdp-form-fade');

                                    $('body').on("click", '.address_result .current-location', function (e) {
                                        navigator.geolocation.getCurrentPosition(function (position) {
                                            return displayLocation(position, e);
                                        });
                                    });
                                },
                                error: function error(_error3) {
                                    console.log({
                                        error: _error3
                                    });
                                    locationAddressField.removeClass('atbdp-form-fade');
                                }
                            });
                        }
                    }, 750));
                });

                // hide address result when click outside the input field
                $(document).on('click', function (e) {
                    if (!$(e.target).closest('.directorist-location-js, #q_addressss, .atbdp-search-address').length) {
                        $('.address_result').hide();
                    }
                });

                let syncLatLngData = function (context, event, args) {
                    event.preventDefault();
                    let text = $(context).text();
                    let lat = $(context).data('lat');
                    let lon = $(context).data('lon');
                    let _this = event.target;
                    $(_this).closest('.address_result').siblings('input[name="cityLat"]').val(lat);
                    $(_this).closest('.address_result').siblings('input[name="cityLng"]').val(lon);
                    let inp = $(context)
                        .closest(args.result_list_container)
                        .parent()
                        .find('.directorist-location-js, #address_widget, #q_addressss, .atbdp-search-address');
                    inp.val(text);
                    $(args.result_list_container).hide();
                };


                $('body').on('click', '.address_result ul li a', function (event) {
                    syncLatLngData(this, event, {
                        result_list_container: '.address_result'
                    });
                });

                $('body').on('click', '#address_widget_result ul li a', function (event) {
                    syncLatLngData(this, event, {
                        result_list_container: '#address_widget_result'
                    });
                });
            }


            if ($('.directorist-location-js, #q_addressss, .atbdp-search-address').val() === '') {
                $(this)
                    .parent()
                    .next('.address_result')
                    .css({
                        display: 'none'
                    });
            }
        }

        $(".directorist-search-contents").each(function () {
            if($(this).next().length === 0){
                $(this).find(".directorist-search-country").css("max-height","175px");
                $(this).find(".directorist-search-field .address_result").css("max-height","175px");
            }
        });

        // Custom Range Slider
        function directorist_custom_range_slider() {
            let sliders = document.querySelectorAll('.directorist-custom-range-slider');

            sliders.forEach(function (sliderItem) {
                let slider = sliderItem.querySelector('.directorist-custom-range-slider__slide');

                if (slider) {
                    let sliderStep = parseInt(slider.getAttribute('step')) || 1;
                    let sliderDefaultValue = parseInt(slider.getAttribute('value'));
                    let minInput = sliderItem.querySelector('.directorist-custom-range-slider__value__min');
                    let maxInput = sliderItem.querySelector('.directorist-custom-range-slider__value__max');
                    let sliderRange = sliderItem.querySelector('.directorist-custom-range-slider__range');
                    let sliderRangeShow = sliderItem.querySelector('.directorist-custom-range-slider__range__show');

                    directoristCustomRangeSlider?.create(slider, {
                        start: [0, sliderDefaultValue ? sliderDefaultValue : 100],
                        connect: true,
                        step: sliderStep ? sliderStep : 1,
                        range: {
                            'min': Number(minInput.value ? minInput.value : 0),
                            'max': Number(maxInput.value ? maxInput.value : 100)
                        }
                    });

                    slider.directoristCustomRangeSlider?.on('update', function (values, handle) {
                        let value = values[handle];
                        handle === 0 ? minInput.value = Math.round(value) : maxInput.value = Math.round(value);
                        let rangeValue = minInput.value + '-' + maxInput.value;
                        sliderRange.value = rangeValue;
                        sliderRangeShow && (sliderRangeShow.innerHTML = rangeValue);
                    });

                    minInput.addEventListener('change', function () {
                        let minValue = Math.round(parseInt(this.value, 10) / sliderStep) * sliderStep;
                        let maxValue = Math.round(parseInt(maxInput.value, 10) / sliderStep) * sliderStep;

                        if (minValue > maxValue) {
                            this.value = maxValue;
                            minValue = maxValue;
                        }

                        slider.directoristCustomRangeSlider.set([minValue, null]);
                    });

                    maxInput.addEventListener('change', function () {
                        let minValue = Math.round(parseInt(minInput.value, 10) / sliderStep) * sliderStep;
                        let maxValue = Math.round(parseInt(this.value, 10) / sliderStep) * sliderStep;

                        if (maxValue < minValue) {
                            this.value = minValue;
                            maxValue = minValue;
                        }

                        slider.directoristCustomRangeSlider.set([null, maxValue]);
                    });
                }
            });
        }

        directorist_custom_range_slider();

        // Reset Custom Range Slider
        function resetCustomRangeSlider(sliderItem) {
            let slider = sliderItem.querySelector('.directorist-custom-range-slider__slide');
            let minInput = sliderItem.querySelector('.directorist-custom-range-slider__value__min');
            let maxInput = sliderItem.querySelector('.directorist-custom-range-slider__value__max');
            let sliderParent = sliderItem.closest('.directorist-search-field-radius_search');
            let maxValue = slider.getAttribute('value') || 'none';

            if (sliderParent) {
                minInput.value = '0';
                maxInput.value = maxValue;
                slider.directoristCustomRangeSlider.set([0, maxValue]); // Set your initial values
            } else {
                // Reset values to their initial state
            slider.directoristCustomRangeSlider.set([0, 0]); // Set your initial values
                minInput.value = ''; // Set your initial min value
                maxInput.value = ''; // Set your initial max value
            }

        }

        // DOM Mutation Observer on Location Field
        function locationObserver() {
            let targetNode = document.querySelector('.directorist-location-js');
            if(targetNode){
                let observer = new MutationObserver( handleRadiusVisibility );
                observer.observe( targetNode, { attributes: true } );
            }
        }

        locationObserver();
        handleRadiusVisibility();

        $('body').on("keyup", '.zip-radius-search', directorist_debounce( function(){
            var zipcode         = $(this).val();
            var zipcode_search  = $(this).closest('.directorist-zipcode-search');
            var country_suggest = zipcode_search.find('.directorist-country');
            var zipcode_search  = $(this).closest('.directorist-zipcode-search');

            if(zipcode) {
                zipcode_search.addClass('dir_loading');
            }

            if( directorist.i18n_text.select_listing_map === 'google' ) {
              var url = directorist.ajax_url;
            } else {
                url = `https://nominatim.openstreetmap.org/?postalcode=${zipcode}&format=json&addressdetails=1`;

                $('.directorist-country').css({
                    display: 'block'
                });

                if (zipcode === '') {
                    $('.directorist-country').css({
                        display: 'none'
                    });
                }

            }

            let res = '';
            let google_data = {
                'nonce' : directorist.directorist_nonce,
                'action' : 'directorist_zipcode_search',
                'zipcode' : zipcode
            };
            $.ajax({
                url: url,
                method: 'POST',
                data : directorist.i18n_text.select_listing_map === 'google' ? google_data : "",
                success: function( data ) {
                    if( data.data && data.data.error_message ) {
                        zipcode_search.find('.error_message').remove();
                        zipcode_search.find('.zip-cityLat').val( '' );
                        zipcode_search.find('.zip-cityLng').val( '' );
                        zipcode_search.append( data.data.error_message );
                    }
                    zipcode_search.removeClass('dir_loading');
                    if( directorist.i18n_text.select_listing_map === 'google' && typeof data.lat !== 'undefined' && typeof data.lng !== 'undefined' ) {
                        zipcode_search.find('.error_message').remove();
                        zipcode_search.find('.zip-cityLat').val( data.lat );
                        zipcode_search.find('.zip-cityLng').val( data.lng );
                    } else {
                        if( data.length === 1 ) {
                            var lat = data[0].lat;
                            var lon = data[0].lon;
                            zipcode_search.find('.zip-cityLat').val(lat);
                            zipcode_search.find('.zip-cityLng').val(lon);
                        } else {
                            for (let i = 0; i < data.length; i++) {
                                res += `<li><a href="#" data-lat=${data[i].lat} data-lon=${data[i].lon}>${data[i].address.country}</a></li>`;
                            }
                        }

                        $(country_suggest).html(`<ul>${res}</ul>`);

                        if (res.length) {
                            $('.directorist-country').show();
                        } else {
                            $('.directorist-country').hide();
                        }
                    }
                }
            });
        }, 250 ));

        // Returns a function, that, as long as it continues to be invoked, will not
        // be triggered. The function will be called after it stops being called for
        // N milliseconds. If `immediate` is passed, trigger the function on the
        // leading edge, instead of the trailing.
        function directorist_debounce(func, wait, immediate) {
            var timeout;
            return function() {
                var context = this, args = arguments;
                var later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        };

        // Custom Range Slider Value Check on Change
        function sliderValueCheck(targetNode, value) {
            let searchForm = targetNode.closest('form');
            if (value > 0) {
                let customSliderMin = targetNode.closest('.directorist-custom-range-slider').querySelector('.directorist-custom-range-slider__value__min');
                let customSliderRange = targetNode.closest('.directorist-custom-range-slider').querySelector('.directorist-custom-range-slider__range');
                customSliderMin.value = customSliderMin.value ? customSliderMin.value : 0;
                customSliderRange.value = customSliderMin.value + '-' + value;
                enableResetButton(searchForm);
            } else {
                initForm(searchForm);
            }
        }

        // DOM Mutation Observer on Custom Range Slider
        function rangeSliderObserver() {
            let targetNodes = document.querySelectorAll('.directorist-search-field:not(.directorist-search-field-radius_search) .directorist-custom-range-slider-handle-upper');
            targetNodes.forEach((targetNode) => {
                if(targetNode){
                    let observerCallback = (mutationList, observer) => {
                        for (let mutation of mutationList) {
                            if (targetNode.classList.contains('directorist-custom-range-slider-handle-upper')) {
                                sliderValueCheck(targetNode, parseInt(targetNode.ariaValueNow));
                            }
                        }
                    };

                    let sliderObserver = new MutationObserver( observerCallback );
                    sliderObserver.observe( targetNode, { attributes: true } );
                }


            })

        }

        rangeSliderObserver();


    });
})(jQuery);