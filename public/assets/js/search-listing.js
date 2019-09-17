(function ($) {
    $('#at_biz_dir-location').select2({
        placeholder: atbdp_search_listing.i18n_text.location_selection,
        allowClear: false,
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
        allowClear: false,
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

})(jQuery);


/*
    get current location
*/
(function () {
    var x = document.querySelector(".location-name");

    function getLocation(){
        if (navigator.geolocation){
            navigator.geolocation.getCurrentPosition(showPosition,showError);
        }
        else{
            x.value="Geolocation is not supported by this browser.";
        }
    }

    function showPosition(position){
        lat=position.coords.latitude;
        lon=position.coords.longitude;
        displayLocation(lat,lon);
    }

    function showError(error){
        switch(error.code){
            case error.PERMISSION_DENIED:
                x.value="User denied the request for Geolocation."
                break;
            case error.POSITION_UNAVAILABLE:
                x.value="Location information is unavailable."
                break;
            case error.TIMEOUT:
                x.value="The request to get user location timed out."
                break;
            case error.UNKNOWN_ERROR:
                x.value="An unknown error occurred."
                break;
        }
    }

    function displayLocation(latitude,longitude){
        var geocoder;
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(latitude, longitude);

        geocoder.geocode(
            {'latLng': latlng},
            function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        var add= results[0].formatted_address ;
                        var  value=add.split(",");

                        count=value.length;
                        country=value[count-1];
                        state=value[count-2];
                        city=value[count-3];
                        x.value = city;
                    }
                    else  {
                        x.value = "address not found";
                    }
                }
                else {
                    x.value = "Geocoder failed due to: " + status;
                }
            }
        );
    }
    var get_loc_btn = document.querySelector(".atbd_get_loc");
    get_loc_btn.addEventListener("click", function () {
        getLocation();
    });
})();