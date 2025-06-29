<?php
/**
 * @author wpWax
 */

namespace Directorist\Widgets;

use Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

class Single_Map extends \WP_Widget {

	public function __construct() {
		$id_base        = 'bdmw_widget';
        $name           = esc_html__( 'Directorist - Map (Single Listing)', 'directorist' );
        $widget_options =             [
            'classname' => 'directorist-widget',
            'description' => esc_html__( 'You can show single listing map by this widget', 'directorist' ),
        ];

		parent::__construct( $id_base, $name, $widget_options );
	}

	public function form( $instance ) {
		$defaults = [
			'title'    => esc_html__( 'Map', 'directorist' ),
			'zoom'     => 16,
		];

		$instance = wp_parse_args( (array) $instance, $defaults );

		$fields = [
			'title'       => [
				'label'   => esc_html__( 'Title:', 'directorist' ),
				'type'    => 'text',
            ],
			'zoom' => [
				'label'   => esc_html__( 'Map Zoom Level', 'directorist' ),
				'type'    => 'text',
			],
        ];

		Widget_Fields::create( $fields, $instance, $this );
	}

	public function update( $new_instance, $old_instance ) {
		$instance = [];

		$instance['title']            = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['zoom']             = ! empty( $new_instance['zoom'] ) ? sanitize_text_field( $new_instance['zoom'] ) : 16;

		return $instance;
	}

	public function widget( $args, $instance ) {
        if( ! is_singular( ATBDP_POST_TYPE ) )
            return;

		echo wp_kses_post( $args['before_widget'] );

		$title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Popular Listings', 'directorist');
		$widget_title = $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
		echo wp_kses_post( $widget_title );

		$map_zoom_level = !empty( $instance['zoom'] ) ? (int) $instance['zoom'] : get_directorist_option('map_zoom_level', 16 );

        $manual_lat = get_post_meta( get_the_ID(), '_manual_lat', true );
        $manual_lng = get_post_meta( get_the_ID(), '_manual_lng', true );
        $tagline    = get_post_meta( get_the_ID(), '_tagline', true );
        $address    = get_post_meta( get_the_ID(), '_address', true );
        $t          = get_the_title();
        $t          = !empty($t) ? $t : __('No Title', 'directorist');
        $info_content = "<div class='map_info_window'> <h3>{$t}</h3>";
        $info_content .= "<p> {$tagline} </p>";
        $info_content .= "<address>{$address}</address>";
        $info_content .= "<a href='http://www.google.com/maps/place/{$manual_lat},{$manual_lng}' target='_blank'> " . __('View On Google Maps', 'directorist') . "</a></div>";

		$listing = \Directorist\Directorist_Single_Listing::instance();
		$map_data = json_decode( $listing->map_data() );
		$map_data->map_zoom_level = $map_zoom_level;
		//$map_data->info_content = $info_content;
		$map_data = json_encode( $map_data );

		Helper::get_template( 'widgets/single-map', compact( 'args', 'instance', 'map_data' ) );

		echo wp_kses_post( $args['after_widget'] );
	}
}