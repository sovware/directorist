<?php
/**
 * @author AazzTech
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Directorist_Single_Listing {

    public $id;

    public function __construct( $id = '' ) {
        if ( ! $id ) {
            $id = get_the_ID();
        }
        $this->id = (int) $id;
    }

    public function get_id() {
        return $this->id;
    }

    public function social_share_data() {
        $title = get_the_title();
        $link  = get_the_permalink();

        $result = array(
            'facebook' => array(
                'title' => __('Facebook', 'directorist'),
                'icon'  => atbdp_icon_type() . '-facebook',
                'link'  => "https://www.facebook.com/share.php?u={$link}&title={$title}",
            ),
            'twitter' => array(
                'title' => __('Twitter', 'directorist'),
                'icon'  => atbdp_icon_type() . '-twitter',
                'link'  => 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $link,
            ),
            'linkedin' => array(
                'title' => __('LinkedIn', 'directorist'),
                'icon'  => atbdp_icon_type() . '-linkedin',
                'link'  => "http://www.linkedin.com/shareArticle?mini=true&url={$link}&title={$title}",
            ),
        );

        return $result;
    }

    public function the_header_actions() {
        $args = array(
            'listing_id'           => $this->get_id(),
            'enable_favourite'     => get_directorist_option('enable_favourite', 1),
            'enable_social_share'  => get_directorist_option('enable_social_share', 1),
            'social_share_data'    => $this->social_share_data(),
            'enable_report_abuse'  => get_directorist_option('enable_report_abuse', 1),
        );

        $html = atbdp_return_shortcode_template( 'single-listing/listing-header-actions', $args );
        
        /**
         * @since 5.0
         */
        echo apply_filters('atbdp_header_before_image_slider', $html);
    }

    public function the_slider() {

        $id = $this->get_id();
        $fm_plan              = get_post_meta($id, '_fm_plans', true);
        $full_image_links     = array();
        $listing_img          = get_post_meta($id, '_listing_img', true);
        $display_slider_image = get_directorist_option('dsiplay_slider_single_page', 1);
        $listing_imgs         = (!empty($listing_img) && !empty($display_slider_image)) ? $listing_img : array();

        foreach ($listing_imgs as $value) {
            $full_image_links[$value] = atbdp_get_image_source($value, 'large');
        }

        $display_prv_image = get_post_meta($id, '_listing_prv_img', true);
        $listing_prv_img = get_post_meta($id, '_listing_prv_img', true);
        $listing_prv_imgurl = !empty($listing_prv_img) ? atbdp_get_image_source($listing_prv_img, 'large') : '';
        $plan_slider = true;
        if (is_fee_manager_active()) {
            $plan_slider = is_plan_allowed_slider($fm_plan);
        }

        $args = array(
            'image_links'        => $full_image_links,
            'display_prv_image'  => $display_prv_image,
            'listing_prv_imgurl' => $listing_prv_imgurl,
            'plan_slider'        => $plan_slider,
            'listing_prv_img'    => $listing_prv_img,
            'p_title'            => get_the_title(),
        );

        $slider = get_plasma_slider($args);

        echo apply_filters('atbdp_single_listing_gallery_section', $slider);
    }

    public function the_header_meta() {
        $id = $this->get_id();
        $fm_plan = get_post_meta($id, '_fm_plans', true);

        $reviews_count = ATBDP()->review->db->count(array('post_id' => $id));
        $reviews = (($reviews_count > 1) || ($reviews_count === 0)) ? __(' Reviews', 'directorist') : __(' Review', 'directorist');
        $review_count_html = $reviews_count . $reviews;

        $args = array(
            'listing_id'                   => $this->get_id(),
            'plan_price'                   => is_fee_manager_active() ? is_plan_allowed_price($fm_plan) : true,
            'plan_average_price'           => is_fee_manager_active() ? is_plan_allowed_average_price_range($fm_plan) : true,
            'enable_review'                => get_directorist_option('enable_review', 'yes'),
            'is_disable_price'             => get_directorist_option('disable_list_price'),
            'review_count_html'            => $review_count_html,
            'price'                        => get_post_meta($id, '_price', true),
            'price_range'                  => get_post_meta($id, '_price_range', true),
            'atbd_listing_pricing'         => get_post_meta($id, '_atbd_listing_pricing', true),
            'enable_new_listing'           => get_directorist_option('display_new_badge_cart', 1),
            'display_feature_badge_single' => get_directorist_option('display_feature_badge_cart', 1),
            'display_popular_badge_single' => get_directorist_option('display_popular_badge_cart', 1),
            'featured'                     => get_post_meta($id, '_featured', true),
            'feature_badge_text'           => get_directorist_option('feature_badge_text', 'Feature'),
            'popular_badge_text'           => get_directorist_option('popular_badge_text', 'Popular'),
            'cat_list'                     => get_the_term_list($id, ATBDP_CATEGORY,'',', '),
        );

        $html = atbdp_return_shortcode_template( 'single-listing/listing-header-meta', $args );
        
        /**
         * @since 5.0
         */
        echo apply_filters('atbdp_before_listing_title', $html);
    }

    public function render_shortcode_top_area() {

        if ( !is_singular( ATBDP_POST_TYPE ) ) {
            return;
        }

        $id      = $this->get_id();
        $fm_plan = get_post_meta($id, '_fm_plans', true);
        $post    = get_post($id);
        $content = apply_filters('get_the_content', $post->post_content);
        $content = do_shortcode(wpautop($content));

        $args = array(
            'listing_id'                   => $id,
            'listing_details_text'         => apply_filters('atbdp_single_listing_details_section_text', get_directorist_option('listing_details_text', __('Listing Details', 'directorist'))),
            'p_title'                      => get_the_title(),
            'fm_plan'                      => $fm_plan,
            'tagline'                      => get_post_meta($id, '_tagline', true),
            'display_tagline_field'        => get_directorist_option('display_tagline_field', 0),
            'content'                      => $content,
        );

        return atbdp_return_shortcode_template( 'single-listing/listing-header', $args );
    }

    public function render_shortcode_custom_fields() {

        if ( !is_singular( ATBDP_POST_TYPE ) ) {
            return;
        }

        
        
    }


}