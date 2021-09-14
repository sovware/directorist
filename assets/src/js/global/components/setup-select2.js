import { convertToSelect2 } from './../../lib/helper';

const $ = jQuery;

window.addEventListener('load', initSelect2 );
document.body.addEventListener( 'directorist-search-form-nav-tab-reloaded', initSelect2 );
document.body.addEventListener( 'directorist-reload-select2-fields', initSelect2 );

// Select 2
function initSelect2() {
    const selectors = [
        '.directorist-select select', // Search form review field
        '#directorist-select-js',
        '#directorist-search-category-js',
        '#directorist-select-st-s-js',
        '#directorist-select-sn-s-js',
        '#directorist-select-mn-e-js',
        '#directorist-select-tu-e-js',
        '#directorist-select-wd-s-js',
        '#directorist-select-wd-e-js',
        '#directorist-select-th-e-js',
        '#directorist-select-fr-s-js',
        '#directorist-select-fr-e-js',
        '#directorist-location-select',
        '#directorist-category-select',
        '.select-basic',
        '#loc-type',
        '.bdas-location-search',
        '.directorist-location-select',
        '#at_biz_dir-category',
        '#cat-type',
        '.bdas-category-search',
        '.directorist-category-select',
    ];

    selectors.forEach( field => {
        convertToSelect2( {
            elm: $( field )
        } );
    } );
}
