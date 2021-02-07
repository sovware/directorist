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

	public static function template_directory() {
		$legacy = get_directorist_option( 'atbdp_legacy_template', false );;

		$dir = ATBDP_DIR. 'templates/';

		if ( $legacy ) {
			$dir = ATBDP_DIR. 'templates-v6/';
		}

		return $dir;
	}

	public static function get_template( $template_file, $args = array(), $shortcode_key = '' ) {
		if ( is_array( $args ) ) {
			extract( $args );
		}

		// Load extension template if exist
		if ( ! empty( $shortcode_key ) ) {
			$default = [ 'template_directory' => '', 'file_path' => '', 'base_directory' => '' ];
			$ex_args = apply_filters( "atbdp_ext_template_path_{$shortcode_key}", $default, $args );
			$ex_args = array_merge( $default, $ex_args );
			
			$extension_path = atbdp_get_extension_template_path( $ex_args['template_directory'], $ex_args['file_path'], $ex_args['base_directory'] );
			
			

			if ( file_exists( $extension_path ) ) {
				$old_template_data = isset( $GLOBALS['atbdp_template_data'] ) ? $GLOBALS['atbdp_template_data'] : null;
				$GLOBALS['atbdp_template_data'] = $args;
	
				include $extension_path;
				
				$GLOBALS['atbdp_template_data'] = $old_template_data;
				return;
			}
		}

		$legacy = get_directorist_option( 'atbdp_legacy_template', false );
		
		if ( $legacy ) {
			$dir = 'directorist-v6';
		}
		else {
			$dir = 'directorist';
		}

		$dir = apply_filters( 'directorist_template_directory', $dir );
		
		$theme_template  = '/' . $dir . '/' . $template_file . '.php';
		$plugin_template = self::template_directory() . $template_file . '.php';

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