import './components/directoristDropdown';
import './components/directoristSelect';
import './components/colorPicker';
import './../global/components/setup-select2';
import './../global/components/select2-custom-control';
import { directorist_callingSlider } from './range-slider';
import { directorist_range_slider } from './range-slider';

(function ($) {
    window.addEventListener('DOMContentLoaded', () => {
        /* ----------------
        Search Listings
        ------------------ */

        //ad search js

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
        $(window).on('load', defaultTags);
        window.addEventListener('triggerSlice', defaultTags);

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

        //remove preload after window load
        $(window).on('load', function () {
            $("body").removeClass("directorist-preload");
            $('.button.wp-color-result').attr('style', ' ');
        });

        /* advanced search form reset */
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
                if(el.getAttribute('name') === "miles"){
                    const radiusDefaultValue = searchForm.querySelector('.directorist-range-slider').dataset.defaultRadius;
                    el.value = radiusDefaultValue;
                    return;
                }
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
                $(el).val(null).trigger('change');

                if (el.parentElement.classList.contains('input-has-value') || el.parentElement.classList.contains('input-is-focused')) {
                    el.parentElement.classList.remove('input-has-value', 'input-is-focused');
                }
            });

            const irisPicker = searchForm.querySelector("input.wp-picker-clear");
            if (irisPicker !== null) {
                irisPicker.click();
            }

            const rangeValue = searchForm.querySelector(".directorist-range-slider-current-value span");
            if (rangeValue !== null) {
                rangeValue.innerHTML = "0";
            }
            handleRadiusVisibility();
            
        }

        /* Advance Search Filter For Search Home Short Code */
        if ($(".directorist-btn-reset-js") !== null) {
            $("body").on("click", ".directorist-btn-reset-js", function (e) {
                e.preventDefault();
                if (this.closest('.directorist-contents-wrap')) {
                    const searchForm = this.closest('.directorist-contents-wrap').querySelector('.directorist-search-form');
                    if (searchForm) {
                        adsFormReset(searchForm);
                    }
                    const advanceSearchForm = this.closest('.directorist-contents-wrap').querySelector('.directorist-advanced-filter__form');
                    if (advanceSearchForm) {
                        adsFormReset(advanceSearchForm);
                    }
                }
                if($(this).closest('.directorist-contents-wrap').find('.directorist-search-field-radius_search').length){
                    directorist_callingSlider(0);
                }
            });
        }


        /* ----------------
        Search-form-listing
        ------------------- */
        $('body').on('click', '.search_listing_types', function (event) {
            event.preventDefault();
            const parent = $(this).closest('.directorist-search-contents');
            const listing_type = $(this).attr('data-listing_type');
            const type_current = parent.find('.directorist-listing-type-selection__link--current');

            if (type_current.length) {
                type_current.removeClass('directorist-listing-type-selection__link--current');
                $(this).addClass('directorist-listing-type-selection__link--current');
            }

            parent.find('.listing_type').val(listing_type);

            const form_data = new FormData();
            form_data.append('action', 'atbdp_listing_types_form');
            form_data.append('nonce', directorist.directorist_nonce);
            form_data.append('listing_type', listing_type);

            const atts = parent.attr('data-atts');
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
                    }

                    const parentAfterAjax = $(this).closest('.directorist-search-contents');

                    parentAfterAjax.find('.directorist-search-form-box').removeClass('atbdp-form-fade');
                    if(parentAfterAjax.find('.directorist-search-form-box').find('.directorist-search-field-radius_search').length){
                        handleRadiusVisibility()
                        directorist_callingSlider();
                    }
                },
                error(error) {
                    // console.log(error);
                }
            });
        });

        // Search Category

        if( $( '.directorist-search-contents' ).length ) {
            $('body').on('change', '.directorist-category-select', function (event) {
                let $this            = $(this);
                let $container       = $this.parents('form');
                let cat_id           = $this.val();
                let directory_type   = $container.find('.listing_type').val();
                let $search_form_box = $container.find('.directorist-search-form-box-wrap');
                let form_data        = new FormData();

                form_data.append('action', 'directorist_category_custom_field_search');
                form_data.append('nonce', directorist.directorist_nonce);
                form_data.append('listing_type', directory_type);
                form_data.append('cat_id', cat_id);
                form_data.append('atts', JSON.stringify($container.data('atts')));

                $search_form_box.addClass('atbdp-form-fade');

                $.ajax({
                  method     : 'POST',
                  processData: false,
                  contentType: false,
                  url        : directorist.ajax_url,
                  data       : form_data,
                  success: function success(response) {
                    if (response) {
                        $search_form_box.html(response['search_form']);

                        $container.find('.directorist-category-select option').data('custom-field', 1);
                        $container.find('.directorist-category-select').val(cat_id);

                        [
                            new CustomEvent('directorist-search-form-nav-tab-reloaded'),
                            new CustomEvent('directorist-reload-select2-fields'),
                            new CustomEvent('directorist-reload-map-api-field'),
                            new CustomEvent('triggerSlice')
                        ].forEach(function (event) {
                            document.body.dispatchEvent(event);
                            window.dispatchEvent(event);
                        });
                    }

                    $search_form_box.removeClass('atbdp-form-fade');
                    checkEmptySearchFields();
                  },
                  error: function error(_error) {
                    //console.log(_error);
                  }
                });
            });
        }

        // Check Empty Search Fields on Search Modal
        function checkEmptySearchFields(){
            let inputFields = document.querySelectorAll('.directorist-search-modal__input');
        
            inputFields.forEach((inputField)=>{
                let searchField = inputField.querySelector('.directorist-search-field');
                if(!searchField){
                    inputField.style.display = 'none';
                }
            });
        }

        checkEmptySearchFields();

        // hide country result when click outside the zipcode field
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.directorist-zip-code').length) {
                $('.directorist-country').hide();
            }
        });

        $('body').on('click', '.directorist-country ul li a', function (event) {
            event.preventDefault();
            let zipcode_search  = $(this).closest('.directorist-zipcode-search');

            const lat = $(this).data('lat');
            const lon = $(this).data('lon');

            zipcode_search.find('.zip-cityLat').val(lat);
            zipcode_search.find('.zip-cityLng').val(lon);

            $('.directorist-country').hide();
        });

        $('.address_result').hide();

        window.addEventListener('load', init_map_api_field);
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
                    const options = directorist.countryRestriction ? opt : '';

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
                        const input = document.querySelectorAll(field.input_class);
                        input.forEach(elm => {
                            if (!elm) {
                                return;
                            }

                            const autocomplete = new google.maps.places.Autocomplete(elm, field.options);

                            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                                const place = autocomplete.getPlace();
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

                const getResultContainer = function (context, field) {
                    return $(context).next(field.search_result_elm);
                };

                const getWidgetResultContainer = function (context, field) {
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

                    $(field.input_elm).on('keyup', directorist_debounce(function (event) {
                        event.preventDefault();

                        const blockedKeyCodes = [16, 17, 18, 19, 20, 27, 33, 34, 35, 36, 37, 38, 39, 40, 45, 91, 93, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 144, 145];

                        // Return early when blocked key is pressed.
                        if (blockedKeyCodes.includes(event.keyCode)) {
                            return;
                        }

                        const locationAddressField = $(this).parent('.directorist-search-field');
                        const result_container = field.getResultContainer(this, field);
                        const search = $(this).val();

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
                                    // console.log({
                                    //     error: _error3
                                    // });
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

                const syncLatLngData = function (context, event, args) {
                    event.preventDefault();
                    const text = $(context).text();
                    const lat = $(context).data('lat');
                    const lon = $(context).data('lon');
                    const _this = event.target;
                    $(_this).closest('.address_result').siblings('input[name="cityLat"]').val(lat);
                    $(_this).closest('.address_result').siblings('input[name="cityLng"]').val(lon);
                    const inp = $(context)
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


            if ($('.directorist-location-js, #q_addressss,.atbdp-search-address').val() === '') {
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

        /*****
            Search Form Modal 
        *****/

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

        // Modal Minimizer
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

        // Search Modal Open
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

        // Search Modal Close
        $('body').on('click', '.directorist-search-modal__contents__btn--close, .directorist-search-modal__overlay', function (e) {
            e.preventDefault();

            let searchModalElement = this.closest('.directorist-search-modal');

            searchModalClose(searchModalElement)
        });

        // Search Modal Minimizer
        $('body').on('click', '.directorist-search-modal__minimizer', function (e) {
            e.preventDefault();

            let searchModalElement = this.closest('.directorist-search-modal');

            searchModalMinimize(searchModalElement)
        });

        // Search Form Input Field Check
        $('body').on('input keyup change focus blur', '.directorist-search-field__input', function(e) {

            if (e.type === 'focusin') {
                this.parentElement.classList.add('input-is-focused');
            } else if (e.type === 'blur') {
                if(this.parentElement.classList.contains('input-is-focused')) {
                    this.parentElement.classList.remove('input-is-focused');
                }
            } else {
                if(this.parentElement.classList.contains('input-is-focused')) {
                    this.parentElement.classList.remove('input-is-focused');
                }
            }

            let inputBox = this;
            
            if (inputBox.value !='') {
                this.parentElement.classList.add('input-has-value');
                if(!this.parentElement.classList.contains('input-is-focused')) {
                    this.parentElement.classList.add('input-is-focused');
                }
            } else {
                inputBox.value = ''
                if(this.parentElement.classList.contains('input-has-value')) {
                    this.parentElement.classList.remove('input-has-value');
                }
            }
        });

        // Search Modal Input Clear Button
        $('body').on('click', '.directorist-search-field__btn--clear', function(e) {
            let inputFields = this.parentElement.querySelectorAll('.directorist-form-element');
            let selectboxField = this.parentElement.querySelector('.directorist-select select');
            let radioFields = this.parentElement.querySelectorAll('input[type="radio"]');
            let checkboxFields = this.parentElement.querySelectorAll('input[type="checkbox"]');

            if (selectboxField) {
                selectboxField.selectedIndex = -1;
                selectboxField.dispatchEvent(new Event('change'));
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

            if (this.parentElement.classList.contains('input-has-value') || this.parentElement.classList.contains('input-is-focused')) {
                this.parentElement.classList.remove('input-has-value', 'input-is-focused');
            }
            

        });

        // Search Form Input Field Back Button
        $('body').on('click', '.directorist-search-field__label', function(e) {
            let windowScreen = window.innerWidth;
            let parentField = this.closest('.directorist-search-field');

            if (windowScreen <= 575) {
                if(parentField.classList.contains('input-is-focused')) {
                    parentField.classList.remove('input-is-focused');
                }
            }
        })

        // Back Button to go back to the previous page
        $('body').on('click', '.directorist-btn__back', function(e) {
            e.preventDefault();
            
            window.history.back();
        });

        /* When location field is empty we need to hide Radius Search */
        function handleRadiusVisibility(){
            $('.directorist-range-slider-wrap').closest('.directorist-search-field').addClass('directorist-search-field-radius_search');
            $('.directorist-location-js').each((index,locationDOM)=>{
                if($(locationDOM).val() === ''){
                    $(locationDOM).closest('.directorist-search-form-top, .directorist-search-modal').find('.directorist-search-field-radius_search').first().css({display: "none"});
                } else{
                    $(locationDOM).closest('.directorist-search-form-top, .directorist-search-modal').find('.directorist-search-field-radius_search').css({display: "block"});
                    directorist_callingSlider();
                }
            });
        }
        
        $('body').on('keyup keydown input change focus', '.directorist-location-js, .zip-radius-search', function (e) {
            handleRadiusVisibility();
        });

        // DOM Mutation observer
        function initObserver() {
            const targetNode = document.querySelector('.directorist-location-js');
            if(targetNode){
                const observer = new MutationObserver( handleRadiusVisibility );
                observer.observe( targetNode, { attributes: true } );
            }
        }
        initObserver();
        handleRadiusVisibility();

        // Returns a function, that, as long as it continues to be invoked, will not
        // be triggered. The function will be called after it stops being called for
        // N milliseconds. If `immediate` is passed, trigger the function on the
        // leading edge, instead of the trailing.
        function directorist_debounce(func, wait, immediate) {
            let timeout;
            return function() {
                let context = this, args = arguments;
                let later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                let callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        };

        $('body').on("keyup", '.zip-radius-search', directorist_debounce( function(){
            let zipcode         = $(this).val();
            let zipcode_search  = $(this).closest('.directorist-zipcode-search');
            let country_suggest = zipcode_search.find('.directorist-country');

            $('.directorist-country').css({
                display: 'block'
            });

            if (zipcode === '') {
                $('.directorist-country').css({
                    display: 'none'
                });
            }
            let res = '';
            $.ajax({
                url: `https://nominatim.openstreetmap.org/?postalcode=+${zipcode}+&format=json&addressdetails=1`,
                type: "POST",
                data: {},
                success: function( data ) {
                    if( data.length === 1 ) {
                        let lat = data[0].lat;
                        let lon = data[0].lon;
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
            });
        }, 250 ));

    });
})(jQuery);