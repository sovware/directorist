<?php

namespace Directorist\Rest_Api\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use WP_REST_Request;
use WP_REST_Server;
use WP_Error;

use Directorist\DTO\Payment\DTO as PaymentDTO;
use Directorist\Enums\Payment\Status as PaymentStatus;
use Directorist\Repositories\PaymentRepository;

class Payments_Controller extends Abstract_Controller {
    protected $rest_base = 'admin/payments';

    public function register_routes() {
        register_rest_route(
            $this->namespace,
            "{$this->rest_base}",
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'index' ],
                    'permission_callback' => [ $this, 'admin_permissions_check' ],
                    'args'                => [
                        'order_id'     => [
                            'description'       => __( 'The order ID.' ),
                            'type'              => 'integer',
                            'required'          => true,
                        ],
                    ],
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
    }

    public function index( WP_REST_Request $request ) {
        $repository = new PaymentRepository();

        return rest_ensure_response( 
            [
                "payments" => $repository->get( (int) $request->get_param( "order_id" ) )
            ]
         );
    }

    public function store( WP_REST_Request $request ) {
        $dto = ( new PaymentDTO )
            ->set_order_id( $request->get_param( "order_id" ) )
            ->set_status( $request->get_param( "status" ) )
            ->set_transaction_id( $request->get_param( "transaction_id" ) )
            ->set_method( $request->get_param( "method" ) );
        
        $repository = new PaymentRepository();
        $id         = $repository->create( $dto );

        status_header( 201 );

        return rest_ensure_response(
            [
                "message" => esc_html__( "Payment was created successfully" ),
                "data"    => [
                    "id" => $id
                ]
            ]
        );
    }

    public function show( WP_REST_Request $request ) {
        $repository = new PaymentRepository();
        $payment    = $repository->get_by_id( $request->get_param( "id" ) );

        if ( ! $payment ) {
            return new WP_Error( 'rest_not_found', __( 'The payment was not found' ) );
        }

        return rest_ensure_response(
            [
                "data" => $payment
            ]
        );
    }

    public function update( WP_REST_Request $request ) {
        $repository = new PaymentRepository();
        
        $dto = new PaymentDTO;
        $dto->set_id( $request->get_param( "id" ) );

        $repository->update( $dto );

        return rest_ensure_response(
            [
                "message" => esc_html__( "Payment was updated successfully" )
            ]
        );
    }

    public function delete( WP_REST_Request $request ) {
        $repository = new PaymentRepository();
        $repository->delete_by_id( $request->get_param( "id" ) );

        return rest_ensure_response(
            [
                "message" => esc_html__( "Payment was deleted successfully" )
            ]
        );
    }

    protected function store_args(): array {
        return [
            'order_id' => [
                'description'       => __( 'The order ID.' ),
                'type'              => 'integer',
                'required'          => true,
            ],
            'status' => [
                'description'       => __( 'The status of the payment.' ),
                'type'              => 'string',
                'required'          => true,
                'enum'              => PaymentStatus::all(),
            ],
            'transaction_id' => [
                'description'       => __( 'The transaction ID.' ),
                'type'              => 'string',
                'required'          => true,
                'maxlength'         => 100,
            ],
            'method' => [
                'description'       => __( 'The method of the payment.' ),
                'type'              => 'string',
                'required'          => true,
                'maxlength'         => 30,
            ],
        ];
    }
}