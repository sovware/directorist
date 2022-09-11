<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Template_Hooks {

	protected static $instance = null;

	private function __construct() {

		// Allow '--directorist-icon' inline style var in wp_kses_post, which is used in directorist_icon()
		add_filter( 'safe_style_css', array( $this, 'add_style_attr' )  );
		add_filter( 'safecss_filter_attr_allow_css', array( $this, 'allow_style_attr' ), 10, 2  );

		// Dashboard ajax
		$dashboard = Directorist_Listing_Dashboard::instance();
		add_action('wp_ajax_directorist_dashboard_listing_tab', array( $dashboard, 'ajax_listing_tab' ) );

		// All Categories
		add_action( 'atbdp_before_all_categories_loop',    array( '\Directorist\Directorist_Listing_Taxonomy', 'archive_type' ) );

		// All Locations
		add_action( 'atbdp_before_all_locations_loop',    array( '\Directorist\Directorist_Listing_Taxonomy', 'archive_type' ) );

		// Listing Archive - @todo: remove these unused hooks
		add_action( 'directorist_archive_header',    array( '\Directorist\Directorist_Listings', 'archive_type' ) );
		add_action( 'directorist_archive_header',    array( '\Directorist\Directorist_Listings', 'archive_header' ), 15 );

		// Listings Badges - Grid View - @todo: remove these unused hooks
		add_filter( 'atbdp_grid_lower_badges', array( '\Directorist\Directorist_Listings', 'featured_badge') );
		add_filter( 'atbdp_grid_lower_badges', array( '\Directorist\Directorist_Listings', 'popular_badge'), 15 );
		add_filter( 'atbdp_grid_lower_badges', array( '\Directorist\Directorist_Listings', 'new_listing_badge'), 20 );

		// Listings Badges - List View
		add_filter( 'atbdp_list_lower_badges', array( '\Directorist\Directorist_Listings', 'featured_badge_list_view') );
		add_filter( 'atbdp_list_lower_badges', array( '\Directorist\Directorist_Listings', 'populer_badge_list_view'), 15 );
		add_filter( 'atbdp_list_lower_badges', array( '\Directorist\Directorist_Listings', 'new_badge_list_view'), 20 );

		// Listings Top - List View - @todo: remove these unused hooks
		add_action( 'directorist_list_view_listing_meta_end', array( '\Directorist\Directorist_Listings', 'list_view_business_hours') );
		add_action( 'directorist_list_view_top_content_end',  array( '\Directorist\Directorist_Listings', 'mark_as_favourite_button'), 15 );

		// Listing Thumbnail Area - @todo: remove these unused hooks
		add_action( 'atbdp_listing_thumbnail_area', array( '\Directorist\Directorist_Listings', 'mark_as_favourite_button'), 15 );

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

	public function add_style_attr( $args ) {
		$args[] = '--directorist-icon';
		return $args;
	}

	public function allow_style_attr( $allow_css, $css_test_string ) {
		$parts = explode( ':', $css_test_string, 2 );
		$attr = trim( $parts[0] );

		if ( $attr === '--directorist-icon' ) {
			return true;
		}

		return $allow_css;
	}

	public static function single_content( $content ) {
		if ( ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		if ( is_singular( ATBDP_POST_TYPE ) && get_directorist_option( 'single_listing_template', 'directorist_template' ) !== 'directorist_template' ) {
			return Helper::get_template_contents( 'single-contents' );
		}

		return $content;
	}

	/**
	 * Include directorist custom template based on user's personalization.
	 *
	 * @param  string $template
	 *
	 * @return string
	 */
	public function single_template_path( $template ) {
		if ( ! is_singular( ATBDP_POST_TYPE ) ) {
			return $template;
		}

		$template_type = get_directorist_option( 'single_listing_template', 'directorist_template' );
		if ( $template_type === 'directorist_template' && ! self::has_block_template( 'single-listing' ) ) {
			$_template = Helper::template_path( 'single' );
		} elseif ( $template_type === 'theme_template_page' ) {
			$_template = get_page_template();
		} elseif ( $template_type === 'current_theme_template' ) {
			$_template = get_single_template();
		}

		// assign custom template if found.
		if ( ! empty( $_template ) ) {
			$template = $_template;
		}

		return $template;
	}

	/**
	 * Checks whether a block template with that name exists.
	 *
	 * **Note: ** This checks both the `templates` and `block-templates` directories
	 * as both conventions should be supported.
	 *
	 * @since  7.1.2
	 * @param string $template_name Template to check.
	 * @return bool
	 */
	private static function has_block_template( $template_name ) {
		if ( ! $template_name ) {
			return false;
		}

		$has_template            = false;
		$template_filename       = $template_name . '.html';
		// Since Gutenberg 12.1.0, the conventions for block templates directories have changed,
		// we should check both these possible directories for backwards-compatibility.
		$possible_templates_dirs = array( 'templates', 'block-templates' );

		// Combine the possible root directory names with either the template directory
		// or the stylesheet directory for child themes, getting all possible block templates
		// locations combinations.
		$filepath        = DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $template_filename;
		$legacy_filepath = DIRECTORY_SEPARATOR . 'block-templates' . DIRECTORY_SEPARATOR . $template_filename;
		$possible_paths  = array(
			get_stylesheet_directory() . $filepath,
			get_stylesheet_directory() . $legacy_filepath,
			get_template_directory() . $filepath,
			get_template_directory() . $legacy_filepath,
		);

		// Check the first matching one.
		foreach ( $possible_paths as $path ) {
			if ( is_readable( $path ) ) {
				$has_template = true;
				break;
			}
		}

		/**
		 * Filters the value of the result of the block template check.
		 *
		 * @since 7.1.2
		 *
		 * @param boolean $has_template value to be filtered.
		 * @param string $template_name The name of the template.
		 */
		return (bool) apply_filters( 'directorist_has_block_template', $has_template, $template_name );
	}
}

add_action( 'init', function(){
	Directorist_Template_Hooks::instance();
} );