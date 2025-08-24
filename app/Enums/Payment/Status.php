<?php

namespace Directorist\App\Enums\Payment;

defined( "ABSPATH" ) || exit;

class Status {

    const PENDING   = 'pending';
    const PAID      = 'paid';
    const FAILED    = 'failed';
    const CANCELLED = 'cancelled';
    const REFUNDED  = 'refunded';
    const UNPAID    = 'unpaid';
    const EXPIRED   = 'expired';

    public static function all() {
        return [
            self::PENDING,
            self::PAID,
            self::FAILED,
            self::CANCELLED,
            self::REFUNDED,
            self::UNPAID,
            self::EXPIRED,
        ];
    }
}