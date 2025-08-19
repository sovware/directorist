<?php

namespace Directorist\App\Http\Controllers\Admin;

defined( "ABSPATH" ) || exit;

use Directorist\App\DTO\Refund\DTO;
use Directorist\App\DTO\Refund\Read;
use Directorist\WpMVC\Exceptions\Exception;
use Directorist\WpMVC\Routing\Response;
use Directorist\WpMVC\RequestValidator\Validator;
use WP_REST_Request;
use Directorist\App\Repositories\RefundRepository;

class RefundController {

    public RefundRepository $repository;

    public function __construct( RefundRepository $repository ) {
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
        $validator->validate(
            [
                "page" => "numeric",
                "per_page" => "numeric",
                "search" => "string",
                "order_id" => "required|numeric",
            ]
        );

        $dto = (new Read)->set_page( (int) $request->get_param( "page" ) )
            ->set_per_page( (int) $request->get_param( "per_page" ) )
            ->set_search( (string) $request->get_param( "search" ) )
            ->set_order_id( (int) $request->get_param( "order_id" ) );

        return Response::send($this->repository->get( $dto ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Validator $validator Instance of the Validator.
     * @param WP_REST_Request $request The REST request instance.
     * @return array
     */
    public function store( Validator $validator, WP_REST_Request $request ): array {
        $validator->validate(
            [
                "order_id" => "required|integer",
                "amount" => "required|numeric|min:1",
                "status" => "required|string",
                "reason" => "string",
            ]
        );

        $dto = (new DTO)->set_order_id( (int) $request->get_param( "order_id" ) )
            ->set_amount( (float) $request->get_param( "amount" ) )
            ->set_status( $request->get_param( "status" ) )
            ->set_reason( (string) $request->get_param( "reason" ) );

        $id  = $this->repository->create( $dto );

        return Response::send(
            [
                "message" => esc_html__( "Refund was created successfully" ),
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
            throw new Exception( esc_html__( "Refund not found" ) );
        }

        return Response::send(
            [
                "data" => $item
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
                "message" => esc_html__( "Refund was deleted successfully" )
            ]
        );
    }
}