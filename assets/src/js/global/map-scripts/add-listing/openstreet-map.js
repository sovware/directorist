/* Add listing OSMap */

import debounce from '../../components/debounce';
import {
    get_dom_data
} from './../../../lib/helper';

(function ($) {
    // Add focus class to the parent field of .directorist-location-js
    function addFocusClass(location) {
        // Get the parent field of .directorist-location-js
        let parentField = location.closest('.directorist-search-field');

        // Add the 'input-is-focused' class if not already present
        if (parentField && !parentField.hasClass('input-is-focused')) {
            parentField.addClass('input-is-focused');
        }
    }

    // Add Listing Map Initialize
    function initAddListingMap() {
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

            const fontAwesomeIcon = L.divIcon({
                html: `<div class="atbd_map_shape">${loc_map_icon}</div>`,
                iconSize: [20, 20],
                className: 'myDivIcon',
            });

            var mymap = L.map('gmap').setView([lat, lon], loc_map_zoom_level);

            // Create draggable marker
            var marker = L.marker([lat, lon], {
                icon: fontAwesomeIcon,
                draggable: true
            }).addTo(mymap);

            // Trigger AJAX request when marker is dropped
            marker.on("dragend", function (e) {
                var position = marker.getLatLng();
                $('#manual_lat').val(position.lat);
                $('#manual_lng').val(position.lng);
                
                // Make AJAX request after the drag ends (marker drop)
                $.ajax({
                    url: `https://nominatim.openstreetmap.org/reverse?format=json&lon=${position.lng}&lat=${position.lat}`,
                    type: 'GET',
                    data: {},
                    success: function (data) {
                        $('.directorist-location-js').val(data.display_name);
                        addFocusClass($('.directorist-location-js'));
                    },
                    error: function () {
                        $('.directorist-location-js').val('Location not found');
                        addFocusClass($('.directorist-location-js'));
                    }
                });
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(mymap);

            function toggleFullscreen() {
                var mapContainer = document.getElementById('gmap');
                var fullScreenEnable = document.querySelector('#gmap_full_screen_button .fullscreen-enable');
                var fullScreenDisable = document.querySelector('#gmap_full_screen_button .fullscreen-disable');

                if (!document.fullscreenElement && !document.webkitFullscreenElement) {
                    if (mapContainer.requestFullscreen) {
                        mapContainer.requestFullscreen();

                        fullScreenEnable.style.display="none";
                        fullScreenDisable.style.display="block";
                    } else if (mapContainer.webkitRequestFullscreen) {
                        mapContainer.webkitRequestFullscreen();
                    }
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();

                        fullScreenDisable.style.display="none";
                        fullScreenEnable.style.display="block";
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    }
                }
            }

            $('body').on('click', '#gmap_full_screen_button', function (event) {
                event.preventDefault();
                toggleFullscreen();
            });
        }

        $('.directorist-location-js').each(function (id, elm) {
            const result_container = $(elm).siblings('.address_result');

            $(elm).on('keyup', debounce(function (event) {
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

        // Add Map on Add Listing Multistep
        $('body').on('click', '.multistep-wizard__btn', function (event) {
            if (document.getElementById('osm')) {
                document.getElementById('osm').innerHTML = "<div id='gmap'></div>";

                mapLeaflet(lat, lon);
            }
        });

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
    }

    $(document).ready(function () {
        initAddListingMap()
    });

    // Add Listing Map on Elementor EditMode 
    $(window).on('elementor/frontend/init', function () {
        setTimeout(function() {
            if ($('body').hasClass('elementor-editor-active')) {
                initAddListingMap()
            }
        }, 3000);

    });

    $('body').on('click', function (e) {
        if ($('body').hasClass('elementor-editor-active')  && (e.target.nodeName !== 'A' && e.target.nodeName !== 'BUTTON')) {
            initAddListingMap()
        }
    });

})(jQuery);