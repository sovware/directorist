<?php
/**
 * Review related helper functions.
 *
 * @package Directorist\Review
 * @since 7.1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check guest review enable status.
 *
 * @return bool
 */
function directorist_is_guest_review_enabled() {
	return (bool) get_directorist_option( 'guest_review', 0 );
}

/**
 * Check review enable status.
 *
 * @return bool
 */
function directorist_is_review_enabled() {
	return (bool) get_directorist_option( 'enable_review', 1 );
}

/**
 * Check owner review status.
 *
 * @return void
 */
function directorist_is_owner_review_enabled() {
	return (bool) get_directorist_option( 'enable_owner_review', true );
}

/**
 * Check review immediate approval status.
 *
 * @return bool
 */
function directorist_is_immediate_review_approve_enabled() {
	return (bool) get_directorist_option( 'approve_immediately', 1 );
}

/**
 * Get number of reviews and comments should be on a page.
 *
 * @return int
 */
function directorist_get_review_per_page() {
	$per_page = get_directorist_option( 'review_num', 5 );
	return absint( $per_page );
}

function directorist_get_listing_rating( $listing_id, $round_precision = 1 ) {
	return \Directorist\Review\Listing_Review_Meta::get_rating( $listing_id, $round_precision );
}

function directorist_get_listing_review_count( $listing_id ) {
	return \Directorist\Review\Listing_Review_Meta::get_review_count( $listing_id );
}

function directorist_get_rating_field_meta_key() {
	return \Directorist\Review\Listing_Review_Meta::FIELD_AVG_RATING;
}
