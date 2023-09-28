<?php
/**
 * @author wpWax
 */

namespace Directorist\Widgets;

use Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

class Listing_Video extends \WP_Widget {

	public function __construct() {
		$id_base        = 'bdvd_widget';
        $name           = esc_html__( 'Directorist - Video', 'directorist' );
        $widget_options =             [
            'classname' => 'directorist-widget',
            'description' => esc_html__( 'You can show listing video by this widget', 'directorist' ),
        ];

		parent::__construct( $id_base, $name, $widget_options );
	}

	public function form( $instance ) {
		$defaults = [
			'title'           => esc_html__( 'Listing Video', 'directorist' ),
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
		$videourl   = get_post_meta( get_the_ID(), '_videourl', true );

		if( is_singular( ATBDP_POST_TYPE ) && ! empty( $videourl ) ) {
			echo wp_kses_post( $args['before_widget'] );

			$title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Listing Video', 'directorist');
			$widget_title = $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
			echo wp_kses_post( $widget_title );

			Helper::get_template( 'widgets/listing-video', compact( 'args', 'instance', 'videourl' ) );

			echo wp_kses_post( $args['after_widget'] );
		}
	}
}