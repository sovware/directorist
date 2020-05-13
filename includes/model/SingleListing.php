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

    public function get_custom_field_type_value($field_id, $field_type, $field_details) {
        switch ($field_type) {
            case 'color':
                $result = sprintf('<div class="atbd_field_type_color" style="background-color: %s;"></div>', $field_details);
                break;

            case 'date':
                $result = date( get_option( 'date_format') , strtotime($field_details));
                break;

            case 'time':
                $result = date('h:i A', strtotime($field_details));
                break;

            case 'url':
                $result = sprintf('<a href="%s" target="_blank">%s</a>', esc_url($field_details), esc_url($field_details));
                break;

            case 'file':
                $done = str_replace('|||', '', $field_details);
                $name_arr = explode('/', $done);
                $filename = end($name_arr);
                $result = sprintf('<a href="%s" target="_blank" download>%s</a>', esc_url($done), $filename);
                break;

            case 'checkbox':
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
                    }
                    else {
                        $_value = trim($choice);
                        $_label = $_value;
                    }
                    $_checked = '';
                    if (in_array($_value, $values)) {
                        $space = str_repeat(' ', 1);
                        $output[] = "{$space}$_value";
                    }
                }
                $result = join(',', $output);
                break;

            default:
                $content = apply_filters('get_the_content', $field_details);
                $result = do_shortcode(wpautop($content));
                break;
        }

        return $result;
    }

    public function get_custom_field_data() {
        $result = array();

        $id = $this->get_id();

        $args = array(
            'post_type' => ATBDP_CUSTOM_FIELD_POST_TYPE,
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );

        $custom_fields = new WP_Query($args);

        $cats = get_the_terms($id, ATBDP_CATEGORY);
        $category_ids = array();
        if (!empty($cats)) {
            foreach ($cats as $single_val) {
                $category_ids[] = $single_val->term_id;
            }
        }

        $field_ids = array();

        foreach ($custom_fields->posts as $custom_fields_post) {
            $custom_field_id = $custom_fields_post->ID;
            $fields = get_post_meta($custom_field_id, 'associate', true);
            if ('form' != $fields){
                $fields_id_with_cat = get_post_meta($custom_field_id, 'category_pass', true);
                if (in_array($fields_id_with_cat, $category_ids)){
                    $has_field_details = get_post_meta($id, $custom_fields_post->ID, true);
                    if (!empty($has_field_details)){
                        $field_ids[] = $custom_field_id;
                    }
                }
            }
            else {
                $has_field_details = get_post_meta($id, $custom_fields_post->ID, true);
                if (!empty($has_field_details)){
                    $field_ids[] = $custom_field_id;
                }
            }
        }

        foreach ($field_ids as $field_id) {
            $field_details = get_post_meta($id, $field_id, true);
            $field_type    = get_post_meta($field_id, 'type', true);

            if (!empty($field_details)) {
                $result[] = array(
                    'title' => get_the_title($field_id),
                    'value' => $this->get_custom_field_type_value($field_id, $field_type, $field_details)
                );
            }
        }

        return $result;
    }

    public function render_shortcode_custom_fields() {

        if ( !is_singular( ATBDP_POST_TYPE ) ) {
            return;
        }

        $id                 = $this->get_id();
        $fm_plan            = get_post_meta($id, '_fm_plans', true);
        $field_data         = $this->get_custom_field_data();
        $has_custom_fields  = !empty($field_data) ? true : false;

        $args = array(
            'plan_custom_field'  => is_fee_manager_active() ? is_plan_allowed_custom_fields($fm_plan) : true,
            'section_title'      => get_directorist_option('custom_section_lable', __('Details', 'directorist')),
            'has_custom_fields'  => apply_filters('atbdp_single_listing_custom_field', $has_custom_fields),
            'custom_field_data'  => $field_data,
        );

        return atbdp_return_shortcode_template( 'single-listing/custom-field', $args );
    }

	public function render_shortcode_video() {
        if ( !is_singular( ATBDP_POST_TYPE ) ) {
            return;
        }

        $id      = $this->get_id();
        $fm_plan = get_post_meta($id, '_fm_plans', true);

        $args = array(
            'enable_video_url' => get_directorist_option('atbd_video_url', 1),
            'videourl'         => get_post_meta($id, '_videourl', true),
            'plan_video'       => is_fee_manager_active() ? is_plan_allowed_listing_video($fm_plan) : true,
            'video_label'      => get_directorist_option('atbd_video_title', __('Video', 'directorist')),
        );

        return atbdp_return_shortcode_template( 'single-listing/listing-video', $args );
	}

	public function render_shortcode_map() {
        if ( !is_singular( ATBDP_POST_TYPE ) ) {
            return;
        }

        $id      = $this->get_id();
        $fm_plan = get_post_meta($id, '_fm_plans', true);

        $address = get_post_meta($post->ID, '_address', true);
        $ad = !empty($address) ? esc_html($address) : '';

        $display_map_info               = apply_filters('atbdp_listing_map_info_window', get_directorist_option('display_map_info', 1));
        $display_image_map              = get_directorist_option('display_image_map', 1);
        $display_title_map              = get_directorist_option('display_title_map', 1);
        $display_address_map            = get_directorist_option('display_address_map', 1);
        $display_direction_map          = get_directorist_option('display_direction_map', 1);

        $info_content = "";
        if(!empty($display_image_map) || !empty($display_title_map)) {
            $info_content .= "<div class='map-info-wrapper'><div class='map-info-img'>$image</div><div class='map-info-details'><div class='atbdp-listings-title-block'><h3>$t</h3></div>";
        }
        if(!empty($display_address_map) && !empty($ad)) {
            $info_content .= apply_filters("atbdp_address_in_map_info_window", "<address>{$ad}</address>");
        }
        if(!empty($display_direction_map)) {
            $info_content .= "<div class='map_get_dir'><a href='http://www.google.com/maps?daddr={$manual_lat},{$manual_lng}' target='_blank'> " . __('Get Direction', 'directorist') . "</a></div><span id='iw-close-btn'><i class='la la-times'></i></span></div></div>";
        }

        $cats = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
        if(!empty($cats)){
            $cat_icon = get_cat_icon($cats[0]->term_id);
        }
        $cat_icon = !empty($cat_icon) ? $cat_icon : 'fa-map-marker';
        $icon_type = substr($cat_icon, 0,2);
        $fa_or_la = ('la' == $icon_type) ? "la " : "fa ";
        $cat_icon = ('none' == $cat_icon) ? 'fa fa-map-marker' : $fa_or_la . $cat_icon ;

        $args = array(
			'disable_map'           => get_directorist_option('disable_map', 0),
			'hide_map'              => get_post_meta($id, '_hide_map', true),
			'manual_lat'            => get_post_meta($id, '_manual_lat', true),
			'manual_lng'            => get_post_meta($id, '_manual_lng', true),
			'display_map_field'     => apply_filters('atbdp_show_single_listing_map', get_directorist_option('display_map_field', 1)),
			'listing_location_text' => apply_filters('atbdp_single_listing_map_section_text', get_directorist_option('listing_location_text', __('Location', 'directorist'))),
			'select_listing_map'    => get_directorist_option('select_listing_map', 'google'),
			'info_content'          =>  $info_content,
			'display_map_info'      => $display_map_info,
			'map_zoom_level'        => get_directorist_option('map_zoom_level', 16),
			'cat_icon'              => $cat_icon,
        );

        return atbdp_return_shortcode_template( 'single-listing/listing-map', $args );
	}



}