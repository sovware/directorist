<?php

namespace Directorist\App\Enums\Subscription;

defined( "ABSPATH" ) || exit;

class Status {
    const PENDING   = 'pending';
    const ACTIVE    = 'active';
    const TRIALING  = 'trialing';
    const CANCELLED = 'cancelled';
    const PAUSED    = 'paused';
    const PASTDUE   = 'past_due';
    const EXPIRED   = 'expired';
}