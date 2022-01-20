<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

class Asset_Loader extends Asset_Loader_Base {

	use Asset_Loader_Utility;
	use Scripts_Loader;
	use Public_Scripts_Loader;
	use Admin_Scripts_Loader;
	use Shortcode_Scripts_Loader;
	use Localized_Data_Loader;

	public static $instance = null;

	public $version;
	public $scripts;

	private function __construct() {
		$this->version = $this->debug_enabled() ? time() : DIRECTORIST_SCRIPT_VERSION;

		$this->set_scripts();

		// Public Assets
		add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_public_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'load_localized_data' ], 15 );
		add_action( 'wp_enqueue_scripts', [ $this, 'inline_styles' ], 15 );

		// Admin Assets
		add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ], 12 );
		add_action( 'admin_enqueue_scripts', [ $this, 'load_localized_data' ], 15 );

		// Block Editor Assets
		add_action( 'enqueue_block_editor_assets', [ $this, 'register_scripts' ], 15 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_scripts' ], 15 );

		// Hooks
		add_action( 'script_loader_tag', array( $this, 'defer_load_js' ), 10, 2 );
		// add_action( 'init', array( $this, 'enqueue_single_listing_shortcode_scripts' ), 20, 2 );
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
	 * Load inline styles.
	 */
	public function inline_styles() {
		wp_add_inline_style( 'directorist-main-style', $this->dynamic_style() );
	}

	/**
	 * Enqueue block editor scripts
	 *
	 * @return void
	 */
	public function enqueue_block_editor_scripts() {
		wp_enqueue_style( 'directorist-main-style' );
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
}

Asset_Loader::instance();