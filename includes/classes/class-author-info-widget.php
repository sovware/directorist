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
        function __construct ()
        {
            $widget_options = array(
                'classname' => 'listings',
                'description' => esc_html__('You can show submit item  listing link by this widget', ATBDP_TEXTDOMAIN),
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
            if( is_singular(ATBDP_POST_TYPE)) {
                $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Title', ATBDP_TEXTDOMAIN);
                echo $args['before_widget'];

                echo $args['before_title'] . esc_html(apply_filters('widget_submit_item_title', $title)) . $args['after_title'];
                ?>
                <div class="directorist">

                    <div class="atbd_avatar_wrapper">
                        <?php
                        $listing_id = get_the_ID();
                        $author_id = get_post_field ('post_author', $listing_id);
                        $author_name = get_the_author_meta( 'display_name' , $author_id );
                        $user_registered = get_the_author_meta( 'user_registered' , $author_id );

                        $avata_img = get_avatar($author_id , 32 ); ?>                                        <div class="atbd_review_avatar"><?php if ($avata_img){echo $avata_img;}else{?><img
                                src="<?php echo ATBDP_PUBLIC_ASSETS . 'images/revav.png' ?>"
                                alt="Avatar Image"><?php }?></div>
                        <div class="atbd_name_time">
                            <p><?= esc_html($author_name); ?></p>
                            <span class="review_time"><?php
                                printf( __( '%s ago', ATBDP_TEXTDOMAIN ), human_time_diff( strtotime($user_registered), current_time( 'timestamp' ) ) );?></span>
                        </div>
                    </div>

                    <div class="atbd_contact_info">
                        <ul>
                            <?php
                            $address         = esc_attr(get_user_meta($author_id, 'address', true));
                            $phone         = esc_attr(get_user_meta($author_id, 'phone', true));
                            $email           = get_the_author_meta( 'user_email' , $author_id );
                            $website         = get_the_author_meta( 'user_url' , $author_id );;

                            if (!empty($address)) { ?>
                                <li>
                                    <div class="atbd_info_title"><span
                                            class="fa fa-map-marker"></span><?php _e('Address', ATBDP_TEXTDOMAIN); ?>
                                    </div>
                                    <div class="atbd_info"><?= esc_html($address); ?></div>
                                </li>
                            <?php } ?>

                            <?php
                            if (isset($phone) && !is_empty_v($phone)) { ?>
                                <!-- In Future, We will have to use a loop to print more than 1 number-->
                                <li>
                                    <div class="atbd_info_title"><span
                                            class="fa fa-phone"></span><?php _e('Phone', ATBDP_TEXTDOMAIN); ?>
                                    </div>
                                    <div class="atbd_info"><?= esc_html($phone); ?></div>
                                </li>
                            <?php } ?>

                            <?php if (!empty($email)) { ?>
                                <li>
                                    <div class="atbd_info_title"><span
                                            class="fa fa-envelope"></span><?php _e('Email', ATBDP_TEXTDOMAIN); ?>
                                    </div>
                                    <span class="atbd_info"><?= esc_html($email); ?></span>
                                </li>
                            <?php } ?>

                            <?php if (!empty($website)) { ?>
                                <li>
                                    <div class="atbd_info_title"><span
                                            class="fa fa-globe"></span><?php _e('Website', ATBDP_TEXTDOMAIN); ?>
                                    </div>
                                    <a href="<?= esc_url($website); ?>"
                                       class="atbd_info"><?= esc_html($website); ?></a>
                                </li>
                            <?php } ?>

                        </ul>
                    </div>

                    <a href="<?= esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>"
                       class="<?= atbdp_directorist_button_classes(); ?>"><?php _e('View Profile', ATBDP_TEXTDOMAIN); ?></a>
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