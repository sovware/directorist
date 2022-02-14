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

	public static function new_badge_text() {
		return get_directorist_option( 'new_badge_text', 'New' );
	}

	public static function popular_badge_text() {
		return get_directorist_option( 'popular_badge_text', 'Popular' );
	}

	public static function featured_badge_text() {
		return get_directorist_option( 'feature_badge_text', 'Featured' );
	}

}