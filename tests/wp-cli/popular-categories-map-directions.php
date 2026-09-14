<?php
/**
 * Regression checks for legacy directory membership and zero coordinates.
 * Run on a disposable WordPress site with Directorist active:
 * wp eval-file wp-content/plugins/directorist/tests/wp-cli/popular-categories-map-directions.php
 *
 * @package Directorist
 */

defined( 'ABSPATH' ) || exit;

$terms    = [];
$posts    = [];
$failures = [];
$results  = [];
$check    = static function ( $condition, $message ) use ( &$failures, &$results ) {
    $results[ $message ] = (bool) $condition;
    if ( ! $condition ) {
        $failures[] = $message;
    }
};

try {
    $directory = wp_insert_term( 'Regression ' . wp_generate_uuid4(), 'atbdp_listing_types' );
    if ( is_wp_error( $directory ) ) {
        throw new RuntimeException( $directory->get_error_message() );
    }
    $directory_id = (int) $directory['term_id'];
    $fixtures     = [
        'Legacy integer' => [ [ $directory_id ], 3 ],
        'Legacy string'  => [ [ (string) $directory_id ], 4 ],
        'Array index'    => [ [ $directory_id => $directory_id + 1000 ], 6 ],
        'Other ID'       => [ [ $directory_id + 1000 ], 5 ],
        'Mixed list'     => [ [ 999999, '888888', $directory_id ], 2 ],
        'Marker'         => [ [], 1 ],
    ];
    foreach ( $fixtures as $label => $fixture ) {
        $term = wp_insert_term( $label . ' ' . wp_generate_uuid4(), ATBDP_CATEGORY );
        if ( is_wp_error( $term ) ) {
            throw new RuntimeException( $term->get_error_message() );
        }
        $term_id         = (int) $term['term_id'];
        $terms[ $label ] = $term_id;
        update_term_meta( $term_id, '_directory_type', $fixture[0] );
        if ( 'Marker' === $label ) {
            update_term_meta( $term_id, '_directory_type_' . $directory_id, true );
        }
        for ( $index = 0; $index < $fixture[1]; $index++ ) {
            $post_id = wp_insert_post( [ 'post_type' => ATBDP_POST_TYPE, 'post_status' => 'publish', 'post_title' => 'Directory regression fixture' ], true );
            if ( is_wp_error( $post_id ) ) {
                throw new RuntimeException( $post_id->get_error_message() );
            }
            $posts[] = $post_id;
            wp_set_object_terms( $post_id, [ $term_id ], ATBDP_CATEGORY );
        }
    }
    $search                  = ( new ReflectionClass( Directorist\Directorist_Listing_Search_Form::class ) )->newInstanceWithoutConstructor();
    $search->listing_type    = $directory_id;
    $search->popular_cat_num = 20;
    $actual                  = array_map( 'intval', wp_list_pluck( $search->top_categories(), 'term_id' ) );
    foreach ( [ 'Legacy integer', 'Legacy string', 'Mixed list', 'Marker' ] as $label ) {
        $check( in_array( $terms[ $label ], $actual, true ), $label . ' membership included' );
    }
    foreach ( [ 'Array index', 'Other ID' ] as $label ) {
        $check( ! in_array( $terms[ $label ], $actual, true ), $label . ' excluded' );
    }
    $search->popular_cat_num = 2;
    $limited                 = array_map( 'intval', wp_list_pluck( $search->top_categories(), 'term_id' ) );
    $check( [ $terms['Legacy string'], $terms['Legacy integer'] ] === $limited, 'Filtering precedes count ordering and limit' );

    foreach ( [ 'google-map', 'openstreet-map' ] as $provider ) {
        foreach ( [
            [ 0, '32.5', '', true, '0,32.5' ],
            [ '23.5', '0', '', true, '23.5,0' ],
            [ '0', 0, '', true, '0,0' ],
            [ '0', '32.5', 'Fallback address', true, '0,32.5' ],
            [ '23.5', '32.5', '', true, '23.5,32.5' ],
            [ '', '32.5', 'Fallback address', true, 'Fallback address' ],
            [ '', '', '', true, null ],
            [ 'invalid', '32.5', '', true, null ],
            [ 0, '32.5', '', false, null ],
        ] as $index => $case ) {
            $ls_data               = [ 'manual_lat' => $case[0], 'manual_lng' => $case[1], 'address' => $case[2], 'phone' => '', 'cat_icon' => '', 'post_id' => 0 ];
            $display_direction_map = $case[3];
            $map_is_disabled       = false;
            ob_start();
            include ATBDP_DIR . 'templates/archive/fields/' . $provider . '.php';
            $html = ob_get_clean();
            preg_match( '/href=[\'\"]([^\'\"]*maps\/dir\/[^\'\"]*)/', $html, $matches );
            $query = [];
            if ( ! empty( $matches[1] ) ) {
                parse_str( wp_parse_url( html_entity_decode( $matches[1] ), PHP_URL_QUERY ), $query );
            }
            $check( $case[4] === ( $query['destination'] ?? null ), $provider . ' coordinate case ' . $index );
        }
    }
} finally {
    foreach ( $posts as $post_id ) {
        wp_delete_post( $post_id, true );
    }
    foreach ( $terms as $term_id ) {
        wp_delete_term( $term_id, ATBDP_CATEGORY );
    }
    if ( ! empty( $directory_id ) ) {
        wp_delete_term( $directory_id, 'atbdp_listing_types' );
    }
}

foreach ( $results as $message => $passed ) {
    WP_CLI::log( ( $passed ? 'PASS: ' : 'FAIL: ' ) . $message );
}
if ( $failures ) {
    WP_CLI::error( count( $failures ) . ' regression checks failed.' );
}
WP_CLI::success( count( $results ) . ' regression checks passed.' );
