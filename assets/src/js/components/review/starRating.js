;(function ($) {

    //Star rating
    if ($('.directorist-stars').length) {
        console.log("yes")
        $(".directorist-stars").barrating({
            theme: 'fontawesome-stars'
        });
    }
    
})(jQuery);