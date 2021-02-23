<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Template_Hooks {

	protected static $instance = null;

	private function __construct() {
		// Dashboard ajax
		add_action('wp_ajax_directorist_dashboard_listing_tab', array( Directorist_Listing_Dashboard::instance(), 'ajax_listing_tab' ) );

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

		// Single Listing
		add_action( 'template_redirect', [ $this, 'single_template' ] );
		add_filter('the_content', [ $this, 'single_content' ], 20 );

		// Legacy
		if ( Helper::is_legacy_mode() ) {
			$author = Directorist_Listing_Author::instance();
			add_action( 'directorist_author_profile_content', array( $author, 'header_template' ) );
			add_action( 'directorist_author_profile_content', array( $author, 'about_template' ), 15 );
			add_action( 'directorist_author_profile_content', array( $author, 'author_listings_template' ), 20 );

			// add_filter('the_content', array( '\Directorist\Directorist_Single_Listing', 'single_content_wrapper' ), 20 );
		}

	}

	// instance
	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public static function single_content( $content ) {
		$single_template = get_directorist_option( 'single_listing_template', 'directorist_template' );

		if ( is_singular( ATBDP_POST_TYPE ) && in_the_loop() && is_main_query() && $single_template != 'directorist_template' ) {
			$content = Helper::get_template_contents( 'single-contents' );

			if ( Helper::is_legacy_mode() ) {
				$content = Helper::get_template_contents( 'single-listing/content-wrapper' );
			}

		}

		return $content;
	}

	public function single_template() {
		if ( ! is_singular( ATBDP_POST_TYPE ) ) {
			return;
		}

		$single_template = get_directorist_option( 'single_listing_template', 'directorist_template' );

		if ( $single_template == 'current_theme_template' ) {
			Helper::get_theme_template_for( 'single' );
		}
		elseif ( $single_template == 'directorist_template' ) {
			Helper::get_template( 'single' );
		}
		else {
			Helper::get_theme_template_for( 'page' );
		}
		
		die();
	}

}

add_action( 'init', function(){
	Directorist_Template_Hooks::instance();
} );