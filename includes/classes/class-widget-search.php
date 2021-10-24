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
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Advance Search', 'directorist');

            wp_enqueue_script( 'directorist-search-form-listing' );
            wp_enqueue_script( 'directorist-range-slider' );
            wp_enqueue_script( 'directorist-search-listing' );

            $listing_type = get_post_meta( get_the_ID(), '_directory_type', true );
            $listing_type = ( ! empty( $listing_type ) ) ? $listing_type : default_directory_type();

            $data = Directorist\Script_Helper::get_search_script_data( [ 'directory_type_id' => $listing_type ] );
            wp_localize_script( 'directorist-search-form-listing', 'atbdp_search_listing', $data );
            wp_localize_script( 'directorist-search-listing', 'atbdp_search', [
                'ajaxnonce' => wp_create_nonce('bdas_ajax_nonce'),
                'ajax_url' => admin_url('admin-ajax.php'),
            ]);
            wp_localize_script( 'directorist-search-listing', 'atbdp_search_listing', $data );
            wp_localize_script( 'directorist-range-slider', 'atbdp_range_slider', $data );

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