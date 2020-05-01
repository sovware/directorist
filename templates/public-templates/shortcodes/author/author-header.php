<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="row">
    <div class="col-md-12">
        <div class="atbd_auhor_profile_area">
            <div class="atbd_author_avatar">
                <?php if (empty($u_pro_pic)) {
                    echo $avatar_img;
                }
                if (!empty($u_pro_pic)) { ?><img
                    src="<?php echo esc_url($u_pro_pic[0]); ?>"
                    alt="Author Image" ><?php } ?>
                <div class="atbd_auth_nd">
                    <h2><?php echo esc_html($author_name); ?></h2>
                    <p><?php
                        printf(__('Member since %s ago', 'directorist'), human_time_diff(strtotime($user_registered), current_time('timestamp'))); ?></p>
                </div>
            </div>

            <div class="atbd_author_meta">
                <?php
                if ($enable_review) {
                    $author_rating       = $author->get_rating();
                    $author_review_count = $author->get_review_count();
                    $total_listing       = $author->get_total_listing_number();
                    ?>
                    <div class="atbd_listing_meta">
                    <span class="atbd_meta atbd_listing_rating">
                        <?php echo $author_rating; ?><i class="<?php atbdp_icon_type(true); ?>-star"></i>
                    </span>
                    </div>
                    <p class="meta-info">
                        <span><?php echo !empty($author_review_count) ? $author_review_count : '0' ?></span>
                        <?php echo (($author_review_count > 1) || ($author_review_count === 0)) ? __('Reviews', 'directorist') : __('Review', 'directorist') ?>
                    </p>
                    <?php
                }
                ?>
                <p class="meta-info">
                    <span><?php echo !empty($total_listing) ? $total_listing : '0' ?></span>
                    <?php echo (($total_listing > 1) || ($total_listing === 0)) ? __('Listings', 'directorist') : __('Listing', 'directorist') ?>
                </p>
            </div>
        </div>
    </div>
</div>