<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */
?>

<span class="directorist-info-item directorist-rating-meta directorist-rating-transparent">
    <?php echo $listings->loop['review']['review_stars']; ?>
    
    <span class="directorist-rating-avg">
        <?php echo $listings->loop['review']['average_reviews']; ?>
    </span>
</span>