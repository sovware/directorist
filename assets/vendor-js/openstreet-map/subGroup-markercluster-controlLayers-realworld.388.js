(function ($) {
    $(document).ready( function() {
        var $maps = $('.directorist-openstreet-map, #map:not(.directorist-openstreet-map)');

        $maps.each(function() {
        var $mapElement = $(this);
        var mapOptions  = JSON.parse( $mapElement.attr('data-options') );
        var mapListings = JSON.parse( $mapElement.attr('data-card') );
        var instanceId = $mapElement.data('directorist-map-instance') || $mapElement.attr('id') || 'map';
        var bridge = window.DirectoristMapBridge && window.DirectoristMapBridge.version >= 1 ? window.DirectoristMapBridge : null;
        var normalizeLongitude = function(longitude) {
            var normalized = parseFloat(longitude);

            if (!isFinite(normalized)) {
                return normalized;
            }

            while (normalized < -180) {
                normalized += 360;
            }

            while (normalized > 180) {
                normalized -= 360;
            }

            return normalized;
        };

        const tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors, Points &copy 2012 LINZ',
        });

        let defCordEnabled = mapOptions.force_default_location;
        const latlng = defCordEnabled ? L.latLng(mapOptions.default_latitude, mapOptions.default_longitude) : L.latLng(mapOptions.base_latitude, mapOptions.base_longitude);
        const fullCount = mapListings.length;
        const quarterCount = Math.round(fullCount / 4);

        try {
            const map = L.map($mapElement[0], {
                center: latlng,
                zoom: mapOptions.zoom_level,
                scrollWheelZoom: false,
                layers: [tiles],
            });
            let userInteracted = false;
            const markUserInteraction = function() {
                userInteracted = true;
            };

            ['mousedown', 'pointerdown', 'wheel', 'touchstart', 'keydown'].forEach(function(eventName) {
                $mapElement[0].addEventListener(eventName, markUserInteraction, {
                    capture: true,
                    passive: true,
                });
            });
            map.on('dragstart zoomstart', markUserInteraction);

            // map.once('focus', function() { map.scrollWheelZoom.enable(); });
            const mcg = L.markerClusterGroup();
            const group1 = L.featureGroup.subGroup(mcg);
            const // use `L.featureGroup.subGroup(parentGroup)` instead of `L.featureGroup()` or `L.layerGroup()`!
                group2 = L.featureGroup.subGroup(mcg);
            const group3 = L.featureGroup.subGroup(mcg);
            const group4 = L.featureGroup.subGroup(mcg);
            const control = L.control.layers(null, null, {
                collapsed: false
            });
            let i;
            let a;
            let title;
            let marker;
            const markersByListingId = {};
            mcg.addTo(map);

            for (i = 0; i < mapListings.length; i++) {
                const listing = mapListings[i];
                const fontAwesomeIcon = L.divIcon({
                    html: `<div class="atbd_map_shape"><span class="">${listing.cat_icon}</span></div>`,
                    iconSize: [20, 20],
                    className: 'myDivIcon',
                });

                title = listing.content;
                marker = L.marker([listing.latitude, listing.longitude], {
                    icon: fontAwesomeIcon
                });
                marker.directoristListingId = listing.listing_id;
                markersByListingId[listing.listing_id] = marker;
                marker.bindPopup(title);
                marker.on('click', function() {
                    if (bridge) {
                        bridge.dispatchMarkerClick(instanceId, this.directoristListingId);
                    }
                });

                marker.addTo(
                    i < quarterCount ?
                    group1 :
                    i < quarterCount * 2 ?
                    group2 :
                    i < quarterCount * 3 ?
                    group3 :
                    group4
                );
            }

            /* control.addOverlay(group1, 'First quarter');
            control.addOverlay(group2, 'Second quarter');
            control.addOverlay(group3, 'Third quarter');
            control.addOverlay(group4, 'Fourth quarter'); */
            control.addTo(map);

            group1.addTo(map); // Adding to map now adds all child layers into the parent group.
            group2.addTo(map);
            group3.addTo(map);
            group4.addTo(map);

            if (bridge) {
                bridge.register({
                    instanceId,
                    provider: 'openstreet',
                    element: $mapElement[0],
                    map,
                    getBounds() {
                        const bounds = map.getBounds();

                        return {
                            north: bounds.getNorth(),
                            east: normalizeLongitude(bounds.getEast()),
                            south: bounds.getSouth(),
                            west: normalizeLongitude(bounds.getWest()),
                        };
                    },
                    openPopup(listingId, html) {
                        const targetMarker = markersByListingId[listingId];

                        if (!targetMarker) {
                            return false;
                        }

                        if (html) {
                            targetMarker.bindPopup(html);
                        }

                        targetMarker.openPopup();

                        return true;
                    },
                });

                map.on('moveend zoomend', function() {
                    if (!userInteracted) {
                        return;
                    }

                    bridge.dispatchViewportChanged(instanceId);
                });
            }
        } catch ( _ ) {}


        });
    });
})(jQuery);
