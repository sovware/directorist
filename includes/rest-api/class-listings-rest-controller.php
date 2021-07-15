<?php
/**
 * Listings REST controller.
 */
namespace wpWax\Directorist\RestApi;

defined( 'ABSPATH' ) || die();

use WP_REST_Server;
use WP_REST_Request;
use WP_Error;
use WP_Query;

/**
 * REST API Listings controller class.
 *
 * @package wpWax\Directorist\RestApi
 * @extends wpWax\Directorist\RestApi\Controller
 */
class Listings_REST_Controller extends Controller {

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'listings';

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = ATBDP_POST_TYPE;

	/**
	 * If object is hierarchical.
	 *
	 * @var bool
	 */
	protected $hierarchical = false;

	// /**
	//  * Initialize product actions.
	//  */
	// public function __construct() {
	// 	add_action( "woocommerce_rest_insert_{$this->post_type}_object", array( $this, 'clear_transients' ) );
	// }

	/**
	 * Register the routes for products.
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					// 'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		// register_rest_route(
		// 	$this->namespace,
		// 	'/' . $this->rest_base . '/(?P<id>[\d]+)',
		// 	array(
		// 		'args'   => array(
		// 			'id' => array(
		// 				'description' => __( 'Unique identifier for the resource.', 'directorist' ),
		// 				'type'        => 'integer',
		// 			),
		// 		),
		// 		array(
		// 			'methods'             => WP_REST_Server::READABLE,
		// 			'callback'            => array( $this, 'get_item' ),
		// 			'permission_callback' => array( $this, 'get_item_permissions_check' ),
		// 			'args'                => array(
		// 				'context' => $this->get_context_param(
		// 					array(
		// 						'default' => 'view',
		// 					)
		// 				),
		// 			),
		// 		),
		// 		array(
		// 			'methods'             => WP_REST_Server::EDITABLE,
		// 			'callback'            => array( $this, 'update_item' ),
		// 			'permission_callback' => array( $this, 'update_item_permissions_check' ),
		// 			'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
		// 		),
		// 		array(
		// 			'methods'             => WP_REST_Server::DELETABLE,
		// 			'callback'            => array( $this, 'delete_item' ),
		// 			'permission_callback' => array( $this, 'delete_item_permissions_check' ),
		// 			'args'                => array(
		// 				'force' => array(
		// 					'default'     => false,
		// 					'description' => __( 'Whether to bypass trash and force deletion.', 'directorist' ),
		// 					'type'        => 'boolean',
		// 				),
		// 			),
		// 		),
		// 		'schema' => array( $this, 'get_public_item_schema' ),
		// 	)
		// );
	}

	/**
	 * Get a collection of posts.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {
		$args                         = array();
		$args['offset']               = $request['offset'];
		$args['order']                = $request['order'];
		$args['orderby']              = $request['orderby'];
		$args['paged']                = $request['page'];
		$args['post__in']             = $request['include'];
		$args['post__not_in']         = $request['exclude'];
		$args['posts_per_page']       = $request['per_page'];
		$args['name']                 = $request['slug'];
		$args['post_parent__in']      = $request['parent'];
		$args['post_parent__not_in']  = $request['parent_exclude'];
		$args['s']                    = $request['search'];

		$args['date_query'] = array();
		// Set before into date query. Date query must be specified as an array of an array.
		if ( isset( $request['before'] ) ) {
			$args['date_query'][0]['before'] = $request['before'];
		}

		// Set after into date query. Date query must be specified as an array of an array.
		if ( isset( $request['after'] ) ) {
			$args['date_query'][0]['after'] = $request['after'];
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
		$args = apply_filters( "directorist_rest_{$this->post_type}_query", $args, $request );

		// Force the post_type argument, since it's not a user input variable.
		$args['post_type'] = $this->post_type;

		$query_args = $this->prepare_items_query( $args, $request );

		$listings_query = new WP_Query();
		$query_result = $listings_query->query( $query_args );

		$listings = array();
		foreach ( $query_result as $listing ) {
			// if ( ! $this->check_post_permissions( $this->post_type, 'read', $listing->ID ) ) {
			// 	continue;
			// }

			$data = $this->prepare_item_for_response( $listing, $request );
			$listings[] = $this->prepare_response_for_collection( $data );
		}

		$page = (int) $query_args['paged'];
		$total_listings = $listings_query->found_posts;

		if ( $total_listings < 1 ) {
			// Out-of-bounds, run the query again without LIMIT for total count.
			unset( $query_args['paged'] );
			$count_query = new WP_Query();
			$count_query->query( $query_args );
			$total_listings = $count_query->found_posts;
		}

		$max_pages = ceil( $total_listings / (int) $query_args['posts_per_page'] );

		$response = rest_ensure_response( $listings );
		$response->header( 'X-WP-Total', (int) $total_listings );
		$response->header( 'X-WP-TotalPages', (int) $max_pages );

		$request_params = $request->get_query_params();
		if ( ! empty( $request_params['filter'] ) ) {
			// Normalize the pagination params.
			unset( $request_params['filter']['posts_per_page'] );
			unset( $request_params['filter']['paged'] );
		}
		$base = add_query_arg( $request_params, rest_url( sprintf( '/%s/%s', $this->namespace, $this->rest_base ) ) );

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

		return $response;
	}

	/**
	 * Create a single item.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function create_item( $request ) {
		if ( ! empty( $request['id'] ) ) {
			/* translators: %s: post type */
			return new WP_Error( "directorist_rest_{$this->post_type}_exists", sprintf( __( 'Cannot create existing %s.', 'directorist' ), $this->post_type ), array( 'status' => 400 ) );
		}

		$post = $this->prepare_item_for_database( $request );
		if ( is_wp_error( $post ) ) {
			return $post;
		}

		$post->post_type = $this->post_type;
		$post_id         = wp_insert_post( $post, true );

		if ( is_wp_error( $post_id ) ) {

			if ( in_array( $post_id->get_error_code(), array( 'db_insert_error' ) ) ) {
				$post_id->add_data( array( 'status' => 500 ) );
			} else {
				$post_id->add_data( array( 'status' => 400 ) );
			}
			return $post_id;
		}
		$post->ID = $post_id;
		$post     = get_post( $post_id );

		$this->update_additional_fields_for_object( $post, $request );

		// Add meta fields.
		$meta_fields = $this->add_post_meta_fields( $post, $request );
		if ( is_wp_error( $meta_fields ) ) {
			// Remove post.
			$this->delete_post( $post );

			return $meta_fields;
		}

		/**
		 * Fires after a single item is created or updated via the REST API.
		 *
		 * @param WP_Post         $post      Post object.
		 * @param WP_REST_Request $request   Request object.
		 * @param boolean         $creating  True when creating item, false when updating.
		 */
		do_action( "directorist_rest_insert_{$this->post_type}", $post, $request, true );

		$request->set_param( 'context', 'edit' );
		$response = $this->prepare_item_for_response( $post, $request );
		$response = rest_ensure_response( $response );
		$response->set_status( 201 );
		$response->header( 'Location', rest_url( sprintf( '/%s/%s/%d', $this->namespace, $this->rest_base, $post_id ) ) );

		return $response;
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
	 * Delete a single item.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error
	 */
	public function delete_item( $request ) {
		$id    = (int) $request['id'];
		$force = (bool) $request['force'];
		$post  = get_post( $id );

		if ( empty( $id ) || empty( $post->ID ) || $post->post_type !== $this->post_type ) {
			return new WP_Error( "directorist_rest_{$this->post_type}_invalid_id", __( 'ID is invalid.', 'directorist' ), array( 'status' => 404 ) );
		}

		$supports_trash = EMPTY_TRASH_DAYS > 0;

		/**
		 * Filter whether an item is trashable.
		 *
		 * Return false to disable trash support for the item.
		 *
		 * @param boolean $supports_trash Whether the item type support trashing.
		 * @param WP_Post $post           The Post object being considered for trashing support.
		 */
		$supports_trash = apply_filters( "directorist_rest_{$this->post_type}_trashable", $supports_trash, $post );

		if ( ! wc_rest_check_post_permissions( $this->post_type, 'delete', $post->ID ) ) {
			/* translators: %s: post type */
			return new WP_Error( "directorist_rest_user_cannot_delete_{$this->post_type}", sprintf( __( 'Sorry, you are not allowed to delete %s.', 'directorist' ), $this->post_type ), array( 'status' => rest_authorization_required_code() ) );
		}

		$request->set_param( 'context', 'edit' );
		$response = $this->prepare_item_for_response( $post, $request );

		// If we're forcing, then delete permanently.
		if ( $force ) {
			$result = wp_delete_post( $id, true );
		} else {
			// If we don't support trashing for this type, error out.
			if ( ! $supports_trash ) {
				/* translators: %s: post type */
				return new WP_Error( 'directorist_rest_trash_not_supported', sprintf( __( 'The %s does not support trashing.', 'directorist' ), $this->post_type ), array( 'status' => 501 ) );
			}

			// Otherwise, only trash if we haven't already.
			if ( 'trash' === $post->post_status ) {
				/* translators: %s: post type */
				return new WP_Error( 'directorist_rest_already_trashed', sprintf( __( 'The %s has already been deleted.', 'directorist' ), $this->post_type ), array( 'status' => 410 ) );
			}

			// (Note that internally this falls through to `wp_delete_post` if
			// the trash is disabled.)
			$result = wp_trash_post( $id );
		}

		if ( ! $result ) {
			/* translators: %s: post type */
			return new WP_Error( 'directorist_rest_cannot_delete', sprintf( __( 'The %s cannot be deleted.', 'directorist' ), $this->post_type ), array( 'status' => 500 ) );
		}

		/**
		 * Fires after a single item is deleted or trashed via the REST API.
		 *
		 * @param object           $post     The deleted or trashed item.
		 * @param WP_REST_Response $response The response data.
		 * @param WP_REST_Request  $request  The request sent to the API.
		 */
		do_action( "directorist_rest_delete_{$this->post_type}", $post, $response, $request );

		return $response;
	}

	/**
	 * Get a single item.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_item( $request ) {
		$id   = (int) $request['id'];
		$post = get_post( $id );

		if ( empty( $id ) || empty( $post->ID ) || $post->post_type !== $this->post_type ) {
			return new WP_Error( "directorist_rest_invalid_{$this->post_type}_id", __( 'Invalid ID.', 'directorist' ), array( 'status' => 404 ) );
		}

		$data = $this->prepare_item_for_response( $post, $request );
		$response = rest_ensure_response( $data );

		if ( $this->public ) {
			$response->link_header( 'alternate', get_permalink( $id ), array( 'type' => 'text/html' ) );
		}

		return $response;
	}

	/**
	 * Prepare a single product output for response.
	 *
	 * @param WC_Data         $object  Object data.
	 * @param WP_REST_Request $request Request object.
	 *
	 * @since  3.0.0
	 * @return WP_REST_Response
	 */
	public function prepare_object_for_response( $object, $request ) {
		$context       = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$this->request = $request;
		$data          = $this->get_product_data( $object, $context, $request );

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
		 * @param WC_Data          $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "directorist_rest_prepare_{$this->post_type}_object", $response, $object, $request );
	}

	/**
	 * Prepare objects query.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @since  3.0.0
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = array();

		return $args;
	}


	/**
	 * Get taxonomy terms.
	 *
	 * @param WC_Product $product  Product instance.
	 * @param string     $taxonomy Taxonomy slug.
	 *
	 * @return array
	 */
	protected function get_taxonomy_terms( $product, $taxonomy = 'cat' ) {
		$terms = array();

		foreach ( wc_get_object_terms( $product->get_id(), 'product_' . $taxonomy ) as $term ) {
			$terms[] = array(
				'id'   => $term->term_id,
				'name' => $term->name,
				'slug' => $term->slug,
			);
		}

		return $terms;
	}

	/**
	 * Get the images for a product or product variation.
	 *
	 * @param WC_Product|WC_Product_Variation $product Product instance.
	 *
	 * @return array
	 */
	protected function get_images( $listing ) {
		$images         = array();
		$attachment_ids = array();

		// // Add featured image.
		// if ( $product->get_image_id() ) {
		// 	$attachment_ids[] = $product->get_image_id();
		// }

		// // Add gallery images.
		// $attachment_ids = array_merge( $attachment_ids, $product->get_gallery_image_ids() );

		// // Build image data.
		// foreach ( $attachment_ids as $position => $attachment_id ) {
		// 	$attachment_post = get_post( $attachment_id );
		// 	if ( is_null( $attachment_post ) ) {
		// 		continue;
		// 	}

		// 	$attachment = wp_get_attachment_image_src( $attachment_id, 'full' );
		// 	if ( ! is_array( $attachment ) ) {
		// 		continue;
		// 	}

		// 	$images[] = array(
		// 		'id'                => (int) $attachment_id,
		// 		'date_created'      => wc_rest_prepare_date_response( $attachment_post->post_date, false ),
		// 		'date_created_gmt'  => wc_rest_prepare_date_response( strtotime( $attachment_post->post_date_gmt ) ),
		// 		'date_modified'     => wc_rest_prepare_date_response( $attachment_post->post_modified, false ),
		// 		'date_modified_gmt' => wc_rest_prepare_date_response( strtotime( $attachment_post->post_modified_gmt ) ),
		// 		'src'               => current( $attachment ),
		// 		'name'              => get_the_title( $attachment_id ),
		// 		'alt'               => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
		// 		'position'          => (int) $position,
		// 	);
		// }

		// // Set a placeholder image if the product has no images set.
		// if ( empty( $images ) ) {
		// 	$images[] = array(
		// 		'id'                => 0,
		// 		'date_created'      => wc_rest_prepare_date_response( current_time( 'mysql' ), false ), // Default to now.
		// 		'date_created_gmt'  => wc_rest_prepare_date_response( time() ), // Default to now.
		// 		'date_modified'     => wc_rest_prepare_date_response( current_time( 'mysql' ), false ),
		// 		'date_modified_gmt' => wc_rest_prepare_date_response( time() ),
		// 		'src'               => wc_placeholder_img_src(),
		// 		'name'              => __( 'Placeholder', 'directorist' ),
		// 		'alt'               => __( 'Placeholder', 'directorist' ),
		// 		'position'          => 0,
		// 	);
		// }

		return $images;
	}

	/**
	 * Fetch related IDs.
	 *
	 * @param WC_Product $product Product object.
	 * @param string     $context Context of request, can be `view` or `edit`.
	 *
	 * @return array
	 */
	protected function api_get_related_ids( $product, $context ) {
		// return array_map( 'absint', array_values( wc_get_related_products( $product->get_id() ) ) );
		return array();
	}

	/**
	 * Fetch meta data.
	 *
	 * @param WC_Product $product Product object.
	 * @param string     $context Context of request, can be `view` or `edit`.
	 *
	 * @return array
	 */
	protected function api_get_meta_data( $product, $context ) {
		return $product->get_meta_data();
	}

	/**
	 * Get product data.
	 *
	 * @param WC_Product $product Product instance.
	 * @param string     $context Request context. Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_product_data( $product, $context = 'view' ) {
		/*
		 * @param WP_REST_Request $request Current request object. For backward compatibility, we pass this argument silently.
		 *
		 *  TODO: Refactor to fix this behavior when DI gets included to make it obvious and clean.
		*/
		$request = func_num_args() >= 2 ? func_get_arg( 2 ) : new WP_REST_Request( '', '', array( 'context' => $context ) );
		$fields  = $this->get_fields_for_response( $request );

		$base_data = array();
		foreach ( $fields as $field ) {
			switch ( $field ) {
				case 'id':
					$base_data['id'] = $product->get_id();
					break;
				case 'name':
					$base_data['name'] = $product->get_name( $context );
					break;
				case 'slug':
					$base_data['slug'] = $product->get_slug( $context );
					break;
				case 'permalink':
					$base_data['permalink'] = $product->get_permalink();
					break;
				case 'date_created':
					$base_data['date_created'] = wc_rest_prepare_date_response( $product->get_date_created( $context ), false );
					break;
				case 'date_created_gmt':
					$base_data['date_created_gmt'] = wc_rest_prepare_date_response( $product->get_date_created( $context ) );
					break;
				case 'date_modified':
					$base_data['date_modified'] = wc_rest_prepare_date_response( $product->get_date_modified( $context ), false );
					break;
				case 'date_modified_gmt':
					$base_data['date_modified_gmt'] = wc_rest_prepare_date_response( $product->get_date_modified( $context ) );
					break;
				case 'type':
					$base_data['type'] = $product->get_type();
					break;
				case 'status':
					$base_data['status'] = $product->get_status( $context );
					break;
				case 'featured':
					$base_data['featured'] = $product->is_featured();
					break;
				case 'catalog_visibility':
					$base_data['catalog_visibility'] = $product->get_catalog_visibility( $context );
					break;
				case 'description':
					$base_data['description'] = 'view' === $context ? wpautop( do_shortcode( $product->get_description() ) ) : $product->get_description( $context );
					break;
				case 'short_description':
					$base_data['short_description'] = 'view' === $context ? apply_filters( 'woocommerce_short_description', $product->get_short_description() ) : $product->get_short_description( $context );
					break;
				case 'sku':
					$base_data['sku'] = $product->get_sku( $context );
					break;
				case 'price':
					$base_data['price'] = $product->get_price( $context );
					break;
				case 'regular_price':
					$base_data['regular_price'] = $product->get_regular_price( $context );
					break;
				case 'sale_price':
					$base_data['sale_price'] = $product->get_sale_price( $context ) ? $product->get_sale_price( $context ) : '';
					break;
				case 'date_on_sale_from':
					$base_data['date_on_sale_from'] = wc_rest_prepare_date_response( $product->get_date_on_sale_from( $context ), false );
					break;
				case 'date_on_sale_from_gmt':
					$base_data['date_on_sale_from_gmt'] = wc_rest_prepare_date_response( $product->get_date_on_sale_from( $context ) );
					break;
				case 'date_on_sale_to':
					$base_data['date_on_sale_to'] = wc_rest_prepare_date_response( $product->get_date_on_sale_to( $context ), false );
					break;
				case 'date_on_sale_to_gmt':
					$base_data['date_on_sale_to_gmt'] = wc_rest_prepare_date_response( $product->get_date_on_sale_to( $context ) );
					break;
				case 'on_sale':
					$base_data['on_sale'] = $product->is_on_sale( $context );
					break;
				case 'purchasable':
					$base_data['purchasable'] = $product->is_purchasable();
					break;
				case 'total_sales':
					$base_data['total_sales'] = $product->get_total_sales( $context );
					break;
				case 'virtual':
					$base_data['virtual'] = $product->is_virtual();
					break;
				case 'downloadable':
					$base_data['downloadable'] = $product->is_downloadable();
					break;
				case 'downloads':
					$base_data['downloads'] = $this->get_downloads( $product );
					break;
				case 'download_limit':
					$base_data['download_limit'] = $product->get_download_limit( $context );
					break;
				case 'download_expiry':
					$base_data['download_expiry'] = $product->get_download_expiry( $context );
					break;
				case 'external_url':
					$base_data['external_url'] = $product->is_type( 'external' ) ? $product->get_product_url( $context ) : '';
					break;
				case 'button_text':
					$base_data['button_text'] = $product->is_type( 'external' ) ? $product->get_button_text( $context ) : '';
					break;
				case 'tax_status':
					$base_data['tax_status'] = $product->get_tax_status( $context );
					break;
				case 'tax_class':
					$base_data['tax_class'] = $product->get_tax_class( $context );
					break;
				case 'manage_stock':
					$base_data['manage_stock'] = $product->managing_stock();
					break;
				case 'stock_quantity':
					$base_data['stock_quantity'] = $product->get_stock_quantity( $context );
					break;
				case 'in_stock':
					$base_data['in_stock'] = $product->is_in_stock();
					break;
				case 'backorders':
					$base_data['backorders'] = $product->get_backorders( $context );
					break;
				case 'backorders_allowed':
					$base_data['backorders_allowed'] = $product->backorders_allowed();
					break;
				case 'backordered':
					$base_data['backordered'] = $product->is_on_backorder();
					break;
				case 'low_stock_amount':
					$base_data['low_stock_amount'] = '' === $product->get_low_stock_amount() ? null : $product->get_low_stock_amount();
					break;
				case 'sold_individually':
					$base_data['sold_individually'] = $product->is_sold_individually();
					break;
				case 'weight':
					$base_data['weight'] = $product->get_weight( $context );
					break;
				case 'dimensions':
					$base_data['dimensions'] = array(
						'length' => $product->get_length( $context ),
						'width'  => $product->get_width( $context ),
						'height' => $product->get_height( $context ),
					);
					break;
				case 'shipping_required':
					$base_data['shipping_required'] = $product->needs_shipping();
					break;
				case 'shipping_taxable':
					$base_data['shipping_taxable'] = $product->is_shipping_taxable();
					break;
				case 'shipping_class':
					$base_data['shipping_class'] = $product->get_shipping_class();
					break;
				case 'shipping_class_id':
					$base_data['shipping_class_id'] = $product->get_shipping_class_id( $context );
					break;
				case 'reviews_allowed':
					$base_data['reviews_allowed'] = $product->get_reviews_allowed( $context );
					break;
				case 'average_rating':
					$base_data['average_rating'] = 'view' === $context ? wc_format_decimal( $product->get_average_rating(), 2 ) : $product->get_average_rating( $context );
					break;
				case 'rating_count':
					$base_data['rating_count'] = $product->get_rating_count();
					break;
				case 'upsell_ids':
					$base_data['upsell_ids'] = array_map( 'absint', $product->get_upsell_ids( $context ) );
					break;
				case 'cross_sell_ids':
					$base_data['cross_sell_ids'] = array_map( 'absint', $product->get_cross_sell_ids( $context ) );
					break;
				case 'parent_id':
					$base_data['parent_id'] = $product->get_parent_id( $context );
					break;
				case 'purchase_note':
					$base_data['purchase_note'] = 'view' === $context ? wpautop( do_shortcode( wp_kses_post( $product->get_purchase_note() ) ) ) : $product->get_purchase_note( $context );
					break;
				case 'categories':
					$base_data['categories'] = $this->get_taxonomy_terms( $product );
					break;
				case 'tags':
					$base_data['tags'] = $this->get_taxonomy_terms( $product, 'tag' );
					break;
				case 'images':
					$base_data['images'] = $this->get_images( $product );
					break;
				case 'attributes':
					$base_data['attributes'] = $this->get_attributes( $product );
					break;
				case 'default_attributes':
					$base_data['default_attributes'] = $this->get_default_attributes( $product );
					break;
				case 'variations':
					$base_data['variations'] = array();
					break;
				case 'grouped_products':
					$base_data['grouped_products'] = array();
					break;
				case 'menu_order':
					$base_data['menu_order'] = $product->get_menu_order( $context );
					break;
			}
		}

		$data = array_merge(
			$base_data,
			$this->fetch_fields_using_getters( $product, $context, $fields )
		);

		return $data;
	}

	/**
	 * Prepare links for the request.
	 *
	 * @param WC_Data         $object  Object data.
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array Links for the given post.
	 */
	protected function prepare_links( $object, $request ) {
		$links = array(
			'self'       => array(
				'href' => rest_url( sprintf( '/%s/%s/%d', $this->namespace, $this->rest_base, $object->get_id() ) ),  // @codingStandardsIgnoreLine.
			),
			'collection' => array(
				'href' => rest_url( sprintf( '/%s/%s', $this->namespace, $this->rest_base ) ),  // @codingStandardsIgnoreLine.
			),
		);

		if ( $object->get_parent_id() ) {
			$links['up'] = array(
				'href' => rest_url( sprintf( '/%s/products/%d', $this->namespace, $object->get_parent_id() ) ),  // @codingStandardsIgnoreLine.
			);
		}

		return $links;
	}

	/**
	 * Prepare a single product for create or update.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|WC_Data
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$id = isset( $request['id'] ) ? absint( $request['id'] ) : 0;

		// Type is the most important part here because we need to be using the correct class and methods.
		if ( isset( $request['type'] ) ) {
			$classname = WC_Product_Factory::get_classname_from_product_type( $request['type'] );

			if ( ! class_exists( $classname ) ) {
				$classname = 'WC_Product_Simple';
			}

			$product = new $classname( $id );
		} elseif ( isset( $request['id'] ) ) {
			$product = wc_get_product( $id );
		} else {
			$product = new WC_Product_Simple();
		}

		if ( 'variation' === $product->get_type() ) {
			return new WP_Error(
				"woocommerce_rest_invalid_{$this->post_type}_id",
				__( 'To manipulate product variations you should use the /products/&lt;product_id&gt;/variations/&lt;id&gt; endpoint.', 'directorist' ),
				array(
					'status' => 404,
				)
			);
		}

		// Post title.
		if ( isset( $request['name'] ) ) {
			$product->set_name( wp_filter_post_kses( $request['name'] ) );
		}

		// Post content.
		if ( isset( $request['description'] ) ) {
			$product->set_description( wp_filter_post_kses( $request['description'] ) );
		}

		// Post excerpt.
		if ( isset( $request['short_description'] ) ) {
			$product->set_short_description( wp_filter_post_kses( $request['short_description'] ) );
		}

		// Post status.
		if ( isset( $request['status'] ) ) {
			$product->set_status( get_post_status_object( $request['status'] ) ? $request['status'] : 'draft' );
		}

		// Post slug.
		if ( isset( $request['slug'] ) ) {
			$product->set_slug( $request['slug'] );
		}

		// Menu order.
		if ( isset( $request['menu_order'] ) ) {
			$product->set_menu_order( $request['menu_order'] );
		}

		// Comment status.
		if ( isset( $request['reviews_allowed'] ) ) {
			$product->set_reviews_allowed( $request['reviews_allowed'] );
		}

		// Virtual.
		if ( isset( $request['virtual'] ) ) {
			$product->set_virtual( $request['virtual'] );
		}

		// Tax status.
		if ( isset( $request['tax_status'] ) ) {
			$product->set_tax_status( $request['tax_status'] );
		}

		// Tax Class.
		if ( isset( $request['tax_class'] ) ) {
			$product->set_tax_class( $request['tax_class'] );
		}

		// Catalog Visibility.
		if ( isset( $request['catalog_visibility'] ) ) {
			$product->set_catalog_visibility( $request['catalog_visibility'] );
		}

		// Purchase Note.
		if ( isset( $request['purchase_note'] ) ) {
			$product->set_purchase_note( wp_kses_post( wp_unslash( $request['purchase_note'] ) ) );
		}

		// Featured Product.
		if ( isset( $request['featured'] ) ) {
			$product->set_featured( $request['featured'] );
		}

		// Shipping data.
		$product = $this->save_product_shipping_data( $product, $request );

		// SKU.
		if ( isset( $request['sku'] ) ) {
			$product->set_sku( wc_clean( $request['sku'] ) );
		}

		// Attributes.
		if ( isset( $request['attributes'] ) ) {
			$attributes = array();

			foreach ( $request['attributes'] as $attribute ) {
				$attribute_id   = 0;
				$attribute_name = '';

				// Check ID for global attributes or name for product attributes.
				if ( ! empty( $attribute['id'] ) ) {
					$attribute_id   = absint( $attribute['id'] );
					$attribute_name = wc_attribute_taxonomy_name_by_id( $attribute_id );
				} elseif ( ! empty( $attribute['name'] ) ) {
					$attribute_name = wc_clean( $attribute['name'] );
				}

				if ( ! $attribute_id && ! $attribute_name ) {
					continue;
				}

				if ( $attribute_id ) {

					if ( isset( $attribute['options'] ) ) {
						$options = $attribute['options'];

						if ( ! is_array( $attribute['options'] ) ) {
							// Text based attributes - Posted values are term names.
							$options = explode( WC_DELIMITER, $options );
						}

						$values = array_map( 'wc_sanitize_term_text_based', $options );
						$values = array_filter( $values, 'strlen' );
					} else {
						$values = array();
					}

					if ( ! empty( $values ) ) {
						// Add attribute to array, but don't set values.
						$attribute_object = new WC_Product_Attribute();
						$attribute_object->set_id( $attribute_id );
						$attribute_object->set_name( $attribute_name );
						$attribute_object->set_options( $values );
						$attribute_object->set_position( isset( $attribute['position'] ) ? (string) absint( $attribute['position'] ) : '0' );
						$attribute_object->set_visible( ( isset( $attribute['visible'] ) && $attribute['visible'] ) ? 1 : 0 );
						$attribute_object->set_variation( ( isset( $attribute['variation'] ) && $attribute['variation'] ) ? 1 : 0 );
						$attributes[] = $attribute_object;
					}
				} elseif ( isset( $attribute['options'] ) ) {
					// Custom attribute - Add attribute to array and set the values.
					if ( is_array( $attribute['options'] ) ) {
						$values = $attribute['options'];
					} else {
						$values = explode( WC_DELIMITER, $attribute['options'] );
					}
					$attribute_object = new WC_Product_Attribute();
					$attribute_object->set_name( $attribute_name );
					$attribute_object->set_options( $values );
					$attribute_object->set_position( isset( $attribute['position'] ) ? (string) absint( $attribute['position'] ) : '0' );
					$attribute_object->set_visible( ( isset( $attribute['visible'] ) && $attribute['visible'] ) ? 1 : 0 );
					$attribute_object->set_variation( ( isset( $attribute['variation'] ) && $attribute['variation'] ) ? 1 : 0 );
					$attributes[] = $attribute_object;
				}
			}
			$product->set_attributes( $attributes );
		}

		// Sales and prices.
		if ( in_array( $product->get_type(), array( 'variable', 'grouped' ), true ) ) {
			$product->set_regular_price( '' );
			$product->set_sale_price( '' );
			$product->set_date_on_sale_to( '' );
			$product->set_date_on_sale_from( '' );
			$product->set_price( '' );
		} else {
			// Regular Price.
			if ( isset( $request['regular_price'] ) ) {
				$product->set_regular_price( $request['regular_price'] );
			}

			// Sale Price.
			if ( isset( $request['sale_price'] ) ) {
				$product->set_sale_price( $request['sale_price'] );
			}

			if ( isset( $request['date_on_sale_from'] ) ) {
				$product->set_date_on_sale_from( $request['date_on_sale_from'] );
			}

			if ( isset( $request['date_on_sale_from_gmt'] ) ) {
				$product->set_date_on_sale_from( $request['date_on_sale_from_gmt'] ? strtotime( $request['date_on_sale_from_gmt'] ) : null );
			}

			if ( isset( $request['date_on_sale_to'] ) ) {
				$product->set_date_on_sale_to( $request['date_on_sale_to'] );
			}

			if ( isset( $request['date_on_sale_to_gmt'] ) ) {
				$product->set_date_on_sale_to( $request['date_on_sale_to_gmt'] ? strtotime( $request['date_on_sale_to_gmt'] ) : null );
			}
		}

		// Product parent ID.
		if ( isset( $request['parent_id'] ) ) {
			$product->set_parent_id( $request['parent_id'] );
		}

		// Sold individually.
		if ( isset( $request['sold_individually'] ) ) {
			$product->set_sold_individually( $request['sold_individually'] );
		}

		// Stock status.
		if ( isset( $request['in_stock'] ) ) {
			$stock_status = true === $request['in_stock'] ? 'instock' : 'outofstock';
		} else {
			$stock_status = $product->get_stock_status();
		}

		// Stock data.
		if ( 'yes' === get_option( 'woocommerce_manage_stock' ) ) {
			// Manage stock.
			if ( isset( $request['manage_stock'] ) ) {
				$product->set_manage_stock( $request['manage_stock'] );
			}

			// Backorders.
			if ( isset( $request['backorders'] ) ) {
				$product->set_backorders( $request['backorders'] );
			}

			if ( $product->is_type( 'grouped' ) ) {
				$product->set_manage_stock( 'no' );
				$product->set_backorders( 'no' );
				$product->set_stock_quantity( '' );
				$product->set_stock_status( $stock_status );
			} elseif ( $product->is_type( 'external' ) ) {
				$product->set_manage_stock( 'no' );
				$product->set_backorders( 'no' );
				$product->set_stock_quantity( '' );
				$product->set_stock_status( 'instock' );
			} elseif ( $product->get_manage_stock() ) {
				// Stock status is always determined by children so sync later.
				if ( ! $product->is_type( 'variable' ) ) {
					$product->set_stock_status( $stock_status );
				}

				// Stock quantity.
				if ( isset( $request['stock_quantity'] ) ) {
					$product->set_stock_quantity( wc_stock_amount( $request['stock_quantity'] ) );
				} elseif ( isset( $request['inventory_delta'] ) ) {
					$stock_quantity  = wc_stock_amount( $product->get_stock_quantity() );
					$stock_quantity += wc_stock_amount( $request['inventory_delta'] );
					$product->set_stock_quantity( wc_stock_amount( $stock_quantity ) );
				}
			} else {
				// Don't manage stock.
				$product->set_manage_stock( 'no' );
				$product->set_stock_quantity( '' );
				$product->set_stock_status( $stock_status );
			}
		} elseif ( ! $product->is_type( 'variable' ) ) {
			$product->set_stock_status( $stock_status );
		}

		// Upsells.
		if ( isset( $request['upsell_ids'] ) ) {
			$upsells = array();
			$ids     = $request['upsell_ids'];

			if ( ! empty( $ids ) ) {
				foreach ( $ids as $id ) {
					if ( $id && $id > 0 ) {
						$upsells[] = $id;
					}
				}
			}

			$product->set_upsell_ids( $upsells );
		}

		// Cross sells.
		if ( isset( $request['cross_sell_ids'] ) ) {
			$crosssells = array();
			$ids        = $request['cross_sell_ids'];

			if ( ! empty( $ids ) ) {
				foreach ( $ids as $id ) {
					if ( $id && $id > 0 ) {
						$crosssells[] = $id;
					}
				}
			}

			$product->set_cross_sell_ids( $crosssells );
		}

		// Product categories.
		if ( isset( $request['categories'] ) && is_array( $request['categories'] ) ) {
			$product = $this->save_taxonomy_terms( $product, $request['categories'] );
		}

		// Product tags.
		if ( isset( $request['tags'] ) && is_array( $request['tags'] ) ) {
			$product = $this->save_taxonomy_terms( $product, $request['tags'], 'tag' );
		}


		// Product url and button text for external products.
		if ( $product->is_type( 'external' ) ) {
			if ( isset( $request['external_url'] ) ) {
				$product->set_product_url( $request['external_url'] );
			}

			if ( isset( $request['button_text'] ) ) {
				$product->set_button_text( $request['button_text'] );
			}
		}

		// Set children for a grouped product.
		if ( $product->is_type( 'grouped' ) && isset( $request['grouped_products'] ) ) {
			$product->set_children( $request['grouped_products'] );
		}

		// Check for featured/gallery images, upload it and set it.
		if ( isset( $request['images'] ) ) {
			$product = $this->set_listing_images( $product, $request['images'] );
		}

		// Allow set meta_data.
		if ( is_array( $request['meta_data'] ) ) {
			foreach ( $request['meta_data'] as $meta ) {
				$product->update_meta_data( $meta['key'], $meta['value'], isset( $meta['id'] ) ? $meta['id'] : '' );
			}
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->post_type`,
		 * refers to the object type slug.
		 *
		 * @param WC_Data         $product  Object object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "woocommerce_rest_pre_insert_{$this->post_type}_object", $product, $request, $creating );
	}

	/**
	 * Set product images.
	 *
	 * @param WC_Product $product Product instance.
	 * @param array      $images  Images data.
	 *
	 * @throws WC_REST_Exception REST API exceptions.
	 * @return WC_Product
	 */
	protected function set_listing_images( $product, $images ) {
		$images = is_array( $images ) ? array_filter( $images ) : array();

		if ( ! empty( $images ) ) {
			$gallery_positions = array();

			foreach ( $images as $index => $image ) {
				$attachment_id = isset( $image['id'] ) ? absint( $image['id'] ) : 0;

				if ( 0 === $attachment_id && isset( $image['src'] ) ) {
					$upload = wc_rest_upload_image_from_url( esc_url_raw( $image['src'] ) );

					if ( is_wp_error( $upload ) ) {
						if ( ! apply_filters( 'woocommerce_rest_suppress_image_upload_error', false, $upload, $product->get_id(), $images ) ) {
							throw new WC_REST_Exception( 'woocommerce_product_image_upload_error', $upload->get_error_message(), 400 );
						} else {
							continue;
						}
					}

					$attachment_id = wc_rest_set_uploaded_image_as_attachment( $upload, $product->get_id() );
				}

				if ( ! wp_attachment_is_image( $attachment_id ) ) {
					/* translators: %s: attachment id */
					throw new WC_REST_Exception( 'woocommerce_product_invalid_image_id', sprintf( __( '#%s is an invalid image ID.', 'directorist' ), $attachment_id ), 400 );
				}

				$gallery_positions[ $attachment_id ] = absint( isset( $image['position'] ) ? $image['position'] : $index );

				// Set the image alt if present.
				if ( ! empty( $image['alt'] ) ) {
					update_post_meta( $attachment_id, '_wp_attachment_image_alt', wc_clean( $image['alt'] ) );
				}

				// Set the image name if present.
				if ( ! empty( $image['name'] ) ) {
					wp_update_post(
						array(
							'ID'         => $attachment_id,
							'post_title' => $image['name'],
						)
					);
				}

				// Set the image source if present, for future reference.
				if ( ! empty( $image['src'] ) ) {
					update_post_meta( $attachment_id, '_wc_attachment_source', esc_url_raw( $image['src'] ) );
				}
			}

			// Sort images and get IDs in correct order.
			asort( $gallery_positions );

			// Get gallery in correct order.
			$gallery = array_keys( $gallery_positions );

			// Featured image is in position 0.
			$image_id = array_shift( $gallery );

			// Set images.
			$product->set_image_id( $image_id );
			$product->set_gallery_image_ids( $gallery );
		} else {
			$product->set_image_id( '' );
			$product->set_gallery_image_ids( array() );
		}

		return $product;
	}

	/**
	 * Save taxonomy terms.
	 *
	 * @param WC_Product $product  Product instance.
	 * @param array      $terms    Terms data.
	 * @param string     $taxonomy Taxonomy name.
	 *
	 * @return WC_Product
	 */
	protected function save_taxonomy_terms( $product, $terms, $taxonomy = 'cat' ) {
		$term_ids = wp_list_pluck( $terms, 'id' );

		if ( 'cat' === $taxonomy ) {
			$product->set_category_ids( $term_ids );
		} elseif ( 'tag' === $taxonomy ) {
			$product->set_tag_ids( $term_ids );
		}

		return $product;
	}

	/**
	 * Get the Listings's schema, conforming to JSON Schema.
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
					'description' => __( 'Listing name.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'slug'                  => array(
					'description' => __( 'Listing slug.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'permalink'             => array(
					'description' => __( 'Listing URL.', 'directorist' ),
					'type'        => 'string',
					'format'      => 'uri',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_created'          => array(
					'description' => __( "The date the listing was created, in the site's timezone.", 'directorist' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_created_gmt'      => array(
					'description' => __( 'The date the listing was created, as GMT.', 'directorist' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_modified'         => array(
					'description' => __( "The date the listing was last modified, in the site's timezone.", 'directorist' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_modified_gmt'     => array(
					'description' => __( 'The date the listing was last modified, as GMT.', 'directorist' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'zip'                  => array(
					'description' => __( 'Zip code.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'phone'                  => array(
					'description' => __( 'Phone number 1.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'phone_2'                  => array(
					'description' => __( 'Phone number 2.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'fax'                  => array(
					'description' => __( 'Fax number.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'email'                  => array(
					'description' => __( 'Email address.', 'directorist' ),
					'type'        => 'string',
					'format'      => 'email',
					'context'     => array( 'view', 'edit' ),
				),
				'website'                => array(
					'description' => __( 'Website url.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'social_links'             => array(
					'description' => __( 'List of social media links.', 'directorist' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'   => array(
								'description' => __( 'Social media name', 'directorist' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'url' => array(
								'description' => __( 'Social media url.', 'directorist' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
						),
					),
				),
				'views_count'              => array(
					'description' => __( 'Visitors view count.', 'directorist' ),
					'type'        => 'integer',
					'default'     => 0,
					'context'     => array( 'view', 'edit' ),
				),
				'map_hidden'              => array(
					'description' => __( 'Map visibility status.', 'directorist' ),
					'type'        => 'boolean',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
				),
				'latitude'              => array(
					'description' => __( 'Address location latitude.', 'directorist' ),
					'type'        => 'number',
					'context'     => array( 'view', 'edit' ),
				),
				'longitude'              => array(
					'description' => __( 'Address location longitude.', 'directorist' ),
					'type'        => 'number',
					'context'     => array( 'view', 'edit' ),
				),
				'pricing_type'              => array(
					'description' => __( 'Pricing type.', 'directorist' ),
					'type'        => 'string',
					'enum'        => array( 'price', 'range' ),
					'context'     => array( 'view', 'edit' ),
				),
				'price'              => array(
					'description' => __( 'Listing price.', 'directorist' ),
					'type'        => 'number',
					'context'     => array( 'view', 'edit' ),
				),
				'price_range'              => array(
					'description' => __( 'Listing price range.', 'directorist' ),
					'type'        => 'string',
					'enum'        => array( 'skimming', 'moderate', 'economy', 'bellow_economy' ),
					'context'     => array( 'view', 'edit' ),
				),
				'contact_owner_hidden'              => array(
					'description' => __( 'Listing owner contact form hidden.', 'directorist' ),
					'type'        => 'boolean',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
				),
				'video_url'              => array(
					'description' => __( 'Video url.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'tagline'              => array(
					'description' => __( 'Tagline.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'address'              => array(
					'description' => __( 'Listing address.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'privacy_policy_signed'              => array(
					'description' => __( 'Agreed with privacy policy.', 'directorist' ),
					'type'        => 'boolen',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
				),
				'terms_condition_signed'              => array(
					'description' => __( 'Agreed with terms and conditions.', 'directorist' ),
					'type'        => 'boolen',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
				),
				'directory_type'              => array(
					'description' => __( 'Multi directory type.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'expiry_date'              => array(
					'description' => __( 'Expiration date.', 'directorist' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
				'never_expired'              => array(
					'description' => __( 'Never expired status.', 'directorist' ),
					'type'        => 'boolen',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
				),
				'featured'              => array(
					'description' => __( 'Featured listing.', 'directorist' ),
					'type'        => 'boolean',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
				),
				'description'           => array(
					'description' => __( 'Listing description.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'short_description'     => array(
					'description' => __( 'Listing short description.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'status'     => array(
					'description' => __( 'Listing status.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'reviews_allowed'       => array(
					'description' => __( 'Allow reviews.', 'directorist' ),
					'type'        => 'boolean',
					'default'     => true,
					'context'     => array( 'view', 'edit' ),
				),
				'average_rating'        => array(
					'description' => __( 'Reviews average rating.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'rating_count'          => array(
					'description' => __( 'Amount of reviews that the product have.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'related_ids'           => array(
					'description' => __( 'List of related listings IDs.', 'directorist' ),
					'type'        => 'array',
					'items'       => array(
						'type' => 'integer',
					),
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'categories'            => array(
					'description' => __( 'List of categories.', 'directorist' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'   => array(
								'description' => __( 'Category ID.', 'directorist' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
							'name' => array(
								'description' => __( 'Category name.', 'directorist' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'slug' => array(
								'description' => __( 'Category slug.', 'directorist' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
						),
					),
				),
				'tags'                  => array(
					'description' => __( 'List of tags.', 'directorist' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'   => array(
								'description' => __( 'Tag ID.', 'directorist' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
							'name' => array(
								'description' => __( 'Tag name.', 'directorist' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'slug' => array(
								'description' => __( 'Tag slug.', 'directorist' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
						),
					),
				),
				'locations'                  => array(
					'description' => __( 'List of locations.', 'directorist' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'   => array(
								'description' => __( 'Location ID.', 'directorist' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
							'name' => array(
								'description' => __( 'Location name.', 'directorist' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'slug' => array(
								'description' => __( 'Location slug.', 'directorist' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
						),
					),
				),
				'images'                => array(
					'description' => __( 'List of images.', 'directorist' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
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
							'position'          => array(
								'description' => __( 'Image position. 0 means that the image is featured.', 'directorist' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
						),
					),
				),
				'menu_order'            => array(
					'description' => __( 'Menu order, used to custom sort listings.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'author'            => array(
					'description' => __( 'Listing author id.', 'directorist' ),
					'type'        => 'integer',
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
	 * Get the query params for collections of listings.
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		$params['orderby']['enum'] = array_merge( $params['orderby']['enum'], array( 'menu_order' ) );

		$params['slug'] = array(
			'description'       => __( 'Limit result set to listings with a specific slug.', 'directorist' ),
			'type'              => 'string',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['status'] = array(
			'default'           => 'any',
			'description'       => __( 'Limit result set to listings assigned a specific status.', 'directorist' ),
			'type'              => 'string',
			'enum'              => array_merge( array( 'any', 'future', 'trash' ), array_keys( get_post_statuses() ) ),
			'sanitize_callback' => 'sanitize_key',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['featured'] = array(
			'description'       => __( 'Limit result set to featured listings.', 'directorist' ),
			'type'              => 'boolean',
			'sanitize_callback' => 'directorist_string_to_bool',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['category'] = array(
			'description'       => __( 'Limit result set to listings assigned a specific category ID.', 'directorist' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['tag'] = array(
			'description'       => __( 'Limit result set to listings assigned a specific tag ID.', 'directorist' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['location'] = array(
			'description'       => __( 'Limit result set to listings assigned a specific location ID.', 'directorist' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['min_price'] = array(
			'description'       => __( 'Limit result set to listings based on a minimum price.', 'directorist' ),
			'type'              => 'string',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['max_price'] = array(
			'description'       => __( 'Limit result set to listings based on maximum price.', 'directorist' ),
			'type'              => 'string',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['price_range'] = array(
			'description' => __( 'Limit result set to listings based on price range.', 'directorist' ),
			'type'        => 'string',
			'enum'        => array( 'skimming', 'moderate', 'economy', 'bellow_economy' ),
		);
		$params['radius'] = array(
			'description'       => __( 'Limit result set to listings based on radius search.', 'directorist' ),
			'type'              => 'string',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['author'] = array(
			'description'       => __( 'Limit result set to listings specific to author ID.', 'directorist' ),
			'type'              => 'string',
			'validate_callback' => 'rest_validate_request_arg',
		);

		return $params;
	}

	/**
	 * Delete post.
	 *
	 * @param WP_Post $post Post object.
	 */
	protected function delete_post( $post ) {
		wp_delete_post( $post->ID, true );
	}
}
