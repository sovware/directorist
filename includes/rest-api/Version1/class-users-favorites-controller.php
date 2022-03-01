<?php
/**
 * User favorites Rest Controller.
 *
 * @package Directorist\Rest_Api
 * @version  1.0.0
 */

namespace Directorist\Rest_Api\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use WP_Error;
use WP_REST_Server;

/**
 * User favorites controller class.
 */
class User_Favorites_Controller extends Abstract_Controller {

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'users/(?P<user_id>[\d]+)/favorites';

	/**
	 * Register the routes for terms.
	 */
	public function register_routes() {
		register_rest_route( $this->namespace, '/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => array_merge(
						$this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
						array(
							'id' => array(
								'type'        => 'integer',
								'description' => __( 'Directory listing id.', 'directorist' ),
								'required'    => true,
							),
						)
					),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', array(
			'args' => array(
				'user_id' => array(
					'description' => __( 'User id.', 'directorist' ),
					'type'        => 'integer',
				),
				'id' => array(
					'description' => __( 'Listing id.', 'directorist' ),
					'type'        => 'integer',
				),
			),
			array(
				'methods'             => WP_REST_Server::DELETABLE,
				'callback'            => array( $this, 'delete_item' ),
				'permission_callback' => array( $this, 'delete_item_permissions_check' ),
				'args'                => array(),
			),
			'schema' => array( $this, 'get_public_item_schema' ),
		) );
	}

	/**
	 * Check if a given request has access to create a user.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function create_item_permissions_check( $request ) {
		$permissions = $this->check_permissions( $request, 'create' );
		if ( is_wp_error( $permissions ) ) {
			return $permissions;
		}

		if ( ! $permissions ) {
			return new WP_Error( 'directorist_rest_cannot_create', __( 'Sorry, you are not allowed to favorite resources.', 'directorist' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Check if a given request has access to delete an activity.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function delete_item_permissions_check( $request ) {
		$permissions = $this->check_permissions( $request, 'delete' );
		if ( is_wp_error( $permissions ) ) {
			return $permissions;
		}

		if ( ! $permissions ) {
			return new WP_Error( 'directorist_rest_cannot_delete', __( 'Sorry, you are not allowed to delete this resource.', 'directorist' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Check permissions.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @param string          $context Request context.
	 * @return bool|WP_Error
	 */
	protected function check_permissions( $request, $context = 'read' ) {
		// Check permissions for a single user.
		$id = intval( $request['user_id'] );
		if ( $id ) {
			$user = get_userdata( $id );

			if ( empty( $user ) ) {
				return new WP_Error( 'directorist_rest_user_invalid', __( 'Resource does not exist.', 'directorist' ), array( 'status' => 404 ) );
			}

			return directorist_rest_check_user_favorite_permissions( $context, $user->ID );
		}

		return directorist_rest_check_user_favorite_permissions( $context );
	}

	/**
	 * Create user favorites.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function create_item( $request ) {
		$user_id   = (int) $request['user_id'];
		$user_data = get_userdata( $user_id );

		if ( empty( $user_data ) ) {
			return new WP_Error( 'directorist_rest_invalid_user_id', __( 'Invalid user ID.', 'directorist' ), 400 );
		}

		$listing_id   = (int) $request['id'];
		$listing_data = get_post( $listing_id );

		if ( empty( $listing_data ) || get_post_type( $listing_data ) != ATBDP_POST_TYPE ) {
			return new WP_Error( 'directorist_rest_invalid_listing_id', __( 'Invalid listing ID.', 'directorist' ), 400 );
		}

		$old_favorites = directorist_get_user_favorites( $user_id );
		$new_favorites = directorist_add_user_favorites( $user_id, $listing_id );

		$data = array(
			'id'            => $listing_id,
			'old_favorites' => $old_favorites,
			'new_favorites' => $new_favorites,
		);

		/**
		 * Fires after a user favorite is created or updated via the REST API.
		 *
		 * @param array           $new_favorites User favorites.
		 * @param WP_REST_Request $request   Request object.
		 * @param boolean         $creating  True when creating user, false when updating user.
		 */
		do_action( 'directorist_rest_insert_user_favorite', $new_favorites, $request, false );

		$request->set_param( 'context', 'edit' );
		$response = $this->prepare_item_for_response( $data, $request );
		$response = rest_ensure_response( $response );
		$response->set_status( 201 );

		return $response;
	}

	/**
	 * Delete a single activity.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function delete_item( $request ) {
		$user_id   = (int) $request['user_id'];
		$user_data = get_userdata( $user_id );

		if ( empty( $user_data ) ) {
			return new WP_Error( 'directorist_rest_invalid_user_id', __( 'Invalid user ID.', 'directorist' ), 400 );
		}

		$listing_id   = (int) $request['id'];
		$listing_data = get_post( $listing_id );

		if ( empty( $listing_data ) || get_post_type( $listing_data ) != ATBDP_POST_TYPE ) {
			return new WP_Error( 'directorist_rest_invalid_listing_id', __( 'Invalid listing ID.', 'directorist' ), 400 );
		}

		$old_favorites = directorist_get_user_favorites( $user_id );

		directorist_delete_user_favorites( $user_id, $listing_id );

		$new_favorites = directorist_get_user_favorites( $user_id );

		$data = array(
			'id'            => $listing_id,
			'old_favorites' => $old_favorites,
			'new_favorites' => $new_favorites,
		);

		$request->set_param( 'context', 'edit' );
		$response = $this->prepare_item_for_response( $data, $request );

		/**
		 * Fires after a user favorite is deleted via the REST API.
		 *
		 * @param arary            $favorites User favorites.
		 * @param WP_REST_Response $response  The response returned from the API.
		 * @param WP_REST_Request  $request   The request sent to the API.
		 */
		do_action( 'directorist_rest_delete_user_favorite', $new_favorites, $response, $request );

		return $response;
	}

	/**
	 * Prepares a single user output for response.
	 *
	 * @param array           $data
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function prepare_item_for_response( $data, $request ) {
		$context  = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );

		/**
		 * Filters user data returned from the REST API.
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param array          $data     Favorites listings id.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( 'directorist_rest_prepare_user_favorite', $response, $data, $request );
	}

	/**
	 * Get the User's favorite schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'favorites',
			'type'       => 'object',
			'properties' => array(
				'id' => array(
					'description' => __( 'User favorite listing id.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}
}
/* This code is retrieving the user meta data for the user ID of the user that is logged
in. */
