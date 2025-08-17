<?php

namespace Directorist\App\Contracts;

defined( "ABSPATH" ) || exit;

use Directorist\App\DTO\Order\DTO as OrderDTO;

interface PaymentInterface {
    public static function get_key(): string;

    public function pay( OrderDTO $dto ): ?string;
}
