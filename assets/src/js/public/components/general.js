// Fix listing with no thumb if card width is less than 220px
(function ($) {
    if($('.directorist-listing-no-thumb').innerWidth() <= 220 ){
        $('.directorist-listing-no-thumb').addClass('directorist-listing-no-thumb--fix');
    }

    // Auhtor Profile Listing responsive fix
    if($('.directorist-author-listing-content').innerWidth() <= 650){
        $('.directorist-author-listing-content').addClass('directorist-author-listing-grid--fix');
    }
})(jQuery)