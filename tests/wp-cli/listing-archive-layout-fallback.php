<?php
/**
 * Smoke test for malformed directory archive card layouts.
 *
 * Run with:
 * wp eval-file tests/wp-cli/listing-archive-layout-fallback.php
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
    exit( "This script must be run with WP-CLI.\n" );
}

$directories = get_terms(
    [
        'taxonomy'   => ATBDP_TYPE,
        'hide_empty' => false,
        'number'     => 1,
        'fields'     => 'ids',
    ]
);

if ( is_wp_error( $directories ) || empty( $directories ) ) {
    WP_CLI::error( 'No directory type is available for the archive-layout smoke test.' );
}

$directory_id = (int) $directories[0];
$meta_key     = 'listings_card_list_view';
$original     = get_term_meta( $directory_id, $meta_key, true );
$grid_before  = get_term_meta( $directory_id, 'listings_card_grid_view', true );

try {
    update_term_meta( $directory_id, $meta_key, [ null ] );
    clean_term_cache( $directory_id, ATBDP_TYPE );

    $fields        = directorist_listing_archive_fields( $directory_id );
    $list_fields   = $fields['list_fields'] ?? [];
    $template      = $list_fields['active_template'] ?? '';
    $template_data = $list_fields['template_data'][ $template ] ?? null;

    if ( empty( $template ) || ! is_array( $template_data ) ) {
        WP_CLI::error( 'Malformed list layout did not fall back to a renderable default.' );
    }

    if ( $grid_before !== $fields['card_fields'] ) {
        WP_CLI::error( 'The valid grid layout changed while recovering the malformed list layout.' );
    }

    WP_CLI::success( 'Malformed list layout falls back to a renderable default.' );
} finally {
    update_term_meta( $directory_id, $meta_key, $original );
    clean_term_cache( $directory_id, ATBDP_TYPE );
}
