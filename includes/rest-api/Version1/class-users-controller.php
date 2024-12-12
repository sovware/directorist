<?php
/**
 * Users Rest Controller.
 *
 * @package Directorist\Rest_Api
 * @version  1.0.0
 */

namespace Directorist\Rest_Api\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use WP_Error;
use WP_User_Query;
use WP_REST_Server;

/**
 * Users controller class.
 */
class Users_Controller extends Abstract_Controller {

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'users';

	/**
	 * Register the routes for terms.
	 */
	public function register_routes() {
		register_rest_route( $this->namespace, '/' . $this->rest_base, array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
				'args'                => $this->get_collection_params(),
			),
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_item' ),
				'permission_callback' => array( $this, 'create_item_permissions_check' ),
				'args'                => array_merge( $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ), array(
					'email' => array(
						'required' => true,
						'type'     => 'string',
						'description' => __( 'New user email address.', 'directorist' ),
					),
					'username' => array(
						'required' => false,
						'description' => __( 'New user username.', 'directorist' ),
						'type'     => 'string',
					),
					'password' => array(
						'required' => true,
						'description' => __( 'New user password.', 'directorist' ),
						'type'     => 'string',
					),
				) ),
			),
			'schema' => array( $this, 'get_public_item_schema' ),
		) );

		register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', array(
			'args' => array(
				'id' => array(
					'description' => __( 'Unique identifier for the resource.', 'directorist' ),
					'type'        => 'integer',
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
				'args'                => array(
					'force' => array(
						'default'     => false,
						'type'        => 'boolean',
						'description' => __( 'Required to be true, as resource does not support trashing.', 'directorist' ),
					),
					'reassign' => array(
						'default'     => 0,
						'type'        => 'integer',
						'description' => __( 'ID to reassign posts to.', 'directorist' ),
					),
				),
			),
			'schema' => array( $this, 'get_public_item_schema' ),
		) );
	}

	/**
	 * Check if a given request has access to read the users.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_items_permissions_check( $request ) {
		$permissions = $this->check_permissions( $request, 'read' );
		if ( is_wp_error( $permissions ) ) {
			return $permissions;
		}

		if ( ! $permissions ) {
			return new WP_Error( 'directorist_rest_cannot_view', __( 'Sorry, you cannot list resources.', 'directorist' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return $permissions;
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

		if ( ! $permissions || ! get_option( 'users_can_register' ) ) {
			return new WP_Error( 'directorist_rest_cannot_create', __( 'Sorry, you are not allowed to create resources.', 'directorist' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return $permissions;
	}

	/**
	 * Check if a given request has access to read a user.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_item_permissions_check( $request ) {
		$permissions = $this->check_permissions( $request, 'read' );
		if ( is_wp_error( $permissions ) ) {
			return $permissions;
		}

		if ( ! $permissions ) {
			return new WP_Error( 'directorist_rest_cannot_view', __( 'Sorry, you cannot view this resource.', 'directorist' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return $permissions;
	}

	/**
	 * Check if a given request has access to update a user.
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

		return $permissions;
	}

	/**
	 * Check if a given request has access to delete a user.
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

		return $permissions;
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
	 * Get all users.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {
		$prepared_args = array();
		$prepared_args['exclude'] = $request['exclude'];
		$prepared_args['include'] = $request['include'];
		$prepared_args['order']   = $request['order'];
		$prepared_args['number']  = $request['per_page'];
		if ( ! empty( $request['offset'] ) ) {
			$prepared_args['offset'] = $request['offset'];
		} else {
			$prepared_args['offset'] = ( $request['page'] - 1 ) * $prepared_args['number'];
		}
		$orderby_possibles = $this->get_orderby_possibles();
		$prepared_args['orderby'] = $orderby_possibles[ $request['orderby'] ];
		$prepared_args['search']  = $request['search'];

		if ( '' !== $prepared_args['search'] ) {
			$prepared_args['search'] = '*' . $prepared_args['search'] . '*';
		}

		// Filter by email.
		if ( ! empty( $request['email'] ) ) {
			$prepared_args['search']         = $request['email'];
			$prepared_args['search_columns'] = array( 'user_email' );
		}

		// Filter by role.
		if ( 'all' !== $request['role'] ) {
			$prepared_args['role'] = $request['role'];
		}

		/**
		 * Filter arguments, before passing to WP_User_Query, when querying users via the REST API.
		 *
		 * @see https://developer.wordpress.org/reference/classes/wp_user_query/
		 *
		 * @param array           $prepared_args Array of arguments for WP_User_Query.
		 * @param WP_REST_Request $request       The current request.
		 */
		$prepared_args = apply_filters( 'directorist_rest_user_query', $prepared_args, $request );

		do_action( 'directorist_rest_before_query', 'get_user_items', $request, $prepared_args );

		$query = new WP_User_Query( $prepared_args );

		$users = array();
		foreach ( $query->results as $user ) {
			$data = $this->prepare_item_for_response( $user, $request );
			$users[] = $this->prepare_response_for_collection( $data );
		}

		$response = rest_ensure_response( $users );

		// Store pagination values for headers then unset for count query.
		$per_page = (int) $prepared_args['number'];
		$page = ceil( ( ( (int) $prepared_args['offset'] ) / $per_page ) + 1 );

		$prepared_args['fields'] = 'ID';

		$total_users = $query->get_total();
		if ( $total_users < 1 ) {
			// Out-of-bounds, run the query again without LIMIT for total count.
			unset( $prepared_args['number'], $prepared_args['offset'] );
			$count_query = new WP_User_Query( $prepared_args );
			$total_users = $count_query->get_total();
		}
		$response->header( 'X-WP-Total', (int) $total_users );
		$max_pages = ceil( $total_users / $per_page );
		$response->header( 'X-WP-TotalPages', (int) $max_pages );

		$base = add_query_arg( $request->get_query_params(), rest_url( sprintf( '/%s/%s', $this->namespace, $this->rest_base ) ) );
		if ( $page > 1 ) {
			$prev_page = $page - 1;
			if ( $prev_page > $max_pages ) {
				$prev_page = $max_pages;
			}
			$prev_link = add_query_arg( 'page', $prev_page, $base );
			$response->link_header( 'prev', $prev_link );
		}
		if ( $max_pages > $page ) {
			$next_page = $page + 1;
			$next_link = add_query_arg( 'page', $next_page, $base );
			$response->link_header( 'next', $next_link );
		}

		do_action( 'directorist_rest_after_query', 'get_user_items', $request, $prepared_args );

		$response = apply_filters( 'directorist_rest_response', $response, 'get_user_items', $request, $prepared_args );

		return $response;
	}

	/**
	 * Create a single user.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function create_item( $request ) {
		if ( ! empty( $request['id'] ) ) {
			return new WP_Error( 'directorist_rest_user_exists', __( 'Cannot create existing resource.', 'directorist' ), 400 );
		}

		if ( email_exists( $request['email'] ) ) {
			return new WP_Error( 'directorist_rest_user_email_exists', __( 'A resource is already registered.', 'directorist' ) );
		}

		// Create user.
		$user_data = array(
			'user_email' => $request['email'],
			'user_pass'  => $request['password'],
			'role'       => 'subscriber',
		);

		if ( isset( $request['username'] ) ) {
			$user_data['user_login'] = $request['username'];
		} else {
			$username = sanitize_user( current( explode( '@', $request['email'] ) ), true );

			// Ensure username is unique.
			$append = 1;
			$o_username = $username;

			while ( username_exists( $username ) ) {
				$username = $o_username . $append;
				$append++;
			}

			$user_data['user_login'] = $username;
		}

		do_action( 'directorist_rest_before_query', 'create_user_item', $request, $user_data );

		$user_id = wp_insert_user( $user_data );
		if ( is_wp_error( $user_id ) ) {
			return $user_id;
		}

		$user_data = get_userdata( $user_id );
		$this->update_user_meta_fields( $user_data, $request );
		$this->update_additional_fields_for_object( $user_data, $request );

		/**
		 * Fires after a user is created or updated via the REST API.
		 *
		 * @param WP_User         $user_data Data used to create the user.
		 * @param WP_REST_Request $request   Request object.
		 * @param boolean         $creating  True when creating user, false when updating user.
		 */
		do_action( 'directorist_rest_insert_user', $user_data, $request, true );

		$request->set_param( 'context', 'edit' );
		$response = $this->prepare_item_for_response( $user_data, $request );
		$response = rest_ensure_response( $response );
		$response->set_status( 201 );
		$response->header( 'Location', rest_url( sprintf( '/%s/%s/%d', $this->namespace, $this->rest_base, $user_id ) ) );

		do_action( 'directorist_rest_after_query', 'create_user_item', $request );

		$response = apply_filters( 'directorist_rest_response', $response, 'create_user_item', $request );

		return $response;
	}

	/**
	 * Get a single user.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_item( $request ) {
		$id = (int) $request['id'];

		do_action( 'directorist_rest_before_query', 'get_user_item', $request, $id );

		$user_data = get_userdata( $id );

		if ( empty( $id ) || empty( $user_data->ID ) ) {
			return new WP_Error( 'directorist_rest_invalid_id', __( 'Invalid resource ID.', 'directorist' ), array( 'status' => 404 ) );
		}

		$user_data = $this->prepare_item_for_response( $user_data, $request );
		$response  = rest_ensure_response( $user_data );

		do_action( 'directorist_rest_after_query', 'get_user_item', $request, $id );

		$response = apply_filters( 'directorist_rest_response', $response, 'get_user_item', $request, $id );

		return $response;
	}

	/**
	 * Update a single user.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function update_item( $request ) {
		$id = (int) $request['id'];

		do_action( 'directorist_rest_before_query', 'update_user_item', $request, $id );

		$user_data = get_userdata( $id );

		if ( empty( $user_data ) ) {
			return new WP_Error( 'directorist_rest_invalid_id', __( 'Invalid resource ID.', 'directorist' ), 400 );
		}

		if ( ! empty( $request['email'] ) && email_exists( $request['email'] ) && $request['email'] !== $user_data->user_email ) {
			return new WP_Error( 'directorist_rest_user_invalid_email', __( 'Email address is invalid.', 'directorist' ), 400 );
		}

		if ( ! empty( $request['username'] ) && $request['username'] !== $user_data->user_login ) {
			return new WP_Error( 'directorist_rest_user_invalid_argument', __( "Username isn't editable.", 'directorist' ), 400 );
		}

		$updated_user_data = array(
			'ID' => $user_data->ID
		);

		// User email.
		if ( isset( $request['email'] ) ) {
			$updated_user_data['user_email'] = sanitize_email( $request['email'] );
		}

		// User password.
		if ( isset( $request['password'] ) ) {
			$updated_user_data['user_pass'] = $request['password'];
		}

		$this->update_user_meta_fields( $user_data, $request );
		wp_update_user( $updated_user_data );

		$user_data = get_userdata( $user_data->ID );
		$this->update_additional_fields_for_object( $user_data, $request );

		if ( ! is_user_member_of_blog( $user_data->ID ) ) {
			$user_data->add_role( 'subscriber' );
		}

		/**
		 * Fires after a user is created or updated via the REST API.
		 *
		 * @param WP_User         $user  Data used to create the user.
		 * @param WP_REST_Request $request   Request object.
		 * @param boolean         $creating  True when creating user, false when updating user.
		 */
		do_action( 'directorist_rest_insert_user', $user_data, $request, false );

		$request->set_param( 'context', 'edit' );
		$response = $this->prepare_item_for_response( $user_data, $request );
		$response = rest_ensure_response( $response );

		do_action( 'directorist_rest_after_query', 'update_user_item', $request, $id );

		$response = apply_filters( 'directorist_rest_response', $response, 'update_user_item', $request );

		return $response;
	}

	/**
	 * Delete a single user.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function delete_item( $request ) {
		$id       = (int) $request['id'];
		$reassign = false === $request['reassign'] ? null : absint( $request['reassign'] );
		$force    = isset( $request['force'] ) ? (bool) $request['force'] : false;

		// We don't support trashing for this type, error out.
		if ( ! $force ) {
			return new WP_Error(
				'directorist_rest_trash_not_supported',
				/* translators: %s: force=true */
				sprintf( __( "Users do not support trashing. Set '%s' to delete.", 'directorist' ), 'force=true' ),
				array( 'status' => 501 )
			);
		}

		do_action( 'directorist_rest_before_query', 'delete_user_item', $request, $id );

		$user_data = get_userdata( $id );
		if ( ! $user_data ) {
			return new WP_Error( 'directorist_rest_invalid_id', __( 'Invalid resource id.', 'directorist' ), array( 'status' => 400 ) );
		}

		if ( ! empty( $reassign ) ) {
			if ( $reassign === $id || ! get_userdata( $reassign ) ) {
				return new WP_Error( 'directorist_rest_user_invalid_reassign', __( 'Invalid resource id for reassignment.', 'directorist' ), array( 'status' => 400 ) );
			}
		}

		$request->set_param( 'context', 'edit' );
		$response = $this->prepare_item_for_response( $user_data, $request );

		/** Include admin user functions to get access to wp_delete_user() */
		require_once ABSPATH . 'wp-admin/includes/user.php';

		$result = wp_delete_user( $id, $reassign );

		if ( ! $result ) {
			return new WP_Error(
				'directorist_rest_cannot_delete',
				__( 'The resource cannot be deleted.', 'directorist' ),
				array( 'status' => 500 )
			);
		}

		/**
		 * Fires after a user is deleted via the REST API.
		 *
		 * @param WP_User          $user_data User data.
		 * @param WP_REST_Response $response  The response returned from the API.
		 * @param WP_REST_Request  $request   The request sent to the API.
		 */
		do_action( 'directorist_rest_delete_user', $user_data, $response, $request );

		do_action( 'directorist_rest_after_query', 'delete_user_item', $request, $id );

		$response = apply_filters( 'directorist_rest_response', $response, 'delete_user_item', $request );

		return $response;
	}

	/**
	 * Prepares a single user output for response.
	 *
	 * @param WP_User         $user    User object.
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function prepare_item_for_response( $user, $request ) {
		$id     = $user->ID;
		$schema = $this->get_item_schema();
		$data   = array(
			'id'             => $id,
			'date_created'   => directorist_rest_prepare_date_response( $user->user_registered ),
			'name'           => $user->display_name,
			'username'       => null, //$user->user_login,
			'nickname'       => null, //$user->nickname,
			'first_name'     => $user->first_name,
			'last_name'      => $user->last_name,
			'description'    => $user->description,
			'email'          => is_user_logged_in() ? $user->user_email : null,
			'url'            => $user->user_url,
			'phone'          => get_user_meta( $id, 'atbdp_phone', true ),
			'address'        => get_user_meta( $id, 'address', true ),
			'avater'         => null,
			'avatar'         => null,
			'social_links'   => null,
			'favorite'       => null,
			'roles'          => null, //array_values( $user->roles ),
			'listings_count' => (int) count_user_posts( $id, ATBDP_POST_TYPE, true ),
		);

		foreach ( array_keys( $schema['properties']['social_links']['properties'] ) as $field ) {
			$value = get_user_meta( $id, 'atbdp_' . $field, true );
			$data['social_links'][ $field ] = ( ! empty( $value ) ? $value : null );
		}

		// User 'avatar'.
		$image_id = get_user_meta( $id, 'pro_pic', true );
		if ( $image_id && ! empty( $attachment = get_post( $image_id ) ) ) {
			$data['avatar'] = array(
				'id'                => (int) $image_id,
				'date_created'      => directorist_rest_prepare_date_response( $attachment->post_date ),
				'date_created_gmt'  => directorist_rest_prepare_date_response( $attachment->post_date_gmt ),
				'date_modified'     => directorist_rest_prepare_date_response( $attachment->post_modified ),
				'date_modified_gmt' => directorist_rest_prepare_date_response( $attachment->post_modified_gmt ),
				'src'               => wp_get_attachment_url( $image_id ),
			);

			$data['avater'] = $data['avatar'];
		}

		// User favorite.
		$favorites = directorist_get_user_favorites( $id );
		if ( ! empty( $favorites ) ) {
			$data['favorite'] = $favorites;
		}

		$context  = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );

		$response->add_links( $this->prepare_links( $user ) );

		/**
		 * Filters user data returned from the REST API.
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param WP_User          $user     User object used to create response.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( 'directorist_rest_prepare_user', $response, $user, $request );
	}

	/**
	 * Update user meta fields.
	 *
	 * @param WP_User $user
	 * @param WP_REST_Request $request
	 */
	protected function update_user_meta_fields( $user, $request ) {
		$schema = $this->get_item_schema();
		$id     = $user->ID;

		// Save first name.
		if ( isset( $request['first_name'] ) ) {
			update_user_meta( $id, 'first_name', directorist_clean( $request['first_name'] ) );
		}

		// Save last name.
		if ( isset( $request['last_name'] ) ) {
			update_user_meta( $id, 'last_name', directorist_clean( $request['last_name'] ) );
		}

		// Save description.
		if ( isset( $request['description'] ) ) {
			update_user_meta( $id, 'description', directorist_clean( $request['description'] ) );
		}

		// Save address.
		if ( isset( $request['address'] ) ) {
			update_user_meta( $id, 'address', directorist_clean( $request['address'] ) );
		}

		// Save phone number.
		if ( isset( $request['phone'] ) ) {
			update_user_meta( $id, 'atbdp_phone', directorist_clean( $request['phone'] ) );
		}

		// Save social links.
		if ( isset( $request['social_links'] ) ) {
			foreach ( array_keys( $schema['properties']['social_links']['properties'] ) as $field ) {
				if ( isset( $request['social_links'][ $field ] ) ) {
					update_user_meta( $id, 'atbdp_' . $field, esc_url_raw( $request['social_links'][ $field ] ) );
				}
			}
		}

		// Save user avater.
		if ( isset( $request['avater'] ) ) {
			if ( empty( $request['avater']['id'] ) && ! empty( $request['avater']['src'] ) ) {
				$upload = directorist_rest_upload_image_from_url( esc_url_raw( $request['avater']['src'] ) );

				if ( is_wp_error( $upload ) ) {
					return $upload;
				}

				$image_id = directorist_rest_set_uploaded_image_as_attachment( $upload );
			} else {
				$image_id = isset( $request['avater']['id'] ) ? absint( $request['avater']['id'] ) : 0;
			}

			// Check if image_id is a valid image attachment before updating the term meta.
			if ( $image_id && wp_attachment_is_image( $image_id ) ) {
				update_user_meta( $id, 'pro_pic', $image_id );
			} else {
				delete_term_meta( $id, 'pro_pic' );
			}
		}
	}

	/**
	 * Prepare links for the request.
	 *
	 * @param WP_User $user User object.
	 * @return array Links for the given user.
	 */
	protected function prepare_links( $user ) {
		$links = array(
			'self' => array(
				'href' => rest_url( sprintf( '/%s/%s/%d', $this->namespace, $this->rest_base, $user->ID ) ),
			),
			'collection' => array(
				'href' => rest_url( sprintf( '/%s/%s', $this->namespace, $this->rest_base ) ),
			),
		);

		return $links;
	}

	/**
	 * Get the User's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'user',
			'type'       => 'object',
			'properties' => array(
				'id' => array(
					'description' => __( 'Unique identifier for the resource.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_created'    => array(
					'description' => __( 'The date the user was created, as GMT.', 'directorist' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'name'           => array(
					'description' => __( 'The display name for the user.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view' ),
				),
				'username' => array(
					'description' => __( 'User login name.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_user',
					),
				),
				'nickname'           => array(
					'description' => __( 'The nickname for the user.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view' ),
				),
				'first_name' => array(
					'description' => __( 'User first name.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'last_name' => array(
					'description' => __( 'User last name.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'description'        => array(
					'description' => __( 'Description of the user.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'email' => array(
					'description' => __( 'The email address for the user.', 'directorist' ),
					'type'        => 'string',
					'format'      => 'email',
					'context'     => array( 'view', 'edit' ),
				),
				'url' => array(
					'description' => __( 'The website url for the user.', 'directorist' ),
					'type'        => 'string',
					'format'      => 'url',
					'context'     => array( 'view', 'edit' ),
				),
				'password' => array(
					'description' => __( 'User password.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'edit' ),
				),
				'address'        => array(
					'description' => __( 'Address of the user.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'phone'        => array(
					'description' => __( 'Phone number of the user.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'avatar'       => array(
					'description' => __( 'User avatar image data.', 'directorist' ),
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
					),
				),
				'avater'       => array(
					'description' => __( 'User avatar image data.', 'directorist' ),
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
					),
				),
				'social_links' => array(
					'description' => __( 'User social links.', 'directorist' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'properties'  => array(
						'facebook' => array(
							'description' => __( 'Facebook profile link.', 'directorist' ),
							'type'        => 'string',
							'format'      => 'uri',
							'context'     => array( 'view', 'edit' ),
						),
						'twitter' => array(
							'description' => __( 'Twitter profile link.', 'directorist' ),
							'type'        => 'string',
							'format'      => 'uri',
							'context'     => array( 'view', 'edit' ),
						),
						'linkedin' => array(
							'description' => __( 'LinkedIn profile link.', 'directorist' ),
							'type'        => 'string',
							'format'      => 'uri',
							'context'     => array( 'view', 'edit' ),
						),
						'youtube' => array(
							'description' => __( 'Youtube profile link.', 'directorist' ),
							'type'        => 'string',
							'format'      => 'uri',
							'context'     => array( 'view', 'edit' ),
						),
					),
				),
				'favorite' =>  array(
					'description' => __( 'User favorite listing ids.', 'directorist' ),
					'type'        => 'array',
					'items'       => array(
						'type' => 'integer',
					),
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
				'listings_count' => array(
					'description' => __( 'Quantity of listings created by the user.', 'directorist' ),
					'type'        => 'integer',
					'default'     => 0,
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}

	/**
	 * Get role names.
	 *
	 * @return array
	 */
	protected function get_role_names() {
		global $wp_roles;

		return array_keys( $wp_roles->role_names );
	}

	/**
	 * Get the query params for collections.
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		$params['context']['default'] = 'view';

		$params['exclude'] = array(
			'description'       => __( 'Ensure result set excludes specific IDs.', 'directorist' ),
			'type'              => 'array',
			'items'             => array(
				'type'          => 'integer',
			),
			'default'           => array(),
			'sanitize_callback' => 'wp_parse_id_list',
		);
		$params['include'] = array(
			'description'       => __( 'Limit result set to specific IDs.', 'directorist' ),
			'type'              => 'array',
			'items'             => array(
				'type'          => 'integer',
			),
			'default'           => array(),
			'sanitize_callback' => 'wp_parse_id_list',
		);
		$params['offset'] = array(
			'description'        => __( 'Offset the result set by a specific number of items.', 'directorist' ),
			'type'               => 'integer',
			'sanitize_callback'  => 'absint',
			'validate_callback'  => 'rest_validate_request_arg',
		);
		$params['order'] = array(
			'default'            => 'asc',
			'description'        => __( 'Order sort attribute ascending or descending.', 'directorist' ),
			'enum'               => array( 'asc', 'desc' ),
			'sanitize_callback'  => 'sanitize_key',
			'type'               => 'string',
			'validate_callback'  => 'rest_validate_request_arg',
		);
		$params['orderby'] = array(
			'default'            => 'name',
			'description'        => __( 'Sort collection by object attribute.', 'directorist' ),
			'enum'               => array_keys( $this->get_orderby_possibles() ),
			'sanitize_callback'  => 'sanitize_key',
			'type'               => 'string',
			'validate_callback'  => 'rest_validate_request_arg',
		);
		$params['email'] = array(
			'description'        => __( 'Limit result set to resources with a specific email.', 'directorist' ),
			'type'               => 'string',
			'format'             => 'email',
			'validate_callback'  => 'rest_validate_request_arg',
		);
		$params['role'] = array(
			'description'        => __( 'Limit result set to resources with a specific role.', 'directorist' ),
			'type'               => 'string',
			'default'            => 'all',
			'enum'               => array_merge( array( 'all' ), $this->get_role_names() ),
			'validate_callback'  => 'rest_validate_request_arg',
		);
		return $params;
	}

	protected function get_orderby_possibles() {
		return array(
			'id'              => 'ID',
			'include'         => 'include',
			'name'            => 'display_name',
			'registered_date' => 'registered',
			// 'listings_count'  => 'listings_count',
		);
	}
}
