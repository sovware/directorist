<?php

namespace Directorist\Rest_Api\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Exception;
use WP_REST_Request;
use WP_REST_Server;
use WP_Error;

use Directorist\DTO\Refund\DTO as RefundDTO;
use Directorist\DTO\Refund\Read as RefundRead;
use Directorist\Enums\Refund\Status as RefundStatus;
use Directorist\Repositories\RefundRepository;

class Order_Refund_Controller extends Abstract_Controller {
    protected $rest_base = 'admin/orders/(?P<order_id>[\d]+)/refunds';

    public function register_routes() {
        register_rest_route(
            $this->namespace,
            "{$this->rest_base}",
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'index' ],
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
            "{$this->rest_base}/(?P<id>[\d]+)",
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
                    'methods'             => WP_REST_Server::EDITABLE,
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
    }

    public function index( WP_REST_Request $request ) {
        $dto = ( new RefundRead )->set_page( (int) $request->get_param( "page" ) )
            ->set_per_page( (int) $request->get_param( "per_page" ) )
            ->set_search( (string) $request->get_param( "search" ) )
            ->set_order_id( (int) $request->get_param( "order_id" ) );

        $repository = new RefundRepository();

        return rest_ensure_response( $repository->get( $dto ) );
    }

    public function store( WP_REST_Request $request ) {
        $dto = ( new RefundDTO )->set_order_id( (int) $request->get_param( "order_id" ) )
            ->set_amount( $request->get_param( "amount" ) )
            ->set_status( $request->get_param( "status" ) )
            ->set_reason( (string) $request->get_param( "reason" ) );

        $repository = new RefundRepository();

        try {
            $id  = $repository->create( $dto );
        } catch ( Exception $e ) {
            return new WP_Error( 'rest_exception', $e->getMessage() );
        }

        status_header( 201 );

        return rest_ensure_response(
            [
                "message" => esc_html__( "Refund was created successfully" ),
                "data"    => [
                    "id" => $id
                ]
            ]
        );
    }

    public function show( WP_REST_Request $request ) {
        $repository = new RefundRepository();
        $refund     = $repository->get_by_id( $request->get_param( "id" ) );

        if ( ! $refund ) {
            return new WP_Error( 'rest_not_found', __( 'The refund was not found' ) );
        }

        return rest_ensure_response(
            [
                "data" => $refund
            ]
        );
    }

    public function update( WP_REST_Request $request ) {
        $dto = ( new RefundDTO )->set_id( $request->get_param( 'id' ) )
            ->set_order_id( (int) $request->get_param( "order_id" ) )
            ->set_amount( $request->get_param( "amount" ) )
            ->set_status( $request->get_param( "status" ) )
            ->set_reason( (string) $request->get_param( "reason" ) );

        $repository = new RefundRepository();
        $repository->update( $dto );

        return rest_ensure_response(
            [
                "message" => esc_html__( "Refund was updated successfully" )
            ]
        );
    }

    public function delete( WP_REST_Request $request ) {
        $repository = new RefundRepository();
        $repository->delete_by_id( $request->get_param( "id" ) );

        return rest_ensure_response(
            [
                "message" => esc_html__( "Refund was deleted successfully" )
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
            'order_id' => [
                'description' => __( 'The order ID.' ),
                'type'        => 'integer',
                'required'    => true,
            ],
            'amount' => [
                'description' => __( 'The listing ID.' ),
                'type'        => 'integer',
                'required'    => true,
                'minimum'     => 1,
            ],
            'status' => [
                'description' => __( 'The status of the order.' ),
                'type'        => 'string',
                'required'    => true,
                'enum'        => RefundStatus::all(),
            ],
            'reason' => [
                'description' => __( 'The reason for the refund.' ),
                'type'        => 'string',
                'required'    => false,
            ],
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
            'per_page'     => [
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
            'order_id'     => [
                'description'       => __( 'The order ID.' ),
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
                'required'          => false,
            ],
        ];
    }
}