<?php

defined( 'ABSPATH' ) || exit;

use Directorist\WpMVC\Enqueue\Enqueue;

if ( 'at_biz_dir_page_directorist-orders' === $hook_suffix ) {
    Enqueue::style( 'directorist/admin-order-dataview', 'build/css/style-admin-order', ['wp-components'] );
    Enqueue::script( 'directorist/admin-order', 'build/js/admin/order' );

    $c_position = directorist_get_currency_position();
    $currency   = directorist_get_currency();
    $symbol     = atbdp_currency_symbol( $currency );

    wp_localize_script(
        'directorist/admin-order', 'directorist_admin_order', [
            'symbol_position' => $c_position,
            'currency'        => $currency,
            'symbol'          => $symbol,
        ] 
    );
}
