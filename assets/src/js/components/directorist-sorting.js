;(function ($) {

    // Sorting Js 
    $('.directorist-dropdown__links--single-js').click( function() {
        var href = $(this).attr('data');
        $('#directorsit-listing-sort').attr('action', href);
        $('#directorsit-listing-sort').submit();
    });

    //sorting toggle
    $('.sorting span').on('click', function () {
        $(this).toggleClass('fa-sort-amount-asc fa-sort-amount-desc');
    });
    
})(jQuery);