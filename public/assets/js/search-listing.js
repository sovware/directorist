(function ($) {
    $('#at_biz_dir-location').select2({
        placeholder: atbdp_search_listing.i18n_text.location_selection,
        allowClear: true
    });

// Category
    $('#at_biz_dir-category').select2({
        placeholder: atbdp_search_listing.i18n_text.category_selection,
        allowClear: true
    });

    $("[data-toggle='tooltip']").tooltip();

    //ad search js
    var checkbox = $(".bads-tags .custom-control");
    checkbox.slice(4).hide();
    var show_more = $(".more-less");
    show_more.on("click", function (e) {
        e.preventDefault();
        var txt = checkbox.slice(4).is(":visible") ? "Show More" : "Show Less";
        $(this).text(txt);
        checkbox.slice(4).slideToggle(200);
        $(this).toggleClass("ad");
    });
    if(checkbox.length <= 4){
        show_more.remove();
    }

    var ad = $(".ads-advanced");
    ad.hide().slideUp();
    $(".more-filter").on("click", function (e) {
        e.preventDefault();
        ad.slideToggle().show();
    })


})(jQuery);