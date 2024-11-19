jQuery(($) => {
    
	$('.directorist__authentication__signup').on( 'submit', function( e ) {
		e.preventDefault();

        const $button = $(this).find('.directorist-authentication__form__btn');
        $button.addClass('directorist-btn-loading'); // Added loading class

        var formData = new FormData( this );
        formData.append( 'action', 'directorist_register_form' );
        formData.append( 'params', JSON.stringify( directorist_signin_signup_params ) );

        $.ajax( {
            url: directorist.ajaxurl,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
        } ).done( function ( {data, success} ) {
            // Removed loading class
            setTimeout( () => $button.removeClass('directorist-btn-loading'), 1000 );

            if ( ! success ) {
                $('.directorist-register-error').empty().show().append( data.error );

                return;
            }

            $('.directorist-register-error').hide();

            if ( data.message ) {
                $('.directorist-register-error').empty().show().append( data.message ).css({
                    'color'           : '#009114',
                    'background-color': '#d9efdc'
                });
            }

            if ( data.redirect_url ) {
                setTimeout( () => window.location.href = data.redirect_url, 500 );
            }
        } );
	} );
} );