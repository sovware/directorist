<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

class URI_Helper {

	public static function get_template_contents( $template, $args = array() ) {
		ob_start();
		self::get_template( $template, $args );
		return ob_get_clean();
	}

	// \Directorist\URI_Helper::get_template
	public static function get_template( $template_file, $args = array() ) {
		if ( is_array( $args ) ) {
			extract( $args );
		}

		$theme_template  = '/directorist/' . $template_file . '.php';
		$plugin_template = ATBDP_TEMPLATES_DIR . $template_file . '.php';

		if ( file_exists( get_stylesheet_directory() . $theme_template ) ) {
			$file = get_stylesheet_directory() . $theme_template;
		} elseif ( file_exists( get_template_directory() . $theme_template ) ) {
			$file = get_template_directory() . $theme_template;
		} else {
			$file = $plugin_template;
		}

		if ( file_exists( $file ) ) {
			include $file;
		}
	}
}