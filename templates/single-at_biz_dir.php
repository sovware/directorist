<?php
global $post;
$listing_id = $post->ID;
/**
 * @since 5.10.0
 * It fires before single listing load
 */
do_action('atbdp_before_single_listing_load', $listing_id);
$fm_plan = get_post_meta($listing_id, '_fm_plans', true);
/*store all data in an array so that we can pass it to filters for extension to get this value*/
$listing_info['never_expire'] = get_post_meta($post->ID, '_never_expire', true);
$listing_info['featured'] = get_post_meta($post->ID, '_featured', true);
$listing_info['price'] = get_post_meta($post->ID, '_price', true);
$listing_info['price_range'] = get_post_meta($post->ID, '_price_range', true);
$listing_info['atbd_listing_pricing'] = get_post_meta($post->ID, '_atbd_listing_pricing', true);
$listing_info['videourl'] = get_post_meta($post->ID, '_videourl', true);
$listing_info['listing_status'] = get_post_meta($post->ID, '_listing_status', true);
$listing_info['tagline'] = get_post_meta($post->ID, '_tagline', true);
$listing_info['excerpt'] = get_post_meta($post->ID, '_excerpt', true);
$listing_info['address'] = get_post_meta($post->ID, '_address', true);
$listing_info['phone'] = get_post_meta($post->ID, '_phone', true);
$listing_info['phone2'] = get_post_meta($post->ID, '_phone2', true);
$listing_info['fax'] = get_post_meta($post->ID, '_fax', true);
$listing_info['email'] = get_post_meta($post->ID, '_email', true);
$listing_info['website'] = get_post_meta($post->ID, '_website', true);
$listing_info['zip'] = get_post_meta($post->ID, '_zip', true);
$listing_info['social'] = get_post_meta($post->ID, '_social', true);
$listing_info['faqs'] = get_post_meta($post->ID, '_faqs', true);
$listing_info['manual_lat'] = get_post_meta($post->ID, '_manual_lat', true);
$listing_info['manual_lng'] = get_post_meta($post->ID, '_manual_lng', true);
$listing_info['hide_map'] = get_post_meta($post->ID, '_hide_map', true);
$listing_info['listing_img'] = get_post_meta($post->ID, '_listing_img', true);
$listing_info['listing_prv_img'] = get_post_meta($post->ID, '_listing_prv_img', true);
$listing_info['hide_contact_info'] = get_post_meta($post->ID, '_hide_contact_info', true);
$listing_info['hide_contact_owner'] = get_post_meta($post->ID, '_hide_contact_owner', true);
$listing_info['expiry_date'] = get_post_meta($post->ID, '_expiry_date', true);
$display_prv_image = get_directorist_option('dsiplay_prv_single_page', 1);
$display_slider_image = get_directorist_option('dsiplay_slider_single_page', 1);
$custom_gl_width = get_directorist_option('gallery_crop_width', 670);
$custom_gl_height = get_directorist_option('gallery_crop_height', 750);
$select_listing_map = get_directorist_option('select_listing_map', 'google');
$enable_review = get_directorist_option('enable_review', 'yes');
$cats = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
$locs = get_the_terms(get_the_ID(), ATBDP_LOCATION);
$tags = get_the_terms(get_the_ID(), ATBDP_TAGS);
$font_type = get_directorist_option('font_type', 'line');
$fa_or_la = ('line' == $font_type) ? "la " : "fa ";
if (!empty($cats)) {
    $cat_icon = get_cat_icon($cats[0]->term_id);
}
$cat_icon = !empty($cat_icon) ? $cat_icon : 'fa-map-marker';
$icon_type = substr($cat_icon, 0, 2);
$fa_or_la = ('la' == $icon_type) ? "la " : "fa ";
$cat_icon = ('none' == $cat_icon) ? 'fa fa-map-marker' : $fa_or_la . $cat_icon;
$display_thumbnail_img = get_directorist_option('dsiplay_thumbnail_img', 1);
extract($listing_info);
/*Prepare Listing Image links*/
/*Code for Business Hour Extensions*/
$text247 = get_directorist_option('text247', __('Open 24/7', 'directorist')); // text for 24/7 type listing
$business_hour_title = get_directorist_option('business_hour_title', __('Business Hour', 'directorist')); // text Business Hour Title

$bdbh = get_post_meta($listing_id, '_bdbh', true);
$enable247hour = get_post_meta($listing_id, '_enable247hour', true);
$disable_bz_hour_listing = get_post_meta($listing_id, '_disable_bz_hour_listing', true);
$business_hours = !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array(); // arrays of days and times if exist

/*Code for Business Hour Extensions*/
$manual_lat = (!empty($manual_lat)) ? floatval($manual_lat) : false;
$manual_lng = (!empty($manual_lng)) ? floatval($manual_lng) : false;
$hide_contact_info = !empty($hide_contact_info) ? $hide_contact_info : false;
$hide_contact_owner = !empty($hide_contact_owner) ? $hide_contact_owner : false;

/*INFO WINDOW CONTENT*/
$t = get_the_title();
$t = !empty($t) ? $t : __('No Title', 'directorist');

$average = ATBDP()->review->get_average($listing_id);
$reviews_count = ATBDP()->review->db->count(array('post_id' => $post->ID)); // get total review count for this post
$reviews = (($reviews_count > 1) || ($reviews_count === 0)) ? __(' Reviews', 'directorist') : __(' Review', 'directorist');
$review_info = '';
$review_info = '';
if (!empty($enable_review)) {
    $review_info = "<div class='miwl-rating'><span class='atbd_meta atbd_listing_rating'>$average<i class='" . atbdp_icon_type() . "-star'></i></span>";

    $review_info .= "<div class='atbd_rating_count'>";

    $review_info .= "<p>" . $reviews_count . $reviews . "</p>";

    $review_info .= "</div></div>";
}

$tg = !empty($tagline) ? esc_html($tagline) : '';
$ad = !empty($address) ? esc_html($address) : '';
$default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
$listing_prv_imgurl = atbdp_image_cropping($listing_prv_img, 150, 150, true, 100)['url'];
$listing_prv_imgurl = is_string( $listing_prv_imgurl ) ? $listing_prv_imgurl : '';

$img_url = ! empty($listing_prv_imgurl) ? $listing_prv_imgurl : $default_image;
$image = "<img src=" . $img_url . "\>";
$display_map_info = apply_filters('atbdp_listing_map_info_window', get_directorist_option('display_map_info', 1));
$display_image_map = get_directorist_option('display_image_map', 1);
$display_title_map = get_directorist_option('display_title_map', 1);
$display_address_map = get_directorist_option('display_address_map', 1);
$display_direction_map = get_directorist_option('display_direction_map', 1);
if (empty($display_image_map)) {
    $image = '';
}
if (empty($display_title_map)) {
    $t = '';
}
$info_content = "";
if (!empty($display_image_map) || !empty($display_title_map)) {
    $info_content .= "<div class='map-info-wrapper'><div class='map-info-img'>$image</div><div class='map-info-details'><div class='atbdp-listings-title-block'><h3>$t</h3></div>";
}
if (!empty($display_address_map) && !empty($ad)) {
    $info_content .= apply_filters("atbdp_address_in_map_info_window", "<address>{$ad}</address>");
}
if (!empty($display_direction_map)) {
    $info_content .= "<div class='map_get_dir'><a href='http://www.google.com/maps?daddr={$manual_lat},{$manual_lng}' target='_blank'> " . __('Get Direction', 'directorist') . "</a></div><span id='iw-close-btn'><i class='la la-times'></i></span></div></div>";
}
/*END INFO WINDOW CONTENT*/
$map_zoom_level = get_directorist_option('map_zoom_level', 16);
$disable_map = get_directorist_option('disable_map', 0);
$disable_sharing = get_directorist_option('disable_sharing', 0);
$is_info = get_directorist_option('disable_contact_info', 0);
$disable_contact_info = apply_filters('atbdp_single_listing_contact_info', $is_info);
$disable_contact_owner = get_directorist_option('disable_contact_owner', 1);
$is_disable_price = get_directorist_option('disable_list_price');
$enable_social_share = get_directorist_option('enable_social_share', 1);
$enable_favourite = get_directorist_option('enable_favourite', 1);
$enable_report_abuse = get_directorist_option('enable_report_abuse', 1);
$enable_video_url = get_directorist_option('atbd_video_url', 1);
$video_label = get_directorist_option('atbd_video_title', __('Video', 'directorist'));
$p_lnk = get_the_permalink();
$p_title = get_the_title();
$featured = get_post_meta(get_the_ID(), '_featured', true);
$reviews_count = ATBDP()->review->db->count(array('post_id' => $listing_id)); // get total review count for this post
$listing_author_id = get_post_field('post_author', $listing_id);
$display_feature_badge_single = get_directorist_option('display_feature_badge_cart', 1);
$display_popular_badge_single = get_directorist_option('display_popular_badge_cart', 1);
$popular_badge_text = get_directorist_option('popular_badge_text', 'Popular');
$feature_badge_text = get_directorist_option('feature_badge_text', 'Feature');
$new_badge_text = get_directorist_option('new_badge_text', 'New');
$enable_new_listing = get_directorist_option('display_new_badge_cart', 1);
$use_nofollow = get_directorist_option('use_nofollow');
$tags_section_lable = get_directorist_option('tags_section_lable', __('Tags', 'directorist'));
$custom_section_lable = get_directorist_option('custom_section_lable', __('Details', 'directorist'));
$listing_details_text = get_directorist_option('listing_details_text', __('Listing Details', 'directorist'));
$listing_details_text = apply_filters('atbdp_single_listing_details_section_text', $listing_details_text);
$listing_location_text = get_directorist_option('listing_location_text', __('Location', 'directorist'));
$listing_location_text = apply_filters('atbdp_single_listing_map_section_text', $listing_location_text);
$contact_info_text = get_directorist_option('contact_info_text', __('Contact Information', 'directorist'));
$contact_listing_owner = get_directorist_option('contact_listing_owner', __('Contact Listing Owner', 'directorist'));
$display_tagline_field = get_directorist_option('display_tagline_field', 0);
$display_pricing_field = get_directorist_option('display_pricing_field', 1);
$display_address_field = get_directorist_option('display_address_field', 1);
$address_map_link = get_directorist_option('address_map_link', 0);
$display_phone_field = get_directorist_option('display_phone_field', 1);
$display_phone2_field = get_directorist_option('display_phone_field2', 1);
$phone_label2 = get_directorist_option('phone_label2', __('Phone Number 2', 'directorist'));
$display_fax_field = get_directorist_option('display_fax', 1);
$fax_label = get_directorist_option('fax_label', __('Fax', 'directorist'));
$display_email_field = get_directorist_option('display_email_field', 1);
$display_website_field = get_directorist_option('display_website_field', 1);
$display_zip_field = get_directorist_option('display_zip_field', 1);
$display_social_info_field = get_directorist_option('display_social_info_field', 1);
$display_social_info_for = get_directorist_option('display_social_info_for', 'admin_users');
$display_map_field = get_directorist_option('display_map_field', 1);
$display_map_field = apply_filters('atbdp_show_single_listing_map', $display_map_field);
$display_video_for = get_directorist_option('display_video_for', 'admin_users');
$preview_enable = get_directorist_option('preview_enable', 1);
$display_back_link = get_directorist_option('display_back_link', 1);
$enable_single_location_taxonomy = get_directorist_option('enable_single_location_taxonomy', 0);
$enable_single_tag = get_directorist_option('enable_single_tag', 1);
$pending_msg = get_directorist_option('pending_confirmation_msg', __( 'Thank you for your submission. Your listing is being reviewed and it may take up to 24 hours to complete the review.', 'directorist' ) );
$publish_msg = get_directorist_option('publish_confirmation_msg', __( 'Congratulations! Your listing has been approved/published. Now it is publicly available.', 'directorist' ) );
$new_listing_status = get_directorist_option('new_listing_status', 'pending' );
$edit_listing_status = get_directorist_option('edit_listing_status', 'pending' );
if( isset( $_GET['edited'] ) && ( $_GET['edited'] === '1' ) ) {
    $confirmation_msg = $edit_listing_status === 'publish' ? $publish_msg : $pending_msg;
}else{
    $confirmation_msg = $new_listing_status === 'publish' ? $publish_msg : $pending_msg; 
}
$main_col_size = is_active_sidebar('right-sidebar-listing') ? 'col-lg-8' : 'col-lg-12';
$active_sidebar = is_active_sidebar('right-sidebar-listing') ? true : false;
$class = isset($_GET['redirect']) ? 'atbdp_float_active' : 'atbdp_float_none';
?>
<section id="directorist" class="directorist atbd_wrapper">
    <div class="row">
        <?php
        if( isset( $_GET['notice'] ) ){ ?>
            <div class="col-lg-12">
                <div class="alert alert-info alert-dismissible fade show" role="alert" style="width: 100%">
                    <?php echo $confirmation_msg; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <?php }
        $html_edit_back = '';
        $html_edit_back .= '<div class="' . apply_filters('atbdp_single_listing_sidebar_class', esc_attr($main_col_size)) . ' col-md-12 atbd_col_left">';
        //is current user is logged in and the original author of the listing
        if (atbdp_logged_in_user() && $listing_author_id == get_current_user_id()) {
            //ok show the edit option
            $html_edit_back .= '<div class="edit_btn_wrap">';
            if (!empty($display_back_link)) {
                if (!isset($_GET['redirect'])) {
                    $html_edit_back .= '<a href="javascript:history.back()" class="atbd_go_back"><i class="' . atbdp_icon_type() . '-angle-left"></i>' . esc_html__(' Go Back', 'directorist') . '</a> ';
                }
            }
            $html_edit_back .= '<div class="' . $class . '">';
            $html_edit_back .= atbdp_get_preview_button();
            $payment = isset($_GET['payment']) ? $_GET['payment'] : '';
            $url = isset($_GET['redirect']) ? $_GET['redirect'] : '';
            $edit_link = !empty($payment) ? add_query_arg('redirect', $url, ATBDP_Permalink::get_edit_listing_page_link($post->ID)) : ATBDP_Permalink::get_edit_listing_page_link($post->ID);
            $html_edit_back .= '<a href="' . esc_url($edit_link) . '" class="btn btn-outline-light">
                            <span class="' . atbdp_icon_type() . '-edit"></span>' . apply_filters('atbdp_listing_edit_btn_text', esc_html__(' Edit', 'directorist')) . '</a>';

            $html_edit_back .= '</div>';
            $html_edit_back .= '</div>';
        } else {
            if (!empty($display_back_link)) {
                $html_edit_back .= '<div class="edit_btn_wrap">
                                <a href="javascript:history.back()" class="atbd_go_back">
                                    <i class="' . atbdp_icon_type() . '-angle-left"></i>' . esc_html__(' Go Back', 'directorist') . '
                                </a>
                           </div>';
            }
        }
        $html_edit_back .= '</div>';

        /**
         * @since 5.5.4
         */
        echo apply_filters('atbdp_single_listing_edit_back', $html_edit_back);


        /**
         * @since 5.0
         */
        do_action('atbdp_before_single_listing_section');
        ?>
        <div class="<?php echo apply_filters('atbdp_single_listing_sidebar_class', esc_attr($main_col_size)); ?> col-md-12 atbd_col_left">
            <?php
            /**
             * @since 5.2.1
             */
            do_action('atbdp_before_single_listing_details_section');
            ?>
            <div class="atbd_content_module atbd_listing_details <?php do_action('atbdp_single_listing_details_class') ?>">
                <div class="atbd_content_module_title_area">
                    <?php if (!empty($listing_details_text)) { ?>
                        <div class="atbd_area_title">
                            <h4>
                                <span class="<?php atbdp_icon_type(true); ?>-file-text atbd_area_icon"></span><?php _e($listing_details_text, 'directorist') ?>
                            </h4>
                        </div>
                    <?php }
                    ob_start();
                    ?>
                    <div class="atbd_listing_action_area">
                        <?php
                        /**
                         * @since 6.4.4
                         */
                        do_action( 'atbdp_single_listing_before_favourite_icon' );
                        if ($enable_favourite) { ?>
                            <div class="atbd_action atbd_save atbd_tooltip" id="atbdp-favourites" aria-label="<?php _e( 'Favorite', 'directorist' ) ?>">
                                <?php echo the_atbdp_favourites_link(); ?>
                            </div>
                        <?php }
                        if ($enable_social_share) { ?>
                        <div class="atbd_action atbd_share atbd_tooltip" aria-label="<?php _e( 'Share', 'directorist' ) ?>">
                            <span class="<?php atbdp_icon_type(true); ?>-share"></span>
                            <div class="atbd_directory_social_wrap">
                                <?php
                                $twt_lnk = 'https://twitter.com/intent/tweet?text=' . $p_title . '&amp;url=' . $p_lnk;
                                $fb_lnk = "https://www.facebook.com/share.php?u={$p_lnk}&title={$p_title}";
                                $in_link = "http://www.linkedin.com/shareArticle?mini=true&url={$p_lnk}&title={$p_title}";
                                ?>
                                <ul>
                                    <li>
                                        <a target="_blank" href="<?php echo esc_url( $fb_lnk ); ?>"><span class="<?php atbdp_icon_type( true ); ?>-facebook"></span> <?php esc_html_e('Facebook', 'directorist'); ?></a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="<?php echo esc_url( $twt_lnk ); ?>"><span class="<?php atbdp_icon_type( true ); ?>-twitter"></span> <?php esc_html_e('Twitter', 'directorist'); ?></a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="<?php echo esc_url( $in_link ); ?>"><span class="<?php atbdp_icon_type( true ); ?>-linkedin"></span> <?php esc_html_e('LinkedIn', 'directorist'); ?></a>
                                    </li>
                                </ul>
                            </div>

                        </div>
                        <?php }
                        if ($enable_report_abuse) {
                            $public_report = apply_filters('atbdp_allow_public_report', false);
                            ?>
                            <div class="atbd_action atbd_report atbd_tooltip" aria-label="<?php _e( 'Report', 'directorist' ) ?>">
                                <?php
                                if(atbdp_logged_in_user() || $public_report){?>
                                    <a href="javascript:void(0)" data-target="atbdp-report-abuse-modal" class="atbdp-report-abuse-modal">
                                    <span class="<?php atbdp_icon_type( true ); ?>-flag"></span>
                                    </a>
                               <?php }else{?>
                                <a href="javascript:void(0)" class="atbdp-require-login">
                                    <span class="<?php atbdp_icon_type( true ); ?>-flag"></span>
                                </a>
                                <?php }
                                ?>
                                    <input type="hidden" id="atbdp-post-id" value="<?php echo get_the_id(); ?>"/>
                            </div>
                        <?php } ?>
                    </div>
                    <?php
                    $listing_header = ob_get_clean(); ?>
                    <div class="at-modal atm-fade" id="atbdp-report-abuse-modal">
                        <div class="at-modal-content at-modal-md">
                            <div class="atm-contents-inner">
                                <a href="" class="at-modal-close"><span aria-hidden="true">&times;</span></a>
                                <div class="row align-items-center">
                                    <div class="col-lg-12">
                                        <form id="atbdp-report-abuse-form" class="form-vertical" role="form">
                                            <div class="modal-header">
                                                <h3 class="modal-title" id="atbdp-report-abuse-modal-label"><?php _e('Report Abuse', 'directorist'); ?></h3>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="atbdp-report-abuse-message"><?php _e('Your Complaint', 'directorist'); ?>
                                                        <span class="atbdp-star">*</span></label>
                                                    <textarea class="form-control" id="atbdp-report-abuse-message" rows="3" placeholder="<?php _e('Message', 'directorist'); ?>..." required></textarea>
                                                </div>
                                                <div id="atbdp-report-abuse-g-recaptcha"></div>
                                                <div id="atbdp-report-abuse-message-display"></div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary"><?php _e('Submit', 'directorist'); ?></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <?php
                    /**
                     * @since 5.0
                     */
                    echo apply_filters('atbdp_header_before_image_slider', $listing_header);
                    ?>
                </div>
                <div class="atbdb_content_module_contents">
                    <?php
                    $slider = get_plasma_slider();
                    echo apply_filters('atbdp_single_listing_gallery_section', $slider);
                    ?>
                    <div class="atbd_listing_detail">
                        <?php
                        $plan_average_price = true;
                        if (is_fee_manager_active()) {
                            $plan_average_price = is_plan_allowed_average_price_range($fm_plan);
                        }
                        $plan_price = true;
                        if (is_fee_manager_active()) {
                            $plan_price = is_plan_allowed_price($fm_plan);
                        }
                        $data_info = '<div class="atbd_data_info">';
                        if (!empty($enable_review) || (empty($is_disable_price) && (!empty($price) || !empty($price_range)))) {
                            $data_info .= '<div class="atbd_listing_meta">';
                            $atbd_listing_pricing = !empty($atbd_listing_pricing) ? $atbd_listing_pricing : '';
                            if (empty($is_disable_price)) {
                                if (!empty($display_pricing_field)) {
                                    if (!empty($price_range) && ('range' === $atbd_listing_pricing) && $plan_average_price) {
                                        //is range selected then print it
                                        $output = atbdp_display_price_range($price_range);
                                        $data_info .= $output;
                                    } elseif ($plan_price) {
                                        $data_info .= atbdp_display_price($price, $is_disable_price, $currency = null, $symbol = null, $c_position = null, $echo = false);
                                    }
                                }
                            }
                            do_action('atbdp_after_listing_price');
                            $average = ATBDP()->review->get_average($post->ID);
                            $reviews_count = ATBDP()->review->db->count(array('post_id' => $post->ID)); // get total review count for this post
                            if (!empty($enable_review)) {
                                $data_info .= '<span class="atbd_meta atbd_listing_rating">
                            ' . $average . '<i class="' . atbdp_icon_type() . '-star"></i>
                        </span>';
                            }

                            $data_info .= '</div>';
                        ?>
                        <?php if (!empty($enable_review)) {
                                $reviews = (($reviews_count > 1) || ($reviews_count === 0)) ? __(' Reviews', 'directorist') : __(' Review', 'directorist');
                                $data_info .= '<div class="atbd_rating_count">';

                                $data_info .= '<p>' . $reviews_count . $reviews . '</p>';

                                $data_info .= ' </div>';
                            }
                        } ?>
                        <?php if (!empty($enable_new_listing) || !empty($display_feature_badge_single) || !empty($display_popular_badge_single)) {
                            $data_info .= '<div class="atbd_badges">';

                            //print the new badge
                            $data_info .= new_badge();
                            /*Print Featured ribbon if it is featured*/
                            if ($featured && !empty($display_feature_badge_single)) {
                                $data_info .= apply_filters('atbdp_featured_badge', '<span class="atbd_badge atbd_badge_featured">' . $feature_badge_text . '</span>');
                            }
                            $popular_listing_id = atbdp_popular_listings(get_the_ID());
                            $badge = '<span class="atbd_badge atbd_badge_popular">' . $popular_badge_text . '</span>';
                            if ($popular_listing_id === get_the_ID()) {
                                $data_info .= $badge;
                            }
                            $data_info .= ' </div>';
                        }
                        $data_info .= '<div class="atbd_listing_category"><ul class="directory_cats">';
                        if (!empty($cats)) {
                            $data_info .= '<li><span class="' . atbdp_icon_type() . '-folder-open"></span></li>';
                            $numberOfCat = count($cats);
                            $output = array();
                            foreach ($cats as $cat) {
                                $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                $space = str_repeat(' ', 1);
                                $output[] = "{$space}<a href='{$link}'>{$cat->name}</a>";
                            }
                            $data_info .= ' <li>
                                        <p class="directory_tag">

                                                    <span>
                                                    ' . join(',', $output) . '
                                                </span>
                                        </p>
                                    </li>';
                        }
                        $data_info .= '</ul></div>';
                        if (!empty($locs) && !empty($enable_single_location_taxonomy)) {
                            $data_info .= '<div class="atbd-listing-location">';
                            $data_info .= '<span class="' . atbdp_icon_type() . '-map-marker"></span>';
                            $numberOfCat = count($locs);
                            $output = array();
                            foreach ($locs as $loc) {
                                $link = ATBDP_Permalink::atbdp_get_location_page($loc);
                                $space = str_repeat(' ', 1);
                                $output[] = "{$space}<a href='{$link}'>{$loc->name}</a>";
                            }
                            $data_info .= join(',', $output);


                            $data_info .= '</div>';
                        }
                        $data_info .= '</div>';
                        /**
                         * @since 5.0
                         * It returns data before listing title
                         */
                        echo apply_filters('atbdp_before_listing_title', $data_info);
                        $class = apply_filters('atbdp_single_listing_title_class', 'atbd_listing_title');
                        echo '<div class="' . $class . '">';
                        $title_html = '<h2>';
                        $title_html .= esc_html($p_title);
                        $title_html .= '</h2>';
                        /**
                         * @since 5.0.5
                         */
                        echo apply_filters('atbdp_listing_title', $title_html);
                        /**
                         * It fires after the listing title
                         */
                        do_action('atbdp_single_listing_after_title', $listing_id);


                        echo '</div>';

                        $tagline_html = '';
                        if (!empty($tagline) && !empty($display_tagline_field)) {
                            $tagline_html .= '<p class="atbd_single_listing_tagline">' . $tagline . '</p>';
                        }
                        /**
                         * @since 5.0.5
                         */
                        echo apply_filters('atbdp_listing_tagline', $tagline_html);
                        /**
                         * Fires after the title and sub title of the listing is rendered on the single listing page
                         *
                         * @since 1.0.0
                         */
                        do_action('atbdp_after_listing_tagline');
                        //listing content
                        $post_object = get_post(get_the_ID());
                        $content = apply_filters('get_the_content', $post_object->post_content);
                        $listing_content = '';
                        if (!empty($content)) {
                            $listing_content = '<div class="about_detail">';
                            $listing_content .= do_shortcode(wpautop($content));
                            $listing_content .= '</div>';
                        }
                        echo apply_filters('atbdp_listing_content', $listing_content);
                        ?>
                    </div>
                </div>
            </div> <!-- end .atbd_listing_details -->
            <?php do_action('atbdp_after_single_listing_details_section');

            $category_ids = array();
            if (!empty($cats)) {
                foreach ($cats as $single_val) {
                    $category_ids[] = $single_val->term_id;
                }
            }
            $c_args = array(
                'post_type' => ATBDP_CUSTOM_FIELD_POST_TYPE,
                'posts_per_page' => -1,
                'post_status' => 'publish',

            );
            $custom_fields = new WP_Query($c_args);
            $custom_fields_posts = $custom_fields->posts;
            $has_field_value = array();
            $has_field_ids = array();
            foreach ($custom_fields_posts as $custom_fields_post) {
                $id = $custom_fields_post->ID;
                $fields = get_post_meta($id, 'associate', true);
                //lets match if the field is associated with a category and the category is selected
                if ('form' != $fields) {
                    $fields_id_with_cat = get_post_meta($id, 'category_pass', true);
                    if (in_array($fields_id_with_cat, $category_ids)) {
                        $has_field_details = get_post_meta($listing_id, $custom_fields_post->ID, true);
                        if (!empty($has_field_details)) {
                            $has_field_ids[] = $id;
                        }
                        $has_field_value[] = $has_field_details;
                    }
                } else {
                    $has_field_details = get_post_meta($listing_id, $custom_fields_post->ID, true);
                    if (!empty($has_field_details)) {
                        $has_field_ids[] = $id;
                    }
                    $has_field_value[] = $has_field_details;
                }
            }
            wp_reset_postdata();
            $has_field = join($has_field_value);
            $has_field = apply_filters('atbdp_single_listing_custom_field', $has_field);
            $plan_custom_field = true;
            if (is_fee_manager_active()) {
                $plan_custom_field = is_plan_allowed_custom_fields($fm_plan);
            }

            // tags
            if (!empty($tags) && !empty($enable_single_tag)) {
            ?>
                <div class="atbd_content_module atbd-listing-tags">
                    <?php if (!empty($tags_section_lable)) { ?>
                        <div class="atbd_content_module_title_area">
                            <div class="atbd_area_title">
                                <h4>
                                    <span class="<?php echo apply_filters('atbdp_single_listing_tag_icon', atbdp_icon_type() . '-tags'); ?>"></span> <?php echo esc_attr($tags_section_lable); ?>
                                </h4>
                            </div>
                        </div> <!-- ends: .atbd_content_module_title_area -->
                    <?php } ?>
                    <div class="atbdb_content_module_contents">
                        <ul>
                            <?php foreach ($tags as $tag) {
                                $link = ATBDP_Permalink::atbdp_get_tag_page($tag);
                                $name = $tag->name; ?>
                                <li>
                                    <a href="<?php echo esc_url($link); ?>">
                                        <span class="<?php echo apply_filters('atbdp_single_listing_tags_icon', atbdp_icon_type() . '-tag'); ?>"></span>
                                        <?php echo esc_attr($name); ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            <?php }

            if (!empty($has_field) && $plan_custom_field) {
            ?>
                <!-- atbdp custom fields -->
                <div class="atbd_content_module atbd_custom_fields_contents">
                    <div class="atbd_content_module_title_area">
                        <div class="atbd_area_title">
                            <h4>
                                <span class="<?php atbdp_icon_type(true); ?>-bars atbd_area_icon"></span><?php _e($custom_section_lable, 'directorist') ?>
                            </h4>
                        </div>
                    </div>
                    <div class="atbdb_content_module_contents">
                        <ul class="atbd_custom_fields">
                            <!--  get data from custom field-->
                            <?php
                            foreach ($has_field_ids as $id) {
                                $field_id = $id;
                                $field_details = get_post_meta($listing_id, $field_id, true);
                                $has_field_value[] = $field_details;

                                $field_title = get_the_title($field_id);
                                $field_type = get_post_meta($field_id, 'type', true);
                                if (!empty($field_details)) {
                            ?>
                                    <li>
                                        <div class="atbd_custom_field_title">
                                            <p><?php echo esc_attr($field_title); ?></p>
                                        </div>
                                        <div class="atbd_custom_field_content">
                                            <p><?php if ('color' == $field_type) {
                                                    printf('<div class="atbd_field_type_color" style="background-color: %s;"></div>', $field_details);
                                                } elseif ($field_type === 'date') {
                                                    $date_format = get_option('date_format');
                                                    echo date($date_format, strtotime($field_details));
                                                } elseif ($field_type === 'time') {
                                                    echo date('h:i A', strtotime($field_details));
                                                } elseif ($field_type === 'url') {
                                                    printf('<a href="%s" target="_blank">%s</a>', esc_url($field_details), esc_url($field_details));
                                                } elseif ($field_type === 'file') {
                                                    $done = str_replace('|||', '', $field_details);
                                                    $name_arr = explode('/', $done);
                                                    $filename = end($name_arr);
                                                    printf('<a href="%s" target="_blank" download>%s</a>', esc_url($done), $filename);
                                                } elseif ($field_type === 'checkbox') {
                                                    $choices = get_post_meta($field_id, 'choices', true);
                                                    $choices = explode("\n", $choices);
                                                    $values = explode("\n", $field_details);
                                                    $values = array_map('trim', $values);
                                                    $output = array();
                                                    foreach ($choices as $choice) {
                                                        if (strpos($choice, ':') !== false) {
                                                            $_choice = explode(':', $choice);
                                                            $_choice = array_map('trim', $_choice);

                                                            $_value = $_choice[0];
                                                            $_label = $_choice[1];
                                                        } else {
                                                            $_value = trim($choice);
                                                            $_label = $_value;
                                                        }
                                                        $_checked = '';
                                                        if (in_array($_value, $values)) {
                                                            $space = str_repeat(' ', 1);
                                                            $output[] = "{$space}$_value";
                                                        }
                                                    }
                                                    echo join(',', $output);
                                                } else {
                                                    $content = apply_filters('get_the_content', $field_details);
                                                    echo do_shortcode( $content );
                                                } ?></p>
                                        </div>
                                    </li>
                            <?php
                                }
                            }
                            wp_reset_query();
                            ?>
                        </ul>
                    </div>
                </div><!-- end .atbd_custom_fields_contents -->
            <?php
            }
            $plan_video = true;
            if (is_fee_manager_active()) {
                $plan_video = is_plan_allowed_listing_video($fm_plan);
            }
            if ($enable_video_url && !empty($videourl) && $plan_video) { ?>
                <div class="atbd_content_module atbd_custom_fields_contents">
                    <div class="atbd_content_module_title_area">
                        <div class="atbd_area_title">
                            <h4>
                                <span class="<?php atbdp_icon_type(true); ?>-video-camera atbd_area_icon"></span><?php _e($video_label, 'directorist') ?>
                            </h4>
                        </div>
                    </div>

                    <div class="atbdb_content_module_contents">
                        <iframe class="atbd_embeded_video embed-responsive-item" src="<?php echo esc_attr(ATBDP()->atbdp_parse_videos($videourl)) ?>" allowfullscreen></iframe>
                    </div>
                </div><!-- end .atbd_custom_fields_contents -->
            <?php } ?>
            <?php do_action('atbdp_after_video_gallery');
            if (!$disable_map && (empty($hide_map)) && !empty($manual_lng || $manual_lat) && !empty($display_map_field)) { ?>
                <div class="atbd_content_module">
                    <div class="atbd_content_module_title_area">
                        <div class="atbd_area_title">
                            <h4>
                                <span class="<?php atbdp_icon_type(true); ?>-map atbd_area_icon"></span><?php _e($listing_location_text, 'directorist'); ?>
                            </h4>
                        </div>
                    </div>

                    <div class="atbdb_content_module_contents">
                        <?php
                        /**
                         * @since 5.10.0
                         *
                         */
                        do_action('atbdp_single_listing_before_map');
                        ?>
                        <div id="gmap" class="atbd_google_map"></div>
                    </div>
                </div><!-- end .atbd_custom_fields_contents -->
            <?php }
            if ((!$hide_contact_info) && !empty($address || $phone || $phone2 || $fax || $email || $website || $zip || $social) && empty($disable_contact_info)) {
                $address_label = get_directorist_option('address_label', __('Address', 'directorist'));
                $phone_label = get_directorist_option('phone_label', __('Phone', 'directorist'));
                $email_label = get_directorist_option('email_label', __('Email', 'directorist'));
                $website_label = get_directorist_option('website_label', __('Website', 'directorist'));
                $zip_label = get_directorist_option('zip_label', __('Zip/Post Code', 'directorist'));
            ?>
                <div class="atbd_content_module atbd_contact_information_module">
                    <div class="atbd_content_module_title_area">
                        <div class="atbd_area_title">
                            <h4>
                                <span class="<?php atbdp_icon_type(true); ?>-envelope-o"></span><?php _e($contact_info_text, 'directorist'); ?>
                            </h4>
                        </div>
                    </div>

                    <div class="atbdb_content_module_contents">
                        <div class="atbd_contact_info">
                            <ul>
                                <?php
                                $address_text = !empty($address_map_link) ? '<a target="google_map" href="https://www.google.com/maps/search/' . esc_html($address) . '">' . esc_html($address) . '</a>' : esc_html($address);
                                if (!empty($address) && !empty($display_address_field)) { ?>
                                    <li>
                                        <div class="atbd_info_title"><span class="<?php atbdp_icon_type(true); ?>-map-marker"></span><?php _e($address_label, 'directorist'); ?>
                                        </div>
                                        <div class="atbd_info"><?php echo $address_text; ?></div>
                                    </li>
                                <?php } ?>

                                <?php
                                $plan_phone = true;
                                if (is_fee_manager_active()) {
                                    $plan_phone = is_plan_allowed_listing_phone($fm_plan);
                                }
                                if (isset($phone) && !is_empty_v($phone) && !empty($display_phone_field) && $plan_phone) { ?>
                                    <!-- In Future, We will have to use a loop to print more than 1 number-->
                                    <li>
                                        <div class="atbd_info_title"><span class="<?php atbdp_icon_type(true); ?>-phone"></span><?php _e($phone_label, 'directorist'); ?>
                                        </div>
                                        <div class="atbd_info"><a href="tel:<?php echo esc_html(stripslashes($phone)); ?>"><?php echo esc_html(stripslashes($phone)); ?></a>
                                        </div>
                                    </li>
                                <?php } ?>

                                <?php
                                if (isset($phone2) && !is_empty_v($phone2) && !empty($display_phone2_field)) { ?>
                                    <!-- In Future, We will have to use a loop to print more than 1 number-->
                                    <li>
                                        <div class="atbd_info_title"><span class="<?php atbdp_icon_type(true); ?>-phone"></span><?php echo $phone_label2; ?>
                                        </div>
                                        <div class="atbd_info"><a href="tel:<?php echo esc_html(stripslashes($phone2)); ?>"><?php echo esc_html(stripslashes($phone2)); ?></a>
                                        </div>
                                    </li>
                                <?php } ?>
                                <?php
                                if (isset($fax) && !is_empty_v($fax) && !empty($display_fax_field)) { ?>
                                    <!-- In Future, We will have to use a loop to print more than 1 number-->
                                    <li>
                                        <div class="atbd_info_title"><span class="<?php atbdp_icon_type(true); ?>-fax"></span><?php echo $fax_label; ?>
                                        </div>
                                        <div class="atbd_info"><a href="tel:<?php echo esc_html(stripslashes($fax)); ?>"><?php echo esc_html(stripslashes($fax)); ?></a>
                                        </div>
                                    </li>
                                <?php } ?>
                                <?php
                                $plan_email = true;
                                if (is_fee_manager_active()) {
                                    $plan_email = is_plan_allowed_listing_email($fm_plan);
                                }
                                if (!empty($email) && !empty($display_email_field) && $plan_email) { ?>
                                    <li>
                                        <div class="atbd_info_title"><span class="<?php atbdp_icon_type(true); ?>-envelope"></span><?php _e($email_label, 'directorist'); ?>
                                        </div>
                                        <span class="atbd_info"><a target="_top" href="mailto:<?php echo esc_html($email); ?>"><?php echo esc_html($email); ?></a></span>
                                    </li>
                                <?php } ?>
                                <?php
                                $plan_webLink = true;
                                if (is_fee_manager_active()) {
                                    $plan_webLink = is_plan_allowed_listing_webLink($fm_plan);
                                }
                                if (!empty($website) && !empty($display_website_field) && $plan_webLink) { ?>
                                    <li>
                                        <div class="atbd_info_title"><span class="<?php atbdp_icon_type(true); ?>-globe"></span><?php _e($website_label, 'directorist'); ?>
                                        </div>
                                        <a target="_blank" href="<?php echo esc_url($website); ?>" class="atbd_info" <?php echo !empty($use_nofollow) ? 'rel="nofollow"' : ''; ?>><?php echo esc_html($website); ?></a>
                                    </li>
                                <?php } ?>
                                <?php
                                if (isset($zip) && !is_empty_v($zip) && !empty($display_zip_field)) { ?>
                                    <!-- In Future, We will have to use a loop to print more than 1 number-->
                                    <li>
                                        <div class="atbd_info_title"><span class="<?php atbdp_icon_type(true); ?>-at"></span><?php _e($zip_label, 'directorist'); ?>
                                        </div>
                                        <div class="atbd_info"><?php echo esc_html($zip); ?></div>
                                    </li>
                                <?php } ?>

                            </ul>
                        </div>
                        <?php
                        $plan_social_networks = true;
                        if (is_fee_manager_active()) {
                            $plan_social_networks = is_plan_allowed_listing_social_networks($fm_plan);
                        }
                        if (!empty($social) && is_array($social) && !empty($display_social_info_field) && $plan_social_networks) { ?>
                            <div class="atbd_director_social_wrap">
                                <?php foreach ($social as $link) {
                                    $link_id = $link['id'];
                                    $link_url = $link['url'];

                                    $n = esc_attr($link_id);
                                    $l = esc_url($link_url);
                                ?>
                                    <a target='_blank' href="<?php echo $l; ?>" class="<?php echo $n; ?>">
                                        <span class="fab fa-<?php echo $n; ?>"></span>
                                    </a>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div><!-- end .atbd_custom_fields_contents -->
            <?php }
            $plan_permission = true;
            global $post;
            if (is_fee_manager_active()) {
                $plan_permission = is_plan_allowed_owner_contact_widget($fm_plan);
            }
            if ($plan_permission && !$hide_contact_owner && empty($disable_contact_owner)) { ?>
                <div class="atbd_content_module atbd_contact_information_module">
                    <div class="atbd_content_module_title_area">
                        <div class="atbd_area_title">
                            <h4>
                                <span class="<?php atbdp_icon_type(true); ?>-paper-plane"></span><?php _e($contact_listing_owner, 'directorist'); ?>
                            </h4>
                        </div>
                    </div>
                    <form id="atbdp-contact-form" class="form-vertical contact_listing_owner contact_listing_owner_form" role="form">
                        <div class="form-group">
                            <input type="text" class="form-control" name="atbdp-contact-name" placeholder="<?php _e('Name', 'directorist'); ?>" required />
                        </div>

                        <div class="form-group">
                            <input type="email" class="form-control" name="atbdp-contact-email" placeholder="<?php _e('Email', 'directorist'); ?>" required />
                        </div>

                        <div class="form-group">
                            <textarea class="form-control" name="atbdp-contact-message" rows="3" placeholder="<?php _e('Message', 'directorist'); ?>..." required></textarea>
                        </div>
                        <?php
                        /**
                         * It fires before contact form in the widget area
                         * @since 4.4.0
                         */

                        do_action('atbdp_before_contact_form_submit_button');
                        ?>
                        <p class="atbdp-contact-message-display" style="margin-bottom: 10px"></p>

                        <input type="hidden" name="atbdp-post-id" value="<?php echo $post->ID; ?>" />
                        <input type="hidden" name="atbdp-listing-email" value="<?php echo !empty($email) ? sanitize_email($email) : ''; ?>" />

                        <button type="submit" class="btn btn-primary"><?php _e('Submit', 'directorist'); ?></button>
                    </form>
                </div>
                
            <?php }
            /**
             * @since 5.0.5
             */
            do_action('atbdp_after_contact_listing_owner_section', $listing_id);
            // if business hour is active then add the following markup...
            if (class_exists('BD_Business_Hour')) {
                if ((BDBH_VERSION < '2.2.8') && (ATBDP_VERSION <= '5.0.5')) {
                    $plan_hours = true;
                    if (is_fee_manager_active()) {
                        $plan_hours = is_plan_allowed_business_hours($fm_plan);
                    }
                    if (is_business_hour_active() && $plan_hours && empty($disable_bz_hour_listing) && (!is_empty_v($business_hours) || !empty($enable247hour))) {
                        BD_Business_Hour()->show_business_hour_module($business_hours, $business_hour_title, $enable247hour); // show the business hour in an unordered list
                    }
                }
            }
            /**
             * Fires after the Map is rendered on single listing page
             *
             *
             * @param object|WP_post $post The current post object which is our listing post
             * @param array $listing_info The meta information of the current listing
             * @since 4.0.3
             *
             */
            $plan_review = true;

            if ($plan_review) {
                do_action('atbdp_before_review_section', $post, $listing_info);
            }

            do_action('atbdp_listing_faqs', $post, $listing_info);
            /**
             * Fires after the Map is rendered on single listing page
             *
             *
             * @param object|WP_post $post The current post object which is our listing post
             * @param array $listing_info The meta information of the current listing
             * @since 1.0.0
             *
             */
            do_action('atbdp_after_map', $post, $listing_info);
            /**
             * Fires after the single listing is rendered on single listing page
             *
             *
             * @param object|WP_post $post The current post object which is our listing post
             * @param array $listing_info The meta information of the current listing
             * @since 1.0.0
             *
             */
            do_action('atbdp_after_single_listing', $post, $listing_info);
            ?>
        </div>
        <?php
        if (apply_filters('atbdp_single_listing_sidebar', $active_sidebar)) {
            include ATBDP_TEMPLATES_DIR . 'sidebar-listing.php';
        }
        ?>
    </div>
    <!--ends .row-->
</section>
<?php
if ('openstreet' == $select_listing_map) {
    wp_register_script('openstreet_layer', ATBDP_PUBLIC_ASSETS . 'js/openstreetlayers.js', array('jquery'), ATBDP_VERSION, true);
    wp_enqueue_script('openstreet_layer');
    wp_enqueue_style('leaflet-css', ATBDP_PUBLIC_ASSETS . 'css/leaflet.css');
}
?>
<script>
    <?php if ('google' == $select_listing_map) { ?>
        var MAP_PIN = 'M0-48c-9.8 0-17.7 7.8-17.7 17.4 0 15.5 17.7 30.6 17.7 30.6s17.7-15.4 17.7-30.6c0-9.6-7.9-17.4-17.7-17.4z';

        var inherits = function(childCtor, parentCtor) {
            /** @constructor */
            function tempCtor() {}

            tempCtor.prototype = parentCtor.prototype;
            childCtor.superClass_ = parentCtor.prototype;
            childCtor.prototype = new tempCtor();
            childCtor.prototype.constructor = childCtor;
        };

        function Marker(options) {
            google.maps.Marker.apply(this, arguments);

            if (options.map_icon_label) {
                this.MarkerLabel = new MarkerLabel({
                    map: this.map,
                    marker: this,
                    text: options.map_icon_label
                });
                this.MarkerLabel.bindTo('position', this, 'position');
            }
        }

        // Apply the inheritance
        inherits(Marker, google.maps.Marker);

        // Custom Marker SetMap
        Marker.prototype.setMap = function() {
            google.maps.Marker.prototype.setMap.apply(this, arguments);
            (this.MarkerLabel) && this.MarkerLabel.setMap.apply(this.MarkerLabel, arguments);
        };

        // Marker Label Overlay
        var MarkerLabel = function(options) {
            var self = this;
            this.setValues(options);

            // Create the label container
            this.div = document.createElement('div');
            this.div.className = 'map-icon-label';

            // Trigger the marker click handler if clicking on the label
            google.maps.event.addDomListener(this.div, 'click', function(e) {
                (e.stopPropagation) && e.stopPropagation();
                google.maps.event.trigger(self.marker, 'click');
            });
        };

        // Create MarkerLabel Object
        MarkerLabel.prototype = new google.maps.OverlayView;

        // Marker Label onAdd
        MarkerLabel.prototype.onAdd = function() {
            var pane = this.getPanes().overlayImage.appendChild(this.div);
            var self = this;

            this.listeners = [
                google.maps.event.addListener(this, 'position_changed', function() {
                    self.draw();
                }),
                google.maps.event.addListener(this, 'text_changed', function() {
                    self.draw();
                }),
                google.maps.event.addListener(this, 'zindex_changed', function() {
                    self.draw();
                })
            ];
        };

        // Marker Label onRemove
        MarkerLabel.prototype.onRemove = function() {
            this.div.parentNode.removeChild(this.div);

            for (var i = 0, I = this.listeners.length; i < I; ++i) {
                google.maps.event.removeListener(this.listeners[i]);
            }
        };

        // Implement draw
        MarkerLabel.prototype.draw = function() {
            var projection = this.getProjection();
            var position = projection.fromLatLngToDivPixel(this.get('position'));
            var div = this.div;

            this.div.innerHTML = this.get('text').toString();

            div.style.zIndex = this.get('zIndex'); // Allow label to overlay marker
            div.style.position = 'absolute';
            div.style.display = 'block';
            div.style.left = (position.x - (div.offsetWidth / 2)) + 'px';
            div.style.top = (position.y - div.offsetHeight) + 'px';
        };

    <?php } ?>


    jQuery(document).ready(function($) {
        // Do not show map if lat long is empty or map is globally disabled.
        <?php if (!$disable_map && (!empty($manual_lat) && !empty($manual_lng)) && !empty($display_map_field) && empty($hide_map)) {
            if ('google' == $select_listing_map) {
        ?>

                // initialize all vars here to avoid hoisting related misunderstanding.
                var map, info_window, saved_lat_lng, info_content;
                saved_lat_lng = {
                    lat: <?php echo (!empty($manual_lat)) ? floatval($manual_lat) : false ?>,
                    lng: <?php echo (!empty($manual_lng)) ? floatval($manual_lng) : false ?>
                }; // default is London city
                info_content = "<?php echo $info_content; ?>";

                // create an info window for map
                <?php if (!empty($display_map_info)) { ?>
                    info_window = new google.maps.InfoWindow({
                        content: info_content,
                        maxWidth: 400 /*Add configuration for max width*/
                    });
                <?php } ?>

                function initMap() {
                    /* Create new map instance*/
                    map = new google.maps.Map(document.getElementById('gmap'), {
                        zoom: <?php echo !empty($map_zoom_level) ? intval($map_zoom_level) : 16; ?>,
                        center: saved_lat_lng
                    });
                    /*var marker = new google.maps.Marker({
                        map: map,
                        position: saved_lat_lng
                    });*/
                    var marker = new Marker({
                        position: saved_lat_lng,
                        map: map,
                        icon: {
                            path: MAP_PIN,
                            fillColor: 'transparent',
                            fillOpacity: 1,
                            strokeColor: '',
                            strokeWeight: 0
                        },
                        map_icon_label: '<div class="atbd_map_shape"><i class="<?php echo $cat_icon; ?>"></i></div>'
                    });

                    <?php if (!empty($display_map_info)) { ?>
                        marker.addListener('click', function() {
                            info_window.open(map, marker);
                        });
                        google.maps.event.addListener(info_window, 'domready', function() {
                            var closeBtn = $('#iw-close-btn').get();
                            google.maps.event.addDomListener(closeBtn[0], 'click', function() {
                                info_window.close();
                            });
                        });
                    <?php } ?>
                }

                initMap();
                //Convert address tags to google map links -
                $('address').each(function() {
                    var link = "<a href='http://maps.google.com/maps?q=" + encodeURIComponent($(this).text()) + "' target='_blank'>" + $(this).text() + "</a>";
                    $(this).html(link);
                });
            <?php } elseif ('openstreet' == $select_listing_map) { ?>

                function mapLeaflet(lat, lon) {
                    const fontAwesomeIcon = L.divIcon({
                        html: '<div class="atbd_map_shape"><span class="<?php echo $cat_icon; ?>"></span></div>',
                        iconSize: [20, 20],
                        className: 'myDivIcon'
                    });
                    var mymap = L.map('gmap').setView([lat, lon], <?php echo !empty($map_zoom_level) ? $map_zoom_level : 16; ?>);
                    <?php if (!empty($display_map_info)) { ?>
                        L.marker([lat, lon], {
                            icon: fontAwesomeIcon
                        }).addTo(mymap).bindPopup(`<?php echo $info_content; ?>`);
                    <?php } else { ?>
                        L.marker([lat, lon], {
                            icon: fontAwesomeIcon
                        }).addTo(mymap);
                    <?php } ?>
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(mymap);
                }

                let lat = <?php echo (!empty($manual_lat)) ? floatval($manual_lat) : false ?>,
                    lon = <?php echo (!empty($manual_lng)) ? floatval($manual_lng) : false ?>;

                mapLeaflet(lat, lon);

        <?php  }
        } ?>
        /* initialize slick  */

        /* image gallery slider */
        function sliderNavigation(slider, prevArrow, nextArrow) {
            $(prevArrow).on('click', function() {
                slider.slick('slickPrev');
            });
            $(nextArrow).on('click', function() {
                slider.slick('slickNext');
            });
        }

        var $listingGallerySlider = $('.atbd_directory_gallery');
        var $listingGalleryThumbnail = $('.atbd_directory_image_thumbnail');

        $listingGallerySlider.slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            asNavFor: '<?php echo (!empty($display_thumbnail_img)) ? ".atbd_directory_image_thumbnail" : ""; ?>',
            rtl: <?php echo is_rtl() ? 'true' : 'false'; ?>
        });


        $(".olAlphaImg").on("click", function() {
            $('.mapHover').addClass('active');
        });

        $('.mapHover span i.fa-times').on('click', (e) => {
            $('.mapHover').removeClass('active');
        });

        /* image gallery slider */
        sliderNavigation($listingGallerySlider, '.atbd_directry_gallery_wrapper .prev', '.atbd_directry_gallery_wrapper .next');

        $listingGalleryThumbnail.slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            arrows: false,
            asNavFor: '.atbd_directory_gallery',
            focusOnSelect: true,
            variableWidth: true,
            rtl: <?php echo is_rtl() ? 'true' : 'false'; ?>
        });

    }); // ends jquery ready function.
</script>
<style>
    #OL_Icon_33 {
        position: relative;
    }

    .mapHover {
        position: absolute;
        background: #fff;
        padding: 5px;
        width: 150px;
        border-radius: 3px;
        border: 1px solid #ddd;
        display: none;
        left: 50%;
        bottom: 100%;
        -webkit-transform: translateX(-50%);
        -moz-transform: translateX(-50%);
        -ms-transform: translateX(-50%);
        -o-transform: translateX(-50%);
        transform: translateX(-50%);
    }

    .mapHover.active {
        display: block;
    }
</style>