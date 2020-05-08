<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="atbd_tab_inner tabContentActive" id="my_listings">
    <div class="row data-uk-masonry">
        <?php if ($listings->have_posts()) {
            foreach ($listings->posts as $post) {
                // get only one parent or high level term object
                $top_category = ATBDP()->taxonomy->get_one_high_level_term($post->ID, ATBDP_CATEGORY);
                $price = get_post_meta($post->ID, '_price', true);
                $featured = get_post_meta($post->ID, '_featured', true);
                $listing_img = get_post_meta($post->ID, '_listing_img', true);
                $listing_prv_img = get_post_meta($post->ID, '_listing_prv_img', true);
                $tagline = get_post_meta($post->ID, '_tagline', true);
                $crop_width = get_directorist_option('crop_width', 360);
                $crop_height = get_directorist_option('crop_height', 300);
                if (!empty($listing_prv_img)) {
                    $prv_image = atbdp_get_image_source($listing_prv_img, 'large');
                    $prv_image_full = atbdp_get_image_source($listing_prv_img, 'full');
                }
                if (!empty($listing_img[0])) {
                    $gallery_img = atbdp_get_image_source($listing_img[0], 'medium');
                    $gallery_img_full = atbdp_get_image_source($listing_img[0], 'full');
                }
                $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');

                ?>

                <div class="col-lg-4 col-sm-6" id="listing_id_<?php echo $post->ID; ?>">
                    <div class="atbd_single_listing atbd_listing_card">
                        <article
                                class="atbd_single_listing_wrapper <?php echo ($featured) ? 'directorist-featured-listings' : ''; ?>">
                            <figure class="atbd_listing_thumbnail_area">
                                <div class="atbd_listing_image">
                                    <a href="<?php echo esc_url(get_post_permalink($post->ID)); ?>"><?php
                                        $has_thumbnail = false;
                                        $thumbnail_img = '';

                                        if (!empty($listing_img[0]) && empty($listing_prv_img)) {
                                            $thumbnail_img = $gallery_img_full;
                                            $has_thumbnail = true;
                                        }
                                        if (empty($listing_img[0]) && empty($listing_prv_img) && !empty($default_image)) {
                                            $thumbnail_img = $default_image;
                                            $has_thumbnail = true;
                                        }
                                        if (!empty($listing_prv_img)) {
                                            $thumbnail_img = $prv_image_full;
                                            $has_thumbnail = true;
                                        }

                                        if ($has_thumbnail) {
                                            the_thumbnail_card($thumbnail_img);
                                            // echo '<img src="' . $thumbnail_img . '" alt="' . esc_html(stripslashes(get_the_title())) . '">';
                                        }
                                        ?>
                                    </a>
                                </div>

                                <figcaption class="atbd_thumbnail_overlay_content">

                                    <div class="atbd_upper_badge">
                                        <?php
                                        $featured_text = get_directorist_option('feature_badge_text', esc_html__('Featured', 'directorist'));
                                        if ($featured) {
                                            printf(
                                                '<span class="atbd_badge atbd_badge_featured">%s</span>', __($featured_text, 'directorist')

                                            );
                                        }
                                        ?>
                                    </div>
                                    <?php
                                    do_action('atbdp_after_user_dashboard_upper_badge', $post->ID);
                                    ?>
                                </figcaption>
                            </figure>

                            <div class="atbd_listing_info">
                                <div class="atbd_content_upper">
                                    <div class="atbd_dashboard_title_metas">
                                        <h4 class="atbd_listing_title">
                                            <a href="<?php echo get_post_permalink($post->ID); ?>">
                                                <?php echo !empty($post->post_title) ? esc_html(stripslashes($post->post_title)) : __('Untitled!', 'directorist'); ?>
                                            </a>
                                        </h4>
                                            <?php
                                            /**
                                             * Fires after the title and sub title of the listing is rendered
                                             *
                                             *
                                             * @since 1.0.0
                                             */
                                            do_action('atbdp_after_listing_tagline');
                                            ?>
                                    </div>

                                    <?php
                                    $exp_date = get_post_meta($post->ID, '_expiry_date', true);
                                    $never_exp = get_post_meta($post->ID, '_never_expire', true);
                                    $lstatus = get_post_meta($post->ID, '_listing_status', true);
                                    ?>
                                </div><!-- end ./atbd_content_upper -->

                                <div class="atbd_listing_bottom_content">
                                    <div class="listing-meta">
                                        <div class="listing-meta-content">
                                        <?php
                                        /**
                                         * @since 5.0.3
                                         */
                                        do_action('atbdp_user_dashboard_listings_before_expiration', $post->ID);
                                        $status = get_post_status_object($post->post_status)->label;
                                        $exp_text = !empty($never_exp)
                                            ? __('Never Expires', 'directorist')
                                            : date_i18n($date_format, strtotime($exp_date)); ?>
                                        <p><?php printf(__('<span>Expiration:</span> %s', 'directorist'), ('expired' == $lstatus) ? '<span style="color: red">' . __('Expired', 'directorist') . '</span>' : $exp_text); ?></p>
                                        <p><?php printf(__('<span class="%s">%s</span> ', 'directorist'),'atbdp__'.strtolower($status), $status ); ?></p></div>
                                        <?php
                                        /**
                                         * Fires before the action buttons are rendered
                                         *
                                         *
                                         * @since 6.3.3
                                         */
                                        do_action('atbdp_user_dashboard_before_button', $post->ID);
                                        ?>

                                        <div class="db_btn_area">
                                            <?php
                                            // If the listing needs renewal then there is no need to show promote button
                                            if ('renewal' == $lstatus || 'expired' == $lstatus) {

                                                $can_renew = get_directorist_option('can_renew_listing');
                                                if (!$can_renew) return false;// vail if renewal option is turned off on the site.
                                                if (is_fee_manager_active()) {
                                                    $modal_id = apply_filters('atbdp_pricing_plan_change_modal_id', 'atpp-plan-change-modal', $post->ID);
                                                    ?>
                                                    <a href=""
                                                       data-target="<?php echo $modal_id; ?>"
                                                       data-listing_id="<?php echo $post->ID; ?>"
                                                       class="directory_btn btn btn-outline-success atbdp_renew_with_plan">
                                                        <?php _e('Renew', 'directorist'); ?>
                                                    </a>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <a href="<?php echo add_query_arg( 'renew_from', 'dashboard', esc_url(ATBDP_Permalink::get_renewal_page_link($post->ID)) ) ?>"
                                                       id="directorist-renew"
                                                       data-listing_id="<?php echo $post->ID; ?>"
                                                       class="directory_btn btn btn-outline-success">
                                                        <?php _e('Renew', 'directorist'); ?>
                                                    </a>
                                                    <!--@todo; add expiration and renew date-->
                                                <?php }
                                            } else {
                                                // show promotions if the featured is available
                                                // featured available but the listing is not featured, show promotion button
                                                if ($featured_active && empty($featured) && !is_fee_manager_active()) {
                                                    ?>
                                                    <div class="atbd_promote_btn_wrapper">
                                                        <a href="<?php echo esc_url(ATBDP_Permalink::get_checkout_page_link($post->ID)) ?>"
                                                           id="directorist-promote"
                                                           data-listing_id="<?php echo $post->ID; ?>"
                                                           class="directory_btn btn btn-primary">
                                                            <?php _e('Promote', 'directorist'); ?>
                                                        </a>
                                                    </div>
                                                <?php }
                                            } ?>

                                            <div><a href="<?php echo esc_url(ATBDP_Permalink::get_edit_listing_page_link($post->ID)); ?>"
                                               class="directory_edit_btn edit_listing"><?php _e('Edit', 'directorist'); ?></a>
                                            <a href="#"
                                               data-listing_id="<?php echo $post->ID; ?>"
                                               class="directory_remove_btn remove_listing"><?php _e('Delete', 'directorist'); ?></a></div>
                                        </div> <!--ends .db_btn_area-->
                                    </div>
                                </div><!-- end ./atbd_listing_bottom_content -->
                            </div>
                        </article>
                    </div>
                </div> <!--ends . col-lg-3 col-sm-6-->
                <?php
            }

        } else {
            echo '<p class="atbdp_nlf">' . __("Looks like you have not created any listing yet!", 'directorist') . '</p>';
        }
        $pagination = get_directorist_option('user_listings_pagination', 1);
        $paged = atbdp_get_paged_num();
        if (!empty($pagination)) {
            ?>
            <div class="col-md-12">
                <?php
                $paged = !empty($paged) ? $paged : '';
                echo atbdp_pagination($listings, $paged);
                ?>
            </div>
        <?php } ?>
    </div>
</div> <!--ends #my_listings-->