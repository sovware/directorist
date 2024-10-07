<?php
/**
 * Rest Temporary Media Upload Controller
 *
 * @package Directorist\Rest_Api
 * @version  1.0.0
 */

namespace Directorist\Rest_Api\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use WP_Error;
use WP_REST_Server;

/**
 * Temporary media upload controller class.
 */
class Temporary_Media_Upload_Controller extends Abstract_Controller {

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'temp-media-upload';

	/**
	 * Register the routes for listings.
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	public function create_item_permissions_check( $request ) {
		$nonce = $request->get_header( 'X-WP-Nonce' );

		if ( is_null( $nonce ) && ! current_user_can( 'upload_files' ) ) {
			return new WP_Error(
				'directorist_rest_cannot_create',
				__( 'Sorry, you are not allowed to upload image.', 'directorist' ),
				array( 'status' => 400 )
			);
		}

		if ( ! is_null( $nonce ) && ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			return new WP_Error(
				'directorist_rest_cannot_create',
				__( 'Sorry, you are not allowed to upload image.', 'directorist' ),
				array( 'status' => 400 )
			);
		}

		return true;
	}

	public function create_item( $request ) {
		// Get the file via $_FILES or raw data.
		$files   = $request->get_file_params();
		$headers = $request->get_headers();

		if ( empty( $files ) ) {
			return new WP_Error(
				'directorist_rest_upload_no_data',
				__( 'No data supplied.', 'directorist' ),
				array( 'status' => 400 )
			);
		}

		$file = $this->upload_from_file( $files, $headers );

		if ( is_wp_error( $file ) ) {
			return $file;
		}

		if ( ! empty( $file['error'] ) ) {
			return new WP_Error(
				'directorist_rest_upload_error',
				sprintf( '(%s) %s', $files['file']['name'], $file['error'] ),
				array( 'status' => 500 )
			);
		}

		if ( empty( $file['url'] ) ) {
			return new WP_Error(
				'directorist_rest_upload_error',
				sprintf( __( 'Could not upload (%s).', 'directorist' ), $files['file']['name'] ),
				array( 'status' => 500 )
			);
		}

		$data = array(
			'name' => $files['file']['name'],
			'file' => basename( $file['url'] ),
		);

		return rest_ensure_response( $data );
	}

	public static function set_temporary_upload_dir( $upload ) {
		$upload['subdir'] = DIRECTORY_SEPARATOR . directorist_get_temp_upload_dir() . DIRECTORY_SEPARATOR . date( 'nj' );
		$upload['path']   = $upload['basedir'] . $upload['subdir'];
		$upload['url']    = $upload['baseurl'] . $upload['subdir'];

		return $upload;
	}

	/**
	 * Determine if uploaded file exceeds space quota on multisite.
	 *
	 * Replicates check_upload_size().
	 *
	 *
	 * @param array $file $_FILES array for a given file.
	 * @return true|WP_Error True if can upload, error for errors.
	 */
	protected function check_upload_size( $file ) {
		if ( ! is_multisite() ) {
			return true;
		}

		if ( get_site_option( 'upload_space_check_disabled' ) ) {
			return true;
		}

		$space_left = get_upload_space_available();

		$file_size = filesize( $file['tmp_name'] );

		if ( $space_left < $file_size ) {
			return new WP_Error(
				'rest_upload_limited_space',
				/* translators: %s: Required disk space in kilobytes. */
				sprintf( __( 'Not enough space to upload. %s KB needed.', 'directorist' ), number_format( ( $file_size - $space_left ) / KB_IN_BYTES ) ),
				array( 'status' => 400 )
			);
		}

		if ( $file_size > ( KB_IN_BYTES * get_site_option( 'fileupload_maxk', 1500 ) ) ) {
			return new WP_Error(
				'rest_upload_file_too_big',
				/* translators: %s: Maximum allowed file size in kilobytes. */
				sprintf( __( 'This file is too big. Files must be less than %s KB in size.', 'directorist' ), get_site_option( 'fileupload_maxk', 1500 ) ),
				array( 'status' => 400 )
			);
		}

		// Include multisite admin functions to get access to upload_is_user_over_quota().
		require_once ABSPATH . 'wp-admin/includes/ms.php';

		if ( upload_is_user_over_quota( false ) ) {
			return new WP_Error(
				'rest_upload_user_quota_exceeded',
				__( 'You have used your space quota. Please delete files before uploading.', 'directorist' ),
				array( 'status' => 400 )
			);
		}

		return true;
	}

	protected function upload_from_file( $files, $headers ) {
		if ( empty( $files ) ) {
			return new WP_Error(
				'directorist_rest_upload_no_data',
				__( 'No data supplied.', 'directorist' ),
				array( 'status' => 400 )
			);
		}

		// Verify hash, if given.
		if ( ! empty( $headers['content_md5'] ) ) {
			$content_md5 = array_shift( $headers['content_md5'] );
			$expected    = trim( $content_md5 );
			$actual      = md5_file( $files['file']['tmp_name'] );

			if ( $expected !== $actual ) {
				return new WP_Error(
					'rest_upload_hash_mismatch',
					__( 'Content hash did not match expected.', 'directorist' ),
					array( 'status' => 412 )
				);
			}
		}

		$size_check = self::check_upload_size( $files['file'] );
		if ( is_wp_error( $size_check ) ) {
			return $size_check;
		}

		// Include filesystem functions to get access to wp_handle_upload().
		require_once ABSPATH . 'wp-admin/includes/file.php';

		// Set temporary upload directory.
		add_filter( 'upload_dir', array( __CLASS__, 'set_temporary_upload_dir' ) );

		// handle file upload
		$status = wp_handle_upload(
			$files['file'],
			array(
				'test_form' => false,
				'test_type' => true,
				'mimes'     => directorist_get_mime_types( 'image' ),
			)
		);

		// Restore to default upload directory.
		remove_filter( 'upload_dir', array( __CLASS__, 'set_temporary_upload_dir' ) );

		return $status;
	}

	/**
	 * Get the Listings's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema         = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'Temporary Media',
			'type'       => 'object',
			'properties' => array(
				'name'                  => array(
					'description' => __( 'Media file name.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'file'                  => array(
					'description' => __( 'Media file slug.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
			)
		);

		return $this->add_additional_fields_schema( $schema );
	}
}
