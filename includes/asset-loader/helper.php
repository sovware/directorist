<?php
/**
 * @author wpWax
 */

namespace Directorist\Asset_Loader;

if ( ! defined( 'ABSPATH' ) ) exit;

class Helper {

	/**
	 * Script debugging enabled or not.
	 *
	 * @return bool
	 */
	public static function debug_enabled() {
		return SCRIPT_DEBUG ? SCRIPT_DEBUG : get_directorist_option( 'script_debugging', false, true );
	}

	public static function instant_search_enabled() {
		return true;
	}

	/**
	 * Register all scripts.
	 *
	 * @param array $script single item of Scripts::get_all_scripts() array.
	 * @param string $version
	 */
	public static function register_all_scripts( $scripts, $version = '' ) {
		if ( !$version ) {
			$version = self::get_script_version();
		}

		foreach ( $scripts as $handle => $script ) {
			Helper::register_single_script( $handle, $script, $version );
		}
	}

	/**
	 * Register a script.
	 *
	 * @param string $handle
	 * @param array $script single item of Scripts::get_all_scripts() array.
	 * @param string $version
	 */
	public static function register_single_script( $handle, $script, $version = '' ) {
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
            wp_register_script( $handle, $url, $dep, $version, true );
        }
	}

	/**
	 * Get script version
	 *
	 * @return string
	 */
	public static function get_script_version() {
		return self::debug_enabled() ? time() : DIRECTORIST_SCRIPT_VERSION;
	}

	/**
	 * Generate dynamic style css from php file.
	 *
	 * @return string css
	 */
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
	 * @return string URL string.
	 */
	public static function script_file_url( $script ) {
		if ( !empty( $script['ext'] ) ) {
			return $script['ext'];
		}

		$min  = self::debug_enabled() ? '' : '.min';
		$rtl  = ( !empty( $script['rtl'] ) && is_rtl() ) ? '.rtl' : '';
		$ext  = $script['type'] == 'css' ? '.css' : '.js';
		$url = $script['path'] . $rtl . $min . $ext;
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
	 * Selected Listing map type from settings.
	 *
	 * @return string
	 */
	public static function map_type() {
		return get_directorist_option( 'select_listing_map', 'openstreet' );
	}

	/**
	 * Determine if a template is inside widgets directory.
	 *
	 * @param string $template template file name.
	 *
	 * @return bool
	 */
	public static function is_widget_template( $template ) {
		if ( empty( $template ) ) {
			return false;
		}
	
		return str_starts_with( $template, 'widgets/' );
	}

	/**
	 * Determine if user is inside an admin page of given page-type.
	 *
	 * @param string $page page type.
	 *
	 * @return bool
	 */
	public static function is_admin_page( $page ) {
		$status = false;
		$screen = get_current_screen()->base;

		switch ( $page ) {

			case 'builder-archive':
				if ( $screen == 'at_biz_dir_page_atbdp-directory-types' && empty( $_GET['action'] ) ) {
					$status = true;
				}
				break;

			case 'builder-edit':
				if ( $screen == 'at_biz_dir_page_atbdp-directory-types' ) {
					// Multi-directory enabled
					if ( !empty( $_GET['action'] ) && ( $_GET['action'] == 'edit' || $_GET['action'] == 'add_new' ) ) {
						$status = true;
					}
				} elseif ( $screen == 'at_biz_dir_page_atbdp-layout-builder' ) {
					// Multi-directory disabled
					$status = true;
				}
				break;

			case 'settings':
				if ( $screen == 'at_biz_dir_page_atbdp-settings' ) {
					$status = true;
				}
				break;

			case 'all_listings':
				if ( $screen == 'edit' && !empty( $_GET['post_type'] ) && $_GET['post_type'] == 'at_biz_dir' ) {
					$status = true;
				}
				break;

			case 'add_listing':
				if ( $screen == 'post' ) {
					if ( get_post_type( get_the_ID() ) === 'at_biz_dir' ) {
						$status = true;
					}
				}
				break;

			case 'taxonomy':
				if ( $screen == 'term' || $screen == 'edit-tags' ) {
					$taxonomies   = [ 'at_biz_dir-category', 'at_biz_dir-location', 'at_biz_dir-tags' ];
					if ( isset( $_GET['taxonomy'] ) && in_array( $_GET['taxonomy'], $taxonomies ) ) {
						$status = true;
					}
				}
				break;

			case 'support':
				if ( $screen == 'at_biz_dir_page_directorist-status' ) {
					$status = true;
				}
				break;

			case 'extensions':
				if ( $screen == 'at_biz_dir_page_atbdp-extension' ) {
					$status = true;
				}
				break;

			case 'import_export':
				if ( $screen == 'at_biz_dir_page_tools' ) {
					$status = true;
				}
				break;

			case 'wp-plugins':
				if ( $screen == 'plugins' ) {
					$status = true;
				}
				break;

			case 'wp-users':
				if ( $screen == 'users' ) {
					$status = true;
				}
				break;
		}

		return $status;
	}
}