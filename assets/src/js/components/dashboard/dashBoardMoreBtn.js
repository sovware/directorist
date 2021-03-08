;(function ($) {

    // User Dashboard Table More Button
    
    $('.directorist-dashboard-listings-tbody').on("click", '.directorist-btn-more', function(e){
        e.preventDefault();
        $(this).removeClass('active');
        $(".directorist-dropdown-menu").removeClass("active");
        console.log($(this).next('.active'))
        if(!$(this).next('.active')){
            $(this).next(".directorist-dropdown-menu").addClass("active");
        }
        
        e.stopPropagation();
    });

    $(document).bind("click", function (e) {
        if(!$(e.target).parents().hasClass('directorist-dropdown-menu__list')){
            $(".directorist-dropdown-menu").removeClass("active");
            $(".directorist-btn-more").removeClass("active");
        }
    });
    
})(jQuery);