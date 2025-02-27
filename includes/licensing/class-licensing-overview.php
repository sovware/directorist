<?php
declare ( strict_types = 1 );

namespace Directorist\Licensing;

defined( 'ABSPATH' ) || exit;
class Licensing_Overview {
	public static function get_extensions_overview( string $type ) {
		$extensions = Licensing_Products::get_extensions();
		if ( ! is_array( $extensions ) ) {
			return 0;
		}

		$official_extensions = array_column( $extensions, 'slug' );
		$installed_plugins   = get_plugins();
		$updates_available   = get_site_transient( 'update_plugins' );
		$outdated_plugins    = isset( $updates_available->response ) ? array_keys( $updates_available->response ) : [];

		$installed_extensions = array_filter( $installed_plugins, function ( $plugin_data, $plugin_base ) use ( $official_extensions ) {
			return preg_match( '/^directorist-/', $plugin_base ) && in_array( strtok( $plugin_base, '/' ), $official_extensions, true );
		}, ARRAY_FILTER_USE_BOTH );

		$active_plugins = array_filter( array_keys( $installed_extensions ), 'is_plugin_active' );
		$active_slugs   = array_map( fn( $plugin ) => strtok( $plugin, '/' ), $active_plugins );

		// Get backdated (outdated) slugs
		$backdated_slugs = array_map( fn( $plugin ) => strtok( $plugin, '/' ), array_intersect( array_keys( $installed_extensions ), $outdated_plugins ) );

		$counts = [
			'active'         => count( $active_slugs ),
			'available'      => count( $installed_extensions ),
			'outdated'       => count( array_intersect( array_keys( $installed_extensions ), $outdated_plugins ) ),
			'officials'      => count( $official_extensions ),
			'backdated'      => count( $backdated_slugs ), // Add count for backdated extensions
			'active_list'    => $active_slugs,
			'backdated_list' => $backdated_slugs, // List of backdated slugs
		];

		return $counts[$type] ?? 0;
	}

	public static function get_templates_overview(): int {
		return count( Licensing_Products::get_templates() );
	}
}
