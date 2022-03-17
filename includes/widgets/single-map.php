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
            'classname' => 'atbd_widget',
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

		if ( ! empty( $instance['title'] ) ) {
			echo wp_kses_post( $args['before_title'] ) . apply_filters( 'widget_title', esc_html( $instance['title'] ) ) . wp_kses_post( $args['after_title'] );
		}

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
        $select_listing_map = get_directorist_option('select_listing_map', 'google');
        $title              = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Map', 'directorist');
        $map_zoom_level     = !empty($instance['zoom']) ? esc_html($instance['zoom']) : 10;
        $cats               = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
        $font_type          = get_directorist_option('font_type','line');
        $fa_or_la           = ('line' == $font_type) ? "la " : "fa ";
        if( ! empty( $cats ) ){
            $cat_icon         = get_cat_icon($cats[0]->term_id);
        }
        $cat_icon = !empty($cat_icon) ? $fa_or_la . $cat_icon : 'fa fa-map-marker';

        $display_map_info           = apply_filters('atbdp_listing_map_info_window', get_directorist_option('display_map_info', 1));
        $data = array(
            'listing'               => $this,
            'map_container_id'      => 'gmap-widget',
            'default_latitude'      => get_directorist_option('default_latitude', '40.7127753'),
            'default_longitude'     => get_directorist_option('default_longitude', '-74.0059728'),
            'manual_lat'            => $manual_lat,
            'manual_lng'            => $manual_lng,
            'listing_location_text' => apply_filters('atbdp_single_listing_map_section_text', get_directorist_option('listing_location_text', __('Location', 'directorist'))),
            'select_listing_map'    => get_directorist_option('select_listing_map', 'google'),
            'info_content'          => $info_content,
            'display_map_info'      => $display_map_info,
            'map_zoom_level'        => get_directorist_option('map_zoom_level', 16),
            'cat_icon'              => $cat_icon,
        );

        if ('openstreet' == $select_listing_map) {
            wp_localize_script('directorist-single-listing-openstreet-map-widget-custom-script', 'localized_data_widget', $data);
            wp_enqueue_script('directorist-single-listing-openstreet-map-widget-custom-script');
        }

        if ('google' == $select_listing_map) {
                wp_localize_script('directorist-single-listing-gmap-widget-custom-script', 'localized_data_widget', $data);
                wp_enqueue_script('directorist-single-listing-gmap-widget-custom-script');
        }

		Helper::get_template( 'widgets/single-map', compact( 'args', 'instance' ) );

		echo wp_kses_post( $args['after_widget'] );
	}
}