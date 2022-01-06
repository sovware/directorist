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

comments_template();
