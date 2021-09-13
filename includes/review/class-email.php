<?php
/**
 * Comment email manager class.
 *
 * @package Directorist\Review
 * @since 7.0.6
 */
namespace Directorist\Review;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Email {

	public static function init() {
		add_action( 'comment_post', [ __CLASS__, 'notify_owner' ] );
		add_action( 'comment_post', [ __CLASS__, 'notify_admin' ] );

		add_action( 'comment_post', [ __CLASS__, 'maybe_disable_default_email' ], 0 );
	}

	public static function maybe_disable_default_email() {
		$post_id = isset( $_POST['comment_post_ID'] ) ? absint( $_POST['comment_post_ID'] ) : 0;

		if ( $post_id && ATBDP_POST_TYPE === get_post_type( $post_id ) ) {
			remove_action( 'comment_post', 'wp_new_comment_notify_moderator' );
			remove_action( 'comment_post', 'wp_new_comment_notify_postauthor' );
		}
	}

	public static function notify_owner( $comment_id ) {
		if ( ! in_array( 'listing_review', get_directorist_option( 'notify_user', array() ), true ) ) {
			return false;
		}

		$comment = get_comment( $comment_id );
		if ( empty( $comment ) || empty( $comment->comment_post_ID ) ) {
			return false;
		}

		if ( ! isset( $comment->comment_type, $comment->comment_approved ) || $comment->comment_type !== 'review' || ! in_array( $comment->comment_approved, array( '', '1' ) ) ) {
			return false;
		}

		$post = get_post( $comment->comment_post_ID );
		$user = get_userdata( $post->post_author );

		// The comment was left by the user.
		if ( $user && $comment->user_id == $post->post_author ) {
			return false;
		}

		// The author moderated a comment on their own post.
		if ( $user && get_current_user_id() == $post->post_author ) {
			return false;
		}

		// The post author is no longer a member of the blog.
		if ( $user && ! user_can( $post->post_author, 'read_post', $post->ID ) ) {
			return false;
		}

		$site_name     = get_bloginfo( 'name' );
		$site_url      = get_bloginfo( 'url' );
		$listing_title = get_the_title( $post->ID );
		$listing_url   = get_permalink( $post->ID );

		$placeholders = array(
			'{site_name}'     => $site_name,
			'{site_link}'     => sprintf('<a href="%s">%s</a>', $site_url, $site_name),
			'{site_url}'      => sprintf('<a href="%s">%s</a>', $site_url, $site_url),
			'{listing_title}' => $listing_title,
			'{listing_link}'  => sprintf('<a href="%s">%s</a>', $listing_url, $listing_title),
			'{listing_url}'   => sprintf('<a href="%s">%s</a>', $listing_url, $listing_url),
			'{sender_name}'   => empty( $comment->comment_author ) ? $comment->comment_author_email : $comment->comment_author,
			'{sender_email}'  => $comment->comment_author_email,
			'{message}'       => $comment->comment_content,
		);

		$to = $user->user_email;

		$subject = __( '[{site_name}] New review at "{listing_title}"', 'directorist' );
		$subject = strtr( $subject, $placeholders );

		$message = __( "Dear User,<br /><br />A new review at {listing_url}.<br /><br />", 'directorist' );
		$message = strtr( $comment->comment_content, $placeholders );

		$headers = "From: {$comment->comment_author_email} <{$comment->comment_author_email}>\r\n";
		$headers .= "Reply-To: {$comment->comment_author_email}\r\n";

		return ATBDP()->email->send_mail( $to, $subject, $message, $headers );
	}

	public static function notify_admin( $comment_id ) {
		if ( get_directorist_option( 'disable_email_notification' ) ) {
			return false;
		}

		if ( ! in_array( 'listing_review', get_directorist_option( 'notify_admin', array() ), true ) )  {
			return false;
		}

		$comment = get_comment( $comment_id );
		if ( empty( $comment ) || empty( $comment->comment_post_ID ) ) {
			return false;
		}

		if ( ! isset( $comment->comment_type, $comment->comment_approved ) || $comment->comment_type !== 'review' || ! in_array( $comment->comment_approved, array( '', '1' ) ) ) {
			return false;
		}

		$post          = get_post( $comment->comment_post_ID );
		$site_name     = get_bloginfo( 'name' );
		$site_url      = get_bloginfo( 'url' );
		$listing_title = get_the_title( $post->ID );
		$listing_url   = get_the_permalink( $post->ID );

		$placeholders = array(
			'{site_name}'     => $site_name,
			'{site_link}'     => sprintf( '<a href="%s">%s</a>', $site_url, $site_name ),
			'{site_url}'      => sprintf( '<a href="%s">%s</a>', $site_url, $site_url ),
			'{listing_title}' => $listing_title,
			'{listing_link}'  => sprintf( '<a href="%s">%s</a>', $listing_url, $listing_title ),
			'{listing_url}'   => sprintf( '<a href="%s">%s</a>', $listing_url, $listing_url ),
			'{sender_name}'   => empty( $comment->comment_author ) ? $comment->comment_author_email : $comment->comment_author,
			'{sender_email}'  => $comment->comment_author_email,
			'{message}'       => $comment->comment_content,
		);

		$to = get_directorist_option( 'admin_email_lists' );

		if ( empty( $to ) ) {
			$to = get_bloginfo( 'admin_email' );
		}

		$subject = __( '[{site_name}] New review at "{listing_title}"', 'directorist' );
		$subject = strtr( $subject, $placeholders );

		$message = __( "Dear Administrator,<br /><br />A new review at {listing_url}.<br /><br />Name: {sender_name}<br />Email: {sender_email}", 'directorist' );
		$message = strtr( $comment->comment_content, $placeholders );

		$headers = "From: {$comment->comment_author_email} <{$comment->comment_author_email}>\r\n";
		$headers .= "Reply-To: {$comment->comment_author_email}\r\n";

		return ATBDP()->email->send_mail( $to, $subject, $message, $headers );
	}
}

Email::init();
