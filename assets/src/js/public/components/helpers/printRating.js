;(function ($) {
    
    /* Helper Function for priting static rating */
    function print_static_rating($star_number) {
        var v;
        if ($star_number) {
            v = '<ul>';
            for (var i = 1; i <= 5; i++) {
                v += (i <= $star_number)
                    ? "<li><span class='directorist-rate-active'></span></li>"
                    : "<li><span class='directorist-rate-disable'></span></li>";
            }
            v += '</ul>';
        }

        return v;
    }

})(jQuery);