/* Add listing OSMap */

import {
    get_dom_data
} from './../../../lib/helper';

;
(function ($) {
    $(document).ready(function () {
        var mapData = get_dom_data('map_data');

        // Localized Data
        var loc_default_latitude = parseFloat(mapData.default_latitude);
        var loc_default_longitude = parseFloat(mapData.default_longitude);
        var loc_manual_lat = parseFloat(mapData.manual_lat);
        var loc_manual_lng = parseFloat(mapData.manual_lng);
        var loc_map_zoom_level = parseInt(mapData.map_zoom_level);
        var loc_map_icon = mapData.map_icon;

        loc_manual_lat = (isNaN(loc_manual_lat)) ? loc_default_latitude : loc_manual_lat;
        loc_manual_lng = (isNaN(loc_manual_lng)) ? loc_default_longitude : loc_manual_lng;

        function mapLeaflet(lat, lon) {

            // @todo @kowsar / remove later. fix js error
            if ($("#gmap").length == 0) {
                return;
            }

            const fontAwesomeIcon = L.icon({
                iconUrl: loc_map_icon,
                iconSize: [20, 25],
            });
            var mymap = L.map('gmap').setView([lat, lon], loc_map_zoom_level);

            L.marker([lat, lon], {
                icon: fontAwesomeIcon,
                draggable: true
            }).addTo(mymap).addTo(mymap).on("drag", function (e) {
                var marker = e.target;
                var position = marker.getLatLng();
                $('#manual_lat').val(position.lat);
                $('#manual_lng').val(position.lng);
                $.ajax({
                    url: `https://nominatim.openstreetmap.org/reverse?format=json&lon=${position.lng}&lat=${position.lat}`,
                    type: 'GET',
                    data: {},
                    success: function (data) {
                        $('.directorist-location-js').val(data.display_name);
                    }
                });
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(mymap);
        }

        function directorist_debounce(func, wait, immediate) {
            var timeout;
            return function() {
                var context = this, args = arguments;
                var later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        };

        $('.directorist-location-js').each(function (id, elm) {
            const result_container = $(elm).siblings('.address_result');

            $(elm).on('keyup', directorist_debounce(function (event) {
                event.preventDefault();

                const blockedKeyCodes = [16, 17, 18, 19, 20, 27, 33, 34, 35, 36, 37, 38, 39, 40, 45, 91, 93, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 144, 145];

                // Return early when blocked key is pressed.
                if (blockedKeyCodes.includes(event.keyCode)) {
                    return;
                }

                const locationAddressField = $(this).parent('.directorist-form-address-field');
                const search = $(elm).val();

                if (search.length < 3) {
                    result_container.css({
                        'display': 'none'
                    });
                } else {
                    locationAddressField.addClass('atbdp-form-fade');
                    result_container.css({
                        'display': 'block'
                    });

                    $.ajax({
                        url: `https://nominatim.openstreetmap.org/?q=%27+${search}+%27&format=json`,
                        type: 'GET',
                        data: {},
                        success: function (data) {
                            let res = '';

                            for (var i = 0; i < data.length; i++) {
                                res += `<li><a href="#" data-lat=${data[i].lat} data-lon=${data[i].lon}>${data[i].display_name}</a></li>`
                            }
                            result_container.find('ul').html(res);
                            if (res.length) {
                                result_container.show();
                            } else {
                                result_container.hide();
                            }
                            locationAddressField.removeClass('atbdp-form-fade');
                        }
                    });
                }
            }, 750));
        })

        var lat = loc_manual_lat,
            lon = loc_manual_lng;

        mapLeaflet(lat, lon);

        $('body').on('click', '.directorist-form-address-field .address_result ul li a', function (event) {
            if (document.getElementById('osm')) {
                document.getElementById('osm').innerHTML = "<div id='gmap'></div>";
            }
            event.preventDefault();
            let text = $(this).text(),
                lat = $(this).data('lat'),
                lon = $(this).data('lon');

            $('#manual_lat').val(lat);
            $('#manual_lng').val(lon);

            $(this).closest('.address_result').siblings('.directorist-location-js').val(text);
            $('.address_result').css({
                'display': 'none'
            });

            mapLeaflet(lat, lon);
        });

        $('body').on('click', '.location-names ul li a', function (event) {
            event.preventDefault();
            let text = $(this).text();

            $(this).closest('.address_result').siblings('.directorist-location-js').val(text);
            $('.address_result').css({
                'display': 'none'
            });
        });


        $('body').on('click', '#generate_admin_map', function (event) {
            event.preventDefault();
            document.getElementById('osm').innerHTML = "<div id='gmap'></div>";
            mapLeaflet($('#manual_lat').val(), $('#manual_lng').val());

        });

        // Popup controller by keyboard
        var index = 0;
        $('.directorist-location-js').on('keyup', function (event) {
            event.preventDefault();
            var length = $('#directorist.atbd_wrapper .address_result ul li a').length;
            if (event.keyCode === 40) {
                index++;
                if (index > length) {
                    index = 0;
                }
            } else if (event.keyCode === 38) {
                index--;
                if (index < 0) {
                    index = length
                };
            }

            if ($('#directorist.atbd_wrapper .address_result ul li a').length > 0) {

                $('#directorist.atbd_wrapper .address_result ul li a').removeClass('active')
                $($('#directorist.atbd_wrapper .address_result ul li a')[index]).addClass('active');

                if (event.keyCode === 13) {
                    $($('#directorist.atbd_wrapper .address_result ul li a')[index]).click();
                    event.preventDefault();
                    index = 0;
                    return false;
                }
            };

        });

        // $('#post').on('submit', function (event) {
        //     event.preventDefault();
        //     return false;
        // });
    });
})(jQuery);