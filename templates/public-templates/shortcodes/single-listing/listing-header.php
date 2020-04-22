<?php
/**
 * @since 5.0
 */
do_action('atbdp_before_listing_section');
?>
<div class="atbd_content_module atbd_listing_details atbdp_listing_ShortCode <?php do_action('atbdp_single_listing_details_class')?>">
    <div class="atbd_content_module_title_area">
        <?php if (!empty($listing_details_text)) { ?>
            <div class="atbd_area_title">
                <h4>
                    <span class="<?php atbdp_icon_type(true); ?>-file-text atbd_area_icon"></span><?php _e($listing_details_text, 'directorist') ?>
                </h4>
            </div>
        <?php } ?>
        <?php
        $listing_header = '<div class="atbd_listing_action_area">';
        if ($enable_favourite) {
            $listing_header .= '<div class="atbd_action atbd_save" id="atbdp-favourites">' . the_atbdp_favourites_link() . '</div>';
        }
        if ($enable_social_share) {
            $listing_header .= '<div class="atbd_action atbd_share">';
            $listing_header .= '<span class="' . atbdp_icon_type() . '-share"></span>' . __('Share', 'directorist') . '';
            $listing_header .= '<div class="atbd_director_social_wrap">';
            //prepare the data for the links because links needs to be escaped
            $twt_lnk = 'https://twitter.com/intent/tweet?text=' . $p_title . '&amp;url=' . $p_lnk;
            $fb_lnk = "https://www.facebook.com/share.php?u={$p_lnk}&title={$p_title}";
            $in_link = "http://www.linkedin.com/shareArticle?mini=true&url={$p_lnk}&title={$p_title}";
            $listing_header .= '                                                               
                         <ul>
                        <li>
                            <a href="' . esc_url($fb_lnk) . '" target="_blank"><span class="' . atbdp_icon_type() . '-facebook"></span>' . __('Facebook', 'directorist') . '</a>
                        </li>
                        <li>
                            <a href="' . esc_url($twt_lnk) . '" target="_blank"><span class="' . atbdp_icon_type() . '-twitter"></span>' . __('Twitter', 'directorist') . '</a>
                           
                        </li>
                        <li>
                            <a href="' . esc_url($in_link) . '" target="_blank"><span class="' . atbdp_icon_type() . '-linkedin"></span>' . __('LinkedIn', 'directorist') . '</a>
                        </li>
                    </ul>';
            $listing_header .= '</div>'; //Ends social share
            $listing_header .= '</div>';
        }
        if ($enable_report_abuse) {
            $public_report = apply_filters('atbdp_allow_public_report', false);
            $listing_header .= '<div class="atbd_action atbd_report">';
            if (atbdp_logged_in_user() || $public_report) {
                $listing_header .= '<span class="' . atbdp_icon_type() . '-flag"></span><a href="" 
                                                               data-target="atbdp-report-abuse-modal">' . __('Report', 'directorist') . '</a>'; //Modal (report abuse form)
            } else {
                $listing_header .= '<a href="javascript:void(0)"
                               class="atbdp-require-login"><span
                                        class="' . atbdp_icon_type() . '-flag"></span>' . __('Report', 'directorist') . '</a>';
            }
            $listing_header .= '<input type="hidden" id="atbdp-post-id" value="' . get_the_ID() . '"/>';
            $listing_header .= '</div>';
        } ?>
        <div class="at-modal atm-fade" id="atbdp-report-abuse-modal">
            <div class="at-modal-content at-modal-md">
                <div class="atm-contents-inner">
                    <a href="" class="at-modal-close"><span aria-hidden="true">&times;</span></a>
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <form id="atbdp-report-abuse-form" class="form-vertical" role="form">
                                <div class="modal-header">
                                    <h3 class="modal-title"
                                        id="atbdp-report-abuse-modal-label"><?php _e('Report Abuse', 'directorist'); ?></h3>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="atbdp-report-abuse-message"><?php _e('Your Complaint', 'directorist'); ?>
                                            <span class="atbdp-star">*</span></label>
                                        <textarea class="form-control" id="atbdp-report-abuse-message"
                                                  rows="3"
                                                  placeholder="<?php _e('Message', 'directorist'); ?>..."
                                                  required></textarea>
                                    </div>
                                    <div id="atbdp-report-abuse-g-recaptcha"></div>
                                    <div id="atbdp-report-abuse-message-display"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit"
                                            class="btn btn-primary"><?php _e('Submit', 'directorist'); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>

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
        
        $listing_prv_imgurl = !empty($listing_prv_img) ? atbdp_get_image_source($listing_prv_img, 'large') : '';
        $gallery_image = '';
        $plan_slider = true;
        if (is_fee_manager_active()) {
            $plan_slider = is_plan_allowed_slider($fm_plan);
        }

        $args = array(
            'image_links' => $full_image_links,
            'display_prv_image' => $display_prv_image,
            'listing_prv_imgurl' => $listing_prv_imgurl,
            'plan_slider' => $plan_slider,
            'listing_prv_img' => $listing_prv_img,
            'p_title' => $p_title,
        );
        $slider = get_plasma_slider($args);
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
                        } elseif($plan_price) {
                            $data_info .= atbdp_display_price($price, $is_disable_price, $currency = null, $symbol = null, $c_position = null, $echo = false);
                        }
                    }
                }
                do_action('atbdp_after_listing_price');
                $average = ATBDP()->review->get_average($post->ID);
                $reviews_count = ATBDP()->review->db->count(array('post_id' => $post->ID)); // get total review count for this post
                if (!empty($enable_review)) {
                    $data_info .= '<span class="atbd_meta atbd_listing_rating">' . $average . '<i class="' . atbdp_icon_type() . '-star"></i>
                            </span>';
                }
                $data_info .= '</div>';
                ?>
                <?php if ($enable_review) {
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
                    $data_info .= '<span class="atbd_badge atbd_badge_featured">' . $feature_badge_text . '</span>';
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
                $data_info .= '<li><span class="' . atbdp_icon_type() . '-tags"></span></li>';
                $numberOfCat = count($cats);
                $output = array();
                foreach ($cats as $cat) {
                    $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                    $space = str_repeat(' ', 1);
                    $output [] = "{$space}<a href='{$link}'>{$cat->name}</a>";
                }
                $data_info .= ' <li><p class="directory_tag"><span>' . join(',', $output) . '</span></p></li>';
                ?>
                <?php
            }
            $data_info .= '</ul></div>';
            $data_info .= '</div>';
            /**
             * @since 5.0
             * It returns data before listing title
             */
            echo apply_filters('atbdp_before_listing_title', $data_info);

            $class = apply_filters('atbdp_single_listing_title_class', 'atbd_listing_title');
            echo '<div class="'.$class.'">';
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
            $listing_content = '<div class="about_detail">';
            /*
             * Automatic embedding done by WP by hooking to the_content filter
             * As we are outputting the data on the content filter before them, therefore it is our duty to parse the embed using the WP_Embed object manually.
             * Here run_shortcode() will parse [embed]url[embed]
             * and autoembed() will parse any embeddable url like https://youtube.com/?v=vidoecode etc.
             * then do_shortcode() will parse the rest of the shortcodes
             * */
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
<?php do_action('atbdp_after_single_listing_details_section'); ?>
<script>
    jQuery(document).ready(function ($) {
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
            asNavFor: '<?php echo !empty($display_thumbnail_img) ? ".atbd_directory_image_thumbnail" : ""; ?>',
            rtl: <?php echo is_rtl() ? 'true' : 'false'; ?>
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
    });
</script>