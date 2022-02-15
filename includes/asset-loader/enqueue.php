<?php
/**
 * @author wpWax
 */

namespace Directorist\Asset_Loader;

if ( ! defined( 'ABSPATH' ) ) exit;

class Enqueue {

    use Enqueue_Shortcodes;
    use Enqueue_Widgets;
    use Enqueue_Admin;

	public static function public_scripts() {
		// Map CSS
		self::map_styles();

		// Icon CSS
		self::icon_styles();

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
			self::single_listing();
		}
	}

	/**
	 * @todo apply icon condition
	 */
	public static function icon_styles() {
		wp_enqueue_style( 'directorist-line-awesome' );
		wp_enqueue_style( 'directorist-font-awesome' );

		return;

		$icon_type = get_directorist_option( 'font_type', '', true );

		if ( 'line' === $icon_type ) {
			wp_enqueue_style( 'directorist-line-awesome' );
		} else {
			wp_enqueue_style( 'directorist-font-awesome' );
		}
	}

    public static function map_styles() {
		if ( Helper::map_type() == 'openstreet' ) {
			wp_enqueue_style( 'directorist-openstreet-map-leaflet' );
			wp_enqueue_style( 'directorist-openstreet-map-openstreet' );
		}
	}

    public static function color_picker_scripts() {
		wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false, 1 );
		wp_enqueue_script( 'wp-color-picker', admin_url( 'js/color-picker.min.js' ), array( 'iris', 'wp-i18n' ), false, 1 );

		$colorpicker_l10n = array(
			'clear'         => __( 'Clear' ),
			'defaultString' => __( 'Default' ),
			'pick'          => __( 'Select Color' ),
			'current'       => __( 'Current Color' ),
		);

		wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n );
	}

	public static function common_shortcode_scripts() {
		wp_enqueue_script('jquery-masonry');
		wp_enqueue_script( 'directorist-ez-media-uploader' );
        wp_enqueue_script( 'directorist-main-script' );
		wp_enqueue_script( 'directorist-popper' );
		wp_enqueue_script( 'directorist-tooltip' );
		wp_enqueue_script( 'directorist-no-script' );
		wp_enqueue_script( 'directorist-global-script' );
		wp_enqueue_script( 'directorist-atmodal' );
	}

	public static function openstreet_map_scripts( $cluster = false ) {
		wp_enqueue_script( 'directorist-openstreet-layers' );
		wp_enqueue_script( 'directorist-openstreet-unpkg' );
		wp_enqueue_script( 'directorist-openstreet-unpkg-index' );
		wp_enqueue_script( 'directorist-openstreet-unpkg-libs' );
		wp_enqueue_script( 'directorist-openstreet-leaflet-versions' );

		if ( $cluster ) {
			wp_enqueue_script( 'directorist-openstreet-leaflet-markercluster-versions' );
		}

		wp_enqueue_script( 'directorist-openstreet-libs-setup' );
		wp_enqueue_script( 'directorist-openstreet-open-layers' );
		wp_enqueue_script( 'directorist-openstreet-crosshairs' );
	}

	public static function google_map_scripts() {
		wp_enqueue_script( 'directorist-google-map' );
		wp_enqueue_script( 'directorist-map-view' );
		wp_enqueue_script( 'directorist-gmap-marker-clusterer' );
	}

}