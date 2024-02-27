jQuery(($) => {
	$('.directorist__authentication__signup').on('submit', function(e) {
		e.preventDefault();

        var formData = new FormData(this);
        formData.append('action', 'directorist_register_form');

        $.ajax({
            url: directorist.ajaxurl,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            success: function (response) {
                console.log(response.data)
                if( response.success ) {
                    $('.directorist-register-error').hide();
                    if( response.redirect_url ) {
                        $('.directorist-register-error').empty().show().append( response.redirect_message ).css({
                            'color'           : '#009114',
                            'background-color': '#d9efdc'
                        });
                        setTimeout(function () {
                            window.location.href = response.redirect_url;
                        }, 500)
                    }
                } else { 
                    $('.directorist-register-error').empty().show().append( response.data );
                }
            }
        });
        
	});
});