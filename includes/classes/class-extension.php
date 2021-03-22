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
        public $extensions          = [];
        public $themes              = [];
        public $required_extensions = [];
        public $extensions_aliases  = [];

        public function __construct()
        {
            // Check for plugin update
            // wp_update_plugins();

            add_action( 'admin_menu', array($this, 'admin_menu'), 100 );
            add_action( 'init', array( $this, 'initial_setup') );
            add_action( 'init', array( $this, 'get_the_product_list') );
            
            // Ajax
            add_action( 'wp_ajax_atbdp_authenticate_the_customer', array($this, 'authenticate_the_customer') );
            add_action( 'wp_ajax_atbdp_download_file', array($this, 'handle_file_download_request') );
            add_action( 'wp_ajax_atbdp_install_file_from_subscriptions', array($this, 'handle_file_install_request_from_subscriptions') );
            add_action( 'wp_ajax_atbdp_plugins_bulk_action', array($this, 'plugins_bulk_action') );
            add_action( 'wp_ajax_atbdp_activate_theme', array($this, 'activate_theme') );
            add_action( 'wp_ajax_atbdp_activate_plugin', array($this, 'activate_plugin') );
            add_action( 'wp_ajax_atbdp_update_plugins', array($this, 'handle_plugins_update_request') );
            add_action( 'wp_ajax_atbdp_update_theme', array($this, 'handle_theme_update_request') );
            add_action( 'wp_ajax_atbdp_refresh_purchase_status', array($this, 'handle_refresh_purchase_status_request') );
            add_action( 'wp_ajax_atbdp_close_subscriptions_sassion', array($this, 'handle_close_subscriptions_sassion_request') );

            // add_action( 'wp_ajax_atbdp_download_purchased_items', array($this, 'download_purchased_items') );
        }

        // initial_setup
        public function initial_setup() {

            $this->setup_extensions_alias();

            wp_update_plugins();
            
            // Check form theme update
            $current_theme = wp_get_theme();
            get_theme_update_available( $current_theme->stylesheet );

            // Apply hook to required extensions
            $this->required_extensions = apply_filters( 'directorist_required_extensions', [] );
        }

        // setup_extensions_alias
        public function setup_extensions_alias() {
            // Latest Key     => Deprecated key
            // Deprecated key => Latest Key
            $this->extensions_aliases = apply_filters( 'directorist_extensions_aliases', [
                'directorist-adverts-manager' => 'directorist-ads-manager',
                'directorist-ads-manager'     => 'directorist-adverts-manager',

                'directorist-gallery'       => 'directorist-image-gallery',
                'directorist-image-gallery' => 'directorist-gallery',

                'directorist-slider-carousel' => 'directorist-listings-slider-carousel',
                'directorist-listings-slider-carousel' => 'directorist-slider-carousel',

                'directorist-faqs'         => 'directorist-listing-faqs',
                'directorist-listing-faqs' => 'directorist-faqs',
            ]);
        }


        // get_required_extension_list
        public function get_required_extension_list() {
            $required_extensions = [];

            foreach ( $this->required_extensions as $recommandation ) {

                if ( ! isset( $recommandation['extensions'] ) ) { continue; }
                if ( ! is_array( $recommandation['extensions'] ) ) { continue; }

                foreach ( $recommandation['extensions'] as $extension ) {
                    $extension_alias = $this->get_extension_alias_key( $extension );

                    if ( ! ( isset( $this->extensions[ $extension ] ) || isset( $this->extensions[ $extension_alias ] ) ) ) { continue; }

                    if ( empty( $required_extensions[ $extension ] ) ) {
                        $required_extensions[ $extension ] = [];
                    }

                    $required_extensions[ $extension ][] = $recommandation['ref'];
                }
            }
            
            return $required_extensions;
        }

        // prepare_the_final_requred_extension_list
        public function prepare_the_final_requred_extension_list( array $args = [] ) {
            $recommandation = [];

            $required_extensions_list              = $this->get_required_extension_list();
            $extensions_available_in_subscriptions = ( ! empty( $args['extensions_available_in_subscriptions'] ) ) ? $args['extensions_available_in_subscriptions'] : [];
            $extensions_available_in_subscriptions = ( is_array( $extensions_available_in_subscriptions ) ) ? array_keys( $extensions_available_in_subscriptions ) : [];
            $installed_extension_list              = ( ! empty( $args['installed_extension_list'] ) ) ? $args['installed_extension_list'] : [];
            $installed_extension_list              = ( is_array( $installed_extension_list ) ) ? array_keys( $installed_extension_list ) : [];

            foreach ( $required_extensions_list as $extension => $recommanded_by ) {

                $extension_alias = $this->get_extension_alias_key( $extension );

                if ( is_plugin_active( "{$extension}/{$extension}.php" ) ) { continue; }
                if ( is_plugin_active( "{$extension_alias}/{$extension_alias}.php" ) ) { continue; }

                $is_purchased = ( in_array( $extension, $extensions_available_in_subscriptions ) ) ? true : false;
                $is_purchased_alias = ( in_array( $extension_alias, $extensions_available_in_subscriptions ) ) ? true : false;

                $is_installed = ( in_array( "{$extension}/{$extension}.php", $installed_extension_list ) ) ? true : false;
                $is_installed_alias = ( in_array( "{$extension_alias}/{$extension_alias}.php", $installed_extension_list ) ) ? true : false;

                $recommandation[ $extension ] = [];
                $recommandation[ $extension ][ 'ref' ] = $recommanded_by;
                $recommandation[ $extension ][ 'purchased' ] = ( $is_purchased || $is_purchased_alias ) ? true : false;
                $recommandation[ $extension ][ 'installed' ] = ( $is_installed || $is_installed_alias ) ? true : false;
            }

            return $recommandation;
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
                'directorist-compare-listing' => [
                    'name'        => 'Compare Listings',
                    'description' => __( 'Compare Listings extension allows users to add a set of listings in a list and compare its features by viewing in a comparison table.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-compare-listing/',
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
                'directorist-google-recaptcha' => [
                    'name'        => 'Google reCAPTCHA',
                    'description' => __( 'Use reCAPTCHA service from Google to help your directory site protect from spam and further abuse. This Google reCAPTCHA extension allows you to make it happen by taking care of your site.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-google-recaptcha/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/10_Google-ReCAPTCHA-2.png',
                    'active'      => true,
                ],
                'directorist-faqs' => [
                    'name'        => 'Listing FAQs',
                    'description' => __( 'Use an organized FAQ page on your directory website and provide quick information to help customers make a potential decision. Here, the idea is to keep the answers short and direct so that people find info quickly.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-faqs/',
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
                'directorist-slider-carousel' => [
                    'name'        => 'Listings Slider & Carousel',
                    'description' => __( 'Increase the beauty of your directory website by displaying numerous listings through attractive sliders or carousels with this highly customizable extension.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-slider-carousel/',
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
                'directorist-gallery' => [
                    'name'        => 'Image Gallery',
                    'description' => __( 'Use a quality image gallery and increase conversation by reducing your return rate on your directory listing website.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-gallery/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/08/07_Image-Gallery-1.png',
                    'active'      => true,
                ],
                'directorist-adverts-manager' => [
                    'name'        => 'Directorist Ads Manager',
                    'description' => __( 'Are you wondering about placing advertisements in your directory? Directorist Ads Manager allows you to insert advertisements on specific Directorist pages such as All listings, Single Listings, All Location, All Category, etc.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-adverts-manager/',
                    'thumbnail'   => 'https://directorist.com/wp-content/uploads/edd/2020/12/single-ad-manager.png',
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

            $purchased_extensions_keys = ( is_array( $purchased_extensions ) ) ? array_keys( $purchased_extensions ) : [];
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

            $purchased_themes_keys = is_array( $purchased_themes ) ? array_keys( $purchased_themes ) : [];
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

        // handle_plugins_update_request
        public function handle_plugins_update_request() {
            $plugin_key = ( isset( $_POST['plugin_key'] ) ) ? $_POST['plugin_key'] : '';
            $status = $this->update_plugins( [ 'plugin_key' => $plugin_key ] );

            wp_send_json( $status );
        }

        // get_extension_alias_key
        public function get_extension_alias_key( string $plugin_key = '' ) {
            $extensions_aliases      = $this->extensions_aliases;
            $extensions_aliases_keys = ( is_array( $extensions_aliases ) && ! empty( $extensions_aliases ) ) ? array_keys( $extensions_aliases ) : [];
            $plugin_alias_key        = in_array( $plugin_key, $extensions_aliases_keys ) ? $extensions_aliases[ $plugin_key ] : '';

            return $plugin_alias_key;
        }

        // update_plugins
        public function update_plugins( array $args = [] ) {
            $default = [ 'plugin_key' => '' ];
            $args = array_merge( $default, $args );

            $status     = [ 'success' => true ];
            $plugin_key = $args[ 'plugin_key' ];

            $plugin_updates       = get_site_transient( 'update_plugins' );
            $outdated_plugins     = $plugin_updates->response;
            $outdated_plugins_key = ( is_array( $outdated_plugins ) ) ? array_keys( $outdated_plugins ) : [];

            if ( empty( $outdated_plugins_key ) ) { 
                $status['massage'] = __( 'All plugins are up to date', 'directorist' );
                return [ 'status' => $status ];
            }

            if ( ! empty( $plugin_key ) && ! in_array( $plugin_key, $outdated_plugins_key )  ) {
                $status['massage'] = __( 'The plugin is up to date', 'directorist' );
                return [ 'status' => $status ];
            }

            $plugins_available_in_subscriptions      = get_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions', true );
            $plugins_available_in_subscriptions_keys = ( is_array( $plugins_available_in_subscriptions ) ) ? array_keys( $plugins_available_in_subscriptions ) : [];
            
            if ( ! empty( $plugin_key ) ) {
                $outdated_plugin = $outdated_plugins[ $plugin_key ];
                $url = $outdated_plugin->package;

                if ( empty( $url ) ) {
                    $plugin_key = preg_replace( '/\/.+/', '', $plugin_key );

                    if ( in_array( $plugin_key, $plugins_available_in_subscriptions_keys ) ) {
                        $url = $plugins_available_in_subscriptions[ $plugin_key ][ 'download_link' ];
                    }

                    $plugin_alias_key = $this->get_extension_alias_key( $plugin_key );
                    if ( empty( $url ) && in_array( $plugin_alias_key, $plugins_available_in_subscriptions_keys ) ) {
                        $url = $plugins_available_in_subscriptions[ $plugin_alias_key ][ 'download_link' ];
                    }
                }

                $download_status = $this->download_plugin( [ 'url' => $url ] );

                if ( ! $download_status['success'] ) {
                    $status['success'] = false;
                    $status['massage'] = __( 'The plugin could not update', 'directorist' );
                    $status['log']     = $download_status['massage'];
                } else {
                    $status['success'] = true;
                    $status['massage'] = __( 'The plugin has been updated successfully', 'directorist' );
                    $status['log']     = $download_status['massage'];
                }

                return [ 'status' => $status ];
            }

            // Update all
            $updated_plugins       = [];
            $update_failed_plugins = [];

            foreach ( $outdated_plugins as $plugin_base => $plugin ) {
                $url = $plugin->package;

                if ( empty( $url ) ) {
                    $plugin_key = preg_replace( '/\/.+/', '', $plugin_base );

                    if ( in_array( $plugin_key, $plugins_available_in_subscriptions_keys ) ) {
                        $url = $plugins_available_in_subscriptions[ $plugin_key ][ 'download_link' ];
                    }
                }

                $download_status = $this->download_plugin( [ 'url' => $url ] );

                if ( ! $download_status['success'] ) {
                    $update_failed_plugins[ $plugin_base ] = $plugin;
                    
                } else {
                    $updated_plugins[ $plugin_base ] = $plugin;
                }
            }

            $status['updated_plugins']       = $updated_plugins;
            $status['update_failed_plugins'] = $update_failed_plugins;

            if ( ! empty( $updated_plugins ) && ! empty( $update_failed_plugins ) ) {
                $status['success'] = false;
                $status['massage']  = __( 'Some of the plugin could not update', 'directorist' );
            }

            if ( empty( $update_failed_plugins ) ) {
                $status['success'] = true;
                $status['massage']  = __( 'All the plugins are updated successfully', 'directorist' );
            }

            if ( empty( $updated_plugins ) ) {
                $status['success'] = true;
                $status['massage']  = __( 'No plugins could not update', 'directorist' );
            }

            return [ 'status' => $status ];
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

        // activate_plugin
        public function activate_plugin() {
            $status = [ 'success' => true ];
            $plugin_key = ( isset( $_POST['item_key'] ) ) ? $_POST['item_key'] : '';

            if ( empty( $plugin_key ) ) {
                $status['success'] = false;
                $status['log'] = [ '$plugin_key' => $plugin_key ];
                $status['message'] = __('Please specefy which plugin to activate', 'directorist');

                wp_send_json( [ 'status' => $status] );
            }

            activate_plugin( $plugin_key );
            wp_send_json( [ 'status' => $status] );
        }

        // handle_theme_update_request
        public function handle_theme_update_request() {
            $theme_stylesheet = ( isset( $_POST['theme_stylesheet'] ) ) ? $_POST['theme_stylesheet'] : '';

            $update_theme_status = $this->update_the_themes( [ 'theme_stylesheet' => $theme_stylesheet ] );
            wp_send_json( $update_theme_status );
        }

        // update_the_theme
        public function update_the_themes( array $args = [] ) {
            $default = [ 'theme_stylesheet' => '' ];
            $args = array_merge( $default, $args ); 

            $status = [ 'success' => true ];

            $theme_stylesheet    = $args[ 'theme_stylesheet' ];
            $theme_updates       = get_site_transient( 'update_themes' );
            $outdated_themes     = $theme_updates->response;
            $outdated_themes_key = ( is_array( $outdated_themes ) ) ? array_keys( $outdated_themes ) : [];

            if ( empty( $outdated_themes_key ) ) { 
                $status['massage'] = __( 'All themes are up to date', 'directorist' );
                return [ 'status' => $status ];
            }

            if ( ! empty( $theme_stylesheet ) && ! in_array( $theme_stylesheet, $outdated_themes_key )  ) {
                $status['massage'] = __( 'The theme is up to date', 'directorist' );
                return [ 'status' => $status ];
            }

            $themes_available_in_subscriptions      = get_user_meta( get_current_user_id(), '_themes_available_in_subscriptions', true );
            $themes_available_in_subscriptions_keys = ( is_array( $themes_available_in_subscriptions ) ) ? array_keys( $themes_available_in_subscriptions ) : [];

            // Check if stylesheet is present
            if ( ! empty( $theme_stylesheet ) ) {

                // Check if the the update is available
                if ( ! in_array( $theme_stylesheet, $outdated_themes_key ) ) {
                    $status['success'] = false;
                    $status['message'] = __('The theme is already upto date', 'directorist');

                    return [ 'status' => $status ];
                }

                $outdated_theme = $outdated_themes[ $theme_stylesheet ];
                $url = $outdated_theme[ 'package' ];

                if ( empty( $url ) ) {
                    if ( in_array( $theme_stylesheet, $themes_available_in_subscriptions_keys ) ) {
                        $url = $themes_available_in_subscriptions_keys[ $theme_stylesheet ][ 'download_link' ];
                    }
                }

                $download_status = $this->download_theme( [ 'url' => $url ] );

                if ( ! $download_status['success'] ) {
                    $status['success'] = false;
                    $status['massage'] = __( 'The theme could not update', 'directorist' );
                    $status['log']     = $download_status['massage'];
                } else {
                    $status['success'] = true;
                    $status['massage'] = __( 'The theme has been updated successfully', 'directorist' );
                    $status['log']     = $download_status['massage'];
                };

                return [ 'status' => $status ];
            }


            // Update all
            $updated_themes       = [];
            $update_failed_themes = [];

            foreach ( $outdated_themes as $theme_key => $theme ) {
                $url = $theme->package;

                if ( empty( $url ) ) {
                    if ( in_array( $theme_key, $themes_available_in_subscriptions_keys ) ) {
                        $url = $themes_available_in_subscriptions[ $theme_key ][ 'download_link' ];
                    }
                }

                $download_status = $this->download_theme( [ 'url' => $url ] );

                if ( ! $download_status['success'] ) {
                    $update_failed_themes[ $theme_key ] = $theme;
                    
                } else {
                    $updated_themes[ $theme_key ] = $theme;
                }
            }

            $status['updated_themes']       = $updated_themes;
            $status['update_failed_themes'] = $update_failed_themes;

            if ( ! empty( $updated_themes ) && ! empty( $update_failed_themes ) ) {
                $status['success'] = false;
                $status['massage']  = __( 'Some of the theme could not update', 'directorist' );
            }

            if ( empty( $update_failed_themes ) ) {
                $status['success'] = true;
                $status['massage']  = __( 'All the themes are updated successfully', 'directorist' );
            }

            if ( empty( $updated_themes ) ) {
                $status['success'] = true;
                $status['massage']  = __( 'No themes could not update', 'directorist' );
            }

            return [ 'status' => $status ];
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

            $previous_username = get_user_meta( get_current_user_id(), '_atbdp_subscribed_username', true );
            
            // Enable Sassion
            update_user_meta( get_current_user_id(), '_atbdp_subscribed_username', $username );
            update_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion', true );

            $plugins_available_in_subscriptions = get_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions', true );
            $themes_available_in_subscriptions  = get_user_meta( get_current_user_id(), '_themes_available_in_subscriptions', true );
            $has_previous_subscriptions         = ( ! empty( $plugins_available_in_subscriptions ) || ! empty( $themes_available_in_subscriptions ) ) ? true : false;

            if ( $previous_username === $username && $has_previous_subscriptions ) {
                // Enable Sassion
                update_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion', true );
                $this->refresh_purchase_status( $args = [ 'password' => $password ] );
                
                wp_send_json( [ 'status' => $status, 'has_previous_subscriptions' => true ] );
            }

            delete_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions' );
            delete_user_meta( get_current_user_id(), '_themes_available_in_subscriptions' );

            $license_data = $response_body['license_data'];

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

        // handle_refresh_purchase_status_request
        public function handle_refresh_purchase_status_request() {
            $status   = [ 'success' => true ];
            $password = ( isset( $_POST['password'] ) ) ? $_POST['password'] : '';

            $status = $this->refresh_purchase_status( [ 'password' => $password ] );

            wp_send_json( $status );
        }
        

        // refresh_purchase_status
        public function refresh_purchase_status( array $args = [] ) {
            $status  = [ 'success' => true ];
            $default = [ 'password' => '' ];
            $args    = array_merge( $default, $args );


            if ( empty( $args['password'] ) ) {
                $status[ 'success' ] = false;
                $status[ 'message' ] = __( 'Password is required', 'directorist' );

                return [ 'status' => $status ];
            }

            $username = get_user_meta( get_current_user_id(), '_atbdp_subscribed_username', true );
            $password = $args['password'];

            if ( empty( $username ) ) {
                $status[ 'success' ]  = false;
                $status[ 'reload' ]   = true;
                $status[ 'message' ]  = __( 'Sassion is destroyed, please sign-in again', 'directorist' );

                delete_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion' );

                return [ 'status' => $status ];
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

                return [ 'status' => $status, 'response_body' => $response_body ];
            }

            $license_data = $response_body['license_data'];

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
            $status[ 'message' ] = __( 'Your purchase has been refreshed successfuly', 'directorist' );

            return [ 'status' => $status ];
        }

        // handle_close_subscriptions_sassion_request
        public function handle_close_subscriptions_sassion_request() {
            $hard_logout_state = ( isset( $_POST[ 'hard_logout' ] ) ) ? $_POST[ 'hard_logout' ] : false;
            $status = $this->close_subscriptions_sassion( [ 'hard_logout' => $hard_logout_state ] );
            
            wp_send_json( $status );
        }

        // close_subscriptions_sassion
        public function close_subscriptions_sassion( array $args = [] ) {
            $default = [ 'hard_logout' => false ];
            $args = array_merge( $default, $args );

            $status = [ 'success' => true ];
            delete_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion' );

            if ( $args[ 'hard_logout' ] ) {
                delete_user_meta( get_current_user_id(), '_atbdp_subscribed_username' );
                delete_user_meta( get_current_user_id(), '_themes_available_in_subscriptions' );
                delete_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions' );
            }

            return $status;
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

            if ( isset( $license_item['skip_licencing'] ) && ! empty( $license_item['skip_licencing'] ) ) {
                return $status;
            }

            $item_id = ( ! empty( $license_item['item_id'] ) ) ? $license_item['item_id'] : 0;
            $license = ( ! empty( $license_item['license'] ) ) ? $license_item['license']: '';
            
            $site_url            = apply_filters( 'atbdp_membership_site_activation_url', home_url() );
            $activation_base_url = 'https://directorist.com?edd_action=activate_license&url=' . $site_url;
            $query_args          = "&item_id={$item_id}&license={$license}";
            $activation_url      = $activation_base_url . $query_args;

            $response        = wp_remote_get( $activation_url );
            $response_status = json_decode( $response['body'], true );

            if ( empty( $response_status['success'] ) ) {
                $status[ 'success' ] = false;
            }

            $status[ 'response' ] = $response_status;

            $product_type = ( 'plugins' === $product_type ) ? 'plugin' : $product_type;
            $product_type = ( 'themes' === $product_type ) ? 'theme' : $product_type;

            if ( $status[ 'success' ] && ( 'plugin' === $product_type || 'theme' === $product_type )  ) {
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
                $purchased_items[ $product_key ] = $license_item;

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
            
            $activatation_status = $this->activate_license( $installing_file, $type );
            $status[ 'log'] = $activatation_status;

            if ( ! $activatation_status[ 'success' ] ) {
                $status[ 'success'] = false;
                $status[ 'message'] = __( 'The license is not valid, please check you subscription.', 'directorist' );

                return [ 'status' => $status ];
            }
            

            $link = $installing_file['download_link'];
            if ( 'plugin' === $type ) {
                $this->download_plugin( [ 'url' => $link ] );
            }

            if ( 'theme' === $type ) {
                $this->download_theme( [ 'url' => $link ] );
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

            if ( empty( $download_item['download_link'] ) ) {
                $status[ 'success'] = false;
                $status[ 'message'] = 'Download Link not found';

                wp_send_json( [ 'status' => $status ] );
            }
            
            if ( ! is_string( $download_item['download_link'] ) ) {
                $status[ 'success'] = false;
                $status[ 'message'] = 'Download Link not found';

                wp_send_json( [ 'status' => $status ] );
            }

            $link = $download_item['download_link'];
            if ( 'plugin' === $type ) {
                $this->download_plugin( [ 'url' => $link ] );
            }

            if ( 'theme' === $type ) {
                $this->download_theme( [ 'url' => $link ] );
            }
            
            $status[ 'message'] = __( 'Donloaded', 'directorist' );
            wp_send_json( [ 'status' => $status ] );
        }

        // download_plugin
        public function download_plugin( array $args = [] ) {
            $status = [ 'success' => true ];

            $default = [ 'url' => '', 'init_wp_filesystem' => true ];
            $args = array_merge( $default, $args );

            if ( empty( $default ) ) { return; }

            if ( empty( $args[ 'url' ] ) ) {
                $status[ 'success' ] = false;
                $status[ 'massage' ] = __( 'Download link not found', 'directorist' );

                return $status;
            }

            global $wp_filesystem;

            if ( $args[ 'init_wp_filesystem' ] ) {
                if ( ! function_exists( 'WP_Filesystem' ) ) {
                    include  ABSPATH . 'wp-admin/includes/file.php';
                }
                WP_Filesystem();
            }
            

            $plugin_path = ABSPATH . 'wp-content/plugins';
            $temp_dest   = "{$plugin_path}/atbdp-temp-dir";
            $file_url    = $args['url'];
            $file_name   = basename( $file_url );
            $tmp_file    = download_url( $file_url );

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

            $status[ 'success' ] = true;
            $status[ 'massage' ] = __( 'The plugin has been downloaded successfully', 'directorist' );

            return $status;
        }

        // download_theme
        public function download_theme( array $args = [] ) {
            $status = [ 'success' => true ];

            $default = [ 'url' => '', 'init_wp_filesystem' => true ];
            $args = array_merge( $default, $args );

            if ( empty( $default ) ) { return; }

            if ( empty( $args[ 'url' ] ) ) {
                $status[ 'success' ] = false;
                $status[ 'massage' ] = __( 'Download link not found', 'directorist' );

                return $status;
            }

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

            $status[ 'success' ] = true;
            $status[ 'massage' ] = __( 'The theme has been downloaded successfully', 'directorist' );

            return $status;
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
                    // @unlink( $zip );

                    continue;
                }

                $main_dest_path = "{$theme_path}/{$dir_name}";
                if ( $wp_filesystem->exists( $main_dest_path ) ) {
                    $wp_filesystem->delete( $main_dest_path, true );
                }

                unzip_file( $zip, $temp_dest );
                // @unlink( $zip );
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
                    $download_link = $extension['download_link'];
                    if ( empty( $download_link ) ) { continue; }

                    $this->download_plugin( [ 'url' => $download_link, 'init_wp_filesystem' => false ] );
                }
            }

            // Download Themes
            if ( ! empty( $cart['purchased_themes'] ) ) {
                foreach ( $cart['purchased_themes'] as $theme ) {
                    $download_link = $extension['download_link'];
                    if ( empty( $download_link ) ) { continue; }

                    $this->download_theme( [ 'url' => $download_link, 'init_wp_filesystem' => false ] );
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

        // get_extensions_overview
        public function get_extensions_overview() {
            // Get Extensions Details
            $plugin_updates       = get_site_transient( 'update_plugins' );
            $outdated_plugins     = $plugin_updates->response;
            $outdated_plugins_key = ( is_array( $outdated_plugins ) ) ? array_keys( $outdated_plugins ) : [];
            
            $all_installed_plugins_list = get_plugins();
            $installed_extensions       = [];
            $total_active_extensions    = 0;
            $total_outdated_extensions  = 0;
            
            foreach ( $all_installed_plugins_list as $plugin_base => $plugin_data ) {
                if ( preg_match( '/^directorist-/', $plugin_base ) ) {
                    $installed_extensions[ $plugin_base ] = $plugin_data;

                    if ( is_plugin_active( $plugin_base ) ) {
                        $total_active_extensions++;
                    }

                    if ( in_array( $plugin_base, $outdated_plugins_key ) ) {
                        $total_outdated_extensions++;
                    }
                }
            }

            // ---
            $extensions_available_in_subscriptions = $this->get_extensions_available_in_subscriptions([ 
                'installed_extensions' => $installed_extensions
            ]);

            // ---
            $extensions_promo_list = $this->get_extensions_promo_list([
                'extensions_available_in_subscriptions' => $extensions_available_in_subscriptions,
                'installed_extensions' => $installed_extensions,
            ]);

            $required_extensions_list = $this->prepare_the_final_requred_extension_list([
                'installed_extension_list'              => $installed_extensions,
                'extensions_available_in_subscriptions' => $extensions_available_in_subscriptions,
            ]);

            $total_installed_ext_list = count( $installed_extensions );
            $total_ext_available_in_subscriptions = count( $extensions_available_in_subscriptions );
            $total_available_extensions = $total_installed_ext_list + $total_ext_available_in_subscriptions;

            $overview = [
                'outdated_plugin_list'                  => $outdated_plugins,
                'outdated_plugins_key'                  => $outdated_plugins_key,
                'all_installed_plugins_list'            => $all_installed_plugins_list,
                'installed_extension_list'              => $installed_extensions,
                'total_active_extensions'               => $total_outdated_extensions,
                'total_outdated_extensions'             => $total_outdated_extensions,
                'extensions_promo_list'                 => $extensions_promo_list,
                'extensions_available_in_subscriptions' => $extensions_available_in_subscriptions,
                'total_available_extensions'            => $total_available_extensions,
                'required_extensions'                   => $required_extensions_list,
            ];

            return $overview;
        }

        // get_extensions_available_in_subscriptions
        public function get_extensions_available_in_subscriptions( array $args = [] ) {
            $installed_extensions = ( ! empty( $args[ 'installed_extensions' ] ) ) ? $args[ 'installed_extensions' ] : [];
            $installed_extensions_keys = $this->get_sanitized_extensions_keys( $installed_extensions );

            $extensions_available_in_subscriptions = get_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions', true );
            $extensions_available_in_subscriptions = ( is_array( $extensions_available_in_subscriptions ) ) ? $extensions_available_in_subscriptions : [];

            if ( ! empty( $extensions_available_in_subscriptions ) && is_array( $extensions_available_in_subscriptions ) ) {
                foreach( $extensions_available_in_subscriptions as $base => $args ) {
                    $base_alias = $this->get_extension_alias_key( $base );
                    $plugin_key = preg_replace( '/(directorist-)/', '', $base );
                    $plugin_alias_key = preg_replace( '/(directorist-)/', '', $base_alias );
                    
                    $is_in_installed_extensions = in_array( $plugin_key, $installed_extensions_keys ) ? true : false;
                    $is_in_installed_extensions_alias = in_array( $plugin_alias_key, $installed_extensions_keys ) ? true : false;

                    if ( $is_in_installed_extensions || $is_in_installed_extensions_alias ) {
                        unset( $extensions_available_in_subscriptions[ $base ] );
                    }
                }
            }
            
            return $extensions_available_in_subscriptions;
        }

        // get_extensions_promo_list
        public function get_extensions_promo_list( array $args = [] ) {
            $installed_extensions = ( ! empty( $args[ 'installed_extensions' ] ) ) ? $args[ 'installed_extensions' ] : [];
            $installed_extensions_keys = $this->get_sanitized_extensions_keys( $installed_extensions );

            $extensions_available_in_subscriptions = ( ! empty( $args['extensions_available_in_subscriptions'] ) ) ? $args['extensions_available_in_subscriptions'] : [];
            $extensions_available_in_subscriptions_keys = is_array( $extensions_available_in_subscriptions ) ? array_keys( $extensions_available_in_subscriptions ) : [];

            // Filter extensions available in subscriptions
            $promo_extensions = $this->get_active_extensions();
            if ( ! empty( $promo_extensions ) && is_array( $installed_extensions_keys ) ) {
                foreach ( $promo_extensions as $_extension_base => $_extension_args ) {
                    $extension_base_alias = $this->get_extension_alias_key( $_extension_base );
                    $ext_key              = preg_replace( '/(directorist-)/', '', $_extension_base );
                    $ext_alias_key        = preg_replace( '/(directorist-)/', '', $extension_base_alias );

                    // Exclude Installed Extensions
                    $in_installed_extensions       = in_array( $ext_key, $installed_extensions_keys ) ? true : false;
                    $in_installed_extensions_alias = in_array( $ext_alias_key, $installed_extensions_keys ) ? true : false;

                    if ( $in_installed_extensions || $in_installed_extensions_alias ) {
                        unset( $promo_extensions[ $_extension_base ] );
                    } 

                    // Exclude Subscripted Extensions
                    $is_available_in_subscriptions       = in_array( $_extension_base, $extensions_available_in_subscriptions_keys ) ? true : false;
                    $is_available_in_subscriptions_alias = in_array( $extension_base_alias, $extensions_available_in_subscriptions_keys ) ? true : false;
                    
                    if ( $is_available_in_subscriptions || $is_available_in_subscriptions_alias ) {
                        unset( $promo_extensions[ $_extension_base ] );
                    } 
                }
            }

            return $promo_extensions;
        }

        // get_sanitized_extensions_keys
        public function get_sanitized_extensions_keys( array $extensions_list = [] ) {
            $extensions_keys = ( is_array( $extensions_list ) ) ? array_keys($extensions_list  ) : [];

            if ( ! empty( $extensions_keys ) && is_array( $extensions_keys ) ) {
                foreach( $extensions_keys as $index => $key) {
                    $new_key = preg_replace( '/\/.+/', '', $key );
                    $new_key = preg_replace( '/(directorist-)/', '', $new_key );

                    $extensions_keys[ $index ] = $new_key;
                }
            }

            return $extensions_keys;
        }

        // get_themes_overview
        public function get_themes_overview() {
            $sovware_themes       = ( is_array( $this->themes ) ) ? array_keys( $this->themes ) : [];
            $theme_updates        = get_site_transient( 'update_themes' );
            $outdated_themes      = $theme_updates->response;
            $outdated_themes_keys = ( is_array( $outdated_themes ) ) ? array_keys( $outdated_themes ) : [];

            $all_themes            = wp_get_themes();
            $active_theme_slug     = get_option('stylesheet');
            $installed_theme_list  = [];
            $total_active_themes   = 0;
            $total_outdated_themes = 0; 

            foreach ( $all_themes as $theme_base => $theme_data ) {
                if ( in_array( $theme_base, $sovware_themes ) ) {
                    $customizer_link = "customize.php?theme={$theme_data->stylesheet}&return=%2Fwp-admin%2Fthemes.php";
                    $customizer_link = admin_url( $customizer_link );

                    $installed_theme_list[ $theme_base ] = [
                        'name'            => $theme_data->name,
                        'version'         => $theme_data->version,
                        'thumbnail'       => $theme_data->get_screenshot(),
                        'customizer_link' => $customizer_link,
                        'has_update'      => ( in_array( $theme_data->stylesheet, $outdated_themes_keys ) ) ? true : false,
                        'stylesheet'      => $theme_data->stylesheet,
                    ];

                    if ( $active_theme_slug === $theme_base ) {
                        $total_active_themes++;
                    }

                    if ( in_array( $theme_base, $outdated_themes_keys ) ) {
                        $total_outdated_themes++;
                    }
                }
            }
            
            $installed_themes_keys = ( is_array( $installed_theme_list ) ) ? array_keys( $installed_theme_list ) : [];
        
            // Themes available in subscriptions
            $themes_available_in_subscriptions = get_user_meta( get_current_user_id(), '_themes_available_in_subscriptions', true );
            $themes_available_in_subscriptions = ( ! empty( $themes_available_in_subscriptions ) && is_array( $themes_available_in_subscriptions ) ) ? $themes_available_in_subscriptions : [];

            if ( ! empty( $themes_available_in_subscriptions ) ) {
                foreach( $themes_available_in_subscriptions as $base => $args ) {
                    $item = $themes_available_in_subscriptions[ $base ];

                    // Merge Local Theme Info
                    if ( ! empty( $this->themes[ $base ] ) ) {
                        $item = array_merge( $this->themes[ $base ], $item );
                    }

                    // Merge Local Theme Info
                    if ( in_array( $base, $installed_themes_keys ) ) {
                        $item = array_merge( $installed_theme_list[ $base ], $item );
                    }

                    $is_installed = ( in_array( $base, $installed_themes_keys ) ) ? true : false;
                    $item[ 'is_installed' ] = $is_installed;
                    
                    $themes_available_in_subscriptions[ $base ] = $item;
                }
            }

            // total_available_themes
            $total_available_themes = count( $themes_available_in_subscriptions );
            
            // themes_promo_list
            $themes_promo_list = $this->get_themes_promo_list([
                'installed_theme_list' => $installed_theme_list,
                'themes_available_in_subscriptions' => $themes_available_in_subscriptions,
            ]);

            // current_active_theme_info
            $current_active_theme_info = $this->get_current_active_theme_info( [ 'outdated_themes_keys' => $outdated_themes_keys ] );
            $current_active_theme_info['stylesheet'];

            $themes_available_in_subscriptions_keys = array_keys( $themes_available_in_subscriptions );

            if ( in_array( $current_active_theme_info['stylesheet'], $themes_available_in_subscriptions_keys ) ) {
                unset( $themes_available_in_subscriptions[ $current_active_theme_info['stylesheet'] ] );
            }

            $overview = [
                'total_active_themes'               => $total_active_themes,
                'total_outdated_themes'             => $total_outdated_themes,
                'installed_theme_list'              => $installed_theme_list,
                'currrent_active_theme_info'        => $current_active_theme_info,
                'themes_promo_list'                 => $themes_promo_list,
                'themes_available_in_subscriptions' => $themes_available_in_subscriptions,
                'total_available_themes'            => $total_available_themes,
            ];


            return $overview;
        }

        // get_current_active_theme_info
        public function get_current_active_theme_info( array $args = [] ) {
            $outdated_themes_keys = ( ! empty( $args['outdated_themes_keys'] ) ) ? $args['outdated_themes_keys'] : [];

            // Get Current Active Theme Info
            $current_active_theme = wp_get_theme();
            $customizer_link = "customize.php?theme={$current_active_theme->stylesheet}&return=%2Fwp-admin%2Fthemes.php";
            $customizer_link = admin_url( $customizer_link );

            $active_theme_info = [
                'name'            => $current_active_theme->name,
                'version'         => $current_active_theme->version,
                'thumbnail'       => $current_active_theme->get_screenshot(),
                'customizer_link' => $customizer_link,
                'has_update'      => ( in_array( $current_active_theme->stylesheet, $outdated_themes_keys ) ) ? true : false,
                'stylesheet'      => $current_active_theme->stylesheet,
            ];

            return $active_theme_info;
        }


        // get_themes_promo_list
        public function get_themes_promo_list( array $args = [] ) {
            $installed_theme_list = ( ! empty( $args[ 'installed_theme_list' ] ) ) ? $args[ 'installed_theme_list' ] : [];
            $installed_themes_keys = $this->get_sanitized_themes_keys( $installed_theme_list );

            $themes_available_in_subscriptions = ( ! empty( $args['themes_available_in_subscriptions'] ) ) ? $args['themes_available_in_subscriptions'] : [];
            $themes_available_in_subscriptions_keys = is_array( $themes_available_in_subscriptions ) ? array_keys( $themes_available_in_subscriptions ) : [];

            // Filter all active themes
            $themes_promo_list = $this->get_active_themes();
            if ( ! empty( $themes_promo_list ) ) {
                foreach ( $themes_promo_list as $_theme_base => $_extension_args ) {

                    // Exclude Installed Themes
                    if ( in_array( $_theme_base, $installed_themes_keys ) ) {
                        unset( $themes_promo_list[ $_theme_base ] );
                    }

                    // Exclude Subscripted Themes
                    if ( in_array( $_theme_base, $themes_available_in_subscriptions_keys ) ) {
                        unset( $themes_promo_list[ $_theme_base ] );
                    }
                }
            }

            return $themes_promo_list;
        }

        // get_sanitized_themes_keys
        public function get_sanitized_themes_keys( array $theme_list = [] ) {
            $theme_keys = ( is_array( $theme_list ) ) ? array_keys($theme_list  ) : [];
            return $theme_keys;
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

            $settings_url = admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-settings#extension_settings__extensions_general' );

            $extensions_overview      = $this->get_extensions_overview();
            $themes_overview          = $this->get_themes_overview();

            $hard_logout = apply_filters( 'atbdp_subscriptions_hard_logout', false );
            $hard_logout = ( $hard_logout ) ? 1 : 0;
            
            $data = [
                'ATBDP_Extensions' => $this,
                'is_logged_in' => $is_logged_in,
                'hard_logout'  => $hard_logout,

                'total_active_extensions'               => $extensions_overview['total_active_extensions'],
                'total_outdated_extensions'             => $extensions_overview['total_outdated_extensions'],
                'outdated_plugin_list'                  => $extensions_overview['outdated_plugin_list'],
                'installed_extension_list'              => $extensions_overview['installed_extension_list'],
                'extensions_available_in_subscriptions' => $extensions_overview['extensions_available_in_subscriptions'],
                'total_available_extensions'            => $extensions_overview['total_available_extensions'],
                'extensions_promo_list'                 => $extensions_overview['extensions_promo_list'],
                'required_extensions_list'              => $extensions_overview['required_extensions'],
                
                'total_active_themes'               => $themes_overview['total_active_themes'],                 // $my_active_themes,
                'total_outdated_themes'             => $themes_overview['total_outdated_themes'],               // $my_outdated_themes,
                'installed_theme_list'              => $themes_overview['installed_theme_list'],                // $installed_theme_list,
                'currrent_active_theme_info'        => $themes_overview['currrent_active_theme_info'],          // $active_theme,
                'themes_available_in_subscriptions' => $themes_overview['themes_available_in_subscriptions'],   // $themes_available_in_subscriptions,
                'total_available_themes'            => $themes_overview['total_available_themes'],
                'themes_promo_list'                 => $themes_overview['themes_promo_list'],
                
                'extension_list' => $this->extensions,
                'theme_list'     => $this->themes,
                
                'settings_url'          => $settings_url,
            ];

            ATBDP()->load_template('admin-templates/theme-extensions/theme-extension', $data );
        }
    }


}