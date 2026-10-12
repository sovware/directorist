<?php
/**
 * Offline regression coverage for native purchased-product identity and theme matching.
 * Run: php tests/php/purchased-product-identity.php
 *
 * @package Directorist
 */

define( 'ABSPATH', __DIR__ );

function is_admin() {
    return true;
}

function get_current_user_id() {
    return 1;
}

function get_user_meta( $user_id, $key, $single = true ) {
    return $GLOBALS['identity_meta'][ $key ] ?? '';
}

function apply_filters( $tag, $value ) {
    return isset( $GLOBALS['identity_filters'][ $tag ] ) ? $GLOBALS['identity_filters'][ $tag ]( $value ) : $value;
}

function admin_url( $path ) {
    return 'https://example.test/wp-admin/' . $path;
}

function get_theme_update_available( $theme ) {
    return '';
}

function get_site_transient( $key ) {
    return $GLOBALS['identity_updates'];
}

function get_option( $key ) {
    return $GLOBALS['identity_active'];
}

function wp_get_theme() {
    return $GLOBALS['identity_themes'][ $GLOBALS['identity_active'] ];
}

function wp_get_themes() {
    return $GLOBALS['identity_themes'];
}

function sanitize_text_field( $value ) {
    return $value;
}

function home_url() {
    return 'https://example.test';
}

function __( $text, $domain = '' ) {
    return $text;
}

function wp_remote_get( $url, $args ) {
    $GLOBALS['identity_requests'][] = $args['body'];
    return [ 'body' => json_encode( [ 'success' => true ] ) ];
}

function update_user_meta( $id, $key, $value ) {
    $GLOBALS['identity_meta'][ $key ] = $value;
}

require dirname( __DIR__, 2 ) . '/includes/classes/class-extension.php';

class Directorist_Identity_Test_Theme {
    public $stylesheet;

    public $name;

    public $version = '1.0.0';

    public function __construct( $slug ) {
        $this->stylesheet = $slug;
        $this->name = $slug;
    }

    public function get_screenshot() {
        return '';
    }
}

$checks = 0;

function identity_assert( $condition, $message ) {
    if ( ! $condition ) {
        fwrite( STDERR, "FAIL: {$message}\n" );
        exit( 1 );
    }
    ++$GLOBALS['checks'];
}

$core    = ( new ReflectionClass( 'ATBDP_Extensions' ) )->newInstanceWithoutConstructor();
$private = [ 'slug' => 'dtour', 'item_id' => 368051, 'permalink' => 'https://directorist.com/?post_type=download&p=368051', 'license' => 'fixture-license', 'download_link' => 'https://example.test/dtour.zip' ];
$legacy  = [ 'item_id' => 66670, 'permalink' => 'https://directorist.com/product/onelisting-pro/', 'license' => 'fixture-onelisting', 'skip_licensing' => true ];
$prepared = $core->prepare_available_in_subscriptions( [ $private, $legacy ] );
identity_assert( isset( $prepared['dtour'] ), 'Private product must use the API slug, not its query permalink.' );
identity_assert( $prepared['dtour'] === $private, 'Identity normalization must preserve the complete license/download record.' );
identity_assert( $prepared['onelisting-pro'] === $legacy, 'OneListing legacy permalink and licensing flags must be preserved.' );
foreach ( [ 'http://directorist.com/product/onelisting-pro/', 'https://directorist.com/product/onelisting-pro/' ] as $url ) {
    $item = array_merge( $legacy, [ 'permalink' => $url ] );
    identity_assert( isset( $core->prepare_available_in_subscriptions( [ $item ] )['onelisting-pro'] ), 'Legacy HTTP and HTTPS permalinks remain supported.' );
}
foreach ( [ '', '../dtour', 'https://example.test/dtour', [ 'dtour' ] ] as $bad_slug ) {
    $item = array_merge( $legacy, [ 'slug' => $bad_slug ] );
    identity_assert( isset( $core->prepare_available_in_subscriptions( [ $item ] )['onelisting-pro'] ), 'Invalid slug must retain the legacy permalink fallback.' );
}
identity_assert( [] === $core->prepare_available_in_subscriptions( [] ), 'Empty response remains empty.' );
identity_assert( [] === $core->prepare_available_in_subscriptions( [ null, 'invalid', [] ] ), 'Malformed rows are ignored without notices.' );
identity_assert( isset( $core->prepare_available_in_subscriptions( [ array_merge( $private, [ 'slug' => 'private--theme_v2' ] ) ] )['private--theme_v2'] ), 'Safe repeated hyphens and underscores remain valid product slugs.' );

$bad_key = 'https:directorist.com?post_type=download&p=368051';
$GLOBALS['identity_meta'] = [
    '_atbdp_has_subscriptions_sassion' => true,
    '_atbdp_subscription_connection_method' => 'account',
    '_themes_available_in_subscriptions' => [ $bad_key => $private, 'onelisting-pro' => $legacy ],
    '_plugins_available_in_subscriptions' => [ 'legacy-private-key' => array_merge( $private, [ 'slug' => 'directorist-booking' ] ) ],
];
$original_meta = $GLOBALS['identity_meta'];
identity_assert( ATBDP_Extensions::get_purchased_theme_list() === $prepared, 'Existing cached themes normalize without reconnecting.' );
identity_assert( isset( ATBDP_Extensions::get_purchased_extension_list()['directorist-booking'] ), 'Private extension caches normalize too.' );
$GLOBALS['identity_filters']['directorist_purchased_theme_list'] = function( $items ) use ( $private ) {
    identity_assert( isset( $items['dtour'] ), 'Purchase filters must receive normalized existing records.' );
    $items['another-legacy-key'] = $private;
    return $items;
};
identity_assert( ATBDP_Extensions::get_purchased_theme_list() === $prepared, 'Filter records normalize and duplicate aliases collapse.' );
unset( $GLOBALS['identity_filters']['directorist_purchased_theme_list'] );
identity_assert( $original_meta === $GLOBALS['identity_meta'], 'Reading purchases must not write account metadata.' );
$GLOBALS['identity_meta']['_themes_available_in_subscriptions'] = [ 'custom-theme' => [ 'title' => 'Custom theme' ] ];
identity_assert( isset( ATBDP_Extensions::get_purchased_theme_list()['custom-theme'] ), 'Keyed legacy/filter records without a slug or permalink survive.' );
$GLOBALS['identity_meta'] = $original_meta;

$core->themes = [ 'dtour' => [ 'active' => true ], 'onelisting-pro' => [ 'active' => true ] ];
$GLOBALS['identity_themes'] = [ 'dtour' => new Directorist_Identity_Test_Theme( 'dtour' ), 'onelisting-pro' => new Directorist_Identity_Test_Theme( 'onelisting-pro' ) ];
$GLOBALS['identity_updates'] = (object) [ 'response' => [ 'onelisting-pro' => [ 'new_version' => '2.0.26', 'package' => 'https://example.test/onelisting.zip' ] ] ];
foreach ( [ 'dtour', 'onelisting-pro' ] as $active ) {
    $GLOBALS['identity_active'] = $active;
    $overview = $core->get_themes_overview();
    identity_assert( ! isset( $overview['themes_available_in_subscriptions'][ $active ] ), "Active {$active} is excluded from the subscription rows." );
    identity_assert( count( $overview['themes_available_in_subscriptions'] ) === 1, 'Only the other installed theme remains in subscription rows.' );
    identity_assert( [] === $overview['themes_promo_list'], 'Installed themes do not produce duplicate marketplace rows.' );
    $other = 'dtour' === $active ? 'onelisting-pro' : 'dtour';
    identity_assert( $overview['themes_available_in_subscriptions'][ $other ]['is_installed'], 'Inactive purchased theme is recognized as installed.' );
    identity_assert( '2.0.26' === $overview['installed_theme_list']['onelisting-pro']['new_version'], 'OneListing update version is preserved.' );
    identity_assert( 'fixture-onelisting' === ATBDP_Extensions::get_purchased_theme_list()['onelisting-pro']['license'], 'OneListing license remains unchanged.' );
}
foreach ( [ 'dtour' => $private, 'onelisting-pro' => $legacy ] as $slug => $record ) {
    $result = $core->activate_license( $record, 'theme' );
    identity_assert( $result['success'], 'Fixture activation retains the native result.' );
    identity_assert( $record === $result['purchased_products']['theme'][ $slug ], 'Activation stores the product under its canonical identity.' );
    $request = end( $GLOBALS['identity_requests'] );
    identity_assert( $record['license'] === $request['license'] && $record['item_id'] === $request['item_id'], 'Native activation sends the original license and item ID.' );
}
$GLOBALS['identity_meta']['_atbdp_has_subscriptions_sassion'] = false;
identity_assert( [] === ATBDP_Extensions::get_purchased_theme_list(), 'Disconnected account must not expose cached themes.' );
identity_assert( [] === ATBDP_Extensions::get_purchased_extension_list(), 'Disconnected account must not expose cached extensions.' );
$GLOBALS['identity_meta']['_atbdp_has_subscriptions_sassion'] = true;
$GLOBALS['identity_meta']['_atbdp_subscription_connection_method'] = 'access_key';
identity_assert( [] === ATBDP_Extensions::get_purchased_theme_list(), 'Legacy access-key connection remains rejected.' );
fwrite( STDOUT, "PASS: {$checks} purchased-product identity checks; HTTP and metadata writes use in-memory test doubles.\n" );
