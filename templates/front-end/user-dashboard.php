<?php
$listings =  ATBDP()->user->current_user_listings();
$uid = get_current_user_id();
$c_user = get_userdata($uid);

$u_website= $c_user->user_url;
$avatar= get_user_meta($uid, 'avatar', true);
$u_phone= get_user_meta($uid, 'phone', true);
$u_pro_pic= get_user_meta($uid, 'pro_pic', true);
$u_address= get_user_meta($uid, 'address', true);
$date_format = get_option( 'date_format' );
$featured_active = get_directorist_option('enable_featured_listing');
$is_disable_price = get_directorist_option('disable_list_price');
/*@todo; later show featured listing first on the user dashboard maybe??? */

?>
<div class="directorist directory_wrapper single_area">
    <div class="<?php echo is_directoria_active() ? 'container': 'container-fluid'; ?>">
        <div class="row">
            <div class="col-md-12">

                <div class="add_listing_title">
                    <h2><?php _e('My Dashboard', ATBDP_TEXTDOMAIN); ?></h2>
                </div> <!--ends add_listing_title-->

                <div class="dashboard_wrapper">
                    <div class="dashboard_nav">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active" >
                                <a href="#my_listings" aria-controls="my_listings" role="tab" data-toggle="tab">
                                    <?php $list_found = ($listings->found_posts > 0) ? $listings->found_posts : '0';
                                    printf(__('My Listing (%s)', ATBDP_TEXTDOMAIN), $list_found); ?>
                                </a>
                            </li>
                            <li role="presentation" ><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><?php _e('My Profile', ATBDP_TEXTDOMAIN);?></a></li>
                        </ul>

                        <div class="nav_button">
                            <a href="<?= esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>" class="<?= atbdp_directorist_button_classes(); ?>"><?php _e('Submit New Listing', ATBDP_TEXTDOMAIN); ?></a>
                            <a href="<?= esc_url(wp_logout_url());?>" class="<?= atbdp_directorist_button_classes(); ?>"><?php _e('Log Out', ATBDP_TEXTDOMAIN); ?></a>
                        </div>
                    </div> <!--ends dashboard_nav-->

<<<<<<< HEAD
=======

>>>>>>> fb54a492e274b90099672a6d5ee6306854a796cb
                    <!-- Tab panes -->
                    <div class="tab-content row">
                        <div role="tabpanel" class="tab-pane active" data-uk-grid id="my_listings">
                            <?php if ($listings->have_posts()) {
                                foreach ($listings->posts as $post) {
                                    $info = ATBDP()->metabox->get_listing_info($post->ID); // get all post meta and extract it.
                                    extract($info);
                                    // get only one parent or high level term object
                                    $top_category = ATBDP()->taxonomy->get_one_high_level_term($post->ID, ATBDP_CATEGORY);
                                    $price= get_post_meta($post->ID, '_price', true);
                                    $featured = get_post_meta(get_the_ID(), '_featured', true);

                                    ?>
                                    <div class="col-lg-4 col-sm-6" id="listing_id_<?= $post->ID; ?>">
                                        <div class="single_directory_post dashboard_listing <?php echo $featured_active ? (!empty($featured) ? 'featured': ''):''?>">
                                            <article>
                                                <figure>
                                                    <div class="post_img_wrapper">
                                                        <img src="<?= (!empty($attachment_id[0]))  ? wp_get_attachment_image_url($attachment_id[0], array(432,400)) : '' ?>" alt="Image">
                                                    </div>
                                                </figure> <!--ends figure-->

                                                <div class="article_content">
                                                    <div class="content_upper">
                                                        <h4 class="post_title"><a href="<?= get_post_permalink($post->ID); ?>"><?= !empty($post->post_title)? esc_html(stripslashes($post->post_title)): ''; ?></a></h4>                                                        <p><?= (!empty($tagline)) ? esc_html(stripslashes($tagline)): '' ?></p>
                                                        <?php

                                                        /**
                                                         * Fires after the title and sub title of the listing is rendered on the single listing page
                                                         *
                                                         *
                                                         * @since 1.0.0
                                                         */

                                                        do_action('atbdp_after_listing_tagline');

                                                        echo $featured_active ? (!empty($featured) ? '<p class="featured_ribbon">Featured</p>': ''):'';
                                                        ?>

                                                    </div> <!--ends .content_upper-->

                                                    <div class="db_btn_area">
                                                        <?php
                                                        $lstatus = get_post_meta($post->ID, '_listing_status', true);
                                                        // If the listing needs renewal then there is no need to show promote button
                                                        if ('renewal' == $lstatus || 'expired' == $lstatus){
                                                            $can_renew = get_directorist_option('can_renew_listing');
                                                            if (!$can_renew) return false;// vail if renewal option is turned off on the site.
                                                            ?>
                                                            <a href="<?= esc_url(ATBDP_Permalink::get_renewal_page_link($post->ID)) ?>"
                                                               id="directorist-renew" data-listing_id="<?= $post->ID; ?>"
                                                               class="directory_btn btn btn-default">
                                                                <?php _e('Renew', ATBDP_TEXTDOMAIN); ?>
                                                            </a>
                                                            <!--@todo; add expiration and renew date-->
                                                        <?php }else{
                                                            // show promotions if the featured is available
                                                            // featured available but the listing is not featured, show promotion button
                                                            if ($featured_active && empty($featured)){
                                                                ?>
                                                                <a href="<?= esc_url(ATBDP_Permalink::get_checkout_page_link($post->ID)) ?>"
                                                                   id="directorist-promote" data-listing_id="<?= $post->ID; ?>"
                                                                   class="directory_btn btn btn-default">
                                                                    <?php _e('Promote', ATBDP_TEXTDOMAIN); ?>
                                                                </a>
                                                            <?php }
                                                        } ?>

                                                        <a href="<?= esc_url(ATBDP_Permalink::get_edit_listing_page_link($post->ID)); ?>" id="edit_listing" class="directory_edit_btn btn btn-default"><?php _e('Edit Listing', ATBDP_TEXTDOMAIN); ?></a>
                                                        <a href="#" id="remove_listing" data-listing_id="<?= $post->ID; ?>" class="directory_remove_btn btn btn-default"><?php _e('Delete', ATBDP_TEXTDOMAIN); ?></a>
                                                    </div> <!--ends .db_btn_area-->

                                                    <div class="listing-meta db_btn_area">
                                                        <?php
                                                        $exp_date           = get_post_meta($post->ID, '_expiry_date', true);
                                                        $never_exp           = get_post_meta($post->ID, '_never_expire', true);
                                                        $exp_text = ! empty( $never_exp ) ? __( 'Never Expires', ATBDP_TEXTDOMAIN ) :  date_i18n( $date_format, strtotime( $exp_date ) ); ?>
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
                                                </div> <!--ends .article_content-->
                                            </article> <!--ends article-->
                                        </div> <!--ends .single_directory_post-->
                                    </div> <!--ends . col-lg-3 col-sm-6-->
                                    <?php
                                }
                            }else{
                                esc_html_e('Looks like you have not created any listing yet!', ATBDP_TEXTDOMAIN);
                                ?>

                                <?php
                            }

                            // echo atbdp_pagination($listings, $paged);

                            ?>

                        </div> <!--ends #my_listings-->
                        <div role="tabpanel" class="tab-pane" id="profile">

                            <form action="#" id="user_profile_form" method="post">
                                <div class="col-md-4">
                                    <div class="user_pro_img_area">
                                        <div class="user_img" id="profile_pic_container">
                                            <div class="cross" id="remove_pro_pic"><span class="fa fa-times"></span></div>
                                            <div class="choose_btn">
                                                <input type="hidden" name="user[pro_pic]" id="pro_pic" value="<?= !empty($u_pro_pic) ? esc_url($u_pro_pic) : ''; ?>">
                                                <label for="pro_pic" id="upload_pro_pic"><?php _e('Change', ATBDP_TEXTDOMAIN); ?></label>
                                            </div> <!--ends .choose_btn-->
                                            <img src="<?= !empty($u_pro_pic) ? esc_url($u_pro_pic) : esc_url(ATBDP_PUBLIC_ASSETS.'images/no-image.jpg'); ?>" id="pro_img" alt="">
                                        </div> <!--ends .user_img-->
                                    </div> <!--ends .user_pro_img_area-->
                                </div> <!--ends .col-md-4-->

                                <div class="col-md-8">
                                    <div class="profile_title"><h4><?php _e('My Profile', ATBDP_TEXTDOMAIN); ?></h4></div>

                                    <div class="user_info_wrap">
                                        <!--hidden inputs-->
                                        <input type="hidden" name="ID" value="<?= get_current_user_id(); ?>">
                                        <!--Full name-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="full_name">Full Name</label>
                                                <input class="directory_field" type="text" name="user[full_name]" value="<?= !empty($c_user->display_name)? esc_attr($c_user->display_name):'';?>" placeholder="Enter your full name">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="user_name">User Name</label>
                                                <input class="directory_field" id="user_name" type="text" disabled="disabled" name="user[user_name]" value="<?= !empty($c_user->user_login)? esc_attr($c_user->user_login):'';?>"> <?php _e('(username can not be changed)', ATBDP_TEXTDOMAIN); ?>
                                            </div>
                                        </div> <!--ends .row-->
                                        <!--First Name-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="first_name"><?php _e('First Name', ATBDP_TEXTDOMAIN); ?></label>
                                                <input class="directory_field" id="first_name" type="text" name="user[first_name]" value="<?= !empty($c_user->first_name)? esc_attr($c_user->first_name):'';?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="last_name"><?php _e('Last Name', ATBDP_TEXTDOMAIN); ?></label>
                                                <input class="directory_field" id="last_name" type="text" name="user[last_name]" value="<?= !empty($c_user->last_name)? esc_attr($c_user->last_name):'';?>">
                                            </div>
                                        </div> <!--ends .row-->
                                        <!--Email-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="req_email"><?php _e('Email (required)', ATBDP_TEXTDOMAIN); ?></label>
                                                <input class="directory_field" id="req_email" type="text" name="user[user_email]" value="<?= !empty($c_user->user_email)? esc_attr($c_user->user_email):'';?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="phone"><?php _e('Cell Number', ATBDP_TEXTDOMAIN); ?></label>
                                                <input class="directory_field" type="tel" name="user[phone]" value="<?= !empty($u_phone)? esc_attr($u_phone):'';?>" placeholder="Enter your phone number">
                                            </div>
                                        </div> <!--ends .row-->
                                        <!--Website-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="website"><?php _e('Website', ATBDP_TEXTDOMAIN); ?></label>
                                                <input class="directory_field" id="website" type="text" name="user[website]" value="<?= !empty($u_website) ? esc_url($u_website):'';?>" >
                                            </div>
                                            <div class="col-md-6">
                                                <label for="address"><?php _e('Address', ATBDP_TEXTDOMAIN); ?></label>
                                                <input class="directory_field" id="address" type="text" name="user[address]" value="<?= !empty($u_address)? esc_attr($u_address):'';?>">
                                            </div>
                                        </div> <!--ends .row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="new_pass"><?php _e('New Password', ATBDP_TEXTDOMAIN); ?></label>
                                                <input class="directory_field" type="password" name="user[new_pass]" value="<?= !empty($new_pass)? esc_attr($new_pass):'';?>" placeholder="Enter a new password" >
                                            </div>
                                            <div class="col-md-6">
                                                <label for="confirm_pass"><?php _e('Confirm New Password', ATBDP_TEXTDOMAIN); ?></label>
                                                <input class="directory_field" type="password" name="user[confirm_pass]" value="<?= !empty($confirm_pass)? esc_attr($confirm_pass):'';?>" placeholder="Confirm your new password" >
                                            </div>
                                        </div><!--ends .row-->


                                        <button type="submit" class="btn btn-primary" id="update_user_profile"><?php _e('Save Changes', ATBDP_TEXTDOMAIN); ?></button>
                                    </div>
                                </div>
                            </form>
                            <div id="pro_notice" style="display: inline-block; padding: 20px">

                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>