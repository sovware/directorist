<?php
/**
 * Licensing helper functions.
 */
defined( 'ABSPATH' ) || exit;

function directorist_licensing_data(): array {
	return get_option( 'directorist_licensing_account_data' ) ?? [];
}

function directorist_licensing_is_connected(): bool {
	$data = directorist_licensing_data();

	return isset( $data['account_data']['user_id'] );
}

function directorist_licensing_get_access_key(): string {
	$data = directorist_licensing_data();

	return $data['account_data']['access_key'] ?? '';
}

function directorist_licensing_get_access_key_with_obfuscation(): string {
	$key = directorist_licensing_get_access_key();

	return str_replace( ' ', ' ', substr( $key, 0, 3 ) . '********' . substr( $key, -3 ) );
}

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
