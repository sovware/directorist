<?php
/**
 * Rest Licensing Controller
 *
 * @package Directorist Licensing
 * @version  1.0.0
 */

namespace Directorist\Licensing;

defined( 'ABSPATH' ) || exit;

class Controllers {

	public function login_with_access_key( \WP_REST_Request $request ) {
		// Debug incoming request data
		$data       = $request->get_params(); // Get request data (JSON, query params, etc.)
		$access_key = (string) $request->get_param( 'login-with-access-key' );

		return rest_ensure_response( [
			'success'    => true,
			'access_key' => $access_key,
			'data'       => $data,
		] );
	}

	public function login_with_account( \WP_REST_Request $request ) {
		// Debug incoming request data
		$data     = $request->get_params(); // Get request data (JSON, query params, etc.)
		$username = (string) $request->get_param( 'directorist-username' );
		$password = (string) $request->get_param( 'directorist-password' );

		return rest_ensure_response( [
			'success'  => true,
			'username' => $username,
			'password' => $password,
			'data'     => $data,
		] );
	}
}
