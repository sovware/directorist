<?php
/**
 * Base class for loading all assets.
 *
 * @author wpWax
 */

namespace Directorist\Asset_Loader;

if ( ! defined( 'ABSPATH' ) ) exit;

class Init {

	public static $instance = null;

	public $version;
	public $scripts;

	private function __construct() {
		$this->version = Helper::debug_enabled() ? time() : DIRECTORIST_SCRIPT_VERSION;
		$this->scripts = Scripts::all_scripts();

		// Frontend
		add_action( 'wp_enqueue_scripts',    [ $this, 'register_scripts' ] );
		add_action( 'wp_enqueue_scripts',    [ $this, 'enqueue_styles' ], 12 );
		add_action( 'wp_enqueue_scripts',    [ $this, 'enqueue_single_listing_scripts' ], 12 );
		add_action( 'wp_enqueue_scripts',    [ $this, 'localized_data' ], 15 );

		add_action( 'before_directorist_template_loaded',  [ $this, 'load_template_scripts' ] );

		// Admin
		add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ], 12 );
		add_action( 'admin_enqueue_scripts', [ $this, 'localized_data' ], 15 );

		add_filter( 'script_loader_tag', array( $this, 'defer_load_js' ), 10, 2 );
	}

	/**
	 * Singletone instance
	 */
	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function enqueue_styles() {
		// Map CSS
		Enqueue::map_styles();

		// Icon CSS
		Enqueue::icon_styles();

		// CSS
		wp_enqueue_style( 'directorist-main-style' );
		wp_enqueue_style( 'directorist-inline-style' );
		wp_enqueue_style( 'directorist-settings-style' );
		wp_enqueue_style( 'directorist-select2-style' );
		wp_enqueue_style( 'directorist-ez-media-uploader-style' );
		wp_enqueue_style( 'directorist-plasma-slider' );
		wp_enqueue_style( 'directorist-slick-style' );
		wp_enqueue_style( 'directorist-sweetalert-style' );

		// Inline styles
		wp_add_inline_style( 'directorist-main-style', Helper::dynamic_style() );
	}

	public function enqueue_single_listing_scripts() {
		if ( ! is_singular( ATBDP_POST_TYPE ) ) {
			return;
		}

		wp_enqueue_script( 'directorist-single-listing' );

		// Reviews
		if ( directorist_is_review_enabled() ) {
			wp_enqueue_script( 'comment-reply' );
			wp_enqueue_script( 'directorist-jquery-barrating' );
		}
	}

	public function load_template_scripts( $template ) {

		switch ( $template ) {
			// All Listings
			case 'archive-contents':
				wp_enqueue_script( 'directorist-all-listings' );
				wp_enqueue_script( 'directorist-select2-script' );
				break;

			// Search Form
			case 'archive/search-form':
			case 'search-form-contents':
			case 'search-form/adv-search':
				wp_enqueue_script( 'directorist-search-form' );
				wp_enqueue_script( 'directorist-select2-script' );
				break;

			// Add Listing Form
			case 'listing-form/add-listing':
				wp_enqueue_script( 'directorist-select2-script' );
				wp_enqueue_script( 'directorist-add-listing' );
				break;

			// Dashboard
			case 'dashboard-contents':
				wp_enqueue_script( 'directorist-dashboard' );
				break;

			// All Authors
			case 'all-authors':
				wp_enqueue_script( 'directorist-all-authors' );
				wp_enqueue_script( 'jquery-masonry' );
				break;

			// Author Profile
			case 'author-contents':
				wp_enqueue_script( 'directorist-author-profile' );
				break;

			// All Location/Category
			case 'taxonomies/categories-grid':
			case 'taxonomies/categories-list':
			case 'taxonomies/locations-grid':
			case 'taxonomies/locations-list':
				wp_enqueue_script( 'directorist-all-location-category' );
				break;

			case 'archive/grid-view':
				/**
				 * @todo load based on Listings::has_masonry() condition.
				 */
				wp_enqueue_script( 'jquery-masonry' );
				break;

			case 'archive/map-view':
			case 'listing-form/fields/map':
			case 'single/fields/map':
				$map_type = get_directorist_option( 'select_listing_map', 'openstreet' );

				if ( $map_type == 'openstreet' ) {
					wp_enqueue_script( 'directorist-openstreet-map' );
				} elseif ( $map_type == 'google' ) {
					wp_enqueue_script( 'directorist-google-map' );
				}
				break;

			case 'search-form/fields/radius_search':
				wp_enqueue_script( 'directorist-range-slider' );
				break;

			case 'search-form/fields/location':
				wp_enqueue_script( 'directorist-geolocation' );

				$map_type = get_directorist_option( 'select_listing_map', 'openstreet' );

				if ( $map_type == 'google' ) {
					wp_enqueue_script( 'google-map-api' );
				}

				break;

			case 'search-form/custom-fields/color_picker':
			case 'listing-form/custom-fields/color_picker':
				wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ) );
				wp_enqueue_script( 'wp-color-picker', admin_url( 'js/color-picker.min.js' ), array( 'iris', 'wp-i18n' ) );
				break;

			case 'listing-form/fields/address':
				wp_enqueue_script( 'directorist-geolocation' );
				break;

			case 'listing-form/fields/image_upload':
			case 'dashboard/profile-pic':
				wp_enqueue_script( 'directorist-ez-media-uploader' );
				break;

			case 'listing-form/custom-fields/file':
				wp_enqueue_script( 'directorist-plupload' );
				break;

			case 'listing-form/fields/social_info':
			case 'dashboard/listing-row':
				wp_enqueue_script( 'directorist-sweetalert' );
				break;

			case 'single/slider':
				wp_enqueue_script( 'directorist-plasma-slider' );
				break;

			case 'single/section-related_listings':
				wp_enqueue_script( 'directorist-slick' );
				break;

			case 'account/login':
			case 'account/registration':
				wp_enqueue_script( 'directorist-account' );
				break;

			case 'payment/checkout':
			case 'payment/payment-receipt':
			case 'payment/transaction-failure':
				wp_enqueue_script( 'directorist-checkout' );
				break;

		}
	}

	public function admin_scripts( $page = '' ) {

		if ( Helper::is_admin_page( 'builder-archive' ) ) {
			wp_enqueue_style( 'directorist-select2-style' ); // remove - select2 reference from admin script
			wp_enqueue_script( 'directorist-select2-script' ); // remove - select2 reference from admin script
			wp_enqueue_style( 'directorist-unicons' ); //  use svg instead
			wp_enqueue_style( 'directorist-font-awesome' );

			wp_enqueue_style( 'directorist-admin-style' );
			wp_enqueue_script( 'directorist-tooltip' ); // order change error
			wp_enqueue_script( 'directorist-admin-script' );
			wp_enqueue_script( 'directorist-multi-directory-archive' );
		} elseif ( Helper::is_admin_page( 'builder-edit-multi' ) ) {
			// wp_enqueue_script( 'directorist-multi-directory-builder' ); // loaded inside class

			wp_enqueue_style( 'directorist-font-awesome' );
			wp_enqueue_style( 'directorist-unicons' ); //  use svg instead
			wp_enqueue_style( 'directorist-admin-style' );
		} else {
			Enqueue::map_styles();
			Enqueue::admin_scripts( $page );
		}

	}

	public function register_scripts() {
		foreach ( $this->scripts as $handle => $script ) {
			Helper::register_single_script( $handle, $script, $this->version );
		}
	}

	public function localized_data() {
		Localized_Data::load_localized_data();
	}

	public function defer_load_js( $tag, $handle ) {

		$scripts = array_filter( $this->scripts, function( $script ) {
			return $script['type'] == 'js' ? true : false;
		} );
		$scripts = array_keys( $scripts );

		if ( in_array( $handle, $scripts ) ) {
			return str_replace(' src', ' defer="defer" src', $tag );
		}

		return $tag;
	}
}