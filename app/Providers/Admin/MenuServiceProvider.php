<?php

namespace Directorist\App\Providers\Admin;

defined( "ABSPATH" ) || exit;

use Directorist\WpMVC\Contracts\Provider;
use Directorist\WpMVC\Exceptions\Exception;

class MenuServiceProvider implements Provider {
    public function boot() {
        add_action( 'admin_menu', [$this, 'action_admin_menu'] );
    }

    public function action_admin_menu() {
        add_submenu_page( 'edit.php?post_type=at_biz_dir', 'Orders New', 'Orders New', 'manage_options', 'directorist-orders', [ $this, 'orders_page' ] );
    }

    public function orders_page() {
        echo '<div class="directorist-orders-page"></div>';
    }
}