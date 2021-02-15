;(function ($) {

    // Dashboard pagination

    $('.directorist-dashboard-pagination .nav-links').on('click', 'a', function(event) {
        var $link = $(this);
        var paged = $link.attr('href');
        paged = paged.split('/page/')[1];
        paged = parseInt(paged);

        var search = $('#directorist-dashboard-mylistings-js').data('search');

        $activeTab = $('.directorist-dashboard-listing-nav-js a.directorist-tab__nav__active');
        directorist_dashboard_listing_ajax($activeTab,paged,search);

        return false;
    });
    
})(jQuery);