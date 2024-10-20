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

//Generate btn
console.log("rony 1")
const generateBtnWrapper = document.querySelector('.directory-generate-btn__wrapper');
if(generateBtnWrapper){
    const finalWidth = generateBtnWrapper.getAttribute('data-width');
    const btnPercentage = document.querySelector('.directory-generate-btn__percentage');
    const progressBar = document.querySelector('.directory-generate-btn--bg');

    let currentWidth = 0;

    const updateProgress = () => {
    if (currentWidth <= finalWidth) {
        btnPercentage.textContent = `${currentWidth}%`;
        progressBar.style.width = `${currentWidth}%`;
        currentWidth++;
    } else {
        clearInterval(progressInterval);
    }
    };

    const progressInterval = setInterval(updateProgress, 30);
    console.log("rony 2")
} else{
    console.log("dom not valid")
}
console.log("rony 3")


var $ = jQuery;
const axios = require('axios').default;
// handle firm step
$('body').on( 'click', '.directorist-ai-directory-submit-step-one', function( e ) {
    e.preventDefault();
    const self = this;

    let form_data = new FormData();
    form_data.append( 'action', 'directorist_ai_directory_form_step_one' );
    form_data.append( 'name', $('#directorist-ai-business-name').val() );
    form_data.append( 'location', $('#directorist-ai-business-location').val() );

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