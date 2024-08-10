<?php
defined( 'ABSPATH' ) || exit;

defined( 'DIRECTORIST_SEARCH_POPUP_BLOCK_TEMPLATE_PATH' ) || define( 'DIRECTORIST_SEARCH_POPUP_BLOCK_TEMPLATE_PATH', __DIR__ . '/templates' );

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function directorist_search_popup_block() {
	register_block_type( __DIR__ . '/build' );
}

add_action( 'init', 'directorist_search_popup_block' );