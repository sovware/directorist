<?php
/**
 * @author wpWax
 */

namespace Directorist\Asset_Loader;

if ( ! defined( 'ABSPATH' ) ) exit;

class Scripts {

	/**
	 * Scripts array.
	 *
	 * Each item may contain following arguments:
	 * 		$scripts['handle'] => [
	 *			'type' => String, // Accespts css, js
	 *			'path' => String, // Absolute url, without the min/rtl/js extension
	 *			'ext'  => String, // External url, in case the path is absent
	 *			'dep'  => Array [], // Dependency list eg. [jquery]
	 *			'rtl'  => Boolean false, // RTL exists or not
	 *		];
	 */
	public static function all_scripts() {
		$scripts = [
			// Vendor CSS
			'directorist-openstreet-map-leaflet' => [
				'type' => 'css',
				'path' => DIRECTORIST_VENDOR_CSS . 'openstreet-map/leaflet',
			],
			'directorist-openstreet-map-openstreet' => [
				'type' => 'css',
				'path' => DIRECTORIST_VENDOR_CSS . 'openstreet-map/openstreet',
			],
			'directorist-select2-style' => [
				'type' => 'css',
				'path' => DIRECTORIST_VENDOR_CSS . 'select2',
			],
			'directorist-unicons' => [
				'type' => 'css',
				'ext'  => '//unicons.iconscout.com/release/v3.0.3/css/line.css',
			],
			'directorist-font-awesome' => [
				'type' => 'css',
				'path' => DIRECTORIST_VENDOR_CSS . 'font-awesome',
			],
			'directorist-line-awesome' => [
				'type' => 'css',
				'path' => DIRECTORIST_VENDOR_CSS . 'line-awesome',
			],
			'directorist-ez-media-uploader-style' => [
				'type' => 'css',
				'path' => DIRECTORIST_VENDOR_CSS . 'ez-media-uploader',
			],
			'directorist-slick-style' => [
				'type' => 'css',
				'path' => DIRECTORIST_VENDOR_CSS . 'slick',
			],
			'directorist-sweetalert-style' => [
				'type' => 'css',
				'path' => DIRECTORIST_VENDOR_CSS . 'sweetalert',
			],

			// Public CSS
			'directorist-main-style' => [
				'type' => 'css',
				'path' => DIRECTORIST_CSS . 'public-main',
			],
			'directorist-inline-style' => [
				'type' => 'css',
				'path' => DIRECTORIST_ASSETS . 'other/inline-style',
				'rtl'  => false,
			],
			'directorist-settings-style' => [
				'type' => 'css',
				'path' => DIRECTORIST_ASSETS . 'other/settings-style',
			],

			// Admin CSS
			'directorist-admin-style' => [
				'type' => 'css',
				'path' => DIRECTORIST_CSS . 'admin-main',
			],

			// Vendor JS
			'directorist-no-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'no-script',
			],
			'directorist-slick' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'slick',
			],
			'directorist-openstreet-layers' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'openstreet-map/openstreetlayers',
			],
			'directorist-openstreet-unpkg' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'openstreet-map/unpkg-min',
			],
			'directorist-openstreet-unpkg-index' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'openstreet-map/unpkg-index',
			],
			'directorist-openstreet-unpkg-libs' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'openstreet-map/unpkg-libs',
			],
			'directorist-openstreet-leaflet-versions' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'openstreet-map/leaflet-versions',
			],
			'directorist-openstreet-leaflet-markercluster-versions' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'openstreet-map/leaflet.markercluster-versions',
			],
			'directorist-openstreet-libs-setup' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'openstreet-map/libs-setup',
			],
			'directorist-openstreet-open-layers' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'openstreet-map/openlayers/openlayers',
			],
			'directorist-openstreet-crosshairs' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'openstreet-map/openlayers4jgsi/crosshairs',
			],
			'directorist-openstreet-load-scripts' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'global-load-osm-map',
			],
			'directorist-google-map' => [
				'type' => 'js',
				'ext'  => self::gmap_url(),
			],
			'directorist-markerclusterer' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'markerclusterer',
			],
			'directorist-select2-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'select2',
			],
			'directorist-sweetalert-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'sweetalert',
			],
			'directorist-popper' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'popper',
			],
			'directorist-tooltip' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'tooltip',
			],
			'directorist-range-slider' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'range-slider',
			],
			'directorist-ez-media-uploader' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'ez-media-uploader',
			],
			'directorist-jquery-barrating' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'jquery.barrating',
			],
			'directorist-plasma-slider' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'plasma-slider',
			],
			'directorist-uikit' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'uikit',
			],
			'directorist-validator' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'validator',
			],

			// Global JS
			'directorist-global-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'global-main',
			],

			// Public JS
			'directorist-main-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'public-main',
				'dep' => ['jquery', 'directorist-plasma-slider' ],
			],
			'directorist-releated-listings-slider' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'public-releated-listings-slider',
			],
			'directorist-atmodal' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'public-atmodal',
			],
			'directorist-geolocation' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'global-geolocation',
			],
			'directorist-geolocation-widget' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'public-geolocation-widget',
			],
			'directorist-search-listing' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'public-search-listing',
			],
			'directorist-search-form-listing' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'public-search-form-listing',
			],
			'directorist-single-listing-openstreet-map-custom-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'public-single-listing-openstreet-map-custom-script',
			],
			'directorist-single-listing-openstreet-map-widget-custom-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'public-single-listing-openstreet-map-widget-custom-script',
			],
			'directorist-single-listing-gmap-custom-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'public-single-listing-gmap-custom-script',
			],
			'directorist-single-listing-gmap-widget-custom-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'public-single-listing-gmap-custom-script',
			],
			'directorist-add-listing' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'global-add-listing',
			],
			'directorist-add-listing-openstreet-map-custom-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'global-add-listing-openstreet-map-custom-script',
			],
			'directorist-add-listing-gmap-custom-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'global-add-listing-gmap-custom-script',
			],
			'directorist-plupload' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'global-directorist-plupload',
			],

			// Admin JS
			'directorist-admin-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'admin-main',
			],
			'directorist-multi-directory-archive' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'admin-multi-directory-archive',
			],
			'directorist-multi-directory-builder' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'admin-multi-directory-builder',
			],
			'directorist-settings-manager' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'admin-settings-manager',
			],
			'directorist-plugins' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'admin-plugins',
			],
			'directorist-import-export' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'admin-import-export',
			],

			// Global JS
			'directorist-map-view' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'global-map-view',
			],
			'directorist-gmap-marker-clusterer' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'global-markerclusterer',
			],
		];

        return $scripts;
	}

	private static function gmap_url() {
		$api = get_directorist_option( 'map_api_key', 'AIzaSyCwxELCisw4mYqSv_cBfgOahfrPFjjQLLo' );
		$url = '//maps.googleapis.com/maps/api/js?key=' . $api . '&libraries=places';
		return $url;
	}
}