(function ($) {
    $(document).ready(function ($) {

        window.addEventListener('load', initMap );
        window.addEventListener('directorist-reload-listings-map-archive', initMap);

        function initMap() {
            if ($('#map').length) {
                // Parse map options and listings from HTML data attributes
                var mapOptions  = JSON.parse( $('#map').attr('data-options') );
                var mapListings = JSON.parse( $('#map').attr('data-card') );

                // Define tile layer with OpenStreetMap attribution
                const tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom    : 18,
                    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors, Points &copy 2012 LINZ',
                });

                // Create a marker cluster group
                var markers = L.markerClusterGroup();

                // Determine initial map center coordinates
                let defCordEnabled = mapOptions.force_default_location;
                const latlng = defCordEnabled ? L.latLng( mapOptions.default_latitude, mapOptions.default_longitude ) : L.latLng( mapOptions.base_latitude, mapOptions.base_longitude );

                // Create the map with specified options
                var map = L.map('map', {
                    center         : latlng,
                    zoom           : mapOptions.zoom_level,
                    scrollWheelZoom: false,
                    layers         : [tiles],
                });

                // Add OpenStreetMap as the base layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo( map );

                // Loop through each map listing and create a marker
                mapListings.forEach( function ( mapInfo, index ) {

                    // Create a custom icon for the marker
                    var fontAwesomeIcon = L.divIcon( {
                        html     : `<div class="atbd_map_shape"><span class="">${mapInfo.cat_icon}</span></div>`,
                        iconSize : [20, 20],
                        className: 'myDivIcon',
                    } );

                    // Create the marker
                    var marker = L.marker( [ mapInfo.latitude, mapInfo.longitude ], {
                        icon: fontAwesomeIcon
                    } );

                    // Bind popup if content is available
                    var title = mapInfo.content;
                    if ( title ) {
                        marker.bindPopup( title );
                    }

                    // Add the marker to the cluster group
                    markers.addLayer( marker );
                });

                // Add the marker cluster group to the map
                map.addLayer( markers );

                // Enable dragging when clicking on the map
                map.on( 'click', function () {
                    map.dragging.enable();
                } );

                // Disable dragging when releasing the mouse button
                map.on('mouseup', function () {
                    map.dragging.disable();
                });
            }
        }
    });

})(jQuery);
