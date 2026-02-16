<?php

namespace Directorist\App\Http\Controllers;

defined( "ABSPATH" ) || exit;

use Directorist\WpMVC\Exceptions\Exception;
use Directorist\WpMVC\Routing\Response;
use Directorist\WpMVC\RequestValidator\Validator;
use WP_REST_Request;

use Directorist\App\Repositories\ListingRepository;

class ListingController {
    public ListingRepository $repository;

    public function __construct( ListingRepository $repository ) {
        $this->repository = $repository;
    }

    public function update_status( Validator $validator, WP_REST_Request $request ): array {
        $validator->validate(
            [
                'id'     => 'required|integer',
                'status' => 'required|string|accepted:publish,private',
            ] 
        );

        $id     = (int) $request->get_param( 'id' );
        $status = $request->get_param( 'status' );

        $listing = get_post( $id );

        if ( ! $listing ) {
            throw new Exception( esc_html__( 'The listing was not found', 'directorist-pricing-plans' ), 404 );
        }

        do_action( 'directorist_before_update_listing_status', $id, $status );

        $this->repository->update_listing_status( $id, $status );

        return Response::send(
            [
                'message' => esc_html__( 'The listing status was updated successfully', 'directorist-pricing-plans' )
            ]
        );
    }
}