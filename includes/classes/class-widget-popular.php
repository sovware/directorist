<?php
if (!class_exists('BD_Popular_Listing_Widget')) {
    /**
     * Adds BD_Popular_Listing_Widget widget.
     */
    class BD_Popular_Listing_Widget extends WP_Widget
    {

        /**
         * Register widget with WordPress.
         */
        function __construct()
        {
            $widget_options = array(
                'classname' => 'atbd_widget',
                'description' => esc_html__('You can show popular listing by this widget', 'directorist'),
            );
            parent::__construct(
                'bdpl_widget', // Base ID
                esc_html__('Directorist - Popular Listings', 'directorist'), // Name
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
            $allowWidget = apply_filters('atbdp_allow_popular_widget', true);
            if (!$allowWidget) return;
            $single_only  = !empty( $instance['single_only'] ) ? 1 : 0;

            $template_path = atbdp_get_widget_template_path( 'popular-listings' );
            if ( file_exists( $template_path ) ) {
                include $template_path;
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
            $title              = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Popular Listings', 'directorist');
            $pop_listing_num    = !empty($instance['pop_listing_num']) ? $instance['pop_listing_num'] : 5;
            $single_only        = !empty($instance['single_only']) ? 1 : 0;
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'directorist'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                       value="<?php echo esc_attr($title); ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('pop_listing_num')); ?>"><?php esc_attr_e('Number of Listings:', 'directorist'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('pop_listing_num')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('pop_listing_num')); ?>" type="text"
                       value="<?php echo esc_attr($pop_listing_num); ?>">
            </p>

            <p>
                <input <?php checked( $single_only,1 ); ?> id="<?php echo $this->get_field_id( 'single_only' ); ?>" name="<?php echo $this->get_field_name( 'single_only' ); ?>" value="1" type="checkbox" />
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
            $instance                       = array();
            $instance['title']              = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
            $instance['pop_listing_num']    = (!empty($new_instance['pop_listing_num'])) ? intval($new_instance['pop_listing_num']) : '';
            $instance['single_only']        = (!empty($new_instance['single_only'])) ? 1 : 0;
            return $instance;
        }

    } // class BD_Popular_Listing_Widget


}
