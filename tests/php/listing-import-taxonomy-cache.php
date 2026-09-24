<?php
/**
 * Regression test for taxonomy-specific listing import term caching.
 *
 * Run with: php tests/php/listing-import-taxonomy-cache.php
 *
 * @package Directorist
 */

require dirname( __DIR__, 2 ) . '/includes/classes/class-tools.php';

/**
 * Test double that creates deterministic term IDs per taxonomy.
 */
class Directorist_Import_Taxonomy_Cache_Test_Tools extends ATBDP_Tools {
    /**
     * Number of term lookups keyed by taxonomy and term name.
     *
     * @var array
     */
    public $term_lookups = [];

    /**
     * Skip the WordPress hook registrations in the parent constructor.
     */
    public function __construct() {}

    /**
     * Return a deterministic term ID for the requested taxonomy.
     *
     * @param string $term     Term name.
     * @param string $taxonomy Taxonomy name.
     * @return int Term ID.
     */
    public function maybe_create_term( $term, $taxonomy ) {
        $key = $taxonomy . ':' . $term;

        if ( ! isset( $this->term_lookups[ $key ] ) ) {
            $this->term_lookups[ $key ] = 0;
        }

        ++$this->term_lookups[ $key ];

        return 'at_biz_dir-tags' === $taxonomy ? 101 : 202;
    }
}

/**
 * Stop the test when an assertion fails.
 *
 * @param bool   $condition Assertion result.
 * @param string $message   Failure message.
 * @return void
 */
function directorist_import_cache_assert( $condition, $message ) {
    if ( $condition ) {
        return;
    }

    fwrite( STDERR, "FAIL: {$message}\n" );
    exit( 1 );
}

$tools = new Directorist_Import_Taxonomy_Cache_Test_Tools();
$cache = [];
$term  = 'Power Wheelchairs';

$tag_id      = $tools->get_cached_term_id( $term, 'at_biz_dir-tags', $cache );
$category_id = $tools->get_cached_term_id( $term, 'at_biz_dir-category', $cache );
$cached_tag  = $tools->get_cached_term_id( $term, 'at_biz_dir-tags', $cache );

directorist_import_cache_assert( 101 === $tag_id, 'The tag term should use the tag taxonomy ID.' );
directorist_import_cache_assert( 202 === $category_id, 'The category term should use the category taxonomy ID.' );
directorist_import_cache_assert( $tag_id !== $category_id, 'Matching names in different taxonomies must not share a cached ID.' );
directorist_import_cache_assert( $tag_id === $cached_tag, 'Repeated terms in one taxonomy should use the cached ID.' );
directorist_import_cache_assert( 1 === $tools->term_lookups[ 'at_biz_dir-tags:' . $term ], 'The tag term should be resolved once.' );
directorist_import_cache_assert( 1 === $tools->term_lookups[ 'at_biz_dir-category:' . $term ], 'The category term should be resolved once.' );

echo "PASS: listing import term IDs are cached per taxonomy.\n";
