<?php
/**
 * Handles database queries.
 *
 * @author wpWax
 */

namespace Directorist\database;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DB {


	public static function get_listings( $args ) {
		$args['fields'] = 'ids';
		$query       = new \WP_Query( $args );
		$paginated   = ! $query->get( 'no_found_rows' );

		$results = (object) [
			'ids'          => wp_parse_id_list( $query->posts ),
			'total'        => $paginated ? (int) $query->found_posts : count( $query->posts ),
			'total_pages'  => $paginated ? (int) $query->max_num_pages : 1,
			'per_page'     => (int) $query->get( 'posts_per_page' ),
			'current_page' => $paginated ? (int) max( 1, $query->get( 'paged', 1 ) ) : 1,
		];

		return $results;
	}

}
