<?php

if ( !class_exists('ATBDP_Rewrite') ):

/**
 * Class ATBDP_Rewrite
 * It handle custom rewrite rules and actions etc.
 */
class ATBDP_Rewrite {

    public function __construct()
    {
        // add the rewrite rules to the init hook
        add_action( 'init', array( $this, 'add_write_rules' ) );
        add_action( 'wp_loaded', array( $this, 'flush_rewrite_rules_on_demand' ) );
    }

    public function add_write_rules()
    {
        $home = home_url();
        // All listing page URL Rewrite
        $id = get_directorist_option('all_listing_page');
        if ( $id > 0 ) {
            $link = str_replace( $home, '', get_permalink( $id ) );
            $link = trim( $link, '/' );
            $link = ( preg_match( '/([?])/', $link ) ) ? 'directory-all-listing' : $link;

            add_rewrite_rule( "$link/page/?([0-9]{1,})/?$", 'index.php?page_id='.$id.'&paged=$matches[1]', 'top' );
        }

        // Author profile page URL Rewrite
        $id = get_directorist_option('author_profile_page');
        if ( $id > 0 ) {
            $link = str_replace( $home, '', get_permalink( $id ) );
            $link = trim( $link, '/' );
            $link = ( preg_match( '/([?])/', $link ) ) ? 'directory-profile' : $link;

            // Link > Page
            add_rewrite_rule( "$link/page/(\d+)/?$", 'index.php?page_id='.$id.'&paged=$matches[1]', 'top' );
            
            // Link > Author > Page
            add_rewrite_rule( "$link/([^/]+)/?$", 'index.php?page_id='.$id.'&author_id=$matches[1]', 'top' );
            add_rewrite_rule( "$link/([^/]+)/page/(\d)/?$", 'index.php?page_id='.$id.'&author_id=$matches[1]&paged=$matches[2]', 'top' );
            
            // Link > Author > Directory > Page
            add_rewrite_rule( "$link/([^/]+)/directory/([^/]+)/?$", 'index.php?page_id='.$id.'&author_id=$matches[1]&directory_type=$matches[2]', 'top' );
            add_rewrite_rule( "$link/([^/]+)/directory/([^/]+)/page/(\d)/?$", 'index.php?page_id='.$id.'&author_id=$matches[1]&directory_type=$matches[2]&paged=$matches[3]', 'top' );
            
            // Link > Directory > Page
            add_rewrite_rule( "$link/directory/([^/]+)/?$", 'index.php?page_id='.$id.'&directory_type=$matches[1]', 'top' );
            add_rewrite_rule( "$link/directory/([^/]+)/page/(\d)/?$", 'index.php?page_id='.$id.'&directory_type=$matches[1]&paged=$matches[2]', 'top' );
        }


        // Checkout page URL Rewrite
        $cp_id = get_directorist_option('checkout_page'); // get the checkout page id
        if( $cp_id ) {
            $link = str_replace( $home, '', get_permalink( $cp_id ) );	// remove the home_url() from the link
            $link = trim( $link, '/' );	// remove slash / from the end and the start
            $link = ( preg_match( '/([?])/', $link ) ) ? 'directory-checkout' : $link;

            add_rewrite_rule( "$link/submit/([0-9]{1,})/?$", 'index.php?page_id='.$cp_id.'&atbdp_action=submission&atbdp_listing_id=$matches[1]', 'top' );
            add_rewrite_rule( "$link/promote/([0-9]{1,})/?$", 'index.php?page_id='.$cp_id.'&atbdp_action=promotion&atbdp_listing_id=$matches[1]', 'top' );
            add_rewrite_rule( "$link/paypal-ipn/([0-9]{1,})/?$", 'index.php?page_id='.$cp_id.'&atbdp_action=paypal-ipn&atbdp_order_id=$matches[1]', 'top' );
            add_rewrite_rule( "$link/([^/]+)/([0-9]{1,})/?$", 'index.php?page_id='.$cp_id.'&atbdp_action=$matches[1]&atbdp_order_id=$matches[2]', 'top' ); // we can add listing_id instead of order_id if we want.
        }

        // Payment receipt page
        $prp_id = get_directorist_option('payment_receipt_page'); // get the payment receipt page id.
        if( $prp_id ) {
            $link = str_replace( $home, '', get_permalink( $prp_id ) );
            $link = trim( $link, '/' );
            $link = ( preg_match( '/([?])/', $link ) ) ? 'directory-payment-receipt' : $link;

            add_rewrite_rule( "$link/order/([0-9]{1,})/?$", 'index.php?page_id='.$prp_id.'&atbdp_action=order&atbdp_order_id=$matches[1]', 'top' );
        }


        // Edit Listing/Renew Listing/Delete listings etc
        $id = get_directorist_option('add_listing_page');
        if( $id  ) {
            $link = str_replace( $home, '', get_permalink( $id ) );
            $link = trim( $link, '/' );
            $link = ( preg_match( '/([?])/', $link ) ) ? 'directory-add-listing' : $link;

            add_rewrite_rule( "$link/([^/]+)/([0-9]{1,})/?$", 'index.php?page_id='.$id.'&atbdp_action=$matches[1]&atbdp_listing_id=$matches[2]', 'top' );
        }

        // Single Category page
        $cat = get_directorist_option('single_category_page'); // get the single category page.
        if( $cat ) {
            $link = str_replace( $home, '', get_permalink( $cat ) );
            $link = trim( $link, '/' );
            $link = ( preg_match( '/([?])/', $link ) ) ? 'directory-single-category' : $link;

            add_rewrite_rule( "$link/([^/]+)/page/?([0-9]{1,})/?$", 'index.php?page_id='.$cat.'&atbdp_category=$matches[1]&paged=$matches[2]', 'top' );
            add_rewrite_rule( "$link/([^/]+)/?$", 'index.php?page_id='.$cat.'&atbdp_category=$matches[1]', 'top' );
        }

        // Single Location page
        $loc = get_directorist_option('single_location_page'); // get the single location page.
        if( $loc ) {
            $link = str_replace( $home, '', get_permalink( $loc ) );
            $link = trim( $link, '/' );
            $link = ( preg_match( '/([?])/', $link ) ) ? 'directory-single-location' : $link;

            add_rewrite_rule( "$link/([^/]+)/page/?([0-9]{1,})/?$", 'index.php?page_id='.$loc.'&atbdp_location=$matches[1]&paged=$matches[2]', 'top' );
            add_rewrite_rule( "$link/([^/]+)/?$", 'index.php?page_id='.$loc.'&atbdp_location=$matches[1]', 'top' );
        }

        // Single Tag page
        $tag = get_directorist_option('single_tag_page'); // get the single location page.
        if( $tag ) {
            $link = str_replace( $home, '', get_permalink( $tag ) );
            $link = trim( $link, '/' );
            $link = ( preg_match( '/([?])/', $link ) ) ? 'directory-single-tag' : $link;

            add_rewrite_rule( "$link/([^/]+)/page/?([0-9]{1,})/?$", 'index.php?page_id='.$tag.'&atbdp_tag=$matches[1]&paged=$matches[2]', 'top' );
            add_rewrite_rule( "$link/([^/]+)/?$", 'index.php?page_id='.$tag.'&atbdp_tag=$matches[1]', 'top' );
        }

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
        add_rewrite_tag( '%directory_type%', '([^/]+)' );
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