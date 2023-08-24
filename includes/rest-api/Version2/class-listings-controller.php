<?php
/**
 * Rest Listings Controller
 *
 * @package Directorist\Rest_Api
 * @version  1.0.0
 */

namespace Directorist\Rest_Api\Controllers\Version2;

defined( 'ABSPATH' ) || exit;

use WP_Error;
use WP_Query;
use WP_REST_Server;
use Directorist\Helper;
use Directorist\Rest_Api\Controllers\Version1\Listings_Controller as Legacy_Listings_Controller;

/**
 * Listings controller class.
 */
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
	 * Get the Listings's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		file_put_contents( __DIR__ . '/data.txt', print_r( get_class_methods( $this ), 1 ) );

		if ( $this->schema ) {
            return $this->add_additional_fields_schema( $this->schema );
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
				'directory' => array(
					'description' => __( 'Directory id when multi directory enabled.', 'directorist' ),
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
					'type'        => 'boolean',
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
				'related_ids'           => array(
					'description' => __( 'List of related listings IDs.', 'directorist' ),
					'type'        => 'array',
					'items'       => array(
						'type' => 'integer',
					),
					'context'  => array( 'view', 'edit' ),
					'readonly' => true,
				),
				// 'categories'            => array(
				// 	'description' => __( 'List of categories.', 'directorist' ),
				// 	'type'        => 'array',
				// 	'context'     => array( 'view', 'edit' ),
				// 	'items'       => array(
				// 		'type'       => 'object',
				// 		'properties' => array(
				// 			'id'   => array(
				// 				'description' => __( 'Category ID.', 'directorist' ),
				// 				'type'        => 'integer',
				// 				'context'     => array( 'view', 'edit' ),
				// 			),
				// 			'name' => array(
				// 				'description' => __( 'Category name.', 'directorist' ),
				// 				'type'        => 'string',
				// 				'context'     => array( 'view', 'edit' ),
				// 				'readonly'    => true,
				// 			),
				// 			'slug' => array(
				// 				'description' => __( 'Category slug.', 'directorist' ),
				// 				'type'        => 'string',
				// 				'context'     => array( 'view', 'edit' ),
				// 				'readonly'    => true,
				// 			),
				// 			'icon' => array(
				// 				'description' => __( 'Category icon.', 'directorist' ),
				// 				'type'        => 'string',
				// 				'context'     => array( 'view', 'edit' ),
				// 				'readonly'    => true,
				// 			),
				// 		),
				// 	),
				// ),
				// 'tags'                  => array(
				// 	'description' => __( 'List of tags.', 'directorist' ),
				// 	'type'        => 'array',
				// 	'context'     => array( 'view', 'edit' ),
				// 	'items'       => array(
				// 		'type'       => 'object',
				// 		'properties' => array(
				// 			'id'   => array(
				// 				'description' => __( 'Tag ID.', 'directorist' ),
				// 				'type'        => 'integer',
				// 				'context'     => array( 'view', 'edit' ),
				// 			),
				// 			'name' => array(
				// 				'description' => __( 'Tag name.', 'directorist' ),
				// 				'type'        => 'string',
				// 				'context'     => array( 'view', 'edit' ),
				// 				'readonly'    => true,
				// 			),
				// 			'slug' => array(
				// 				'description' => __( 'Tag slug.', 'directorist' ),
				// 				'type'        => 'string',
				// 				'context'     => array( 'view', 'edit' ),
				// 				'readonly'    => true,
				// 			),
				// 		),
				// 	),
				// ),
				// 'locations'                  => array(
				// 	'description' => __( 'List of locations.', 'directorist' ),
				// 	'type'        => 'array',
				// 	'context'     => array( 'view', 'edit' ),
				// 	'items'       => array(
				// 		'type'       => 'object',
				// 		'properties' => array(
				// 			'id'   => array(
				// 				'description' => __( 'Location ID.', 'directorist' ),
				// 				'type'        => 'integer',
				// 				'context'     => array( 'view', 'edit' ),
				// 			),
				// 			'name' => array(
				// 				'description' => __( 'Location name.', 'directorist' ),
				// 				'type'        => 'string',
				// 				'context'     => array( 'view', 'edit' ),
				// 				'readonly'    => true,
				// 			),
				// 			'slug' => array(
				// 				'description' => __( 'Location slug.', 'directorist' ),
				// 				'type'        => 'string',
				// 				'context'     => array( 'view', 'edit' ),
				// 				'readonly'    => true,
				// 			),
				// 		),
				// 	),
				// ),
				// 'images'                => array(
				// 	'description' => __( 'List of images.', 'directorist' ),
				// 	'type'        => 'array',
				// 	'context'     => array( 'view', 'edit' ),
				// 	'items'       => array(
				// 		'type'       => 'object',
				// 		'properties' => array(
				// 			'id'                => array(
				// 				'description' => __( 'Image ID.', 'directorist' ),
				// 				'type'        => 'integer',
				// 				'context'     => array( 'view', 'edit' ),
				// 			),
				// 			'date_created'      => array(
				// 				'description' => __( "The date the image was created, in the site's timezone.", 'directorist' ),
				// 				'type'        => 'date-time',
				// 				'context'     => array( 'view', 'edit' ),
				// 				'readonly'    => true,
				// 			),
				// 			'date_created_gmt'  => array(
				// 				'description' => __( 'The date the image was created, as GMT.', 'directorist' ),
				// 				'type'        => 'date-time',
				// 				'context'     => array( 'view', 'edit' ),
				// 				'readonly'    => true,
				// 			),
				// 			'date_modified'     => array(
				// 				'description' => __( "The date the image was last modified, in the site's timezone.", 'directorist' ),
				// 				'type'        => 'date-time',
				// 				'context'     => array( 'view', 'edit' ),
				// 				'readonly'    => true,
				// 			),
				// 			'date_modified_gmt' => array(
				// 				'description' => __( 'The date the image was last modified, as GMT.', 'directorist' ),
				// 				'type'        => 'date-time',
				// 				'context'     => array( 'view', 'edit' ),
				// 				'readonly'    => true,
				// 			),
				// 			'src'               => array(
				// 				'description' => __( 'Image URL.', 'directorist' ),
				// 				'type'        => 'string',
				// 				'format'      => 'uri',
				// 				'context'     => array( 'view', 'edit' ),
				// 			),
				// 			'name'              => array(
				// 				'description' => __( 'Image name.', 'directorist' ),
				// 				'type'        => 'string',
				// 				'context'     => array( 'view', 'edit' ),
				// 			),
				// 			'alt'               => array(
				// 				'description' => __( 'Image alternative text.', 'directorist' ),
				// 				'type'        => 'string',
				// 				'context'     => array( 'view', 'edit' ),
				// 			),
				// 			'position'          => array(
				// 				'description' => __( 'Image position. 0 means that the image is featured.', 'directorist' ),
				// 				'type'        => 'integer',
				// 				'context'     => array( 'view', 'edit' ),
				// 			),
				// 		),
				// 	),
				// ),


						  // 'description'           => array(
						  // 	'description' => __( 'Listing description.', 'directorist' ),
						  // 	'type'        => 'string',
						  // 	'context'     => array( 'view', 'edit' ),
						  // ),
						  // 'short_description'     => array(
						  // 	'description' => __( 'Listing short description.', 'directorist' ),
						  // 	'type'        => 'string',
						  // 	'context'     => array( 'view', 'edit' ),
						  // ),
						  // 'zip'                  => array(
						  // 	'description' => __( 'Zip code.', 'directorist' ),
						  // 	'type'        => 'string',
						  // 	'context'     => array( 'view', 'edit' ),
						  // ),
						  // 'phone'                  => array(
						  // 	'description' => __( 'Phone number 1.', 'directorist' ),
						  // 	'type'        => 'string',
						  // 	'context'     => array( 'view', 'edit' ),
						  // ),
						  // 'phone_2'                  => array(
						  // 	'description' => __( 'Phone number 2.', 'directorist' ),
						  // 	'type'        => 'string',
						  // 	'context'     => array( 'view', 'edit' ),
						  // ),
						  // 'fax'                  => array(
						  // 	'description' => __( 'Fax number.', 'directorist' ),
						  // 	'type'        => 'string',
						  // 	'context'     => array( 'view', 'edit' ),
						  // ),
						  // 'email'                  => array(
						  // 	'description' => __( 'Email address.', 'directorist' ),
						  // 	'type'        => 'string',
						  // 	'format'      => 'email',
						  // 	'context'     => array( 'view', 'edit' ),
						  // ),
						  // 'website'                => array(
						  // 	'description' => __( 'Website url.', 'directorist' ),
						  // 	'type'        => 'string',
						  // 	'context'     => array( 'view', 'edit' ),
						  // ),
						  // 'social_links'             => array(
						  // 	'description' => __( 'List of social media links.', 'directorist' ),
						  // 	'type'        => 'array',
						  // 	'context'     => array( 'view', 'edit' ),
						  // 	'items'       => array(
						  // 		'type'       => 'object',
						  // 		'properties' => array(
						  // 			'id'   => array(
						  // 				'description' => __( 'Social media name', 'directorist' ),
						  // 				'type'        => 'string',
						  // 				'context'     => array( 'view', 'edit' ),
						  // 			),
						  // 			'url' => array(
						  // 				'description' => __( 'Social media url.', 'directorist' ),
						  // 				'type'        => 'string',
						  // 				'context'     => array( 'view', 'edit' ),
						  // 			),
						  // 		),
						  // 	),
						  // ),
						  // 'views_count'              => array(
						  // 	'description' => __( 'Visitors view count.', 'directorist' ),
						  // 	'type'        => 'integer',
						  // 	'default'     => 0,
						  // 	'context'     => array( 'view', 'edit' ),
						  // ),
						  // 'map_hidden'              => array(
						  // 	'description' => __( 'Map visibility status status.', 'directorist' ),
						  // 	'type'        => 'boolean',
						  // 	'default'     => false,
						  // 	'context'     => array( 'view', 'edit' ),
						  // ),
						  // 'address'              => array(
						  // 	'description' => __( 'Listing address.', 'directorist' ),
						  // 	'type'        => 'string',
						  // 	'context'     => array( 'view', 'edit' ),
						  // ),
						  // 'latitude'              => array(
						  // 	'description' => __( 'Address location latitude.', 'directorist' ),
						  // 	'type'        => 'number',
						  // 	'context'     => array( 'view', 'edit' ),
						  // ),
						  // 'longitude'              => array(
						  // 	'description' => __( 'Address location longitude.', 'directorist' ),
						  // 	'type'        => 'number',
						  // 	'context'     => array( 'view', 'edit' ),
						  // ),
						  // 'pricing_type'              => array(
						  // 	'description' => __( 'Pricing type.', 'directorist' ),
						  // 	'type'        => 'string',
						  // 	// 'enum'        => array( 'price', 'range' ),
						  // 	'context'     => array( 'view', 'edit' ),
						  // ),
						  // 'price'              => array(
						  // 	'description' => __( 'Listing price.', 'directorist' ),
						  // 	'type'        => 'number',
						  // 	'context'     => array( 'view', 'edit' ),
						  // ),
						  // 'price_range'              => array(
						  // 	'description' => __( 'Listing price range.', 'directorist' ),
						  // 	'type'        => 'string',
						  // 	'enum'        => array( 'skimming', 'moderate', 'economy', 'bellow_economy' ),
						  // 	'context'     => array( 'view', 'edit' ),
						  // ),
						  // 'owner_contact_hidden'              => array(
						  // 	'description' => __( 'Listing owner contact form visibility status.', 'directorist' ),
						  // 	'type'        => 'boolean',
						  // 	'default'     => false,
						  // 	'context'     => array( 'view', 'edit' ),
						  // ),
						  // 'video_url'              => array(
						  // 	'description' => __( 'Video url.', 'directorist' ),
						  // 	'type'        => 'string',
						  // 	'context'     => array( 'view', 'edit' ),
						  // ),
						  // 'tagline'              => array(
						  // 	'description' => __( 'Tagline.', 'directorist' ),
						  // 	'type'        => 'string',
						  // 	'context'     => array( 'view', 'edit' ),
						  // ),
			),
		);

		$this->schema = $schema;

		return $this->add_additional_fields_schema( $this->schema );
	}
}
