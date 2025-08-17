<?php

defined( 'ABSPATH' ) || exit;

use Directorist\App\Providers\ShortcodeServiceProvider;
use Directorist\App\Providers\Admin\MenuServiceProvider;
use Directorist\App\Http\Middleware\EnsureIsUserAdmin;
use Directorist\App\Providers\FeaturedListingCheckoutServiceProvider;
use Directorist\App\Providers\PaymentServiceProvider;

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
        ShortcodeServiceProvider::class,
        FeaturedListingCheckoutServiceProvider::class,
        PaymentServiceProvider::class
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