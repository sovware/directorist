
;(function ($) {

    // Dashboard Search

    $('#directorist-dashboard-listing-searchform input[name=searchtext').val(''); //onready
    
    $('#directorist-dashboard-listing-searchform').on('submit', function(event) {
        var $activeTab = $('.directorist-dashboard-listing-nav-js a.tabItemActive');
        var search = $(this).find('input[name=searchtext]').val();
        directorist_dashboard_listing_ajax($activeTab,1,search);
        $('#my_listings').data('search',search);
        return false;
    });

})(jQuery);