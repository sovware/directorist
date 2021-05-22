(function ($) {
    $('body').on('click', '.search_listing_types', function (event) {
        event.preventDefault();
        const listing_type = $(this).attr('data-listing_type');
        const type_current = $('.directorist-listing-type-selection__link--current');
        if (type_current.length) {
            type_current.removeClass('directorist-listing-type-selection__link--current');
        }
        $('#listing_type').val(listing_type);
        $(this).addClass('directorist-listing-type-selection__link--current');
        const form_data = new FormData();

        form_data.append('action', 'atbdp_listing_types_form');
        form_data.append('listing_type', listing_type);
        $('.directorist-search-form-box').addClass('atbdp-form-fade');

        $.ajax({
            method: 'POST',
            processData: false,
            contentType: false,
            url: atbdp_search.ajax_url,
            data: form_data,
            success(response) {
                if (response) {
                    let atbdp_search_listing = (response['atbdp_search_listing']) ? response['atbdp_search_listing'] : atbdp_search_listing;

                    $('.directorist-search-form-box')
                        .empty()
                        .html(response['search_form']);
                    $('.directorist-listing-category-top')
                        .empty()
                        .html(response['popular_categories']);

                    let events = [
                        new CustomEvent('directorist-search-form-nav-tab-reloaded'),
                        new CustomEvent('directorist-reload-select2-fields'),
                        new CustomEvent('directorist-reload-map-api-field'),
                    ];

                    events.forEach( event => {
                        document.body.dispatchEvent( event );
                    });
                }

                $('.directorist-search-form-box').removeClass('atbdp-form-fade');
            },
            error(error) {
                console.log(error);
            },
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
                security: atbdp_search.ajaxnonce,
            };

            $.post(atbdp_search.ajax_url, data, function (response) {
                $this.parent()
                    .find('div:first')
                    .remove();
                $this.parent().append(response);
            });
        }
    });

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
                security: atbdp_search.ajaxnonce,
            };

            $.post(atbdp_search.ajax_url, data, function (response) {
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

    $('.address_result').hide();


    window.addEventListener('load', init_map_api_field);
    document.body.addEventListener('directorist-reload-map-api-field', init_map_api_field);

    function init_map_api_field() {

        if (atbdp_search_listing.i18n_text.select_listing_map === 'google') {

            function initialize() {
                let opt = {
                    types: ['geocode'],
                    componentRestrictions: {
                        country: atbdp_search_listing.restricted_countries
                    },
                };
                const options = atbdp_search_listing.countryRestriction ? opt : '';

                let input_fields = [
                    { input_id: 'address', lat_id: 'cityLat', lng_id: 'cityLng', options },
                    { input_id: 'address_widget', lat_id: 'cityLat', lng_id: 'cityLng', options },
                ];

                var setupAutocomplete = function( field ) {
                    const input = document.getElementById( field.input_id );
                    const autocomplete = new google.maps.places.Autocomplete( input, field.options );

                    google.maps.event.addListener(autocomplete, 'place_changed', function () {
                        const place = autocomplete.getPlace();

                        console.log( { place } );

                        document.getElementById( field.lat_id ).value = place.geometry.location.lat();
                        document.getElementById( field.lng_id ).value = place.geometry.location.lng();
                    });
                };

                input_fields.forEach( field => {
                    setupAutocomplete( field );
                });
            }

            initialize();

        } else if (atbdp_search_listing.i18n_text.select_listing_map === 'openstreet') {

            const getResultContainer = function ( context, field ) {
                return $( context ).next( field.search_result_elm );
            };

            const getWidgetResultContainer = function ( context, field ) {
                return $( context ).parent().next( field.search_result_elm );
            };

            let input_fields = [
                { input_elm: '#address', search_result_elm: '.address_result', getResultContainer },
                { input_elm: '#q_addressss', search_result_elm: '.address_result', getResultContainer },
                { input_elm: '.atbdp-search-address', search_result_elm: '.address_result', getResultContainer },
                { input_elm: '#address_widget', search_result_elm: '#address_widget_result', getResultContainer: getWidgetResultContainer },
            ];

            input_fields.forEach( field => {

                if ( ! $( field.input_elm ).length ) { return; }

                $( field.input_elm ).on( 'keyup', function( event ) {
                    event.preventDefault();
                    const search = $(this).val();

                    let result_container = field.getResultContainer( this, field );
                    result_container.css({ display: 'block' });

                    if ( search === '' ) {
                        result_container.css({ display: 'none' });
                    }

                    let res = '';
                    $.ajax({
                        url: `https://nominatim.openstreetmap.org/?q=%27+${search}+%27&format=json`,
                        type: 'POST',
                        data: {},
                        success(data) {

                            for (let i = 0; i < data.length; i++) {
                                res += `<li><a href="#" data-lat=${data[i].lat} data-lon=${data[i].lon}>${data[i].display_name}</a></li>`;
                            }

                            result_container.html(`<ul>${res}</ul>`);

                            if ( res.length ) {
                                result_container.show();
                            } else {
                                result_container.hide();
                            }
                        },
                        error(error) {
                            console.log({ error });
                        }
                    });
                });
            });

            
            // hide address result when click outside the input field
            $(document).on('click', function (e) {
                if (!$(e.target).closest('#address, #q_addressss, .atbdp-search-address').length) {
                    $('.address_result').hide();
                }
            });

            const syncLatLngData = function( context, event, args ) {
                event.preventDefault();

                const text = $( context ).text();
                const lat = $( context ).data('lat');
                const lon = $( context ).data('lon');

                $('#cityLat').val(lat);
                $('#cityLng').val(lon);

                const inp = $( context )
                    .closest( args.result_list_container )
                    .parent()
                    .find('#address, #address_widget, #q_addressss, .atbdp-search-address');
                
                inp.val(text);

                $( args.result_list_container ).hide();
            };


            $('body').on('click', '.address_result ul li a', function ( event ) {
                syncLatLngData( this, event , { result_list_container: '.address_result' } );
            });

            $('body').on('click', '#address_widget_result ul li a', function ( event ) {
                syncLatLngData( this, event, { result_list_container: '#address_widget_result' } );
            });
        }


        if ($('#address, #q_addressss,.atbdp-search-address').val() === '') {
            $(this)
                .parent()
                .next('.address_result')
                .css({ display: 'none' });
        }
    }

})(jQuery);