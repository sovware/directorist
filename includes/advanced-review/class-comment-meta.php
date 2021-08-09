<?php
/**
 * Comment meta class
 *
 * @package wpWax\Directorist
 * @subpackage Review
 * @since 7.x
 */
namespace wpWax\Directorist\Review;

defined( 'ABSPATH' ) || die();

class Comment_Meta {

	const FIELD_RATING = 'rating';

	const FIELD_CRITERIA_RATING = 'criteria_rating';

	const FIELD_HELPFUL = 'helpful';

	const FIELD_UNHELPFUL = 'unhelpful';

	const FIELD_REPORT = 'reported';

	const FIELD_ATTACHMENTS = 'attachments';

	static $valid_fields = array(
		self::FIELD_RATING,
		self::FIELD_CRITERIA_RATING,
		self::FIELD_HELPFUL,
		self::FIELD_UNHELPFUL,
		self::FIELD_REPORT,
		self::FIELD_ATTACHMENTS,
	);

	protected static function is_valid_field( $field_key ) {
		return in_array( $field_key, self::$valid_fields, true );
	}

	protected static function get_data( $comment_id, $field_key, $default_value ) {
		if ( ! self::is_valid_field( $field_key ) ) {
			return '';
		}

		$value = get_comment_meta( $comment_id, $field_key, true );

		return ( $value !== '' ? $value : $default_value );
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
