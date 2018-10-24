<?php
if (!class_exists('BD_Login_Form_Widget')) {
    /**
     * Adds BD_Popular_Listing_Widget widget.
     */
    class BD_Login_Form_Widget extends WP_Widget
    {
        /**
         * Register widget with WordPress.
         */
        function __construct ()
        {
            $widget_options = array(
                'classname' => 'listings',
                'description' => esc_html__('When user will not log in, then you can show login form by this widget', ATBDP_TEXTDOMAIN),
            );
            parent::__construct(
                'bdlf_widget', // Base ID
                esc_html__('Directorist - Login Form', ATBDP_TEXTDOMAIN), // Name
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
            if( is_singular(ATBDP_POST_TYPE)) {
                if (!is_user_logged_in()) {
                    $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Title', ATBDP_TEXTDOMAIN);
                    echo $args['before_widget'];

                    echo $args['before_title'] . esc_html(apply_filters('widget_submit_item_title', $title)) . $args['after_title'];
                    ?>
                    <div class="directorist">
                    <?php
                        wp_login_form();
                        wp_register();
                    ?>
                    </div>
                    <?php
                    echo $args['after_widget'];
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
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Login Form', ATBDP_TEXTDOMAIN);
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title', ATBDP_TEXTDOMAIN); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                       value="<?php echo esc_attr($title); ?>">
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

            return $instance;
        }
    }
}