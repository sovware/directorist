<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.0.6.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! directorist_is_review_enabled() ) {
	return;
}

// TODO: Find a better place to change comments per page setting.
add_filter( 'option_comments_per_page', 'directorist_get_review_per_page' );
set_query_var( 'comments_per_page', directorist_get_review_per_page() );

comments_template();

// TODO: Find a better place to change comments per page setting.
remove_filter( 'option_comments_per_page', 'directorist_get_review_per_page' );
