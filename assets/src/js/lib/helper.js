const $ = jQuery;

function get_dom_data(key, parent) {
    // var elmKey = 'directorist-dom-data-' + key;
    var elmKey = 'directorist-dom-data-' + key;
    var dataElm = (parent) ? parent.getElementsByClassName(elmKey) : document.getElementsByClassName(elmKey);

    if (!dataElm) {
        return '';
    }

    var is_script_debugging = (directorist && directorist.script_debugging && directorist.script_debugging == '1') ? true : false;

    try {
        let dataValue = atob(dataElm[0].dataset.value);
        dataValue = JSON.parse(dataValue);
        return dataValue;
    } catch (error) {
        if (is_script_debugging) {
            console.warn({
                key,
                dataElm,
                error
            });
        }
        return '';
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

