<?php
/**
 * @author AazzTech
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Template_Hooks {

    protected static $instance = null;

    private function __construct( $id = '' ) {

        // Author Profile
        add_action( 'directorist_author_profile_content', array( __CLASS__, 'author_profile_header' ) );
        add_action( 'directorist_author_profile_content', array( __CLASS__, 'author_profile_about' ), 15 );
        add_action( 'directorist_author_profile_content', array( __CLASS__, 'author_profile_listings' ), 20 );

        // Dashboard
        add_action( 'directorist_dashboard_before_container', array( __CLASS__, 'dashboard_alert_message' ) );
        add_action( 'directorist_dashboard_title_area',       array( __CLASS__, 'dashboard_title' ) );
        add_action( 'directorist_dashboard_navigation',       array( __CLASS__, 'dashboard_nav_tabs' ) );
        add_action( 'directorist_dashboard_navigation',       array( __CLASS__, 'dashboard_nav_buttons' ), 15 );
        add_action( 'directorist_dashboard_tab_contents',     array( __CLASS__, 'dashboard_tab_contents' ) );

        // Add Listing
        add_action( 'directorist_add_listing_title',      array( __CLASS__, 'add_listing_title' ) );
        add_action( 'directorist_add_listing_contents',   array( __CLASS__, 'add_listing_general' ) );
        add_action( 'directorist_add_listing_contents',   array( __CLASS__, 'add_listing_contact' ), 15 );
        add_action( 'directorist_add_listing_contents',   array( __CLASS__, 'add_listing_map' ), 20 );
        add_action( 'directorist_add_listing_contents',   array( __CLASS__, 'add_listing_image' ), 25 );
        add_action( 'directorist_add_listing_contents',   array( __CLASS__, 'add_listing_submit' ), 30 );
        add_action( 'atbdp_add_listing_after_excerpt',    array( __CLASS__, 'add_listing_custom_field' ) );

        // Listing Archive
        add_action( 'directorist_archive_header',    array( __CLASS__, 'archive_header' ) );

        // Listings Badges
        add_filter( 'atbdp_upper_badges', array( __CLASS__, 'business_hours_badge'), 10, 1 );
        add_filter( 'atbdp_grid_lower_badges', array( __CLASS__, 'featured_badge'), 10, 1 );
        add_filter( 'atbdp_grid_lower_badges', array( __CLASS__, 'popular_badge'), 10, 1 );
        add_filter( 'atbdp_grid_lower_badges', array( __CLASS__, 'new_listing_badge'), 10, 1 );
        
        // Listing Thumbnail Area
        add_action( 'atbdp_listing_thumbnail_area', array( __CLASS__, 'mark_as_favourite_button'), 10, 1 );
    }

    // instance
    public static function instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    // mark_as_favourite_button
    public static function mark_as_favourite_button() {
        $display_mark_as_fav = get_directorist_option( 'display_mark_as_fav', 1 );
        
        if ( ! empty( $display_mark_as_fav ) ) {
            echo atbdp_listings_mark_as_favourite( get_the_ID() );
        }
    }

    // featured_badge
    public static function featured_badge( $content ) {
        $featured = get_post_meta( get_the_ID(), '_featured', true );
        $feature_badge_text = get_directorist_option( 'feature_badge_text', 'Featured' );

        if ( $featured && !empty($display_feature_badge_cart) ) {
            $badge_html .= '<span class="atbd_badge atbd_badge_featured">' . $feature_badge_text . '</span>';
            return $content . $badge_html;
        }

        return $content;
    }

    // popular_badge
    public static function popular_badge( $content ) {
        $popular_badge_text = get_directorist_option( 'popular_badge_text', 'Popular' );
        $popular_listing_id = atbdp_popular_listings(get_the_ID());

        
        if ($popular_listing_id === get_the_ID() && !empty($display_popular_badge_cart)) {
            $badge = '<span class="atbd_badge atbd_badge_popular">' . $popular_badge_text . '</span>';
            return $content . $badge;
        }

        return $content;
    }

    // new_listing_badge
    public static function new_listing_badge( $content ) {
        global $post;

        $new_listing_time = get_directorist_option('new_listing_day');
        $new_badge_text = get_directorist_option('new_badge_text', 'New');
        $enable_new_listing = get_directorist_option('display_new_badge_cart', 1);
        $each_hours = 60 * 60 * 24; // seconds in a day
        $s_date1 = strtotime(current_time('mysql')); // seconds for date 1
        $s_date2 = strtotime($post->post_date); // seconds for date 2
        $s_date_diff = abs($s_date1 - $s_date2); // different of the two dates in seconds
        $days = round($s_date_diff / $each_hours); // divided the different with second in a day
        $new = '<span class="atbd_badge atbd_badge_new">' . $new_badge_text . '</span>';
        if ($days <= (int)$new_listing_time) {
            if (!empty($enable_new_listing)) {
                return  $content .= $new;
            }

        }

        return $content;
    }

    // business_hours_badge
    public static function business_hours_badge( $content ) {
        $plan_hours = true;
        if (is_fee_manager_active()) {
            $plan_hours = is_plan_allowed_business_hours(get_post_meta(get_the_ID(), '_fm_plans', true));
        }

        $bdbh = get_post_meta( get_the_ID(), '_bdbh', true );
        $business_hours = ! empty( $bdbh ) ? atbdp_sanitize_array( $bdbh ) : array();
        $disable_bz_hour_listing = get_post_meta( get_the_ID(), '_disable_bz_hour_listing', true );

        $u_badge_html = '';
        if (is_business_hour_active() && $plan_hours && empty($disable_bz_hour_listing)) {
            // lets check is it 24/7
            $open = get_directorist_option('open_badge_text', __('Open Now', 'directorist'));

            if (!empty($enable247hour)) {
              $u_badge_html .= ' <span class="atbd_badge atbd_badge_open">' . $open . '</span>';
            } else {
              $bh_statement = BD_Business_Hour()->show_business_open_close($business_hours);
  
              return $content . "\n" . "$bh_statement";
            }
        }

        return $content;
    }

    public static function author_profile_header() {
        $author = new Directorist_Listing_Author();
        $author_id = $author->get_id();

        $args = array(
            'author'          => $author,
            'u_pro_pic'       => get_user_meta($author_id, 'pro_pic', true),
            'avatar_img'      => get_avatar($author_id, apply_filters('atbdp_avatar_size', 96)),
            'author_name'     => get_the_author_meta('display_name', $author_id),
            'user_registered' => get_the_author_meta('user_registered', $author_id),
            'enable_review'   => get_directorist_option('enable_review', 1),
        );

        atbdp_get_shortcode_template( 'author/author-header', $args );
    }

    public static function author_profile_about() {
        $author = new Directorist_Listing_Author();
        $author_id = $author->get_id();

        $bio = get_user_meta($author_id, 'description', true);

        $args = array(
            'author'       => $author,
            'bio'          => $bio,
            'content'      => apply_filters('the_content', $bio),
            'address'      => get_user_meta($author_id, 'address', true),
            'phone'        => get_user_meta($author_id, 'phone', true),
            'email_show'   => get_directorist_option('display_author_email', 'public'),
            'email'        => get_the_author_meta('user_email', $author_id),
            'website'      => get_the_author_meta('user_url', $author_id),
            'facebook'     => get_user_meta($author_id, 'atbdp_facebook', true),
            'twitter'      => get_user_meta($author_id, 'atbdp_twitter', true),
            'linkedIn'     => get_user_meta($author_id, 'atbdp_linkedin', true),
            'youtube'      => get_user_meta($author_id, 'youtube', true),
        );

        atbdp_get_shortcode_template( 'author/author-about', $args );
    }

    public static function author_profile_listings() {
        $author = new Directorist_Listing_Author();
        $author_id = $author->get_id();

        $args = array(
            'author'             => $author,
            'header_title'       => apply_filters('atbdp_author_listings_header_title', 1),
            'author_cat_filter'  => get_directorist_option('author_cat_filter',1),
            'categories'         => get_terms(ATBDP_CATEGORY, array('hide_empty' => 0)),
            'listings'           => apply_filters('atbdp_author_listings', true),
            'all_listings'       => $author->all_listings_query(),
            'paginate'           => get_directorist_option('paginate_author_listings', 1),
            'is_disable_price'   => get_directorist_option('disable_list_price'),
        );

        atbdp_get_shortcode_template( 'author/author-listings', $args );
    }

    public static function dashboard_alert_message() {
        atbdp_get_shortcode_template( 'dashboard/alert-message' );
    }

    public static function dashboard_title( $show_title ) {
        if ('yes' === $show_title) {
            atbdp_get_shortcode_template( 'dashboard/title' );
        }
    }

    public static function dashboard_nav_tabs() {
        $dashboard = new Directorist_Listing_Dashboard();

        $args = array(
            'dashboard_tabs' => $dashboard->get_dashboard_tabs(),
        );

        atbdp_get_shortcode_template( 'dashboard/navigation-tabs', $args );
    }

    public static function dashboard_nav_buttons() {
        atbdp_get_shortcode_template( 'dashboard/nav-buttons' );
    }

    public static function dashboard_tab_contents() {
        $dashboard = new Directorist_Listing_Dashboard();
        $dashboard_tabs = $dashboard->get_dashboard_tabs();

        foreach ($dashboard_tabs as $key => $value) {
            echo $value['content'];
            if (!empty($value['after_content_hook'])) {
                do_action($value['after_content_hook']);
            }
        }
    }

    public static function add_listing_title() {
        $forms = Directorist_Listing_Forms::instance();

        $args = array(
            'p_id' => $forms->get_add_listing_id(),
        );

        atbdp_get_shortcode_template( 'forms/add-listing-title', $args );
    }

    public static function add_listing_general() {
        $forms = Directorist_Listing_Forms::instance();

        $p_id       = $forms->get_add_listing_id();
        $fm_plan    = get_post_meta($p_id, '_fm_plans', true);
        $currency   = get_directorist_option('g_currency', 'USD');

        $plan_cat = array();
        if (is_fee_manager_active()) {
            $plan_cat = is_plan_allowed_category($fm_plan);
        }

        $plan_tag = true;
        if (is_fee_manager_active()) {
            $plan_tag = is_plan_allowed_tag($fm_plan);
        }

        $plan_custom_field = true;
        if (is_fee_manager_active()) {
            $plan_custom_field = is_plan_allowed_custom_fields($fm_plan);
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

        $args = array(
            'p_id'                           => $p_id,
            'listing'                        => $forms->get_add_listing_post(),
            'display_title_for'              => get_directorist_option('display_title_for', 0),
            'title'                          => get_directorist_option('title_label', __('Title', 'directorist')),
            'require_title'                  => get_directorist_option('require_title', 1),
            'display_desc_for'               => get_directorist_option('display_desc_for', 0),
            'long_details'                   => get_directorist_option('long_details_label', __('Long Description', 'directorist')),
            'require_long_details'           => get_directorist_option('require_long_details'),
            'display_tagline_field'          => get_directorist_option('display_tagline_field', 0),
            'display_tagline_for'            => get_directorist_option('display_tagline_for', 0),
            'tagline_label'                  => get_directorist_option('tagline_label', __('Tagline', 'directorist')),
            'tagline_placeholder'            => get_directorist_option('tagline_placeholder', __('Your Listing\'s motto or tag-line', 'directorist')),
            'tagline'                        => get_post_meta($p_id, '_tagline', true),
            'display_price_for'              => get_directorist_option('display_price_for', 'admin_users'),
            'display_price_range_for'        => get_directorist_option('display_price_range_for', 'admin_users'),
            'display_pricing_field'          => get_directorist_option('display_pricing_field', 1),
            'display_price_range_field'      => get_directorist_option('display_price_range_field', 1),
            'plan_average_price'             => is_fee_manager_active() ? is_plan_allowed_average_price_range($fm_plan) : true,
            'plan_price'                     => is_fee_manager_active() ? is_plan_allowed_price($fm_plan) : true,
            'price_range'                    => get_post_meta($p_id, '_price_range', true),
            'atbd_listing_pricing'           => get_post_meta($p_id, '_atbd_listing_pricing', true),
            'pricing_label'                  => get_directorist_option('pricing_label', __('Pricing', 'directorist')),
            'price_label'                    => get_directorist_option('price_label', __('Price', 'directorist')),
            'currency'                       => $currency,
            'require_price'                  => get_directorist_option('require_price'),
            'price_range_label'              => get_directorist_option('price_range_label', __('Price Range', 'directorist')),
            'require_price_range'            => get_directorist_option('require_price_range'),
            'allow_decimal'                  => get_directorist_option('allow_decimal', 1),
            'price'                          => get_post_meta($p_id, '_price', true),
            'price_placeholder'              => get_directorist_option('price_placeholder', __('Price of this listing. Eg. 100', 'directorist')),
            'c_symbol'                       => atbdp_currency_symbol($currency),
            'price_range_placeholder'        => get_directorist_option('price_range_placeholder', __('Price Range', 'directorist')),
            'display_views_count'            => apply_filters('atbdp_listing_form_view_count_field', get_directorist_option('display_views_count', 1)),
            'display_views_count_for'        => get_directorist_option('display_views_count_for', 1),
            'views_count_label'              => get_directorist_option('views_count_label', __('Views Count', 'directorist')),
            'atbdp_post_views_count'         => get_post_meta($p_id, '_atbdp_post_views_count', true),
            'display_excerpt_field'          => get_directorist_option('display_excerpt_field', 0),
            'display_short_desc_for'         => get_directorist_option('display_short_desc_for', 0),
            'excerpt_label'                  => get_directorist_option('excerpt_label', __('Short Description/Excerpt', 'directorist')),
            'require_excerpt'                => get_directorist_option('require_excerpt'),
            'excerpt'                        => get_post_meta($p_id, '_excerpt', true),
            'excerpt_placeholder'            => get_directorist_option('excerpt_placeholder', __('Short Description or Excerpt', 'directorist')),
            'display_loc_for'                => get_directorist_option('display_loc_for', 0),
            'location_label'                 => get_directorist_option('location_label', __('Location', 'directorist')),
            'loc_placeholder'                => get_directorist_option('loc_placeholder', __('Select Location', 'directorist')),
            'require_location'               => get_directorist_option('require_location'),
            'multiple_loc_for_user'          => get_directorist_option('multiple_loc_for_user', 1),
            'query_args'                     => $query_args,
            'plan_tag'                       => $plan_tag,
            'tag_label'                      => get_directorist_option('tag_label', __('Tags', 'directorist')),
            'p_tags'                         => wp_get_post_terms($p_id, ATBDP_TAGS),
            'listing_tags'                   => get_terms(ATBDP_TAGS, array('hide_empty' => 0)),
            'category_label'                 => get_directorist_option('category_label', __('Select Category', 'directorist')),
            'cat_placeholder'                => get_directorist_option('cat_placeholder', __('Select Category', 'directorist')),
            'plan_custom_field'              => $plan_custom_field,
            'plan_cat'                       => $plan_cat,
        );

        atbdp_get_shortcode_template( 'forms/add-listing-general', $args );
    }

    public static function add_listing_contact() {
        $forms   = Directorist_Listing_Forms::instance();

        $p_id    = $forms->get_add_listing_id();
        $fm_plan = get_post_meta($p_id, '_fm_plans', true);
        $social  = get_post_meta($p_id, '_social', true);

        $args = array(
            'p_id'                       => $p_id,
            'display_fax_for'            => get_directorist_option('display_fax_for', 0),
            'display_phone2_for'         => get_directorist_option('display_fax_for', 0),
            'display_phone_for'          => get_directorist_option('display_phone_for', 0),
            'display_address_for'        => get_directorist_option('display_address_for', 0),
            'display_email_for'          => get_directorist_option('display_email_for', 0),
            'display_website_for'        => get_directorist_option('display_website_for', 0),
            'display_zip_for'            => get_directorist_option('display_zip_for', 0),
            'display_social_info_for'    => get_directorist_option('display_social_info_for', 0),
            'display_address_field'      => get_directorist_option('display_address_field', 1),
            'display_phone_field'        => get_directorist_option('display_phone_field', 1),
            'display_phone2_field'       => get_directorist_option('display_phone_field2', 1),
            'display_fax_field'          => get_directorist_option('display_fax', 1),
            'display_email_field'        => get_directorist_option('display_email_field', 1),
            'display_website_field'      => get_directorist_option('display_website_field', 1),
            'display_zip_field'          => get_directorist_option('display_zip_field', 1),
            'display_social_info_field'  => get_directorist_option('display_social_info_field', 1),
            'display_map_for'            => get_directorist_option('display_map_for', 0),
            'display_map_field'          => get_directorist_option('display_map_field', 1),
            'display_contact_hide'       => get_directorist_option('display_contact_hide', 1),
            'hide_contact_info'          => get_post_meta($p_id, '_hide_contact_info', true),
            'contact_hide_label'         => get_directorist_option('contact_hide_label', __('Check it to hide Contact Information for this listing', 'directorist')),
            'disable_contact_owner'      => get_directorist_option('disable_contact_owner', 1),
            'hide_contact_owner'         => get_post_meta($p_id, '_hide_contact_owner', true),
            'address_label'              => get_directorist_option('address_label', __('Google Address', 'directorist')),
            'require_address'            => get_directorist_option('require_address'),
            'address'                    => get_post_meta($p_id, '_address', true),
            'address_placeholder'        => get_directorist_option('address_placeholder', __('Listing address eg. New York, USA', 'directorist')),
            'zip_label'                  => get_directorist_option('zip_label', __('Zip/Post Code', 'directorist')),
            'require_zip'                => get_directorist_option('require_zip'),
            'zip'                        => get_post_meta($p_id, '_zip', true),
            'zip_placeholder'            => get_directorist_option('zip_placeholder', __('Enter Zip/Post Code', 'directorist')),
            'plan_phone'                 => is_fee_manager_active() ? is_plan_allowed_listing_phone($fm_plan) : true,
            'phone_label'                => get_directorist_option('phone_label', __('Phone Number', 'directorist')),
            'require_phone_number'       => get_directorist_option('require_phone_number'),
            'phone_placeholder'          => get_directorist_option('phone_placeholder', __('Phone Number', 'directorist')),
            'phone'                      => get_post_meta($p_id, '_phone', true),
            'phone_label2'               => get_directorist_option('phone_label2', __('Phone Number 2', 'directorist')),
            'phone_placeholder2'         => get_directorist_option('phone_placeholder2', __('Phone Number 2', 'directorist')),
            'require_phone_number2'      => get_directorist_option('require_phone_number2'),
            'phone2'                     => get_post_meta($p_id, '_phone2', true),
            'fax_label'                  => get_directorist_option('fax_label', __('Fax', 'directorist')),
            'fax_placeholder'            => get_directorist_option('fax_placeholder', __('Fax', 'directorist')),
            'require_fax'                => get_directorist_option('require_fax'),
            'fax'                        => get_post_meta($p_id, '_fax', true),
            'plan_email'                 => is_fee_manager_active() ? is_plan_allowed_listing_email($fm_plan) : true,
            'email_label'                => get_directorist_option('email_label', __('Email', 'directorist')),
            'email_placeholder'          => get_directorist_option('email_placeholder', __('Enter Email', 'directorist')),
            'require_email'              => get_directorist_option('require_email'),
            'email'                      => get_post_meta($p_id, '_email', true),
            'plan_webLink'               => is_fee_manager_active() ? is_plan_allowed_listing_webLink($fm_plan) : true,
            'website_label'              => get_directorist_option('website_label', __('Website', 'directorist')),
            'require_website'            => get_directorist_option('require_website'),
            'website'                    => get_post_meta($p_id, '_website', true),
            'website_placeholder'        => get_directorist_option('website_placeholder', __('Listing Website eg. http://example.com', 'directorist')),
            'plan_social_networks'       => is_fee_manager_active() ? is_plan_allowed_listing_social_networks($fm_plan) : true,
            'social_info'                => !empty($social) ? $social : array(),
            'plan_hours'                 => is_fee_manager_active() ? is_plan_allowed_business_hours($fm_plan) : true,
        );

        atbdp_get_shortcode_template( 'forms/add-listing-contact', $args );
    }

    public static function add_listing_map() {
        $forms   = Directorist_Listing_Forms::instance();

        $p_id    = $forms->get_add_listing_id();
        $fm_plan = get_post_meta($p_id, '_fm_plans', true);

        $args = array(
            'p_id'                       => $p_id,
            'display_map_for'            => get_directorist_option('display_map_for', 0),
            'display_map_field'          => get_directorist_option('display_map_field', 1),
            'display_address_for'        => get_directorist_option('display_address_for', 0),
            'display_address_field'      => get_directorist_option('display_address_field', 1),
            'address_label'              => get_directorist_option('address_label', __('Google Address', 'directorist')),
            'require_address'            => get_directorist_option('require_address'),
            'address'                    => get_post_meta($p_id, '_address', true),
            'address_placeholder'        => get_directorist_option('address_placeholder', __('Listing address eg. New York, USA', 'directorist')),
            'select_listing_map'         => get_directorist_option('select_listing_map', 'google'),
            'hide_map'                   => get_post_meta($p_id, '_hide_map', true),
            'display_map_for'            => get_directorist_option('display_map_for', 0),
            'manual_lat'                 => get_post_meta($p_id, '_manual_lat', true),
            'manual_lng'                 => get_post_meta($p_id, '_manual_lng', true),
            'default_latitude'           => get_directorist_option('default_latitude', '40.7127753'),
            'default_longitude'          => get_directorist_option('default_longitude', '-74.0059728'),
        );

        atbdp_get_shortcode_template( 'forms/add-listing-map', $args );
    }

    public static function add_listing_image() {
        $forms   = Directorist_Listing_Forms::instance();

        $p_id        = $forms->get_add_listing_id();
        $fm_plan     = get_post_meta($p_id, '_fm_plans', true);

        $max_size   = get_directorist_option('max_gallery_upload_size', 4);
        $plan_image = get_directorist_option('max_gallery_image_limit', 5);
        $slider_unl = '';
        if (is_fee_manager_active()) {
            $selected_plan = selected_plan_id();
            $planID = !empty($selected_plan) ? $selected_plan : $fm_plan;
            $allow_slider = is_plan_allowed_slider($planID);
            $slider_unl = is_plan_slider_unlimited($planID);
            if (!empty($allow_slider) && empty($slider_unl)) {
                $plan_image = is_plan_slider_limit($planID);
            }
        }

        $args = array(
            'p_id'               => $p_id,
            'title'              => $forms->get_add_listing_image_title(),
            'plan_video'         => $forms->get_plan_video(),
            'plan_slider'        => $forms->get_plan_slider(),
            'listing_img'        => atbdp_get_listing_attachment_ids($p_id),
            'videourl'           => get_post_meta($p_id, '_videourl', true),
            'plan_image'         => $plan_image,
            'slider_unl'         => $slider_unl,
            'req_gallery_image'  => get_directorist_option('require_gallery_img'),
            'max_size_kb'        => (int)$max_size * 1024,
            'gallery_label'      => get_directorist_option('gallery_label', __('Select Files', 'directorist')),
            'listing_img'        => atbdp_get_listing_attachment_ids($p_id),
            'video_label'        => get_directorist_option('video_label', __('Video Url', 'directorist')),
            'video_placeholder'  => get_directorist_option('video_placeholder', __('Only YouTube & Vimeo URLs.', 'directorist')),
            'require_video'      => get_directorist_option('require_video'),
            'videourl'           => get_post_meta($p_id, '_videourl', true),
        );

        atbdp_get_shortcode_template( 'forms/add-listing-image', $args );
    }

    public static function add_listing_submit() {
        $forms = Directorist_Listing_Forms::instance();
        $p_id  = $forms->get_add_listing_id();

        $args = array(
            'p_id'                     => $p_id,
            'guest_listings'           => get_directorist_option('guest_listings', 0),
            'guest_email_label'        => get_directorist_option('guest_email', __('Your Email', 'directorist')),
            'guest_email_placeholder'  => get_directorist_option('guest_email_placeholder', __('example@gmail.com', 'directorist')),
            'terms_label'              => get_directorist_option('terms_label', __('I agree with all', 'directorist')),
            'terms_label_link'         => get_directorist_option('terms_label_link', __('terms & conditions', 'directorist')),
            't_C_page_link'            => ATBDP_Permalink::get_terms_and_conditions_page_url(),
            'privacy_page_link'        => ATBDP_Permalink::get_privacy_policy_page_url(),
            'privacy_label'            => get_directorist_option('privacy_label', __('I agree to the', 'directorist')),
            'privacy_label_link'       => get_directorist_option('privacy_label_link', __('Privacy & Policy', 'directorist')),
            'listing_privacy'          => get_directorist_option('listing_privacy', 1),
            'require_privacy'          => get_directorist_option('require_privacy'),
            'listing_terms_condition'  => get_directorist_option('listing_terms_condition', 1),
            'require_terms_conditions' => get_directorist_option('require_terms_conditions'),
            't_c_check'                => get_post_meta($p_id, '_t_c_check', true),
            'submit_label'             => get_directorist_option('submit_label', __('Save & Preview', 'directorist')),
        );

        atbdp_get_shortcode_template( 'forms/add-listing-submit', $args );
    }

    public static function add_listing_custom_field() {
        $forms  = Directorist_Listing_Forms::instance();
        $fields = $forms->get_custom_fields_query();
        $p_id   = $forms->get_add_listing_id();

        foreach ($fields as $post) {
            $id    = $post->ID;
            $value = get_post_meta($p_id, $id, true);

            $args = array(
                'title'        => get_the_title($id),
                'cf_required'  => get_post_meta($id, 'required', true),
                'instructions' => get_post_meta($id, 'instructions', true),
                'input_field'  => $forms->get_custom_field_input($id,$value),
            );

            atbdp_get_shortcode_template( 'forms/add-listing-custom-fields', $args );
        }
    }

    public static function archive_header($atts = array()) {
        if ( ! empty( $atts['header'] ) && 'yes' !== $atts['header'] ) {
            return '';
        }

        $listing = new Directorist_All_Listings( $atts );

        $args = array(
            'listing' => $listing,
            'container_class' => ( ! empty( $listing->header_container_fluid ) ) ? $listing->header_container_fluid : '',
            'header_title' => $listing->header_title,
            'filters' => $listing->filters,

            'display_viewas_dropdown' => $listing->display_viewas_dropdown,
            'view_as_text' => $listing->view_as_text,
            'display_sortby_dropdown' => $listing->display_sortby_dropdown,
            'text_placeholder' => $listing->text_placeholder,
            'category_placeholder' => $listing->category_placeholder,
            'categories_fields' => $listing->categories_fields,
            'location_placeholder' => $listing->location_placeholder,
            'locations_fields' => $listing->locations_fields,
            'c_symbol' => $listing->c_symbol,
        );

        ob_start();
        atbdp_get_shortcode_template( 'listings-archive/listings-header', $args, $listing, true );
        echo ob_get_clean();
    }
}

Directorist_Template_Hooks::instance();