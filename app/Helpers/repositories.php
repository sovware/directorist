<?php

defined( "ABSPATH" ) || exit;

use Directorist\App\Repositories\PaymentRepository;
use Directorist\App\Repositories\OrderRepository;

function directorist_order_repository(): OrderRepository {
    return directorist_singleton( OrderRepository::class );
}

function directorist_payment_repository(): PaymentRepository {
    return directorist_singleton( PaymentRepository::class );
}