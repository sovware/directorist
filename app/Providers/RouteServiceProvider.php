<?php

namespace Directorist\App\Providers;

defined( "ABSPATH" ) || exit;

use Directorist\WpMVC\Routing\Providers\RouteServiceProvider as WpMVCRouteServiceProvider;
use Directorist\DI\Container;

class RouteServiceProvider extends WpMVCRouteServiceProvider {

    public function boot() {
        /**
         * Set Di Container
         */
        parent::$container = new Container();

        /**
         * Set required properties
         */
        parent::$properties = [
            'rest'       => [
                'namespace' => 'directorist',
                'versions'  => []
            ],
            'ajax'       => [
                'namespace' => 'directorist',
                'versions'  => []
            ],
            'middleware' => [],
            'routes-dir' => ABSPATH . 'wp-content/plugins/directorist/routes'
        ];

        parent::boot();
    }
}
