<?php
$listing_id = $post->ID;
$fm_plan = get_post_meta($listing_id, '_fm_plans', true);
/*store all data in an array so that we can pass it to filters for extension to get this value*/
$listing_info['never_expire']       = get_post_meta($post->ID, '_never_expire', true);
$listing_info['featured']           = get_post_meta($post->ID, '_featured', true);
$listing_info['price']              = get_post_meta($post->ID, '_price', true);
$listing_info['price_range']        = get_post_meta($post->ID, '_price_range', true);
$listing_info['videourl']           = get_post_meta($post->ID, '_videourl', true);
$listing_info['listing_status']     = get_post_meta($post->ID, '_listing_status', true);
$listing_info['tagline']            = get_post_meta($post->ID, '_tagline', true);
$listing_info['excerpt']            = get_post_meta($post->ID, '_excerpt', true);
$listing_info['address']            = get_post_meta($post->ID, '_address', true);
$listing_info['phone']              = get_post_meta($post->ID, '_phone', true);
$listing_info['email']              = get_post_meta($post->ID, '_email', true);
$listing_info['website']            = get_post_meta($post->ID, '_website', true);
$listing_info['social']             = get_post_meta($post->ID, '_social', true);
$listing_info['faqs']               = get_post_meta($post->ID, '_faqs', true);
$listing_info['manual_lat']         = get_post_meta($post->ID, '_manual_lat', true);
$listing_info['manual_lng']         = get_post_meta($post->ID, '_manual_lng', true);
$listing_info['hide_map']           = get_post_meta($post->ID, '_hide_map', true);
$listing_info['listing_img']        = get_post_meta($post->ID, '_listing_img', true);
$listing_info['listing_prv_img']    = get_post_meta($post->ID, '_listing_prv_img', true);
$listing_info['hide_contact_info']  = get_post_meta($post->ID, '_hide_contact_info', true);
$listing_info['hide_contact_owner'] = get_post_meta($post->ID, '_hide_contact_owner', true);
$listing_info['expiry_date']        = get_post_meta($post->ID, '_expiry_date', true);
$display_prv_image                  = get_directorist_option('dsiplay_prv_single_page',1);
$display_slider_image               = get_directorist_option('dsiplay_slider_single_page',1);
$gallery_cropping                   = get_directorist_option('gallery_cropping',1);
$custom_gl_width                    = get_directorist_option('gallery_crop_width', 670);
$custom_gl_height                   = get_directorist_option('gallery_crop_height', 750);
extract($listing_info);
/*Prepare Listing Image links*/
$listing_imgs= (!empty($listing_img) && !empty($display_slider_image)) ? $listing_img : array();
$image_links = array(); // define a link placeholder variable
foreach ($listing_imgs as $id){

    if(!empty($gallery_cropping)) {
        $image_links[$id] = atbdp_image_cropping($id, $custom_gl_width, $custom_gl_height, true, 100)['url'];
    } else {
        $image_links[$id] = wp_get_attachment_image_src($id, 'large')[0];
    }

    $image_links_thumbnails[$id]= wp_get_attachment_image_src($id, 'thumbnail')[0]; // store the attachment id and url

    //@todo; instead of getting a full size image, define a an image size and then fetch that size and let the user change that image size via a hook.
}

/*Code for Business Hour Extensions*/
/*@todo; Make business hour settings compatible to our new settings panel. It is good to prefix all settings of extensions with their prefix*/
$text247                = get_directorist_option('text247',  __('Open 24/7', ATBDP_TEXTDOMAIN)); // text for 24/7 type listing
$business_hour_title    = get_directorist_option('business_hour_title',  __('Business Hour', ATBDP_TEXTDOMAIN)); // text Business Hour Title

$bdbh                   = get_post_meta($listing_id, '_bdbh', true);
$enable247hour          = get_post_meta($listing_id, '_enable247hour', true);
$disable_bz_hour_listing          = get_post_meta($listing_id, '_disable_bz_hour_listing', true);
$business_hours         = !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array(); // arrays of days and times if exist

/*Code for Business Hour Extensions*/
$manual_lat = (!empty($manual_lat)) ? floatval($manual_lat) : false;
$manual_lng = (!empty($manual_lng)) ? floatval($manual_lng) : false;
$hide_contact_info = !empty($hide_contact_info) ? $hide_contact_info : false;
$hide_contact_owner = !empty($hide_contact_owner) ? $hide_contact_owner : false;

/*INFO WINDOW CONTENT*/
$t = get_the_title();
$t = !empty($t) ? $t : __('No Title', ATBDP_TEXTDOMAIN);
$tg = !empty($tagline) ? esc_html($tagline) : '';
$ad = !empty($address) ? esc_html($address) : '';
$image = (!empty($attachment_id[0])) ? "<img src='" . esc_url(wp_get_attachment_image_url($attachment_id[0], 'thumbnail')) . "'>" : '';
$info_content = "<div class='map_info_window'> <h3>{$t}</h3>";
$info_content .= "<p> {$tg} </p>";
$info_content .= $image ; // add the image if available
$info_content .= "<address>{$ad}</address>";
$info_content .= "<a href='http://www.google.com/maps/place/{$manual_lat},{$manual_lng}' target='_blank'> ".__('View On Google Maps', ATBDP_TEXTDOMAIN)."</a></div>";
/*END INFO WINDOW CONTENT*/
$map_zoom_level                   = get_directorist_option('map_zoom_level', 16);
$disable_map                      = get_directorist_option('disable_map', 0);
$disable_sharing                  = get_directorist_option('disable_sharing', 0);
$disable_contact_info             = get_directorist_option('disable_contact_info', 0);
$disable_contact_owner            = get_directorist_option('disable_contact_owner',1);
$is_disable_price                 = get_directorist_option('disable_list_price');
$enable_social_share              = get_directorist_option('enable_social_share', 1);
$enable_favourite                 = get_directorist_option('enable_favourite', 1);
$enable_report_abuse              = get_directorist_option('enable_report_abuse', 1);
$enable_video_url                 = get_directorist_option('atbd_video_url', 1);
$video_label                      = get_directorist_option('atbd_video_title');
$p_lnk                            = get_the_permalink();
$p_title                          = get_the_title();
$featured                         = get_post_meta(get_the_ID(), '_featured', true);
$cats                             = get_the_terms($post->ID, ATBDP_CATEGORY);
$reviews_count                    = ATBDP()->review->db->count(array('post_id' => $listing_id)); // get total review count for this post
$listing_author_id                = get_post_field( 'post_author', $listing_id );
$display_feature_badge_single     = get_directorist_option('display_feature_badge_cart',1);
$display_popular_badge_single     = get_directorist_option('display_popular_badge_cart',1);
$popular_badge_text               = get_directorist_option('popular_badge_text','Popular');
$feature_badge_text               = get_directorist_option('feature_badge_text','Feature');
$new_badge_text                   = get_directorist_option('new_badge_text','New');
$enable_new_listing               = get_directorist_option('display_new_badge_cart',1);
$use_nofollow                     = get_directorist_option('use_nofollow');
$enable_review                    = get_directorist_option('enable_review', 1);
$custom_section_lable             = get_directorist_option('custom_section_lable', __('Details', ATBDP_TEXTDOMAIN));

// make main column size 12 when sidebar or submit widget is active @todo; later make the listing submit widget as real widget instead of hard code
$main_col_size = is_active_sidebar('right-sidebar-listing')  ? 'col-lg-8' : 'col-lg-12';
?>
<section id="directorist" class="directorist atbd_wrapper">
    <div class="row">
        <div class="<?php echo esc_attr($main_col_size); ?> col-md-12 atbd_col_left">

            <?php
            //is current user is logged in and the original author of the listing
            if (is_user_logged_in() && $listing_author_id == get_current_user_id()){
                //ok show the edit option
                ?>
                <div class="edit_btn_wrap">
                    <a href="<?= esc_url(ATBDP_Permalink::get_edit_listing_page_link($post->ID)); ?>" class="btn btn-success"><span class="fa fa-edit"></span><?PHP _e(' Edit Listing', ATBDP_TEXTDOMAIN)?></a>
                </div>

                <?php
            }
            ?>
            <div class="atbd_content_module atbd_listing_details">
                <div class="atbd_content_module__tittle_area">
                    <div class="atbd_area_title">
                        <h4><span class="fa fa-file-text atbd_area_icon"></span><?php _e('Listing Details', ATBDP_TEXTDOMAIN)?></h4>
                    </div>

                    <div class="atbd_listing_action_area">
                        <?php if($enable_favourite) {?>
                        <div class="atbd_action atbd_save" id="atbdp-favourites"><?php the_atbdp_favourites_link(); ?></div>
                        <?php } ?>
                        <?php if($enable_social_share) {?>
                        <div class="atbd_action atbd_share">
                            <span class="fa fa-share-alt"></span>Share
                                <div class="atbd_director_social_wrap">
                                    <?php
                                    //prepare the data for the links because links needs to be escaped
                                    $twt_lnk = "http://twitter.com/intent/tweet?status={$p_title}+{$p_lnk}";
                                    $fb_lnk = "https://www.facebook.com/share.php?u={$p_lnk}&title={$p_title}";
                                    $g_lnk = "https://plus.google.com/share?url={$p_lnk}";
                                    $in_link = "http://www.linkedin.com/shareArticle?mini=true&url={$p_lnk}&title={$p_title}";
                                    ?>
                                    <ul>
                                        <li>
                                            <a href="<?php echo esc_url($fb_lnk); ?>" target="_blank">
                                                <span class="fa fa-facebook"></span><?PHP _e('Facebook', ATBDP_TEXTDOMAIN)?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo esc_url($twt_lnk); ?>" target="_blank">
                                                <span class="fa fa-twitter"></span><?PHP _e('Twitter', ATBDP_TEXTDOMAIN)?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo esc_url($g_lnk); ?>" target="_blank">
                                                <span class="fa fa-google-plus"></span><?PHP _e('Google Plus', ATBDP_TEXTDOMAIN)?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo esc_url($in_link); ?>" target="_blank">
                                                <span class="fa fa-linkedin"></span><?PHP _e('LinkedIn', ATBDP_TEXTDOMAIN)?>
                                            </a>
                                        </li>
                                    </ul>
                                </div> <!--Ends social share-->

                        </div>
                        <?php } ?>
                        <!-- Report Abuse-->
                        <?php
                        if ($enable_report_abuse) { ?>
                            <div class="atbd_action atbd_report">
                                <?php if (is_user_logged_in()) { ?>

                                    <span class="fa fa-flag"></span><a href="javascript:void(0)" data-toggle="modal" data-target="#atbdp-report-abuse-modal"><?php _e( 'Report', ATBDP_TEXTDOMAIN ); ?></a>
                                    <!-- Modal (report abuse form) -->

                                <?php } else { ?>
                                    <a href="javascript:void(0)"
                                       class="atbdp-require-login"><span class="fa fa-flag"></span><?php _e('Report', ATBDP_TEXTDOMAIN); ?></a>
                                <?php } ?>
                                <input type="hidden" id="atbdp-post-id" value="<?php echo get_the_ID(); ?>"/>
                            </div>
                        <?php } ?>
                        <div class="modal fade" id="atbdp-report-abuse-modal" tabindex="-1" role="dialog"
                             aria-labelledby="atbdp-report-abuse-modal-label" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content modal-dialog-centered">
                                    <form id="atbdp-report-abuse-form" class="form-vertical" role="form">
                                        <div class="modal-header">
                                            <h3 class="modal-title"
                                                id="atbdp-report-abuse-modal-label"><?php _e('Report Abuse', ATBDP_TEXTDOMAIN); ?></h3>
                                            <button type="button" class="close" data-dismiss="modal"><span
                                                        aria-hidden="true">&times;</span></button>
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
                    </div>
                </div>

                <div class="atbdb_content_module_contents">
                    <?php
                    $listing_prv_imgurl = wp_get_attachment_image_src($listing_prv_img, 'large')[0];
                    if (!empty($image_links)) {
                        if (!empty($listing_prv_img) && !empty($display_prv_image)){
                            if(!empty($gallery_cropping)) {
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
                                    <?php foreach ($image_links as $image_link) {?>
                                        <div class="single_image">
                                            <img src="<?= !empty($image_link)?esc_url($image_link): ''; ?>"
                                                 alt="<?php esc_attr_e('Details Image', ATBDP_TEXTDOMAIN); ?>">
                                        </div>

                                        <?php
                                        // do not output more than one image if the MI extension is not active
                                        if (!is_multiple_images_active()) break;
                                    } ?>
                                </div>
                                <?php if (count($image_links) > 1 && is_multiple_images_active()) { ?>
                                    <span class="prev fa fa-angle-left"></span>
                                    <span class="next fa fa-angle-right"></span>
                                <?php } ?>
                            </div>
                            <div class="atbd_directory_image_thumbnail">
                                <?php
                                $listing_prv_imgurl_thumb = wp_get_attachment_image_src($listing_prv_img, 'thumbnail')['0'];
                                if (!empty($listing_prv_imgurl_thumb && !empty($display_prv_image))){
                                    array_unshift($image_links_thumbnails, $listing_prv_imgurl_thumb);
                                }
                                foreach ($image_links_thumbnails as $image_links_thumbnail) { ?>
                                    <div class="single_thumbnail">
                                        <img src="<?= esc_url($image_links_thumbnail); ?>"
                                             alt="<?php esc_attr_e('Details Image', ATBDP_TEXTDOMAIN); ?>">
                                    </div>
                                    <?php
                                    // do not output more than one image if the MI extension is not active
                                    if (!is_multiple_images_active()) break;
                                } ?>
                            </div><!-- end /.atbd_directory_image_wrapper -->
                        </div>
                    <?php }elseif(!empty($display_prv_image)){
                        $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
                        ?>
                        <div class="single_image">
                            <img src="<?= !empty($listing_prv_img) ? esc_url($listing_prv_imgurl) : $default_image; ?>"
                                 alt="<?php esc_attr_e('Details Image', ATBDP_TEXTDOMAIN); ?>">
                        </div>
                        <?php
                    } ?>
                    <div class="atbd_listing_detail">
                        <div class="atbd_data_info">
                            <div class="atbd_listing_meta">
                                <?php
                                if(!empty($price_range)) {
                                    //is range selected then print it
                                    $output = atbdp_display_price_range($price_range);
                                    echo $output;
                                }else{
                                    atbdp_display_price($price, $is_disable_price);
                                }
                                do_action('atbdp_after_listing_price');
                                /**
                                 * Fires after the title and sub title of the listing is rendered on the single listing page
                                 *
                                 * @since 1.0.0
                                 */
                                do_action('atbdp_after_listing_tagline');
                                ?>
                            </div>
                            <?php if($enable_review) {?>
                            <div class="atbd_rating_count">
                                <p><?php echo $reviews_count;
                                    _e($reviews_count>1 ? ' Reviews': ' Review', ATBDP_TEXTDOMAIN);
                                    ?>
                                </p>
                            </div>
                            <?php } ?>
                            <div class="atbd_badges">
                                <?php
                                    //print the new badge
                                    echo new_badge();
                                    /*Print Featured ribbon if it is featured*/
                                    if ($featured && !empty($display_feature_badge_single)) {
                                        printf(
                                            '<span class="atbd_badge atbd_badge_featured">%s</span>',
                                            $feature_badge_text
                                        );
                                    }
                                    $count = !empty($count) ? $count : '';
                                    $popular_listings = ATBDP()->get_popular_listings($count);
                                    if ($display_popular_badge_single){
                                        if ($popular_listings->have_posts()) {

                                            foreach ($popular_listings->posts as $pop_post) {
                                                if ($pop_post->ID == get_the_ID()){
                                                    echo '<span class="atbd_badge atbd_badge_popular">Popular</span>';
                                                }
                                            }
                                        }
                                    }
                                ?>
                            </div>
                            <div class="atbd_listting_category">
                                <ul class="directory_cats">
                                    <?php
                                    if (!empty($cats)) {
                                        echo '<span class="fa fa-folder-open"></span>';
                                        $numberOfCat = count($cats);
                                        $output = array();
                                        foreach ($cats as $cat) {
                                            $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                            $space = str_repeat(' ', 1);
                                            $output []= "{$space}<a href='{$link}'>{$cat->name}</a>";
                                        }
                                            ?>
                                            <li>
                                                <p class="directory_tag">

                                                    <span>
                                                    <?php echo join(',',$output);?>
                                                </span>
                                                </p>
                                            </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>

                        </div>

                        <div class="atbd_listing_title">
                            <h2><?php echo esc_html($p_title); ?></h2>
                            <?php
                            /**
                             * @since 4.5.2
                             * It fires after the title in single listing
                             */
                            do_action('atbdp_single_listing_after_title');
                            ?>
                            <?php if(!empty($tagline)) {?>
                            <p class="atbd_sub_title"><?= (!empty($tagline)) ? esc_html(stripslashes($tagline)) : ''; ?></p>
                            <?php } ?>
                        </div>

                        <div class="about_detail">
                            <?php
                            /*
                             * Automatic embedding done by WP by hooking to the_content filter
                             * As we are outputting the data on the content filter before them, therefore it is our duty to parse the embed using the WP_Embed object manually.
                             * Here run_shortcode() will parse [embed]url[embed]
                             * and autoembed() will parse any embeddable url like https://youtube.com/?v=vidoecode etc.
                             * then do_shortcode() will parse the rest of the shortcodes
                             * */
                            $post_object = get_post(get_the_ID());

                            $content =  apply_filters('get_the_content',$post_object->post_content);
                            echo do_shortcode(wpautop($content));
                            /*
                            global $wp_embed;
                            $cont = $wp_embed->autoembed($wp_embed->run_shortcode(wp_kses_post($post->post_content)));
                            echo do_shortcode($cont);*/
                            ?>


                        </div>
                    </div>
                </div>
            </div> <!-- end .atbd_listing_details -->
            <?php
            $term_id = get_post_meta($post->ID, '_admin_category_select', true);
            $meta_array = array('relation'=>'AND');
            $meta_array =array(
                    'key' => 'category_pass',
                    'value' => $term_id,
                    'compare' => 'EXISTS'
                );

            if (('-1' === $term_id) || empty($term_id)){
                $post_ids_array = $cats; //this array will be dynamically generated
                if (!empty($post_ids_array)){
                    $meta_array = array('relation'=>'OR');
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
            $custom_fields  = new WP_Query( array(
                'post_type'      => ATBDP_CUSTOM_FIELD_POST_TYPE,
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'meta_query'  => array(
                        'relation' => 'OR',
                    array(
                            'key'   => 'associate',
                            'value' => 'form',
                            'compare' => 'EXISTS'
                    ),
                    $meta_array
                )
            ) );

            $custom_fields_posts = $custom_fields->posts;
            $has_field_value = array();
            foreach ($custom_fields_posts as $custom_fields_post) {
                setup_postdata($custom_fields_post);
                $has_field_id = $custom_fields_post->ID;
                $has_field_details = get_post_meta($listing_id, $has_field_id, true);
                $has_field_value[] = $has_field_details;
            }
            $has_field = join($has_field_value);

                        if(!empty($has_field)){
                            ?>
                            <div class="atbd_content_module atbd_custom_fields_contents">
                                <div class="atbd_content_module__tittle_area">
                                    <div class="atbd_area_title">
                                        <h4><span class="fa fa-bars atbd_area_icon"></span><?php _e($custom_section_lable, ATBDP_TEXTDOMAIN)?></h4>
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
                                                            } elseif($field_type === 'time'){
                                                                echo date('h:i A', strtotime($field_details));
                                                            }else {
                                                                echo esc_attr(ucwords($field_details));
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
                        ?>

                            <?php

            if ($enable_video_url && !empty($videourl)) { ?>
                <div class="atbd_content_module atbd_custom_fields_contents">
                    <div class="atbd_content_module__tittle_area">
                        <div class="atbd_area_title">
                            <h4><span class="fa fa-video-camera atbd_area_icon"></span><?php _e($video_label, ATBDP_TEXTDOMAIN)?></h4>
                        </div>
                    </div>

                    <div class="atbdb_content_module_contents">
                        <iframe class="atbd_embeded_video embed-responsive-item" src="<?php echo esc_attr(ATBDP()->atbdp_parse_videos($videourl)) ?>"
                                allowfullscreen></iframe>
                    </div>
                </div><!-- end .atbd_custom_fields_contents -->
            <?php } ?>
                <?php do_action('atbdp_after_video_gallery');?>
            <!--Google map section-->
            <?php
            if (!$disable_map && (empty($hide_map)) && !empty($manual_lng || $manual_lat) ) { ?>
                <div class="atbd_content_module">
                    <div class="atbd_content_module__tittle_area">
                        <div class="atbd_area_title">
                            <h4><span class="fa fa fa-map atbd_area_icon"></span><?php _e('Location', ATBDP_TEXTDOMAIN);?></h4>
                        </div>
                    </div>

                    <div class="atbdb_content_module_contents">
                        <div id="gmap" class="atbd_google_map"></div>
                    </div>
                </div><!-- end .atbd_custom_fields_contents -->
            <?php } ?>


            <?php if ((!$hide_contact_info) && !empty($address||$phone||$email||$website||$social) && empty($disable_contact_info) ) { ?>
                <div class="atbd_content_module atbd_contact_information_module">
                    <div class="atbd_content_module__tittle_area">
                        <div class="atbd_area_title">
                            <h4>
                                <span class="fa fa-envelope-o"></span><?php _e('Contact Information', ATBDP_TEXTDOMAIN); ?>
                            </h4>
                        </div>
                    </div>

                    <div class="atbdb_content_module_contents">
                        <div class="atbd_contact_info">
                            <ul>
                                <?php if (!empty($address)) { ?>
                                    <li>
                                        <div class="atbd_info_title"><span
                                                    class="fa fa-map-marker"></span><?php _e('Address', ATBDP_TEXTDOMAIN); ?>
                                        </div>
                                        <div class="atbd_info"><?= esc_html($address); ?></div>
                                    </li>
                                <?php } ?>

                                <?php
                                if (isset($phone) && !is_empty_v($phone)) { ?>
                                    <!-- In Future, We will have to use a loop to print more than 1 number-->
                                    <li>
                                        <div class="atbd_info_title"><span
                                                    class="fa fa-phone"></span><?php _e('Phone', ATBDP_TEXTDOMAIN); ?>
                                        </div>
                                        <div class="atbd_info"><?= esc_html($phone); ?></div>
                                    </li>
                                <?php } ?>

                                <?php if (!empty($email)) { ?>
                                    <li>
                                        <div class="atbd_info_title"><span
                                                    class="fa fa-envelope"></span><?php _e('Email', ATBDP_TEXTDOMAIN); ?>
                                        </div>
                                        <span class="atbd_info"><?= esc_html($email); ?></span>
                                    </li>
                                <?php } ?>

                                <?php if (!empty($website)) { ?>
                                    <li>
                                        <div class="atbd_info_title"><span
                                                    class="fa fa-globe"></span><?php _e('Website', ATBDP_TEXTDOMAIN); ?>
                                        </div>
                                        <a href="<?= esc_url($website); ?>"
                                           class="atbd_info" <?php echo !empty($use_nofollow) ? 'rel="nofollow"': '';?>><?= esc_html($website); ?></a>
                                    </li>
                                <?php } ?>

                            </ul>
                        </div>
                        <?php if (!empty($social) && is_array($social)) { ?>
                            <div class="atbd_director_social_wrap">
                                <?php foreach ($social as $link) {
                                    $n = esc_attr($link['id']);
                                    $l = esc_url($link['url']);
                                    ?>
                                    <a target='_blank' href="<?php echo $l;?>"><span class="fa fa-<?php echo $n;?>"></span></a>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div><!-- end .atbd_custom_fields_contents -->
            <?php } ?>
            <?php if(!$hide_contact_owner && empty($disable_contact_owner)) { ?>
            <div class="atbd_content_module atbd_contact_information_module">
                <div class="atbd_content_module__tittle_area">
                    <div class="atbd_area_title">
                        <h4>
                            <span class="fa fa-envelope-o"></span><?php _e('Contact Listing Owner', ATBDP_TEXTDOMAIN); ?>
                        </h4>
                    </div>
                </div>
                <form id="atbdp-contact-form" class="form-vertical" role="form">
                    <div class="form-group">
                        <input type="text" class="form-control" id="atbdp-contact-name" placeholder="<?php _e( 'Name', ATBDP_TEXTDOMAIN ); ?>" required />
                    </div>

                    <div class="form-group">
                        <input type="email" class="form-control" id="atbdp-contact-email" placeholder="<?php _e( 'Email', ATBDP_TEXTDOMAIN ); ?>" required />
                    </div>

                    <div class="form-group">
                        <textarea class="form-control" id="atbdp-contact-message" rows="3" placeholder="<?php _e( 'Message', ATBDP_TEXTDOMAIN ); ?>..." required ></textarea>
                    </div>
                    <?php
                    /**
                     * It fires before contact form in the widget area
                     * @since 4.4.0
                     */

                    do_action('atbdp_before_submit_contact_form_inWidget');
                    ?>
                    <p id="atbdp-contact-message-display"></p>

                    <button type="submit" class="btn btn-primary"><?php _e( 'Submit', ATBDP_TEXTDOMAIN ); ?></button>
                </form>
            </div>
            <?php } ?>
            <?php
            // if business hour is active then add the following markup...
            $plan_hours = true;
            if (is_fee_manager_active()){
                $plan_hours = is_plan_allowed_business_hours($fm_plan);
            }
            if (is_business_hour_active() && $plan_hours && empty($disable_bz_hour_listing) && (!is_empty_v($business_hours) || !empty($enable247hour))) {
                BD_Business_Hour()->show_business_hour_module($business_hours, $business_hour_title, $enable247hour); // show the business hour in an unordered list
            } ?>


            <?php
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
            ?>


            <?php
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

<script>

    jQuery(document).ready(function ($) {
        // Do not show map if lat long is empty or map is globally disabled.
        <?php if (!$disable_map && (!empty($manual_lat) && !empty($manual_lng))){ ?>
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
        <?php } ?>
    }); // ends jquery ready function.


</script>



