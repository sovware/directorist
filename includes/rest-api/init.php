<?php
/**
 * Init REST api.
 */
namespace wpWax\Directorist\RestApi;

defined( 'ABSPATH' ) || die();

function register_controllers() {
	$dir = trailingslashit( __DIR__ );

	require_once $dir . 'class-abstract-controller.php';
	require_once $dir . 'class-listings-rest-controller.php';

	$listings = new Listings_REST_Controller();
	$listings->register_routes();
}
add_action( 'rest_api_init', __NAMESPACE__ . '\\register_controllers' );

/**
 * Converts a string (e.g. 'yes' or 'no') to a bool.
 *
 * @since 7.1.0
 * @param string|bool $string String to convert. If a bool is passed it will be returned as-is.
 * @return bool
 */
function directorist_string_to_bool( $string ) {
	return is_bool( $string ) ? $string : ( 'yes' === strtolower( $string ) || 1 === $string || 'true' === strtolower( $string ) || '1' === $string );
}

/**
 * Converts a bool to a 'yes' or 'no'.
 *
 * @since 7.1.0
 * @param bool|string $bool Bool to convert. If a string is passed it will first be converted to a bool.
 * @return string
 */
function directorist_bool_to_string( $bool ) {
	if ( ! is_bool( $bool ) ) {
		$bool = directorist_string_to_bool( $bool );
	}
	return true === $bool ? 'yes' : 'no';
}
