function get_dom_data ( key ) {
    var dom_content = document.body.innerHTML;

    if ( ! dom_content.length ) { return ''; }

    var pattern = new RegExp("(<!-- directorist-dom-data::" + key + "\\s)(.+)(\\s-->)");
    var terget_content = pattern.exec( dom_content );

    if ( ! terget_content ) { return ''; }
    if ( typeof terget_content[2] === 'undefined' ) { return ''; }
    
    var dom_data = JSON.parse( terget_content[2] );

    if ( ! dom_data ) { return ''; }

    return dom_data;
}


export { get_dom_data };