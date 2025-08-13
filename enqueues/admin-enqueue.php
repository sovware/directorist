<?php

defined( 'ABSPATH' ) || exit;

use Directorist\WpMVC\Enqueue\Enqueue;

if ( 'admin_page_directorist-orders' === $hook_suffix ) {
    Enqueue::script( 'directorist/admin-order', 'build/js/admin-order' );
}
