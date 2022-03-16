<?php
/**
 * @author wpWax
 */

namespace Directorist\Widgets;

use Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

class Search_Form extends \WP_Widget {

	public function __construct() {
		$id_base        = 'bdsw_widget';
        $name           = esc_html__( 'Directorist - Search Listings', 'directorist' );
        $widget_options =             [
            'classname' => 'atbd_widget',
            'description' => esc_html__( 'You can show search listing form by this widget', 'directorist' ),
        ];

		parent::__construct( $id_base, $name, $widget_options );
	}

	public function form( $instance ) {
		$defaults = [
			'title'           => esc_html__( 'Search', 'directorist' ),
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
        $allowWidget = apply_filters('atbdp_allow_search_widget', true);
        if ( ! $allowWidget ) return;

		echo wp_kses_post( $args['before_widget'] );

		if ( ! empty( $instance['title'] ) ) {
			echo wp_kses_post( $args['before_title'] ) . apply_filters( 'widget_title', esc_html( $instance['title'] ) ) . wp_kses_post( $args['after_title'] );
		}

        wp_enqueue_script( 'directorist-search-form-listing' );
        wp_enqueue_script( 'directorist-range-slider' );
        wp_enqueue_script( 'directorist-search-listing' );

        $listing_type = get_post_meta( get_the_ID(), '_directory_type', true );
        $listing_type = ( ! empty( $listing_type ) ) ? $listing_type : default_directory_type();

        $data = \Directorist\Script_Helper::get_search_script_data( [ 'directory_type_id' => $listing_type ] );
        wp_localize_script( 'directorist-search-form-listing', 'atbdp_search_listing', $data );
        wp_localize_script( 'directorist-search-listing', 'atbdp_search', [
            'ajaxnonce' => wp_create_nonce('bdas_ajax_nonce'),
            'ajax_url' => admin_url('admin-ajax.php'),
        ]);
        wp_localize_script( 'directorist-search-listing', 'atbdp_search_listing', $data );
        wp_localize_script( 'directorist-range-slider', 'atbdp_range_slider', $data );

		Helper::get_template( 'widgets/search-form', compact( 'args', 'instance' ) );

		echo wp_kses_post( $args['after_widget'] );
	}
}