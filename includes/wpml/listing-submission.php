<?php

namespace Directorist\WPML;

class Filter_Permalinks {

    public static $instance = null;

     /**
     * Constuctor
     * 
     * @return void
     */
    public function __construct() {
        add_filter( 'atbdp_checkout_page_url', [ $this, 'checkout_page_url' ], 20, 2 );
        add_filter( 'atbdp_payment_receipt_page_url', [ $this, 'payment_receipt_page_url' ], 20, 2 );
        add_filter( 'atbdp_edit_listing_page_url', [ $this, 'edit_listing_page_url' ], 20, 2 );
    }

    /**
     * Get Instance
     * 
     * @return void
     */
    public static function get_instance() {

        if ( null === self::$instance ) {
            self::$instance = new Filter_Permalinks();
        }

        return self::$instance;
    }

    /**
     * Checkout Page URL
     * 
     * @param string $url = ''
     * @param string $listing_id = 0
     */
    public function checkout_page_url( $url = '',  $listing_id = 0 ) {
        $pattern = '/(\/submit\/\d+)\/?/';

        if ( preg_match( $pattern, $url ) ) {
            $url = preg_replace( $pattern, '', $url );
            $url = add_query_arg( [ 'submit' => $listing_id ], $url );
        }
        
        return $url;
    }

    /**
     * Payment Receipt Page URL
     * 
     * @param string $url = ''
     * @param string $order_id = 0
     */
    public function payment_receipt_page_url( $url = '',  $order_id = 0 ) {
        $pattern = '/(\/order\/\d+)\/?/';

        if ( preg_match( $pattern, $url ) ) {
            $url = preg_replace( $pattern, '', $url );
            $url = add_query_arg( [ 'order' => $order_id ], $url );
        }

        return $url;
    }

    /**
     * Edit Listing Page URL
     * 
     * @param string $url = ''
     * @param string $listing_id = 0
     */
    public function edit_listing_page_url( $url = '',  $listing_id = 0 ) {
        $pattern = '/(\/edit\/\d+)\/?/';

        if ( preg_match( $pattern, $url ) ) {
            $url = preg_replace( $pattern, '', $url );
            $url = add_query_arg( [ 'edit' => $listing_id ], $url );
        }

        return $url;
    }
}
