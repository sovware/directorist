<?php
/**
 * Directory builder rest controller.
 *
 * @package Directorist\Rest_Api
 * @version  1.0.0
 */

namespace Directorist\Rest_Api\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use WP_Error;
use WP_REST_Server;

/**
 * REST API directory builder controller class.
 *
 * @package Directorist\Rest_Api
 */
class Builder_Controller extends Abstract_Controller {

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'directories/(?P<directory_id>[\d]+)/builder/(?P<builder_tab>[\w-]+)';

	/**
	 * Register the routes for builder settings.
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				'args'   => array(
					'directory_id' => array(
						'description' => __( 'Directory id.', 'directorist' ),
						'type'        => 'integer',
					),
					'builder_tab' => array(
						'description' => __( 'Directory builder tab id.', 'directorist' ),
						'type'        => 'string',
						'enum'        => array(
							'add-form',
							'all-listings',
							'search-form',
							'single-listing',
						),
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(
						'context' => $this->get_context_param( array( 'default' => 'view' ) ),
					),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Get a single term from a taxonomy.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Request|WP_Error
	 */
	public function get_item( $request ) {
		$directory_id = (int) $request['directory_id'];

		if ( ! directorist_is_directory( $directory_id ) ) {
			return new WP_Error( 'directorist_rest_invalid_directory', __( 'Invalid directory id.', 'directorist' ), array( 'status' => 400 ) );
		}

		$builder_tab = $request['builder_tab'];

		$data = array();
		$data['groups'] = directorist_get_listing_form_groups( $directory_id );
		$data['fields'] = directorist_get_listing_form_fields( $directory_id );



		// $response = $this->prepare_item_for_response( $term, $request );

		// do_action( 'directorist_rest_after_query', 'get_term_item', $request, $id, $taxonomy );

		// $response = apply_filters( 'directorist_rest_response', $response, 'get_term_item', $request, $id, $taxonomy );

		return rest_ensure_response( $data );
	}

	public function get_item_permissions_check( $request ) {
		return true;
	}

	/**
	 * Prepare a single listing category output for response.
	 *
	 * @param WP_Term         $item    Term object.
	 * @param WP_REST_Request $request Request instance.
	 * @return WP_REST_Response
	 */
	public function prepare_item_for_response( $item, $request ) {

		// $data     = $this->add_additional_fields_to_object( $data, $request );
		// $data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );

		// $response->add_links( $this->prepare_links( $item, $request ) );

		/**
		 * Filter a term item returned from the API.
		 *
		 * Allows modification of the term data right before it is returned.
		 *
		 * @param WP_REST_Response  $response  The response object.
		 * @param object            $item      The original term object.
		 * @param WP_REST_Request   $request   Request used to generate the response.
		 */
		return $response; //apply_filters( "directorist_rest_prepare_{$this->taxonomy}", $response, $item, $request );
	}

	/**
	 * Get the Category schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		file_put_contents( __DIR__ . '/data.txt', print_r( $this, 1 ) );

		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => __( 'Directory Builder.', 'directorist' ),
			'type'       => 'object',
			'properties' => array(
				'groups'        => array(
					'description' => __( 'Groups.', 'directorist' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'label'  => array(
								'description' => __( 'Group label.', 'directorist' ),
								'type'        => 'string',
							),
							'fields'  => array(
								'description' => __( 'Group fields key.', 'directorist' ),
								'type'        => 'array',
								'items'       => array(
									'type' => 'string',
								)
							),
						),
					),
				),
				'fields'        => array(
					'description' => __( 'Fields.', 'directorist' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
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
}
