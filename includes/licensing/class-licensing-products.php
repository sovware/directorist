<?php
declare ( strict_types = 1 );

namespace Directorist\Licensing;

defined( 'ABSPATH' ) || exit;

class Licensing_Products {
    public static function get_templates(): array {
        return self::get_merged_products( 'templates', true );
    }

    public static function get_extensions(): array {
        return self::get_merged_products( 'extensions', true );
    }

    private static function get_merged_products( string $type, bool $merge_unlocked = false ): array {
        $products = Repository::get_promotional_content()[$type] ?? [];

        if ( $merge_unlocked ) {
            $unlocked = Licensing_Plan::get_unlocked_products( $type );
            $merged   = [];

            // Index unlocked products by item_id
            foreach ( $unlocked as $item ) {
                $merged[$item['item_id']] = $item;
            }

            // Merge promotional products
            foreach ( $products as $item ) {
                if ( isset( $merged[$item['item_id']] ) ) {
                    $merged[$item['item_id']] = array_merge( $merged[$item['item_id']], $item );
                } else {
                    $merged[$item['item_id']] = $item;
                }
            }

            // Convert associative array to indexed array

            return array_values( $merged );
        }

        return $products;
    }
}