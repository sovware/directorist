<?php
/**
 * @author AazzTech
 */

class ATBDP_Shortcode {

	public function __construct() {

		$shortcodes = apply_filters( 'atbdp_shortcodes', [
			// Archive
			'directorist_all_listing' => [ $this, 'listing_archive' ],
			'directorist_category'    => [ $this, 'category_archive' ],
			'directorist_tag'         => [ $this, 'tag_archive' ],
			'directorist_location'    => [ $this, 'location_archive' ],
			
			// Taxonomy
			'directorist_all_categories' => [ $this, 'all_categories' ],
			'directorist_all_locations'  => [ $this, 'all_locations' ],

			// Search
			'directorist_search_listing' => [ $this, 'search_listing' ],
			'directorist_search_result'  => [ $this, 'search_result' ],

			// Single
			'directorist_single_listing'              => [ $this, 'directorist_single_listing' ],
			'directorist_listing_top_area'            => [ $this, 'directorist_single_listing' ],
			'directorist_listing_tags'                => [ $this, 'directorist_tags' ],
			'directorist_listing_custom_fields'       => [ $this, 'directorist_custom_field' ],
			'directorist_listing_video'               => [ $this, 'directorist_listing_video' ],
			'directorist_listing_map'                 => [ $this, 'directorist_listing_map' ],
			'directorist_listing_contact_information' => [ $this, 'directorist_listing_contact_information' ],
			'directorist_listing_author_info'         => [ $this, 'directorist_listing_author_details' ],
			'directorist_listing_contact_owner'       => [ $this, 'directorist_listing_contact_owner' ],
			'directorist_listing_review'              => [ $this, 'directorist_listing_review' ],
			'directorist_related_listings'            => [ $this, 'directorist_related_listings' ],
			
			// Author
			'directorist_author_profile' => [ $this, 'author_profile' ],
			'directorist_user_dashboard' => [ $this, 'user_dashboard' ],
			
			// Forms
			'directorist_add_listing'         => [ $this, 'add_listing' ],
			'directorist_custom_registration' => [ $this, 'user_registration' ],
			'directorist_user_login'          => [ $this, 'custom_user_login' ],
			
			// Checkout
			'directorist_checkout'            => [ new ATBDP_Checkout, 'display_checkout_content' ],
			'directorist_payment_receipt'     => [ new ATBDP_Checkout, 'payment_receipt' ],
			'directorist_transaction_failure' => [ new ATBDP_Checkout, 'transaction_failure' ],

		]);

		// Register Shorcodes
		foreach ( $shortcodes as $shortcode => $callback ) {
			add_shortcode( $shortcode, $callback);
		}
	}

	public function listing_archive( $atts ) {
		$listings = new Directorist_Listings( $atts );
		return $listings->render_shortcode();
	}

	public function category_archive( $atts ) {
		$atts             = !empty( $atts ) ? $atts : array();
		$category_slug    = get_query_var('atbdp_category');
		$atts['category'] = sanitize_text_field( $category_slug );
		return $this->listing_archive( $atts );
	}

	public function tag_archive( $atts ) {
		$atts        = !empty( $atts ) ? $atts : array();
		$tag_slug    = get_query_var('atbdp_tag');
		$atts['tag'] = sanitize_text_field( $tag_slug );
		return $this->listing_archive( $atts );
	}

	public function location_archive( $atts ) {
		$atts        = !empty( $atts ) ? $atts : array();
		$tag_slug    = get_query_var('atbdp_location');
		$atts['location'] = sanitize_text_field( $tag_slug );
		return $this->listing_archive( $atts );
	}

	public function all_categories($atts) {
		$taxonomy = new Directorist_Listing_Taxonomy($atts, 'category');
		return $taxonomy->render_shortcode();
	}

	public function all_locations($atts) {
		$taxonomy = new Directorist_Listing_Taxonomy($atts, 'location');
		return $taxonomy->render_shortcode();
	}

	public function search_listing($atts) {
		$listing_type = '';
		if (!empty($atts['listing_type'])) {
			$listing_type = $atts['listing_type'];
		}
		$searchform = new Directorist_Listing_Search_Form( 'search_form', $listing_type, $atts );
		return $searchform->render_search_shortcode();
	}

	public function search_result($atts) {
		$listings = new Directorist_Listings( $atts, 'search_result' );
		return $listings->render_shortcode();
	}

	public function directorist_single_listing() {
		$listing = new Directorist_Single_Listing();
		return $listing->render_shortcode_single_listing();
	}

	public function directorist_listing_header() {
		return '';
		$listing = new Directorist_Single_Listing();
		return $listing->render_shortcode_top_area();
	}

	public function directorist_tags() {
		return '';
		$listing = new Directorist_Single_Listing();
		return $listing->render_shortcode_tags();
	}

	public function directorist_custom_field() {
		return '';
		$listing = new Directorist_Single_Listing();
		return $listing->render_shortcode_custom_fields();
	}

	public function directorist_listing_video() {
		return '';
		$listing = new Directorist_Single_Listing();
		return $listing->render_shortcode_video();
	}

	public function directorist_listing_map() {
		return '';
		$listing = new Directorist_Single_Listing();
		return $listing->render_shortcode_map();
	}

	public function directorist_listing_contact_information() {
		return '';
		$listing = new Directorist_Single_Listing();
		return $listing->render_shortcode_contact_information();
	}

	public function directorist_listing_author_details() {
		return '';
		$listing = new Directorist_Single_Listing();
		return $listing->render_shortcode_author_info();
	}

	public function directorist_listing_contact_owner() {
		return '';
		$listing = new Directorist_Single_Listing();
		return $listing->render_shortcode_contact_owner();
	}

	public function directorist_listing_review() {
		return '';
		$listing = new Directorist_Single_Listing();
		return $listing->render_shortcode_listing_review();
	}
	
	public function directorist_related_listings() {
		return '';
		$listing = new Directorist_Single_Listing();
		return $listing->render_shortcode_related_listings();
    	// @todo @kowsar filter=atbdp_related_listing_template in "Post Your Need" extention
	}

	public function author_profile($atts) {
		$author = Directorist_Listing_Author::instance();
		return $author->render_shortcode_author_profile($atts);
	}

	public function user_dashboard($atts) {
		$dashboard = Directorist_Listing_Dashboard::instance();
		return $dashboard->render_shortcode_user_dashboard($atts);
	}

	public function add_listing($atts) {
		$url = $_SERVER['REQUEST_URI'];
		$pattern = "/edit\/(\d+)/i";
		$id = preg_match($pattern, $url, $matches) ? (int) $matches[1] : '';
		$forms = Directorist_Listing_Forms::instance($id);
		return $forms->render_shortcode_add_listing($atts);
	}

	public function user_registration() {
		$forms = Directorist_Listing_Forms::instance();
		return $forms->render_shortcode_custom_registration();
	}

	public function custom_user_login() {
		$forms = Directorist_Listing_Forms::instance();
		return $forms->render_shortcode_user_login();
	}

}