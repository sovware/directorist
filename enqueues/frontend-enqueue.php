<?php

defined( 'ABSPATH' ) || exit;

use Directorist\WpMVC\Enqueue\Enqueue;

Enqueue::style( 'directorist/frontend', 'build/css/frontend', ['wp-components'] );
Enqueue::register_script( 'directorist-payment-receipt', 'build/js/frontend/payment-receipt.js', ['jquery', 'wp-api-fetch'] );
Enqueue::script( 'directorist-listing-owner-dashboard', 'build/js/frontend/listing-owner-dashboard' );

$c_position   = directorist_get_currency_position();
    $currency = directorist_get_currency();
    $symbol   = atbdp_currency_symbol( $currency );
    
wp_localize_script(
    'directorist-listing-owner-dashboard', 'directorist_admin_order', [
        'symbol_position' => $c_position,
        'currency'        => $currency,
        'symbol'          => $symbol,
    ] 
);