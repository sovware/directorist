/* Add listing google map */

import {
    get_dom_data
} from './../../../lib/helper';

var $ = jQuery;

// Add Listing Map Initialize
export function initAddListingMap() {
    if (typeof google === "undefined" || !google.maps || !google.maps.Geocoder) {
        return;
    }

    if ($('#gmap').length) {
        var localized_data = get_dom_data('map_data');

        // initialize all vars here to avoid hoisting related misunderstanding.
        let map;
        let autocomplete;
        let address_input;
        let markers;
        let $manual_lat;
        let $manual_lng;
        let saved_lat_lng;

        // Localized Data
        const loc_default_latitude = parseFloat(localized_data.default_latitude);
        const loc_default_longitude = parseFloat(localized_data.default_longitude);
        let loc_manual_lat = parseFloat(localized_data.manual_lat);
        let loc_manual_lng = parseFloat(localized_data.manual_lng);
        const loc_map_zoom_level = parseInt(localized_data.map_zoom_level);

        const searchIcon = `<i class="directorist-icon-mask"></i>`
        const markerShape = document.createElement("div");
        markerShape.className = "atbd_map_shape";
        markerShape.innerHTML = searchIcon;

        loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;
        loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;

        $manual_lat = $('#manual_lat');
        $manual_lng = $('#manual_lng');

        saved_lat_lng = {
            lat: loc_manual_lat,
            lng: loc_manual_lng,
        };

        // default is London city
        (markers = []), // initialize the array to keep track all the marker

        address_input = document.getElementById('address');
        if (address_input !== null) {
            address_input.addEventListener('focus', geolocate);
        }

        const geocoder = new google.maps.Geocoder();

        // This function will help to get the current location of the user
        function markerDragInit(marker) {
            marker.addListener('dragend', (event) => {
                // set the value of input field to save them to the database
                $manual_lat.val(event.latLng.lat());
                $manual_lng.val(event.latLng.lng());

                // Regenerate Address
                geocodeAddress(geocoder, map);
            });
        }

        // this function will work on sites that uses SSL, it applies to Chrome especially, other browsers may allow location sharing without securing.
        function geolocate() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    const geolocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    const circle = new google.maps.Circle({
                        center: geolocation,
                        radius: position.coords.accuracy,
                    });
                    autocomplete.setBounds(circle.getBounds());
                });
            }
        }

        function initAutocomplete() {
            // Create the autocomplete object, restricting the search to geographical
            let opt = {
                types: ['geocode'],
                componentRestrictions: {
                    country: directorist.restricted_countries
                },
            };
            const options = directorist.countryRestriction ? opt : {
                types: []
            };

            // location types.
            autocomplete = new google.maps.places.Autocomplete( address_input, options );

            // When the user selects an address from the dropdown, populate the necessary input fields and draw a marker
            autocomplete.addListener('place_changed', fillInAddress);
        }

        function fillInAddress() {
            // Get the place details from the autocomplete object.
            const place = autocomplete.getPlace();

            // set the value of input field to save them to the database
            $manual_lat.val(place.geometry.location.lat());
            $manual_lng.val(place.geometry.location.lng());
            map.setCenter(place.geometry.location);
            const marker = new google.maps.marker.AdvancedMarkerElement({
                map,
                position: place.geometry.location,
                gmpDraggable: true,
                content: markerShape, 
                title: localized_data.marker_title,
            });

            // Delete Previous Marker
            deleteMarker();

            // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.
            markers.push(marker);
            markerDragInit(marker);
        }

        initAutocomplete(); // start google map place auto complete API call

        // Map Initialize
        function initMap() {
            /* Create new map instance */
            map = new google.maps.Map(document.getElementById('gmap'), {
                zoom: loc_map_zoom_level,
                center: saved_lat_lng,
                mapId: "add_listing_map",
            });

            const marker = new google.maps.marker.AdvancedMarkerElement({
                map,
                position: saved_lat_lng,
                gmpDraggable: true,
                content: markerShape, 
                title: localized_data.marker_title,
            });                
            
            markers.push(marker);

            document.getElementById('generate_admin_map').addEventListener('click', function (e) {
                e.preventDefault();
                geocodeAddress(geocoder, map);
            });

            // This event listener calls addMarker() when the map is clicked.
            marker.addListener('click', (event) => {
                deleteMarker(); // at first remove previous marker and then set new marker;
                // set the value of input field to save them to the database
                $manual_lat.val(event.latLng.lat());
                $manual_lng.val(event.latLng.lng());

                // add the marker to the given map.
                addMarker(event.latLng, map);
            });
            
            markerDragInit(marker);
        }

        /*
            * Geocode and address using google map javascript api and then populate the input fields for storing lat and long
            * */

        function geocodeAddress(geocoder, resultsMap) {
            const lat = parseFloat(document.getElementById('manual_lat').value);
            const lng = parseFloat(document.getElementById('manual_lng').value);
            const latLng = new google.maps.LatLng(lat, lng);
            const opt = {
                location: latLng,
            };

            geocoder.geocode(opt, function (results, status) {
                if (status === 'OK') {
                    // set the value of input field to save them to the database
                    $manual_lat.val(results[0].geometry.location.lat());
                    $manual_lng.val(results[0].geometry.location.lng());
                    resultsMap.setCenter(results[0].geometry.location);
                    const marker = new google.maps.marker.AdvancedMarkerElement({
                        map: resultsMap,
                        position: results[0].geometry.location,
                        gmpDraggable: true,
                        content: markerShape, 
                        title: localized_data.marker_title,
                    });

                    deleteMarker();
                    // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.
                    markers.push(marker);

                    address_input.value = results[0].formatted_address;

                    markerDragInit(marker);
                } else {
                    alert(localized_data.geocode_error_msg + status);
                }
            });
        }

        initMap();

        // adding features of creating marker manually on the map on add listing page.
        /* var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        var labelIndex = 0; */

        // Adds a marker to the map.
        function addMarker(location, map) {
            // Add the marker at the clicked location, and add the next-available label;

            // from the array of alphabetical characters.
            const marker = new google.maps.marker.AdvancedMarkerElement({
                map,
                position: location,
                gmpDraggable: true,
                content: markerShape, 
                title: localized_data.marker_title,
            });
            
            // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.
            markers.push(marker);
            markerDragInit(marker);
        }

        // Delete Marker
        $('#delete_marker').on('click', function (e) {
            e.preventDefault();
            deleteMarker();
        });

        function deleteMarker() {
            for (let i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
            markers = [];
        }
    }
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
