<?php
/**
 * Comment form process handler.
 *
 * @package Directorist\Review
 * @since 7.1.0
 */
namespace Directorist\Review;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Exception;

class Comment_Form_Processor {

	const AJAX_ACTION = 'directorist_process_comment_form';

	public static function init() {
		add_action( 'wp_ajax_' . self::AJAX_ACTION , array( __CLASS__, 'process' ) );
		add_action( 'wp_ajax_nopriv_' . self::AJAX_ACTION, array( __CLASS__, 'process' ) );
	}

	public static function process() {
		try {
			$nonce      = ! empty( $_POST['directorist_comment_nonce'] ) ? sanitize_key( $_POST['directorist_comment_nonce'] ) : '';
			$post_id    = ! empty( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
			$comment_id = ! empty( $_POST['comment_id'] ) ? absint( $_POST['comment_id'] ) : 0;

			if ( ! wp_verify_nonce( $nonce, self::AJAX_ACTION ) ) {
				throw new Exception( __( 'Invalid request.', 'directorist' ), 400 );
			}

			if ( ! directorist_is_review_enabled() ) {
				throw new Exception( __( 'Review is disabled.', 'directorist' ) );
			}

			$comment = get_comment( $comment_id );
			if ( is_null( $comment ) ) {
				throw new Exception( __( 'Invalid review or comment id.', 'directorist' ), 400 );
			}

			if ( ! directorist_is_listing_post_type( $post_id ) || ! directorist_is_listing_post_type( $comment->comment_post_ID ) ) {
				throw new Exception( __( 'Invalid listing id.', 'directorist' ), 400 );
			}

			$is_review          = ( $comment->comment_type === 'review' );
			$comment_type_label = $is_review ? __( 'review', 'directorist' ) : __( 'comment', 'directorist' );

			if ( ! is_user_logged_in() ) {
				throw new Exception( sprintf(
					__( 'Please login to update your %s.', 'directorist' ),
					$comment_type_label
				) );
			}

			if ( ! current_user_can( 'edit_comment', $comment_id ) ) {
				throw new Exception( sprintf( __( 'You are not allowed to edit this %s.', 'directorist' ), 400 ), $comment_type_label );
			}

			if ( $is_review ) {
				$rating = ! empty( $_POST['rating'] ) ? directorist_clean( wp_unslash( $_POST['rating'] ) ) : '';
				if ( empty( $rating ) ) {
					throw new Exception( __( 'Please share review rating.', 'directorist' ) );
				}
			}

			$comment_content = isset( $_POST['comment'] ) ? trim( sanitize_textarea_field( wp_unslash( $_POST['comment'] ) ) ) : '';

			if ( empty( $comment_content ) ) {
				if ( $is_review ) {
					$text = __( 'To submit your review, please describe your rating.', 'directorist' );
				} else {
					$text = __( 'To submit your reply, please add your comment.', 'directorist' );
				}

				throw new Exception( $text );
			}

			$comment_data = array(
				'comment_ID'      => $comment->comment_ID,
				'comment_post_ID' => $comment->comment_post_ID,
				'comment_type'    => $comment->comment_type,
				'comment_content' => $comment_content
			);

			$updated_comment = wp_update_comment( $comment_data, true );
			if ( is_wp_error( $updated_comment ) ) {
				throw new Exception( $updated_comment->get_error_message() );
			}

			if ( false === $updated_comment ) {
				throw new Exception( __( 'Something went wrong, try again.', 'directorist' ) );
			}

			Comment::post_rating( $comment_id, $comment_data, $_POST );
			Comment::clear_transients( $comment->comment_post_ID );

			$cpage = isset( $_POST['cpage'] ) ? absint( $_POST['cpage'] ) : 0;
			$redirect_to = get_permalink( $comment->comment_post_ID );
			if ( $cpage ) {
				$redirect_to = add_query_arg( 'cpage', $cpage, $redirect_to );
			}
			$redirect_to .= '#' . $comment_id;

			/**
			* @since 7.7.0
			*
			*/

			do_action( 'directorist_review_updated', $comment_id, $comment_data );


			wp_safe_redirect( $redirect_to );
			exit;
		} catch ( Exception $e ) {
			$html = sprintf( '<div class="directorist-alert directorist-alert-danger">%s</div>', $e->getMessage() );

			wp_send_json_error( array(
				'error' => $e->getMessage(),
				'html'  => $html,
			) );
		}
	}
}

Comment_Form_Processor::init();
