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

function directorist_get_listing_form_fields_data( $directory_id ) {
	$form_data = directorist_get_directory_meta( $directory_id, 'submission_form_fields' );
	return directorist_get_var( $form_data['fields'], array() );
}

function directorist_get_listing_form_groups_data( $directory_id ) {
	$form_data = directorist_get_directory_meta( $directory_id, 'submission_form_fields' );
	return directorist_get_var( $form_data['groups'], array() );
}

function directorist_get_listing_form_data( int $directory_id, $plan_id = 0 ) {
	$_fields   = directorist_get_listing_form_fields_data( $directory_id );
	$groups    = directorist_get_listing_form_groups_data( $directory_id );
	$fields    = array();

	foreach ( $groups as &$group ) {
		$allowed_fields_key = array();

		foreach ( $group['fields'] as $field_key ) {
			if ( empty( $_fields[ $field_key ] ) || ! is_array( $_fields[ $field_key ] ) ) {
				continue;
			}

			if ( (bool) apply_filters( 'directorist_listing_form_field_is_allowed', true, $_fields[ $field_key ], $directory_id, $plan_id ) ) {
				$allowed_fields_key[] = $field_key;
				$fields[ $field_key ] = apply_filters( 'directorist_listing_form_field', $_fields[ $field_key ], $directory_id, $plan_id );
			}
		}

		$group['fields'] = $allowed_fields_key;
	}

	return array(
		'fields' => $fields,
		'groups' => $groups,
	);
}

function directorist_get_listing_form_fields( int $directory_id, $plan_id = 0 ) {
	$fields = directorist_get_listing_form_data( $directory_id, $plan_id )['fields'];

	return apply_filters( 'directorist_listing_form_fields', $fields, $directory_id, $plan_id );
}

function directorist_get_listing_form_groups( int $directory_id, $plan_id = 0 ) {
	$_groups = directorist_get_listing_form_data( $directory_id, $plan_id )['groups'];
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

	return apply_filters( 'directorist_listing_form_field_groups', $groups, $directory_id, $plan_id );
}

function directorist_get_listing_form_field( $directory_id, $field_key = '' ) {
	if ( empty( $field_key ) ) {
		return array();
	}

	$form_fields = directorist_get_listing_form_fields( $directory_id );

	return empty( $form_fields[ $field_key ] ) ? array() : $form_fields[ $field_key ];
}

function directorist_get_listing_form_category_field( int $directory_id ) {
	return directorist_get_listing_form_field( $directory_id, 'category' );
}

function directorist_listing_form_has_category_field( int $directory_id ) {
	$category_field = directorist_get_listing_form_category_field( $directory_id );
	return ! empty( $category_field );
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

function directorist_get_single_listing_data( int $directory_id, $plan_id = 0 ) {
	$single_listing_data = directorist_get_directory_meta( $directory_id, 'single_listings_contents' );
	$_fields             = directorist_get_var( $single_listing_data['fields'], array() );
	$groups              = directorist_get_var( $single_listing_data['groups'], array() );
	$listing_form_fields = directorist_get_listing_form_fields( $directory_id, $plan_id );
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

function directorist_get_single_listing_fields( int $directory_id, $plan_id = 0 ) {
	return directorist_get_single_listing_data( $directory_id, $plan_id )['fields'];
}

function directorist_get_single_listing_groups( int $directory_id, $plan_id = 0 ) {
	$groups = directorist_get_single_listing_data( $directory_id, $plan_id )['groups'];

	return array_filter( $groups, static function( $group ) {
		return ! empty( $group['fields'] );
	} );
}

function directorist_update_term_directory( $term_id, array $directory_ids = array(), $append = false ) {
	if ( empty( $directory_ids ) ) {
		return;
	}

	$directory_ids = wp_parse_id_list( $directory_ids );

	if ( $append ) {
		$old_directory_ids = directorist_get_term_directory( $term_id );
		$directory_ids     = array_unique( array_merge( $old_directory_ids, $directory_ids ) );
	}

	update_term_meta( $term_id, '_directory_type', $directory_ids );
}

function directorist_update_location_directory( $location_id, array $directory_ids = array(), $append = false) {
	directorist_update_term_directory( $location_id, $directory_ids, $append );
}

function directorist_update_category_directory( $location_id, array $directory_ids = array(), $append = false) {
	directorist_update_term_directory( $location_id, $directory_ids, $append );
}

function directorist_get_term_directory( $term_id ) {
	$directories = (array) get_term_meta( $term_id, '_directory_type', true );

	if ( empty( $directories ) ) {
		return array();
	}

	return wp_parse_id_list( $directories );
}

function directorist_get_location_directory( $location_id ) {
	return directorist_get_term_directory( $location_id );
}

function directorist_get_category_directory( $category_id ) {
	return directorist_get_term_directory( $category_id );
}
