<?php

defined( 'ABSPATH' ) || exit;

use Directorist\WpMVC\Enqueue\Enqueue;

if ( 'at_biz_dir_page_directorist-orders' === $hook_suffix ) {
    wp_enqueue_style( 'wp-components' );
    Enqueue::script( 'directorist/admin-order', 'build/js/admin/order' );
}
