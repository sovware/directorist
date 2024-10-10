;
(function ($) {
    // Make sure the codes in this file runs only once, even if enqueued twice
    if ( typeof window.directorist_sorting_executed === 'undefined' ) {
        window.directorist_sorting_executed = true;
    } else {
        return;
    }
    window.addEventListener('load', () => {
        // Sorting Js
        if(!$('.directorist-instant-search').length){
            $('.directorist-dropdown__links__single-js').click(function (e) {
                e.preventDefault();
                var href = $(this).attr('data-link');
                $('#directorsit-listing-sort').attr('action', href);
                $('#directorsit-listing-sort').submit();
            });
        }

        //sorting toggle
        $('.sorting span').on('click', function () {
            $(this).toggleClass('fa-sort-amount-asc fa-sort-amount-desc');
        });
    });
})(jQuery);