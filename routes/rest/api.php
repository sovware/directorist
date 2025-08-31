<?php

defined( 'ABSPATH' ) || exit;

use Directorist\App\Http\Controllers\CheckoutController;
use Directorist\App\Http\Controllers\Admin\PaymentController;
use Directorist\App\Http\Controllers\Admin\OrderController;
use Directorist\App\Http\Controllers\Admin\RefundController;
use Directorist\WpMVC\Routing\Route;


Route::group(
    'checkout', function() {
        Route::post( '/', [CheckoutController::class, 'checkout'] );
        Route::post( 'retry-payment', [CheckoutController::class, 'retry_payment'] );
    }
);

Route::group(
    'admin', function() {
        Route::group(
            'orders', function() {
                Route::group(
                    '{id}', function() {
                        Route::post( 'status', [OrderController::class, 'update_status'] );
                    }
                );
                Route::resource( '{order_id}/refunds', RefundController::class );
                Route::resource( '/', OrderController::class );
            }
        );
        Route::resource( 'payments', PaymentController::class );
    }, ['admin']
);