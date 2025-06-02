<?php

defined( 'ABSPATH' ) || exit;

use Directorist\App\Http\Controllers\Admin\PaymentController;
use Directorist\App\Http\Controllers\Admin\OrderController;
use Directorist\WpMVC\Routing\Route;

Route::group(
    'admin', function() {
        Route::resource( 'orders', OrderController::class );
        Route::resource( 'payments', PaymentController::class );
    }, ['admin']
);