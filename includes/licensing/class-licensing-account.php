<?php
declare ( strict_types = 1 );

namespace Directorist\Licensing;

defined( 'ABSPATH' ) || exit;

class Licensing_Account {
    public static function get_name(): string {
        return self::get_account_data()['display_name'] ?? '';
    }

    public static function get_email(): string {
        return self::get_account_data()['user_email'] ?? '';
    }

    public static function get_access_key(): string {
        return self::get_account_data()['access_key'] ?? '';
    }

    public static function get_templatiq_token(): string {
        return self::get_account_data()['templatiq_token'] ?? '';
    }

    private static function get_account_data(): array {
        return Licensing::get_data()['account_data'] ?? [];
    }
}