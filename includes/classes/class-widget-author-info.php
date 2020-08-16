<?php
if (!class_exists('BD_Author_Info_Widget')) {
    /**
     * Adds BD_Popular_Listing_Widget widget.
     */
    class BD_Author_Info_Widget extends WP_Widget
    {
        /**
         * Register widget with WordPress.
         */
        function __construct()
        {
            $widget_options = array(
                'classname' => 'atbd_widget',
                'description' => esc_html__('You can show author info by this widget', 'directorist'),
            );
            parent::__construct(
                'bdsi_widget', // Base ID
                esc_html__('Directorist - Author Info', 'directorist'), // Name
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
            if (is_singular(ATBDP_POST_TYPE)) {
                $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Title', 'directorist');

                $template_path = atbdp_get_widget_template_path( 'author-info' );
                if ( file_exists( $template_path ) ) {
                    include $template_path;
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
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Author Info', 'directorist');
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'directorist'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
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

            return $instance;
        }
    }
}
