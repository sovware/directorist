;(function ($) {

    //Star rating
    if ($('.directorist-stars').length) {
        $(".directorist-stars").barrating({
            theme: 'fontawesome-stars'
        });
    }

    if ($('.directorist-review-criteria-select').length) {
        $('.directorist-review-criteria-select').barrating({
            theme: 'fontawesome-stars'
        });
    }

})(jQuery);