<?php
/**
 * Listing review data class.
 *
 * @package Directorist\Review
 * @since 7.1.0
 */
namespace Directorist\Review;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Listing_Review_Meta {

	const FIELD_RATING_COUNTS = '_directorist_listing_rating_counts';

	const FIELD_AVG_RATING = '_directorist_listing_rating';

	const FIELD_REVIEW_COUNT = '_directorist_listing_review_count';

	public static function get_rating_counts( $listing_id ) {
		$counts = get_post_meta( $listing_id, self::FIELD_RATING_COUNTS, true );
		return ( ! empty( $counts ) && is_array( $counts ) ) ? $counts : array();
	}

	public static function update_rating_counts( $listing_id, $counts ) {
		update_post_meta( $listing_id, self::FIELD_RATING_COUNTS, (array) $counts );
	}

	public static function get_rating( $listing_id, $round_precision = 1 ) {
		$rating = get_post_meta( $listing_id, self::FIELD_AVG_RATING, true );
		return round( ( ! empty( $rating ) ? $rating : 0 ), $round_precision );
	}

	public static function update_rating( $listing_id, $rating ) {
		update_post_meta( $listing_id, self::FIELD_AVG_RATING, $rating );
	}

	public static function get_review_count( $listing_id ) {
		$counts = get_post_meta( $listing_id, self::FIELD_REVIEW_COUNT, true );
		return ( ! empty( $counts ) ) ? absint( $counts ) : 0;
	}

	public static function update_review_count( $listing_id, $counts ) {
		update_post_meta( $listing_id, self::FIELD_REVIEW_COUNT, absint( $counts ) );
	}
}
