<?php
/**
 * Legacy REST orders controller.
 *
 * @package Directorist\Rest_Api
 */

namespace Directorist\Rest_Api\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Directorist\DTO\Order\DTO as OrderDTO;
use Directorist\DTO\Payment\DTO as PaymentDTO;
use Directorist\Enums\Order\Status as OrderStatus;
use Directorist\Rest_Api\DateTime;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Restores the pre-migration orders API shape on directorist/v1 while using
 * the new Directorist order/payment repositories internally.
 */
class Orders_Controller extends Abstract_Controller {
    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'orders';

    /**
     * Register routes.
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            array(
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_items' ),
                    'permission_callback' => array( $this, 'get_items_permissions_check' ),
                    'args'                => $this->get_collection_params(),
                ),
                array(
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => array( $this, 'create_item' ),
                    'permission_callback' => array( $this, 'create_item_permissions_check' ),
                    'args'                => $this->get_create_params(),
                ),
                'schema' => array( $this, 'get_public_item_schema' ),
            )
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/(?P<id>[\d]+)',
            array(
                'args'   => array(
                    'id' => array(
                        'description' => __( 'Order id.', 'directorist' ),
                        'type'        => 'integer',
                    ),
                ),
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_item' ),
                    'permission_callback' => array( $this, 'get_items_permissions_check' ),
                    'args'                => array(
                        'context' => $this->get_context_param( array( 'default' => 'view' ) ),
                    ),
                ),
                'schema' => array( $this, 'get_public_item_schema' ),
            )
        );
    }

    public function get_items_permissions_check( $request ) {
        return is_user_logged_in();
    }

    public function create_item_permissions_check( $request ) {
        return is_user_logged_in();
    }

    public function create_item( WP_REST_Request $request ) {
        if ( ! directorist_is_monetization_enabled() ) {
            return new WP_Error( 'invalid_request', __( 'Monetization disabled.', 'directorist' ), array( 'status' => 400 ) );
        }

        $customer_id = absint( $request->get_param( 'customer' ) );
        $customer_id = $customer_id ? $customer_id : get_current_user_id();

        if ( ! $customer_id || ! get_user_by( 'id', $customer_id ) ) {
            return new WP_Error( 'invalid_customer', __( 'Invalid customer.', 'directorist' ), array( 'status' => 400 ) );
        }

        $plan_id = absint( $request->get_param( 'plan' ) );
        if ( $plan_id && get_post_type( $plan_id ) !== 'atbdp_pricing_plans' && ! apply_filters( 'directorist_rest_legacy_order_valid_plan', false, $plan_id, $request ) ) {
            return new WP_Error( 'invalid_plan', __( 'Invalid plan.', 'directorist' ), array( 'status' => 400 ) );
        }

        $listing_id = absint( $request->get_param( 'listing' ) );
        if ( $listing_id && ! directorist_is_listing_post_type( $listing_id ) ) {
            return new WP_Error( 'invalid_listing', __( 'Invalid listing.', 'directorist' ), array( 'status' => 400 ) );
        }

        if ( function_exists( 'directorist_direct_purchase' ) && directorist_direct_purchase() ) {
            $listing_id = 0;
        }

        $amount        = round( (float) $request->get_param( 'amount' ), 2 );
        $currency      = sanitize_text_field( (string) $request->get_param( 'currency' ) );
        $currency      = $currency ? $currency : atbdp_get_payment_currency();
        $legacy_status = sanitize_key( (string) $request->get_param( 'payment_status' ) );
        $legacy_status = $legacy_status ? $legacy_status : 'created';
        $created_by    = sanitize_key( (string) $request->get_param( 'created_by' ) );
        $created_by    = $created_by ? $created_by : 'web';
        $gateway       = sanitize_key( (string) $request->get_param( 'payment_gateway' ) );
        $gateway       = $gateway ? $gateway : 'free';

        $dto = ( new OrderDTO() )
            ->set_user_id( $customer_id )
            ->set_amount( $amount )
            ->set_sub_total( $amount )
            ->set_currency( $currency )
            ->set_status( $this->legacy_status_to_current_status( $legacy_status ) );

        if ( $listing_id ) {
            $dto->set_listing_id( $listing_id );
        }

        if ( $plan_id ) {
            $dto->set_ref( (string) $plan_id )->set_ref_type( 'pricing_plan' );
        }

        if ( $request->get_param( 'featured' ) ) {
            $dto->set_is_featured_listing( 1 );
        }

        $dto = apply_filters( 'directorist_rest_legacy_order_create_dto', $dto, $request );

        $order_id = directorist_order_repository()->create( $dto );

        if ( ! $order_id ) {
            return new WP_Error( 'invalid_order', __( 'Unable to create order.', 'directorist' ), array( 'status' => 400 ) );
        }

        $transaction_id = sanitize_text_field( (string) $request->get_param( 'transaction_id' ) );

        if ( $gateway || $transaction_id ) {
            $payment_dto = ( new PaymentDTO() )
                ->set_order_id( $order_id )
                ->set_amount( $amount )
                ->set_currency( $currency )
                ->set_status( $this->legacy_status_to_current_status( $legacy_status ) )
                ->set_method( $gateway )
                ->set_transaction_id( $transaction_id ? $transaction_id : null );

            directorist_payment_repository()->create( apply_filters( 'directorist_rest_legacy_order_create_payment_dto', $payment_dto, $request, $dto ) );
        }

        do_action( 'atbdp_order_created', $order_id, $listing_id );
        do_action( 'directorist_rest_legacy_order_created', $order_id, $request, $dto );

        $order    = $this->get_order( $order_id );
        $response = rest_ensure_response( $this->prepare_legacy_order_data( $order, $request ) );
        $response->set_status( 201 );
        $response->header( 'Location', rest_url( '/' . $this->namespace . '/' . $this->rest_base . '/' . $order_id ) );

        return apply_filters( 'directorist_rest_response', $response, 'create_order_item', $request );
    }

    public function get_items( WP_REST_Request $request ) {
        $page     = max( 1, absint( $request->get_param( 'page' ) ) );
        $per_page = max( 1, absint( $request->get_param( 'per_page' ) ) );
        $query    = $this->get_order_query( $request );
        $total    = (int) ( clone $query )->count( 'd_order.id' );
        $orders   = $query->pagination( $page, $per_page );
        $items    = array();

        foreach ( $orders as $order ) {
            $items[] = $this->prepare_legacy_order_data( $order, $request );
        }

        $max_pages = (int) ceil( $total / $per_page );
        $response  = rest_ensure_response( $items );
        $response->header( 'X-WP-Total', $total );
        $response->header( 'X-WP-TotalPages', $max_pages );

        $base = add_query_arg( $request->get_query_params(), rest_url( sprintf( '/%s/%s', $this->namespace, $this->rest_base ) ) );

        if ( $page > 1 ) {
            $response->link_header( 'prev', add_query_arg( 'page', min( $page - 1, $max_pages ), $base ) );
        }

        if ( $max_pages > $page ) {
            $response->link_header( 'next', add_query_arg( 'page', $page + 1, $base ) );
        }

        return apply_filters( 'directorist_rest_response', $response, 'get_order_items', $request );
    }

    public function get_item( WP_REST_Request $request ) {
        $order = $this->get_order( absint( $request->get_param( 'id' ) ) );

        if ( ! $order ) {
            return new WP_Error( 'directorist_rest_invalid_atbdp_orders_id', __( 'Invalid ID.', 'directorist' ), array( 'status' => 404 ) );
        }

        $response = rest_ensure_response( $this->prepare_legacy_order_data( $order, $request ) );

        return apply_filters( 'directorist_rest_response', $response, 'get_order_item', $request, absint( $request->get_param( 'id' ) ) );
    }

    protected function get_order_query( WP_REST_Request $request ) {
        $query = directorist_order_repository()->get_query_builder()
            ->select( 'd_order.*' )
            ->with(
                array(
                    'payment' => function( $payment_query ) {
                        $payment_query->select( 'id', 'order_id', 'method', 'transaction_id', 'status' )->order_by_desc( 'id' );
                    },
                )
            );

        $customer = absint( $request->get_param( 'customer' ) );
        if ( $customer ) {
            $query->where( 'd_order.user_id', $customer );
        }

        $orderby = sanitize_key( (string) $request->get_param( 'orderby' ) );
        $order   = strtoupper( sanitize_key( (string) $request->get_param( 'order' ) ) );
        $order   = in_array( $order, array( 'ASC', 'DESC' ), true ) ? $order : 'DESC';
        $column  = 'customer' === $orderby ? 'd_order.user_id' : 'd_order.created_at';

        if ( 'ASC' === $order ) {
            $query->order_by( $column );
        } else {
            $query->order_by_desc( $column );
        }

        return apply_filters( 'directorist_rest_legacy_order_object_query', $query, $request );
    }

    protected function get_order( int $order_id ) {
        if ( ! $order_id ) {
            return null;
        }

        $request = new WP_REST_Request();
        $request->set_param( 'orderby', 'date' );
        $request->set_param( 'order', 'desc' );

        return $this->get_order_query( $request )->where( 'd_order.id', $order_id )->first();
    }

    protected function prepare_legacy_order_data( $order, WP_REST_Request $request ): array {
        $payment = ! empty( $order->payment ) ? $order->payment : null;
        $plan_id = $this->get_plan_id( $order );

        $data = array(
            'id'                          => (int) $order->id,
            'title'                       => sprintf( __( 'Order #%d', 'directorist' ), (int) $order->id ),
            'date_created'                => $this->format_date( $order->created_at ?? '' ),
            'date_modified'               => $this->format_date( $order->updated_at ?? $order->created_at ?? '' ),
            'customer'                    => (int) $order->user_id,
            'plan'                        => $plan_id,
            'directory'                   => $plan_id ? (int) get_post_meta( $plan_id, '_assign_to_directory', true ) : 0,
            'listing'                     => (int) ( $order->listing_id ?? 0 ),
            'featured'                    => ! empty( $order->is_featured_listing ),
            'remaining_listings'          => 0,
            'remaining_featured_listings' => 0,
            'amount'                      => round( (float) ( $order->amount ?? 0 ), 2 ),
            'currency'                    => (string) ( $order->currency ?? '' ),
            'payment_status'              => $this->current_status_to_legacy_status( $payment->status ?? $order->status ?? '' ),
            'payment_gateway'             => (string) ( $payment->method ?? '' ),
            'transaction_id'              => (string) ( $payment->transaction_id ?? '' ),
            'created_by'                  => 'web',
        );

        $data['remaining_listings']          = $this->get_remaining_listings_count( $order, $data );
        $data['remaining_featured_listings'] = $this->get_remaining_featured_listings_count( $order, $data );

        return apply_filters( 'directorist_rest_legacy_order_data', $data, $order, $request );
    }

    protected function get_plan_id( $order ): int {
        if ( ! empty( $order->ref ) && ! empty( $order->ref_type ) && 'pricing_plan' === $order->ref_type ) {
            return (int) $order->ref;
        }

        return (int) apply_filters( 'directorist_rest_legacy_order_plan_id', 0, $order );
    }

    protected function get_remaining_listings_count( $order, array $data ): int {
        return (int) apply_filters( 'directorist_rest_legacy_order_remaining_listings', 0, $order, $data );
    }

    protected function get_remaining_featured_listings_count( $order, array $data ): int {
        return (int) apply_filters( 'directorist_rest_legacy_order_remaining_featured_listings', 0, $order, $data );
    }

    protected function legacy_status_to_current_status( string $status ): string {
        $map = array(
            'created'   => OrderStatus::PENDING,
            'pending'   => OrderStatus::PENDING,
            'completed' => OrderStatus::PAID,
            'failed'    => OrderStatus::FAILED,
            'cancelled' => OrderStatus::CANCELLED,
            'refunded'  => OrderStatus::REFUNDED,
        );

        return $map[ $status ] ?? OrderStatus::PENDING;
    }

    protected function current_status_to_legacy_status( string $status ): string {
        $map = array(
            OrderStatus::PENDING   => 'pending',
            OrderStatus::PAID      => 'completed',
            OrderStatus::FAILED    => 'failed',
            OrderStatus::CANCELLED => 'cancelled',
            OrderStatus::REFUNDED  => 'refunded',
            OrderStatus::UNPAID    => 'created',
            OrderStatus::EXPIRED   => 'failed',
        );

        return $map[ $status ] ?? $status;
    }

    protected function format_date( string $date ): string {
        if ( ! $date ) {
            return '';
        }

        return directorist_rest_prepare_date_response( $date, false );
    }

    public function get_collection_params() {
        return array(
            'context'  => $this->get_context_param( array( 'default' => 'view' ) ),
            'page'     => array(
                'default'           => 1,
                'description'       => __( 'Current page of the collection.', 'directorist' ),
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
            ),
            'per_page' => array(
                'default'           => 10,
                'description'       => __( 'Maximum number of items to be returned in result set.', 'directorist' ),
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
            ),
            'order'    => array(
                'default'           => 'desc',
                'description'       => __( 'Order sort attribute ascending or descending.', 'directorist' ),
                'enum'              => array( 'asc', 'desc' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_key',
            ),
            'orderby'  => array(
                'default'           => 'date',
                'description'       => __( 'Sort collection by object attribute.', 'directorist' ),
                'enum'              => array( 'customer', 'date' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_key',
            ),
            'customer' => array(
                'description'       => __( 'Limit result set to specific customer id.', 'directorist' ),
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
            ),
        );
    }

    protected function get_create_params() {
        $schema = $this->get_item_schema();

        return $schema['properties'];
    }

    public function get_item_schema() {
        $schema = array(
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'atbdp_orders',
            'type'       => 'object',
            'properties' => array(
                'id'                          => array( 'type' => 'integer', 'readonly' => true, 'context' => array( 'view', 'edit' ) ),
                'title'                       => array( 'type' => 'string', 'readonly' => true, 'context' => array( 'view', 'edit' ) ),
                'date_created'                => array( 'type' => 'date-time', 'readonly' => true, 'context' => array( 'view', 'edit' ) ),
                'date_modified'               => array( 'type' => 'date-time', 'readonly' => true, 'context' => array( 'view', 'edit' ) ),
                'customer'                    => array( 'type' => 'integer', 'context' => array( 'view', 'edit' ) ),
                'plan'                        => array( 'type' => 'integer', 'context' => array( 'view', 'edit' ) ),
                'directory'                   => array( 'type' => 'integer', 'readonly' => true, 'context' => array( 'view' ) ),
                'listing'                     => array( 'type' => 'integer', 'context' => array( 'view', 'edit' ) ),
                'featured'                    => array( 'type' => 'boolean', 'context' => array( 'view', 'edit' ) ),
                'remaining_listings'          => array( 'type' => 'integer', 'readonly' => true, 'context' => array( 'view' ) ),
                'remaining_featured_listings' => array( 'type' => 'integer', 'readonly' => true, 'context' => array( 'view' ) ),
                'amount'                      => array( 'type' => 'number', 'context' => array( 'view', 'edit' ) ),
                'currency'                    => array( 'type' => 'string', 'context' => array( 'view', 'edit' ) ),
                'payment_status'              => array( 'type' => 'string', 'enum' => array_keys( atbdp_get_payment_statuses() ), 'context' => array( 'view', 'edit' ) ),
                'payment_gateway'             => array( 'type' => 'string', 'default' => 'free', 'context' => array( 'view', 'edit' ) ),
                'transaction_id'              => array( 'type' => 'string', 'context' => array( 'view', 'edit' ) ),
                'created_by'                  => array( 'type' => 'string', 'enum' => array( 'app-store', 'play-store', 'web' ), 'default' => 'web', 'context' => array( 'view', 'edit' ) ),
            ),
        );

        return $this->add_additional_fields_schema( $schema );
    }
}
