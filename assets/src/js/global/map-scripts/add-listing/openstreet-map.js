/* Add listing OSMap */

import {
    get_dom_data
} from './../../../lib/helper';

;
(function ($) {

    $(document).ready(function () {
        if ($('#gmap').length) {
            var mapData = get_dom_data('map_data');
            // Localized Data
            var loc_default_latitude  = parseFloat( mapData.default_latitude );
            var loc_default_longitude = parseFloat( mapData.default_longitude );
            var loc_manual_lat        = parseFloat( mapData.manual_lat );
            var loc_manual_lng        = parseFloat( mapData.manual_lng );
            var loc_map_zoom_level    = parseInt( mapData.map_zoom_level );
            var loc_map_icon          = mapData.map_icon;
        
            loc_manual_lat = ( isNaN( loc_manual_lat ) ) ? loc_default_latitude : loc_manual_lat;
            loc_manual_lng = ( isNaN( loc_manual_lng ) ) ? loc_default_longitude : loc_manual_lng;
        
            var map = L.map('gmap').setView( [ loc_manual_lat, loc_manual_lng ], loc_map_zoom_level );
        
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
        
            var customIcon = L.icon({
                iconUrl   : loc_map_icon,   // Adjust the path to your icon file
                iconSize  : [20, 25],           // Adjust the size of your icon
            });
        
            var marker = L.marker( [ loc_manual_lat, loc_manual_lng ], { icon: customIcon, draggable: true } ).addTo( map );
        
            // Autocomplete for the address input field
            $('#address').autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url     : 'https://nominatim.openstreetmap.org/',
                        method  : 'GET',
                        dataType: 'json',
                        data    : {
                            q     : request.term,
                            format: 'json',
                            limit : 5,
                        },
                        success: function (data) {
                            // Extract and format suggestions from the geocoding service response
                            var suggestions = $.map(data, function (item) {
                                return {
                                    label    : item.display_name,
                                    latitude : item.lat,
                                    longitude: item.lon
                                };
                            });
        
                            // Call the response callback with the suggestions
                            response(suggestions);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching address suggestions:', status, error);
                            response([]); // Return an empty array in case of an error
                        }
                    });
                },
                select: function (event, ui) {
                    // Update the map when the user selects an address
                    map.setView([ui.item.latitude, ui.item.longitude], 15);
                    marker.setLatLng([ui.item.latitude, ui.item.longitude]);
                    $('#manual_lat').val(ui.item.latitude);
                    $('#manual_lng').val(ui.item.longitude);
                }
            });
        
            // Handle marker dragend event
            marker.on('dragend', function (event) {
                var markerLatLng = marker.getLatLng();
                $('#manual_lat').val(markerLatLng.lat);
                $('#manual_lng').val(markerLatLng.lng);
        
                // Reverse geocode to get the address based on the marker's new position
                reverseGeocode(markerLatLng.lat, markerLatLng.lng, function (address) {
                    $('#address').val(address);
                });
            });
        
            // Function to reverse geocode and get the address based on latitude and longitude
            function reverseGeocode(latitude, longitude, callback) {
                $.ajax({
                    url     : 'https://nominatim.openstreetmap.org/reverse',
                    method  : 'GET',
                    dataType: 'json',
                    data    : {
                        lat   : latitude,
                        lon   : longitude,
                        format: 'json',
                    },
                    success: function (data) {
                        if (data.display_name) {
                            callback(data.display_name);
                        } else {
                            console.error('Reverse geocoding failed');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching reverse geocoding data:', status, error);
                    }
                });
            }
        }
    });
})(jQuery);