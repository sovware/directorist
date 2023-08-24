<?php
/**
 * Directorist Directory Functions
 *
 * Directory related functions.
 *
 * @package Directorist\Functions
 * @version 8.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function directorist_get_directory_meta( int $directory_id, string $meta_key ) {
	if ( ! term_exists( $directory_id, ATBDP_DIRECTORY_TYPE ) ) {
		return new WP_Error( 'invalid_directory', __( 'Invalid directory.', 'directorist' ) );
	}

	if ( empty( $meta_key ) ) {
		return new WP_Error( 'empty_directory_meta_key', __( 'Empty directory meta key.', 'directorist' ) );
	}

    return get_term_meta( $directory_id, $meta_key, true );
}

function directorist_get_listing_submission_meta( int $directory_id ) {
    return directorist_get_directory_meta( $directory_id, 'submission_form_fields' );
}

function directorist_get_listing_submission_form_fields( int $directory_id ) {
    $form_data = directorist_get_listing_submission_meta( $directory_id );
    return $form_data['fields'];
}

function directorist_get_listing_submission_form_groups( int $directory_id ) {
	$form_meta = directorist_get_listing_submission_meta( $directory_id );
	$groups    = array();

    foreach ( $form_meta['groups'] as $group ) {
		$groups[] = array(
			'label'  => $group['label'],
			'fields' => $group['fields'],
		);
	}

	return $groups;
}

function directorist_generate_listing_submission_form_group_key( array $fields ) {
	return md5( json_encode( $fields ) );
}
