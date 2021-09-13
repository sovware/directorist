<?php
/**
 * Review activity class.
 *
 * @package Directorist\Review
 * @since 7.0.6
 */
namespace Directorist\Review;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Activity {

	const AJAX_ACTION = 'directorist_comment_activity';

	public static function init() {
		add_action( 'wp_ajax_' . self::AJAX_ACTION, array( __CLASS__, 'handle_request' ) );
		add_action( 'wp_ajax_nopriv_' . self::AJAX_ACTION, array( __CLASS__, 'handle_request' ) );

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ), 20 );
	}

	public static function enqueue_scripts() {
		wp_localize_script(
			'directorist-main-script',
			'directorist',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( self::AJAX_ACTION ),
				'action'  => self::AJAX_ACTION
			)
		);
	}

	public static function handle_request() {
		$nonce      = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
		$comment_id = isset( $_POST['comment_id'] ) ? absint( $_POST['comment_id'] ) : 0;
		$activity   = isset( $_POST['activity'] ) ? wp_unslash( $_POST['activity'] ) : '';

		try {
			if ( ! wp_verify_nonce( $nonce, self::AJAX_ACTION ) ) {
				throw new \Exception( __( 'Invalid request.', 'directorist' ), 401 );
			}

			if ( empty( $comment_id ) ) {
				throw new \Exception( __( 'Comment id cannot be empty.', 'directorist' ), 400 );
			}

			if ( is_null( get_comment( $comment_id ) ) ) {
				throw new \Exception( __( 'Comment does not exist!.', 'directorist' ), 400 );
			}

			if ( empty( $activity ) || ( ! method_exists( __CLASS__, 'on_' . $activity ) ) ) {
				throw new \Exception( __( 'Your intended action is not clear. Please take a valid action.', 'directorist' ), 400 );
			}

			if ( in_array( $activity, self::get_login_only_activities(), true ) && ! is_user_logged_in() ) {
				throw new \Exception( __( 'You must login to complete this action.', 'directorist' ) );
			}

			$response = call_user_func( array( __CLASS__, 'on_' . $activity ), $comment_id );

			if ( empty( $response ) ) {
				$response = __( 'Thank you for your feedback.', 'directorist' );
			}

			wp_send_json_success( $response );

		} catch ( \Exception $e ) {
			wp_send_json_error( $e->getMessage(), $e->getCode() );
		}
	}

	protected static function on_report( $comment_id ) {
		$reported = Comment_Meta::get_reported( $comment_id );

		if ( empty( $reported ) ) {
			$reported = 0;
		}

		Comment_Meta::set_reported( $comment_id, $reported + 1 );

		return __( 'Thank you for reporting.', 'directorist' );
	}

	protected static function on_helpful( $comment_id ) {
		$helpful = Comment_Meta::get_helpful( $comment_id );

		if ( empty( $helpful ) ) {
			$helpful = 0;
		}

		Comment_Meta::set_helpful( $comment_id, $helpful + 1 );

		return __( 'Nice to hear that it has been helpful.', 'directorist' );
	}

	protected static function on_unhelpful( $comment_id ) {
		$unhelpful = Comment_Meta::get_unhelpful( $comment_id );

		if ( empty( $unhelpful ) ) {
			$unhelpful = 0;
		}

		Comment_Meta::set_unhelpful( $comment_id, $unhelpful + 1 );

		return __( 'Thank you for your feedback.', 'directorist' );
	}

	protected static function get_login_only_activities() {
		return array(
			'report'
		);
	}
}

Activity::init();
