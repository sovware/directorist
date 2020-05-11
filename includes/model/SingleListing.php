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
        $this->id = $id;
    }

    public function get_id() {
        return $this->id;
    }

    public function render_shortcode_top_area() {

        if ( !is_singular( ATBDP_POST_TYPE ) ) {
            return;
        }

        $id = $this->get_id();

        $full_image_links     = array();
        $listing_img          = get_post_meta($post->ID, '_listing_img', true);
        $display_slider_image = get_directorist_option('dsiplay_slider_single_page', 1);
        $listing_imgs         = (!empty($listing_img) && !empty($display_slider_image)) ? $listing_img : array();
        foreach ($listing_imgs as $id) {
            $full_image_links[$id] = atbdp_get_image_source($id, 'large');
        }

        $args = array(
            'listing_id'                   => $this->get_id(),
            'listing_details_text'         => apply_filters('atbdp_single_listing_details_section_text', get_directorist_option('listing_details_text', __('Listing Details', 'directorist'))),
            'enable_favourite'             => get_directorist_option('enable_favourite', 1),
            'enable_social_share'          => get_directorist_option('enable_social_share', 1),
            'p_title'                      => get_the_title(),
            'p_lnk'                        => get_the_permalink(),
            'enable_report_abuse'          => get_directorist_option('enable_report_abuse', 1),
            'listing_prv_img'              => get_post_meta($id, '_listing_prv_img', true),
            'display_prv_image'            => get_directorist_option('dsiplay_prv_single_page', 1),
            'display_slider_image'         => $display_slider_image,
            'full_image_links'             => $full_image_links,
            'enable_review'                => get_directorist_option('enable_review', 'yes'),
            'is_disable_price'             => get_directorist_option('disable_list_price'),
            'price'                        => get_post_meta($id, '_price', true),
            'price_range'                  => get_post_meta($id, '_price_range', true),
            'atbd_listing_pricing'         => get_post_meta($id, '_atbd_listing_pricing', true),
            'enable_new_listing'           => get_directorist_option('display_new_badge_cart', 1),
            'display_feature_badge_single' => get_directorist_option('display_feature_badge_cart', 1),
            'display_popular_badge_single' => get_directorist_option('display_popular_badge_cart', 1),
            'featured'                     => get_post_meta($id, '_featured', true),
            'feature_badge_text'           => get_directorist_option('feature_badge_text', 'Feature'),
            'popular_badge_text'           => get_directorist_option('popular_badge_text', 'Popular'),
            'cats'                         => get_the_terms($id, ATBDP_CATEGORY),
            'listing_id'                   => $this->get_id(),
            'tagline'                      => get_post_meta($id, '_tagline', true),
            'display_tagline_field'        => get_directorist_option('display_tagline_field', 0),
        );

        return atbdp_return_shortcode_template( 'single-listing/listing-header', $args );
    }


}