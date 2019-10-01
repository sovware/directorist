
(function ($) {
    //Advance search
    // Populate atbdp child terms dropdown
    $( '.bdas-terms' ).on( 'change', 'select', function( e ) {

        e.preventDefault();

        var $this    = $( this );
        var taxonomy = $this.data( 'taxonomy' );
        var parent   = $this.data( 'parent' );
        var value    = $this.val();
        var classes  = $this.attr( 'class' );

        $this.closest( '.bdas-terms' ).find( 'input.bdas-term-hidden' ).val( value );
        $this.parent().find( 'div:first' ).remove();

        if( parent != value ) {
            $this.parent().append( '<div class="bdas-spinner"></div>' );

            var data = {
                'action': 'bdas_public_dropdown_terms',
                'taxonomy': taxonomy,
                'parent': value,
                'class': classes,
                'security': atbdp_search.ajaxnonce
            };

            $.post( atbdp_search.ajax_url, data, function( response ) {
                $this.parent().find( 'div:first' ).remove();
                $this.parent().append( response );
            });
        };

    });

    // load custom fields of the selected category in the search form
    $( 'body' ).on( 'change', '.bdas-category-search, #at_biz_dir-category', function() {
        var $search_elem = $( this ).closest ( 'form' ).find( ".atbdp-custom-fields-search" );

        if( $search_elem.length ) {

            $search_elem.html( '<div class="atbdp-spinner"></div>' );

            var data = {
                'action': 'atbdp_custom_fields_search',
                'term_id': $( this ).val(),
                'security': atbdp_search.ajaxnonce
            };

            $.post( atbdp_search.ajax_url, data, function(response) {
                $search_elem.html( response );
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
            });

        };

    });

    // Price Range Slider
    var slider_range = $(".atbd_slider-range");
    var miles = atbdp_search_listing.i18n_text.Miles;
    slider_range.each(function () {
        $(this).slider({
            range: "min",
            min: 0,
            max: 1000,
            value: 0,
            slide: function (event, ui) {
                $(".atbdpr_amount").text(ui.value + miles);
                $("#atbd_rs_value").val(ui.value);
            }
        });
    });
    $(".atbdpr_amount").text(slider_range.slider("value") + miles);
    $("#atbd_rs_value").val(slider_range.slider("value"));

    if(atbdp_search_listing.i18n_text.select_listing_map === 'google') {
        function initialize() {
            var input = document.getElementById('address');
            var autocomplete = new google.maps.places.Autocomplete(input);
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                document.getElementById('cityLat').value = place.geometry.location.lat();
                document.getElementById('cityLng').value = place.geometry.location.lng();
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    }else if(atbdp_search_listing.i18n_text.select_listing_map === 'openstreet') {
        $('#address').on('keyup', function(event) {
            event.preventDefault();
            var search = $('#address').val();
            $('#address_result').css({'display':'block'});
            if(search === ""){
                $('#result').css({'display':'none'});
            }
            var res = "";
            $.ajax({
                url: `http://nominatim.openstreetmap.org/?q=%27+${search}+%27&format=json`,
                type: 'POST',
                data: {},
                success: function (data) {
                    //console.log(data);
                    for (var i = 0; i < data.length; i++) {
                        res += `<li><a href="#" data-lat=${data[i].lat} data-lon=${data[i].lon}>${data[i].display_name}</a></li>`
                    }
                    $('#address_result ul').html(res);
                }
            });
        });

        $('body').on('click', '#address_result ul li a', function(event) {
            event.preventDefault();
            let text = $(this).text(),
                lat = $(this).data('lat'),
                lon = $(this).data('lon');

            $('#cityLat').val(lat);
            $('#cityLng').val(lon);

            $('#address').val(text);
            $('#address_result').hide();
        });
    }

    /*
    get current location
*/
    (function () {
        var x = document.querySelector(".location-name");
        var get_lat = document.querySelector("#cityLat");
        var get_lng = document.querySelector("#cityLng");

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
            get_lat.value = lat;
            get_lng.value = lon;
        }

        function showError(error){
            switch(error.code){
                case error.PERMISSION_DENIED:
                    x.value="User denied the request for Geolocation.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    x.value="Location information is unavailable.";
                    break;
                case error.TIMEOUT:
                    x.value="The request to get user location timed out.";
                    break;
                case error.UNKNOWN_ERROR:
                    x.value="An unknown error occurred.";
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
        if(get_loc_btn){
            get_loc_btn.addEventListener("click", function () {
                getLocation();
            });
        }
    })();

})(jQuery);
