<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>

<div class="atbd_listing_meta">
    <span class="atbd_meta atbd_listing_rating atbd_listing_transparent">
        <?php echo $listings->loop['review']['review_stars']; ?>
        
        <span class="atbd_listing_avg">
            <?php echo $listings->loop['review']['average_reviews']; ?>
        </span>
    </span>
</div>