<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */

if (!$disable_map && (empty($hide_map)) && !empty($manual_lng || $manual_lat) && !empty($display_map_field)) { ?>
    <div class="atbd_content_module">
        <div class="atbd_content_module_title_area">
            <div class="atbd_area_title">
                <h4>
                    <span class="<?php atbdp_icon_type(true);?>-map atbd_area_icon"></span><?php echo esc_html( $listing_location_text ); ?>
                </h4>
            </div>
        </div>
        <div class="atbdb_content_module_contents">
            <?php
            /**
             * @since 5.10.0
             *
             */
            do_action('atbdp_single_listing_before_map');
            ?>
            <div id="gmap" class="atbd_google_map"></div>
        </div>
    </div><!-- end .atbd_custom_fields_contents -->
    <?php
}
if ('openstreet' == $select_listing_map) {
    wp_register_script( 'openstreet_layer', ATBDP_PUBLIC_ASSETS . 'js/openstreetlayers.js', array( 'jquery' ), ATBDP_VERSION, true );
    wp_enqueue_script( 'openstreet_layer' );
    wp_enqueue_style('leaflet-css',ATBDP_PUBLIC_ASSETS . 'css/leaflet.css');
} ?>
<script>
    <?php if ( 'google' == $select_listing_map ) { 
        wp_enqueue_script('atbdp-template-gmap');
    } ?>

    jQuery(document).ready(function ($) {
        // Do not show map if lat long is empty or map is globally disabled.
        <?php if (!$disable_map && (!empty($manual_lat) && !empty($manual_lng)) && !empty($display_map_field) && empty($hide_map) ){
        if('google' == $select_listing_map) {
        ?>

        // initialize all vars here to avoid hoisting related misunderstanding.
        var map, info_window, saved_lat_lng, info_content;
        saved_lat_lng = {
            lat:<?php echo (!empty($manual_lat)) ? floatval($manual_lat) : false ?>,
            lng: <?php echo (!empty($manual_lng)) ? floatval($manual_lng) : false ?> }; // default is London city
        info_content = "<?php echo $info_content; ?>";

        // create an info window for map
        <?php if(!empty($display_map_info)) {?>
        info_window = new google.maps.InfoWindow({
            content: info_content,
            maxWidth: 400/*Add configuration for max width*/
        });
        <?php } ?>
        function initMap() {
            /* Create new map instance*/
            map = new google.maps.Map(document.getElementById('gmap'), {
                zoom: <?php echo !empty($map_zoom_level) ? intval($map_zoom_level) : 16; ?>,
                center: saved_lat_lng
            });
            /*var marker = new google.maps.Marker({
                map: map,
                position: saved_lat_lng
            });*/
            var marker = new Marker({
                position: saved_lat_lng,
                map: map,
                icon: {
                    path: MAP_PIN,
                    fillColor: 'transparent',
                    fillOpacity: 1,
                    strokeColor: '',
                    strokeWeight: 0
                },
                map_icon_label: '<div class="atbd_map_shape"><i class="<?php echo $cat_icon; ?>"></i></div>'
            });

            <?php if(!empty($display_map_info)) {?>
            marker.addListener('click', function () {
                info_window.open(map, marker);
            });
            google.maps.event.addListener(info_window, 'domready', function() {
                var closeBtn = $('#iw-close-btn').get();
                google.maps.event.addDomListener(closeBtn[0], 'click', function() {
                    info_window.close();
                });
            });
            <?php } ?>
        }

        initMap();
        //Convert address tags to google map links -
        $('address').each(function () {
            var link = "<a href='http://maps.google.com/maps?q=" + encodeURIComponent($(this).text()) + "' target='_blank'>" + $(this).text() + "</a>";
            $(this).html(link);
        });
        <?php } elseif('openstreet' == $select_listing_map) { ?>
        function mapLeaflet (lat, lon)	 {

            const fontAwesomeIcon = L.divIcon({
                html: '<div class="atbd_map_shape"><span class="<?php echo $cat_icon; ?>"></span></div>',
                iconSize: [20, 20],
                className: 'myDivIcon'
            });

            var mymap = L.map('gmap').setView([lat, lon], <?php echo !empty($map_zoom_level) ? $map_zoom_level : 16;?>);

            <?php if(!empty($display_map_info)) { ?>
            L.marker([lat, lon], {icon: fontAwesomeIcon}).addTo(mymap).bindPopup(`<?php echo $info_content; ?>`);
            <?php } else { ?>
            L.marker([lat, lon], {icon: fontAwesomeIcon}).addTo(mymap);
            <?php } ?>

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(mymap);
        }

        let lat = <?php echo (!empty($manual_lat)) ? floatval($manual_lat) : false ?>,
            lon = <?php echo (!empty($manual_lng)) ? floatval($manual_lng) : false ?>;

        mapLeaflet (lat, lon);

        <?php  } } ?>

    }); // ends jquery ready function.
</script>