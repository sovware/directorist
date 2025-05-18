<?php
/**
 * Helper.
 */
namespace Directorist\Licensing\Utils;

use Elementor\Plugin;

class Helper {

	public static function is_edit() {
		return ( Plugin::instance()->editor->is_edit_mode() ||
			Plugin::instance()->preview->is_preview_mode() ||
			is_preview() );
	}

	public static function log( $data, string $prefix = '' ): void {
		$_data = '';
		if ( is_array( $data ) || is_object( $data ) ) {
			$_data = print_r( $data, true );
		} else {
			$_data = $data;
		}

		if ( $prefix ) {
			error_log( $prefix . ':' . $_data );
		} else {
			error_log( $_data );
		}
	}

	public static function get_ip(): string {
		$ip = '127.0.0.1'; // Local IP
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = sanitize_text_field( $_SERVER['HTTP_CLIENT_IP'] );
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = sanitize_text_field( $_SERVER['HTTP_X_FORWARDED_FOR'] );
		} else {
			$ip = ! empty( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) : $ip;
		}

		return $ip;
	}
}
