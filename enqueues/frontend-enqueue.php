<?php

defined( 'ABSPATH' ) || exit;

use Directorist\WpMVC\Enqueue\Enqueue;

Enqueue::register_script( 'directorist-payment-receipt', 'build/js/frontend/payment-receipt.js', ['jquery', 'wp-api-fetch'] );