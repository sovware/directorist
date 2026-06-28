<?php

defined( "ABSPATH" ) || exit;

use Directorist\Repositories\PaymentRepository;
use Directorist\Repositories\OrderRepository;

function directorist_order_repository(): OrderRepository {
    return directorist_make( OrderRepository::class );
}

function directorist_payment_repository(): PaymentRepository {
    return directorist_make( PaymentRepository::class );
}