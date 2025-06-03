
<?php

defined( "ABSPATH" ) || exit;

use Directorist\WpMVC\Config;
use Directorist\App\Helpers\DateTime;
use Directorist\DI\Container;
use Directorist\WpMVC\App;

function directorist():App {
    return App::$instance;
}

function directorist_container():Container {
    return directorist()::$container;
}

function directorist_make( string $class ) {
    return directorist_container()->make( $class );
}

function directorist_config(): Config {
    return directorist()::$config;
}

function directorist_singleton( string $class ) {
    return directorist_container()->get( $class );
}

function directorist_now() {
    return DateTime::now();
}

function directorist_get_payment_processors() {
    return directorist_config()->get( 'payment-processors' );
}