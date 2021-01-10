(function ($) {
    /*// Price Range Slider
    var slider_range = $(".atbd_slider-range");
    var miles = atbdp_search_listing.i18n_text.Miles;
    var dvalue = $("#atbd_rs_value").val();
    slider_range.each(function () {
        $(this).slider({
            range: "min",
            min: 0,
            max: 1000,
            value: dvalue,
            slide: function (event, ui) {
                $(".atbdpr_amount").text(ui.value + miles);
                $("#atbd_rs_value").val(ui.value);
            }
        });
    });
    $(".atbdpr_amount").text(slider_range.slider("value") + miles);
    $("#atbd_rs_value").val(slider_range.slider("value"));*/


    /*
    get current location
*/
    if('google' === adbdp_geolocation.select_listing_map) {
        (function () {
            var x = document.querySelector(".widget-location-name");
            var get_lat = document.querySelector("#cityLat");
            var get_lng = document.querySelector("#cityLng");

            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition, showError);
                } else {
                    x.value = "Geolocation is not supported by this browser.";
                }
            }

            function showPosition(position) {
                lat = position.coords.latitude;
                lon = position.coords.longitude;
                displayLocation(lat, lon);
                get_lat.value = lat;
                get_lng.value = lon;
            }

            function showError(error) {
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        x.value = "User denied the request for Geolocation.";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        x.value = "Location information is unavailable.";
                        break;
                    case error.TIMEOUT:
                        x.value = "The request to get user location timed out.";
                        break;
                    case error.UNKNOWN_ERROR:
                        x.value = "An unknown error occurred.";
                        break;
                }
            }

            function displayLocation(latitude, longitude) {
                var geocoder;
                geocoder = new google.maps.Geocoder();
                var latlng = new google.maps.LatLng(latitude, longitude);
                geocoder.geocode(
                    {'latLng': latlng},
                    function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                var add = results[0].formatted_address;
                                var value = add.split(",");

                                count = value.length;
                                country = value[count - 1];
                                state = value[count - 2];
                                city = value[count - 3];
                                x.value = city;
                            } else {
                                x.value = "address not found";
                            }
                        } else {
                            x.value = "Geocoder failed due to: " + status;
                        }
                    }
                );
            }

            var get_loc_btn = document.querySelector(".atbd_get_loc_wid");
            get_loc_btn.addEventListener("click", function () {
                getLocation();
            });
            if (atbdp_search_listing.i18n_text.select_listing_map === 'google') {
                function initialize() {
                    var input = document.getElementById('address_widget');
                    var options = atbdp_search_listing.countryRestriction ? {
                        types: ['geocode'],
                        componentRestrictions: {country: atbdp_search_listing.restricted_countries}
                       } : '';
                    var autocomplete = new google.maps.places.Autocomplete(input, options);
                    google.maps.event.addListener(autocomplete, 'place_changed', function () {
                        var place = autocomplete.getPlace();
                        document.getElementById('cityLat').value = place.geometry.location.lat();
                        document.getElementById('cityLng').value = place.geometry.location.lng();
                    });
                }

                google.maps.event.addDomListener(window, 'load', initialize);
            }
        })();
    } else if (atbdp_search_listing.i18n_text.select_listing_map === 'openstreet') {
        $('#address_widget').on('keyup', function (event) {
            event.preventDefault();
            var search = $('#address_widget').val();
            $('#address_widget_result').css({'display': 'block'});
            if (search === "") {
                $('#address_widget_result').css({'display': 'none'});
            }

            var res = "";
            $.ajax({
                url: `https://nominatim.openstreetmap.org/?q=%27+${search}+%27&format=json`,
                type: 'POST',
                data: {},
                success: function (data) {
                    //console.log(data);
                    for (var i = 0; i < data.length; i++) {
                        res += '<li><a href="#" data-lat=' + data[i].lat + ' data-lon=' + data[i].lon + '>' + data[i].display_name + '</a></li>'
                    }
                    $('#address_widget_result').html('<ul>' + res + '</ul>');
                }
            });
        });

        $('body').on('click', '#address_widget_result ul li a', function (event) {
            event.preventDefault();
            let text = $(this).text(),
                lat = $(this).data('lat'),
                lon = $(this).data('lon');

            $('#cityLat').val(lat);
            $('#cityLng').val(lon);

            $('#address_widget').val(text);
            $('#address_widget_result').hide();
        });

        function displayLocation(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;

            $.ajax({
                url: `https://nominatim.openstreetmap.org/reverse?format=json&lon=${lng}&lat=${lat}`,
                type: 'POST',
                data: {},
                success: function (data) {
                    $('#address_widget').val(data.display_name);
                    $('#cityLat').val(lat);
                    $('#cityLng').val(lng);
                }
            });
        }

        $(".atbd_get_loc_wid").on('click', () => {
            navigator.geolocation.getCurrentPosition(displayLocation);

        })
    }
    if ($('#address_widget').val() === "") {
        $('#address_widget_result').css({'display': 'none'});
    }
})(jQuery);
