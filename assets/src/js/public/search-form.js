//import './components/directoristDropdown';
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
        $(".bads-custom-checks").parent(".form-group").addClass("ads-filter-tags");

        function defaultTags() {
            $('.directorist-btn-ml').each((index, element) => {
                let item = $(element).siblings('.atbdp_cf_checkbox, .direcorist-search-field-tag, .directorist-search-tags');
                var abc2 = $(item).find('.directorist-checkbox');
                $(abc2).slice(4, abc2.length).fadeOut();
                if(abc2.length <= 4){
                    $(element).css('display', 'none');
                }
            });
        }
        $(window).on('load', defaultTags);
        window.addEventListener('triggerSlice', defaultTags);

        $('body').on('click', '.directorist-btn-ml', function (event) {
            event.preventDefault();
            var item = $(this).siblings('.atbdp_cf_checkbox, .direcorist-search-field-tag, .directorist-search-tags');
            var abc2 = $(item).find('.directorist-checkbox ');
            $(abc2).slice(4, abc2.length).fadeOut();

            $(this).toggleClass('active');

            if ($(this).hasClass('active')) {
                $(this).text(directorist.i18n_text.show_less);
                $(abc2).slice(4, abc2.length).fadeIn();
            } else {
                $(this).text(directorist.i18n_text.show_more);
                $(abc2).slice(4, abc2.length).fadeOut();
            }

        });

        /* Advanced search */
        var ad = $(".directorist-search-float .directorist-advanced-filter");
        ad.css({
            visibility: 'hidden',
            height: '0',
        });

        let adsFilterHeight = () => $('.directorist-advanced-filter .directorist-advanced-filter__action').innerHeight();
        let adsItemsHeight;

        function getItemsHeight(selector) {
            let advElmHeight;
            let basicElmHeight;
            let adsAdvItemHeight = () => $(selector).closest('.directorist-search-form-box, .directorist-archive-contents, .directorist-search-form').find('.directorist-advanced-filter__advanced--element');
            let adsBasicItemHeight = () => $(selector).closest('.directorist-search-form-box, .directorist-archive-contents').find('.directorist-advanced-filter__basic');
            for (let i = 0; i <= adsAdvItemHeight().length; i++) {
                adsAdvItemHeight().length <= 1 ? advElmHeight = adsAdvItemHeight().innerHeight() : advElmHeight = adsAdvItemHeight().innerHeight() * i;
            }
            if (isNaN(advElmHeight)) {
                advElmHeight = 0;
            }
            let basicElmHeights = adsBasicItemHeight().innerHeight();
            basicElmHeights === undefined ? basicElmHeight = 0 : basicElmHeight = basicElmHeights;
            return adsItemsHeight = advElmHeight + basicElmHeight;
        }
        getItemsHeight('.directorist-filter-btn');

        var count = 0;
        $('body').on('click', '.directorist-listing-type-selection .search_listing_types, .directorist-type-nav .directorist-type-nav__link', function () {
            count = 0;
        });

        /* Toggle overlapped advanced filter wrapper */
        $('body').on("click", '.directorist-filter-btn', function (e) {
            count++;
            e.preventDefault();
            let _this = $(this);
            setTimeout(() => {
                getItemsHeight(_this);
            }, 500);
            _this.toggleClass('directorist-filter-btn--active');
            var currentPos = e.clientY,
                displayPos = window.innerHeight,
                height = displayPos - currentPos;
            var advFilterWrap = $(e.currentTarget).closest('.directorist-search-form, .directorist-archive-contents').find('.directorist-search-float').find('.directorist-advanced-filter');
            if (count % 2 === 0) {
                $(advFilterWrap).css({
                    visibility: 'hidden',
                    opacity: '0',
                    height: '0',
                    transition: '.3s ease'
                });
            } else {
                $(advFilterWrap).css({
                    visibility: 'visible',
                    height: adsItemsHeight + adsFilterHeight() + 50 + 'px',
                    transition: '0.3s ease',
                    opacity: '1',
                    display: 'block'
                });
            }
        });

        /* Hide overlapped advanced filter */
        var directoristAdvFilter = () => $('.directorist-search-float .directorist-advanced-filter');
        var ad_slide = $(".directorist-search-slide .directorist-advanced-filter");
        ad_slide.hide().slideUp();

        $(document).on('click', function (e) {
            if (!e.target.closest('.directorist-search-form-top, .directorist-listings-header, .directorist-search-form, .select2-container') && !e.target.closest('.directorist-search-float .directorist-advanced-filter')) {
                count = 0;
                directoristAdvFilter().css({
                    visibility: 'hidden',
                    opacity: '0',
                    height: '0',
                    transition: '.3s ease'
                });
            }
        });
        $('body').on('click', '.directorist-sortby-dropdown > a, .directorist-viewas-dropdown > a', function(){
            count = 0;
            directoristAdvFilter().css({
                visibility: 'hidden',
                opacity: '0',
                height: '0',
                transition: '.3s ease'
            });

            ad_slide.hide().slideUp();
        });

        $('body').on("click", '.directorist-filter-btn', function (e) {
            e.preventDefault();
            let miles = parseInt($('.directorist-range-slider-value').val());
            let default_args = {
                maxValue: directorist.args.search_max_radius_distance,
                minValue: miles,
                maxWidth: '100%',
                barColor: '#d4d5d9',
                barBorder: 'none',
                pointerColor: '#fff',
                pointerBorder: '4px solid #444752',
            };
            let config = default_args;
            $(this).closest('.directorist-search-form, .directorist-archive-contents').find('.directorist-search-slide').find('.directorist-advanced-filter').slideToggle().show();
            $(this).closest('.directorist-search-form, .directorist-archive-contents').find('.directorist-search-slide').find('.directorist-advanced-filter').toggleClass("directorist-advanced-filter--show");
            if($(this).closest('.directorist-search-form, .directorist-archive-contents').find('.direcorist-search-field-radius_search').length){
                directorist_callingSlider();
                directorist_range_slider('.directorist-range-slider', config);
            }
        });
        $(".directorist-advanced-filter").parents("div").css("overflow", "visible");


        //remove preload after window load
        $(window).on('load', function () {
            $("body").removeClass("directorist-preload");
            $('.button.wp-color-result').attr('style', ' ');
        });

        //reset fields
        function resetFields() {
            var inputArray = document.querySelectorAll('.search-area input');
            inputArray.forEach(function (input) {
                if (input.getAttribute("type") !== "hidden" || input.getAttribute("id") === "atbd_rs_value") {
                    input.value = "";
                }
            });

            var textAreaArray = document.querySelectorAll('.search-area textArea');
            textAreaArray.forEach(function (textArea) {
                textArea.innerHTML = "";
            });

            var range = document.querySelector(".atbdpr-range .ui-slider-horizontal .ui-slider-range");
            var rangePos = document.querySelector(".atbdpr-range .ui-slider-horizontal .ui-slider-handle");
            var rangeAmount = document.querySelector(".atbdpr_amount");
            if (range) {
                range.setAttribute("style", "width: 0;");
            }
            if (rangePos) {
                rangePos.setAttribute("style", "left: 0;");
            }
            if (rangeAmount) {
                rangeAmount.innerText = "0 Mile";
            }

            var checkBoxes = document.querySelectorAll('.directorist-advanced-filter input[type="checkbox"]');
            checkBoxes.forEach(function (el, ind) {
                el.checked = false;
            })
            var radios = document.querySelectorAll('.directorist-advanced-filter input[type="radio"]');
            radios.forEach(function (el, ind) {
                el.checked = false;
            })
            $('.search-area select').prop('selectedIndex', 0);
            $(".bdas-location-search, .bdas-category-search").val('').trigger('change');
        }
        $("body").on("click", ".atbd_widget .directorist-advanced-filter #atbdp_reset", function (e) {
            e.preventDefault();
            resetFields();
        });

        /* advanced search form reset */
        function adsFormReset(searchForm) {
            searchForm.querySelectorAll("input[type='text']").forEach(function (el) {
                el.value = "";
            });
            searchForm.querySelectorAll("input[type='date']").forEach(function (el) {
                el.value = "";
            });
            searchForm.querySelectorAll("input[type='time']").forEach(function (el) {
                el.value = "";
            });
            searchForm.querySelectorAll("input[type='url']").forEach(function (el) {
                el.value = "";
            });
            searchForm.querySelectorAll("input[type='number']").forEach(function (el) {
                el.value = "";
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
        if ($(".directorist-search-form .directorist-btn-reset-js") !== null) {
            $("body").on("click", ".directorist-search-form .directorist-btn-reset-js", function (e) {
                e.preventDefault();
                if (this.closest('.directorist-search-contents')) {
                    const searchForm = this.closest('.directorist-search-contents').querySelector('.directorist-search-form');
                    if (searchForm) {
                        adsFormReset(searchForm);
                    }
                }
                if($(this).closest('.directorist-search-contents').find('.direcorist-search-field-radius_search').length){
                    directorist_callingSlider(0);
                }

            });
        }

        /* All Listing Advance Filter */
        if ($(".directorist-advanced-filter__form .directorist-btn-reset-js") !== null) {
            $("body").on("click", ".directorist-advanced-filter__form .directorist-btn-reset-js", function (e) {
                e.preventDefault();
                if (this.closest('.directorist-advanced-filter')) {
                    const searchForm = this.closest('.directorist-advanced-filter').querySelector('.directorist-advanced-filter__form');
                    if (searchForm) {
                        adsFormReset(searchForm);
                    }
                }
                if($(this).closest('.directorist-advanced-filter').find('.direcorist-search-field-radius_search').length){
                    directorist_callingSlider(0);
                }
            });
        }

        if ($("#bdlm-search-area #atbdp_reset") !== null) {
            $("body").on("click", "#bdlm-search-area #atbdp_reset", function (e) {
                e.preventDefault();
                if (this.closest('.directorist-search-contents')) {
                    const searchForm = this.closest('.directorist-search-contents').querySelector('.directorist-search-form');
                    if (searchForm) {
                        adsFormReset(searchForm);
                    }
                }
                if (this.closest('.directorist-advanced-filter')) {
                    const searchForm = this.closest('.directorist-advanced-filter').querySelector('.directorist-advanced-filter__form');
                    if (searchForm) {
                        adsFormReset(searchForm);
                    }
                }
                if($(this).closest('.directorist-search-contents').find('.direcorist-search-field-radius_search').length){
                    directorist_callingSlider(0);
                }
            });
        }

        /* Map Listing Search Form */
        if ($("#directorist-search-area .directorist-btn-reset-js") !== null) {
            $("body").on("click", "#directorist-search-area .directorist-btn-reset-js", function (e) {
                e.preventDefault();
                if (this.closest('#directorist-search-area')) {
                    const searchForm = this.closest('#directorist-search-area').querySelector('#directorist-search-area-form');
                    if (searchForm) {
                        adsFormReset(searchForm);
                    }
                }
                if($(this).closest('#directorist-search-area').find('.direcorist-search-field-radius_search').length){
                    directorist_callingSlider(0);
                }
            });
        }

        /* Single Listing widget Form */
        if ($(".atbd_widget .search-area .directorist-btn-reset-js") !== null) {
            $("body").on("click", ".atbd_widget .search-area .directorist-btn-reset-js", function (e) {
                e.preventDefault();
                if (this.closest('.search-area')) {
                    const searchForm = this.closest('.search-area').querySelector('.directorist-advanced-filter__form');
                    if (searchForm) {
                        adsFormReset(searchForm);
                    }
                }
                if($(this).closest('.search-area').find('.direcorist-search-field-radius_search').length){
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
                    if(parentAfterAjax.find('.directorist-search-form-box').find('.direcorist-search-field-radius_search').length){
                        handleRadiusVisibility()
                        directorist_callingSlider();
                    }
                },
                error(error) {
                    console.log(error);
                }
            });
        });


        // Advance search
        // Populate atbdp child terms dropdown
        $('.bdas-terms').on('change', 'select', function (e) {
            e.preventDefault();

            const $this = $(this);
            const taxonomy = $this.data('taxonomy');
            const parent = $this.data('parent');
            const value = $this.val();
            const classes = $this.attr('class');

            $this.closest('.bdas-terms')
                .find('input.bdas-term-hidden')
                .val(value);
            $this.parent()
                .find('div:first')
                .remove();

            if (parent != value) {
                $this.parent().append('<div class="bdas-spinner"></div>');

                const data = {
                    action: 'bdas_public_dropdown_terms',
                    taxonomy,
                    parent: value,
                    class: classes,
                    security: directorist.ajaxnonce,
                };

                $.post(directorist.ajax_url, data, function (response) {
                    $this.parent()
                        .find('div:first')
                        .remove();
                    $this.parent().append(response);
                });
            }
        });

        if( $( '.directorist-search-contents' ).length ) {
            $('body').on('change', '.directorist-category-select', function (event) {
                var $this            = $(this);
                var $container       = $this.parents('form');
                var cat_id           = $this.val();
                var directory_type   = $container.find('.listing_type').val();
                var $search_form_box = $container.find('.directorist-search-form-box-wrap');
                var form_data        = new FormData();

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
                  },
                  error: function error(_error) {
                    //console.log(_error);
                  }
                });
              });
        }

        // load custom fields of the selected category in the search form
        $('body').on('change', '.bdas-category-search, .directorist-category-select', function () {
            const $search_elem = $(this)
                .closest('form')
                .find('.atbdp-custom-fields-search');

            if ($search_elem.length) {
                $search_elem.html('<div class="atbdp-spinner"></div>');

                const data = {
                    action: 'atbdp_custom_fields_search',
                    term_id: $(this).val(),
                    security: directorist.ajaxnonce,
                };

                $.post(directorist.ajax_url, data, function (response) {
                    $search_elem.html(response);
                    const item = $('.custom-control').closest('.bads-custom-checks');
                    item.each(function (index, el) {
                        const count = 0;
                        const abc = $(el)[0];
                        const abc2 = $(abc).children('.custom-control');
                        if (abc2.length <= 4) {
                            $(abc2)
                                .closest('.bads-custom-checks')
                                .next('a.more-or-less')
                                .hide();
                        }
                        $(abc2)
                            .slice(4, abc2.length)
                            .hide();
                    });
                });
            }
        });

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
                method: 'GET',
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

        // hide country result when click outside the zipcode field
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.directorist-zip-code').length) {
                $('.directorist-country').hide();
            }
        });

        $('body').on('click', '.directorist-country ul li a', function (event) {
            event.preventDefault();
            var zipcode_search  = $(this).closest('.directorist-zipcode-search');

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

                    var setupAutocomplete = function (field) {
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

                                    for (let i = 0, len = data.length; i < len; i++) {
                                        res += "<li><a href=\"#\" data-lat=".concat(data[i].lat, " data-lon=").concat(data[i].lon, ">").concat(data[i].display_name, "</a></li>");
                                    }

                                    result_container.html("<ul>".concat(res, "</ul>"));
                                    if (res.length) {
                                        result_container.show();
                                    } else {
                                        result_container.hide();
                                    }

                                    locationAddressField.removeClass('atbdp-form-fade');
                                },
                                error: function error(_error3) {
                                    console.log({
                                        error: _error3
                                    });
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

        /* When location field is empty we need to hide Radius Search */
        function handleRadiusVisibility(){
            $('.directorist-range-slider-wrap').closest('.directorist-search-field').addClass('direcorist-search-field-radius_search');
            $('.directorist-location-js, .zip-radius-search').each((index,locationDom)=>{
                if($(locationDom).val() === ''){
                    $(locationDom).closest('.directorist-search-form, .directorist-advanced-filter__form').find('.direcorist-search-field-radius_search').css({display: "none"});
                }else{
                    $(locationDom).closest('.directorist-search-form, .directorist-advanced-filter__form').find('.direcorist-search-field-radius_search').css({display: "block"});
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
    });
})(jQuery);