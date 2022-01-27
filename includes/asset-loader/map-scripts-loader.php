<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Map_Scripts_Loader {

	/**
	 * Enqueue Map scripts
	 *
	 * @return void
	 */
	public function enqueue_archive_page_map_scripts() {

		if ( Script_Helper::is_enable_map( 'openstreet' ) ) {
			$this->enqueue_openstreetmap_archive_page_scripts();
		}

		if ( Script_Helper::is_enable_map( 'google' ) ) {
			$this->enqueue_google_map_archive_page_scripts();
		}

	}

	/**
	 * Enqueue Map scripts
	 *
	 * @return void
	 */
	public function enqueue_single_listing_page_map_scripts() {

		if ( Script_Helper::is_enable_map( 'openstreet' ) ) {
			$this->enqueue_openstreetmap_single_listing_page_scripts();
		}

		if ( Script_Helper::is_enable_map( 'google' ) ) {
			$this->enqueue_google_map_single_listing_page_scripts();
		}

	}

	/**
	 * Enqueue Map scripts
	 *
	 * @return void
	 */
	public function enqueue_add_listing_page_map_scripts() {

		if ( Script_Helper::is_enable_map( 'openstreet' ) ) {
			$this->enqueue_openstreetmap_add_listing_page_scripts();
		}

		if ( Script_Helper::is_enable_map( 'google' ) ) {
			$this->enqueue_google_map_add_listing_page_scripts();
		}

	}

	/**
	 * Enqueue Map scripts
	 *
	 * @return void
	 */
	public function enqueue_openstreetmap_global_scripts( $args = [] ) {
		$default['with_cluster'] = false;
		$args = array_merge( $default, $args );

		$with_cluster = ( ! empty( $args['with_cluster'] ) ) ? true : false;

		wp_enqueue_script( 'directorist-openstreet-layers' );
		wp_enqueue_script( 'directorist-openstreet-unpkg' );
		wp_enqueue_script( 'directorist-openstreet-unpkg-index' );
		wp_enqueue_script( 'directorist-openstreet-unpkg-libs' );
		wp_enqueue_script( 'directorist-openstreet-leaflet-versions' );

		if ( $with_cluster ) {
			wp_enqueue_script( 'directorist-openstreet-leaflet-markercluster-versions' );
		}

		wp_enqueue_script( 'directorist-openstreet-libs-setup' );
		wp_enqueue_script( 'directorist-openstreet-open-layers' );
		wp_enqueue_script( 'directorist-openstreet-crosshairs' );
	}

	/**
	 * Enqueue Map scripts
	 *
	 * @return void
	 */
	public function enqueue_openstreetmap_archive_page_scripts() {
		$this->enqueue_openstreetmap_global_scripts( [ 'with_cluster' => true ] );
	}

	/**
	 * Enqueue Map scripts
	 *
	 * @return void
	 */
	public function enqueue_openstreetmap_single_listing_page_scripts() {
		$this->enqueue_openstreetmap_global_scripts();
		// wp_enqueue_script( 'directorist-single-listing-openstreet-map-custom-script' );

	}

	/**
	 * Enqueue Map scripts
	 *
	 * @return void
	 */
	public function enqueue_openstreetmap_add_listing_page_scripts() {
		$this->enqueue_openstreetmap_global_scripts();
		wp_enqueue_script( 'directorist-add-listing-openstreet-map-custom-script' );
	}

	/**
	 * Enqueue Map scripts
	 *
	 * @return void
	 */
	public function enqueue_google_map_global_scripts() {
		wp_enqueue_script( 'directorist-google-map' );
		wp_enqueue_script( 'directorist-map-view' );
	}

	/**
	 * Enqueue Map scripts
	 *
	 * @return void
	 */
	public function enqueue_google_map_archive_page_scripts() {
		$this->enqueue_google_map_global_scripts();
		wp_enqueue_script( 'directorist-gmap-marker-clusterer' );
	}

	/**
	 * Enqueue Map scripts
	 *
	 * @return void
	 */
	public function enqueue_google_map_single_listing_page_scripts() {
		$this->enqueue_google_map_global_scripts();
		// wp_enqueue_script( 'directorist-single-listing-gmap-custom-script' );
	}

	/**
	 * Enqueue Map scripts
	 *
	 * @return void
	 */
	public function enqueue_google_map_add_listing_page_scripts() {
		$this->enqueue_google_map_global_scripts();
		wp_enqueue_script( 'directorist-add-listing-gmap-custom-script' );
	}


	/**
	 * Enqueue Map scripts
	 *
	 * @return void
	 */
	public function enqueue_map_styles() {

		if ( Script_Helper::is_enable_map( 'openstreet' ) ) {
			$this->enqueue_openstreetmap_styles();
		}

	}

	/**
	 * Enqueue Openstreet Map Styles
	 *
	 * @return void
	 */
	public function enqueue_openstreetmap_styles() {
		wp_enqueue_style( 'directorist-openstreet-map-leaflet' );
		wp_enqueue_style( 'directorist-openstreet-map-openstreet' );
	}
}