import { get_dom_data } from './../../../lib/helper';


; (function ($) {
    $(document).ready(function () {
        var localized_data = get_dom_data( 'map_data' );

        // Localized Data
        var loc_default_latitude = parseFloat(localized_data.default_latitude);
        var loc_default_longitude = parseFloat(localized_data.default_longitude);
        var loc_manual_lat = parseFloat(localized_data.manual_lat);
        var loc_manual_lng = parseFloat(localized_data.manual_lng);
        var loc_map_zoom_level = parseInt(localized_data.map_zoom_level);
        var loc_map_icon = localized_data.map_icon;

        loc_manual_lat = (isNaN(loc_manual_lat)) ? loc_default_latitude : loc_manual_lat;
        loc_manual_lng = (isNaN(loc_manual_lng)) ? loc_default_longitude : loc_manual_lng;

        function mapLeaflet(lat, lon) {

            // @todo @kowsar / remove later. fix js error
            if($("#gmap").length == 0) {
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
                    type: 'POST',
                    data: {},
                    success: function (data) {
                        $('#address').val(data.display_name);
                    }
                });
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(mymap);
        }

        $('#address').on('keyup', function (event) {
            event.preventDefault();
            if (event.keyCode !== 40 && event.keyCode !== 38) {
                var search = $('#address').val();
                $('#result').css({ 'display': 'block' });
                if (search === "") {
                    $('#result').css({ 'display': 'none' });
                }
                var res = "";
                $.ajax({
                    url: `https://nominatim.openstreetmap.org/?q=%27+${search}+%27&format=json`,
                    type: 'POST',
                    data: {},
                    success: function (data) {
                        //console.log(data);
                        for (var i = 0; i < data.length; i++) {
                            res += `<li><a href="#" data-lat=${data[i].lat} data-lon=${data[i].lon}>${data[i].display_name}</a></li>`
                        }
                        $('#result ul').html(res);
                    }
                });
            }
        });

        var lat = loc_manual_lat,
            lon = loc_manual_lng;

        mapLeaflet(lat, lon);

        $('body').on('click', '#result ul li a', function (event) {
            document.getElementById('osm').innerHTML = "<div id='gmap'></div>";
            event.preventDefault();
            let text = $(this).text(),
                lat = $(this).data('lat'),
                lon = $(this).data('lon');

            $('#manual_lat').val(lat);
            $('#manual_lng').val(lon);

            $('#address').val(text);
            $('#result').css({ 'display': 'none' });

            mapLeaflet(lat, lon);
        });

        $('body').on('click', '#generate_admin_map', function (event) {
            event.preventDefault();
            document.getElementById('osm').innerHTML = "<div id='gmap'></div>";
            mapLeaflet($('#manual_lat').val(), $('#manual_lng').val());

        });

        // Popup controller by keyboard
        var index = 0;
        $('#address').on('keyup', function (event) {
            event.preventDefault();
            var length = $('#directorist.atbd_wrapper #result ul li a').length;
            if (event.keyCode === 40) {
                index++;
                if (index > length) {
                    index = 0;
                }
            } else if (event.keyCode === 38) {
                index--;
                if (index < 0) {
                    index = length
                }
                ;
            }

            if ($('#directorist.atbd_wrapper #result ul li a').length > 0) {

                $('#directorist.atbd_wrapper #result ul li a').removeClass('active')
                $($('#directorist.atbd_wrapper #result ul li a')[index]).addClass('active');

                if (event.keyCode === 13) {
                    $($('#directorist.atbd_wrapper #result ul li a')[index]).click();
                    event.preventDefault();
                    index = 0;
                    return false;
                }
            }
            ;

        });

        // $('#post').on('submit', function (event) {
        //     event.preventDefault();
        //     return false;
        // });
    });
})(jQuery);