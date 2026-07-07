<?php

namespace Directorist;

defined( "ABSPATH" ) || exit;

class AdminMenu {
    public function __construct() {
        add_action( 'admin_menu', [$this, 'action_admin_menu'] );
    }

    public function action_admin_menu() {
        add_submenu_page( 'edit.php?post_type=at_biz_dir', 'Orders', 'Orders', 'manage_options', 'directorist-orders', [ $this, 'orders_page' ] );
    }

    public function orders_page() {
        echo '<div class="directorist-orders-page"></div>';
    }
}