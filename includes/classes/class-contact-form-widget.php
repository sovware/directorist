<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

    if ( !class_exists('BD_contact_form_Widget')) {
        /**
         * Adds BD_contact_form_Widget widget.
         */
        class BD_contact_form_Widget extends WP_Widget {
            /**
             * Register widget with WordPress.
             */
            function __construct()
            {
                $widget_options = array(
                    'classname' => 'atbd_widget',
                    'description' => esc_html__('You can show a form to contact the listing owners by this widget', 'directorist'),
                );

                parent::__construct(
                    'bdco_widget', // Base ID
                    esc_html__('Directorist - Custom Contact Form', 'directorist'), // Name
                    $widget_options, // Args
                    'fdgfdg'
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
            public function widget($args, $instance) {
                if( is_singular(ATBDP_POST_TYPE)) {
                    $plan_permission = true;
                    global $post;
                    $email = get_post_meta($post->ID, '_email', true);
                    if (is_fee_manager_active()){
                        $plan_permission = is_plan_allowed_owner_contact_widget(get_post_meta($post->ID, '_fm_plans', true));
                    }
                    if ($plan_permission){

                        $title      = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Contact Listing Owner', 'directorist');
                        echo $args['before_widget'];
                        echo '<div class="atbd_widget_title">';
                        echo $args['before_title'] . esc_html(apply_filters('widget_contact_form_title', $title)) . $args['after_title'];
                        echo '</div>';
                        $form_id = apply_filters('atbdp_contact_listing_owner_widget_form_id', 'atbdp-contact-form-widget');
                        ?>
                        <div class="atbdp directorist atbdp-widget-listing-contact">
                            <form id="<?php echo $form_id; ?>" class="form-vertical" role="form">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="atbdp-contact-name" placeholder="<?php _e( 'Name', 'directorist' ); ?>" required />
                                </div>

                                <div class="form-group">
                                    <input type="email" class="form-control" id="atbdp-contact-email" placeholder="<?php _e( 'Email', 'directorist' ); ?>" required />
                                </div>

                                <?php
                                $msg_html = '<div class="form-group">';
                                $msg_html .= '<textarea class="form-control" id="atbdp-contact-message" rows="3" placeholder="'.__('Message', 'directorist').'..." required ></textarea>';
                                $msg_html .= '</div>';
                                /**
                                 * @since 5.10.0
                                 */
                                echo apply_filters('atbdp_widget_contact_form_message_field', $msg_html);
                                ?>
                                <?php
                                /**
                                 * It fires before contact form in the widget area
                                 * @since 4.4.0
                                 */

                                do_action('atbdp_before_contact_form_submit_button');
                                ?>
                                <p id="atbdp-contact-message-display"></p>

                                <button type="submit" class="btn btn-primary"><?php _e( 'Submit', 'directorist' ); ?></button>
                            </form>
                        </div>
                        <input type="hidden" id="atbdp-post-id" value="<?php echo $post->ID; ?>" />
                        <input type="hidden" id="atbdp-listing-email" value="<?php echo !empty($email) ? sanitize_email($email) : ''; ?>" />
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
                $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Contact the listing owner', 'directorist');
                ?>
                <p>
                    <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'directorist'); ?></label>
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

        } //end class
    } // end if condition

