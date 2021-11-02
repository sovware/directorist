// author sorting
(function ($) {
    /* Masonry layout */
    function authorsMasonry() {
        let authorsCard = $('.directorist-authors__cards');
        $(authorsCard).each(function(id, elm){
            let authorsCardRow = $(elm).find('.directorist-row');
            let authorMasonryInit = $(authorsCardRow).imagesLoaded(function () {
                $(authorMasonryInit).masonry({
                    percentPosition: true
                });
            })
        })
    }
    authorsMasonry();

    /* alphabet data value */
    let alphabetValue;

    $('body').on( 'click', '.directorist-alphabet', function(e) {
        e.preventDefault();
        _this = $(this);
        var alphabet   = $(this).attr("data-alphabet");
        $('body').addClass('atbdp-form-fade');
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
               $('body').removeClass('atbdp-form-fade');
               $( '.' + alphabet ).parent().addClass('active');
               alphabetValue = $(_this).attr('data-alphabet');
               authorsMasonry();
            },
            error(error) {
                console.log(error);
            },
        });
    });

    $('body').on( 'click', '.directorist-authors-pagination a', function(e) {
        e.preventDefault();
        var paged = $(this).attr('href');
        paged = paged.split('/page/')[1];
        paged = parseInt(paged);
        paged = paged !== undefined ? paged : 1;
        $('body').addClass('atbdp-form-fade');
        $.ajax({
            method: 'POST',
            url: atbdp_public_data.ajaxurl,
            data: {
                action   : 'directorist_author_pagination',
                paged    : paged,
                alphabet : alphabetValue
            },
            success( response ) {
                $('body').removeClass('atbdp-form-fade');
                $('#directorist-all-authors').empty().append( response );
                authorsMasonry();
            },
            error(error) {
                console.log(error);
            },
        });
    });
})(jQuery)