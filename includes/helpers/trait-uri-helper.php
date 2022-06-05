<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

trait URI_Helper {

	public static function get_template_contents( $template, $args = array() ) {
		ob_start();
		self::get_template( $template, $args );
		return ob_get_clean();
	}

	public static function template_directory() {
		$dir = ATBDP_DIR. 'templates/';
		return $dir;
	}

	public static function theme_template_directory() {
		$dir = apply_filters( 'directorist_template_directory', 'directorist' );
		return $dir;
	}

	public static function template_path( $template_name, $args = array() ) {
		$templates = trailingslashit( self::theme_template_directory() ) . "{$template_name}.php";
		$template  = locate_template( $templates );

		if ( ! $template ) {
			$template = self::template_directory() . "{$template_name}.php";;
		}

		return apply_filters( 'directorist_template_file_path', $template, $template_name, $args );
	}

	public static function get_template( $template, $args = array(), $shortcode_key = '' ) {
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

		$template = apply_filters( 'directorist_template', $template, $args );
		$file = self::template_path( $template, $args );

		do_action( 'before_directorist_template_loaded', $template, $file, $args );

		if ( file_exists( $file ) ) {
			include $file;
		}
	}

	public static function get_theme_template_path_for( $template ) {
		$template_path = locate_template( $template . '.php' );
		$singular_path = locate_template( 'singular.php' );
		$index_path    = locate_template( 'index.php' );

		if ( $template_path ) {
			return $template_path;
		}
		elseif ( $singular_path ) {
			return $singular_path;
		}
		else {
			return $index_path;
		}
	}

	public static function get_icon_src( $icon ) {
		$file = self::get_icon_file( $icon );

		if ( !$file ) {
			return '';
		}

		$url = ATBDP_URL . 'assets/icons/' . $file;
		return $url;
	}

	private static function get_icon_file( $icon ) {
		if ( str_starts_with( $icon, 'fa' ) ) {
			return self::get_font_awesome_file( $icon );
		} elseif ( str_starts_with( $icon, 'la' ) ) {
			return self::get_line_awesome_file( $icon );
		} elseif ( str_starts_with( $icon, 'ui' ) ) {
			return self::get_unicons_file( $icon );
		}

		return '';
	}

	/**
	 * FontAwesome 5.15.4
	 *
	 * @param string $icon
	 *
	 * @return string
	 */
	private static function get_font_awesome_file( $icon ) {
		$slice = explode(' ', $icon );
		$filename = str_replace( 'fa-', '', $slice[1] );
		$filename = $filename . '.svg';

		if ( $slice[0] == 'fa' || $slice[0] == 'far' ) {
			$dir = 'font-awesome/regular/';
		} elseif ( $slice[0] == 'fab' ) {
			$dir = 'font-awesome/brands/';
		} elseif ( $slice[0] == 'fas' ) {
			$dir = 'font-awesome/solid/';
		} else {
			return '';
		}

		return $dir . $filename;
	}

	/**
	 * LineAwesome 1.3.0
	 *
	 * @param string $icon
	 *
	 * @return string
	 */
	private static function get_line_awesome_file( $icon ) {
		$slice = explode(' ', $icon );

		$filename = str_replace( 'la-', '', $slice[1] );

		if ( $slice[0] == 'la' || $slice[0] == 'las' ) {
			$filename = $filename . '-solid.svg';
		} else {
			$filename = $filename . '.svg';
		}

		// Backward commpatibility for v1.2.1
		if ( $slice[0] == 'la' || $slice[0] == 'las' ) {
			$filename = str_replace( '-o-', '-', $filename );
		}

		$dir = 'line-awesome/';

		return $dir . $filename;
	}

	/**
	 * Unicons 4.0.1
	 *
	 * @param string $icon
	 *
	 * @return string
	 */
	private static function get_unicons_file( $icon ) {
		$slice = explode(' ', $icon );

		if ( $slice[0] == 'uil' ) {
			$filename = str_replace( 'uil-', '', $slice[1] );
			$dir = 'unicons/line/';
		} else if ( $slice[0] == 'uis' ) {
			$filename = str_replace( 'uis-', '', $slice[1] );
			$dir = 'unicons/solid/';
		} else {
			return '';
		}

		return $dir . $filename . '.svg';
	}
}