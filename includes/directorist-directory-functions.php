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

function directorist_get_directory_meta( $directory_id, string $meta_key ) {
	if ( ! term_exists( $directory_id, ATBDP_DIRECTORY_TYPE ) ) {
		return false;
	}

	if ( empty( $meta_key ) ) {
		return false;
	}

    return get_term_meta( $directory_id, $meta_key, true );
}

function directorist_get_listing_form_fields( $directory_id ) {
	$form_data = directorist_get_directory_meta( $directory_id, 'submission_form_fields' );
	$_fields   = directorist_get_var( $form_data['fields'], array() );
	$_groups   = directorist_get_var( $form_data['groups'], array() );

	$fields_keys = array();
	$fields      = array();

	foreach ( $_groups as $group ) {
		$fields_keys = array_merge( $fields_keys, $group['fields'] );
	}

	foreach ( $fields_keys as $field_key ) {
		$fields[ $field_key ] = $_fields[ $field_key ];
	}

	return $fields;
}

function directorist_get_listing_form_groups( $directory_id ) {
	$form_data = directorist_get_directory_meta( $directory_id, 'submission_form_fields' );
	$_groups   = directorist_get_var( $form_data['groups'], array() );
	$groups    = array();

    foreach ( $_groups as $group ) {
		$groups[] = array(
			'label' => $group['label'],
			'fields' => $group['fields'],
		);
	}

	return $groups;
}

function directorist_get_listing_form_field( $directory_id, $field_key = '' ) {
	if ( empty( $field_key ) ) {
		return array();
	}

	$form_fields = directorist_get_listing_form_fields( $directory_id );

	return empty( $form_fields[ $field_key ] ) ? array() : $form_fields[ $field_key ];
}

function directorist_get_listing_form_category_field( $directory_id ) {
	return directorist_get_listing_form_field( $directory_id, 'category' );
}

function directorist_listing_form_has_category_field( $directory_id ) {
	$category_field = directorist_get_listing_form_category_field( $directory_id );
	return ! empty( $category_field );
}

function directorist_is_terms_and_condition_enabled( $directory_id ) {
	return (bool) directorist_get_directory_meta( $directory_id, 'listing_terms_condition' );
}

function directorist_is_terms_and_condition_required( $directory_id ) {
	return (bool) directorist_get_directory_meta( $directory_id, 'require_terms_conditions' );
}

function directorist_should_check_terms_and_condition( $directory_id ) {
	return ( directorist_is_terms_and_condition_enabled( $directory_id ) && directorist_is_terms_and_condition_required( $directory_id ) );
}

function directorist_is_privacy_policy_enabled( $directory_id ) {
	return (bool) directorist_get_directory_meta( $directory_id, 'listing_privacy' );
}

function directorist_is_privacy_policy_required( $directory_id ) {
	return (bool) directorist_get_directory_meta( $directory_id, 'require_privacy' );
}

function directorist_should_check_privacy_policy( $directory_id ) {
	return ( directorist_is_privacy_policy_enabled( $directory_id ) && directorist_is_privacy_policy_required( $directory_id ) );
}

/**
 * Get listing default create status from directory settings.
 *
 * @param  int  $directory_id
 *
 * @return string Default create status.
 */
function directorist_get_listing_create_status( $directory_id ) {
	$status = directorist_get_directory_meta( $directory_id, 'new_listing_status' );

	return apply_filters( 'directorist_listing_create_status', $status, $directory_id );
}

/**
 * Get listing default edit status from directory settings.
 * And if $listing_id is given then follow the conditions.
 *
 * @param  int  $directory_id
 * @param  int  $listing_id Added in version 8.
 *
 * @return string Default edit status.
 */
function directorist_get_listing_edit_status( $directory_id, $listing_id = 0 ) {
	$builder_status = directorist_get_directory_meta( $directory_id, 'edit_listing_status' );
	$status         = 'pending';
	/**
	 * Builder Edit Status, Listing Edit Status.
	 * BES - Publish
	 * LES - Publish
	 * Result - Publish
	 *
	 * BES - Pending
	 * LES - Pending
	 * Result - Pending
	 *
	 * BES - Publish
	 * LES - Pending
	 * Result - Pending
	 *
	 * BES - Pending
	 * LES - Publish
	 * Result - Pending
	 */
	if ( $listing_id && ( $listing_status = get_post_status( $listing_id ) ) !== false ) {
		if ( $builder_status === 'publish' && $listing_status === 'publish' ) {
			$status = 'publish';
		} else if ( $builder_status === 'pending' || $listing_status === 'pending' ) {
			$status = 'pending';
		}
	}

	return apply_filters( 'directorist_listing_edit_status', $status, $directory_id );
}

function directorist_get_default_expiration( $directory_id ) {
	return (int) directorist_get_directory_meta( $directory_id, 'default_expiration' );
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

function directorist_get_field( $properties ) {
	return Directorist\Fields\Fields::create( $properties );
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

/**
 * Get directory General tab settings.
 *
 * @param int $directory_id
 * @since 7.8.9
 *
 * @return array
 */
function directorist_get_directory_general_settings( $directory_id ) {
	$settings = (array) directorist_get_directory_meta( $directory_id, 'general_config' );
	$defaults = array(
		'icon'          => '',
		'preview_image' => '',
	);

	return array_merge( $defaults, $settings );
}

function directorist_get_directories( array $args = array() ) {
	$defaults = array(
		'hide_empty' => false,
		'default_only' => false,
	);

	$args = wp_parse_args( $args, $defaults );

	if ( $args['default_only'] ) {
		$args['number']     = 1;
		$args['meta_value'] = '1';
		$args['meta_key']   = '_default';

		unset( $args['default_only'] );
	}

	$args['taxonomy'] = ATBDP_DIRECTORY_TYPE;

	return get_terms( $args );
}

function directorist_get_directories_for_template( array $args = array() ) {
	$directories = directorist_get_directories( $args );

	if ( is_wp_error( $directories ) ) {
		return array();
	}

	return array_reduce( $directories, static function( $carry, $directory ) {
		$carry[ $directory->term_id ] = array(
			'term' => $directory,
			'name' => $directory->name,
			'data' => directorist_get_directory_general_settings( $directory->term_id ),
		);

		return $carry;
	}, array() );
}

/**
 * Get the the relations of directory custom fields to category.
 *
 * @since 8.0.0
 * @param  int $directory_id
 *
 * @return array
 */
function directorist_get_category_custom_field_relations( $directory_id ) {
	$submission_form_fields = get_term_meta( $directory_id, 'submission_form_fields', true );

	if ( empty( $submission_form_fields['fields'] ) ) {
		return array();
	}

	$relations = array();

	foreach( $submission_form_fields['fields'] as $field ) {
		if ( empty( $field['assign_to'] ) || empty( $field['category'] ) ) {
			continue;
		}

		$relations[ $field['field_key'] ] = (int) $field['category'];
	}

	return $relations;
}
