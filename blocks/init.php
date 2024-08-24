<?php
/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
define( 'DIRECTORIST_BLOCK_TEMPLATE_PATH', __DIR__ . '/templates' );

function directorist_account_block() {
	register_block_type( __DIR__ . '/build/account-button' );
	register_block_type( __DIR__ . '/build/search-modal' );
}
add_action( 'init', 'directorist_account_block' );

function directorist_account_block_avatar_image( $size = 40 ) {
	$image_id  = (int) get_user_meta( get_current_user_id(), 'pro_pic', true );
	$image_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );

	if ( empty( $image_url ) ) {
		echo get_avatar(
			get_current_user_id(),
			$size,
			null,
			null,
			[
				'class' => 'rounded-circle'
			]
		);
	} else {
		echo sprintf(
			'<img width="%1$s" src="%2$s" alt="%2$s" class="avatar rounded-circle"/>',
			$size,
			esc_url( $image_url ),
			get_the_author_meta( 'display_name', get_current_user_id() )
		);
	}
}
