<?php

namespace Directorist\App\Repositories;

defined( "ABSPATH" ) || exit;

use Directorist\WpMVC\Repositories\Repository;
use Directorist\WpMVC\Database\Query\Builder;
use Directorist\WpMVC\Exceptions\Exception;
use Directorist\App\Models\Order;

class OrderRepository extends Repository {
    public function get_query_builder(): Builder {
        return Order::query( 'order' );
    }

    public function get(): array {
        $query = $this->get_query_builder();

        // Add any additional query conditions here if needed.
        // For example, filtering by user ID, status, etc.

        return $query->order_by_desc( 'id' )->get();
    }

    /**
     * Create a new order.
     *
     * @param \Directorist\App\DTO\Order\DTO $dto The DTO containing order data.
     * @return int The ID of the newly created order.
     * @throws Exception If the insert operation fails.
     */
    public function create( \Directorist\WpMVC\DTO\DTO $dto ) {
        $dto->set_final_amount( $dto->get_amount() );

        do_action( 'directorist_before_order_create', $dto );

        return parent::create( $dto );
    }

    /**
     * Update an existing order.
     *
     * @param \Directorist\App\DTO\Order\DTO $dto The DTO containing updated order data.
     * @return int The number of affected rows.
     * @throws Exception If the update operation fails.
     */
    public function update( \Directorist\WpMVC\DTO\DTO $dto ) {
        $dto->set_final_amount( $dto->get_amount() );

        do_action( 'directorist_before_order_update', $dto );

        return parent::update( $dto );
    }
}