<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

if ( !class_exists('BD_Map_Widget')) {
    /**
     * Adds BD_Map_Widget widget.
     */
    class BD_Map_Widget extends WP_Widget {

        /*
         * register search widget
         */
        public function __construct ()
        {
            $widget_options = array(
                'classname' => 'atbd_widget',
                'description' => esc_html__('You can show single listing map by this widget', ATBDP_TEXTDOMAIN),
            );
            parent::__construct(
                'bdmw_widget', // Base ID
                esc_html__('Directorist - Map (Single Listing)', ATBDP_TEXTDOMAIN), // Name
                $widget_options // Args
            );
        }

        /**
         * Front-end display of widget.
         *
         * @see WP_Widget::widget()
         *
         * @param array $args Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget ($args, $instance)
        {
            if( is_singular(ATBDP_POST_TYPE)) {
                global $post;
                $manual_lat = get_post_meta($post->ID, '_manual_lat', true);
                $manual_lng = get_post_meta($post->ID, '_manual_lng', true);
                $address = get_post_meta($post->ID, '_address', true);
                $select_listing_map = get_directorist_option('select_listing_map', 'google');
                $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Map', ATBDP_TEXTDOMAIN);
                $map_zoom_level = !empty($instance['zoom']) ? esc_html($instance['zoom']) : 16;
                echo $args['before_widget'];
                echo '<div class="atbd_widget_title">';
                echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];
                echo '</div>';

                if ( !empty($manual_lat) && !empty($manual_lng)) {
                    ?>
                        <div class="atbdb_content_module_contents">
                            <div id="widgetMap" class="atbd_google_map"></div>
                        </div>

                    <?php } ?>
                    <?php
                    if('openstreet' == $select_listing_map) {
                        wp_register_script( 'openstreet_layer', ATBDP_PUBLIC_ASSETS . 'js/openstreetlayers.js', array( 'jquery' ), ATBDP_VERSION, true );
                        wp_enqueue_script( 'openstreet_layer' );
                    }
                    ?>
                    <script>

                        jQuery(document).ready(function ($) {
                            <?php if('google' == $select_listing_map) {?>
                            // Do not show map if lat long is empty or map is globally disabled.
                            <?php if ((!empty($manual_lat) && !empty($manual_lng))){ ?>
                            // initialize all vars here to avoid hoisting related misunderstanding.
                            var map, info_window, saved_lat_lng;
                            saved_lat_lng = {
                                lat:<?= (!empty($manual_lat)) ? floatval($manual_lat) : false ?>,
                                lng: <?= (!empty($manual_lng)) ? floatval($manual_lng) : false ?> }; // default is London city

                            // create an info window for map
                            info_window = new google.maps.InfoWindow({
                                maxWidth: 400/*Add configuration for max width*/
                            });


                            function initMap() {
                                /* Create new map instance*/
                                map = new google.maps.Map(document.getElementById('widgetMap'), {
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
                            <?php }
                            } elseif('openstreet' == $select_listing_map) { ?>
                            map = new OpenLayers.Map("widgetMap");

                            let mymap = (lon, lat) => {
                                map.addLayer(new OpenLayers.Layer.OSM());
                                let pois = new OpenLayers.Layer.Text( "My Points",
                                    { location:"",
                                        projection: map.displayProjection
                                    });
                                map.addLayer(pois);
                                // create layer switcher widget in top right corner of map.
                                let layer_switcher= new OpenLayers.Control.LayerSwitcher({});
                                map.addControl(layer_switcher);
                                //Set start centrepoint and zoom
                                let lonLat = new OpenLayers.LonLat( lon, lat )
                                    .transform(
                                        new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
                                        map.getProjectionObject() // to Spherical Mercator Projection
                                    );
                                let zoom= <?php echo !empty($map_zoom_level) ? intval($map_zoom_level) : 16; ?>;
                                let markers = new OpenLayers.Layer.Markers( "Markers" );
                                map.addLayer(markers);
                                markers.addMarker(new OpenLayers.Marker(lonLat));
                                map.setCenter (lonLat, zoom);
                            }

                            let lat = <?php echo !empty($manual_lat) ? floatval($manual_lat) : false;?>,
                                lon = <?php echo !empty($manual_lng) ? floatval($manual_lng) : false; ?>;

                            mymap(lon, lat);
                            $('#OL_Icon_33').append('<div class="mapHover"><?php echo !empty($address) ? esc_attr($address) : ''; ?></div>');
                       <?php }
                            ?>
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

                <?php
                echo $args['after_widget'];
            }
        }

        /**
         * Back-end widget form.
         *
         * @see WP_Widget::form()
         *
         * @param array $instance Previously saved values from database.
         * @return void
         */
        public function form ($instance)
        {
            $title = !empty($instance['title']) ? esc_html($instance['title']) : __( 'Map',ATBDP_TEXTDOMAIN );
            $map_zoom_level = !empty($instance['zoom']) ? esc_html($instance['zoom']) : 16;
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', ATBDP_TEXTDOMAIN); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                       value="<?php echo esc_attr($title); ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('zoom')); ?>"><?php esc_attr_e('Map zoom level:', ATBDP_TEXTDOMAIN); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('zoom')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('zoom')); ?>" type="number"
                       value="<?php echo esc_attr($map_zoom_level); ?>">
            </p>
            <?php
        }

        /**
         * Sanitize widget form values as they are saved.
         *
         * @see WP_Widget::update()
         *
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         *
         * @return array Updated safe values to be saved.
         */
        public function update($new_instance, $old_instance)
        {
            $instance = array();
            $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
            $instance['zoom'] = (!empty($new_instance['zoom'])) ? strip_tags($new_instance['zoom']) : '16';

            return $instance;
        }
    }

} // end class exist