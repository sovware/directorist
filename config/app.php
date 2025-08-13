<?php

defined( 'ABSPATH' ) || exit;

use Directorist\App\Providers\ShortcodeServiceProvider;
use Directorist\App\Providers\PaymentServiceProvider;
use Directorist\App\Providers\Admin\MenuServiceProvider;
use Directorist\App\Providers\OrderServiceProvider;
use Directorist\App\Http\Middleware\EnsureIsUserAdmin;

return [
    'version'                 => ATBDP_VERSION,

    'rest_api'                => [
        'namespace' => 'directorist',
        'versions'  => []
    ],

    'ajax_api'                => [
        'namespace' => 'directorist',
        'versions'  => []
    ],

    'providers'               => [
        // OrderServiceProvider::class,
        // PaymentServiceProvider::class,
        // ShortcodeServiceProvider::class
    ],

    'admin_providers'         => [
        MenuServiceProvider::class,
    ],

    'middleware'              => [
        'admin' => EnsureIsUserAdmin::class
    ],

    'migration_db_option_key' => 'directorist_migrations',

    'migrations'              => []
];