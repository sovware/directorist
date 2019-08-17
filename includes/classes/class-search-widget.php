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
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Advance Search', 'directorist');
            $search_by_text_field          = ! empty( $instance['search_by_text_field'] ) ? 1 : 0;
            $search_by_category            = ! empty( $instance['search_by_category'] ) ? 1 : 0;
            $search_by_location            = ! empty( $instance['search_by_location'] ) ? 1 : 0;
            $search_by_tag                 = ! empty( $instance['search_by_tag'] ) ? 1 : 0;
            $search_by_custom_fields       = ! empty( $instance['search_by_custom_fields'] ) ? 1 : 0;
            $search_by_price               = ! empty( $instance['search_by_price'] ) ? 1 : 0;
            $search_by_price_range         = ! empty( $instance['search_by_price_range'] ) ? 1 : 0;
            $search_by_open_now            = ! empty( $instance['search_by_open_now'] ) ? 1 : 0;
            $search_by_review              = ! empty( $instance['search_by_review'] ) ? 1 : 0;
            $search_by_website             = ! empty( $instance['search_by_website'] ) ? 1 : 0;
            $search_by_email               = ! empty( $instance['search_by_email'] ) ? 1 : 0;
            $search_by_phone               = ! empty( $instance['search_by_phone'] ) ? 1 : 0;
            $search_by_address             = ! empty( $instance['search_by_address'] ) ? 1 : 0;
            $search_by_zip_code            = ! empty( $instance['search_by_zip_code'] ) ? 1 : 0;
            wp_enqueue_script( 'atbdp-search-listing', ATBDP_PUBLIC_ASSETS . 'js/search-form-listing.js');
            if (is_rtl()){
                wp_enqueue_style('atbdp-search-style-rtl', ATBDP_PUBLIC_ASSETS . 'css/search-style-rtl.css');
            }else{
                wp_enqueue_style('atbdp-search-style', ATBDP_PUBLIC_ASSETS . 'css/search-style.css');
            }
            wp_localize_script('atbdp-search-listing','atbdp_search',array(
                'ajaxnonce'         => wp_create_nonce( 'bdas_ajax_nonce' ),
                'ajax_url'           => admin_url( 'admin-ajax.php' ),
            ));
            echo $args['before_widget'];
            echo '<div class="atbd_widget_title">';
            echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];
            echo '</div>';

            require ATBDP_TEMPLATES_DIR . 'search-widget-front.php';

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
        public function form ($instance)
        {
            // Define the array of defaults
            $defaults = array(
                'title'                   =>  __( 'Search', 'directorist' ),
                'search_by_text_field'    => 1,
                'search_by_category'      => 1,
                'search_by_location'      => 1,
                'search_by_tag'           => 0,
                'search_by_custom_fields' => 1,
                'search_by_price'         => 1,
                'search_by_price_range'   => 0,
                'search_by_open_now'      => 0,
                'search_by_review'        => 1,
                'search_by_website'       => 0,
                'search_by_email'         => 0,
                'search_by_phone'         => 0,
                'search_by_address'       => 0,
                'search_by_zip_code'      => 0,
            );
            // Parse incoming $instance into an array and merge it with $defaults
            $instance = wp_parse_args(
                (array) $instance,
                $defaults
            );

            require ATBDP_TEMPLATES_DIR . 'search-widget-form.php';
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
            $instance['search_by_text_field']    = (isset($new_instance['search_by_text_field'])) ? 1 : 0;
            $instance['search_by_category']      = (isset($new_instance['search_by_category'])) ? 1 : 0;
            $instance['search_by_location']      = (isset($new_instance['search_by_location'])) ? 1 : 0;
            $instance['search_by_tag']           = (isset($new_instance['search_by_tag'])) ? 1 : 0;
            $instance['search_by_custom_fields'] = (isset($new_instance['search_by_custom_fields'])) ? 1 : 0;
            $instance['search_by_price']         = (isset($new_instance['search_by_price'])) ? 1 : 0;
            $instance['search_by_price_range']   = (isset($new_instance['search_by_price_range'])) ? 1 : 0;
            $instance['search_by_open_now']      = (isset($new_instance['search_by_open_now'])) ? 1 : 0;
            $instance['search_by_review']        = (isset($new_instance['search_by_review'])) ? 1 : 0;
            $instance['search_by_website']       = (isset($new_instance['search_by_website'])) ? 1 : 0;
            $instance['search_by_email']         = (isset($new_instance['search_by_email'])) ? 1 : 0;
            $instance['search_by_phone']         = (isset($new_instance['search_by_phone'])) ? 1 : 0;
            $instance['search_by_address']       = (isset($new_instance['search_by_address'])) ? 1 : 0;
            $instance['search_by_zip_code']      = (isset($new_instance['search_by_zip_code'])) ? 1 : 0;

            return $instance;
        }
    }

} // end class exist