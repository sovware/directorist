<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

if ( !class_exists('BD_Search_Widget')) {
    /**
     * Adds BD_Search_Widget widget.
     */
    class Bd_search_Widget extends WP_Widget {

        /*
         * register search widget
         */
        public function __construct ()
        {
            $widget_options = array(
                'classname' => 'atbd_widget',
                'description' => esc_html__('You can show search listing form by this widget', 'directorist'),
            );
            parent::__construct(
                'bdsw_widget', // Base ID
                esc_html__('Directorist - Search Listings', 'directorist'), // Name
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
        public function widget ($args, $instance)
        {
            $allowWidget = apply_filters('atbdp_allow_search_widget', true);
            if (!$allowWidget) return;
            // $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Advance Search', 'directorist');

			// Enqueue Scripts
			Directorist\Asset_Loader::instance()->enqueue_search_listing_form_widget_scripts();

            $template_path = atbdp_get_widget_template_path( 'search' );
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
        public function form ($instance)
        {
            // Define the array of defaults
            $defaults = array(
                'title'                   =>  __( 'Search', 'directorist' ),
            );
            // Parse incoming $instance into an array and merge it with $defaults
            $instance = wp_parse_args(
                (array) $instance,
                $defaults
            );

            require ATBDP_VIEWS_DIR . 'search-widget-form.php';
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
            $instance['title']                   = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

            return $instance;
        }
    }

} // end class exist