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
            $search_by_zip_code            = ! empty( $instance['search_by_zip_code'] ) ? 1 : 0;
            $search_by_radius              = ! empty( $instance['search_by_radius'] ) ? 1 : 0;
            $location_source               = ! empty( $instance['location_source'] ) ? $instance['location_source'] : 'map_api';
            $select_listing_map            = get_directorist_option('select_listing_map','google');

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

            $tag_label               = get_directorist_option('tag_label',__('Tag','directorist'));
            $address_label           = get_directorist_option('address_label',__('Address','directorist'));
            $fax_label               = get_directorist_option('fax_label',__('Fax','directorist'));
            $email_label             = get_directorist_option('email_label',__('Email','directorist'));
            $website_label           = get_directorist_option('website_label',__('Website','directorist'));
            $zip_label               = get_directorist_option('zip_label',__('Zip','directorist'));
            $price_range_placeholder = get_directorist_option('price_range_placeholder', __('Price Range', 'directorist'));
            $currency                = get_directorist_option('g_currency', 'USD');
            $c_symbol                = atbdp_currency_symbol($currency);

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
                'search_by_zip_code'      => 0,
                'search_by_radius'        => 0,
                'location_source'         => 'listing_location',
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
            $instance['search_by_zip_code']      = (isset($new_instance['search_by_zip_code'])) ? 1 : 0;
            $instance['search_by_zip_code']      = (isset($new_instance['search_by_zip_code'])) ? 1 : 0;
            $instance['search_by_radius']      = (isset($new_instance['search_by_radius'])) ? 1 : 0;
            $instance['location_source']      = (isset($new_instance['location_source'])) ? $new_instance['location_source'] : 'listing_location';

            return $instance;
        }
    }

} // end class exist