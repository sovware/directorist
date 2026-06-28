<?php

defined( "ABSPATH" ) || exit;

use Directorist\Helpers\DateTime;
use Directorist\PaymentProcessors\BankTransfer;

function directorist_make( string $class, ?string $error_message = null ) {
    if ( ! class_exists( $class ) ) {
        throw new Exception( $error_message ? $error_message : sprintf( __( 'Class %s does not exist.', 'directorist' ), $class ) );
    }

    return new $class();
}

function directorist_now() {
    return DateTime::now();
}

function directorist_get_payment_processors() {
    return apply_filters(
        'directorist_payment_processors', [
            BankTransfer::get_key() => BankTransfer::class
        ] 
    );
}

function directorist_url( string $url = '' ) {
    return ATBDP_URL . $url;
}

function directorist_dir( string $dir = '' ) {
    return ATBDP_DIR . $dir;
}
