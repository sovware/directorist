// author sorting
(function ($) {
    window.addEventListener('load', () => {
        /* Masonry layout */
        function authorsMasonry() {
            let authorsCard = $('.directorist-authors__cards');
            $(authorsCard).each(function (id, elm) {
                let authorsCardRow = $(elm).find('.directorist-row');
                let authorMasonryInit = $(authorsCardRow).imagesLoaded(function () {
                    $(authorMasonryInit).masonry({
                        percentPosition: true,
                        horizontalOrder: true
                    });
                })
            })
        }
        authorsMasonry();

        /* alphabet data value */
        let alphabetValue;

        /* authors nav default active item */
        if ($('.directorist-authors__nav').length) {
            $('.directorist-authors__nav ul li:first-child').addClass('active');
        }
        /* authors nav item */
        $('body').on('click', '.directorist-alphabet', function (e) {
            e.preventDefault();
            var _this = $(this);
            var alphabet = $(this).attr("data-alphabet");
            $('body').addClass('atbdp-form-fade');
            $.ajax({
                method: 'POST',
                url: directorist.ajaxurl,
                data: {
                    action: 'directorist_author_alpha_sorting',
                    _nonce: $(this).attr("data-nonce"),
                    alphabet: $(this).attr("data-alphabet")
                },
                success(response) {
                    $('#directorist-all-authors').empty().append(response);
                    $('body').removeClass('atbdp-form-fade');
                    $('.' + alphabet).parent().addClass('active');
                    alphabetValue = $(_this).attr('data-alphabet');
                    authorsMasonry();
                },
                error(error) {
                    //console.log(error);
                },
            });
        });

        /* authors pagination */
        $('body').on('click', '.directorist-authors-pagination a', function (e) {
            e.preventDefault();
            var paged = $(this).text();
            if($(this).hasClass('prev')){
                paged = parseInt($('.directorist-authors-pagination .current').text()) - 1;
            }
            if($(this).hasClass('next')){
                paged = parseInt($('.directorist-authors-pagination .current').text()) + 1;
            }
            $('body').addClass('atbdp-form-fade');
            var getAlphabetValue = alphabetValue;
            $.ajax({
                method: 'POST',
                url: directorist.ajaxurl,
                data: {
                    action: 'directorist_author_pagination',
                    paged: paged
                },
                success(response) {
                    $('body').removeClass('atbdp-form-fade');
                    $('#directorist-all-authors').empty().append(response);
                    authorsMasonry();
                    if(document.querySelector('.'+getAlphabetValue) !== null){
                        document.querySelector('.'+getAlphabetValue).closest('li').classList.add('active');
                    }else if ($('.directorist-authors__nav').length) {
                        $('.directorist-authors__nav ul li:first-child').addClass('active');
                    };

                },
                error(error) {
                    //console.log(error);
                },
            });
        });
    });
})(jQuery)