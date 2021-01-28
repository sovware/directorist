;(function ($) {

    // User Dashboard Tab

    $(function () {
        var hash = window.location.hash;
        var selectedTab = $('.navbar .menu li a [target= "' + hash + '"]');
    });


    // store the currently selected tab in the hash value

    $("ul.atbd-dashboard-nav > li > a.atbd_tn_link").on("click", function (e) {
        var id = $(e.target).attr("target").substr();
        window.location.hash = "#active_" + id;
        e.stopPropagation();
    });

})(jQuery);