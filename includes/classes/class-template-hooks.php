<?php
/**
 * @author AazzTech
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Template_Hooks {

	protected static $instance = null;

	private function __construct( $id = '' ) {

        // Author Profile
		add_action( 'directorist_author_profile_content', array( __CLASS__, 'author_profile_header' ), 10 );
		add_action( 'directorist_author_profile_content', array( __CLASS__, 'author_profile_about' ), 15 );
		add_action( 'directorist_author_profile_content', array( __CLASS__, 'author_profile_listings' ), 20 );

        // Dashboard
        add_action( 'directorist_dashboard_before_container', array( __CLASS__, 'dashboard_alert_message' ) );
		add_action( 'directorist_dashboard_title_area',       array( __CLASS__, 'dashboard_title' ) );
		add_action( 'directorist_dashboard_navigation',       array( __CLASS__, 'dashboard_nav_tabs' ), 10 );
		add_action( 'directorist_dashboard_navigation',       array( __CLASS__, 'dashboard_nav_buttons' ), 15 );
		add_action( 'directorist_dashboard_tab_contents',     array( __CLASS__, 'dashboard_tab_contents' ) );

		// Add Listing
		add_action( 'directorist_add_listing_title',      array( __CLASS__, 'add_listing_title' ) );
		add_action( 'directorist_add_listing_contents',   array( __CLASS__, 'add_listing_general' ) );
		add_action( 'atbdp_add_listing_after_excerpt',    array( __CLASS__, 'add_listing_custom_field' ) );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
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
			// 'p_id'              => '',
			// 'p_id'              => '',
			// 'p_id'              => '',



		);

		atbdp_get_shortcode_template( 'forms/add-listing-general', $args );
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
}

Directorist_Template_Hooks::instance();