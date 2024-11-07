<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait Icon_Helper {

	/**
	 * Get the url of icon svg file.
	 *
	 * Supports FontAwesome 5.15.4, LineAwesome 1.3.0, Unicons 3.0.3
	 *
	 * @param string $icon Icon class name eg. 'las la-home'.
	 *
	 * @return string Svg file url eg. https:/example.com/.../line-awesome/svgs/home-solid.svg
	 */
	public static function get_icon_src( $icon ) {
		$file = self::get_icon_file( $icon );

		if ( ! $file ) {
			return '';
		}

		$url = ATBDP_URL . 'assets/icons/' . $file;
		return $url;
	}

	private static function get_icon_file( $icon ) {

		if( empty( $icon ) || ! is_string( $icon ) ) {
			return '';
		}

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
		list( $prefix, $name ) = array_pad( explode( ' ', $icon ), 2, '' );

		if ( ! $name ) { 
			return '';
		}

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
		list( $prefix, $name ) = array_pad( explode( ' ', $icon ), 2, '' );
		
		if ( ! $name ) { 
			return '';
		}

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
				$file1    = 'line-awesome/svgs/' . $filename[0] . '.svg';
				$file2    = 'line-awesome/svgs/' . $filename[0] . '-solid.svg';
				$file3    = 'line-awesome/svgs/' . $filename[1] . '.svg';
				$file4    = 'line-awesome/svgs/' . $filename[1] . '-solid.svg';

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
		list( $prefix, $name ) = array_pad( explode( ' ', $icon ), 2, '' );
		
		if ( ! $name ) { 
			return '';
		}

		if ( $prefix == 'uil' ) {
			$filename = str_replace( 'uil-', '', $name );
			$dir      = 'unicons/svgs/line/';
		} elseif ( $prefix == 'uis' ) {
			$filename = str_replace( 'uis-', '', $name );
			$dir      = 'unicons/svgs/solid/';
		} else {
			return '';
		}

		return $dir . $filename . '.svg';
	}
}
