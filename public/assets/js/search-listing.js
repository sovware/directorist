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
    if (checkbox.length <= 4) {
        show_more.remove();
    }


    var item = $('.custom-control').closest('.bads-custom-checks');
    item.each(function (index, el) {
        var count = 0;
        var abc = $(el)[0];
        var abc2 = $(abc).children('.custom-control');
        if(abc2.length <= 4){
            $(abc2).closest('.bads-custom-checks').next('a.more-or-less').hide();
        }
        $(abc2).slice(4, abc2.length).hide();

    });

    $('.more-or-less').each(function(index, el) {
        var count = 1;
        $(el).on('click', function(event) {
            event.preventDefault();
            count++;
            var item = $(this).closest('.ads-filter-tags');

            var abc2 = $(item).find('.custom-control');
            $(abc2).slice(4, abc2.length).hide();
            if (count%2 == 1) {
                $(this).removeClass('active');
                $(this).text('Show More');
                $(abc2).slice(4, abc2.length).hide();
            } else {
                $(this).addClass('active');
                $(this).text('Show Less');
                $(abc2).slice(4, abc2.length).show();
            }

        });
    });
    // var checkbox2 = $(".bads-custom-checks .custom-control");
    // if (checkbox2.length <= 4) {
    //     $(".more-or-less").remove();
    // }


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
        var currentPos = e.clientY, displayPos = window.innerHeight, height = displayPos - currentPos;

        if (count % 2 === 0) {
            $(this).closest('.atbd_wrapper').find('.ads_float').find('.ads-advanced').css({
                visibility: 'hidden',
                opacity: '0',
                height: '0',
                transition: '.3s ease'
            });
        } else {
            $(this).closest('.atbd_wrapper').find('.ads_float').find('.ads-advanced').css({
                visibility: 'visible',
                height: height - 70 + 'px',
                transition: '0.3s ease',
                opacity: '1',
            });
        }
    });

    var ad_slide = $(".ads_slide .ads-advanced");
    ad_slide.hide().slideUp();
    $(".more-filter").on("click", function (e) {
        e.preventDefault();
        $(this).closest('.atbd_wrapper').find('.ads_slide').find('.ads-advanced').slideToggle().show();
        $(".ads_slide .ads-advanced").toggleClass("ads_ov")
    });
    $(".ads-advanced").parents("div").css("overflow", "visible");

    //remove preload after window load
    $(window).load(function () {
        $("body").removeClass("atbdp_preload");
    });


})(jQuery);