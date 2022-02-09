<?php
/**
 * @author wpWax
 */

namespace Directorist\Asset_Loader;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Enqueue_Admin {

	public static function admin_builder_scripts() {
		wp_enqueue_script( 'directorist-multi-directory-builder' );
	}

	public static function admin_settings_scripts() {
		wp_enqueue_script( 'directorist-settings-manager' );
	}

	public static function admin_scripts( $page ) {

		switch ( $page ) {
			// Directorist add Listing Page
			case 'post.php':
			case 'post-new.php':
				$is_add_listing_page = ( get_post_type( get_the_ID() ) === 'at_biz_dir' ) ? true : false;
				if ( $is_add_listing_page ) {
					self::admin_add_listing_page_scripts();
				}
				break;

			// Directorist taxonomy Page
			case 'term.php':
			case 'edit-tags.php':
				$listing_taxonomies       = [ 'at_biz_dir-category', 'at_biz_dir-location', 'at_biz_dir-tags' ];
				$has_listing_taxonomy     = ( isset( $_GET['taxonomy'] ) && in_array( $_GET['taxonomy'], $listing_taxonomies ) );
				if ( $has_listing_taxonomy ) {
					self::listing_taxonomy_page_scripts();
				}
				break;

			// Directory Builder Archive Page
			case 'at_biz_dir_page_atbdp-directory-types':
				self::directory_builder_archive_page_scripts();
				break;

			// Directory Builder Page
			case 'at_biz_dir_page_atbdp-layout-builder':
				self::directory_builder_page_styles();
				break;



			// Directorist Settings Page
			case 'at_biz_dir_page_atbdp-settings':
				self::settings_builder_page_styles();
				break;
				
			// Plugins Page
			case 'plugins.php':
				self::plugins_page_scripts();
				break;
			
			// Directorist Status Page
			case 'at_biz_dir_page_directorist-status':
				self::admin_common_scripts();
				break;

			// Directorist Extensions Page
			case 'at_biz_dir_page_atbdp-extension':
				self::admin_common_scripts();
				break;





		}
	}

	public static function admin_common_scripts() {
		// CSS
		wp_enqueue_style( 'directorist-admin-style' );

		// JS
		wp_enqueue_script( 'jquery' );
		wp_enqueue_media();
		wp_enqueue_script( 'directorist-no-script' );
		wp_enqueue_script( 'directorist-global-script' );

		// Bootstrap
		wp_enqueue_script( 'directorist-popper' );
		wp_enqueue_script( 'directorist-tooltip' );

		// Select 2
		wp_enqueue_style( 'directorist-select2-style' );
		wp_enqueue_script( 'directorist-select2-script' );

		// Sweetalert
		wp_enqueue_style( 'directorist-sweetalert-style' );
		wp_enqueue_script( 'directorist-sweetalert-script' );

		wp_enqueue_script( 'directorist-admin-script' );
	}

	public static function admin_add_listing_page_scripts() {
		self::admin_icon_styles();
		Enqueue::map_styles();

		wp_enqueue_script( 'directorist-range-slider' );
		wp_enqueue_script( 'directorist-plupload' );

		self::admin_common_scripts();

		Enqueue::color_picker_scripts();

		// Map Scripts
        if ( Helper::map_type() == 'openstreet' ) {
            self::enqueue_openstreet_map_scripts();
            wp_enqueue_script( 'directorist-add-listing-openstreet-map-custom-script' );
        } elseif ( Helper::map_type() == 'google' ) {
            self::enqueue_google_map_scripts();
            wp_enqueue_script( 'directorist-add-listing-gmap-custom-script' );
        }

		wp_enqueue_script( 'directorist-add-listing' );
	}

	public static function listing_taxonomy_page_scripts() {
		self::admin_common_scripts();
		self::admin_icon_styles();
	}

	public static function plugins_page_scripts() {
		self::admin_common_scripts();
		wp_enqueue_script( 'directorist-plugins' );
	}

	public static function directory_builder_page_styles() {
		wp_enqueue_style( 'directorist-unicons' );
		wp_enqueue_style( 'directorist-admin-style' );
		self::admin_icon_styles();
	}

	public static function directory_builder_archive_page_scripts() {
		self::admin_common_scripts();
		self::admin_icon_styles();

		wp_enqueue_script( 'directorist-multi-directory-archive' );
	}

	public static function settings_builder_page_styles() {
		self::admin_common_scripts();
		wp_enqueue_style( 'directorist-font-awesome' );
	}

	public static function admin_icon_styles() {
		wp_enqueue_style( 'directorist-line-awesome' );
		wp_enqueue_style( 'directorist-font-awesome' );
	}

}