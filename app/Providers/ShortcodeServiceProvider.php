<?php

namespace Directorist\App\Providers;

defined( "ABSPATH" ) || exit;

use Directorist\WpMVC\Contracts\Provider;
use Directorist\WpMVC\Exceptions\Exception;

class ShortcodeServiceProvider implements Provider {
    public function boot() {
        add_shortcode( 'directorist_payment_receipt', [$this, 'payment_receipt'] );
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
        $order            = $order_repository->get_by_id( $order_id );

        if ( ! $order ) {
            return __( "Order not found" );
        }

        $payment_repository = directorist_payment_repository();
        $payments           = $payment_repository->get( $order_id );

        ob_start();
        echo "<pre>";
        print_r( $order );
        print_r( $payments );
        echo "</pre>";
        return ob_get_clean();
    }
}