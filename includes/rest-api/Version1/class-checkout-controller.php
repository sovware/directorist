<?php

namespace Directorist\Rest_Api\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use WP_Error;
use WP_REST_Request;
use WP_REST_Server;

use Exception;
use Directorist\Contracts\PaymentInterface;
use Directorist\DTO\Order\DTO as OrderDTO;
use Directorist\Enums\Order\Status as OrderStatus;

class Checkout_Controller extends Abstract_Controller {
    protected $rest_base = 'checkout';

    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'checkout' ],
                    'permission_callback' => [ $this, 'auth_permissions_check' ],
                    'args'                => [
                        'checkout_type'     => [
                            'description'       => __( 'The type of checkout to be performed.' ),
                            'type'              => 'string',
                            'sanitize_callback' => 'sanitize_text_field',
                            'enum'              => directorist_get_checkout_types(),
                            'required'          => true,
                        ],
                        'payment_gateway'     => [
                            'description'       => __( 'The payment gateway to be used for the checkout.' ),
                            'type'              => 'string',
                            'sanitize_callback' => 'sanitize_text_field',
                            'required'          => false,
                        ],
                    ],
                ],
            ]
        );
        
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/retry-payment',
            [
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'retry_payment' ],
                    'permission_callback' => [ $this, 'auth_permissions_check' ],
                    'args'                => [
                        'order_id'     => [
                            'description'       => __( 'The order id to be retried.' ),
                            'type'              => 'integer',
                            'sanitize_callback' => 'absint',
                            'required'          => true,
                        ],
                    ],
                ],
            ]
        );
    }

    public function checkout( WP_REST_Request $request ) {
        try {
            $checkout_type = $request->get_param( 'checkout_type' );

            do_action( 'directorist_checkout_validation', $checkout_type, $request );

            $dto = ( new OrderDTO )
                ->set_user_id( get_current_user_id() )
                ->set_currency( atbdp_get_payment_currency() )
                ->set_status( OrderStatus::PENDING );

            do_action( 'directorist_checkout_create_order', $dto, $checkout_type, $request );

            $repository = directorist_order_repository();

            if ( $dto->is_initialized( 'id' ) ) {
                $order = $repository->get_by_id( $dto->get_id() );

                if ( ! $order ) {
                    return new WP_Error( 'rest_not_found', __( 'Order not found.' ) );
                }

                $dto = $repository->to_dto( $order );
            }

            $processor_instance = null;
            $order_payable      = directorist_order_payable( $dto );
            $process_payment    = apply_filters( 'directorist_checkout_process_payment', $order_payable > 0, $dto, $request );
            
            if ( $process_payment ) {
                $payment_gateway = $request->get_param( 'payment_gateway' );

                if ( ! $payment_gateway ) {
                    return new WP_Error( 'rest_empty_value', __( 'Payment gateway is required.' ) );
                }

                $payment_processors = directorist_get_payment_processors();

                if ( ! isset( $payment_processors[ $payment_gateway ] ) ) {
                    return new WP_Error( 'rest_invalid_value', __( 'Invalid payment gateway.' ) );
                }

                /**
                 * @var PaymentInterface
                 */
                $processor_instance = directorist_make( $payment_processors[ $payment_gateway ], __( 'Invalid payment gateway.', 'directorist' ) );

                if ( ! $processor_instance instanceof PaymentInterface ) {
                    return new WP_Error( 'rest_invalid_value', __( 'Invalid payment gateway.' ) );
                }

                do_action( 'directorist_checkout_validate_payment_processor', $processor_instance, $dto, $checkout_type, $request );
            }

            $repository = directorist_order_repository();

            if ( ! $dto->is_initialized( 'id' ) ) {
                $repository->create( $dto );
            } else {
                $repository->silent_update( $dto );
            }

            if ( $process_payment ) {
                do_action( 'directorist_before_redirect_checkout', $dto, $checkout_type, $request );

                return rest_ensure_response(
                    [
                        "redirect_url" => $processor_instance->pay( $dto, $request->get_params() ) ?? $this->get_redirect_url( $dto )
                    ]
                );
            }

            do_action( 'directorist_before_redirect_checkout', $dto, $checkout_type, $request );

            // Update the order status to paid if the payable amount is zero or less
            if ( $order_payable < 1 ) {
                $dto->set_id( $dto->get_id() )->set_status( OrderStatus::PAID );
                $repository->update( $dto );
            }

            return rest_ensure_response(
                [
                    "redirect_url" => $this->get_redirect_url( $dto )
                ]
            );
        } catch ( Exception $e ) {
            return new WP_Error( 'rest_error', $e->getMessage() );
        }
    }

    protected function get_redirect_url( OrderDTO $dto ): string {
        return apply_filters( 'atbdp_payment_receipt_page_link', \ATBDP_Permalink::get_payment_receipt_page_link( $dto->get_id() ), $dto->get_id() );
    }

    public function retry_payment( WP_REST_Request $request ) {
        $order_id         = $request->get_param( 'order_id' );
        $order_repository = directorist_order_repository();
        $order            = $order_repository->get_by_id( $order_id );

        if ( ! $order ) {
            return new WP_Error( 'rest_not_found', __( 'Order not found.' ) );
        }

        $order = $order_repository->to_dto( $order );

        if ( ! in_array( $order->get_status(), [ OrderStatus::PENDING, OrderStatus::FAILED ], true ) ) {
            return new WP_Error( 'rest_invalid_value', __( 'Order is not pending or failed.' ) );
        }

        $payment_repository = directorist_payment_repository();
        $payment            = $payment_repository->get_last_payment( $order_id );

        if ( ! $payment ) {
            return new WP_Error( 'rest_not_found', __( 'Payment not found.' ) );
        }

        $payment            = $payment_repository->to_dto( $payment );
        $payment_processors = directorist_get_payment_processors();

        if ( ! isset( $payment_processors[$payment->get_method()] ) ) {
            return new WP_Error( 'rest_invalid_value', __( 'Invalid payment gateway.' ) );
        }

        /**
         * @var PaymentInterface $processor_instance
         */
        $processor_instance = directorist_make( $payment_processors[$payment->get_method()], __( 'Invalid payment gateway.', 'directorist' ) );

        if ( $processor_instance instanceof PaymentInterface ) {
            $redirect_url = $processor_instance->pay( $order );
        }

        return rest_ensure_response(
            [
                "redirect_url" => $redirect_url ?? $this->get_redirect_url( $order )
            ]
        );
    }
}