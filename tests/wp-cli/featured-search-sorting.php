<?php
/**
 * Check featured search ordering and joins against real WordPress queries.
 * Run on a disposable site: wp eval-file wp-content/plugins/directorist/tests/wp-cli/featured-search-sorting.php
 *
 * @package Directorist
 */

defined( 'ABSPATH' ) || exit;

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Preserve request state for this CLI regression check.
$original_request = $_REQUEST;
$fixtures         = [];
$checks           = 0;

try {
    $_REQUEST = [];
    foreach ( [ 0, 1, 0, 1 ] as $index => $featured ) {
        $id = wp_insert_post(
            [
                'post_type'   => ATBDP_POST_TYPE,
                'post_status' => 'publish',
                'post_title'  => 'Sorting regression ' . $index,
                'post_date'   => '2026-01-0' . ( $index + 1 ) . ' 12:00:00',
                'meta_input'  => [
                    '_directory_type' => directorist_get_default_directory(),
                    '_featured'       => $featured,
                    '_price'          => 40 - $index * 10,
                ],
            ], true
        );
        if ( is_wp_error( $id ) ) {
            throw new RuntimeException( $id->get_error_message() );
        }
        $fixtures[] = $id;
    }

    $model                = new \Directorist\Directorist_Listings( [], 'listing', [ 'post__in' => $fixtures ] );
    $model->has_featured  = true;
    $model->ids           = $fixtures;
    $model->featured_only = '';

    foreach ( [ 'title-asc', 'title-desc', 'date-asc', 'date-desc', 'price-asc', 'price-desc', 'rand' ] as $sort ) {
        foreach ( [ 'parse_query_args', 'parse_search_query_args' ] as $method ) {
            $_REQUEST       = [ 'sort' => $sort, 'ids' => implode( ',', $fixtures ) ];
            $args           = $model->$method();
            $args['fields'] = 'ids';
            $query          = new WP_Query( $args );
            $ids            = array_map( 'intval', $query->posts );

            if ( 4 !== count( $ids ) || 1 !== substr_count( $query->request, ".meta_key = '_featured'" ) ) {
                throw new RuntimeException( $method . ' ' . $sort . ': missing results or duplicate featured joins' );
            }
            if ( 1 !== (int) get_post_meta( $ids[0], '_featured', true ) || 1 !== (int) get_post_meta( $ids[1], '_featured', true ) ) {
                throw new RuntimeException( $method . ' ' . $sort . ': featured listings must remain first' );
            }
            if ( 'rand' !== $sort ) {
                $ascending = in_array( $sort, [ 'title-asc', 'date-asc', 'price-desc' ], true );
                $expected  = $ascending
                    ? [ $fixtures[1], $fixtures[3], $fixtures[0], $fixtures[2] ]
                    : [ $fixtures[3], $fixtures[1], $fixtures[2], $fixtures[0] ];
                if ( $expected !== $ids ) {
                    throw new RuntimeException( $method . ' ' . $sort . ': secondary ordering changed' );
                }
            }
            ++$checks;
        }
    }

    $_REQUEST             = [ 'ids' => implode( ',', $fixtures ), 'sort' => 'title-asc' ];
    $model->featured_only = 'yes';
    $query                = new WP_Query( $model->parse_search_query_args() );
    if ( 2 !== $query->post_count ) {
        throw new RuntimeException( 'Featured-only filtering changed' );
    }
    ++$checks;
} finally {
    foreach ( $fixtures as $id ) {
        wp_delete_post( $id, true );
    }
    $_REQUEST = $original_request;
}

WP_CLI::success( $checks . ' featured sorting checks passed; fixtures removed.' );
