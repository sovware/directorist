import { convertToSelect2 } from './../../lib/helper';

const $ = jQuery;

window.addEventListener('load', initSelect2 );
document.body.addEventListener( 'directorist-search-form-nav-tab-reloaded', initSelect2 );
document.body.addEventListener( 'directorist-reload-select2-fields', initSelect2 );

// Init Static Select 2 Fields
function initSelect2() {
    const select_fields = [
        { elm: $('.directorist-select').find( 'select' ) },
        { elm: $('#directorist-select-js') },
        { elm: $('#directorist-search-category-js') },
        { elm: $('#directorist-select-st-s-js') },
        { elm: $('#directorist-select-sn-s-js') },
        { elm: $('#directorist-select-mn-e-js') },
        { elm: $('#directorist-select-tu-e-js') },
        { elm: $('#directorist-select-wd-s-js') },
        { elm: $('#directorist-select-wd-e-js') },
        { elm: $('#directorist-select-th-e-js') },
        { elm: $('#directorist-select-fr-s-js') },
        { elm: $('#directorist-select-fr-e-js') },
        // { elm: $('#directorist-location-select') },
        // { elm: $('#directorist-category-select') },
        { elm: $('.select-basic') },
        { elm: $('#loc-type') },
        { elm: $('.bdas-location-search') },
        // { elm: $('.directorist-location-select') },
        { elm: $('#at_biz_dir-category') },
        { elm: $('#cat-type') },
        { elm: $('.bdas-category-search') },
        // { elm: $('.directorist-category-select') },
    ];

    select_fields.forEach( field => {
        convertToSelect2( field );
    });

    const lazy_load_taxonomy_fields = atbdp_public_data.lazy_load_taxonomy_fields;
    if ( lazy_load_taxonomy_fields ) {
        // Init Select2 Ajax Fields
        initSelect2AjaxFields();
    }
}



// Init Select2 Ajax Fields
function initSelect2AjaxFields () {
    const rest_base_url = `${atbdp_public_data.rest_url}directorist/v1`;

    // Init Select2 Ajax Category Field
    initSelect2AjaxTaxonomy({
        selector: $('.directorist-search-category').find( 'select' ),
        url: `${rest_base_url}/listings/categories`,
    });

    // Init Select2 Ajax Category Field
    initSelect2AjaxTaxonomy({
        selector: $('.directorist-search-location').find( 'select' ),
        url: `${rest_base_url}/listings/locations`,
    });
}


// initSelect2AjaxTaxonomy
function initSelect2AjaxTaxonomy( args ) {
    const defaultArgs = { selector: '', url: '', perPage: 10};
    args = { ...defaultArgs, ...args };

    if ( ! args.selector.length ) {
        return;
    }

    [ ...args.selector ].forEach( ( item, index ) => {
        const parent = $( item ).closest( '.directorist-search-form' );
        const directory_type_id = parent.find( '.directorist-listing-type-selection__link--current' ).data( 'listing_type_id' );

        var currentPage = 1;
        $( item ).select2({
            allowClear: true,
            width: '100%',
            escapeMarkup: function (text) {
                return text;
            },
            ajax: {
                url: args.url,
                dataType: 'json',
                cache: true,
                data: function (params) {
                    currentPage = params.page || 1;
                    const search_term = ( params.term ) ? params.term : '';

                    let query = {
                        search: search_term,
                        page: currentPage,
                        per_page: args.perPage,
                    }

                    if ( directory_type_id ) {
                        query.directory = directory_type_id;
                    }

                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data.items,
                        pagination: { more: data.paginationMore }
                    };
                },

                transport: function (params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(function( data, textStatus, jqXHR ) {
                        var totalPage = parseInt( jqXHR.getResponseHeader('x-wp-totalpages') );
                        var paginationMore = currentPage < totalPage;

                        var items = data.map(item => {
                            return {
                                id: item.id,
                                text: item.name,
                            };
                        });

                        return {
                            items,
                            paginationMore,
                        };
                    }).then(success);

                    $request.fail(failure);

                    return $request;
                }
            }
        });

        // Setup Preselected Option
        const selected_item_id = $( item ).data( 'selected-id' );
        const selected_item_label = $( item ).data( 'selected-label' );

        if ( selected_item_id ) {
            var option = new Option( selected_item_label, selected_item_id, true, true );
            $( item ).append( option );

            $( item ).trigger({
                type: 'select2:select',
                params: {
                    data: {
                        id: selected_item_id, text:
                        selected_item_label
                    }
                }
            });
        }
    });

}