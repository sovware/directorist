<?php
/**
 * @author  wpWax
 * @since   7.7.0
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Return early when review is disabled.
if ( ! directorist_is_review_enabled() ) {
	return;
}
?>
<span class="directorist-info-item directorist-rating-meta directorist-rating-transparent">
    <?php echo wp_kses_post( $review_stars ); ?>
    <span class="directorist-rating-avg"><?php echo esc_html( $average_reviews ); ?></span>
    <span class="directorist-total-review">(<?php echo esc_html( $total_reviews ) ?>)</span>
</span>
