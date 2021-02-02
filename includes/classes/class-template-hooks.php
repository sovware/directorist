<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Template_Hooks {

	protected static $instance = null;

	private function __construct() {
    	// Dashboard
		$dashboard = Directorist_Listing_Dashboard::instance();
		add_action( 'directorist_dashboard_before_container', array( $dashboard, 'alert_message_template' ) );
		add_action( 'directorist_dashboard_title_area',       array( $dashboard, 'section_title' ) );
		add_action( 'directorist_dashboard_navigation',       array( $dashboard, 'nav_tabs_template' ) );
		add_action( 'directorist_dashboard_navigation',       array( $dashboard, 'nav_buttons_template' ), 15 );
		add_action( 'directorist_dashboard_tab_contents',     array( $dashboard, 'tab_contents_html' ) );

		// All Categories
		add_action( 'atbdp_before_all_categories_loop',    array( '\Directorist\Directorist_Listing_Taxonomy', 'archive_type' ) );

		// All Locations
		add_action( 'atbdp_before_all_locations_loop',    array( '\Directorist\Directorist_Listing_Taxonomy', 'archive_type' ) );

    	// Listing Archive
		add_action( 'directorist_archive_header',    array( '\Directorist\Directorist_Listings', 'archive_type' ) );
		add_action( 'directorist_archive_header',    array( '\Directorist\Directorist_Listings', 'archive_header' ), 15 );
		
		// Listings Badges - Grid View
		add_filter( 'atbdp_grid_lower_badges', array( '\Directorist\Directorist_Listings', 'featured_badge') );
		add_filter( 'atbdp_grid_lower_badges', array( '\Directorist\Directorist_Listings', 'popular_badge'), 15 );
		add_filter( 'atbdp_grid_lower_badges', array( '\Directorist\Directorist_Listings', 'new_listing_badge'), 20 );

    	// Listings Badges - List View
		add_filter( 'atbdp_list_lower_badges', array( '\Directorist\Directorist_Listings', 'featured_badge_list_view') );
		add_filter( 'atbdp_list_lower_badges', array( '\Directorist\Directorist_Listings', 'populer_badge_list_view'), 15 );
		add_filter( 'atbdp_list_lower_badges', array( '\Directorist\Directorist_Listings', 'new_badge_list_view'), 20 );

    	// Listings Top - List View
		add_action( 'directorist_list_view_listing_meta_end', array( '\Directorist\Directorist_Listings', 'list_view_business_hours') );
		add_action( 'directorist_list_view_top_content_end',  array( '\Directorist\Directorist_Listings', 'mark_as_favourite_button'), 15 );

    	// Listing Thumbnail Area
		add_action( 'atbdp_listing_thumbnail_area', array( '\Directorist\Directorist_Listings', 'mark_as_favourite_button'), 15 );

		// Single Listing content wrapper
		add_filter('the_content', array( '\Directorist\Directorist_Single_Listing', 'single_content_wrapper' ), 20 );

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