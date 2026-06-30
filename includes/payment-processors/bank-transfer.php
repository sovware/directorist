<?php

namespace Directorist\PaymentProcessors;

defined( "ABSPATH" ) || exit;

use Directorist\DTO\Payment\DTO;
use Directorist\Enums\Payment\Status;
use Directorist\Contracts\PaymentInterface;
use Directorist\DTO\Order\DTO as OrderDTO;

class BankTransfer extends Payment implements PaymentInterface {
    public static function get_key(): string {
        return 'bank_transfer';
    }

    public function pay( OrderDTO $dto, array $params = [] ): ?string {
        $payment_dto = ( new DTO )
        ->set_order_id( $dto->get_id() )
        ->set_amount( $dto->get_amount() )
        ->set_currency( directorist_currency() )
        ->set_status( Status::PENDING )
        ->set_method( static::get_key() )
        ->set_transaction_id( wp_generate_password( 15, false ) );

        directorist_payment_repository()->create( $payment_dto );
        return null;
    }
}
