<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Shortcode_Scripts_Loader {

	use Asset_Loader_Utility;
	use Map_Scripts_Loader;

	/**
	 * Enqueue Common Shortcode Scripts
	 *
	 * @return void
	 */
	protected function enqueue_common_shortcode_scripts() {
		// Vendor JS
		wp_enqueue_script( 'directorist-popper' );
		wp_enqueue_script( 'directorist-tooltip' );
		wp_enqueue_script( 'directorist-plasma-slider' );
		wp_enqueue_script( 'directorist-no-script' );

		// Custom JS
		wp_enqueue_script( 'directorist-global-script' );
		wp_enqueue_script( 'directorist-main-script' );
		wp_enqueue_script( 'directorist-atmodal' );
	}

	/**
	 * Enqueue Single Listing Shortcode Scripts
	 *
	 * @return void
	 */
	public function enqueue_single_listing_shortcode_scripts() {
		// Vendor Scripts
		wp_enqueue_script( 'directorist-jquery-barrating' );
		wp_enqueue_script( 'directorist-sweetalert-script' );
		// wp_enqueue_script( 'directorist-plasma-slider' );
		wp_enqueue_script( 'directorist-slick' );

		// Map Scripts
		$this->enqueue_single_listing_page_map_scripts();

		// Common Scripts
		$this->enqueue_common_shortcode_scripts();
	}

	/**
	 * Enqueue Listing Archive Shortcode Scripts
	 *
	 * [directorist_all_listing]
	 * [directorist_search_result]
	 * [directorist_category]
	 * [directorist_tag]
	 * [directorist_location]
	 *
	 * @return void
	 */
	public function enqueue_listing_archive_shortcode_scripts() {
		// Vendor Scripts
		wp_enqueue_script( 'jquery-masonry' );
		wp_enqueue_script( 'directorist-range-slider' );
		wp_enqueue_script( 'directorist-jquery-barrating' );
		wp_enqueue_script( 'directorist-select2-script' );
		wp_enqueue_script( 'directorist-geolocation' );

		// Map Scripts
		$this->enqueue_archive_page_map_scripts();

		// Common Scripts
		$this->enqueue_common_shortcode_scripts();

		// Custom Scripts
		wp_enqueue_script( 'directorist-search-listing' );
		wp_enqueue_script( 'directorist-search-form-listing' );
	}

	/**
	 * Enqueue All Taxonomy Shortcode Scripts
	 *
	 * [directorist_all_categories]
	 * [directorist_all_locations]
	 *
	 * @return void
	 */
	public function enqueue_all_taxonomy_shortcode_scripts() {
		$this->enqueue_common_shortcode_scripts();
	}

	/**
	 * Enqueue Search Listing Form Shortcode Scripts
	 *
	 * [directorist_search_listing]
	 *
	 * @return void
	 */
	public function enqueue_search_listing_form_shortcode_scripts() {
		// Vendor Scripts
		wp_enqueue_script( 'directorist-select2-script' );
		wp_enqueue_script( 'directorist-range-slider' );
		wp_enqueue_script( 'directorist-jquery-barrating' );
		wp_enqueue_script( 'directorist-geolocation' );

		// Common Scripts
		$this->enqueue_common_shortcode_scripts();

		// Custom Scripts
		wp_enqueue_script( 'directorist-search-form-listing' );
	}

	/**
	 * Enqueue Author Profile Shortcode Scripts
	 *
	 * [directorist_author_profile]
	 *
	 * @return void
	 */
	public function enqueue_author_profile_shortcode_scripts() {
		$this->enqueue_common_shortcode_scripts();
	}

	/**
	 * Enqueue User Dashboard Shortcode Scripts
	 *
	 * [directorist_user_dashboard]
	 *
	 * @return void
	 */
	public function enqueue_user_dashboard_shortcode_scripts() {
		// Vendor Scripts
		wp_enqueue_script( 'directorist-sweetalert-script' );
		wp_enqueue_script( 'directorist-ez-media-uploader' );

		// Common Scripts
		$this->enqueue_common_shortcode_scripts();
	}

	/**
	 * Enqueue All Authors Shortcode Scripts
	 *
	 * [directorist_all_authors]
	 *
	 * @return void
	 */
	public function enqueue_all_authors_shortcode_scripts() {
		$this->enqueue_common_shortcode_scripts();
	}

	/**
	 * Enqueue Add Listing Shortcode Scripts
	 *
	 * [directorist_add_listing]
	 *
	 * @return void
	 */
	public function enqueue_add_listing_shortcode_scripts() {
		// Vendor Scripts
		wp_enqueue_media();
		wp_enqueue_script( 'directorist-select2-script' );
		wp_enqueue_script( 'directorist-ez-media-uploader' );
		wp_enqueue_script( 'directorist-validator' );
		wp_enqueue_script( 'directorist-plupload' );
		wp_enqueue_script( 'directorist-sweetalert-script' );

		// Color Picker
		$this->enqueue_custom_color_picker_scripts();

		// Map Scripts
		$this->enqueue_add_listing_page_map_scripts();

		// Common Scripts
		$this->enqueue_common_shortcode_scripts();

		// Custom Scripts
		wp_enqueue_script( 'directorist-add-listing' );
	}

	/**
	 * Enqueue Shortcode Scripts
	 *
	 * [directorist_custom_registration]
	 *
	 * @return void
	 */
	public function enqueue_signup_form_shortcode_scripts() {
		// $this->enqueue_common_shortcode_scripts();
	}

	/**
	 * Enqueue Shortcode Scripts
	 *
	 * [directorist_user_login]
	 *
	 * @return void
	 */
	public function enqueue_signin_form_shortcode_scripts() {
		$this->enqueue_common_shortcode_scripts();
	}

	/**
	 * Enqueue Checkout Shortcode Scripts
	 *
	 * [directorist_checkout]
	 *
	 * @return void
	 */
	public function enqueue_checkout_shortcode_scripts() {
		$this->enqueue_common_shortcode_scripts();
	}

	/**
	 * Enqueue Payment Receipt Shortcode Scripts
	 *
	 * [directorist_payment_receipt]
	 *
	 * @return void
	 */
	public function enqueue_payment_receipt_shortcode_scripts() {
		$this->enqueue_common_shortcode_scripts();
	}

	/**
	 * Enqueue Transaction Failure Shortcode Scripts
	 *
	 * [directorist_transaction_failure]
	 *
	 * @return void
	 */
	public function enqueue_transaction_failure_shortcode_scripts() {
		$this->enqueue_common_shortcode_scripts();
	}

}