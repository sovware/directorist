<?php
/**
 * @author wpWax
 */

namespace wpWax\Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

class Settings {

	use \wpWax\Directorist\Settings\Map;
	use \wpWax\Directorist\Settings\Listings;
	use \wpWax\Directorist\Settings\Single;

	public static function is_multi_directory_enabled() {
		return get_directorist_option( 'enable_multi_directory', false );
	}


}