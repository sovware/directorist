<?php
if (!class_exists('BD_Similar_Listings_Widget')) {
    /**
     * Adds BD_Similar_Listings_Widget widget.
     */
    class BD_Similar_Listings_Widget extends WP_Widget
    {

        /**
         * Register widget with WordPress.
         */
        function __construct()
        {
            $widget_options = array(
                'classname' => 'atbd_widget',
                'description' => esc_html__('You can show similar listing by this widget', 'directorist'),
            );
            parent::__construct(
                'bdsl_widget', // Base ID
                esc_html__('Directorist - Similar Listings', 'directorist'), // Name
                $widget_options // Args
            );
        }

        /**
         * Front-end display of widget.
         *
         * @param array $args Widget arguments.
         * @param array $instance Saved values from database.
         * @see WP_Widget::widget()
         *
         */
        public function widget($args, $instance)
        {
            $allowWidget = apply_filters('atbdp_allow_similar_widget', true);
            if (!$allowWidget) return;
            if (is_singular(ATBDP_POST_TYPE)) {
                $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Similar Listings', 'directorist');
                $sim_listing_num = !empty($instance['sim_listing_num']) ? $instance['sim_listing_num'] : 5;
                
                $template_file = 'similar-listing.php';
                $theme_template_file =  ATBDP_WIDGET_TEMPLATES_THEME_DIR . $template_file;
                $default_template_file = ATBDP_WIDGET_TEMPLATES_DEFAULT_DIR . $template_file;

                // Load theme template if exist
                $theme_template = atbdp_get_theme_file( $theme_template_file );
                if ( $theme_template ) {
                    include $theme_template;
                } 

                // Load default template
                if ( file_exists( $default_template_file ) ) {
                    include $default_template_file;
                }
            }
        }

        /**
         * Back-end widget form.
         *
         * @param array $instance Previously saved values from database.
         * @return void
         * @see WP_Widget::form()
         *
         */
        public function form($instance)
        {
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Similar Listings', 'directorist');
            $sim_listing_num = !empty($instance['sim_listing_num']) ? $instance['sim_listing_num'] : 5;
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'directorist'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                       value="<?php echo esc_attr($title); ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('sim_listing_num')); ?>"><?php esc_attr_e('Number of Listings:', 'directorist'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('sim_listing_num')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('sim_listing_num')); ?>" type="text"
                       value="<?php echo esc_attr($sim_listing_num); ?>">
            </p>

            <?php
        }

        /**
         * Sanitize widget form values as they are saved.
         *
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         *
         * @return array Updated safe values to be saved.
         * @see WP_Widget::update()
         *
         */
        public function update($new_instance, $old_instance)
        {
            $instance = array();
            $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
            $instance['sim_listing_num'] = (!empty($new_instance['sim_listing_num'])) ? intval($new_instance['sim_listing_num']) : '';

            return $instance;
        }

    } // class BD_Similar_Listings_Widget


}
