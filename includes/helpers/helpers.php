<?php

defined( "ABSPATH" ) || exit;

require_once __DIR__ . '/app.php';
require_once __DIR__ . '/repositories.php';

function directorist_get_checkout_page_url( string $checkout_type, array $query_args = [] ) {
    $query_args['checkout_type'] = $checkout_type;
    
    $checkout_page_id = get_directorist_option( 'checkout_page', 0 );
    $link             = add_query_arg( $query_args, get_permalink( $checkout_page_id ) );

    return apply_filters( 'directorist_checkout_page_url', $link, $checkout_page_id, $checkout_type, $query_args );
}

function directorist_get_checkout_types(): array {
    return apply_filters( 'directorist_checkout_types', [] );
}

function directorist_price( $price, $html = true ) {
    return atbdp_display_price( $price, false, '', '', '', false, $html );
}

function directorist_currency(): string {
    return atbdp_get_payment_currency();
}

function directorist_permalink(): ATBDP_Permalink {
    static $instance = null;

    if ( null === $instance ) {
        $instance = new ATBDP_Permalink();
    }

    return $instance;
}


function directorist_payment_failure_url( array $query_args = [] ) {
    return add_query_arg( $query_args, ATBDP_Permalink::get_transaction_failure_page_link() );
}

function directorist_payment_receipt_page_link( $order_id ) {
    return ATBDP_Permalink::get_payment_receipt_page_link( $order_id );
}

function directorist_get_order_by_id( $order_id ) {
    $order_repository = directorist_order_repository();
    return $order_repository->get_by_id( $order_id );
}

function directorist_calculate_tax_amount( string $tax_type, float $tax_rate, float $sub_total ): float {
    return directorist_compute_fixed_or_percent_amount( $tax_type, $tax_rate, $sub_total );
}

function directorist_compute_fixed_or_percent_amount( string $type, float $rate, float $amount ): float {
    if ( $rate <= 0 || $amount <= 0 ) {
        return 0;
    }

    if ( $type === 'flat' || $type === 'fixed' ) {
        return round( $rate, 2 );
    } else {
        return round( ( $amount * $rate ) / 100, 2 );
    }
}

function directorist_is_listing_featured( int $listing_id ): bool {
    return strval( get_post_meta( $listing_id, '_featured', true ) ) === '1';
}

function directorist_set_listing_featured( int $listing_id, bool $is_featured = true ): bool {
    return update_post_meta( $listing_id, '_featured', $is_featured ? '1' : '0' );
}

function directorist_set_listing_status( int $listing_id, string $status ): bool {
    return wp_update_post(
        [
            'ID'          => $listing_id,
            'post_status' => $status,
        ] 
    );
}

function directorist_get_directory_by_slug( string $slug ) {
    $directory = get_term_by( 'slug', $slug, 'atbdp_listing_types' );
    return $directory ? $directory : null;
}

function directorist_date_time_format() {
    return apply_filters( 'directorist_date_time_format', 'Y-m-d H:i:s' );
}