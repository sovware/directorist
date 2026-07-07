<?php

namespace Directorist\Contracts;

defined( "ABSPATH" ) || exit;

use Directorist\DTO\Order\DTO as OrderDTO;

interface PaymentInterface {
    public static function get_key(): string;

    public function supports_recurring(): bool;

    public function supports_trial(): bool;

    public function pay( OrderDTO $dto, array $params = [] ): ?string;
}
