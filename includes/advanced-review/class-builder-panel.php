<?php
/**
 * Builder panel class.
 *
 * @package wpWax\Directorist
 * @subpackage Review
 * @since 7.x
 */
namespace wpWax\Directorist\Review;

defined( 'ABSPATH' ) || die();

class Builder_Panel {

	public static function init() {
		add_filter( 'atbdp_listing_type_settings_layout', [ __CLASS__, 'register_fields' ] );
	}

	public static function register_fields( $layout ) {
		if ( isset( $layout['review'] ) ) {
			$layout['review']['sections']['labels']['fields'] = ['enable_review', 'enable_owner_review', 'approve_immediately', 'review_approval_text', 'enable_reviewer_img', 'enable_reviewer_content', 'required_reviewer_content', 'review_num', 'guest_review'];
		} else {
			$layout['review'] = [
				'label' => __('Review', 'directorist'),
				'icon' => '<i class="fa fa-star"></i>',
				'sections' => apply_filters( 'atbdp_listing_settings_review_sections', [
					'labels' => [
						'fields' => [
							'enable_review', 'enable_owner_review', 'approve_immediately', 'review_approval_text', 'enable_reviewer_img', 'enable_reviewer_content', 'required_reviewer_content', 'review_num', 'guest_review'
						],
					],
				] ),
			];
		}

		return $layout;
	}

	public static function register( $fields ) {
		$_fields = [
			// review settings
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

		return array_merge( $fields, $_fields );
	}

}

Settings_Panel::init();
