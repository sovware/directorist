<?php
global $post;
$listing_id = $post->ID;
$fm_plan = get_post_meta($listing_id, '_fm_plans', true);
$listing_info['address'] = get_post_meta($post->ID, '_address', true);
$listing_info['manual_lat'] = get_post_meta($post->ID, '_manual_lat', true);
$listing_info['manual_lng'] = get_post_meta($post->ID, '_manual_lng', true);
$listing_info['listing_prv_img'] = get_post_meta($post->ID, '_listing_prv_img', true);
$listing_info['hide_map'] = get_post_meta($post->ID, '_hide_map', true);
$select_listing_map = get_directorist_option('select_listing_map', 'google');
$display_map_field = get_directorist_option('display_map_field', 1);
extract($listing_info);
/*INFO WINDOW CONTENT*/
$t = get_the_title();
$t = !empty($t) ? $t : __('No Title', 'directorist');
$average = ATBDP()->review->get_average($listing_id);
$reviews_count = ATBDP()->review->db->count(array('post_id' => $post->ID)); // get total review count for this post
$reviews = (($reviews_count > 1) || ($reviews_count === 0)) ? __(' Reviews', 'directorist') : __(' Review', 'directorist');
$review_info = '';
$review_info = '';
if (!empty($enable_review)) {
    $review_info = "<div class='miwl-rating'><span class='atbd_meta atbd_listing_rating'>$average<i class='".atbdp_icon_type()."-star'></i></span>";
    $review_info .= "<div class='atbd_rating_count'>";
    $review_info .= "<p>" . $reviews_count . $reviews . "</p>";
    $review_info .= "</div></div>";
}
$tg = !empty($tagline) ? esc_html($tagline) : '';
$ad = !empty($address) ? esc_html($address) : '';
$default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
$listing_prv_imgurl = wp_get_attachment_image_src($listing_prv_img, 'small')[0];
$img_url = !empty($listing_prv_imgurl)?$listing_prv_imgurl:$default_image;
$image = "<img src=". $img_url.">";
$info_content = "<div class='map_info_window'>$image <div class='miw-contents'><h3>{$t}</h3>";
$info_content .= apply_filters("atbdp_address_in_map_info_window", "<address>{$ad}</address>");
$info_content .= "<div class='miw-contents-footer'>{$review_info}";
$info_content .= "<a href='http://www.google.com/maps?daddr={$manual_lat},{$manual_lng}' target='_blank'> " . __('Get Direction', 'directorist') . "</a></div></div></div>";
/*END INFO WINDOW CONTENT*/
$map_zoom_level = get_directorist_option('map_zoom_level', 16);
$disable_map = get_directorist_option('disable_map', 0);
$listing_location_text = get_directorist_option('listing_location_text', __('Location', 'directorist'));
$main_col_size = is_active_sidebar('right-sidebar-listing') ? 'col-lg-8' : 'col-lg-12';
if (!$disable_map && (empty($hide_map)) && !empty($manual_lng || $manual_lat) && !empty($display_map_field)) { ?>
                <div class="atbd_content_module">
                    <div class="atbd_content_module__tittle_area">
                        <div class="atbd_area_title">
                            <h4>
                                <span class="<?php atbdp_icon_type(true);?>-map atbd_area_icon"></span><?php _e($listing_location_text, 'directorist'); ?>
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
<?php }
if ('openstreet' == $select_listing_map) {
    wp_register_script( 'openstreet_layer', ATBDP_PUBLIC_ASSETS . 'js/openstreetlayers.js', array( 'jquery' ), ATBDP_VERSION, true );
    wp_enqueue_script( 'openstreet_layer' );
    wp_localize_script('openstreet_layer', 'atbdp_map', array(
        'Overlays' => __('Overlays','directorist'),
        'base_layer' => __('Base Layer','directorist')
    ));
} ?>
<script>
    jQuery(document).ready(function ($) {
        // Do not show map if lat long is empty or map is globally disabled.
        <?php if (!$disable_map && (!empty($manual_lat) && !empty($manual_lng)) && !empty($display_map_field) && empty($hide_map) ){ if('google' == $select_listing_map) { ?>
        // initialize all vars here to avoid hoisting related misunderstanding.
        var map, info_window, saved_lat_lng, info_content;
        saved_lat_lng = {
            lat:<?php echo (!empty($manual_lat)) ? floatval($manual_lat) : false ?>,
            lng: <?php echo (!empty($manual_lng)) ? floatval($manual_lng) : false ?> }; // default is London city
        info_content = "<?php echo $info_content; ?>";
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
        setInterval(() => {
            $('img.olTileImage').each((index, el) => {

                if($(el).attr('src').startsWith('http:')){
                    var attr = $(el).attr('src').split('/')[0] = "https:";

                    var url = attr+"/"+$(el).attr('src').split('/').slice(1, 15).join('/');
                    $(el).attr('src', url)

                }

            })
        }, 1000);
        map = new OpenLayers.Map("gmap");
        let mymap = (lon, lat) => {
            map.addLayer(new OpenLayers.Layer.OSM());
            let pois = new OpenLayers.Layer.Text("<?php _e('My Points','directorist');?>",
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
            let markers = new OpenLayers.Layer.Markers("<?php _e('Markers','directorist');?>");
            map.addLayer(markers);
            markers.addMarker(new OpenLayers.Marker(lonLat));
            map.setCenter(lonLat, zoom);
        };
        let lat = <?php echo !empty($manual_lat) ? floatval($manual_lat) : false;?>,
            lon = <?php echo !empty($manual_lng) ? floatval($manual_lng) : false; ?>;
        mymap(lon, lat);

        var abc = `<?php echo !empty($info_content)?$info_content:'' ?>` + '<span><i class="fa fa-times"></i></span>';

        $('#OL_Icon_33').append('<div class="mapHover"></div>');
        $('.mapHover').html(abc);
        <?php } }?>
        /* initialize slick  */

        $(".olAlphaImg").on("click", function(){
            $('.mapHover').addClass('active');
        });

        $('.mapHover span i.fa-times').on('click', (e) => {
            $('.mapHover').removeClass('active');
        });

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
    .mapHover.active{
        display: block;
    }
</style>
