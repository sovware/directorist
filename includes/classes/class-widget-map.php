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
                $map_zoom_level = !empty($instance['zoom']) ? esc_html($instance['zoom']) : 10;
                $cats                           = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
                $font_type = get_directorist_option('font_type','line');
                $fa_or_la = ('line' == $font_type) ? "la " : "fa ";
                if(!empty($cats)){
                    $cat_icon                       = get_cat_icon($cats[0]->term_id);
                }
                $cat_icon = !empty($cat_icon) ? $fa_or_la . $cat_icon : 'fa fa-map-marker';

                $template_path = atbdp_get_widget_template_path( 'map-single' );
                if ( file_exists( $template_path ) ) {
                    include $template_path;
                }
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