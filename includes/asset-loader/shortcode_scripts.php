<?php
/**
 * @author wpWax
 */

namespace Directorist\Asset_Loader;

if (!defined('ABSPATH')) exit;

class Shortcode_Scripts {

    /**
     * @todo write conditions for grid/list/map view
     *
     * @param object $listings
     *
     * @return void
     */
    public static function all_listings( $listings ) {

        wp_enqueue_script('jquery-masonry');
        wp_enqueue_script('directorist-range-slider');
        wp_enqueue_script('directorist-jquery-barrating');
        wp_enqueue_script('directorist-select2-script');
        wp_enqueue_script('directorist-geolocation');

        // Map Scripts
        if ( Asset_Helper::map_type() == 'openstreet' ) {
            Asset_Helper::enqueue_openstreet_map_scripts( true );
        } elseif ( Asset_Helper::map_type() == 'google' ) {
            Asset_Helper::enqueue_google_map_scripts();
        }

        // Common Scripts
		self::enqueue_common_shortcode_scripts();

        // Search
        wp_enqueue_script('directorist-search-listing');
        wp_enqueue_script('directorist-search-form-listing');
    }

    public static function search_form() {

    }

    public static function single_listing() {

		wp_enqueue_script( 'directorist-jquery-barrating' );
		wp_enqueue_script( 'directorist-sweetalert-script' );
		wp_enqueue_script( 'directorist-slick' );

		// Map Scripts
        if ( Asset_Helper::map_type() == 'openstreet' ) {
            Asset_Helper::enqueue_openstreet_map_scripts();
        } elseif ( Asset_Helper::map_type() == 'google' ) {
            Asset_Helper::enqueue_google_map_scripts();
        }

		// Common Scripts
		self::enqueue_common_shortcode_scripts();


    }

    public static function add_listing(  $listing  ) {
		// Common Scripts
		self::enqueue_common_shortcode_scripts();

		wp_enqueue_media();
		wp_enqueue_script( 'directorist-select2-script' );
		wp_enqueue_script( 'directorist-ez-media-uploader' );
		wp_enqueue_script( 'directorist-validator' );
		wp_enqueue_script( 'directorist-plupload' );
		wp_enqueue_script( 'directorist-sweetalert-script' );

		// Color Picker
		Asset_Helper::enqueue_color_picker_scripts();

		// Map Scripts
        if ( Asset_Helper::map_type() == 'openstreet' ) {
            Asset_Helper::enqueue_openstreet_map_scripts();
            wp_enqueue_script( 'directorist-add-listing-openstreet-map-custom-script' );
        } elseif ( Asset_Helper::map_type() == 'google' ) {
            Asset_Helper::enqueue_google_map_scripts();
            wp_enqueue_script( 'directorist-add-listing-gmap-custom-script' );
        }

		// Custom Scripts
		wp_enqueue_script( 'directorist-add-listing' );
    }

    /**
     * @todo remove this method
     *
     * @return void
     */
	public static function enqueue_common_shortcode_scripts() {
        wp_enqueue_script( 'directorist-main-script' );
		wp_enqueue_script( 'directorist-popper' );
		wp_enqueue_script( 'directorist-tooltip' );
		wp_enqueue_script( 'directorist-plasma-slider' );
		wp_enqueue_script( 'directorist-no-script' );
		wp_enqueue_script( 'directorist-global-script' );
		wp_enqueue_script( 'directorist-atmodal' );
	}


}
