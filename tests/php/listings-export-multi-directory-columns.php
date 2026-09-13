<?php
/**
 * Regression test for multi-directory listing export columns.
 *
 * Run with: php tests/php/listings-export-multi-directory-columns.php
 *
 * @package Directorist
 */

require dirname( __DIR__, 2 ) . '/includes/classes/class-listings-export.php';

/**
 * Stop the test when an assertion fails.
 *
 * @param bool   $condition Assertion result.
 * @param string $message   Failure message.
 * @return void
 */
function directorist_export_test_assert( $condition, $message ) {
    if ( $condition ) {
        return;
    }

    fwrite( STDERR, "FAIL: {$message}\n" );
    exit( 1 );
}

$rows = [
    [
        'id'            => 101,
        'directory'     => 'directory-one',
        'custom-number' => '5000',
    ],
    [
        'id'              => 202,
        'directory'       => 'directory-two',
        'price'           => '3500',
        'custom-checkbox' => null,
    ],
    [
        'id'                => 303,
        'directory'         => 'directory-three',
        'custom-date'       => '2027-08-27',
        'custom-date-2'     => '2027-08-29',
        'address'           => 'Example City',
        'hide_map'          => '',
        'manual_lat'        => '44.2600',
        'manual_lng'        => '12.3500',
        'custom-checkbox-2' => 'Famiglie',
    ],
];

$expected_columns = [
    'id',
    'directory',
    'custom-number',
    'price',
    'custom-checkbox',
    'custom-date',
    'custom-date-2',
    'address',
    'hide_map',
    'manual_lat',
    'manual_lng',
    'custom-checkbox-2',
];

$justified_rows = Directorist\Listings_Exporter::justifyDataTableRow(
    $rows,
    array_map( 'count', $rows )
);

foreach ( $justified_rows as $row ) {
    directorist_export_test_assert(
        $expected_columns === array_keys( $row ),
        'Every exported row should contain the union of fields from all directories.'
    );
}

directorist_export_test_assert(
    null === $justified_rows[1]['custom-checkbox'],
    'An existing null value should not be replaced by the default empty string.'
);

directorist_export_test_assert(
    '' === $justified_rows[0]['custom-date'],
    'A field unavailable to a directory should be exported as an empty value.'
);

echo "PASS: multi-directory export preserves every directory column.\n";
