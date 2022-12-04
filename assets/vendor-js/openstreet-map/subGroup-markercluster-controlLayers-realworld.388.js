(function ($) {
    $(document).ready( function() {
        var mapOptions  = JSON.parse( $('#map').attr('data-options') );
        var mapListings = JSON.parse( $('#map').attr('data-card') );

        const tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors, Points &copy 2012 LINZ',
        });

        let defCordEnabled = mapOptions.force_default_location;
        const latlng = defCordEnabled ? L.latLng(mapOptions.default_latitude, mapOptions.default_longitude) : L.latLng(mapOptions.base_latitude, mapOptions.base_longitude);
        const fullCount = mapListings.length;
        const quarterCount = Math.round(fullCount / 4);

        try {
            const map = L.map('map', {
                center: latlng,
                zoom: mapOptions.zoom_level,
                scrollWheelZoom: false,
                layers: [tiles],
            });

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
                marker.bindPopup(title);

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
        } catch ( _ ) {}


    });
})(jQuery);

