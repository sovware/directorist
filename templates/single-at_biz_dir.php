<?php
global $post;
$listing_id = $post->ID;
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
$gallery_cropping = get_directorist_option('gallery_cropping', 1);
$custom_gl_width = get_directorist_option('gallery_crop_width', 670);
$custom_gl_height = get_directorist_option('gallery_crop_height', 750);
$select_listing_map = get_directorist_option('select_listing_map', 'google');
$enable_review = get_directorist_option('enable_review', 'yes');
extract($listing_info);
/*Prepare Listing Image links*/
$listing_imgs = (!empty($listing_img) && !empty($display_slider_image)) ? $listing_img : array();
$image_links = array(); // define a link placeholder variable
foreach ($listing_imgs as $id) {

    if (!empty($gallery_cropping)) {
        $image_links[$id] = atbdp_image_cropping($id, $custom_gl_width, $custom_gl_height, true, 100)['url'];
    } else {
        $image_links[$id] = wp_get_attachment_image_src($id, 'large')[0];
    }

    $image_links_thumbnails[$id] = wp_get_attachment_image_src($id, 'thumbnail')[0]; // store the attachment id and url
}
/*Code for Business Hour Extensions*/
$text247 = get_directorist_option('text247', __('Open 24/7', ATBDP_TEXTDOMAIN)); // text for 24/7 type listing
$business_hour_title = get_directorist_option('business_hour_title', __('Business Hour', ATBDP_TEXTDOMAIN)); // text Business Hour Title

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
$t = !empty($t) ? $t : __('No Title', ATBDP_TEXTDOMAIN);

$average = ATBDP()->review->get_average($listing_id);
$reviews_count = ATBDP()->review->db->count(array('post_id' => $post->ID)); // get total review count for this post
$reviews = ($reviews_count > 1) ? __(' Reviews', ATBDP_TEXTDOMAIN) : __(' Review', ATBDP_TEXTDOMAIN);
$review_info = '';
$review_info = '';
if (!empty($enable_review)) {
    $review_info = "<div class='miwl-rating'><span class='atbd_meta atbd_listing_rating'>$average<i class='".atbdp_icon_type()."-star'></i></span>";

    $review_info .= "<div class='atbd_rating_count'>";

    $review_info .= "<p>" . $reviews_count . $reviews . "</p>";

    $review_info .= "</div></div>";
}

$tg = !empty($tagline) ? esc_html($tagline) : '';
$ad = !empty($address) ? esc_html($address) : '';
$default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
$listing_prv_imgurl = wp_get_attachment_image_src($listing_prv_img, 'small')[0];
$img_url = !empty($listing_prv_imgurl)?$listing_prv_imgurl:$default_image;
$image = "<img src=". $img_url.">";

$info_content = "<div class='map_info_window'>$image <div class='miw-contents'><h3>{$t}</h3>";
$info_content .= "<address>{$ad}</address>";
$info_content .= "<div class='miw-contents-footer'>{$review_info}";
$info_content .= "<a href='http://www.google.com/maps?daddr={$manual_lat},{$manual_lng}' target='_blank'> " . __('Get Direction', ATBDP_TEXTDOMAIN) . "</a></div></div></div>";

/*END INFO WINDOW CONTENT*/
$map_zoom_level = get_directorist_option('map_zoom_level', 16);
$disable_map = get_directorist_option('disable_map', 0);
$disable_sharing = get_directorist_option('disable_sharing', 0);
$disable_contact_info = get_directorist_option('disable_contact_info', 0);
$disable_contact_owner = get_directorist_option('disable_contact_owner', 1);
$is_disable_price = get_directorist_option('disable_list_price');
$enable_social_share = get_directorist_option('enable_social_share', 1);
$enable_favourite = get_directorist_option('enable_favourite', 1);
$enable_report_abuse = get_directorist_option('enable_report_abuse', 1);
$enable_video_url = get_directorist_option('atbd_video_url', 1);
$video_label = get_directorist_option('atbd_video_title', __('Video', ATBDP_TEXTDOMAIN));
$p_lnk = get_the_permalink();
$p_title = get_the_title();
$featured = get_post_meta(get_the_ID(), '_featured', true);
$cats = get_the_terms($post->ID, ATBDP_CATEGORY);
$reviews_count = ATBDP()->review->db->count(array('post_id' => $listing_id)); // get total review count for this post
$listing_author_id = get_post_field('post_author', $listing_id);
$display_feature_badge_single = get_directorist_option('display_feature_badge_cart', 1);
$display_popular_badge_single = get_directorist_option('display_popular_badge_cart', 1);
$popular_badge_text = get_directorist_option('popular_badge_text', 'Popular');
$feature_badge_text = get_directorist_option('feature_badge_text', 'Feature');
$new_badge_text = get_directorist_option('new_badge_text', 'New');
$enable_new_listing = get_directorist_option('display_new_badge_cart', 1);
$use_nofollow = get_directorist_option('use_nofollow');
$custom_section_lable = get_directorist_option('custom_section_lable', __('Details', ATBDP_TEXTDOMAIN));
$listing_details_text = get_directorist_option('listing_details_text', __('Listing Details', ATBDP_TEXTDOMAIN));
$listing_location_text = get_directorist_option('listing_location_text', __('Location', ATBDP_TEXTDOMAIN));
$contact_info_text = get_directorist_option('contact_info_text', __('Contact Information', ATBDP_TEXTDOMAIN));
$contact_listing_owner = get_directorist_option('contact_listing_owner', __('Contact Listing Owner', ATBDP_TEXTDOMAIN));
$display_tagline_field = get_directorist_option('display_tagline_field', 0);
$display_pricing_field = get_directorist_option('display_pricing_field', 1);
$display_address_field = get_directorist_option('display_address_field', 1);
$display_phone_field = get_directorist_option('display_phone_field', 1);
$display_email_field = get_directorist_option('display_email_field', 1);
$display_website_field = get_directorist_option('display_website_field', 1);
$display_zip_field = get_directorist_option('display_zip_field', 1);
$display_social_info_field = get_directorist_option('display_social_info_field', 1);
$display_social_info_for = get_directorist_option('display_social_info_for', 'admin_users');
$display_map_field = get_directorist_option('display_map_field', 1);
$display_video_for = get_directorist_option('display_video_for', 'admin_users');
// make main column size 12 when sidebar or submit widget is active @todo; later make the listing submit widget as real widget instead of hard code
$main_col_size = is_active_sidebar('right-sidebar-listing') ? 'col-lg-8' : 'col-lg-12';
?>
<section id="directorist" class="directorist atbd_wrapper">
    <div class="row">
        <div class="col-lg-8 col-md-12 atbd_col_left">
            <?php
            //is current user is logged in and the original author of the listing
            if (is_user_logged_in() && $listing_author_id == get_current_user_id()) {
                //ok show the edit option
                ?>
                <div class="edit_btn_wrap">
                    <a href="javascript:history.back()" class="atbd_go_back"><i
                                class="<?php atbdp_icon_type(true);?>-angle-left"></i><?php _e(' Go Back', ATBDP_TEXTDOMAIN) ?></a>
                    <a href="<?= esc_url(ATBDP_Permalink::get_edit_listing_page_link($post->ID)); ?>"
                       class="btn btn-success"><span
                                class="<?php atbdp_icon_type(true);?>-edit"></span><?PHP _e(' Edit Listing', ATBDP_TEXTDOMAIN) ?></a>
                </div>
                <?php
            } else {
                ?>
                <div class="edit_btn_wrap">
                    <a href="javascript:history.back()" class="atbd_go_back"><i
                                class="<?php atbdp_icon_type(true);?>-angle-left"></i><?php _e(' Go Back', ATBDP_TEXTDOMAIN) ?></a>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
        /**
         * @since 5.0
         */
        do_action('atbdp_before_single_listing_section');
        ?>
        <div class="<?php echo esc_attr($main_col_size); ?> col-md-12 atbd_col_left">
            <?php
            /**
             * @since 5.2.1
             */
            do_action('atbdp_before_single_listing_details_section');
            ?>
            <div class="atbd_content_module atbd_listing_details">
                <div class="atbd_content_module__tittle_area">
                    <div class="atbd_area_title">
                        <h4>
                            <span class="<?php atbdp_icon_type(true);?>-file-text atbd_area_icon"></span><?php _e($listing_details_text, ATBDP_TEXTDOMAIN) ?>
                        </h4>
                    </div>
                    <?php
                    $listing_header = '<div class="atbd_listing_action_area">';
                    if ($enable_favourite) {
                        $listing_header .= '<div class="atbd_action atbd_save"
                                 id="atbdp-favourites">' . the_atbdp_favourites_link() . '</div>';
                    }
                    if ($enable_social_share) {
                        $listing_header .= '<div class="atbd_action atbd_share">';
                        $listing_header .= '<span class="'.atbdp_icon_type().'-share"></span>' . __('Share', ATBDP_TEXTDOMAIN) . '';

                        $listing_header .= '<div class="atbd_director_social_wrap">';
                        //prepare the data for the links because links needs to be escaped
                        $twt_lnk = "http://twitter.com/share?url={$p_lnk}";
                        $fb_lnk = "https://www.facebook.com/share.php?u={$p_lnk}&title={$p_title}";
                        $in_link = "http://www.linkedin.com/shareArticle?mini=true&url={$p_lnk}&title={$p_title}";
                        $listing_header .= '
                         <ul>
                        <li>
                            <a href="' . esc_url($fb_lnk) . '" target="_blank">
                                <span class="'.atbdp_icon_type().'-facebook"></span>' . __('Facebook', ATBDP_TEXTDOMAIN) . '</a>
                        </li>
                        <li>
                            <a href="' . esc_url($twt_lnk) . '" target="_blank">
                                <span class="'.atbdp_icon_type().'-twitter"></span>' . __('Twitter', ATBDP_TEXTDOMAIN) . '</a>
                        </li>
                        <li>
                            <a href="' . esc_url($in_link) . '" target="_blank">
                                <span class="'.atbdp_icon_type().'-linkedin"></span>' . __('LinkedIn', ATBDP_TEXTDOMAIN) . '</a>
                        </li>
                    </ul>';
                        $listing_header .= '</div>'; //Ends social share
                        ?>
                        <script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
                        <?php
                        $listing_header .= '</div>';
                    }
                    if ($enable_report_abuse) {
                        $listing_header .= '<div class="atbd_action atbd_report">';
                        if (is_user_logged_in()) {
                            $listing_header .= '<span class="'.atbdp_icon_type().'-flag"></span><a href="" data-target="#atbdp-report-abuse-modal">' . __('Report', ATBDP_TEXTDOMAIN) . '</a>'; //Modal (report abuse form)
                        } else {
                            $listing_header .= '<a href="javascript:void(0)"
                               class="atbdp-require-login"><span
                                        class="'.atbdp_icon_type().'-flag"></span>' . __('Report', ATBDP_TEXTDOMAIN) . '</a>';
                        }
                        $listing_header .= '<input type="hidden" id="atbdp-post-id" value="' . get_the_ID() . '"/>';
                        $listing_header .= '</div>';
                    } ?>
                    <div class="modal fade" id="atbdp-report-abuse-modal">
                        <div class="at-modal-content">
                            <div class="atm-contents-inner">
                                <form id="atbdp-report-abuse-form" class="form-vertical" role="form">
                                    <div class="modal-header">
                                        <h3 class="modal-title"
                                            id="atbdp-report-abuse-modal-label"><?php _e('Report Abuse', ATBDP_TEXTDOMAIN); ?></h3>
                                        <a href="" class="at-modal-close"><span aria-hidden="true">&times;</span></a>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="atbdp-report-abuse-message"><?php _e('Your Complaint', ATBDP_TEXTDOMAIN); ?>
                                                <span class="atbdp-star">*</span></label>
                                            <textarea class="form-control" id="atbdp-report-abuse-message"
                                                      rows="3"
                                                      placeholder="<?php _e('Message', ATBDP_TEXTDOMAIN); ?>..."
                                                      required></textarea>
                                        </div>
                                        <div id="atbdp-report-abuse-g-recaptcha"></div>
                                        <div id="atbdp-report-abuse-message-display"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                                data-dismiss="modal"><?php _e('Close', ATBDP_TEXTDOMAIN); ?></button>
                                        <button type="submit"
                                                class="btn btn-primary"><?php _e('Submit', ATBDP_TEXTDOMAIN); ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php $listing_header .= '</div>';
                    /**
                     * @since 5.0
                     */
                    echo apply_filters('atbdp_header_before_image_slider', $listing_header);
                    ?>
                </div>
                <div class="atbdb_content_module_contents">
                    <?php
                    $listing_prv_imgurl = wp_get_attachment_image_src($listing_prv_img, 'large')[0];
                    if (!empty($image_links)) {
                        if (!empty($listing_prv_img && $display_prv_image)) {
                            if (!empty($gallery_cropping)) {
                                $listing_prv_imgurl = atbdp_image_cropping($listing_prv_img, $custom_gl_width, $custom_gl_height, true, 100)['url'];
                            } else {
                                $listing_prv_imgurl = wp_get_attachment_image_src($listing_prv_img, 'large')[0];
                            }
                            array_unshift($image_links, $listing_prv_imgurl);
                        }
                        ?>
                        <div class="atbd_directry_gallery_wrapper">
                            <div class="atbd_big_gallery">
                                <div class="atbd_directory_gallery">
                                    <?php foreach ($image_links as $image_link) { ?>
                                        <div class="single_image">
                                            <img src="<?= !empty($image_link) ? esc_url($image_link) : ''; ?>"
                                                 alt="<?php echo esc_html($p_title); ?>">
                                        </div>

                                        <?php
                                    } ?>
                                </div>
                                <?php if (count($image_links) > 1) { ?>
                                    <span class="prev <?php atbdp_icon_type(true);?>-angle-left"></span>
                                    <span class="next <?php atbdp_icon_type(true);?>-angle-right"></span>
                                <?php } ?>
                            </div>
                            <div class="atbd_directory_image_thumbnail">
                                <?php
                                $listing_prv_imgurl_thumb = wp_get_attachment_image_src($listing_prv_img, 'thumbnail')['0'];
                                if (!empty($listing_prv_imgurl_thumb && !empty($display_prv_image))) {
                                    array_unshift($image_links_thumbnails, $listing_prv_imgurl_thumb);
                                }
                                foreach ($image_links_thumbnails as $image_links_thumbnail) { ?>
                                    <div class="single_thumbnail">
                                        <img src="<?= esc_url($image_links_thumbnail); ?>"
                                             alt="<?php echo esc_html($p_title); ?>">
                                    </div>
                                    <?php
                                    // do not output more than one image if the MI extension is not active
                                    if (!is_multiple_images_active()) break;
                                } ?>
                            </div><!-- end /.atbd_directory_image_wrapper -->
                        </div>
                    <?php } elseif (!empty($display_prv_image)) {
                        $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
                        ?>
                        <div class="single_image">
                            <img src="<?= !empty($listing_prv_img) ? esc_url($listing_prv_imgurl) : $default_image; ?>"
                                 alt="<?php echo esc_html($p_title); ?>">
                        </div>
                        <?php
                    } ?>
                    <div class="atbd_listing_detail">
                        <?php
                        $data_info = '<div class="atbd_data_info">';
                        if (empty($is_disable_price) || !empty($enable_review)) {
                            $data_info .= '<div class="atbd_listing_meta">';
                            $atbd_listing_pricing = !empty($atbd_listing_pricing) ? $atbd_listing_pricing : '';
                            if (empty($is_disable_price)) {
                                if (!empty($display_pricing_field)) {
                                    if (!empty($price_range) && ('range' === $atbd_listing_pricing)) {
                                        //is range selected then print it
                                        $output = atbdp_display_price_range($price_range);
                                        $data_info .= $output;
                                    } else {
                                        $data_info .= atbdp_display_price($price, $is_disable_price, $currency = null, $symbol = null, $c_position = null, $echo = false);
                                    }
                                }
                            }
                            do_action('atbdp_after_listing_price');
                            $average = ATBDP()->review->get_average($post->ID);
                            $reviews_count = ATBDP()->review->db->count(array('post_id' => $post->ID)); // get total review count for this post
                            if (!empty($enable_review)) {
                                $data_info .= '<span class="atbd_meta atbd_listing_rating">
                            ' . $average . '<i class="'.atbdp_icon_type().'-star"></i>
                        </span>';
                            }

                            $data_info .= '</div>';
                            ?>
                            <?php if (!empty($enable_review)) {
                                $reviews = ($reviews_count > 1) ? __(' Reviews', ATBDP_TEXTDOMAIN) : __(' Review', ATBDP_TEXTDOMAIN);
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
                                $data_info .= '<span class="atbd_badge atbd_badge_featured">' . $feature_badge_text . '</span>';
                            }
                            $popular_listing_id = atbdp_popular_listings(get_the_ID());
                            $badge = '<span class="atbd_badge atbd_badge_popular">' . $popular_badge_text . '</span>';
                            if ($popular_listing_id === get_the_ID()) {
                                $data_info .= $badge;
                            }
                            $data_info .= ' </div>';
                        }
                        $data_info .= '<div class="atbd_listting_category"><ul class="directory_cats">';
                        if (!empty($cats)) {
                            $data_info .= '<span class="'.atbdp_icon_type().'-tags"></span>';
                            $numberOfCat = count($cats);
                            $output = array();
                            foreach ($cats as $cat) {
                                $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                $space = str_repeat(' ', 1);
                                $output [] = "{$space}<a href='{$link}'>{$cat->name}</a>";
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

                        $data_info .= ' </div>';
                        /**
                         * @since 5.0
                         * It returns data before listing title
                         */
                        echo apply_filters('atbdp_before_listing_title', $data_info);

                        echo '<div class="atbd_listing_title">';
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
            <?php
            $term_id = get_post_meta($post->ID, '_admin_category_select', true);
            $meta_array = array('relation' => 'AND');
            $meta_array = array(
                'key' => 'category_pass',
                'value' => $term_id,
                'compare' => 'EXISTS'
            );

            if (('-1' === $term_id) || empty($term_id)) {
                $post_ids_array = $cats; //this array will be dynamically generated
                if (!empty($post_ids_array)) {
                    $meta_array = array('relation' => 'OR');
                    foreach ($post_ids_array as $key => $value) {
                        array_push($meta_array,
                            array(
                                'key' => 'category_pass',
                                'value' => $value->term_id,
                                'compare' => 'EXISTS'
                            )
                        );
                    }
                }
            }
            $custom_fields = new WP_Query(array(
                'post_type' => ATBDP_CUSTOM_FIELD_POST_TYPE,
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => 'associate',
                        'value' => 'form',
                        'compare' => 'EXISTS'
                    ),
                    $meta_array
                )
            ));
            $custom_fields_posts = $custom_fields->posts;
            $has_field_value = array();
            foreach ($custom_fields_posts as $custom_fields_post) {
                setup_postdata($custom_fields_post);
                $has_field_id = $custom_fields_post->ID;
                $has_field_details = get_post_meta($listing_id, $has_field_id, true);
                $has_field_value[] = $has_field_details;
            }
            $has_field = join($has_field_value);

            if (!empty($has_field)) {
                ?>
                <div class="atbd_content_module atbd_custom_fields_contents">
                    <div class="atbd_content_module__tittle_area">
                        <div class="atbd_area_title">
                            <h4>
                                <span class="<?php atbdp_icon_type(true);?>-bars atbd_area_icon"></span><?php _e($custom_section_lable, ATBDP_TEXTDOMAIN) ?>
                            </h4>
                        </div>
                    </div>
                    <div class="atbdb_content_module_contents">
                        <ul class="atbd_custom_fields">
                            <!--  get data from custom field-->
                            <?php

                            foreach ($custom_fields_posts as $post) {
                                setup_postdata($post);
                                $field_id = $post->ID;
                                $field_details = get_post_meta($listing_id, $field_id, true);
                                $has_field_value[] = $field_details;

                                $field_title = get_the_title($field_id);
                                $field_type = get_post_meta($field_id, 'type', true);
                                if (!empty($field_details)) {
                                    ?>
                                    <li>
                                        <div class="atbd_custom_field_title">
                                            <p><?php echo esc_attr($field_title); ?></p></div>
                                        <div class="atbd_custom_field_content">
                                            <p><?php if ('color' == $field_type) {
                                                    printf('<div class="atbd_field_type_color" style="background-color: %s;"></div>', $field_details);
                                                } elseif ($field_type === 'time') {
                                                    echo date('h:i A', strtotime($field_details));
                                                } elseif ($field_type === 'url') {
                                                    printf('<a href="%s" target="_blank">%s</a>', esc_url($field_details), esc_url($field_details));
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
                                                    echo do_shortcode(wpautop($content));
                                                    //echo esc_attr($field_details);
                                                } ?></p>
                                        </div>
                                    </li>
                                    <?php
                                }
                            }
                            wp_reset_postdata();
                            ?>
                        </ul>
                    </div>
                </div><!-- end .atbd_custom_fields_contents -->
                <?php
            }
            if ($enable_video_url && !empty($videourl) && 'none' != $display_video_for) { ?>
                <div class="atbd_content_module atbd_custom_fields_contents">
                    <div class="atbd_content_module__tittle_area">
                        <div class="atbd_area_title">
                            <h4>
                                <span class="<?php atbdp_icon_type(true);?>-video-camera atbd_area_icon"></span><?php _e($video_label, ATBDP_TEXTDOMAIN) ?>
                            </h4>
                        </div>
                    </div>

                    <div class="atbdb_content_module_contents">
                        <iframe class="atbd_embeded_video embed-responsive-item"
                                src="<?php echo esc_attr(ATBDP()->atbdp_parse_videos($videourl)) ?>"
                                allowfullscreen></iframe>
                    </div>
                </div><!-- end .atbd_custom_fields_contents -->
            <?php } ?>
            <?php do_action('atbdp_after_video_gallery');
            if (!$disable_map && (empty($hide_map)) && !empty($manual_lng || $manual_lat) && !empty($display_map_field)) { ?>
                <div class="atbd_content_module">
                    <div class="atbd_content_module__tittle_area">
                        <div class="atbd_area_title">
                            <h4>
                                <span class="<?php atbdp_icon_type(true);?>-map atbd_area_icon"></span><?php _e($listing_location_text, ATBDP_TEXTDOMAIN); ?>
                            </h4>
                        </div>
                    </div>

                    <div class="atbdb_content_module_contents">
                        <div id="gmap" class="atbd_google_map"></div>
                    </div>
                </div><!-- end .atbd_custom_fields_contents -->
            <?php }
            if ((!$hide_contact_info) && !empty($address || $phone || $email || $website || $zip || $social) && empty($disable_contact_info)) { ?>
                <div class="atbd_content_module atbd_contact_information_module">
                    <div class="atbd_content_module__tittle_area">
                        <div class="atbd_area_title">
                            <h4>
                                <span class="<?php atbdp_icon_type(true);?>-envelope-o"></span><?php _e($contact_info_text, ATBDP_TEXTDOMAIN); ?>
                            </h4>
                        </div>
                    </div>

                    <div class="atbdb_content_module_contents">
                        <div class="atbd_contact_info">
                            <ul>
                                <?php if (!empty($address) && !empty($display_address_field)) { ?>
                                    <li>
                                        <div class="atbd_info_title"><span
                                                    class="<?php atbdp_icon_type(true);?>-map-marker"></span><?php _e('Address', ATBDP_TEXTDOMAIN); ?>
                                        </div>
                                        <div class="atbd_info"><?= esc_html($address); ?></div>
                                    </li>
                                <?php } ?>

                                <?php
                                if (isset($phone) && !is_empty_v($phone) && !empty($display_phone_field)) { ?>
                                    <!-- In Future, We will have to use a loop to print more than 1 number-->
                                    <li>
                                        <div class="atbd_info_title"><span
                                                    class="<?php atbdp_icon_type(true);?>-phone"></span><?php _e('Phone', ATBDP_TEXTDOMAIN); ?>
                                        </div>
                                        <div class="atbd_info"><?= esc_html($phone); ?></div>
                                    </li>
                                <?php } ?>

                                <?php if (!empty($email) && !empty($display_email_field)) { ?>
                                    <li>
                                        <div class="atbd_info_title"><span
                                                    class="<?php atbdp_icon_type(true);?>-envelope"></span><?php _e('Email', ATBDP_TEXTDOMAIN); ?>
                                        </div>
                                        <span class="atbd_info"><a target="_top"
                                                                   href="mailto:<?= esc_html($email); ?>"><?= esc_html($email); ?></a></span>
                                    </li>
                                <?php } ?>

                                <?php if (!empty($website) && !empty($display_website_field)) { ?>
                                    <li>
                                        <div class="atbd_info_title"><span
                                                    class="<?php atbdp_icon_type(true);?>-globe"></span><?php _e('Website', ATBDP_TEXTDOMAIN); ?>
                                        </div>
                                        <a target="_blank" href="<?= esc_url($website); ?>"
                                           class="atbd_info" <?php echo !empty($use_nofollow) ? 'rel="nofollow"' : ''; ?>><?= esc_html($website); ?></a>
                                    </li>
                                <?php } ?>
                                <?php
                                if (isset($zip) && !is_empty_v($zip) && !empty($display_zip_field)) { ?>
                                    <!-- In Future, We will have to use a loop to print more than 1 number-->
                                    <li>
                                        <div class="atbd_info_title"><span
                                                    class="<?php atbdp_icon_type(true);?>-at"></span><?php _e('Zip/Post Code', ATBDP_TEXTDOMAIN); ?>
                                        </div>
                                        <div class="atbd_info"><?= esc_html($zip); ?></div>
                                    </li>
                                <?php } ?>

                            </ul>
                        </div>
                        <?php if (!empty($social) && is_array($social) && !empty($display_social_info_field)) { ?>
                            <div class="atbd_director_social_wrap">
                                <?php foreach ($social as $link) {
                                    $n = esc_attr($link['id']);
                                    $l = esc_url($link['url']);
                                    ?>
                                    <a target='_blank' href="<?php echo $l; ?>"><span
                                                class="<?php atbdp_icon_type(true);?>-<?php echo $n; ?>"></span></a>
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
                    <div class="atbd_content_module__tittle_area">
                        <div class="atbd_area_title">
                            <h4>
                                <span class="<?php atbdp_icon_type(true);?>-paper-plane"></span><?php _e($contact_listing_owner, ATBDP_TEXTDOMAIN); ?>
                            </h4>
                        </div>
                    </div>
                    <form id="atbdp-contact-form" class="form-vertical contact_listing_owner" role="form">
                        <div class="form-group">
                            <input type="text" class="form-control" id="atbdp-contact-name"
                                   placeholder="<?php _e('Name', ATBDP_TEXTDOMAIN); ?>" required/>
                        </div>

                        <div class="form-group">
                            <input type="email" class="form-control" id="atbdp-contact-email"
                                   placeholder="<?php _e('Email', ATBDP_TEXTDOMAIN); ?>" required/>
                        </div>

                        <div class="form-group">
                            <textarea class="form-control" id="atbdp-contact-message" rows="3"
                                      placeholder="<?php _e('Message', ATBDP_TEXTDOMAIN); ?>..." required></textarea>
                        </div>
                        <?php
                        /**
                         * It fires before contact form in the widget area
                         * @since 4.4.0
                         */

                        do_action('atbdp_before_submit_contact_form_inWidget');
                        ?>
                        <p id="atbdp-contact-message-display" style="margin-bottom: 10px"></p>

                        <button type="submit" class="btn btn-primary"><?php _e('Submit', ATBDP_TEXTDOMAIN); ?></button>
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
             * @since 4.0.3
             *
             * @param object|WP_post $post The current post object which is our listing post
             * @param array $listing_info The meta information of the current listing
             */
            $plan_review = true;

            if ($plan_review) {
                do_action('atbdp_before_review_section', $post, $listing_info);
            }
            /**
             * Fires after the Map is rendered on single listing page
             *
             *
             * @since 1.0.0
             *
             * @param object|WP_post $post The current post object which is our listing post
             * @param array $listing_info The meta information of the current listing
             */
            do_action('atbdp_after_map', $post, $listing_info);
            /**
             * Fires after the single listing is rendered on single listing page
             *
             *
             * @since 1.0.0
             *
             * @param object|WP_post $post The current post object which is our listing post
             * @param array $listing_info The meta information of the current listing
             */
            do_action('atbdp_after_single_listing', $post, $listing_info);
            ?>
        </div>
        <?php
        include ATBDP_TEMPLATES_DIR . 'sidebar-listing.php';
        ?>
    </div> <!--ends .row-->
</section>
<?php
if ('openstreet' == $select_listing_map) {
    wp_register_script('openstreet_layer', ATBDP_PUBLIC_ASSETS . 'js/openstreetlayers.js', array('jquery'), ATBDP_VERSION, true);
    wp_enqueue_script('openstreet_layer');
}
?>
<script>
    jQuery(document).ready(function ($) {
        // Do not show map if lat long is empty or map is globally disabled.
        <?php if (!$disable_map && (!empty($manual_lat) && !empty($manual_lng)) && !empty($display_map_field) && empty($hide_map) ){
        if('google' == $select_listing_map) {
        ?>

        // initialize all vars here to avoid hoisting related misunderstanding.
        var map, info_window, saved_lat_lng, info_content;
        saved_lat_lng = {
            lat:<?= (!empty($manual_lat)) ? floatval($manual_lat) : false ?>,
            lng: <?= (!empty($manual_lng)) ? floatval($manual_lng) : false ?> }; // default is London city
        info_content = "<?= $info_content; ?>";

        // create an info window for map
        info_window = new google.maps.InfoWindow({
            content: info_content,
            maxWidth: 400/*Add configuration for max width*/
        });

        function initMap() {
            /* Create new map instance*/
            map = new google.maps.Map(document.getElementById('gmap'), {
                zoom: <?php echo !empty($map_zoom_level) ? intval($map_zoom_level) : 16; ?>,
                center: saved_lat_lng
            });
            var marker = new google.maps.Marker({
                map: map,
                position: saved_lat_lng
            });
            marker.addListener('click', function () {
                info_window.open(map, marker);
            });
        }

        initMap();
        //Convert address tags to google map links -
        $('address').each(function () {
            var link = "<a href='http://maps.google.com/maps?q=" + encodeURIComponent($(this).text()) + "' target='_blank'>" + $(this).text() + "</a>";
            $(this).html(link);
        });
        <?php } elseif('openstreet' == $select_listing_map) { ?>


        map = new OpenLayers.Map("gmap");

        let mymap = (lon, lat) => {
            map.addLayer(new OpenLayers.Layer.OSM());
            let pois = new OpenLayers.Layer.Text("My Points",
                {
                    location: "",
                    projection: map.displayProjection
                });
            map.addLayer(pois);
            // create layer switcher widget in top right corner of map.
            let layer_switcher = new OpenLayers.Control.LayerSwitcher({});
            map.addControl(layer_switcher);
            //Set start centrepoint and zoom
            let lonLat = new OpenLayers.LonLat(lon, lat)
                .transform(
                    new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
                    map.getProjectionObject() // to Spherical Mercator Projection
                );
            let zoom = <?php echo !empty($map_zoom_level) ? intval($map_zoom_level) : 16; ?>;
            let markers = new OpenLayers.Layer.Markers("Markers");
            map.addLayer(markers);
            markers.addMarker(new OpenLayers.Marker(lonLat));
            map.setCenter(lonLat, zoom);
        }

        let lat = <?php echo !empty($manual_lat) ? floatval($manual_lat) : false;?>,
            lon = <?php echo !empty($manual_lng) ? floatval($manual_lng) : false; ?>;

        mymap(lon, lat);
        var abc = `<?php echo !empty($info_content)?$info_content:'' ?>` + '<span><i class="fa fa-times"></i></span>';

        $('#OL_Icon_33').append('<div class="mapHover"></div>');
        $('.mapHover').html(abc);

        <?php } }?>
        /* initialize slick  */

        /* image gallery slider */
        function sliderNavigation(slider, prevArrow, nextArrow) {
            $(prevArrow).on('click', function () {
                slider.slick('slickPrev');
            });
            $(nextArrow).on('click', function () {
                slider.slick('slickNext');
            });
        }

        var $listingGallerySlider = $('.atbd_directory_gallery');
        var $listingGalleryThumbnail = $('.atbd_directory_image_thumbnail');

        $listingGallerySlider.slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            asNavFor: '.atbd_directory_image_thumbnail',
            rtl: <?php echo is_rtl() ? 'true' : 'false'; ?>
        });

       

        $(".olAlphaImg").on("click", function(){
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
    .mapHover.active{
        display: block;
    }
</style>