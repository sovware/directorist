<?php
/**
 * Regression test for filtering unavailable checkout gateways.
 *
 * Run with: php tests/php/checkout-active-gateways.php
 *
 * @package Directorist
 */

define( 'ABSPATH', dirname( __DIR__, 2 ) . '/' );

/**
 * Stores the gateway fixtures used by the Directorist function stubs.
 */
class Directorist_Checkout_Gateway_Test_Data {
    /**
     * Saved active gateways.
     *
     * @var mixed
     */
    public static $active_gateways = [];

    /**
     * Registered payment processors.
     *
     * @var mixed
     */
    public static $payment_processors = [];
}

/**
 * Return the saved Directorist option fixture.
 *
 * @param string $name    Option name.
 * @param mixed  $default Default value.
 * @return mixed
 */
function get_directorist_option( $name, $default = false ) {
    return 'active_gateways' === $name
        ? Directorist_Checkout_Gateway_Test_Data::$active_gateways
        : $default;
}

/**
 * Return the registered payment processor fixture.
 *
 * @return mixed
 */
function directorist_get_payment_processors() {
    return Directorist_Checkout_Gateway_Test_Data::$payment_processors;
}

require ABSPATH . 'includes/gateways/class-gateway.php';

/**
 * Stop the test when an assertion fails.
 *
 * @param bool   $condition Assertion result.
 * @param string $message   Failure message.
 * @return void
 */
function directorist_checkout_gateway_assert( $condition, $message ) {
    if ( $condition ) {
        return;
    }

    fwrite( STDERR, "FAIL: {$message}\n" );
    exit( 1 );
}

Directorist_Checkout_Gateway_Test_Data::$active_gateways    = [
    'stripe_gateway',
    'bank_transfer',
];
Directorist_Checkout_Gateway_Test_Data::$payment_processors = [
    'bank_transfer' => 'BankTransferProcessor',
];

$active_gateways = ATBDP_Gateway::get_active_gateways();

directorist_checkout_gateway_assert(
    [ 'bank_transfer' ] === $active_gateways,
    'A saved gateway must be hidden when its payment processor is no longer registered.'
);

Directorist_Checkout_Gateway_Test_Data::$payment_processors['stripe_gateway'] = 'StripeProcessor';

$active_gateways = ATBDP_Gateway::get_active_gateways();

directorist_checkout_gateway_assert(
    [ 'stripe_gateway', 'bank_transfer' ] === $active_gateways,
    'A saved gateway must remain available while its payment processor is registered.'
);

Directorist_Checkout_Gateway_Test_Data::$active_gateways = 'stripe_gateway';

directorist_checkout_gateway_assert(
    [] === ATBDP_Gateway::get_active_gateways(),
    'An invalid saved gateway value must not reach the checkout.'
);

echo "PASS: checkout gateways are limited to registered payment processors.\n";
