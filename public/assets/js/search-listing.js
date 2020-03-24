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
        $(".ads_slide .ads-advanced").toggleClass("ads_ov")
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
            var fav_tooltip_success = '<span>'+atbdp_search.added_favourite+'</span>';
            var fav_tooltip_warning = '<span>'+atbdp_search.please_login+'</span>';

            $(".atbd_fav_tooltip").hide();
            $.post(atbdp_search_listing.ajax_url, data, function (response) {
                var staElement = $('#atbdp-fav_'+data['post_id']).selector;
                if(response === "login_required"){
                    $(staElement).children(".atbd_fav_tooltip").append(fav_tooltip_warning);
                    $(staElement).children(".atbd_fav_tooltip").fadeIn();
                    setTimeout(function () {
                        $(staElement).children(".atbd_fav_tooltip").children("span").remove();
                    },3000);

                }else if('false' === response){
                    $(staElement).removeClass('atbdp_fav_isActive');
                    $(".atbd_fav_tooltip span").remove();
                }else{
                    if ($('#atbdp-fav_'+response).selector === staElement){
                        $(staElement).addClass('atbdp_fav_isActive');
                        $(staElement).children(".atbd_fav_tooltip").append(fav_tooltip_success);
                        $(staElement).children(".atbd_fav_tooltip").fadeIn();
                        setTimeout(function () {
                            $(staElement).children(".atbd_fav_tooltip").children("span").remove();
                        },3000)
                    }
                }
            });

        })
    });

    /* range slider */

    const slider = (selector, obj) => {
        var isDraging 	= false,
            isDraging2 	= false,
            max 		= obj.maxValue + (Math.ceil(obj.maxValue*1.633/100)),
            min 		= obj.minValue,
            down 		= 'mousedown',
            up 			= 'mouseup',
            move 		= 'mousemove',

            div = `<div class="atbd-slide1" draggable="true"></div>		
		<p class="atbd-min" style="color : ${obj.fontColor}; font-size: ${obj.fontSize} "></p>		
		<input type='hidden' class="atbd-minimum" name="minimum" value=${min} /><div class="atbd-child"></div>
		`;

        if ("ontouchstart" in document.documentElement){
            down 	= 'touchstart';
            up 		= 'touchend';
            move 	= 'touchmove';
        }

        const slider = document.querySelectorAll(selector);

        slider.forEach((id, index) => {
            id.setAttribute('style', `max-width: ${obj.maxWidth}; border: ${obj.barBorder}; width: 100%; height: 10px; background: ${obj.barColor}; position: relative; border-radius: 20px;`);
            id.innerHTML = div;
            let slide1 	= id.querySelector('.atbd-slide1'),
                width 	= id.clientWidth;

            slide1.style.background = obj.pointerColor;
            slide1.style.border = obj.pointerBorder;

            id.querySelector('.atbd-min').innerHTML = min;

            var x 			= null,
                count 		= 0,
                x2 			= null,
                slid1_val 	= 0,
                slid1_val2 	= 0,
                count2 		= width;

            if(window.outerWidth < 600){
                id.classList.add('m-device');
                slide1.classList.add('m-device2');
                slide2.classList.add('m-device2');
            }
            slide1.addEventListener(down, (event) => {
                event.preventDefault();
                event.stopPropagation();
                x = event.clientX;
                if ("ontouchstart" in document.documentElement){
                    x = event.touches[0].clientX;
                }
                isDraging = true;
                event.target.classList.add('atbd-active');
            });
            window.addEventListener(up, (event2) => {
                event2.preventDefault();
                event2.stopPropagation();
                isDraging 	= false;
                slid1_val2 	= slid1_val;
                slide1.classList.remove('atbd-active');
            });
            window.addEventListener(move, (e) => {
                if(isDraging){
                    count = e.clientX + slid1_val2 * width / max - x;
                    if ("ontouchmove" in document.documentElement){
                        count = event.touches[0].clientX * width / max - x;
                    }
                    if(count < 0){
                        count = 0;
                    } else if(count > count2 - 30){
                        count = count2 - 30;
                    }
                }
                if(slide1.classList.contains('atbd-active')){
                    slid1_val 	= Math.floor(max/ width * count);
                    id.querySelector('.atbd-min').innerHTML = slid1_val;
                    id.querySelector('.atbd-minimum').value = slid1_val;
                    id.querySelector('.atbd-active').style.left = count +'px';
                    id.querySelector('.atbd-child').style.width = count+'px';
                }
            });

        });
    };

    slider ('#slider', {
        maxValue: 500,
        minValue: 0,
        maxWidth: '100%',
        barColor: '#f5548e',
        barBorder: 'none',
        pointerColor: '#000',
        pointerBorder: 'none',
        fontColor: '#f5548e',
        fontSize: '20px'
    });






})(jQuery);
