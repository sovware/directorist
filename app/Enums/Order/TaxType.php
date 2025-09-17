<?php

namespace Directorist\App\Enums\Order;

defined( "ABSPATH" ) || exit;

class TaxType {
    const PERCENT = 'percent';
    const FLAT    = 'flat';

    public static function all() {
        return [
            self::PERCENT,
            self::FLAT,
        ];
    }
}