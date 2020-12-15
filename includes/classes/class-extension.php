<?php
/**
 * ATBDP Extensions class
 *
 * This class is for interacting with Extensions eg. showing extensions lists
 *
 * @package     ATBDP
 * @subpackage  inlcudes/classes Extensions
 * @copyright   Copyright (c) 2018, AazzTech
 * @since       1.0
 */


// Exit if accessed directly
if ( ! defined('ABSPATH') ) { die( 'Direct access is not allowed.' ); }

if ( ! class_exists('ATBDP_Extensions') ) {

    /**
     * Class ATBDP_Extensions
     */
    class ATBDP_Extensions
    {
        public $extensions = [];
        public $themes     = [];

        public function __construct()
        {
            add_action( 'admin_menu', array($this, 'admin_menu'), 100 );
            add_action( 'init', array( $this, 'get_the_product_list') );
            add_filter( 'atbdp_extension_list', array( $this, 'exclude_purchased_extensions'), 20, 1 );
            add_filter( 'atbdp_theme_list', array( $this, 'exclude_purchased_themes'), 20, 1 );
            
            // Ajax
            add_action( 'wp_ajax_atbdp_authenticate_the_customer', array($this, 'authenticate_the_customer') );
            add_action( 'wp_ajax_atbdp_download_file', array($this, 'handle_file_download_request') );
            add_action( 'wp_ajax_atbdp_install_file_from_subscriptions', array($this, 'handle_file_install_request_from_subscriptions') );
            add_action( 'wp_ajax_atbdp_plugins_bulk_action', array($this, 'plugins_bulk_action') );
            add_action( 'wp_ajax_atbdp_update_plugins', array($this, 'update_plugins') );
            add_action( 'wp_ajax_atbdp_activate_theme', array($this, 'activate_theme') );
            add_action( 'wp_ajax_atbdp_update_theme', array($this, 'handle_theme_update_request') );
            add_action( 'wp_ajax_atbdp_refresh_purchase', array($this, 'handle_refresh_purchase_request') );
            add_action( 'wp_ajax_atbdp_close_subscriptions_sassion', array($this, 'handle_close_subscriptions_sassion_request') );

            // add_action( 'wp_ajax_atbdp_download_purchased_items', array($this, 'download_purchased_items') );
        }

        // get_the_products_list
        public function get_the_product_list() {
            $this->extensions = apply_filters( 'atbdp_extension_list', [
                'directorist-coupon' => [
                    'name'        => 'Coupon',
                    'description' => __( 'It lets you offer discounts to users when purchasing listing plans or paying for featured listings.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-coupon/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/11/19_Coupon.png',
                    'active'      => true,
                ],
                'compare-listings' => [
                    'name'        => 'Compare Listings',
                    'description' => __( 'Compare Listings extension allows users to add a set of listings in a list and compare its features by viewing in a comparison table.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/compare-listings/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/2020/07/Compare-Listings.png',
                    'active'      => true,
                ],
                'directorist-rank-featured-listings' => [
                    'name'        => 'Rank Featured Listings',
                    'description' => __( 'Rank all your featured listings if it happens on a larger scale on your directory website and earn extra revenue from your users.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-rank-featured-listings/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/Rank-Featured-List.png',
                    'active'      => true,
                ],
                'directorist-post-your-need' => [
                    'name'        => 'Post Your Need',
                    'description' => __( 'Post your expected services according to your need and get the respective service provider with no time.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-post-your-need/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/05_Post-Your-Need-1.png',
                    'active'      => true,
                ],
                'directorist-listings-with-map' => [
                    'name'        => 'Listings With Map',
                    'description' => __( 'Show your listings with the interactive maps and make your business visible comprehensively. This awesome extension will make your website the brand recognition it deserves.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-listings-with-map/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/06_Listings-With-Map-1.png',
                    'active'      => true,
                ],
                'directorist-pricing-plans' => [
                    'name'        => 'Pricing Plans',
                    'description' => __( 'Do you have a growing directory site? Do you want to make money with your site very easily? Start generating a handsome amount of revenue from your directory site with Directorist Pricing Plans today.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-pricing-plans/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/15_Pricing-Plans-1.png',
                    'active'      => true,
                ],
                'directorist-woocommerce-pricing-plans' => [
                    'name'        => 'WooCommerce Pricing Plans',
                    'description' => __( 'Do you have a growing directory site? Do you want to make money with your site by integrating your favorite WooCommerce payment gateway? Start generating a handsome amount of revenue from your directory site with Directorist WooCommerce Pricing Plans today.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-woocommerce-pricing-plans/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/16_WooCommerce-Pricing-Plans-1.png',
                    'active'      => true,
                ],
                'directorist-paypal' => [
                    'name'        => 'PayPal Payment Gateway',
                    'description' => __( 'Do you want to boost your income on your business directory site? Are you looking for a robust payment gateway with worldwide acceptance? If you are, then Directorist PayPal Payment Gateway is the perfect fit for you.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-paypal/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/14_PayPal-Payment-Gateway-2.png',
                    'active'      => true,
                ],
                'directorist-stripe' => [
                    'name'        => 'Stripe Payment Gateway',
                    'description' => __( 'Are you looking for a versatile Directorist payment gateway for your business directory that accepts a great number of currencies? If yes, then Directorist Stripe Payment Gateway is the smartest way to go', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-stripe/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/13_Stripe-Payment-Gateway-3.png',
                    'active'      => true,
                ],
                'directorist-claim-listing' => [
                    'name'        => 'Claim Listing',
                    'description' => __( 'Let business owners maintain tons of listings by claiming them and monetize your directory listing website with instant revenue.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-claim-listing/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/12_Claim-Listing-2.png',
                    'active'      => true,
                ],
                'directorist-mark-as-sold' => [
                    'name'        => 'Mark as Sold',
                    'description' => __( 'Mark as sold is a dynamic extension that provides listing authors the opportunity to show visitors if a particular item is sold or not.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-mark-as-sold/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/03_Mark-As-Sold-1.png',
                    'active'      => true,
                ],
                'directorist-social-login' => [
                    'name'        => 'Social Login',
                    'description' => __( 'Use Directorist Social Login to accelerate the registration process by offering a single-click login option using Facebook or Google profile.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-social-login/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/04_Social-Login-1.png',
                    'active'      => true,
                ],
                'google-recaptcha' => [
                    'name'        => 'Google reCAPTCHA',
                    'description' => __( 'Use reCAPTCHA service from Google to help your directory site protect from spam and further abuse. This Google reCAPTCHA extension allows you to make it happen by taking care of your site.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-social-login/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/10_Google-ReCAPTCHA-2.png',
                    'active'      => true,
                ],
                'directorist-listing-faqs' => [
                    'name'        => 'Listing FAQs',
                    'description' => __( 'Use an organized FAQ page on your directory website and provide quick information to help customers make a potential decision. Here, the idea is to keep the answers short and direct so that people find info quickly.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-listing-faqs/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/08_Listing-FAQs-1.png',
                    'active'      => true,
                ],
                'directorist-business-hours' => [
                    'name'        => 'Business Hours',
                    'description' => __( 'Inform your customers about your business hours in the best way possible especially when your businesses are opened and when they are closed', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-business-hours/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/11_Business-Hours.png',
                    'active'      => true,
                ],
                'directorist-listings-slider-carousel' => [
                    'name'        => 'Listings Slider & Carousel',
                    'description' => __( 'Increase the beauty of your directory website by displaying numerous listings through attractive sliders or carousels with this highly customizable extension.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-listings-slider-carousel/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/09_Listings-Slider-Carousel-1.png',
                    'active'      => true,
                ],
                'directorist-live-chat' => [
                    'name'        => 'Live Chat',
                    'description' => __( 'Live Chat is an extension that allows the visitors to contact business owners immediately and easily. It makes the business more credible as customer satisfaction increases notably.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-live-chat/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/02_Live-Chats-1.png',
                    'active'      => true,
                ],
                'directorist-booking' => [
                    'name'        => 'Booking (Reservation & Appointment)',
                    'description' => __( 'This extension comes with all the solutions you need to set up a dynamic booking and reservation system on your directory website.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-booking/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/01_Booking-1.png',
                    'active'      => true,
                ],
                'directorist-image-gallery' => [
                    'name'        => 'Image Gallery',
                    'description' => __( 'Use a quality image gallery and increase conversation by reducing your return rate on your directory listing website.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-image-gallery/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/07_Image-Gallery-1.png',
                    'active'      => true,
                ],
            ]);


            $this->themes = apply_filters( 'atbdp_theme_list', [
                'dlist' => [
                    'name'        => 'DList',
                    'description' => __( 'DList is a listing directory WordPress theme that provides immense opportunities to build any kind of directory or listing site. You may design pages on the front-end and watch them instantly come to life.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/dlist/',
                    'demo_link'   => 'https://demo.directorist.com/theme/dlist/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/dlist-featured.png',
                    'active'      => true,
                ],
                'dservice' => [
                    'name'        => 'DService',
                    'description' => __( 'DService is a kind of listing Directory WordPress theme that brings business owners and customers on the same platform. This multifunctional WordPress theme provides them the opportunity to interact with one another for business purposes.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/dservice/',
                    'demo_link'   => 'https://demo.directorist.com/theme/dservice/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/dservice-featured.png',
                    'active'      => true,
                ],
                'directoria' => [
                    'name'        => 'Directoria',
                    'description' => __( 'Directoria is an astonishing directory and listing WordPress theme that is designed and developed to provide fastest page loading speed without knowing a single line of code.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directoria/',
                    'demo_link'   => 'https://demo.directorist.com/plugin/demo-one/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/Directoria-1.png',
                    'active'      => true,
                ],
            ]);
        }

        // exclude_purchased_extensions
        public function exclude_purchased_extensions( $extensions ) {
            $has_subscriptions_sassion = get_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion', true );
            $is_logged_in              = ( ! empty( $has_subscriptions_sassion ) ) ? true : false;
            
            if ( ! $is_logged_in ) { return $extensions; }

            $purchased_products = get_user_meta( get_current_user_id(), '_atbdp_purchased_products', true );
            if ( empty( $purchased_products ) ) { return $extensions; }

            $purchased_extensions = ( ! empty( $purchased_products['plugins'] ) ) ? $purchased_products['plugins'] : '';
            if ( empty( $purchased_extensions ) ) { return $extensions; }

            $purchased_extensions_keys = array_keys( $purchased_extensions );
            $excluded_extensions = $extensions;

            foreach ( $excluded_extensions as $extension_key => $extension ) {
                if ( ! in_array( $extension_key, $purchased_extensions_keys ) ) { continue; }
                $excluded_extensions[ $extension_key ]['active'] = false;
            }
            
            return $excluded_extensions;
        }

        // exclude_purchased_themes
        public function exclude_purchased_themes( $themes ) {
            $has_subscriptions_sassion = get_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion', true );
            $is_logged_in              = ( ! empty( $has_subscriptions_sassion ) ) ? true : false;

            if ( ! $is_logged_in ) { return $themes; }

            $purchased_products = get_user_meta( get_current_user_id(), '_atbdp_purchased_products', true );
            if ( empty( $purchased_products ) ) { return $themes; }

            $purchased_themes = ( ! empty( $purchased_products['themes'] ) ) ? $purchased_products['themes'] : '';
            if ( empty( $purchased_themes ) ) { return $themes; }

            $purchased_themes_keys = array_keys( $purchased_themes );
            $excluded_themes = $themes;

            foreach ( $excluded_themes as $theme_key => $theme ) {
                if ( ! in_array( $theme_key, $purchased_themes_keys ) ) { continue; }
                $excluded_themes[ $theme_key ]['active'] = false;
            }

            return $excluded_themes;
        }

        // get_active_extensions
        public function get_active_extensions() {
            $active_extensions = [];

            foreach( $this->extensions as $extension_key => $extension_args ) {
                if ( empty( $extension_args['active'] ) ) { continue; }

                $active_extensions[ $extension_key ] = $extension_args;
            }

            return $active_extensions;
        }

        // get_active_themes
        public function get_active_themes() {
            $active_themes = [];

            foreach( $this->themes as $theme_key => $theme_args ) {
                if ( empty( $theme_args['active'] ) ) { continue; }

                $active_themes[ $theme_key ] = $theme_args;
            }

            return $active_themes;
        }

        // update_plugins
        public function update_plugins() {
            $status = [ 'success' => true ];
            $plugin_item = ( isset( $_POST['plugin_item'] ) ) ? $_POST['plugin_item'] : '';

            $plugin_updates       = get_site_transient( 'update_plugins' );
            $outdated_plugins     = $plugin_updates->response;
            $outdated_plugins_key = array_keys( $outdated_plugins );

            if ( empty( $outdated_plugins_key ) ) { 
                $status['massage'] = __( 'All plugins are up to date', 'directorist' );
                wp_send_json( [ 'status' => $status ] );
            }

            if ( ! empty( $plugin_item ) && ! in_array( $plugin_item, $outdated_plugins_key )  ) {
                $status['massage'] = __( 'The plugin is up to date', 'directorist' );
                wp_send_json( [ 'status' => $status ] );
            }

            if ( ! empty( $plugin_item ) ) {
                $outdated_plugin = $outdated_plugins[ $plugin_item ];
                $this->download_plugin( [ 'url' => $outdated_plugin->package ] );

                $status['massage'] = __( 'The plugin has been updated successfully', 'directorist' );
                wp_send_json( [ 'status' => $status ] );
            }

            // Update all
            foreach ( $outdated_plugins as $plugin_base => $plugin ) {
                $this->download_plugin( [ 'url' => $plugin->package ] );
            }

            $status['massage'] = __( 'All the plugins are updated successfully', 'directorist' );
            wp_send_json( [ 'status' => $status ] );
        }

        // plugins_bulk_action
        public function plugins_bulk_action() {
            $status = [ 'success' => true ];

            $task = ( isset( $_POST['task'] ) ) ? $_POST['task'] : '';
            $plugin_items = ( isset( $_POST['plugin_items'] ) ) ? $_POST['plugin_items'] : '';

            // Validation
            if ( empty( $task ) ) {
                $status['success'] = false;
                $status['message'] = 'No task found';
                wp_send_json([ 'status' => $status ]);
            }

            if ( empty( $plugin_items ) ) {
                $status['success'] = false;
                $status['message'] = 'No plugin items found';
                wp_send_json([ 'status' => $status ]);
            }

            // Activate
            if ( 'activate' === $task ) {
                foreach ( $plugin_items as $plugin ) {
                    activate_plugin( $plugin );
                }
            }

            // Deactivate
            if ( 'deactivate' === $task ) {
                deactivate_plugins( $plugin_items );
            }

            // Uninstall
            if ( 'uninstall' === $task ) {
                delete_plugins( $plugin_items );
            }

            wp_send_json([ 'status' => $status,  ]);
        }

        // activate_theme
        public function activate_theme() {
            $status = [ 'success' => true ];
            $theme_stylesheet = ( isset( $_POST['theme_stylesheet'] ) ) ? $_POST['theme_stylesheet'] : '';

            if ( empty( $theme_stylesheet ) ) {
                $status['success'] = false;
                $status['message'] = __('Theme\'s stylesheet is missing', 'directorist');

                wp_send_json( [ 'status' => $status] );
            }

            switch_theme( $theme_stylesheet );
            wp_send_json( [ 'status' => $status] );
        }

        // handle_theme_update_request
        public function handle_theme_update_request() {
            $status = [ 'success' => true ];
            $theme_stylesheet = ( isset( $_POST['theme_stylesheet'] ) ) ? $_POST['theme_stylesheet'] : '';

            if ( empty( $theme_stylesheet ) ) {
                $status['success'] = false;
                $status['message'] = __('Theme\'s stylesheet is missing', 'directorist');

                wp_send_json( [ 'status' => $status ] );
            }

            $update_theme_status = $this->update_the_theme( $theme_stylesheet );
            wp_send_json( $update_theme_status );
        }

        // update_the_theme
        public function update_the_theme( $theme_stylesheet = '' ) {
            $status = [ 'success' => true ];

            // Check if stylesheet is present
            if ( empty( $theme_stylesheet ) ) {
                $status['success'] = false;
                $status['message'] = __('Theme\'s stylesheet is missing', 'directorist');

                return [ 'status' => $status ];
            }

            $theme_updates       = get_site_transient( 'update_themes' );
            $outdated_themes     = $theme_updates->response;
            $outdated_themes_key = array_keys( $outdated_themes );

            // Check if the the update is available
            if ( ! in_array( $theme_stylesheet, $outdated_themes_key ) ) {
                $status['success'] = false;
                $status['message'] = __('The theme is already upto date', 'directorist');

                return [ 'status' => $status ];
            }

            $outdated_theme = $outdated_themes['theme_stylesheet'];
        }

        // authenticate_the_customer
        public function authenticate_the_customer() {
            $status = [ 'success' => true, 'log' => [] ];


            $plugins_available_in_subscriptions = get_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions', true );
            $themes_available_in_subscriptions  = get_user_meta( get_current_user_id(), '_themes_available_in_subscriptions', true );
            $has_previous_subscriptions         = ( ! empty( $plugins_available_in_subscriptions ) || ! empty( $themes_available_in_subscriptions ) ) ? true : false;

            if ( $has_previous_subscriptions ) {
                // Enable Sassion
                update_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion', true );
                wp_send_json( [ 'status' => $status, 'has_previous_subscriptions' => true ] );
            }
            
            // Get form data
            $username = ( isset( $_POST['username'] ) ) ? $_POST['username'] : '';
            $password = ( isset( $_POST['password'] ) ) ? $_POST['password'] : '';

            // Validate username
            if ( empty( $username ) ) {
                $status['success'] = false;
                $status[ 'log' ]['username_missing'] = [
                    'type'    => 'error',
                    'message' => 'Username is required',
                ];
            }

            // Validate password
            if ( empty( $password ) ) {
                $status['success'] = false;
                $status[ 'log' ]['password_missing'] = [
                    'type'    => 'error',
                    'message' => 'Password is required',
                ];
            }

            if ( ! $status['success'] ) {
                wp_send_json( [ 'status' => $status ] );
            }

            // Get licencing data
            $url_base = 'https://directorist.com/wp-json/directorist/v1/licencing';
            $args     = '?user=' . $username;
            $args    .= '&password=' . $password;
            $url      = $url_base . $args;

            $response = wp_remote_get( $url);
            $response_body = ( 'string' === gettype( $response['body'] ) ) ? json_decode( $response['body'], true ) : $response['body'];

            // Validate response
            if ( ! $response_body['success'] ) {
                $status['success'] = false;
                $status['massage'] = $response_body['massage'];
                $status[ 'log' ]['unknown_error'] = [
                    'type'    => 'error',
                    'message' => $response_body['massage'],
                ];

                wp_send_json([ 'status' => $status, 'response_body' => $response_body ]);
            }

            // Enable Sassion
            update_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion', true );

            $license_data = $response_body['license_data'];

            // Update All Access License For Extensions
            if ( ! empty( $response_body['all_access'] ) && ! empty( $response_body['active_licenses'] ) && ! empty( $license_data['plugins'] ) ) {
                foreach ( $license_data['plugins'] as $plugin_index => $plugin ) {
                    $license_data[ 'plugins' ][ $plugin_index ]['license'] = $response_body['active_licenses'][0];
                }
            }

            // Update All Access License For Themes
            if ( ! empty( $response_body['all_access'] ) && ! empty( $response_body['active_licenses'] ) && ! empty( $license_data['themes'] ) ) {
                foreach ( $license_data['plugins'] as $theme_index => $theme ) {
                    $license_data[ 'themes' ][ $theme_index ]['license'] = $response_body['active_licenses'][0];
                }
            }

            // Update user meta
            if ( ! empty( $license_data[ 'themes' ] ) ) {
                $themes_available_in_subscriptions = $this->prepare_available_in_subscriptions( $license_data[ 'themes' ] );
                update_user_meta( get_current_user_id(), '_themes_available_in_subscriptions', $themes_available_in_subscriptions );
            }

            if ( ! empty( $license_data[ 'plugins' ] ) ) {
                $plugins_available_in_subscriptions = $this->prepare_available_in_subscriptions( $license_data[ 'plugins' ] );
                update_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions', $plugins_available_in_subscriptions );
            }
            
            $status[ 'success' ] = true;
            $status[ 'log' ]['login_successful'] = [
                'type'    => 'success',
                'message' => 'Login is successful',
            ];

            wp_send_json([ 'status' => $status, 'license_data' => $license_data ]);
        }

        // handle_refresh_purchase_request
        public function handle_refresh_purchase_request() {
            $status = [ 'success' => true ];


            wp_send_json( $status );
        }

        // handle_close_subscriptions_sassion_request
        public function handle_close_subscriptions_sassion_request() {
            $status = [ 'success' => true ];

            delete_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion' );

            wp_send_json( $status );
        }

        // prepare_available_in_subscriptions
        public function prepare_available_in_subscriptions( array $products = [] ) {
            $available_in_subscriptions = [];

            if ( empty( $products ) ) { return $available_in_subscriptions; }

            foreach ( $products as $product ) {
                $product_key = $this->get_product_key_from_permalink( $product[ 'permalink' ] );
                $available_in_subscriptions[ $product_key ] = $product;
            }

            return $available_in_subscriptions;
        }

        // get_product_key_from_permalink
        public function get_product_key_from_permalink( string $permalink = '' ) {
            $product_key = str_replace( 'http://directorist.com/product/', '', $permalink );
            $product_key = str_replace( 'https://directorist.com/product/', '', $product_key );
            $product_key = str_replace( '/', '', $product_key );

            return $product_key;
        }   

        // handle_license_activation_request
        public function handle_license_activation_request() {
            $status = [ 'success' => true ];
            $license_item = ( isset( $_POST['license_item'] ) ) ? $_POST['license_item'] : '';
            $product_type = ( isset( $_POST['product_type'] ) ) ? $_POST['product_type'] : '';

            if ( empty( $license_item ) ) {
                $status[ 'success' ] = false;
                $status[ 'message' ] = 'License item is missing';

                wp_send_json( [ 'status' => $status ] );
            }

            if ( empty( $product_type ) ) {
                $status[ 'success' ] = false;
                $status[ 'message' ] = 'Product type is required';

                wp_send_json( [ 'status' => $status ] );
            }

            $activation_status = $this->activate_license( $license_item, $product_type );
            $status[ 'success' ] = $activation_status['success'];

            wp_send_json( [ 'status' => $status, 'activation_status' => $activation_status ] );
        }

        // activate_license
        public function activate_license( $license_item , $product_type = '' ) {
            $status = [ 'success' => true ];

            // $activation_base_url = 'https://directorist.com?edd_action=activate_license&url=' . home_url();
            $activation_base_url = 'https://directorist.com?edd_action=activate_license';

            $item_id        = $license_item['item_id'];
            $license        = $license_item['license'];
            $files          = $license_item['links'];
            $query_args     = "&item_id={$item_id}&license={$license}";
            $activation_url = $activation_base_url . $query_args;

            $response        = wp_remote_get( $activation_url );
            $response_status = json_decode( $response['body'], true );

            if ( empty( $response_status['success'] ) ) {
                $status[ 'success' ] = false;
            }

            $status[ 'response' ] = $response_status;

            $product_type = ( 'plugin' === $product_type ) ? 'plugins' : $product_type;
            $product_type = ( 'theme' === $product_type ) ? 'themes' : $product_type;

            if ( $status[ 'success' ] && ( 'plugins' === $product_type || 'themes' === $product_type )  ) {
                $user_purchased = get_user_meta( get_current_user_id(), '_atbdp_purchased_products', true );

                if ( empty( $user_purchased ) ) {
                    $user_purchased = [];
                }

                if ( empty( $user_purchased[ $product_type ] ) ) {
                    $user_purchased[ $product_type ] = [];
                }

                $purchased_items = $user_purchased[ $product_type ];

                // Append new product
                $product_key = $this->get_product_key_from_permalink( $license_item[ 'permalink' ] );
                $purchased_items[ $product_key ] = [
                    'item_id' => $item_id,
                    'license' => $license,
                    'files'   => $files,
                ];

                $user_purchased[ $product_type ] = $purchased_items;
                update_user_meta( get_current_user_id(), '_atbdp_purchased_products', $user_purchased );

                $status['purchased_products'] = $user_purchased;
            }

            return $status;
        }

        // handle_file_install_request_from_subscriptions
        public function handle_file_install_request_from_subscriptions() {
            $item_key = ( isset( $_POST['item_key'] ) ) ? $_POST['item_key'] : '';
            $type     = ( isset( $_POST['type'] ) ) ? $_POST['type'] : '';
            
            $installation_status = $this->install_file_from_subscriptions( [ 'item_key' => $item_key, 'type' => $type ] );
            wp_send_json( $installation_status );
        }

        // install_file_from_subscriptions
        public function install_file_from_subscriptions( array $args = [] ) {
            $default = [ 'item_key' => '', 'type' => '' ];
            $args = array_merge( $default, $args );

            $item_key = $args[ 'item_key' ];
            $type     = $args[ 'type' ];

            $status   = [ 'success' => true ];

            if ( empty( $item_key ) ) {
                $status[ 'success'] = false;
                $status[ 'message'] = __( 'Item key is missing', 'directorist' );

                return [ 'status' => $status ];
            }

            if ( empty( $type ) ) {
                $status[ 'success'] = false;
                $status[ 'message'] = __( 'Type not specified', 'directorist' );

                return [ 'status' => $status ];
            }

            if ( 'plugin' !== $type && 'theme' !== $type ) {
                $status[ 'success'] = false;
                $status[ 'message'] = __( 'Invalid type', 'directorist' );

                return [ 'status' => $status ];
            }

            if ( 'theme' === $type ) {
                $available_in_subscriptions = get_user_meta( get_current_user_id(), '_themes_available_in_subscriptions', true );
            }

            if ( 'plugin' === $type ) {
                $available_in_subscriptions = get_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions', true );
            }
            

            if ( empty( $available_in_subscriptions ) ) {
                $status[ 'success'] = false;
                $status[ 'message'] = __( 'Nothing available in subscriptions', 'directorist' );

                return [ 'status' => $status ];
            }

            if ( empty( $available_in_subscriptions[ $item_key ] ) ) {
                $status[ 'success'] = false;
                $status[ 'message'] = __( 'The item is not available in your subscriptions', 'directorist' );

                return [ 'status' => $status ];
            }

            $installing_file = $available_in_subscriptions[ $item_key ];
            $activatation_status = $this->activate_license( $installing_file, $type ); // success

            if ( ! $activatation_status[ 'success' ] ) {
                $status[ 'success'] = false;
                $status[ 'message'] = __( 'The license is not valid', 'directorist' );

                return [ 'status' => $status ];
            }

            foreach( $installing_file['links'] as $link ) {
                if ( 'plugin' === $type ) {
                    $this->download_plugin( [ 'url' => $link ] );
                }

                if ( 'theme' === $type ) {
                    $this->download_theme( [ 'url' => $link ] );
                }
            }
            
            $status[ 'success'] = true;
            $status[ 'message'] = __( 'Installed Successfully', 'directorist' );

            return [ 'status' => $status ];
        }

        // handle_plugin_download_request
        public function handle_file_download_request() {
            $status         = [ 'success' => true ];
            $download_item  = ( isset( $_POST['download_item'] ) ) ? $_POST['download_item'] : '';
            $type           = ( isset( $_POST['type'] ) ) ? $_POST['type'] : '';

            if ( empty( $download_item ) ) {
                $status[ 'success'] = false;
                $status[ 'message'] = 'Download item is missing';

                wp_send_json( [ 'status' => $status ] );
            }

            if ( empty( $type ) ) {
                $status[ 'success'] = false;
                $status[ 'message'] = 'Type not specified';

                wp_send_json( [ 'status' => $status ] );
            }

            if ( 'plugin' !== $type && 'theme' !== $type ) {
                $status[ 'success'] = false;
                $status[ 'message'] = 'Invalid type';

                wp_send_json( [ 'status' => $status ] );
            }

            $activate_license = $this->activate_license( $download_item, $type );
            if ( ! $activate_license['success'] ) {
                $status[ 'success'] = false;
                $status[ 'message'] = __( 'Activation failed', 'directorist' );
                $status[ 'ref']     = $activate_license;

                wp_send_json( [ 'status' => $status ] );
            }

            if ( empty( $download_item['links'] ) ) {
                $status[ 'success'] = false;
                $status[ 'message'] = 'Links not found';

                wp_send_json( [ 'status' => $status ] );
            }

            if ( empty( $download_item['links'] ) ) {
                $status[ 'success'] = false;
                $status[ 'message'] = 'Links not found';

                wp_send_json( [ 'status' => $status ] );
            }
            
            if ( ! is_array( $download_item['links'] ) ) {
                $status[ 'success'] = false;
                $status[ 'message'] = 'Links not found';

                wp_send_json( [ 'status' => $status ] );
            }

            foreach( $download_item['links'] as $link ) {
                if ( 'plugin' === $type ) {
                    $this->download_plugin( [ 'url' => $link ] );
                }

                if ( 'theme' === $type ) {
                    $this->download_theme( [ 'url' => $link ] );
                }
            }
            
            $status[ 'message'] = __( 'Donloaded', 'directorist' );
            wp_send_json( [ 'status' => $status ] );
        }

        // download_plugin
        public function download_plugin( array $args = [] ) {
            $default = [ 'url' => '', 'init_wp_filesystem' => true ];
            $args = array_merge( $default, $args );

            if ( empty( $default ) ) { return; }

            global $wp_filesystem;

            if ( $args[ 'init_wp_filesystem' ] ) {
                if ( ! function_exists( 'WP_Filesystem' ) ) {
                    include  ABSPATH . 'wp-admin/includes/file.php';
                }
                WP_Filesystem();
            }
            

            $plugin_path   = ABSPATH . 'wp-content/plugins';
            $temp_dest     = "{$plugin_path}/atbdp-temp-dir";
            $file_url      = $args['url'];
            $file_name     = basename( $file_url );
            $tmp_file      = download_url( $file_url );

            // Make Temp Dir
            if ( $wp_filesystem->exists( $temp_dest ) ) {
                $wp_filesystem->delete( $temp_dest, true );
            }
            $wp_filesystem->mkdir( $temp_dest );

            // Sets file temp destination.
            $file_path = "{$temp_dest}/{$file_name}";

            // Copies the file to the final destination and deletes temporary file.
            copy( $tmp_file, $file_path );
            @unlink( $tmp_file );

            unzip_file( $file_path, $temp_dest );
            if ( $file_path !== "{$plugin_path}/" || $file_path !== $plugin_path ) {
                @unlink( $file_path );
            }

            $extracted_file_dir = glob( "{$temp_dest}/*", GLOB_ONLYDIR );
            foreach ( $extracted_file_dir as $dir_path ) {
                $dir_name  = basename( $dir_path );
                $dest_path = "{$plugin_path}/{$dir_name}";

                // Delete Previous Files if Exists
                if ( $wp_filesystem->exists( $dest_path ) ) {
                    $wp_filesystem->delete( $dest_path, true );
                }
            }

            copy_dir( $temp_dest, $plugin_path );
            $wp_filesystem->delete( $temp_dest, true );
        }

        // download_theme
        public function download_theme( array $args = [] ) {
            $default = [ 'url' => '', 'init_wp_filesystem' => true ];
            $args = array_merge( $default, $args );

            if ( empty( $default ) ) { return; }

            global $wp_filesystem;

            if ( $args[ 'init_wp_filesystem' ] ) {
                if ( ! function_exists( 'WP_Filesystem' ) ) {
                    include  ABSPATH . 'wp-admin/includes/file.php';
                }
                WP_Filesystem();
            }

            $theme_path = ABSPATH . 'wp-content/themes';
            $temp_dest  = "{$theme_path}/atbdp-temp-dir";
            $file_url   = $args['url'];
            $file_name  = basename( $file_url );
            $tmp_file   = download_url( $file_url );

            // Make Temp Dir
            if ( $wp_filesystem->exists( $temp_dest ) ) {
                $wp_filesystem->delete( $temp_dest, true );
            }
            $wp_filesystem->mkdir( $temp_dest );

            // Sets file temp destination.
            $file_path = "{$temp_dest}/{$file_name}";

            // Copies the file to the final destination and deletes temporary file.
            copy( $tmp_file, $file_path );
            @unlink( $tmp_file );

            unzip_file( $file_path, $temp_dest );
            if ( $file_path !== "{$theme_path}/" || $file_path !== $theme_path ) {
                @unlink( $file_path );
            }

            $extracted_file_dir = glob( "{$temp_dest}/*", GLOB_ONLYDIR );
            $dir_path = $extracted_file_dir[0];

            $dir_name  = basename( $dir_path );
            $dest_path = "{$theme_path}/{$dir_name}";
            $zip_files = glob( "{$dir_path}/*.zip" );

            // If has child theme
            if ( ! empty( $zip_files ) ) {
                $new_temp_dest = "{$temp_dest}/_temp_dest";
                $this->install_themes_from_zip_files( $zip_files, $new_temp_dest, $wp_filesystem );

                copy_dir( $new_temp_dest, $theme_path );
                $wp_filesystem->delete( $temp_dest, true );

                return;
            }

            // Delete Previous Files If Exists
            if ( $wp_filesystem->exists( $dest_path ) ) {
                $wp_filesystem->delete( $dest_path, true );
            }

            copy_dir( $temp_dest, $theme_path );
            $wp_filesystem->delete( $temp_dest, true );
        }

        // install_theme_from_zip
        public function install_themes_from_zip_files( $zip_files, $temp_dest, $wp_filesystem ) {
            $theme_path = ABSPATH . 'wp-content/themes';

            foreach( $zip_files as $zip ) {
                $file     = basename( $zip );
                $dir_name = str_replace( '.zip', '', $file );

                if ( preg_match( '/[-]child[.]zip$/', $file ) ) {
                    $temp_dest_path = "{$temp_dest}/{$dir_name}";
                    $main_dest_path = "{$theme_path}/{$dir_name}";

                    // Skip if has child
                    if ( $wp_filesystem->exists( $main_dest_path ) ) {
                        continue;
                    }

                    $wp_filesystem->mkdir( $temp_dest_path );
                    unzip_file( $zip, $temp_dest_path );
                    @unlink( $zip );

                    continue;
                }

                $main_dest_path = "{$theme_path}/{$dir_name}";
                if ( $wp_filesystem->exists( $main_dest_path ) ) {
                    $wp_filesystem->delete( $main_dest_path, true );
                }

                unzip_file( $zip, $temp_dest );
                @unlink( $zip );
            }
        }

        // get_customers_purchased
        public function get_customers_purchased( $license_data ) {
            // Activate the licenses
            // $activation_base_url = 'https://directorist.com?edd_action=activate_license&url=' . home_url();
            $activation_base_url = 'https://directorist.com?edd_action=activate_license';
            
            // Activate the Extensions
            $purchased_extensions_meta    = [];
            $purchased_extensions         = [];
            $invalid_purchased_extensions = [];

            if ( ! empty( $license_data[ 'plugins' ] ) ) {
                foreach( $license_data[ 'plugins' ] as $extension ) {
                    $item_id        = $extension['item_id'];
                    $license        = ( ! empty( $response_body['all_access'] ) ) ? $response_body['active_licenses'][0] : $extension['license'];
                    $query_args     = "&item_id={$item_id}&license={$license}";
                    $activation_url = $activation_base_url . $query_args;

                    $response        = wp_remote_get( $activation_url );
                    $response_status = json_decode( $response['body'], true );

                    if ( empty( $response_status['success'] ) ) {
                        $invalid_purchased_extensions[] = [ 'extension' => $extension, 'response' => $response_status ];
                        continue;
                    }
                    
                    $purchased_extensions[] = $extension;

                    // Store the ref for db
                    $link    = $extension[ 'permalink' ];
                    $ext_key = str_replace( 'http://directorist.com/product/', '', $link );
                    $ext_key = str_replace( 'https://directorist.com/product/', '', $ext_key );
                    $ext_key = str_replace( '/', '', $ext_key );

                    $purchased_extensions_meta[ $ext_key ] = [
                        'item_id' => $extension[ 'item_id' ],
                        'license' => $extension[ 'license' ],
                        'license' => $extension[ 'license' ],
                        'file'    => $extension[ 'links' ],
                    ];
                }
            }

            // Activate the Themes
            $purchased_themes_meta    = [];
            $purchased_themes         = [];
            $invalid_purchased_themes = [];

            if ( ! empty( $license_data[ 'themes' ] ) ) {
                foreach( $license_data[ 'themes' ] as $theme ) {
                    $item_id        = $theme['item_id'];
                    $license        = ( ! empty( $response_body['all_access'] ) ) ? $response_body['active_licenses'][0] : $theme['license'];
                    $query_args     = "&item_id={$item_id}&license={$license}";
                    $activation_url = $activation_base_url . $query_args;

                    $response        = wp_remote_get( $activation_url );
                    $response_status = json_decode( $response['body'], true );

                    if ( empty( $response_status['success'] ) ) {
                        $invalid_purchased_themes[] = $theme;
                        $invalid_purchased_themes[] = [ 'extension' => $theme, 'response' => $response_status ];
                        continue;
                    }

                    $purchased_themes[] = $theme;
                    
                    // Store the ref for db
                    $link      = $theme[ 'permalink' ];
                    $theme_key = str_replace( 'http://directorist.com/product/', '', $link );
                    $theme_key = str_replace( 'https://directorist.com/product/', '', $theme_key );
                    $theme_key = str_replace( '/', '', $theme_key );

                    $purchased_themes_meta[ $theme_key ] = [
                        'item_id' => $extension[ 'item_id' ],
                        'license' => $extension[ 'license' ],
                        'file'    => $extension[ 'links' ],
                    ];
                }
            }

            $customers_purchased = [
                'extensions' => $purchased_extensions_meta,
                'themes'     => $purchased_themes_meta,
            ];

            update_user_meta( get_current_user_id(), '_atbdp_purchased_products', $customers_purchased );

            $status['purchased_extensions'] = $purchased_extensions;
            $status['invalid_purchased_extensions'] = $invalid_purchased_extensions;

            $status['purchased_themes'] = $purchased_themes;
            $status['invalid_purchased_themes'] = $invalid_purchased_themes;

            $status['customers_purchased'] = $customers_purchased;

            return $status;
        }

        // download_purchased_items
        public function download_purchased_items() {
            $status = [ 'success' => true, 'log' => [] ];

            $cart = ( isset( $_POST['customers_purchased'] ) ) ? $_POST['customers_purchased'] : '';

            if ( empty( $cart ) ) {
                $status['success'] = false;
                $status['log']['no_purchased_data_found'] = [
                    'type'    => 'error',
                    'message' => 'No purchased data found',
                ];
                wp_send_json([ 'status' => $status ]);
            }

            // Download the extensions
            if ( ! function_exists( 'WP_Filesystem' ) ) {
                include  ABSPATH . 'wp-admin/includes/file.php';
            }
            WP_Filesystem();

            // Download Extenstions
            if ( ! empty( $cart['purchased_extensions'] ) ) {
                foreach ( $cart['purchased_extensions'] as $extension ) {
                    $paths = $extension['links'];
                    if ( empty( $paths ) ) { continue; }

                    foreach ( $paths as $path ) {
                        $this->download_plugin( [ 'url' => $path, 'init_wp_filesystem' => false ] );
                    }
                }
            }

            // Download Themes
            if ( ! empty( $cart['purchased_themes'] ) ) {
                foreach ( $cart['purchased_themes'] as $theme ) {
                    $paths = $theme['links'];
                    if ( empty( $paths ) ) { continue; }

                    foreach ( $paths as $path ) {
                        $this->download_theme( [ 'url' => $path, 'init_wp_filesystem' => false ] );
                    }
                }
            }

            $status['message'] = 'Download has been completed, redirecting...';

            wp_send_json([ 'status' => $status ]);
            
        }


        /**
         * It Adds menu item
         */
        public function admin_menu()
        {
            add_submenu_page('edit.php?post_type=at_biz_dir',
                __('Get Extensions', 'directorist'),
                __('<span>Themes & Extensions</span>', 'directorist'),
                'manage_options',
                'atbdp-extension',
                array($this, 'show_extension_view')
            );

        }

        /**
         * It Loads Extension view
         */
        public function show_extension_view()
        {
            // delete_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion' );

            // Check Sassion
            $has_subscriptions_sassion = get_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion', true );
            $is_logged_in = ( ! empty( $has_subscriptions_sassion ) ) ? true : false;

            // Get Extensions Details
            $plugin_updates       = get_site_transient( 'update_plugins' );
            $outdated_plugins     = $plugin_updates->response;
            $outdated_plugins_key = array_keys( $outdated_plugins );
            
            $all_plugins_list     = get_plugins();
            $installed_extensions = [];
            $active_extensions    = 0;
            $outdated_extensions  = 0;
            
            foreach ( $all_plugins_list as $plugin_base => $plugin_data ) {
                if ( preg_match( '/^directorist-/', $plugin_base ) ) {
                    $installed_extensions[ $plugin_base ] = $plugin_data;

                    if ( is_plugin_active( $plugin_base ) ) {
                        $active_extensions++;
                    }

                    if ( in_array( $plugin_base, $outdated_plugins_key ) ) {
                        $outdated_extensions++;
                    }
                }
            }

            // Get Themes Informations
            $sovware_themes = array_keys( $this->themes );

            $theme_updates       = get_site_transient( 'update_themes' );
            $outdated_themes     = $theme_updates->response;
            $outdated_themes_key = array_keys( $outdated_themes );

            $all_themes         = wp_get_themes();
            $active_theme_slug  = get_option('stylesheet');
            $installed_themes   = [];
            $my_active_themes   = 0;
            $my_outdated_themes = 0;

            foreach ( $all_themes as $theme_base => $theme_data ) {
                if ( in_array( $theme_base, $sovware_themes ) ) {
                    $installed_themes[ $theme_base ] = $theme_data;

                    if ( $active_theme_slug === $theme_base ) {
                        $my_active_themes++;
                    }

                    if ( in_array( $theme_base, $outdated_themes_key ) ) {
                        $my_outdated_themes++;
                    }
                }
            }

            $purchased_products     = get_user_meta( get_current_user_id(), '_atbdp_purchased_products', true );
            $has_purchased_products = ( ! empty( $purchased_products )  ) ? true : false;
            $settings_url           = admin_url( 'edit.php?post_type=at_biz_dir&page=aazztech_settings#_extensions_switch' );

            // Get Active Theme Info
            $current_theme   = wp_get_theme();
            $customizer_link = "customize.php?theme={$current_theme->stylesheet}&return=%2Fwp-admin%2Fthemes.php";
            $customizer_link = admin_url( $customizer_link );

            $active_theme = [
                'name'            => $current_theme->name,
                'version'         => $current_theme->version,
                'thumbnail'       => $current_theme->get_screenshot(),
                'customizer_link' => $customizer_link,
                'has_update'      => ( in_array( $current_theme->stylesheet, $outdated_themes_key ) ) ? true : false,
                'stylesheet'      => $current_theme->stylesheet,
            ];

            // Purshased Installed Themes Info
            $all_purshased_themes = [];
            foreach ( $installed_themes as $theme_base => $purshased_theme ) {
                if ( $active_theme[ 'stylesheet' ] === $theme_base ) { continue; }

                $customizer_link = "customize.php?theme={$purshased_theme->stylesheet}&return=%2Fwp-admin%2Fthemes.php";
                $customizer_link = admin_url( $customizer_link );

                $all_purshased_themes[ $theme_base ] = [
                    'name'            => $purshased_theme->name,
                    'version'         => $purshased_theme->version,
                    'thumbnail'       => $purshased_theme->get_screenshot(),
                    'customizer_link' => $customizer_link,
                    'has_update'      => ( in_array( $purshased_theme->stylesheet, $outdated_themes_key ) ) ? true : false,
                    'stylesheet'      => $purshased_theme->stylesheet,
                ];
            }

            // Plugins available in subscriptions
            $installed_extensions_keys = array_keys( $installed_extensions );
            if ( ! empty( $installed_extensions_keys ) ) {
                foreach( $installed_extensions_keys as $index => $key) {
                    $new_key = preg_replace( '/\/.+/', '', $key );
                    $new_key = preg_replace( '/(directorist-)/', '', $new_key );

                    $installed_extensions_keys[ $index ] = $new_key;
                }
            }

            $plugins_available_in_subscriptions = get_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions', true );
            if ( ! empty( $plugins_available_in_subscriptions ) ) {
                foreach( $plugins_available_in_subscriptions as $base => $args ) {
                    $plugin_key = preg_replace( '/(directorist-)/', '', $base );

                    if ( in_array( $plugin_key, $installed_extensions_keys ) ) {
                        unset( $plugins_available_in_subscriptions[ $base ] );
                    }
                }
            }

            // Filter all active extensions
            $all_active_extensions = $this->get_active_extensions();
            if ( $is_logged_in && ! empty( $all_active_extensions ) ) {
                $plugins_available_in_subscriptions_keys = array_keys( $plugins_available_in_subscriptions );
                foreach ( $all_active_extensions as $_extension_base => $_extension_args ) {
                    if ( in_array( $_extension_base, $plugins_available_in_subscriptions_keys ) ) {
                        unset( $all_active_extensions[ $_extension_base ] );
                    }
                }
            }


            // Themes available in subscriptions
            $all_purshased_themes_keys = array_keys( $all_purshased_themes );
            $_themes_available_in_subscriptions = get_user_meta( get_current_user_id(), '_themes_available_in_subscriptions', true );
            if ( ! empty( $_themes_available_in_subscriptions ) ) {
                $_active_theme_key = $active_theme[ 'stylesheet' ];
                unset( $_themes_available_in_subscriptions[ $_active_theme_key ] );

                foreach( $_themes_available_in_subscriptions as $base => $args ) {
                    if ( in_array( $base, $all_purshased_themes_keys ) ) {
                        unset( $_themes_available_in_subscriptions[ $base ] );
                    }
                }
            }

            $_themes_available_in_subscriptions_keys = array_keys( $_themes_available_in_subscriptions );
            $themes_available_in_subscriptions = [];

            // Import themes which are installed
            foreach ( $all_purshased_themes as $_theme_key => $_theme_atgs ) {
                $item = $all_purshased_themes[ $_theme_key ];
                $item[ 'is_installed' ] = true;

                $themes_available_in_subscriptions[ $_theme_key ] = $item;
            }

            // Import themes which are not installed
            foreach ( $_themes_available_in_subscriptions_keys as $_theme_key ) {
                if ( empty( $this->themes[ $_theme_key ] ) ) { continue; }

                $item = $this->themes[ $_theme_key ];
                $item[ 'is_installed' ] = false;

                $themes_available_in_subscriptions[ $_theme_key ] = $item;
            }


            // Filter all active extensions
            $all_active_themes = $this->get_active_themes();
            if ( $is_logged_in && ! empty( $all_active_themes ) ) {
                $themes_available_in_subscriptions_keys = array_keys( $themes_available_in_subscriptions );
                foreach ( $all_active_themes as $_theme_base => $_extension_args ) {
                    if ( in_array( $_theme_base, $themes_available_in_subscriptions_keys ) ) {
                        unset( $all_active_themes[ $_theme_base ] );
                    }
                }
            }

            // var_dump([
            //     'installed_extensions_keys'          => $installed_extensions_keys,
            //     'plugins_available_in_subscriptions' => array_keys( $plugins_available_in_subscriptions ),
            //     'skipped_plugins'                    => $skipped_plugins,
            //     'extension_list'                     => array_keys( $this->extensions ),
            //     // 'themes_available_in_subscriptions'  => $themes_available_in_subscriptions,
            // ]);

            $data = [
                'is_logged_in'                       => $is_logged_in,
                'installed_extensions'               => $installed_extensions,
                'extension_list'                     => $this->extensions,
                'theme_list'                         => $this->themes,
                'outdated_plugins'                   => $outdated_plugins,
                'active_extensions'                  => $active_extensions,
                'outdated_extensions'                => $outdated_extensions,
                'installed_themes'                   => $installed_themes,
                'active_themes'                      => $my_active_themes,
                'active_theme'                       => $active_theme,
                'outdated_themes'                    => $my_outdated_themes,
                'all_active_extensions'              => $all_active_extensions,
                'all_active_themes'                  => $all_active_themes,
                'all_purshased_themes'               => $all_purshased_themes,
                'plugins_available_in_subscriptions' => $plugins_available_in_subscriptions,
                'themes_available_in_subscriptions'  => $themes_available_in_subscriptions,
                'settings_url'                       => $settings_url,
            ];

            ATBDP()->load_template('theme-extensions/theme-extension', $data );
        }
    }


}