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

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Direct access is not allowed.' );
}

if ( ! is_admin() ) {
    return;
}

use Directorist\Core\API;

if ( ! class_exists( 'ATBDP_Extensions' ) ) {

    /**
     * Class ATBDP_Extensions
     */
    class ATBDP_Extensions {
        public static $extensions_aliases = [];

        public static $load_from_api = false;

        public $extensions          = [];

        public $themes              = [];

        public $required_extensions = [];

        public function __construct() {
            add_action( 'admin_menu', [ $this, 'admin_menu' ], 100 );
            add_action( 'admin_init', [ $this, 'setup_ajax_actions' ] );
            add_action( 'admin_head', [ $this, 'add_menu_separator_classes' ] );
            add_filter( 'submenu_file', [ $this, 'set_active_submenu' ], 10, 2 );

            if ( ! empty( $_GET['page'] ) && ( 'atbdp-extension' === $_GET['page'] ) ) {
                add_action( 'admin_init', [ $this, 'initial_setup' ] );
            }
        }

        public function setup_ajax_actions() {
            if ( ! current_user_can( 'manage_options' ) ) {
                return;
            }

            // Ajax
            add_action( 'wp_ajax_atbdp_authenticate_the_customer', [ $this, 'authenticate_the_customer' ] );
            add_action( 'wp_ajax_atbdp_download_file', [ $this, 'handle_file_download_request' ] );
            add_action( 'wp_ajax_atbdp_install_file_from_subscriptions', [ $this, 'handle_file_install_request_from_subscriptions' ] );
            add_action( 'wp_ajax_atbdp_plugins_bulk_action', [ $this, 'plugins_bulk_action' ] );
            add_action( 'wp_ajax_atbdp_activate_theme', [ $this, 'activate_theme' ] );
            add_action( 'wp_ajax_atbdp_activate_plugin', [ $this, 'activate_plugin' ] );
            add_action( 'wp_ajax_atbdp_update_plugins', [ $this, 'handle_plugins_update_request' ] );
            add_action( 'wp_ajax_atbdp_update_theme', [ $this, 'handle_theme_update_request' ] );
            add_action( 'wp_ajax_atbdp_refresh_purchase_status', [ $this, 'handle_refresh_purchase_status_request' ] );
            add_action( 'wp_ajax_atbdp_close_subscriptions_sassion', [ $this, 'handle_close_subscriptions_sassion_request' ] );
            add_action( 'wp_ajax_directorist_te_get_activity', [ $this, 'get_dashboard_activity' ] );

            // add_action( 'wp_ajax_atbdp_download_purchased_items', array($this, 'download_purchased_items') );
        }

        // initial_setup
        public function initial_setup() {
            $this->setup_extensions_alias();

            wp_update_plugins();

            // Apply hook to required extensions
            $this->required_extensions = apply_filters( 'directorist_required_extensions', [] );

            $this->setup_products_list();
        }

        // setup_extensions_alias
        public function setup_extensions_alias() {

            // Latest Key     => Deprecated key
            // Deprecated key => Latest Key
            self::$extensions_aliases = apply_filters(
                'directorist_extensions_aliases',
                [
                    'directorist-listings-with-map'        => 'directorist-listings-map',
                    'directorist-listings-map'             => 'directorist-listings-with-map',

                    'directorist-adverts-manager'          => 'directorist-ads-manager',
                    'directorist-ads-manager'              => 'directorist-adverts-manager',

                    'directorist-gallery'                  => 'directorist-image-gallery',
                    'directorist-image-gallery'            => 'directorist-gallery',

                    'directorist-slider-carousel'          => 'directorist-listings-slider-carousel',
                    'directorist-listings-slider-carousel' => 'directorist-slider-carousel',

                    'directorist-faqs'                     => 'directorist-listing-faqs',
                    'directorist-listing-faqs'             => 'directorist-faqs',

                    'directorist-mailchimp'                => 'directorist-mailchimp-integration',
                    'directorist-mailchimp-integration'    => 'directorist-mailchimp',
                ]
            );
        }

        // get_required_extension_list
        public function get_required_extension_list() {
            $required_extensions = [];

            foreach ( $this->required_extensions as $recommandation ) {

                if ( ! isset( $recommandation['extensions'] ) ) {
                    continue;
                }

                if ( ! is_array( $recommandation['extensions'] ) ) {
                    continue;
                }

                foreach ( $recommandation['extensions'] as $extension ) {
                    $extension_alias = $this->get_extension_alias_key( $extension );

                    if ( ! ( isset( $this->extensions[ $extension ] ) || isset( $this->extensions[ $extension_alias ] ) ) ) {
                        continue;
                    }

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

            $required_extensions_list = $this->get_required_extension_list();
            $purchased_extension_list = self::get_purchased_extension_list();
            $purchased_extensions     = ( ! empty( $purchased_extension_list ) && is_array( $purchased_extension_list ) ) ? array_keys( $purchased_extension_list ) : [];
            $plugin_dir_path          = trailingslashit( dirname( ATBDP_DIR ) );

            foreach ( $required_extensions_list as $extension => $recommanded_by ) {
                $extension_alias = $this->get_extension_alias_key( $extension );

                if ( $this->has_match_in_active_plugins( [ $extension, $extension_alias ] ) ) {
                    continue;
                }

                $is_purchased       = ( in_array( $extension, $purchased_extensions ) ) ? true : false;
                $is_purchased_alias = ( in_array( $extension_alias, $purchased_extensions ) ) ? true : false;

                $is_installed = file_exists( $plugin_dir_path . $extension );
                $is_installed_alias = ( ! empty( $extension_alias ) && file_exists( $plugin_dir_path . $extension_alias ) ) ? true : false;

                $base = "{$extension}/{$extension}.php";

                if ( ! empty( $this->extensions[ $extension ] ) && ! empty( $this->extensions[ $extension ]['base'] ) ) {
                    $base = $this->extensions[ $extension ]['base'];
                }

                if ( ! empty( $this->extensions[ $extension_alias ] ) && ! empty( $this->extensions[ $extension_alias ]['base'] ) ) {
                    $base = $this->extensions[ $extension_alias ]['base'];
                }

                $recommandation[ $extension ]              = [];
                $recommandation[ $extension ]['ref']       = $recommanded_by;
                $recommandation[ $extension ]['base']      = $base;
                $recommandation[ $extension ]['purchased'] = ( $is_purchased || $is_purchased_alias ) ? true : false;
                $recommandation[ $extension ]['installed'] = ( $is_installed || $is_installed_alias ) ? true : false;
            }

            return $recommandation;
        }

        public function has_match_in_active_plugins( $plugin_name = '' ) {
            $match_found = false;

            $active_plugins = get_option( 'active_plugins', [] );

            if ( empty( $plugin_name ) ) {
                return false;
            }

            if ( empty( $active_plugins ) ) {
                return false;
            }

            if ( ! is_array( $active_plugins ) ) {
                return false;
            }

            foreach ( $active_plugins as $plugin_path ) {
                if ( empty( $plugin_name ) && ( false !== strpos( $plugin_path, $plugin_name ) ) ) {
                    return true;
                }

                if ( is_array( $plugin_name ) ) {
                    foreach ( $plugin_name as $plugin_key ) {
                        if ( is_string( $plugin_key ) && ! empty( $plugin_key ) && false !== strpos( $plugin_path, $plugin_key ) ) {
                            return true;
                        }
                    }
                }
            }

            return $match_found;
        }

        // get_the_products_list
        public function setup_products_list() {
            if ( static::$load_from_api ) {
                // Fetch products from the API
                $products = API::get_products();

                // Apply filters for extensions and themes
                $this->extensions = apply_filters( 'atbdp_extension_list', $products['extensions'] ?? [] );
                $this->themes     = apply_filters( 'atbdp_theme_list', $products['themes'] ?? [] );

                // Fall back to local data if API returned nothing
                $this->extensions = empty( $this->extensions ) ? static::get_default_extensions() : $this->extensions;
                $this->themes     = empty( $this->themes ) ? static::get_default_themes() : $this->themes;
            } else {
                $this->extensions = apply_filters( 'atbdp_extension_list', static::get_default_extensions() );
                $this->themes     = apply_filters( 'atbdp_theme_list', static::get_default_themes() );
            }
        }

        private static function get_product_badge( $type, $label, $expires_at = null ) {
            $badge = [
                'type'  => $type,
                'label' => $label,
            ];

            if ( null !== $expires_at ) {
                $badge['expires_at'] = $expires_at;
            }

            return $badge;
        }

        public static function get_default_extensions() {
            return [
                'directorist-notifications-pro' => [
                    'name'        => 'Directorist Notifications Pro',
                    'description' => __( 'Send instant browser push notifications for listings, payments, reviews, renewals, and other important directory events.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-notifications-pro/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/directorist-notifications-pro.jpg',
                    'active'      => true,
                    'item_id'     => 371698,
                    'badges'      => [ self::get_product_badge( 'new', __( 'New', 'directorist' ) ) ],
                ],
                'directorist-divi-integration' => [
                    'name'        => 'Directorist Divi Integration',
                    'description' => __( 'Build and customize your directory visually with native Divi 5 modules.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-divi-integration/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/directorist-divi-integration.jpg',
                    'active'      => true,
                    'item_id'     => 371246,
                    'badges'      => [ self::get_product_badge( 'new', __( 'New', 'directorist' ) ) ],
                ],
                'directorist-ai-search' => [
                    'name'        => 'Directorist AI Search',
                    'description' => __( 'AI-powered directory search that understands intent and improves listing discovery.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-ai-search/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/AI-Search-Preview.jpg',
                    'active'      => true,
                    'item_id'     => 370908,
                    'badges'      => [ self::get_product_badge( 'new', __( 'New', 'directorist' ) ) ],
                ],
                'directorist-listing-importer' => [
                    'name'        => 'Directorist Listing Importer',
                    'description' => __( 'Import Google Maps and feeds into Directorist automatically, effortlessly.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-listing-importer/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/directorist-listing-importer.png',
                    'active'      => true,
                    'item_id'     => 370853,
                    'badges'      => [
                        self::get_product_badge( 'new', __( 'New', 'directorist' ) ),
                        self::get_product_badge( 'trending', __( 'Trending', 'directorist' ) ),
                    ],
                ],

                'directorist-analytics' => [
                    'name'        => 'Directorist Analytics',
                    'description' => __( 'Unlock powerful insights to grow your directory with confidence.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-analytics/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/directorist-analytics.jpg',
                    'active'      => true,
                    'item_id'     => 369611,
                ],

                'directorist-advanced-review' => [
                    'name'        => 'Directorist Advance Review',
                    'description' => __( 'Detailed, criteria-based review to make listings more trustworthy.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-advanced-review/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/directorist-advanced-review.jpg',
                    'active'      => true,
                    'item_id'     => 366908,
                ],

                'directorist-universal-search' => [
                    'name'        => 'Directorist Universal Search',
                    'description' => __( 'Unified Search Across All Your Directories – Instantly Find What You Need', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-universal-search/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/directorist-universal-search.jpg',
                    'active'      => true,
                    'item_id'     => 340478,
                ],

                'directorist-search-alert' => [
                    'name'        => 'Directorist Search Alert',
                    'description' => __( 'The Search Alert Plugin lets users create, manage, and receive alerts for matches.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-search-alert/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/directorist-search-alert.png',
                    'active'      => true,
                    'item_id'     => 323908,
                    'badges'      => [ self::get_product_badge( 'new', __( 'New', 'directorist' ) ) ],
                ],

                'directorist-announcement' => [
                    'name'        => 'Directorist Announcement',
                    'description' => __( 'Effortlessly share updates, news, or promotions with the Directorist Announcement Extension.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-announcement/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/directorist-announcement.svg',
                    'active'      => true,
                    'item_id'     => 308031,
                    'badges'      => [ self::get_product_badge( 'new', __( 'New', 'directorist' ) ) ],
                ],

                'addonskit-for-bricks' => [
                    'name'        => 'AddonsKit for Bricks',
                    'description' => __( 'Enhance directory sites with AddonsKit for Bricks Builder with drag-and-drop custom elements, interactive maps, and more.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/addonskit-for-bricks/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/addonskit-bricks.svg',
                    'active'      => true,
                    'item_id'     => 307581,
                    'badges'      => [ self::get_product_badge( 'new', __( 'New', 'directorist' ) ) ],
                ],

                'directorist-coupon' => [
                    'name'        => 'Coupon',
                    'description' => __( 'It lets you offer discounts to users when purchasing listing plans or paying for featured listings.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-coupon/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/coupon.png',
                    'active'      => true,
                    'item_id'     => 32345,
                ],
                'directorist-compare-listing' => [
                    'name'        => 'Compare Listings',
                    'description' => __( 'Compare Listings extension allows users to add a set of listings in a list and compare its features by viewing in a comparison table.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-compare-listing/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/compare-listings.png',
                    'active'      => true,
                    'item_id'     => 26378,
                ],
                'directorist-listings-with-map' => [
                    'name'        => 'Listings With Map',
                    'description' => __( 'Show your listings with the interactive maps and make your business visible comprehensively. This awesome extension will make your website the brand recognition it deserves.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-listings-with-map/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/listings-with-map.png',
                    'base'        => 'directorist-listings-with-map/directorist-listings-map.php',
                    'active'      => true,
                    'item_id'     => 13794,
                    'badges'      => [ self::get_product_badge( 'popular', __( 'Popular', 'directorist' ) ) ],
                ],
                'directorist-pricing-plans' => [
                    'name'        => 'Pricing Plans',
                    'description' => __( 'Do you have a growing directory site? Do you want to make money with your site very easily? Start generating a handsome amount of revenue from your directory site with Directorist Pricing Plans today.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-pricing-plans/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/pricing-plans.png',
                    'active'      => true,
                    'item_id'     => 13776,
                    'badges'      => [
                        self::get_product_badge( 'popular', __( 'Popular', 'directorist' ) ),
                        self::get_product_badge( 'trending', __( 'Trending', 'directorist' ) ),
                    ],
                ],
                'directorist-woocommerce-pricing-plans' => [
                    'name'        => 'WooCommerce Pricing Plans',
                    'description' => __( 'Do you have a growing directory site? Do you want to make money with your site by integrating your favorite WooCommerce payment gateway? Start generating a handsome amount of revenue from your directory site with Directorist WooCommerce Pricing Plans today.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-woocommerce-pricing-plans/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/woo-pricing-plans.png',
                    'active'      => true,
                    'item_id'     => 13784,
                    'badges'      => [ self::get_product_badge( 'popular', __( 'Popular', 'directorist' ) ) ],
                ],
                'directorist-paypal' => [
                    'name'        => 'PayPal Payment Gateway',
                    'description' => __( 'Do you want to boost your income on your business directory site? Are you looking for a robust payment gateway with worldwide acceptance? If you are, then Directorist PayPal Payment Gateway is the perfect fit for you.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-paypal/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/paypal-gateway.png',
                    'active'      => true,
                    'item_id'     => 13702,
                    'badges'      => [ self::get_product_badge( 'popular', __( 'Popular', 'directorist' ) ) ],
                ],
                'directorist-stripe' => [
                    'name'        => 'Stripe Payment Gateway',
                    'description' => __( 'Are you looking for a versatile Directorist payment gateway for your business directory that accepts a great number of currencies? If yes, then Directorist Stripe Payment Gateway is the smartest way to go', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-stripe/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/stripe-gateway.png',
                    'active'      => true,
                    'item_id'     => 13700,
                ],
                'directorist-claim-listing' => [
                    'name'        => 'Claim Listing',
                    'description' => __( 'Let business owners maintain tons of listings by claiming them and monetize your directory listing website with instant revenue.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-claim-listing/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/claim-listing.png',
                    'active'      => true,
                    'item_id'     => 13786,
                ],
                'directorist-mark-as-sold' => [
                    'name'        => 'Mark as Sold',
                    'description' => __( 'Mark as sold is a dynamic extension that provides listing authors the opportunity to show visitors if a particular item is sold or not.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-mark-as-sold/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/mark-as-sold.png',
                    'active'      => true,
                    'item_id'     => 20204,
                ],
                'directorist-social-login' => [
                    'name'        => 'Social Login',
                    'description' => __( 'Use Directorist Social Login to accelerate the registration process by offering a single-click login option using Facebook or Google profile.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-social-login/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/social-login.png',
                    'active'      => true,
                    'item_id'     => 13795,
                ],
                'directorist-google-recaptcha' => [
                    'name'        => 'Google reCAPTCHA',
                    'description' => __( 'Use reCAPTCHA service from Google to help your directory site protect from spam and further abuse. This Google reCAPTCHA extension allows you to make it happen by taking care of your site.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-google-recaptcha/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/recaptcha.png',
                    'active'      => true,
                    'item_id'     => 13768,
                ],
                'directorist-faqs' => [
                    'name'        => 'Listing FAQs',
                    'description' => __( 'Use an organized FAQ page on your directory website and provide quick information to help customers make a potential decision. Here, the idea is to keep the answers short and direct so that people find info quickly.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-faqs/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/listing-faqs.png',
                    'active'      => true,
                    'item_id'     => 13780,
                ],
                'directorist-business-hours' => [
                    'name'        => 'Business Hours',
                    'description' => __( 'Inform your customers about your business hours in the best way possible especially when your businesses are opened and when they are closed', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-business-hours/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/business-hours.png',
                    'base'        => 'directorist-business-hours/bd-business-hour.php',
                    'active'      => true,
                    'item_id'     => 13714,
                    'badges'      => [ self::get_product_badge( 'popular', __( 'Popular', 'directorist' ) ) ],
                ],
                'directorist-slider-carousel' => [
                    'name'        => 'Listings Slider & Carousel',
                    'description' => __( 'Increase the beauty of your directory website by displaying numerous listings through attractive sliders or carousels with this highly customizable extension.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-slider-carousel/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/listings-slider.png',
                    'base'        => 'directorist-slider-carousel/bd-directorist-slider.php',
                    'active'      => true,
                    'item_id'     => 13774
                ],
                'directorist-live-chat' => [
                    'name'        => 'Live Chat',
                    'description' => __( 'Live Chat is an extension that allows the visitors to contact business owners immediately and easily. It makes the business more credible as customer satisfaction increases notably.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-live-chat/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/live-chats.png',
                    'active'      => true,
                    'item_id'     => 21274
                ],
                'directorist-booking' => [
                    'name'        => 'Booking (Reservation & Appointment)',
                    'description' => __( 'This extension comes with all the solutions you need to set up a dynamic booking and reservation system on your directory website.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-booking/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/booking.png',
                    'active'      => true,
                    'item_id'     => 21718,
                    'badges'      => [ self::get_product_badge( 'popular', __( 'Popular', 'directorist' ) ) ],
                ],
                'directorist-gallery' => [
                    'name'        => 'Image Gallery',
                    'description' => __( 'Use a quality image gallery and increase conversation by reducing your return rate on your directory listing website.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-gallery/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/image-gallery.png',
                    'base'        => 'directorist-gallery/bd-directorist-gallery.php',
                    'active'      => true,
                    'item_id'     => 13778,
                ],
                'directorist-adverts-manager' => [
                    'name'        => 'Directorist Ads Manager',
                    'description' => __( 'Are you wondering about placing advertisements in your directory? Directorist Ads Manager allows you to insert advertisements on specific Directorist pages such as All listings, Single Listings, All Location, All Category, etc.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-adverts-manager/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/single-ad-manager.png',
                    'active'      => true,
                    'item_id'     => 32342,
                ],
                'directorist-buddyboss-integration' => [
                    'name'        => 'BuddyBoss Integration',
                    'description' => __( 'Directorist - BuddyBoss Integration extension is used to integrate the giant Directorist with the popular BuddyBoss plugin. It combines all the functionalities needed to create a complete community based WordPress directory website using Directorist plugin and BuddyBoss platform', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-buddyboss-integration/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/buddyboss.png',
                    'active'      => true,
                    'item_id'     => 60945,
                ],
                'directorist-oxygen-integration' => [
                    'name'        => 'Directorist Oxygen',
                    'description' => __( 'Directorist Oxygen is used to integrate the giant Directorist with the popular Oxygen Page Builder plugin. It combines all the functionalities needed to create a complete WordPress directory website using Oxygen Builder.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-oxygen-integration/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/oxygen-builder.png',
                    'active'      => true,
                    'item_id'     => 56997,
                ],
                'directorist-authorize-net' => [
                    'name'        => 'Authorize.net Payment Gateway',
                    'description' => __( 'Directorist Authorize Payment Gateway is a secured payment solution that accepts a great number of payment options for Directorist Pricing Plan like Visa, MasterCard, Discover, AmEx, JCB, PayPal, and more.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-authorize-net/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/authorize-net.png',
                    'active'      => true,
                    'item_id'     => 52499,
                ],
                'directorist-buddypress-integration' => [
                    'name'        => 'BuddyPress Integration',
                    'description' => __( 'Directorist - BuddyPress Integration is a premium extension which makes Direcorist and BuddyPress work as a single integrated app, allowing you to build a hybrid listings directory and social network together.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-buddypress-integration/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/buddypress.svg',
                    'active'      => true,
                    'item_id'     => 62897,
                ],
                'directorist-directory-linking' => [
                    'name'        => 'Multi Directory Linking',
                    'description' => __( 'If you are running multi-directories on your directory website, Multi-directory Linking will be an awesome extension that will allow your users to connect to other types of directories. This opens up a new window to earn money from your directory website.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-directory-linking/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/type-linking.svg',
                    'active'      => true,
                    'item_id'     => 70261,
                ],
                'directorist-job-manager' => [
                    'name'        => 'Job Manager',
                    'description' => __( 'If you\'re wondering how to place job listings with detailed specifications, then Directorist-Job Manager gets you rid out of this problem', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-job-manager/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/jobs-manager.svg',
                    'active'      => true,
                    'item_id'     => 134332,
                    'badges'      => [ self::get_product_badge( 'trending', __( 'Trending', 'directorist' ) ) ],
                ],
                'directorist-mailchimp-integration' => [
                    'name'        => 'Mailchimp Integration',
                    'description' => __( 'Directorist Mailchimp Integration Connects Directorist with Mailchimp. It helps you to make your directory business grow faster and smarter with more leads.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-mailchimp/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/27_Mailchimp.svg',
                    'active'      => true,
                    'item_id'     => 76269,
                ],
                'directorist-helpgent-integration' => [
                    'name'        => 'HelpGent Integration',
                    'description' => __( 'Directorist HelpGent Integration puts Directorist and HelpGent on the same avenue. The integration helps you to make your directory website more accessible to your audience which eventually creates more leads and conversions', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-helpgent-integration/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/helpgent.svg',
                    'active'      => true,
                    'item_id'     => 188735,
                    'badges'      => [ self::get_product_badge( 'new', __( 'New', 'directorist' ) ) ],
                ],
                'directorist-wpml-integration' => [
                    'name'        => 'WPML Integration',
                    'description' => __( 'Directorist WPML Integration connects Directorist and WPML in one place. It helps you to make your directory sites multilingual more conveniently & efficiently by switching your directory website from one language to another.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-wpml-integration/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/wpml.svg',
                    'active'      => true,
                    'item_id'     => 104564,
                ],
                'directorist-digital-marketplace' => [
                    'name'        => 'Digital Marketplace',
                    'description' => __( 'If you want to create a marketplace of fixed-price services or digital downloads, then the Digital Marketplace Extension will be a worth-investment to kickstart.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-digital-marketplace/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/marketplace.svg',
                    'active'      => true,
                    'item_id'     => 148417,
                    'badges'      => [ self::get_product_badge( 'trending', __( 'Trending', 'directorist' ) ) ],
                ],
                'directorist-gamipress-integration' => [
                    'name'        => 'Gamipress Integration',
                    'description' => __( 'Directorist GamiPress Integration Connects Directorist with GamiPress in one place. It helps you to aggrandize the engagement of your directory business with the utmost possible ease.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/directorist-gamipress-integration/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/extensions/gamipress.svg',
                    'active'      => true,
                    'item_id'     => 102370,
                ],

            ];
        }

        public static function get_default_themes() {
            return [
                'djobs' => [
                    'name'        => 'dJobs',
                    'description' => __( 'dJobs is a beautiful WordPress directory theme for jobs, employment, and other job-related businesses.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/djobs/',
                    'demo_link'   => 'https://demo.directorist.com/theme/djobs/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/themes/djobs.png',
                    'active'      => true,
                    'badges'      => [ self::get_product_badge( 'new', __( 'New', 'directorist' ) ) ],
                ],
                'dhotels' => [
                    'name'        => 'dHotels',
                    'description' => __( 'dHotels is a beautiful WordPress directory theme for hotels, motels, resorts, and other hospitality-related businesses.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/dhotels/',
                    'demo_link'   => 'https://demo.directorist.com/theme/dhotels/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/themes/dhotels.png',
                    'active'      => true,
                    'badges'      => [ self::get_product_badge( 'popular', __( 'Popular', 'directorist' ) ) ],
                ],
                'dclassified' => [
                    'name'        => 'dClassified',
                    'description' => __( 'dClassified is a beautiful WordPress directory theme for classifieds, ads, and other classified-related businesses.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/dclassified/',
                    'demo_link'   => 'https://demo.directorist.com/theme/dclassified/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/themes/dclassified.png',
                    'active'      => true,
                    'badges'      => [ self::get_product_badge( 'trending', __( 'Trending', 'directorist' ) ) ],
                ],
                'onelisting' => [
                    'name'        => 'OneListing',
                    'description' => __( 'Onelisting is a beautiful WordPress directory theme for cars, motorcycles, and other vehicle-related businesses.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/onelisting/',
                    'demo_link'   => 'https://demo.directorist.com/theme/onelisting/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/themes/onelisting-free.png',
                    'active'      => true,
                ],
                'onelisting-pro' => [
                    'name'        => 'OneListing Pro',
                    'description' => __( 'Onelisting Pro is a beautiful WordPress directory theme for doctor, nurse, medical techonologist, hospital, clinic, and other medical-related businesses.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/onelisting-pro/',
                    'demo_link'   => 'https://demo.directorist.com/theme/onelisting-pro/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/themes/onelisting.png',
                    'active'      => true,
                    'badges'      => [
                        self::get_product_badge( 'popular', __( 'Popular', 'directorist' ) ),
                        self::get_product_badge( 'trending', __( 'Trending', 'directorist' ) ),
                    ],
                ],
                'dplace' => [
                    'name'        => 'dPlace',
                    'description' => __( 'dPlace theme is tailored to meet all the nitty gritties to build attractive mobile responsive travel agency directory websites. As a full-fledged theme, it will allow you to create travel & tour directories with booking and reservation features.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/dplace/',
                    'demo_link'   => 'https://demo.directorist.com/theme/dplace/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/themes/dplace.jpg',
                    'active'      => true,
                ],
                'drestaurant' => [
                    'name'        => 'dRestaurant',
                    'description' => __( 'Are you looking for the best restaurant directory theme that brings you more business? Then, nothing can beat dRestaurant, as it is the most powerful theme that checks all your visual needs with the concept of scalability in mind.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/drestaurant/',
                    'demo_link'   => 'https://demo.directorist.com/theme/drestaurant/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/themes/drestaurant.png',
                    'active'      => true,
                ],
                'drealestate' => [
                    'name'        => 'dRealEstate',
                    'description' => __( 'dRealEstate is a beautiful WordPress directory theme for real estate, property, and other real estate-related businesses.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/drealestate/',
                    'demo_link'   => 'https://demo.directorist.com/theme/drealestate/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/themes/drealestate.png',
                    'active'      => true,
                ],
                'dcar' => [
                    'name'        => 'dCar',
                    'description' => __( 'dCar is a beautiful WordPress directory theme for cars, motorcycles, and other vehicle-related businesses.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/dcar/',
                    'demo_link'   => 'https://demo.directorist.com/theme/dcar/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/themes/dcar.png',
                    'active'      => true,
                    'badges'      => [ self::get_product_badge( 'popular', __( 'Popular', 'directorist' ) ) ],
                ],
                'dlist' => [
                    'name'        => 'dList',
                    'description' => __( 'DList is a listing directory WordPress theme that provides immense opportunities to build any kind of directory or listing site. You may design pages on the front-end and watch them instantly come to life.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/dlist/',
                    'demo_link'   => 'https://demo.directorist.com/theme/dlist/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/themes/dlist.png',
                    'active'      => true,
                ],
                'dservice' => [
                    'name'        => 'dService',
                    'description' => __( 'DService is a kind of listing Directory WordPress theme that brings business owners and customers on the same platform. This multifunctional WordPress theme provides them the opportunity to interact with one another for business purposes.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/dservice/',
                    'demo_link'   => 'https://demo.directorist.com/theme/dservice/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/themes/dservice.png',
                    'active'      => true,
                ],
                'ddoctors' => [
                    'name'        => 'dDoctors',
                    'description' => __( 'dDoctors is a beautiful WordPress directory theme for doctor, nurse, medical techonologist, hospital, clinic, and other medical-related businesses.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/ddoctors/',
                    'demo_link'   => 'https://demo.directorist.com/theme/ddoctors/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/themes/ddoctors.png',
                    'active'      => true,
                    'badges'      => [ self::get_product_badge( 'popular', __( 'Popular', 'directorist' ) ) ],
                ],
                'dlawyers' => [
                    'name'        => 'dLawyers',
                    'description' => __( 'dLawyers is a beautiful WordPress directory theme for legal, legal adviser companies, legal offices, court consultants, lawyers, counsel bureau, attorney agencies, and other law-related businesses.', 'directorist' ),
                    'link'        => 'https://directorist.com/product/dlawyers/',
                    'demo_link'   => 'https://demo.directorist.com/theme/dlawyers/',
                    'thumbnail'   => ATBDP_URL . 'assets/images/themes/dlawyers.png',
                    'active'      => true,
                ],
            ];
        }

        // exclude_purchased_extensions
        public function exclude_purchased_extensions( $extensions ) {
            $has_subscriptions_sassion = get_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion', true );
            $is_logged_in              = ( ! empty( $has_subscriptions_sassion ) ) ? true : false;

            if ( ! $is_logged_in ) {
                return $extensions;
            }

            $purchased_products = get_user_meta( get_current_user_id(), '_atbdp_purchased_products', true );

            if ( empty( $purchased_products ) ) {
                return $extensions;
            }

            $purchased_extensions = ( ! empty( $purchased_products['plugins'] ) ) ? $purchased_products['plugins'] : '';

            if ( empty( $purchased_extensions ) ) {
                return $extensions;
            }

            $purchased_extensions_keys = ( is_array( $purchased_extensions ) ) ? array_keys( $purchased_extensions ) : [];
            $excluded_extensions       = $extensions;

            foreach ( $excluded_extensions as $extension_key => $extension ) {

                if ( ! in_array( $extension_key, $purchased_extensions_keys ) ) {
                    continue;
                }

                $excluded_extensions[ $extension_key ]['active'] = false;
            }

            return $excluded_extensions;
        }

        // exclude_purchased_themes
        public function exclude_purchased_themes( $themes ) {
            $has_subscriptions_sassion = get_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion', true );
            $is_logged_in              = ( ! empty( $has_subscriptions_sassion ) ) ? true : false;

            if ( ! $is_logged_in ) {
                return $themes;
            }

            $purchased_products = get_user_meta( get_current_user_id(), '_atbdp_purchased_products', true );

            if ( empty( $purchased_products ) ) {
                return $themes;
            }

            $purchased_themes = ( ! empty( $purchased_products['themes'] ) ) ? $purchased_products['themes'] : '';

            if ( empty( $purchased_themes ) ) {
                return $themes;
            }

            $purchased_themes_keys = is_array( $purchased_themes ) ? array_keys( $purchased_themes ) : [];
            $excluded_themes       = $themes;

            foreach ( $excluded_themes as $theme_key => $theme ) {

                if ( ! in_array( $theme_key, $purchased_themes_keys ) ) {
                    continue;
                }

                $excluded_themes[ $theme_key ]['active'] = false;
            }

            return $excluded_themes;
        }

        // get_active_extensions
        public function get_active_extensions() {
            $active_extensions = [];

            foreach ( $this->extensions as $extension_slug => $extension ) {
                if ( empty( $extension['active'] ) ) {
                    continue;
                }

                $active_extensions[ $extension_slug ] = $extension;
            }

            return $active_extensions;
        }

        // get_active_themes
        public function get_active_themes() {
            $active_themes = [];

            foreach ( $this->themes as $theme_slug => $theme ) {
                if ( empty( $theme['active'] ) ) {
                    continue;
                }

                $active_themes[ $theme_slug ] = $theme;
            }

            return $active_themes;
        }

        // handle_plugins_update_request
        public function handle_plugins_update_request() {
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'directorist' ) ), 403 );
            }

            if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
                $status            = [];
                $status['success'] = false;
                $status['message'] = 'Invalid request';

                wp_send_json( [ 'status' => $status ] );
            }

            $plugin_key = ( isset( $_POST['plugin_key'] ) ) ? directorist_clean( wp_unslash( $_POST['plugin_key'] ) ) : '';
            $status     = $this->update_plugins( [ 'plugin_key' => $plugin_key ] );

            wp_send_json( $status );
        }

        // update_plugins
        public function update_plugins( array $args = array() ) {
            $default = array( 'plugin_key' => '' );
            $args    = wp_parse_args( $args, $default );

            $status     = array( 'success' => true );
            $plugin_key = isset( $args['plugin_key'] ) ? sanitize_text_field( $args['plugin_key'] ) : '';

            // Get outdated plugins via API instead of transient.
            $outdated_plugins     = $this->get_outdated_extensions_via_api();
            $outdated_plugins_key = array_keys( $outdated_plugins );

            if ( empty( $outdated_plugins_key ) ) {
                $status['message'] = __( 'All plugins are up to date', 'directorist' );
                return array( 'status' => $status );
            }

            // If specific plugin key provided, validate it exists in outdated list.
            if ( ! empty( $plugin_key ) ) {
                // Convert plugin base to plugin key.
                $filtered_key = self::filter_plugin_key_from_base_name( $plugin_key );

                // Check if this plugin is in outdated list.
                $is_outdated = false;

                foreach ( $outdated_plugins as $base => $version_info ) {
                    $base_key = self::filter_plugin_key_from_base_name( $base );
                    if ( $base_key === $filtered_key || $base === $plugin_key ) {
                        $is_outdated = true;
                        $plugin_key  = $base_key; // Use the correct key.
                        $matched_base = $base;
                        break;
                    }
                }

                if ( ! $is_outdated ) {
                    $status['message'] = __( 'The plugin is up to date', 'directorist' );
                    return array( 'status' => $status );
                }
            }

            $plugins_available_in_subscriptions = self::get_purchased_extension_list();

            if ( ! is_array( $plugins_available_in_subscriptions ) ) {
                $status['success'] = false;
                $status['message'] = __( 'No purchased extensions found', 'directorist' );
                return array( 'status' => $status );
            }

            // Update single plugin.
            if ( ! empty( $plugin_key ) ) {
                $plugin_item = self::extract_plugin_from_list( $plugin_key, $plugins_available_in_subscriptions );

                if ( empty( $plugin_item ) || ! is_array( $plugin_item ) ) {
                    $status['success'] = false;
                    $status['message'] = __( 'License not found for this extension', 'directorist' );
                    return array( 'status' => $status );
                }

                $url = self::get_file_download_link( $plugin_item, 'plugin' );

                if ( empty( $url ) ) {
                    $status['success'] = false;
                    $status['message'] = __( 'Download link could not be retrieved', 'directorist' );
                    return array( 'status' => $status );
                }

                $download_status = $this->download_plugin( array( 'url' => $url ) );

                if ( ! $download_status['success'] ) {
                    $status['success'] = false;
                    $status['message'] = __( 'The plugin could not update', 'directorist' );
                    $status['log']     = isset( $download_status['message'] ) ? $download_status['message'] : '';
                } else {
                    // Clear version cache after successful update.
                    $cache_key = 'directorist_ext_version_' . md5( $plugin_key . $plugin_item['license'] . 'any' );
                    delete_transient( $cache_key );

                    $status['success'] = true;
                    $status['message'] = __( 'The plugin has been updated successfully', 'directorist' );
                    $status['log']     = isset( $download_status['message'] ) ? $download_status['message'] : '';
                }

                return array( 'status' => $status );
            }

            // Update all outdated plugins.
            $updated_plugins       = array();
            $update_failed_plugins = array();

            foreach ( $outdated_plugins as $plugin_base => $version_info ) {
                $base_key   = self::filter_plugin_key_from_base_name( $plugin_base );
                $plugin_item = self::extract_plugin_from_list( $base_key, $plugins_available_in_subscriptions );

                if ( empty( $plugin_item ) || ! is_array( $plugin_item ) ) {
                    $update_failed_plugins[ $plugin_base ] = $version_info;
                    continue;
                }

                $url = self::get_file_download_link( $plugin_item, 'plugin' );

                if ( empty( $url ) ) {
                    $update_failed_plugins[ $plugin_base ] = $version_info;
                    continue;
                }

                $download_status = $this->download_plugin( array( 'url' => $url ) );

                if ( ! $download_status['success'] ) {
                    $update_failed_plugins[ $plugin_base ] = $version_info;
                } else {
                    // Clear version cache after successful update.
                    $cache_key = 'directorist_ext_version_' . md5( $base_key . $plugin_item['license'] . 'any' );
                    delete_transient( $cache_key );

                    $updated_plugins[ $plugin_base ] = $version_info;
                }
            }

            $status['updated_plugins']       = $updated_plugins;
            $status['update_failed_plugins'] = $update_failed_plugins;

            // Set appropriate status message.
            if ( ! empty( $updated_plugins ) && ! empty( $update_failed_plugins ) ) {
                $status['success'] = false;
                $status['message'] = __( 'Some of the plugins could not update', 'directorist' );
            } elseif ( ! empty( $updated_plugins ) ) {
                $status['success'] = true;
                $status['message'] = __( 'All plugins have been updated successfully', 'directorist' );
            } elseif ( ! empty( $update_failed_plugins ) ) {
                $status['success'] = false;
                $status['message'] = __( 'No plugins could be updated', 'directorist' );
            }

            return array( 'status' => $status );
        }

        /**
         * Check extension version via API with caching.
         *
         * @since 8.6.1
         *
         * @param string $plugin_key      Extension key (e.g., 'directorist-gallery').
         * @param array  $plugin_item     Extension item data from subscriptions.
         * @param string $current_version Current installed version.
         * @return array|false Version info array on success, false on failure.
         */
        private function check_extension_version_via_api( $plugin_key, $plugin_item, $current_version ) {
            // Validate inputs.
            if ( ! is_string( $plugin_key ) || empty( $plugin_key ) ) {
                return false;
            }

            if ( ! is_array( $plugin_item ) || empty( $plugin_item['license'] ) || empty( $plugin_item['item_id'] ) ) {
                return false;
            }

            if ( ! is_string( $current_version ) || empty( $current_version ) ) {
                return false;
            }

            // Sanitize inputs.
            $plugin_key      = sanitize_key( $plugin_key );
            $current_version = sanitize_text_field( $current_version );
            $license         = sanitize_text_field( $plugin_item['license'] );
            $item_id         = absint( $plugin_item['item_id'] );

            // Get extension definition.
            $default_extensions = static::get_default_extensions();
            $extension          = isset( $default_extensions[ $plugin_key ] ) ? $default_extensions[ $plugin_key ] : null;

            if ( ! $extension || empty( $extension['item_id'] ) ) {
                return false;
            }

            // Use extension item_id if available, fallback to plugin_item item_id.
            $extension_item_id = absint( $extension['item_id'] );

            // Cache key for version check (3 hour cache).
            $cache_key = 'directorist_ext_version_' . md5( $plugin_key . $license . $current_version . $extension_item_id );
            $cached    = get_transient( $cache_key );

            if ( false !== $cached && is_array( $cached ) ) {
                return $cached;
            }

            // Prepare API request.
            $api_url    = 'https://directorist.com';
            $api_params = array(
                'edd_action' => 'get_version',
                'license'    => $license,
                'item_id'    => $extension_item_id,
                'version'    => $current_version,
                'slug'       => $plugin_key,
                'author'     => 'AazzTech',
                'url'        => esc_url_raw( home_url() ),
                'beta'       => false,
            );

            // Allow SSL verification to be filtered.
            $verify_ssl = apply_filters( 'edd_sl_api_request_verify_ssl', true, null );

            // Make API request with timeout.
            $response = wp_remote_post(
                esc_url_raw( $api_url ),
                array(
                    'timeout'   => 15,
                    'sslverify' => $verify_ssl,
                    'body'      => $api_params,
                )
            );

            // Handle request errors.
            if ( is_wp_error( $response ) ) {
                return false;
            }

            $response_code = wp_remote_retrieve_response_code( $response );
            if ( 200 !== $response_code ) {
                return false;
            }

            $body        = wp_remote_retrieve_body( $response );
            $version_info = json_decode( $body, true );

            // Validate response structure.
            if ( ! is_array( $version_info ) || empty( $version_info['new_version'] ) ) {
                return false;
            }

            // Sanitize version info.
            $version_info['new_version'] = sanitize_text_field( $version_info['new_version'] );

            // Cache for 3 hours.
            set_transient( $cache_key, $version_info, 3 * HOUR_IN_SECONDS );

            return $version_info;
        }

        /**
         * Get outdated extensions list by checking API directly.
         *
         * @since 8.6.1
         *
         * @return array Array of outdated plugins. Structure: [plugin_base => version_info_object].
         */
        private function get_outdated_extensions_via_api() {
            $outdated_plugins = array();
            $plugins_data     = get_plugins();

            if ( ! is_array( $plugins_data ) || empty( $plugins_data ) ) {
                return $outdated_plugins;
            }

            $purchased_extensions = self::get_purchased_extension_list();
            $default_extensions   = static::get_default_extensions();

            if ( ! is_array( $purchased_extensions ) || ! is_array( $default_extensions ) ) {
                return $outdated_plugins;
            }

            // Loop through default extensions and check for updates.
            foreach ( $default_extensions as $plugin_key => $extension ) {
                // Validate extension data.
                if ( ! is_array( $extension ) || empty( $extension['item_id'] ) ) {
                    continue;
                }

                // Get plugin base file path.
                $base = isset( $extension['base'] ) ? $extension['base'] : $plugin_key . '/' . $plugin_key . '.php';

                // Check if plugin is installed.
                if ( ! isset( $plugins_data[ $base ] ) || ! is_array( $plugins_data[ $base ] ) ) {
                    continue;
                }

                // Check if user has license for this extension.
                $plugin_item = self::extract_plugin_from_list( $plugin_key, $purchased_extensions );
                if ( empty( $plugin_item ) || ! is_array( $plugin_item ) || empty( $plugin_item['license'] ) ) {
                    continue;
                }

                // Get current version.
                $current_version = isset( $plugins_data[ $base ]['Version'] ) ? $plugins_data[ $base ]['Version'] : '0.0.0';
                if ( empty( $current_version ) ) {
                    continue;
                }

                // Check version via API.
                $version_info = $this->check_extension_version_via_api( $plugin_key, $plugin_item, $current_version );

                if ( $version_info && isset( $version_info['new_version'] ) ) {
                    // Compare versions.
                    if ( version_compare( $current_version, $version_info['new_version'], '<' ) ) {
                        // Format to match WordPress update transient structure.
                        $outdated_plugins[ $base ] = (object) array(
                            'id'            => absint( $extension['item_id'] ),
                            'slug'          => sanitize_key( $plugin_key ),
                            'plugin'        => $base,
                            'new_version'   => sanitize_text_field( $version_info['new_version'] ),
                            'url'           => isset( $version_info['homepage'] ) ? esc_url_raw( $version_info['homepage'] ) : '',
                            'package'       => '', // Will be fetched when downloading.
                            'icons'         => isset( $version_info['icons'] ) && is_array( $version_info['icons'] ) ? $version_info['icons'] : array(),
                            'banners'       => isset( $version_info['banners'] ) && is_array( $version_info['banners'] ) ? $version_info['banners'] : array(),
                            'banners_rtl'   => array(),
                            'tested'        => isset( $version_info['tested'] ) ? sanitize_text_field( $version_info['tested'] ) : '',
                            'requires_php'  => isset( $version_info['requires_php'] ) ? sanitize_text_field( $version_info['requires_php'] ) : '',
                            'compatibility' => new stdClass(),
                        );
                    }
                }
            }

            return $outdated_plugins;
        }

        // extract_plugin_from_list
        public static function extract_plugin_from_list( $plugin_key = '', $list = [] ) {

            $plugin_item = [];
            $plugin_key  = ( is_string( $plugin_key ) ) ? $plugin_key : '';
            $list        = ( is_array( $list ) ) ? $list : [];

            $keys_in_list = array_keys( $list );

            if ( in_array( $plugin_key, $keys_in_list ) ) {
                $plugin_item = $list[ $plugin_key ];
            }

            $plugin_alias_key = self::get_extension_alias_key( $plugin_key );

            if ( in_array( $plugin_alias_key, $keys_in_list ) ) {
                $plugin_item = $list[ $plugin_alias_key ];
            }

            return $plugin_item;
        }

        // filter_plugin_key_from_base_name
        public static function filter_plugin_key_from_base_name( $plugin_key = '' ) {

            if ( ! is_string( $plugin_key ) ) {
                return '';
            }

            $plugin_key = preg_replace( '/\/.+/', '', $plugin_key );

            return $plugin_key;
        }

        // get_extension_alias_key
        public static function get_extension_alias_key( string $plugin_key = '' ) {
            $extensions_aliases      = self::$extensions_aliases;
            $extensions_aliases_keys = ( is_array( $extensions_aliases ) && ! empty( $extensions_aliases ) ) ? array_keys( $extensions_aliases ) : [];
            $plugin_alias_key        = in_array( $plugin_key, $extensions_aliases_keys ) ? $extensions_aliases[ $plugin_key ] : '';

            return $plugin_alias_key;
        }

        // plugins_bulk_action
        public function plugins_bulk_action() {
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'directorist' ) ), 403 );
            }
            $status = [
                'success'         => true,
                'processed_items' => [],
                'failed_items'    => [],
            ];

            if ( ! directorist_verify_nonce() ) {
                $status['success'] = false;
                $status['message'] = 'Invalid request';

                wp_send_json( [ 'status' => $status ] );
            }

            $task         = isset( $_POST['task'] ) ? directorist_clean( wp_unslash( $_POST['task'] ) ) : '';
            $plugin_items = isset( $_POST['plugin_items'] ) ? (array) directorist_clean( wp_unslash( $_POST['plugin_items'] ) ) : [];
            $plugin_items  = array_values( array_unique( array_filter( array_map( 'sanitize_text_field', $plugin_items ) ) ) );
            $allowed_tasks = [ 'activate', 'deactivate', 'uninstall' ];

            // Validation
            if ( ! in_array( $task, $allowed_tasks, true ) ) {
                $status['success'] = false;
                $status['message'] = __( 'Invalid plugin action.', 'directorist' );
                wp_send_json( [ 'status' => $status ] );
            }

            if ( empty( $plugin_items ) ) {
                $status['success'] = false;
                $status['message'] = 'No plugin items found';
                wp_send_json( [ 'status' => $status ] );
            }

            if ( ! function_exists( 'get_plugins' ) || ! function_exists( 'delete_plugins' ) ) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }

            $installed_plugins = get_plugins();

            foreach ( $plugin_items as $plugin ) {
                if ( ! isset( $installed_plugins[ $plugin ] ) ) {
                    $status['failed_items'][ $plugin ] = __( 'Plugin files were not found.', 'directorist' );
                    continue;
                }

                if ( 'activate' === $task ) {
                    $activated = activate_plugin( $plugin );

                    if ( is_wp_error( $activated ) ) {
                        $status['failed_items'][ $plugin ] = $activated->get_error_message();
                        continue;
                    }
                } elseif ( 'deactivate' === $task ) {
                    deactivate_plugins( [ $plugin ] );

                    if ( is_plugin_active( $plugin ) ) {
                        $status['failed_items'][ $plugin ] = __( 'WordPress could not deactivate this plugin.', 'directorist' );
                        continue;
                    }
                } else {
                    if ( is_plugin_active( $plugin ) ) {
                        $status['failed_items'][ $plugin ] = __( 'Deactivate the plugin before deleting it.', 'directorist' );
                        continue;
                    }

                    $deleted = delete_plugins( [ $plugin ] );

                    if ( is_wp_error( $deleted ) ) {
                        $status['failed_items'][ $plugin ] = $deleted->get_error_message();
                        continue;
                    }

                    if ( false === $deleted ) {
                        $status['failed_items'][ $plugin ] = __( 'WordPress could not delete this plugin.', 'directorist' );
                        continue;
                    }
                }

                $status['processed_items'][] = $plugin;
            }

            if ( ! empty( $status['failed_items'] ) ) {
                $status['success'] = false;
                $status['message'] = reset( $status['failed_items'] );
            } else {
                $status['message'] = __( 'Plugin action completed successfully.', 'directorist' );
            }

            wp_send_json( [ 'status' => $status ] );
        }

        // activate_theme
        public function activate_theme() {
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'directorist' ) ), 403 );
            }
            $status           = [ 'success' => true ];
            $theme_stylesheet = ( isset( $_POST['theme_stylesheet'] ) ) ? directorist_clean( wp_unslash( $_POST['theme_stylesheet'] ) ) : '';

            if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
                $status['success'] = false;
                $status['message'] = 'Invalid request';

                wp_send_json( [ 'status' => $status ] );
            }

            if ( empty( $theme_stylesheet ) ) {
                $status['success'] = false;
                $status['message'] = __( 'Theme\'s stylesheet is missing', 'directorist' );

                wp_send_json( [ 'status' => $status ] );
            }

            switch_theme( $theme_stylesheet );
            wp_send_json( [ 'status' => $status ] );
        }

        // activate_plugin
        public function activate_plugin() {
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'directorist' ) ), 403 );
            }
            $status     = [ 'success' => true ];
            $plugin_key = ( isset( $_POST['item_key'] ) ) ? directorist_clean( wp_unslash( $_POST['item_key'] ) ) : '';

            if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
                $status['success'] = false;
                $status['message'] = 'Invalid request';

                wp_send_json( [ 'status' => $status ] );
            }

            if ( empty( $plugin_key ) ) {
                $status['success'] = false;
                $status['log']     = [ '$plugin_key' => $plugin_key ];
                $status['message'] = __( 'Please specefy which plugin to activate', 'directorist' );

                wp_send_json( [ 'status' => $status ] );
            }

            $activation_status = activate_plugin( $plugin_key );

            if ( is_wp_error( $activation_status ) ) {
                $status['success'] = false;
                $status['message'] = $activation_status->get_error_message();
            }

            wp_send_json( [ 'status' => $status ] );
        }

        // handle_theme_update_request
        public function handle_theme_update_request() {
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'directorist' ) ), 403 );
            }

            if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
                $status            = [];
                $status['success'] = false;
                $status['message'] = 'Invalid request';

                wp_send_json( [ 'status' => $status ] );
            }

            $theme_stylesheet = ( isset( $_POST['theme_stylesheet'] ) ) ? directorist_clean( wp_unslash( $_POST['theme_stylesheet'] ) ) : '';

            $update_theme_status = $this->update_the_themes( [ 'theme_stylesheet' => $theme_stylesheet ] );
            wp_send_json( $update_theme_status );
        }

        // update_the_theme
        public function update_the_themes( array $args = [] ) {
            $default = [ 'theme_stylesheet' => '' ];
            $args    = array_merge( $default, $args );

            $status = [ 'success' => true ];

            $theme_stylesheet = $args['theme_stylesheet'];
            $theme_updates    = get_site_transient( 'update_themes' );
            $outdated_themes  = is_object( $theme_updates ) && isset( $theme_updates->response ) && is_array( $theme_updates->response )
                ? $theme_updates->response
                : [];
            $outdated_themes_key = ( is_array( $outdated_themes ) ) ? array_keys( $outdated_themes ) : [];

            if ( empty( $outdated_themes_key ) ) {
                $status['message'] = __( 'All themes are up to date', 'directorist' );

                return [ 'status' => $status ];
            }

            if ( ! empty( $theme_stylesheet ) && ! in_array( $theme_stylesheet, $outdated_themes_key ) ) {
                $status['message'] = __( 'The theme is up to date', 'directorist' );

                return [ 'status' => $status ];
            }

            $themes_available_in_subscriptions      = self::get_purchased_theme_list();
            $themes_available_in_subscriptions_keys = ( is_array( $themes_available_in_subscriptions ) ) ? array_keys( $themes_available_in_subscriptions ) : [];

            // Check if stylesheet is present
            if ( ! empty( $theme_stylesheet ) ) {

                // Check if the the update is available
                if ( ! in_array( $theme_stylesheet, $outdated_themes_key ) ) {
                    $status['success'] = false;
                    $status['message'] = __( 'The theme is already upto date', 'directorist' );

                    return [ 'status' => $status ];
                }

                if ( empty( $themes_available_in_subscriptions[ $theme_stylesheet ] ) ) {
                    $status['success'] = false;
                    $status['message'] = __( 'License not found for this theme', 'directorist' );

                    return [ 'status' => $status ];
                }

                $theme_item = $themes_available_in_subscriptions[ $theme_stylesheet ];
                $url        = self::get_file_download_link( $theme_item, 'theme' );
                $theme_update_item = $outdated_themes[ $theme_stylesheet ];
                $package_url       = is_object( $theme_update_item ) ? ( $theme_update_item->package ?? '' ) : ( $theme_update_item['package'] ?? '' );
                $url               = empty( $url ) && ! empty( $package_url ) ? $package_url : $url;

                if ( empty( $url ) ) {
                    $status['success'] = false;
                    $status['message'] = __( 'Download link could not be retrieved', 'directorist' );

                    return [ 'status' => $status ];
                }

                $download_status = $this->download_theme( [ 'url' => $url ] );

                if ( ! $download_status['success'] ) {
                    $status['success'] = false;
                    $status['message'] = __( 'The theme could not update', 'directorist' );
                    $status['log']     = $download_status['message'];
                } else {
                    $status['success'] = true;
                    $status['message'] = __( 'The theme has been updated successfully', 'directorist' );
                    $status['log']     = $download_status['message'];
                    wp_clean_themes_cache();
                };

                return [ 'status' => $status ];
            }

            // Update all
            $updated_themes       = [];
            $update_failed_themes = [];

            foreach ( $outdated_themes as $theme_key => $theme ) {
                $url = '';

                if ( ! in_array( $theme_key, $themes_available_in_subscriptions_keys ) ) {
                    continue;
                }

                $theme_item = $themes_available_in_subscriptions[ $theme_key ];
                $url        = self::get_file_download_link( $theme_item, 'theme' );

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
                $status['message'] = __( 'Some of the theme could not update', 'directorist' );
            }

            if ( empty( $update_failed_themes ) ) {
                $status['success'] = true;
                $status['message'] = __( 'All the themes are updated successfully', 'directorist' );
            }

            if ( empty( $updated_themes ) ) {
                $status['success'] = true;
                $status['message'] = __( 'No themes could not update', 'directorist' );
            }

            return [ 'status' => $status ];
        }

        /**
         * Authenticate users as directorist customer.
         *
         * @return void
         */
        public function authenticate_the_customer() {

            $status = [
                'success' => true,
                'log'     => [],
            ];

            if ( ! current_user_can( 'manage_options' ) ) {
                $status['success']                  = false;
                $status['log']['permission_denied'] = [
                    'type'    => 'error',
                    'message' => __( 'You do not have permission to perform this action.', 'directorist' ),
                ];

                wp_send_json( [ 'status' => $status ] );
            }

            if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
                $status['success']                 = false;
                $status['log']['invalid_request'] = [
                    'type'    => 'error',
                    'message' => 'Invalid request',
                ];
            }

            // Get form data
            $auth_method = isset( $_POST['auth_method'] ) && 'access_key' === sanitize_key( wp_unslash( $_POST['auth_method'] ) )
                ? 'access_key'
                : 'account';
            $access_key  = ( isset( $_POST['access_key'] ) ) ? sanitize_text_field( wp_unslash( $_POST['access_key'] ) ) : '';
			$submitted_login = ( isset( $_POST['username'] ) ) ? wp_unslash( $_POST['username'] ) : ''; // @codingStandardsIgnoreLine.
			$username        = is_email( $submitted_login ) ? sanitize_email( $submitted_login ) : sanitize_user( $submitted_login );
			$password_raw    = ( isset( $_POST['password'] ) ) ? wp_unslash( $_POST['password'] ) : ''; // @codingStandardsIgnoreLine.
			$password        = urlencode( $password_raw );

            if ( 'access_key' === $auth_method && empty( $access_key ) ) {
                $status['success']                    = false;
                $status['log']['access_key_missing'] = [
                    'type'    => 'error',
                    'message' => __( 'Access key is required', 'directorist' ),
                ];
            }

            if ( 'account' === $auth_method ) {
                // Validate username
                if ( empty( $username ) && ! empty( $password ) ) {
                    $status['success']                 = false;
                    $status['log']['username_missing'] = [
                        'type'    => 'error',
                        'message' => __( 'Username or email address is required', 'directorist' ),
                    ];
                }

                // Validate password
                if ( empty( $password ) && ! empty( $username ) ) {
                    $status['success']                 = false;
                    $status['log']['password_missing'] = [
                        'type'    => 'error',
                        'message' => __( 'Password is required', 'directorist' ),
                    ];
                }

                // Validate username && password
                if ( empty( $password ) && empty( $username ) ) {
                    $status['success']                 = false;
                    $status['log']['password_missing'] = [
                        'type'    => 'error',
                        'message' => __( 'Username or email address and password are required', 'directorist' ),
                    ];
                }
            }

            if ( ! $status['success'] ) {
                wp_send_json( [ 'status' => $status ] );
            }

            // Get licencing data
            $response = 'access_key' === $auth_method
                ? self::remote_authenticate_user_by_access_key( $access_key )
                : self::remote_authenticate_user(
                    [
                        'user'         => $username,
                        'password'     => $password,
                        'password_raw' => $password_raw,
                    ]
                );

            // Validate response
            if ( ! $response['success'] ) {
                $status['success']      = false;
                $default_status_message = ( isset( $response['message'] ) ) ? $response['message'] : '';

                if ( isset( $response['log'] ) && isset( $response['log']['errors'] ) && is_array( $response['log']['errors'] ) ) {
                    foreach ( $response['log']['errors'] as $error_key => $error_value ) {
                        $status['log'][ $error_key ] = [
                            'type'    => 'error',
                            'message' => ( is_array( $error_value ) ) ? $error_value[0] : $error_value,
                        ];
                    }
                } else {
                    $status['log']['unknown_error'] = [
                        'type'    => 'error',
                        'message' => ( ! empty( $default_status_message ) ) ? $default_status_message : __( 'Something went wrong', 'directorist' ),
                    ];
                }

                wp_send_json(
                    [
                        'status' => $status,
                        'response_body' => $response,
                    ]
                );
            }

            $this->store_account_summary_from_response( $response );

            $account_data       = isset( $response['account_data'] ) && is_array( $response['account_data'] ) ? $response['account_data'] : [];
            $account_identifier = $username;

            if ( 'access_key' === $auth_method ) {
                $account_identifier = isset( $account_data['user_email'] ) && is_scalar( $account_data['user_email'] )
                    ? sanitize_email( (string) $account_data['user_email'] )
                    : '';

                if ( ! $account_identifier && isset( $account_data['display_name'] ) && is_scalar( $account_data['display_name'] ) ) {
                    $account_identifier = sanitize_text_field( (string) $account_data['display_name'] );
                }
            }

            $previous_username    = get_user_meta( get_current_user_id(), '_atbdp_subscribed_username', true );
            $previous_auth_method = get_user_meta( get_current_user_id(), '_atbdp_subscription_connection_method', true );
            $previous_auth_method = 'access_key' === $previous_auth_method ? 'access_key' : 'account';

            // Enable Sassion
            update_user_meta( get_current_user_id(), '_atbdp_subscribed_username', $account_identifier );
            update_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion', true );
            update_user_meta( get_current_user_id(), '_atbdp_subscription_connection_method', $auth_method );

            $plugins_available_in_subscriptions = self::get_purchased_extension_list();
            $themes_available_in_subscriptions  = self::get_purchased_theme_list();
            $has_previous_subscriptions         = ( ! empty( $plugins_available_in_subscriptions ) || ! empty( $themes_available_in_subscriptions ) ) ? true : false;
            $is_returning_customer              = $previous_username === $account_identifier
                && $previous_auth_method === $auth_method
                && $has_previous_subscriptions;

            delete_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions' );
            delete_user_meta( get_current_user_id(), '_themes_available_in_subscriptions' );

            $license_data = $response['license_data'];

            // Update user meta
            if ( ! empty( $license_data['themes'] ) ) {
                $themes_available_in_subscriptions = $this->prepare_available_in_subscriptions( $license_data['themes'] );
                update_user_meta( get_current_user_id(), '_themes_available_in_subscriptions', $themes_available_in_subscriptions );
            }

            if ( ! empty( $license_data['plugins'] ) ) {
                $plugins_available_in_subscriptions = $this->prepare_available_in_subscriptions( $license_data['plugins'] );
                update_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions', $plugins_available_in_subscriptions );
            }

            if ( $is_returning_customer ) {
                wp_send_json(
                    [
                        'status'                     => $status,
                        'has_previous_subscriptions' => true,
                    ]
                );
            }

            $status['success']                 = true;
            $status['log']['login_successful'] = [
                'type'    => 'success',
                'message' => 'Login is successful',
            ];

            wp_send_json(
                [
                    'status' => $status,
                    'license_data' => $license_data,
                ]
            );
        }

        // handle_refresh_purchase_status_request
        public function handle_refresh_purchase_status_request() {
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'directorist' ) ), 403 );
            }

            $status   = [ 'success' => true ];

            if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
                $status['success'] = false;
                $status['message'] = 'Invalid request';

                wp_send_json( [ 'status' => $status ] );
            }

			$credential        = isset( $_POST['credential'] )
                ? wp_unslash( $_POST['credential'] ) // @codingStandardsIgnoreLine.
                : ( ( isset( $_POST['password'] ) ) ? wp_unslash( $_POST['password'] ) : '' ); // @codingStandardsIgnoreLine.
            $connection_method = get_user_meta( get_current_user_id(), '_atbdp_subscription_connection_method', true );
            $connection_method = 'access_key' === $connection_method ? 'access_key' : 'account';

            $status = $this->refresh_purchase_status(
                [
                    'credential'        => $credential,
                    'password'          => $credential,
                    'password_raw'      => $credential,
                    'connection_method' => $connection_method,
                ]
            );

            wp_send_json( $status );
        }

        // refresh_purchase_status
        public function refresh_purchase_status( array $args = [] ) {
            $status  = [ 'success' => true ];
            $default = [
                'credential'        => '',
                'password'          => '',
                'password_raw'      => null,
                'connection_method' => 'account',
            ];
            $args              = array_merge( $default, $args );
            $connection_method = 'access_key' === $args['connection_method'] ? 'access_key' : 'account';
            $credential        = '' !== $args['credential'] ? $args['credential'] : $args['password'];

            if ( empty( $credential ) ) {
                $status['success'] = false;
                $status['message'] = 'access_key' === $connection_method
                    ? __( 'Access key is required', 'directorist' )
                    : __( 'Password is required', 'directorist' );

                return [ 'status' => $status ];
            }

            $username = get_user_meta( get_current_user_id(), '_atbdp_subscribed_username', true );

            if ( 'account' === $connection_method && empty( $username ) ) {
                $status['success'] = false;
                $status['reload']  = true;
                $status['message'] = __( 'Sassion is destroyed, please sign-in again', 'directorist' );

                delete_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion' );

                return [ 'status' => $status ];
            }

            // Get licencing data
            $authentication = 'access_key' === $connection_method
                ? self::remote_authenticate_user_by_access_key( sanitize_text_field( $credential ) )
                : self::remote_authenticate_user(
                    [
                        'user'         => $username,
                        'password'     => $credential,
                        'password_raw' => $args['password_raw'],
                    ]
                );

            // Validate response
            if ( ! $authentication['success'] ) {
                $status['success'] = false;
                $status['message'] = $authentication['message'];

                return [
                    'status' => $status,
                    'response_body' => $authentication,
                ];
            }

            $this->store_account_summary_from_response( $authentication );

            if ( 'access_key' === $connection_method ) {
                $account_data = isset( $authentication['account_data'] ) && is_array( $authentication['account_data'] )
                    ? $authentication['account_data']
                    : [];

                if ( isset( $account_data['user_email'] ) && is_scalar( $account_data['user_email'] ) ) {
                    update_user_meta( get_current_user_id(), '_atbdp_subscribed_username', sanitize_email( (string) $account_data['user_email'] ) );
                }
            }

            $license_data = $authentication['license_data'];

            // Update user meta
            if ( ! empty( $license_data['themes'] ) ) {
                $themes_available_in_subscriptions = $this->prepare_available_in_subscriptions( $license_data['themes'] );
                update_user_meta( get_current_user_id(), '_themes_available_in_subscriptions', $themes_available_in_subscriptions );
            }

            if ( ! empty( $license_data['plugins'] ) ) {
                $plugins_available_in_subscriptions = $this->prepare_available_in_subscriptions( $license_data['plugins'] );
                update_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions', $plugins_available_in_subscriptions );
            }

            $status['success'] = true;
            $status['message'] = __( 'Your purchase has been refreshed successfuly', 'directorist' );

            return [ 'status' => $status ];
        }

        // handle_close_subscriptions_sassion_request
        public function handle_close_subscriptions_sassion_request() {
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'directorist' ) ), 403 );
            }

            if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
                $status            = [];
                $status['success'] = false;
                $status['message'] = 'Invalid request';

                wp_send_json( [ 'status' => $status ] );
            }

            $hard_logout_state = ( isset( $_POST['hard_logout'] ) ) ? boolval( $_POST['hard_logout'] ) : false;
            $status            = $this->close_subscriptions_sassion( [ 'hard_logout' => $hard_logout_state ] );

            wp_send_json( $status );
        }

        // close_subscriptions_sassion
        public function close_subscriptions_sassion( array $args = [] ) {
            $default = [ 'hard_logout' => false ];
            $args    = array_merge( $default, $args );

            $status = [ 'success' => true ];
            delete_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion' );
            delete_user_meta( get_current_user_id(), '_atbdp_account_summary' );
            delete_user_meta( get_current_user_id(), '_atbdp_subscription_connection_method' );

            if ( $args['hard_logout'] ) {
                delete_user_meta( get_current_user_id(), '_atbdp_subscribed_username' );
                delete_user_meta( get_current_user_id(), '_themes_available_in_subscriptions' );
                delete_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions' );
            }

            return $status;
        }

        // prepare_available_in_subscriptions
        public function prepare_available_in_subscriptions( array $products = [] ) {
            $available_in_subscriptions = [];

            if ( empty( $products ) ) {
                return $available_in_subscriptions;
            }

            foreach ( $products as $product ) {
                $product_key                              = $this->get_product_key_from_permalink( $product['permalink'] );
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
            $status       = [ 'success' => true ];
            $license_item = ( isset( $_POST['license_item'] ) ) ? directorist_clean( wp_unslash( $_POST['license_item'] ) ) : '';
            $product_type = ( isset( $_POST['product_type'] ) ) ? directorist_clean( wp_unslash( $_POST['product_type'] ) ) : '';

            if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
                $status            = [];
                $status['success'] = false;
                $status['message'] = 'Invalid request';

                wp_send_json( [ 'status' => $status ] );
            }

            if ( empty( $license_item ) ) {
                $status['success'] = false;
                $status['message'] = 'License item is missing';

                wp_send_json( [ 'status' => $status ] );
            }

            if ( empty( $product_type ) ) {
                $status['success'] = false;
                $status['message'] = 'Product type is required';

                wp_send_json( [ 'status' => $status ] );
            }

            $activation_status = $this->activate_license( $license_item, $product_type );
            $status['success'] = $activation_status['success'];

            wp_send_json(
                [
                    'status' => $status,
                    'activation_status' => $activation_status,
                ]
            );
        }

        // activate_license
        public function activate_license( $license_item, $product_type = '' ) {
            $status            = [ 'success' => true ];
            $activation_status = self::remote_activate_license( $license_item );

            if ( empty( $activation_status['success'] ) ) {
                $status['success'] = false;
            }

            $status['response'] = $activation_status['response'];
            $product_type       = self::filter_product_type( $product_type );

            if ( $status['success'] && ( 'plugin' === $product_type || 'theme' === $product_type ) ) {
                $user_purchased = get_user_meta( get_current_user_id(), '_atbdp_purchased_products', true );

                if ( empty( $user_purchased ) ) {
                    $user_purchased = [];
                }

                if ( empty( $user_purchased[ $product_type ] ) ) {
                    $user_purchased[ $product_type ] = [];
                }

                $purchased_items = $user_purchased[ $product_type ];

                // Append new product
                $product_key                   = $this->get_product_key_from_permalink( $license_item['permalink'] );
                $purchased_items[ $product_key ] = $license_item;

                $user_purchased[ $product_type ] = $purchased_items;
                update_user_meta( get_current_user_id(), '_atbdp_purchased_products', $user_purchased );

                $status['purchased_products'] = $user_purchased;
            }

            return $status;
        }

        // handle_file_install_request_from_subscriptions
        public function handle_file_install_request_from_subscriptions() {
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'directorist' ) ), 403 );
            }
            $item_key = ( isset( $_POST['item_key'] ) ) ? directorist_clean( wp_unslash( $_POST['item_key'] ) ) : '';
            $type     = ( isset( $_POST['type'] ) ) ? directorist_clean( wp_unslash( $_POST['type'] ) ) : '';

            if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
                $status            = [];
                $status['success'] = false;
                $status['message'] = 'Invalid request';

                wp_send_json( [ 'status' => $status ] );
            }

            $installation_status = $this->install_file_from_subscriptions(
                [
                    'item_key' => $item_key,
                    'type' => $type,
                ]
            );

            wp_send_json( $installation_status );
        }

        // install_file_from_subscriptions
        public function install_file_from_subscriptions( array $args = [] ) {
            $default = [
                'item_key' => '',
                'type' => '',
            ];
            $args    = array_merge( $default, $args );

            $item_key = $args['item_key'];
            $type     = $args['type'];

            $status = [ 'success' => true ];

            if ( empty( $item_key ) ) {
                $status['success'] = false;
                $status['message'] = __( 'Item key is missing', 'directorist' );

                return [ 'status' => $status ];
            }

            if ( empty( $type ) ) {
                $status['success'] = false;
                $status['message'] = __( 'Type not specified', 'directorist' );

                return [ 'status' => $status ];
            }

            if ( 'plugin' !== $type && 'theme' !== $type ) {
                $status['success'] = false;
                $status['message'] = __( 'Invalid type', 'directorist' );

                return [ 'status' => $status ];
            }

            if ( 'theme' === $type ) {
                $available_in_subscriptions = self::get_purchased_theme_list();
            }

            if ( 'plugin' === $type ) {
                $available_in_subscriptions = self::get_purchased_extension_list();
            }

            if ( empty( $available_in_subscriptions ) ) {
                $status['success'] = false;
                $status['message'] = __( 'Nothing available in subscriptions', 'directorist' );

                return [ 'status' => $status ];
            }

            if ( empty( $available_in_subscriptions[ $item_key ] ) ) {
                $status['success'] = false;
                $status['message'] = __( 'The item is not available in your subscriptions', 'directorist' );

                return [ 'status' => $status ];
            }

            $installing_file = $available_in_subscriptions[ $item_key ];

            $activatation_status = $this->activate_license( $installing_file, $type );
            $status['log']       = $activatation_status;

            if ( ! $activatation_status['success'] ) {
                $status['success'] = false;
                $status['message'] = __( 'The license is not valid, please check you subscription.', 'directorist' );

                return [ 'status' => $status ];
            }

            $beta_link = ! empty( $installing_file['beta_link'] ) ? $installing_file['beta_link'] : '';

            $link          = ATBDP()->beta ? $beta_link : $installing_file['download_link'];
            $download_args = [ 'url' => $link ];

            if ( 'plugin' === $type ) {
                $download_status = $this->download_plugin( $download_args );
            }

            if ( 'theme' === $type ) {
                $download_status = $this->download_theme( $download_args );
            }

            if ( ! $download_status['success'] ) {
                return $download_status;
            }

            $status['success'] = true;
            $status['message'] = __( 'Installed Successfully', 'directorist' );

            return [ 'status' => $status ];
        }

        // handle_plugin_download_request
        public function handle_file_download_request() {
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'directorist' ) ), 403 );
            }
            $status        = [ 'success' => true ];

            if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
                $status['success'] = false;
                $status['message'] = 'Invalid request';

                wp_send_json( [ 'status' => $status ] );
            }

            $download_item = ( isset( $_POST['download_item'] ) ) ? directorist_clean( wp_unslash( $_POST['download_item'] ) ) : '';
            $type          = ( isset( $_POST['type'] ) ) ? directorist_clean( wp_unslash( $_POST['type'] ) ) : '';

            if ( empty( $download_item ) ) {
                $status['success'] = false;
                $status['message'] = 'Download item is missing';

                wp_send_json( [ 'status' => $status ] );
            }

            if ( empty( $type ) ) {
                $status['success'] = false;
                $status['message'] = 'Type not specified';

                wp_send_json( [ 'status' => $status ] );
            }

            if ( 'plugin' !== $type && 'theme' !== $type ) {
                $status['success'] = false;
                $status['message'] = 'Invalid type';

                wp_send_json( [ 'status' => $status ] );
            }

            $activate_license = $this->activate_license( $download_item, $type );

            if ( ! $activate_license['success'] ) {
                $status['success'] = false;
                $status['message'] = __( 'Activation failed', 'directorist' );
                $status['ref']     = $activate_license;

                wp_send_json( [ 'status' => $status ] );
            }

            if ( empty( $download_item['download_link'] ) ) {
                $status['success'] = false;
                $status['message'] = 'Download Link not found';

                wp_send_json( [ 'status' => $status ] );
            }

            if ( ! is_string( $download_item['download_link'] ) ) {
                $status['success'] = false;
                $status['message'] = 'Download Link not found';

                wp_send_json( [ 'status' => $status ] );
            }

            $link          = $download_item['download_link'];
            $download_args = [ 'url' => $link ];

            if ( 'plugin' === $type ) {
                $download_status = $this->download_plugin( $download_args );
            }

            if ( 'theme' === $type ) {
                $download_status = $this->download_theme( $download_args );
            }

            if ( ! $download_status['success'] ) {
                $status['success'] = false;
                $status['message'] = $download_status['message'] ?? __( 'Download failed', 'directorist' );
                wp_send_json( [ 'status' => $status ] );
            }

            $status['success'] = true;
            $status['message'] = __( 'Downloaded', 'directorist' );

            wp_send_json( [ 'status' => $status ] );
        }

        // download_plugin
        public function download_plugin( array $args = [] ) {
            return $this->download_product_package( $args, 'plugin' );
        }

        // download_theme
        public function download_theme( array $args = [] ) {
            return $this->download_product_package( $args, 'theme' );
        }

        private function download_product_package( array $args, $product_type ) {
            $status = [ 'success' => false ];
            $args   = array_merge(
                [
                    'url'                => '',
                    'init_wp_filesystem' => true,
                ],
                $args
            );

            if ( empty( $args['url'] ) || ! self::is_varified_host( $args['url'] ) ) {
                $status['message'] = __( 'Invalid download link', 'directorist' );
                return $status;
            }

            if ( ! in_array( $product_type, [ 'plugin', 'theme' ], true ) ) {
                $status['message'] = __( 'Invalid product type', 'directorist' );
                return $status;
            }

            if ( ! function_exists( 'WP_Filesystem' ) ) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }

            global $wp_filesystem;

            if ( $args['init_wp_filesystem'] && ! WP_Filesystem() ) {
                $status['message'] = __( 'Could not initialize the WordPress filesystem.', 'directorist' );
                return $status;
            }

            if ( ! is_object( $wp_filesystem ) ) {
                $status['message'] = __( 'The WordPress filesystem is unavailable.', 'directorist' );
                return $status;
            }

            $destination_root = 'plugin' === $product_type ? WP_PLUGIN_DIR : get_theme_root();
            $token            = str_replace( '-', '', wp_generate_uuid4() );
            $extract_path     = trailingslashit( get_temp_dir() ) . "directorist-{$product_type}-{$token}";
            $tmp_file         = download_url( $args['url'] );
            $stage_paths      = [];
            $backup_paths     = [];
            $installed_paths  = [];

            if ( is_wp_error( $tmp_file ) ) {
                $status['message'] = $tmp_file->get_error_message();
                return $status;
            }

            if ( ! is_string( $tmp_file ) || ! $wp_filesystem->mkdir( $extract_path ) ) {
                if ( is_string( $tmp_file ) && file_exists( $tmp_file ) ) {
                    wp_delete_file( $tmp_file );
                }

                $status['message'] = __( 'Could not create a temporary product directory.', 'directorist' );
                return $status;
            }

            $unzip_status = unzip_file( $tmp_file, $extract_path );
            wp_delete_file( $tmp_file );

            if ( is_wp_error( $unzip_status ) ) {
                $wp_filesystem->delete( $extract_path, true );
                $status['message'] = $unzip_status->get_error_message();
                return $status;
            }

            $sources = $this->get_product_package_sources( $extract_path, $product_type );

            if ( is_wp_error( $sources ) ) {
                $wp_filesystem->delete( $extract_path, true );
                $status['message'] = $sources->get_error_message();
                return $status;
            }

            foreach ( $sources as $source_path ) {
                $directory_name = basename( $source_path );

                if ( 0 !== validate_file( $directory_name ) || in_array( $directory_name, [ '.', '..' ], true ) ) {
                    $status['message'] = __( 'The product package contains an invalid directory name.', 'directorist' );
                    break;
                }

                $stage_path = trailingslashit( $destination_root ) . ".directorist-stage-{$token}-{$directory_name}";
                $stage_paths[ $directory_name ] = $stage_path;
                $copy_status = copy_dir( $source_path, $stage_path );

                if ( is_wp_error( $copy_status ) ) {
                    $status['message'] = $copy_status->get_error_message();
                    break;
                }

                if ( ! $this->is_valid_product_directory( $stage_path, $product_type ) ) {
                    $status['message'] = __( 'The staged product files are invalid.', 'directorist' );
                    break;
                }

            }

            if ( empty( $status['message'] ) ) {
                foreach ( $stage_paths as $directory_name => $stage_path ) {
                    $destination_path = trailingslashit( $destination_root ) . $directory_name;
                    $backup_path      = trailingslashit( $destination_root ) . ".directorist-backup-{$token}-{$directory_name}";

                    if ( $wp_filesystem->exists( $destination_path ) ) {
                        if ( ! $wp_filesystem->move( $destination_path, $backup_path, false ) ) {
                            $status['message'] = __( 'Could not prepare the existing product files for replacement.', 'directorist' );
                            break;
                        }

                        $backup_paths[ $directory_name ] = $backup_path;
                    }

                    if ( ! $wp_filesystem->move( $stage_path, $destination_path, false ) ) {
                        $status['message'] = __( 'Could not move the staged product files into place.', 'directorist' );
                        break;
                    }

                    $installed_paths[ $directory_name ] = $destination_path;
                    unset( $stage_paths[ $directory_name ] );
                }
            }

            if ( ! empty( $status['message'] ) ) {
                foreach ( $installed_paths as $destination_path ) {
                    $wp_filesystem->delete( $destination_path, true );
                }

                foreach ( $backup_paths as $directory_name => $backup_path ) {
                    $destination_path = trailingslashit( $destination_root ) . $directory_name;

                    if ( $wp_filesystem->exists( $backup_path ) ) {
                        $wp_filesystem->move( $backup_path, $destination_path, false );
                    }
                }
            } else {
                foreach ( $backup_paths as $backup_path ) {
                    $wp_filesystem->delete( $backup_path, true );
                }

                $status['success'] = true;
                $status['message'] = 'plugin' === $product_type
                    ? __( 'The plugin has been downloaded successfully', 'directorist' )
                    : __( 'The theme has been downloaded successfully', 'directorist' );
            }

            foreach ( $stage_paths as $stage_path ) {
                $wp_filesystem->delete( $stage_path, true );
            }

            $wp_filesystem->delete( $extract_path, true );
            return $status;
        }

        private function get_product_package_sources( $extract_path, $product_type ) {
            $directories = glob( trailingslashit( $extract_path ) . '*', GLOB_ONLYDIR );
            $sources     = [];

            foreach ( (array) $directories as $directory ) {
                if ( '__MACOSX' === basename( $directory ) ) {
                    continue;
                }

                if ( $this->is_valid_product_directory( $directory, $product_type ) ) {
                    $sources[ basename( $directory ) ] = $directory;
                }
            }

            if ( 'theme' === $product_type && empty( $sources ) ) {
                $nested_path = trailingslashit( $extract_path ) . '_directorist_nested_themes';
                $zip_files   = glob( trailingslashit( $extract_path ) . '*/*.zip' );

                if ( ! empty( $zip_files ) ) {
                    wp_mkdir_p( $nested_path );

                    foreach ( $zip_files as $zip_file ) {
                        $unzip_status = unzip_file( $zip_file, $nested_path );

                        if ( is_wp_error( $unzip_status ) ) {
                            return $unzip_status;
                        }
                    }

                    foreach ( (array) glob( trailingslashit( $nested_path ) . '*', GLOB_ONLYDIR ) as $directory ) {
                        if ( $this->is_valid_product_directory( $directory, $product_type ) ) {
                            $sources[ basename( $directory ) ] = $directory;
                        }
                    }
                }
            }

            if ( empty( $sources ) ) {
                return new WP_Error( 'directorist_invalid_product_package', __( 'The downloaded archive does not contain valid product files.', 'directorist' ) );
            }

            if ( 'plugin' === $product_type && 1 !== count( $sources ) ) {
                return new WP_Error( 'directorist_ambiguous_plugin_package', __( 'The downloaded archive contains multiple plugin directories.', 'directorist' ) );
            }

            return array_values( $sources );
        }

        private function is_valid_product_directory( $directory, $product_type ) {
            if ( 'theme' === $product_type ) {
                $style_file = trailingslashit( $directory ) . 'style.css';

                if ( ! file_exists( $style_file ) ) {
                    return false;
                }

                $headers = get_file_data( $style_file, [ 'name' => 'Theme Name' ], 'theme' );
                return ! empty( $headers['name'] );
            }

            foreach ( (array) glob( trailingslashit( $directory ) . '*.php' ) as $plugin_file ) {
                $headers = get_file_data( $plugin_file, [ 'name' => 'Plugin Name' ], 'plugin' );

                if ( ! empty( $headers['name'] ) ) {
                    return true;
                }
            }

            return false;
        }

        // install_theme_from_zip
        public function install_themes_from_zip_files( $zip_files, $temp_dest, $wp_filesystem ) {
            $theme_path = WP_CONTENT_DIR . '/themes';

            foreach ( $zip_files as $zip ) {
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
            $activation_url = 'https://directorist.com';

            // Activate the Extensions
            $purchased_extensions_meta    = [];
            $purchased_extensions         = [];
            $invalid_purchased_extensions = [];

            if ( ! empty( $license_data['plugins'] ) ) {

                foreach ( $license_data['plugins'] as $extension ) {
                    $license              = ( ! empty( $response_body['all_access'] ) ) ? $response_body['active_licenses'][0] : $extension['license'];
                    $extension['license'] = $license;

                    $activation_status = self::remote_activate_license( $extension, 'plugin' );

                    if ( empty( $activation_status['success'] ) ) {
                        $invalid_purchased_extensions[] = [
                            'extension' => $extension,
                            'response' => $activation_status['response'],
                        ];
                        continue;
                    }

                    $purchased_extensions[] = $extension;

                    // Store the ref for db
                    $link    = $extension['permalink'];
                    $ext_key = str_replace( 'http://directorist.com/product/', '', $link );
                    $ext_key = str_replace( 'https://directorist.com/product/', '', $ext_key );
                    $ext_key = str_replace( '/', '', $ext_key );

                    $purchased_extensions_meta[ $ext_key ] = [
                        'item_id' => $extension['item_id'],
                        'license' => $extension['license'],
                        'license' => $extension['license'],
                        'file'    => $extension['links'],
                    ];
                }
            }

            // Activate the Themes
            $purchased_themes_meta    = [];
            $purchased_themes         = [];
            $invalid_purchased_themes = [];

            if ( ! empty( $license_data['themes'] ) ) {

                foreach ( $license_data['themes'] as $theme ) {
                    $license          = ( ! empty( $response_body['all_access'] ) ) ? $response_body['active_licenses'][0] : $theme['license'];
                    $theme['license'] = $license;

                    $activation_status = self::remote_activate_license( $theme );

                    if ( empty( $activation_status['success'] ) ) {
                        $invalid_purchased_themes[] = $theme;
                        $invalid_purchased_themes[] = [
                            'extension' => $theme,
                            'response' => $activation_status['response'],
                        ];
                        continue;
                    }

                    $purchased_themes[] = $theme;

                    // Store the ref for db
                    $link      = $theme['permalink'];
                    $theme_key = str_replace( 'http://directorist.com/product/', '', $link );
                    $theme_key = str_replace( 'https://directorist.com/product/', '', $theme_key );
                    $theme_key = str_replace( '/', '', $theme_key );

                    $purchased_themes_meta[ $theme_key ] = [
                        'item_id' => $extension['item_id'],
                        'license' => $extension['license'],
                        'file'    => $extension['links'],
                    ];
                }
            }

            $customers_purchased = [
                'extensions' => $purchased_extensions_meta,
                'themes'     => $purchased_themes_meta,
            ];

            update_user_meta( get_current_user_id(), '_atbdp_purchased_products', $customers_purchased );

            $status['purchased_extensions']         = $purchased_extensions;
            $status['invalid_purchased_extensions'] = $invalid_purchased_extensions;

            $status['purchased_themes']         = $purchased_themes;
            $status['invalid_purchased_themes'] = $invalid_purchased_themes;

            $status['customers_purchased'] = $customers_purchased;

            return $status;
        }

        // download_purchased_items
        public function download_purchased_items() {
            $status = [
                'success' => true,
                'log' => [],
            ];

            if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
                $status['success'] = false;
                $status['message'] = 'Invalid request';

                wp_send_json( [ 'status' => $status ] );
            }

            $cart = ( isset( $_POST['customers_purchased'] ) ) ? directorist_clean( wp_unslash( $_POST['customers_purchased'] ) ) : '';

            if ( empty( $cart ) ) {
                $status['success']                        = false;
                $status['log']['no_purchased_data_found'] = [
                    'type'    => 'error',
                    'message' => 'No purchased data found',
                ];
                wp_send_json( [ 'status' => $status ] );
            }

            // Download the extensions
            if ( ! function_exists( 'WP_Filesystem' ) ) {
                include ABSPATH . 'wp-admin/includes/file.php';
            }

            WP_Filesystem();

            // Download Extenstions
            if ( ! empty( $cart['purchased_extensions'] ) ) {
                foreach ( $cart['purchased_extensions'] as $extension ) {
                    $download_link = $extension['download_link'];
                    if ( empty( $download_link ) ) {
                        continue;
                    }

                    $this->download_plugin(
                        [
                            'url' => $download_link,
                            'init_wp_filesystem' => false,
                        ]
                    );
                }
            }

            // Download Themes
            if ( ! empty( $cart['purchased_themes'] ) ) {
                foreach ( $cart['purchased_themes'] as $theme ) {
                    $download_link = isset( $theme['download_link'] ) ? $theme['download_link'] : '';
                    if ( empty( $download_link ) ) {
                        continue;
                    }

                    $this->download_theme(
                        [
                            'url' => $download_link,
                            'init_wp_filesystem' => false,
                        ]
                    );
                }
            }

            $status['message'] = 'Download has been completed, redirecting...';

            wp_send_json( [ 'status' => $status ] );
        }

        /**
         * It Adds menu item
         */
        public function admin_menu() {
            $parent_slug  = 'edit.php?post_type=at_biz_dir';
            $is_connected = (bool) get_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion', true );
            $is_addons     = isset( $_GET['te_view'] ) && is_scalar( $_GET['te_view'] ) && 'addons' === sanitize_key( wp_unslash( $_GET['te_view'] ) );

            add_submenu_page(
                $parent_slug,
                $is_connected && ! $is_addons ? __( 'Directorist Dashboard', 'directorist' ) : __( 'Themes & Extensions', 'directorist' ),
                $is_connected ? __( 'Dashboard', 'directorist' ) : __( 'Themes & Extensions', 'directorist' ),
                'manage_options',
                'atbdp-extension',
                [ $this, 'show_extension_view' ]
            );

            if ( ! $is_connected ) {
                return;
            }

            global $submenu;

            if ( ! empty( $submenu[ $parent_slug ] ) ) {
                foreach ( $submenu[ $parent_slug ] as $index => $item ) {
                    if ( isset( $item[2] ) && 'atbdp-extension' === $item[2] ) {
                        unset( $submenu[ $parent_slug ][ $index ] );
                        array_unshift( $submenu[ $parent_slug ], $item );
                        break;
                    }
                }
            }

            $addons_url = add_query_arg(
                [
                    'post_type' => ATBDP_POST_TYPE,
                    'page'      => 'atbdp-extension',
                    'te_view'   => 'addons',
                ],
                admin_url( 'edit.php' )
            );

            $submenu[ $parent_slug ][] = [
                __( 'Themes & Extensions', 'directorist' ),
                'manage_options',
                esc_url_raw( $addons_url ),
                __( 'Themes & Extensions', 'directorist' ),
            ];
        }

        /**
         * Keep the WordPress submenu selection aligned with the current page view.
         *
         * @param string $submenu_file Current submenu file.
         * @param string $parent_file  Current parent file.
         *
         * @return string
         */
        public function set_active_submenu( $submenu_file, $parent_file ) {
            $requested_page = isset( $_GET['page'] ) && is_scalar( $_GET['page'] )
                ? sanitize_key( wp_unslash( $_GET['page'] ) )
                : '';

            if ( 'edit.php?post_type=at_biz_dir' !== $parent_file || 'atbdp-extension' !== $requested_page ) {
                return $submenu_file;
            }

            $is_connected = (bool) get_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion', true );
            $requested    = isset( $_GET['te_view'] ) && is_scalar( $_GET['te_view'] )
                ? sanitize_key( wp_unslash( $_GET['te_view'] ) )
                : '';

            if ( ! $is_connected || 'addons' !== $requested ) {
                return 'atbdp-extension';
            }

            return add_query_arg(
                [
                    'post_type' => ATBDP_POST_TYPE,
                    'page'      => 'atbdp-extension',
                    'te_view'   => 'addons',
                ],
                admin_url( 'edit.php' )
            );
        }

        /**
         * Add CSS classes to specific menu items for separator/border styling.
         * 
         * Note: JavaScript is required because WordPress doesn't provide a PHP filter
         * to add classes to admin menu <li> elements. The menu HTML is generated by core.
         * 
         * @since 1.0
         */
        public function add_menu_separator_classes() {
            if ( ! is_admin() ) {
                return;
            }

            /**
             * Filter the URL patterns for menu items that should have separator classes.
             * 
             * @param array $url_patterns Array of URL patterns => class name suffixes.
             */
            $url_patterns = apply_filters(
                'directorist_menu_separator_patterns',
                [
                    'edit-comments.php?post_type=at_biz_dir' => 'reviews',
                    'atbdp-settings' => 'settings',
                ]
            );

            if ( empty( $url_patterns ) ) {
                return;
            }

            // Build class map
            $class_map = [];
            foreach ( $url_patterns as $pattern => $name ) {
                $class_map[ $pattern ] = 'directorist-menu-separator directorist-menu-separator-' . sanitize_html_class( $name );
            }

            ?>
            <style type="text/css">
            /**
             * Add border-top separator to specific admin menu items.
             * Uses semi-transparent white for better visibility in dark admin themes.
             */
            .wp-submenu.wp-submenu-wrap li.directorist-menu-separator {
                border-top: 1px solid rgba(255, 255, 255, 0.2) !important;
                margin-top: 8px !important;
                padding-top: 8px !important;
            }

            #adminmenu .menu-icon-at_biz_dir .wp-menu-image img{
                padding:7px 0 0 !important
            }

            </style>
            <script>
            /* Minimal JS - WordPress doesn't allow PHP to modify admin menu <li> classes */
            (function(d) {
                var map = <?php echo wp_json_encode( $class_map ); ?>;
                var patterns = Object.keys(map);
                
                function init() {
                    d.querySelectorAll('.wp-submenu.wp-submenu-wrap li:not(.wp-submenu-head)').forEach(function(li) {
                        var a = li.querySelector('a');
                        if (!a) return;
                        
                        var href = a.href || '';
                        for (var i = 0; i < patterns.length; i++) {
                            if (href.indexOf(patterns[i]) > -1) {
                                li.className += ' ' + map[patterns[i]];
                                break;
                            }
                        }
                    });
                }
                
                if (d.readyState === 'loading') {
                    d.addEventListener('DOMContentLoaded', init);
                } else {
                    init();
                }
            })(document);
            </script>
            <?php
        }

        // get_extensions_overview
        public function get_extensions_overview() {
            // Get outdated plugins via API instead of transient.
            $outdated_plugins     = $this->get_outdated_extensions_via_api();
            $outdated_plugins_key = array_keys( $outdated_plugins );

            $official_extensions = is_array( $this->extensions ) ? array_keys( $this->extensions ) : array();

            if ( ! is_array( $official_extensions ) ) {
                $official_extensions = array();
            }

            $all_installed_plugins_list = get_plugins();
            $installed_extensions       = array();
            $total_active_extensions    = 0;
            $total_outdated_extensions  = 0;

            if ( ! is_array( $all_installed_plugins_list ) ) {
                $all_installed_plugins_list = array();
            }

            // Process installed plugins.
            foreach ( $all_installed_plugins_list as $plugin_base => $plugin_data ) {
                if ( ! is_string( $plugin_base ) || ! is_array( $plugin_data ) ) {
                    continue;
                }

                $folder_base = strtok( $plugin_base, '/' );

                if ( preg_match( '/^directorist-/', $plugin_base ) && in_array( $folder_base, $official_extensions, true ) ) {
                    $installed_extensions[ $plugin_base ] = $plugin_data;

                    if ( is_plugin_active( $plugin_base ) ) {
                        $total_active_extensions++;
                    }

                    if ( in_array( $plugin_base, $outdated_plugins_key, true ) ) {
                        $total_outdated_extensions++;
                    }
                }
            }

            // Get extensions available in subscriptions.
            $extensions_available_in_subscriptions = $this->get_extensions_available_in_subscriptions(
                array(
                    'installed_extensions' => $installed_extensions,
                )
            );

            // Get promo extensions list.
            $extensions_promo_list = $this->get_extensions_promo_list(
                array(
                    'extensions_available_in_subscriptions' => $extensions_available_in_subscriptions,
                    'installed_extensions'                  => $installed_extensions,
                )
            );

            // Get required extensions list.
            $required_extensions_list = $this->prepare_the_final_requred_extension_list(
                array(
                    'installed_extension_list'              => $installed_extensions,
                    'extensions_available_in_subscriptions' => $extensions_available_in_subscriptions,
                )
            );

            $total_installed_ext_list             = count( $installed_extensions );
            $total_ext_available_in_subscriptions = count( $extensions_available_in_subscriptions );
            $total_available_extensions           = $total_installed_ext_list + $total_ext_available_in_subscriptions;

            $overview = array(
                'outdated_plugin_list'                  => $outdated_plugins,
                'outdated_plugins_key'                  => $outdated_plugins_key,
                'all_installed_plugins_list'            => $all_installed_plugins_list,
                'installed_extension_list'              => $installed_extensions,
                'total_active_extensions'               => $total_active_extensions,
                'total_outdated_extensions'             => $total_outdated_extensions,
                'extensions_promo_list'                 => $extensions_promo_list,
                'extensions_available_in_subscriptions' => $extensions_available_in_subscriptions,
                'total_available_extensions'            => $total_available_extensions,
                'required_extensions'                   => $required_extensions_list,
            );

            return $overview;
        }

        // get_extensions_available_in_subscriptions
        public function get_extensions_available_in_subscriptions( array $args = [] ) {
            $installed_extensions      = ( ! empty( $args['installed_extensions'] ) ) ? $args['installed_extensions'] : [];
            $installed_extensions_keys = $this->get_sanitized_extensions_keys( $installed_extensions );

            $extensions_available_in_subscriptions = self::get_purchased_extension_list();
            $extensions_available_in_subscriptions = ( is_array( $extensions_available_in_subscriptions ) ) ? $extensions_available_in_subscriptions : [];

            if ( ! empty( $extensions_available_in_subscriptions ) && is_array( $extensions_available_in_subscriptions ) ) {

                foreach ( $extensions_available_in_subscriptions as $base => $args ) {
                    $base_alias       = $this->get_extension_alias_key( $base );
                    $plugin_key       = preg_replace( '/(directorist-)/', '', $base );
                    $plugin_alias_key = preg_replace( '/(directorist-)/', '', $base_alias );

                    $is_in_installed_extensions       = in_array( $plugin_key, $installed_extensions_keys ) ? true : false;
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
            $installed_extensions      = ( ! empty( $args['installed_extensions'] ) ) ? $args['installed_extensions'] : [];
            $installed_extensions_keys = $this->get_sanitized_extensions_keys( $installed_extensions );

            $extensions_available_in_subscriptions      = ( ! empty( $args['extensions_available_in_subscriptions'] ) ) ? $args['extensions_available_in_subscriptions'] : [];
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
            $extensions_keys = ( is_array( $extensions_list ) ) ? array_keys( $extensions_list ) : [];

            if ( ! empty( $extensions_keys ) && is_array( $extensions_keys ) ) {

                foreach ( $extensions_keys as $index => $key ) {
                    $new_key = preg_replace( '/\/.+/', '', $key );
                    $new_key = preg_replace( '/(directorist-)/', '', $new_key );

                    $extensions_keys[ $index ] = $new_key;
                }
            }

            return $extensions_keys;
        }

        // get_themes_overview
        public function get_themes_overview() {
            // Check form theme update
            $current_theme = wp_get_theme();
            get_theme_update_available( $current_theme->stylesheet );

            $sovware_themes       = ( is_array( $this->themes ) ) ? array_keys( $this->themes ) : [];
            $theme_updates        = get_site_transient( 'update_themes' );
            $outdated_themes      = ( is_object( $theme_updates ) && isset( $theme_updates->response ) && is_array( $theme_updates->response ) ) ? $theme_updates->response : array();
            $outdated_themes_keys = ( is_array( $outdated_themes ) ) ? array_keys( $outdated_themes ) : [];

            $all_themes            = wp_get_themes();
            $active_theme_slug     = get_option( 'stylesheet' );
            $installed_theme_list  = [];
            $total_active_themes   = 0;
            $total_outdated_themes = 0;

            foreach ( $all_themes as $theme_base => $theme_data ) {

                if ( in_array( $theme_base, $sovware_themes ) ) {
                    $customizer_link = "customize.php?theme={$theme_data->stylesheet}&return=%2Fwp-admin%2Fthemes.php";
                    $customizer_link = admin_url( $customizer_link );
                    $has_theme_update  = isset( $outdated_themes[ $theme_data->stylesheet ] );
                    $theme_update_info = $has_theme_update ? $outdated_themes[ $theme_data->stylesheet ] : array();
                    $theme_new_version = '';

                    if ( is_object( $theme_update_info ) ) {
                        $theme_update_info = get_object_vars( $theme_update_info );
                    }

                    if ( is_array( $theme_update_info ) ) {
                        if ( ! empty( $theme_update_info['new_version'] ) && is_scalar( $theme_update_info['new_version'] ) ) {
                            $theme_new_version = sanitize_text_field( $theme_update_info['new_version'] );
                        } elseif ( ! empty( $theme_update_info['version'] ) && is_scalar( $theme_update_info['version'] ) ) {
                            $theme_new_version = sanitize_text_field( $theme_update_info['version'] );
                        }
                    }

                    $installed_theme_list[ $theme_base ] = [
                        'name'            => $theme_data->name,
                        'version'         => $theme_data->version,
                        'thumbnail'       => $theme_data->get_screenshot(),
                        'customizer_link' => $customizer_link,
                        'has_update'      => $has_theme_update,
                        'new_version'     => $theme_new_version,
                        'stylesheet'      => $theme_data->stylesheet,
                    ];

                    if ( $active_theme_slug === $theme_base ) {
                        $total_active_themes++;
                    }

                    if ( $has_theme_update ) {
                        $total_outdated_themes++;
                    }
                }
            }

            $installed_themes_keys = ( is_array( $installed_theme_list ) ) ? array_keys( $installed_theme_list ) : [];

            // Themes available in subscriptions
            $themes_available_in_subscriptions = self::get_purchased_theme_list();
            $themes_available_in_subscriptions = ( ! empty( $themes_available_in_subscriptions ) && is_array( $themes_available_in_subscriptions ) ) ? $themes_available_in_subscriptions : [];

            if ( ! empty( $themes_available_in_subscriptions ) ) {

                foreach ( $themes_available_in_subscriptions as $base => $args ) {
                    $item = $themes_available_in_subscriptions[ $base ];

                    // Merge Local Theme Info
                    if ( ! empty( $this->themes[ $base ] ) ) {
                        $item = array_merge( $this->themes[ $base ], $item );
                    }

                    // Merge Local Theme Info
                    if ( in_array( $base, $installed_themes_keys ) ) {
                        $item = array_merge( $installed_theme_list[ $base ], $item );
                    }

                    $is_installed         = ( in_array( $base, $installed_themes_keys ) ) ? true : false;
                    $item['is_installed'] = $is_installed;

                    $themes_available_in_subscriptions[ $base ] = $item;
                }
            }

            // total_available_themes
            $total_available_themes = count( $themes_available_in_subscriptions );

            // themes_promo_list
            $themes_promo_list = $this->get_themes_promo_list(
                [
                    'installed_theme_list'              => $installed_theme_list,
                    'themes_available_in_subscriptions' => $themes_available_in_subscriptions,
                ]
            );

            // current_active_theme_info
            $current_active_theme_info = $this->get_current_active_theme_info(
                [
                    'outdated_themes_keys' => $outdated_themes_keys,
                    'installed_theme_list' => $installed_theme_list,
                ]
            );
            $current_active_theme_info['stylesheet'];

            $themes_available_in_subscriptions_keys = array_keys( $themes_available_in_subscriptions );

            if ( in_array( $current_active_theme_info['stylesheet'], $themes_available_in_subscriptions_keys ) ) {
                unset( $themes_available_in_subscriptions[ $current_active_theme_info['stylesheet'] ] );
            }

            $overview = [
                'total_active_themes'               => $total_active_themes,
                'total_outdated_themes'             => $total_outdated_themes,
                'installed_theme_list'              => $installed_theme_list,
                'current_active_theme_info'         => $current_active_theme_info,
                'themes_promo_list'                 => $themes_promo_list,
                'themes_available_in_subscriptions' => $themes_available_in_subscriptions,
                'total_available_themes'            => $total_available_themes,
            ];

            return $overview;
        }

        // get_current_active_theme_info
        public function get_current_active_theme_info( array $args = [] ) {
            // Get Current Active Theme Info
            $current_active_theme = wp_get_theme();
            $customizer_link      = "customize.php?theme={$current_active_theme->stylesheet}&return=%2Fwp-admin%2Fthemes.php";
            $customizer_link      = admin_url( $customizer_link );

            // Check form theme update
            $active_theme_state = isset( $args['installed_theme_list'][ $current_active_theme->stylesheet ] ) ? $args['installed_theme_list'][ $current_active_theme->stylesheet ] : array();
            $has_update         = isset( $active_theme_state['has_update'] ) ? $active_theme_state['has_update'] : '';
            $new_version        = isset( $active_theme_state['new_version'] ) ? $active_theme_state['new_version'] : '';

            $active_theme_info = [
                'name'            => $current_active_theme->name,
                'version'         => $current_active_theme->version,
                'thumbnail'       => $current_active_theme->get_screenshot(),
                'customizer_link' => $customizer_link,
                'has_update'      => $has_update,
                'new_version'     => $new_version,
                'stylesheet'      => $current_active_theme->stylesheet,
            ];

            return $active_theme_info;
        }

        // get_themes_promo_list
        public function get_themes_promo_list( array $args = [] ) {
            $installed_theme_list  = ( ! empty( $args['installed_theme_list'] ) ) ? $args['installed_theme_list'] : [];
            $installed_themes_keys = $this->get_sanitized_themes_keys( $installed_theme_list );

            $themes_available_in_subscriptions      = ( ! empty( $args['themes_available_in_subscriptions'] ) ) ? $args['themes_available_in_subscriptions'] : [];
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
            $theme_keys = ( is_array( $theme_list ) ) ? array_keys( $theme_list ) : [];

            return $theme_keys;
        }

        // remote_activate_license
        public static function remote_activate_license( $license_item = [] ) {
            $status = [ 'success' => false ];

            if ( ! is_array( $license_item ) ) {
                $status['message'] = __( 'Nothing to activate', 'directorist' );

                return $status;
            }

            if ( isset( $license_item['skip_licencing'] ) && ! empty( $license_item['skip_licencing'] ) ) {
                $status['success'] = true;

                return $status;
            }

            $item_id = ( ! empty( $license_item['item_id'] ) ) ? $license_item['item_id'] : 0;
            $license = ( ! empty( $license_item['license'] ) ) ? $license_item['license'] : '';

            $activation_url = 'https://directorist.com';
            $query_args     = [
                'edd_action' => 'activate_license',
                'url'        => home_url(),
                'item_id'    => $item_id,
                'license'    => $license,
            ];

            try {
                $response = wp_remote_get(
                    $activation_url,
                    [
                        'timeout'   => 15,
                        'sslverify' => false,
                        'body'      => $query_args,
                    ]
                );

                $response_status = json_decode( $response['body'], true );
            } catch ( Exception $e ) {
                $status['success']  = false;
                $status['message']  = $e->getMessage();
                $status['response'] = null;

                return $status;
            }

            $status['response'] = $response_status;

            if ( empty( $response_status['success'] ) ) {
                $is_item_name_mismatch = isset( $response_status['error'] ) && $response_status['error'] === 'item_name_mismatch';
                $response_item_id      = isset( $response_status['item_id'] ) ? (int) $response_status['item_id'] : 0;
                $item_id               = (int) $item_id;
                
                // If item_name_mismatch but item_id matches, allow activation
                if ( $is_item_name_mismatch && $response_item_id === $item_id && ! empty( $item_id ) ) {
                    $status['success'] = true;
                    $status['message'] = __( 'License activated successfully', 'directorist' );
                } else {
                    $status['success'] = false;
                    $status['message'] = __( 'Activation failed', 'directorist' );
                }

                return $status;
            }

            $status['success'] = true;
            $status['message'] = __( 'License activated successfully', 'directorist' );

            return $status;
        }

        private static function get_remote_auth_connection_error_message() {
            return __( 'Could not reach Directorist.com. Please try again.', 'directorist' );
        }

        private static function get_remote_auth_invalid_credentials_message() {
            return __( 'The username, email address, or password is incorrect. Please check your details and try again.', 'directorist' );
        }

        private static function get_remote_auth_invalid_access_key_message() {
            return __( 'The access key is invalid. Check the key in your Directorist account and try again.', 'directorist' );
        }

        /**
         * Normalize the shared Directorist License Manager response contract.
         *
         * @param array $response_body Remote response body.
         *
         * @return array|null
         */
        private static function normalize_license_manager_response( $response_body ) {
            if ( ! is_array( $response_body ) ) {
                return null;
            }

            if ( isset( $response_body['data'] ) && is_array( $response_body['data'] ) && isset( $response_body['data']['plan_data'] ) ) {
                $response_body = $response_body['data'];
            }

            if ( empty( $response_body['plan_data'] ) || ! is_array( $response_body['plan_data'] ) ) {
                return null;
            }

            $plan_data        = $response_body['plan_data'];
            $raw_account_data = isset( $response_body['account_data'] ) && is_array( $response_body['account_data'] )
                ? $response_body['account_data']
                : [];
            $account_data     = [
                'user_id'      => isset( $raw_account_data['user_id'] ) ? absint( $raw_account_data['user_id'] ) : 0,
                'user_email'   => isset( $raw_account_data['user_email'] ) && is_scalar( $raw_account_data['user_email'] )
                    ? sanitize_email( (string) $raw_account_data['user_email'] )
                    : '',
                'display_name' => isset( $raw_account_data['display_name'] ) && is_scalar( $raw_account_data['display_name'] )
                    ? sanitize_text_field( (string) $raw_account_data['display_name'] )
                    : '',
            ];

            if (
                empty( $plan_data['downloads'] )
                || ! is_array( $plan_data['downloads'] )
                || ! isset( $plan_data['downloads']['templates'], $plan_data['downloads']['extensions'] )
                || ! is_array( $plan_data['downloads']['templates'] )
                || ! is_array( $plan_data['downloads']['extensions'] )
            ) {
                return null;
            }

            $downloads       = $plan_data['downloads'];
            $account_summary = isset( $plan_data['account_summary'] ) && is_array( $plan_data['account_summary'] )
                ? $plan_data['account_summary']
                : [];

            return [
                'success'           => true,
                'connection_method' => isset( $response_body['method'] ) && is_scalar( $response_body['method'] )
                    ? sanitize_key( (string) $response_body['method'] )
                    : '',
                'account_data'      => $account_data,
                'plan_data'         => $plan_data,
                'account_summary'   => $account_summary,
                'license_data'      => [
                    'themes'         => $downloads['templates'],
                    'plugins'        => $downloads['extensions'],
                    'account_summary' => $account_summary,
                ],
            ];
        }

        /**
         * Authenticate with a Directorist account access key.
         *
         * The key is used for this request only and is never persisted locally.
         *
         * @param string $access_key Directorist account access key.
         *
         * @return array
         */
        private static function remote_authenticate_user_by_access_key( $access_key ) {
            $url = apply_filters(
                'directorist_license_manager_access_key_api_url',
                'https://directorist.com/wp-json/directorist-license-manager/user-connect'
            );

            $response = wp_remote_post(
                $url,
                [
                    'timeout'     => 30,
                    'redirection' => 0,
                    'headers'     => [
                        'user-agent' => 'Directorist/' . md5( esc_url( home_url() ) ) . ';',
                        'Accept'     => 'application/json',
                    ],
                    'body'        => [
                        'access_key' => $access_key,
                        'domain'     => home_url(),
                    ],
                ]
            );

            if ( is_wp_error( $response ) ) {
                return [
                    'success' => false,
                    'message' => self::get_remote_auth_connection_error_message(),
                ];
            }

            $response_code = wp_remote_retrieve_response_code( $response );
            $response_body = json_decode( wp_remote_retrieve_body( $response ), true );

            if ( 422 === $response_code ) {
                return [
                    'success' => false,
                    'message' => self::get_remote_auth_invalid_access_key_message(),
                ];
            }

            if ( $response_code < 200 || $response_code >= 300 ) {
                return [
                    'success' => false,
                    'message' => self::get_remote_auth_connection_error_message(),
                ];
            }

            $normalized_response = self::normalize_license_manager_response( $response_body );

            if ( null === $normalized_response || empty( $normalized_response['account_data']['user_id'] ) ) {
                return [
                    'success' => false,
                    'message' => __( 'Directorist.com could not verify this access key. Please try again.', 'directorist' ),
                ];
            }

            $normalized_response['connection_method'] = 'access_key';

            return $normalized_response;
        }

        /**
         * Authenticate through the current Directorist License Manager API.
         *
         * Returning null allows the legacy endpoint to remain the compatibility
         * fallback when the newer route is unavailable.
         *
         * @param array $user_credentials User and password values.
         *
         * @return array|null
         */
        private static function remote_authenticate_user_v2( $user_credentials ) {
            $url = apply_filters(
                'directorist_license_manager_api_url',
                'https://directorist.com/wp-json/directorist-license-manager/user-login'
            );
            $password = array_key_exists( 'password_raw', $user_credentials ) && is_string( $user_credentials['password_raw'] )
                ? $user_credentials['password_raw']
                : ( $user_credentials['password'] ?? '' );

            $response = wp_remote_post(
                $url,
                [
                    'timeout'     => 30,
                    'redirection' => 0,
                    'headers'     => [
                        'user-agent' => 'Directorist/' . md5( esc_url( home_url() ) ) . ';',
                        'Accept'     => 'application/json',
                    ],
                    'body'        => [
                        'email'  => $user_credentials['user'] ?? '',
                        'pass'   => $password,
                        'domain' => home_url(),
                    ],
                ]
            );

            if ( is_wp_error( $response ) ) {
                return null;
            }

            $response_code = wp_remote_retrieve_response_code( $response );
            $response_body = json_decode( wp_remote_retrieve_body( $response ), true );

            if ( 422 === $response_code ) {
                if ( ! is_email( $user_credentials['user'] ?? '' ) ) {
                    return null;
                }

                return [
                    'success' => false,
                    'message' => self::get_remote_auth_invalid_credentials_message(),
                ];
            }

            if ( $response_code < 200 || $response_code >= 300 || ! is_array( $response_body ) ) {
                return null;
            }

            return self::normalize_license_manager_response( $response_body );
        }

        // remote_authenticate_user
        public static function remote_authenticate_user( $user_credentials = [] ) {
            $license_manager_response = self::remote_authenticate_user_v2( $user_credentials );
            unset( $user_credentials['password_raw'] );

            if ( null !== $license_manager_response ) {
                return $license_manager_response;
            }

            $status = [ 'success' => true ];

            $url     = 'https://directorist.com/wp-json/directorist/v1/licencing';
            $headers = [
                'user-agent' => 'Directorist/' . md5( esc_url( home_url() ) ) . ';',
                'Accept'     => 'application/json',
            ];

            $config = [
                'method'      => 'GET',
                'timeout'     => 30,
                'redirection' => 5,
                'httpversion' => '1.0',
                'headers'     => $headers,
                'cookies'     => [],
                'body'        => $user_credentials, // [ 'user' => '', 'password' => '']
            ];

            $response_body = [];

            try {
                $response = wp_remote_get( $url, $config );

                if ( is_wp_error( $response ) ) {
                    $status['success']    = false;
                    $status['error_code'] = $response->get_error_code();
                    $status['message']    = self::get_remote_auth_connection_error_message();
                } else {
                    $response_code = wp_remote_retrieve_response_code( $response );
                    $response_body = is_string( $response['body'] ) ? json_decode( $response['body'], true ) : $response['body'];

                    if ( empty( $response_body ) && in_array( $response_code, [ 401, 403 ], true ) ) {
                        $status['message'] = self::get_remote_auth_invalid_credentials_message();
                    }
                }
            } catch ( Exception $e ) {
                $status['success'] = false;
                $status['message'] = self::get_remote_auth_connection_error_message();
            }

            if ( is_array( $response_body ) ) {
                $status = array_merge( $status, $response_body );
            }

            if ( empty( $response_body['success'] ) ) {
                $status['success'] = false;

                if ( empty( $status['message'] ) && empty( $status['log'] ) ) {
                    $status['message'] = self::get_remote_auth_invalid_credentials_message();
                }
            }

            $status['response'] = $response_body;

            return $status;
        }

        // get_file_download_link
        public static function get_file_download_link( $file_item = [], $product_type = 'plugin' ) {
            if ( ! is_array( $file_item ) ) {
                return '';
            }

            if ( ! isset( $file_item['item_id'] ) ) {
                return '';
            }

            if ( ! isset( $file_item['license'] ) ) {
                return '';
            }

            if ( empty( $file_item['item_id'] ) || empty( $file_item['license'] ) ) {
                return '';
            }

            $activation_url = 'https://directorist.com/wp-json/directorist/v1/get-product-data/';
            $query_args     = [
                'product_type' => $product_type,
                'license'      => $file_item['license'],
                'item_id'      => $file_item['item_id'],
                'get_info'     => 'download_link',
            ];

            if ( ATBDP()->beta ) {
                $query_args['beta'] = true;
            }

            try {
                $response = wp_remote_get(
                    $activation_url,
                    [
                        'timeout'   => 15,
                        'sslverify' => false,
                        'body'      => $query_args,
                    ]
                );

                $response = json_decode( $response['body'], true );
            } catch ( Exception $e ) {
                return '';
            }

            $status['response'] = $response;

            if ( empty( $response['success'] ) && empty( $response['data'] ) ) {
                return '';
            }

            return $response['data'];
        }

        // get_purchased_extension_list
        public static function get_purchased_extension_list() {
            $extensions_available_in_subscriptions = get_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions', true );
            $directorist_purchased_extension_list  = apply_filters( 'directorist_purchased_extension_list', $extensions_available_in_subscriptions );

            if ( is_array( $directorist_purchased_extension_list ) ) {
                return $directorist_purchased_extension_list;
            }

            return $extensions_available_in_subscriptions;
        }

        // get_purchased_theme_list
        public static function get_purchased_theme_list() {
            $themes_available_in_subscriptions = get_user_meta( get_current_user_id(), '_themes_available_in_subscriptions', true );
            $directorist_purchased_theme_list  = apply_filters( 'directorist_purchased_theme_list', $themes_available_in_subscriptions );

            if ( is_array( $directorist_purchased_theme_list ) ) {
                return $directorist_purchased_theme_list;
            }

            return $themes_available_in_subscriptions;
        }

        // filter_product_name
        public static function filter_product_type( $product_type = '' ) {
            if ( 'plugins' === $product_type ) {
                $product_type = 'plugin';
            }

            if ( 'themes' === $product_type ) {
                $product_type = 'theme';
            }

            return $product_type;
        }

        /**
         * Store a normalized optional account summary from a remote response.
         *
         * @param array $response Remote API response.
         *
         * @return void
         */
        private function store_account_summary_from_response( $response ) {
            $candidates = [
                $response['account_summary'] ?? null,
                $response['account']['summary'] ?? null,
                $response['plan_data']['account_summary'] ?? null,
                $response['license_data']['account_summary'] ?? null,
            ];
            $summary    = null;

            foreach ( $candidates as $candidate ) {
                if ( is_array( $candidate ) ) {
                    $summary = $candidate;
                    break;
                }
            }

            if ( null === $summary ) {
                delete_user_meta( get_current_user_id(), '_atbdp_account_summary' );
                return;
            }

            $allowed_statuses = [ 'active', 'expired', 'cancelled', 'unknown' ];
            $status           = isset( $summary['subscription_status'] ) && is_scalar( $summary['subscription_status'] )
                ? sanitize_key( (string) $summary['subscription_status'] )
                : 'unknown';
            $expires_at       = isset( $summary['expires_at'] ) && is_scalar( $summary['expires_at'] )
                ? trim( (string) $summary['expires_at'] )
                : '';
            $expires_timestamp = $expires_at ? strtotime( $expires_at ) : false;
            $account_data      = isset( $response['account_data'] ) && is_array( $response['account_data'] )
                ? $response['account_data']
                : [];
            $account_email     = isset( $account_data['user_email'] ) && is_scalar( $account_data['user_email'] )
                ? sanitize_email( (string) $account_data['user_email'] )
                : '';
            $avatar_url        = isset( $summary['avatar_url'] ) && is_scalar( $summary['avatar_url'] )
                ? esc_url_raw( (string) $summary['avatar_url'] )
                : '';

            if ( ! $avatar_url && is_email( $account_email ) ) {
                $avatar_url = esc_url_raw( get_avatar_url( $account_email, [ 'size' => 64 ] ) );
            }

            $normalized = [
                'display_name'        => isset( $summary['display_name'] ) && is_scalar( $summary['display_name'] )
                    ? sanitize_text_field( (string) $summary['display_name'] )
                    : ( isset( $account_data['display_name'] ) && is_scalar( $account_data['display_name'] )
                        ? sanitize_text_field( (string) $account_data['display_name'] )
                        : null ),
                'avatar_url'          => $avatar_url ?: null,
                'plan_name'           => isset( $summary['plan_name'] ) && is_scalar( $summary['plan_name'] )
                    ? sanitize_text_field( (string) $summary['plan_name'] )
                    : null,
                'subscription_status' => in_array( $status, $allowed_statuses, true ) ? $status : 'unknown',
                'expires_at'          => false !== $expires_timestamp ? gmdate( DATE_ATOM, $expires_timestamp ) : null,
                'all_access'          => array_key_exists( 'all_access', $summary )
                    ? filter_var( $summary['all_access'], FILTER_VALIDATE_BOOLEAN )
                    : null,
                'is_lifetime'         => array_key_exists( 'is_lifetime', $summary )
                    ? filter_var( $summary['is_lifetime'], FILTER_VALIDATE_BOOLEAN )
                    : null,
            ];

            update_user_meta( get_current_user_id(), '_atbdp_account_summary', $normalized );
        }

        /**
         * Build connected account copy from authoritative summary data.
         *
         * @param array $account_summary Account summary data.
         * @param bool  $has_entitlements Whether subscribed products are available.
         *
         * @return string
         */
        private function get_dashboard_account_description( $account_summary, $has_entitlements ) {
            $status            = is_array( $account_summary ) ? ( $account_summary['subscription_status'] ?? 'unknown' ) : 'unknown';
            $plan_name         = is_array( $account_summary ) ? trim( (string) ( $account_summary['plan_name'] ?? '' ) ) : '';
            $all_access        = is_array( $account_summary ) && true === ( $account_summary['all_access'] ?? null );
            $is_lifetime       = is_array( $account_summary ) && true === ( $account_summary['is_lifetime'] ?? null );
            $expires_at        = is_array( $account_summary ) ? ( $account_summary['expires_at'] ?? null ) : null;
            $expires_timestamp = is_scalar( $expires_at ) ? strtotime( (string) $expires_at ) : false;
            $formatted_date    = false !== $expires_timestamp
                ? ( function_exists( 'wp_date' )
                    ? wp_date( get_option( 'date_format' ), $expires_timestamp )
                    : date_i18n( get_option( 'date_format' ), $expires_timestamp ) )
                : '';

            if ( 'active' === $status ) {
                if ( $all_access && $is_lifetime ) {
                    return __( 'Your lifetime plan is active, so every theme and extension is unlocked.', 'directorist' );
                }

                if ( $all_access && $formatted_date ) {
                    /* translators: %s: Subscription expiration date. */
                    return sprintf( __( 'Your plan is active until %s, so every theme and extension is unlocked.', 'directorist' ), $formatted_date );
                }

                if ( $plan_name && $formatted_date ) {
                    /* translators: 1: Plan name, 2: Subscription expiration date. */
                    return sprintf( __( 'Your %1$s plan is active until %2$s.', 'directorist' ), $plan_name, $formatted_date );
                }

                if ( $formatted_date ) {
                    /* translators: %s: Subscription expiration date. */
                    return sprintf( __( 'Your plan is active until %s.', 'directorist' ), $formatted_date );
                }
            }

            if ( 'expired' === $status ) {
                if ( $formatted_date ) {
                    /* translators: %s: Subscription expiration date. */
                    return sprintf( __( 'Your plan expired on %s. Renew it to receive subscription updates and installs.', 'directorist' ), $formatted_date );
                }

                return __( 'Your plan has expired. Renew it to receive subscription updates and installs.', 'directorist' );
            }

            if ( 'cancelled' === $status ) {
                return $formatted_date
                    ? sprintf(
                        /* translators: %s: Subscription access end date. */
                        __( 'Your plan is cancelled. Your access remains available until %s.', 'directorist' ),
                        $formatted_date
                    )
                    : __( 'Your plan is cancelled. Check your Directorist account for current access details.', 'directorist' );
            }

            return $has_entitlements
                ? __( 'Your Directorist account is connected. Your subscribed themes and extensions are ready to manage.', 'directorist' )
                : __( 'Your Directorist account is connected, but no subscribed products were found. Refresh purchases to sync your account.', 'directorist' );
        }

        /**
         * Build the connected account plan label for the dashboard footer.
         *
         * @param array $account_summary Account summary data.
         *
         * @return string
         */
        private function get_dashboard_plan_label( $account_summary ) {
            $plan_name = is_array( $account_summary ) && isset( $account_summary['plan_name'] )
                ? trim( sanitize_text_field( (string) $account_summary['plan_name'] ) )
                : '';

            if ( $plan_name ) {
                if ( preg_match( '/\bplan$/i', $plan_name ) ) {
                    return $plan_name;
                }

                /* translators: %s: Connected Directorist subscription plan name. */
                return sprintf( __( '%s plan', 'directorist' ), $plan_name );
            }

            if ( is_array( $account_summary ) && true === ( $account_summary['is_lifetime'] ?? null ) ) {
                return __( 'Lifetime plan', 'directorist' );
            }

            $status = is_array( $account_summary ) ? ( $account_summary['subscription_status'] ?? 'unknown' ) : 'unknown';

            if ( 'active' === $status ) {
                return __( 'Active plan', 'directorist' );
            }

            if ( 'expired' === $status ) {
                return __( 'Expired plan', 'directorist' );
            }

            if ( 'cancelled' === $status ) {
                return __( 'Cancelled plan', 'directorist' );
            }

            return __( 'Connected account', 'directorist' );
        }

        /**
         * Prepare the connected dashboard welcome section data.
         *
         * @param array $extensions_overview Extensions overview data.
         * @param array $themes_overview     Themes overview data.
         *
         * @return array
         */
        private function get_dashboard_welcome_data( $extensions_overview, $themes_overview ) {
            $has_entitlements = ! empty( $extensions_overview['extensions_available_in_subscriptions'] )
                || ! empty( $themes_overview['themes_available_in_subscriptions'] );
            $account_summary  = get_user_meta( get_current_user_id(), '_atbdp_account_summary', true );
            $account_summary  = is_array( $account_summary ) ? $account_summary : [];
            $connection_method = get_user_meta( get_current_user_id(), '_atbdp_subscription_connection_method', true );
            $connection_method = 'access_key' === $connection_method ? 'access_key' : 'account';
            $account_name     = isset( $account_summary['display_name'] )
                ? trim( sanitize_text_field( (string) $account_summary['display_name'] ) )
                : '';
            $account_login    = trim( (string) get_user_meta( get_current_user_id(), '_atbdp_subscribed_username', true ) );

            if ( ! $account_name && $account_login && ! is_email( $account_login ) ) {
                $account_name = sanitize_user( $account_login );
            }

            if ( is_email( $account_name ) ) {
                $account_name = '';
            }

            if ( $account_name ) {
                /* translators: %s: Connected Directorist account owner's display name. */
                $title = sprintf( __( 'Welcome back, %s', 'directorist' ), $account_name );
            } else {
                $title = __( 'Welcome back', 'directorist' );
            }

            $name_parts = $account_name ? preg_split( '/\s+/', $account_name ) : [];
            $initials   = '';

            if ( ! empty( $name_parts ) ) {
                $first_part = reset( $name_parts );
                $last_part  = end( $name_parts );
                $initials   = function_exists( 'mb_substr' )
                    ? mb_substr( (string) $first_part, 0, 1 )
                    : substr( (string) $first_part, 0, 1 );

                if ( count( $name_parts ) > 1 ) {
                    $initials .= function_exists( 'mb_substr' )
                        ? mb_substr( (string) $last_part, 0, 1 )
                        : substr( (string) $last_part, 0, 1 );
                }
            }

            $description      = $this->get_dashboard_account_description( $account_summary, $has_entitlements );
            $plugin_version   = defined( 'ATBDP_VERSION' ) ? sanitize_text_field( (string) ATBDP_VERSION ) : '';
            $whats_new_url    = apply_filters(
                'directorist_themes_extensions_whats_new_url',
                'https://directorist.com/changelog/',
                $plugin_version
            );

            $directories = directory_types();
            $directories = is_array( $directories ) && ! is_wp_error( $directories ) ? $directories : [];

            return [
                'title'               => $title,
                'description'         => $description,
                'account_name'        => $account_name,
                'account_avatar_url'  => isset( $account_summary['avatar_url'] ) ? esc_url_raw( (string) $account_summary['avatar_url'] ) : '',
                'account_initials'    => $initials
                    ? ( function_exists( 'mb_strtoupper' ) ? mb_strtoupper( $initials ) : strtoupper( $initials ) )
                    : 'D',
                'connection_method'   => $connection_method,
                'plugin_version'      => $plugin_version,
                'plan_label'          => $this->get_dashboard_plan_label( $account_summary ),
                'whats_new_url'       => esc_url_raw( (string) $whats_new_url ),
                'has_directories'     => ! empty( $directories ),
                'view_listings_url'   => ATBDP_Permalink::get_directorist_listings_page_link(),
                'primary_action_url'  => ! empty( $directories )
                    ? ATBDP_Permalink::get_add_listing_page_link()
                    : admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-directory-types&action=add_new' ),
                'primary_action_text' => ! empty( $directories )
                    ? __( 'Add listing', 'directorist' )
                    : __( 'Create directory', 'directorist' ),
            ];
        }

        /**
         * Build a Directory Builder URL for the current directory mode.
         *
         * @param int    $directory_id Directory term ID.
         * @param string $target       Optional stable Builder navigation target.
         *
         * @return string
         */
        private function get_dashboard_builder_url( $directory_id = 0, $target = '' ) {
            $is_multi_directory = directorist_is_multi_directory_enabled();
            $query_args         = [
                'post_type' => ATBDP_POST_TYPE,
                'page'      => $is_multi_directory ? 'atbdp-directory-types' : 'atbdp-layout-builder',
            ];

            if ( $is_multi_directory && $directory_id ) {
                $query_args['listing_type_id'] = absint( $directory_id );
                $query_args['action']          = 'edit';
            }

            $url    = add_query_arg( $query_args, admin_url( 'edit.php' ) );
            $target = sanitize_key( $target );

            return $target ? $url . '#' . $target : $url;
        }

        /**
         * Prepare directory-aware connected Dashboard quick actions.
         *
         * @return array
         */
        private function get_dashboard_quick_actions_data() {
            $directories = directory_types();
            $directories = is_array( $directories ) && ! is_wp_error( $directories ) ? $directories : [];
            $default_id  = (string) absint( default_directory_type() );
            $items       = [];

            foreach ( $directories as $directory ) {
                if ( ! $directory instanceof WP_Term ) {
                    continue;
                }

                $directory_id   = (string) absint( $directory->term_id );
                $directory_name = sanitize_text_field( $directory->name );
                $builder_url    = $this->get_dashboard_builder_url( $directory->term_id );

                $items[] = [
                    'id'      => $directory_id,
                    'name'    => $directory_name,
                    'actions' => [
                        'add-listing'       => [
                            'key'         => 'add-listing',
                            'label'       => __( 'Add a listing', 'directorist' ),
                            /* translators: %s: Directory type name. */
                            'description' => sprintf( __( 'Create a new %s listing', 'directorist' ), $directory_name ),
                            /* translators: %s: Directory type name. */
                            'aria_label'  => sprintf( __( 'Add a listing for %s', 'directorist' ), $directory_name ),
                            'icon'        => 'la la-plus',
                            'url'         => add_query_arg(
                                [
                                    'post_type'      => ATBDP_POST_TYPE,
                                    'directory_type' => $directory->term_id,
                                ],
                                admin_url( 'post-new.php' )
                            ),
                        ],
                        'manage-categories' => [
                            'key'         => 'manage-categories',
                            'label'       => __( 'Manage categories', 'directorist' ),
                            'description' => __( 'Organize how listings are grouped', 'directorist' ),
                            /* translators: %s: Directory type name. */
                            'aria_label'  => sprintf( __( 'Manage categories for %s', 'directorist' ), $directory_name ),
                            'icon'        => 'la la-tags',
                            'url'         => add_query_arg(
                                [
                                    'taxonomy'       => 'at_biz_dir-category',
                                    'post_type'      => ATBDP_POST_TYPE,
                                    'directory_type' => $directory->term_id,
                                ],
                                admin_url( 'edit-tags.php' )
                            ),
                        ],
                        'listing-layout'    => [
                            'key'         => 'listing-layout',
                            'label'       => __( 'Customize listing layout', 'directorist' ),
                            'description' => __( 'Design the single listing page', 'directorist' ),
                            /* translators: %s: Directory type name. */
                            'aria_label'  => sprintf( __( 'Customize the listing layout for %s', 'directorist' ), $directory_name ),
                            'icon'        => 'la la-paint-roller',
                            'url'         => $builder_url . '#single_page_layout__contents',
                        ],
                        'submission-form'   => [
                            'key'         => 'submission-form',
                            'label'       => __( 'Submission form settings', 'directorist' ),
                            'description' => __( 'Control what users can submit', 'directorist' ),
                            /* translators: %s: Directory type name. */
                            'aria_label'  => sprintf( __( 'Edit submission form settings for %s', 'directorist' ), $directory_name ),
                            'icon'        => 'la la-file-alt',
                            'url'         => $builder_url . '#submission_form',
                        ],
                    ],
                ];
            }

            $available_ids = wp_list_pluck( $items, 'id' );

            if ( $items && ! in_array( $default_id, $available_ids, true ) ) {
                $default_id = (string) $items[0]['id'];
            }

            $builder_page = directorist_is_multi_directory_enabled()
                ? add_query_arg(
                    [
                        'post_type' => ATBDP_POST_TYPE,
                        'page'      => 'atbdp-directory-types',
                        'action'    => 'add_new',
                    ],
                    admin_url( 'edit.php' )
                )
                : $this->get_dashboard_builder_url();

            return [
                'default_id'       => $default_id,
                'directories'      => $items,
                'create_directory' => [
                    'key'         => 'create-directory',
                    'label'       => __( 'Create directory', 'directorist' ),
                    'description' => __( 'Set up your first directory type', 'directorist' ),
                    'aria_label'  => __( 'Create a directory', 'directorist' ),
                    'icon'        => 'la la-folder-plus',
                    'url'         => $builder_page,
                ],
                'email'            => [
                    'key'         => 'email-notifications',
                    'label'       => __( 'Email notifications', 'directorist' ),
                    'description' => __( 'Set who gets notified, and when', 'directorist' ),
                    'aria_label'  => __( 'Manage Directorist email notifications', 'directorist' ),
                    'icon'        => 'la la-envelope',
                    'url'         => add_query_arg(
                        [
                            'post_type' => ATBDP_POST_TYPE,
                            'page'      => 'atbdp-settings',
                        ],
                        admin_url( 'edit.php' )
                    ) . '#email_settings__email_general__active_channels__disable_email_notification',
                ],
            ];
        }

        /**
         * Return a paginated connected-dashboard activity page.
         */
        public function get_dashboard_activity() {
            if ( ! current_user_can( 'manage_options' ) || ! $this->is_verified_nonce() ) {
                wp_send_json_error( [ 'message' => __( 'You are not allowed to load this activity.', 'directorist' ) ], 403 );
            }

            $page = isset( $_POST['activity_page'] ) ? absint( $_POST['activity_page'] ) : 1;
            $type = isset( $_POST['activity_type'] ) && is_scalar( $_POST['activity_type'] )
                ? sanitize_key( wp_unslash( $_POST['activity_type'] ) )
                : 'all';

            $activity = new ATBDP_Extension_Activity();

            wp_send_json_success( $activity->get_page( $page, 10, $type ) );
        }

        /**
         * It Loads Extension view
         */
        public function show_extension_view() {
            // delete_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion' );
            // delete_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion' );

            // Check Sassion
            $has_subscriptions_sassion = get_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion', true );
            $is_logged_in              = ( ! empty( $has_subscriptions_sassion ) ) ? true : false;

            $settings_url = admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-settings#extension_settings__extensions_general' );

            $extensions_overview = $this->get_extensions_overview();
            $themes_overview     = $this->get_themes_overview();
            $dashboard_activity  = $is_logged_in ? new ATBDP_Extension_Activity() : null;
            $dashboard_metrics   = $dashboard_activity ? $dashboard_activity->get_dashboard_metrics() : [];

            $hard_logout = apply_filters( 'atbdp_subscriptions_hard_logout', false );
            $hard_logout = ( $hard_logout ) ? 1 : 0;

            $data = [
                'ATBDP_Extensions'                      => $this,
                'is_logged_in'                          => $is_logged_in,
                'hard_logout'                           => $hard_logout,
                'is_beta'                               => ATBDP()->beta,

                'total_active_extensions'               => $extensions_overview['total_active_extensions'],
                'total_outdated_extensions'             => $extensions_overview['total_outdated_extensions'],
                'outdated_plugin_list'                  => $extensions_overview['outdated_plugin_list'],
                'installed_extension_list'              => $extensions_overview['installed_extension_list'],
                'extensions_available_in_subscriptions' => $extensions_overview['extensions_available_in_subscriptions'],
                'total_available_extensions'            => $extensions_overview['total_available_extensions'],
                'extensions_promo_list'                 => $extensions_overview['extensions_promo_list'],
                'required_extensions_list'              => $extensions_overview['required_extensions'],

                'total_active_themes'                   => $themes_overview['total_active_themes'],               // $my_active_themes,
                'total_outdated_themes'                 => $themes_overview['total_outdated_themes'],             // $my_outdated_themes,
                'installed_theme_list'                  => $themes_overview['installed_theme_list'],              // $installed_theme_list,
                'current_active_theme_info'             => $themes_overview['current_active_theme_info'],         // $active_theme,
                'themes_available_in_subscriptions'     => $themes_overview['themes_available_in_subscriptions'], // $themes_available_in_subscriptions,
                'total_available_themes'                => $themes_overview['total_available_themes'],
                'themes_promo_list'                     => $themes_overview['themes_promo_list'],

                'extension_list'                        => $this->extensions,
                'theme_list'                            => $this->themes,

                'settings_url'                          => $settings_url,
                'dashboard_welcome'                     => $is_logged_in ? $this->get_dashboard_welcome_data( $extensions_overview, $themes_overview ) : [],
                'dashboard_quick_actions'               => $is_logged_in ? $this->get_dashboard_quick_actions_data() : [],
                'dashboard_metrics'                     => $dashboard_metrics,
                'dashboard_setup'                       => $dashboard_activity ? $dashboard_activity->get_dashboard_setup( $dashboard_metrics ) : [],
                'dashboard_activity'                    => $dashboard_activity ? $dashboard_activity->get_page( 1, 5, 'all' ) : [],
                'dashboard_recommendations'             => $is_logged_in
                    ? ( new ATBDP_Extension_Recommendations(
                        $this->extensions,
                        $extensions_overview,
                        self::$extensions_aliases,
                        ATBDP()->beta
                    ) )->get_dashboard_data()
                    : [],
            ];

            ATBDP()->load_template( 'admin-templates/theme-extensions/theme-extension', $data );
        }

        private function is_verified_nonce() {
            $nonce = ! empty( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
            return wp_verify_nonce( $nonce, 'atbdp_nonce_action_js' );
        }

        /**
         * Check the extension is being downloaded from varified source.
         *
         * @param  string $extension_url
         *
         * @return bool
         */
        protected function is_varified_host( $extension_url ) {
            $signed_hostnames = [ 'directorist.com' ];

            return in_array( parse_url( $extension_url, PHP_URL_HOST ), $signed_hostnames, true );
        }
    }

}
