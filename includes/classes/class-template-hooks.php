<?php
/**
 * @author AazzTech
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Template_Hooks {

    protected static $instance = null;

    private function __construct( $id = '' ) {

        // Author Profile
        $author = Directorist_Listing_Author::instance();
        add_action( 'directorist_author_profile_content', array( $author, 'header_template' ) );
        add_action( 'directorist_author_profile_content', array( $author, 'about_template' ), 15 );
        add_action( 'directorist_author_profile_content', array( $author, 'author_listings_template' ), 20 );

        // Dashboard
        $dashboard = Directorist_Listing_Dashboard::instance();
        add_action( 'directorist_dashboard_before_container', array( $dashboard, 'alert_message_template' ) );
        add_action( 'directorist_dashboard_title_area',       array( $dashboard, 'section_title' ) );
        add_action( 'directorist_dashboard_navigation',       array( $dashboard, 'nav_tabs_template' ) );
        add_action( 'directorist_dashboard_navigation',       array( $dashboard, 'nav_buttons_template' ), 15 );
        add_action( 'directorist_dashboard_tab_contents',     array( $dashboard, 'tab_contents_html' ) );

        // Add Listing
        $forms = Directorist_Listing_Forms::instance();
        add_action( 'directorist_add_listing_title',            array( $forms, 'add_listing_title_template' ) );
        add_action( 'directorist_add_listing_contents',         array( $forms, 'add_listing_general_template' ) );
        add_action( 'directorist_add_listing_contents',         array( $forms, 'add_listing_contact_template' ), 15 );
        add_action( 'directorist_add_listing_contents',         array( $forms, 'add_listing_map_template' ), 20 );
        add_action( 'directorist_add_listing_contents',         array( $forms, 'add_listing_image_template' ), 25 );
        add_action( 'directorist_add_listing_contents',         array( $forms, 'add_listing_submit_template' ), 30 );
        add_action( 'directorist_add_listing_before_location',  array( $forms, 'add_listing_custom_fields_template' ) );

        // Listing Archive
        add_action( 'directorist_archive_header',    array( __CLASS__, 'archive_header' ) );

        // Listings Badges - Grid View
        add_filter( 'atbdp_upper_badges',      array( __CLASS__, 'business_hours_badge') );
        add_filter( 'atbdp_grid_lower_badges', array( __CLASS__, 'featured_badge') );
        add_filter( 'atbdp_grid_lower_badges', array( __CLASS__, 'popular_badge'), 15 );
        add_filter( 'atbdp_grid_lower_badges', array( __CLASS__, 'new_listing_badge'), 20 );

        // Listings Badges - List View
        add_filter( 'atbdp_list_lower_badges', array( __CLASS__, 'featured_badge_list_view') );
        add_filter( 'atbdp_list_lower_badges', array( __CLASS__, 'populer_badge_list_view'), 15 );
        add_filter( 'atbdp_list_lower_badges', array( __CLASS__, 'new_badge_list_view'), 20 );

        // Listings Top - List View
        add_action( 'directorist_list_view_listing_meta_end', array( __CLASS__, 'list_view_business_hours') );
        add_action( 'directorist_list_view_top_content_end',  array( __CLASS__, 'mark_as_favourite_button') );
        
        // Listing Thumbnail Area
        add_action( 'atbdp_listing_thumbnail_area', array( __CLASS__, 'mark_as_favourite_button') );
    }

    // instance
    public static function instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public static function mark_as_favourite_button() {
        $display_mark_as_fav = get_directorist_option( 'display_mark_as_fav', 1 );
        
        if ( ! empty( $display_mark_as_fav ) ) {
            echo atbdp_listings_mark_as_favourite( get_the_ID() );
        }
    }

    public static function featured_badge( $content ) {
        $featured = get_post_meta( get_the_ID(), '_featured', true );
        $display_feature_badge_cart = get_directorist_option( 'display_feature_badge_cart', 1 ) ? true : false;
        $feature_badge_text         = get_directorist_option( 'feature_badge_text', __( 'Featured', 'directorist' ) );

        if ( $featured && $$display_feature_badge_cart ) {
            $badge_html = '<span class="atbd_badge atbd_badge_featured">' . $feature_badge_text. '</span>';
            return $content . $badge_html;
        }

        return $content;
    }

    public static function popular_badge( $content ) {
        $popular_listing_id = atbdp_popular_listings(get_the_ID());
        $display_popular_badge_cart = get_directorist_option( 'display_popular_badge_cart', 1 ) ? true : false;
        $popular_badge_text         = get_directorist_option( 'popular_badge_text', __( 'Popular', 'directorist' ) );
    
        if ($popular_listing_id === get_the_ID() && $display_popular_badge_cart) {
            $badge = '<span class="atbd_badge atbd_badge_popular">' . $popular_badge_text . '</span>';
            return $content . $badge;
        }

        return $content;
    }

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

    public static function business_hours_badge( $content ) {
        $plan_hours = true;
        if (is_fee_manager_active()) {
            $plan_hours = is_plan_allowed_business_hours(get_post_meta(get_the_ID(), '_fm_plans', true));
        }

        $bdbh = get_post_meta( get_the_ID(), '_bdbh', true );
        $business_hours = ! empty( $bdbh ) ? atbdp_sanitize_array( $bdbh ) : array();
        $disable_bz_hour_listing = get_post_meta( get_the_ID(), '_disable_bz_hour_listing', true );
        $enable247hour = get_post_meta( get_the_ID(), '_enable247hour', true );

        $u_badge_html = '';
        if (is_business_hour_active() && $plan_hours && empty($disable_bz_hour_listing)) {
            // lets check is it 24/7
            $open = get_directorist_option('open_badge_text', __('Open Now', 'directorist'));

            if (!empty($enable247hour)) {
              $u_badge_html .= ' <span class="atbd_badge atbd_badge_open">' . $open . '</span>';
            }
            else {
              $bh_statement = BD_Business_Hour()->show_business_open_close($business_hours);
  
              return $content . "\n" . "$bh_statement";
            }
        }

        return $content;
    }

    public static function new_badge_list_view( $content ) {
        $content .= new_badge();

        return $content;
    }

    public static function populer_badge_list_view( $content ) {
        $display_popular_badge_cart = get_directorist_option('display_popular_badge_cart', 1);
        $popular_badge_text = get_directorist_option('popular_badge_text', 'Popular');

        if ( atbdp_popular_listings(get_the_ID()) === get_the_ID() && !empty($display_popular_badge_cart)) {
            $badge = "<span class='atbd_badge atbd_badge_popular'>$popular_badge_text;</span>";
            $content .= $badge;
        }

        return $content;
    }

    public static function featured_badge_list_view( $content ) {
        $featured = get_post_meta(get_the_ID(), '_featured', true);
        $display_feature_badge_cart = get_directorist_option('display_feature_badge_cart', 1);
        $feature_badge_text = get_directorist_option('feature_badge_text', 'Featured');

        if ( $featured && !empty( $display_feature_badge_cart ) ) {
            $badge = "<span class='atbd_badge atbd_badge_featured'>$feature_badge_text</span>";
            $content .= $badge;
        }

        return $content;
    }

    public static function list_view_business_hours() {
        $content = '';
        $plan_hours              = true;
        $disable_bz_hour_listing = get_post_meta(get_the_ID(), '_disable_bz_hour_listing', true);
        $enable247hour           = get_post_meta(get_the_ID(), '_enable247hour', true);
        $bdbh                    = get_post_meta(get_the_ID(), '_bdbh', true);
        $business_hours          = !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array();

        if (is_fee_manager_active()) {
            $plan_hours = is_plan_allowed_business_hours(get_post_meta(get_the_ID(), '_fm_plans', true));
        }

        if (is_business_hour_active() && $plan_hours && empty($disable_bz_hour_listing)) {
            // lets check is it 24/7
            if ( ! empty($enable247hour) ) {
                 $content = "<span class='atbd_badge atbd_badge_open'>" . get_directorist_option('open_badge_text') . "</span>";
            } else {
                // show the business hour in an unordered list
                $content = BD_Business_Hour()->show_business_open_close($business_hours, false); 
            }
        }

        echo $content;
    }

    public static function archive_header($listings) {
        if ( !$listings->header ) {
            return;
        }

        atbdp_get_shortcode_template( 'listings-archive/listings-header', array('listings' => $listings) );
    }
}

add_action( 'init', function(){
    Directorist_Template_Hooks::instance();
} );