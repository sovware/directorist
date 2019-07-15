<?php
$listings = ATBDP()->user->current_user_listings();
$fav_listings = ATBDP()->user->current_user_fav_listings();
$uid = get_current_user_id();
$c_user = get_userdata($uid);
$u_website = $c_user->user_url;
$avatar = get_user_meta($uid, 'avatar', true);
$u_phone = get_user_meta($uid, 'phone', true);
$u_pro_pic_id = get_user_meta($uid, 'pro_pic', true);
$u_pro_pic = wp_get_attachment_image_src($u_pro_pic_id, 'directory-large');
$facebook = get_user_meta($uid, 'facebook', true);
$twitter = get_user_meta($uid, 'twitter', true);
$linkedIn = get_user_meta($uid, 'linkedIn', true);
$youtube = get_user_meta($uid, 'youtube', true);
$bio = get_user_meta($uid, 'bio', true);
$u_address = get_user_meta($uid, 'address', true);
$date_format = get_option('date_format');
$featured_active = get_directorist_option('enable_featured_listing');
$is_disable_price = get_directorist_option('disable_list_price');
$my_listing_tab = get_directorist_option('my_listing_tab', 1);
$my_profile_tab = get_directorist_option('my_profile_tab', 1);
$fav_listings_tab = get_directorist_option('fav_listings_tab', 1);
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
                        <h2><?php _e('My Dashboard', ATBDP_TEXTDOMAIN); ?></h2>
                    </div> <!--ends add_listing_title-->
                    <?php
                }
                ?>
                <div class="atbd_dashboard_wrapper atbd_tab">
                    <div class="atbd_user_dashboard_nav atbd_tab_nav">
                        <!-- Nav tabs -->
                        <div class="atbdp_tab_nav_wrapper"><ul class="atbdp_tab_nav--content">
                            <?php if (!empty($my_listing_tab)) { ?>
                                <li>
                                    <a href="" target="my_listings" class="atbd_tn_link tabItemActive">
                                        <?php $list_found = ($listings->found_posts > 0) ? $listings->found_posts : '0';
                                        printf(__('My Listing (%s)', ATBDP_TEXTDOMAIN), $list_found); ?>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (!empty($my_profile_tab)) { ?>
                                <li><a href="" class="atbd_tn_link" target="profile"><?php _e('My Profile', ATBDP_TEXTDOMAIN); ?></a>
                                </li>
                            <?php } ?>
                            <?php if (!empty($fav_listings_tab)) { ?>
                                <li><a href="" class="atbd_tn_link" target="saved_items"><?php _e('Favorite Listings', ATBDP_TEXTDOMAIN); ?></a>
                                </li>
                            <?php } ?>
                            <?php
                            do_action('atbdp_tab_after_favorite_listings');
                            ?>

                        </ul></div>

                        <div class="nav_button">
                            <?php if (!empty($submit_listing_button)) { ?>
                                <a href="<?= (is_fee_manager_active()) ? esc_url(ATBDP_Permalink::get_fee_plan_page_link()) : esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>"
                                   class="<?= atbdp_directorist_button_classes(); ?>"><?php _e('Submit Listing', ATBDP_TEXTDOMAIN); ?></a>
                            <?php } ?>
                            <a href="<?= esc_url(wp_logout_url(home_url())); ?>"
                               class="<?= atbdp_directorist_button_classes('secondary'); ?>"><?php _e('Log Out', ATBDP_TEXTDOMAIN); ?></a>
                        </div>
                    </div> <!--ends dashboard_nav-->

                    <!-- Tab panes -->
                    <div class="atbd_tab-content">
                        <?php if (!empty($my_listing_tab)) { ?>

                            <div class="atbd_tab_inner tabContentActive" data-uk-grid id="my_listings"><div class="row">
                                <?php if ($listings->have_posts()) {
                                    foreach ($listings->posts as $post) {
                                        // get only one parent or high level term object
                                        $top_category = ATBDP()->taxonomy->get_one_high_level_term($post->ID, ATBDP_CATEGORY);
                                        $price = get_post_meta($post->ID, '_price', true);
                                        $featured = get_post_meta($post->ID, '_featured', true);
                                        $listing_img = get_post_meta($post->ID, '_listing_img', true);
                                        $listing_prv_img = get_post_meta($post->ID, '_listing_prv_img', true);
                                        $tagline = get_post_meta($post->ID, '_tagline', true);
                                        $thumbnail_cropping = get_directorist_option('thumbnail_cropping', 1);
                                        $crop_width = get_directorist_option('crop_width', 360);
                                        $crop_height = get_directorist_option('crop_height', 300);
                                        if (!empty($listing_prv_img)) {

                                            if ($thumbnail_cropping) {

                                                $prv_image = atbdp_image_cropping($listing_prv_img, $crop_width, $crop_height, true, 100)['url'];

                                            } else {
                                                $prv_image = wp_get_attachment_image_src($listing_prv_img, 'large')[0];
                                            }

                                        }
                                        if (!empty($listing_img[0])) {
                                            if ($thumbnail_cropping) {
                                                $gallery_img = atbdp_image_cropping($listing_img[0], $crop_width, $crop_height, true, 100)['url'];

                                            } else {
                                                $gallery_img = wp_get_attachment_image_src($listing_img[0], 'medium')[0];
                                            }

                                        }
                                        $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');

                                        ?>

                                        <div class="col-lg-4 col-sm-6" id="listing_id_<?= $post->ID; ?>">
                                            <div class="atbd_single_listing atbd_listing_card">
                                                <article
                                                        class="atbd_single_listing_wrapper <?php echo ($featured) ? 'directorist-featured-listings' : ''; ?>">
                                                    <figure class="atbd_listing_thumbnail_area">
                                                        <div class="atbd_listing_image">
                                                            <?php if (!empty($listing_prv_img)) {

                                                                echo '<a href="' . esc_url(get_post_permalink($post->ID)) . '"><img src="' . esc_url($prv_image) . '" alt="' . esc_html(stripslashes(get_the_title($post->ID))) . '"></a>';

                                                            }
                                                            if (!empty($listing_img[0]) && empty($listing_prv_img)) {

                                                                echo '<a href="' . esc_url(get_post_permalink($post->ID)) . '"><img src="' . esc_url($gallery_img) . '" alt="' . esc_html(stripslashes(get_the_title($post->ID))) . '"></a>';

                                                            }
                                                            if (empty($listing_img[0]) && empty($listing_prv_img)) {

                                                                echo '<a href="' . esc_url(get_post_permalink($post->ID)) . '"><img src="' . esc_url($default_image) . '" alt="' . esc_html(get_the_title($post->ID)) . '"></a>';

                                                            }
                                                            ?>
                                                        </div>

                                                        <figcaption class="atbd_thumbnail_overlay_content">

                                                            <div class="atbd_lower_badge">
                                                                <?php
                                                                $featured_text = get_directorist_option('feature_badge_text', esc_html__('Featured', ATBDP_TEXTDOMAIN));
                                                                if ($featured) {
                                                                    printf(
                                                                        '<span class="atbd_badge atbd_badge_featured">%s</span>', __($featured_text, ATBDP_TEXTDOMAIN)

                                                                    );
                                                                }
                                                                ?>
                                                            </div>
                                                        </figcaption>
                                                    </figure>

                                                    <div class="atbd_listing_info">
                                                        <div class="atbd_content_upper">
                                                            <div class="atbd_dashboard_tittle_metas">
                                                                <h4 class="atbd_listing_title">
                                                                    <a href="<?= get_post_permalink($post->ID); ?>">
                                                                        <?= !empty($post->post_title) ? esc_html(stripslashes($post->post_title)) : ''; ?>
                                                                    </a>
                                                                </h4>

                                                                <div class="atbd_listing_meta">
                                                                    <?php
                                                                    /**
                                                                     * Fires after the title and sub title of the listing is rendered
                                                                     *
                                                                     *
                                                                     * @since 1.0.0
                                                                     */

                                                                    do_action('atbdp_after_listing_tagline');
                                                                    ?>
                                                                </div><!-- End atbd listing meta -->
                                                            </div>

                                                            <div class="db_btn_area">
                                                                <?php
                                                                $exp_date = get_post_meta($post->ID, '_expiry_date', true);
                                                                $never_exp = get_post_meta($post->ID, '_never_expire', true);
                                                                $lstatus = get_post_meta($post->ID, '_listing_status', true);

                                                                $post_date = $post->post_date;
                                                                $datetime2 = new DateTime($exp_date);
                                                                $datetime1 = new DateTime($post_date);
                                                                $interval = $datetime1->diff($datetime2);
                                                                $interval = $interval->format('%R%a');
                                                                $result = substr($interval, 0, 1);
                                                                if ('+' === $result) {
                                                                    $interval = true;
                                                                } else {
                                                                    $interval = false;
                                                                }
                                                                // If the listing needs renewal then there is no need to show promote button
                                                                if (($interval) && ('renewal' == $lstatus || 'expired' == $lstatus)) {

                                                                    $can_renew = get_directorist_option('can_renew_listing');
                                                                    if (!$can_renew) return false;// vail if renewal option is turned off on the site.
                                                                    if (is_fee_manager_active()){
                                                                        ?>
                                                                        <a href="javascript:void(0)"
                                                                           data-toggle="modal"
                                                                           data-target="#atpp-plan-change-modal"
                                                                           data-listing_id="<?= $post->ID; ?>"
                                                                           class="directory_btn btn btn-outline-success atbdp_renew_with_plan">
                                                                            <?php _e('Renew', ATBDP_TEXTDOMAIN); ?>
                                                                        </a>
                                                                        <?php
                                                                    }else{
                                                                    ?>
                                                                    <a href="<?php echo esc_url(ATBDP_Permalink::get_renewal_page_link($post->ID)) ?>"
                                                                       id="directorist-renew"
                                                                       data-listing_id="<?= $post->ID; ?>"
                                                                       class="directory_btn btn btn-outline-success">
                                                                        <?php _e('Renew', ATBDP_TEXTDOMAIN); ?>
                                                                    </a>
                                                                    <!--@todo; add expiration and renew date-->
                                                                <?php }} else {
                                                                    // show promotions if the featured is available
                                                                    // featured available but the listing is not featured, show promotion button
                                                                    if ($featured_active && empty($featured) && !is_fee_manager_active()) {
                                                                        ?>
                                                                        <div class="atbd_promote_btn_wrapper">
                                                                            <a href="<?= esc_url(ATBDP_Permalink::get_checkout_page_link($post->ID)) ?>"
                                                                               id="directorist-promote"
                                                                               data-listing_id="<?= $post->ID; ?>"
                                                                               class="directory_btn btn btn-primary">
                                                                                <?php _e('Promote Your listing', ATBDP_TEXTDOMAIN); ?>
                                                                            </a>
                                                                        </div>
                                                                    <?php }
                                                                } ?>

                                                                <a href="<?= esc_url(ATBDP_Permalink::get_edit_listing_page_link($post->ID)); ?>"
                                                                   id="edit_listing"
                                                                   class="directory_edit_btn btn btn-outline-primary"><?php _e('Edit', ATBDP_TEXTDOMAIN); ?></a>
                                                                <a href="#" id="remove_listing"
                                                                   data-listing_id="<?= $post->ID; ?>"
                                                                   class="directory_remove_btn btn btn-outline-danger"><?php _e('Delete', ATBDP_TEXTDOMAIN); ?></a>
                                                            </div> <!--ends .db_btn_area-->
                                                            <?php /* @todo: deleted the read more link */ ?>
                                                        </div><!-- end ./atbd_content_upper -->

                                                        <div class="atbd_listing_bottom_content">
                                                            <div class="listing-meta">
                                                                <?php
                                                                /**
                                                                 * @since 5.0.3
                                                                 */
                                                                do_action('atbdp_user_dashboard_listings_before_expireation', $post->ID);
                                                                $exp_text = !empty($never_exp)
                                                                    ? __('Never Expires', ATBDP_TEXTDOMAIN)
                                                                    : date_i18n($date_format, strtotime($exp_date)); ?>
                                                                <p><?php printf(__('<span>Expiration:</span> %s', ATBDP_TEXTDOMAIN), (($interval) && ('expired' == $lstatus)) ? '<span style="color: red">' . __('Expired', ATBDP_TEXTDOMAIN) . '</span>' : $exp_text); ?></p>
                                                                <p><?php printf(__('<span>Listing Status:</span> %s', ATBDP_TEXTDOMAIN), get_post_status_object($post->post_status)->label); ?></p>
                                                                <?php
                                                                /**
                                                                 * Fires after the price of the listing is rendered
                                                                 *
                                                                 *
                                                                 * @since 3.1.0
                                                                 */
                                                                do_action('atbdp_after_listing_price');
                                                                ?>

                                                            </div>
                                                        </div><!-- end ./atbd_listing_bottom_content -->
                                                    </div>
                                                </article>
                                            </div>
                                        </div> <!--ends . col-lg-3 col-sm-6-->
                                        <?php
                                    }
                                } else {
                                    echo '<p class="atbdp_nlf">' . __("Looks like you have not created any listing yet!", ATBDP_TEXTDOMAIN) . '</p>';
                                }
                                $pagination = get_directorist_option('user_listings_pagination',1);
                                $paged      = atbdp_get_paged_num();
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
                                                               value="<?= !empty($u_pro_pic_id) ? esc_attr($u_pro_pic_id) : ''; ?>">
                                                        <label for="pro_pic"
                                                               id="upload_pro_pic"><?php _e('Change', ATBDP_TEXTDOMAIN); ?></label>
                                                    </div> <!--ends .choose_btn-->
                                                    <img src="<?= !empty($u_pro_pic) ? esc_url($u_pro_pic[0]) : esc_url(ATBDP_PUBLIC_ASSETS . 'images/no-image.jpg'); ?>"
                                                         id="pro_img" alt="">

                                                </div> <!--ends .user_img-->
                                            </div> <!--ends .user_pro_img_area-->
                                        </div> <!--ends .col-md-4-->

                                        <div class="col-md-9">
                                            <div class="atbd_user_profile_edit">
                                                <div class="profile_title">
                                                    <h4><?php _e('My Profile', ATBDP_TEXTDOMAIN); ?></h4>
                                                </div>

                                                <div class="user_info_wrap">
                                                    <!--hidden inputs-->
                                                    <input type="hidden" name="ID" value="<?= get_current_user_id(); ?>">
                                                    <!--Full name-->
                                                    <div class="row row_fu_name">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="full_name"><?php _e('Full Name', ATBDP_TEXTDOMAIN); ?></label>
                                                                <input class="form-control" type="text"
                                                                       name="user[full_name]"
                                                                       value="<?= !empty($c_user->display_name) ? esc_attr($c_user->display_name) : ''; ?>"
                                                                       placeholder="<?php _e('Enter your full name', ATBDP_TEXTDOMAIN); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="user_name"><?php _e('User Name', ATBDP_TEXTDOMAIN); ?></label>
                                                                <input class="form-control" id="user_name" type="text"
                                                                       disabled="disabled" name="user[user_name]"
                                                                       value="<?= !empty($c_user->user_login) ? esc_attr($c_user->user_login) : ''; ?>"> <?php _e('(username can not be changed)', ATBDP_TEXTDOMAIN); ?>
                                                            </div>
                                                        </div>
                                                    </div> <!--ends .row-->
                                                    <!--First Name-->
                                                    <div class="row row_fl_name">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="first_name"><?php _e('First Name', ATBDP_TEXTDOMAIN); ?></label>
                                                                <input class="form-control" id="first_name" type="text"
                                                                       name="user[first_name]"
                                                                       value="<?= !empty($c_user->first_name) ? esc_attr($c_user->first_name) : ''; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="last_name"><?php _e('Last Name', ATBDP_TEXTDOMAIN); ?></label>
                                                                <input class="form-control" id="last_name" type="text"
                                                                       name="user[last_name]"
                                                                       value="<?= !empty($c_user->last_name) ? esc_attr($c_user->last_name) : ''; ?>">
                                                            </div>
                                                        </div>
                                                    </div> <!--ends .row-->
                                                    <!--Email-->
                                                    <div class="row row_email_cell">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="req_email"><?php _e('Email (required)', ATBDP_TEXTDOMAIN); ?></label>
                                                                <input class="form-control" id="req_email" type="text"
                                                                       name="user[user_email]"
                                                                       value="<?= !empty($c_user->user_email) ? esc_attr($c_user->user_email) : ''; ?>"
                                                                       required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="phone"><?php _e('Cell Number', ATBDP_TEXTDOMAIN); ?></label>
                                                                <input class="form-control" type="tel" name="user[phone]"
                                                                       value="<?= !empty($u_phone) ? esc_attr($u_phone) : ''; ?>"
                                                                       placeholder="<?php _e('Enter your phone number', ATBDP_TEXTDOMAIN); ?>">
                                                            </div>
                                                        </div>
                                                    </div> <!--ends .row-->
                                                    <!--Website-->
                                                    <div class="row row_site_addr">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="website"><?php _e('Website', ATBDP_TEXTDOMAIN); ?></label>
                                                                <input class="form-control" id="website" type="text"
                                                                       name="user[website]"
                                                                       value="<?= !empty($u_website) ? esc_url($u_website) : ''; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="address"><?php _e('Address', ATBDP_TEXTDOMAIN); ?></label>
                                                                <input class="form-control" id="address" type="text"
                                                                       name="user[address]"
                                                                       value="<?= !empty($u_address) ? esc_attr($u_address) : ''; ?>">
                                                            </div>
                                                        </div>
                                                    </div> <!--ends .row-->


                                                    <div class="row row_password">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="new_pass"><?php _e('New Password', ATBDP_TEXTDOMAIN); ?></label>
                                                                <input id="new_pass" class="form-control" type="password"
                                                                       name="user[new_pass]"
                                                                       value="<?= !empty($new_pass) ? esc_attr($new_pass) : ''; ?>"
                                                                       placeholder="<?php _e('Enter a new password', ATBDP_TEXTDOMAIN); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="confirm_pass"><?php _e('Confirm New Password', ATBDP_TEXTDOMAIN); ?></label>
                                                                <input id="confirm_pass" class="form-control"
                                                                       type="password"
                                                                       name="user[confirm_pass]"
                                                                       value="<?= !empty($confirm_pass) ? esc_attr($confirm_pass) : ''; ?>"
                                                                       placeholder="<?php _e('Confirm your new password', ATBDP_TEXTDOMAIN); ?>">
                                                            </div>
                                                        </div>
                                                    </div><!--ends .row-->
                                                    <!--social info-->
                                                    <div class="row row_socials">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="facebook"><?php _e('Facebook', ATBDP_TEXTDOMAIN); ?></label>
                                                                <p><?php _e('Leave it empty to hide', ATBDP_TEXTDOMAIN) ?></p>
                                                                <input id="facebook" class="form-control" type="url"
                                                                       name="user[facebook]"
                                                                       value="<?= !empty($facebook) ? esc_attr($facebook) : ''; ?>"
                                                                       placeholder="<?php _e('Enter your facebook url', ATBDP_TEXTDOMAIN); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="twitter"><?php _e('Twitter', ATBDP_TEXTDOMAIN); ?></label>
                                                                <p><?php _e('Leave it empty to hide', ATBDP_TEXTDOMAIN) ?></p>
                                                                <input id="twitter" class="form-control" type="url"
                                                                       name="user[twitter]"
                                                                       value="<?= !empty($twitter) ? esc_attr($twitter) : ''; ?>"
                                                                       placeholder="<?php _e('Enter your twitter url', ATBDP_TEXTDOMAIN); ?>">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="linkedIn"><?php _e('LinkedIn', ATBDP_TEXTDOMAIN); ?></label>
                                                                <p><?php _e('Leave it empty to hide', ATBDP_TEXTDOMAIN) ?></p>
                                                                <input id="linkedIn" class="form-control" type="url"
                                                                       name="user[linkedIn]"
                                                                       value="<?= !empty($linkedIn) ? esc_attr($linkedIn) : ''; ?>"
                                                                       placeholder="<?php _e('Enter linkedIn url', ATBDP_TEXTDOMAIN); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="youtube"><?php _e('Youtube', ATBDP_TEXTDOMAIN); ?></label>
                                                                <p><?php _e('Leave it empty to hide', ATBDP_TEXTDOMAIN) ?></p>
                                                                <input id="youtube" class="form-control" type="url"
                                                                       name="user[youtube]"
                                                                       value="<?= !empty($youtube) ? esc_attr($youtube) : ''; ?>"
                                                                       placeholder="<?php _e('Enter youtube url', ATBDP_TEXTDOMAIN); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="bio"><?php _e('About Author', ATBDP_TEXTDOMAIN); ?></label>
                                                                <textarea class="wp-editor-area form-control"
                                                                          style="height: 200px" autocomplete="off" cols="40"
                                                                          name="user[bio]"
                                                                          id="bio"><?= !empty($bio) ? esc_attr($bio) : ''; ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div><!--ends social info .row-->


                                                    <button type="submit" class="btn btn-primary"
                                                            id="update_user_profile"><?php _e('Save Changes', ATBDP_TEXTDOMAIN); ?></button>

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
                                                <th><?php _e('Listing Name', ATBDP_TEXTDOMAIN) ?></th>
                                                <th><?php _e('Category', ATBDP_TEXTDOMAIN) ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($fav_listings->posts as $post) {
                                                $title = !empty($post->post_title) ? $post->post_title : __('Untitled', ATBDP_TEXTDOMAIN);
                                                $cats = get_the_terms($post->ID, ATBDP_CATEGORY);
                                                $category = get_post_meta($post->ID, '_admin_category_select', true);
                                                $category_name = !empty($cats) ? $cats[0]->name : 'Uncategorized';
                                                $category_icon = !empty($cats) ? esc_attr(get_cat_icon($cats[0]->term_id)) : atbdp_icon_type().'-tags';

                                                $icon_type = substr($category_icon, 0,2);
                                                $category_link = !empty($cats) ? esc_url(ATBDP_Permalink::atbdp_get_category_page($cats[0])) : '#';
                                                $post_link = esc_url(get_post_permalink($post->ID));

                                                $listing_img = get_post_meta($post->ID, '_listing_img', true);
                                                $listing_prv_img = get_post_meta($post->ID, '_listing_prv_img', true);
                                                $thumbnail_cropping = get_directorist_option('thumbnail_cropping', 1);
                                                $crop_width = get_directorist_option('crop_width', 360);
                                                $crop_height = get_directorist_option('crop_height', 300);
                                                if (!empty($listing_prv_img)) {

                                                    if ($thumbnail_cropping) {

                                                        $prv_image = atbdp_image_cropping($listing_prv_img, $crop_width, $crop_height, true, 100)['url'];

                                                    } else {
                                                        $prv_image = wp_get_attachment_image_src($listing_prv_img, 'large')[0];
                                                    }

                                                }
                                                if (!empty($listing_img[0])) {
                                                    if ($thumbnail_cropping) {
                                                        $gallery_img = atbdp_image_cropping($listing_img[0], $crop_width, $crop_height, true, 100)['url'];

                                                    } else {
                                                        $gallery_img = wp_get_attachment_image_src($listing_img[0], 'medium')[0];
                                                    }

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


                                        </tr>', $post_link, $img_src, $title, $post_link, $title, $category_link, ('la' === $icon_type)?$icon_type.' '. $category_icon:'fa '. $category_icon , $category_name, atbdp_get_remove_favourites_page_link($post->ID), __('Remove', ATBDP_TEXTDOMAIN));
                                            }
                                            ?>
                                            </tbody>
                                            <?php
                                        } else {
                                            printf('<p class="atbdp_nlf">%s</p>', __("No listing found !", ATBDP_TEXTDOMAIN));
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

