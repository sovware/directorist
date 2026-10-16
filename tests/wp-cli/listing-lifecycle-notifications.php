<?php
/**
 * Regression checks for listing expiry transitions and renewal reminder timing.
 *
 * Run on a disposable WordPress site with:
 * wp --user=<administrator> eval-file wp-content/plugins/directorist/tests/wp-cli/listing-lifecycle-notifications.php
 *
 * @package Directorist
 */

defined( 'ABSPATH' ) || exit;

if ( ! current_user_can( 'manage_options' ) ) {
    WP_CLI::error( 'Run these checks as an administrator.' );
}

$fixtures    = [];
$transitions = [];
$reminders   = [];
$results     = [];
$timezone    = 'America/New_York';
$delay       = 3;
$scoped      = false;

$capture_mail = static function () {
    return true;
};
$set_timezone = static function () use ( &$timezone ) {
    return $timezone;
};
$set_options = static function ( $value, $key ) use ( &$delay ) {
    return 'email_renewal_day' === $key ? $delay : $value;
};
$scope_query = static function ( $query ) use ( &$fixtures, &$scoped ) {
    if ( $scoped && ATBDP_POST_TYPE === $query->get( 'post_type' ) ) {
        $query->set( 'post__in', $fixtures ?: [ 0 ] );
    }
};
$capture_transition = static function ( $new_status, $old_status, $post ) use ( &$fixtures, &$transitions ) {
    if ( in_array( $post->ID, $fixtures, true ) && $new_status !== $old_status ) {
        $transitions[] = [ $post->ID, $old_status, $new_status ];
    }
};
$capture_reminder = static function ( $listing_id ) use ( &$reminders ) {
    $reminders[] = $listing_id;
};
$make_listing = static function ( $status ) use ( &$fixtures ) {
    $id = wp_insert_post(
        [
            'post_type'   => ATBDP_POST_TYPE,
            'post_title'  => 'Directorist lifecycle regression fixture',
            'post_status' => $status,
            'post_author' => get_current_user_id(),
        ],
        true
    );
    if ( is_wp_error( $id ) ) {
        throw new RuntimeException( $id->get_error_message() );
    }
    $fixtures[] = $id;
    return $id;
};

add_filter( 'pre_wp_mail', $capture_mail, PHP_INT_MIN );
add_filter( 'pre_option_timezone_string', $set_timezone );
add_filter( 'directorist_option', $set_options, 10, 2 );
add_action( 'pre_get_posts', $scope_query );
add_action( 'transition_post_status', $capture_transition, 1, 3 );
add_action( 'atbdp_send_renewal_reminder', $capture_reminder, 1 );

try {
    $cron = null;
    foreach ( $GLOBALS['wp_filter']['directorist_hourly_scheduled_events']->callbacks as $callbacks ) {
        foreach ( $callbacks as $callback ) {
            if ( is_array( $callback['function'] ) && $callback['function'][0] instanceof ATBDP_Cron ) {
                $cron = $callback['function'][0];
            }
        }
    }
    if ( ! $cron ) {
        throw new RuntimeException( 'Directorist listing lifecycle worker is unavailable.' );
    }
    $expire = new ReflectionMethod( $cron, 'update_expired_status' );
    $expire->setAccessible( true );
    $remind = new ReflectionMethod( $cron, 'send_renewal_reminders' );
    $remind->setAccessible( true );

    $id = $make_listing( 'publish' );
    update_post_meta( $id, '_listing_status', 'post_status' );
    update_post_meta( $id, '_expiry_date', date( 'Y-m-d H:i:s', current_time( 'timestamp' ) - HOUR_IN_SECONDS ) );
    delete_post_meta( $id, '_never_expire' );
    get_post( $id ); // Prime the cache before wp_update_post() saves the status and metadata together.
    $scoped      = true;
    $transitions = [];
    $expire->invoke( $cron );
    $results['expiry emits one publish-to-expired transition'] = [ [ $id, 'publish', 'expired' ] ] === $transitions;
    $results['expiry keeps legacy status metadata in sync']    = 'expired' === get_post_meta( $id, '_listing_status', true );
    $transitions = [];
    $expire->invoke( $cron );
    $results['repeated expiry checks do not repeat the transition'] = [] === $transitions;

    update_post_meta( $id, '_listing_status', 'renewal' );
    $results['metadata-only renewal still publishes the listing'] = 'publish' === get_post_status( $id );
    $transitions = [];
    update_post_meta( $id, '_listing_status', 'expired' );
    $results['metadata-only expiry still changes status once'] = [ [ $id, 'publish', 'expired' ] ] === $transitions;
    update_post_meta( $id, '_renewal_reminder_sent', 1 );

    foreach ( [ 'America/New_York', 'UTC' ] as $timezone ) {
        $early = $make_listing( 'expired' );
        $due   = $make_listing( 'expired' );
        $bad   = $make_listing( 'expired' );
        foreach ( [ $early, $due, $bad ] as $listing_id ) {
            update_post_meta( $listing_id, '_renewal_reminder_sent', 0 );
            delete_post_meta( $listing_id, '_never_expire' );
        }
        update_post_meta( $early, '_expiry_date', date( 'Y-m-d H:i:s', current_time( 'timestamp' ) - HOUR_IN_SECONDS ) );
        update_post_meta( $due, '_expiry_date', date( 'Y-m-d H:i:s', current_time( 'timestamp' ) - 3 * DAY_IN_SECONDS - MINUTE_IN_SECONDS ) );
        update_post_meta( $bad, '_expiry_date', 'invalid-date' );
        $reminders = [];
        $remind->invoke( $cron );
        $results[ $timezone . ': reminder waits three days, then sends once' ] = [ $due ] === $reminders;
        $results[ $timezone . ': early and invalid dates remain unsent' ]     = '0' === get_post_meta( $early, '_renewal_reminder_sent', true ) && '0' === get_post_meta( $bad, '_renewal_reminder_sent', true );
        $reminders = [];
        $remind->invoke( $cron );
        $results[ $timezone . ': later checks do not repeat a reminder' ] = [] === $reminders;
        update_post_meta( $early, '_renewal_reminder_sent', 1 );
        update_post_meta( $bad, '_renewal_reminder_sent', 1 );
    }
} finally {
    $scoped = false;
    remove_action( 'pre_get_posts', $scope_query );
    remove_action( 'transition_post_status', $capture_transition, 1 );
    remove_action( 'atbdp_send_renewal_reminder', $capture_reminder, 1 );
    remove_filter( 'pre_option_timezone_string', $set_timezone );
    remove_filter( 'directorist_option', $set_options, 10 );
    foreach ( $fixtures as $listing_id ) {
        wp_delete_post( $listing_id, true );
    }
    remove_filter( 'pre_wp_mail', $capture_mail, PHP_INT_MIN );
}

foreach ( $results as $message => $passed ) {
    WP_CLI::log( ( $passed ? 'PASS: ' : 'FAIL: ' ) . $message );
}
if ( in_array( false, $results, true ) ) {
    WP_CLI::error( 'Listing lifecycle regression checks failed.' );
}
WP_CLI::success( count( $results ) . ' listing lifecycle regression checks passed.' );
