<?php

defined( 'ABSPATH' ) || exit;

use Directorist\App\Http\Middleware\EnsureIsUserAdmin;

return [
    'version'                 => ATBDP_VERSION,

    'rest_api'                => [
        'namespace'=> 'directorist',
        'versions'  => []
    ],

    'ajax_api'                => [
        'namespace' => 'directorist',
        'versions'  => []
    ],

    'providers'               => [ ],

    'admin_providers'         => [],

    'middleware'              => [
        'admin' => EnsureIsUserAdmin::class
    ],

    'migration_db_option_key' => 'directorist_migrations',

    'migrations'              => []
];