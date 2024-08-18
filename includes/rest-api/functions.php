<?php
/**
 * Rest API helper functions.
 */
defined( 'ABSPATH' ) || exit;

/**
 * Parses and formats a date for ISO8601/RFC3339.
 *
 * Required WP 4.4 or later.
 * See https://developer.wordpress.org/reference/functions/mysql_to_rfc3339/
 * @see wc_rest_prepare_date_response
 *
 * @param  string|null|Directorist_DateTime $date Date.
 * @param  bool                    $utc  Send false to get local/offset time.
 * @return string|null ISO8601/RFC3339 formatted datetime.
 */
function directorist_rest_prepare_date_response( $date, $utc = true ) {
	if ( is_numeric( $date ) ) {
		$date = new Directorist_DateTime( "@$date", new DateTimeZone( 'UTC' ) );
		$date->setTimezone( new DateTimeZone( directorist_timezone_string() ) );
	} elseif ( is_string( $date ) ) {
		$date = new Directorist_DateTime( $date, new DateTimeZone( 'UTC' ) );
		$date->setTimezone( new DateTimeZone( directorist_timezone_string() ) );
	}

	if ( ! is_a( $date, 'Directorist_DateTime' ) ) {
		return null;
	}

	// Get timestamp before changing timezone to UTC.
	return gmdate( 'Y-m-d\TH:i:s', $utc ? $date->getTimestamp() : $date->getOffsetTimestamp() );
}


/**
 * Directorist Timezone - helper to retrieve the timezone string for a site until.
 * a WP core method exists (see https://core.trac.wordpress.org/ticket/24730).
 *
 * Adapted from https://secure.php.net/manual/en/function.timezone-name-from-abbr.php#89155.
 *
 * Copied from wc_timezone_string
 *
 * @return string PHP timezone string for the site
 */
function directorist_timezone_string() {
	// Added in WordPress 5.3 Ref https://developer.wordpress.org/reference/functions/wp_timezone_string/.
	if ( function_exists( 'wp_timezone_string' ) ) {
		return wp_timezone_string();
	}

	// If site timezone string exists, return it.
	$timezone = get_option( 'timezone_string' );
	if ( $timezone ) {
		return $timezone;
	}

	// Get UTC offset, if it isn't set then return UTC.
	$utc_offset = floatval( get_option( 'gmt_offset', 0 ) );
	if ( ! is_numeric( $utc_offset ) || 0.0 === $utc_offset ) {
		return 'UTC';
	}

	// Adjust UTC offset from hours to seconds.
	$utc_offset = (int) ( $utc_offset * 3600 );

	// Attempt to guess the timezone string from the UTC offset.
	$timezone = timezone_name_from_abbr( '', $utc_offset );
	if ( $timezone ) {
		return $timezone;
	}

	// Last try, guess timezone string manually.
	foreach ( timezone_abbreviations_list() as $abbr ) {
		foreach ( $abbr as $city ) {
			// WordPress restrict the use of date(), since it's affected by timezone settings, but in this case is just what we need to guess the correct timezone.
			if ( (bool) date( 'I' ) === (bool) $city['dst'] && $city['timezone_id'] && intval( $city['offset'] ) === $utc_offset ) { // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
				return $city['timezone_id'];
			}
		}
	}

	// Fallback to UTC.
	return 'UTC';
}

/**
 * Upload image from URL.
 *
 * Copied from wc_rest_upload_image_from_url
 *
 * @param string $image_url Image URL.
 * @return array|WP_Error Attachment data or error message.
 */
function directorist_rest_upload_image_from_url( $image_url ) {
	$parsed_url = wp_parse_url( $image_url );

	// Check parsed URL.
	if ( ! $parsed_url || ! is_array( $parsed_url ) ) {
		/* translators: %s: image URL */
		return new WP_Error( 'directorist_rest_invalid_image_url', sprintf( __( 'Invalid URL %s.', 'directorist' ), $image_url ), array( 'status' => 400 ) );
	}

	// Ensure url is valid.
	$image_url = esc_url_raw( $image_url );

	// download_url function is part of wp-admin.
	if ( ! function_exists( 'download_url' ) ) {
		include_once ABSPATH . 'wp-admin/includes/file.php';
	}

	$file_array         = array();
	$file_array['name'] = basename( current( explode( '?', $image_url ) ) );

	// Download file to temp location.
	$file_array['tmp_name'] = download_url( $image_url );

	// If error storing temporarily, return the error.
	if ( is_wp_error( $file_array['tmp_name'] ) ) {
		return new WP_Error(
			'directorist_rest_invalid_remote_image_url',
			/* translators: %s: image URL */
			sprintf( __( 'Error getting remote image %s.', 'directorist' ), $image_url ) . ' '
			/* translators: %s: error message */
			. sprintf( __( 'Error: %s', 'directorist' ), $file_array['tmp_name']->get_error_message() ),
			array( 'status' => 400 )
		);
	}

	$allowed_mime_types = get_allowed_mime_types();

	// Add extension to the name when downloaded from extension less url
	if ( strrpos( $file_array['name'], '.' ) === false ) {
		$mime_type          = mime_content_type( $file_array['tmp_name'] );
		$_mime_types        = array_flip( $allowed_mime_types );
		$extensions         = $_mime_types[ $mime_type ] ?? '';
		$extensions         = explode( '|', $extensions, 2 );
		$file_array['name'] .= '.' . $extensions[0];
	}

	// Do the validation and storage stuff.
	$file = wp_handle_sideload(
		$file_array,
		array(
			'test_form' => false,
			'mimes'     => $allowed_mime_types,
		),
		current_time( 'Y/m' )
	);

	if ( isset( $file['error'] ) ) {
		@unlink( $file_array['tmp_name'] ); // @codingStandardsIgnoreLine.

		/* translators: %s: error message */
		return new WP_Error( 'directorist_rest_invalid_image', sprintf( __( 'Invalid image: %s', 'directorist' ), $file['error'] ), array( 'status' => 400 ) );
	}

	do_action( 'directorist_rest_api_uploaded_image_from_url', $file, $image_url );

	return $file;
}

/**
 * Returns image mime types users are allowed to upload via the API.
 *
 * Copied from wc_rest_allowed_image_mime_types
 *
 * @return array
 */
function directorist_rest_allowed_image_mime_types() {
	return apply_filters(
		'directorist_rest_allowed_image_mime_types',
		array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			// 'gif'          => 'image/gif',
			'png'          => 'image/png',
			// 'bmp'          => 'image/bmp',
			// 'tiff|tif'     => 'image/tiff',
			// 'ico'          => 'image/x-icon',
		)
	);
}

/**
 * Set uploaded image as attachment.
 *
 * Copied from wc_rest_set_uploaded_image_as_attachment
 *
 * @param array $upload Upload information from wp_upload_bits.
 * @param int   $id Post ID. Default to 0.
 * @return int Attachment ID
 */
function directorist_rest_set_uploaded_image_as_attachment( $upload, $id = 0 ) {
	$info    = wp_check_filetype( $upload['file'] );
	$title   = '';
	$content = '';

	if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
		include_once ABSPATH . 'wp-admin/includes/image.php';
	}

	$image_meta = wp_read_image_metadata( $upload['file'] );
	if ( $image_meta ) {
		if ( trim( $image_meta['title'] ) && ! is_numeric( sanitize_title( $image_meta['title'] ) ) ) {
			$title = directorist_clean( $image_meta['title'] );
		}
		if ( trim( $image_meta['caption'] ) ) {
			$content = directorist_clean( $image_meta['caption'] );
		}
	}

	$attachment = array(
		'post_mime_type' => $info['type'],
		'guid'           => $upload['url'],
		'post_parent'    => $id,
		'post_title'     => $title ? $title : basename( $upload['file'] ),
		'post_content'   => $content,
	);

	$attachment_id = wp_insert_attachment( $attachment, $upload['file'], $id );
	if ( ! is_wp_error( $attachment_id ) ) {
		wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $upload['file'] ) );
	}

	return $attachment_id;
}

/**
 * Check permissions of listing terms on REST API.
 *
 * Copied from wc_rest_check_product_term_permissions
 *
 * @param string $taxonomy  Taxonomy.
 * @param string $context   Request context.
 * @param int    $object_id Post ID.
 * @return bool
 */
function directorist_rest_check_listing_term_permissions( $taxonomy, $context = 'read', $object_id = 0 ) {
	$contexts = array(
		'read'   => 'manage_terms',
		'create' => 'edit_terms',
		'edit'   => 'edit_terms',
		'delete' => 'delete_terms',
		'batch'  => 'edit_terms',
	);

	$cap             = $contexts[ $context ];
	$taxonomy_object = get_taxonomy( $taxonomy );
	$permission      = current_user_can( $taxonomy_object->cap->$cap, $object_id );

	return apply_filters( 'directorist_rest_check_permissions', $permission, $context, $object_id, $taxonomy );
}

/**
 * Converts a string (e.g. 'yes' or 'no') to a bool.
 *
 * @since 7.1.0
 * @param string|bool $string String to convert. If a bool is passed it will be returned as-is.
 * @return bool
 */
function directorist_string_to_bool( $string ) {
	return is_bool( $string ) ? $string : ( 'yes' === strtolower( $string ) || 1 === $string || 'true' === strtolower( $string ) || '1' === $string );
}

/**
 * Converts a bool to a 'yes' or 'no'.
 *
 * @since 7.1.0
 * @param bool|string $bool Bool to convert. If a string is passed it will first be converted to a bool.
 * @return string
 */
function directorist_bool_to_string( $bool ) {
	if ( ! is_bool( $bool ) ) {
		$bool = directorist_string_to_bool( $bool );
	}
	return true === $bool ? 'yes' : 'no';
}


/**
 * Check permissions of users on REST API.
 *
 * Copied from wc_rest_check_user_permissions
 *
 * @param string $context   Request context.
 * @param int    $object_id Post ID.
 * @return bool
 */
function directorist_rest_check_user_permissions( $context = 'read', $object_id = 0 ) {
	$contexts = array(
		'read'   => 'edit_user',
		'create' => 'promote_users',
		'edit'   => 'edit_user',
		'delete' => 'delete_users',
		'batch'  => 'promote_users',
	);

	$permission = current_user_can( $contexts[ $context ], $object_id );

	return apply_filters( 'directorist_rest_check_permissions', $permission, $context, $object_id, 'user' );
}

/**
 * Helper to get cached object terms and filter by field using wp_list_pluck().
 * Works as a cached alternative for wp_get_post_terms() and wp_get_object_terms().
 *
 * @param  int    $object_id Object ID.
 * @param  string $taxonomy  Taxonomy slug.
 * @param  string $field     Field name.
 * @param  string $index_key Index key name.
 * @return array
 */
function directorist_get_object_terms( $object_id, $taxonomy, $field = null, $index_key = null ) {
	// Test if terms exists. get_the_terms() return false when it finds no terms.
	$terms = get_the_terms( $object_id, $taxonomy );

	if ( ! $terms || is_wp_error( $terms ) ) {
		return array();
	}

	return is_null( $field ) ? $terms : wp_list_pluck( $terms, $field, $index_key );
}

/**
 * Check listings reviews permissions on REST API.
 *
 * Copied from wc_rest_check_product_reviews_permissions
 *
 * @param string $context   Request context.
 * @param string $object_id Object ID.
 * @return bool
 */
function directorist_rest_check_listing_reviews_permissions( $context = 'read', $object_id = 0 ) {
	$permission = false;
	$contexts   = array(
		'read'   => 'moderate_comments',
		'create' => 'moderate_comments',
		'edit'   => 'moderate_comments',
		'delete' => 'moderate_comments',
		'batch'  => 'moderate_comments',
	);

	if ( isset( $contexts[ $context ] ) ) {
		$permission = current_user_can( $contexts[ $context ] );
	}

	return apply_filters( 'directorist_rest_check_permissions', $permission, $context, $object_id, 'listing_review' );
}

/**
 * Check permissions of users favorite on REST API.
 *
 * @param string $context   Request context.
 * @param int    $object_id Post ID.
 * @return bool
 */
function directorist_rest_check_user_favorite_permissions( $context = 'read', $object_id = 0 ) {
	$contexts = array(
		'read'   => 'read',
		'create' => 'read',
		'edit'   => 'read',
		'delete' => 'read',
		'batch'  => 'read',
	);

	$permission = current_user_can( $contexts[ $context ], $object_id );

	return apply_filters( 'directorist_rest_check_permissions', $permission, $context, $object_id, 'user_favorite' );
}