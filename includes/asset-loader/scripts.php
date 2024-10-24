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
	 *			'type' => String, // Accepts css, js
	 *			'path' => String, // Absolute url, without the min/rtl/js extension
	 *			'ext'  => String, // External url, in case the path is absent
	 *			'dep'  => Array [], // Dependency list eg. [jquery]
	 *			'rtl'  => Boolean false, // RTL exists or not
	 *		];
	 */
	public static function get_all_scripts() {
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
				'path' => DIRECTORIST_ICON_URL . 'unicons/css/line',
			],
			'directorist-font-awesome' => [
				'type' => 'css',
				'path' => DIRECTORIST_ICON_URL . 'font-awesome/css/all',
			],
			'directorist-line-awesome' => [
				'type' => 'css',
				'path' => DIRECTORIST_ICON_URL . 'line-awesome/css/line-awesome',
			],
			'directorist-ez-media-uploader-style' => [
				'type' => 'css',
				'path' => DIRECTORIST_VENDOR_CSS . 'ez-media-uploader',
				'rtl' => true,
			],
			'directorist-swiper-style' => [
				'type' => 'css',
				'path' => DIRECTORIST_VENDOR_CSS . 'swiper',
			],
			'directorist-sweetalert-style' => [
				'type' => 'css',
				'path' => DIRECTORIST_VENDOR_CSS . 'sweetalert',
			],

			// Public CSS
			'directorist-main-style' => [
				'type' => 'css',
				'path' => DIRECTORIST_CSS . 'public-main',
				'rtl' => true,
			],

			// Support v7 CSS
			'directorist-support-v7-style' => [
				'type' => 'css',
				'path' => DIRECTORIST_CSS . 'support-v7-style',
				'rtl' => true,
			],

			// Admin CSS
			'directorist-admin-style' => [
				'type' => 'css',
				'path' => DIRECTORIST_CSS . 'admin-main',
				'rtl'  => true,
				'dep' => [
					'directorist-font-awesome',
					'directorist-line-awesome',
				],
			],

			// Vendor JS
			'directorist-no-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'no-script',
			],
			'directorist-swiper' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'swiper',
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
			'directorist-openstreet-leaflet-markercluster-versions' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'openstreet-map/leaflet.markercluster-versions',
			],
			'google-map-api' => [
				'type' => 'js',
				'ext'  => self::gmap_url(),
			],
			'directorist-markerclusterer' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'markerclusterer',
			],
			'directorist-openstreet-map' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'openstreet-map',
				'dep' => [
					'jquery',
					'directorist-openstreet-layers',
					'directorist-openstreet-unpkg',
					'directorist-openstreet-unpkg-index',
					'directorist-openstreet-unpkg-libs',
					'directorist-openstreet-leaflet-versions',
					'directorist-openstreet-leaflet-markercluster-versions',
					'directorist-openstreet-libs-setup',
					'directorist-openstreet-open-layers',
					'directorist-openstreet-crosshairs',
				],
			],
			'directorist-google-map' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'google-map',
				'dep' => [
					'jquery',
					'google-map-api',
					'directorist-markerclusterer',
				],
			],
			'directorist-select2-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'select2',
			],
			'directorist-sweetalert' => [
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
			'directorist-ez-media-uploader' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'ez-media-uploader',
			],
			'directorist-jquery-barrating' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'jquery.barrating',
			],
			'directorist-uikit' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'uikit',
			],
			'directorist-validator' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'validator',
			],
			'directorist-font-awesome-icons' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'icon-picker/font-awesome',
			],
			'directorist-line-awesome-icons' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'icon-picker/line-awesome',
			],
			'directorist-icon-picker' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS . 'icon-picker/icon-picker',
				'dep' => [
					'directorist-font-awesome-icons',
					'directorist-line-awesome-icons',
				],
			],

			// Global JS
			'directorist-global-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'global-main',
			],

			// Public JS
			'directorist-widgets' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'widgets',
			],
			'directorist-all-listings' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'all-listings',
			],
			'directorist-search-form' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'search-form',
			],
			'directorist-listing-slider' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'listing-slider',
			],
			'directorist-dashboard' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'directorist-dashboard',
			],
			'directorist-all-authors' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'all-authors',
			],
			'directorist-author-profile' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'author-profile',
			],
			'directorist-all-location-category' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'all-location-category',
			],
			'directorist-account' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'account',
			],
			'directorist-range-slider' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'range-slider',
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
			'directorist-checkout' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'checkout',
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
				'path' => DIRECTORIST_JS . 'add-listing',
			],
			'directorist-single-listing' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'single-listing',
			],
			'directorist-plupload' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'directorist-plupload',
				'dep'  => ['jquery', 'plupload-handlers'],
			],

			// Admin JS
			'directorist-admin-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'admin-main',
			],
			'directorist-admin-builder-archive' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'admin-builder-archive',
			],
			'directorist-multi-directory-builder' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'admin-multi-directory-builder',
				'dep'  => [ 'lodash' ]
			],
			'directorist-settings-manager' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'admin-settings-manager',
				'dep'  => [ 'lodash' ]
			],
			'directorist-plugins' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'admin-plugins',
			],
			'directorist-import-export' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS . 'admin-import-export',
			],
		];

        return apply_filters( 'directorist_scripts', $scripts );
	}

	private static function gmap_url() {
		$api = get_directorist_option( 'map_api_key', 'AIzaSyCwxELCisw4mYqSv_cBfgOahfrPFjjQLLo' );
		$url = '//maps.googleapis.com/maps/api/js?key=' . $api . '&libraries=places&callback=Function.prototype';
		return $url;
	}
}