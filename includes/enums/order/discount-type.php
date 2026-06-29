<?php

namespace Directorist\Enums\Order;

defined( "ABSPATH" ) || exit;

class DiscountType {
    const PERCENT = 'percent';
    const FLAT    = 'flat';

    public static function all() {
        return [
            self::PERCENT,
            self::FLAT,
        ];
    }
}