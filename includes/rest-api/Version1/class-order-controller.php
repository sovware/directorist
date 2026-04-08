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
                            'description'       => __( 'The order ID.' ),
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
                                'description'       => __( 'The order ID.' ),
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
                            'description'       => __( 'The order ID.' ),
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
                            'description'       => __( 'The order ID.' ),
                            'type'              => 'integer',
                            'required'          => true,
                        ],
                        'status' => [
                            'description'       => __( 'The status of the order.' ),
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
        $page       = (int) $request->get_param( "page" );
        $per_page   = (int) $request->get_param( "perPage" );
        $search     = (string) $request->get_param( "search" );
        $dto        = ( new OrderRead )->set_page( $page )->set_per_page( $per_page )->set_search( $search );
        $repository = directorist_order_repository();

        return rest_ensure_response( $repository->get_by_user_id( get_current_user_id(), $dto ) );
    }

    public function store( WP_REST_Request $request ) {
        $dto = ( new OrderDTO )
            ->set_user_id( $request->get_param( "user_id" ) )
            ->set_listing_id( $request->get_param( "listing_id" ) )
            ->set_amount( $request->get_param( "amount" ) )
            ->set_currency( $request->get_param( "currency" ) )
            ->set_status( $request->get_param( "status" ) )            ;
        
        $id = directorist_order_repository()->create( $dto );

        status_header( 201 );

        return rest_ensure_response(
            [
                "message" => esc_html__( "Order was created successfully" ),
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
            return new WP_Error( 'rest_not_found', __( 'The order was not found' ) );
        }

        return rest_ensure_response(
            [
                "order" => $order
            ]
        );
    }

    public function update( WP_REST_Request $request ) {
        $dto = ( new OrderDTO )->set_id( $request->get_param( 'id' ) )
            ->set_user_id(  $request->get_param( 'user_id'  ) )
            ->set_listing_id( $request->get_param( 'listing_id' ) )
            ->set_status( $request->get_param( 'status' ) );

        directorist_order_repository()->update( $dto );

        return rest_ensure_response(
            [
                "message" => esc_html__( "Order was updated successfully" )
            ]
        );
    }

    public function delete( WP_REST_Request $request ) {
        directorist_order_repository()->delete_by_id( $request->get_param( "id" ) );

        return rest_ensure_response(
            [
                "message" => esc_html__( "Order was deleted successfully" )
            ]
        );
    }

    public function update_status( WP_REST_Request $request ) {
        $repository = directorist_order_repository();
        $old_item   = $repository->get_by_id( $request->get_param( "id" ) );

        if ( ! $old_item ) {
            return new WP_Error( 'rest_not_found', __( 'The order was not found' ) );
        }

        $dto = $repository->to_dto( $old_item );

        $dto->set_status($request->get_param("status"));

        $repository->update( $dto );

        return rest_ensure_response( [
            'message' => esc_html__("Status updated successfully")
        ] );
    }

    protected function store_args(): array {
        return [
            'user_id' => [
                'description'       => __( 'The user ID.' ),
                'type'              => 'integer',
                'required'          => true,
            ],
            'listing_id' => [
                'description'       => __( 'The listing ID.' ),
                'type'              => 'integer',
                'required'          => false,
            ],
            'status' => [
                'description'       => __( 'The status of the order.' ),
                'type'              => 'string',
                'required'          => true,
                'enum'              => OrderStatus::all(),
            ]
        ];
    }

    protected function index_args(): array {
        return [
            'page'     => [
                'description'       => __( 'The page number.' ),
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
                'default'           => 1,
                'required'          => false,
            ],
            'perPage'     => [
                'description'       => __( 'The number of items per page.' ),
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
                'default'           => 10,
                'required'          => false,
            ],
            'search'     => [
                'description'       => __( 'The search query.' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'required'          => false,
            ],
        ];
    }
}