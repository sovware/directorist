<?php

/**
 * @param WP_Query $all_listings It contains all the queried listings by a user
 * @since 5.5.1
 * @package Directorist
 */
do_action('atbdp_before_all_listings_grid', $all_listings);
?>

<div id="directorist" class="atbd_wrapper ads-advaced--wrapper">
    <?php atbdp_listings_header( $atts ); ?>
    
    <div class="<?php echo !empty($grid_container_fluid) ? $grid_container_fluid : ''; ?>">
        <?php
        /**
         * @since 5.0
         * It fires before the listings columns
         * It only fires if the parameter [directorist_all_listing action_before_after_loop="yes"]
         */
        $action_before_after_loop = !empty($action_before_after_loop) ? $action_before_after_loop : '';
        if ('yes' === $action_before_after_loop) {
            do_action('atbdp_before_grid_listings_loop');
        }
        ?>
        <div class="row" <?php echo ($view_as !== 'masonry_grid') ? '' : 'data-uk-grid'; ?>>
            <?php
            if ($all_listings->have_posts()) {
                while ($all_listings->have_posts()) {
                    $all_listings->the_post();
                    $listing_id = get_the_ID();
                    $cats = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
                    $locs = get_the_terms(get_the_ID(), ATBDP_LOCATION);
                    $featured = get_post_meta(get_the_ID(), '_featured', true);
                    $price = get_post_meta(get_the_ID(), '_price', true);
                    $price_range = get_post_meta(get_the_ID(), '_price_range', true);
                    $atbd_listing_pricing = get_post_meta(get_the_ID(), '_atbd_listing_pricing', true);
                    $listing_img = get_post_meta(get_the_ID(), '_listing_img', true);
                    $listing_prv_img = get_post_meta(get_the_ID(), '_listing_prv_img', true);
                    $excerpt = get_post_meta(get_the_ID(), '_excerpt', true);
                    $tagline = get_post_meta(get_the_ID(), '_tagline', true);
                    $address = get_post_meta(get_the_ID(), '_address', true);
                    $email = get_post_meta(get_the_ID(), '_email', true);
                    $web = get_post_meta(get_the_ID(), '_website', true);
                    $phone_number = get_post_meta(get_the_Id(), '_phone', true);
                    $category = get_post_meta(get_the_Id(), '_admin_category_select', true);
                    $post_view = get_post_meta(get_the_Id(), '_atbdp_post_views_count', true);
                    $hide_contact_info = get_post_meta(get_the_ID(), '_hide_contact_info', true);
                    $disable_contact_info = get_directorist_option('disable_contact_info', 0);
                    $display_title = get_directorist_option('display_title', 1);
                    $display_review = get_directorist_option('enable_review', 1);
                    $display_price = get_directorist_option('display_price', 1);
                    $display_email = get_directorist_option('display_email', 0);
                    $display_web_link = get_directorist_option('display_web_link', 0);
                    $display_category = get_directorist_option('display_category', 1);
                    $display_view_count = get_directorist_option('display_view_count', 1);
                    $display_mark_as_fav = get_directorist_option('display_mark_as_fav', 1);
                    $display_publish_date = get_directorist_option('display_publish_date', 1);
                    $display_contact_info = get_directorist_option('display_contact_info', 1);
                    $display_feature_badge_cart = get_directorist_option('display_feature_badge_cart', 1);
                    $display_popular_badge_cart = get_directorist_option('display_popular_badge_cart', 1);
                    $popular_badge_text = get_directorist_option('popular_badge_text', 'Popular');
                    $feature_badge_text = get_directorist_option('feature_badge_text', 'Featured');
                    $enable_tagline = get_directorist_option('enable_tagline');
                    $enable_excerpt = get_directorist_option('enable_excerpt');
                    $address_location = get_directorist_option('address_location', 'location');
                    /*Code for Business Hour Extensions*/
                    $bdbh = get_post_meta(get_the_ID(), '_bdbh', true);
                    $enable247hour = get_post_meta(get_the_ID(), '_enable247hour', true);
                    $disable_bz_hour_listing = get_post_meta(get_the_ID(), '_disable_bz_hour_listing', true);
                    $business_hours = !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array(); // arrays of days and times if exist
                    $author_id = get_the_author_meta('ID');
                    $display_author_image = get_directorist_option('display_author_image', 1);
                    $u_pro_pic = get_user_meta($author_id, 'pro_pic', true);
                    $u_pro_pic = !empty($u_pro_pic) ? wp_get_attachment_image_src($u_pro_pic, 'thumbnail') : '';
                    $avatar_img = get_avatar($author_id, apply_filters('atbdp_avatar_size', 32));
                    $display_tagline_field = get_directorist_option('display_tagline_field', 0);
                    $display_pricing_field = get_directorist_option('display_pricing_field', 1);
                    $display_excerpt_field = get_directorist_option('display_excerpt_field', 0);
                    $display_address_field = get_directorist_option('display_address_field', 1);
                    $display_phone_field = get_directorist_option('display_phone_field', 1);
                    $display_image = !empty($display_image) ? $display_image : '';
                    $listing_preview_img = empty(get_directorist_option('display_preview_image', 1)) || 'no' == $display_image ? 'no' : 'yes';
                    $prv_image = '';
                    if (!empty($listing_prv_img)) {
                        $prv_image_full = atbdp_get_image_source($listing_prv_img, 'full');
                    }
                    if (!empty($listing_img[0])) {
                        $gallery_img_full = atbdp_get_image_source($listing_img[0], 'full');
                    }
                    $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
                    $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
                    
                    $listing_preview_img_class = 'no' == $listing_preview_img || (empty($prv_image) && empty($default_image) && empty($gallery_img)) ? ' listing_preview_img_none' : '';
                    /*Code for Business Hour Extensions*/ 

                    // directorist_get_loop_item( __FILE__ , 'grid');

                    $grid_path = dirname( __FILE__ ) . '/loop/grid.php';
                    if ( file_exists( $grid_path ) ) { include $grid_path; }
                    
                }
                wp_reset_postdata();
            } else { ?>
                <p class="atbdp_nlf"><?php _e('No listing found.', 'directorist'); ?></p>
            <?php }
            ?>

        </div>
        <!--end row-->

        <div class="row">
            <div class="col-lg-12">
                <?php
                /**
                 * @since 5.0
                 */
                do_action('atbdp_before_listings_pagination');
                $show_pagination = !empty($show_pagination) ? $show_pagination : '';
                if ('yes' == $show_pagination) {
                    $paged = !empty($paged) ? $paged : '';
                    echo atbdp_pagination($all_listings, $paged);
                } ?>
            </div>
        </div>

        <?php
        /**
         * @since 5.0
         * to add custom html
         * It only fires if the parameter [directorist_all_listing action_before_after_loop="yes"]
         */
        $action_before_after_loop = !empty($action_before_after_loop) ? $action_before_after_loop : '';
        if ('yes' === $action_before_after_loop) {
            do_action('atbdp_after_grid_listings_loop');
        }
        ?>
    </div>
</div>
<style>
    .atbd_content_active #directorist.atbd_wrapper .atbdp_column {
        width: <?php echo $column_width; ?>;
    }
</style>