<?php

namespace Directorist\App\PaymentProcessors;

defined( "ABSPATH" ) || exit;

use Directorist\App\DTO\Payment\DTO;
use Directorist\App\Enums\Payment\Status;
use Directorist\App\Contracts\PaymentInterface;
use Directorist\App\DTO\Order\DTO as OrderDTO;

class BankTransfer implements PaymentInterface {
    public static function get_key(): string {
        return 'bank_transfer';
    }

    public function pay( OrderDTO $dto ): ?string {
        $payment_dto = ( new DTO )
        ->set_order_id( $dto->get_id() )
        ->set_amount( $dto->get_amount() )
        ->set_currency( directorist_currency() )
        ->set_status( Status::PENDING )
        ->set_method( static::get_key() );

        directorist_payment_repository()->create( $payment_dto );
        return null;
    }
}
