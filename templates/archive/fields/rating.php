<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

if ( !Helper::is_review_enabled() ) {
	return;
}
?>

<span class="directorist-info-item directorist-rating-meta directorist-rating-transparent">
    <?php echo $listings->loop['review']['review_stars']; ?>
    
    <span class="directorist-rating-avg">
        <?php echo $listings->loop['review']['average_reviews']; ?>
    </span>
</span>