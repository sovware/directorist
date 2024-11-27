<?php

if ( !class_exists('ATBDP_Rewrite') ):

/**
 * Class ATBDP_Rewrite
 * It handle custom rewrite rules and actions etc.
 */
class ATBDP_Rewrite {

	protected $pages = array();

	public function __construct() {
		// add the rewrite rules to the init hook
		add_action( 'init', array( $this, 'add_write_rules' ) );

		$flush_rewrite_rules_on_demand = apply_filters( 'directorist_flush_rewrite_rules_on_demand', false );

		if ( $flush_rewrite_rules_on_demand ) {
			add_action( 'wp_loaded', array( $this, 'flush_rewrite_rules_on_demand' ) );
		}
		add_action( 'directorist_setup_wizard_page_created', array( $this, 'flush_rewrite_rules_on_demand' ) );
		add_action( 'directorist_setup_wizard_payment_page_created', array( $this, 'flush_rewrite_rules_on_demand' ) );
		add_action( 'directorist_setup_wizard_completed', array( $this, 'flush_rewrite_rules_on_demand' ) );
		add_action( 'directorist_options_updated', array( $this, 'flush_rewrite_rules_on_demand' ) );
	}

	protected function get_pages() {
		if ( ! empty( $this->pages ) ) {
			return $this->pages;
		}

		$pages = array(
			'all_listing_page',
			'author_profile_page',
			'checkout_page',
			'payment_receipt_page',
			'add_listing_page',
			'single_category_page',
			'single_location_page',
			'single_tag_page',
		);

		foreach ( $pages as $page_option_key ) {
			$this->pages[ $page_option_key ] = (int) get_directorist_option( $page_option_key );
		}

		return $this->pages;
	}

	protected function get_page_ids() {
		return array_unique( array_values( $this->get_pages() ) );
	}

	protected function get_page_id( $option_key ) {
		$pages = $this->get_pages();
		return array_key_exists( $option_key, $pages ) ? $pages[ $option_key ] : 0;
	}

	protected function get_page_slug( $page_id, $default_slug ) {
		$home_url = home_url( '/' );
		$slug     = str_replace( $home_url, '', get_permalink( $page_id ) );
		if(function_exists('wpml_get_current_language') && $home_url != ($site_url = site_url().'/')) {
            $slug     = str_replace( $site_url, '', $slug );
        }
		$slug     = rtrim( $slug, '/' );
		$slug     = preg_match( '/([?])/', $slug ) ? $default_slug : $slug;

		return apply_filters( 'directorist_rewrite_get_page_slug', $slug, $page_id, $default_slug );
	}

	public function add_write_rules() {
		$cached_pages = get_pages( array(
			'include' => $this->get_page_ids()
		) );

		$page_id = $this->get_page_id( 'all_listing_page' );
		if ( $page_id ) {
			$link = $this->get_page_slug( $page_id, 'directory-all-listing' );

			add_rewrite_rule( "$link/page/?([0-9]{1,})/?$", 'index.php?page_id='.$page_id.'&paged=$matches[1]', 'top' );
		}

		// Author profile page URL Rewrite
		$page_id = $this->get_page_id( 'author_profile_page' );
		if ( $page_id ) {
			$link = $this->get_page_slug( $page_id, 'directory-profile' );

			// Link > Page
			add_rewrite_rule( "$link/page/(\d+)/?$", 'index.php?page_id='.$page_id.'&paged=$matches[1]', 'top' );

			// Link > Author > Page
			add_rewrite_rule( "$link/([^/]+)/?$", 'index.php?page_id='.$page_id.'&author_id=$matches[1]', 'top' );
			add_rewrite_rule( "$link/([^/]+)/page/(\d)/?$", 'index.php?page_id='.$page_id.'&author_id=$matches[1]&paged=$matches[2]', 'top' );

			// Link > Author > Directory > Page
			add_rewrite_rule( "$link/([^/]+)/directory/([^/]+)/?$", 'index.php?page_id='.$page_id.'&author_id=$matches[1]&directory-type=$matches[2]', 'top' );
			add_rewrite_rule( "$link/([^/]+)/directory/([^/]+)/page/(\d)/?$", 'index.php?page_id='.$page_id.'&author_id=$matches[1]&directory-type=$matches[2]&paged=$matches[3]', 'top' );

			// Link > Directory > Page
			add_rewrite_rule( "$link/directory/([^/]+)/?$", 'index.php?page_id='.$page_id.'&directory-type=$matches[1]', 'top' );
			add_rewrite_rule( "$link/directory/([^/]+)/page/(\d)/?$", 'index.php?page_id='.$page_id.'&directory-type=$matches[1]&paged=$matches[2]', 'top' );
		}

		// Checkout page URL Rewrite
		$page_id = $this->get_page_id( 'checkout_page' );
		if ( $page_id ) {
			$link = $this->get_page_slug( $page_id, 'directory-checkout' );

			add_rewrite_rule( "$link/submit/([0-9]{1,})/?$", 'index.php?page_id='.$page_id.'&atbdp_action=submission&atbdp_listing_id=$matches[1]', 'top' );
			add_rewrite_rule( "$link/promote/([0-9]{1,})/?$", 'index.php?page_id='.$page_id.'&atbdp_action=promotion&atbdp_listing_id=$matches[1]', 'top' );
			add_rewrite_rule( "$link/paypal-ipn/([0-9]{1,})/?$", 'index.php?page_id='.$page_id.'&atbdp_action=paypal-ipn&atbdp_order_id=$matches[1]', 'top' );
			add_rewrite_rule( "$link/([^/]+)/([0-9]{1,})/?$", 'index.php?page_id='.$page_id.'&atbdp_action=$matches[1]&atbdp_order_id=$matches[2]', 'top' ); // we can add listing_id instead of order_id if we want.
		}

		// Payment receipt page
		$page_id = $this->get_page_id( 'payment_receipt_page' );
		if( $page_id ) {
			$link = $this->get_page_slug( $page_id, 'directory-payment-receipt' );

			add_rewrite_rule( "$link/order/([0-9]{1,})/?$", 'index.php?page_id='.$page_id.'&atbdp_action=order&atbdp_order_id=$matches[1]', 'top' );
		}


		// Edit Listing/Renew Listing/Delete listings etc
		$page_id = $this->get_page_id( 'add_listing_page' );
		if ( $page_id  ) {
			$link = $this->get_page_slug( $page_id, 'directory-add-listing' );

			add_rewrite_rule( "$link/([^/]+)/([0-9]{1,})/?$", 'index.php?page_id='.$page_id.'&atbdp_action=$matches[1]&atbdp_listing_id=$matches[2]', 'top' );
		}

		// Single Category page
		$page_id = $this->get_page_id( 'single_category_page' );
		if ( $page_id ) {
			$link = $this->get_page_slug( $page_id, 'directory-single-category' );
			add_rewrite_rule( "$link/([^/]+)/page/?([0-9]{1,})/?$", 'index.php?page_id='.$page_id.'&atbdp_category=$matches[1]&paged=$matches[2]', 'top' );
			add_rewrite_rule( "$link/([^/]+)/?$", 'index.php?page_id='.$page_id.'&atbdp_category=$matches[1]', 'top' );
		}

		// Single Location page
		$page_id = $this->get_page_id( 'single_location_page' );
		if ( $page_id ) {
			$link = $this->get_page_slug( $page_id, 'directory-single-location' );

			add_rewrite_rule( "$link/([^/]+)/page/?([0-9]{1,})/?$", 'index.php?page_id='.$page_id.'&atbdp_location=$matches[1]&paged=$matches[2]', 'top' );
			add_rewrite_rule( "$link/([^/]+)/?$", 'index.php?page_id='.$page_id.'&atbdp_location=$matches[1]', 'top' );
		}

		// Single Tag page
		$page_id = $this->get_page_id( 'single_tag_page' );
		if ( $page_id ) {
			$link = $this->get_page_slug( $page_id, 'directory-single-tag' );

			add_rewrite_rule( "$link/([^/]+)/page/?([0-9]{1,})/?$", 'index.php?page_id='.$page_id.'&atbdp_tag=$matches[1]&paged=$matches[2]', 'top' );
			add_rewrite_rule( "$link/([^/]+)/?$", 'index.php?page_id='.$page_id.'&atbdp_tag=$matches[1]', 'top' );
		}

		unset( $cached_pages );

		// Rewrite tags (Making custom query var available throughout the application
		// WordPress by default does not understand the unknown query vars. It needs to be registered with WP for using it.
		// by using add_rewrite_tag() or add_query_arg() on init hook or other earlier hook, we can register custom query var eg. atbdp_action and  we can access it later on any other page
		// by using get_query_var( 'atbdp_action' );  anywhere in the page.
		// otherwise, get_query_var() would return and empty string even if the 'atbdp_action' var is available in the query string.
		//
		add_rewrite_tag( '%atbdp_action%', '([^/]+)' );
		add_rewrite_tag( '%atbdp_order_id%', '([0-9]{1,})' );
		add_rewrite_tag( '%atbdp_listing_id%', '([0-9]{1,})' );
		add_rewrite_tag( '%author_id%', '([^/]+)' );
		add_rewrite_tag( '%directory-type%', '([^/]+)' );
		add_rewrite_tag( '%atbdp_category%', '([^/]+)' );
		add_rewrite_tag( '%atbdp_location%', '([^/]+)' );
		add_rewrite_tag( '%atbdp_tag%', '([^/]+)' );
	}

	/**
	 * Flush the rewrite rules if needed as we have added new rewrite rules
	 *
	 * @since    3.1.2
	 * @access   public
	 */
	public function flush_rewrite_rules_on_demand() {

		$rewrite_rules = get_option( 'rewrite_rules' );

		if( $rewrite_rules ) {

			global $wp_rewrite;
			$rewrite_rules_array = array();
			foreach( $rewrite_rules as $rule => $rewrite ) {
				$rewrite_rules_array[$rule]['rewrite'] = $rewrite;
			}
			$rewrite_rules_array = array_reverse( $rewrite_rules_array, true );

			$maybe_missing = $wp_rewrite->rewrite_rules();
			$missing_rules = false;

			foreach( $maybe_missing as $rule => $rewrite ) {
				if( ! array_key_exists( $rule, $rewrite_rules_array ) ) {
					$missing_rules = true;
					break;
				}
			}

			if( true === $missing_rules ) {
				flush_rewrite_rules();
			}

		}

	}
} // ends ATBDP_Rewrite

endif;
