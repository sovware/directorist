window.addEventListener('DOMContentLoaded', () => {
    ;(function ($) {
        //Star rating
        if ($('.directorist-review-criteria-select').length) {
            $('.directorist-review-criteria-select').barrating({
                theme: 'fontawesome-stars'
            });
        }
    })(jQuery);
});