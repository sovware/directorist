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
	return \Directorist\Helper::is_review_enabled();
}

function directorist_is_owner_review_enabled() {
	return (bool) get_directorist_option( 'enable_owner_review', true );
}

function directorist_is_immediate_review_approve_enabled() {
	return get_directorist_option( 'approve_immediately', 1 );
}

function directorist_get_review_per_page() {
	return get_directorist_option( 'review_num', 5 );
}
