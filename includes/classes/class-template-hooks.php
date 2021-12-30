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
		$dashboard = \wpWax\Directorist\Model\Dashboard::instance();
		add_action('wp_ajax_directorist_dashboard_listing_tab', array( $dashboard, 'ajax_listing_tab' ) );

		// All Categories
		add_action( 'atbdp_before_all_categories_loop',    array( '\wpWax\Directorist\Model\Listing_Taxonomy', 'archive_type' ) );

		// All Locations
		add_action( 'atbdp_before_all_locations_loop',    array( '\wpWax\Directorist\Model\Listing_Taxonomy', 'archive_type' ) );

		// Listing Archive
		add_action( 'directorist_archive_header',    array( '\wpWax\Directorist\Model\Listings', 'archive_type' ) );
		add_action( 'directorist_archive_header',    array( '\wpWax\Directorist\Model\Listings', 'archive_header' ), 15 );

		// Listings Badges - Grid View
		add_filter( 'atbdp_grid_lower_badges', array( '\wpWax\Directorist\Model\Listings', 'featured_badge') );
		add_filter( 'atbdp_grid_lower_badges', array( '\wpWax\Directorist\Model\Listings', 'popular_badge'), 15 );
		add_filter( 'atbdp_grid_lower_badges', array( '\wpWax\Directorist\Model\Listings', 'new_listing_badge'), 20 );

		// Listings Badges - List View
		add_filter( 'atbdp_list_lower_badges', array( '\wpWax\Directorist\Model\Listings', 'featured_badge_list_view') );
		add_filter( 'atbdp_list_lower_badges', array( '\wpWax\Directorist\Model\Listings', 'populer_badge_list_view'), 15 );
		add_filter( 'atbdp_list_lower_badges', array( '\wpWax\Directorist\Model\Listings', 'new_badge_list_view'), 20 );

		// Listings Top - List View
		add_action( 'directorist_list_view_listing_meta_end', array( '\wpWax\Directorist\Model\Listings', 'list_view_business_hours') );
		add_action( 'directorist_list_view_top_content_end',  array( '\wpWax\Directorist\Model\Listings', 'mark_as_favourite_button'), 15 );

		// Listing Thumbnail Area
		add_action( 'atbdp_listing_thumbnail_area', array( '\wpWax\Directorist\Model\Listings', 'mark_as_favourite_button'), 15 );

		// Single Listing
		// Set high priority to override page builders.
		add_filter( 'template_include', [ $this, 'single_template_path' ], 999 );
		add_filter( 'the_content',      [ $this, 'single_content' ], 20 );
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
		}

		// Put a dummy content for selected single pages
		$selected_single_pages = Helper::builder_selected_single_pages();

		if( !empty( $selected_single_pages ) && in_the_loop() && is_main_query() && is_page( array_keys( $selected_single_pages ) ) ) {
			$page_id = get_the_id();
			$type_name = $selected_single_pages[$page_id];
			$content = sprintf( __( 'This page is currently selected as the Single Listing Page for \'%s\' Listing Type', 'directorist' ) , $type_name );
		}

		return $content;
	}

	public function single_template_path( $template_path ) {
		if ( ! is_singular( ATBDP_POST_TYPE ) ) {
			return $template_path;
		}

		$single_template = get_directorist_option( 'single_listing_template', 'directorist_template' );

		if ( $single_template == 'current_theme_template' ) {
			$template_path = Helper::get_theme_template_path_for( 'single' );
		}
		elseif ( $single_template == 'directorist_template' ) {
			$template_path = Helper::template_path( 'single' );
		}
		else {
			$template_path = Helper::get_theme_template_path_for( 'page' );
		}

		return $template_path;
	}

}

add_action( 'init', function(){
	Directorist_Template_Hooks::instance();
} );