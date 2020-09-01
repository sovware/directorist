<?php
/**
 * @author AazzTech
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Template_Hooks {

	protected static $instance = null;

	private function __construct() {
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
		add_action( 'directorist_archive_header',    array( 'Directorist_Listings', 'archive_header' ) );
		
		// Listings Badges - Grid View
		add_filter( 'atbdp_grid_lower_badges', array( 'Directorist_Listings', 'featured_badge') );
		add_filter( 'atbdp_grid_lower_badges', array( 'Directorist_Listings', 'popular_badge'), 15 );
		add_filter( 'atbdp_grid_lower_badges', array( 'Directorist_Listings', 'new_listing_badge'), 20 );

    // Listings Badges - List View
		add_filter( 'atbdp_list_lower_badges', array( 'Directorist_Listings', 'featured_badge_list_view') );
		add_filter( 'atbdp_list_lower_badges', array( 'Directorist_Listings', 'populer_badge_list_view'), 15 );
		add_filter( 'atbdp_list_lower_badges', array( 'Directorist_Listings', 'new_badge_list_view'), 20 );

    // Listings Top - List View
		add_action( 'directorist_list_view_listing_meta_end', array( 'Directorist_Listings', 'list_view_business_hours') );
		add_action( 'directorist_list_view_top_content_end',  array( 'Directorist_Listings', 'mark_as_favourite_button'), 15 );

    // Listing Thumbnail Area
		add_action( 'atbdp_listing_thumbnail_area', array( 'Directorist_Listings', 'mark_as_favourite_button'), 15 );

		// Single Listing content wrapper
		add_filter('the_content', array( 'Directorist_Single_Listing', 'single_content_wrapper' ), 20 );
	}

    // instance
	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
}

add_action( 'init', function(){
	Directorist_Template_Hooks::instance();
} );