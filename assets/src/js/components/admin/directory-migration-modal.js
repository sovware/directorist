var $ = jQuery;
const axios = require('axios').default;

// Migration Link
$( '.atbdp-directory-migration-link' ).on( 'click', function( e ) {
    e.preventDefault();
    const self = this;

    $( '.cptm-directory-migration-form' ).find( '.cptm-comfirmation-text' ).html( 'Please wait...' );
    $( '.atbdp-directory-migration-cencel-link' ).remove();
    
    $( this ).html( '<i class="fas fa-circle-notch fa-spin"></i> Migrating' );
    $( this ).addClass( 'atbdp-disabled' );

    let form_data = new FormData();
    form_data.append( 'action', 'directorist_force_migrate' );

    // Response Success Callback
    const responseSuccessCallback = function ( response ) {
        console.log( { response } );

        if ( response?.data?.success ) {
            let msg = ( response?.data?.message ) ?? 'Migration Successful';
            let alert_content = `
            <div class="cptm-section-alert-content">
                <div class="cptm-section-alert-icon cptm-alert-success">
                    <span class="fa fa-check"></span>
                </div>

                <div class="cptm-section-alert-message">${msg}</div>
            </div>
            `;
            
            $( '.cptm-directory-migration-form' ).find( '.cptm-comfirmation-text' ).html( alert_content );
            $( self ).remove();

            location.reload();
            return;
        }

        responseFaildCallback( response );
    };

    // Response Error Callback
    const responseFaildCallback = function ( response ) {
        console.log( { response } );

        let msg = ( response?.data?.message ) ?? 'Something went wrong please try again';
        let alert_content = `
        <div class="cptm-section-alert-content">
            <div class="cptm-section-alert-icon cptm-alert-error">
                <span class="fa fa-times"></span>
            </div>

            <div class="cptm-section-alert-message">${msg}</div>
        </div>
        `;
        
        $( '.cptm-directory-migration-form' ).find( '.cptm-comfirmation-text' ).html( alert_content );
        $( self ).remove();
    };

    // Send Request
    axios.post( ajax_data.ajax_url, form_data ).then( response => {
        responseSuccessCallback( response );
    }).catch( response => {
        responseFaildCallback( response );
    });
});