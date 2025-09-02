<?php

namespace Directorist\App\Http\Controllers\Admin;

defined( "ABSPATH" ) || exit;

use Directorist\App\DTO\Payment\DTO;
use Directorist\App\Repositories\PaymentRepository;
use Directorist\WpMVC\Exceptions\Exception;
use Directorist\WpMVC\Routing\Response;
use Directorist\WpMVC\RequestValidator\Validator;
use WP_REST_Request;

class PaymentController {

    public PaymentRepository $repository;

    public function __construct( PaymentRepository $repository ) {
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
                "payments" => $this->repository->get()
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

        $dto = (new DTO)
            ->set_order_id( $request->get_param( "order_id" ) )
            // ->set_amount( $request->get_param( "amount" ) )
            // ->set_currency( $request->get_param( "currency" ) )
            ->set_status( $request->get_param( "status" ) )
            ->set_transaction_id( $request->get_param( "transaction_id" ) )
            ->set_method( $request->get_param( "method" ) );
        $id  = $this->repository->create( $dto );

        return Response::send(
            [
                "message" => esc_html__( "Payment was created successfully" ),
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

        $payment = $this->repository->get_by_id( $request->get_param( "id" ) );

        if ( ! $payment ) {
            throw new Exception( esc_html__( "Payment not found" ) );
        }

        return Response::send(
            [
                "data" => $payment
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
            array_merge([
                $this->get_validation_rules(),
                [
                    "id" => "required|numeric"
                ]
            ])
        );

        $dto = new DTO;
        $dto->set_id( $request->get_param( "id" ) );

        $this->repository->update( $dto );

        return Response::send(
            [
                "message" => esc_html__( "Payment was updated successfully" )
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
                "message" => esc_html__( "Payment was deleted successfully" )
            ]
        );
    }

    protected function get_validation_rules() {
        return [
                "order_id"         => "required|numeric",
                // "amount"           => "required|numeric",
                // "currency"         => "required|string|max:10",
                "status"   => "required|string|accepted:pending,paid,failed",
                "transaction_id"   => "required|string|max:100",
                "method"   => "required|string|max:30"
        ];
    }
}

// $args = [
//     'post_type'      => 'post',
//     'posts_per_page' => 10,
//     'fields'         => 'all', // Get full post objects
// ];

// add_filter('posts_where', function ($where) {
//     global $wpdb;
//     $where .= " AND {$wpdb->posts}.post_content LIKE '%centuries%' AND {$wpdb->posts}.post_content LIKE '%point%'";
//     return $where;
// });

// $query = new \WP_Query($args);

// remove_all_filters('posts_where'); // Clean up after query

        // return Response::send(
        //     [
        //         // "posts" => $query->posts
        //         'posts' => Post::query()->where('post_type', 'post')->where('post_content', 'LIKE', '%centuries%')
        //             ->where('post_content', 'LIKE', '%point%')
        //             ->limit(10)->get()
        //     ]
        // );