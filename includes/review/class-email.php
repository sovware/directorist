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
		if ( ! directorist_owner_notifiable_for( 'listing_review' ) ) {
			return false;
		}

		$review = self::get_review( $comment_id );
		if ( ! $review ) {
			return false;
		}

		$post = get_post( $review->comment_post_ID );
		$user = get_userdata( $post->post_author );

		// The comment was left by the user.
		if ( $user && $review->user_id == $post->post_author ) {
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
			'{site_link}'     => sprintf( '<a href="%s">%s</a>', $site_url, $site_name ),
			'{site_url}'      => sprintf( '<a href="%s">%s</a>', $site_url, $site_url ),
			'{listing_title}' => $listing_title,
			'{listing_link}'  => sprintf( '<a href="%s">%s</a>', $listing_url, $listing_title ),
			'{listing_url}'   => sprintf( '<a href="%s">%s</a>', $listing_url, $listing_url ),
			'{sender_name}'   => empty( $review->comment_author ) ? $review->comment_author_email : $review->comment_author,
			'{sender_email}'  => $review->comment_author_email,
			'{message}'       => $review->comment_content,
		);

		$subject = __( '[{site_name}] New review at "{listing_title}"', 'directorist' );
		$subject = strtr( $subject, $placeholders );

		$message = __( "Dear User,<br /><br />A new review at {listing_url}.<br /><br />Name: {sender_name}<br />Email: {sender_email}<br />Review: {message}", 'directorist' );
		$message = strtr( $message, $placeholders );

		$headers = "From: {$review->comment_author_email} <{$review->comment_author_email}>\r\n";
		$headers .= "Reply-To: {$review->comment_author_email}\r\n";

		return ATBDP()->email->send_mail( $user->user_email, $subject, $message, $headers );
	}

	public static function notify_admin( $comment_id ) {
		if ( ! directorist_admin_notifiable_for( 'listing_review' ) )  {
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

		$placeholders = array(
			'{site_name}'     => $site_name,
			'{site_link}'     => sprintf( '<a href="%s">%s</a>', $site_url, $site_name ),
			'{site_url}'      => sprintf( '<a href="%s">%s</a>', $site_url, $site_url ),
			'{listing_title}' => $listing_title,
			'{listing_link}'  => sprintf( '<a href="%s">%s</a>', $listing_url, $listing_title ),
			'{listing_url}'   => sprintf( '<a href="%s">%s</a>', $listing_url, $listing_url ),
			'{sender_name}'   => empty( $review->comment_author ) ? $review->comment_author_email : $review->comment_author,
			'{sender_email}'  => $review->comment_author_email,
			'{message}'       => $review->comment_content,
		);

		$to = get_directorist_option( 'admin_email_lists' );

		if ( empty( $to ) ) {
			$to = get_bloginfo( 'admin_email' );
		}

		$subject = __( '[{site_name}] New review at "{listing_title}"', 'directorist' );
		$subject = strtr( $subject, $placeholders );

		$message = __( "Dear Admin,<br /><br />A new review at {listing_url}.<br /><br />Name: {sender_name}<br />Email: {sender_email}<br />Review: {message}", 'directorist' );
		$message = strtr( $message, $placeholders );

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
