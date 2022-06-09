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
		if ( ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		if ( is_singular( ATBDP_POST_TYPE ) && get_directorist_option( 'single_listing_template', 'directorist_template' ) !== 'directorist_template' ) {
			return Helper::get_template_contents( 'single-contents' );
		}

		$directory_types = Helper::get_directory_types_with_custom_single_page( get_the_ID() );
		if ( get_post_type() === 'page' && ! empty( $directory_types ) ) {
			$directory_names = wp_list_pluck( $directory_types, 'name' );
			return sprintf( __( '<p style="text-align:center" class="directorist-alert directorist-alert-info">This page is currently selected as single Listing details page for %s directory.</p>', 'directorist' ) , implode( ', ', $directory_names ) );
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