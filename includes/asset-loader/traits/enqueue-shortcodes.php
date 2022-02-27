<?php
/**
 * @author wpWax
 */

namespace Directorist\Asset_Loader;

if (!defined('ABSPATH')) exit;

trait Enqueue_Shortcodes {

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
