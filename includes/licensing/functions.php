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
