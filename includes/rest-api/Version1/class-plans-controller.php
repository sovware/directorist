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

	public function get_items_permissions_check( $request ) {
		if ( ! is_fee_manager_active() ) {
			return new WP_Error( 'extension_inactive', __( 'Pricing plan extension inactive.', 'directorist' ), 400 );
		}
		return parent::get_items_permissions_check( $request );
	}

	public function get_item_permissions_check( $request ) {
		if ( ! is_fee_manager_active() ) {
			return new WP_Error( 'extension_inactive', __( 'Pricing plan extension inactive.', 'directorist' ), 400 );
		}
		return parent::get_item_permissions_check( $request );
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
		$args['order']          = $request['order'];
		$args['orderby']        = $request['orderby'];
		$args['paged']          = $request['page'];

		if ( directorist_is_multi_directory_enabled() && ! empty( $request['directory'] ) ) {
			$args['meta_key'] = '_assign_to_directory';
			$args['meta_value'] = $request['directory'];
		}

		if ( ! directorist_is_multi_directory_enabled() ) {
			$args['meta_key'] = '_assign_to_directory';
			$args['meta_value'] = directorist_get_default_directory();
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

	protected function get_directory_id( $plan ) {
		return (int) get_post_meta( $plan->ID, '_assign_to_directory', true );
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
					$base_data['description'] = get_post_meta( $plan->ID, 'fm_description', true );
					break;
				case 'hide_description_from_plan':
					$base_data['hide_description_from_plan'] = (bool) get_post_meta( $plan->ID, 'hide_description', true );
					break;
				case 'directory':
					$base_data['directory'] = $this->get_directory_id( $plan );
					break;
				case 'status':
					$base_data['status'] = $plan->post_status;
					break;
				case 'is_recommended':
					$base_data['is_recommended'] = ( get_post_meta( $plan->ID, 'default_pln', true ) === 'yes' );
					break;
				case 'is_hidden':
					$base_data['is_hidden'] = ( get_post_meta( $plan->ID, '_hide_from_plans', true ) === 'yes' );
					break;
				case 'type':
					$base_data['type'] = $this->get_plan_type( $plan );
					break;
				case 'type_label':
					$base_data['type_label'] = $this->get_plan_type( $plan ) === 'package' ? esc_html__( 'Per Package', 'directorist' ) : esc_html__( 'Per Listing', 'directorist' );
					break;
				case 'currency':
					$base_data['currency'] = atbdp_get_payment_currency();
					break;
				case 'currency_symbol':
					$base_data['currency_symbol'] = html_entity_decode( atbdp_currency_symbol( atbdp_get_payment_currency() ) );
					break;
				case 'is_free':
					$base_data['is_free'] = (bool) get_post_meta( $plan->ID, 'free_plan', true );
					break;
				case 'price':
					$base_data['price'] = (float) get_post_meta( $plan->ID, 'fm_price', true );
					break;
				case 'is_taxable':
					$base_data['is_taxable'] = (bool) get_post_meta( $plan->ID, 'plan_tax', true );
					break;
				case 'tax_type':
					$base_data['tax_type'] = $this->get_tax_type( $plan );
					break;
				case 'tax':
					$base_data['tax'] = (float) get_post_meta( $plan->ID, 'fm_tax', true );
					break;
				case 'validity_period':
					$base_data['validity_period'] = (int) get_post_meta( $plan->ID, 'fm_length', true );
					break;
				case 'validity_period_unit':
					$base_data['validity_period_unit'] = $this->get_validity_period_unit( $plan );
					break;
				case 'validity_period_label':
					$base_data['validity_period_label'] = $this->get_validity_period_label( $plan );
					break;
				case 'is_non_expiring':
					$base_data['is_non_expiring'] = (bool) get_post_meta( $plan->ID, 'fm_length_unl', true );
					break;
				case 'features':
					$base_data['features'] = $this->get_features_data( $plan );
					break;
				case 'fields':
					$base_data['fields'] = $this->get_fields_data( $plan );
					break;
			}
		}

		return $base_data;
	}

	protected function get_validity_period_label( $plan ) {
		$is_non_expiring = (bool) get_post_meta( $plan->ID, 'fm_length_unl', true );

		if ( $is_non_expiring ) {
			return esc_html__( 'Lifetime', 'directorist' );
		}

		$validity_period = (int) get_post_meta( $plan->ID, 'fm_length', true );

		$translations = array(
			'day'   => _n( 'Day', '%d days', $validity_period, 'directorist' ),
			'week'  => _n( 'Week', '%d weeks', $validity_period, 'directorist' ),
			'month' => _n( 'Month', '%d months', $validity_period, 'directorist' ),
			'year'  => _n( 'Year', '%d years', $validity_period, 'directorist' ),
		);

		return sprintf( $translations[ $this->get_validity_period_unit( $plan ) ], $validity_period );
	}

	protected function get_validity_period_unit( $plan ) {
		$unit = get_post_meta( $plan->ID, '_recurrence_period_term', true );
		return ( in_array( $unit, array( 'day', 'week', 'month', 'year' ), true ) ? $unit : 'day' );
	}

	protected function get_tax_type( $plan ) {
		$tax_type = get_post_meta( $plan->ID, 'plan_tax_type', true );

		if ( ! empty( $tax_type ) && $tax_type === 'percent' ) {
			return 'percentage';
		}

		return 'fixed';
	}

	protected function get_plan_type( $plan ) {
		$plan_type = get_post_meta( $plan->ID, 'plan_type', true );

		if ( ! empty( $plan_type ) && $plan_type === 'pay_per_listng' ) {
			return 'pay_per_listing';
		}

		return 'package';
	}

	protected function get_features_data( $plan ) {
		$features = array();

		$features[] = array(
			'key'            => 'auto_renewal',
			'label'          => esc_html__( 'Auto renewing', 'directorist' ),
			'is_active'      => (bool) get_post_meta( $plan->ID, '_atpp_recurring', true ),
			'hide_from_plan' => (bool) get_post_meta( $plan->ID, 'hide_recurring', true ),
		);

		if ( $this->get_plan_type( $plan ) === 'package' ) {
			$regular_listing_count = (int) get_post_meta( $plan->ID, 'num_regular', true );
			$unlimited_regular_listings = (bool) get_post_meta( $plan->ID, 'num_regular_unl', true );

			if ( $unlimited_regular_listings ) {
				$regular_listing_label = __( 'Unlimited Regular Listings', 'directorist' );
			} else {
				$regular_listing_label = sprintf( _n( '%s Regular Listing', '%s Regular Listings', $regular_listing_count, 'directorist' ), $regular_listing_count );
			}

			$features[] = array(
				'key'            => 'regular_listings',
				'label'          => $regular_listing_label,
				'is_active'      => true,
				'hide_from_plan' => (bool) get_post_meta( $plan->ID, 'hide_listings', true ),
				'limit'          => $unlimited_regular_listings ? -1 : $regular_listing_count,
			);

			$featured_listing_count = (int) get_post_meta( $plan->ID, 'num_featured', true );
			$unlimited_featured_listings = (bool) get_post_meta( $plan->ID, 'num_featured_unl', true );

			if ( $unlimited_featured_listings ) {
				$featured_listing_label = __( 'Unlimited Featured Listings', 'directorist' );
			} else {
				$featured_listing_label = sprintf( _n( '%s Featured Listing', '%s Featured Listings', $featured_listing_count, 'directorist' ), $featured_listing_count );
			}

			$features[] = array(
				'key'            => 'featured_listings',
				'label'          => $featured_listing_label,
				'is_active'      => true,
				'hide_from_plan' => apply_filters( 'atbdp_plan_featured_compare', (bool) get_post_meta( $plan->ID, 'hide_featured', true ) ),
				'limit'          => $unlimited_featured_listings ? -1 : $featured_listing_count,
			);
		} else {
			$features[] = array(
				'key'            => 'featured_listing',
				'label'          => esc_html__( 'Listing as featured', 'directorist' ),
				'is_active'      => (bool) get_post_meta( $plan->ID, 'is_featured_listing', true ),
				'hide_from_plan' => apply_filters( 'atbdp_plan_featured_compare', (bool) get_post_meta( $plan->ID, 'hide_listing_featured', true ) ), //$this->get_plan_type( $plan ) !== 'pay_per_listing'
			);
		}

		$features[] = array(
			'key'            => 'contact_listing_owner',
			'label'          => esc_html__( 'Contact Owner', 'directorist' ),
			'is_active'      => (bool) get_post_meta( $plan->ID, 'cf_owner', true ),
			'hide_from_plan' => apply_filters( 'atbdp_plan_contact_owner_compare', (bool) get_post_meta( $plan->ID, 'hide_Cowner', true ) ),
		);

		$features[] = array(
			'key'            => 'reviews_allowed',
			'label'          => esc_html__( 'Allow Customer Review', 'directorist' ),
			'is_active'      => (bool) get_post_meta( $plan->ID, 'fm_cs_review', true ),
			'hide_from_plan' => apply_filters( 'atbdp_plan_review_compare', (bool) get_post_meta( $plan->ID, 'hide_review', true ) ),
		);

		$features[] = array(
			'key'            => 'claim_badge_included',
			'label'          => esc_html__( 'Claim Badge Included', 'directorist' ),
			'is_active'      => (bool) get_post_meta( $plan->ID, '_fm_claim', true ),
			'hide_from_plan' => apply_filters( 'atbdp_plan_claim_compare', (bool) get_post_meta( $plan->ID, '_hide_claim', true ) ),
		);

		$features[] = array(
			'key'            => 'booking_included',
			'label'          => esc_html__( 'Booking Included', 'directorist' ),
			'is_active'      => (bool) get_post_meta( $plan->ID, '_fm_booking', true ),
			'hide_from_plan' => apply_filters( 'atbdp_plan_booking_compare', (bool) get_post_meta( $plan->ID, '_hide_booking', true ) ),
		);

		$features[] = array(
			'key'            => 'live_chat_included',
			'label'          => esc_html__( 'Live Chat Included', 'directorist' ),
			'is_active'      => (bool) get_post_meta( $plan->ID, '_fm_live_chat', true ),
			'hide_from_plan' => apply_filters( 'atbdp_plan_live_chat_compare', (bool) get_post_meta( $plan->ID, '_hide_live_chat', true ) ),
		);

		$features[] = array(
			'key'            => 'mark_as_sold_included',
			'label'          => esc_html__( 'Mark as Sold Included', 'directorist' ),
			'is_active'      => (bool) get_post_meta( $plan->ID, '_fm_mark_as_sold', true ),
			'hide_from_plan' => apply_filters( 'atbdp_plan_mark_as_sold_compare', (bool) get_post_meta( $plan->ID, '_hide_mark_as_sold', true ) ),
		);

		$features[] = array(
			'key'            => 'categories_included',
			'label'          => esc_html__( 'All Categories', 'directorist' ),
			'is_active'      => (bool) get_post_meta( $plan->ID, 'exclude_cat', true ),
			'hide_from_plan' => (bool) get_post_meta( $plan->ID, 'hide_categories', true ),
		);

		return $features;
	}

	protected function get_fields_data( $plan ) {
		$form_fields  = directorist_get_listing_form_fields_data( $this->get_directory_id( $plan ) );
		$translations = array(
			'location'     => _n_noop( '%s (maximum %d item)', '%s (maximum %d items)', 'directorist' ),
			'category'     => _n_noop( '%s (maximum %d item)', '%s (maximum %d items)', 'directorist' ),
			'tag'          => _n_noop( '%s (maximum %d item)', '%s (maximum %d items)', 'directorist' ),
			'number'       => _n_noop( '%s (maximum %d)', '%s (maximum %d)', 'directorist' ),
			'textarea'     => _n_noop( '%s (maximum %d character)', '%s (maximum %d characters)', 'directorist' ),
			'description'  => _n_noop( '%s (maximum %d character)', '%s (maximum %d characters)', 'directorist' ),
			'excerpt'      => _n_noop( '%s (maximum %d character)', '%s (maximum %d characters)', 'directorist' ),
			'image_upload' => _n_noop( '%s (maximum %d item)', '%s (maximum %d items)', 'directorist' ),
		);
		$fields     = array_keys( $translations );
		$field_data = array();

		foreach ( $form_fields as $form_field ) {
			$field_key = $form_field['field_key'];

			if ( 'tax_input[at_biz_dir-location][]' === $field_key ) {
				$field_key = 'location';
			}

			if ( 'admin_category_select[]' === $field_key ) {
				$field_key = 'category';
			}

			if ( 'tax_input[at_biz_dir-tags][]' === $field_key ) {
				$field_key = 'tag';
			}

			if ( empty( $field_key ) ) {
				continue;
			}

			$data = array(
				'key'            => $field_key,
				'label'          => $form_field['label'],
				'is_preset'      => ( $form_field['widget_group'] === 'preset' ),
				'is_active'      => (bool) get_post_meta( $plan->ID, '_' . $field_key, true ),
				'hide_from_plan' => (bool) get_post_meta( $plan->ID, '_hide_' . $field_key, true ),
			);

			if ( $data['is_active'] && isset( $form_field['widget_name'] ) && in_array( $form_field['widget_name'], $fields, true ) ) {
				if ( (bool) get_post_meta( $plan->ID, '_unlimited_'. $field_key, true ) ) {
					$data['label'] = sprintf( __( '%s (unlimited)', 'directorist' ), $data['label'] );
					$data['limit'] = -1;
				} elseif ( ( $count = (int) get_post_meta( $plan->ID, '_max_'. $field_key, true ) ) ) {
					$data['label'] = sprintf(
						translate_nooped_plural( $translations[ $form_field['widget_name'] ], $count, 'directorist' ),
						$data['label'],
						$count,
					);
					$data['limit'] = $count;
				}
			}

			$field_data[] = $data;
		}

		return $field_data;
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
				'date_created'          => array(
					'description' => __( "The date the plan was created, in the site's timezone.", 'directorist' ),
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
				'description'           => array(
					'description' => __( 'Plan description.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'hide_description_from_plan'           => array(
					'description' => __( 'Hide description from plan.', 'directorist' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'edit' ),
				),
				'directory' => array(
					'description' => __( 'Directory id.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'status'     => array(
					'description' => __( 'Plan status.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'is_recommended'     => array(
					'description' => __( 'Plan recommendation status.', 'directorist' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'edit' ),
				),
				'is_hidden'     => array(
					'description' => __( 'Plan hidden during plan selection.', 'directorist' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'edit' ),
				),
				'type'     => array(
					'description' => __( 'Plan type.', 'directorist' ),
					'type'        => 'string',
					'enum'        => array( 'package', 'pay_per_listing' ),
					'context'     => array( 'view', 'edit' ),
				),
				'type_label'     => array(
					'description' => __( 'Plan type label.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'currency'     => array(
					'description' => __( 'Plan currency.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'currency_symbol'     => array(
					'description' => __( 'Plan currency symbol.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'is_free'     => array(
					'description' => __( 'Is plan free?.', 'directorist' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'edit' ),
				),
				'price'     => array(
					'description' => __( 'Plan price.', 'directorist' ),
					'type'        => 'float',
					'context'     => array( 'view', 'edit' ),
				),
				'is_taxable'     => array(
					'description' => __( 'Is plan taxable?', 'directorist' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'edit' ),
				),
				'tax_type'     => array(
					'description' => __( 'Plan tax type', 'directorist' ),
					'type'        => 'string',
					'enum'        => array( 'fixed', 'percentage' ),
					'context'     => array( 'view', 'edit' ),
				),
				'tax'     => array(
					'description' => __( 'Plan tax amount.', 'directorist' ),
					'type'        => 'float',
					'context'     => array( 'view', 'edit' ),
				),
				'validity_period'     => array(
					'description' => __( 'Plan validity period.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'validity_period_unit'     => array(
					'description' => __( 'Plan validity period unit.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'validity_period_label'     => array(
					'description' => __( 'Plan validity period label.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'is_non_expiring'    => array(
					'description' => __( 'Is plan non expiring?', 'directorist' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'edit' ),
				),
				'features'             => array(
					'description' => __( 'Features data.', 'directorist' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'key'    => array(
								'description' => __( 'Feature key.', 'directorist' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'label' => array(
								'description' => __( 'Feature label.', 'directorist' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'is_active' => array(
								'description' => __( 'Feature active status.', 'directorist' ),
								'type'        => 'bool',
								'context'     => array( 'view', 'edit' ),
							),
							'hide_from_plan' => array(
								'description' => __( 'Feature visibility status from plan package.', 'directorist' ),
								'type'        => 'bool',
								'context'     => array( 'view', 'edit' ),
							),
							'limit' => array(
								'description' => __( 'Feature limited to number of times (-1 indicates unlimited).', 'directorist' ),
								'type'        => 'number',
								'context'     => array( 'view', 'edit' ),
							),
						),
					),
				),
				'fields'             => array(
					'description' => __( 'Fields data.', 'directorist' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'key'    => array(
								'description' => __( 'Field key.', 'directorist' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'label' => array(
								'description' => __( 'Field label.', 'directorist' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'is_preset' => array(
								'description' => __( 'Preset or custom field status.', 'directorist' ),
								'type'        => 'bool',
								'context'     => array( 'view', 'edit' ),
							),
							'is_active' => array(
								'description' => __( 'Field active status.', 'directorist' ),
								'type'        => 'bool',
								'context'     => array( 'view', 'edit' ),
							),
							'hide_from_plan' => array(
								'description' => __( 'Field visibility status from plan package.', 'directorist' ),
								'type'        => 'bool',
								'context'     => array( 'view', 'edit' ),
							),
							'limit' => array(
								'description' => __( 'Feature limited to number of times (-1 indicates unlimited).', 'directorist' ),
								'type'        => 'number',
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
			'default'            => 'title',
			'type'               => 'string',
			'sanitize_callback'  => 'sanitize_key',
		);

		if ( directorist_is_multi_directory_enabled() ) {
			$params['directory'] = array(
				'description'        => __( 'Query plans by directory id.', 'directorist' ),
				'type'               => 'integer',
				'sanitize_callback'  => 'absint',
			);
		}

		return $params;
	}

	protected function get_orderby_possibles() {
		return array(
			'title'   => 'title',
			'date'    => 'date',
		);
	}
}
