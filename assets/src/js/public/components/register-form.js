jQuery(($) => {
	$('.directorist-register-form').on('submit', function(e) {
		e.preventDefault();
        
        var data = {
            action        : 'directorist_register_form',
            _nonce        : directorist.ajax_nonce,
            username      : $(this).find("input[name='username']").val(),
            email         : $(this).find("input[name='email']").val(),
            password      : $(this).find("input[name='password']").val(),
            fname         : $(this).find("input[name='fname']").val(),
            lname         : $(this).find("input[name='lname']").val(),
            website       : $(this).find("input[name='website']").val(),
            bio           : $(this).find("input[name='bio']").val(),
            previous_page : $(this).find("input[name='previous_page']").val(),
            user_type     : $(this).find("input[name='user_type']:checked").val(),
            privacy_policy: $(this).find("input[name='privacy_policy']:checked").val(),
            t_c_check     : $(this).find("input[name='t_c_check']:checked").val(),
        };

        $.ajax({
            url: directorist.ajaxurl,
            type: "POST",
            data: data,
            success: function (response) {
                if( response.success ) {
                    $('.directorist-register-error').hide();
                } else { 
                    $('.directorist-register-error').empty().show().append( response.message );
                }
            }
        });
        
	});
});