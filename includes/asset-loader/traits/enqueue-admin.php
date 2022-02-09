<?php
/**
 * @author wpWax
 */

namespace Directorist\Asset_Loader;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Enqueue_Admin {

	public static function builder_scripts() {
		wp_enqueue_script( 'directorist-multi-directory-builder' );
	}

	public static function settings_scripts() {
		wp_enqueue_script( 'directorist-settings-manager' );
	}

	public static function admin_scripts( $page ) {

		// Add Listing Page Scripts
		if ( self::is_listing_submition_page( $page ) ) {
			self::add_listing_page_scripts();
			return;
		}

		// Listing Taxonomy Page
		if ( self::is_listing_taxonomy_page( $page ) ) {
			self::listing_taxonomy_page_scripts();
			return;
		}

		// Plugins Page Scripts
		if ( self::is_plugins_page( $page ) ) {
			self::plugins_page_scripts();
			return;
		}

		// Directorist Status Page Scripts
		if ( self::is_directorist_status_page( $page ) ) {
			self::admin_common_scripts();
			return;
		}

		// Directorist Extensions Page Scripts
		if ( self::is_directorist_extensions_page( $page ) ) {
			self::admin_common_scripts();
			return;
		}

		// Directory Builder Page Scripts
		if ( self::is_directory_builder_page( $page ) ) {
			self::directory_builder_page_styles();
			return;
		}

		// Directory Builder Archive Page Scripts
		if ( self::is_directory_builder_archive_page( $page ) ) {
			self::directory_builder_archive_page_scripts();
			return;
		}

		// Settings Builder Page Scripts
		if ( self::is_settings_builder_page( $page ) ) {
			self::settings_builder_page_styles();
			return;
		}
	}

	public static function is_listing_submition_page( $page = '' ) {
		$single_post_pages         = [ 'post.php', 'post-new.php' ];
		$is_add_listing_page       = ( get_post_type( get_the_ID() ) === 'at_biz_dir' ) ? true : false;
		$is_listing_submition_page = ( in_array( $page, $single_post_pages ) && $is_add_listing_page );

		return $is_listing_submition_page;
	}

	public static function is_listing_taxonomy_page( $page = '' ) {
		$listing_taxonomies       = [ 'at_biz_dir-category', 'at_biz_dir-location', 'at_biz_dir-tags' ];
		$has_listing_taxonomy     = ( isset( $_GET['taxonomy'] ) && in_array( $_GET['taxonomy'], $listing_taxonomies ) );
		$is_listing_taxonomy_page = ( in_array( $page, [ 'term.php', 'edit-tags.php' ] )  && $has_listing_taxonomy );

		return $is_listing_taxonomy_page;
	}

	public static function is_directory_builder_page( $page = '' ) {
		return ( 'at_biz_dir_page_atbdp-layout-builder' === $page );
	}

	public static function is_directory_builder_archive_page( $page = '' ) {
		return ( 'at_biz_dir_page_atbdp-directory-types' === $page );
	}

	public static function is_settings_builder_page( $page = '' ) {
		return ( 'at_biz_dir_page_atbdp-settings' === $page );
	}

	public static function is_plugins_page( $page = '' ) {
		return ( 'plugins.php' === $page );
	}

	public static function is_directorist_status_page( $page = '' ) {
		return ( 'at_biz_dir_page_directorist-status' === $page );
	}

	public static function is_directorist_extensions_page( $page = '' ) {
		return ( 'at_biz_dir_page_atbdp-extension' === $page );
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

	public static function add_listing_page_scripts() {
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