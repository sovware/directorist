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
		$data = $request->get_params(); // Get request data (JSON, query params, etc.)

		return rest_ensure_response( [
			'success' => true,
			'data'    => $data,
		] );
	}
}
