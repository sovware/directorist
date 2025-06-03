<?php

namespace Directorist\App\Contracts;

defined( "ABSPATH" ) || exit;

use stdClass;

interface PaymentInterface {
    public static function get_key(): string;

    public function pay( stdClass $amount, array $params = [] );
}
