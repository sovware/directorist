<?php
/**
 * Listing Reviews Rest Controller.
 *
 * Handles requests to /listings/reviews.
 *
 * @package Directorist\Rest_Api
 * @version  1.0.0
 */

namespace Directorist\Rest_Api\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use WP_Error;
use WP_REST_Server;
use WP_Comment_Query;
use Directorist\Review\Comment_Meta;

/**
 * REST API Listing Reviews Controller Class.
 *
 * @extends Abstract_Controller
 */
class Listing_Reviews_Controller extends Abstract_Controller {

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'listings/reviews';

	/**
	 * Register the routes for listings reviews.
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace, '/' . $this->rest_base, array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		register_rest_route(
			$this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', array(
				'args'   => array(
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
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Check whether a given request has permission to read listing reviews.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_items_permissions_check( $request ) {
		if ( ! directorist_rest_check_listing_reviews_permissions( 'read' ) ) {
			return new WP_Error( 'directorist_rest_cannot_view', __( 'Sorry, you cannot list resources.', 'directorist' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Check if a given request has access to read a listing review.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_item_permissions_check( $request ) {
		$id     = (int) $request['id'];
		$review = get_comment( $id );

		if ( $review && ! directorist_rest_check_listing_reviews_permissions( 'read', $review->comment_ID ) ) {
			return new WP_Error( 'directorist_rest_cannot_view', __( 'Sorry, you cannot view this resource.', 'directorist' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Get all reviews.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return array|WP_Error
	 */
	public function get_items( $request ) {
		// Retrieve the list of registered collection query parameters.
		$registered = $this->get_collection_params();

		/*
		 * This array defines mappings between public API query parameters whose
		 * values are accepted as-passed, and their internal WP_Query parameter
		 * name equivalents (some are the same). Only values which are also
		 * present in $registered will be set.
		 */
		$parameter_mappings = array(
			'reviewer'         => 'author__in',
			'reviewer_email'   => 'author_email',
			'reviewer_exclude' => 'author__not_in',
			'exclude'          => 'comment__not_in',
			'include'          => 'comment__in',
			'offset'           => 'offset',
			'order'            => 'order',
			'per_page'         => 'number',
			'listing'          => 'post__in',
			'search'           => 'search',
			'status'           => 'status',
		);

		$prepared_args = array();

		/*
		 * For each known parameter which is both registered and present in the request,
		 * set the parameter's value on the query $prepared_args.
		 */
		foreach ( $parameter_mappings as $api_param => $wp_param ) {
			if ( isset( $registered[ $api_param ], $request[ $api_param ] ) ) {
				$prepared_args[ $wp_param ] = $request[ $api_param ];
			}
		}

		// Ensure certain parameter values default to empty strings.
		foreach ( array( 'author_email', 'search' ) as $param ) {
			if ( ! isset( $prepared_args[ $param ] ) ) {
				$prepared_args[ $param ] = '';
			}
		}

		if ( isset( $registered['orderby'] ) ) {
			$prepared_args['orderby'] = $this->normalize_query_param( $request['orderby'] );
		}

		if ( isset( $prepared_args['status'] ) ) {
			$prepared_args['status'] = 'approved' === $prepared_args['status'] ? 'approve' : $prepared_args['status'];
		}

		$prepared_args['no_found_rows'] = false;
		$prepared_args['date_query']    = array();

		// Set before into date query. Date query must be specified as an array of an array.
		if ( isset( $registered['before'], $request['before'] ) ) {
			$prepared_args['date_query'][0]['before'] = $request['before'];
		}

		// Set after into date query. Date query must be specified as an array of an array.
		if ( isset( $registered['after'], $request['after'] ) ) {
			$prepared_args['date_query'][0]['after'] = $request['after'];
		}

		if ( isset( $registered['page'] ) && empty( $request['offset'] ) ) {
			$prepared_args['offset'] = $prepared_args['number'] * ( absint( $request['page'] ) - 1 );
		}

		/**
		 * Filters arguments, before passing to WP_Comment_Query, when querying reviews via the REST API.
		 *
		 * @link https://developer.wordpress.org/reference/classes/wp_comment_query/
		 * @param array           $prepared_args Array of arguments for WP_Comment_Query.
		 * @param WP_REST_Request $request       The current request.
		 */
		$prepared_args = apply_filters( 'directorist_rest_listing_review_query', $prepared_args, $request );

		// Make sure that returns only reviews.
		$prepared_args['type']      = 'review';
		$prepared_args['post_type'] = ATBDP_POST_TYPE;

		// Query reviews.
		$query        = new WP_Comment_Query();
		$query_result = $query->query( $prepared_args );
		$reviews      = array();

		foreach ( $query_result as $review ) {
			if ( ! directorist_rest_check_listing_reviews_permissions( 'read', $review->comment_ID ) ) {
				continue;
			}

			$data      = $this->prepare_item_for_response( $review, $request );
			$reviews[] = $this->prepare_response_for_collection( $data );
		}

		$total_reviews = (int) $query->found_comments;
		$max_pages     = (int) $query->max_num_pages;

		if ( $total_reviews < 1 ) {
			// Out-of-bounds, run the query again without LIMIT for total count.
			unset( $prepared_args['number'], $prepared_args['offset'] );

			$query                  = new WP_Comment_Query();
			$prepared_args['count'] = true;

			$total_reviews = $query->query( $prepared_args );
			$max_pages     = ceil( $total_reviews / $request['per_page'] );
		}

		$response = rest_ensure_response( $reviews );
		$response->header( 'X-WP-Total', $total_reviews );
		$response->header( 'X-WP-TotalPages', $max_pages );

		$base = add_query_arg( $request->get_query_params(), rest_url( sprintf( '%s/%s', $this->namespace, $this->rest_base ) ) );

		if ( $request['page'] > 1 ) {
			$prev_page = $request['page'] - 1;

			if ( $prev_page > $max_pages ) {
				$prev_page = $max_pages;
			}

			$prev_link = add_query_arg( 'page', $prev_page, $base );
			$response->link_header( 'prev', $prev_link );
		}

		if ( $max_pages > $request['page'] ) {
			$next_page = $request['page'] + 1;
			$next_link = add_query_arg( 'page', $next_page, $base );

			$response->link_header( 'next', $next_link );
		}

		return $response;
	}

	/**
	 * Get a single listing review.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_item( $request ) {
		$review = $this->get_review( $request['id'] );
		if ( is_wp_error( $review ) ) {
			return $review;
		}

		$data     = $this->prepare_item_for_response( $review, $request );
		$response = rest_ensure_response( $data );

		return $response;
	}

	/**
	 * Prepare a single listing review output for response.
	 *
	 * @param WP_Comment      $review Listing review object.
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response $response Response data.
	 */
	public function prepare_item_for_response( $review, $request ) {
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$fields  = $this->get_fields_for_response( $request );
		$data    = array();

		if ( in_array( 'id', $fields, true ) ) {
			$data['id'] = (int) $review->comment_ID;
		}

		if ( in_array( 'date_created', $fields, true ) ) {
			$data['date_created'] = directorist_rest_prepare_date_response( $review->comment_date );
		}

		if ( in_array( 'date_created_gmt', $fields, true ) ) {
			$data['date_created_gmt'] = directorist_rest_prepare_date_response( $review->comment_date_gmt );
		}

		if ( in_array( 'listing_id', $fields, true ) ) {
			$data['listing_id'] = (int) $review->comment_post_ID;
		}

		if ( in_array( 'status', $fields, true ) ) {
			$data['status'] = $this->prepare_status_response( (string) $review->comment_approved );
		}

		if ( in_array( 'reviewer', $fields, true ) ) {
			$data['reviewer'] = $review->comment_author;
		}

		if ( in_array( 'reviewer_email', $fields, true ) ) {
			$data['reviewer_email'] = $review->comment_author_email;
		}

		if ( in_array( 'review', $fields, true ) ) {
			$data['review'] = 'view' === $context ? wpautop( $review->comment_content ) : $review->comment_content;
		}

		if ( in_array( 'rating', $fields, true ) ) {
			$data['rating'] = (int) Comment_Meta::get_rating( $review->comment_ID );
		}

		if ( in_array( 'reviewer_avatar_urls', $fields, true ) ) {
			$avatar_id  = get_user_meta( $review->by_user_id, 'pro_pic', true );
			$avatar_img = wp_get_attachment_image_url( $avatar_id, 'thumbnail' );

			if ( $avatar_img ) {
				$avatar_sizes = rest_get_avatar_sizes();
				$urls         = array();

				foreach ( $avatar_sizes as $size ) {
					$urls[ $size ] = $avatar_img;
				}

				$data['reviewer_avatar_urls'] = $urls;
			} else {
				$data['reviewer_avatar_urls'] = rest_get_avatar_urls( $review->email );
			}
		}

		$data = $this->add_additional_fields_to_object( $data, $request );
		$data = $this->filter_response_by_context( $data, $context );

		// Wrap the data in a response object.
		$response = rest_ensure_response( $data );

		$response->add_links( $this->prepare_links( $review ) );

		/**
		 * Filter listing reviews object returned from the REST API.
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param WP_Comment       $review   Listing review object used to create response.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( 'directorist_rest_prepare_listing_review', $response, $review, $request );
	}

	/**
	 * Prepare links for the request.
	 *
	 * @param WP_Comment $review Listing review object.
	 * @return array Links for the given listing review.
	 */
	protected function prepare_links( $review ) {
		$links = array(
			'self'       => array(
				'href' => rest_url( sprintf( '/%s/%s/%d', $this->namespace, $this->rest_base, $review->id ) ),
			),
			'collection' => array(
				'href' => rest_url( sprintf( '/%s/%s', $this->namespace, $this->rest_base ) ),
			),
		);

		if ( 0 !== (int) $review->post_id ) {
			$links['up'] = array(
				'href' => rest_url( sprintf( '/%s/listings/%d', $this->namespace, $review->post_id ) ),
			);
		}

		if ( 0 !== (int) $review->user_id ) {
			$links['reviewer'] = array(
				'href'       => rest_url( 'directorist/v1/users/' . $review->user_id ),
				'embeddable' => true,
			);
		}

		return $links;
	}

	/**
	 * Get the Listing Review's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'listing_review',
			'type'       => 'object',
			'properties' => array(
				'id'               => array(
					'description' => __( 'Unique identifier for the resource.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_created'     => array(
					'description' => __( "The date the review was created, in the site's timezone.", 'directorist' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_created_gmt' => array(
					'description' => __( 'The date the review was created, as GMT.', 'directorist' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'listing_id'       => array(
					'description' => __( 'Unique identifier for the listing that the review belongs to.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'status'           => array(
					'description' => __( 'Status of the review.', 'directorist' ),
					'type'        => 'string',
					'default'     => 'approved',
					'enum'        => array( 'approved', 'hold', 'spam', 'unspam', 'trash', 'untrash' ),
					'context'     => array( 'view', 'edit' ),
				),
				'reviewer'         => array(
					'description' => __( 'Reviewer name.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'reviewer_email'   => array(
					'description' => __( 'Reviewer email.', 'directorist' ),
					'type'        => 'string',
					'format'      => 'email',
					'context'     => array( 'view', 'edit' ),
				),
				'review'           => array(
					'description' => __( 'The content of the review.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'wp_filter_post_kses',
					),
				),
				'rating'           => array(
					'description' => __( 'Review rating (0 to 5).', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
			),
		);

		// if ( get_option( 'show_avatars' ) ) {
			$avatar_properties = array();
			$avatar_sizes      = rest_get_avatar_sizes();

			foreach ( $avatar_sizes as $size ) {
				$avatar_properties[ $size ] = array(
					/* translators: %d: avatar image size in pixels */
					'description' => sprintf( __( 'Avatar URL with image size of %d pixels.', 'directorist' ), $size ),
					'type'        => 'string',
					'format'      => 'uri',
					'context'     => array( 'embed', 'view', 'edit' ),
				);
			}
			$schema['properties']['reviewer_avatar_urls'] = array(
				'description' => __( 'Avatar URLs for the object reviewer.', 'directorist' ),
				'type'        => 'object',
				'context'     => array( 'view', 'edit' ),
				'readonly'    => true,
				'properties'  => $avatar_properties,
			);
		// }

		return $this->add_additional_fields_schema( $schema );
	}

	/**
	 * Get the query params for collections.
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		$params['context']['default'] = 'view';

		$params['per_page']['default'] = get_directorist_option( 'review_num', 5 );

		$params['after']            = array(
			'description' => __( 'Limit response to resources published after a given ISO8601 compliant date.', 'directorist' ),
			'type'        => 'string',
			'format'      => 'date-time',
		);
		$params['before']           = array(
			'description' => __( 'Limit response to reviews published before a given ISO8601 compliant date.', 'directorist' ),
			'type'        => 'string',
			'format'      => 'date-time',
		);
		$params['exclude']          = array(
			'description' => __( 'Ensure result set excludes specific IDs.', 'directorist' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'integer',
			),
			'default'     => array(),
		);
		$params['include']          = array(
			'description' => __( 'Limit result set to specific IDs.', 'directorist' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'integer',
			),
			'default'     => array(),
		);
		$params['offset']           = array(
			'description' => __( 'Offset the result set by a specific number of items.', 'directorist' ),
			'type'        => 'integer',
		);
		$params['order']            = array(
			'description' => __( 'Order sort attribute ascending or descending.', 'directorist' ),
			'type'        => 'string',
			'default'     => 'desc',
			'enum'        => array(
				'asc',
				'desc',
			),
		);
		$params['orderby']          = array(
			'description' => __( 'Sort collection by object attribute.', 'directorist' ),
			'type'        => 'string',
			'default'     => 'date_gmt',
			'enum'        => array(
				'date',
				'date_gmt',
				'id',
				'include',
				'listing',
			),
		);
		$params['reviewer']         = array(
			'description' => __( 'Limit result set to reviews assigned to specific user IDs.', 'directorist' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'integer',
			),
		);
		$params['reviewer_exclude'] = array(
			'description' => __( 'Ensure result set excludes reviews assigned to specific user IDs.', 'directorist' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'integer',
			),
		);
		$params['reviewer_email']   = array(
			'default'     => null,
			'description' => __( 'Limit result set to that from a specific author email.', 'directorist' ),
			'format'      => 'email',
			'type'        => 'string',
		);
		$params['listing']          = array(
			'default'     => array(),
			'description' => __( 'Limit result set to reviews assigned to specific listing IDs.', 'directorist' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'integer',
			),
		);
		$params['status']           = array(
			'default'           => 'approved',
			'description'       => __( 'Limit result set to reviews assigned a specific status.', 'directorist' ),
			'sanitize_callback' => 'sanitize_key',
			'type'              => 'string',
			'enum'              => array(
				'all',
				'hold',
				'approved',
				'spam',
				'trash',
			),
		);

		/**
		 * Filter collection parameters for the reviews controller.
		 *
		 * This filter registers the collection parameter, but does not map the
		 * collection parameter to an internal WP_Comment_Query parameter. Use the
		 * `wc_rest_review_query` filter to set WP_Comment_Query parameters.
		 *
		 * @param array $params JSON Schema-formatted collection parameters.
		 */
		return apply_filters( 'directorist_rest_listing_review_collection_params', $params );
	}

	/**
	 * Get the reivew, if the ID is valid.
	 *
	 * @param int $id Supplied ID.
	 * @return WP_Comment|WP_Error Comment object if ID is valid, WP_Error otherwise.
	 */
	protected function get_review( $id ) {
		$id    = (int) $id;
		$error = new WP_Error( 'directorist_rest_review_invalid_id', __( 'Invalid review ID.', 'directorist' ), array( 'status' => 404 ) );

		if ( 0 >= $id ) {
			return $error;
		}

		$review = get_comment( $id );
		if ( empty( $review ) ) {
			return $error;
		}

		if ( ! empty( $review->comment_post_ID ) ) {
			$post = get_post( (int) $review->comment_post_ID );

			if ( ATBDP_POST_TYPE !== get_post_type( (int) $review->comment_post_ID ) ) {
				return new WP_Error( 'directorist_rest_listing_invalid_id', __( 'Invalid listing ID.', 'directorist' ), array( 'status' => 404 ) );
			}
		}

		return $review;
	}

	/**
	 * Prepends internal property prefix to query parameters to match our response fields.
	 *
	 * @param string $query_param Query parameter.
	 * @return string
	 */
	protected function normalize_query_param( $query_param ) {
		$prefix = 'comment_';

		switch ( $query_param ) {
			case 'id':
				$normalized = $prefix . 'ID';
				break;
			case 'listing':
				$normalized = $prefix . 'post_ID';
				break;
			case 'include':
				$normalized = 'comment__in';
				break;
			default:
				$normalized = $prefix . $query_param;
				break;
		}

		return $normalized;
	}

	/**
	 * Checks comment_approved to set comment status for single comment output.
	 *
	 * @param string|int $comment_approved comment status.
	 * @return string Comment status.
	 */
	protected function prepare_status_response( $comment_approved ) {
		switch ( $comment_approved ) {
			case 'hold':
			case '0':
				$status = 'hold';
				break;
			case 'approve':
			case '1':
				$status = 'approved';
				break;
			case 'spam':
			case 'trash':
			default:
				$status = $comment_approved;
				break;
		}

		return $status;
	}

	/**
	 * Sets the comment_status of a given review object when creating or updating a review.
	 *
	 * @param string|int $new_status New review status.
	 * @param int        $id         Review ID.
	 * @return bool Whether the status was changed.
	 */
	protected function handle_status_param( $new_status, $id ) {
		$old_status = wp_get_comment_status( $id );

		if ( $new_status === $old_status ) {
			return false;
		}

		switch ( $new_status ) {
			case 'approved':
			case 'approve':
			case '1':
				$changed = wp_set_comment_status( $id, 'approve' );
				break;
			case 'hold':
			case '0':
				$changed = wp_set_comment_status( $id, 'hold' );
				break;
			case 'spam':
				$changed = wp_spam_comment( $id );
				break;
			case 'unspam':
				$changed = wp_unspam_comment( $id );
				break;
			case 'trash':
				$changed = wp_trash_comment( $id );
				break;
			case 'untrash':
				$changed = wp_untrash_comment( $id );
				break;
			default:
				$changed = false;
				break;
		}

		return $changed;
	}
}
