<?php

/*store all data in an array so that we can pass it to filters for extension to get this value*/
$listing_info['never_expire']       = get_post_meta($post->ID, '_never_expire', true);
$listing_info['featured']           = get_post_meta($post->ID, '_featured', true);
$listing_info['price']              = get_post_meta($post->ID, '_price', true);
$listing_info['videourl']           = get_post_meta($post->ID, '_videourl', true);
$listing_info['listing_status']     = get_post_meta($post->ID, '_listing_status', true);
$listing_info['tagline']            = get_post_meta($post->ID, '_tagline', true);
$listing_info['excerpt']            = get_post_meta($post->ID, '_excerpt', true);
$listing_info['address']            = get_post_meta($post->ID, '_address', true);
$listing_info['phone']              = get_post_meta($post->ID, '_phone', true);
$listing_info['email']              = get_post_meta($post->ID, '_email', true);
$listing_info['website']            = get_post_meta($post->ID, '_website', true);
$listing_info['social']             = get_post_meta($post->ID, '_social', true);
$listing_info['manual_lat']         = get_post_meta($post->ID, '_manual_lat', true);
$listing_info['manual_lng']         = get_post_meta($post->ID, '_manual_lng', true);
$listing_info['listing_img']        = get_post_meta($post->ID, '_listing_img', true);
$listing_info['hide_contact_info']  = get_post_meta($post->ID, '_hide_contact_info', true);
$listing_info['expiry_date']        = get_post_meta($post->ID, '_expiry_date', true);
extract($listing_info);
/*Prepare Listing Image links*/
$listing_imgs= (!empty($listing_img)) ? $listing_img : array();
$image_links = array(); // define a link placeholder variable
foreach ($listing_imgs as $id){
    $image_links[$id]= wp_get_attachment_image_src($id, 'full')[0]; // store the attachment id and url
    //@todo; instead of getting a full size image, define a an image size and then fetch that size and let the user change that image size via a hook.
}

/*Code for Business Hour Extensions*/
/*@todo; Make business hour settings compatible to our new settings panel. It is good to prefix all settings of extensions with their prefix*/
$enable_bh_on_page      = get_directorist_option('enable_bh_on_page', 0 ); // yes or no
$text247                = get_directorist_option('text247',  __('Open 24/7', ATBDP_TEXTDOMAIN)); // text for 24/7 type listing
$business_hour_title    = get_directorist_option('business_hour_title',  __('Business Hour', ATBDP_TEXTDOMAIN)); // text Business Hour Title
$bdbh                   = get_post_meta($post->ID, '_bdbh', true);
$bdbh_ops               = get_post_meta($post->ID, '_bdbh_settings', true);
$business_hours         = !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array(); // arrays of days and times if exist
$bdbh_settings          = !empty($bdbh_ops) ? extract(atbdp_sanitize_array($bdbh_ops)) : array();
/*Code for Business Hour Extensions*/


$manual_lat = (!empty($manual_lat)) ? floatval($manual_lat) : false;
$manual_lng = (!empty($manual_lng)) ? floatval($manual_lng) : false;
$hide_contact_info = !empty($hide_contact_info) ? $hide_contact_info : false;

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
$map_zoom_level = get_directorist_option('map_zoom_level', 16);
$disable_map = get_directorist_option('disable_map', 0);
$disable_sharing = get_directorist_option('disable_sharing', 0);
$disable_contact_info = get_directorist_option('disable_contact_info', 0);
$is_disable_price = get_directorist_option('disable_list_price');
$enable_report_abuse = get_directorist_option('enable_report_abuse', 1);
$enable_video_url = get_directorist_option('atbd_video_url', 1);
$video_label = get_directorist_option('atbd_video_title');
$p_lnk = get_the_permalink();
$p_title = get_the_title();
$featured = get_post_meta(get_the_ID(), '_featured', true);
// make main column size 12 when sidebar or submit widget is active @todo; later make the listing submit widget as real widget instead of hard code
$main_col_size = is_active_sidebar('right-sidebar-listing') || !$disable_s_widget ? 'col-md-8' : 'col-md-12';
?>

<section id="directorist" class="directorist atbd_wrapper">
    <div class="row">
        <div class="<?php echo esc_attr($main_col_size); ?>">
            <div class="atbd_content_module atbd_listing_details">

                <?php /* @todo: Shahadat -> New markup implementation */ ?>
                <div class="atbd_content_module__tittle_area">
                    <div class="atbd_area_title">
                        <h4><span class="fa fa-file-text atbd_area_icon"></span>Listing Details</h4>
                    </div>

                    <div class="atbd_listing_action_area">
                        <div class="atbd_action atbd_save"><span class="fa fa-heart"></span>Save</div>

                        <div class="atbd_action atbd_share">
                            <span class="fa fa-share-alt"></span>Share
                            <?php if (!$disable_sharing) { ?>
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
                                                <span class="fa fa-facebook"></span>Facebook
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo esc_url($twt_lnk); ?>" target="_blank">
                                                <span class="fa fa-twitter"></span>Twitter
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo esc_url($g_lnk); ?>" target="_blank">
                                                <span class="fa fa-google-plus"></span>Google Plus
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo esc_url($in_link); ?>" target="_blank">
                                                <span class="fa fa-linkedin"></span>LinkedIn
                                            </a>
                                        </li>
                                    </ul>
                                </div> <!--Ends social share-->
                            <?php } ?>
                        </div>

                        <?php /* @todo: Shahadat -> moved report abuse from bottom */ ?>
                        <!-- Report Abuse-->
                        <?php
                        if ($enable_report_abuse) { ?>
                            <div class="atbd_action atbd_report" data-toggle="modal" data-target="#atbdp-report-abuse-modal">
                            <?php if (is_user_logged_in()) { ?>

                                <span class="fa fa-flag"></span><?php _e('Report', ATBDP_TEXTDOMAIN); ?>
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
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form id="atbdp-report-abuse-form" class="form-vertical" role="form">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span
                                                        aria-hidden="true">&times;</span></button>
                                            <h3 class="modal-title"
                                                id="atbdp-report-abuse-modal-label"><?php _e('Report Abuse', ATBDP_TEXTDOMAIN); ?></h3>
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
                                            <button type="button" class="btn btn-default"
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
                    <?php if (!empty($image_links)) {
                        ?>
                        <div class="atbd_directry_gallery_wrapper">
                            <div class="atbd_directory_gallery">
                                <?php foreach ($image_links as $image_link) { ?>
                                    <div class="single_image">
                                        <img src="<?= esc_url($image_link); ?>"
                                             alt="<?php esc_attr_e('Details Image', ATBDP_TEXTDOMAIN); ?>">
                                    </div>

                                    <?php
                                    // do not output more than one image if the MI extension is not active
                                    if (!is_multiple_images_active()) break;
                                } ?>
                            </div>

                            <div class="atbd_directory_image_thumbnail">
                                <?php foreach ($image_links as $image_link) { ?>
                                    <div class="single_image">
                                        <img src="<?= esc_url($image_link); ?>"
                                             alt="<?php esc_attr_e('Details Image', ATBDP_TEXTDOMAIN); ?>">
                                    </div>

                                    <?php
                                    // do not output more than one image if the MI extension is not active
                                    if (!is_multiple_images_active()) break;
                                } ?>
                            </div><!-- end /.atbd_directory_image_wrapper -->

                            <?php if (count($image_links) > 1 && is_multiple_images_active()) { ?>
                                <span class="prev fa fa-angle-left"></span>
                                <span class="next fa fa-angle-right"></span>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <div class="atbd_listing_detail">
                        <?php /* @todo: Shahadat -> */ ?>
                        <div class="atbd_data_info">
                            <div class="atbd_listing_meta">
                                <?php
                                atbdp_display_price($price, $is_disable_price);
                                do_action('atbdp_after_listing_price');
                                /**
                                 * Fires after the title and sub title of the listing is rendered on the single listing page
                                 *
                                 * @since 1.0.0
                                 */
                                do_action('atbdp_after_listing_tagline');
                                ?>
                            </div>
                            <div class="atbd_rating_count">
                                <p>26 <span>Reviews</span></p>
                            </div>
                            <div class="atbd_listting_category">
                                <a href="#"><span class="fa fa-glass"></span>Restaurant</a>
                            </div>

                            <?php
                            /* @todo: Shahadat -> Moved featured */
                            /*Print Featured ribbon if it is featured*/
                            if ($featured) {
                                printf(
                                    '<span class="atbd_badge atbd_badge_featured">%s</span>',
                                    esc_html__('Featured', ATBDP_TEXTDOMAIN)
                                );
                            }
                            ?>
                            <span class="atbd_badge atbd_badge_popular">Popular</span>
                        </div>

                        <div class="atbd_listing_title">
                            <h2><?php echo esc_html($p_title); ?></h2>
                            <p class="atbd_sub_title"><?= (!empty($tagline)) ? esc_html(stripslashes($tagline)) : ''; ?></p>
                            <!--@todo: style the price and Add the Currency Symbol or letter, Show the price in the search and all listing pages, and dashboard-->
                        </div>


                        <ul class="directory_tags">
                            <?php
                            $cats = get_the_terms($post->ID, ATBDP_CATEGORY);
                            if (!empty($cats)) {
                                foreach ($cats as $cat) {

                                    ?>
                                    <li>
                                        <p class="directory_tag">
                                            <span class="fa <?= esc_attr(get_cat_icon($cat->term_id)); ?>"
                                                  aria-hidden="true"></span>
                                            <span>
                                                    <a href="<?= ATBDP_Permalink::get_category_archive($cat); ?>">
                                                                <?= $cat->name; ?>
                                                    </a>
                                                </span>
                                        </p>
                                    </li>
                                <?php }
                            }
                            ?>

                        </ul>

                        <div class="about_detail">
                            <?php
                            /*
                             * Automatic embedding done by WP by hooking to the_content filter
                             * As we are outputting the data on the content filter before them, therefore it is our duty to parse the embed using the WP_Embed object manually.
                             * Here run_shortcode() will parse [embed]url[embed]
                             * and autoembed() will parse any embeddable url like https://youtube.com/?v=vidoecode etc.
                             * then do_shortcode() will parse the rest of the shortcodes
                             * */
                            global $wp_embed;
                            $cont = $wp_embed->autoembed($wp_embed->run_shortcode($post->post_content));
                            echo do_shortcode($cont);

                            ?>

                        </div>
                    </div>
                </div>
            </div> <!-- end .atbd_listing_details -->

            <?php /* @todo: Shahadat -> added new contents */ ?>
            <div class="atbd_content_module atbd_custom_fields_contents">
                <div class="atbd_content_module__tittle_area">
                    <div class="atbd_area_title">
                        <h4><span class="fa fa-bars atbd_area_icon"></span>Custom Field Style One</h4>
                    </div>
                </div>

                <div class="atbdb_content_module_contents">
                    <ul class="atbd_custom_fields">
                        <li>
                            <div class="atbd_custom_field_title"><p>Custom Option One</p></div>
                            <div class="atbd_custom_field_content"><p>Curabitur aliquet quam id dui posuere blandit.</p>
                            </div>
                        </li>
                        <li>
                            <div class="atbd_custom_field_title"><p>Custom Option Two</p></div>
                            <div class="atbd_custom_field_content"><p>Curabitur aliquet quam id dui posuere blandit.</p>
                            </div>
                        </li>
                        <li>
                            <div class="atbd_custom_field_title"><p>Custom Option Three</p></div>
                            <div class="atbd_custom_field_content"><p>Curabitur aliquet quam id dui posuere blandit.
                                    Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia is
                                    Curae; Donec velit neque, auctor sit amet aliquam.</p></div>
                        </li>
                    </ul>
                </div>
            </div><!-- end .atbd_custom_fields_contents -->

            <?php /* @todo: Shahadat -> added new contents */ ?>
            <?php if ($enable_video_url && !empty($video_url)) { ?>
                <div class="atbd_content_module atbd_custom_fields_contents">
                    <div class="atbd_content_module__tittle_area">
                        <div class="atbd_area_title">
                            <h4><span class="fa fa-video-camera atbd_area_icon"></span>See Our Video</h4>
                        </div>
                    </div>

                    <div class="atbdb_content_module_contents">
                        <iframe class="atbd_embeded_video embed-responsive-item" src="<?php echo $video_url; ?>"
                                allowfullscreen></iframe>
                    </div>
                </div><!-- end .atbd_custom_fields_contents -->
            <?php } ?>

            <?php /* @todo: Shahadat -> added new contents */ ?>
            <!--Google map section-->
            <?php /*@todo; add a settings to toggle the display of map for individual listing or all listings.*/
            if (!$disable_map && (!empty($manual_lat) && !empty($manual_lng))) { ?>
                <div class="atbd_content_module">
                    <div class="atbd_content_module__tittle_area">
                        <div class="atbd_area_title">
                            <h4><span class="fa fa fa-map atbd_area_icon"></span>Location</h4>
                        </div>
                    </div>

                    <div class="atbdb_content_module_contents">
                        <div id="gmap" class="atbd_google_map"></div>
                    </div>
                </div><!-- end .atbd_custom_fields_contents -->
            <?php } ?>


            <?php /* @todo: Shahadat -> added new contents */ ?>
            <?php if (!$disable_contact_info || !$hide_contact_info) { ?>
                <div class="atbd_content_module atbd_custom_fields_contents">
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
                                        <div class="atbd_info"><?= esc_html($phone[0]); ?></div>
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
                                           class="atbd_info"><?= esc_html($website); ?></a>
                                    </li>
                                <?php } ?>

                            </ul>
                        </div>
                    </div>
                </div><!-- end .atbd_custom_fields_contents -->
            <?php } ?>

            <?php
            // if business hour is active then add the following markup...
            if (is_business_hour_active() && $enable_bh_on_page && (!is_empty_v($business_hours) || !empty($enable247hour))) {
            ?>
            <div class="row">
                <!-- we need to add a row when business hour extension is active in order to divide the width in two columns-->
                <div class="col-md-5">
                    <!-- Opening/Business hour Information section-->
                    <div class="opening_hours">
                        <div class="directory_are_title">
                            <h4><span class="fa fa-calendar-o"></span><?php echo esc_html($business_hour_title); ?></h4>
                        </div>
                        <div class="directory_open_hours">
                            <?php
                            // if 24 hours 7 days open then show it only, otherwise, show the days and its opening time.
                            if (!empty($enable247hour)) {
                                echo '<p>' . esc_html($text247) . '</p>';
                            } else {
                                BD_Business_Hour()->show_business_hour($business_hours); // show the business hour in an unordered list
                            } ?>
                        </div> <!--ends .directory_open_hours -->
                    </div> <!--ends. .opening hours-->
                </div> <!--ends. .col-md-5-->
                <!-- video -->
                <div class="col-md-7">
                    <?php } ?>
                    <?php if (!empty($social) && is_array($social)) { ?>
                        <div class="director_social_wrap">
                            <p><?php _e('Social Link', ATBDP_TEXTDOMAIN); ?></p>
                            <ul>
                                <?php foreach ($social as $link) {
                                    $n = esc_attr($link['id']);
                                    $l = esc_url($link['url']);
                                    echo "<li><a href='{$l}'><span class='fa fa-{$n}'></span></a></li>";
                                    ?>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                    <!--We need to close the row and col div when we have business hour enabled. We used negative checking so that they can show by default if the setting is not set by the user after adding the plugin.-->
                    <?php if (is_business_hour_active() && $enable_bh_on_page && (!is_empty_v($business_hours) || !empty($enable247hour))) {
                    ?>
                </div> <!--ends. .col-md-7 wrapper before contact information-->
            </div> <!-- ends .row-->
        <?php } ?>



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
            ?>


            <?php

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



