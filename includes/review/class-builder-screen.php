<?php
/**
 * Builder screen class.
 *
 * @package Directorist\Review
 * @since 7.1.0
 */
namespace Directorist\Review;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Builder_Screen {

	public static function init() {
		add_filter( 'directorist/builder/config', [ __CLASS__, 'register_config' ] );
		add_filter( 'directorist/builder/fields', [ __CLASS__, 'register_fields' ] );
		add_filter( 'directorist/builder/layouts', [ __CLASS__, 'register_layout' ] );
	}

	public static function register_config( $config ) {
		$review_config = apply_filters( 'directorist/builder/config/review_form', array_keys( self::get_fields() ) );
		$config['fields_group']['review_config'] = $review_config;

		return $config;
	}

	public static function register_layout( $layouts ) {
		$layout = [
			'label'     => __( 'Review Form', 'directorist' ),
			'icon'      => '<span class="uil uil-star"></span>',
			'container' => 'wide',
			'sections'  => self::get_sections(),
		];

		$layouts['single_page_layout']['submenu']['review_form'] = apply_filters( 'directorist/builder/layouts/review_form', $layout );

		return $layouts;
	}

	protected static function get_sections() {
		$sections = [
			// 'rating_fields' => [
			// 	'title'     => __( 'Rating', 'directorist' ),
			// 	'container' => 'short-width',
			// 	'fields'    => [
			// 		'review_rating_type',
			// 	],
			// ],
			'regular_fields' => [
				'title'     => __( 'Regular Fields', 'directorist' ),
				'container' => 'short-width',
				'fields'    => [
					'review_rating_type',
					'review_cookies_consent',
					'review_comment_label',
					'review_comment_placeholder',
					'review_email_label',
					'review_email_placeholder',
					'review_name_label',
					'review_name_placeholder',
					'review_show_website_field',
					'review_website_label',
					'review_website_placeholder',
				],
			],
		];

		return apply_filters( 'directorist/builder/sections/review_form', $sections );
	}

	public static function get_fields() {
		$fields = [
			// Rating fields
			// 'review_rating_type' => [
			// 	'type' => 'select',
			// 	'label' => __( 'Rating Type', 'directorist' ),
			// 	'options' => [
			// 		[ 'value' => 'single' , 'label' => __( 'Single', 'directorist' ) ],
			// 	],
			// 	'value' => 'single'
			// ],

			'review_rating_type' => [
				'type'  => 'hidden',
				'value' => 'single'
			],

			// Regular fields
			'review_cookies_consent' => [
				'label' => __( 'Show Cookies Consent', 'directorist' ),
				'type'  => 'toggle',
				'value' => false,
			],
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
			'review_show_website_field' => [
				'label' => __( 'Show Website Field?', 'directorist' ),
				'type'  => 'toggle',
				'value' => false,
			],
			'review_website_label' => [
				'label'   => __( 'Website Label', 'directorist' ),
				'type'    => 'text',
				'value'   => __( 'Website', 'directorist' ),
				'show-if' => [
					'where'      => 'review_show_website_field',
					'conditions' => [
						[
							'key'     => 'value',
							'compare' => '=',
							'value'   => true
						],
					],
				],
			],
			'review_website_placeholder' => [
				'label'   => __( 'Website Placeholder', 'directorist' ),
				'type'    => 'text',
				'value'   => __( 'Website url', 'directorist' ),
				'show-if' => [
					'where'      => 'review_show_website_field',
					'conditions' => [
						[
							'key'     => 'value',
							'compare' => '=',
							'value'   => true
						],
					],
				],
			],
		];

		return apply_filters( 'directorist/builder/fields/review_form', $fields );
	}

	public static function register_fields( $fields ) {
		return array_merge( $fields, self::get_fields() );
	}
}

Builder_Screen::init();
