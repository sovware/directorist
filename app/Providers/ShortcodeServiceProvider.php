<?php

namespace Directorist\App\Providers;

defined( "ABSPATH" ) || exit;

use Directorist\WpMVC\View\View;
use Directorist\WpMVC\Contracts\Provider;
use Directorist\WpMVC\Exceptions\Exception;
use Directorist\WpMVC\Helpers\Helpers;
use Directorist\WpMVC\RequestValidator\Mime;
use Directorist\WpMVC\RequestValidator\Validator;

class ShortcodeServiceProvider implements Provider {
    public function boot() {
        add_shortcode( 'directorist_payment_receipt', [$this, 'payment_receipt'] );
        add_shortcode( 'directorist_checkout', [$this, 'checkout'] );
    }

    public function payment_receipt() {
        if ( ! atbdp_is_user_logged_in() ) return null;

        $order_id = (int) get_query_var( 'atbdp_order_id' );

        //phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if ( empty( $order_id ) && ! empty( $_REQUEST['order'] ) ) {
            //phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $order_id = sanitize_text_field( wp_unslash( $_REQUEST['order'] ) );
        }
        
        if ( empty( $order_id ) ) {
            return __( 'Sorry! No order id has been provided.', 'directorist' );
        }

        $order_repository = directorist_order_repository();
        $order_db         = $order_repository->get_by_id( $order_id );

        if ( ! $order_db ) {
            return __( "Order not found" );
        }

        $order = $order_repository->to_dto( $order_db );

        $payment_repository = directorist_payment_repository();
        $payments           = $payment_repository->get( $order_id );
        $payment            = $payments[0] ?? null;

        if ( $payment ) {
            $payment = $payment_repository->to_dto( $payment );
        }

        wp_enqueue_script( 'directorist-payment-receipt' );

        return View::get(
            'checkout/receipt', [
                'order'   => $order,
                'payment' => $payment
            ]
        );
    }

    public function checkout() {
        // return null if user is not logged in
        if ( ! atbdp_is_user_logged_in() ) return null;

        $request   = Helpers::request();
        $validator = new Validator( $request, new Mime );

        try {
            $enable_monetization = apply_filters( 'atbdp_enable_monetization_checkout', directorist_is_monetization_enabled() );

            if ( ! $enable_monetization ) {
                throw new \Exception( __( 'Monetization is not active on this site. if you are an admin, you can enable it from the settings panel.', 'directorist' ) );
            }

            $validator->validate(
                [
                    'checkout_type' => 'required|accepted:' . implode( ',', directorist_get_checkout_types() )
                ], false 
            );

            if ( $validator->is_fail() ) {
                throw new \Exception( __( 'Invalid checkout type.', 'directorist' ) );
            }

            do_action( 'directorist_checkout_validation', $request->get_param( 'checkout_type' ), $request, $validator );

        } catch ( \Throwable $th ) {
            return "<div class='notice_wrapper'><div class='directorist-alert directorist-alert-warning'>{$th->getMessage()}</div></div>";
        }

        wp_enqueue_script( 'directorist-notification' );
        wp_enqueue_script( 'directorist-checkout' );
        wp_enqueue_script( 'wp-api-fetch' );

        return View::get(
            'checkout/checkout', [
                'checkout_type' => $request->get_param( 'checkout_type' ),
                'request'       => $request,
                'subtotal'      => apply_filters( 'directorist_checkout_subtotal', 0, $request->get_param( 'checkout_type' ), $request )
            ]
        );
    }
}