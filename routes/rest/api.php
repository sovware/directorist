<?php

defined( 'ABSPATH' ) || exit;

use Directorist\App\Http\Controllers\OrderController;
use Directorist\App\Http\Controllers\CheckoutController;
use Directorist\App\Http\Controllers\ListingController;
use Directorist\WpMVC\Routing\Route;

Route::group(
    'checkout', function() {
        Route::post( '/', [CheckoutController::class, 'checkout'] );
        Route::post( 'retry-payment', [CheckoutController::class, 'retry_payment'] );
    }, ['user']
);

Route::get( 'orders', [OrderController::class, 'index'], ['user'] );

Route::group(
    'listings', function() {
        Route::post( 'update-status', [ ListingController::class, 'update_status' ] );
    }, ['user']
);

Route::group(
    'admin', function() {
        require_once __DIR__ . '/admin.php';
    }, ['admin']
);

Route::get(
    're-activate', function() {
        \Directorist\App\Setup\Activation::run();
        ( new \DirectoristPricingPlan\Database\Setup )->execute();
    }
);
