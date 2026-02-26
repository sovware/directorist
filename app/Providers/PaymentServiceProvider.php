<?php

namespace Directorist\App\Providers;

defined( "ABSPATH" ) || exit;

use Directorist\App\DTO\Order\DTO as OrderDTO;
use Directorist\App\PaymentProcessors\BankTransfer;
use Directorist\App\Enums\Order\Status as OrderStatus;
use Directorist\App\Enums\Payment\Status as PaymentStatus;
use Directorist\WpMVC\Contracts\Provider;

class PaymentServiceProvider implements Provider {
    public function boot() {
        add_filter( 'directorist_payment_receipt_is_allowed_retry_payment', [$this, 'ignore_retry_payment'], 10, 2 );

        add_action( 'directorist_repository_after_order_update', [$this, 'update_payment_status'], 10, 1 );
    }

    public function ignore_retry_payment( bool $is_retry, ?DTO $payment ) {
        if ( $payment && $payment->get_method() === BankTransfer::get_key() ) {
            return false;
        }

        return $is_retry;
    }

    public function update_payment_status( OrderDTO $order_dto ) {
        if ( $order_dto->get_status() !== OrderStatus::PAID ) {
            return;
        }

        $payment_repository = directorist_payment_repository();
        $last_payment       = $payment_repository->get_last_payment( $order_dto->get_id() );

        if ( ! $last_payment ) {
            return;
        }

        if ( $last_payment->status === PaymentStatus::PAID ) {
            return;
        }

        $last_payment = $payment_repository->to_dto( $last_payment );

        $payment_repository->update( $last_payment->set_status( $order_dto->get_status() ) );
    }
}