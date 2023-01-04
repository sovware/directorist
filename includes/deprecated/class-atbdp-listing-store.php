<?php
/**
 * @deprecated 7.4.3
 */

use Directorist\database\DB;

class ATBDP_Listings_Data_Store {

	/**
	 * Unused method
	 *
	 * @return object WP_Query
	 */
	public static function get_archive_listings_query( array $query_args = [], array $custom_option = [] ) {
		_deprecated_function( __METHOD__, '7.4.3', 'DB::get_listings' );
		return DB::get_listings_data( $query_args );
	}

	/**
	 * Unused method
	 *
	 * @return object WP_Query
	 */
	public static function get_listings( array $args = [] ) {
		_deprecated_function( __METHOD__, '7.4.3' );
		return get_posts( $args );
	}

	/**
	 * Unused method
	 *
	 * @return object WP_Query
	 */
	public static function get_listings_query( array $args = [] ) {
		_deprecated_function( __METHOD__, '7.4.3' );
		return new WP_Query();
	}

	// get_listings_ids
	public static function get_listings_ids( array $query_args = [] ) {
		_deprecated_function( __METHOD__, '7.4.3' );
		$default = [
			'post_type'      => ATBDP_POST_TYPE,
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'fields'         => 'ids'
		];
		$query_args = array_merge( $default, $query_args );

		$query = new WP_Query( $query_args );

		return $query->posts;
	}

	/**
	 * Unused method
	 *
	 * @return object WP_Query
	 */
	public static function get_listings_by_user( $user_id = 0 ) {
		_deprecated_function( __METHOD__, '7.4.3' );
		return new WP_Query();
	}

	/**
	 * Unused method
	 *
	 * @return object WP_Query
	 */
	public static function get_favourite_listings( $user_id = 0 ) {
		_deprecated_function( __METHOD__, '7.4.3' );
		return new WP_Query();
	}
}
