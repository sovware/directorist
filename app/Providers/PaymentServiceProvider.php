<?php

namespace Directorist\App\Providers;

defined( "ABSPATH" ) || exit;

use Directorist\App\DTO\Payment\DTO;
use Directorist\App\Enums\Payment\Status;
use Directorist\WpMVC\Contracts\Provider;
use Directorist\App\Enums\Order\Status as OrderStatus;
use Directorist\App\DTO\Order\DTO as OrderDTO;
use Directorist\WpMVC\Exceptions\Exception;

class PaymentServiceProvider implements Provider {
    public function boot() {
        // add_action( 'dir_after_order_complete', [$this, 'handle_payment_response'], 9 );
        // add_action( 'dir_after_order_complete', [$this, 'handle_payment_response'], 9 );
    }

    public function handle_payment_response( $data ) {
        $order_id = 11; //TODO: need to make it dynamic

        $dto = ( new DTO )
        ->set_order_id( $order_id )
        ->set_amount( 10 ) //TODO: need to set payment amount
        ->set_currency( atbdp_get_payment_currency() ) //TODO: need to set payment processed currency code
        ->set_status( Status::PAID )
        ->set_transaction_id( $data['transaction_id'] )
        ->set_method( "stripe" );

        $repository = directorist_payment_repository();
        $repository->create( $dto );

        $order_dto = ( new OrderDTO )->set_id( $order_id )->set_status( OrderStatus::PAID );

        $order_repository = directorist_order_repository();
        $order_repository->update( $order_dto );
    }
}