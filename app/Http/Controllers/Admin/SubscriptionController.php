<?php

namespace Directorist\App\Http\Controllers\Admin;

use Directorist\App\DTO\Subscription\DTO;

defined( "ABSPATH" ) || exit;

use Directorist\App\Repositories\SubscriptionRepository;
use Directorist\App\Http\Controllers\Controller;
use Directorist\WpMVC\Exceptions\Exception;
use Directorist\WpMVC\Routing\Response;
use Directorist\WpMVC\RequestValidator\Validator;
use WP_REST_Request;

class SubscriptionController {

    public SubscriptionRepository $repository;

    public function __construct( SubscriptionRepository $repository ) {
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
                "subscriptions" => $this->repository->get()
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
        	// 		`status` ENUM('active','cancelled','past_due','expired'),
			// started_at TIMESTAMP NULL DEFAULT NULL,
			// current_period_end TIMESTAMP NULL DEFAULT NULL,
			// cancelled_at TIMESTAMP NULL DEFAULT NULL,
        $validator->validate(
            [
                "order_id" => "required|numeric",
                "status"   => "required|accepted:active,cancelled,past_due,expired",
                // "started_at" => "date_format:Y-m-d H:i:s",
                // "current_period_end" => "date_format:Y-m-d H:i:s",
                // "cancelled_at" => "date_format:Y-m-d H:i:s"
            ]
        );

        $dto = new DTO;
        $id  = $this->repository->create( $dto );

        return Response::send(
            [
                "message" => esc_html__( "Item was created successfully" ),
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

        $item = $this->repository->get_by_id( $request->get_param( "id" ) );

        if ( ! $item ) {
            throw new Exception( esc_html__( "Item not found" ) );
        }

        return Response::send(
            [
                "data" => $item
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
            [
                "id" => "required|numeric"
                // Add other validation rules as needed.
            ]
        );

        $dto = new YourDTO;
        $dto->set_id( $request->get_param( "id" ) );

        $this->repository->update( $dto );

        return Response::send(
            [
                "message" => esc_html__( "Item was updated successfully" )
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
                "message" => esc_html__( "Item was deleted successfully" )
            ]
        );
    }

     protected function get_validation_rules() {
        return [
                "order_id"         => "required|numeric",
                // "payment_date"     => "required|date",
                // "amount"           => "required|numeric",
                // "currency"         => "required|string|max:10",
                "payment_status"   => "required|string|accepted:pending,paid,failed",
                "transaction_id"   => "required|string|max:100",
                "payment_method"   => "required|string|max:30"
        ];
    }
}