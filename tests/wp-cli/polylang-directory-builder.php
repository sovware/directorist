<?php
/**
 * Run on a disposable WordPress site with Polylang, a configured default Directory
 * Type and its French translation: wp eval-file tests/wp-cli/polylang-directory-builder.php
 * Creates temporary terms only and removes them even when an assertion fails.
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || ! function_exists( 'pll_current_language' ) ) {
    return;
}

$original_language = PLL()->curlang;
$default_language  = pll_default_language();
PLL()->curlang     = PLL()->model->get_language( $default_language );
$source_default   = (int) directorist_get_default_directory();
$target_default   = (int) pll_get_term( $source_default, 'fr' );
if ( ! $source_default || ! $target_default || 'fr' === $default_language ) {
    WP_CLI::error( 'Requires a non-French default language and a default Directory Type linked to French.' );
}

$failures = 0;
$checks   = 0;
$created  = [];
$assert   = function( $condition, $label ) use ( &$failures, &$checks ) {
    ++$checks;
    WP_CLI::log( ( $condition ? 'PASS ' : 'FAIL ' ) . $label );
    $failures += $condition ? 0 : 1;
};
$create_term = function( $taxonomy, $language ) use ( &$created ) {
    $result = wp_insert_term( 'Polylang builder regression ' . wp_generate_uuid4(), $taxonomy );
    if ( is_wp_error( $result ) ) {
        throw new RuntimeException( $result->get_error_message() );
    }
    $id        = (int) $result['term_id'];
    $created[] = [ $id, $taxonomy ];
    pll_set_term_language( $id, $language );
    return $id;
};

try {
    PLL()->curlang = PLL()->model->get_language( 'fr' );
    $assert( $target_default === (int) directorist_get_default_directory(), 'French default resolves the linked source default without a copied _default flag' );
    $assert( get_term( $target_default )->slug === directorist_get_default_directory( 'slug' ), 'Default slug and default ID resolve the same French directory' );
    $model = new Directorist\Directorist_Listings( [ 'listings_per_page' => 8 ] );
    $assert( $target_default === $model->current_listing_type, 'All Listings without Directory Type attributes resolves French default' );
    $form = new Directorist\Directorist_Listing_Search_Form( 'search_form' );
    $assert( $target_default === (int) $form->listing_type, 'Standalone Search Form without attributes resolves French default' );
    PLL()->curlang = PLL()->model->get_language( $default_language );
    $assert( $source_default === (int) directorist_get_default_directory(), 'Source default is unchanged after French lookups' );

    $source = $create_term( ATBDP_DIRECTORY_TYPE, $default_language );
    $target = $create_term( ATBDP_DIRECTORY_TYPE, 'fr' );
    pll_save_term_translations( [ $default_language => $source, 'fr' => $target ] );
    $keys = [ 'submission_form_fields', 'search_form_fields', 'single_listing_header', 'single_listings_contents', 'single_listings_shortcodes', 'listings_card_grid_view', 'listings_card_list_view' ];
    foreach ( $keys as $key ) {
        update_term_meta( $source, $key, [ 'regression' => $key, 'nested' => [ 'label' => 'Source layout' ] ] );
    }
    update_term_meta( $source, 'enable_sidebar', false );
    update_term_meta( $source, 'submit_button_label', 'Source submit label' );
    update_term_meta( $source, '_created_date', 'source-only' );
    update_term_meta( $source, '_default', false );
    update_term_meta( $source, 'third_party_setting', 'source-only' );
    $raw_before = $GLOBALS['wpdb']->get_results( $GLOBALS['wpdb']->prepare( "SELECT meta_key, meta_value FROM {$GLOBALS['wpdb']->termmeta} WHERE term_id = %d ORDER BY meta_id", $target ), ARRAY_A );

    foreach ( $keys as $key ) {
        $assert( get_term_meta( $source, $key, true ) === get_term_meta( $target, $key, true ), 'Missing ' . $key . ' inherits the linked source layout as an array' );
        $assert( [ get_term_meta( $source, $key, true ) ] === get_term_meta( $target, $key, false ), $key . ' respects the multiple-value metadata contract' );
    }
    $assert( '' === get_term_meta( $target, 'enable_sidebar', true ), 'False source configuration is returned without changing type semantics' );
    $assert( 'Source submit label' === get_term_meta( $target, 'submit_button_label', true ), 'Scalar builder configuration inherits' );
    $all = get_term_meta( $target );
    $assert( maybe_serialize( get_term_meta( $source, 'search_form_fields', true ) ) === $all['search_form_fields'][0], 'All-meta builder loading preserves serialized storage format' );
    $assert( ! array_intersect( [ '_created_date', '_default', 'third_party_setting' ], array_keys( $all ) ), 'All-meta inheritance excludes identity, default and third-party metadata' );
    $assert( '' === get_term_meta( $target, '_default', true ), 'Source default flag is not inherited' );
    $assert( '' === get_term_meta( $target, 'third_party_setting', true ), 'Unrelated metadata is not inherited' );
    $raw_after = $GLOBALS['wpdb']->get_results( $GLOBALS['wpdb']->prepare( "SELECT meta_key, meta_value FROM {$GLOBALS['wpdb']->termmeta} WHERE term_id = %d ORDER BY meta_id", $target ), ARRAY_A );
    $assert( $raw_before === $raw_after, 'Frontend reads do not write or initialize target metadata' );
    $assert( ! metadata_exists( 'term', $target, 'search_form_fields' ), 'Inherited defaults do not pretend that missing metadata is stored' );
    $source_value = get_term_meta( $source, 'search_form_fields', true );
    update_term_meta( $target, 'search_form_fields', $source_value );
    $assert( metadata_exists( 'term', $target, 'search_form_fields' ), 'Saving an unchanged inherited layout persists an independent French configuration' );

    update_term_meta( $target, 'search_form_fields', [ 'label' => 'Disposition française' ] );
    $assert( [ 'label' => 'Disposition française' ] === get_term_meta( $target, 'search_form_fields', true ), 'Existing French builder layout is authoritative' );
    $assert( [ 'label' => 'Disposition française' ] === maybe_unserialize( get_term_meta( $target )['search_form_fields'][0] ), 'All-meta builder loading preserves existing French values' );
    foreach ( [ [], '', false, 0 ] as $empty ) {
        delete_term_meta( $target, 'search_form_fields' );
        add_term_meta( $target, 'search_form_fields', $empty );
        $expected = is_array( $empty ) ? [] : (string) $empty;
        $assert( $expected === get_term_meta( $target, 'search_form_fields', true ), 'Explicit empty/false/zero French configuration is not replaced: ' . wp_json_encode( $empty ) );
    }
    delete_term_meta( $target, 'search_form_fields' );
    update_term_meta( $source, 'search_form_fields', [ 'label' => 'Updated source layout' ] );
    $assert( [ 'label' => 'Updated source layout' ] === get_term_meta( $target, 'search_form_fields', true ), 'Missing French layout follows source updates without stale cached inheritance' );

    $unlinked = $create_term( ATBDP_DIRECTORY_TYPE, 'fr' );
    $assert( '' === get_term_meta( $unlinked, 'search_form_fields', true ), 'Unlinked directories do not inherit another directory layout' );
    $assert( '' === get_term_meta( $unlinked, 'single_listing_header', true ), 'Missing source values terminate without recursion' );
    $category = $create_term( ATBDP_CATEGORY, 'fr' );
    $assert( '' === get_term_meta( $category, 'search_form_fields', true ), 'Non-directory taxonomy metadata is unaffected' );
    directorist_update_term_directory( $category, [ $source ] );
    $assert( [ $target ] === directorist_get_term_directory( $category ), 'Copied category Directory Type array maps to French' );
    $assert( metadata_exists( 'term', $category, '_directory_type_' . $target ), 'Translated category directory query index is created' );
    $assert( ! metadata_exists( 'term', $category, '_directory_type_' . $source ), 'Late source directory marker cannot leave a stale category index' );
    $location = $create_term( ATBDP_LOCATION, 'fr' );
    update_term_meta( $location, '_directory_type', [ $source ] );
    $assert( [ $target ] === directorist_get_term_directory( $location ), 'Copied location Directory Type array maps to French' );
    update_term_meta( $category, '_directory_type', [ $source, $target, $unlinked ] );
    $assert( [ $target, $unlinked ] === directorist_get_term_directory( $category ), 'Multiple directory associations deduplicate and preserve missing translations' );
    do_action( 'pll_save_term', $category, ATBDP_CATEGORY, [ 'fr' => $category ] );
    $assert( [ $target, $unlinked ] === directorist_get_term_directory( $category ), 'Repeated taxonomy save is idempotent' );

    // A real French default takes priority over the translated global fallback.
    update_term_meta( $target, '_default', true );
    PLL()->curlang = PLL()->model->get_language( 'fr' );
    $assert( $target === (int) directorist_get_default_directory(), 'Explicit language-specific default is preserved' );
    delete_term_meta( $target, '_default' );

    // Simulate the integration being unavailable without deactivating plugins.
    $integration = null;
    foreach ( $GLOBALS['wp_filter']['directorist_default_directory']->callbacks[10] ?? [] as $callback ) {
        if ( is_array( $callback['function'] ) && $callback['function'][0] instanceof Directorist_Multilingual_Polylang ) {
            $integration = $callback['function'][0];
            break;
        }
    }
    if ( $integration ) {
        remove_filter( 'directorist_default_directory', [ $integration, 'translate_default_directory' ], 10 );
        remove_filter( 'default_term_metadata', [ $integration, 'inherit_directory_builder_meta' ], 10 );
        remove_filter( 'get_term_metadata', [ $integration, 'inherit_all_directory_builder_meta' ], 10 );
        try {
            $assert( 0 === (int) directorist_get_default_directory(), 'Without integration a missing French default keeps original zero behavior' );
            $assert( '' === get_term_meta( $target, 'search_form_fields', true ), 'Without integration missing builder metadata keeps original empty behavior' );
        } finally {
            add_filter( 'directorist_default_directory', [ $integration, 'translate_default_directory' ], 10, 2 );
            add_filter( 'default_term_metadata', [ $integration, 'inherit_directory_builder_meta' ], 10, 4 );
            add_filter( 'get_term_metadata', [ $integration, 'inherit_all_directory_builder_meta' ], 10, 4 );
        }
    } else {
        $assert( false, 'Polylang default/builder integration hooks are registered' );
    }
} finally {
    foreach ( array_reverse( $created ) as $term ) {
        wp_delete_term( $term[0], $term[1] );
    }
    PLL()->curlang = $original_language;
}

if ( $failures ) {
    WP_CLI::error( sprintf( '%d of %d assertions failed; temporary terms removed.', $failures, $checks ) );
}
WP_CLI::success( sprintf( '%d assertions passed; temporary terms removed.', $checks ) );
