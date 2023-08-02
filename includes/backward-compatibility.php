<?php
/**
 * This file contains all the backward compatible functions, filters and actions.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Backward compatibility for get_post_meta( $listing_id, '_listing_status', true );
 * @since 7.7
 */
add_filter( 'get_post_metadata', static function( $value, $object_id, $meta_key ) {
	if ( get_post_type( $object_id ) === ATBDP_POST_TYPE && $meta_key === '_listing_status' ) {
		return get_post_status( $object_id );
	}

	return $value;
}, 9999, 3 );
