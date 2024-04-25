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
	protected $rest_base = 'builder/(?P<builder_tab>[\w-]+)';

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
						'default'     => directorist_get_default_directory()
					),
					'builder_tab' => array(
						'description' => __( 'Directory builder tab id.', 'directorist' ),
						'type'        => 'string',
						'enum'        => array(
							'all-listings',
							'listing-form',
							'search-form',
							'single-listing',
						),
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<directory_id>[\d]+)',
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
							'all-listings',
							'listing-form',
							'search-form',
							'single-listing',
						),
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
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

		if ( 'listing-form' === $builder_tab ) {
			$data = $this->get_listing_form_data( $directory_id, $request );
		} else if ( 'single-listing' === $builder_tab ) {
			$data = $this->get_single_listing_data( $directory_id, $request );
		}



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
		$date_created = get_term_meta( $item->term_id, '_created_date', true );
		$expiration   = get_term_meta( $item->term_id, 'default_expiration', true );
		$new_status   = get_term_meta( $item->term_id, 'new_listing_status', true );
		$edit_status  = directorist_get_listing_edit_status( $item->term_id );
		$is_default   = get_term_meta( $item->term_id, '_default', true );
		$config       = directorist_get_directory_general_settings( $item->term_id );
		$data         = array(
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

	protected function get_single_listing_data( $directory_id, $request ) {
		return array(
			'groups' => directorist_get_single_listing_groups( $directory_id ),
			'fields' => directorist_get_single_listing_fields( $directory_id ),
		);
	}

	protected function get_listing_form_data( $directory_id, $request ) {
		return array(
			'groups' => directorist_get_listing_form_groups( $directory_id ),
			'fields' => directorist_get_listing_form_fields( $directory_id ),
		);
	}

	/**
	 * Get the Category schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => __( 'Directory Builder.', 'directorist' ),
			'type'       => 'object',
			'properties' => array(
				'groups'        => array(
					'description' => __( 'Listing field groups.', 'directorist' ),
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
					'description' => __( 'Listing item fields.', 'directorist' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'properties' => array(
						'[field_id]'        => array(
							'description' => __( 'Field map.', 'directorist' ),
							'type'        => 'object',
						),
					)
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}
}
