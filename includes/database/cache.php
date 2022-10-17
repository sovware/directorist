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
	public static $type             = 'object'; // object, transient
	public static $expire           = 43000;

	public static function cache_enabled() {
		return get_directorist_option( 'atbdp_enable_cache', true );
	}

	public static function external_cache_active() {
		return wp_using_ext_object_cache();
	}

	public static function set( $key, $value, $expire = '', $group = '', $type = '' ) {
		$type   = in_array( $type, array( 'object', 'transient' ) ) ? $type : self::$type;
		$expire = $expire ? $expire : self::$expire;
		$group  = $group ? $group : self::$cache_group;

		if ( $type === 'object' ) {
			return wp_cache_set( $key, $value, $group, $expire );
		} else {
			$transient_key = self::$transient_prefix . $key;
			return set_transient( $transient_key, $value, $expire );
		}
	}

	public static function get( $key, $group = '', $type = '' ) {
		$type   = in_array( $type, array( 'object', 'transient' ) ) ? $type : self::$type;
		$group  = $group ? $group : self::$cache_group;

		if ( $type === 'object' ) {
			return wp_cache_get( $key, $group );
		} else {
			$transient_key = self::$transient_prefix . $key;
			return get_transient( $transient_key );
		}
	}



}
