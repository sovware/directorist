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
	if ( ! directorist_is_directory( $directory_id ) ) {
		return false;
	}

	if ( empty( $meta_key ) ) {
		return false;
	}

    return get_term_meta( $directory_id, $meta_key, true );
}

function directorist_get_listing_form_data( int $directory_id ) {
	$form_data = directorist_get_directory_meta( $directory_id, 'submission_form_fields' );
	$_fields   = directorist_get_var( $form_data['fields'], array() );
	$groups    = directorist_get_var( $form_data['groups'], array() );
	$fields    = array();

	foreach ( $groups as &$group ) {
		$allowed_fields_key = array();

		foreach ( $group['fields'] as $field_key ) {
			if ( empty( $_fields[ $field_key ] ) || ! is_array( $_fields[ $field_key ] ) ) {
				continue;
			}

			if ( (bool) apply_filters( 'directorist_listing_form_field_is_allowed', true, $_fields[ $field_key ], $directory_id ) ) {
				$allowed_fields_key[] = $field_key;
				$fields[ $field_key ] = apply_filters( 'directorist_listing_form_field', $_fields[ $field_key ], $directory_id );
			}
		}

		$group['fields'] = $allowed_fields_key;
	}

	return array(
		'fields' => $fields,
		'groups' => $groups,
	);
}

function directorist_get_listing_form_fields( int $directory_id ) {
	$fields = directorist_get_listing_form_data( $directory_id )['fields'];

	return apply_filters( 'directorist_listing_form_fields', $fields, $directory_id );
}

function directorist_get_listing_form_groups( int $directory_id ) {
	$_groups = directorist_get_listing_form_data( $directory_id )['groups'];
	$groups  = array();

    foreach ( $_groups as $group ) {
		if ( empty( $group['fields'] ) ) {
			continue;
		}

		$groups[] = array(
			'label'  => $group['label'],
			'fields' => $group['fields'],
		);
	}

	return apply_filters( 'directorist_listing_form_field_groups', $groups, $directory_id );
}

function directorist_get_listing_form_field( $directory_id, $field_key = '' ) {
	if ( empty( $field_key ) ) {
		return array();
	}

	$fields = directorist_get_listing_form_fields( $directory_id );

	return ( empty( $fields[ $field_key ] ) ? array() : $fields[ $field_key ] );
}

function directorist_get_listing_form_category_field( int $directory_id ) {
	return directorist_get_listing_form_field( $directory_id, 'category' );
}

function directorist_listing_form_has_category_field( int $directory_id ) {
	$category_field = directorist_get_listing_form_category_field( $directory_id );
	return ! empty( $category_field );
}

function directorist_is_multi_directory_enabled() {
	return (bool) get_directorist_option( 'enable_multi_directory', false );
}

function directorist_is_guest_submission_enabled() {
	return (bool) get_directorist_option( 'guest_listings', 0 );
}

function directorist_is_featured_listing_enabled() {
	return (bool) get_directorist_option( 'enable_featured_listing' );
}

function directorist_is_monetization_enabled() {
	return (bool) get_directorist_option( 'enable_monetization' );
}

function directorist_is_terms_and_condition_enabled( int $directory_id ) {
	return (bool) directorist_get_directory_meta( $directory_id, 'listing_terms_condition' );
}

function directorist_is_terms_and_condition_required( int $directory_id ) {
	return (bool) directorist_get_directory_meta( $directory_id, 'require_terms_conditions' );
}

function directorist_should_check_terms_and_condition( int $directory_id ) {
	return ( directorist_is_terms_and_condition_enabled( $directory_id ) && directorist_is_terms_and_condition_required( $directory_id ) );
}

function directorist_is_privacy_policy_enabled( int $directory_id ) {
	return (bool) directorist_get_directory_meta( $directory_id, 'listing_privacy' );
}

function directorist_is_privacy_policy_required( int $directory_id ) {
	return (bool) directorist_get_directory_meta( $directory_id, 'require_privacy' );
}

function directorist_should_check_privacy_policy( int $directory_id ) {
	return ( directorist_is_privacy_policy_enabled( $directory_id ) && directorist_is_privacy_policy_required( $directory_id ) );
}

function directorist_get_new_listing_status( int $directory_id ) {
	return directorist_get_directory_meta( $directory_id, 'new_listing_status' );
}

function directorist_get_edit_listing_status( int $directory_id ) {
	return directorist_get_directory_meta( $directory_id, 'edit_listing_status' );
}

function directorist_get_default_expiration( int $directory_id ) {
	return directorist_get_directory_meta( $directory_id, 'default_expiration' );
}

function directorist_is_directory( $directory_id ) {
	$directory = term_exists( absint( $directory_id ), ATBDP_DIRECTORY_TYPE );

	return ( ! empty( $directory ) );
}

function directorist_set_listing_directory( $listing_id, $directory_id ) {
	if ( ! directorist_is_listing_post_type( $listing_id ) ) {
		return new WP_Error( 'invalid_listing', __( 'Invalid listing id.', 'directorist' ) );
	}

	if ( ! directorist_is_directory( $directory_id ) ) {
		return new WP_Error( 'invalid_directory', __( 'Invalid directory id.', 'directorist' ) );
	}

	update_post_meta( $listing_id, '_directory_type', $directory_id );
	wp_set_object_terms( $listing_id, $directory_id, ATBDP_DIRECTORY_TYPE );

	return true;
}

function directorist_get_single_listing_data( int $directory_id ) {
	$single_listing_data = directorist_get_directory_meta( $directory_id, 'single_listings_contents' );
	$_fields             = directorist_get_var( $single_listing_data['fields'], array() );
	$groups              = directorist_get_var( $single_listing_data['groups'], array() );
	$listing_form_fields = directorist_get_listing_form_fields( $directory_id );
	$fields              = array();

	foreach ( $groups as &$group ) {
		$allowed_fields_key = array();

		foreach ( $group['fields'] as $field_key ) {
			if ( empty( $_fields[ $field_key ] ) || ! is_array( $_fields[ $field_key ] ) ) {
				continue;
			}

			if ( ! isset( $_fields[ $field_key ]['original_widget_key'] ) || ! isset( $listing_form_fields[ $_fields[ $field_key ]['original_widget_key'] ] ) ) {
				continue;
			}

			$fields[ $field_key ] = $_fields[ $field_key ];
			$allowed_fields_key[] = $field_key;
		}

		$group['fields'] = $allowed_fields_key;
	}

	return array(
		'fields' => $fields,
		'groups' => $groups,
	);
}

function directorist_get_single_listing_fields( int $directory_id ) {
	return directorist_get_single_listing_data( $directory_id )['fields'];
}

function directorist_get_single_listing_groups( int $directory_id ) {
	$groups = directorist_get_single_listing_data( $directory_id )['groups'];

	return array_filter( $groups, static function( $group ) {
		return ! empty( $group['fields'] );
	} );
}
