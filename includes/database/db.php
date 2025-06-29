<?php
/**
 * Common database queries will go here.
 *
 * @author wpWax
 * @since  7.4.3
 */

namespace Directorist\database;

use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DB {

	/**
	 * Get listings data
	 *
	 * @param array $args
	 *
	 * @return object
	 */
	public static function get_listings_data( $args ) {
		$args['fields'] = 'ids';
		$query       = new WP_Query( $args );
		$paginated   = ! $query->get( 'no_found_rows' );

		$results = (object) [
			'ids'          => wp_parse_id_list( $query->posts ),
			'total'        => $paginated ? (int) $query->found_posts : count( $query->posts ),
			'total_pages'  => $paginated ? (int) $query->max_num_pages : 1,
			'per_page'     => (int) $query->get( 'posts_per_page' ),
			'current_page' => $paginated ? (int) max( 1, $query->get( 'paged', 1 ) ) : 1,
		];

		return apply_filters( 'directorist_listings_query_results', $results );
	}

	/**
	 * Get favorite listings query
	 *
	 * @param int $user_id
	 *
	 * @return object WP_Query object.
	 */
	public static function favorite_listings_query( $user_id = 0 ) {
		$user_id   = $user_id ? $user_id : get_current_user_id();
		$favorites = directorist_get_user_favorites( $user_id );

		if ( !$favorites ) {
			return new WP_Query();
		}

		$args = array(
			'post_type'      => ATBDP_POST_TYPE,
			'posts_per_page' => -1,
			'order'          => 'DESC',
			'post__in'       => $favorites,
			'orderby'        => 'date'
	   );

		return new WP_Query( $args );
	}

}
