<?php
/**
 * Review related helper functions.
 *
 * @package Directorist\Review
 * @since 7.0.6
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function directorist_is_guest_review_enabled() {
	return (bool) get_directorist_option( 'guest_review', 0 );
}

function directorist_is_review_enabled() {
	return (bool) get_directorist_option( 'enable_review', 1 );
}

function directorist_is_owner_review_enabled() {
	return (bool) get_directorist_option( 'enable_owner_review', true );
}

function directorist_is_immediate_review_approve_enabled() {
	return (bool) get_directorist_option( 'approve_immediately', 1 );
}

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
