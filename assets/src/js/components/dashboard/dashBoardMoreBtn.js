;(function ($) {

    // User Dashboard Table More Button
    
    $('.directorist-dashboard-listings-tbody').on("click", '.directorist-btn-more', function(e){
        e.preventDefault();
        $(this).toggleClass('active');
        $(".directorist-dropdown-menu").removeClass("active");
        $(this).next(".directorist-dropdown-menu").toggleClass("active");
        e.stopPropagation();
    });

    $(document).bind("click", function (e) {
        if(!$(e.target).parents().hasClass('directorist-dropdown-menu__list')){
            $(".directorist-dropdown-menu").removeClass("active");
            $(".directorist-btn-more").removeClass("active");
        }
    });
    
})(jQuery);