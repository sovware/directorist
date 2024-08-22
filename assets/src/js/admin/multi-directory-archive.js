// Scrips
import './components/import-directory-modal';
import './components/delete-directory-modal';
import './components/directory-migration-modal';

window.addEventListener('DOMContentLoaded', () => {
    var $ = jQuery;
    const axios = require('axios').default;

    // Migration Link
    $( '.directorist_directory_template_library' ).on( 'click', function( e ) {
        e.preventDefault();
        const self = this;

        $( '.cptm-create-directory-modal__action' ).after( "<span class='directorist_template_notice'>Installing Templetiq, Please wait..</span>" );

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

    $( '.directorist_build_with_ai' ).on( 'click', function( e ) {
        e.preventDefault();
        $( '.directorist-build-directory-with-ai' ).css({ display: "block" });
    });

    // build with AI
    $( '.directorist-ai-directory-builder' ).on( 'click', function( e ) {
        e.preventDefault();
        const self = this;

        $( self ).after( "<span class='directorist_template_notice_ai'>Thinking..It may take a minute or two.</span>" );

        let form_data = new FormData();
        form_data.append( 'action', 'directorist_directory_ai_prompt' );
        form_data.append('name', $('.directory_ai_name').val() );
        form_data.append('details', $('.directory_ai_details').val() );
        form_data.append('directorist_nonce', directorist_admin.directorist_nonce);

        // Response Success Callback
        const responseSuccessCallback = function ( response ) {

            console.log( response.data );



            if ( response.data.status.success ) {

                $( '.directorist_template_notice_ai' ).html( response.data.status.message );

                console.log(response.data.status.fields);

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
});