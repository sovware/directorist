jQuery(($) => {
	$('.directorist__authentication__signup').on('submit', function(e) {
		e.preventDefault();
        
        var data = {
            action           : 'directorist_register_form',
            directorist_nonce: $(this).find("input[name='directorist_nonce']").val(),
            username         : $(this).find("input[name='username']").val(),
            email            : $(this).find("input[name='email']").val(),
            password         : $(this).find("input[name='password']").val(),
            fname            : $(this).find("input[name='fname']").val(),
            lname            : $(this).find("input[name='lname']").val(),
            website          : $(this).find("input[name='website']").val(),
            bio              : $(this).find("input[name='bio']").val(),
            previous_page    : $(this).find("input[name='previous_page']").val(),
            user_type        : $(this).find("input[name='user_type']:checked").val(),
            privacy_policy   : $(this).find("input[name='privacy_policy']:checked").val(),
            t_c_check        : $(this).find("input[name='t_c_check']:checked").val(),
        };

        $.ajax({
            url: directorist.ajaxurl,
            type: "POST",
            data: data,
            success: function (response) {
                console.log( response )
                if( response.success ) {
                    $('.directorist-register-error').hide();
                    if( response.redirect_url ) {
                        window.location.href = response.redirect_url;
                    }
                } else { 
                    $('.directorist-register-error').empty().show().append( response.message );
                }
            }
        });
        
	});
});