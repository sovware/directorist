<?php
/**
 * Background Process Image
 *
 * @since 7.8
 * @package Directorist
 */
namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\Background_Process', false ) ) {
	include_once ATBDP_INC_DIR . 'classes/class-abstract-background-process.php';
}

/**
 * Background_Image_Process Class.
 */
class Background_Image_Process extends Background_Process {

	/**
	 * Initiate new background process.
	 */
	public function __construct() {
		// Uses unique prefix per blog so each blog has separate queue.
		$this->prefix = 'wp_' . get_current_blog_id();
		$this->action = 'directorist_background_image_process';

		parent::__construct();
	}

	/**
	 * Handle cron healthcheck
	 *
	 * Restart the background process if not already running
	 * and data exists in the queue.
	 */
	public function handle_cron_healthcheck() {
		if ( $this->is_process_running() ) {
			// Background process already running.
			return;
		}

		if ( $this->is_queue_empty() ) {
			// No data to process.
			$this->clear_scheduled_event();
			return;
		}

		$this->handle();
	}

	/**
	 * Schedule fallback event.
	 */
	protected function schedule_event() {
		if ( ! wp_next_scheduled( $this->cron_hook_identifier ) ) {
			wp_schedule_event( time() + 10, $this->cron_interval_identifier, $this->cron_hook_identifier );
		}
	}


	/**
	 * Task
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param array $attachments { attachment_id => attachment_path }
	 * @return string|bool
	 */
	protected function task( $image ) {
		$image_id = key( $image );

		if ( ! headers_sent() ) {
			header( 'X-WP-Upload-Attachment-ID: ' . $image_id );
		}

		\wp_update_attachment_metadata( $image_id, \wp_generate_attachment_metadata( $image_id, $image[ $image_id ] ) );
	}

	/**
	 * See if the batch limit has been exceeded.
	 *
	 * @return bool
	 */
	public function is_memory_exceeded() {
		return $this->memory_exceeded();
	}
}
