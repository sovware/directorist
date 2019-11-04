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
$display_map_field = apply_filters('atbdp_show_single_listing_map', $display_map_field);
$cats                           = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
$font_type = get_directorist_option('font_type','line');
$fa_or_la = ('line' == $font_type) ? "la " : "fa ";
if(!empty($cats)){
    $cat_icon                       = get_cat_icon($cats[0]->term_id);
}
$cat_icon = !empty($cat_icon) ? $fa_or_la . $cat_icon : 'fa fa-map-marker';

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
$listing_prv_imgurl = atbdp_image_cropping($listing_prv_img, 150, 150, true, 100)['url'];
$img_url = !empty($listing_prv_imgurl)?$listing_prv_imgurl:$default_image;
$image = "<img src=". $img_url.">";
$display_map_info               = get_directorist_option('display_map_info', 1);
$display_image_map              = get_directorist_option('display_image_map', 1);
$display_title_map              = get_directorist_option('display_title_map', 1);
$display_address_map            = get_directorist_option('display_address_map', 1);
$display_direction_map          = get_directorist_option('display_direction_map', 1);
if(empty($display_image_map)) {
    $image = '';
}
if(empty($display_title_map)) {
    $t = '';
}
$info_content = "";
if(!empty($display_image_map) || !empty($display_title_map)) {
    $info_content .= "<div class='map-info-wrapper'><div class='map-info-img'>$image</div><div class='map-info-details'><div class='atbdp-listings-title-block'><h3>$t</h3></div>";
}
if(!empty($display_address_map)) {
    $info_content .= apply_filters("atbdp_address_in_map_info_window", "<address>{$ad}</address>");
}
if(!empty($display_direction_map)) {
    $info_content .= "<div class='map_get_dir'><a href='http://www.google.com/maps?daddr={$manual_lat},{$manual_lng}' target='_blank'> " . __('Get Direction', 'directorist') . "</a></div><span id='iw-close-btn'><i class='la la-times'></i></span></div></div>";
}
/*END INFO WINDOW CONTENT*/
$map_zoom_level = get_directorist_option('map_zoom_level', 16);
$disable_map = get_directorist_option('disable_map', 0);
$listing_location_text = get_directorist_option('listing_location_text', __('Location', 'directorist'));
$listing_location_text = apply_filters('atbdp_single_listing_map_section_text', $listing_location_text);
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
    wp_enqueue_style('leaflet-css',ATBDP_PUBLIC_ASSETS . 'css/leaflet.css');
} ?>

<script>
    var MAP_PIN = 'M0-48c-9.8 0-17.7 7.8-17.7 17.4 0 15.5 17.7 30.6 17.7 30.6s17.7-15.4 17.7-30.6c0-9.6-7.9-17.4-17.7-17.4z';

    var inherits = function (childCtor, parentCtor) {
        /** @constructor */
        function tempCtor() {
        }

        tempCtor.prototype = parentCtor.prototype;
        childCtor.superClass_ = parentCtor.prototype;
        childCtor.prototype = new tempCtor();
        childCtor.prototype.constructor = childCtor;
    };

    function Marker(options) {
        google.maps.Marker.apply(this, arguments);

        if (options.map_icon_label) {
            this.MarkerLabel = new MarkerLabel({
                map: this.map,
                marker: this,
                text: options.map_icon_label
            });
            this.MarkerLabel.bindTo('position', this, 'position');
        }
    }

    // Apply the inheritance
    inherits(Marker, google.maps.Marker);

    // Custom Marker SetMap
    Marker.prototype.setMap = function () {
        google.maps.Marker.prototype.setMap.apply(this, arguments);
        (this.MarkerLabel) && this.MarkerLabel.setMap.apply(this.MarkerLabel, arguments);
    };

    // Marker Label Overlay
    var MarkerLabel = function (options) {
        var self = this;
        this.setValues(options);

        // Create the label container
        this.div = document.createElement('div');
        this.div.className = 'map-icon-label';

        // Trigger the marker click handler if clicking on the label
        google.maps.event.addDomListener(this.div, 'click', function (e) {
            (e.stopPropagation) && e.stopPropagation();
            google.maps.event.trigger(self.marker, 'click');
        });
    };

    // Create MarkerLabel Object
    MarkerLabel.prototype = new google.maps.OverlayView;

    // Marker Label onAdd
    MarkerLabel.prototype.onAdd = function () {
        var pane = this.getPanes().overlayImage.appendChild(this.div);
        var self = this;

        this.listeners = [
            google.maps.event.addListener(this, 'position_changed', function () {
                self.draw();
            }),
            google.maps.event.addListener(this, 'text_changed', function () {
                self.draw();
            }),
            google.maps.event.addListener(this, 'zindex_changed', function () {
                self.draw();
            })
        ];
    };

    // Marker Label onRemove
    MarkerLabel.prototype.onRemove = function () {
        this.div.parentNode.removeChild(this.div);

        for (var i = 0, I = this.listeners.length; i < I; ++i) {
            google.maps.event.removeListener(this.listeners[i]);
        }
    };

    // Implement draw
    MarkerLabel.prototype.draw = function () {
        var projection = this.getProjection();
        var position = projection.fromLatLngToDivPixel(this.get('position'));
        var div = this.div;

        this.div.innerHTML = this.get('text').toString();

        div.style.zIndex = this.get('zIndex'); // Allow label to overlay marker
        div.style.position = 'absolute';
        div.style.display = 'block';
        div.style.left = (position.x - (div.offsetWidth / 2)) + 'px';
        div.style.top = (position.y - div.offsetHeight) + 'px';
    };


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

            L.marker([lat, lon], {icon: fontAwesomeIcon}).addTo(mymap).bindPopup(`<?php echo $info_content; ?>`);

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

