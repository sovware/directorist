<?php
/**
 * @author wpWax
 */

namespace Directorist\Asset_Loader;

if ( ! defined( 'ABSPATH' ) ) exit;

class Admin_Scripts {

	public static function enqueue_builder_scripts() {
		wp_enqueue_script( 'directorist-multi-directory-builder' );
	}

	public static function enqueue_settings_scripts() {
		wp_enqueue_script( 'directorist-settings-manager' );
	}

	public static function enqueue_admin_scripts( $page ) {

		// Add Listing Page Scripts
		if ( self::is_listing_submition_page( $page ) ) {
			self::enqueue_add_listing_page_scripts();
			return;
		}

		// Listing Taxonomy Page
		if ( self::is_listing_taxonomy_page( $page ) ) {
			self::enqueue_listing_taxonomy_page_scripts();
			return;
		}

		// Plugins Page Scripts
		if ( self::is_plugins_page( $page ) ) {
			self::enqueue_plugins_page_scripts();
			return;
		}

		// Directorist Status Page Scripts
		if ( self::is_directorist_status_page( $page ) ) {
			self::enqueue_admin_common_scripts();
			return;
		}

		// Directorist Extensions Page Scripts
		if ( self::is_directorist_extensions_page( $page ) ) {
			self::enqueue_admin_common_scripts();
			return;
		}

		// Directory Builder Page Scripts
		if ( self::is_directory_builder_page( $page ) ) {
			self::enqueue_directory_builder_page_styles();
			return;
		}

		// Directory Builder Archive Page Scripts
		if ( self::is_directory_builder_archive_page( $page ) ) {
			self::enqueue_directory_builder_archive_page_scripts();
			return;
		}

		// Settings Builder Page Scripts
		if ( self::is_settings_builder_page( $page ) ) {
			self::enqueue_settings_builder_page_styles();
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
		$builder_pages = [
			'at_biz_dir_page_atbdp-layout-builder',
			'at_biz_dir_page_atbdp-directory-types',
		];

		$is_directory_builder_page = ( in_array( $page, $builder_pages ) );

		return $is_directory_builder_page;
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

	public static function enqueue_admin_common_scripts() {
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

	public static function enqueue_add_listing_page_scripts() {
		self::enqueue_admin_icon_styles();
		Helper::enqueue_map_styles();

		wp_enqueue_script( 'directorist-range-slider' );
		wp_enqueue_script( 'directorist-plupload' );

		self::enqueue_admin_common_scripts();

		Helper::enqueue_color_picker_scripts();

		// Map Scripts
        if ( Helper::map_type() == 'openstreet' ) {
            Shortcode_Scripts::enqueue_openstreet_map_scripts();
            wp_enqueue_script( 'directorist-add-listing-openstreet-map-custom-script' );
        } elseif ( Helper::map_type() == 'google' ) {
            Shortcode_Scripts::enqueue_google_map_scripts();
            wp_enqueue_script( 'directorist-add-listing-gmap-custom-script' );
        }

		wp_enqueue_script( 'directorist-add-listing' );
	}

	public static function enqueue_listing_taxonomy_page_scripts() {
		self::enqueue_admin_common_scripts();
		self::enqueue_admin_icon_styles();
	}

	public static function enqueue_plugins_page_scripts() {
		self::enqueue_admin_common_scripts();
		wp_enqueue_script( 'directorist-plugins' );
	}

	public static function enqueue_directory_builder_page_styles() {
		wp_enqueue_style( 'directorist-unicons' );
		self::enqueue_admin_icon_styles();
	}

	public static function enqueue_directory_builder_archive_page_scripts() {
		self::enqueue_admin_common_scripts();
		self::enqueue_admin_icon_styles();

		wp_enqueue_script( 'directorist-multi-directory-archive' );
	}

	public static function enqueue_settings_builder_page_styles() {
		self::enqueue_admin_common_scripts();
		wp_enqueue_style( 'directorist-font-awesome' );
	}

	public static function enqueue_admin_icon_styles() {
		wp_enqueue_style( 'directorist-line-awesome' );
		wp_enqueue_style( 'directorist-font-awesome' );
	}

}