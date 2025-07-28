<?php
declare ( strict_types = 1 );

namespace Directorist\Licensing;

defined( 'ABSPATH' ) || exit;
class Licensing_Access {
    public static function get_key(): string {
        return self::get_data()['account_data']['access_key'] ?? '';
    }

    public static function get_key_obfuscated(): string {
        $key = self::get_key();

        return $key ? substr( $key, 0, 3 ) . '********' . substr( $key, -3 ) : '';
    }

    private static function get_data(): array {
        return Licensing::get_data();
    }
}