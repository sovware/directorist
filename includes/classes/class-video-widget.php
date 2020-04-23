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
                'description' => esc_html__('You can show listing video by this widget', 'directorist'),
            );
            parent::__construct(
                'bdvd_widget', // Base ID
                esc_html__('Directorist - Video', 'directorist'), // Name
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
            $allowWidget = apply_filters('atbdp_allow_video_widget', true);
            if (!$allowWidget) return;
            global $post;
            $listing_info = ATBDP()->metabox->get_listing_info( $post->ID);
            $listing      =  !empty($listing_info) ? $listing_info : array();
            extract($listing);

            if( is_singular(ATBDP_POST_TYPE) && !empty($videourl)) {

                $videourl   = !empty($videourl) ? esc_attr(ATBDP()->atbdp_parse_videos($videourl)) : '';
                $title      = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Listing Video', 'directorist');
                
                $template_file = 'video.php';
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
         * @see WP_Widget::form()
         *
         * @param array $instance Previously saved values from database.
         * @return void
         */
        public function form($instance)
        {
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Listing Video', 'directorist');
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


    }
}
