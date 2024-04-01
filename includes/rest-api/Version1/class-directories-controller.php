<?php
/**
 * Directory Types Rest Controller.
 *
 * @package Directorist\Rest_Api
 * @version  1.0.0
 */

namespace Directorist\Rest_Api\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use WP_Error;

/**
 * REST API Directory types controller class.
 *
 * @package Directorist\Rest_Api
 * @extends Directorist\Rest_Api\Controllers\Version1\Terms_Controller
 */
class Directories_Controller extends Terms_Controller {

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'directories';

	/**
	 * Taxonomy.
	 *
	 * @var string
	 */
	protected $taxonomy = ATBDP_TYPE;

	/**
	 * Prepare a single directory output for response.
	 *
	 * @param WP_Term         $item    Term object.
	 * @param WP_REST_Request $request Request instance.
	 * @return WP_REST_Response
	 */
	public function prepare_item_for_response( $item, $request ) {
		$date_created = get_term_meta( $item->term_id, '_created_date', true );
		$expiration   = get_term_meta( $item->term_id, 'default_expiration', true );
		$new_status   = get_term_meta( $item->term_id, 'new_listing_status', true );
		$edit_status  = directorist_get_listing_edit_status( $item->term_id );
		$is_default   = get_term_meta( $item->term_id, '_default', true );
		$config       = directorist_get_directory_general_settings( $item->term_id );

		$data = array(
			'id'              => (int) $item->term_id,
			'name'            => $item->name,
			'slug'            => $item->slug,
			'icon'            => null,
			'image_url'       => null,
			'count'           => (int) $item->count,
			'is_default'      => (bool) $is_default,
			'new_status'      => $new_status,
			'edit_status'     => $edit_status,
			'expiration_days' => (int) $expiration,
			'date_created'    => directorist_rest_prepare_date_response( $date_created ),
		);

		if ( ! empty( $config['icon'] ) ) {
			$data['icon'] = $config['icon'];
		}

		if ( ! empty( $config['preview_image'] ) ) {
			$data['image_url'] = $config['preview_image'];
		}

		$context  = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );

		$response->add_links( $this->prepare_links( $item, $request ) );

		/**
		 * Filter a term item returned from the API.
		 *
		 * Allows modification of the term data right before it is returned.
		 *
		 * @param WP_REST_Response  $response  The response object.
		 * @param object            $item      The original term object.
		 * @param WP_REST_Request   $request   Request used to generate the response.
		 */
		return apply_filters( "directorist_rest_prepare_{$this->taxonomy}", $response, $item, $request );
	}

	/**
	 * Get the Category schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => $this->taxonomy,
			'type'       => 'object',
			'properties' => array(
				'id'          => array(
					'description' => __( 'Unique identifier for the resource.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'name'        => array(
					'description' => __( 'Category name.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'slug'        => array(
					'description' => __( 'An alphanumeric identifier for the resource unique to its type.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_title',
					),
				),
				'image_url'    => array(
					'description' => __( 'Preview image url.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'icon' => array(
					'description' => __( 'Icon class.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'count' => array(
					'description' => __( 'Number of published listings for the resource.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'is_default' => array(
					'description' => __( 'Default directory status.', 'directorist' ),
					'type'        => 'boolean',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
				),
				'new_status' => array(
					'description' => __( 'Newly created listing status under this directory.', 'directorist' ),
					'type'        => 'string',
					'default'     => 'pending',
					'enum'        => array(
						'pending',
						'publish',
					),
					'context'     => array( 'view', 'edit' ),
				),
				'edit_status' => array(
					'description' => __( 'Edited listing status under this directory.', 'directorist' ),
					'type'        => 'string',
					'default'     => 'pending',
					'enum'        => array(
						'pending',
						'publish',
					),
					'context'     => array( 'view', 'edit' ),
				),
				'expiration_days' => array(
					'description' => __( 'Validity days for listings under this directory.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'date_created'      => array(
					'description' => __( "The date the directory was created, in the site's timezone.", 'directorist' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}

	/**
	 * Update term meta fields.
	 *
	 * @param WP_Term         $term    Term object.
	 * @param WP_REST_Request $request Request instance.
	 * @return bool|WP_Error
	 *
	 */
	protected function update_term_meta_fields( $term, $request ) {
		return true;
	}
}
