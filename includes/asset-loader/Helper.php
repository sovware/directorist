<?php
/**
 * @author wpWax
 */

namespace Directorist\Asset_Loader;

use \Directorist\Script_Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

class Helper {

	public static function debug_enabled() {
		return get_directorist_option('script_debugging', false, true );
	}

	public static function register_single_script( $handle, $script, $version ) {
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

	public static function dynamic_style() {
		$style_path = ATBDP_DIR . 'assets/other/style.php';

		ob_start();
		include $style_path;
		$style = ob_get_clean();
		$style = str_replace( ['<style>', '</style>'], '', $style );
		$style = self::minify_css( $style );
		return $style;
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

	public static function map_type() {
		return get_directorist_option( 'select_listing_map', 'openstreet' );
	}

}