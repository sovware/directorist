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
    var showMore = atbdp_search_listing.i18n_text.show_more;
    var showLess = atbdp_search_listing.i18n_text.show_less;
    var checkbox = $(".bads-tags .custom-control");
    checkbox.slice(4).hide();
    var show_more = $(".more-less");
    show_more.on("click", function (e) {
        e.preventDefault();
        var txt = checkbox.slice(4).is(":visible") ? showMore : showLess;
        $(this).text(txt);
        checkbox.slice(4).slideToggle(200);
        $(this).toggleClass("ad");
    });
    if(checkbox.length <= 4){
        show_more.remove();
    }

    var checkbox2 = $(".bads-custom-checks .custom-control");
    checkbox2.slice(4).hide();
    var show_more2 = $(".more-or-less");
    show_more2.on("click", function (e) {
        e.preventDefault();
        var txt = checkbox2.slice(4).is(":visible") ? showMore : showLess;
        $(this).text(txt);
        checkbox2.slice(4).slideToggle(200);
        $(this).toggleClass("sml");
    });
    if(checkbox2.length <= 4){
        show_more2.remove();
    }
    $(".bads-custom-checks").parent(".form-group").addClass("ads-filter-tags");

    var ad = $(".ads_float .ads-advanced");
    ad.css({
        visibility: 'hidden',
        height: '0',
    });
    var count = 0;
    $("body").on("click", '.more-filter', function (e) {
        count++;
        e.preventDefault();
        var currentPos = e.clientY, displayPos = window.innerHeight, height = displayPos-currentPos;
        if(count%2 === 0) {
            ad.css({
                visibility: 'hidden',
                opacity: '0',
                height: '0',
                transition: '.3s ease'
            });
        } else {
            ad.css({
                visibility: 'visible',
                height: height-70+'px',
                transition: '0.3s ease',
                opacity: '1',
            });
        }
    });

    var ad_slide = $(".ads_slide .ads-advanced");
    ad_slide.hide().slideUp();
    $(".more-filter").on("click", function (e) {
        e.preventDefault();
        ad_slide.slideToggle().show();
        $(".ads-advanced").toggleClass("ads_ov")
    });
    $(".ads-advanced").parents("div").css("overflow", "visible");

    //remove preload after window load
    $(window).load(function () {
        $("body").removeClass("atbdp_preload");
    });


})(jQuery);