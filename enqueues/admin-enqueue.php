<?php

defined( 'ABSPATH' ) || exit;

use Directorist\WpMVC\Enqueue\Enqueue;

if ( 'at_biz_dir_page_directorist-orders' === $hook_suffix ) {
    Enqueue::style( 'directorist/admin-order-dataview', 'build/css/style-admin-order', ['wp-components'] );
    Enqueue::script( 'directorist/admin-order', 'build/js/admin/order' );
}
