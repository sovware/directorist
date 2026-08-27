<?php

namespace Directorist\Repositories;

defined( "ABSPATH" ) || exit;

use Directorist\Helpers\DateTime;
use Directorist\Utils\Repositories\Repository;
use Directorist\Utils\Database\Query\Builder;
use Directorist\DTO\Payment\DTO;
use Directorist\Enums\Order\Status as OrderStatus;
use Directorist\Enums\Payment\Status as PaymentStatus;
use Directorist\DBModels\Payment;

class PaymentRepository extends Repository {
    public OrderRepository $order_repository;

    public function __construct() {
        $this->order_repository = new OrderRepository();
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
    public function create( \Directorist\Utils\DTO $dto ) {
        $payment_id = $this->create_without_order_update( $dto );
        $payment    = $payment_id ? $this->get_last_payment( $dto->get_order_id() ) : null;

        if ( ! $payment || (int) $payment->id !== $payment_id || $payment->status !== $dto->get_status() ) {
            return 0;
        }

        if ( $dto->is_initialized( 'status' ) && $dto->get_status() === PaymentStatus::PAID ) {
            $this->order_repository->update_status( $dto->get_order_id(), OrderStatus::PAID );
        }

        return $payment_id;
    }

    /**
     * Create a payment without synchronizing the related order status.
     *
     * @param \Directorist\Utils\DTO $dto Payment data.
     * @return int Payment ID, or zero when the insert fails.
     */
    public function create_without_order_update( \Directorist\Utils\DTO $dto ) {
        global $wpdb;

        $payment_id = parent::create( $dto );

        return $wpdb->last_error ? 0 : $payment_id;
    }

    public function update_status_by_order_id( int $order_id, string $status ): int {
        return $this->get_query_builder()
            ->where( 'order_id', $order_id )
            ->update( [ 'status' => $status ] );
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
