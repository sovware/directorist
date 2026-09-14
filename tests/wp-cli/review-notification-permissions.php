<?php
/**
 * Integration regression for review notifications and permissions.
 *
 * Run on a disposable WordPress site with Directorist active:
 * wp eval-file wp-content/plugins/directorist/tests/wp-cli/review-notification-permissions.php
 * Mail is intercepted and all test users/posts/comments are removed.
 *
 * @package Directorist
 */

use Directorist\Review\Email;

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
    return;
}

$users         = [];
$posts         = [];
$mails         = [];
$enabled       = true;
$failures      = [];
$checks        = 0;
$assert        = function( $condition, $message ) use ( &$failures, &$checks ) {
    ++$checks;
    if ( ! $condition ) {
        $failures[] = $message;
    }
};
$mail_filter   = function( $pre, $mail ) use ( &$mails ) {
    $mails[] = $mail;
    return true;
};
$option_filter = function( $value, $name ) use ( &$enabled ) {
    if ( 'notify_user' === $name ) {
        return $enabled ? [ 'listing_review' ] : [];
    }
    return $value;
};
add_filter( 'pre_wp_mail', $mail_filter, 1000, 2 );
add_filter( 'directorist_option', $option_filter, 999, 2 );

try {
    foreach ( [ 'owner' => 'subscriber', 'reviewer' => 'subscriber', 'other' => 'subscriber', 'moderator' => 'administrator', 'blogger' => 'author' ] as $label => $role ) {
        $id = wp_insert_user(
            [
                'user_login'   => wp_unique_id( 'review_qa_' . $label . '_' ) . wp_rand(),
                'user_pass'    => wp_generate_password(),
                'user_email'   => $label . wp_rand() . '@example.invalid',
                'display_name' => 'Public ' . $label,
                'role'         => $role,
            ] 
        );
        if ( is_wp_error( $id ) ) {
            throw new RuntimeException( $id->get_error_message() );
        }
        $users[ $label ] = $id;
    }
    $listing = wp_insert_post( [ 'post_type' => ATBDP_POST_TYPE, 'post_status' => 'publish', 'post_title' => 'Review QA listing', 'post_author' => $users['owner'] ] );
    $posts[] = $listing;
    $create  = function( $status = 0, $extra = [] ) use ( $listing, &$users ) {
        return wp_insert_comment(
            array_merge(
                [
                    'comment_post_ID'   => $listing,
                    'comment_type'      => 'review',
                    'comment_content'   => 'Public review <script>not executable</script>',
                    'user_id'           => $users['reviewer'],
                    'comment_approved'  => $status,
                    'comment_author_IP' => '192.0.2.37',
                    'comment_meta'      => [ 'rating' => 4.5 ],
                ], $extra 
            ) 
        );
    };
    $review  = $create();
    $mails   = [];
    Email::send_pending_owner_notifications();
    $assert( ! $mails, 'Pending review must not notify its owner.' );
    $assert( ! user_can( $users['owner'], 'moderate_comments' ), 'Owner fixture must not be a moderator.' );
    $assert( user_can( $users['owner'], 'edit_post', $listing ), 'Owner must retain listing editing.' );
    foreach ( [ 'owner', 'reviewer', 'other' ] as $label ) {
        $assert( ! user_can( $users[ $label ], 'edit_comment', $review ), $label . ' must not use native moderation.' );
    }
    $assert( user_can( $users['reviewer'], 'edit_directorist_review', $review ), 'Reviewer must retain content-only editing.' );
    $assert( ! user_can( $users['owner'], 'edit_directorist_review', $review ), 'Owner must not edit another reviewer text.' );
    $assert( ! user_can( $users['other'], 'edit_directorist_review', $review ), 'Unrelated user must not edit review text.' );
    $assert( user_can( $users['moderator'], 'edit_comment', $review ), 'Moderator must retain native moderation.' );
    $assert( user_can( $users['moderator'], 'edit_directorist_review', $review ), 'Moderator must retain review editing.' );
    $reply = $create( 1, [ 'comment_type' => 'comment', 'comment_parent' => $review, 'user_id' => $users['owner'] ] );
    $assert( user_can( $users['owner'], 'edit_directorist_review', $reply ), 'Owner must retain own reply editing.' );

    wp_set_current_user( $users['moderator'] );
    wp_set_comment_status( $review, 'approve' );
    Email::send_pending_owner_notifications();
    $assert( 1 === count( $mails ), 'Manual approval must send exactly one owner email.' );
    $mail = end( $mails );
    $body = isset( $mail['message'] ) ? $mail['message'] : '';
    foreach ( [ 'Review QA listing', 'Public reviewer', '4.5 / 5', 'Public review', 'View review' ] as $text ) {
        $assert( false !== strpos( $body, $text ), 'Email missing ' . $text );
    }
    foreach ( [ '192.0.2.37', 'localhost', 'wp-admin', '<script>', get_userdata( $users['reviewer'] )->user_email, 'Email:', 'URL:' ] as $text ) {
        $assert( false === strpos( $body . wp_json_encode( $mail['headers'] ), $text ), 'Email must exclude ' . $text );
    }
    $assert( false !== strpos( $body, esc_url( get_comment_link( $review ) ) ), 'Email must include public review link.' );
    wp_notify_postauthor( $review );
    wp_new_comment_notify_postauthor( $review );
    wp_set_comment_status( $review, 'hold' );
    wp_set_comment_status( $review, 'approve' );
    Email::send_pending_owner_notifications();
    $assert( 1 === count( $mails ), 'Default notifications and reapproval must not duplicate mail.' );

    $mails     = [];
    $immediate = $create( 1, [ 'comment_meta' => [] ] );
    update_comment_meta( $immediate, 'rating', 3 );
    Email::send_pending_owner_notifications();
    $assert( 1 === count( $mails ) && false !== strpos( $mails[0]['message'], '3.0 / 5' ), 'Immediate approval must wait for rating metadata.' );
    $mails = [];
    $spam  = $create( 1 );
    wp_set_comment_status( $spam, 'spam' );
    Email::send_pending_owner_notifications();
    $assert( ! $mails, 'Review spammed before notification must not send mail.' );
    $guest = $create( 1, [ 'user_id' => 0, 'comment_author' => 'Public guest', 'comment_author_email' => 'private-guest@example.invalid' ] );
    Email::send_pending_owner_notifications();
    $assert( 1 === count( $mails ) && false !== strpos( $mails[0]['message'], 'Public guest' ), 'Guest public name must be included.' );
    $assert( false === strpos( wp_json_encode( $mails ), 'private-guest@example.invalid' ), 'Guest private email must be excluded.' );
    $mails   = [];
    $enabled = false;
    $off     = $create( 1 );
    wp_notify_postauthor( $off );
    Email::send_pending_owner_notifications();
    $assert( ! $mails, 'Disabled owner notifications must remain disabled.' );
    $enabled = true;
    $own     = $create( 1, [ 'user_id' => $users['owner'] ] );
    Email::send_pending_owner_notifications();
    $assert( ! $mails, 'Owner must not be notified of own review.' );

    $blog    = wp_insert_post( [ 'post_type' => 'post', 'post_status' => 'publish', 'post_title' => 'Ordinary blog QA', 'post_author' => $users['blogger'] ] );
    $posts[] = $blog;
    $comment = wp_insert_comment( [ 'comment_post_ID' => $blog, 'comment_content' => 'Blog comment', 'comment_approved' => 1 ] );
    $assert( user_can( $users['blogger'], 'edit_comment', $comment ), 'Ordinary blog permissions must stay unchanged.' );
    wp_notify_postauthor( $comment );
    $assert( 1 === count( $mails ), 'Ordinary WordPress comment email must stay unchanged.' );
} finally {
    wp_set_current_user( 0 );
    foreach ( $posts as $post_id ) {
        wp_delete_post( $post_id, true );
    }
    require_once ABSPATH . 'wp-admin/includes/user.php';
    foreach ( $users as $user_id ) {
        wp_delete_user( $user_id );
    }
    Email::send_pending_owner_notifications();
    remove_filter( 'pre_wp_mail', $mail_filter, 1000 );
    remove_filter( 'directorist_option', $option_filter, 999 );
}

if ( $failures ) {
    WP_CLI::error( implode( "\n", $failures ) );
}
WP_CLI::success( $checks . ' review notification and permission checks passed; fixtures removed.' );
