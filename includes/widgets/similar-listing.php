<?php
/**
 * @author wpWax
 */

namespace Directorist\Widgets;

use Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

class Similar_Listing extends \WP_Widget {

	public function __construct() {
		$id_base        = 'bdsl_widget';
        $name           = esc_html__( 'Directorist - Similar Listings', 'directorist' );
        $widget_options =             [
            'classname' => 'atbd_widget',
            'description' => esc_html__( 'You can show similar listing by this widget', 'directorist' ),
        ];

		parent::__construct( $id_base, $name, $widget_options );
	}

	public function form( $instance ) {
		$defaults = [
			'title'               => esc_html__( 'Similar Listings', 'directorist' ),
			'sim_listing_num'     => 5,
		];

		$instance = wp_parse_args( (array) $instance, $defaults );

		$fields = [
			'title'       => [
				'label'   => esc_html__( 'Title:', 'directorist' ),
				'type'    => 'text',
            ],
			'sim_listing_num' => [
				'label'   => esc_html__( 'Number of Listings', 'directorist' ),
				'type'    => 'text',
			],
        ];

		Widget_Fields::create( $fields, $instance, $this );
	}

	public function update( $new_instance, $old_instance ) {
		$instance = [];

		$instance['title']            = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['sim_listing_num']  = ! empty( $new_instance['sim_listing_num'] ) ? sanitize_text_field( $new_instance['sim_listing_num'] ) : 5;

		return $instance;
	}

	public function widget( $args, $instance ) {
        $allowWidget = apply_filters('atbdp_allow_similar_widget', true);
        if( ! is_singular( ATBDP_POST_TYPE ) || ! $allowWidget )
            return;

		echo wp_kses_post( $args['before_widget'] );

		$title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Similar Listings', 'directorist');
		echo '<div class="atbd_widget_title">';
		echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];
		echo '</div>';

		Helper::get_template( 'widgets/similar-listing', compact( 'args', 'instance' ) );

		echo wp_kses_post( $args['after_widget'] );
	}
}