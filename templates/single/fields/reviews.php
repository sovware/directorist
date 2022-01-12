<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Return early when review is disabled.
if ( ! directorist_is_review_enabled() ) {
	return;
}

$count       = $listing->get_review_count();
$review_text = sprintf( _nx( '%s Review', '%s Reviews', $count, 'Review count single template', 'directorist' ), $count );
?>

<span class="directorist-info-item directorist-review-meta directorist-info-item-review"><?php echo esc_html( $review_text ); ?></span>
