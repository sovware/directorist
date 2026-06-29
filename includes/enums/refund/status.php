<?php

namespace Directorist\Enums\Refund;

defined( "ABSPATH" ) || exit;

class Status {
    const PENDING    = 'pending';
    const PROCESSING = 'processing';
    const PAID       = 'paid';
    const REJECTED   = 'rejected';

    public static function all() {
        return [
            self::PENDING,
            self::PROCESSING,
            self::PAID,
            self::REJECTED,
        ];
    }
}