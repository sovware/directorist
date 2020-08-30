(function ($) {
    jQuery(document).ready(function ($) {
var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 18,
      attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors, Points &copy 2012 LINZ'
    }),
    latlng = L.latLng(atbdp_lat_lon.lat, atbdp_lat_lon.lon),
    fullCount = listings_data.length,
    quarterCount = Math.round(fullCount / 4);

var map = L.map('map', {center: latlng, zoom: atbdp_map.zoom, scrollWheelZoom: false, layers: [tiles]});
//map.once('focus', function() { map.scrollWheelZoom.enable(); });

var mcg = L.markerClusterGroup(),
    group1 = L.featureGroup.subGroup(mcg),// use `L.featureGroup.subGroup(parentGroup)` instead of `L.featureGroup()` or `L.layerGroup()`!
    group2 = L.featureGroup.subGroup(mcg),
    group3 = L.featureGroup.subGroup(mcg),
    group4 = L.featureGroup.subGroup(mcg),
    control = L.control.layers(null, null, { collapsed: false }),
    i, a, title, marker;
mcg.addTo(map);
var test = $(".openstreet_icon");

for (i = 0; i < listings_data.length; i++) {
    const listing = listings_data[i];
    const fontAwesomeIcon = L.divIcon({
        html: '<div class="atbd_map_shape"><span class="' + listing.cat_icon + '"></span></div>',
        iconSize: [20, 20],
        className: 'myDivIcon'
    });

    title = listing.info_content;
    marker = L.marker([listing.manual_lat, listing.manual_lng], { icon: fontAwesomeIcon });
    marker.bindPopup(title);

    marker.addTo(i < quarterCount ? group1 : i < quarterCount * 2 ? group2 : i < quarterCount * 3 ? group3 : group4);
}


/*control.addOverlay(group1, 'First quarter');
control.addOverlay(group2, 'Second quarter');
control.addOverlay(group3, 'Third quarter');
control.addOverlay(group4, 'Fourth quarter');*/
control.addTo(map);

group1.addTo(map); // Adding to map now adds all child layers into the parent group.
group2.addTo(map);
group3.addTo(map);
group4.addTo(map);
    });
})(jQuery);

