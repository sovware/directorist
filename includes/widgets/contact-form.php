<?php
/**
 * @author wpWax
 */

namespace Directorist\Widgets;

use Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

class Contact_Form extends \WP_Widget {

	public function __construct() {
		$id_base        = 'bdco_widget';
        $name           = esc_html__( 'Directorist - Contact Listing Owner', 'directorist' );
        $widget_options =             [
            'classname' => 'directorist-widget',
            'description' => esc_html__( 'You can show a form to contact the listing owners by this widget', 'directorist' ),
        ];

		parent::__construct( $id_base, $name, $widget_options );
	}

	public function form( $instance ) {
		$defaults = [
			'title'           => esc_html__('Contact the listing owner', 'directorist'),
		];

		$instance = wp_parse_args( (array) $instance, $defaults );

		$fields = [
			'title'       => [
				'label'   => esc_html__( 'Title:', 'directorist' ),
				'type'    => 'text',
            ],
        ];

		Widget_Fields::create( $fields, $instance, $this );
	}

	public function update( $new_instance, $old_instance ) {
		$instance = [];

		$instance['title']            = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
	}

	public function widget( $args, $instance ) {
		if( is_singular( ATBDP_POST_TYPE ) ) {
			echo wp_kses_post( $args['before_widget'] );

			$plan_permission = true;
			$email = get_post_meta( get_the_ID(), '_email', true );
			$author_id = get_post_field( 'post_author', get_the_ID() );
			$hide_form = get_user_meta( $author_id, 'hide_contact_form', true );

			if( is_fee_manager_active() ){
				$plan_permission = is_plan_allowed_owner_contact_widget( get_post_meta( get_the_ID(), '_fm_plans', true ) );
			}

			if ( $plan_permission && ! $hide_form ) {

				$title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Contact the listing owner', 'directorist');
				$widget_title = $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
				echo wp_kses_post( $widget_title );

				Helper::get_template( 'widgets/contact-form', compact( 'args', 'instance', 'email' ) );

			}

			echo wp_kses_post( $args['after_widget'] );
		}
	}
}