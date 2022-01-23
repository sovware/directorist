<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Options_Helper {

	public static function display_search_filter_icon() {
		return get_directorist_option( 'default_listing_view', 'grid' );
	}

}