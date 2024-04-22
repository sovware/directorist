<?php
/**
 * @author wpWax
 */

namespace Directorist\Widgets;

use Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

class Popular_Listings extends \WP_Widget {

	public function __construct() {
		$id_base        = 'bdpl_widget';
        $name           = esc_html__( 'Directorist - Popular Listings', 'directorist' );
        $widget_options =             [
            'classname' => 'atbd_widget',
            'description' => esc_html__( 'You can show popular listing by this widget', 'directorist' ),
        ];

		parent::__construct( $id_base, $name, $widget_options );
	}

	public function form( $instance ) {
		$defaults = [
			'title'           => esc_html__( 'Popular Listings', 'directorist' ),
			'pop_listing_num' => 5,
			'single_only'     => 1,
		];

		$instance = wp_parse_args( (array) $instance, $defaults );

		$fields = [
			'title'       => [
				'label'   => esc_html__( 'Title:', 'directorist' ),
				'type'    => 'text',
            ],
			'pop_listing_num' => [
				'label'   => esc_html__( 'Number of Listings:', 'directorist' ),
				'type'    => 'number',
			],
			'single_only' => [
				'label'   => esc_html__( 'Display only on single listing', 'directorist' ),
				'type'    => 'checkbox',
				'value'   => 1,
			],
        ];

		Widget_Fields::create( $fields, $instance, $this );
	}

	public function update( $new_instance, $old_instance ) {
		$instance = [];

		$instance['title']            = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['pop_listing_num']  = ! empty( $new_instance['pop_listing_num'] ) ? sanitize_text_field( $new_instance['pop_listing_num'] ) : '';
		$instance['single_only']      = ! empty( $new_instance['single_only'] ) ? 1 : 0;

		return $instance;
	}

	public function widget( $args, $instance ) {
		echo wp_kses_post( $args['before_widget'] );

		$title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Popular Listings', 'directorist');
		$widget_title = $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
		echo '<div class="atbd_widget_title">';
		echo wp_kses_post( $widget_title );
		echo '</div>';

		$count = !empty( $instance['pop_listing_num'] ) ? $instance['pop_listing_num'] : 5;
		$query = $this->popular_listings_query( $count );

		Helper::get_template( 'widgets/popular-listings', compact( 'args', 'instance', 'query' ) );

		echo wp_kses_post( $args['after_widget'] );
	}

	public function popular_listings_query( $count = 5 ) {
		$count           = intval( $count > 0 ? $count : 5 );
		$view_to_popular = get_directorist_option( 'views_for_popular' );
		$count = apply_filters( 'atbdp_popular_listing_number', $count );

		$args = array(
			'post_type'      => ATBDP_POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => $count,
		);

		$has_featured               = directorist_is_featured_listing_enabled() || is_fee_manager_active();
		$listing_popular_by         = get_directorist_option( 'listing_popular_by' );
		$average_review_for_popular = (int) get_directorist_option( 'average_review_for_popular', 4 );
		$view_to_popular            = (int) get_directorist_option( 'views_for_popular' );

		$meta_queries = array();

		if ( $has_featured ) {
			if ( 'average_rating' === $listing_popular_by ) {
				$meta_queries['_rating'] = array(
					'key'     => directorist_get_rating_field_meta_key(),
					'value'   => $average_review_for_popular,
					'type'    => 'NUMERIC',
					'compare' => '<=',
				);
			} elseif ( 'view_count' === $listing_popular_by ) {
				$meta_queries['views'] = array(
					'key'     => '_atbdp_post_views_count',
					'value'   => $view_to_popular,
					'type'    => 'NUMERIC',
					'compare' => '>=',
				);

				$args['orderby'] = array(
					'_featured' => 'DESC',
					'views'     => 'DESC',
				);
			} else {
				$meta_queries['views'] = array(
					'key'     => '_atbdp_post_views_count',
					'value'   => $view_to_popular,
					'type'    => 'NUMERIC',
					'compare' => '>=',
				);

				$meta_queries['_rating'] = array(
					'key'     => directorist_get_rating_field_meta_key(),
					'value'   => $average_review_for_popular,
					'type'    => 'NUMERIC',
					'compare' => '<=',
				);

				$args['orderby'] = array(
					'_featured' => 'DESC',
					'views'     => 'DESC',
				);
			}
		} else {
			if ( 'average_rating' === $listing_popular_by ) {
				$meta_queries['_rating'] = array(
					'key'     => directorist_get_rating_field_meta_key(),
					'value'   => $average_review_for_popular,
					'type'    => 'NUMERIC',
					'compare' => '<=',
				);
			} elseif ( 'view_count' === $listing_popular_by ) {
				$meta_queries['views'] = array(
					'key'     => '_atbdp_post_views_count',
					'value'   => $view_to_popular,
					'type'    => 'NUMERIC',
					'compare' => '>=',
				);

				$args['orderby'] = array(
					'views' => 'DESC',
				);
			} else {
				$meta_queries['views'] = array(
					'key'     => '_atbdp_post_views_count',
					'value'   => $view_to_popular,
					'type'    => 'NUMERIC',
					'compare' => '>=',
				);

				$meta_queries['_rating'] = array(
					'key'     => directorist_get_rating_field_meta_key(),
					'value'   => $average_review_for_popular,
					'type'    => 'NUMERIC',
					'compare' => '<=',
				);

				$args['orderby'] = array(
					'views' => 'DESC',
				);
			}
		}

		if ( count( $meta_queries ) ) {
			$meta_queries['relation'] = 'AND';
			$args['meta_query'] = $meta_queries;
		}

		return new \WP_Query( apply_filters( 'atbdp_popular_listing_args', $args ) );
	}
}