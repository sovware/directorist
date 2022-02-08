<?php
/**
 * @author wpWax
 */

namespace Directorist\Asset_Loader;

use \Directorist\Script_Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

class Asset_Helper {

	public static function debug_enabled() {
		return get_directorist_option('script_debugging', false, true );
	}

	public static function register_single_script($handle, $script, $version ) {
        $url = self::script_file_url( $script );

        if ( !empty( $script['dep'] ) ) {
            $dep = $script['dep'];
        }
        else {
            $dep = ( $script['type'] == 'js' ) ? ['jquery'] : [];
        }

        if ( $script['type'] == 'css' ) {
            wp_register_style( $handle, $url, $dep, $version );
        }
        else {
            wp_register_script( $handle, $url, $dep, $version );
        }
	}
	/**
	 * Absoulute url based on various factors eg. min, rtl etc.
	 *
	 * @param  array $script Single item of $Asset_Loader::scripts array.
	 *
	 * @return string        URL string.
	 */
	public static function script_file_url( $script ) {
		if ( !empty( $script['ext'] ) ) {
			return $script['ext'];
		}

		$min  = self::debug_enabled() ? '' : '.min';
		$rtl  = ( $script['type'] == 'css' && !empty( $script['rtl'] ) && is_rtl() ) ? '.rtl' : '';
		$ext  = $script['type'] == 'css' ? '.css' : '.js';
		$url = $script['path'] . $min . $rtl . $ext;
		return $url;
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
	/**
	 * @todo apply icon condition
	 */
	public static function enqueue_icon_styles() {
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

	public static function load_localized_data() {
		// Public JS
		wp_localize_script( 'directorist-main-script', 'atbdp_public_data', Script_Helper::get_main_script_data() );
		wp_localize_script( 'directorist-main-script', 'directorist_options', self::directorist_options_data() );
		wp_localize_script( 'directorist-search-form-listing', 'atbdp_search_listing', self::search_form_localized_data() );

		wp_localize_script( 'directorist-range-slider', 'atbdp_range_slider', self::search_listing_localized_data() );
		wp_localize_script( 'directorist-search-listing', 'atbdp_search_listing', self::search_listing_localized_data() );
		wp_localize_script( 'directorist-search-listing', 'atbdp_search', [
			'ajaxnonce' => wp_create_nonce('bdas_ajax_nonce'),
			'ajax_url' => admin_url('admin-ajax.php'),
		]);

		// Admin JS
		wp_localize_script( 'directorist-admin-script', 'atbdp_admin_data', Script_Helper::get_admin_script_data() );
		wp_localize_script( 'directorist-admin-script', 'directorist_options', self::directorist_options_data() );
		wp_localize_script( 'directorist-admin-script', 'atbdp_public_data', Script_Helper::get_main_script_data() );
		wp_localize_script( 'directorist-multi-directory-builder', 'ajax_data', self::admin_ajax_localized_data() );
		wp_localize_script( 'directorist-multi-directory-archive', 'ajax_data', self::admin_ajax_localized_data() );
		wp_localize_script( 'directorist-settings-manager', 'ajax_data', self::admin_ajax_localized_data() );
		wp_localize_script( 'directorist-import-export', 'import_export_data', self::admin_ajax_localized_data() );
	}

	private static function search_listing_localized_data() {
		return Script_Helper::get_search_script_data([
			'directory_type_id' => get_post_meta( '_directory_type', get_the_ID(), true ),
		]);
	}

	public static function search_form_localized_data() {
		$directory_type_id = ( isset( $args['directory_type_id'] ) ) ? $args['directory_type_id'] : '';
		$data = Script_Helper::get_search_script_data([
			'directory_type_id' => $directory_type_id
		]);
		return $data;
	}

	private static function directorist_options_data() {
		return Script_Helper::get_option_data();
	}

	private static function admin_ajax_localized_data() {
		return [ 'ajax_url' => admin_url('admin-ajax.php') ];
	}

	public static function enqueue_color_picker_scripts() {
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

	public static function map_type() {
		return get_directorist_option( 'select_listing_map', 'openstreet' );
	}

	public static function enqueue_map_styles() {
		if ( self::map_type() == 'openstreet' ) {
			wp_enqueue_style( 'directorist-openstreet-map-leaflet' );
			wp_enqueue_style( 'directorist-openstreet-map-openstreet' );
		}
	}

	public static function enqueue_openstreet_map_scripts( $cluster = false ) {
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

	public static function enqueue_google_map_scripts() {
		wp_enqueue_script( 'directorist-google-map' );
		wp_enqueue_script( 'directorist-map-view' );
		wp_enqueue_script( 'directorist-gmap-marker-clusterer' );
	}

}