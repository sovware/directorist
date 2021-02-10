;(function ($) {

    // User Dashboard Table More Button
    
    $('.directorist-dashboard-listings-tbody').on("click", '.directorist_btn-more', function(e){
        e.preventDefault();
        $(this).toggleClass('active');
        $(".directorist_dropdown-menu").removeClass("active");
        $(this).next(".directorist_dropdown-menu").toggleClass("active");
        e.stopPropagation();
    });

    $(document).bind("click", function (e) {
        if(!$(e.target).parents().hasClass('directorist_dropdown-menu__list')){
            $(".directorist_dropdown-menu").removeClass("active");
            $(".directorist_btn-more").removeClass("active");
        }
    });
    
})(jQuery);