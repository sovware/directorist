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

		$access_key = (string) $request->get_param( 'access_key' );

		if ( empty( $access_key ) ) {
			return rest_ensure_response( ['success' => false, 'message' => __( 'Access key is required', 'directorist' )] );
		}

		try {
			$repo = new Repository();
			$repo->login_with_access_key( $access_key );

			return rest_ensure_response( [
				'success' => true,
				'message' => __( 'Connected successfully', 'directorist' ),
			] );

		} catch ( \Throwable $th ) {
			return rest_ensure_response( [
				'success' => false,
				'message' => $th->getMessage(),
			] );
		}
	}

	public function login_with_account( \WP_REST_Request $request ) {
		$email = (string) $request->get_param( 'email' );
		$pass  = (string) $request->get_param( 'pass' );

		if ( empty( $email ) || empty( $pass ) ) {
			return rest_ensure_response( ['success' => false, 'message' => __( 'Email and Password is required', 'directorist' )] );
		}

		try {
			$repo = new Repository();
			$repo->login_with_account( $email, $pass );

			return rest_ensure_response( [
				'success' => true,
				'message' => __( 'Connected successfully', 'directorist' ),
			] );

		} catch ( \Throwable $th ) {
			return rest_ensure_response( [
				'success' => false,
				'message' => $th->getMessage(),
			] );
		}
	}

	public function logout_account() {
		delete_option( 'directorist_licensing_account_data' );

		// Remove 'logout' from the URL
		$redirect_url = remove_query_arg( 'logout' );

		// Redirect to the new URL without 'logout' parameter
		wp_safe_redirect( $redirect_url );
		exit;
	}
}