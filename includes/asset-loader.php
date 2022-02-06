<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

class Asset_Loader {

    use \Directorist\Asset_Loader\Asset_List;
    use \Directorist\Asset_Loader\Misc_Functions;

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

		apply_filters( 'script_loader_tag', array( $this, 'defer_load_js' ), 10, 2 );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function set_scripts() {
		$this->scripts = apply_filters( 'directorist_scripts', $this->asset_list() );
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

	private function search_form_localized_data() {
		$directory_type_id = ( isset( $args['directory_type_id'] ) ) ? $args['directory_type_id'] : '';
		$data = Script_Helper::get_search_script_data([
			'directory_type_id' => $directory_type_id
		]);
		return $data;
	}
}

Asset_Loader::instance();