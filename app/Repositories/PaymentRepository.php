<?php

namespace Directorist\App\Repositories;

defined( "ABSPATH" ) || exit;

use Directorist\App\Enums\Payment\Status;
use Directorist\App\Models\Payment;
use Directorist\WpMVC\Repositories\Repository;
use Directorist\WpMVC\Database\Query\Builder;
use Directorist\App\DTO\Payment\DTO;
use Directorist\App\Enums\Order\Status as OrderStatus;
use Directorist\WpMVC\Exceptions\Exception;

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

        // if ( $dto->is_initialized( 'status' ) && $dto->get_status() === Status::PAID ) {
        //     $dto = ( new \Directorist\App\DTO\Order\DTO )->set_id( $dto->get_order_id() )->set_status( OrderStatus::PAID );
        //     $this->order_repository->update( $dto );
        // }

        return $payment_id;
    }
}