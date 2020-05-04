<?php
/**
 * @author AazzTech
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Listing_Forms {

    public $add_listing_id;
    public $add_listing_post;

    public function __construct() {
        $this->add_listing_id   = get_query_var('atbdp_listing_id', 0);
        $this->add_listing_post = !empty($this->add_listing_id) ? get_post($this->add_listing_id) : '';
    }

    public function get_add_listing_id() {
        return $this->add_listing_id;
    }

    public function get_add_listing_post() {
        return $this->add_listing_post;
    }
    
    public function render_shortcode_add_listing($atts) {
        ob_start();
        $include = apply_filters('include_style_settings', true);
        if ($include) {
            include ATBDP_DIR . 'public/assets/css/style.php';
        }
        wp_enqueue_script('adminmainassets');
        $guest_submission = get_directorist_option('guest_listings', 0);

        if ( false === $guest_submission && ! atbdp_logged_in_user() ) {
            return $this->guard( ['type' => 'auth'] );
        }

        global $wp;
        global $pagenow;
        $current_url = home_url(add_query_arg(array(), $wp->request));

        $p_id = get_query_var('atbdp_listing_id', 0);
        if (!empty($p_id)) {
            $p_id = absint($p_id);
            $listing = get_post($p_id);
            // kick the user out if he tries to edit the listing of other user
            if ($listing->post_author != get_current_user_id() && !current_user_can('edit_others_at_biz_dirs')) {
                echo '<p class="error">' . __('You do not have permission to edit this listing', 'directorist') . '</p>';
                return;
            }
            $listing_info['never_expire'] = get_post_meta($p_id, '_never_expire', true);
            $listing_info['featured'] = get_post_meta($p_id, '_featured', true);
            $listing_info['listing_type'] = get_post_meta($p_id, '_listing_type', true);
            $listing_info['price'] = get_post_meta($p_id, '_price', true);
            $listing_info['videourl'] = get_post_meta($p_id, '_videourl', true);
            $listing_info['price_range'] = get_post_meta($p_id, '_price_range', true);
            $listing_info['atbd_listing_pricing'] = get_post_meta($p_id, '_atbd_listing_pricing', true);
            $listing_info['listing_status'] = get_post_meta($p_id, '_listing_status', true);
            $listing_info['tagline'] = get_post_meta($p_id, '_tagline', true);
            $listing_info['atbdp_post_views_count'] = get_post_meta($p_id, '_atbdp_post_views_count', true);
            $listing_info['excerpt'] = get_post_meta($p_id, '_excerpt', true);
            $listing_info['address'] = get_post_meta($p_id, '_address', true);
            $listing_info['phone'] = get_post_meta($p_id, '_phone', true);
            $listing_info['phone2'] = get_post_meta($p_id, '_phone2', true);
            $listing_info['fax'] = get_post_meta($p_id, '_fax', true);
            $listing_info['email'] = get_post_meta($p_id, '_email', true);
            $listing_info['website'] = get_post_meta($p_id, '_website', true);
            $listing_info['zip'] = get_post_meta($p_id, '_zip', true);
            $listing_info['social'] = get_post_meta($p_id, '_social', true);
            $listing_info['faqs'] = get_post_meta($p_id, '_faqs', true);
            $listing_info['manual_lat'] = get_post_meta($p_id, '_manual_lat', true);
            $listing_info['manual_lng'] = get_post_meta($p_id, '_manual_lng', true);
            $listing_info['hide_map'] = get_post_meta($p_id, '_hide_map', true);
            $listing_info['bdbh'] = get_post_meta($p_id, '_bdbh', true);
            $listing_info['enable247hour'] = get_post_meta($p_id, '_enable247hour', true);
            $listing_info['disable_bz_hour_listing'] = get_post_meta($p_id, '_disable_bz_hour_listing', true);
            $listing_info['hide_contact_info'] = get_post_meta($p_id, '_hide_contact_info', true);
            $listing_info['hide_contact_owner'] = get_post_meta($p_id, '_hide_contact_owner', true);
            $listing_info['expiry_date'] = get_post_meta($p_id, '_expiry_date', true);
            $listing_info['t_c_check'] = get_post_meta($p_id, '_t_c_check', true);
            $listing_info['privacy_policy'] = get_post_meta($p_id, '_privacy_policy', true);
            $listing_info['id_itself'] = $p_id;

            extract($listing_info);
            $listing_img = atbdp_get_listing_attachment_ids($p_id);
            //for editing page
            $p_tags = wp_get_post_terms($p_id, ATBDP_TAGS);
            $p_locations = wp_get_post_terms($p_id, ATBDP_LOCATION);
            $p_cats = wp_get_post_terms($p_id, ATBDP_CATEGORY);
        }
        // prevent the error if it is not edit listing page when listing info var is not defined.
        if (empty($listing_info)) {
            $listing_info = array();
        }

        $t = get_the_title();
        $t = !empty($t) ? esc_html($t) : __('No Title ', 'directorist');
        $tg = !empty($tagline) ? esc_html($tagline) : '';
        $ad = !empty($address) ? esc_html($address) : '';
        $image = (!empty($listing_img[0])) ? "<img src='" . esc_url(wp_get_attachment_image_url($listing_img[0], 'thumbnail')) . "'>" : '';
        /*build the markup for google map info window*/
        $info_content = "<div class='map_info_window'> <h3> {$t} </h3>";
        $info_content .= "<p> {$tg} </p>";
        $info_content .= $image; // add the image if available
        $info_content .= "<p> {$ad}</p></div>";
        // grab social information
        $social_info = !empty($social) ? (array)$social : array();
        $listing_img = !empty($listing_img) ? (array)$listing_img : array();
        $listing_prv_img = !empty($listing_prv_img) ? $listing_prv_img : '';
        // get the category and location lists/array
        $categories = get_terms(ATBDP_CATEGORY, array('hide_empty' => 0));
        $locations = get_terms(ATBDP_LOCATION, array('hide_empty' => 0));
        $listing_tags = get_terms(ATBDP_TAGS, array('hide_empty' => 0));

        // get the map zoom level from the user settings
        $default_latitude = get_directorist_option('default_latitude', '40.7127753');
        $default_longitude = get_directorist_option('default_longitude', '-74.0059728');
        $map_zoom_level = get_directorist_option('map_zoom_level', 4);
        $disable_price = get_directorist_option('disable_list_price');
        $enable_video_url = get_directorist_option('atbd_video_url', 1);
        $disable_contact_info = get_directorist_option('disable_contact_info');
        $disable_contact_owner = get_directorist_option('disable_contact_owner', 1);
        $display_title_for = get_directorist_option('display_title_for', 0);
        $display_desc_for = get_directorist_option('display_desc_for', 0);
        $display_cat_for = get_directorist_option('display_cat_for', 'users');
        $display_loc_for = get_directorist_option('display_loc_for', 0);
        $multiple_loc_for_user = get_directorist_option('multiple_loc_for_user', 1);
        $multiple_cat_for_user = get_directorist_option('multiple_cat_for_user', 1);
        $display_tag_for = get_directorist_option('display_tag_for', 0);
        $display_tagline_field = get_directorist_option('display_tagline_field', 0);
        $tagline_placeholder = get_directorist_option('tagline_placeholder', __('Your Listing\'s motto or tag-line', 'directorist'));
        $display_tagline_for = get_directorist_option('display_tagline_for', 0);
        $guest_listings = get_directorist_option('guest_listings', 0);
        // get the custom terms and conditions
        $listing_terms_condition_text = get_directorist_option('listing_terms_condition_text');
        $display_pricing_field = get_directorist_option('display_pricing_field', 1);
        $price_placeholder = get_directorist_option('price_placeholder', __('Price of this listing. Eg. 100', 'directorist'));
        $display_price_for = get_directorist_option('display_price_for', 'admin_users');
        $display_price_range_field = get_directorist_option('display_price_range_field', 1);
        $price_range_placeholder = get_directorist_option('price_range_placeholder', __('Price Range', 'directorist'));
        $display_price_range_for = get_directorist_option('display_price_range_for', 'admin_users');
        $display_excerpt_field = get_directorist_option('display_excerpt_field', 0);
        $display_views_count = apply_filters('atbdp_listing_form_view_count_field', get_directorist_option('display_views_count', 1));
        $display_views_count_for = get_directorist_option('display_views_count_for', 1);
        $excerpt_placeholder = get_directorist_option('excerpt_placeholder', __('Short Description or Excerpt', 'directorist'));
        $display_short_desc_for = get_directorist_option('display_short_desc_for', 0);
        $display_address_field = get_directorist_option('display_address_field', 1);
        $display_address_for = get_directorist_option('display_address_for', 0);
        $display_phone_field = get_directorist_option('display_phone_field', 1);
        $display_phone_for = get_directorist_option('display_phone_for', 0);
        $display_phone2_field = get_directorist_option('display_phone_field2', 1);
        $display_phone2_for = get_directorist_option('display_phone2_for', 0);
        $display_fax_field = get_directorist_option('display_fax', 1);
        $display_fax_for = get_directorist_option('display_fax_for', 0);
        $display_email_field = get_directorist_option('display_email_field', 1);
        $display_email_for = get_directorist_option('display_email_for', 0);
        $allow_decimal = get_directorist_option('allow_decimal', 1);
        $display_website_field = get_directorist_option('display_website_field', 1);
        $display_website_for = get_directorist_option('display_website_for', 0);
        $display_zip_field = get_directorist_option('display_zip_field', 1);
        $display_zip_for = get_directorist_option('display_zip_for', 0);
        $zip_placeholder = get_directorist_option('zip_placeholder', __('Enter Zip/Post Code', 'directorist'));
        $display_social_info_field = get_directorist_option('display_social_info_field', 1);
        $display_social_info_for = get_directorist_option('display_social_info_for', 0);
        $display_map_field = get_directorist_option('display_map_field', 1);
        $display_map_for = get_directorist_option('display_map_for', 0);
        $address_placeholder = get_directorist_option('address_placeholder', __('Listing address eg. New York, USA', 'directorist'));
        $website_placeholder = get_directorist_option('website_placeholder', __('Listing Website eg. http://example.com', 'directorist'));
        $display_gallery_field = get_directorist_option('display_gallery_field', 1);
        $display_video_field = get_directorist_option('display_video_field', 1);
        $display_glr_img_for = get_directorist_option('display_glr_img_for', 0);
        $display_video_for = get_directorist_option('display_video_for', 0);
        $select_listing_map = get_directorist_option('select_listing_map', 'google');
        $display_contact_hide = get_directorist_option('display_contact_hide', 1);
        $contact_hide_label = get_directorist_option('contact_hide_label', __('Check it to hide Contact Information for this listing', 'directorist'));
        $container_fluid = is_directoria_active() ? 'container' : 'container-fluid';
        $fm_plan = !empty(get_post_meta($p_id, '_fm_plans', true)) ? get_post_meta($p_id, '_fm_plans', true) : '';
        $currency = get_directorist_option('g_currency', 'USD');
        $plan_cat = array();
        if (is_fee_manager_active()) {
            $plan_cat = is_plan_allowed_category($fm_plan);
        }
        $query_args = array(
            'parent' => 0,
            'term_id' => 0,
            'exclude' => $plan_cat,
            'hide_empty' => 0,
            'orderby' => 'name',
            'order' => 'asc',
            'show_count' => 0,
            'single_only' => 0,
            'pad_counts' => true,
            'immediate_category' => 0,
            'active_term_id' => 0,
            'ancestors' => array()
        );

        if (is_fee_manager_active() && !selected_plan_id()) {
            if ((strpos($current_url, '/edit/') !== false) && ($pagenow = 'at_biz_dir')) {
                ATBDP()->enquirer->add_listing_scripts_styles();
                // ATBDP()->load_template('front-end/add-listing');

                $path = atbdp_get_theme_file("/directorist/shortcodes/forms/add-listing.php");
                if ( $path ) {
                    include $path;
                } else {
                    include ATBDP_TEMPLATES_DIR . "public-templates/shortcodes/forms/add-listing.php";
                }
            } else {
                if (class_exists('ATBDP_Pricing_Plans')) {
                    do_action('atbdp_before_pricing_plan_page_load');
                    ATBDP_Pricing_Plans()->load_template('fee-plans', array('atts' => $atts));
                } else {
                    do_action('atbdp_before_pricing_plan_page_load');
                    DWPP_Pricing_Plans()->load_template('fee-plans', array('atts' => $atts));
                }

            }
        } else {
            ATBDP()->enquirer->add_listing_scripts_styles();
            $path = atbdp_get_theme_file("/directorist/shortcodes/forms/add-listing.php");
            if ( $path ) {
                include $path;
            } else {
                include ATBDP_TEMPLATES_DIR . "public-templates/shortcodes/forms/add-listing.php";
            }
        }
        return ob_get_clean();
    }
}