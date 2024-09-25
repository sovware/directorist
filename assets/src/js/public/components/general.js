// Fix listing with no thumb if card width is less than 220px
(function ($) {
    window.addEventListener('load', () => {
        if ($('.directorist-listing-no-thumb').innerWidth() <= 220) {
            $('.directorist-listing-no-thumb').addClass('directorist-listing-no-thumb--fix');
        }
        // Auhtor Profile Listing responsive fix
        if ($('.directorist-author-listing-content').innerWidth() <= 750) {
            $('.directorist-author-listing-content').addClass('directorist-author-listing-grid--fix');
        }
        // Directorist Archive responsive fix
        if ($('.directorist-archive-grid-view').innerWidth() <= 500) {
            $('.directorist-archive-grid-view').addClass('directorist-archive-grid--fix');
        }

        // Back Button to go back to the previous page
        $('body').on('click', '.directorist-btn__back', function(e) {
            window.history.back();
        });
    });
})(jQuery)