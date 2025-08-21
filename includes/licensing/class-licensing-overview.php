<?php
declare ( strict_types = 1 );

namespace Directorist\Licensing;

defined( 'ABSPATH' ) || exit;

class Licensing_Overview {
    public static function get( string $type ) {
        $extensions = Licensing_Products::get_extensions();
        if ( ! is_array( $extensions ) ) {
            return 0;
        }

        $official_extensions = array_column( $extensions, 'slug' );
        $installed_plugins   = get_plugins();
        $updates_available   = self::get_outdated_plugins();

        $installed_extensions = self::get_installed_extensions( $installed_plugins, $official_extensions );
        $active_slugs         = self::get_active_plugins( $installed_extensions );
        $backdated_slugs      = self::get_backdated_slugs( $installed_extensions, $updates_available );
        $inactive_slugs       = array_diff( array_keys( $installed_extensions ), $active_slugs );

        switch ( $type ) {
            case 'active_count':
                return count( $active_slugs );

            case 'available_count':
                return count( Licensing_Plan::get_unlocked_products( 'extensions' ) );

            case 'officials_count':
                return count( $official_extensions );

            case 'backdated_count':
                return count( $backdated_slugs );

            case 'available_templates_count':
                return count( Licensing_Plan::get_unlocked_products( 'templates' ) );
            
            case 'templates_count':
                return count( Licensing_Products::get_templates() );

            case 'active_slug_list':
                return $active_slugs;

            case 'inactive_slug_list':
                return $inactive_slugs;

            case 'backdated_slug_list':
                return $backdated_slugs;

            case 'outdated':
                return count( array_intersect( array_keys( $installed_extensions ), $updates_available ) );

            case 'outdated_plugins':
                return $updates_available;

            default:
                return 0;
        }
    }

    private static function get_outdated_plugins(): array {
        $updates = get_site_transient( 'update_plugins' );

        return isset( $updates->response ) ? array_keys( $updates->response ) : [];
    }

    private static function get_installed_extensions( array $installed_plugins, array $official_extensions ): array {
        return array_filter(
            $installed_plugins, function ( $plugin_data, $plugin_base ) use ( $official_extensions ) {
                return strpos( $plugin_base, 'directorist-' ) === 0 && in_array( strtok( $plugin_base, '/' ), $official_extensions, true );
            }, ARRAY_FILTER_USE_BOTH 
        );
    }

    private static function get_active_plugins( array $installed_extensions ): array {
        return array_map(
            function ( $plugin ) {
                return strtok( $plugin, '/' );
            }, array_filter( array_keys( $installed_extensions ), 'is_plugin_active' ) 
        );
    }

    private static function get_backdated_slugs( array $installed_extensions, array $outdated_plugins ): array {
        return array_map(
            function ( $plugin ) {
                return strtok( $plugin, '/' );
            }, array_intersect( array_keys( $installed_extensions ), $outdated_plugins ) 
        );
    }
}
