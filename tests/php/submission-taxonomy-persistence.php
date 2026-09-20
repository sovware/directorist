<?php
/**
 * Regression test for front-end submission taxonomy persistence.
 *
 * Run with: php tests/php/submission-taxonomy-persistence.php
 *
 * @package Directorist
 */

define( 'ABSPATH', dirname( __DIR__, 2 ) . '/' );
define( 'ATBDP_LOCATION', 'at_biz_dir-location' );
define( 'ATBDP_CATEGORY', 'at_biz_dir-category' );
define( 'ATBDP_TAGS', 'at_biz_dir-tags' );

/**
 * Stores calls made to the WordPress taxonomy API.
 */
class Directorist_Submission_Taxonomy_Test_Data {
    /**
     * Recorded wp_set_object_terms() calls.
     *
     * @var array
     */
    public static $calls = array();
}

/**
 * Minimal wp_set_object_terms() stub.
 *
 * @param int    $object_id Object ID.
 * @param array  $terms     Term IDs.
 * @param string $taxonomy  Taxonomy name.
 * @return array
 */
function wp_set_object_terms( $object_id, $terms, $taxonomy ) {
    Directorist_Submission_Taxonomy_Test_Data::$calls[] = array(
        'object_id' => $object_id,
        'terms'     => $terms,
        'taxonomy'  => $taxonomy,
    );

    return $terms;
}

require ABSPATH . 'includes/classes/class-submission-controller.php';

/**
 * Stop the test when an assertion fails.
 *
 * @param bool   $condition Assertion result.
 * @param string $message   Failure message.
 * @return void
 */
function directorist_submission_test_assert( $condition, $message ) {
    if ( $condition ) {
        return;
    }

    fwrite( STDERR, "FAIL: {$message}\n" );
    exit( 1 );
}

$method = new ReflectionMethod( 'Directorist\\AddListingForm\\SubmissionController', 'set_listing_taxonomy' );
$method->invoke(
    null,
    42,
    array(
        ATBDP_CATEGORY => array( '7', 9 ),
        ATBDP_LOCATION => array(),
    )
);

directorist_submission_test_assert(
    array(
        array(
            'object_id' => 42,
            'terms'     => array(),
            'taxonomy'  => ATBDP_LOCATION,
        ),
        array(
            'object_id' => 42,
            'terms'     => array( 7, 9 ),
            'taxonomy'  => ATBDP_CATEGORY,
        ),
    ) === Directorist_Submission_Taxonomy_Test_Data::$calls,
    'Submitted term IDs should be assigned, empty submitted taxonomies should be cleared, and absent taxonomies should remain unchanged.'
);

echo "PASS: submitted listing taxonomies are persisted explicitly.\n";
