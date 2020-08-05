<?php
/**
 *
 * Handles directorist Tools Page
 * 
 * @author AazzTech
 */

defined( 'ABSPATH' ) || exit;

class ATBDP_Tools {

	public $theme_overrides = false;

	public function __construct() {
		add_action( 'admin_menu',     array( $this, 'tools_submenu'), 11);
		add_action( 'admin_notices',  array( $this, 'admin_notice' ) );
	}

	public function admin_notice() {
		$screen = get_current_screen();
		if ( !empty($screen->id) && $screen->id == 'at_biz_dir_page_directorist-tools' ) {

			$outdated = false;

			foreach ($this->get_theme_overrides() as $override) {
				if ( $override['core_version'] && ( empty( $override['version'] ) || version_compare( $override['version'], $override['core_version'], '<' ) ) ) {
					$outdated = true;
					break;
				}
			}

			if ($outdated) {
				_e( '<div class="notice notice-warning is-dismissible"><p><strong>Some template files of your theme is outdated. These files may need updating to avoid any compatiblity issues.</strong></p></div>', 'directorist' );
			}
		}
	}

	public function scan_template_files( $template ) {
		$files  = @scandir( $template );
		$all_files = array();

		if ( ! empty( $files ) ) {
			foreach ( $files as $file ) {
				if ( ! in_array( $file, array( '.', '..' ), true ) ) {
					if ( is_dir( $template . '/' . $file ) ) {
						$inner_files = $this->scan_template_files( $template . '/' . $file );
						foreach ( $inner_files as $inner_file ) {
							$all_files[] = $file . '/' . $inner_file;
						}
					}
					else {
						$all_files[] = $file;
					}
				}
			}
		}
		return $all_files;
	}

	public function get_file_version( $file ) {
		if ( ! file_exists( $file ) ) {
			return '';
		}

		$fopen = fopen( $file, 'r' );
		$fdata = fread( $fopen, 8192 );
		fclose( $fopen );
		$fdata = str_replace( "\r", "\n", $fdata );
		$version   = '';

		if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( '@version', '/' ) . '(.*)$/mi', $fdata, $match ) && $match[1] ) {
			$version = _cleanup_header_comment( $match[1] );
		}

		return $version;
	}

	public function get_theme_overrides() {
		if ( $this->theme_overrides !== false ) {
			return $this->theme_overrides;
		}

		$template_path = '/directorist/';
		$theme_overrides = [];
		$all_files = $this->scan_template_files( ATBDP_TEMPLATES_DIR . 'public-templates/' );
		foreach ( $all_files as $file ) {
			if ( file_exists( get_stylesheet_directory() . $template_path . $file ) ) {
				$theme_file = get_stylesheet_directory() . $template_path . $file;
			}
			elseif ( file_exists( get_template_directory() . $template_path . $file ) ) {
				$theme_file = get_template_directory() . $template_path . $file;
			}
			else {
				$theme_file = false;
			}

			if ( ! empty( $theme_file ) ) {
				$core_version  = $this->get_file_version( ATBDP_TEMPLATES_DIR . 'public-templates/' . $file );
				$theme_version = $this->get_file_version( $theme_file );
				$theme_overrides[] = array(
					'file'         => str_replace( WP_CONTENT_DIR . '/themes/', '', $theme_file ),
					'version'      => $theme_version,
					'core_version' => $core_version,
				);
			}
		}

		$this->theme_overrides = $theme_overrides;

		return $this->theme_overrides;
	}

	/**
	 * It adds a submenu for showing Tools
	 */
	public function tools_submenu() {
		add_submenu_page('edit.php?post_type=at_biz_dir', __('Tools', 'directorist'), __('Tools', 'directorist'), 'manage_options', 'directorist-tools', array($this, 'display_tools_menu'));
	}

	/**
	 * It displays settings page markup
	 */
	public function display_tools_menu() {
		$theme_overrides = $this->get_theme_overrides();
        $path = ATBDP_TEMPLATES_DIR . 'tools.php';
        include( $path );
	}
}
