// author sorting
(function ($) {
    $('body').on( 'click', '.directorist-alphabet', function() {
        $.ajax({
            method: 'POST',
            url: atbdp_public_data.ajaxurl,
            data: {
                action   : 'directorist_author_alpha_sorting',
                _nonce   : $(this).attr("data-nonce"),
                alphabet : $(this).attr("data-alphabet")
            },
            success( response ) {
               $('#directorist-all-authors').empty().append( response );
            },
            error(error) {
                console.log(error);
            },
        });
    });
})(jQuery)