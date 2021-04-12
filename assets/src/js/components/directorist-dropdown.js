;(function ($) {
    
    // Directorist Dropdown
    $('body').on('click', '.directorist-dropdown-js .directorist-dropdown__toggle-js', function(e){
        e.preventDefault();
        $('.directorist-dropdown__links').hide();
        $(this).siblings('.directorist-dropdown__links-js').toggle();
    });

    // Hide Clicked Anywhere
    $(document).bind('click', function(e) {
        let clickedDom = $(e.target);
        if ( ! clickedDom.parents().hasClass('directorist-dropdown-js') )
        $('.directorist-dropdown__links-js').hide();
    });

})(jQuery);