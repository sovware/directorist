<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! directorist_is_review_enabled() || ! comments_open() ) {
	return;
}

comments_template();
