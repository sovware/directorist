<?php
/**
 * @package Directorist Licensing
 * @version 1.0.0
 */

namespace Directorist\Licensing;

defined( 'ABSPATH' ) || exit;

use Directorist\Licensing\Controllers;

// Adjust namespace as necessary.

class Routes {

	/**
	 * REST API namespace.
	 */
	protected $namespace = 'directorist/v1/admin';

	protected Controllers $controller;

	public function __construct() {
		// Initialize the Controllers class once
		$this->controller = new Controllers();

		// Hook into the REST API initialization
		add_action( 'rest_api_init', [$this, 'register_routes'] );
	}

	/**
	 * Register the REST API routes.
	 */
	public function register_routes() {
		$routes = [
			'login_with_access_key' => 'login_with_access_key',
			'login_with_account'    => 'login_with_account',
			'install_theme'         => 'install_theme',
			'install_extension'     => 'install_extension',
			'account_data'          => 'account_data',
		];

		foreach ( $routes as $route => $method ) {
			register_rest_route(
				$this->namespace,
				$route,
				[
					[
						'methods'             => \WP_REST_Server::CREATABLE,
						'callback'            => [$this->controller, $method],
						'permission_callback' => [$this, 'check_permissions'],
						'args'                => [],
					],
				]
			);
		}
	}

	/**
	 * Check permissions for API routes.
	 *
	 * @return bool True if the user has permissions, false otherwise.
	 */
	public function check_permissions(): bool {
		return true; //current_user_can( 'edit_options' );
	}
}
