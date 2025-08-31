<?php

namespace Directorist\App\Http\Controllers;

defined( "ABSPATH" ) || exit;

use Directorist\App\Contracts\PaymentInterface;
use Directorist\WpMVC\Exceptions\Exception;
use Directorist\WpMVC\Routing\Response;
use Directorist\WpMVC\RequestValidator\Validator;
use Directorist\App\DTO\Order\DTO;
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

        $payment_gateway    = $request->get_param( 'payment_gateway' );
        $payment_processors = directorist_get_payment_processors();

        if ( ! isset( $payment_processors[$payment_gateway] ) || ! class_exists( $payment_processors[$payment_gateway] ) ) {
            throw new \Exception( __( 'Invalid payment gateway.', 'directorist' ) );
        }

        $checkout_type = $request->get_param( 'checkout_type' );

        do_action( 'directorist_checkout_validation', $checkout_type, $request, $validator );

        $dto = ( new DTO )
        ->set_user_id( get_current_user_id() )
        ->set_currency( atbdp_get_payment_currency() )
        ->set_status( Status::PENDING );

        do_action( 'directorist_checkout_create_order', $dto, $checkout_type, $request );

        $repository = directorist_order_repository();
        $repository->create( $dto );

        $redirect_url = null;

        if ( $dto->get_amount() == 0 ) {
            directorist_order_repository()->update( ( new DTO )->set_id( $dto->get_id() )->set_status( Status::PAID ) );
        } else {
            $processor_instance = directorist_make( $payment_processors[$payment_gateway] );

            if ( $processor_instance instanceof PaymentInterface ) {
                $redirect_url = $processor_instance->pay( $dto );
            }
        }

        return Response::send(
            [
                "redirect_url" => $redirect_url ?? apply_filters( 'atbdp_payment_receipt_page_link', \ATBDP_Permalink::get_payment_receipt_page_link( $dto->get_id() ), $dto->get_id() )
            ]
        );
    }

    public function retry_payment( Validator $validator, WP_REST_Request $request ): array {
        $validator->validate(
            [
                'order_id' => 'required|integer'
            ]
        );

        $order_id         = $request->get_param( 'order_id' );
        $order_repository = directorist_order_repository();
        $order            = $order_repository->get_by_id( $order_id );

        if ( ! $order ) {
            throw new \Exception( __( 'Order not found.', 'directorist' ) );
        }

        $order = $order_repository->to_dto( $order );

        if ( ! in_array( $order->get_status(), [Status::PENDING, Status::FAILED], true ) ) {
            throw new \Exception( __( 'Order is not pending or failed.', 'directorist' ) );
        }

        $payment_repository = directorist_payment_repository();
        $payment            = $payment_repository->get_last_payment( $order_id );

        if ( ! $payment ) {
            throw new \Exception( __( 'Payment not found.', 'directorist' ) );
        }

        $payment            = $payment_repository->to_dto( $payment );
        $payment_processors = directorist_get_payment_processors();

        if ( ! isset( $payment_processors[$payment->get_method()] ) || ! class_exists( $payment_processors[$payment->get_method()] ) ) {
            throw new \Exception( __( 'Invalid payment gateway.', 'directorist' ) );
        }

        $processor_instance = directorist_make( $payment_processors[$payment->get_method()] );

        if ( $processor_instance instanceof PaymentInterface ) {
            $redirect_url = $processor_instance->pay( $order );
        }

        return Response::send(
            [
                "redirect_url" => $redirect_url ?? apply_filters( 'atbdp_payment_receipt_page_link', \ATBDP_Permalink::get_payment_receipt_page_link( $order->get_id() ), $order->get_id() )
            ]
        );
    }
}