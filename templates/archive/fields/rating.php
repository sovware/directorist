<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;

if ( !Helper::is_review_enabled() ) {
	return;
}
?>

<span class="directorist-info-item directorist-rating-meta directorist-rating-transparent">

    <?php echo wp_kses_post( $listings->loop_review_star_html() ); ?>
    
    <span class="directorist-rating-avg">
        <?php echo esc_html( number_format( $listings->loop_rating_average(), 1 ) ); ?>
    </span>

</span>