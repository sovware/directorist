; (function ($) {
    jQuery(document).ready(function () {

        // Localized Data
        var loc_default_latitude  = parseFloat(localized_data.default_latitude);
        var loc_default_longitude = parseFloat(localized_data.default_longitude);
        var loc_manual_lat        = parseFloat(localized_data.manual_lat);
        var loc_manual_lng        = parseFloat(localized_data.manual_lng);
        var loc_map_zoom_level    = parseInt(localized_data.map_zoom_level);
        var display_map_info      = localized_data.display_map_info;
        var cat_icon              = localized_data.cat_icon;
        var info_content          = localized_data.info_content;

        loc_manual_lat = (isNaN(loc_manual_lat)) ? loc_default_latitude : loc_manual_lat;
        loc_manual_lng = (isNaN(loc_manual_lng)) ? loc_default_longitude : loc_manual_lng;

        $manual_lat = $('#manual_lat');
        $manual_lng = $('#manual_lng');

        saved_lat_lng = {
            lat: loc_manual_lat,
            lng: loc_manual_lng,
        };
        
        function mapLeaflet (lat, lon)	 {
            const fontAwesomeIcon = L.divIcon({
                html: '<div class="atbd_map_shape"><span class="'+ cat_icon +'"></span></div>',
                iconSize: [20, 20],
                className: 'myDivIcon'
            });

            var mymap = L.map('gmap').setView([lat, lon], loc_map_zoom_level);

            if ( display_map_info ) {
                L.marker([lat, lon], {icon: fontAwesomeIcon}).addTo(mymap).bindPopup( info_content );
            } else {
                L.marker([lat, lon], {icon: fontAwesomeIcon}).addTo(mymap);
            }

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(mymap);
        }

        mapLeaflet (loc_manual_lat, loc_manual_lng);
    
    });
})(jQuery);