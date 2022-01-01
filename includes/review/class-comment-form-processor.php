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

class Comment_Form_Processor {

	const AJAX_NONCE = 'directorist_process_comment_form';

	public static function init() {
		add_action( 'wp_ajax_directorist_process_feedback_form', array( __CLASS__, 'process' ) );
		add_action( 'wp_ajax_nopriv_directorist_process_feedback_form', array( __CLASS__, 'process' ) );
	}

	public static function process() {
		try {
			$post    = wp_unslash( $_POST );
			$comment = wp_handle_comment_submission( $post );

			if ( is_wp_error( $comment ) ) {
				file_put_contents( __DIR__ . '/data.txt', print_r( $comment->get_error_data(), true ) );

				$data = (int) $comment->get_error_data();
				if ( ! empty( $data ) ) {
					throw new \Exception( $comment->get_error_message(), $data );
				}
			}
		} catch ( \Exception $e ) {
			wp_send_json_error( $e->getMessage(), $e->getCode() );
		}
	}
}

Comment_Form_Processor::init();
