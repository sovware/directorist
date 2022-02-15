<?php
/**
 * @author wpWax
 */

namespace Directorist\Asset_Loader;

if (!defined('ABSPATH')) exit;

trait Enqueue_Shortcodes {

    public static function all_listings( $listings ) {

        wp_enqueue_script('directorist-all-listings');

		wp_enqueue_script('jquery-masonry');
        wp_enqueue_script('directorist-range-slider');
        wp_enqueue_script('directorist-jquery-barrating');
        wp_enqueue_script('directorist-select2-script');
        wp_enqueue_script('directorist-geolocation');

        // Map Scripts
        if ( Helper::map_type() == 'openstreet' ) {
            self::openstreet_map_scripts( true );
        } elseif ( Helper::map_type() == 'google' ) {
            self::google_map_scripts();
        }

        // Search
        wp_enqueue_script('directorist-search-listing');
        wp_enqueue_script('directorist-search-form-listing');
    }

    public static function search_form( $searchform ) {

		wp_enqueue_script( 'directorist-select2-script' );
		wp_enqueue_script( 'directorist-range-slider' );
		wp_enqueue_script( 'directorist-jquery-barrating' );
		wp_enqueue_script( 'directorist-geolocation' );
        
		// Custom Scripts
		wp_enqueue_script( 'directorist-search-form-listing' );
    }

    public static function single_listing() {

		wp_enqueue_script( 'directorist-jquery-barrating' );
		wp_enqueue_script( 'directorist-sweetalert-script' );
		wp_enqueue_script( 'directorist-slick' );

		// Map Scripts
        if ( Helper::map_type() == 'openstreet' ) {
            self::openstreet_map_scripts();
        } elseif ( Helper::map_type() == 'google' ) {
            self::google_map_scripts();
        }
    }

    public static function add_listing(  $listing  ) {

		wp_enqueue_media();
		wp_enqueue_script( 'directorist-select2-script' );
		wp_enqueue_script( 'directorist-ez-media-uploader' );
		wp_enqueue_script( 'directorist-validator' );
		wp_enqueue_script( 'directorist-plupload' );
		wp_enqueue_script( 'directorist-sweetalert-script' );

		// Color Picker
		Enqueue::color_picker_scripts();

		// Map Scripts
        if ( Helper::map_type() == 'openstreet' ) {
            self::openstreet_map_scripts();
            wp_enqueue_script( 'directorist-add-listing-openstreet-map-custom-script' );
        } elseif ( Helper::map_type() == 'google' ) {
            self::google_map_scripts();
            wp_enqueue_script( 'directorist-add-listing-gmap-custom-script' );
        }

		// Custom Scripts
		wp_enqueue_script( 'directorist-add-listing' );
    }

	public static function dashboard( $dashboard  ) {
		wp_enqueue_script( 'directorist-sweetalert-script' );
		wp_enqueue_script( 'directorist-ez-media-uploader' );
	}
}
