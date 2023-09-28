<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Return early when review is disabled.
if ( ! directorist_is_review_enabled() ) {
	return;
}

$count       = $listing->get_review_count();
$review_text = sprintf( _nx( '(%s Review)', '(%s Reviews)', $count, 'Review count single template', 'directorist' ), $count );
?>

<span class="directorist-info-item directorist-rating-meta directorist-info-item-rating">

	<?php directorist_icon( 'las la-star' ); ?><?php echo esc_html( $listing->get_rating_count() );?>
	
	<span class="directorist-review"><?php echo esc_html( $review_text ); ?></span>

</span>
