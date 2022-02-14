<?php
/**
 * @author wpWax
 */

namespace wpWax\Directorist\Settings;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Listings {

	public static function display_card_info_in_single_line() {
		return get_directorist_option( 'info_display_in_single_line', false );
	}

}