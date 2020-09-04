(function ($) {
    $('#at_biz_dir-location').select2({
        placeholder: atbdp_search_listing.i18n_text.location_selection,
        allowClear: true,
        templateResult: function (data) {
            // We only really care if there is an element to pull classes from
            if (!data.element) {
                return data.text;
            }

            var $element = $(data.element);

            var $wrapper = $('<span></span>');
            $wrapper.addClass($element[0].className);

            $wrapper.text(data.text);

            return $wrapper;
        }
    });

    // Category
    $('#at_biz_dir-category').select2({
        placeholder: atbdp_search_listing.i18n_text.category_selection,
        allowClear: true,
        templateResult: function (data) {
            // We only really care if there is an element to pull classes from
            if (!data.element) {
                return data.text;
            }

            var $element = $(data.element);

            var $wrapper = $('<span></span>');
            $wrapper.addClass($element[0].className);

            $wrapper.text(data.text);

            return $wrapper;
        }
    });

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


    $('body').on('click', '.more-or-less', function(event) {
        event.preventDefault();
        var item = $(this).closest('.atbdp_cf_checkbox, .ads-filter-tags');
        var abc2 = $(item).find('.custom-control');
        $(abc2).slice(4, abc2.length).slideUp();

        $(this).toggleClass('active');

       if($(this).hasClass('active')){
           $(this).text(atbdp_search_listing.i18n_text.show_less);
           $(abc2).slice(4, abc2.length).slideDown();
       } else {
           $(this).text(atbdp_search_listing.i18n_text.show_more);
           $(abc2).slice(4, abc2.length).slideUp();
       }

    });


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
        $(".ads_slide .ads-advanced").toggleClass("ads_ov");
        atbd_callingSlider();
    });
    $(".ads-advanced").parents("div").css("overflow", "visible");



    //remove preload after window load
    $(window).load(function () {
        $("body").removeClass("atbdp_preload");
        $('.button.wp-color-result').attr('style', ' ');
    });
    $('.atbdp_mark_as_fav').each(function () {
        $(this).on('click', function (event) {
            event.preventDefault();
            var data = {
                'action': 'atbdp-favourites-all-listing',
                'post_id': $(this).data('listing_id')
            };
            var fav_tooltip_success = '<span>'+atbdp_search_listing.i18n_text.added_favourite+'</span>';
            var fav_tooltip_warning = '<span>'+atbdp_search_listing.i18n_text.please_login+'</span>';

            $(".atbd_fav_tooltip").hide();
            $.post(atbdp_search_listing.ajax_url, data, function (response) {
                var post_id = data['post_id'].toString();
                var staElement = $('#atbdp-fav_'+ post_id);
                var data_id = staElement.attr('data-listing_id');

                if (response === "login_required") {
                    staElement.children(".atbd_fav_tooltip").append(fav_tooltip_warning);
                    staElement.children(".atbd_fav_tooltip").fadeIn();
                    setTimeout(function () {
                        staElement.children(".atbd_fav_tooltip").children("span").remove();
                    },3000);

                }else if('false' === response){
                    staElement.removeClass('atbdp_fav_isActive');
                    $(".atbd_fav_tooltip span").remove();
                }else{
                    if ( data_id === post_id){
                        staElement.addClass('atbdp_fav_isActive');
                        staElement.children(".atbd_fav_tooltip").append(fav_tooltip_success);
                        staElement.children(".atbd_fav_tooltip").fadeIn();
                        setTimeout(function () {
                            staElement.children(".atbd_fav_tooltip").children("span").remove();
                        },3000)
                    }
                }
            });

        })
    });
})(jQuery);

/* advanced search form reset */
function adsFormReset() {
    const adsForm = document.querySelector(".atbd_ads-form");
    adsForm.querySelectorAll("input[type='text']").forEach(function (el) {
        el.value = "";
    });
    adsForm.querySelectorAll("input[type='radio']").forEach(function (el) {
        el.checked = false;
    });
    adsForm.querySelectorAll("input[type='checkbox']").forEach(function (el) {
        el.checked = false;
    });
    adsForm.querySelectorAll("select").forEach(function (el) {
        el.selectedIndex = 0;
    });
    const irisPicker = adsForm.querySelector("input.wp-picker-clear");
    if(irisPicker !== null){
        irisPicker.click();
    }
    const rangeValue = adsForm.querySelector(".atbd-current-value span");
    if(rangeValue !== null){
        rangeValue.textContent = "0";
    }
}
if(document.querySelector(".atbd_ads-form #atbdp_reset") !== null){
    document.querySelector(".atbd_ads-form #atbdp_reset").addEventListener("click", function (e) {
        e.preventDefault();
        adsFormReset();
        atbd_callingSlider(0);
    });
}
if(document.querySelector("#bdlm-search-area #atbdp_reset") !== null){
    document.querySelector("#bdlm-search-area #atbdp_reset").addEventListener("click", function (e) {
        e.preventDefault();
        adsFormReset();
        atbd_callingSlider(0);
    })
}
