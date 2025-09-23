<?php

namespace Directorist\App\Http\Controllers;

defined( "ABSPATH" ) || exit;

use Directorist\WpMVC\Routing\Response;
use Directorist\WpMVC\RequestValidator\Validator;
use Directorist\App\Repositories\OrderRepository;
use Directorist\App\DTO\Order\Read;
use WP_REST_Request;

class OrderController {
    public OrderRepository $repository;

    public function __construct( OrderRepository $repository ) {
        $this->repository = $repository;
    }

    public function index( Validator $validator, WP_REST_Request $request ): array {
        $validator->validate(
            [
                "page"    => "numeric",
                "perPage" => "numeric",
                "search"  => "string"
            ]
        );

        $page     = (int) $request->get_param( "page" );
        $per_page = (int) $request->get_param( "perPage" );
        $search   = (string) $request->get_param( "search" );

        $dto = ( new Read )->set_page( $page )->set_per_page( $per_page )->set_search( $search );

        return Response::send( $this->repository->get_by_user_id( get_current_user_id(), $dto ) );
    }
}