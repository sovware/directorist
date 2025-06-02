<?php

namespace Directorist\App\Http\Controllers\Admin;

defined( "ABSPATH" ) || exit;

use Directorist\App\DTO\Order\DTO;
use Directorist\App\Repositories\OrderRepository;
use Directorist\WpMVC\Exceptions\Exception;
use Directorist\WpMVC\Routing\Response;
use Directorist\WpMVC\RequestValidator\Validator;
use WP_REST_Request;

class OrderController {
    public OrderRepository $repository;

    public function __construct( OrderRepository $repository ) {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Validator $validator Instance of the Validator.
     * @param WP_REST_Request $request The REST request instance.
     * @return array
     */
    public function index( Validator $validator, WP_REST_Request $request ): array {
        return Response::send(
            [
                "orders" => $this->repository->get()
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Validator $validator Instance of the Validator.
     * @param WP_REST_Request $request The REST request instance.
     * @return array
     */
    public function store( Validator $validator, WP_REST_Request $request ): array {
        $validator->validate( $this->get_validation_rules() );

        $dto = ( new DTO )
            ->set_user_id( $request->get_param( "user_id" ) )
            ->set_listing_id( $request->get_param( "listing_id" ) )
            // ->set_plan_id( $request->get_param( "plan_id" ) ) // Uncomment if plan_id is needed.
            ->set_order_type( $request->get_param( "order_type" ) )
            ->set_amount( $request->get_param( "amount" ) )
            ->set_currency( $request->get_param( "currency" ) )
            // ->set_coupon_discount( $request->get_param( "coupon_discount" ) ) // Uncomment if coupon discount is needed.
            ->set_order_status( $request->get_param( "order_status" ) )
            // ->set_expires_at( $request->get_param( "expires_at" ) ) // Uncomment if expires_at is needed.
            ;
        $id = $this->repository->create( $dto );

        return Response::send(
            [
                "message" => esc_html__( "Order was created successfully" ),
                "data"    => [
                    "id" => $id
                ]
            ], 201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Validator $validator Instance of the Validator.
     * @param WP_REST_Request $request The REST request instance.
     * @return array
     * @throws Exception
     */
    public function show( Validator $validator, WP_REST_Request $request ): array {
        $validator->validate(
            [
                "id" => "required|numeric"
            ]
        );

        $order = $this->repository->get_by_id( $request->get_param( "id" ) );

        if ( ! $order ) {
            throw new Exception( esc_html__( "Order not found" ) );
        }

        return Response::send(
            [
                "data" => $order
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Validator $validator Instance of the Validator.
     * @param WP_REST_Request $request The REST request instance.
     * @return array
     */
    public function update( Validator $validator, WP_REST_Request $request ): array {
        $validator->validate(
            array_merge( 
                $this->get_validation_rules(),
                [ "id" => "required|numeric"]
            )
        );

        $dto = new DTO;
        $dto->set_id( $request->get_param( "id" ) );

        $this->repository->update( $dto );

        return Response::send(
            [
                "message" => esc_html__( "Order was updated successfully" )
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Validator $validator Instance of the Validator.
     * @param WP_REST_Request $request The REST request instance.
     * @return array
     */
    public function delete( Validator $validator, WP_REST_Request $request ): array {
        $validator->validate(
            [
                "id" => "required|numeric"
            ]
        );

        $this->repository->delete_by_id( $request->get_param( "id" ) );

        return Response::send(
            [
                "message" => esc_html__( "Order was deleted successfully" )
            ]
        );
    }

    protected function get_validation_rules() {
        return [
            "user_id"      => "required|numeric",
            "listing_id"   => "numeric",
                // "plan_id"      => "nullable|numeric", // Uncomment if plan_id is needed.
            "order_type"   => "required|accepted:one_time,recurring",
            "amount"       => "required|numeric|min:0",
            "currency"     => "required|string|max:10",
                // "coupon_discount" => "nullable|numeric|min:0", // Uncomment if coupon discount is needed.
            "order_status" => "required|accepted:pending,paid,failed,cancelled,expired",
                // "expires_at"   => "nullable|date", // Uncomment if expires_at is needed.
        ];
    }
}