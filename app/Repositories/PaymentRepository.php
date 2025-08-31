<?php

namespace Directorist\App\Repositories;

defined( "ABSPATH" ) || exit;

use Directorist\App\Helpers\DateTime;
use Directorist\App\Models\Payment;
use Directorist\WpMVC\Repositories\Repository;
use Directorist\WpMVC\Database\Query\Builder;
use Directorist\App\DTO\Payment\DTO;
use Directorist\App\Enums\Order\Status as OrderStatus;

class PaymentRepository extends Repository {
    public OrderRepository $order_repository;

    public function __construct( OrderRepository $order_repository ) {
        $this->order_repository = $order_repository;
    }

    public function get_query_builder(): Builder {
        return Payment::query( 'payment' );
    }

    public function get( $order_id ) {
        $query = $this->get_query_builder();
        return $query->where( 'order_id', $order_id )->order_by_desc( 'id' )->get();
    }

    /**
     * Summary of create
     * @param DTO $dto
     * @return int
     */
    public function create( \Directorist\WpMVC\DTO\DTO $dto ) {
        $payment_id = parent::create( $dto );

        if ( $dto->is_initialized( 'status' ) && $dto->get_status() === OrderStatus::PAID ) {
            $this->order_repository->update_status( $dto->get_order_id(), OrderStatus::PAID );
        }

        return $payment_id;
    }

    public function get_last_payment( $order_id ) {
        $query = $this->get_query_builder();
        return $query->where( 'order_id', $order_id )->order_by_desc( 'id' )->first();
    }

    public function to_dto( $payment ) {
        $dto = ( new DTO )->set_id( $payment->id )
            ->set_order_id( $payment->order_id )
            ->set_amount( $payment->amount )
            ->set_currency( $payment->currency )
            ->set_status( $payment->status )
            ->set_method( $payment->method )
            ->set_transaction_id( $payment->transaction_id )
            ->set_created_at( new DateTime( $payment->created_at ) );

        if ( ! empty( $payment->updated_at ) ) {
            $dto->set_updated_at( new DateTime( $payment->updated_at ) );
        }

        return $dto;
    }
}