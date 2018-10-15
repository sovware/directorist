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
                'classname' => 'listings',
                'description' => esc_html__('You can show popular listing by this widget', ATBDP_TEXTDOMAIN),
            );
            parent::__construct(
                'bdvd_widget', // Base ID
                esc_html__('Directorist Video', ATBDP_TEXTDOMAIN), // Name
                $widget_options // Args
            );
        }
    }
}
