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
}

Directorist_Template_Hooks::instance();