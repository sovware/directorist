<?php
/**
 * Rest Listings Controller
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
 * Listings controller class.
 */
class Listings_Controller extends Posts_Controller {

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
	 * Register the routes for listings.
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
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
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
						'description' => __( 'Unique identifier for the resource.', 'directorist' ),
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

		do_action( 'directorist_rest_before_query', 'get_listing_items', $request, $query_args );

		$query_results = $this->get_listings( $query_args );

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

		do_action( 'directorist_rest_after_query', 'get_listing_items', $request, $query_args );

		$response = apply_filters( 'directorist_rest_response', $response, 'get_listing_items', $request, $query_args );

		return $response;
	}

	protected function get_listings( $query_args ) {
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

		// Set featured query.
		$is_featured = false;
		if ( isset( $request['featured'] ) && $request['featured'] ) {
			$is_featured = true;
			$meta_query['_featured'] = [
				'key'     => '_featured',
				'value'   => 1,
				'compare' => '=',
			];
		}

		// Set directory type query.
		if ( isset( $request['directory'] ) ) {
			$meta_query['_directory_type'] = [
				'key'     => '_directory_type',
				'value'   => $request['directory'],
				'compare' => '=',
			];
		}

		// Set categories query.
		if ( isset( $request['categories'] ) ) {
			$tax_query['tax_query'][] = [
				'taxonomy'         => ATBDP_CATEGORY,
				'field'            => 'term_id',
				'terms'            => $request['categories'],
				'include_children' => true, /*@todo; Add option to include children or exclude it*/
			];
		}

		// Set locations query.
		if ( isset( $request['locations'] ) ) {
			$tax_query['tax_query'][] = [
				'taxonomy'         => ATBDP_LOCATION,
				'field'            => 'term_id',
				'terms'            => $request['locations'],
				'include_children' => true, /*@todo; Add option to include children or exclude it*/
			];
		}

		// Set locations query.
		if ( isset( $request['tags'] ) ) {
			$tax_query['tax_query'][] = [
				'taxonomy'         => ATBDP_TAGS,
				'field'            => 'term_id',
				'terms'            => $request['tags'],
				'include_children' => true, /*@todo; Add option to include children or exclude it*/
			];
		}

		switch ( $args['orderby'] ) {
			case 'id':
				$args['orderby'] = 'ID';
				break;

			case 'include':
				$args['orderby'] = 'post__in';
				break;

			case 'title':
				if ( $is_featured ) {
					$args['meta_key'] = '_featured';
					$args['orderby']  = [
						'meta_value_num' => 'DESC',
						'title'          => $args['order'],
					];
				}
				break;

			case 'date':
				if ( $is_featured ) {
					$args['meta_key'] = '_featured';
					$args['orderby']  = [
						'meta_value_num' => 'DESC',
						'date'           => $args['order'],
					];
				}
				break;

			case 'price':
				if ( $is_featured ) {
					$meta_query['price'] = [
						'key'     => '_price',
						'type'    => 'NUMERIC',
						'compare' => 'EXISTS',
					];

					$args['orderby'] = [
						'_featured' => 'DESC',
						'price'     => $args['orderby'],
					];
				} else {
					$args['meta_key'] = '_price';
					$args['orderby']  = 'meta_value_num';
					$args['order']    = $args['orderby'];
				}
				break;

			case 'popular':
				$meta_query['views'] = [
					'key'     => directorist_get_listing_views_count_meta_key(),
					'value'   => get_directorist_option( 'views_for_popular', 4 ),
					'type'    => 'NUMERIC',
					'compare' => '>=',
				];

				if ( $is_featured ) {
					$args['orderby'] = [
						'_featured' => $args['order'],
						'views'     => $args['order'],
					];
				} else {
					$args['orderby'] = [
						'views' => $args['order'],
					];
				}
		}

		// TODO: Status has been migrated, remove related code.
		// // Expired listings query.
		// $meta_query['expired'] = array(
		// 	'key'     => '_listing_status',
		// 	'value'   => 'expired',
		// 	'compare' => '!='
		// );

		// if ( $args['post_status'] === 'expired' ) {
		// 	// Get only expired listings
		// 	$meta_query['expired'] = array(
		// 		'key'     => '_listing_status',
		// 		'value'   => 'expired',
		// 		'compare' => '==',
		// 	);

		// 	// Expired listings have post_status => private hence we need to set any.
		// 	$args['post_status'] = 'any';
		// }
		// TODO: Remove the above lines due to listing status migration.

		// Price query.
		if ( isset( $request['min_price'] ) || isset( $request['max_price'] ) ) {
			if ( $request['min_price'] && $request['min_price'] ) {
				$meta_query['price'] = array(
					'key'     => '_price',
					'value'   => array( $request['min_price'], $request['max_price'] ),
					'type'    => 'NUMERIC',
					'compare' => 'BETWEEN'
				);
			} elseif ( $request['min_price'] ) {
				$meta_query['price'] = array(
					'key'     => '_price',
					'value'   => $request['min_price'],
					'type'    => 'NUMERIC',
					'compare' => '>='
				);
			} elseif ( $request['max_price'] ) {
				$meta_query['price'] = array(
					'key'     => '_price',
					'value'   => $request['max_price'],
					'type'    => 'NUMERIC',
					'compare' => '<='
				);
			}
		}

		// Price range query.
		if ( ! empty( $request['price_range'] ) ) {
			$meta_query['price_range'] = array(
				'key'     => '_price_range',
				'value'   => $request['price_range'],
				'compare' => '='
			);
		}

		if ( ! empty( $request['website'] ) ) {
			$meta_query['website'] = array(
				'key'     => '_website',
				'value'   => $request['website'],
				'compare' => 'LIKE'
			);
		}

		if ( ! empty( $request['email'] ) ) {
			$meta_query['email'] = array(
				'key'     => '_email',
				'value'   => $request['email'],
				'compare' => 'LIKE'
			);
		}

		if ( ! empty( $request['phone'] ) ) {
			$meta_query['phone'] = array(
				'relation' => 'OR',
				array(
					'key' => '_phone2',
					'value' => $request['phone'],
					'compare' => 'LIKE'
				),
				array(
					'key' => '_phone',
					'value' => $request['phone'],
					'compare' => 'LIKE'
				)
			);
		}

		if ( ! empty( $request['fax'] ) ) {
			$meta_query['fax'] = array(
				'key'     => '_fax',
				'value'   => $request['fax'],
				'compare' => 'LIKE'
			);
		}

		if ( ! empty( $request['zip'] ) ) {
			$meta_query['zip'] = array(
				'key'     => '_zip',
				'value'   => $request['zip'],
				'compare' => 'LIKE'
			);
		}

		// Rating query.
		if ( ! empty( $request['rating'] ) ) {
			$meta_query['rating'] = array(
				'key'     => directorist_get_rating_field_meta_key(),
				'value'   => $request['rating'],
				'type'    => 'NUMERIC',
				'compare' => '>=',
			);
		}

		// Radius query.
		if ( isset( $request['radius'] ) ) {
			$args['atbdp_geo_query'] = array(
				'lat_field' => '_manual_lat',
				'lng_field' => '_manual_lng',
				'latitude'  => $request['radius']['latitude'],
				'longitude' => $request['radius']['longitude'],
				'distance'  => $request['radius']['distance'],
				'units'     => get_directorist_option( 'radius_search_unit', 'miles' )
			);
		}

		if ( ! empty( $meta_query ) ) {
			$meta_query[]['relation'] = 'AND';
			$args['meta_query'] = $meta_query;
		}

		if ( ! empty( $tax_query ) ) {
			$tax_query[]['relation'] = 'AND';
			$args['tax_query']       = $tax_query;
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

		do_action( 'directorist_rest_before_query', 'get_listing_item', $request, $id );

		$post = get_post( $id );

		if ( empty( $id ) || empty( $post->ID ) || $post->post_type !== $this->post_type ) {
			return new WP_Error( "directorist_rest_invalid_{$this->post_type}_id", __( 'Invalid ID.', 'directorist' ), array( 'status' => 404 ) );
		}

		$data = $this->prepare_item_for_response( $post, $request );
		$response = rest_ensure_response( $data );

		// if ( $this->public ) {
		// 	$response->link_header( 'alternate', get_permalink( $id ), array( 'type' => 'text/html' ) );
		// }

		do_action( 'directorist_rest_after_query', 'get_listing_item', $request, $id );

		$response = apply_filters( 'directorist_rest_response', $response, 'get_listing_item', $request, $id );

		return $response;
	}

	/**
	 * Prepare a single listings output for response.
	 *
	 * @param WP_Post         $object  Object data.
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response
	 */
	public function prepare_item_for_response( $object, $request ) {
		$context       = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$this->request = $request;
		$data          = $this->get_listing_data( $object, $request, $context );

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
	 * Get taxonomy terms.
	 *
	 * @param int     $post_id  Listing id.
	 * @param string  $taxonomy Taxonomy slug.
	 *
	 * @return array
	 */
	protected function get_taxonomy_terms( $post_id, $taxonomy = '' ) {
		$terms = array();

		foreach ( directorist_get_object_terms( $post_id, $taxonomy ) as $term ) {
			$_term = array(
				'id'   => $term->term_id,
				'name' => $term->name,
				'slug' => $term->slug,
			);

			if ( $taxonomy === ATBDP_CATEGORY ) {
				$_term['icon'] = get_term_meta( $term->term_id, 'category_icon', true );
			}

			$terms[] = $_term;
		}

		return $terms;
	}

	/**
	 * Get the images for a listing.
	 *
	 * @param WP_Post $listing.
	 *
	 * @return array
	 */
	protected function get_images( $listing, $context ) {
		$images         = array();
		$attachment_ids = array();

		// Add featured image.
		if ( has_post_thumbnail( $listing ) ) {
			$attachment_ids[] = get_post_thumbnail_id( $listing );
		} else {
			$thumbnail_id = (int) get_post_meta( $listing->ID, '_listing_prv_img', true );
			if ( $thumbnail_id ) {
				$attachment_ids[] = $thumbnail_id;
			}
		}

		// Add gallery images.
		$gallery_images = (array) get_post_meta( $listing->ID, '_listing_img', true );
		if ( ! empty( $gallery_images ) ) {
			$attachment_ids = array_unique( array_merge( $attachment_ids, array_filter( wp_parse_id_list( $gallery_images ) ) ) );
		}

		// Build image data.
		foreach ( $attachment_ids as $position => $attachment_id ) {
			$attachment_post = get_post( $attachment_id );
			if ( is_null( $attachment_post ) ) {
				continue;
			}

			$image_url = wp_get_attachment_image_url( $attachment_id, ( $context === 'view' ? 'large' : 'full' ) );
			if ( ! $image_url ) {
				continue;
			}

			$images[] = array(
				'id'                => (int) $attachment_id,
				'date_created'      => directorist_rest_prepare_date_response( $attachment_post->post_date, false ),
				'date_created_gmt'  => directorist_rest_prepare_date_response( strtotime( $attachment_post->post_date_gmt ) ),
				'date_modified'     => directorist_rest_prepare_date_response( $attachment_post->post_modified, false ),
				'date_modified_gmt' => directorist_rest_prepare_date_response( strtotime( $attachment_post->post_modified_gmt ) ),
				'src'               => $image_url,
				'name'              => get_the_title( $attachment_id ),
				'alt'               => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
				'position'          => (int) $position,
			);
		}

		return $images;
	}

	/**
	 * Get listing data.
	 *
	 * @param WP_Post   $listing WP_Post instance.
	 * @param WP_REST_Request $request Request object.
	 * @param string    $context Request context. Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_listing_data( $listing, $request, $context = 'view' ) {
		$fields  = $this->get_fields_for_response( $request );

		$base_data = array();
		foreach ( $fields as $field ) {
			switch ( $field ) {
				case 'id':
					$base_data['id'] = $listing->ID;
					break;
				case 'name':
					$base_data['name'] = get_the_title( $listing );
					break;
				case 'slug':
					$base_data['slug'] = $listing->post_name;
					break;
				case 'permalink':
					$base_data['permalink'] = get_the_permalink( $listing );
					break;
				case 'date_created':
					$base_data['date_created'] = directorist_rest_prepare_date_response( $listing->post_date, false );
					break;
				case 'date_created_gmt':
					$base_data['date_created_gmt'] = directorist_rest_prepare_date_response( $listing->post_date_gmt );
					break;
				case 'date_modified':
					$base_data['date_modified'] = directorist_rest_prepare_date_response( $listing->post_date_modified, false );
					break;
				case 'date_modified_gmt':
					$base_data['date_modified_gmt'] = directorist_rest_prepare_date_response( $listing->post_date_modified_gmt );
					break;
				case 'views_count':
					$base_data['views_count'] = directorist_get_listing_views_count( $listing->ID );
					break;
				case 'directory':
					$base_data['directory'] = $this->get_directory_id( $listing );
					break;
				case 'date_expired':
					$base_data['date_expired'] = directorist_rest_prepare_date_response( get_post_meta( $listing->ID, '_expiry_date', true ) );
					break;
				case 'never_expired':
					$base_data['never_expired'] = (bool) get_post_meta( $listing->ID, '_never_expire', true );
					break;
				case 'featured':
					$base_data['featured'] = (bool) get_post_meta( $listing->ID, '_featured', true );
					break;
				case 'new':
					$base_data['new'] = (bool) Helper::is_new( $listing->ID );
					break;
				case 'popular':
					$base_data['popular'] = (bool) Helper::is_popular( $listing->ID );
					break;
				case 'status':
					// TODO: Status has been migrated, remove related code.
					// $listing_status = get_post_meta( $listing->ID, '_listing_status', true );
					// if ( $listing_status && $listing_status === 'expired' ) {
					// 	$base_data['status'] = 'expired';
					// } else {
					// 	$base_data['status'] = $listing->post_status;
					// }
					$base_data['status'] = $listing->post_status;
					break;
				case 'reviews_allowed':
					$base_data['reviews_allowed'] = directorist_is_review_enabled();
					break;
				case 'average_rating':
					$base_data['average_rating'] = directorist_get_listing_rating( $listing->ID );
					break;
				case 'rating_count':
					$base_data['rating_count'] = directorist_get_listing_review_count( $listing->ID );
					break;
				case 'related_ids':
					$base_data['related_ids'] = $this->get_related_listings_ids( $listing->ID );
					break;
				case 'menu_order':
					$base_data['menu_order'] = (int) $listing->menu_order;
					break;
				case 'author':
					$base_data['author'] = (int) $listing->post_author;
					break;
				case 'plan':
					$base_data['plan'] = $this->get_plan_id( $listing );
					break;
				case 'description':
					$base_data['description'] = 'view' === $context ? wpautop( do_shortcode( $listing->post_content ) ) : $listing->post_content;
					break;
				case 'short_description':
					$base_data['short_description'] = 'view' === $context ? $listing->post_excerpt : $listing->post_excerpt;
					break;
				case 'zip':
					$base_data['zip'] = get_post_meta( $listing->ID, '_zip', true );
					break;
				case 'phone':
					$base_data['phone'] = get_post_meta( $listing->ID, '_phone', true );
					break;
				case 'phone_2':
					$base_data['phone_2'] = get_post_meta( $listing->ID, '_phone2', true );
					break;
				case 'fax':
					$base_data['fax'] = get_post_meta( $listing->ID, '_fax', true );
					break;
				case 'email':
					$base_data['email'] = get_post_meta( $listing->ID, '_email', true );
					break;
				case 'website':
					$base_data['website'] = get_post_meta( $listing->ID, '_website', true );
					break;
				case 'social_links':
					$base_data['social_links'] = $this->get_listing_social_links( $listing->ID );
					break;
				case 'map_hidden':
					$base_data['map_hidden'] = (bool) get_post_meta( $listing->ID, '_hide_map', true );
					break;
				case 'address':
					$base_data['address'] = get_post_meta( $listing->ID, '_address', true );
					break;
				case 'latitude':
					$base_data['latitude'] = get_post_meta( $listing->ID, '_manual_lat', true );
					break;
				case 'longitude':
					$base_data['longitude'] = get_post_meta( $listing->ID, '_manual_lng', true );
					break;
				case 'pricing_type':
					$base_data['pricing_type'] = get_post_meta( $listing->ID, '_atbd_listing_pricing', true );
					break;
				case 'price':
					$base_data['price'] = (float) get_post_meta( $listing->ID, '_price', true );
					break;
				case 'price_range':
					$base_data['price_range'] = get_post_meta( $listing->ID, '_price_range', true );
					break;
				case 'owner_contact_hidden':
					$base_data['owner_contact_hidden'] = (bool) get_post_meta( $listing->ID, '_hide_contact_owner', true );
					break;
				case 'video_url':
					$base_data['video_url'] = get_post_meta( $listing->ID, '_videourl', true );
					break;
				case 'tagline':
					$base_data['tagline'] = get_post_meta( $listing->ID, '_tagline', true );
					break;
				case 'categories':
					$base_data['categories'] = $this->get_taxonomy_terms( $listing->ID, ATBDP_CATEGORY );
					break;
				case 'tags':
					$base_data['tags'] = $this->get_taxonomy_terms( $listing->ID, ATBDP_TAGS );
					break;
				case 'locations':
					$base_data['locations'] = $this->get_taxonomy_terms( $listing->ID, ATBDP_LOCATION );
					break;
				case 'images':
					$base_data['images'] = $this->get_images( $listing, $context );
					break;
			}
		}

		return $base_data;
	}

	protected function get_listing_social_links( $id ) {
		$links = get_post_meta( $id, '_social', true );

		if ( empty( $links ) || ! is_array( $links )) {
			return null;
		}

		$data = array();
		foreach ( $links as $link ) {
			if ( empty( $link['id'] ) || empty( $link['url'] ) ) {
				continue;
			}
			$data[] = array(
				'id' => $link['id'],
				'url' => $link['url']
			);
		}

		return $data;
	}

	protected function get_directory_id( $listing ) {
		$directory_id = (int) get_post_meta( $listing->ID, '_directory_type', true );

		return $directory_id;
	}

	protected function get_plan_id( $listing ) {
		return ( (int) get_post_meta( $listing->ID, '_fm_plans', true ) );
	}

	protected function get_related_listings_ids( $listing_id ) {
		$directory_type = (int) get_post_meta( $listing_id, '_directory_type', true );
		$number         = get_directorist_type_option( $directory_type, 'similar_listings_number_of_listings_to_show', 2 );
		$same_author    = get_directorist_type_option( $directory_type, 'listing_from_same_author', false );
		$logic          = get_directorist_type_option( $directory_type, 'similar_listings_logics', 'OR' );
		$relationship   = ( in_array( $logic, array( 'AND', 'OR' ) ) ? $logic : 'OR' );

		$categories   = directorist_get_object_terms( $listing_id, ATBDP_CATEGORY, 'term_id' );
		$tags         = directorist_get_object_terms( $listing_id, ATBDP_TAGS, 'term_id' );

		$args = array(
			'post_type'      => ATBDP_POST_TYPE,
			'posts_per_page' => (int) $number,
			'post__not_in'   => array( $listing_id ),
			'tax_query'      => array(
				'relation' => $relationship,
				array(
					'taxonomy' => ATBDP_CATEGORY,
					'field'    => 'term_id',
					'terms'    => $categories,
				),
				array(
					'taxonomy' => ATBDP_TAGS,
					'field'    => 'term_id',
					'terms'    => $tags,
				),
			),
		);

		if ( ! empty( $same_author ) ){
			$args['author']  = get_post_field( 'post_author', $listing_id );
		}

		$meta_queries = array();
		// TODO: Status has been migrated, remove related code.
		// $meta_queries['expired'] = array(
		// 	'key'     => '_listing_status',
		// 	'value'   => 'expired',
		// 	'compare' => '!=',
		// );
		$meta_queries['directory_type'] = array(
			'key'     => '_directory_type',
			'value'   => $directory_type,
			'compare' => '=',
		);

		$meta_queries = apply_filters('atbdp_related_listings_meta_queries', $meta_queries);
		$count_meta_queries = count($meta_queries);
		if ($count_meta_queries) {
			$args['meta_query'] = ($count_meta_queries > 1) ? array_merge(array('relation' => 'AND'), $meta_queries) : $meta_queries;
		}

		$args    = apply_filters( 'directorist_related_listing_args', $args );
		$related = new \Directorist\Directorist_Listings( [], 'related', $args, ['cache' => false] );

		return $related->post_ids();
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

		$plan_id = $this->get_plan_id( $object );
		if ( $plan_id ) {
			$links['plan'] = array(
				'href' => rest_url( sprintf( '/%s/%s/%d', $this->namespace, 'plans', $plan_id ) ),  // @codingStandardsIgnoreLine.
			);
		}

		if ( $object->post_parent ) {
			$links['up'] = array(
				'href' => rest_url( sprintf( '/%s/listings/%d', $this->namespace, $object->post_parent ) ),  // @codingStandardsIgnoreLine.
			);
		}

		return $links;
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
					'description' => __( 'Map visibility status status.', 'directorist' ),
					'type'        => 'boolean',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
				),
				'address'              => array(
					'description' => __( 'Listing address.', 'directorist' ),
					'type'        => 'string',
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
					  // 'enum'        => array( 'price', 'range' ),
					'context' => array( 'view', 'edit' ),
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
				'owner_contact_hidden'              => array(
					'description' => __( 'Listing owner contact form visibility status.', 'directorist' ),
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
				'directory' => array(
					'description' => __( 'Multi directory type id.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'date_expired'              => array(
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
				'new'              => array(
					'description' => __( 'New listing.', 'directorist' ),
					'type'        => 'boolean',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'popular'              => array(
					'description' => __( 'Popular listing.', 'directorist' ),
					'type'        => 'boolean',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
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
					'description' => __( 'Amount of reviews that the listing have.', 'directorist' ),
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
					'context'  => array( 'view', 'edit' ),
					'readonly' => true,
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
							'icon' => array(
								'description' => __( 'Category icon.', 'directorist' ),
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
				'plan' => array(
					'description' => __( 'Listing plan id.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
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

		$params['context']['default'] = 'view';

		$params['exclude'] = array(
			'description'       => __( 'Ensure result set excludes specific IDs.', 'directorist' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback'  => 'rest_validate_request_arg',
		);
		$params['include'] = array(
			'description'       => __( 'Limit result set to specific IDs.', 'directorist' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback'  => 'rest_validate_request_arg',
		);
		$params['offset'] = array(
			'description'        => __( 'Offset the result set by a specific number of items.', 'directorist' ),
			'type'               => 'integer',
			'sanitize_callback'  => 'absint',
			'validate_callback'  => 'rest_validate_request_arg',
		);
		$params['order'] = array(
			'default'            => 'desc',
			'description'        => __( 'Order sort attribute ascending or descending.', 'directorist' ),
			'enum'               => array( 'asc', 'desc' ),
			'sanitize_callback'  => 'sanitize_key',
			'type'               => 'string',
			'validate_callback'  => 'rest_validate_request_arg',
		);
		$params['orderby'] = array(
			'default'            => 'date',
			'description'        => __( 'Sort collection by object attribute.', 'directorist' ),
			'enum'               => array_keys( $this->get_orderby_possibles() ),
			'sanitize_callback'  => 'sanitize_key',
			'type'               => 'string',
			'validate_callback'  => 'rest_validate_request_arg',
		);
		$params['slug'] = array(
			'description'       => __( 'Limit result set to listings with a specific slug.', 'directorist' ),
			'type'              => 'string',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['status'] = array(
			'default'           => 'publish',
			'description'       => __( 'Limit result set to listings assigned a specific status.', 'directorist' ),
			'type'              => 'string',
			'enum'              => array_merge( array( 'any', 'future', 'trash', 'expired' ), array_keys( get_post_statuses() ) ),
			'sanitize_callback' => 'sanitize_key',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['featured'] = array(
			'description'       => __( 'Limit result set to featured listings.', 'directorist' ),
			'type'              => 'boolean',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['categories'] = array(
			'description'       => __( 'Limit result set to listings assigned a specific category ID.', 'directorist' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['tags'] = array(
			'description'       => __( 'Limit result set to listings assigned a specific tag ID.', 'directorist' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['locations'] = array(
			'description'       => __( 'Limit result set to listings assigned a specific location ID.', 'directorist' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['min_price'] = array(
			'description'       => __( 'Limit result set to listings based on a minimum price.', 'directorist' ),
			'type'              => 'integer',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['max_price'] = array(
			'description'       => __( 'Limit result set to listings based on maximum price.', 'directorist' ),
			'type'              => 'integer',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['price_range'] = array(
			'description'       => __( 'Limit result set to listings based on price range.', 'directorist' ),
			'type'              => 'string',
			'enum'              => array( 'skimming', 'moderate', 'economy', 'bellow_economy' ),
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['rating'] = array(
			'description'       => __( 'Limit result set to specified rating.', 'directorist' ),
			'type'              => 'integer',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['radius'] = array(
			'description'       => __( 'Limit result set to listings based on radius search.', 'directorist' ),
			'type'              => 'object',
			'properties'        => array(
				'latitude'  => array(
					'type'     => 'string',
					'required' => true,
				),
				'longitude' => array(
					'type'     => 'string',
					'required' => true,
				),
				'distance' => array(
					'type'     => 'string',
					'required' => true,
				)
			),
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['directory'] = array(
			'description'       => __( 'Limit result set to listings to sepecific directory type.', 'directorist' ),
			'type'              => 'integar',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['author'] = array(
			'description'       => __( 'Limit result set to listings specific to author ID.', 'directorist' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);

		return $params;
	}

	protected function get_orderby_possibles() {
		return array(
			'id'      => 'ID',
			'include' => 'include',
			'title'   => 'title',
			'date'    => 'date',
			// 'rating'  => 'rating',
			'popular' => 'popular',
			'price'   => 'price',
		);
	}

	/**
	 * Delete post.
	 *
	 * @param WP_Post $post Post object.
	 */
	protected function delete_post( $post ) {
		do_action( 'directorist_rest_before_query', 'delete_listing_item', $post );

		wp_delete_post( $post->ID, true );

		do_action( 'directorist_rest_after_query', 'delete_listing_item', $post );
	}
}
