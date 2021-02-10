;(function ($) {
    
    /* atbd alert dismiss */
    
    if($('.atbd-alert-close') !== null){
        $('.atbd-alert-close').each(function(i,e){
            $(e).on('click', function(e){
                e.preventDefault();
                $(this).parent('.atbd-alert').remove();
            });
        });
    }

})(jQuery);