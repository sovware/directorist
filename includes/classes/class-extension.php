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
            add_action( 'wp_ajax_atbdp_download_purchased_items', array($this, 'download_purchased_items') );
            add_action( 'wp_ajax_atbdp_plugins_bulk_action', array($this, 'plugins_bulk_action') );
            add_action( 'wp_ajax_atbdp_update_plugins', array($this, 'update_plugins') );
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
                    'name'        => 'Stripe Payment Gateway',
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
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/dlist-featured.png',
                    'active'      => true,
                ],
                'dservice' => [
                    'name'        => 'DList',
                    'description' => __( 'DService is a kind of listing Directory WordPress theme that brings business owners and customers on the same platform. This multifunctional WordPress theme provides them the opportunity to interact with one another for business purposes.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/dservice/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/dservice-featured.png',
                    'active'      => true,
                ],
                'directoria' => [
                    'name'        => 'Directoria',
                    'description' => __( 'Directoria is an astonishing directory and listing WordPress theme that is designed and developed to provide fastest page loading speed without knowing a single line of code.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directoria/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/Directoria-1.png',
                    'active'      => true,
                ],
            ]);
        }

        // exclude_purchased_extensions
        public function exclude_purchased_extensions( $extensions ) {
            $purchased_products = get_user_meta( get_current_user_id(), '_atbdp_purchased_products', true );
            if ( empty( $purchased_products ) ) { return $extensions; }

            $purchased_extensions = $purchased_products['extensions'];
            if ( empty( $purchased_extensions ) ) { return $extensions; }

            $extensions_keys = array_keys( $extensions );
            $excluded_extensions = $extensions;

            foreach ( $excluded_extensions as $extension_key => $extension ) {
                if ( ! in_array( $extension_key, $extensions_keys ) ) { continue; }

                $excluded_extensions[ $extension_key ]['active'] = false;
            }

            return $excluded_extensions;
        }

        // exclude_purchased_themes
        public function exclude_purchased_themes( $themes ) {
            $purchased_products = get_user_meta( get_current_user_id(), '_atbdp_purchased_products', true );
            if ( empty( $purchased_products ) ) { return $themes; }

            $purchased_themes = $purchased_products['themes'];
            if ( empty( $purchased_themes ) ) { return $themes; }

            $themes_keys = array_keys( $themes );
            $excluded_themes = $themes;

            foreach ( $excluded_themes as $theme_key => $theme ) {
                if ( ! in_array( $theme_key, $themes_keys ) ) { continue; }

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

        // get_license_authentication
        public function get_license_authentication() {
            $status = [ 'success' => true, 'log' => [] ];
            
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
                wp_send_json( $status );
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

                wp_send_json([ 'status' => $status, 'response_body' => $response_body ]);
            }

            $license_data = $response_body['license_data'];

            // Activate the licenses
            $activation_base_url = 'https://directorist.com?edd_action=activate_license&url=' . home_url();
            
            // Activate the Extensions
            $purchased_extensions         = [];
            $purchased_extensions_meta    = [];
            $invalid_purchased_extensions = [];

            if ( ! empty( $license_data[ 'plugins' ] ) ) {
                foreach( $license_data[ 'plugins' ] as $extension ) {
                    $item_id        = $extension['item_id'];
                    $license        = ( ! empty( $response_body['all_access'] ) ) ? $response_body['active_licenses'][0] : $extension['license'];
                    $query_args     = "&item_id={$item_id}&license={$license}";
                    $activation_url = $activation_base_url . $query_args;

                    $activation_response = wp_remote_get( $activation_url );

                    if ( empty( $activation_response['success'] ) ) {
                        $invalid_purchased_extensions[] = $extension;
                        continue;
                    }

                    $purchased_extensions[] = $extension;

                    $link    = $extension[ 'permalink' ];
                    $ext_key = str_replace( 'http://directorist.com/product/', '', $link );
                    $ext_key = str_replace( 'https://directorist.com/product/', '', $ext_key );
                    $ext_key = str_replace( '/', '', $ext_key );

                    $purchased_extensions_meta[ $ext_key ] = [
                        'item_id' => $extension[ 'item_id' ],
                        'license' => $extension[ 'license' ],
                    ];
                }
            }

            // Activate the Themes
            $purchased_themes         = [];
            $purchased_themes_meta    = [];
            $invalid_purchased_themes = [];

            if ( ! empty( $license_data[ 'themes' ] ) ) {
                foreach( $license_data[ 'themes' ] as $theme ) {
                    $item_id        = $theme['item_id'];
                    $license        = ( ! empty( $response_body['all_access'] ) ) ? $response_body['active_licenses'][0] : $theme['license'];
                    $query_args     = "&item_id={$item_id}&license={$license}";
                    $activation_url = $activation_base_url . $query_args;

                    $activation_response = wp_remote_get( $activation_url );

                    if ( empty( $activation_response['success'] ) ) {
                        $invalid_purchased_themes[] = $theme;
                        continue;
                    }

                    $purchased_themes[] = $theme;

                    $link      = $extension[ 'permalink' ];
                    $theme_key = str_replace( 'http://directorist.com/product/', '', $link );
                    $theme_key = str_replace( 'https://directorist.com/product/', '', $theme_key );
                    $theme_key = str_replace( '/', '', $theme_key );

                    $purchased_themes_meta[ $theme_key ] = [
                        'item_id' => $extension[ 'item_id' ],
                        'license' => $extension[ 'license' ],
                    ];
                }
            }

            $customers_purchased = [
                'extensions' => $purchased_extensions_meta,
                'themes'     => $purchased_themes_meta,
            ];

            update_user_meta( get_current_user_id(), '_atbdp_purchased_products', $customers_purchased );

            $status[ 'success' ] = true;
            wp_send_json([ 
                'status'              => $status,
                'license_data'        => $license_data,
                'response'            => $response_body,
                'customers_purchased' => $customers_purchased,
            ]);
        }

        // authenticate_the_customer
        public function authenticate_the_customer() {
            $status = [ 'success' => true, 'log' => [] ];
            
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

            $license_data = $response_body['license_data'];
            update_user_meta( get_current_user_id(), '_atbdp_customer_id', $username );

            $customers_purchased = $this->get_customers_purchased( $license_data );

            $status[ 'success' ] = true;
            $status[ 'log' ]['login_successful'] = [
                'type'    => 'success',
                'message' => 'Login is successful',
            ];

            wp_send_json([ 'status' => $status, 'customers_purchased' => $customers_purchased ]);
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
            // Download Extenstions
            if ( ! empty( $cart['purchased_extensions'] ) ) {
                foreach ( $cart['purchased_extensions'] as $extension ) {
                    $paths = $extension['links'];
                    if ( empty( $paths ) ) { continue; }

                    foreach ( $paths as $path ) {
                        $this->download_plugin( [ 'url' => $path ] );
                    }
                }
            }

            // Download Themes
            if ( ! empty( $cart['purchased_themes'] ) ) {
                foreach ( $cart['purchased_themes'] as $theme ) {
                    $paths = $theme['links'];
                    if ( empty( $paths ) ) { continue; }

                    foreach ( $paths as $path ) {
                        $this->download_theme( [ 'url' => $path ] );
                    }
                }
            }

            $status['message'] = 'Download has been completed, redirecting...';

            wp_send_json([ 'status' => $status ]);
            
        }

        // download_plugin
        public function download_plugin( array $args = [] ) {
            $default = [ 'url' => '' ];
            $args = array_merge( $default, $args );

            if ( empty( $default ) ) { return; }

            WP_Filesystem();

            $installation_path = ABSPATH . 'wp-content/plugins';
            $file_url          = $args['url'];
            $file_name         = basename( $file_url );
            $file_dir_name     = preg_replace( '/[.].+$/', '', $file_name );
            $installation_dir  = $installation_path . '/' . $file_dir_name;
            $tmp_file          = download_url( $file_url );

            // Sets file final destination.
            $filepath = "{$installation_path}/{$file_name}";

            // Copies the file to the final destination and deletes temporary file.
            copy( $tmp_file, $filepath );
            @unlink( $tmp_file );

            global $wp_filesystem;
            if ( $installation_dir !== $installation_path . '/' ) {
                $wp_filesystem->rmdir( $installation_dir, true );
            }
            
            unzip_file( $filepath, $installation_path );
            @unlink( $filepath );
        }
        
        // download_theme
        public function download_theme( array $args = [] ) {
            $default = [ 'url' => '' ];
            $args = array_merge( $default, $args );

            if ( empty( $default ) ) { return; }

            $installation_path = ABSPATH . 'wp-content/themes';
            $file_url          = $args['url'];
            $file_name         = basename( $file_url );
            $file_dir_name     = preg_replace( '/[.].+$/', '', $file_name );
            $installation_dir  = $installation_path . '/' . $file_dir_name;
            $tmp_file          = download_url( $file_url );

            // Sets file final destination.
            $filepath = "{$installation_path}/{$file_name}";
            
            // Copies the file to the final destination and deletes temporary file.
            copy( $tmp_file, $filepath );
            @unlink( $tmp_file );

            global $wp_filesystem;
            if ( $installation_dir !== $installation_path . '/' ) {
                $wp_filesystem->rmdir( $installation_dir, true );
            }
            
            unzip_file( $filepath, $installation_path );
            @unlink( $filepath );

            // If is child theme
            // $theme_dir_path = "{$theme_path}/$file_dir_name";
            // $main_theme_file = "{$theme_dir_path}/";
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
            $sovware_themes = [
                'direo',
                'dlist',
                'dservice',
            ];

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

            // delete_user_meta( get_current_user_id(), '_atbdp_purchased_products' );
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
            foreach ( $all_themes as $theme_base => $purshased_theme ) {
                if ( $active_theme[ 'stylesheet' ] === $theme_base ) { continue; }

                $all_purshased_themes[ $theme_base ] = [
                    'name'            => $purshased_theme->name,
                    'version'         => $purshased_theme->version,
                    'thumbnail'       => $purshased_theme->get_screenshot(),
                    'customizer_link' => $customizer_link,
                    'has_update'      => ( in_array( $purshased_theme->stylesheet, $outdated_themes_key ) ) ? true : false,
                    'stylesheet'      => $purshased_theme->stylesheet,
                ];
            }

            // var_dump( $all_purshased_themes );

            $data = [
                'has_purchased_products' => $has_purchased_products,
                'installed_extensions'   => $installed_extensions,
                'outdated_plugins'       => $outdated_plugins,
                'active_extensions'      => $active_extensions,
                'outdated_extensions'    => $outdated_extensions,
                'installed_themes'       => $installed_themes,
                'active_themes'          => $my_active_themes,
                'active_theme'           => $active_theme,
                'outdated_themes'        => $my_outdated_themes,
                'all_active_extensions'  => $this->get_active_extensions(),
                'all_active_themes'      => $this->get_active_themes(),
                'all_purshased_themes'   => $all_purshased_themes,
                'settings_url'           => $settings_url,
            ];

            ATBDP()->load_template('theme-extensions/theme-extension', $data );
        }
    }


}