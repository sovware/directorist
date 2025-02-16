<?php
/**
 * Licensing helper functions.
 */
defined( 'ABSPATH' ) || exit;

function directorist_licensing_data(): array {
	$data = (array) get_option( 'directorist_licensing_account_data' );

	return $data ?? [];
}

function directorist_licensing_is_connected(): bool {
	$data = directorist_licensing_data();

	return isset( $data['account_data']['user_id'] );
}

function directorist_licensing_connection_method(): string {
	$data = directorist_licensing_data();

	return $data['method'] ?? 'access_token';
}

function directorist_licensing_get_disconnect_url(): string {
	$url = add_query_arg(
		[
			'post_type' => 'at_biz_dir',
			'page'      => 'directorist-licensing',
			'logout'    => 'true',
		],
		admin_url( 'edit.php' )
	);

	return $url;
}

/**
 * Access Key Functions
 */
function directorist_licensing_get_access_key(): string {
	$data = directorist_licensing_data();

	return $data['account_data']['access_key'] ?? '';
}

function directorist_licensing_get_access_key_with_obfuscation(): string {
	$key = directorist_licensing_get_access_key();

	return str_replace( ' ', ' ', substr( $key, 0, 3 ) . '********' . substr( $key, -3 ) );
}

/**
 * Account Functions
 */
function directorist_licensing_get_account_data(): array {
	$data = directorist_licensing_data();

	return $data['account_data'] ?? [];
}

function directorist_licensing_get_account_name(): string {
	$data = directorist_licensing_get_account_data();

	return $data['display_name'] ?? '';
}

function directorist_licensing_get_account_email(): string {
	$data = directorist_licensing_get_account_data();

	return $data['user_email'] ?? '';
}

/**
 * Plan Functions
 */
function directorist_licensing_get_plan_data(): array {
	$data = directorist_licensing_data();

	return $data['plan_data'] ?? [];
}

function directorist_licensing_get_plan_upgrade_url(): string {
	$data = directorist_licensing_get_plan_data();

	if ( isset( $data['upgrade_to'] ) && $data['upgrade_to'] ) {
		if ( strpos( $data['upgrade_to'], 'sl_license_upgrade' ) !== false ) {
			return $data['upgrade_to'];
		}
	}

	return '';
}

function directorist_licensing_get_plan_has_active(): bool {
	$data = directorist_licensing_get_plan_data();

	return isset( $data['license_data'][0]['item_id'] );
}

function directorist_licensing_get_plan_is_expired(): bool {
	$data = directorist_licensing_get_plan_data();

	return false;
}

function directorist_licensing_get_plan_next_payment(): string {
	$data = directorist_licensing_get_plan_data();

	if ( isset( $data['license_data'][0]['expiration'] ) ) {
		return \date( 'M d, Y', $data['license_data'][0]['expiration'] );
	}

	return '';
}

function directorist_licensing_get_plan_name(): string {
	$data = directorist_licensing_get_plan_data();

	return $data['license_data'][0]['item_name'] ?? __( 'You’re on Directorist Premium Membership', 'directorist' );
}

/**
 * Themes and Extensions
 */
function directorist_licensing_get( $endpoint = '' ) {
	$args = [
		'method'      => 'GET',
		'timeout'     => 30,
		'redirection' => 5,
		'headers'     => [
			'user-agent' => 'Directorist/' . ATBDP_VERSION,
			'Accept'     => 'application/json',
		],
		'cookies'     => [],
	];

	$url      = 'https://app.directorist.com/wp-json/directorist/' . $endpoint;
	$response = wp_remote_get( $url, $args );

	return wp_remote_retrieve_body( $response );
}

function directorist_licensing_get_products() {
	$products = get_transient( 'directorist_products' );

	if ( ! empty( $products ) ) {
		return $products;
	}

	$products = directorist_licensing_get( 'v1/get-remote-products' );

	if ( empty( $products ) ) {
		return [
			'themes'     => [],
			'extensions' => [],
		];
	}

	$products = json_decode( $products, true );

	set_transient( 'directorist_products', $products, 30 * DAY_IN_SECONDS );

	return $products;
}

function directorist_licensing_get_extensions_overview( string $type ) {
	$extensions = directorist_licensing_get_products();

	// Get Extensions Details
	$plugin_updates       = get_site_transient( 'update_plugins' );
	$outdated_plugins     = $plugin_updates->response;
	$outdated_plugins_key = ( is_array( $outdated_plugins ) ) ? array_keys( $outdated_plugins ) : [];
	$official_extensions  = is_array( $extensions ) ? array_keys( $extensions ) : [];

	$installed_plugins    = get_plugins();
	$installed_extensions = [];
	$active_extensions    = 0;
	$outdated_extensions  = 0;

	foreach ( $installed_plugins as $plugin_base => $plugin_data ) {

		$folder_base = strtok( $plugin_base, '/' );

		if (
			preg_match( '/^directorist-/', $plugin_base )
			// && in_array( $folder_base, $official_extensions )
		) {
			$installed_extensions[$plugin_base] = $plugin_data;

			if ( is_plugin_active( $plugin_base ) ) {
				$active_extensions++;
			}

			if ( in_array( $plugin_base, $outdated_plugins_key ) ) {
				$outdated_extensions++;
			}
		}
	}

	switch ( $type ) {
		case 'active':
			$number = $active_extensions;
			break;

		case 'available':
			$number = count( $installed_extensions );
			break;

		case 'outdated':
			$number = $outdated_extensions;
			break;

		default:
			$number = 0;
			break;
	}

	return $number;
}
