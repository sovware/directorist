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

function directorist_price( $price ) {
    return atbdp_display_price( $price, false, '', '', '', false );
}

function directorist_currency(): string {
    return atbdp_get_payment_currency();
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
    if ( $tax_rate <= 0 ) {
        return 0;
    }

    if ( $tax_type === 'percent' ) {
        return round( ( $tax_rate * $sub_total ) / 100, 2 );
    } else {
        // flat/fixed tax
        return round( $tax_rate, 2 );
    }
}
