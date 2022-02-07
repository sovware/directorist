<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Widget_Scripts_Loader {

	use Asset_Loader_Utility;
	use Map_Scripts_Loader;

	/**
	 * Enqueue Common Widget Scripts
	 *
	 * @return void
	 */
	protected function enqueue_common_widget_scripts() {

	}

	/**
	 * Enqueue Search Listing Form Widget Scripts
	 *
	 * @return void
	 */
	public function enqueue_search_listing_form_widget_scripts() {
		// Vendor Scripts
		wp_enqueue_script( 'directorist-select2-script' );
		wp_enqueue_script( 'directorist-range-slider' );
		wp_enqueue_script( 'directorist-jquery-barrating' );
		wp_enqueue_script( 'directorist-geolocation-widget' );

		// Common Scripts
		$this->enqueue_common_widget_scripts();

		// Custom Scripts
		wp_enqueue_script( 'directorist-search-form-listing' );
		wp_enqueue_script( 'directorist-search-listing' );

	}
}