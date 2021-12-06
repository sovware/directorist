var atbdp_lat_lon = get_dom_data( 'atbdp_lat_lon' );
var listings_data = get_dom_data( 'listings_data' );
var atbdp_map     = get_dom_data( 'atbdp_map' );

function get_dom_data( key, parent ) {
    var elmKey = 'directorist-dom-data-' + key;
    var dataElm = ( parent ) ? parent.getElementsByClassName( elmKey ) : document.getElementsByClassName( elmKey );

    if ( ! dataElm ) {
        return '';
    }

    var is_script_debugging = ( directorist_options && directorist_options.script_debugging && directorist_options.script_debugging == '1' ) ? true : false;

    try {
        let dataValue = atob( dataElm[0].dataset.value );
        dataValue = JSON.parse( dataValue );
        return dataValue;
    } catch (error) {
        if ( is_script_debugging ) {
            console.log({key,dataElm,error});
        }

        return '';
    }
}


(function($) {
        jQuery(document).ready(function($) {
                const tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 18,
                        attribution:
                                '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors, Points &copy 2012 LINZ',
                });
                let defCordEnabled = atbdp_map.use_def_lat_long;
                const latlng = defCordEnabled ? L.latLng(atbdp_map.default_lat, atbdp_map.default_long) : L.latLng(atbdp_lat_lon.lat, atbdp_lat_lon.lon);
                const fullCount = listings_data.length;
                const quarterCount = Math.round(fullCount / 4);

                const map = L.map('map', {
                        center: latlng,
                        zoom: atbdp_map.zoom,
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
                const control = L.control.layers(null, null, { collapsed: false });
                let i;
                let a;
                let title;
                let marker;
                mcg.addTo(map);
                const test = $('.openstreet_icon');

                for (i = 0; i < listings_data.length; i++) {
                        const listing = listings_data[i];
                        const fontAwesomeIcon = L.divIcon({
                                html: `<div class="atbd_map_shape"><span class="${listing.cat_icon}"></span></div>`,
                                iconSize: [20, 20],
                                className: 'myDivIcon',
                        });

                        title = listing.info_content;
                        marker = L.marker([listing.manual_lat, listing.manual_lng], { icon: fontAwesomeIcon });
                        marker.bindPopup(title);

                        marker.addTo(
                                i < quarterCount
                                        ? group1
                                        : i < quarterCount * 2
                                        ? group2
                                        : i < quarterCount * 3
                                        ? group3
                                        : group4
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
        });
})(jQuery);
