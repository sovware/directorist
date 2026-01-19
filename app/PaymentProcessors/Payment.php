<?php

namespace Directorist\App\PaymentProcessors;

defined( "ABSPATH" ) || exit;

abstract class Payment {
    public bool $has_subscription = false;
}
