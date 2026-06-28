<?php

namespace Directorist\Repositories;

defined( "ABSPATH" ) || exit;

use Directorist\Utils\Repositories\Repository;
use Directorist\Utils\Database\Query\Builder;
use Directorist\Utils\Exception;
use Directorist\Enums\Order\Status as OrderStatus;
use Directorist\Enums\Refund\Status;
use Directorist\DTO\Refund\Read;
use Directorist\DTO\Order\DTO as OrderDTO;
use Directorist\DTO\Refund\DTO;
use Directorist\DBModels\Refund;
use Directorist\Repositories\OrderRepository;

class RefundRepository extends Repository {
    private OrderRepository $order_repository;

    public function __construct() {
        $this->order_repository = new OrderRepository();
    }

    public function get_query_builder(): Builder {
        return Refund::query();
    }

    public function get( Read $dto ): array {
        $query = $this->get_query_builder()->where( 'order_id', $dto->get_order_id() );

        if ( $dto->get_search() ) {
            $query->where( 'reason', 'like', '%' . $dto->get_search() . '%' );
        }

        $count = clone $query;

        return [
            'total'          => $count->count(),
            'total_refunded' => $this->get_order_total_refunded( $dto->get_order_id() ),
            'items'          => $query->order_by_desc( 'id' )->pagination( $dto->get_page(), $dto->get_per_page() ),
        ];
    }

    /**
     * Create a new refund for an order
     * 
     * This method handles the complete refund creation workflow including:
     * - Order validation and retrieval
     * - Refund amount validation against remaining order amount
     * - Refund creation in database
     * - Order status update if fully refunded
     * - Action hooks for extensibility
     * 
     * @param DTO $dto Data Transfer Object containing refund information
     * @return int The ID of the created refund
     * @throws Exception When order not found, already refunded, or refund amount exceeds remaining amount
     */
    public function create( $dto ) {
        // Retrieve and validate the order exists
        $order = $this->order_repository->to_dto( $this->order_repository->get_by_id( $dto->get_order_id() ) );

        if ( ! $order ) {
            throw new Exception( esc_html__( "Order not found" ) );
        }

        $total = $order->get_sub_total();

        if ( ! empty( $order->get_tax_rate() ) ) {
            $total += directorist_calculate_tax_amount( $order->get_tax_type(), $order->get_tax_rate(), $order->get_sub_total() );
        }

        // Calculate total amount already refunded for this order
        $already_refunded = $this->get_order_total_refunded( $dto->get_order_id() );

        // Prevent refunding if order is already fully refunded
        if ( $already_refunded >= $total ) {
            throw new Exception( esc_html__( "Order is already refunded" ) );
        }

        // Calculate remaining amount that can still be refunded
        $remaining_amount = abs( $total - $already_refunded );

        // Validate that refund amount doesn't exceed remaining refundable amount
        if ( $remaining_amount < $dto->get_amount() ) {
            // translators: %s is the remaining amount
            throw new Exception( sprintf( esc_html__( "You cannot refund more than the remaining amount: %s", "directorist" ), $remaining_amount ) );
        }

        // Allow external code to hook into the refund creation process
        do_action( 'directorist_before_refund_created', $dto, $order, $remaining_amount, $already_refunded );

        // Insert the refund record and get the new refund ID
        $refund_id = parent::create( $dto );

        // After refund creation, recalculate remaining refundable amount
        $remaining_amount = $already_refunded + $dto->get_amount();

        // If order is now fully refunded, update order status to REFUNDED
        if ( $remaining_amount === $total ) {
            $dto = ( new OrderDTO )->set_id( $order->get_id() )->set_status( OrderStatus::REFUNDED );
            $this->order_repository->update( $dto );
        }

        // Allow external code to hook into post-refund creation process
        do_action( 'directorist_after_refund_created', $refund_id, $dto, $order, $remaining_amount, $already_refunded );

        return $refund_id;
    }

    public function get_order_total_refunded( int $order_id ) {
        return $this->get_query_builder()->where( 'order_id', $order_id )->where( 'status', Status::PAID )->sum( 'amount' );
    }
}