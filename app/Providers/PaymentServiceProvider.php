<?php

namespace Directorist\App\Providers;

defined( "ABSPATH" ) || exit;

use Directorist\App\DTO\Payment\DTO;
use Directorist\App\PaymentProcessors\BankTransfer;
use Directorist\WpMVC\Contracts\Provider;

class PaymentServiceProvider implements Provider {
    public function boot() {
        add_filter( 'directorist_payment_receipt_is_allowed_retry_payment', [$this, 'ignore_retry_payment'], 10, 2 );
        add_filter( 'directorist_order_data', [$this, 'handle_order_data'], 10, 1 );
    }

    public function ignore_retry_payment( bool $is_retry, DTO $payment ) {
        if ( $payment->get_method() === BankTransfer::get_key() ) {
            return false;
        }

        return $is_retry;
    }

    public function handle_order_data( $order ) {
        if ( BankTransfer::get_key() === $order->payment_method ) {
            $title                 = get_directorist_option( "{$order->payment_method}_title" );
            $order->payment_method = ! empty( $title ) ? $title : __( 'Bank Transfer', 'directorist' );
        }

        return $order;
    }
}