<?php
/**
 * @author wpWax
 */

namespace Directorist\Asset_Loader;

if ( ! defined( 'ABSPATH' ) ) exit;

class Asset_Loader {

	public static $instance = null;

	public $version;
	public $scripts;

	private function __construct() {
		$this->version = Asset_Helper::debug_enabled() ? time() : DIRECTORIST_SCRIPT_VERSION;
		$this->set_scripts();

		add_action( 'wp_enqueue_scripts',    [ $this, 'register_scripts' ] );
		add_action( 'wp_enqueue_scripts',    [ $this, 'enqueue_scripts' ], 12 );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ], 12 );
		add_action( 'wp_enqueue_scripts',    [ $this, 'localized_data' ], 15 );
		add_action( 'wp_enqueue_scripts',    [ $this, 'inline_styles' ], 15 );

		apply_filters( 'script_loader_tag', array( $this, 'defer_load_js' ), 10, 2 );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function set_scripts() {
		$this->scripts = apply_filters( 'directorist_scripts', Asset_List::all_scripts() );
	}


	/**
	 * Register all assets.
	 */
	public function register_scripts() {
		foreach ( $this->scripts as $handle => $script ) {
			Asset_Helper::register_single_script( $handle, $script, $this->version );
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
		Localized_Data::load_localized_data();
	}



	public function enqueue_scripts() {
		// Map CSS
		Asset_Helper::enqueue_map_styles();

		// Icon CSS
		Asset_Helper::enqueue_icon_styles();

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
			Shortcode_Scripts::single_listing();
		}
	}

	public function enqueue_admin_scripts() {

	}

	/**
	 * Enqueue scripts based on shortcode.
	 *
	 * @param string $shortcode Shortcode Name.
	 */
	public function load_shortcode_scripts( $shortcode, $model = false ) {

		switch ( $shortcode ) {
			case 'directorist_all_listing':
				Shortcode_Scripts::all_listings( $model );
				break;

			case 'directorist_add_listing':
				Shortcode_Scripts::add_listing( $model );
				break;

			case 'directorist_search_listing':
				Shortcode_Scripts::search_form( $model );
				break;

			case 'directorist_custom_registration':
			case 'directorist_user_login':
			case 'directorist_user_login':
				Shortcode_Scripts::enqueue_common_shortcode_scripts();
				break;



		}
	}

	public function dynamic_style() {
		$style_path = ATBDP_DIR . 'assets/other/style.php';

		ob_start();
		include $style_path;
		$style = ob_get_clean();
		$style = str_replace( ['<style>', '</style>'], '', $style );
		$style = Asset_Helper::minify_css( $style );
		return $style;
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
}