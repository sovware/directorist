<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$reviews_count = $listing->get_review_count();
$review_text = (($reviews_count > 1) || ($reviews_count === 0)) ? __('Reviews', 'directorist') : __('Review', 'directorist');
?>

<div class="directorist-info-item directorist-review-meta directorist-info-item-review"><?php printf( '%s %s', $reviews_count, $review_text ); ?></div>