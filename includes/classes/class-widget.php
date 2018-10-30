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
                'classname' => 'listings',
                'description' => esc_html__('You can show popular listing by this widget', ATBDP_TEXTDOMAIN),
            );
            parent::__construct(
                'bdpl_widget', // Base ID
                esc_html__('Directorist - Popular Listings', ATBDP_TEXTDOMAIN), // Name
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
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Popular Listings', ATBDP_TEXTDOMAIN);
            $pop_listing_num = !empty($instance['pop_listing_num']) ? $instance['pop_listing_num'] : 5;
            echo $args['before_widget'];

            echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];

            ATBDP()->show_popular_listing($pop_listing_num);


            echo $args['after_widget'];
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
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Popular Listings', ATBDP_TEXTDOMAIN);
            $pop_listing_num = !empty($instance['pop_listing_num']) ? $instance['pop_listing_num'] : 5;
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', ATBDP_TEXTDOMAIN); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                       value="<?php echo esc_attr($title); ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('pop_listing_num')); ?>"><?php esc_attr_e('No of Listings:', ATBDP_TEXTDOMAIN); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('pop_listing_num')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('pop_listing_num')); ?>" type="text"
                       value="<?php echo esc_attr($pop_listing_num); ?>">
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
            $instance['pop_listing_num'] = (!empty($new_instance['pop_listing_num'])) ? intval($new_instance['pop_listing_num']) : '';

            return $instance;
        }

    } // class BD_Popular_Listing_Widget


}

if (!class_exists('ATBDP_Submit_Listing_Widget')) {
    /**
     * Adds ATBDP_Submit_Listing_Widget widget.
     */
    class ATBDP_Submit_Listing_Widget extends WP_Widget
    {

        /**
         * Register widget with WordPress.
         */
        function __construct()
        {
            $widget_options = array(
                'classname' => 'submit-listings',
                'description' => esc_html__('You can show submit listing button by this widget', ATBDP_TEXTDOMAIN),
            );
            parent::__construct(
                'atbdp_submit_listing_widget', // Base ID
                esc_html__('Directorist - Submit Listings', ATBDP_TEXTDOMAIN), // Name
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
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Submit Your Listing', ATBDP_TEXTDOMAIN);
            $button_text = !empty($instance['button_text']) ? esc_html($instance['button_text']) : esc_html__('Submit new listing', ATBDP_TEXTDOMAIN);
            $disable_widget_login = !empty($instance['disable_widget_login']) ? $instance['disable_widget_login'] : 0;
            echo $args['before_widget'];

            echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];
            ?>
            <div class="directory_user_area_form">
                <a href="<?= esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>" class="<?= atbdp_directorist_button_classes(); ?>">
                    <?php echo esc_html($button_text);?>
                </a>

                <?php
                if (!$disable_widget_login) {
                    atbdp_after_new_listing_button(); // fires an empty action to let dev extend by adding anything here
                    if (!is_user_logged_in()) {
                        wp_login_form();
                        wp_register();
                    }
                    /**
                     * Fires after the side bar login from is rendered on single listing page
                     *
                     *
                     * @since 1.0.0
                     *
                     * @param object|WP_post $post The current post object which is our listing post
                     * @param array $listing_info The meta information of the current listing
                     */

                    //do_action('atbdp_after_sidebar_login_form', $post, $listing_info);
                    do_action('atbdp_after_widget_login_form', $args, $instance);
                }
                ?>
            </div>


        <?php
            echo $args['after_widget'];
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
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Submit Listings', ATBDP_TEXTDOMAIN);
            $button_text = !empty($instance['button_text']) ? esc_html($instance['button_text']) : esc_html__('Submit New Listing', ATBDP_TEXTDOMAIN);
            $disable_widget_login = !empty($instance['disable_widget_login']) ? $instance['disable_widget_login'] : 0;
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', ATBDP_TEXTDOMAIN); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                       placeholder="<?php _e('eg. Submit listing', ATBDP_TEXTDOMAIN); ?>"
                       value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('button_text')); ?>"><?php esc_attr_e('Text for the button:', ATBDP_TEXTDOMAIN); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_text')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('button_text')); ?>" type="text"
                       placeholder="<?php _e('eg. Submit a listing', ATBDP_TEXTDOMAIN); ?>"
                       value="<?php echo esc_attr($button_text); ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('disable_widget_login')); ?>"><?php esc_attr_e('Check it to disable login form in this widget', ATBDP_TEXTDOMAIN); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('disable_widget_login')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('disable_widget_login')); ?>" type="checkbox"
                       value="1" <?php checked(1, $disable_widget_login, true); ?>>
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
            $instance['button_text'] = (!empty($new_instance['button_text'])) ? strip_tags($new_instance['button_text']) : '';
            $instance['disable_widget_login'] = (!empty($new_instance['disable_widget_login'])) ? $new_instance['disable_widget_login'] : 0;

            return $instance;
        }

    } // class ATBDP_Submit_Listing_Widget


}