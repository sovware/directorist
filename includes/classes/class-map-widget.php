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
                'description' => esc_html__('You can show single listing map by this widget', 'directorist'),
            );
            parent::__construct(
                'bdmw_widget', // Base ID
                esc_html__('Directorist - Map (Single Listing)', 'directorist'), // Name
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
                $tagline    = get_post_meta($post->ID, '_tagline', true);
                $address = get_post_meta($post->ID, '_address', true);
                $t = get_the_title();
                $t = !empty($t) ? $t : __('No Title', 'directorist');
                $info_content = "<div class='map_info_window'> <h3>{$t}</h3>";
                $info_content .= "<p> {$tagline} </p>";
                $info_content .= "<address>{$address}</address>";
                $info_content .= "<a href='http://www.google.com/maps/place/{$manual_lat},{$manual_lng}' target='_blank'> " . __('View On Google Maps', 'directorist') . "</a></div>";
                $select_listing_map = get_directorist_option('select_listing_map', 'google');
                $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Map', 'directorist');
                $map_zoom_level = !empty($instance['zoom']) ? esc_html($instance['zoom']) : 16;
                $cats                           = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
                $font_type = get_directorist_option('font_type','line');
                $fa_or_la = ('line' == $font_type) ? "la " : "fa ";
                if(!empty($cats)){
                    $cat_icon                       = get_cat_icon($cats[0]->term_id);
                }
                $cat_icon = !empty($cat_icon) ? $fa_or_la . $cat_icon : 'fa fa-map-marker';
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
                if ('openstreet' == $select_listing_map) {
                    wp_register_script( 'openstreet_layer', ATBDP_PUBLIC_ASSETS . 'js/openstreetlayers.js', array( 'jquery' ), ATBDP_VERSION, true );
                    wp_enqueue_script( 'openstreet_layer' );
                    wp_enqueue_style('leaflet-css',ATBDP_PUBLIC_ASSETS . 'css/leaflet.css');
                }
                ?>
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
                            <?php if('google' == $select_listing_map) {?>
                            // Do not show map if lat long is empty or map is globally disabled.
                            <?php if ((!empty($manual_lat) && !empty($manual_lng))){ ?>
                            // initialize all vars here to avoid hoisting related misunderstanding.
                            var map, info_window, saved_lat_lng;
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
                                map = new google.maps.Map(document.getElementById('widgetMap'), {
                                    zoom: <?php echo !empty($map_zoom_level) ? intval($map_zoom_level) : 16; ?>,
                                    center: saved_lat_lng
                                });
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

                            }

                            initMap();
                            //Convert address tags to google map links -
                            $('address').each(function () {
                                var link = "<a href='http://maps.google.com/maps?q=" + encodeURIComponent($(this).text()) + "' target='_blank'>" + $(this).text() + "</a>";
                                $(this).html(link);
                            });
                            <?php }
                            } elseif('openstreet' == $select_listing_map) { ?>
                            function mapLeaflet (lat, lon)	 {

                                const fontAwesomeIcon = L.divIcon({
                                    html: '<div class="atbd_map_shape"><span class="<?php echo $cat_icon; ?>"></span></div>',
                                    iconSize: [20, 20],
                                    className: 'myDivIcon'
                                });

                                var mymap = L.map('widgetMap').setView([lat, lon], <?php echo !empty($map_zoom_level) ? $map_zoom_level : 16;?>);

                                L.marker([lat, lon], {icon: fontAwesomeIcon}).addTo(mymap).bindPopup("<?php echo $info_content; ?>");

                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                }).addTo(mymap);
                            }

                            let lat = <?php echo (!empty($manual_lat)) ? floatval($manual_lat) : false ?>,
                                lon = <?php echo (!empty($manual_lng)) ? floatval($manual_lng) : false ?>;

                            mapLeaflet (lat, lon);
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
            $title = !empty($instance['title']) ? esc_html($instance['title']) : __( 'Map','directorist' );
            $map_zoom_level = !empty($instance['zoom']) ? esc_html($instance['zoom']) : 16;
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'directorist'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                       value="<?php echo esc_attr($title); ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('zoom')); ?>"><?php esc_attr_e('Map zoom level:', 'directorist'); ?></label>
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