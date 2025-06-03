<?php

defined( 'ABSPATH' ) || exit;

use Directorist\App\PaymentProcessors\BankTransfer;

return [
	BankTransfer::get_key() => BankTransfer::class
];