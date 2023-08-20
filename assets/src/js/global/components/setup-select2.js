import {
    convertToSelect2
} from './../../lib/helper';
import './select2-custom-control';

const $ = jQuery;

window.addEventListener('load', initSelect2);
document.body.addEventListener('directorist-search-form-nav-tab-reloaded', initSelect2);
document.body.addEventListener('directorist-reload-select2-fields', initSelect2);

// Init Static Select 2 Fields
function initSelect2() {
    const select_fields = [{
            elm: $('.directorist-select').find('select')
        },
        {
            elm: $('#directorist-select-js')
        },
        {
            elm: $('#directorist-search-category-js')
        },
        {
            elm: $('#directorist-select-st-s-js')
        },
        {
            elm: $('#directorist-select-sn-s-js')
        },
        {
            elm: $('#directorist-select-mn-e-js')
        },
        {
            elm: $('#directorist-select-tu-e-js')
        },
        {
            elm: $('#directorist-select-wd-s-js')
        },
        {
            elm: $('#directorist-select-wd-e-js')
        },
        {
            elm: $('#directorist-select-th-e-js')
        },
        {
            elm: $('#directorist-select-fr-s-js')
        },
        {
            elm: $('#directorist-select-fr-e-js')
        },
        // { elm: $('#directorist-location-select') },
        // { elm: $('#directorist-category-select') },
        {
            elm: $('.select-basic')
        },
        {
            elm: $('#loc-type')
        },
        {
            elm: $('.bdas-location-search')
        },
        // { elm: $('.directorist-location-select') },
        {
            elm: $('#at_biz_dir-category')
        },
        {
            elm: $('#cat-type')
        },
        {
            elm: $('.bdas-category-search')
        },
        // { elm: $('.directorist-category-select') },
    ];

    select_fields.forEach(field => {
        convertToSelect2(field);
    });

    const lazy_load_taxonomy_fields = directorist.lazy_load_taxonomy_fields;
    if (lazy_load_taxonomy_fields) {
        // Init Select2 Ajax Fields
        initSelect2AjaxFields();
    }
}



// Init Select2 Ajax Fields
function initSelect2AjaxFields() {
    const rest_base_url = `${directorist.rest_url}directorist/v1`;

    // Init Select2 Ajax Category Field
    initSelect2AjaxTaxonomy({
        selector: $('.directorist-search-category').find('select'),
        url: `${rest_base_url}/listings/categories`,
    });

    initSelect2AjaxTaxonomy({
        selector: $('.directorist-form-categories-field').find('select'),
        url: `${rest_base_url}/listings/categories`,
    });

    // Init Select2 Ajax Location Field
    initSelect2AjaxTaxonomy({
        selector: $('.directorist-search-location').find('select'),
        url: `${rest_base_url}/listings/locations`,
    });

    initSelect2AjaxTaxonomy({
        selector: $('.directorist-form-location-field').find('select'),
        url: `${rest_base_url}/listings/locations`,
    });

    // Init Select2 Ajax Tag Field
    initSelect2AjaxTaxonomy({
        selector: $('.directorist-form-tag-field').find('select'),
        url: `${rest_base_url}/listings/tags`,
    }, { has_directory_type: false });
}


// initSelect2AjaxTaxonomy
function initSelect2AjaxTaxonomy( args, terms_options ) {
    const defaultArgs = {
        selector: '',
        url: '',
        perPage: 10
    };

    args = { ...defaultArgs, ...args };

    const default_terms_options = { has_directory_type: true };
    terms_options = ( terms_options ) ? { ...default_terms_options, ...terms_options } : default_terms_options;

    if ( ! args.selector.length ) {
        return;
    }

    [ ...args.selector ].forEach( ( item, index ) => {
        let directory_type_id = 0;

        let createNew = item.getAttribute("data-allow_new");
        let maxLength = item.getAttribute("data-max");

        if ( terms_options.has_directory_type ) {
            const search_form_parent            = $( item ).closest( '.directorist-search-form' );
            const archive_page_parent           = $( item ).closest( '.directorist-archive-contents' );
            const add_listing_form_hidden_input = $( item ).closest( '.directorist-add-listing-form' ).find( 'input[name="directory_type"]' );

            let nav_list_item = [];

            // If search page
            if ( search_form_parent.length ) {
                nav_list_item = search_form_parent.find( '.directorist-listing-type-selection__link--current' );
            }

            // If archive page
            if ( archive_page_parent.length ) {
                nav_list_item = archive_page_parent.find( '.directorist-type-nav__list li.current .directorist-type-nav__link' );
            }

            // If has nav item
            if ( nav_list_item.length ) {
                directory_type_id = ( nav_list_item ) ? nav_list_item.data( 'listing_type_id' ) : 0;
            }

            // If has nav item
            if ( add_listing_form_hidden_input.length ) {
                directory_type_id = add_listing_form_hidden_input.val();
            }

            if ( directory_type_id ) {
                directory_type_id = parseInt( directory_type_id );
            }
        }

        var currentPage = 1;

        $( item ).select2({
            allowClear: true,
            tags: createNew,
            maximumSelectionLength: maxLength,
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
        const selected_item_id    = $( item ).data( 'selected-id' );
        const selected_item_label = $( item ).data( 'selected-label' );

        const setup_selected_items = function ( element, selected_id, selected_label ) {
            if ( ! element || ! selected_id ) {
                return;
            }

            const selected_ids    = `${selected_id}`.split( ',' );
            const selected_labels = selected_label ? `${selected_label}`.split( ',' ) : [];

            selected_ids.forEach( ( id, index ) => {
                const label  = ( selected_labels.length >= ( index + 1 ) ) ? selected_labels[index] : '';
                var   option = new Option( label, id, true, true );

                $( element ).append( option );
                $( element ).trigger({
                    type: 'select2:select',
                    params: { data: { id: id,  text: selected_item_label } }
                });
            } );
        }

        setup_selected_items( item, selected_item_id, selected_item_label );
    });

}