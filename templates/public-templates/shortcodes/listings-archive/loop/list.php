<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */

$featured_class = $listings->loop['featured'] ? 'directorist-featured-listings' : '';
?>
<div class="atbd_single_listing atbd_listing_list">
   <article class="atbd_single_listing_wrapper <?php echo esc_attr( $featured_class ); ?>">

        <?php if ( $listings->display_preview_image ): ?>

            <figure class="atbd_listing_thumbnail_area">
                <?php
                $listings->loop_thumb_card_template();

                /**
                 * @since 5.0
                 *
                 * @hooked Directorist_Listings::featured_badge_list_view - 10
                 * @hooked Directorist_Listings::populer_badge_list_view - 15
                 * @hooked Directorist_Listings::new_badge_list_view - 20
                 */
                ?>
                <span class="atbd_lower_badge"><?php echo apply_filters('atbdp_list_lower_badges', '');?></span>
            </figure>

        <?php endif; ?>

        <div class="atbd_listing_info">
            <?php 
            $listings->loop_top_content_template();
            $listings->loop_list_bottom_content_template();
            ?>
        </div>
        
    </article>
</div>