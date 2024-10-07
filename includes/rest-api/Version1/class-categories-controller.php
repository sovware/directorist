<?php
/**
 * Listing Categories Rest Controller.
 *
 * @package Directorist\Rest_Api
 * @version  1.0.0
 */

namespace Directorist\Rest_Api\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use WP_Error;

/**
 * REST API Listing Categories controller class.
 *
 * @package Directorist\Rest_Api
 * @extends Directorist\Rest_Api\Controllers\Version1\Terms_Controller
 */
class Categories_Controller extends Terms_Controller {

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'listings/categories';

	/**
	 * Taxonomy.
	 *
	 * @var string
	 */
	protected $taxonomy = ATBDP_CATEGORY;

	/**
	 * Prepare a single listing category output for response.
	 *
	 * @param WP_Term         $item    Term object.
	 * @param WP_REST_Request $request Request instance.
	 * @return WP_REST_Response
	 */
	public function prepare_item_for_response( $item, $request ) {
		// Category icon.
		$icon = get_term_meta( $item->term_id, 'category_icon', true );

		$data = array(
			'id'          => (int) $item->term_id,
			'name'        => $item->name,
			'slug'        => $item->slug,
			'parent'      => (int) $item->parent,
			'description' => $item->description,
			'image'       => null,
			'icon'        => $icon,
			'directory'   => null,
			'count'       => (int) $item->count,
		);

		// Category directory type.
		if ( directorist_is_multi_directory_enabled() ) {
			$directory = get_term_meta( $item->term_id, '_directory_type', true );
			if ( ! empty( $directory ) && is_array( $directory ) ) {
				$data['directory'] = array_map( 'absint', $directory );
			}
		}

		// Category image.
		$image_id = get_term_meta( $item->term_id, 'image', true );
		if ( $image_id && ! empty( $attachment = get_post( $image_id ) ) ) {
			$data['image'] = array(
				'id'                => (int) $image_id,
				'date_created'      => directorist_rest_prepare_date_response( $attachment->post_date ),
				'date_created_gmt'  => directorist_rest_prepare_date_response( $attachment->post_date_gmt ),
				'date_modified'     => directorist_rest_prepare_date_response( $attachment->post_modified ),
				'date_modified_gmt' => directorist_rest_prepare_date_response( $attachment->post_modified_gmt ),
				'src'               => wp_get_attachment_url( $image_id ),
				'name'              => get_the_title( $attachment ),
				'alt'               => get_post_meta( $image_id, '_wp_attachment_image_alt', true ),
			);
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
				'parent'      => array(
					'description' => __( 'The ID for the parent of the resource.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'description' => array(
					'description' => __( 'HTML description of the resource.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'wp_filter_post_kses',
					),
				),
				'image'       => array(
					'description' => __( 'Image data.', 'directorist' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'properties'  => array(
						'id'                => array(
							'description' => __( 'Image ID.', 'directorist' ),
							'type'        => 'integer',
							'context'     => array( 'view', 'edit' ),
						),
						'date_created'      => array(
							'description' => __( "The date the image was created, in the site's timezone.", 'directorist' ),
							'type'        => 'date-time',
							'context'     => array( 'view', 'edit' ),
							'readonly'    => true,
						),
						'date_created_gmt'  => array(
							'description' => __( 'The date the image was created, as GMT.', 'directorist' ),
							'type'        => 'date-time',
							'context'     => array( 'view', 'edit' ),
							'readonly'    => true,
						),
						'date_modified'     => array(
							'description' => __( "The date the image was last modified, in the site's timezone.", 'directorist' ),
							'type'        => 'date-time',
							'context'     => array( 'view', 'edit' ),
							'readonly'    => true,
						),
						'date_modified_gmt' => array(
							'description' => __( 'The date the image was last modified, as GMT.', 'directorist' ),
							'type'        => 'date-time',
							'context'     => array( 'view', 'edit' ),
							'readonly'    => true,
						),
						'src'               => array(
							'description' => __( 'Image URL.', 'directorist' ),
							'type'        => 'string',
							'format'      => 'uri',
							'context'     => array( 'view', 'edit' ),
						),
						'name'              => array(
							'description' => __( 'Image name.', 'directorist' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'alt'               => array(
							'description' => __( 'Image alternative text.', 'directorist' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
					),
				),
				'icon' => array(
					'description' => __( 'Icon class.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'directory' => array(
					'description' => __( 'Directory type ids for this resource.', 'directorist' ),
					'type'        => 'array',
					'items' => array(
						'type'   => 'integer',
					),
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'wp_parse_id_list',
					),
				),
				'count' => array(
					'description' => __( 'Number of published listings for the resource.', 'directorist' ),
					'type'        => 'integer',
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
	 * @since 3.5.5
	 */
	protected function update_term_meta_fields( $term, $request ) {
		$id = (int) $term->term_id;

		// Save category icon.
		if ( isset( $request['icon'] ) ) {
			update_term_meta( $id, 'category_icon', $request['icon'] );
		}

		// Save directory types
		if ( isset( $request['directory'] ) ) {
			if ( ! directorist_is_multi_directory_enabled() ) {
				return new WP_Error(
					'directorist_rest_invalid_directory_request',
					__( 'Multi directory is disabled.', 'directorist' ),
					array( 'status' => 400 )
				);
			}

			$directory_ids = $request['directory'];
			$directory     = get_terms( array(
				'include'                => $directory_ids,
				'taxonomy'               => ATBDP_TYPE,
				'update_term_meta_cache' => false,
				'hide_empty'             => false,
			) );

			if ( is_wp_error( $directory ) || empty( $directory ) ) {
				return new WP_Error(
					'directorist_rest_invalid_directory_ids',
					__( 'Directory do not exist.', 'directorist' ),
					array( 'status' => 400 )
				);
			}

			$_valid_directory     = wp_list_filter( $directory, array( 'taxonomy' => ATBDP_TYPE ) );
			$_valid_directory_ids = wp_list_pluck( $_valid_directory, 'term_id' );

			if ( count( $directory_ids ) !== count( $_valid_directory_ids ) ) {
				return new WP_Error(
					'directorist_rest_invalid_directory_ids',
					__( 'Invalid directory id.', 'directorist' ),
					array( 'status' => 400 )
				);
			}

			update_term_meta( $id, '_directory_type', $_valid_directory_ids );
		}

		// Save category image.
		if ( isset( $request['image'] ) ) {
			if ( empty( $request['image']['id'] ) && ! empty( $request['image']['src'] ) ) {
				$upload = directorist_rest_upload_image_from_url( esc_url_raw( $request['image']['src'] ) );

				if ( is_wp_error( $upload ) ) {
					return $upload;
				}

				$image_id = directorist_rest_set_uploaded_image_as_attachment( $upload );
			} else {
				$image_id = isset( $request['image']['id'] ) ? absint( $request['image']['id'] ) : 0;
			}

			// Check if image_id is a valid image attachment before updating the term meta.
			if ( $image_id && wp_attachment_is_image( $image_id ) ) {
				update_term_meta( $id, 'image', $image_id );

				// Set the image alt.
				if ( ! empty( $request['image']['alt'] ) ) {
					update_post_meta( $image_id, '_wp_attachment_image_alt', directorist_clean( $request['image']['alt'] ) );
				}

				// Set the image title.
				if ( ! empty( $request['image']['name'] ) ) {
					wp_update_post(
						array(
							'ID'         => $image_id,
							'post_title' => directorist_clean( $request['image']['name'] ),
						)
					);
				}
			} else {
				delete_term_meta( $id, 'image' );
			}
		}

		return true;
	}

	/**
	 * Get the query params for collections
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		if ( directorist_is_multi_directory_enabled() ) {
			$params['directory'] = array(
				'description' => __( 'Limit result set to specific directory type ids.', 'directorist' ),
				'type'        => 'array',
				'items'       => array(
					'type' => 'integer',
				),
				'validate_callback' => 'rest_validate_request_arg',
			);
		}

		return $params;
	}
}
