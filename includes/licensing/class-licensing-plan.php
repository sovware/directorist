<?php
declare ( strict_types = 1 );

namespace Directorist\Licensing;

defined( 'ABSPATH' ) || exit;
class Licensing_Plan {
    public static function has_active_plan(): bool {
        return isset( self::get_license_data()['item_id'] );
    }

    public static function is_expired(): bool {
        $expire = self::get_license_data()['expiration'] ?? '';

        return ( 'lifetime' !== $expire && $expire <= time() );
    }

    public static function get_next_payment_date(): string {
        $expire = self::get_license_data()['expiration'] ?? '';

        return ( 'lifetime' !== $expire && $expire > time() ) ? date( 'M d, Y', $expire ) : '';
    }

    public static function get_plan_name(): string {
        return self::get_license_data()['item_name'] ?? __( 'You’re on Directorist Premium Membership', 'directorist' );
    }

    public static function get_upgrade_url(): string {
        return self::get_license_data()['upgrade_to'] ?? '';
    }

    public static function get_unlocked_products( string $type ): array {
        $products = self::get_downloads()[$type] ?? [];

        if ( ! is_array( $products ) ) {
            return [];
        }

        $unique_products = [];
        $seen_slugs      = [];

        foreach ( $products as $product ) {
            $slug = $product['slug'] ?? null;

            if ( $slug && ! isset( $seen_slugs[$slug] ) ) {
                $unique_products[]   = $product;
                $seen_slugs[$slug] = true;
            }
        }

        return $unique_products;
    }

    private static function get_license_data(): array {
        return Licensing::get_data()['plan_data']['license_data'][0] ?? [];
    }

    public static function get_downloads(): array {
        return Licensing::get_data()['plan_data']['downloads'] ?? [];
    }
}