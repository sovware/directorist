<?php

namespace Directorist\App\Repositories;

defined( "ABSPATH" ) || exit;

use Directorist\App\Enums\Order\Status;
use Directorist\WpMVC\Repositories\Repository;
use Directorist\WpMVC\Database\Query\Builder;
use Directorist\WpMVC\Exceptions\Exception;
use Directorist\App\Models\Order;

class OrderRepository extends Repository {
    public function get_query_builder(): Builder {
        return Order::query( 'd_order' ); // in alias using d_order instead of order. because order keyword is reserved by sql.
    }

    public function get(): array {
        $query = $this->get_query_builder()->with(
            'payments', function( $query ) {
                $query->order_by_desc( 'id' );
            }
        );

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

        // do_action( 'directorist_before_order_create', $dto );
        
        $order_id = parent::create( $dto );

        $dto->set_id( $order_id );
        
        // do_action( 'directorist_after_order_create', $dto );

        return $order_id;
    }

    /**
     * Update an existing order.
     *
     * @param \Directorist\App\DTO\Order\DTO $dto The DTO containing updated order data.
     * @return int The number of affected rows.
     * @throws Exception If the update operation fails.
     */
    public function update( \Directorist\WpMVC\DTO\DTO $dto ) {
        if ( $dto->is_initialized( "amount" ) ) {
            $dto->set_final_amount( $dto->get_amount() );
        }

        if ( $dto->is_initialized( 'status' ) && $dto->get_status() === Status::PAID ) {
            $order = $this->get_by_id( $dto->get_id() );

            if ( $order->is_featured_listing && ! $order->expires_at ) {
                $featured_days = get_directorist_option( 'featured_listing_time', 30 );
                $dto->set_expires_at( directorist_now()->add_days( $featured_days ) );
            }
        }

        // do_action( 'directorist_before_order_update', $dto );

        $update = parent::update( $dto );

        // do_action( 'directorist_after_order_update', $dto );

        return $update;
    }

    public function single( $id ) {
        return $this->get_query_builder()->with(
            'payments', function( $query ) {
                $query->order_by_desc( 'id' );
            }
        )->where( 'id', $id )->get();
    }
}