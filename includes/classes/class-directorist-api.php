<?php
/**
 * Directorist API class.
 */
namespace Directorist\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class API {

	const URL = 'https://app.directorist.com/wp-json/directorist/';

	/**
	 * @return object
	 */
	public static function get_promotion() {
		$promotion = get_transient( 'directorist_promotion' );

		if ( ! empty( $promotion ) ) {
			return $promotion;
		}

		$promotion  = static::get( 'v1/get-promo' );
		$promotion = json_decode( $promotion );
		$end_time  = static::get_promotion_end_time( $promotion );

		set_transient( 'directorist_promotion', $promotion, $end_time );

		return $promotion;
	}

	protected static function get_promotion_end_time( $promotion ) {
		if ( empty( $promotion ) ||
			( is_object( $promotion ) && empty( $promotion->promo_end_date ) ) ||
			( is_array( $promotion ) && empty( $promotion['promo_end_date'] ) ) ) {
			return ( 3 * DAY_IN_SECONDS );
		}

		$promotion = (object) $promotion;
		$end_time  = is_numeric( $promotion->promo_end_date ) ? (int) $promotion->promo_end_date : strtotime( $promotion->promo_end_date );
		$end_time  = $end_time - time();

		return $end_time;
	}

	/**
	 * @return object
	 */
	public static function get_products() {
		$products = get_transient( 'directorist_products' );

		if ( ! empty( $products ) ) {
			return $products;
		}

		$products = static::get( 'v1/get-remote-products' );

		if ( empty( $products ) ) {
			return array(
				'themes' => array(),
				'extensions' => array(),
			);
		}

		$products = json_decode( $products, true );

		set_transient( 'directorist_products', $products, 30 * DAY_IN_SECONDS );

		return $products;
	}

	/**
	 * @return array
	 */
	protected static function get_request_args() {
		return array(
			'method'      => 'GET',
			'timeout'     => 30,
			'redirection' => 5,
			'headers'     => array(
				'user-agent' => 'Directorist/' . ATBDP_VERSION,
				'Accept'     => 'application/json',
			),
			'cookies'     => array(),
		);
	}

	/**
	 *
	 * @return string
	 */
	public static function get( $endpoint = '' ) {
		$url = static::URL . $endpoint;
		$response = wp_remote_get( $url, static::get_request_args() );

		return wp_remote_retrieve_body( $response );
	}
}
