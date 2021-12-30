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

function directorist_get_listing_rating( $listing_id, $round_precision = 1 ) {
	return \Directorist\Review\Listing_Review_Meta::get_rating( $listing_id, $round_precision );
}

function directorist_get_listing_review_count( $listing_id ) {
	return \Directorist\Review\Listing_Review_Meta::get_review_count( $listing_id );
}

function directorist_get_rating_field_meta_key() {
	return \Directorist\Review\Listing_Review_Meta::FIELD_AVG_RATING;
}

function directorist_get_comment_edit_link( $args = array(), $comment = null, $post = null ) {
    $defaults = array(
        'add_below'    => 'comment',
        'respond_id'   => 'respond',
        'edit_text'    => __( 'Edit', 'directorist' ),
        'edit_of_text' => __( 'Edit %s', 'directorist' ),
        'login_text'   => __( 'Log in to Edit', 'directorist' ),
		'cancel_text'  => __( 'Cancel edit', 'directorist' ),
        'max_depth'    => 0,
        'depth'        => 0,
        'before'       => '',
        'after'        => '',
    );

    $args = wp_parse_args( $args, $defaults );

    if ( 0 == $args['depth'] || $args['max_depth'] <= $args['depth'] ) {
        return;
    }

    $comment = get_comment( $comment );

    if ( empty( $comment ) ) {
        return;
    }

    if ( empty( $post ) ) {
        $post = $comment->comment_post_ID;
    }

    $post = get_post( $post );

    if ( ! comments_open( $post->ID ) ) {
        return false;
    }

    if ( get_option( 'page_comments' ) ) {
        $permalink = str_replace( '#comment-' . $comment->comment_ID, '', get_comment_link( $comment ) );
    } else {
        $permalink = get_permalink( $post->ID );
    }

	$comment_type = ( $comment->comment_type === 'review' ? __( 'review', 'directorist' ) : __( 'comment', 'directorist' ) );
	$data_attributes = array(
		'commentid'      => $comment->comment_ID,
		'postid'         => $post->ID,
		'belowelement'   => $args['add_below'] . '-' . $comment->comment_ID,
		'respondelement' => $args['respond_id'],
		'editof'         => sprintf( $args['edit_of_text'], $comment_type ),
	);

	$data_attribute_string = '';

	foreach ( $data_attributes as $name => $value ) {
		$data_attribute_string .= " data-${name}=\"" . esc_attr( $value ) . '"';
	}

	$data_attribute_string = trim( $data_attribute_string );

	$link = sprintf(
		"<a rel='nofollow' class='directorist-comment-edit-link' href='%s' %s aria-label='%s'>%s</a>",
		esc_url(
			add_query_arg(
				array(
					'editofcom'      => $comment->comment_ID,
				),
				$permalink
			)
		) . '#' . $args['respond_id'],
		$data_attribute_string,
		esc_attr( sprintf( $args['edit_of_text'], $comment_type ) ),
		$args['edit_text']
	);

    return apply_filters( 'directorist_comment_edit_link', $args['before'] . $link . $args['after'], $args, $comment, $post );
}
