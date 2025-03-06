<?php
/**
 * Extension Handler Repository
 *
 * @package Directorist Licensing
 * @version  1.0.0
 */

namespace Directorist\Licensing;

use Plugin_Upgrader;
use WP_Ajax_Upgrader_Skin;

defined( 'ABSPATH' ) || exit;

class Extension_Handler {

	public function install( string $slug ) {
		$extensions = $this->get_unlocked_extensions();

		// Find the extension by slug
		$plugin_data = null;
		foreach ( $extensions as $extension ) {
			if ( $extension['slug'] === $slug ) {
				$plugin_data = $extension;
				break;
			}
		}

		if ( ! $plugin_data ) {
			throw new \Exception(
				__( 'You do not have access to install this extension.', 'directorist' ),
				403
			);
		}

		try {
			$this->installer( $plugin_data );

			$this->activate( $slug );

			return [
				'success' => true,
				'message' => __( 'Plugin installed and activated successfully.', 'directorist' ),
			];

		} catch ( \Exception $e ) {
			return [
				'success' => false,
				'message' => $e->getMessage(),
				'code'    => $e->getCode(),
			];
		}
	}

	public function installer( array $plugin ) {
		$this->includes();
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$slug         = $plugin['slug'] ?? '';
		$download_url = $plugin['download_link'] ?? '';

		if ( ! $download_url ) {
			throw new \Exception(
				__( 'Invalid download link', 'directorist' ),
				403
			);
		}

		$errors   = [];
		$skin     = new WP_Ajax_Upgrader_Skin();
		$upgrader = new Plugin_Upgrader( $skin );

		$result = $upgrader->install( $download_url );

		if ( is_wp_error( $result ) ) {
			throw new \Exception( $result->get_error_message(), $result->get_error_code() );
		}

		error_log( '$result  : ' . print_r( $result, true ) );

		return [
			'success' => true,
			'slug'    => $slug,
		];
	}

	public function activate( string $slug ) {
		$installed_plugins = $this->get();

		// Find the correct plugin file
		$file_name = null;
		foreach ( $installed_plugins as $plugin_file => $plugin_info ) {
			if ( strpos( $plugin_file, $slug . '/' ) === 0 || strpos( $plugin_file, $slug . '-' ) === 0 ) {
				$file_name = $plugin_file;
				break;
			}
		}

		if ( ! $file_name ) {
			throw new \Exception(
				__( 'Plugin file not found for activation.', 'directorist' ),
				404
			);
		}

		if ( ! is_plugin_inactive( $file_name ) ) {
			return true;
		}

		$result = activate_plugin( $file_name, false, false );

		if ( is_wp_error( $result ) ) {
			throw new \Exception(
				esc_html__( $result->get_error_message(), 'directorist' ),
				401
			);
		}

		return true;
	}

	public function deactivate( string $slug ) {
		$installed_plugins = $this->get();

		// Find the correct plugin file
		$file_name = null;
		foreach ( $installed_plugins as $plugin_file => $plugin_info ) {
			if ( strpos( $plugin_file, $slug . '/' ) === 0 || strpos( $plugin_file, $slug . '-' ) === 0 ) {
				$file_name = $plugin_file;
				break;
			}
		}

		if ( ! $file_name ) {
			throw new \Exception(
				__( 'Plugin file not found for deactivation.', 'directorist' ),
				404
			);
		}

		// Check if the plugin is already inactive
		if ( ! is_plugin_active( $file_name ) ) {
			return true; // Plugin is already inactive
		}

		// Deactivate the plugin
		deactivate_plugins( $file_name ); // Use deactivate_plugins for deactivation

		// Ensure the plugin is deactivated after calling deactivate_plugins
		if ( is_plugin_active( $file_name ) ) {
			throw new \Exception(
				__( 'Failed to deactivate the plugin.', 'directorist' ),
				500
			);
		}

		return true;
	}

	public function update( string $slug ) {
		$extensions = $this->get_unlocked_extensions();

		// Find the extension by slug
		$plugin_data = null;
		foreach ( $extensions as $extension ) {
			if ( $extension['slug'] === $slug ) {
				$plugin_data = $extension;
				break;
			}
		}

		if ( ! $plugin_data ) {
			throw new \Exception(
				__( 'You do not have access to update this extension.', 'directorist' ),
				403
			);
		}

		try {
			// Attempt to update the plugin by reinstalling
			$this->installer( $plugin_data );

			// Optionally, activate after update
			$this->activate( $slug );

			return [
				'success' => true,
				'message' => __( 'Plugin updated and activated successfully.', 'directorist' ),
			];

		} catch ( \Exception $e ) {
			return [
				'success' => false,
				'message' => $e->getMessage(),
				'code'    => $e->getCode(),
			];
		}
	}

	private function get(): array {
		$this->includes();

		return get_plugins();
	}

	public function get_unlocked_extensions(): array {
		return Licensing_Plan::get_unlocked_products( 'extensions' ) ?? [];
	}

	public function includes(): void {
		if ( ! function_exists( 'is_plugin_active' ) || ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
	}
}
