;(function ($) {

    // Dashboard pagination

    $('.directorist-dashboard-pagination .nav-links').on('click', 'a', function(event) {
        var $link = $(this);
        var paged = $link.attr('href');
        paged = paged.split('/page/')[1];
        paged = parseInt(paged);

        var search = $('#my_listings').data('search');

        $activeTab = $('.directorist-dashboard-listing-nav-js a.tabItemActive');
        directorist_dashboard_listing_ajax($activeTab,paged,search);

        return false;
    });
    
})(jQuery);