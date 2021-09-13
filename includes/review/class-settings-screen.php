<?php
/**
 * Settings screen class.
 *
 * @package Directorist\Review
 * @since 7.0.6
 */
namespace Directorist\Review;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings_Screen {

	public static function init() {
		// add_filter( 'atbdp_listing_type_settings_layout', [ __CLASS__, 'register_layout' ] );
		// add_filter( 'atbdp_listing_type_settings_field_list', [ __CLASS__, 'register_fields' ] );
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
				'type' => 'toggle',
				'label' => __('Enable Reviews & Rating', 'directorist'),
				'value' => true,
			],
			'enable_owner_review' => [
				'type' => 'toggle',
				'label' => __('Enable Owner Review', 'directorist'),
				'description' => __('Allow a listing owner to post a review on his/her own listing.', 'directorist'),
				'value' => true,
				'show-if' => [
					'where' => "enable_review",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
			],
			'approve_immediately' => [
				'type' => 'toggle',
				'label' => __('Approve Immediately?', 'directorist'),
				'value' => true,
				'show-if' => [
					'where' => "enable_review",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
			],
			'review_approval_text' => [
				'type' => 'textarea',
				'label' => __('Approval Notification Text', 'directorist'),
				'description' => __('You can set the title for featured listing to show on the ORDER PAGE', 'directorist'),
				'value' => __('We have received your review. It requires approval.', 'directorist'),
				'show-if' => [
					'where' => "enable_review",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
			],
			'enable_reviewer_img' => [
				'type' => 'toggle',
				'label' => __('Enable Reviewer Image', 'directorist'),
				'value' => true,
				'show-if' => [
					'where' => "enable_review",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
			],
			'enable_reviewer_content' => [
				'type' => 'toggle',
				'label' => __('Enable Reviewer content', 'directorist'),
				'value' => true,
				'description' => __('Allow to display content of reviewer on single listing page.', 'directorist'),
				'show-if' => [
					'where' => "enable_review",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
			],
			'required_reviewer_content' => [
				'type' => 'toggle',
				'label' => __('Required Reviewer content', 'directorist'),
				'value' => true,
				'description' => __('Allow to Require the content of reviewer on single listing page.', 'directorist'),
				'show-if' => [
					'where' => "enable_review",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
			],
			'review_num' => [
				'label' => __('Number of Reviews', 'directorist'),
				'description' => __('Enter how many reviews to show on Single listing page.', 'directorist'),
				'type'  => 'number',
				'value' => '5',
				'min' => '1',
				'max' => '20',
				'step' => '1',
				'show-if' => [
					'where' => "enable_review",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
			],
			'guest_review' => [
				'type' => 'toggle',
				'label' => __('Guest Review Submission', 'directorist'),
				'value' => false,
				'show-if' => [
					'where' => "enable_review",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
			],
		];
	}

	public static function register_fields( $fields ) {
		if ( ! isset( $fields['enable_review'] ) ) {
			$fields = array_merge( $fields, self::get_fields() );
		}

		return $fields;
	}
}

Settings_Screen::init();
