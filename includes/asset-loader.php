<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

class Asset_Loader {

	public static $instance = null;

	public $version;
	public $scripts;

	private function __construct() {
		$this->version = $this->debug_enabled() ? time() : DIRECTORIST_SCRIPT_VERSION;

		$this->set_scripts();

		add_action( 'wp_enqueue_scripts',    [ $this, 'register_scripts' ] );
		add_action( 'wp_enqueue_scripts',    [ $this, 'enqueue_scripts' ], 12 );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ], 12 );

		add_action( 'script_loader_tag', array( $this, 'defer_js' ), 10, 2 );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function debug_enabled() {
		return get_directorist_option( 'script_debugging', false, true );
	}

	function defer_js( $tag, $handle ) {

		$scripts = array_filter( $this->scripts, function( $script ) {
			return $script['type'] == 'js' ? true : false;
		} );
		$scripts = array_keys( $scripts );

		if ( in_array( $handle, $scripts ) ) {
			return str_replace(' src', ' defer="defer" src', $tag );
		}

		return $tag;
	}

	public function script_file_path( $script ) {
		$min  = $this->debug_enabled() ? '' : '.min';
		$rtl  = ( $script['type'] == 'css' && !empty( $script['rtl'] ) && is_rtl() ) ? '.rtl' : '';
		$ext  = $script['type'] == 'css' ? '.css' : '.js';
		$path = $script['path'] . $min . $rtl . $ext;
		return $path;
	}

	public function set_scripts() {
		$scripts = [
			'directorist-main-style' => [
				'type' => 'css',
				'path' => DIRECTORIST_CSS. 'public-main',
				'dep'  => [],
				'rtl'  => false,
			],
			'directorist-inline-style' => [
				'type' => 'css',
				'path' => DIRECTORIST_ASSETS . 'other/inline-style',
				'rtl'  => true,
			],
			'directorist-settings-style' => [
				'type' => 'css',
				'path' => DIRECTORIST_ASSETS . 'other/settings-style',
			],

			'directorist-main-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS. 'public-main',
			],
			'directorist-releated-listings-slider' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS. 'public-releated-listings-slider',
			],
			'directorist-atmodal' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS. 'public-atmodal',
			],
			'directorist-geolocation' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS. 'global-geolocation',
			],
			'directorist-geolocation-widget' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS. 'public-geolocation-widget',
			],
			'directorist-search-listing' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS. 'public-search-listing',
			],
			'directorist-search-form-listing' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS. 'public-search-form-listing',
			],
			'directorist-single-listing-openstreet-map-custom-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS. 'public-single-listing-openstreet-map-custom-script',
			],
			'directorist-single-listing-openstreet-map-widget-custom-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS. 'public-single-listing-openstreet-map-widget-custom-script',
			],
			'directorist-single-listing-gmap-widget-custom-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS. 'public-single-listing-gmap-custom-script',
			],
			'directorist-add-listing-public' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS. 'public-single-listing-gmap-widget-custom-script',
			],
			'directorist-add-listing-openstreet-map-custom-script-public' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS. 'global-add-listing',
			],
			'directorist-add-listing-gmap-custom-script-public' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS. 'global-add-listing-openstreet-map-custom-script',
			],

			'directorist-global-script' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS. 'global-main',
			],

			'directorist-map-view' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS. 'global-map-view',
			],

			'directorist-gmap-marker-clusterer' => [
				'type' => 'js',
				'path' => DIRECTORIST_JS. 'global-markerclusterer',
			],

			/* Vendors */

			'directorist-openstreet-map-leaflet' => [
				'type' => 'css',
				'path' => DIRECTORIST_VENDOR_CSS . 'openstreet-map/leaflet',
			],
			'directorist-openstreet-map-openstreet' => [
				'type' => 'css',
				'path' => DIRECTORIST_VENDOR_CSS . 'openstreet-map/openstreet',
			],
			'directorist-select2' => [
				'type' => 'css',
				'path' => DIRECTORIST_VENDOR_CSS. 'select2',
			],
			'directorist-unicons' => [
				'type' => 'js',
				'path' => '//unicons.iconscout.com/release/v3.0.3/css/line.css',
			],
			'directorist-font-awesome' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_CSS. 'font-awesome',
			],
			'directorist-line-awesome' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_CSS. 'line-awesome',
			],
			'directorist-ez-media-uploader' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_CSS. 'ez-media-uploader',
			],
			'directorist-slick' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_CSS. 'slick',
			],
			'directorist-sweetalert' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_CSS. 'sweetalert.min',
			],


		];

		$this->scripts = apply_filters( 'directorist_register_scripts', $scripts );
	}

	public function register_scripts() {

		foreach ( $this->scripts as $handle => $script ) {

			$path = $this->script_file_path( $script );

			if ( !empty( $script['dep'] ) ) {
				$dep = $script['dep'];
			}
			else {
				$dep = ( $script['type'] == 'js' ) ? ['jquery'] : [];
			}

			if ( $script['type'] == 'css' ) {
				wp_register_style( $handle, $path, [$dep], $this->version );
			}
			else {
				wp_register_script( $handle, $path, $dep, $this->version );
			}
		}
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'directorist-main-style' );
		wp_enqueue_script( 'directorist-main-script' );
	}

	public function enqueue_admin_scripts() {

	}

}

Asset_Loader::instance();