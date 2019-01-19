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
$google = get_user_meta($uid, 'google', true);
$linkedIn = get_user_meta($uid, 'linkedIn', true);
$youtube = get_user_meta($uid, 'youtube', true);
$bio = get_user_meta($uid, 'bio', true);
$u_address = get_user_meta($uid, 'address', true);
$date_format = get_option('date_format');
$featured_active = get_directorist_option('enable_featured_listing');
$is_disable_price = get_directorist_option('disable_list_price');


/*@todo; later show featured listing first on the user dashboard maybe??? */
?>
<div id="directorist" class="directorist atbd_wrapper dashboard_area">
    <div class="<?php echo is_directoria_active() ? 'container' : 'container-fluid'; ?>">
        <div class="row">
            <div class="col-md-12">

                <div class="atbd_add_listing_title">
                    <h2><?php _e('My Dashboard', ATBDP_TEXTDOMAIN); ?></h2>
                </div> <!--ends add_listing_title-->

                <div class="atbd_dashboard_wrapper">
                    <div class="atbd_user_dashboard_nav">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist" id="atbdp_tabs">
                            <li role="presentation" class="active nav-item">
                                <a href="#my_listings" class="nav-link active" aria-controls="my_listings" role="tab"
                                   data-toggle="tab">
                                    <?php $list_found = ($listings->found_posts > 0) ? $listings->found_posts : '0';
                                    printf(__('My Listing (%s)', ATBDP_TEXTDOMAIN), $list_found); ?>
                                </a>
                            </li>
                            <li role="presentation" class="nav-item"><a href="#profile" class="nav-link"
                                                                        aria-controls="profile" role="tab"
                                                                        data-toggle="tab"><?php _e('My Profile', ATBDP_TEXTDOMAIN); ?></a>
                            </li>
                            <li role="presentation" class="nav-item"><a href="#saved_items" class="nav-link"
                                                                        aria-controls="profile" role="tab"
                                                                        data-toggle="tab"><?php _e('Favorite Listings', ATBDP_TEXTDOMAIN); ?></a>
                            </li>
                            <?php
                            if (class_exists('ATBDP_Fee_Manager')){
                                ?>
                                <li role="presentation" class="nav-item"><a href="#manage_fees" class="nav-link"
                                                                            aria-controls="profile" role="tab"
                                                                            data-toggle="tab"><?php _e('Manage Fees', ATBDP_TEXTDOMAIN); ?></a>
                                </li>
                                <?php
                            }
                            ?>

                        </ul>

                        <div class="nav_button">
                            <a href="<?= esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>"
                               class="<?= atbdp_directorist_button_classes(); ?>"><?php _e('Submit Listing', ATBDP_TEXTDOMAIN); ?></a>
                            <a href="<?= esc_url(wp_logout_url()); ?>"
                               class="<?= atbdp_directorist_button_classes(); ?>"><?php _e('Log Out', ATBDP_TEXTDOMAIN); ?></a>
                        </div>
                    </div> <!--ends dashboard_nav-->

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active row" data-uk-grid id="my_listings">
                            <?php if ($listings->have_posts()) {
                                foreach ($listings->posts as $post) {
                                    // get only one parent or high level term object
                                    $top_category = ATBDP()->taxonomy->get_one_high_level_term($post->ID, ATBDP_CATEGORY);
                                    $price = get_post_meta($post->ID, '_price', true);
                                    $featured = get_post_meta($post->ID, '_featured', true);
                                    $listing_img = get_post_meta($post->ID, '_listing_img', true);
                                    $listing_prv_img = get_post_meta($post->ID, '_listing_prv_img', true);
                                    $tagline = get_post_meta($post->ID, '_tagline', true);
                                    $thumbnail_cropping = get_directorist_option('thumbnail_cropping',1);
                                    $crop_width                    = get_directorist_option('crop_width', 360);
                                    $crop_height                   = get_directorist_option('crop_height', 300);
                                    if(!empty($listing_prv_img)) {

                                        if($thumbnail_cropping) {

                                            $prv_image = atbdp_image_cropping($listing_prv_img, $crop_width, $crop_height, true, 100)['url'];

                                        }else{
                                            $prv_image   = wp_get_attachment_image_src($listing_prv_img, 'large')[0];
                                        }

                                    }
                                    if(!empty($listing_img[0])) {
                                        if( $thumbnail_cropping ) {
                                            $prv_image = atbdp_image_cropping($listing_prv_img, $crop_width, $crop_height, true, 100)['url'];

                                        }else{
                                            $gallery_img = wp_get_attachment_image_src($listing_img[0], 'medium')[0];
                                        }

                                    }
                                    ?>
                                    <div class="col-lg-4 col-sm-6" id="listing_id_<?= $post->ID; ?>">
                                        <div class="atbd_single_listing atbd_listing_card">
                                            <article
                                                    class="atbd_single_listing_wrapper <?php echo ($featured) ? 'directorist-featured-listings' : ''; ?>">
                                                <figure class="atbd_listing_thumbnail_area">
                                                    <div class="atbd_listing_image">
                                                        <?php if(!empty($listing_prv_img)){

                                                echo '<img src="'.esc_url($prv_image).'" alt="listing image">';

                                            } if(!empty($listing_img[0]) && empty($listing_prv_img)) {

                                                echo '<img src="' . esc_url($gallery_img) . '" alt="listing image">';

                                            }if (empty($listing_img[0]) && empty($listing_prv_img)){

                                                echo '<img src="'.ATBDP_PUBLIC_ASSETS . 'images/grid.jpg'.'" alt="listing image">';

                                            }
                                            ?>
                                                    </div>

                                                    <figcaption class="atbd_thumbnail_overlay_content">

                                                        <div class="atbd_lower_badge">
                                                            <?php
                                                            if ($featured) {
                                                                printf(
                                                                    '<span class="atbd_badge atbd_badge_featured">Featured</span>',
                                                                    esc_html__('Featured', ATBDP_TEXTDOMAIN)
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
                                                            $lstatus = get_post_meta($post->ID, '_listing_status', true);
                                                            // If the listing needs renewal then there is no need to show promote button
                                                            if ('renewal' == $lstatus || 'expired' == $lstatus) {
                                                                $can_renew = get_directorist_option('can_renew_listing');
                                                                if (!$can_renew) return false;// vail if renewal option is turned off on the site.
                                                                ?>
                                                                <a href="<?= esc_url(ATBDP_Permalink::get_renewal_page_link($post->ID)) ?>"
                                                                   id="directorist-renew"
                                                                   data-listing_id="<?= $post->ID; ?>"
                                                                   class="directory_btn btn btn-default">
                                                                    <?php _e('Renew', ATBDP_TEXTDOMAIN); ?>
                                                                </a>
                                                                <!--@todo; add expiration and renew date-->
                                                            <?php } else {
                                                                // show promotions if the featured is available
                                                                // featured available but the listing is not featured, show promotion button
                                                                if ($featured_active && empty($featured)) {
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
                                                            $exp_date = get_post_meta($post->ID, '_expiry_date', true);
                                                            $never_exp = get_post_meta($post->ID, '_never_expire', true);
                                                            $exp_text = !empty($never_exp)
                                                                ? __('Never Expires', ATBDP_TEXTDOMAIN)
                                                                : date_i18n($date_format, strtotime($exp_date)); ?>
                                                            <p><?php printf(__('<span>Expiration:</span> %s', ATBDP_TEXTDOMAIN), $exp_text); ?></p>
                                                            <p><?php printf(__('<span>Listing Status:</span> %s', ATBDP_TEXTDOMAIN), get_post_status_object($post->post_status)->label); ?></p>
                                                            <?php
                                                            atbdp_display_price($price, $is_disable_price);
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
                                esc_html_e('Looks like you have not created any listing yet!', ATBDP_TEXTDOMAIN);
                            }
                            //@todo;add pagination on dashboard echo atbdp_pagination($listings, $paged);
                            ?>

                        </div> <!--ends #my_listings-->
                            <div role="tabpanel" class="tab-pane" id="profile">
                                <form action="#" id="user_profile_form" method="post">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 offset-sm-3 offset-md-0">
                                            <div class="user_pro_img_area">
                                                <div class="user_img" id="profile_pic_container">
                                                    <div class="cross" id="remove_pro_pic"><span class="fa fa-times"></span>
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

                                        <div class="col-md-8">
                                            <div class="profile_title"><h4><?php _e('My Profile', ATBDP_TEXTDOMAIN); ?></h4>
                                            </div>

                                            <div class="user_info_wrap">
                                                <!--hidden inputs-->
                                                <input type="hidden" name="ID" value="<?= get_current_user_id(); ?>">
                                                <!--Full name-->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="full_name">Full Name</label>
                                                            <input class="form-control" type="text" name="user[full_name]"
                                                                   value="<?= !empty($c_user->display_name) ? esc_attr($c_user->display_name) : ''; ?>"
                                                                   placeholder="<?php _e('Enter your full name', ATBDP_TEXTDOMAIN); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="user_name">User Name</label>
                                                            <input class="form-control" id="user_name" type="text"
                                                                   disabled="disabled" name="user[user_name]"
                                                                   value="<?= !empty($c_user->user_login) ? esc_attr($c_user->user_login) : ''; ?>"> <?php _e('(username can not be changed)', ATBDP_TEXTDOMAIN); ?>
                                                        </div>
                                                    </div>
                                                </div> <!--ends .row-->
                                                <!--First Name-->
                                                <div class="row">
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
                                                <div class="row">
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
                                                <div class="row">
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


                                                <div class="row">
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
                                                            <input id="confirm_pass" class="form-control" type="password"
                                                                   name="user[confirm_pass]"
                                                                   value="<?= !empty($confirm_pass) ? esc_attr($confirm_pass) : ''; ?>"
                                                                   placeholder="<?php _e('Confirm your new password', ATBDP_TEXTDOMAIN); ?>">
                                                        </div>
                                                    </div>
                                                </div><!--ends .row-->
                                                <!--social info-->
                                                <div class="row">
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
                                                            <label for="google"><?php _e('Google+', ATBDP_TEXTDOMAIN); ?></label>
                                                            <p><?php _e('Leave it empty to hide', ATBDP_TEXTDOMAIN) ?></p>
                                                            <input id="google" class="form-control" type="url"
                                                                   name="user[google]"
                                                                   value="<?= !empty($google) ? esc_attr($google) : ''; ?>"
                                                                   placeholder="<?php _e('Enter google+ url', ATBDP_TEXTDOMAIN); ?>">
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
                                </form>
                            </div>


                        <div role="tabpanel" class="tab-pane" id="saved_items">
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
                                            $category_icon = !empty($cats) ? esc_attr(get_cat_icon($cats[0]->term_id)) : 'fa fa-square-o';
                                            $category_link = !empty($cats) ? esc_url(ATBDP_Permalink::get_category_archive($cats[0])) : '#';
                                            $post_link = esc_url(get_post_permalink($post->ID));

                                            printf(' <tr>
                                            <td class="thumb_title">
                                                <div class="img_wrapper"><img
                                                            src=""
                                                            alt=""></div>
                                                <a href="%s"><h4>%s</h4></a>
                                            </td>

                                            <td class="saved_item_category">
                                                <a href="%s"><span class="fa %s"></span>%s</a>
                                            </td>
                                            
                                            
                                        </tr>', $post_link, $title, $category_link, $category_icon, $category_name, atbdp_get_remove_favourites_page_link($post->ID), __('Remove', ATBDP_TEXTDOMAIN));
                                        }
                                        ?>
                                        </tbody>
                                        <?php
                                    }else{
                                        printf('<p>%s</p>',__("No listing found !", ATBDP_TEXTDOMAIN));
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                        <?php
                        if (class_exists('ATBDP_Fee_Manager')){
                            ?>
                            <div role="tabpanel" class="tab-pane" id="manage_fees">
                                <div class="atbd_manage_fees_wrapper">
                                    <table class="table table-bordered atbd_single_saved_item table-responsive-sm">
                                        <div id="fm_plans_container">
                                            <?php
                                            ATBDP_Fee_Manager()->atfm_fees_for_listing_submit_frontend();
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="atbd_pricing_header">
                                                        <?php printf('<h3>%s</h3>', __('Upgrade/Downgrade your plan', ATBDP_TEXTDOMAIN)); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <?php
                                                $args = array(
                                                    'post_type'      => 'atbdp_fee_manager',
                                                    'posts_per_page' => -1,
                                                    'status'         => 'publish'
                                                );
                                                $atbdp_query = new WP_Query( $args );

                                                if ($atbdp_query->have_posts()){
                                                    global $post;
                                                    $plans = $atbdp_query->posts;
                                                    foreach ($plans as $key => $value){
                                                        $plan_id = $value->ID;
                                                        $unl = __( 'Unlimited', ATBDP_TEXTDOMAIN );
                                                        $fm_price = esc_attr(get_post_meta($plan_id, 'fm_price', true));
                                                        $price_decimal = esc_attr(get_post_meta($plan_id, 'price_decimal', true));
                                                        $fm_length = esc_attr(get_post_meta( $plan_id, 'fm_length', true ));
                                                        $fm_length_unl = esc_attr(get_post_meta( $plan_id, 'fm_length_unl', true ));
                                                        $num_regular = esc_attr(get_post_meta($plan_id, 'num_regular', true));
                                                        $num_regular_unl = esc_attr(get_post_meta($plan_id, 'num_regular_unl', true));
                                                        $num_featured = esc_attr(get_post_meta($plan_id, 'num_featured', true));
                                                        $num_featured_unl = esc_attr(get_post_meta($plan_id, 'num_featured_unl', true));
                                                        $price_range = esc_attr(get_post_meta($plan_id, 'price_range', true));
                                                        $price_range_unl = esc_attr(get_post_meta($plan_id, 'price_range_unl', true));
                                                        $num_image = esc_attr(get_post_meta($plan_id, 'num_image', true));
                                                        $num_image_unl = esc_attr(get_post_meta($plan_id, 'num_image_unl', true));
                                                        $fm_trail_p = esc_attr(get_post_meta($plan_id, 'fm_trail_p', true));
                                                        $fm_trail_p_unl = esc_attr(get_post_meta($plan_id, 'fm_trail_p_unl', true));
                                                        $business_hrs = esc_attr(get_post_meta($plan_id, 'business_hrs', true));
                                                        $stf_form = esc_attr(get_post_meta($plan_id, 'stf_form', true));
                                                        $l_video = esc_attr(get_post_meta($plan_id, 'l_video', true));
                                                        $cf_owner = esc_attr(get_post_meta($plan_id, 'cf_owner', true));
                                                        $fm_email = esc_attr(get_post_meta($plan_id, 'fm_email', true));
                                                        $fm_phone = esc_attr(get_post_meta($plan_id, 'fm_phone', true));
                                                        $fm_web_link = esc_attr(get_post_meta($plan_id, 'fm_web_link', true));
                                                        $fm_social_network = esc_attr(get_post_meta($plan_id, 'fm_social_network', true));
                                                        $fm_cs_review = esc_attr(get_post_meta($plan_id, 'fm_cs_review', true));
                                                        $fm_listing_faq = esc_attr(get_post_meta($plan_id, 'fm_listing_faq', true));
                                                        $exclude_cat = array(get_post_meta($plan_id, 'exclude_cat', true));
                                                        $fm_custom_field = esc_attr(get_post_meta($plan_id, 'fm_custom_field', true));
                                                        $fm_coupon_code = esc_attr(get_post_meta($plan_id, 'fm_coupon_code', true));
                                                        $default_pln = esc_attr(get_post_meta($plan_id, 'default_pln', true));
                                                        ?>

                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="pricing pricing--1 <?php if ($default_pln == 'yes'){echo 'atbd_pricing_special';}?> shadow-lg-2">
                                                                <div class="pricing__title">
                                                                    <h4><?php echo $value->post_title; ?><?php if ($default_pln == 'yes'){_e(' (Popular)', ATBDP_TEXTDOMAIN);}?></h4>
                                                                </div>

                                                                <div class="pricing__price rounded">
                                                                    <p><sup><?php echo atbdp_get_payment_currency(); ?></sup><?php echo $fm_price;if ($price_decimal)echo '.'.$price_decimal?><small>/<?php echo ($fm_length_unl) ? $unl : $fm_length;?> days</small></p>
                                                                </div>
                                                                <div class="pricing__features">
                                                                    <ul>
                                                                        <li><span class="fa fa-<?php if (($num_regular > 0) || $num_regular_unl ){echo 'check';}else{echo 'times';}?>"></span><?php echo $num_regular_unl ? '<span class="atbd_color-success">'.$unl.'</span>' : $num_regular; ?> Regular Listings</li>
                                                                        <li><span class="fa fa-<?php if (($num_featured > 0) || $num_featured_unl){echo 'check';}else{echo 'times';}?>"></span><?php echo $num_featured_unl ? '<span class="atbd_color-success">'.$unl.'</span>' : $num_featured; ?> Featured Listings</li>
                                                                        <li><span class="fa fa-<?php if (($price_range > 0) || $price_range_unl){echo 'check';}else{echo 'times';}?>"></span><?php echo $price_range_unl ? '<span class="atbd_color-success">'.$unl.'</span>' : $price_range; ?> Price Range</li>
                                                                        <li><span class="fa fa-<?php if (($num_image > 0) || $num_image_unl){echo 'check';}else{echo 'times';}?>"></span><?php echo $num_image_unl ? '<span class="atbd_color-success">'.$unl.'</span>' : $num_image; ?> Listing Image</li>
                                                                        <li><span class="fa fa-<?php if (($fm_trail_p > 0) || $fm_trail_p_unl){echo 'check';}else{echo 'times';}?>"></span><?php echo $fm_trail_p_unl ? '<span class="atbd_color-success">'.$unl.'</span>' : $fm_trail_p; ?> Days Trail</li>
                                                                        <?php
                                                                        if (class_exists('BD_Business_Hour')){
                                                                            ?>
                                                                            <li><span class="fa fa-<?php if ($business_hrs == 'yes'){echo 'check';}else{echo 'times';}?>"> </span>Allow business hours</li>
                                                                        <?php } ?>
                                                                        <li><span class="fa fa-<?php if ($stf_form == 'yes'){echo 'check';}else{echo 'times';}?>"> </span>Allow send to friend</li>
                                                                        <li><span class="fa fa-<?php if ($l_video == 'yes'){echo 'check';}else{echo 'times';}?>"> </span>Listing video</li>
                                                                        <li><span class="fa fa-<?php if ($cf_owner == 'yes'){echo 'check';}else{echo 'times';}?>"> </span>Contact owner</li>
                                                                        <li><span class="fa fa-<?php if ($fm_email == 'yes'){echo 'check';}else{echo 'times';}?>"> </span>Show email</li>
                                                                        <li><span class="fa fa-<?php if ($fm_phone == 'yes'){echo 'check';}else{echo 'times';}?>"> </span>Show contact number</li>
                                                                        <li><span class="fa fa-<?php if ($fm_web_link == 'yes'){echo 'check';}else{echo 'times';}?>"> </span>Show web link</li>
                                                                        <li><span class="fa fa-<?php if ($fm_social_network == 'yes'){echo 'check';}else{echo 'times';}?>"> </span>Show social network</li>
                                                                        <li><span class="fa fa-<?php if ($fm_cs_review == 'yes'){echo 'check';}else{echo 'times';}?>"> </span>Allow customer review</li>
                                                                        <li><span class="fa fa-<?php if ($fm_listing_faq == 'yes'){echo 'check';}else{echo 'times';}?>"> </span>Listing FAQs</li>
                                                                        <li><span class="fa fa-<?php if (empty($exclude_cat[0])){echo 'check';}else{echo 'times';}?>"> </span>All categories</li>
                                                                        <li><span class="fa fa-<?php if ($fm_custom_field == 'yes'){echo 'check';}else{echo 'times';}?>"> </span>Custom field</li>
                                                                        <li><span class="fa fa-<?php if ($fm_coupon_code == 'yes'){echo 'check';}else{echo 'times';}?>"> </span>Promo coupon</li>

                                                                    </ul>
                                                                    <div>
                                                                        <form method="post" action="<?= esc_url(ATBDP_Permalink::get_fee_renewal_checkout_page_link($post->ID)) ?>">
                                                                            <input id="fee_plans[<?php echo $value->ID; ?>]" type="hidden" value="<?php echo $value->ID; ?>" name="fm_plan_id_updated">
                                                                            <label  for="fee_plans[<?php echo $value->ID; ?>]"><input type="submit" name="fm_plans_updated" value="<?php _e('Get This Plan', ATBDP_TEXTDOMAIN) ?>"></label>

                                                                        </form>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }}
                                                ?>
                                            </div> <!--ends. row-->
                                        </div> <!--ends. fm_plans_container-->
                                    </table>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

