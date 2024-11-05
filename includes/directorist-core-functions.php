<?php
/**
 * Directorist Core Functions
 *
 * @package Directorist\Functions
 * @version 8.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function directorist_is_multi_directory_enabled() {
	return (bool) get_directorist_option( 'enable_multi_directory', true );
}

function directorist_is_guest_submission_enabled() {
	return (bool) get_directorist_option( 'guest_listings', false );
}

function directorist_is_featured_listing_enabled() {
	return (bool) get_directorist_option( 'enable_featured_listing' );
}

function directorist_is_monetization_enabled() {
	return (bool) get_directorist_option( 'enable_monetization' );
}

function directorist_get_currency() {
	return get_directorist_option( 'g_currency', 'USD' );
}

function directorist_get_currency_position() {
	return get_directorist_option( 'g_currency_position' );
}

function directorist_can_user_renew_listings() {
	return (bool) get_directorist_option( 'can_renew_listing', true );
}
