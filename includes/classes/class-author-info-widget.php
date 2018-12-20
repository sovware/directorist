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
                'description' => esc_html__('You can show author info by this widget', ATBDP_TEXTDOMAIN),
            );
            parent::__construct(
                'bdsi_widget', // Base ID
                esc_html__('Directorist - Author Info', ATBDP_TEXTDOMAIN), // Name
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
            if (is_singular(ATBDP_POST_TYPE)) {
                $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Title', ATBDP_TEXTDOMAIN);
                echo $args['before_widget'];
                echo '<div class="atbd_widget_title">';
                echo $args['before_title'] . esc_html(apply_filters('widget_submit_item_title', $title)) . $args['after_title'];
                echo '</div>';
                ?>
                <div class="atbdp atbd_author_info_widget">

                    <div class="atbd_avatar_wrapper">
                        <?php
                        $listing_id = get_the_ID();
                        $author_id = get_post_field('post_author', $listing_id);
                        $author_name = get_the_author_meta('display_name', $author_id);
                        $user_registered = get_the_author_meta('user_registered', $author_id);
                        $u_pro_pic = get_user_meta($author_id, 'pro_pic', true);
                        $avata_img = get_avatar($author_id, 32);

                        ?>
                        <div class="atbd_review_avatar"><?php if (empty($u_pro_pic)) {
                                echo $avata_img;
                            }
                            if (!empty($u_pro_pic)) { ?><img
                                src="<?php echo esc_url($u_pro_pic); ?>"
                                alt="Avatar Image"><?php } ?></div>
                        <div class="atbd_name_time">
                            <h4><?= esc_html($author_name); ?></h4>
                            <span class="review_time"><?php
                                printf(__('Member since %s ago', ATBDP_TEXTDOMAIN), human_time_diff(strtotime($user_registered), current_time('timestamp'))); ?></span>
                        </div>
                    </div>

                    <div class="atbd_widget_contact_info">
                        <ul>
                            <?php
                            $address = esc_attr(get_user_meta($author_id, 'address', true));
                            $phone = esc_attr(get_user_meta($author_id, 'phone', true));
                            $email = get_the_author_meta('user_email', $author_id);
                            $website = get_the_author_meta('user_url', $author_id);;
                            $facebook = get_user_meta($author_id, 'facebook', true);
                            $twitter = get_user_meta($author_id, 'twitter', true);
                            $google = get_user_meta($author_id, 'google', true);
                            $linkedIn = get_user_meta($author_id, 'linkedIn', true);
                            $youtube = get_user_meta($author_id, 'youtube', true);
                            if (!empty($address)) { ?>
                                <li>
                                    <span class="fa fa-map-marker"></span>
                                    <span class="atbd_info"><?= !empty($address)?esc_html($address):''; ?></span>
                                </li>
                            <?php } ?>

                            <?php
                            if (isset($phone) && !is_empty_v($phone)) { ?>
                                <!-- In Future, We will have to use a loop to print more than 1 number-->
                                <li>
                                    <span class="fa fa-phone"></span>
                                    <span class="atbd_info"><?= esc_html($phone); ?></span>
                                </li>
                            <?php } ?>

                            <?php if (!empty($email)) { ?>
                                <li>
                                    <span class="fa fa-envelope"></span>
                                    <span class="atbd_info"><?= esc_html($email); ?></span>
                                </li>
                            <?php } ?>

                            <?php if (!empty($website)) { ?>
                                <li>
                                    <span class="fa fa-globe"></span>
                                    <a href="<?= esc_url($website); ?>" class="atbd_info"><?= esc_html($website); ?></a>
                                </li>
                            <?php } ?>

                        </ul>
                    </div>
                    <div class="atbd_social_wrap">
                        <?php
                        if ($facebook) {
                            printf('<p><a target="_blank" href="%s"><span class="fa fa-facebook"></span></a></p>', $facebook);
                        }
                        if ($twitter) {
                            printf('<p><a target="_blank" href="%s"><span class="fa fa-twitter"></span></a></p>', $twitter);
                        }
                        if ($google) {
                            printf('<p><a target="_blank" href="%s"><span class="fa fa-google-plus"></span></a></p>', $google);
                        }
                        if ($linkedIn) {
                            printf('<p><a target="_blank" href="%s"><span class="fa fa-linkedin"></span></a></p>', $linkedIn);
                        }
                        if ($youtube) {
                            printf('<p><a target="_blank" href="%s"><span class="fa fa-youtube"></span></a></p>', $youtube);
                        }
                        ?>
                    </div>
                    <a href="<?= ATBDP_Permalink::get_user_profile_page_link($author_id); ?>" class="<?= atbdp_directorist_button_classes(); ?>"><?php _e('View Profile', ATBDP_TEXTDOMAIN); ?>
                       </a>
                </div>
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
        public function form($instance)
        {
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Author Info', ATBDP_TEXTDOMAIN);
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', ATBDP_TEXTDOMAIN); ?></label>
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