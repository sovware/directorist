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

/**
 * Get listing rating which is cached and calculated from listing review rating.
 *
 * @param int $listing_id
 * @param int $round_precision
 *
 * @return float|int
 */
function directorist_get_listing_rating( $listing_id, $round_precision = 1 ) {
	return \Directorist\Review\Listing_Review_Meta::get_rating( $listing_id, $round_precision );
}

/**
 * Get listing review count.
 *
 * @param int $listing_id
 *
 * @return int
 */
function directorist_get_listing_review_count( $listing_id ) {
	return \Directorist\Review\Listing_Review_Meta::get_review_count( $listing_id );
}

/**
 * Listing rating meta field key.
 *
 * @return string
 */
function directorist_get_rating_field_meta_key() {
	return \Directorist\Review\Listing_Review_Meta::FIELD_AVG_RATING;
}

/**
 * Get comment edit link.
 *
 * @param array $args
 * @param WP_Comment|string|int $comment
 * @param WP_Post|int $post
 *
 * @return void
 */
function directorist_get_comment_edit_link( $args = array(), $comment = null, $post = null ) {
    $defaults = array(
        'edit_text'    => __( 'Edit', 'directorist' ),
        'edit_of_text' => __( 'Edit %s', 'directorist' ),
        'max_depth'    => 0,
        'depth'        => 0,
    );

    $args = wp_parse_args( $args, $defaults );

    $comment = get_comment( $comment );

    if ( empty( $comment ) ) {
        return;
    }

	if ( ! current_user_can( 'edit_comment', $comment->comment_ID ) ) {
		return;
	}

    if ( empty( $post ) ) {
        $post = $comment->comment_post_ID;
    }

    $post = get_post( $post );

    if ( ! comments_open( $post->ID ) ) {
        return false;
    }

	$comment_type = ( $comment->comment_type === 'review' ? __( 'review', 'directorist' ) : __( 'comment', 'directorist' ) );
	$data_attributes = array(
		'commentid' => $comment->comment_ID,
		'postid'    => $post->ID,
		'editof'    => sprintf( $args['edit_of_text'], $comment_type ),
	);

	$data_attribute_string = '';

	foreach ( $data_attributes as $name => $value ) {
		$data_attribute_string .= " data-{$name}=\"" . esc_attr( $value ) . '"';
	}

	$data_attribute_string = trim( $data_attribute_string );

	$link = sprintf(
		"<a rel='nofollow' class='directorist-comment-edit-link directorist-js-edit-comment' href='%s' %s aria-label='%s'>%s</a>",
		esc_url( directorist_get_comment_form_ajax_url( 'edit' ) ),
		$data_attribute_string,
		esc_attr( sprintf( $args['edit_of_text'], $comment_type ) ),
		$args['edit_text']
	);

    return apply_filters( 'directorist_comment_edit_link', $link, $args, $comment, $post );
}

/**
 * Get comment form renderer ajax url
 *
 * @param string $type
 *
 * @return string
 */
function directorist_get_comment_form_ajax_url( $type = 'add' ) {
	return \Directorist\Review\Comment_Form_Renderer::get_ajax_url( $type );
}

/**
 * Check if user already shared a review.
 *
 * @param string $user_email
 * @param int $post_id.
 * @return bool
 */
function directorist_user_review_exists( $user_email, $post_id ) {
	if ( ! is_email( $user_email ) ) {
		return false;
	}

	global $wpdb;

	$cache_key = 'directorist_user_review_found_by_' . md5( $user_email . $post_id );
	$exists    = wp_cache_get( $cache_key );

	if ( ! $exists ) {
		$exists = $wpdb->get_var(
			$wpdb->prepare(
				"
			SELECT count(comment_ID) FROM $wpdb->comments
			WHERE comment_post_ID = %d
			AND ( comment_approved = '1' OR comment_approved = '0' )
			AND comment_type = 'review'
			AND comment_author_email = %s
				",
				$post_id,
				$user_email
			)
		);

		if ( ! empty( $exists ) ) {
			wp_cache_set( $cache_key, $exists );
		}
	}

	return (bool) $exists;
}

/**
 * Check if reply is enabled for review.
 *
 * @return bool
 */
function directorist_is_review_reply_enabled() {
	return (bool) get_directorist_option( 'review_enable_reply', false );
}

/**
 * Check if listing owner can review.
 *
 * @since 7.1.1
 * @param int $listing_id
 *
 * @return bool
 */
function directorist_can_owner_review( $listing_id = null ) {
	return ( directorist_is_owner_review_enabled() && directorist_is_current_user_listing_author( $listing_id ) );
}

/**
 * Check if guest can review.
 *
 * @since 7.1.1
 *
 * @return bool
 */
function directorist_can_guest_review() {
	return ( directorist_is_guest_review_enabled() && ! is_user_logged_in() );
}

/**
 * Check if logged in or current user can review.
 *
 * @since 7.1.1
 * @param int $listing_id
 *
 * @return bool
 */
function directorist_can_current_user_review( $listing_id = null ) {
	// Return early if not logged in.
	if ( ! is_user_logged_in() ) {
		return false;
	}

	// Set listing id to current post id.
	if ( is_null( $listing_id ) ) {
		$listing_id = get_the_ID();
	}

	// Return false for different post type.
	if ( get_post_type( $listing_id ) !== ATBDP_POST_TYPE ) {
		return false;
	}

	// Owner cannot review so return.
	if ( directorist_is_current_user_listing_author( $listing_id ) && ! directorist_is_owner_review_enabled() ) {
		return false;
	}

	// Current user already reviewed so return.
	if ( directorist_user_review_exists( wp_get_current_user()->user_email, $listing_id ) ) {
		return false;
	}

	return true;
}

function directorist_is_review_gdpr_consent_enabled() {
	return (bool) get_directorist_option( 'enable_gdpr_consent', false );
}

function directorist_get_review_gdpr_consent_label() {
	$label = get_directorist_option( 'gdpr_consent_label', '' );

	return preg_replace(
		array(
			'/\[privacy_policy\]/',
			'/\[\/privacy_policy\]/',
			'/\[terms_conditions\]/',
			'/\[\/terms_conditions\]/',
		),
		array(
			'<a target="_blank" href="' . esc_url( ATBDP_Permalink::get_privacy_policy_page_url() ) . '">',
			'</a>',
			'<a target="_blank" href="' . esc_url( ATBDP_Permalink::get_terms_and_conditions_page_url() ) . '">',
			'</a>'
		),
		$label
	);
}
