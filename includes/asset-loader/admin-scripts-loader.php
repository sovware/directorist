<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Admin_Scripts_Loader {

	use Map_Scripts_Loader;
	use Asset_Loader_Utility;

	/**
	 * Enqueue admin scripts
	 *
	 * @return void
	 */
	public function enqueue_admin_scripts( $page = '' ) {

		// Add Listing Page Scripts
		if ( $this->is_listing_submition_page( $page ) ) {
			$this->enqueue_add_listing_page_scripts();
			return;
		}

		// Listing Taxonomy Page
		if ( $this->is_listing_taxonomy_page( $page ) ) {
			$this->enqueue_listing_taxonomy_page_scripts();
			return;
		}

		// Plugins Page Scripts
		if ( $this->is_plugins_page( $page ) ) {
			$this->enqueue_plugins_page_scripts();
			return;
		}

		// Directorist Status Page Scripts
		if ( $this->is_directorist_status_page( $page ) ) {
			$this->enqueue_admin_common_scripts();
			return;
		}

		// Directorist Extensions Page Scripts
		if ( $this->is_directorist_extensions_page( $page ) ) {
			$this->enqueue_admin_common_scripts();
			return;
		}

		// Directory Builder Page Scripts
		if ( $this->is_directory_builder_page( $page ) ) {
			$this->enqueue_directory_builder_page_styles();
			return;
		}

		// Directory Builder Archive Page Scripts
		if ( $this->is_directory_builder_archive_page( $page ) ) {
			$this->enqueue_directory_builder_archive_page_scripts();
			return;
		}

		// Settings Builder Page Scripts
		if ( $this->is_settings_builder_page( $page ) ) {
			$this->enqueue_settings_builder_page_styles();
			return;
		}
	}

	/**
	 * Is Listing Submition Page
	 *
	 * @return bool $is_listing_submition_page
	 */
	public function is_listing_submition_page( $page = '' ) {
		$single_post_pages         = [ 'post.php', 'post-new.php' ];
		$is_add_listing_page       = ( get_post_type( get_the_ID() ) === 'at_biz_dir' ) ? true : false;
		$is_listing_submition_page = ( in_array( $page, $single_post_pages ) && $is_add_listing_page );

		return $is_listing_submition_page;
	}

	/**
	 * Is Listing Taxonomy Page
	 *
	 * @return bool $is_listing_taxonomy_page
	 */
	public function is_listing_taxonomy_page( $page = '' ) {
		$listing_taxonomies       = [ 'at_biz_dir-category', 'at_biz_dir-location', 'at_biz_dir-tags' ];
		$has_listing_taxonomy     = ( isset( $_GET['taxonomy'] ) && in_array( $_GET['taxonomy'], $listing_taxonomies ) );
		$is_listing_taxonomy_page = ( in_array( $page, [ 'term.php', 'edit-tags.php' ] )  && $has_listing_taxonomy );

		return $is_listing_taxonomy_page;
	}

	/**
	 * Is Directory Builder Page
	 *
	 * @return bool $is_directory_builder_page
	 */
	public function is_directory_builder_page( $page = '' ) {
		return ( 'at_biz_dir_page_atbdp-layout-builder' === $page );
	}

	/**
	 * Is Directory Builder Archive Page
	 *
	 * @return bool $is_directory_builder_archive_page
	 */
	public function is_directory_builder_archive_page( $page = '' ) {
		return ( 'at_biz_dir_page_atbdp-directory-types' === $page );
	}

	/**
	 * Is Settings Builder Page
	 *
	 * @return bool $is_settings_builder_page
	 */
	public function is_settings_builder_page( $page = '' ) {
		return ( 'at_biz_dir_page_atbdp-settings' === $page );
	}

	/**
	 * Is Plugins Page
	 *
	 * @return bool $is_plugins_page
	 */
	public function is_plugins_page( $page = '' ) {
		return ( 'plugins.php' === $page );
	}

	/**
	 * Is Directorist Status Page
	 *
	 * @return bool $is_directorist_status_page
	 */
	public function is_directorist_status_page( $page = '' ) {
		return ( 'at_biz_dir_page_directorist-status' === $page );
	}

	/**
	 * Is Directorist Extensions Page
	 *
	 * @return bool $is_directorist_extensions_page
	 */
	public function is_directorist_extensions_page( $page = '' ) {
		return ( 'at_biz_dir_page_atbdp-extension' === $page );
	}

	/**
	 * Enqueue Scripts
	 *
	 * @return void
	 */
	public function enqueue_admin_common_scripts() {
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

	/**
	 * Enqueue Scripts
	 *
	 * @return void
	 */
	public function enqueue_add_listing_page_scripts() {
		wp_enqueue_script( 'directorist-range-slider' );
		wp_enqueue_script( 'directorist-plupload' );

		$this->enqueue_admin_common_scripts();
		$this->enqueue_admin_icon_styles();

		$this->enqueue_custom_color_picker_scripts();

		$this->enqueue_map_styles();
		$this->enqueue_add_listing_page_map_scripts();

		wp_enqueue_script( 'directorist-add-listing' );
	}

	/**
	 * Enqueue Scripts
	 *
	 * @return void
	 */
	public function enqueue_listing_taxonomy_page_scripts() {
		$this->enqueue_admin_common_scripts();
		$this->enqueue_admin_icon_styles();
	}

	/**
	 * Directory Plugins Page Scripts
	 *
	 * @return void
	 */
	public function enqueue_plugins_page_scripts() {
		$this->enqueue_admin_common_scripts();
		wp_enqueue_script( 'directorist-plugins' );
	}

	/**
	 * Directory Builder Styles
	 *
	 * @return void
	 */
	public function enqueue_directory_builder_page_styles() {
		wp_enqueue_style( 'directorist-unicons' );
		wp_enqueue_style( 'directorist-admin-style' );
		$this->enqueue_admin_icon_styles();
	}

	/**
	 * Directory Builder Scripts
	 *
	 * @return void
	 */
	public function enqueue_directory_builder_page_scripts() {
		wp_enqueue_script( 'directorist-multi-directory-builder' );
	}

	/**
	 * Directory Builder Archive Scripts
	 *
	 * @return void
	 */
	public function enqueue_directory_builder_archive_page_scripts() {
		$this->enqueue_admin_common_scripts();
		$this->enqueue_admin_icon_styles();

		wp_enqueue_script( 'directorist-multi-directory-archive' );
	}

	/**
	 * Settings Builder Styles
	 *
	 * @return void
	 */
	public function enqueue_settings_builder_page_styles() {
		$this->enqueue_admin_common_scripts();
		wp_enqueue_style( 'directorist-font-awesome' );
	}

	/**
	 * Settings Builder Scripts
	 *
	 * @return void
	 */
	public function enqueue_settings_builder_page_scripts() {
		wp_enqueue_script( 'directorist-settings-manager' );
	}

	/**
	 * Icon Scripts
	 *
	 * @return void
	 */
	public function enqueue_admin_icon_styles() {
		wp_enqueue_style( 'directorist-line-awesome' );
		wp_enqueue_style( 'directorist-font-awesome' );
	}
}