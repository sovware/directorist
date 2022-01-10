<?php
/**
 * Comment meta class
 *
 * @package Directorist\Review
 * @since 7.1.0
 */
namespace Directorist\Review;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Comment_Meta {

	const FIELD_RATING = 'rating';

	const FIELD_REPORT = 'reported';

	static $valid_fields = [
		self::FIELD_RATING,
		self::FIELD_REPORT,
	];

	protected static function is_valid_field( $field_key ) {
		return in_array( $field_key, self::$valid_fields, true );
	}

	protected static function get_data( $comment_id, $field_key, $default = '' ) {
		if ( ! self::is_valid_field( $field_key ) ) {
			return '';
		}

		$value = get_comment_meta( $comment_id, $field_key, true );

		return ( $value !== '' ? $value : $default );
	}

	protected static function set_data( $comment_id, $field_key, $new_value ) {
		if ( ! self::is_valid_field( $field_key ) ) {
			return '';
		}

		update_comment_meta( $comment_id, $field_key, $new_value );

		return $new_value;
	}

	public static function __callStatic( $method, $args ) {
		$method    = strtolower( $method );
		$action    = substr( $method, 0, 3 );
		$field_key = substr( $method, 4 );

		if ( $action === 'get' && isset( $args[0] ) ) {
			$default = isset( $args[1] ) ? $args[1] : '';
			return self::get_data( $args[0], $field_key, $default );
		}

		if ( $action === 'set' && isset( $args[0], $args[1] ) ) {
			return self::set_data( $args[0], $field_key, $args[1] );
		}
	}
}
