jQuery(($) => {
    
	$('.directorist__authentication__signup').on( 'submit', function( e ) {
		e.preventDefault();

        var formData = new FormData( this );
        formData.append( 'action', 'directorist_register_form' );
        formData.append( 'new_user_registration', account_object.new_user_registration );
        formData.append( 'enable_registration_password', account_object.enable_registration_password );
        formData.append( 'enable_registration_website', account_object.enable_registration_website );
        formData.append( 'registration_website_required', account_object.registration_website_required );
        formData.append( 'enable_registration_first_name', account_object.enable_registration_first_name );
        formData.append( 'registration_first_name_required', account_object.registration_first_name_required );
        formData.append( 'enable_registration_last_name', account_object.enable_registration_last_name );
        formData.append( 'registration_last_name_required', account_object.registration_last_name_required );
        formData.append( 'enable_registration_bio', account_object.enable_registration_bio );
        formData.append( 'registration_bio_required', account_object.registration_bio_required );
        formData.append( 'enable_registration_privacy', account_object.enable_registration_privacy );
        formData.append( 'enable_user_type', account_object.enable_user_type );
        formData.append( 'enable_registration_terms', account_object.enable_registration_terms );
        formData.append( 'auto_login_after_registration', account_object.auto_login_after_registration );
        formData.append( 'redirection_after_registration', account_object.redirection_after_registration );

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