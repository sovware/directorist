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
					'labels' => [
						'fields' => array_keys( self::get_fields() ),
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
				'label'       => __( 'Enable Review', 'directorist' ),
				'description' => __( 'Allow your customers, users, or listing owners to share their review or comment on listings.', 'directorist' ),
				'value'       => true,
			],
			'enable_owner_review' => [
				'type'        => 'toggle',
				'label'       => __( 'Allow Owner Review', 'directorist'),
				'description' => __( 'You are allowing listing owners to post review on his/her own listings.', 'directorist' ),
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
				'label'       => __( 'Allow Guest Review', 'directorist' ),
				'description' => __( 'Guest reviews are not published immediately even when the setting is enabled.', 'directorist' ),
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
				'label'   => __( 'Approve Immediately?', 'directorist' ),
				'description' => __( 'Are you sure you do not need any review or comment moderation?', 'directorist' ),
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
				'label'       => __( 'Enable Reply', 'directorist' ),
				'description' => __( 'Allow users to reply to review or reply to another reply.', 'directorist' ),
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
			'review_enable_multiple' => [
				'type'        => 'toggle',
				'label'       => __( 'Enable Multiple Review', 'directorist' ),
				'description' => __( 'Allow users to submit multiple reviews.', 'directorist' ),
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
				'label'       => __( 'Number of Reviews', 'directorist' ),
				'description' => __( 'Number of reviews to show per page. More than 10 is not recommended due to impact on loading speed.', 'directorist' ),
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
			'enable_gdpr_consent' => [
				'label'       => __( 'Enable GDPR Consent', 'directorist' ),
				'description' => __( 'Make your site GDPR compliant by enabling this consent checkbox which will enforce users to accept the privacy policy before submitting any review or comment.', 'directorist' ),
				'type'        => 'toggle',
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
			'gdpr_consent_label' => [
				'label'       => __( 'GDPR Consent Label', 'directorist' ),
				'description' => __( 'Use [privacy_policy]privacy policy[/privacy_policy] for privacy link and [terms_conditions]terms and conditions[/terms_conditions] for terms and conditions link.', 'directorist' ),
				'type'        => 'textarea',
				'rows'        => 3,
				'value'       => 'I have read and agree to the [privacy_policy]privacy policy[/privacy_policy] & [terms_conditions]terms and conditions[/terms_conditions].',
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
