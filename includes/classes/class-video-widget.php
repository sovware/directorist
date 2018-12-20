<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

if( !class_exists('BD_VIDEO_WIDGET')) {
    /**
     * Adds BD_VIDEO_WIDGET widget.
     */
    class BD_VIDEO_WIDGET extends WP_Widget {
        /**
         * Register widget with WordPress.
         */
        function __construct()
        {
            $widget_options = array(
                'classname' => 'atbd_widget',
                'description' => esc_html__('You can show video by this widget', ATBDP_TEXTDOMAIN),
            );
            parent::__construct(
                'bdvd_widget', // Base ID
                esc_html__('Directorist - Video', ATBDP_TEXTDOMAIN), // Name
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
            $videourl   = !empty($videourl) ? esc_attr(ATBDP()->atbdp_parse_videos($videourl)) : '';
            if( is_singular(ATBDP_POST_TYPE) && !empty($videourl)) {
                global $post;
                $listing_info = ATBDP()->metabox->get_listing_info( $post->ID);
                $listing      =  !empty($listing_info) ? $listing_info : array();
                extract($listing);
                $videourl   = !empty($videourl) ? esc_attr(ATBDP()->atbdp_parse_videos($videourl)) : '';
                $title      = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Listing Video', ATBDP_TEXTDOMAIN);
                echo $args['before_widget'];
                echo '<div class="atbd_widget_title">';
                echo $args['before_title'] . esc_html(apply_filters('widget_video_title', $title)) . $args['after_title'];
                echo '</div>';
                ?>
                <div class="atbdp">
                    <iframe class="embed-responsive-item" src="<?php echo $videourl; ?>"
                            allowfullscreen></iframe>
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
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Listing Video', ATBDP_TEXTDOMAIN);
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
