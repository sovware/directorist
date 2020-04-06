<?php
$listings = ATBDP()->user->current_user_listings();
$fav_listings = ATBDP()->user->current_user_fav_listings();
$uid = get_current_user_id();
$c_user = get_userdata($uid);
$u_website = $c_user->user_url;
$avatar = get_user_meta($uid, 'avatar', true);
$u_phone = get_user_meta($uid, 'atbdp_phone', true);
$u_pro_pic_id = get_user_meta($uid, 'pro_pic', true);
$u_pro_pic = !empty($u_pro_pic_id) ? wp_get_attachment_image_src($u_pro_pic_id, 'directory-large') : '';
$facebook = get_user_meta($uid, 'atbdp_facebook', true);
$twitter = get_user_meta($uid, 'atbdp_twitter', true);
$linkedIn = get_user_meta($uid, 'atbdp_linkedin', true);
$youtube = get_user_meta($uid, 'atbdp_youtube', true);
$bio = get_user_meta($uid, 'description', true);
$u_address = get_user_meta($uid, 'address', true);
$date_format = get_option('date_format');
$featured_active = get_directorist_option('enable_featured_listing');
$is_disable_price = get_directorist_option('disable_list_price');
$my_listing_tab = get_directorist_option('my_listing_tab', 1);
$my_listing_tab_text = get_directorist_option('my_listing_tab_text', __('My Listing', 'directorist'));
$my_profile_tab = get_directorist_option('my_profile_tab', 1);
$my_profile_tab_text = get_directorist_option('my_profile_tab_text', __('My Profile', 'directorist'));
$fav_listings_tab = get_directorist_option('fav_listings_tab', 1);
$fav_listings_tab_text = get_directorist_option('fav_listings_tab_text', __('Favorite Listings', 'directorist'));
$submit_listing_button = get_directorist_option('submit_listing_button', 1);
$show_title = !empty($show_title) ? $show_title : '';
$container_fluid = is_directoria_active() ? 'container' : 'container-fluid';
/*@todo; later show featured listing first on the user dashboard maybe??? */
?>

<div id="directorist" class="directorist atbd_wrapper dashboard_area">
    <div class="<?php echo apply_filters('atbdp_deshboard_container_fluid', $container_fluid) ?>">
        <div class="row">
            <div class="col-md-12">
                <?php
                if ('yes' === $show_title) {
                    ?>
                    <div class="atbd_add_listing_title">
                        <h2><?php _e('My Dashboard', 'directorist'); ?></h2>
                    </div> <!--ends add_listing_title-->
                    <?php
                }
                ?>
                <div class="atbd_dashboard_wrapper atbd_tab">
                    <div class="atbd_user_dashboard_nav atbd_tab_nav">
                        <!-- Nav tabs -->
                        <div class="atbdp_tab_nav_wrapper">
                            <ul class="atbdp_tab_nav--content">
                                <?php if (!empty($my_listing_tab)) { ?>
                                    <li class="atbdp_tab_nav--content-link">
                                        <a href="" target="my_listings" class="atbd_tn_link tabItemActive">
                                            <?php $list_found = ($listings->found_posts > 0) ? $listings->found_posts : '0';
                                            printf(__('%s (%s)', 'directorist'), $my_listing_tab_text, $list_found); ?>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php
                                /**
                                 * @since 5.10.0
                                 */
                                do_action('atbdp_tab_after_my_listings');
                                ?>
                                <?php if (!empty($my_profile_tab)) { ?>
                                    <li class="atbdp_tab_nav--content-link"><a href="" class="atbd_tn_link"
                                           target="profile"><?php _e($my_profile_tab_text, 'directorist'); ?></a>
                                    </li>
                                <?php } ?>
                                <?php if (!empty($fav_listings_tab)) { ?>
                                    <li class="atbdp_tab_nav--content-link"><a href="" class="atbd_tn_link"
                                           target="saved_items"><?php _e($fav_listings_tab_text, 'directorist'); ?></a>
                                    </li>
                                <?php } ?>
                                <?php
                                do_action('atbdp_tab_after_favorite_listings');
                                ?>
                                <li class="atbdp_tab_nav--content-link atbdp-tab-nav-last">
                                    <a href="#" class="atbdp-tab-nav-link"><span class="fa fa-ellipsis-h"></span></a>
                                </li>
                            </ul>
                        </div>

                        <div class="nav_button">
                            <?php if (!empty($submit_listing_button)) { ?>
                                <a href="<?php echo esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>"
                                   class="<?php echo atbdp_directorist_button_classes(); ?>"><?php _e('Submit Listing', 'directorist'); ?></a>
                            <?php } ?>
                            <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>"
                               class="<?php echo atbdp_directorist_button_classes('secondary'); ?>"><?php _e('Log Out', 'directorist'); ?></a>
                        </div>
                    </div> <!--ends dashboard_nav-->

                    <!-- Tab panes -->
                    <div class="atbd_tab-content">
                        <?php if (!empty($my_listing_tab)) { ?>
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
                                                $prv_image = wp_get_attachment_image_src($listing_prv_img, 'large');
                                                $prv_image = is_array($prv_image) ? $prv_image[0] : '';
                                                $prv_image_full = wp_get_attachment_image_src($listing_prv_img, 'full');
                                                $prv_image_full = is_array($prv_image_full) ? $prv_image_full[0] : '';
                                            }
                                            if (!empty($listing_img[0])) {
                                                $gallery_img = wp_get_attachment_image_src($listing_img[0], 'medium')[0];
                                                $gallery_img_full = wp_get_attachment_image_src($listing_img[0], 'full')[0];
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
                                                                                <a href="<?php echo esc_url(ATBDP_Permalink::get_renewal_page_link($post->ID)) ?>"
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
                                                                           id="edit_listing"
                                                                           class="directory_edit_btn"><?php _e('Edit', 'directorist'); ?></a>
                                                                        <a href="#" id="remove_listing"
                                                                           data-listing_id="<?php echo $post->ID; ?>"
                                                                           class="directory_remove_btn"><?php _e('Delete', 'directorist'); ?></a></div>
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
                        <?php } ?>
                        <?php
                        /**
                         * @package Directorist
                         * @since 5.5.2
                         */
                        do_action('atbdp_after_loop_dashboard_listings', $listings);
                        ?>
                        <?php if (!empty($my_profile_tab)) { ?>
                            <div class="atbd_tab_inner" id="profile">
                                <form action="#" id="user_profile_form" method="post">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 offset-sm-3 offset-md-0">
                                            <div class="user_pro_img_area">
                                                <div class="user_img" id="profile_pic_container">
                                                    <div class="cross" id="remove_pro_pic"><span
                                                                class="fa fa-times"></span>
                                                    </div>
                                                    <div class="choose_btn">
                                                        <input type="hidden" name="user[pro_pic]" id="pro_pic"
                                                               value="<?php echo !empty($u_pro_pic_id) ? esc_attr($u_pro_pic_id) : ''; ?>">
                                                        <label for="pro_pic"
                                                               id="upload_pro_pic"><?php _e('Change', 'directorist'); ?></label>
                                                    </div> <!--ends .choose_btn-->
                                                    <img src="<?php echo !empty($u_pro_pic) ? esc_url($u_pro_pic[0]) : esc_url(ATBDP_PUBLIC_ASSETS . 'images/no-image.jpg'); ?>"
                                                         id="pro_img" alt="">

                                                </div> <!--ends .user_img-->
                                            </div> <!--ends .user_pro_img_area-->
                                        </div> <!--ends .col-md-4-->

                                        <div class="col-md-9">
                                            <div class="atbd_user_profile_edit">
                                                <div class="profile_title">
                                                    <h4><?php _e('My Profile', 'directorist'); ?></h4>
                                                </div>

                                                <div class="user_info_wrap">
                                                    <!--hidden inputs-->
                                                    <input type="hidden" name="ID"
                                                           value="<?php echo get_current_user_id(); ?>">
                                                    <!--Full name-->
                                                    <div class="row row_fu_name">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="full_name"><?php _e('Full Name', 'directorist'); ?></label>
                                                                <input class="form-control" type="text"
                                                                       name="user[full_name]"
                                                                       value="<?php echo !empty($c_user->display_name) ? esc_attr($c_user->display_name) : ''; ?>"
                                                                       placeholder="<?php _e('Enter your full name', 'directorist'); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="user_name"><?php _e('User Name', 'directorist'); ?></label>
                                                                <input class="form-control" id="user_name" type="text"
                                                                       disabled="disabled" name="user[user_name]"
                                                                       value="<?php echo !empty($c_user->user_login) ? esc_attr($c_user->user_login) : ''; ?>"> <?php _e('(username can not be changed)', 'directorist'); ?>
                                                            </div>
                                                        </div>
                                                    </div> <!--ends .row-->
                                                    <!--First Name-->
                                                    <div class="row row_fl_name">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="first_name"><?php _e('First Name', 'directorist'); ?></label>
                                                                <input class="form-control" id="first_name" type="text"
                                                                       name="user[first_name]"
                                                                       value="<?php echo !empty($c_user->first_name) ? esc_attr($c_user->first_name) : ''; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="last_name"><?php _e('Last Name', 'directorist'); ?></label>
                                                                <input class="form-control" id="last_name" type="text"
                                                                       name="user[last_name]"
                                                                       value="<?php echo !empty($c_user->last_name) ? esc_attr($c_user->last_name) : ''; ?>">
                                                            </div>
                                                        </div>
                                                    </div> <!--ends .row-->
                                                    <!--Email-->
                                                    <div class="row row_email_cell">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="req_email"><?php _e('Email (required)', 'directorist'); ?></label>
                                                                <input class="form-control" id="req_email" type="text"
                                                                       name="user[user_email]"
                                                                       value="<?php echo !empty($c_user->user_email) ? esc_attr($c_user->user_email) : ''; ?>"
                                                                       required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="phone"><?php _e('Cell Number', 'directorist'); ?></label>
                                                                <input class="form-control" type="tel"
                                                                       name="user[phone]"
                                                                       value="<?php echo !empty($u_phone) ? esc_attr($u_phone) : ''; ?>"
                                                                       placeholder="<?php _e('Enter your phone number', 'directorist'); ?>">
                                                            </div>
                                                        </div>
                                                    </div> <!--ends .row-->
                                                    <!--Website-->
                                                    <div class="row row_site_addr">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="website"><?php _e('Website', 'directorist'); ?></label>
                                                                <input class="form-control" id="website" type="text"
                                                                       name="user[website]"
                                                                       value="<?php echo !empty($u_website) ? esc_url($u_website) : ''; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="address"><?php _e('Address', 'directorist'); ?></label>
                                                                <input class="form-control" id="address" type="text"
                                                                       name="user[address]"
                                                                       value="<?php echo !empty($u_address) ? esc_attr($u_address) : ''; ?>">
                                                            </div>
                                                        </div>
                                                    </div> <!--ends .row-->


                                                    <div class="row row_password">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="new_pass"><?php _e('New Password', 'directorist'); ?></label>
                                                                <input id="new_pass" class="form-control"
                                                                       type="password"
                                                                       name="user[new_pass]"
                                                                       value="<?php echo !empty($new_pass) ? esc_attr($new_pass) : ''; ?>"
                                                                       placeholder="<?php _e('Enter a new password', 'directorist'); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="confirm_pass"><?php _e('Confirm New Password', 'directorist'); ?></label>
                                                                <input id="confirm_pass" class="form-control"
                                                                       type="password"
                                                                       name="user[confirm_pass]"
                                                                       value="<?php echo !empty($confirm_pass) ? esc_attr($confirm_pass) : ''; ?>"
                                                                       placeholder="<?php _e('Confirm your new password', 'directorist'); ?>">
                                                            </div>
                                                        </div>
                                                    </div><!--ends .row-->
                                                    <!--social info-->
                                                    <div class="row row_socials">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="facebook"><?php _e('Facebook', 'directorist'); ?></label>
                                                                <p><?php _e('Leave it empty to hide', 'directorist') ?></p>
                                                                <input id="facebook" class="form-control" type="url"
                                                                       name="user[facebook]"
                                                                       value="<?php echo !empty($facebook) ? esc_attr($facebook) : ''; ?>"
                                                                       placeholder="<?php _e('Enter your facebook url', 'directorist'); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="twitter"><?php _e('Twitter', 'directorist'); ?></label>
                                                                <p><?php _e('Leave it empty to hide', 'directorist') ?></p>
                                                                <input id="twitter" class="form-control" type="url"
                                                                       name="user[twitter]"
                                                                       value="<?php echo !empty($twitter) ? esc_attr($twitter) : ''; ?>"
                                                                       placeholder="<?php _e('Enter your twitter url', 'directorist'); ?>">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="linkedIn"><?php _e('LinkedIn', 'directorist'); ?></label>
                                                                <p><?php _e('Leave it empty to hide', 'directorist') ?></p>
                                                                <input id="linkedIn" class="form-control" type="url"
                                                                       name="user[linkedIn]"
                                                                       value="<?php echo !empty($linkedIn) ? esc_attr($linkedIn) : ''; ?>"
                                                                       placeholder="<?php _e('Enter linkedIn url', 'directorist'); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="youtube"><?php _e('Youtube', 'directorist'); ?></label>
                                                                <p><?php _e('Leave it empty to hide', 'directorist') ?></p>
                                                                <input id="youtube" class="form-control" type="url"
                                                                       name="user[youtube]"
                                                                       value="<?php echo !empty($youtube) ? esc_attr($youtube) : ''; ?>"
                                                                       placeholder="<?php _e('Enter youtube url', 'directorist'); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="bio"><?php _e('About Author', 'directorist'); ?></label>
                                                                <textarea class="wp-editor-area form-control"
                                                                          style="height: 200px" autocomplete="off"
                                                                          cols="40"
                                                                          name="user[bio]"
                                                                          id="bio"><?php echo !empty($bio) ? esc_attr($bio) : ''; ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div><!--ends social info .row-->


                                                    <button type="submit" class="btn btn-primary"
                                                            id="update_user_profile"><?php _e('Save Changes', 'directorist'); ?></button>

                                                    <div id="pro_notice" style="padding: 20px"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php } ?>
                        <?php if (!empty($fav_listings_tab)) { ?>
                            <div class="atbd_tab_inner" id="saved_items">
                                <div class="atbd_saved_items_wrapper">
                                    <table class="table table-bordered atbd_single_saved_item table-responsive-sm">
                                        <?php
                                        if ($fav_listings->have_posts()) {
                                            ?>
                                            <thead>
                                            <tr>
                                                <th><?php _e('Listing Name', 'directorist') ?></th>
                                                <th><?php _e('Category', 'directorist') ?></th>
                                                <th><?php _e('Unfavourite', 'directorist') ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($fav_listings->posts as $post) {
                                                $title = !empty($post->post_title) ? $post->post_title : __('Untitled', 'directorist');
                                                $cats = get_the_terms($post->ID, ATBDP_CATEGORY);
                                                $category = get_post_meta($post->ID, '_admin_category_select', true);
                                                $category_name = !empty($cats) ? $cats[0]->name : 'Uncategorized';
                                                $category_icon = !empty($cats) ? esc_attr(get_cat_icon($cats[0]->term_id)) : atbdp_icon_type() . '-tags';

                                                $icon_type = substr($category_icon, 0, 2);
                                                $category_link = !empty($cats) ? esc_url(ATBDP_Permalink::atbdp_get_category_page($cats[0])) : '#';
                                                $post_link = esc_url(get_post_permalink($post->ID));

                                                $listing_img = get_post_meta($post->ID, '_listing_img', true);
                                                $listing_prv_img = get_post_meta($post->ID, '_listing_prv_img', true);
                                                $crop_width = get_directorist_option('crop_width', 360);
                                                $crop_height = get_directorist_option('crop_height', 300);
                                                if (!empty($listing_prv_img)) {
                                                    $prv_image = wp_get_attachment_image_src($listing_prv_img, 'large');
                                                    $prv_image = is_array($prv_image) ? $prv_image[0] : '';
                                                }
                                                if (!empty($listing_img[0])) {

                                                    $gallery_img = wp_get_attachment_image_src($listing_img[0], 'medium')[0];
                                                }

                                                if (!empty($listing_prv_img)) {

                                                    $img_src = $prv_image;

                                                }
                                                if (!empty($listing_img[0]) && empty($listing_prv_img)) {

                                                    $img_src = $gallery_img;

                                                }
                                                if (empty($listing_img[0]) && empty($listing_prv_img)) {

                                                    $img_src = ATBDP_PUBLIC_ASSETS . 'images/grid.jpg';

                                                }

                                                printf(' <tr>
                                            <td class="thumb_title">
                                                <div class="img_wrapper"><a href="%s">
                                                <img
                                                            src="%s"
                                                            alt="%s">
                                               
                                                </a>
                                                <h4><a href="%s">%s</a></h4>
                                                </div>
                                            </td>

                                            <td class="saved_item_category">
                                                <a href="%s"><span class="%s"></span>%s</a>
                                            </td>
                                             <td class="remove_saved_item">
                                               %s
                                            </td>
                                            

                                        </tr>',
                                                    $post_link, $img_src, $title, $post_link, $title, //first td
                                                    $category_link, ('la' === $icon_type) ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon, $category_name, // second td
                                                    atbdp_listings_mark_as_favourite($post->ID) // third td
                                                );
                                            }
                                            ?>
                                            </tbody>
                                            <?php
                                        } else {
                                            printf('<p class="atbdp_nlf">%s</p>', __("Nothing found!", 'directorist'));
                                        }
                                        ?>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>
                        <?php
                        do_action('atbdp_tab_content_after_favorite');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
/**
 * @package Directorist
 * @since 5.9.3
 */
do_action('atbdp_after_user_dashboard'); ?>

