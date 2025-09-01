<?php

namespace Directorist\App\Repositories;

defined( "ABSPATH" ) || exit;

use Directorist\App\DTO\Order\DTO;
use Directorist\App\DTO\Order\Read;
use Directorist\App\Helpers\DateTime;
use Directorist\WpMVC\Repositories\Repository;
use Directorist\WpMVC\Database\Query\Builder;
use Directorist\App\Models\Order;

class OrderRepository extends Repository {
    public function get_query_builder(): Builder {
        return Order::query( 'd_order' ); // in alias using d_order instead of order. because order keyword is reserved by sql.
    }

    public function get( Read $dto ): array {
        $query = $this->get_query_builder()->select( 'd_order.*', 'users.user_email', 'users.display_name as user_display_name' );

        $query->join( 'users', 'd_order.user_id', '=', 'users.ID' );

        if ( ! empty( $dto->get_search() ) ) {
            $search_term = trim( $dto->get_search() );
            $query->where(
                function( $query ) use ( $search_term ) {
                    $query->where( 'd_order.status', 'like', '%' . $search_term . '%' )
                        ->or_where( 'd_order.final_amount', 'like', '%' . $search_term . '%' )
                        ->or_where( 'users.user_email', 'like', '%' . $search_term . '%' )
                        ->or_where( 'users.display_name', 'like', '%' . $search_term . '%' );

                    // Check if search term contains 'featured listing' (case-insensitive)
                    if ( is_int( stripos( 'featured listing', $search_term ) ) ) {
                        $query->or_where( 'd_order.is_featured_listing', 1 );
                    }
                } 
            );
        }

        $count_query = clone $query;

        $query->with(
            [
                'payment' => function( $query ) {
                    $query->select( 'id', 'order_id', 'method' )->order_by_desc( 'id' );
                }
            ]
        );

        $orders = array_map(
            function( $order ) {
                $payment_method_title  = get_directorist_option( "{$order->payment->method}_title" );
                $order->payment_method = ! empty( $payment_method_title ) ? $payment_method_title : $order->payment_method;
                
                return apply_filters( 'directorist_order_data', $order );
            }, $query->order_by_desc( 'd_order.id' )->pagination( $dto->get_page(), $dto->get_per_page() ) 
        );

        return [
            "items" => $orders,
            "total" => $count_query->count( "d_order.id" )
        ];
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
        do_action( 'directorist_before_order_update', $dto );

        $update = parent::update( $dto );

        do_action( 'directorist_after_order_update', $dto );

        return $update;
    }

    public function update_status( int $order_id, string $status ) {
        $dto    = ( new DTO )->set_id( $order_id )->set_status( $status );
        $update = $this->update( $dto );
        return $update;
    }

    public function single( $id ) {
        return $this->get_query_builder()->with(
            [
                'user'     => function( $query ) {
                    $query->select( 'ID', 'user_email', 'display_name' );
                },
                'payments' => function( $query ) {
                    $query->order_by_desc( 'id' );
                }
            ]
        )->where( 'd_order.id', $id )->first();
    }

    public function to_dto( $order ) {
        $dto = ( new DTO )
            ->set_id( $order->id )
            ->set_subscription_id( $order->subscription_id )
            ->set_user_id( $order->user_id )
            ->set_listing_id( $order->listing_id )
            ->set_plan_id( $order->plan_id )
            ->set_is_featured_listing( $order->is_featured_listing )
            ->set_amount( $order->amount )
            ->set_currency( $order->currency )
            ->set_final_amount( $order->final_amount )
            ->set_status( $order->status )
            ->set_created_at( new DateTime( $order->created_at ) );
        
        if ( ! empty( $order->updated_at ) ) {
            $dto->set_updated_at( new DateTime( $order->updated_at ) );
        }

        if ( ! empty( $order->expires_at ) ) {
            $dto->set_expires_at( new DateTime( $order->expires_at ) );
        }

        return $dto;
    }
}