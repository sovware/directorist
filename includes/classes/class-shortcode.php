<?php
/**
 * @author wpWax
 */

namespace Directorist;

use wpWax\Directorist\Model\Listings;
use wpWax\Directorist\Model\Account;
use wpWax\Directorist\Model\All_Authors;
use wpWax\Directorist\Model\Author;
use wpWax\Directorist\Model\Dashboard;
use wpWax\Directorist\Model\Listing_Form;
use wpWax\Directorist\Model\Listing_Taxonomy;
use wpWax\Directorist\Model\Search_Form;
use wpWax\Directorist\Model\Single_Listing;

if ( ! defined( 'ABSPATH' ) ) exit;

class ATBDP_Shortcode {

	public static $instance = null;

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		foreach ( $this->get_all_shortcodes() as $shortcode => $callback ) {
			add_shortcode( $shortcode, $callback);
		}
	}

	public function get_all_shortcodes() {
		$shortcodes = [
			// Archive
			'directorist_all_listing'    => [ $this, 'listing_archive' ],
			'directorist_category'       => [ $this, 'category_archive' ],
			'directorist_tag'            => [ $this, 'tag_archive' ],
			'directorist_location'       => [ $this, 'location_archive' ],
			'directorist_search_result'  => [ $this, 'search_result_archive' ],

			// Taxonomy
			'directorist_all_categories' => [ $this, 'all_categories' ],
			'directorist_all_locations'  => [ $this, 'all_locations' ],

			// Search Form
			'directorist_search_listing' => [ $this, 'search_form' ],

			// Author
			'directorist_author_profile' => [ $this, 'author_profile' ],
			'directorist_user_dashboard' => [ $this, 'user_dashboard' ],
			'directorist_all_authors' 	 => [ $this, 'all_authors' ],

			// Forms
			'directorist_add_listing'         => [ $this, 'add_listing' ],
			'directorist_custom_registration' => [ $this, 'user_registration' ],
			'directorist_user_login'          => [ $this, 'user_login' ],

			// Checkout
			'directorist_checkout'            => [ new \ATBDP_Checkout, 'display_checkout_content' ],
			'directorist_payment_receipt'     => [ new \ATBDP_Checkout, 'payment_receipt' ],
			'directorist_transaction_failure' => [ new \ATBDP_Checkout, 'transaction_failure' ],

			// Single
			'directorist_single_listings_header' => [ $this, 'single_listings_header' ],
			'directorist_single_listing_section' => [ $this, 'single_listing_section' ],
		];

		return apply_filters( 'atbdp_shortcodes', $shortcodes );
	}

	// single_listings_header
	public function single_listings_header( $atts ) {

		if ( !is_singular( ATBDP_POST_TYPE ) ) {
			return '';
		}

		$listing = Single_Listing::instance();

		ob_start();
		echo '<div class="directorist-single-wrapper">';
		$listing->header_template();
		echo '<br>';
		echo '</div>';

		return ob_get_clean();
	}

	public function single_listing_section( $atts ) {
		ob_start();
		$post_id = ( isset( $atts['post_id'] ) && is_numeric( $atts['post_id'] ) ) ? ( int ) esc_attr( $atts['post_id'] ) : 0;
		$listing = Single_Listing::instance( $post_id );

		foreach ( $listing->content_data as $section ) {
			$section_id = isset( $section['section_id'] ) ? strval( $section['section_id'] ) : '';

			$section_key  = ( isset( $atts['key'] ) ) ? $atts['key'] : '';
			$section_key  = trim( preg_replace( '/\s{2,}/', ' ', $section_key ) );
			$section_keys = preg_split( '/\s*[,]\s/', $section_key );

			if ( ! empty( $section_keys ) && ! in_array( $section_id, $section_keys ) ) {
				continue;
			}

			$listing->section_template( $section );
		}

		return ob_get_clean();
	}

	public function listing_archive( $atts ) {
		$atts = !empty( $atts ) ? $atts : [];
		$listings = directorist()->listings;
		$listings->init( $atts );

		if ( !empty( $listings->redirect_page_url() ) ) {
			$redirect = '<script>window.location="' . esc_url( $listings->redirect_page_url() ) . '"</script>';
			return $redirect;
		}

		if ( $listings->display_only_for_logged_in() && ! is_user_logged_in() ) {
			return \ATBDP_Helper::guard([ 'type' => 'auth' ]);
		}

		$script_args = [ 'directory_type_id' => $listings->current_directory_type_id() ];
		Script_Helper::load_search_form_script( $script_args );

		Helper::get_template( 'archive-contents' );
	}

	public function category_archive( $atts ) {
		$atts             = !empty( $atts ) ? $atts : array();
		$category_slug    = !empty( $_GET['category'] ) ? $_GET['category'] : get_query_var('atbdp_category');
		$atts['category'] = sanitize_title_for_query( $category_slug );

		return $this->listing_archive( $atts );
	}

	public function tag_archive( $atts ) {
		$atts        = !empty( $atts ) ? $atts : array();
		$tag_slug    = !empty( $_GET['tag'] ) ? $_GET['tag'] : get_query_var('atbdp_tag');
		$atts['tag'] = sanitize_title_for_query( $tag_slug );

		return $this->listing_archive( $atts );
	}

	public function location_archive( $atts ) {
		$atts             = !empty( $atts ) ? $atts : array();
		$location_slug    = !empty( $_GET['location'] ) ? $_GET['location'] : get_query_var('atbdp_location');
		$atts['location'] = sanitize_title_for_query( $location_slug );

		return $this->listing_archive( $atts );
	}

	public function search_result_archive( $atts ) {
		$atts = !empty( $atts ) ? $atts : array();
		return $this->listing_archive( $atts );
	}

	public function all_categories( $atts ) {
		$atts = !empty( $atts ) ? $atts : array();
		$taxonomy = new Listing_Taxonomy($atts, 'category');
		
		$atts[ 'shortcode' ] = 'directorist_all_categories';
		
		return $taxonomy->render_shortcode( $atts );
	}

	public function all_locations( $atts ) {
		$atts = !empty( $atts ) ? $atts : array();
		$taxonomy = new Listing_Taxonomy($atts, 'location');
		
		return $taxonomy->render_shortcode( $atts );
	}

	public function search_form( $atts ) {
		$atts = !empty( $atts ) ? $atts : array();
		$listing_type = '';
		if (!empty($atts['listing_type'])) {
			$listing_type = $atts['listing_type'];
		}
		$searchform = new Search_Form( 'search_form', $listing_type, $atts );
		
		return $searchform->render_search_shortcode( $atts );
	}

	public function author_profile( $atts ) {
		$atts = !empty( $atts ) ? $atts : array();
		$author = Author::instance();

		return $author->render_shortcode_author_profile($atts);
	}

	public function all_authors() {
		$all_authors = new All_Authors();
		return $all_authors->render_shortcode_all_authors();
	}

	public function user_dashboard( $atts ) {
		$atts = !empty( $atts ) ? $atts : array();
		$dashboard = Dashboard::instance();
		
		return $dashboard->render_shortcode($atts);
	}

	public function add_listing( $atts ) {
		$atts = !empty( $atts ) ? $atts : array();
		$url = $_SERVER['REQUEST_URI'];
		$pattern = "/edit\/(\d+)/i";
		$id = preg_match($pattern, $url, $matches) ? (int) $matches[1] : '';
		$forms = Listing_Form::instance($id);
		
		return $forms->render_shortcode($atts);
	}

	public function user_registration( $atts ) {
		$atts = !empty( $atts ) ? $atts : array();
		$account = Account::instance();

		return $account->render_shortcode_registration( $atts );
	}

	public function user_login( $atts ) {
		$atts = !empty( $atts ) ? $atts : array();
		$account = Account::instance();

		return $account->render_shortcode_login( $atts );
	}

}