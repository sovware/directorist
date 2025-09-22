<?php

defined( 'ABSPATH' ) || exit;

use Directorist\WpMVC\Enqueue\Enqueue;

Enqueue::style( 'directorist/frontend', 'build/css/frontend', ['wp-components'] );
Enqueue::register_script( 'directorist-payment-receipt', 'build/js/frontend/payment-receipt.js', ['jquery', 'wp-api-fetch'] );
Enqueue::script( 'directorist-listing-owner-dashboard', 'build/js/frontend/listing-owner-dashboard' );