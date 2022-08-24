<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait URI_Helper {

	public static function get_template_contents( $template, $args = array() ) {
		ob_start();
		self::get_template( $template, $args );
		return ob_get_clean();
	}

	public static function template_directory() {
		$dir = ATBDP_DIR . 'templates/';
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
			$default = array(
				'template_directory' => '',
				'file_path'          => '',
				'base_directory'     => '',
			);
			$ex_args = apply_filters( "atbdp_ext_template_path_{$shortcode_key}", $default, $args );
			$ex_args = array_merge( $default, $ex_args );

			$extension_path = atbdp_get_extension_template_path( $ex_args['template_directory'], $ex_args['file_path'], $ex_args['base_directory'] );

			if ( file_exists( $extension_path ) ) {
				$old_template_data              = isset( $GLOBALS['atbdp_template_data'] ) ? $GLOBALS['atbdp_template_data'] : null;
				$GLOBALS['atbdp_template_data'] = $args;

				include $extension_path;

				$GLOBALS['atbdp_template_data'] = $old_template_data;
				return;
			}
		}

		$template = apply_filters( 'directorist_template', $template, $args );
		$file     = self::template_path( $template, $args );

		do_action( 'before_directorist_template_loaded', $template, $file, $args );

		do_action( 'before_directorist_template_loaded', $template, $file, $args );

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
		} elseif ( $singular_path ) {
			return $singular_path;
		} else {
			return $index_path;
		}
	}

	public static function get_icon_src( $icon ) {
		$file = self::get_icon_file( $icon );

		if ( ! $file ) {
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
		list( $prefix, $name ) = explode( ' ', $icon );
		$filename              = str_replace( 'fa-', '', $name );
		$filename              = $filename . '.svg';

		$far_file = 'font-awesome/svgs/regular/' . $filename;
		$fas_file = 'font-awesome/svgs/solid/' . $filename;
		$fab_file = 'font-awesome/svgs/brands/' . $filename;

		// Legacy 'fa' support
		if ( $prefix == 'fa' ) {
			if ( file_exists( DIRECTORIST_ICON_PATH . $far_file ) ) {
				return $far_file;
			} elseif ( file_exists( DIRECTORIST_ICON_PATH . $fas_file ) ) {
				return $fas_file;
			} elseif ( file_exists( DIRECTORIST_ICON_PATH . $fab_file ) ) {
				return $fab_file;
			} else {
				return '';
			}
		}

		if ( $prefix == 'far' ) {
			return $far_file;
		} elseif ( $prefix == 'fab' ) {
			return $fab_file;
		} elseif ( $prefix == 'fas' ) {
			return $fas_file;
		} else {
			return '';
		}
	}

	/**
	 * LineAwesome 1.3.0
	 *
	 * @param string $icon
	 *
	 * @return string
	 */
	private static function get_line_awesome_file( $icon ) {
		list( $prefix, $name ) = explode( ' ', $icon );
		$filename              = str_replace( 'la-', '', $name );

		$lar_file = 'line-awesome/svgs/' . $filename . '.svg';
		$las_file = 'line-awesome/svgs/' . $filename . '-solid.svg';

		if ( $prefix == 'las' ) {
			return $las_file;
		} elseif ( in_array( $prefix, array( 'lab', 'lar' ) ) ) {
			return $lar_file;
		} elseif ( $prefix == 'la' ) {
			return self::get_line_awesome_legacy_file( $filename ); // Legacy 'la' support for v1.2.1
		} else {
			return '';
		}
	}

	/**
	 * LineAwesome v1.2.1 support
	 *
	 * @param string $filename
	 *
	 * @return string
	 */
	private static function get_line_awesome_legacy_file( $filename ) {
		$filename = str_replace( '-o', '', $filename );

		$fa_lar_file = 'line-awesome/svgs/' . $filename . '.svg';
		$fa_las_file = 'line-awesome/svgs/' . $filename . '-solid.svg';

		if ( file_exists( DIRECTORIST_ICON_PATH . $fa_las_file ) ) {
			return $fa_las_file;
		} elseif ( file_exists( DIRECTORIST_ICON_PATH . $fa_lar_file ) ) {
			return $fa_lar_file;
		} else {
			// Try 4 more vatiations in case of la la-x-y
			if ( stripos( $filename, '-' ) ) {
				$filename = explode( '-', $filename );
				$file1 = 'line-awesome/svgs/' . $filename[0] . '.svg';
				$file2 = 'line-awesome/svgs/' . $filename[0] . '-solid.svg';
				$file3 = 'line-awesome/svgs/' . $filename[1] . '.svg';
				$file4 = 'line-awesome/svgs/' . $filename[1] . '-solid.svg';

				if ( file_exists( DIRECTORIST_ICON_PATH . $file1 ) ) {
					return $file1;
				} elseif ( file_exists( DIRECTORIST_ICON_PATH . $file2 ) ) {
					return $file2;
				} elseif ( file_exists( DIRECTORIST_ICON_PATH . $file3 ) ) {
					return $file3;
				} elseif ( file_exists( DIRECTORIST_ICON_PATH . $file4 ) ) {
					return $file4;
				}
			}

			return '';
		}
	}

	/**
	 * Unicons 3.0.3
	 *
	 * Unicon svgs are kept only for backward compatibility.
	 *
	 * @param string $icon
	 *
	 * @return string
	 */
	private static function get_unicons_file( $icon ) {
		$slice = explode( ' ', $icon );

		if ( $slice[0] == 'uil' ) {
			$filename = str_replace( 'uil-', '', $slice[1] );
			$dir      = 'unicons/svgs/line/';
		} elseif ( $slice[0] == 'uis' ) {
			$filename = str_replace( 'uis-', '', $slice[1] );
			$dir      = 'unicons/svgs/solid/';
		} else {
			return '';
		}

		return $dir . $filename . '.svg';
	}
}
