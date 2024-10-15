<?php
/**
 * Base class for loading all assets.
 *
 * @author wpWax
 */

namespace Directorist\Asset_Loader;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Asset_Loader {

	/**
	 * Initialize
	 *
	 * @return void
	 */
	public static function init() {
		// Frontend scripts
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_styles' ), 12 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_single_listing_scripts' ), 12 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'localized_data' ), 15 );

		add_action( 'enqueue_block_assets', array( __CLASS__, 'register_scripts' ) );
		add_action( 'enqueue_block_assets', array( __CLASS__, 'localized_data' ), 15 );

		// Admin Scripts
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_scripts' ), 12 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'localized_data' ), 15 );

		// Enqueue conditional scripts depending on loaded template
		add_action( 'before_directorist_template_loaded', array( __CLASS__, 'load_template_scripts' ) );
	}

	/**
	 * Enqueue all styles to all pages.
	 *
	 * @return void
	 */
	public static function enqueue_styles() {
		// Map CSS
		self::enqueue_map_styles();

		// Load all icon web-fonts if legacy-icon option is enabled
		if ( (bool) get_directorist_option( 'legacy_icon' ) ) {
			self::enqueue_icon_styles();
		}

		// CSS
		wp_enqueue_style( 'directorist-main-style' );
		wp_enqueue_style( 'directorist-support-v7-style' );
		wp_enqueue_style( 'directorist-select2-style' );
		wp_enqueue_style( 'directorist-ez-media-uploader-style' );
		wp_enqueue_style( 'directorist-swiper-style' );
		wp_enqueue_style( 'directorist-sweetalert-style' );
	}

	/**
	 * Enqueue scripts in Single listing page.
	 *
	 * @return void
	 */
	public static function enqueue_single_listing_scripts() {
		if ( ! is_singular( ATBDP_POST_TYPE ) ) {
			return;
		}

		wp_enqueue_script( 'directorist-single-listing' );

		// Reviews
		if ( directorist_is_review_enabled() ) {
			wp_enqueue_script( 'wp-hooks' );
			wp_enqueue_script( 'comment-reply' );
			wp_enqueue_script( 'directorist-jquery-barrating' );
		}
	}

	/**
	 * Enqueue conditional scripts depending on loaded template.
	 *
	 * @param string $template
	 *
	 * @return void
	 */
	public static function load_template_scripts( $template ) {

		if ( Helper::is_widget_template( $template ) && ! wp_script_is( 'directorist-widgets' ) ) {
			wp_enqueue_script( 'directorist-widgets' );
		}

		switch ( $template ) {
			// All Listings
			case 'archive-contents':
			case 'sidebar-archive-contents':
				wp_enqueue_script( 'directorist-all-listings' );
				wp_enqueue_script( 'directorist-listing-slider' );
				wp_enqueue_script( 'directorist-swiper' );
				wp_enqueue_script( 'directorist-select2-script' );
				wp_enqueue_script( 'directorist-search-form' );

				if ( Helper::instant_search_enabled() ) {
					wp_enqueue_script( 'jquery-masonry' );
					self::enqueue_map_scripts();
				}
				break;

			// Search Form
			case 'archive/search-form':
			case 'archive/advanced-search-form':
			case 'archive/basic-search-form':
			case 'search-form-contents':
			case 'search-form/adv-search':
			case 'widgets/search-form':
				wp_enqueue_script( 'directorist-search-form' );
				wp_enqueue_script( 'directorist-select2-script' );
				wp_enqueue_script( 'directorist-listing-slider' );
				wp_enqueue_script( 'directorist-swiper' );
				break;

			// Add Listing Form
			case 'listing-form/add-listing':
				wp_enqueue_script( 'directorist-select2-script' );
				wp_enqueue_script( 'directorist-add-listing' );
				break;

			// Dashboard
			case 'dashboard-contents':
				wp_enqueue_script( 'directorist-dashboard' );
				wp_enqueue_script( 'directorist-select2-script' );
				break;

			// All Authors
			case 'all-authors':
				wp_enqueue_script( 'directorist-all-authors' );
				wp_enqueue_script( 'jquery-masonry' );
				break;

			// Author Profile
			case 'author-contents':
				wp_enqueue_script( 'directorist-swiper' );
				wp_enqueue_script( 'directorist-listing-slider' );
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
				wp_enqueue_script( 'directorist-listing-slider' );
				wp_enqueue_script( 'directorist-swiper' );
				wp_enqueue_script( 'jquery-masonry' );
				break;

			case 'archive/map-view':
			case 'listing-form/fields/map':
			case 'single/fields/map':
			case 'widgets/single-map':
				self::enqueue_map_scripts();
				break;

			case 'search-form/fields/radius_search':
				wp_enqueue_script( 'directorist-range-slider' );
				break;

			case 'search-form/fields/location':
				wp_enqueue_script( 'directorist-geolocation' );

				if ( Helper::map_type() === 'google' ) {
					wp_enqueue_script( 'google-map-api' );
				}
				break;

			case 'search-form/custom-fields/color_picker':
			case 'listing-form/custom-fields/color_picker':
				wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), Helper::get_script_version() );
				wp_enqueue_script( 'wp-color-picker', admin_url( 'js/color-picker.min.js' ), array( 'iris', 'wp-i18n' ), Helper::get_script_version() );
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
				wp_enqueue_script( 'directorist-swiper' );
				wp_enqueue_script( 'directorist-listing-slider' );
				break;

			case 'single/section-related_listings':
				wp_enqueue_script( 'directorist-swiper' );
				wp_enqueue_script( 'directorist-listing-slider' );
				break;

			case 'account/login':
			case 'account/registration':
			case 'account/login-registration-form':
				wp_enqueue_script( 'directorist-account' );
				break;

			case 'payment/checkout':
			case 'payment/payment-receipt':
			case 'payment/transaction-failure':
				wp_enqueue_script( 'directorist-checkout' );
				break;
		}
	}

	/**
	 * Enqueue conditional admin scripts depending on current admin screen.
	 *
	 * @return void
	 */
	public static function admin_scripts() {

		if ( Helper::is_admin_page( 'builder-archive' ) ) {
			wp_enqueue_style( 'directorist-admin-style' );
			wp_enqueue_script( 'directorist-admin-script' );
			wp_enqueue_script( 'directorist-admin-builder-archive' );
			wp_enqueue_script( 'directorist-tooltip' );
		} elseif ( Helper::is_admin_page( 'builder-edit' ) ) {
			wp_enqueue_style( 'directorist-admin-style' );
			wp_enqueue_script( 'directorist-icon-picker' );
			wp_enqueue_style( 'directorist-unicons' );
			wp_enqueue_script( 'directorist-multi-directory-builder' );
			wp_enqueue_script('wp-tinymce');
			wp_enqueue_script('wp-media');
			wp_enqueue_media();
		} elseif ( Helper::is_admin_page( 'settings' ) ) {
			wp_enqueue_style( 'directorist-admin-style' );
			wp_enqueue_style( 'directorist-unicons' );
			wp_enqueue_script( 'directorist-settings-manager' );
			wp_enqueue_media();
		} elseif ( Helper::is_admin_page( 'support' ) ) {
			// @todo remove lineawesome dependency
			wp_enqueue_style( 'directorist-admin-style' );
			wp_enqueue_script( 'directorist-admin-script' );
			wp_enqueue_script( 'directorist-tooltip' );
		} elseif ( Helper::is_admin_page( 'extensions' ) ) {
			wp_enqueue_style( 'directorist-admin-style' );
			wp_enqueue_script( 'directorist-admin-script' );
			wp_enqueue_script( 'directorist-tooltip' );
		} elseif ( Helper::is_admin_page( 'wp-plugins' ) ) {
			wp_enqueue_style( 'directorist-admin-style' );
			wp_enqueue_script( 'directorist-plugins' );
		} elseif ( Helper::is_admin_page( 'wp-users' ) ) {
			wp_enqueue_script( 'directorist-admin-script' );
		} elseif ( Helper::is_admin_page( 'taxonomy' ) ) {
			wp_enqueue_style( 'directorist-admin-style' );
			wp_enqueue_script( 'directorist-admin-script' );
			wp_enqueue_script( 'directorist-icon-picker' );
			wp_enqueue_script( 'directorist-tooltip' );
			wp_enqueue_style( 'directorist-select2-style' );
			wp_enqueue_script( 'directorist-select2-script' );
			wp_enqueue_media();
		} elseif ( Helper::is_admin_page( 'import_export' ) ) {
			wp_enqueue_style( 'directorist-admin-style' );
			wp_enqueue_script( 'directorist-admin-script' );
			wp_enqueue_script( 'directorist-import-export' );
		} elseif ( Helper::is_admin_page( 'all_listings' ) ) {
			wp_enqueue_style( 'directorist-font-awesome' );
			wp_enqueue_style( 'directorist-admin-style' );
			wp_enqueue_script( 'directorist-admin-script' );
		} elseif ( Helper::is_admin_page( 'add_listing' ) ) {
			wp_enqueue_style( 'directorist-admin-style' );
			wp_enqueue_style( 'directorist-unicons' );
			wp_enqueue_script( 'directorist-admin-script' );
			wp_enqueue_script( 'directorist-plupload' );
			wp_enqueue_script( 'directorist-select2-script' );
			wp_enqueue_script( 'directorist-add-listing' );
			wp_enqueue_media();

			wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), Helper::get_script_version() );
			wp_enqueue_script( 'wp-color-picker', admin_url( 'js/color-picker.min.js' ), array( 'iris', 'wp-i18n' ), Helper::get_script_version() );

			self::enqueue_map_styles();

			// Inline styles
			$load_inline_style = apply_filters( 'directorist_load_inline_style', true );
			if ( $load_inline_style ) {
				wp_add_inline_style( 'directorist-admin-style', Helper::dynamic_style() );
			}
		}
	}

	public static function register_scripts() {
		Helper::register_all_scripts( Scripts::get_all_scripts() );

		// Inline styles
		if ( apply_filters( 'directorist_load_inline_style', true ) ) {
			wp_add_inline_style( 'directorist-main-style', Helper::dynamic_style() );
		}
	}

	public static function localized_data() {
		Localized_Data::load_localized_data();
	}

	/**
	 * @todo apply icon condition
	 */
	public static function enqueue_icon_styles() {
		wp_enqueue_style( 'directorist-line-awesome' );
		wp_enqueue_style( 'directorist-font-awesome' );
		wp_enqueue_style( 'directorist-unicons' );
	}

	public static function enqueue_map_styles() {
		if ( Helper::map_type() === 'openstreet' ) {
			wp_enqueue_style( 'directorist-openstreet-map-leaflet' );
			wp_enqueue_style( 'directorist-openstreet-map-openstreet' );
		}
	}

	public static function enqueue_map_scripts() {
		if ( Helper::map_type() === 'openstreet' ) {
			wp_enqueue_script( 'directorist-openstreet-map' );
		} elseif ( Helper::map_type() === 'google' ) {
			wp_enqueue_script( 'directorist-google-map' );
		}
	}
}
