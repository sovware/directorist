<?php

defined( 'ABSPATH' ) || exit;

use Directorist\App\PaymentProcessors\BankTransfer;

return apply_filters( 'directorist_payment_processors', [
	BankTransfer::get_key() => BankTransfer::class
] );