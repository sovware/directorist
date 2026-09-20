<?php
/**
 * Comment email manager class.
 *
 * @package Directorist\Review
 * @since 7.1.0
 */
namespace Directorist\Review;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Email {
    /**
     * Approved reviews awaiting their metadata before notification.
     *
     * @var array
     */
    private static $pending_owner_notifications = [];

    public static function init() {
        add_action( 'wp_insert_comment', [ __CLASS__, 'queue_owner_notification' ] );
        add_action( 'transition_comment_status', [ __CLASS__, 'on_status_transition' ], 10, 3 );
        add_action( 'shutdown', [ __CLASS__, 'send_pending_owner_notifications' ] );
        add_filter( 'notify_post_author', [ __CLASS__, 'disable_default_owner_notification' ], 10, 2 );
        add_filter( 'comment_notification_recipients', [ __CLASS__, 'exclude_default_owner_recipients' ], 10, 2 );
        add_action( 'comment_post', [ __CLASS__, 'notify_admin' ] );

        add_action( 'comment_post', [ __CLASS__, 'maybe_disable_default_email' ], 0 );
    }

    public static function disable_default_owner_notification( $notify, $comment_id ) {
        return self::get_review( $comment_id ) ? false : $notify;
    }

    public static function exclude_default_owner_recipients( $recipients, $comment_id ) {
        return self::get_review( $comment_id ) ? [] : $recipients;
    }

    public static function queue_owner_notification( $comment_id ) {
        $review = self::get_review( $comment_id );
        if ( $review && '1' === $review->comment_approved ) {
            self::$pending_owner_notifications[ $comment_id ] = $comment_id;
        }
    }

    public static function on_status_transition( $new_status, $old_status, $comment ) {
        if ( 'approved' === $new_status && $new_status !== $old_status ) {
            self::queue_owner_notification( $comment->comment_ID );
        }
    }

    public static function send_pending_owner_notifications() {
        $comment_ids                       = self::$pending_owner_notifications;
        self::$pending_owner_notifications = [];

        foreach ( $comment_ids as $comment_id ) {
            self::notify_owner( $comment_id );
        }
    }

    public static function maybe_disable_default_email() {
		$post_id = isset( $_POST['comment_post_ID'] ) ? absint( $_POST['comment_post_ID'] ) : 0; // @codingStandardsIgnoreLine.

        if ( $post_id && ATBDP_POST_TYPE === get_post_type( $post_id ) ) {
            remove_action( 'comment_post', 'wp_new_comment_notify_moderator' );
            remove_action( 'comment_post', 'wp_new_comment_notify_postauthor' );
        }
    }

    public static function notify_owner( $comment_id ) {
        if ( ! directorist_is_owner_notifiable_event( 'listing_review' ) ) {
            return false;
        }

        $review = self::get_review( $comment_id );
        if ( ! $review || '1' !== $review->comment_approved || get_comment_meta( $comment_id, '_directorist_owner_notified', true ) ) {
            return false;
        }

        $post = get_post( $review->comment_post_ID );
        $user = get_userdata( $post->post_author );

        if ( ! $user ) {
            return false;
        }

        // The comment was left by the user.
        if ( $user && $review->user_id == $post->post_author ) {
            return false;
        }

        // The post author is no longer a member of the blog.
        if ( $user && ! user_can( $post->post_author, 'read_post', $post->ID ) ) {
            return false;
        }

        $reviewer      = get_userdata( $review->user_id );
        $reviewer_name = $reviewer ? $reviewer->display_name : $review->comment_author;
        $reviewer_name = $reviewer_name ? $reviewer_name : __( 'Anonymous', 'directorist' );
        $listing_title = get_the_title( $post->ID );
        $review_link   = sprintf( '<a href="%s">%s</a>', esc_url( get_comment_link( $review ) ), esc_html__( 'View review', 'directorist' ) );
        $rating        = (float) get_comment_meta( $comment_id, 'rating', true );

        /* translators: 1: Site name, 2: Listing title. */
        $subject = sprintf( __( '[%1$s] New review at "%2$s"', 'directorist' ), get_bloginfo( 'name' ), $listing_title );
        /* translators: 1: Listing title, 2: Public reviewer name, 3: Star rating, 4: Review text, 5: Public review link. */
        $message = sprintf(
            __( 'Listing: %1$s<br />Reviewer: %2$s<br />Rating: %3$s / 5<br />Review: %4$s<br /><br />%5$s', 'directorist' ),
            esc_html( $listing_title ),
            esc_html( $reviewer_name ),
            esc_html( number_format_i18n( $rating, 1 ) ),
            nl2br( esc_html( $review->comment_content ) ),
            $review_link
        );

        $sent = ATBDP()->email->send_mail( $user->user_email, $subject, $message, ATBDP()->email->get_email_headers() );
        if ( $sent ) {
            update_comment_meta( $comment_id, '_directorist_owner_notified', 1 );
        }

        return $sent;
    }

    public static function notify_admin( $comment_id ) {
        if ( ! directorist_is_admin_notifiable_event( 'listing_review' ) ) {
            return false;
        }

        $review = self::get_review( $comment_id );
        if ( ! $review ) {
            return false;
        }

        $post          = get_post( $review->comment_post_ID );
        $site_name     = get_bloginfo( 'name' );
        $site_url      = get_bloginfo( 'url' );
        $listing_title = get_the_title( $post->ID );
        $listing_url   = get_the_permalink( $post->ID );
        $listing_url   = sprintf( '<a href="%s">%s</a>', $listing_url, $listing_url );
        $comment_author = empty( $review->comment_author ) ? $review->comment_author_email : $review->comment_author;

        $to = get_directorist_option( 'admin_email_lists' );

        if ( empty( $to ) ) {
            $to = get_bloginfo( 'admin_email' );
        }

        $subject = "[$site_name] New review at $listing_title";
        /* translators: %1$s: Listing URL, %2$s: Comment author name, %3$s: Comment author email, %4$s: Review content */
        $message = sprintf(
            __( 'Dear Admin,<br /><br />A new review at %1$s.<br /><br />Name: %2$s<br />Email: %3$s<br />Review: %4$s', 'directorist' ),
            $listing_url,
            esc_html( $comment_author ),
            esc_html( $review->comment_author_email ),
            esc_html( $review->comment_content )
        );
        $message = atbdp_email_html( $subject, $message );
        $headers = "From: {$review->comment_author_email} <{$review->comment_author_email}>\r\n";
        $headers .= "Reply-To: {$review->comment_author_email}\r\n";

        return ATBDP()->email->send_mail( $to, $subject, $message, $headers );
    }

    public static function get_review( $comment_id ) {
        $comment = get_comment( $comment_id );
        if ( empty( $comment ) || empty( $comment->comment_post_ID ) ) {
            return false;
        }

        if ( get_post_type( $comment->comment_post_ID ) !== ATBDP_POST_TYPE ) {
            return false;
        }

        if ( ! isset( $comment->comment_type ) || $comment->comment_type !== 'review' ) {
            return false;
        }

        return $comment;
    }
}

Email::init();
