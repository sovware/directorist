<?php

namespace Directorist\App\Http\Controllers\Admin;

defined( "ABSPATH" ) || exit;

use Directorist\App\Enums\Order\Status as OrderStatus;
use Directorist\App\DTO\Order\Read;
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
        $validator->validate(
            [
                "page"     => "numeric",
                "per_page" => "numeric",
                "search"   => "string"
            ]
        );

        $page = (int) $request->get_param( "page" );
        $per_page = (int) $request->get_param( "per_page" );
        $search = (string) $request->get_param( "search" );

        $dto = (new Read)->set_page( $page )->set_per_page( $per_page )->set_search( $search );

        return Response::send( $this->repository->get( $dto ) );
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
            ->set_amount( $request->get_param( "amount" ) )
            ->set_currency( $request->get_param( "currency" ) )
            ->set_status( $request->get_param( "status" ) )            ;
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

        $order = $this->repository->single( $request->get_param( "id" ) );

        if ( ! $order ) {
            throw new Exception( esc_html__( "Order not found" ) );
        }

        return Response::send(
            [
                "order" => $order
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

        $dto = (new DTO)->set_id( $request->get_param( "id" ) )
        ->set_user_id( $request->get_param('user_id') )
        ->set_listing_id($request->get_param('listing_id') )
        ->set_status($request->get_param('status'));

        $this->repository->update( $dto );

        return Response::send(
            [
                "message" => esc_html__( "Order was updated successfully" )
            ]
        );
    }

    public function update_status(Validator $validator, WP_REST_Request $request) {
        $validator->validate([
            'id' => 'numeric',
            'status' => "required|accepted:". implode(',', OrderStatus::all()),
        ]);

        $dto = new DTO;
        $dto->set_id( $request->get_param( "id" ) )->set_status($request->get_param("status"));

        $this->repository->update( $dto );

        return Response::send([
            'message' => esc_html__("Status updated successfully")
        ]);
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
            "status" => "required|accepted:pending,paid,failed,cancelled,expired"
        ];
    }
}