function get_dom_data ( key ) {
    const dom_elm = document.getElementById( key );
    if ( ! dom_elm ) { return ''; }

    var dom_content = dom_elm.innerHTML;
    dom_content = dom_content.replace( /(<!-- )(.*)( -->)/, '$2' );

    if ( ! dom_content.length ) { return ''; }
    var dom_data = '';

    try {
        dom_data = JSON.parse( dom_content );
        // console.log( { key, dom_data, dom_content } );
    } catch ( error ) {
        // console.log( { key, error, content: dom_content } );
    }

    if ( ! dom_data ) { return ''; }

    return dom_data;
}


export { get_dom_data };