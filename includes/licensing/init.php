<?php
/**
 * Init Licensing.
 */
namespace Directorist\Licensing;

defined( 'ABSPATH' ) || die();

require_once trailingslashit( __DIR__ ) . 'functions.php';
require_once trailingslashit( __DIR__ ) . 'class-controllers.php';
require_once trailingslashit( __DIR__ ) . 'class-routes.php';

new Routes();