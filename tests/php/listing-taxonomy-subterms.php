<?php
/**
 * Regression test for taxonomy list subterm depth handling.
 *
 * Run with: php tests/php/listing-taxonomy-subterms.php
 *
 * @package Directorist
 */

define( 'ABSPATH', dirname( __DIR__, 2 ) . '/' );
define( 'ATBDP_LOCATION', 'at_biz_dir-location' );

/**
 * Minimal permalink stub used by the taxonomy model.
 */
class ATBDP_Permalink {
    /**
     * Return a location permalink.
     *
     * @param object $term           Location term.
     * @param string $directory_type Directory type.
     * @return string
     */
    public static function atbdp_get_location_page( $term, $directory_type = '' ) {
        unset( $directory_type );

        return '/location/' . $term->term_id;
    }

    /**
     * Return a category permalink.
     *
     * @param object $term           Category term.
     * @param string $directory_type Directory type.
     * @return string
     */
    public static function atbdp_get_category_page( $term, $directory_type = '' ) {
        unset( $directory_type );

        return '/category/' . $term->term_id;
    }
}

/**
 * Stores the taxonomy fixtures used by the WordPress function stubs.
 */
class Directorist_Taxonomy_Test_Data {
    /**
     * Child terms keyed by parent ID.
     *
     * @var array
     */
    public static $children = [];
}

/**
 * Minimal get_terms() stub.
 *
 * @param array $args Query arguments.
 * @return array
 */
function get_terms( $args ) {
    return isset( Directorist_Taxonomy_Test_Data::$children[ $args['parent'] ] )
        ? Directorist_Taxonomy_Test_Data::$children[ $args['parent'] ]
        : [];
}

/**
 * Minimal get_term_children() stub.
 *
 * @param int    $term_id  Parent term ID.
 * @param string $taxonomy Taxonomy name.
 * @return array
 */
function get_term_children( $term_id, $taxonomy ) {
    unset( $taxonomy );

    if ( empty( Directorist_Taxonomy_Test_Data::$children[ $term_id ] ) ) {
        return [];
    }

    return array_map(
        function( $term ) {
            return $term->term_id;
        },
        Directorist_Taxonomy_Test_Data::$children[ $term_id ]
    );
}

require ABSPATH . 'includes/model/ListingTaxonomy.php';

/**
 * Create a term fixture.
 *
 * @param int    $term_id Term ID.
 * @param string $name    Term name.
 * @return object
 */
function directorist_test_term( $term_id, $name ) {
    return (object) [
        'term_id' => $term_id,
        'name'    => $name,
    ];
}

/**
 * Create a taxonomy model without loading WordPress.
 *
 * @param int $depth Maximum subterm depth.
 * @return Directorist\Directorist_Listing_Taxonomy
 */
function directorist_test_taxonomy( $depth ) {
    $reflection = new ReflectionClass( 'Directorist\\Directorist_Listing_Taxonomy' );
    $taxonomy   = $reflection->newInstanceWithoutConstructor();

    $taxonomy->depth                = $depth;
    $taxonomy->tax                  = ATBDP_LOCATION;
    $taxonomy->orderby              = 'id';
    $taxonomy->order                = 'asc';
    $taxonomy->hide_empty           = false;
    $taxonomy->show_count           = false;
    $taxonomy->type                 = 'location';
    $taxonomy->directory_type       = [];
    $taxonomy->directory_type_count = 0;

    return $taxonomy;
}

/**
 * Stop the test when an assertion fails.
 *
 * @param bool   $condition Assertion result.
 * @param string $message   Failure message.
 * @return void
 */
function directorist_test_assert( $condition, $message ) {
    if ( $condition ) {
        return;
    }

    fwrite( STDERR, "FAIL: {$message}\n" );
    exit( 1 );
}

Directorist_Taxonomy_Test_Data::$children = [
    1 => [ directorist_test_term( 101, 'USA child' ) ],
    2 => [ directorist_test_term( 201, 'South Africa child' ) ],
    3 => [ directorist_test_term( 301, 'Australia child' ) ],
    4 => [ directorist_test_term( 401, 'Africa child' ) ],
];

$taxonomy = directorist_test_taxonomy( 2 );
$roots    = [
    directorist_test_term( 1, 'USA' ),
    directorist_test_term( 2, 'South Africa' ),
    directorist_test_term( 3, 'Australia' ),
    directorist_test_term( 4, 'Africa' ),
];

foreach ( $roots as $root ) {
    $html = $taxonomy->subterms_html( $root );

    directorist_test_assert(
        false !== strpos( $html, $root->name . ' child' ),
        "{$root->name} should render its child list."
    );
}

directorist_test_assert( 2 === $taxonomy->depth, 'Rendering one root must not reduce the depth available to another root.' );

Directorist_Taxonomy_Test_Data::$children = [
    10 => [ directorist_test_term( 11, 'Level 1' ) ],
    11 => [ directorist_test_term( 12, 'Level 2' ) ],
    12 => [ directorist_test_term( 13, 'Level 3' ) ],
];

$taxonomy = directorist_test_taxonomy( 2 );
$html     = $taxonomy->subterms_html( directorist_test_term( 10, 'Root' ) );

directorist_test_assert( false !== strpos( $html, 'Level 1' ), 'The first configured subterm level should render.' );
directorist_test_assert( false !== strpos( $html, 'Level 2' ), 'The second configured subterm level should render.' );
directorist_test_assert( false === strpos( $html, 'Level 3' ), 'Terms deeper than the configured depth should not render.' );

echo "PASS: taxonomy subterm depth is isolated per root.\n";
