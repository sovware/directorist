// Scrips
import './components/delete-directory-modal';
import './components/directory-migration-modal';
import './components/import-directory-modal';

window.addEventListener('load', () => {
    var $ = jQuery;
    const axios = require('axios').default;

    // Migration Link
    $( '.directorist_directory_template_library' ).on( 'click', function( e ) {
        e.preventDefault();
        const self = this;

        $( '.cptm-create-directory-modal__action' ).after( "<span class='directorist_template_notice'>Installing Templatiq, Please wait..</span>" );

        let form_data = new FormData();
        form_data.append( 'action', 'directorist_directory_type_library' );
        form_data.append('directorist_nonce', directorist_admin.directorist_nonce);

        // Response Success Callback
        const responseSuccessCallback = function ( response ) {

            if ( response?.data?.success ) {
                let msg = ( response?.data?.message ) ?? 'Imported successfully!';

                $( '.directorist_template_notice' ).text( msg );

                location.reload();
                return;
            }

            responseFaildCallback( response );
        };

        // Response Error Callback
        const responseFaildCallback = function ( response ) {
            // console.log( { response } );

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
        axios.post( directorist_admin.ajax_url, form_data ).then( response => {
            responseSuccessCallback( response );
        }).catch( response => {
            responseFaildCallback( response );
        });
    });


    // show the form
    $( '.directorist-ai-directory-creation' ).on( 'click', function( e ) {
        e.preventDefault();
        const self = this;

        let form_data = new FormData();
        form_data.append( 'action', 'directorist_ai_directory_form' );

        // Response Success Callback
        const responseAiFormSuccess = function ( response ) {

            if ( response?.data?.success ) {
                $( '.cptm-create-directory-modal__body' ).empty().html( response?.data?.html );
                return;
            }

            alert('Something went wrong! Please try again');
        };

        // Send Request
        axios.post( directorist_admin.ajax_url, form_data ).then( response => {
            responseAiFormSuccess( response );
        }).catch( response => {
            alert('Something went wrong! Please try again');
        });
    });

});

document.addEventListener('load', () =>{
        
})


var $ = jQuery;
const axios = require('axios').default;
// handle firm step
$('body').on( 'click', '.directorist_generate_ai_directory', function( e ) {
    e.preventDefault();
    const self = this;

    var step = $(self).data('step');
    let keywords = $('input[name="keywords[]"]:checked').map(function() {
        return this.value;
    }).get();

    let form_data = new FormData();
    form_data.append( 'action', 'directorist_ai_directory_creation' );
    form_data.append( 'prompt', $('.directorist-ai-prompt').val() );
    form_data.append( 'keywords', keywords );
    form_data.append( 'step', step );

    // Response Success Callback
    const responseAiFormSuccess = function ( response ) {

        if ( response?.data?.success ) {

            if( step == 1 ) {
                $( '.directorist-ai-keywords' ).empty().html( response?.data?.html );
                $(self).data('step', step + 1 );
            }

            console.log( response );
            return;
        }

        alert('Something went wrong! Please try again');
    };

    // Send Request
    axios.post( directorist_admin.ajax_url, form_data ).then( response => {
        responseAiFormSuccess( response );
    }).catch( response => {
        alert('Something went wrong! Please try again');
    });
});