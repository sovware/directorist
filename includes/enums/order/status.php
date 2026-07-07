<?php

namespace Directorist\Enums\Order;

defined( "ABSPATH" ) || exit;

class Status {
    const PENDING   = 'pending';
    const PAID      = 'paid';
    const FAILED    = 'failed';
    const CANCELLED = 'cancelled';
    const EXPIRED   = 'expired';
    const REFUNDED  = 'refunded';
    const UNPAID    = 'unpaid';

    public static function all() {
        return [
            self::PENDING,
            self::PAID,
            self::FAILED,
            self::CANCELLED,
            self::EXPIRED,
            self::REFUNDED,
            self::UNPAID,
        ];
    }
}