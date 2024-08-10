<?php
defined( 'ABSPATH' ) || exit;

defined( 'DIRECTORIST_ACCOUNT_BLOCK_TEMPLATE_PATH' ) || define( 'DIRECTORIST_ACCOUNT_BLOCK_TEMPLATE_PATH', __DIR__ . '/templates' );

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function directorist_account_block() {
	register_block_type( __DIR__ . '/build' );
}

add_action( 'init', 'directorist_account_block' );

function directorist_account_block_avatar_image( $size = 40 ) {
	$gravatar     = get_avatar( get_current_user_id(), $size, null, null, ['class' => 'rounded-circle'] );
	$author_id    = get_user_meta( get_current_user_id(), 'pro_pic', true );
	$author_image = wp_get_attachment_image_src( $author_id );

	if ( empty( $author_image ) ) {
		echo wp_kses_post( $gravatar );
	} else {
		echo sprintf(
			'<img width="%s" src="%s" alt="%s" class="avatar rounded-circle"/>',
			$size,
			esc_url( $author_image[0] ),
			get_the_author_meta( 'display_name', get_current_user_id() )
		);
	}
}