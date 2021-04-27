;(function ($) {

    /* Directorist alert dismiss */
    if($('.directorist-alert__close') !== null){
        $('.directorist-alert__close').each(function(i,e){
            $(e).on('click', function(e){
                e.preventDefault();
                $(this).closest('.directorist-alert').remove();
            });
        });
    }

})(jQuery);