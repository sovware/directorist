<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Asset_Loader_Utility {
	/**
	 * Icon Scripts
	 *
	 * @return void
	 */
	public function enqueue_icon_styles() {
		$icon_type = get_directorist_option( 'font_type', '', true );

		if ( 'line' === $icon_type ) {
			wp_enqueue_style( 'directorist-line-awesome' );
		} else {
			wp_enqueue_style( 'directorist-font-awesome' );
		}
	}

	/**
	 * Enqueue Custom Color Picker Scripts
	 *
	 * @return void
	 */
	public static function enqueue_custom_color_picker_scripts() {
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

	public function dynamic_style() {
		$style_path = ATBDP_DIR . 'assets/other/style.php';

		ob_start();
		include $style_path;
		$style = ob_get_clean();
		$style = str_replace( ['<style>', '</style>'], '', $style );
		$style = $this->minify_css( $style );
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
}