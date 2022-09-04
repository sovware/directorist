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

function convertToSelect2( field ) {
    if ( ! field ) { return; }
    if ( ! field.elm ) { return; }
    if ( ! field.elm.length ) { return; }

    [ ...field.elm ].forEach( item => {
        const default_args = {
            allowClear: true,
            width: '100%',
            templateResult: function (data) {
                // We only really care if there is an field to pull classes from
                if ( ! data.field ) {
                    return data.text;
                }
                var $field = $(data.field);
                var $wrapper = $('<span></span>');

                $wrapper.addClass($field[0].className);
                $wrapper.text(data.text);

                return $wrapper;
            }
        };

        var args = ( field.args && typeof field.args === 'object' ) ? Object.assign( default_args, field.args ) : default_args;

        var options = $(item).find( 'option' );
        var placeholder = ( options.length ) ? options[0].innerHTML: '';
        if ( placeholder.length ) {
            args.placeholder = placeholder;
        }
        $(item).select2( args )
    });
}

export {
    get_dom_data,
    convertToSelect2
};