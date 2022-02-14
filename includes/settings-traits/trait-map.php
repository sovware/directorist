<?php
/**
 * @author wpWax
 */

namespace wpWax\Directorist\Settings;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Map {

	public static function map_type() {
		return get_directorist_option( 'select_listing_map', 'google' );
	}

	public static function display_map_card_window() {
		return get_directorist_option( 'display_map_info', true );
	}

	public static function display_map_image() {
		return get_directorist_option( 'display_image_map', true );
	}

	public static function display_map_title() {
		return get_directorist_option( 'display_title_map', true );
	}

	public static function display_map_address() {
		return get_directorist_option( 'display_address_map', true );
	}

	public static function display_map_direction() {
		return get_directorist_option( 'display_direction_map', true );
	}

	public static function map_force_default_location() {
		return get_directorist_option( 'use_def_lat_long', true );
	}

	public static function map_default_latitude() {
		return get_directorist_option( 'default_latitude', 40.7127753 );
	}

	public static function map_default_longitude() {
		return get_directorist_option( 'default_longitude', -74.0059728 );
	}

}