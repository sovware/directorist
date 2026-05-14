<?php

namespace Directorist\Repositories;


defined( "ABSPATH" ) || exit;

use Directorist\Utils\Exception;
use Directorist\Utils\Repositories\Repository;
use Directorist\Utils\Database\Query\Builder;
use Directorist\Helpers\DateTime;
use Directorist\DTO\Order\DTO;
use Directorist\Enums\Order\Status as OrderStatus;
use Directorist\Enums\Order\TaxType as OrderTaxType;
use Directorist\Enums\Order\DiscountType as OrderDiscountType;
use Directorist\DTO\Order\Read;
use Directorist\DBModels\Post;
use Directorist\DBModels\Order;

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
                    $query->where_like( 'd_order.id', $search_term )
                        ->or_where_like( 'd_order.status', $search_term )
                        ->or_where_like( 'users.user_email', $search_term )
                        ->or_where_like( 'users.display_name', $search_term );

                    // Check if search term contains 'featured listing' (case-insensitive)
                    if ( is_int( stripos( 'featured listing', $search_term ) ) ) {
                        $query->or_where( 'd_order.is_featured_listing', 1 );
                    }
                }
            );
        }

        $count_query = clone $query;

        return [
            "items" => $this->get_orders( $query, $dto ),
            "total" => $count_query->count( "d_order.id" )
        ];
    }

    public function get_by_user_id( int $user_id, Read $dto ) {
        $query = $this->get_query_builder()->select( 'd_order.*' )->where( 'd_order.user_id', $user_id );

        if ( ! empty( $dto->get_search() ) ) {
            $search_term = trim( $dto->get_search() );
            $query->where(
                function( $query ) use ( $search_term ) {
                    $query->where_like( 'd_order.id', $search_term )
                        ->or_where_like( 'd_order.status', $search_term );

                    // Check if search term contains 'featured listing' (case-insensitive)
                    if ( is_int( stripos( 'featured listing', $search_term ) ) ) {
                        $query->or_where( 'd_order.is_featured_listing', 1 );
                    }
                }
            );
        }

        $count_query = clone $query;

        return [
            "items" => $this->get_orders( $query, $dto ),
            "total" => $count_query->count( "d_order.id" )
        ];
    }

    public function listing_has_paid_featured_order( int $listing_id ): bool {
        $order = $this->get_query_builder()->select( 'd_order.id' )
            ->where( 'd_order.ref_type', 'featured_listing' )
            ->where( 'd_order.listing_id', $listing_id )
            ->where( 'd_order.status', OrderStatus::PAID )
            ->first();

        return $order ? true : false;
    }

    protected function get_orders( Builder $query, Read $dto ) {
        $query = apply_filters( 'directorist_order_list_query', $query, $dto );

        $query->with(
            [
                'payment' => function( $query ) {
                    $query->select( 'id', 'order_id', 'method', 'transaction_id' )->order_by_desc( 'id' );
                }
            ]
        );

        $orders = array_map(
            function( $order ) {
                if ( ! empty( $order->payment ) ) {
                    $order->payment_method  = $this->get_payment_method_title( $order->payment->method );
                    $order->transaction_id  = $order->payment->transaction_id ?? null;
                }

                $order->total_amount = directorist_order_total_amount( $order );

                return apply_filters( 'directorist_order_data', $order );
            }, $query->order_by_desc( 'd_order.id' )->pagination( $dto->get_page(), $dto->get_per_page() ) 
        );

        return $orders;
    }

    /**
     * Create a new order.
     *
     * @param \Directorist\DTO\Order\DTO $dto The DTO containing order data.
     * @return int The ID of the newly created order.
     * @throws Exception If the insert operation fails.
     */
    public function create( \Directorist\Utils\DTO $dto ) {
        do_action( 'directorist_before_order_create', $dto );
        
        $order_id = parent::create( $dto );

        $dto->set_id( $order_id );
        
        do_action( 'directorist_after_order_create', $dto );

        return $order_id;
    }

    public function create_many( array $dtos ) {
        return $this->get_query_builder()->insert(
            array_map(
                function( $dto ) {
                    return $this->process_values( $dto->to_array() );
                }, $dtos
            )
        );
    }

    /**
     * Update an existing order.
     *
     * @param \Directorist\DTO\Order\DTO $dto The DTO containing updated order data.
     * @return int The number of affected rows.
     * @throws Exception If the update operation fails.
     */
    public function update( \Directorist\Utils\DTO $dto ) {
        $old_order = $this->get_by_id( $dto->get_id() );
        
        if ( ! $old_order ) {
            throw new Exception( 'Order not found' );
        }

        do_action( 'directorist_before_order_update', $dto, $old_order );
        
        $update        = parent::update( $dto );
        $updated_order = $this->get_by_id( $dto->get_id() );

        if ( ! $updated_order ) {
            throw new Exception( 'Failed to update order' );
        }

        do_action( 'directorist_after_order_update', $this->to_dto( $updated_order ), $old_order );

        if ( $dto->is_initialized( 'status' ) ) {
            ( new PaymentRepository )->update_status_by_order_id( $dto->get_id(), $dto->get_status() );
        }

        return $update;
    }

    /**
     * Update an existing order silently.
     *
     * @param \Directorist\DTO\Order\DTO $dto The DTO containing updated order data.
     * @return int The number of affected rows.
     * @throws Exception If the update operation fails.
     */
    public function silent_update( \Directorist\Utils\DTO $dto ) {
        return parent::update( $dto );
    }

    public function update_status( int $order_id, string $status ) {
        $dto    = ( new DTO )->set_id( $order_id )->set_status( $status );
        $update = $this->update( $dto );
        return $update;
    }

    public function single( $id ) {
        $order = $this->get_query_builder()->select( 'd_order.*', 'directorist_listing.post_title as listing_title' )->with(
            [
                'user'     => function( $query ) {
                    $query->select( 'ID', 'user_email', 'display_name' );
                },
                'payments' => function( $query ) {
                    $query->order_by_desc( 'id' );
                }
            ]
        )->left_join( Post::get_table_name() . ' as directorist_listing', 'd_order.listing_id', '=', 'directorist_listing.ID' )->where( 'd_order.id', $id )->first();

        if ( ! $order ) {
            return null;
        }

        if ( ! empty( $order->payments[0] ) ) {
            $order->payment_method = $this->get_payment_method_title( $order->payments[0]->method );
            $order->transaction_id = $order->payments[0]->transaction_id ?? null;
        } else {
            $order->payment_method = 'N/A';
            $order->transaction_id = null;
        }

        $sub_total = (float) $order->sub_total;

        $order->discount_label  = null;
        $order->discount_amount = null;

        if ( ! empty( $order->coupon_discount ) && ! empty( $order->coupon_discount_type ) ) {
            $discount_amount = directorist_compute_fixed_or_percent_amount( $order->coupon_discount_type, $order->coupon_discount, $sub_total );
            $sub_total       = max( 0, $sub_total - $discount_amount );

            $order->discount_amount = $discount_amount > 0 ? $discount_amount : null;
            $order->discount_label  = OrderDiscountType::PERCENT === $order->coupon_discount_type ? sprintf( esc_html__( 'Discount ( %s%% )', 'directorist' ), $order->coupon_discount ) : esc_html__( 'Discount', 'directorist' );
        }

        $order->tax_label  = null;
        $order->tax_amount = null;
        $tax_amount        = directorist_compute_fixed_or_percent_amount( $order->tax_type, $order->tax_rate, $sub_total );
        
        if ( $tax_amount > 0 ) {
            $order->tax_amount = $tax_amount;
            $order->tax_label  = OrderTaxType::PERCENT === $order->tax_type ? sprintf( esc_html__( 'Tax ( %s%% )', 'directorist' ), $order->coupon_discount ) : esc_html__( 'Tax', 'directorist' );
        }
        
        $order->total_amount = directorist_order_total_amount( $order );

        return apply_filters( 'directorist_order_data', $order );
    }

    public function to_dto( $order ) {
        if ( ! $order ) {
            return null;
        }
        $dto = ( new DTO )
            ->set_id( $order->id )
            ->set_subscription_id( $order->subscription_id )
            ->set_user_id( $order->user_id )
            ->set_listing_id( $order->listing_id )
            ->set_is_featured_listing( $order->is_featured_listing )
            ->set_ref( $order->ref ?? null )
            ->set_ref_type( $order->ref_type ?? null )
            ->set_amount( $order->amount )
            ->set_currency( $order->currency )
            ->set_coupon_code( $order->coupon_code )
            ->set_coupon_discount( $order->coupon_discount )
            ->set_coupon_discount_type( $order->coupon_discount_type )
            ->set_tax_type( $order->tax_type ?? '' )
            ->set_tax_rate( $order->tax_rate ?? 0.0 )
            ->set_sub_total( $order->sub_total )
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

    private function get_payment_method_title( $payment_method ) {
        $payment_method_title = get_directorist_option( "{$payment_method}_title" );
        return ! empty( $payment_method_title ) ? $payment_method_title : $payment_method;
    }
}