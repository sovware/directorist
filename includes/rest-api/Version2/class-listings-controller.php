<?php
/**
 * Rest Listings Controller
 *
 * @package Directorist\Rest_Api
 * @version  2.0.0
 */

namespace Directorist\Rest_Api\Controllers\Version2;

use WP_Error;
use WP_REST_Server;
use Directorist\Helper;
use Directorist\AddListingForm\SubmissionController;
use Directorist\Rest_Api\Controllers\Version1\Listings_Controller as Legacy_Listings_Controller;

defined( 'ABSPATH' ) || exit;

class Listings_Controller extends Legacy_Listings_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'directorist/v2';

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
					'args'                => array_merge(
						$this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
						array(
							'directory' => array(
								'description' => __( 'Directory id.', 'directorist' ),
								'type'        => 'integer',
								'required'    => directorist_is_multi_directory_enabled(),
								'default'     => directorist_get_default_directory()
							),
							'plan' => array(
								'description' => __( 'Plan id.', 'directorist' ),
								'type'        => 'integer',
								'default'     => 0,
							),
							'order' => array(
								'description' => __( 'Order id.', 'directorist' ),
								'type'        => 'integer',
								'default'     => 0,
							),
						)
					),
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
						'context' => $this->get_context_param( array( 'default' => 'view' ) ),
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	protected function get_schema_to_post_fields_map() {
		return array(
			'title' => array(
				'title' => 'listing_title',
			),
			'description' => array(
				'description' => 'listing_content',
			),
			'location' => array(
				'locations' => ATBDP_LOCATION,
			),
			'category' => array(
				'categories' => ATBDP_CATEGORY,
			),
			'tag' => array(
				'tags' => ATBDP_TAGS,
			),
			'hide_contact_owner' => array(
				'contact_form_hidden' => 'hide_contact_owner',
			),
			'pricing' => array(
				'price_type'  => 'atbd_listing_pricing',
				'price'       => 'price',
				'price_range' => 'price_range',
			),
			'map' => array(
				'map_hidden' => 'hide_map',
				'latitude'   => 'manual_lat',
				'longitude'  => 'manual_lng',
			),
		);
	}

	protected function get_schema_to_post_map() {
		return array(
			'privacy_policy'   => 'privacy_policy',
			'terms_conditions' => 't_c_check',
			'directory'        => 'directory_type',
			'plan'             => 'plan_id',
			'order'            => 'order_id',
			'id'               => 'listing_id',
		);
	}

	protected function hydrate_global_post( $request ) {
		$directory_id = $request['directory'];
		$plan_id      = $request['plan'];

		$form_fields           = directorist_get_listing_form_fields( $directory_id, $plan_id );
		$map                   = $this->get_schema_to_post_fields_map();
		$_POST['directory_id'] = $directory_id;
		$_POST['tax_input']    = array();

		foreach ( $form_fields as $form_field ) {
			if ( empty( $form_field['widget_name'] ) || (bool) directorist_get_var( $form_field['only_for_admin'] ) ) {
				continue;
			}

			$field_key  = directorist_get_var( $form_field['field_key'] );
			$widget_key = directorist_get_var( $form_field['widget_key'] );
			$type       = directorist_get_var( $form_field['type'], '' );

			if ( isset( $request['fields'] ) && isset( $map[ $widget_key ] ) ) {

				foreach ( $map[ $widget_key ] as $schema_key => $post_key ) {
					if ( ! isset( $request['fields'][ $schema_key ] ) ) {
						continue;
					}

					if ( in_array( $schema_key, array( 'categories', 'locations', 'tags' ), true ) ) {
						$_POST['tax_input'] = array_merge(
							$_POST['tax_input'],
							array(
								$post_key => $this->transform_taxonomy_data( $schema_key, $request['fields'][ $schema_key ] )
							)
						);
					} else {
						$_POST[ $post_key ] = $request['fields'][ $schema_key ];
					}
				}

			} elseif ( isset( $request['fields'] ) && isset( $request['fields'][ $field_key ] ) ) {

				$_POST[ $field_key ] = $request['fields'][ $field_key ];

			} elseif ( isset( $request[ $field_key ] ) && isset( $map[ $widget_key ] ) ) {

				foreach ( $map[ $widget_key ] as $schema_key => $post_key ) {
					if ( ! isset( $request[ $schema_key ] ) ) {
						continue;
					}

					if ( in_array( $schema_key, array( 'categories', 'locations', 'tags' ), true ) ) {
						$_POST['tax_input'] = array_merge(
							$_POST['tax_input'],
							array(
								$post_key => $this->transform_taxonomy_data( $schema_key, $request[ $schema_key ] )
							)
						);
					} else {
						$_POST[ $post_key ] = $request[ $schema_key ];
					}
				}

			} elseif ( isset( $request[ $field_key ] ) ) {

				$_POST[ $field_key ] = $request[ $field_key ];

			}
		}

		unset( $schema_key, $post_key );

		foreach ( $this->get_schema_to_post_map() as $schema_key => $post_key ) {
			if ( isset( $request[ $schema_key ] ) ) {

				$_POST[ $post_key ] = $request[ $schema_key ];

			}
		}
	}

	protected function transform_taxonomy_data( $taxonomy, $taxonomy_data ) {
		if ( $taxonomy === 'categories' || $taxonomy === 'locations' ) {
			return array_map( static function( $item ) {
				if ( ! empty( $item['id'] ) ) {
					return $item['id'];
				}

				if ( ! empty( $item['name'] ) ) {
					return $item['name'];
				}

				return '';
			}, $taxonomy_data );
		} else {
			$names = wp_list_pluck( $taxonomy_data, 'name' );
			$ids   = wp_list_pluck( $taxonomy_data, 'id' );
			$tags  = get_terms( array(
				'taxonomy'   => ATBDP_TAGS,
				'include'    => $ids,
				'hide_empty' => false,
				'fields'     => 'names',
			) );

			if ( ! empty( $tags ) ) {
				return array_merge( $names, $tags );
			}

			return $names;
		}
	}

	public function create_item( $request ) {
		$this->hydrate_global_post( $request );

		$controller_response = SubmissionController::submit( wp_unslash( $_POST ), 'api' );

		if ( is_wp_error( $controller_response ) ) {
			return $controller_response;
		}

		$listing = get_post( $controller_response['id'] );

		$request->set_param( 'context', 'edit' );
		$response = $this->prepare_item_for_response( $listing, $request );
		$response = rest_ensure_response( $response );
		$response->set_status( 201 );

		if ( isset( $controller_response['preview_url'] ) ) {
			$response->add_link( 'preview', $controller_response['preview_url'] );
		}

		$base = '/' . $this->namespace . '/' . $this->rest_base;

		$response->header( 'Location', rest_url( $base . '/' . $listing->ID ) );

		$response = apply_filters( 'directorist_rest_response', $response, 'create_listing_item', $request );

		return $response;
	}

	public function update_item( $request ) {
		$id = (int) $request['id'];

		do_action( 'directorist_rest_before_query', 'update_listing_item', $request, $id );

		$this->hydrate_global_post( $request );

		$controller_response = SubmissionController::submit( wp_unslash( $_POST ), 'api' );

		if ( is_wp_error( $controller_response ) ) {
			return $controller_response;
		}

		$listing = get_post( $id );

		$request->set_param( 'context', 'edit' );
		$response = $this->prepare_item_for_response( $listing, $request );
		$response = rest_ensure_response( $response );
		$response->set_status( 201 );

		do_action( 'directorist_rest_after_query', 'update_listing_item', $request, $id );

		$response = apply_filters( 'directorist_rest_response', $response, 'update_listing_item', $request, $id );

		return rest_ensure_response( $response );
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
				case 'title':
					$base_data['title'] = get_the_title( $listing );
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
				case 'fields':
					$base_data['fields'] = $this->get_fields_data( $listing, $context );
					break;
			}
		}

		return $base_data;
	}

	protected function get_fields_data( $listing, $context ) {
		$directory_id = $this->get_directory_id( $listing );

		if ( ! $directory_id ) {
			return apply_filters( 'directorist_rest_listing_fields_data', array(), $listing, $context );
		}

		$form_fields      = directorist_get_listing_form_fields( $directory_id, $this->get_plan_id( $listing ) );
		$data             = array();
		$ignorable_fields = array(
			'view_count',
			'atbdp_post_views_count',
		);

		foreach ( $form_fields as $form_field ) {
			if ( empty( $form_field['widget_name'] ) ) {
				continue;
			}

			$internal_key = $form_field['widget_name'];
			$field_key    = $form_field['field_key'];

			if ( in_array( $internal_key, $ignorable_fields, true ) ) {
				continue;
			}

			switch ( $internal_key ) {
				case 'title':
					$data['title'] = ( 'view' === $context ? get_the_title( $listing ) : $listing->post_title );
					break;

				case 'description':
					$data['description'] = ( 'view' === $context ? wpautop( do_shortcode( $listing->post_content ) ) : $listing->post_content );
					break;

				case 'excerpt':
					$data['excerpt'] = empty( $listing->post_excerpt ) ? get_post_meta( $listing->ID, '_excerpt', true ) : $listing->post_excerpt;
					break;

				case 'location':
					$data['locations'] = $this->get_taxonomy_terms( $listing->ID, ATBDP_LOCATION );
					break;

				case 'category':
					$data['categories'] = $this->get_taxonomy_terms( $listing->ID, ATBDP_CATEGORY );
					break;

				case 'tag':
					$data['tags'] = $this->get_taxonomy_terms( $listing->ID, ATBDP_TAGS );
					break;

				case 'social_info':
					$data['social'] = $this->get_listing_social_links( $listing->ID );
					break;

				case 'hide_contact_owner':
					$data['contact_form_hidden'] = (bool) get_post_meta( $listing->ID, '_'. $field_key, true );
					break;

				case 'image_upload':
					$data[ $field_key ] = $this->get_images( $listing, $context );
					break;

				case 'url':
					$field_value       = get_post_meta( $listing->ID, '_'. $field_key, true );
					$data[ $field_key ] = ( 'view' === $context ? esc_url( $field_value ) : esc_url_raw( $field_value ) );
					break;

				case 'textarea':
					$field_value       = get_post_meta( $listing->ID, '_'. $field_key, true );
					$data[ $field_key ] = esc_textarea( $field_value );
					break;

				case 'pricing':
					$data['price']       = directorist_clean( get_post_meta( $listing->ID, '_price', true ) );
					$data['price_type']  = directorist_clean( get_post_meta( $listing->ID, '_atbd_listing_pricing', true ) );
					$data['price_range'] = directorist_clean( get_post_meta( $listing->ID, '_price_range', true ) );
					break;

				case 'map':
					$data['map_hidden']  = (bool) get_post_meta( $listing->ID, '_hide_map', true );
					$data['latitude']  = directorist_clean( get_post_meta( $listing->ID, '_manual_lat', true ) );
					$data['longitude'] = directorist_clean( get_post_meta( $listing->ID, '_manual_lng', true ) );
					break;

				default:
					$value              = get_post_meta( $listing->ID, '_'. $field_key, true );
					$value              = directorist_clean( $value );
					$data[ $field_key ] = apply_filters( 'directorist_rest_listing_field_data', $value, $form_field, $listing, $context );
					break;
			}
		}

		return apply_filters( 'directorist_rest_listing_fields_data', $data, $listing, $context );
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
				'src'               => $image_url,
				'name'              => get_the_title( $attachment_id ),
				'alt'               => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
				'position'          => (int) $position,
			);
		}

		return $images;
	}

	/**
	 * Get the Listings's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
            return $this->schema;
        }

		$fields = array(
			'title'                => array(
				'description' => __( 'Listing title.', 'directorist' ),
				'type'        => 'string',
				'context'     => array( 'view', 'edit' ),
			),
			'tagline'              => array(
				'description' => __( 'Tagline.', 'directorist' ),
				'type'        => 'string',
				'context'     => array( 'view', 'edit' ),
			),
			'description'           => array(
				'description' => __( 'Listing description.', 'directorist' ),
				'type'        => 'string',
				'context'     => array( 'view', 'edit' ),
			),
			'excerpt'     => array(
				'description' => __( 'Listing short description.', 'directorist' ),
				'type'        => 'string',
				'context'     => array( 'view', 'edit' ),
			),
			'categories' => array(
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
			'tags' => array(
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
			'locations' => array(
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
			'social'             => array(
				'description' => __( 'List of social links.', 'directorist' ),
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
			'price_type'              => array(
				'description' => __( 'Price type.', 'directorist' ),
				'type'        => 'string',
				'context'     => array( 'view', 'edit' ),
				'enum' 		  => array( 'price', 'range' ),
			),
			'price'              => array(
				'description' => __( 'Price amount.', 'directorist' ),
				'type'        => 'number',
				'context'     => array( 'view', 'edit' ),
			),
			'price_range'              => array(
				'description' => __( 'Price range.', 'directorist' ),
				'type'        => 'string',
				'enum'        => array( 'skimming', 'moderate', 'economy', 'bellow_economy' ),
				'context'     => array( 'view', 'edit' ),
			),
			'contact_form_hidden' => array(
				'description' => __( 'Listing owner contact form visibility status.', 'directorist' ),
				'type'        => 'boolean',
				'default'     => false,
				'context'     => array( 'view', 'edit' ),
			),
			'zip'                  => array(
				'description' => __( 'Zip code.', 'directorist' ),
				'type'        => 'string',
				'context'     => array( 'view', 'edit' ),
			),
			'address'              => array(
				'description' => __( 'Listing address.', 'directorist' ),
				'type'        => 'string',
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
			'phone'                  => array(
				'description' => __( 'Phone number 1.', 'directorist' ),
				'type'        => 'string',
				'context'     => array( 'view', 'edit' ),
			),
			'phone2'                  => array(
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
			'videourl'              => array(
				'description' => __( 'Video url.', 'directorist' ),
				'type'        => 'string',
				'context'     => array( 'view', 'edit' ),
			),
			'listing_img'            => array(
				'description' => __( 'Listing images.', 'directorist' ),
				'type'        => 'array',
				'context'     => array( 'view', 'edit' ),
				'items'       => array(
					'type'       => array( 'object', 'string', 'integer' ),
					'properties' => array(
						'id'                => array(
							'description' => __( 'Image ID.', 'directorist' ),
							'type'        => 'integer',
							'context'     => array( 'view', 'edit' ),
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
			'[field_key]'   => array(
				'description' => __( 'Field key: value.', 'directorist' ),
				'type'        => array( 'string', 'array', 'integer', 'boolean' ),
				'context'     => array( 'view', 'edit' ),
			),
		);

		if ( ! is_fee_manager_active() && directorist_is_featured_listing_enabled() ) {
			$fields['listing_type'] = array(
				'description' => __( 'Listing type.', 'directorist' ),
				'type'        => 'string',
				'context'     => array( 'edit' ),
				'enum'        => array( 'general', 'featured' )
			);
		}

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
				'views_count'              => array(
					'description' => __( 'Visitors view count.', 'directorist' ),
					'type'        => 'integer',
					'default'     => 0,
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'directory' => array(
					'description' => __( 'Directory id.', 'directorist' ),
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
					'readonly'    => true,
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
					'readonly'    => true,
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
				'menu_order'            => array(
					'description' => __( 'Menu order, used to custom sort listings.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'author'            => array(
					'description' => __( 'Listing author id.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'plan' => array(
					'description' => __( 'Listing plan id.', 'directorist' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'privacy_policy' => array(
					'description' => __( 'Agree to listing privacy policy.', 'directorist' ),
					'type'        => 'boolean',
					'context'     => array( 'edit' ),
				),
				'terms_conditions' => array(
					'description' => __( 'Agree to terms and conditions.', 'directorist' ),
					'type'        => 'boolean',
					'context'     => array( 'edit' ),
				),
				'fields'             => array(
					'description' => __( 'Fields data.', 'directorist' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'properties'  => $fields,
				),
			),
		);

		$this->schema = $this->add_additional_fields_schema( $schema );

		return $this->schema;
	}
}
