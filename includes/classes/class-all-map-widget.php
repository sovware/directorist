<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

if ( !class_exists('BD_All_Map_Widget')) {
    /**
     * Adds BD_Map_Widget widget.
     */
    class BD_All_Map_Widget extends WP_Widget {

        /*
         * register search widget
         */
        public function __construct ()
        {
            $widget_options = array(
                'classname' => 'atbd_widget',
                'description' => esc_html__('You can show all maps by this widget', ATBDP_TEXTDOMAIN),
            );
            parent::__construct(
                'bdamw_widget', // Base ID
                esc_html__('Directorist - All Maps', ATBDP_TEXTDOMAIN), // Name
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
            $display_map = get_directorist_option('display_map_field',1);
            if(!empty($display_map)) {
                $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Map', ATBDP_TEXTDOMAIN);
                echo $args['before_widget'];
                echo '<div class="atbd_widget_title">';
                echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];
                echo '</div>';
                $map_zoom_level = !empty($instance['zoom']) ? esc_html($instance['zoom']) : 16;
                wp_enqueue_script('atbdp-map-view', ATBDP_PUBLIC_ASSETS . 'js/map-view.js');
                $data = array(
                    'plugin_url' => ATBDP_URL,
                    'zoom' => !empty($map_zoom_level) ? $map_zoom_level : 16
                );
                wp_localize_script('atbdp-map-view', 'atbdp_map', $data);
                $map = array(
                    'post_type' => ATBDP_POST_TYPE,
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                );
                $all_listings = new WP_Query($map); ?>
                <!-- the loop -->
                <div class="atbdp-body atbdp-map embed-responsive embed-responsive-16by9 atbdp-margin-bottom"
                     data-type="markerclusterer" style="height: 330px;">
                    <?php while ($all_listings->have_posts()) : $all_listings->the_post();
                        global $post;
                        $manual_lat = get_post_meta($post->ID, '_manual_lat', true);
                        $manual_lng = get_post_meta($post->ID, '_manual_lng', true);
                        $listing_img = get_post_meta(get_the_ID(), '_listing_img', true);
                        $listing_prv_img = get_post_meta(get_the_ID(), '_listing_prv_img', true);
                        $crop_width = get_directorist_option('crop_width', 360);
                        $crop_height = get_directorist_option('crop_height', 300);
                        $address = get_post_meta(get_the_ID(), '_address', true);
                        if (!empty($listing_prv_img)) {


                            $prv_image = wp_get_attachment_image_src($listing_prv_img, 'large')[0];


                        }
                        if (!empty($listing_img[0])) {

                            $default_img = atbdp_image_cropping(ATBDP_PUBLIC_ASSETS . 'images/grid.jpg', $crop_width, $crop_height, true, 100)['url'];;
                            $gallery_img = wp_get_attachment_image_src($listing_img[0], 'medium')[0];

                        }
                        ?>

                        <?php if (!empty($manual_lat) && !empty($manual_lng)) : ?>
                            <div class="marker" data-latitude="<?php echo $manual_lat; ?>"
                                 data-longitude="<?php echo $manual_lng; ?>">
                                <div>

                                    <div class="media-image">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php
                                            $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
                                            if (!empty($listing_prv_img)) {

                                                echo '<img src="' . esc_url($prv_image) . '" alt="' . esc_html(stripslashes(get_the_title())) . '">';

                                            }
                                            if (!empty($listing_img[0]) && empty($listing_prv_img)) {

                                                echo '<img src="' . esc_url($gallery_img) . '" alt="' . esc_html(stripslashes(get_the_title())) . '">';

                                            }
                                            if (empty($listing_img[0]) && empty($listing_prv_img)) {

                                                echo '<img src="' . $default_image . '" alt="' . esc_html(stripslashes(get_the_title())) . '">';

                                            }
                                            ?>
                                        </a>
                                    </div>


                                    <div class="media-contents">
                                        <div class="atbdp-listings-title-block">
                                            <h3 class="atbdp-no-margin"><a
                                                        href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                                        </div>

                                        <span class="glyphicon glyphicon-briefcase"></span><a
                                                href=""><?php echo $address; ?></a>


                                        <?php do_action('atbdp_after_listing_content', $post->ID, 'map'); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                    <?php endwhile; ?>
                </div>
                <!-- end of the loop -->

                <!-- Use reset postdata to restore orginal query -->
                <?php wp_reset_postdata();

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