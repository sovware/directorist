<?php
/**
 * Handles transient and object cache.
 *
 * @author wpWax
 */

namespace Directorist\database;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cache {

	public static $transient_prefix = 'directorist_';
	public static $cache_group      = 'directorist';
	public static $expire           = 43000;

	public static function cache_enabled() {
		return get_directorist_option( 'atbdp_enable_cache', true );
	}

	public static function external_cache_active() {
		return wp_using_ext_object_cache();
	}

	public static function set_object_cache( $key, $value, $expire = '', $group = '' ) {
		$expire = $expire ? $expire : self::$expire;
		$group  = $group ? $group : self::$cache_group;
		return wp_cache_set( $key, $value, $group, $expire );
	}

	public static function get_object_cache( $key, $group = '' ) {
		$group  = $group ? $group : self::$cache_group;
		return wp_cache_get( $key, $group );
	}

	public static function delete_object_cache( $key, $group = '' ) {
		$group  = $group ? $group : self::$cache_group;
		return wp_cache_delete( $key, $group );
	}

	public static function set_transient( $key, $value, $expire = '' ) {
		$transient_key = self::$transient_prefix . $key;
		return set_transient( $transient_key, $value, $expire );
	}

	public static function get_transient( $key ) {
		$transient_key = self::$transient_prefix . $key;
		return get_transient( $transient_key );
	}

	public static function delete_transient( $key ) {
		$transient_key = self::$transient_prefix . $key;
		return delete_transient( $transient_key );
	}
}
