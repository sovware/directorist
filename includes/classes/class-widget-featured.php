<?php
if (!class_exists('BD_Featured_Listings_Widget')) {
    /**
     * Adds BD_Featured_Listings_Widget widget.
     */
    class BD_Featured_Listings_Widget extends WP_Widget
    {

        /**
         * Register widget with WordPress.
         */
        function __construct()
        {
            $widget_options = array(
                'classname' => 'atbd_widget',
                'description' => esc_html__('You can show featured listings by this widget', 'directorist'),
            );
            parent::__construct(
                'bdfl_widget', // Base ID
                esc_html__('Directorist - Featured Listings', 'directorist'), // Name
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
        public function widget($args, $instance)
        {
            $allowWidget = apply_filters('atbdp_allow_featured_widget', true);
            if (!$allowWidget) return;
            $single_only    = ! empty( $instance['single_only'] ) ? 1 : 0;
            
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Featured Listings', 'directorist');
            $f_listing_num = !empty($instance['f_listing_num']) ? $instance['f_listing_num'] : 5;

            $template_path = atbdp_get_widget_template_path( 'featured-listings' );
            if ( file_exists( $template_path ) ) {
                if(!empty($single_only)) {
                    if(is_singular(ATBDP_POST_TYPE)) {
                        include $template_path;
                    }
                } else {
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
        public function form($instance)
        {
            $values = array(
                'single_only'=>0,
            );

            $instance = wp_parse_args((array)$instance,$values);
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Featured Listings', 'directorist');
            $f_listing_num = !empty($instance['f_listing_num']) ? $instance['f_listing_num'] : 5;
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'directorist'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                       value="<?php echo esc_attr($title); ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('f_listing_num')); ?>"><?php esc_attr_e('Number of Listings:', 'directorist'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('f_listing_num')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('f_listing_num')); ?>" type="text"
                       value="<?php echo esc_attr($f_listing_num); ?>">
            </p>

            <p>
                <input <?php checked( $instance['single_only'],1 ); ?> id="<?php echo $this->get_field_id( 'single_only' ); ?>" name="<?php echo $this->get_field_name( 'single_only' ); ?>" value="1" type="checkbox" />
                <label for="<?php echo $this->get_field_id( 'single_only' ); ?>"><?php _e( 'Display only on single listing', 'directorist' ); ?></label>
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
            $instance['title']               = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
            $instance['f_listing_num']       = (!empty($new_instance['f_listing_num'])) ? intval($new_instance['f_listing_num']) : '';
            $instance['single_only']         = isset( $new_instance['single_only'] ) ? 1 : 0;
            return $instance;
        }

    } // class BD_Featured_Listings_Widget


}
