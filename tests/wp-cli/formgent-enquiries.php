<?php
/**
 * WP-CLI smoke coverage for the Directorist Formgent enquiry views.
 *
 * Run with: wp eval-file wp-content/plugins/directorist/tests/wp-cli/formgent-enquiries.php
 *
 * @package Directorist
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'formgent_response_repository' ) ) {
    WP_CLI::error( 'Formgent must be active.' );
}

global $wpdb;

$class           = $GLOBALS['directorist_formgent_test_class'] ?? 'ATBDP_Formgent';
$api             = new $class();
$responses_table = $wpdb->prefix . 'formgent_responses';
$meta_table      = $wpdb->prefix . 'formgent_response_meta';
$users           = [];
$posts           = [];
$responses       = [];
$suffix          = substr( str_replace( '-', '', wp_generate_uuid4() ), 0, 12 );

$assert = function( $condition, $message ) {
    if ( ! $condition ) {
        throw new RuntimeException( $message );
    }
};

$request = function( $box = null, $extra = [] ) {
    $rest_request = new WP_REST_Request( 'GET' );
    if ( null !== $box ) {
        $rest_request->set_param( 'box', $box );
    }
    foreach ( $extra as $key => $value ) {
        $rest_request->set_param( $key, $value );
    }
    return $rest_request;
};

try {
    foreach ( [ 'owner', 'sender', 'outsider' ] as $role ) {
        $user_id = wp_create_user( 'directorist_enquiry_' . $role . '_' . $suffix, wp_generate_password(), $role . '_' . $suffix . '@example.invalid' );
        $assert( ! is_wp_error( $user_id ), 'Could not create ' . $role . ' fixture user.' );
        $users[$role] = $user_id;
    }

    foreach ( [ 'published' => [ 'publish', ATBDP_POST_TYPE ], 'expired' => [ 'draft', ATBDP_POST_TYPE ], 'trashed' => [ 'trash', ATBDP_POST_TYPE ], 'other' => [ 'publish', 'post' ] ] as $name => $details ) {
        $post_id = wp_insert_post(
            [
                'post_type'   => $details[1],
                'post_status' => $details[0],
                'post_author' => $users['owner'],
                'post_title'  => 'Enquiry fixture ' . $name . ' ' . $suffix,
            ], true
        );
        $assert( ! is_wp_error( $post_id ), 'Could not create ' . $name . ' fixture listing.' );
        $posts[$name] = $post_id;
    }
    $posts['self'] = wp_insert_post(
        [
            'post_type'   => ATBDP_POST_TYPE,
            'post_status' => 'publish',
            'post_author' => $users['sender'],
            'post_title'  => 'Enquiry fixture self ' . $suffix,
        ], true
    );
    $assert( ! is_wp_error( $posts['self'] ), 'Could not create self-owned fixture listing.' );

    $insert_response = function( $listing, $submitter, $status = 'publish', $completed = 1 ) use ( $wpdb, $responses_table, $meta_table, &$responses, $assert ) {
        $inserted = $wpdb->insert( $responses_table, [ 'form_id' => 1, 'created_by' => $submitter, 'status' => $status, 'is_completed' => $completed ] );
        $assert( false !== $inserted, 'Could not create response fixture.' );
        $response_id = $wpdb->insert_id;
        $responses[] = $response_id;
        $assert( false !== $wpdb->insert( $meta_table, [ 'response_id' => $response_id, 'meta_key' => 'listing_id', 'meta_value' => $listing ] ), 'Could not link response fixture to listing.' );
        return $response_id;
    };

    $sent    = $insert_response( $posts['published'], $users['sender'] );
    $guest   = $insert_response( $posts['published'], null );
    $expired = $insert_response( $posts['expired'], $users['sender'] );
    $self    = $insert_response( $posts['self'], $users['sender'] );
    $insert_response( $posts['published'], $users['sender'], 'draft', 0 );
    $insert_response( $posts['published'], $users['sender'], 'draft', 1 );
    $insert_response( $posts['published'], $users['sender'], 'publish', 0 );
    $insert_response( $posts['trashed'], $users['sender'] );
    $insert_response( $posts['other'], $users['sender'] );

    wp_set_current_user( $users['sender'] );
    $sent_view = $api->get_responses( $request( 'send', [ 'per_page' => 1 ] ) );
    $assert( 3 === (int) $sent_view['total'] && 1 === count( $sent_view['responses'] ), 'Send must page only completed listing responses from the submitter.' );
    $second_page = $api->get_responses( $request( 'send', [ 'page' => 2, 'per_page' => 1 ] ) );
    $assert( 1 === count( $second_page['responses'] ), 'Send second page is missing.' );
    $assert( $sent_view['responses'][0]->id !== $second_page['responses'][0]->id, 'Send pages must contain distinct records.' );
    $assert( 3 === (int) $api->get_kpis( $request( 'send' ) )['total'], 'Send KPI total must match the scoped list.' );
    $assert( 1 === (int) $api->get_responses( $request( 'receive' ) )['total'], 'Submitter must see only its own listing in Receive.' );
    $assert( $self === (int) $api->get_responses( $request( 'receive' ) )['responses'][0]->id, 'Self-submission must appear in both views.' );
    $assert( 1 === (int) $api->get_responses( $request( 'send', [ 'search' => 'expired ' . $suffix ] ) )['total'], 'Send search must include old listings.' );
    $assert( 2 === (int) $api->get_responses( $request( 'send', [ 'search' => 'owner_' . $suffix . '@example.invalid' ] ) )['total'], 'Send search must match the recipient rather than the submitter.' );
    $detail = $api->single_response( $request( 'receive', [ 'id' => $sent ] ) )->get_data();
    $assert( false === $detail['success'], 'Submitter must not open the owner-only detail route.' );
    $assert( false === $api->read_responses( $request( 'send', [ 'id' => $sent ] ) )->get_data()['success'], 'Submitter must not mark the owner\'s response as read.' );
    $assert( false === $api->delete_responses( $request( 'send', [ 'id' => $sent ] ) )->get_data()['success'], 'Submitter must not get a successful delete response.' );
    $assert( 1 === (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$responses_table} WHERE id = %d", $sent ) ), 'Submitter must not delete the owner\'s response.' );

    wp_set_current_user( $users['owner'] );
    $received = $api->get_responses( $request() );
    $assert( 3 === (int) $received['total'], 'Receive must contain incoming completed submissions, including guests and old listings.' );
    $assert( 2 === (int) $api->get_responses( $request( 'receive', [ 'search' => 'published ' . $suffix ] ) )['total'], 'Receive search must filter by listing title.' );
    $assert( 2 === (int) $api->get_responses( $request( 'receive', [ 'search' => 'sender_' . $suffix . '@example.invalid' ] ) )['total'], 'Receive search must match the submitter.' );
    $assert( 3 === (int) $api->get_kpis( $request( 'receive' ) )['total'], 'Receive KPI total must match the scoped list.' );
    $assert( 0 === (int) $api->get_responses( $request( 'send' ) )['total'], 'Owner must not see another user\'s sent submissions.' );
    $assert( 3 === (int) $api->get_responses( $request( 'invalid' ) )['total'], 'Unknown scopes must preserve the default Receive behavior.' );
    $assert( true === $api->read_responses( $request( 'receive', [ 'id' => $sent ] ) )->get_data()['success'], 'Owner must be able to mark received submissions as read.' );
    $assert( 1 === (int) $api->get_kpis( $request( 'receive' ) )['read'], 'Read KPI must reflect the owner action.' );
    $assert( true === $api->delete_responses( $request( 'receive', [ 'id' => $expired ] ) )->get_data()['success'], 'Owner must be able to delete received submissions.' );
    $assert( 2 === (int) $api->get_responses( $request( 'receive' ) )['total'], 'Deleted submissions must leave the received view.' );
    wp_set_current_user( $users['sender'] );
    $assert( 2 === (int) $api->get_responses( $request( 'send' ) )['total'], 'Both views must use the same stored submission after deletion.' );

    wp_set_current_user( $users['outsider'] );
    $assert( 0 === (int) $api->get_responses( $request( 'send' ) )['total'], 'Outsider must not see Send responses.' );
    $assert( 0 === (int) $api->get_responses( $request( 'receive' ) )['total'], 'Outsider must not see Receive responses.' );
    $assert( false === $api->single_response( $request( 'send', [ 'id' => $guest ] ) )->get_data()['success'], 'Guest response must not be available through Send.' );
    wp_set_current_user( 0 );
    $assert( 0 === (int) $api->get_responses( $request( 'send' ) )['total'], 'Unauthenticated queries must be empty.' );
    WP_CLI::success( 'Formgent enquiry Receive/Send scopes, pagination, search, and action permissions passed.' );
} finally {
    wp_set_current_user( 0 );
    foreach ( $responses as $response_id ) {
        $wpdb->delete( $meta_table, [ 'response_id' => $response_id ] );
        $wpdb->delete( $responses_table, [ 'id' => $response_id ] );
    }
    foreach ( $posts as $post_id ) {
        wp_delete_post( $post_id, true );
    }
    if ( ! function_exists( 'wp_delete_user' ) ) {
        require_once ABSPATH . 'wp-admin/includes/user.php';
    }
    foreach ( $users as $user_id ) {
        wp_delete_user( $user_id );
    }
}
