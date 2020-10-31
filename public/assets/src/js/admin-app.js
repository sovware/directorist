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

// Change label on file select/change
$('.cptm-file-field').on( 'change', function( e ) {
    let target_id = $( this ).attr( 'id' );
    $( 'label[for='+ target_id +']').text( 'Change' );
});

// cptm-import-directory-form
let term_id = 0;
$( '.cptm-import-directory-form' ).on( 'submit', function( e ) {
    e.preventDefault();

    let form_feedback = $( this ).find( '.cptm-form-group-feedback' );
    
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

    const self = this;
    form_feedback.html('');

    axios.post( ajax_data.ajax_url, form_data )
        .then( response => {
            // console.log( { response } );

            // Store term ID if exist
            if ( response.data.term_id && Number.isInteger( response.data.term_id ) && response.data.term_id > 0 ) {
                term_id = response.data.term_id;
                // console.log( 'Term ID has been updated' );
            }

            // Show status log
            if ( response.data && response.data.status.status_log ) {
                let status_log = response.data.status.status_log;
                for ( let status in status_log ) {
                    let alert = '<div class="cptm-form-alert cptm-'+ status_log[status].type +'">'+ status_log[status].message +'</div>';
                    form_feedback.append( alert );
                }
            }

            // Reload the page if success
            if ( response.data && response.data.status && response.data.status.success ) {
                // console.log( 'reloading...' );
                $( self ).trigger("reset");
                location.reload();
            }
        })
        .catch( error => {
            console.log( { error } );
        } );
});