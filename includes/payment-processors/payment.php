<?php

namespace Directorist\PaymentProcessors;

defined( "ABSPATH" ) || exit;

abstract class Payment {
    protected bool $supports_recurring = false;

    protected bool $supports_trial = false;

    public function supports_recurring(): bool {
        return $this->supports_recurring;
    }

    public function supports_trial(): bool {
        return $this->supports_trial;
    }
}
