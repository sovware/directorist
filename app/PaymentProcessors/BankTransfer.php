<?php

namespace Directorist\App\PaymentProcessors;

defined( "ABSPATH" ) || exit;

use Directorist\App\DTO\Payment\DTO;
use Directorist\App\Enums\Payment\Status;
use Directorist\App\Repositories\PaymentRepository;
use Directorist\App\Contracts\PaymentInterface;
use stdClass;

class BankTransfer implements PaymentInterface {
    public PaymentRepository $payment_repository;

    public function __construct( PaymentRepository $payment_repository ) {
        $this->payment_repository = $payment_repository;
    }

    public static function get_key(): string {
        return 'bank_transfer';
    }

    public function pay( stdClass $order, array $params = [] ) {
        $dto = ( new DTO )->set_order_id( $order->id )->set_amount( $order->amount )->set_currency( atbdp_get_payment_currency() )->set_status( Status::PENDING )->set_method( static::get_key() );
        $this->payment_repository->create( $dto );
    }
}
