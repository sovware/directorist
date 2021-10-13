<?php
/**
 * User activities Rest Controller.
 *
 * @package Directorist\Rest_Api
 * @version  1.0.0
 */

namespace Directorist\Rest_Api\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use WP_Error;
use WP_REST_Server;

/**
 * User activities controller class.
 */
class User_Activities_Controller extends Abstract_Controller {

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'users/(?P<user_id>[\d]+)/favorite';

	/**
	 * Register the routes for terms.
	 */
	public function register_routes() {
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
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'update_item' ),
				'permission_callback' => array( $this, 'update_item_permissions_check' ),
				'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
			),
			array(
				'methods'             => WP_REST_Server::DELETABLE,
				'callback'            => array( $this, 'delete_item' ),
				'permission_callback' => array( $this, 'delete_item_permissions_check' ),
				'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::DELETABLE ),
			),
			'schema' => array( $this, 'get_public_item_schema' ),
		) );
	}

	/**
	 * Check if a given request has access to update an activity.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function update_item_permissions_check( $request ) {
		$permissions = $this->check_permissions( $request, 'edit' );
		if ( is_wp_error( $permissions ) ) {
			return $permissions;
		}

		if ( ! $permissions ) {
			return new WP_Error( 'directorist_rest_cannot_edit', __( 'Sorry, you are not allowed to edit this resource.', 'directorist' ), array( 'status' => rest_authorization_required_code() ) );
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
		return true; //TODO: remove when done!

		// Check permissions for a single user.
		$id = intval( $request['id'] );
		if ( $id ) {
			$user = get_userdata( $id );

			if ( empty( $user ) ) {
				return new WP_Error( 'directorist_rest_user_invalid', __( 'Resource does not exist.', 'directorist' ), array( 'status' => 404 ) );
			}

			return directorist_rest_check_user_permissions( $context, $user->ID );
		}

		return directorist_rest_check_user_permissions( $context );
	}

	/**
	 * Update a single activity.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function update_item( $request ) {
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

		$favorites = $this->get_favorites( $user_id );
		$favorites = array_merge( $favorites, [ $listing_id ] );
		$favorites = array_unique( $favorites );

		update_user_meta( $user_id, 'atbdp_favourites', $favorites );

		/**
		 * Fires after a user favorite is created or updated via the REST API.
		 *
		 * @param array           $favorites User favorites.
		 * @param WP_REST_Request $request   Request object.
		 * @param boolean         $creating  True when creating user, false when updating user.
		 */
		do_action( 'directorist_rest_insert_user_favorite', $favorites, $request, false );

		$request->set_param( 'context', 'edit' );
		$response = $this->prepare_item_for_response( $listing_id, $request );
		$response = rest_ensure_response( $response );

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

		$favorites = $this->get_favorites( $user_id );
		$favorites = array_diff( $favorites, [ $listing_id ] );

		update_user_meta( $user_id, 'atbdp_favourites', $favorites );

		$request->set_param( 'context', 'edit' );
		$response = $this->prepare_item_for_response( $listing_id, $request );

		/**
		 * Fires after a user favorite is deleted via the REST API.
		 *
		 * @param arary            $favorites User favorites.
		 * @param WP_REST_Response $response  The response returned from the API.
		 * @param WP_REST_Request  $request   The request sent to the API.
		 */
		do_action( 'directorist_rest_delete_user_favorite', $favorites, $response, $request );

		return $response;
	}

	/**
	 * Prepares a single user output for response.
	 *
	 * @param int             $listing_id    Listing id.
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function prepare_item_for_response( $listing_id, $request ) {
		$data     = [ $listing_id ];
		$context  = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );

		/**
		 * Filters user data returned from the REST API.
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param array            $listing_id     Listing id used to create response objects.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( 'directorist_rest_prepare_user_favorite', $response, $listing_id, $request );
	}

	public function get_favorites( $user_id ) {
		$favorites = get_user_meta( $user_id, 'atbdp_favourites', true );

		if ( ! empty( $favorites ) ) {
			$favorites = wp_parse_id_list( $favorites );
		} else {
			$favorites = [];
		}

		return $favorites;
	}

	/**
	 * Get the User's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'favorite',
			'type'       => 'object',
			'properties' => array(
				'id' => array(
					'description' => __( 'Unique identifier for the listing.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}
}
