<?php
/**
 * Rest Plans Controller
 *
 * @package Directorist\Rest_Api
 * @version  1.0.0
 */

namespace Directorist\Rest_Api\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use WP_Error;
use WP_Query;
use WP_REST_Server;
use Directorist\Helper;

/**
 * Plans controller class.
 */
class Plans_Controller extends Posts_Controller {

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'plans';

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = 'atbdp_pricing_plans';

	/**
	 * Register the routes for plans.
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
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
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
			array(
				'args'   => array(
					'id' => array(
						'description' => __( 'Plan id.', 'directorist' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(
						'context' => $this->get_context_param(
							array(
								'default' => 'view',
							)
						),
					),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Get a collection of posts.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {
		$query_args = $this->prepare_objects_query( $request );

		do_action( 'directorist_rest_before_query', 'get_plan_items', $request, $query_args );

		$query_results = $this->get_plans( $query_args );

		$objects = array();
		foreach ( $query_results['objects'] as $object ) {
			if ( ! $this->check_post_permissions( $this->post_type, 'read', $object->ID ) ) {
				continue;
			}

			$data = $this->prepare_item_for_response( $object, $request );
			$objects[] = $this->prepare_response_for_collection( $data );
		}

		$page      = (int) $query_args['paged'];
		$max_pages = $query_results['pages'];

		$response = rest_ensure_response( $objects );
		$response->header( 'X-WP-Total', $query_results['total'] );
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

		do_action( 'directorist_rest_after_query', 'get_plan_items', $request, $query_args );

		$response = apply_filters( 'directorist_rest_response', $response, 'get_plan_items', $request, $query_args );

		return $response;
	}

	protected function get_plans( $query_args ) {
		$query  = new WP_Query();
		$result = $query->query( $query_args );

		$total_posts = $query->found_posts;
		if ( $total_posts < 1 ) {
			// Out-of-bounds, run the query again without LIMIT for total count.
			unset( $query_args['paged'] );
			$count_query = new WP_Query();
			$count_query->query( $query_args );
			$total_posts = $count_query->found_posts;
		}

		return array(
			'objects' => $result,
			'total'   => (int) $total_posts,
			'pages'   => (int) ceil( $total_posts / (int) $query->query_vars['posts_per_page'] ),
		);
	}

	/**
	 * Prepare objects query.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args                   = [];
		$args['offset']         = $request['offset'];
		$args['order']          = $request['order'];
		$args['orderby']        = $request['orderby'];
		$args['paged']          = $request['page'];
		$args['post__in']       = $request['include'];
		$args['post__not_in']   = $request['exclude'];
		$args['posts_per_page'] = $request['per_page'];
		$args['name']           = $request['slug'];
		$args['s']              = $request['search'];
		$args['post_status']    = $request['status'];
		$args['fields']         = $this->get_fields_for_response( $request );

		// Taxonomy query.
		$tax_query = [];
		// Meta query.
		$meta_query = [];
		// Date query.
		$args['date_query'] = [];

		// Set before into date query. Date query must be specified as an array of an array.
		if ( isset( $request['before'] ) ) {
			$args['date_query'][0]['before'] = $request['before'];
		}

		// Set after into date query. Date query must be specified as an array of an array.
		if ( isset( $request['after'] ) ) {
			$args['date_query'][0]['after'] = $request['after'];
		}

		// Check flag to use post_date vs post_date_gmt.
		if ( true === $request['dates_are_gmt'] ) {
			if ( isset( $request['before'] ) || isset( $request['after'] ) ) {
				$args['date_query'][0]['column'] = 'post_date_gmt';
			}
		}

		// Set author query.
		if ( isset( $request['author'] ) ) {
			$args['author'] = $request['author'];
		}

		// Set directory type query.
		if ( isset( $request['directory'] ) ) {
			$meta_query['_directory_type'] = [
				'key'     => '_directory_type',
				'value'   => $request['directory'],
				'compare' => '=',
			];
		}

		/**
		 * Filter the query arguments for a request.
		 *
		 * Enables adding extra arguments or setting defaults for a post
		 * collection request.
		 *
		 * @param array           $args    Key value array of query var to query value.
		 * @param WP_REST_Request $request The request used.
		 */
		$args = apply_filters( "directorist_rest_{$this->post_type}_object_query", $args, $request );

		// Force the post_type argument, since it's not a user input variable.
		$args['post_type'] = $this->post_type;

		return $this->prepare_items_query( $args, $request );
	}

	/**
	 * Add post meta fields.
	 *
	 * @param WP_Post         $post Post Object.
	 * @param WP_REST_Request $request WP_REST_Request Object.
	 * @return bool|WP_Error
	 */
	protected function add_post_meta_fields( $post, $request ) {
		return true;
	}

	/**
	 * Get a single item.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_item( $request ) {
		$id = (int) $request['id'];

		do_action( 'directorist_rest_before_query', 'get_plan_item', $request, $id );

		$post = get_post( $id );

		if ( empty( $id ) || empty( $post->ID ) || $post->post_type !== $this->post_type ) {
			return new WP_Error( "directorist_rest_invalid_{$this->post_type}_id", __( 'Invalid ID.', 'directorist' ), array( 'status' => 404 ) );
		}

		$data = $this->prepare_item_for_response( $post, $request );
		$response = rest_ensure_response( $data );

		if ( $this->public ) {
			$response->link_header( 'alternate', get_permalink( $id ), array( 'type' => 'text/html' ) );
		}

		do_action( 'directorist_rest_after_query', 'get_plan_item', $request, $id );

		$response = apply_filters( 'directorist_rest_response', $response, 'get_plan_item', $request, $id );

		return $response;
	}

	/**
	 * Prepare a single plans output for response.
	 *
	 * @param WP_Post         $object  Object data.
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response
	 */
	public function prepare_item_for_response( $object, $request ) {
		$context       = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$this->request = $request;
		$data          = $this->get_plan_data( $object, $request, $context );

		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );

		$response = rest_ensure_response( $data );
		$response->add_links( $this->prepare_links( $object, $request ) );

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->post_type,
		 * refers to object type being prepared for the response.
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param WP_Post          $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "directorist_rest_prepare_{$this->post_type}_object", $response, $object, $request );
	}

	/**
	 * Get plan data.
	 *
	 * @param WP_Post   $plan WP_Post instance.
	 * @param WP_REST_Request $request Request object.
	 * @param string    $context Request context. Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_plan_data( $plan, $request, $context = 'view' ) {
		$fields  = $this->get_fields_for_response( $request );

		$base_data = array();
		foreach ( $fields as $field ) {
			switch ( $field ) {
				case 'id':
					$base_data['id'] = $plan->ID;
					break;
				case 'name':
					$base_data['name'] = get_the_title( $plan );
					break;
				case 'slug':
					$base_data['slug'] = $plan->post_name;
					break;
				case 'date_created':
					$base_data['date_created'] = directorist_rest_prepare_date_response( $plan->post_date, false );
					break;
				case 'date_created_gmt':
					$base_data['date_created_gmt'] = directorist_rest_prepare_date_response( $plan->post_date_gmt );
					break;
				case 'date_modified':
					$base_data['date_modified'] = directorist_rest_prepare_date_response( $plan->post_date_modified, false );
					break;
				case 'date_modified_gmt':
					$base_data['date_modified_gmt'] = directorist_rest_prepare_date_response( $plan->post_date_modified_gmt );
					break;
				case 'description':
					$base_data['description'] = 'view' === $context ? wpautop( do_shortcode( $plan->post_content ) ): $plan->post_content;
					break;
				case 'directory':
					$base_data['directory'] = (int) get_post_meta( $plan->ID, '_assign_to_directory', true );
					break;
				case 'status':
					$base_data['status'] = $plan->post_status;
					break;
				case 'type':
					$base_data['type'] = get_post_meta( $plan->ID, 'plan_type', true );
					break;
				case 'price':
					$base_data['price'] = get_post_meta( $plan->ID, 'fm_price', true );
					break;
			}
		}

		return $base_data;
	}

	/**
	 * Prepare links for the request.
	 *
	 * @param WP_Post         $object  Object data.
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array Links for the given post.
	 */
	protected function prepare_links( $object, $request ) {
		$links = array(
			'self'       => array(
				'href' => rest_url( sprintf( '/%s/%s/%d', $this->namespace, $this->rest_base, $object->ID ) ),  // @codingStandardsIgnoreLine.
			),
			'collection' => array(
				'href' => rest_url( sprintf( '/%s/%s', $this->namespace, $this->rest_base ) ),  // @codingStandardsIgnoreLine.
			),
		);

		if ( $object->post_parent ) {
			$links['up'] = array(
				'href' => rest_url( sprintf( '/%s/plans/%d', $this->namespace, $object->post_parent ) ),  // @codingStandardsIgnoreLine.
			);
		}

		return $links;
	}

	/**
	 * Get the plans's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema         = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => $this->post_type,
			'type'       => 'object',
			'properties' => array(
				'id'                    => array(
					'description' => __( 'Unique identifier for the resource.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'name'                  => array(
					'description' => __( 'plan name.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'slug'                  => array(
					'description' => __( 'plan slug.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'permalink'             => array(
					'description' => __( 'plan URL.', 'directorist' ),
					'type'        => 'string',
					'format'      => 'uri',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_created'          => array(
					'description' => __( "The date the plan was created, in the site's timezone.", 'directorist' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_created_gmt'      => array(
					'description' => __( 'The date the plan was created, as GMT.', 'directorist' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_modified'         => array(
					'description' => __( "The date the plan was last modified, in the site's timezone.", 'directorist' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_modified_gmt'     => array(
					'description' => __( 'The date the plan was last modified, as GMT.', 'directorist' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'description'           => array(
					'description' => __( 'plan description.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'directory' => array(
					'description' => __( 'Directory id.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'status'     => array(
					'description' => __( 'plan status.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'meta_data'             => array(
					'description' => __( 'Meta data.', 'directorist' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'    => array(
								'description' => __( 'Meta ID.', 'directorist' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'key'   => array(
								'description' => __( 'Meta key.', 'directorist' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'value' => array(
								'description' => __( 'Meta value.', 'directorist' ),
								'type'        => 'mixed',
								'context'     => array( 'view', 'edit' ),
							),
						),
					),
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}

	/**
	 * Get the query params for collections of plans.
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		$params['context']['default'] = 'view';

		$params['exclude'] = array(
			'description'       => __( 'Ensure result set excludes specific IDs.', 'directorist' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
		);
		$params['include'] = array(
			'description'       => __( 'Limit result set to specific IDs.', 'directorist' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
		);
		$params['offset'] = array(
			'description'        => __( 'Offset the result set by a specific number of items.', 'directorist' ),
			'type'               => 'integer',
			'sanitize_callback'  => 'absint',
		);
		$params['order'] = array(
			'default'            => 'desc',
			'description'        => __( 'Order sort attribute ascending or descending.', 'directorist' ),
			'enum'               => array( 'asc', 'desc' ),
			'type'               => 'string',
			'sanitize_callback'  => 'sanitize_key',
		);
		$params['orderby'] = array(
			'description'        => __( 'Sort collection by object attribute.', 'directorist' ),
			'enum'               => array_keys( $this->get_orderby_possibles() ),
			'type'               => 'string',
			'sanitize_callback'  => 'sanitize_key',
		);
		$params['status'] = array(
			'default'           => 'publish',
			'description'       => __( 'Limit result set to plans assigned a specific status.', 'directorist' ),
			'type'              => 'string',
			'enum'              => array_merge( array( 'any', 'future', 'trash', 'expired' ), array_keys( get_post_statuses() ) ),
			'sanitize_callback' => 'sanitize_key',
		);
		$params['directory'] = array(
			'description'       => __( 'Limit result set to plans to sepecific directory type.', 'directorist' ),
			'type'              => 'integar',
		);
		$params['author'] = array(
			'description'       => __( 'Limit result set to plans specific to author ID.', 'directorist' ),
			'type'              => 'integer',
		);

		return $params;
	}

	protected function get_orderby_possibles() {
		return array(
			'id'      => 'ID',
			'include' => 'include',
			'title'   => 'title',
			'date'    => 'date',
		);
	}
}
