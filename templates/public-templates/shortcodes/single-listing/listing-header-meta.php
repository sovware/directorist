<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="atbd_data_info">
    <?php if ( !empty($enable_review) || (empty($is_disable_price) && (!empty($price) || !empty($price_range))) ): ?>
        <div class="atbd_listing_meta">
            <?php if ( empty($is_disable_price) && !empty($display_pricing_field) ): ?>
                <?php
                    if (!empty($price_range) && ('range' === $atbd_listing_pricing) && $plan_average_price) {
                        echo atbdp_display_price_range($price_range);
                    }
                    elseif($plan_price) {
                        echo atbdp_display_price($price, $is_disable_price, $currency = null, $symbol = null, $c_position = null, $echo = false);
                    }
                ?>
            <?php endif; ?>

            <?php do_action('atbdp_after_listing_price'); ?>

            <?php if (!empty($enable_review)): ?>
                <span class="atbd_meta atbd_listing_rating"><?php echo ATBDP()->review->get_average($listing_id);?><i class="<?php atbdp_icon_type(true); ?>-star"></i></span>
            <?php endif; ?>
        </div>

        <?php if ($enable_review): ?>
            <div class="atbd_rating_count"><p><?php echo $review_count_html; ?></p></div>
        <?php endif; ?>

        <?php if (!empty($enable_new_listing) || !empty($display_feature_badge_single) || !empty($display_popular_badge_single)): ?>
            <div class="atbd_badges">
                <?php echo new_badge();?>

                <?php if ($featured && !empty($display_feature_badge_single)): ?>
                    <span class="atbd_badge atbd_badge_featured"><?php echo $feature_badge_text; ?></span>
                <?php endif; ?>

                <?php if (atbdp_popular_listings($listing_id) === $listing_id): ?>
                    <span class="atbd_badge atbd_badge_popular"><?php echo $popular_badge_text; ?></span>
                <?php endif; ?>
                
            </div>
        <?php endif; ?>

        <div class="atbd_listing_category">
            <ul class="directory_cats">
                <?php if ( !empty($cat_list) ): ?>
                    <li><span class="<?php atbdp_icon_type(true);?>-tags"></span></li>
                    <li><p class="directory_tag"><span><?php echo $cat_list;?></span></p></li>
                <?php endif; ?>
            </ul>
        </div>

    <?php endif; ?>
</div>