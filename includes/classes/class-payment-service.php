<?php

namespace Directorist;

defined( "ABSPATH" ) || exit;

use Directorist\Utils\Template;
use Directorist\DTO\Order\DTO as OrderDTO;
use Directorist\DTO\Payment\DTO as PaymentDTO;
use Directorist\PaymentProcessors\BankTransfer;
use Directorist\Enums\Order\Status as OrderStatus;
use Directorist\Enums\Payment\Status as PaymentStatus;
use Directorist\PaymentProcessors\BankTransfer as BankTransferPaymentProcessor;

class PaymentService {
    public function __construct() {
        add_filter( 'directorist_payment_receipt_is_allowed_retry_payment', [$this, 'ignore_retry_payment'], 10, 2 );
        add_action( 'directorist_repository_after_order_update', [$this, 'update_payment_status'], 10, 1 );
        add_action( 'directorist_payment_receipt_before_order_details', [ $this, 'maybe_show_bank_transfer_instruction' ], 10, 1 );
    }

    public function maybe_show_bank_transfer_instruction( OrderDTO $order ) {
        $payable_order_statuses = [ 
            OrderStatus::PENDING,
            OrderStatus::FAILED,
            OrderStatus::UNPAID,
        ];

        if ( ! in_array( $order->get_status(), $payable_order_statuses ) ) {
            return;
        }

        $payment_repository = directorist_payment_repository();
        $payment            = $payment_repository->get_last_payment( $order->get_id() );

        if ( ! $payment ) {
            return;
        }

        if ( $payment->status === PaymentStatus::PAID ) {
            return;
        }
        
        if ( BankTransferPaymentProcessor::get_key() !== $payment->method ) {
            return;
        }

        $instruction = get_directorist_option( 'bank_transfer_instruction', __( 'Please transfer the amount to our bank account. We will process your order once we receive the payment.', 'directorist' ) );
        $instruction = str_replace( '#==ORDER_ID==', '#' . $order->get_id() . '', $instruction );
        $instruction = nl2br( $instruction );

        Template::render(
            'checkout/bank-transfer-instruction', [
                'instruction' => $instruction,
                'order'       => $order,
                'payment'     => $payment,
            ]
        );
    }

    public function ignore_retry_payment( bool $is_retry, ?PaymentDTO $payment ) {
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