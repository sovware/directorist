<?php
/**
 * Run inside WP-CLI after applying the mapping fix:
 *   wp eval-file repair-polylang-directory-types.php             # dry run
 *   wp eval-file repair-polylang-directory-types.php apply       # repair
 * Back up the database before apply. Processes listings and taxonomy terms in batches of 200.
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || ! function_exists( 'pll_get_post_language' ) ) {
    return;
}
$integration = null;
foreach ( $GLOBALS['wp_filter']['pll_save_post']->callbacks[30] ?? [] as $callback ) {
    if ( is_array( $callback['function'] ) && $callback['function'][0] instanceof Directorist_Multilingual_Polylang ) {
        $integration = $callback['function'][0];
        break;
    }
}
if ( ! $integration || ! method_exists( $integration, 'sync_taxonomy_directory' ) ) {
    WP_CLI::error( 'Apply the Directorist Polylang Directory Type mapping fix first.' );
}
$apply = isset( $args[0] ) && 'apply' === $args[0];
$count = 0;
$page = 1;
do {
    $ids = get_posts( [ 'post_type' => ATBDP_POST_TYPE, 'post_status' => 'any', 'fields' => 'ids', 'posts_per_page' => 200, 'paged' => $page++, 'orderby' => 'ID', 'order' => 'ASC', 'lang' => '' ] );
    foreach ( $ids as $id ) {
        $lang = pll_get_post_language( $id );
        $old = get_post_meta( $id, '_directory_type', true );
        $mapped = $integration->translate_directory_type( $old, $lang );
        if ( ! $lang || ! is_numeric( $mapped ) || pll_get_term_language( (int) $mapped ) !== $lang ) {
            continue; }
        $terms = wp_get_object_terms( $id, ATBDP_DIRECTORY_TYPE, [ 'fields' => 'ids' ] );
        if ( is_wp_error( $terms ) || ( (int) $old === (int) $mapped && [ (int) $mapped ] === $terms ) ) {
            continue; }
        WP_CLI::log( sprintf( '%s listing %d (%s): meta %s -> %d; taxonomy %s -> [%d]', $apply ? 'Repair' : 'Would repair', $id, $lang, $old, $mapped, wp_json_encode( $terms ), $mapped ) );
        if ( $apply ) {
            $integration->sync_listing_directory( $id );
            $saved_terms = wp_get_object_terms( $id, ATBDP_DIRECTORY_TYPE, [ 'fields' => 'ids' ] );
            if ( (int) get_post_meta( $id, '_directory_type', true ) !== (int) $mapped || $saved_terms !== [ (int) $mapped ] ) {
                WP_CLI::error( 'Repair verification failed for listing ' . $id );
            }
        }
        $count++;
    }
} while ( count( $ids ) === 200 );
WP_CLI::success( sprintf( '%d listings %s.', $count, $apply ? 'repaired' : 'would be repaired (dry run)' ) );

$count = 0;
$offset = 0;
do {
    $terms = get_terms( [ 'taxonomy' => [ ATBDP_CATEGORY, ATBDP_LOCATION ], 'hide_empty' => false, 'lang' => '', 'number' => 200, 'offset' => $offset, 'orderby' => 'term_id', 'order' => 'ASC' ] );
    if ( is_wp_error( $terms ) ) {
        WP_CLI::error( $terms->get_error_message() );
    }
    foreach ( $terms as $term ) {
        $old = directorist_get_term_directory( $term->term_id );
        $mapped = $integration->translate_taxonomy_directories( $term->term_id );
        if ( $old === $mapped ) {
            continue;
        }
        WP_CLI::log( sprintf( '%s term %d (%s): directories %s -> %s', $apply ? 'Repair' : 'Would repair', $term->term_id, $term->taxonomy, wp_json_encode( $old ), wp_json_encode( $mapped ) ) );
        if ( $apply ) {
            $integration->sync_taxonomy_directory( $term->term_id );
            if ( directorist_get_term_directory( $term->term_id ) !== $mapped ) {
                WP_CLI::error( 'Repair verification failed for term ' . $term->term_id );
            }
        }
        ++$count;
    }
    $offset += 200;
} while ( count( $terms ) === 200 );
WP_CLI::success( sprintf( '%d taxonomy terms %s.', $count, $apply ? 'repaired' : 'would be repaired (dry run)' ) );
