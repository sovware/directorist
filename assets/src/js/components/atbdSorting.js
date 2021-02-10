;(function ($) {

    // Sorting Js 
    $('.atbdp_sorting_item').click( function() {
        var href = $(this).attr('data');
        $('#atbdp_sort').attr('action', href);
        $('#atbdp_sort').submit();
    });

    //sorting toggle
    $('.sorting span').on('click', function () {
        $(this).toggleClass('fa-sort-amount-asc fa-sort-amount-desc');
    });
    
})(jQuery);