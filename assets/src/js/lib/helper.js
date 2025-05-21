const $ = jQuery;

function get_dom_data(selector, parent) {
    selector = '.directorist-dom-data-' + selector;
    if ( ! parent ) {
        parent = document;
    }

    const el = parent.querySelector(selector);
    if ( ! el || ! el.dataset.value ) {
        return {};
    }

    const IS_SCRIPT_DEBUGGING = (directorist && directorist.script_debugging && directorist.script_debugging == '1' );

    try {
        let value = atob( el.dataset.value );
        return JSON.parse( value );
    } catch (error) {
        if (IS_SCRIPT_DEBUGGING) {
            console.log( el, error );
        }

        return {};
    }
}

function convertToSelect2( selector ) {
    const $selector = $( selector );

    const args = {
        allowClear: true,
        width: '100%',
        templateResult: function( data ) {
            if ( ! data.id ) {
                return data.text;
            }

            var iconURI = $(data.element).data('icon');
            var iconElm = `<i class="directorist-icon-mask" aria-hidden="true" style="--directorist-icon: url(${iconURI})"></i>`;

            let originalText = data.text;
            let modifiedText = originalText.replace(/^(\s*)/, "$1" + iconElm);

            var $state = $( `<div class="directorist-select2-contents">${typeof iconURI !== 'undefined' && iconURI !== '' ? modifiedText : originalText}</div>` );

            return $state;
        }
    };

    const options = $selector.find( 'option' );

    if ( options.length && options[0].textContent.length ) {
        args.placeholder = options[0].textContent;
    }

    $selector.length && $selector.select2( args )
}

export {
    convertToSelect2, get_dom_data
};

