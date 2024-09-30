jQuery(($) => {
    
	$('.directorist__authentication__signup').on( 'submit', function( e ) {
		e.preventDefault();

        var formData = new FormData( this );
        formData.append( 'action', 'directorist_register_form' );
        formData.append( 'new_user_registration', ajax_login_object.new_user_registration );
        formData.append( 'enable_registration_password', ajax_login_object.enable_registration_password );
        
        $.ajax( {
            url: directorist.ajaxurl,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
        } ).done( function ( {data, success} ) {
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