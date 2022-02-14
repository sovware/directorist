<?php
/**
 * @author wpWax
 */

namespace wpWax\Directorist\Settings;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Single {

	public static function disable_single_listing() {
		return get_directorist_option( 'disable_single_listing', false );
	}

}