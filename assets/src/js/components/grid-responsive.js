;(function ($) {
    
    /* Responsive grid control */
    $(document).ready(function () {
        var d_wrapper = $("#directorist.atbd_wrapper");
        var columnLeft = $(".atbd_col_left.col-lg-8");
        var columnRight = $(".directorist.col-lg-4");
        var tabColumn = $(".atbd_dashboard_wrapper .tab-content .tab-pane .col-lg-4");
        var w_size = d_wrapper.width();
        if (w_size >= 500 && w_size <= 735) {
            columnLeft.toggleClass("col-lg-8");
            columnRight.toggleClass("col-lg-4");
        }
        if (w_size <= 600) {
            d_wrapper.addClass("size-xs");
            tabColumn.toggleClass("col-lg-4");
        }

        var listing_size = $(".atbd_dashboard_wrapper .atbd_single_listing").width();
        if (listing_size < 200) {
            $(".atbd_single_listing .db_btn_area").addClass("db_btn_area--sm");
        }
    });

})(jQuery);