<?php
/**
 * Builder screen class.
 *
 * @package Directorist\Review
 * @since 7.0.6
 */
namespace Directorist\Review;

defined( 'ABSPATH' ) || die();

class Builder_Screen {

	public static function init() {
		add_filter( 'directorist/builder/config', [ __CLASS__, 'register_config' ] );
		add_filter( 'directorist/builder/fields', [ __CLASS__, 'register_fields' ] );
		add_filter( 'directorist/builder/layouts', [ __CLASS__, 'register_layout' ] );
	}

	public static function register_config( $config ) {
		$config['fields_group']['review_config'] = array_keys( self::get_fields() );
		return $config;
	}

	public static function register_layout( $layouts ) {
		$layouts['review_form'] = [
			'label'     => __( 'Review Form', 'directorist' ),
			'icon'      => '<span class="uil uil-star"></span>',
			'container' => 'wide',
			'sections'  => apply_filters( 'directorist/builder/sections/review_form', self::get_sections() ),
		];

		return $layouts;
	}

	protected static function get_sections() {
		return [
			// 'rating_fields' => [
			// 	// 'title'     => __( 'Rating', 'directorist' ),
			// 	// 'container' => 'short-width',
			// 	'fields'    => [
			// 		'review_rating_type',
			// 		// 'review_rating_criteria',
			// 	],
			// ],
			// 'attachment_fields' => [
			// 	'title'     => __( 'Images', 'directorist' ),
			// 	'container' => 'short-width',
			// 	'fields'    => [
			// 		'review_enable_attachments',
			// 		'review_max_attachments',
			// 		'review_attachments_size',
			// 		'review_attachments_required',
			// 	],
			// ],
			'regular_fields' => [
				'title'     => __( 'Regular Fields', 'directorist' ),
				'container' => 'short-width',
				'fields'    => [
					'review_comment_label',
					'review_comment_placeholder',
					'review_email_label',
					'review_email_placeholder',
					'review_name_label',
					'review_name_placeholder',
					'review_website_label',
					'review_website_placeholder',
				],
			],
		];
	}

	public static function get_fields() {
		return [
			// Rating fields
			// 'review_rating_type' => [
			// 	'type' => 'hidden',
			// 	// 'label' => __( 'Rating Type', 'directorist' ),
			// 	// 'options' => [
			// 	// 	[ 'value' => 'single' , 'label' => __( 'Single', 'directorist' ) ],
			// 	// 	[ 'value' => 'multiple' , 'label' => __( 'Multiple', 'directorist' ) ],
			// 	// ],
			// 	'value' => 'single'
			// ],
			// 'review_rating_criteria' => [
			// 	'type' => 'textarea',
			// 	'label' => __( 'Rating Criteria', 'directorist' ),
			// 	'description' => __( 'Make sure to write criteria in <code>key | Label</code> pairs with valid keys (<code>a-z,0-9,_,-</code>) and separate each of them with a new line. Removing or changing <code>key</code> may cause data loss.', 'directorist' ),
			// 	'show_if' => [
			// 		'where' => 'review_rating_type',
			// 		'conditions' => [
			// 			['key' => 'value', 'compare' => '=', 'value' => 'multiple'],
			// 		],
			// 	],
			// 	'value' => "service | Service\nlocation | Location\nprice | Price\n",
			// ],

			// Attachment fields
			// 'review_enable_attachments' => [
			// 	'label' => __( 'Enable', 'directorist' ),
			// 	'type'  => 'toggle',
			// 	'value' => true,
			// ],
			// 'review_max_attachments' => [
			// 	'label' => __( 'Number of Images', 'directorist' ),
			// 	'type'  => 'number',
			// 	'value' => 3,
			// 	'show_if' => [
			// 		'where' => 'review_enable_attachments',
			// 		'conditions' => [
			// 			['key' => 'value', 'compare' => '=', 'value' => true],
			// 		],
			// 	],
			// ],
			// 'review_attachments_size' => [
			// 	'label' => __( 'Total Size (MB)', 'directorist' ),
			// 	'type'  => 'text',
			// 	'value' => 2,
			// 	'show_if' => [
			// 		'where' => 'review_enable_attachments',
			// 		'conditions' => [
			// 			['key' => 'value', 'compare' => '=', 'value' => true],
			// 		],
			// 	],
			// ],
			// 'review_attachments_required' => [
			// 	'label' => __( 'Required', 'directorist' ),
			// 	'type'  => 'toggle',
			// 	'value' => false,
			// 	'show_if' => [
			// 		'where' => 'review_enable_attachments',
			// 		'conditions' => [
			// 			['key' => 'value', 'compare' => '=', 'value' => true],
			// 		],
			// 	],
			// ],

			// Regular fields
			'review_comment_label' => [
				'label' => __( 'Comment Label', 'directorist' ),
				'type'  => 'text',
				'value' => __( 'Comment', 'directorist' ),
			],
			'review_comment_placeholder' => [
				'label' => __( 'Comment Placeholder', 'directorist' ),
				'type'  => 'text',
				'value' => __( 'Leave a review', 'directorist' ),
			],
			'review_email_label' => [
				'label' => __( 'Email Label', 'directorist' ),
				'type'  => 'text',
				'value' => __( 'Email', 'directorist' ),
			],
			'review_email_placeholder' => [
				'label' => __( 'Email Placeholder', 'directorist' ),
				'type'  => 'text',
				'value' => __( 'Your Email', 'directorist' ),
			],
			'review_name_label' => [
				'label' => __( 'Name Label', 'directorist' ),
				'type'  => 'text',
				'value' => __( 'Name', 'directorist' ),
			],
			'review_name_placeholder' => [
				'label' => __( 'Name Placeholder', 'directorist' ),
				'type'  => 'text',
				'value' => __( 'Your Name', 'directorist' ),
			],
			'review_website_label' => [
				'label' => __( 'Website Label', 'directorist' ),
				'type'  => 'text',
				'value' => __( 'Website', 'directorist' ),
			],
			'review_website_placeholder' => [
				'label' => __( 'Website Placeholder', 'directorist' ),
				'type'  => 'text',
				'value' => __( 'Website url', 'directorist' ),
			],
		];
	}

	public static function register_fields( $fields ) {
		return array_merge( $fields, self::get_fields() );
	}
}

Builder_Screen::init();
