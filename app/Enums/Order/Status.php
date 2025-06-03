<?php

namespace Directorist\App\Enums\Order;

defined( "ABSPATH" ) || exit;

class Status {
    const PENDING   = 'pending';
    const PAID      = 'paid';
    const FAILED    = 'failed';
    const CANCELLED = 'cancelled';
    const EXPIRED   = 'expired';
    const REFUNDED  = 'refunded';
    const UNPAID    = 'unpaid';
}