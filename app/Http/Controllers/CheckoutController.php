<?php

namespace Directorist\App\Http\Controllers;

defined( "ABSPATH" ) || exit;

use Directorist\App\Http\Controllers\Controller;
use Directorist\WpMVC\Exceptions\Exception;
use Directorist\WpMVC\Routing\Response;
use Directorist\WpMVC\RequestValidator\Validator;
use Directorist\App\DTO\Order\DTO;
use Directorist\App\Enums\Order\Type;
use Directorist\App\Enums\Order\Status;
use WP_REST_Request;

class CheckoutController {
    public function checkout( Validator $validator, WP_REST_Request $request ): array {
        $enable_monetization = apply_filters( 'atbdp_enable_monetization_checkout', directorist_is_monetization_enabled() );

        if ( ! $enable_monetization ) {
            throw new \Exception( __( 'Monetization is not active on this site. if you are an admin, you can enable it from the settings panel.', 'directorist' ) );
        }

        $validator->validate(
            [
                'checkout_type' => 'required|accepted:' . implode( ',', directorist_get_checkout_types() )
            ] 
        );

        $checkout_type = $request->get_param( 'checkout_type' );

        do_action( 'directorist_checkout_validation', $checkout_type, $request, $validator );

        $dto = ( new DTO )
        ->set_user_id( get_current_user_id() )
        ->set_currency( atbdp_get_payment_currency() )
        ->set_status( Status::PENDING );

        do_action( 'directorist_checkout_create_order', $dto, $checkout_type, $request );
        
        $repository = directorist_order_repository();
        $repository->create( $dto );


        return Response::send(
            [
                "message" => esc_html__( "Checkout" )
            ]
        );
    }
}