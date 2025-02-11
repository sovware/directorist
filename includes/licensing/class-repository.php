<?php
/**
 * Licensing Repository
 *
 * @package Directorist Licensing
 * @version  1.0.0
 */

namespace Directorist\Licensing;

use Directorist\Licensing\Utils\Http;

defined( 'ABSPATH' ) || exit;

class Repository {

	private string $endpoint = 'http://localhost:10014/wp-json/directorist-license-manager';

	public function login_with_access_key( string $access_key ) {
		try {
			$http = new Http(
				$this->endpoint . '/user-connect',
				[
					'access_key' => $access_key,
				]
			);

			$response = $http->post()->response();
			$raw_body = wp_remote_retrieve_body( $response );
			$data     = json_decode( $raw_body, true );

			if ( ! isset( $data['account_data']['user_id'] ) ) {
				throw new \Exception( __( 'Invalid Access Key', 'directorist' ) );
			}

			update_option( 'directorist_licensing_account_data', $data );

			return true;

		} catch ( \Throwable $th ) {
			throw $th;
		}
	}

	public function login_with_account( string $email, string $pass ) {
		try {
			$http = new Http(
				$this->endpoint . '/user-login',
				[
					'email' => $email,
					'pass'  => $pass,
				]
			);

			$response = $http->post()->response();
			$raw_body = wp_remote_retrieve_body( $response );
			$data     = json_decode( $raw_body, true );

			if ( ! isset( $data['account_data']['user_id'] ) ) {
				throw new \Exception( __( 'Invalid Email or Password', 'directorist' ) );
			}

			update_option( 'directorist_licensing_account_data', $data );

			return true;

		} catch ( \Throwable $th ) {
			throw $th;
		}
	}
}