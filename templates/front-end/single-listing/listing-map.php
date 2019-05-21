<?php
global $post;
$listing_id = $post->ID;
$fm_plan = get_post_meta($listing_id, '_fm_plans', true);
$listing_info['address'] = get_post_meta($post->ID, '_address', true);
$listing_info['manual_lat'] = get_post_meta($post->ID, '_manual_lat', true);
$listing_info['manual_lng'] = get_post_meta($post->ID, '_manual_lng', true);
$listing_info['hide_map'] = get_post_meta($post->ID, '_hide_map', true);
$select_listing_map = get_directorist_option('select_listing_map', 'google');
$display_map_field = get_directorist_option('display_map_field', 1);
extract($listing_info);
/*INFO WINDOW CONTENT*/
$t = get_the_title();
$t = !empty($t) ? $t : __('No Title', ATBDP_TEXTDOMAIN);
$tg = !empty($tagline) ? esc_html($tagline) : '';
$ad = !empty($address) ? esc_html($address) : '';
$image = (!empty($attachment_id[0])) ? "<img src='" . esc_url(wp_get_attachment_image_url($attachment_id[0], 'thumbnail')) . "'>" : '';
$info_content  = "<div class='map_info_window'> <h3>{$t}</h3>";
$info_content .= "<p> {$tg} </p>";
$info_content .= $image; // add the image if available
$info_content .= "<address>{$ad}</address>";
$info_content .= "<a href='http://www.google.com/maps/place/{$manual_lat},{$manual_lng}' target='_blank'> " . __('View On Google Maps', ATBDP_TEXTDOMAIN) . "</a></div>";
/*END INFO WINDOW CONTENT*/
$map_zoom_level = get_directorist_option('map_zoom_level', 16);
$disable_map = get_directorist_option('disable_map', 0);
$listing_location_text = get_directorist_option('listing_location_text', __('Location', ATBDP_TEXTDOMAIN));
$main_col_size = is_active_sidebar('right-sidebar-listing') ? 'col-lg-8' : 'col-lg-12';
if (!$disable_map && (empty($hide_map)) && !empty($manual_lng || $manual_lat) && !empty($display_map_field)) { ?>

                <div class="atbd_content_module">
                    <div class="atbd_content_module__tittle_area">
                        <div class="atbd_area_title">
                            <h4>
                                <span class="fa fa fa-map atbd_area_icon"></span><?php _e($listing_location_text, ATBDP_TEXTDOMAIN); ?>
                            </h4>
                        </div>
                    </div>

                    <div class="atbdb_content_module_contents">
                        <div id="gmap" class="atbd_google_map"></div>
                    </div>
                </div><!-- end .atbd_custom_fields_contents -->

<?php } ?>

<?php
if ('openstreet' == $select_listing_map) {
    wp_register_script( 'openstreet_layer', ATBDP_PUBLIC_ASSETS . 'js/openstreetlayers.js', array( 'jquery' ), ATBDP_VERSION, true );
    wp_enqueue_script( 'openstreet_layer' );
}
?>
<script>

    jQuery(document).ready(function ($) {
        // Do not show map if lat long is empty or map is globally disabled.
        <?php if (!$disable_map && (!empty($manual_lat) && !empty($manual_lng)) && !empty($display_map_field) && empty($hide_map) ){
        if('google' == $select_listing_map) {
        ?>

        // initialize all vars here to avoid hoisting related misunderstanding.
        var map, info_window, saved_lat_lng, info_content;
        saved_lat_lng = {
            lat:<?= (!empty($manual_lat)) ? floatval($manual_lat) : false ?>,
            lng: <?= (!empty($manual_lng)) ? floatval($manual_lng) : false ?> }; // default is London city
        info_content = "<?= $info_content; ?>";

        // create an info window for map
        info_window = new google.maps.InfoWindow({
            content: info_content,
            maxWidth: 400/*Add configuration for max width*/
        });


        function initMap() {
            /* Create new map instance*/
            map = new google.maps.Map(document.getElementById('gmap'), {
                zoom: <?php echo !empty($map_zoom_level) ? intval($map_zoom_level) : 16; ?>,
                center: saved_lat_lng
            });
            var marker = new google.maps.Marker({
                map: map,
                position: saved_lat_lng
            });
            marker.addListener('click', function () {
                info_window.open(map, marker);
            });
        }


        initMap();
        //Convert address tags to google map links -
        $('address').each(function () {
            var link = "<a href='http://maps.google.com/maps?q=" + encodeURIComponent($(this).text()) + "' target='_blank'>" + $(this).text() + "</a>";
            $(this).html(link);
        });
        <?php } elseif('openstreet' == $select_listing_map) { ?>


        map = new OpenLayers.Map("gmap");

        let mymap = (lon, lat) => {
            map.addLayer(new OpenLayers.Layer.OSM());
            let pois = new OpenLayers.Layer.Text("My Points",
                {
                    location: "",
                    projection: map.displayProjection
                });
            map.addLayer(pois);
            // create layer switcher widget in top right corner of map.
            let layer_switcher = new OpenLayers.Control.LayerSwitcher({});
            map.addControl(layer_switcher);
            //Set start centrepoint and zoom
            let lonLat = new OpenLayers.LonLat(lon, lat)
                .transform(
                    new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
                    map.getProjectionObject() // to Spherical Mercator Projection
                );
            let zoom = <?php echo !empty($map_zoom_level) ? intval($map_zoom_level) : 16; ?>;
            let markers = new OpenLayers.Layer.Markers("Markers");
            map.addLayer(markers);
            markers.addMarker(new OpenLayers.Marker(lonLat));
            map.setCenter(lonLat, zoom);
        }

        let lat = <?php echo !empty($manual_lat) ? floatval($manual_lat) : false;?>,
            lon = <?php echo !empty($manual_lng) ? floatval($manual_lng) : false; ?>;

        mymap(lon, lat);

        $('#OL_Icon_33').append('<div class="mapHover"><?php echo !empty($address) ? esc_attr($address) : ''; ?></div>');
        <?php } }?>
        /* initialize slick  */


    }); // ends jquery ready function.
</script>
<style>
    #OL_Icon_33{
        position: relative;
    }
    .mapHover {
        position: absolute;
        background: #fff;
        padding: 5px;
        width: 150px;
        border-radius: 3px;
        border: 1px solid #ddd;
        display: none;
    }
    #OL_Icon_33:hover .mapHover{
        display: block;
    }
</style>
