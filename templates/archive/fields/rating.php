<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<span class="directorist-info-item directorist-rating-meta directorist-rating-transparent">
    <?php echo $listings->loop['review']['review_stars']; ?>
    
    <span class="directorist-rating-avg">
        <?php echo $listings->loop['review']['average_reviews']; ?>
    </span>
</span>