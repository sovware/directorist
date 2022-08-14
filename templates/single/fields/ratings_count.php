<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Return early when review is disabled.
if ( ! directorist_is_review_enabled() ) {
	return;
}
?>

<span class="directorist-info-item directorist-rating-meta directorist-info-item-rating"><?php echo esc_html( $listing->get_rating_count() );?><?php directorist_icon( 'las la-star' ); ?></span>
