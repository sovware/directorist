<?php

namespace Directorist\Rest_Api\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use WP_REST_Request;
use WP_REST_Server;
use WP_Error;

use Directorist\Enums\Order\Status as OrderStatus;
use Directorist\DTO\Order\DTO as OrderDTO;
use Directorist\DTO\Order\Read as OrderRead;

class Order_Controller extends Abstract_Controller {
    protected $rest_base = 'orders';

    public function register_routes() {
        // Admin Routes
        register_rest_route(
            $this->namespace,
            "admin/{$this->rest_base}",
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'admin_index' ],
                    'permission_callback' => [ $this, 'admin_permissions_check' ],
                    'args'                => $this->index_args(),
                ],
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'store' ],
                    'permission_callback' => [ $this, 'admin_permissions_check' ],
                    'args'                => $this->store_args(),
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            "admin/{$this->rest_base}/(?P<id>[\d]+)",
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'show' ],
                    'permission_callback' => [ $this, 'admin_permissions_check' ],
                    'args'                => [
                        'id'     => [
                            'description'       => __( 'The order ID.', 'directorist' ),
                            'type'              => 'integer',
                            'required'          => true,
                        ],
                    ],
                ],
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'update' ],
                    'permission_callback' => [ $this, 'admin_permissions_check' ],
                    'args'                => array_merge(
                        $this->store_args(), 
                        [
                            'id'     => [
                                'description'       => __( 'The order ID.', 'directorist' ),
                                'type'              => 'integer',
                                'required'          => true,
                            ],
                        ],
                    )
                ],
                [
                    'methods'             => WP_REST_Server::DELETABLE,
                    'callback'            => [ $this, 'delete' ],
                    'permission_callback' => [ $this, 'admin_permissions_check' ],
                    'args'                => [
                        'id'     => [
                            'description'       => __( 'The order ID.', 'directorist' ),
                            'type'              => 'integer',
                            'required'          => true,
                        ],
                    ],
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            "admin/{$this->rest_base}/(?P<id>[\d]+)/status",
            [
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'update_status' ],
                    'permission_callback' => [ $this, 'admin_permissions_check' ],
                    'args'                => [
                        'id'     => [
                            'description'       => __( 'The order ID.', 'directorist' ),
                            'type'              => 'integer',
                            'required'          => true,
                        ],
                        'status' => [
                            'description'       => __( 'The status of the order.', 'directorist' ),
                            'type'              => 'string',
                            'enum'              => OrderStatus::all(),
                            'required'          => true,
                        ],
                    ],
                ],
            ]
        );

        // User Routes
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'index' ],
                    'permission_callback' => [ $this, 'auth_permissions_check' ],
                    'args'                => $this->index_args(),
                ],
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'user_store' ],
                    'permission_callback' => [ $this, 'auth_permissions_check' ],
                    'args'                => $this->user_store_args(),
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/(?P<id>[\d]+)',
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'user_show' ],
                    'permission_callback' => [ $this, 'auth_permissions_check' ],
                    'args'                => [
                        'id' => [
                            'description'       => __( 'The order ID.', 'directorist' ),
                            'type'              => 'integer',
                            'sanitize_callback' => 'absint',
                            'required'          => true,
                        ],
                    ],
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/(?P<id>[\d]+)/cancel',
            [
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'cancel' ],
                    'permission_callback' => [ $this, 'auth_permissions_check' ],
                    'args'                => [
                        'id' => [
                            'description'       => __( 'The order ID.', 'directorist' ),
                            'type'              => 'integer',
                            'sanitize_callback' => 'absint',
                            'required'          => true,
                        ],
                    ],
                ],
            ]
        );
    }

    public function admin_index( WP_REST_Request $request ) {
        $page       = (int) $request->get_param( "page" );
        $per_page   = (int) $request->get_param( "perPage" );
        $search     = (string) $request->get_param( "search" );
        $dto        = ( new OrderRead )->set_page( $page )->set_per_page( $per_page )->set_search( $search );
        $repository = directorist_order_repository();

        return rest_ensure_response( $repository->get( $dto ) );
    }

    public function index( WP_REST_Request $request ) {
        $pre_response = apply_filters( 'directorist_rest_pre_get_user_orders', null, $request );

        if ( null !== $pre_response ) {
            return is_wp_error( $pre_response ) ? $pre_response : rest_ensure_response( $pre_response );
        }

        $page       = max( 1, (int) $request->get_param( 'page' ) );
        $per_page   = $request->get_param( 'per_page' );
        $per_page   = max( 1, min( 100, null !== $per_page ? (int) $per_page : (int) $request->get_param( 'perPage' ) ) );
        $search     = (string) $request->get_param( 'search' );
        $dto        = ( new OrderRead )->set_page( $page )->set_per_page( $per_page )->set_search( $search );
        $repository = directorist_order_repository();
        $orders     = $repository->get_by_user_id( get_current_user_id(), $dto );
        $response   = rest_ensure_response( $this->prepare_user_order_data( $orders['items'], $request ) );

        $response->header( 'X-WP-Total', (int) $orders['total'] );
        $response->header( 'X-WP-TotalPages', (int) ceil( $orders['total'] / $per_page ) );

        return $response;
    }

    /**
     * Create an order for the current user.
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_Error|\WP_REST_Response
     */
    public function user_store( WP_REST_Request $request ) {
        $pre_response = apply_filters( 'directorist_rest_pre_create_user_order', null, $request );

        if ( null !== $pre_response ) {
            return is_wp_error( $pre_response ) ? $pre_response : rest_ensure_response( $pre_response );
        }

        $listing_id = (int) $request->get_param( 'listing_id' );

        if ( $listing_id && ( ! directorist_is_listing_post_type( $listing_id ) || (int) get_post_field( 'post_author', $listing_id ) !== get_current_user_id() ) ) {
            return new WP_Error( 'rest_invalid_listing', __( 'The listing was not found.', 'directorist' ), [ 'status' => 404 ] );
        }

        $amount   = max( 0, round( (float) $request->get_param( 'amount' ), 2 ) );
        $currency = sanitize_text_field( (string) $request->get_param( 'currency' ) );
        $dto      = ( new OrderDTO )
            ->set_user_id( get_current_user_id() )
            ->set_amount( $amount )
            ->set_sub_total( $amount )
            ->set_currency( $currency ? $currency : atbdp_get_payment_currency() )
            ->set_status( OrderStatus::PENDING );

        if ( $listing_id ) {
            $dto->set_listing_id( $listing_id );
        }

        $plan_id = (int) $request->get_param( 'plan_id' );

        if ( $plan_id ) {
            $dto->set_ref( (string) $plan_id )->set_ref_type( 'pricing_plan' );
        }

        if ( $request->get_param( 'featured' ) ) {
            $dto->set_is_featured_listing( 1 );
        }

        $dto        = apply_filters( 'directorist_rest_user_order_dto', $dto, $request );
        $repository = directorist_order_repository();
        $order_id   = $repository->create( $dto );

        if ( ! $order_id ) {
            return new WP_Error( 'rest_order_create_failed', __( 'Unable to create the order.', 'directorist' ), [ 'status' => 500 ] );
        }

        $order    = $repository->single( $order_id );
        $response = rest_ensure_response( $this->prepare_user_order_data( $order, $request ) );

        $response->set_status( 201 );
        $response->header( 'Location', rest_url( '/' . $this->namespace . '/' . $this->rest_base . '/' . $order_id ) );

        do_action( 'directorist_rest_user_order_created', $order_id, $request, $dto );

        return $response;
    }

    /**
     * Get an order belonging to the current user.
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_Error|\WP_REST_Response
     */
    public function user_show( WP_REST_Request $request ) {
        $pre_response = apply_filters( 'directorist_rest_pre_get_user_order', null, $request );

        if ( null !== $pre_response ) {
            return is_wp_error( $pre_response ) ? $pre_response : rest_ensure_response( $pre_response );
        }

        $order = directorist_order_repository()->single( $request->get_param( 'id' ) );

        if ( ! $order || (int) $order->user_id !== get_current_user_id() ) {
            return new WP_Error( 'rest_not_found', __( 'The order was not found.', 'directorist' ), [ 'status' => 404 ] );
        }

        return rest_ensure_response( $this->prepare_user_order_data( $order, $request ) );
    }

    public function store( WP_REST_Request $request ) {
        $dto = ( new OrderDTO )
            ->set_user_id( $request->get_param( "user_id" ) )
            ->set_listing_id( $request->get_param( "listing_id" ) )
            ->set_amount( $request->get_param( "amount" ) )
            ->set_currency( $request->get_param( "currency" ) )
            ->set_status( $request->get_param( "status" ) );

        $id = directorist_order_repository()->create( $dto );

        status_header( 201 );

        return rest_ensure_response(
            [
                "message" => esc_html__( "Order was created successfully", 'directorist' ),
                "data"    => [
                    "id" => $id
                ]
            ]
        );
    }

    public function show( WP_REST_Request $request ) {
        $repository = directorist_order_repository();
        $order      = $repository->single( $request->get_param( "id" ) );

        if ( ! $order ) {
            return new WP_Error( 'rest_not_found', __( 'The order was not found', 'directorist' ) );
        }

        return rest_ensure_response(
            [
                "order" => $order
            ]
        );
    }

    public function update( WP_REST_Request $request ) {
        $dto = ( new OrderDTO )->set_id( $request->get_param( 'id' ) )
            ->set_user_id( $request->get_param( 'user_id' ) )
            ->set_listing_id( $request->get_param( 'listing_id' ) )
            ->set_status( $request->get_param( 'status' ) );

        directorist_order_repository()->update( $dto );

        return rest_ensure_response(
            [
                "message" => esc_html__( "Order was updated successfully", 'directorist' )
            ]
        );
    }

    public function delete( WP_REST_Request $request ) {
        directorist_order_repository()->delete_by_id( $request->get_param( "id" ) );

        return rest_ensure_response(
            [
                "message" => esc_html__( "Order was deleted successfully", 'directorist' )
            ]
        );
    }

    public function update_status( WP_REST_Request $request ) {
        $repository = directorist_order_repository();
        $old_item   = $repository->get_by_id( $request->get_param( "id" ) );

        if ( ! $old_item ) {
            return new WP_Error( 'rest_not_found', __( 'The order was not found', 'directorist' ) );
        }

        $dto = $repository->to_dto( $old_item );

        $dto->set_status( $request->get_param( "status" ) );

        $repository->update( $dto );

        return rest_ensure_response(
            [
                'message' => esc_html__( "Status updated successfully", 'directorist' )
            ]
        );
    }

    public function cancel( WP_REST_Request $request ) {
        $repository = directorist_order_repository();
        $order      = $repository->get_by_id( $request->get_param( 'id' ) );

        if ( ! $order ) {
            return new WP_Error( 'rest_not_found', __( 'The order was not found', 'directorist' ), [ 'status' => 404 ] );
        }

        if ( (int) $order->user_id !== get_current_user_id() ) {
            return new WP_Error( 'rest_forbidden', __( 'You are not authorized to cancel this order.', 'directorist' ), [ 'status' => 403 ] );
        }

        if ( OrderStatus::PENDING !== $order->status ) {
            return new WP_Error( 'rest_invalid_status', __( 'Only pending orders can be cancelled.', 'directorist' ), [ 'status' => 400 ] );
        }

        $dto = $repository->to_dto( $order );
        $dto->set_status( OrderStatus::CANCELLED );

        $repository->update( $dto );

        return rest_ensure_response(
            [
                'message' => esc_html__( 'Order was cancelled successfully', 'directorist' ),
            ]
        );
    }

    protected function store_args(): array {
        return [
            'user_id' => [
                'description'       => __( 'The user ID.', 'directorist' ),
                'type'              => 'integer',
                'required'          => true,
            ],
            'listing_id' => [
                'description'       => __( 'The listing ID.', 'directorist' ),
                'type'              => 'integer',
                'required'          => false,
            ],
            'status' => [
                'description'       => __( 'The status of the order.', 'directorist' ),
                'type'              => 'string',
                'required'          => true,
                'enum'              => OrderStatus::all(),
            ]
        ];
    }

    protected function index_args(): array {
        return [
            'page'     => [
                'description'       => __( 'The page number.', 'directorist' ),
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
                'default'           => 1,
                'required'          => false,
            ],
            'perPage'  => [
                'description'       => __( 'The number of items per page.', 'directorist' ),
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
                'default'           => 10,
                'required'          => false,
            ],
            'per_page' => [
                'description'       => __( 'The number of items per page.', 'directorist' ),
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
                'minimum'           => 1,
                'maximum'           => 100,
                'required'          => false,
            ],
            'search'   => [
                'description'       => __( 'The search query.', 'directorist' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'required'          => false,
            ],
        ];
    }

    /**
     * Get arguments for user order creation.
     *
     * @return array
     */
    protected function user_store_args(): array {
        return [
            'listing_id' => [
                'description'       => __( 'The listing ID.', 'directorist' ),
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
                'required'          => false,
            ],
            'plan_id'    => [
                'description'       => __( 'The pricing plan ID.', 'directorist' ),
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
                'required'          => false,
            ],
            'amount'     => [
                'description' => __( 'The order amount.', 'directorist' ),
                'type'        => 'number',
                'minimum'     => 0,
                'default'     => 0,
                'required'    => false,
            ],
            'currency'   => [
                'description'       => __( 'The order currency.', 'directorist' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'required'          => false,
            ],
            'featured'   => [
                'description' => __( 'Whether the order is for a featured listing.', 'directorist' ),
                'type'        => 'boolean',
                'default'     => false,
                'required'    => false,
            ],
        ];
    }

    /**
     * Wrap user order data with its provider type.
     *
     * @param mixed           $data    Order data.
     * @param WP_REST_Request $request Request object.
     * @return array
     */
    protected function prepare_user_order_data( $data, WP_REST_Request $request ): array {
        $response_data = [
            'order_type' => 'directorist',
            'data'       => $data,
        ];

        return apply_filters( 'directorist_rest_user_order_response_data', $response_data, $request );
    }
}
