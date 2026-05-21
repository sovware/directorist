<?php

namespace Directorist;

defined( "ABSPATH" ) || exit;

use Exception;
use WP_REST_Request;
use Directorist\Utils\Template;
use Directorist\DTO\Order\DTO;
use Directorist\Enums\Order\Status;
use Directorist\Utils\RequestValidator as DirectoristValidator;
use Directorist\Utils\Mime;

class PaymentCheckoutService {

    const CHECKOUT_TYPE = 'payment';

    public function __construct() {
        add_filter( 'directorist_checkout_types', [ $this, 'add_checkout_type' ] );
        add_action( 'directorist_checkout_validation', [ $this, 'validate_checkout' ], 10, 2 );
        add_action( 'directorist_checkout_table', [ $this, 'handle_checkout_table' ], 10, 4 );
        add_filter( 'directorist_checkout_subtotal', [$this, 'handle_checkout_subtotal'], 10, 3 );
        add_filter( 'directorist_checkout_total', [ $this, 'handle_checkout_total' ], 10, 3 );
        add_action( 'directorist_checkout_create_order', [ $this, 'handle_checkout_create_order' ], 10, 3 );
        add_action( 'atbdp_before_checkout_form_end', [ $this, 'before_checkout_form_end' ], 10 );
    }

    public function add_checkout_type( array $checkout_types ) {
        $checkout_types[] = self::CHECKOUT_TYPE;
        return $checkout_types;
    }

    public function validate_checkout( string $checkout_type, WP_REST_Request $request ) {
        if ( $checkout_type !== self::CHECKOUT_TYPE ) {
            return;
        }

        $validator = new DirectoristValidator( $request, new Mime );

        $errors = $validator->validate(
            [
                'order_id' => 'required|numeric',
            ], false
        );

        if ( ! empty( $errors ) ) {
            throw new Exception( array_values( $errors )[0][0], 422 );
        }

        $order = directorist_get_order_by_id( $request->get_param( 'order_id' ) );

        if ( ! $order ) {
            throw new Exception( __( 'Order not found.', 'directorist-pricing-plans' ) );
        }

        if ( (int) $order->user_id !== get_current_user_id() ) {
            throw new Exception( __( 'You do not have permission to pay for this order.', 'directorist-pricing-plans' ) );
        }

        if ( Status::PENDING !== $order->status ) {
            throw new Exception( __( 'This order is not pending payment.', 'directorist-pricing-plans' ) );
        }
    }

    public function handle_checkout_table( string $checkout_type, float $total, float $subtotal, WP_REST_Request $request ) {
        if ( $checkout_type !== self::CHECKOUT_TYPE ) {
            return;
        }

        $order = directorist_get_order_by_id( $request->get_param( 'order_id' ) );
        
        if ( ! $order ) {
            return;
        }
        
        $order = apply_filters( 'directorist_checkout_payment_order', $order, $request );

        $discount_amount     = directorist_compute_fixed_or_percent_amount( $order->coupon_discount_type, $order->coupon_discount, $order->sub_total );
        $discounted_subtotal = max( 0, (float) $order->sub_total - $discount_amount );
        $tax_amount          = directorist_compute_fixed_or_percent_amount( $order->tax_type, $order->tax_rate, $discounted_subtotal );

        Template::render( 'checkout/order-payment-summary', [
            'order'               => $order,
            'discount_amount'     => $discount_amount,
            'discounted_subtotal' => $discounted_subtotal,
            'tax_amount'          => $tax_amount,
        ] );
    }

    public function handle_checkout_subtotal( float $subtotal, string $checkout_type, WP_REST_Request $request ) {
        if ( $checkout_type !== self::CHECKOUT_TYPE ) {
            return $subtotal;
        }

        $order = directorist_get_order_by_id( $request->get_param( 'order_id' ) );

        if ( ! $order ) {
            return $subtotal;
        }

        return $order->sub_total;
    }

    public function handle_checkout_total( float $total, string $checkout_type, WP_REST_Request $request ) {
        if ( $checkout_type !== self::CHECKOUT_TYPE ) {
            return $total;
        }
    
        $order = directorist_get_order_by_id( $request->get_param( 'order_id' ) );

        if ( ! $order ) {
            return $total;
        }

        return directorist_order_total_amount( $order );
    }

    public function handle_checkout_create_order( DTO $dto, string $checkout_type, WP_REST_Request $request ) {
        if ( $checkout_type !== self::CHECKOUT_TYPE ) {
            return;
        }

        $order = directorist_get_order_by_id( $request->get_param( 'order_id' ) );

        if ( ! $order ) {
            throw new Exception( __( 'Order not found.', 'directorist-pricing-plans' ) );
        }

        // Set the existing order ID on the DTO so the core skips order creation
        $dto->set_id( (int) $order->id );
    }

    public function before_checkout_form_end() {
        if ( isset( $_GET['order_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            echo '<input type="hidden" name="order_id" value="' . esc_attr( absint( $_GET['order_id'] ) ) . '">'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        }
    }
}