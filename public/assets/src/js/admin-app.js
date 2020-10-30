import './cpt_manager';
const axios = require('axios').default;

document.querySelectorAll('.la-icon i').forEach(function(item) {
    className.push(item.getAttribute('class'))
})

var $ = jQuery;

// Toggle Modal
$( '.cptm-modal-toggle' ).on( 'click', function( e ) {
    e.preventDefault();
    let target_class = $( this ).data( 'target' );
    
    $( '.' + target_class ).toggleClass('active');
});


// cptm-import-directory-form
let term_id = 0;
$( '.cptm-import-directory-form' ).on( 'submit', function( e ) {
    e.preventDefault();

    let form_data = new FormData();
    form_data.append( 'action', 'save_imported_post_type_data' );

    if ( Number.isInteger( term_id ) && term_id > 0 ) {
        form_data.append( 'term_id', term_id );
    } 

    let form_fields = $( this ).find( '.cptm-form-field' );
    let general_fields = [ 'text', 'number' ];

    for ( let field of form_fields ) {
        if ( ! field.name.length ) { continue; }

        // General fields
        if ( general_fields.includes( field.type ) ) {
            form_data.append( field.name, $( field ).val() );
        }

        // Media fields
        if ( 'file' === field.type ) {
            form_data.append( field.name, field.files[0] );
        }
    }

    axios.post( ajax_data.ajax_url, form_data )
        .then( response => {
            console.log( { response } );

            if ( response.data.term_id && Number.isInteger( response.data.term_id ) && response.data.term_id > 0 ) {
                term_id = response.data.term_id;
                // console.log( 'Term ID has been updated' );
            }

            if ( response.data && response.data.status && response.data.status.success ) {
                // console.log( 'reloading...' );
                location.reload();
            }
        })
        .catch( error => {
            console.log( { error } );
        } );

    // console.log( { form_fields } );
});