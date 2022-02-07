<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Public_Scripts_Loader {

	use Map_Scripts_Loader;
	use Asset_Loader_Utility;
	use Shortcode_Scripts_Loader;

	/**
	 * Enqueue public scripts
	 *
	 * @return void
	 */
	public function enqueue_public_scripts() {
		// Map CSS
		$this->enqueue_map_styles();

		// Icon CSS
		$this->enqueue_icon_styles();

		// CSS
		wp_enqueue_style( 'directorist-main-style' );
		wp_enqueue_style( 'directorist-inline-style' );
		wp_enqueue_style( 'directorist-settings-style' );
		wp_enqueue_style( 'directorist-select2-style' );
		wp_enqueue_style( 'directorist-ez-media-uploader-style' );
		wp_enqueue_style( 'directorist-plasma-slider' );
		wp_enqueue_style( 'directorist-slick-style' );
		wp_enqueue_style( 'directorist-sweetalert-style' );

		// Enqueue Single Listing Scripts
		if ( is_singular( ATBDP_POST_TYPE ) ) {
			$this->enqueue_single_listing_shortcode_scripts();
		}
	}
}