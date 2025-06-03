<?php
declare ( strict_types = 1 );

namespace Directorist\Licensing;

defined( 'ABSPATH' ) || exit;
class Licensing {
    public static function get_data(): array {
        $data = (array) get_option( 'directorist_licensing_account_data' );

        return $data ?? [];
    }

    public static function is_connected(): bool {
        return isset( self::get_data()['account_data']['user_id'] );
    }

    public static function get_connection_method(): string {
        return self::get_data()['method'] ?? 'access_token';
    }

    public static function get_disconnect_url(): string {
        return add_query_arg(
            [
                'post_type' => 'at_biz_dir',
                'page'      => 'directorist-licensing',
                'logout'    => 'true',
            ], admin_url( 'edit.php' ) 
        );
    }
}