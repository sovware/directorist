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
		add_action( 'wp_enqueue_scripts',    [ $this, 'localized_data' ], 15 );
		add_action( 'wp_enqueue_scripts',    [ $this, 'inline_styles' ], 15 );

		add_action( 'script_loader_tag', array( $this, 'defer_load_js' ), 10, 2 );
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

	function defer_load_js( $tag, $handle ) {

		$scripts = array_filter( $this->scripts, function( $script ) {
			return $script['type'] == 'js' ? true : false;
		} );
		$scripts = array_keys( $scripts );

		if ( in_array( $handle, $scripts ) ) {
			return str_replace(' src', ' defer="defer" src', $tag );
		}

		return $tag;
	}

	/**
	 * Absoulute url based on various factors eg. min, rtl etc.
	 *
	 * @param  array $script Single item of $Asset_Loader::scripts array.
	 *
	 * @return string        URL string.
	 */
	public function script_file_url( $script ) {
		if ( !empty( $script['ext'] ) ) {
			return $script['ext'];
		}

		$min  = $this->debug_enabled() ? '' : '.min';
		$rtl  = ( $script['type'] == 'css' && !empty( $script['rtl'] ) && is_rtl() ) ? '.rtl' : '';
		$ext  = $script['type'] == 'css' ? '.css' : '.js';
		$url = $script['path'] . $min . $rtl . $ext;
		return $url;
	}

	public function gmap_url() {
		$api = get_directorist_option( 'map_api_key', 'AIzaSyCwxELCisw4mYqSv_cBfgOahfrPFjjQLLo' );
		$url = '//maps.googleapis.com/maps/api/js?key=' . $api . '&libraries=places';
		return $url;
	}

	/**
	 * Set the scripts array.
	 *
	 * Each item may contain following arguments:
	 * 		$scripts['handle'] => [
				'type' => String, // Accespts css, js
				'path' => String, // Absolute url, without the min/rtl/js extension
				'ext'  => String, // External url, in case the path is absent
				'dep'  => Array [], // Dependency list eg. [jquery]
				'rtl'  => Boolean false, // RTL exists or not
			];
	 */
	public function set_scripts() {
		$scripts = [
			'directorist-main-style' => [
				'type' => 'css',
				'path' => DIRECTORIST_CSS. 'public-main',
				'ext'  => '',
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
				'ext'  => '//unicons.iconscout.com/release/v3.0.3/css/line.css',
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
				'path' => DIRECTORIST_VENDOR_CSS. 'sweetalert',
			],


			'directorist-openstreet-layers' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'openstreet-map/openstreetlayers',
			],
			'directorist-openstreet-unpkg' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'openstreet-map/unpkg-min',
			],
			'directorist-openstreet-unpkg-index' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'openstreet-map/unpkg-index',
			],
			'directorist-openstreet-unpkg-libs' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'openstreet-map/unpkg-libs',
			],
			'directorist-openstreet-leaflet-versions' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'openstreet-map/leaflet-versions',
			],
			'directorist-openstreet-leaflet-markercluster-versions' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'openstreet-map/leaflet.markercluster-versions',
			],
			'directorist-openstreet-libs-setup' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'openstreet-map/libs-setup',
			],
			'directorist-openstreet-open-layers' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'openstreet-map/openlayers/OpenLayers',
			],
			'directorist-openstreet-crosshairs' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'openstreet-map/openlayers4jgsi/Crosshairs',
			],
			'directorist-openstreet-load-scripts' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'global-load-osm-map',
			],
			'directorist-google-map' => [
				'type' => 'js',
				'ext'  => $this->gmap_url(),
			],
			'directorist-markerclusterer' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'markerclusterer',
			],
			'directorist-select2' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'select2',
			],
			'directorist-sweetalert' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'sweetalert',
			],
			'directorist-tooltip' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'tooltip',
			],
			'directorist-popper' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'popper',
			],
			'directorist-range-slider' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'range-slider',
			],
			'directorist-ez-media-uploader' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'ez-media-uploader',
			],

			'directorist-jquery-barrating' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'jquery.barrating',
			],
			'directorist-plasma-slider' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'plasma-slider',
			],
			'directorist-uikit' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'uikit',
			],
			'directorist-validator' => [
				'type' => 'js',
				'path' => DIRECTORIST_VENDOR_JS. 'validator',
			],
		];

		$this->scripts = apply_filters( 'directorist_register_scripts', $scripts );
	}

	/**
	 * Register all assets.
	 */
	public function register_scripts() {

		foreach ( $this->scripts as $handle => $script ) {

			$url = $this->script_file_url( $script );

			if ( !empty( $script['dep'] ) ) {
				$dep = $script['dep'];
			}
			else {
				$dep = ( $script['type'] == 'js' ) ? ['jquery'] : [];
			}

			if ( $script['type'] == 'css' ) {
				wp_register_style( $handle, $url, $dep, $this->version );
			}
			else {
				wp_register_script( $handle, $url, $dep, $this->version );
			}
		}
	}

	/**
	 * Load inline styles.
	 */
	public function inline_styles() {
		wp_add_inline_style( 'directorist-main-style', $this->dynamic_style() );
	}

	/**
	 * Load localized data.
	 */
	public function localized_data() {
		wp_localize_script( 'directorist-search-form-listing', 'atbdp_search_listing', $this->search_form_localized_data() );
	}



	public function enqueue_scripts() {
		// Global
		wp_enqueue_script( 'directorist-main-script' );

		// Conditional
		if ( is_singular( ATBDP_POST_TYPE ) ) {
			wp_enqueue_style( 'directorist-main-style' );
			wp_enqueue_style( 'directorist-settings-style' );
		}



	}

	public function enqueue_admin_scripts() {

	}

	/**
	 * Enqueue scripts based on shortcode.
	 *
	 * @param string $shortcode Shortcode Name.
	 */
	public function load_shortcode_scripts( $shortcode ) {

		switch ( $shortcode ) {
			case 'directorist_add_listing':
				wp_enqueue_style( 'directorist-ez-media-uploader' );
				break;
		}
	}

	public function dynamic_style() {
		$style_path = ATBDP_DIR . 'assets/other/style.php';

		ob_start();
		include $style_path;
		$style = ob_get_clean();
		$style = str_replace( ['<style>', '</style>'], '', $style );
		$style = this->minify_css( $style );
		return $style;
	}

	/**
	 * Minify inline styles.
	 *
	 * @link https://gist.github.com/Rodrigo54/93169db48194d470188f
	 *
	 * @param  string $input
	 *
	 * @return string
	 */
	public static function minify_css( $input ) {
		if(trim($input) === "") return $input;
		return preg_replace(
			array(
				// Remove comment(s)
				'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
				// Remove unused white-space(s)
				'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
				// Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
				'#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
				// Replace `:0 0 0 0` with `:0`
				'#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
				// Replace `background-position:0` with `background-position:0 0`
				'#(background-position):0(?=[;\}])#si',
				// Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
				'#(?<=[\s:,\-])0+\.(\d+)#s',
				// Minify string value
				'#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
				'#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
				// Minify HEX color code
				'#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
				// Replace `(border|outline):none` with `(border|outline):0`
				'#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
				// Remove empty selector(s)
				'#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
			),
			array(
				'$1',
				'$1$2$3$4$5$6$7',
				'$1',
				':0',
				'$1:0 0',
				'.$1',
				'$1$3',
				'$1$2$4$5',
				'$1$2$3',
				'$1:0',
				'$1$2'
			),
			$input);
	}

	private function search_form_localized_data() {
		$directory_type_id = ( isset( $args['directory_type_id'] ) ) ? $args['directory_type_id'] : '';
		$data = Script_Helper::get_search_script_data([
			'directory_type_id' => $directory_type_id
		]);
		return $data;
	}
}

Asset_Loader::instance();