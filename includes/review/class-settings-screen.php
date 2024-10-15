<?php
/**
 * Settings screen class.
 *
 * @package Directorist\Review
 * @since 7.1.0
 */
namespace Directorist\Review;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings_Screen {

	public static function init() {
		add_filter( 'atbdp_listing_type_settings_layout', [ __CLASS__, 'register_layout' ] );
		add_filter( 'atbdp_listing_type_settings_field_list', [ __CLASS__, 'register_fields' ] );
	}

	public static function register_layout( $layout ) {
		if ( ! isset( $layout['listing_settings']['submenu']['review'] ) ) {
			$layout['listing_settings']['submenu']['review'] = [
				'label' => __( 'Review', 'directorist' ),
				'icon' => '<i class="fa fa-star"></i>',
				'sections' => apply_filters( 'atbdp_listing_settings_review_sections', [
					'review_options' => [
						'title' => __( 'Review Options', 'directorist' ),
						'fields' => [
							'enable_review', 'enable_owner_review', 'guest_review', 'review_enable_reply'
						],
					],
					'moderation_options' => [
						'title' => __( 'Moderation Option', 'directorist' ),
						'fields' => [
							'approve_immediately'
						],
					],
					'display_settings' => [
						'title' => __( 'Display Setting', 'directorist' ),
						'fields' => [
							'review_num'
						],
					],
				] ),
			];
		}

		return $layout;
	}

	public static function get_fields() {
		return [
			'enable_review' => [
				'type'        => 'toggle',
				'label'       => __( 'Enable User Reviews', 'directorist' ),
				'description' => __( 'Let customers and listing owners leave reviews on listings.', 'directorist' ),
				'value'       => true,
			],
			'enable_owner_review' => [
				'type'        => 'toggle',
				'label'       => __( 'Enable Owner Reviews', 'directorist'),
				'description' => __( 'Allow listing owners to review their own listings.', 'directorist' ),
				'value'       => true,
				'show-if'     => [
					'where'      => 'enable_review',
					'conditions' => [
						[
							'key'     => 'value',
							'compare' => '=',
							'value'   => true
						],
					],
				],
			],
			'guest_review' => [
				'type'        => 'toggle',
				'label'       => __( 'Enable Guest Reviews', 'directorist' ),
				'description' => __( 'Allow non-logged-in users to leave reviews (subject to moderation).', 'directorist' ),
				'value'       => false,
				'show-if'     => [
					'where'      => 'enable_review',
					'conditions' => [
						[
							'key'     => 'value',
							'compare' => '=',
							'value'   => true
						],
					],
				],
			],
			'approve_immediately' => [
				'type'    => 'toggle',
				'label'   => __( 'Auto-Approve Reviews', 'directorist' ),
				'description' => __( 'Automatically approve all submitted reviews without moderation.', 'directorist' ),
				'value'   => true,
				'show-if' => [
					'where'      => 'enable_review',
					'conditions' => [
						[
							'key'     => 'value',
							'compare' => '=',
							'value'   => true
						],
					],
				],
			],
			'review_enable_reply' => [
				'type'        => 'toggle',
				'label'       => __( 'Allow Review Replies', 'directorist' ),
				'description' => __( 'Let users reply to reviews or comments.', 'directorist' ),
				'value'       => false,
				'show-if'     => [
					'where'      => 'enable_review',
					'conditions' => [
						[
							'key'     => 'value',
							'compare' => '=',
							'value'   => true
						],
					],
				],
			],
			// 'review_approval_text' => [
			// 	'type'        => 'textarea',
			// 	'label'       => __( 'Approval Notification Text', 'directorist'),
			// 	'value'       => __( 'We have received your review. It requires approval.', 'directorist' ),
			// 	'show-if'     => [
			// 		'where'      => 'enable_review',
			// 		'conditions' => [
			// 			[
			// 				'key'     => 'value',
			// 				'compare' => '=',
			// 				'value'   => true
			// 			],
			// 		],
			// 	],
			// ],
			// 'enable_reviewer_img' => [
			// 	'type'    => 'toggle',
			// 	'label'   => __( 'Enable Reviewer Image', 'directorist' ),
			// 	'value'   => true,
			// 	'show-if' => [
			// 		'where'      => 'enable_review',
			// 		'conditions' => [
			// 			[
			// 				'key'     => 'value',
			// 				'compare' => '=',
			// 				'value'   => true
			// 			],
			// 		],
			// 	],
			// ],
			// 'enable_reviewer_content' => [
			// 	'type'        => 'toggle',
			// 	'label'       => __('Enable Reviewer content', 'directorist'),
			// 	'value'       => true,
			// 	'description' => __('Allow to display content of reviewer on single listing page.', 'directorist'),
			// 	'show-if'     => [
			// 		'where'      => 'enable_review',
			// 		'conditions' => [
			// 			[
			// 				'key'     => 'value',
			// 				'compare' => '=',
			// 				'value'   => true
			// 			],
			// 		],
			// 	],
			// ],
			// 'required_reviewer_content' => [
			// 	'type'        => 'toggle',
			// 	'label'       => __('Required Reviewer content', 'directorist'),
			// 	'value'       => true,
			// 	'description' => __('Allow to Require the content of reviewer on single listing page.', 'directorist'),
			// 	'show-if'     => [
			// 		'where'      => 'enable_review',
			// 		'conditions' => [
			// 			[
			// 				'key'     => 'value',
			// 				'compare' => '=',
			// 				'value'   => true
			// 			],
			// 		],
			// 	],
			// ],
			'review_num' => [
				'label'       => __( 'Reviews Per Page', 'directorist' ),
				'description' => __( 'Set how many reviews to display per page (For the best performance, we suggest keeping it under 10)', 'directorist' ),
				'type'        => 'number',
				'value'       => 5,
				'min'         => 1,
				'max'         => 20,
				'step'        => 1,
				'show-if'     => [
					'where'      => 'enable_review',
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
	}

	public static function register_fields( $fields ) {
		// if ( ! isset( $fields['enable_review'] ) ) {
			$fields = array_merge( $fields, self::get_fields() );
		// }

		return $fields;
	}
}

Settings_Screen::init();
