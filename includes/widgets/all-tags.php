<?php
/**
 * @author wpWax
 */

namespace Directorist\Widgets;

use Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

class All_Tags extends \WP_Widget {

	public function __construct() {
		$id_base        = 'bdtw_widget';
        $name           = esc_html__( 'Directorist - Tags', 'directorist' );
        $widget_options =             [
            'classname' => 'atbd_widget',
            'description' => esc_html__( 'You can show listing tags by this widget', 'directorist' ),
        ];

		parent::__construct( $id_base, $name, $widget_options );
	}

	public function form( $instance ) {
		$defaults = [
			'title'                 => esc_html__( 'Tags', 'directorist' ),
			'display_as'            => 'list',
            'order_by'              => 'id',
            'order'                 => 'asc',
            'max_number'            => '',
            'hide_empty'            => 0,
            'show_count'            => 0,
            'display_single_tag'    => 0,
		];

		$instance = wp_parse_args( (array) $instance, $defaults );

		$fields = [
			'title'       => [
				'label'   => esc_html__( 'Title:', 'directorist' ),
				'type'    => 'text',
            ],
			'display_as' => [
				'label'   => esc_html__( 'View as:', 'directorist' ),
				'type'    => 'select',
                'options' => [
                    'list'      => esc_html__( 'List', 'directorist' ),
                    'dropdown'  => esc_html__( 'Dropdown', 'directorist' )
                ]
			],
            'order_by' => [
				'label'   => esc_html__( 'Order By:', 'directorist' ),
				'type'    => 'select',
                'options' => [
                    'id'      => esc_html__( 'Id', 'directorist' ),
                    'count'   => esc_html__( 'Count', 'directorist' ),
                    'name'    => esc_html__( 'Name', 'directorist' ),
                    'slug'    => esc_html__( 'Slug', 'directorist' )
                ]
			],
            'order' => [
				'label'   => esc_html__( 'Sort By:', 'directorist' ),
				'type'    => 'select',
                'options' => [
                    'asc'    => esc_html__( 'Ascending', 'directorist' ),
                    'desc'   => esc_html__( 'Descending', 'directorist' ),
                ]
			],
            'max_number'       => [
				'label'   => esc_html__( 'Maximum Number:', 'directorist' ),
				'type'    => 'text',
            ],
            'hide_empty' => [
				'label'   => esc_html__( 'Hide empty tags', 'directorist' ),
				'type'    => 'checkbox',
				'value'   => 1,
			],
            'show_count' => [
				'label'   => esc_html__( 'Display listing counts', 'directorist' ),
				'type'    => 'checkbox',
				'value'   => 1,
			],
            'display_single_tag' => [
				'label'   => esc_html__( 'Display single listing tags', 'directorist' ),
				'type'    => 'checkbox',
				'value'   => 1,
			],
        ];

		Widget_Fields::create( $fields, $instance, $this );
	}

	public function update( $new_instance, $old_instance ) {
		$instance = [];

		$instance['title']              = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['display_as']         = ! empty( $new_instance['display_as'] ) ? sanitize_text_field( $new_instance['display_as'] ) : 'list';
        $instance['order_by']           = ! empty( $new_instance['order_by'] ) ? sanitize_text_field( $new_instance['order_by'] ) : 'id';
        $instance['order']              = ! empty( $new_instance['order'] ) ? sanitize_text_field( $new_instance['order'] ) : 'asc';
        $instance['hide_empty']         = ! empty( $new_instance['hide_empty'] ) ? 1 : 0;
        $instance['show_count']         = ! empty( $new_instance['show_count'] ) ? 1 : 0;
        $instance['display_single_tag'] = ! empty( $new_instance['display_single_tag'] ) ? 1 : 0;
        $instance['max_number']         = ! empty( $new_instance['max_number'] ) ? $new_instance['max_number'] : '';

		return $instance;
	}

	public function widget( $args, $instance ) {
        $allowWidget = apply_filters('atbdp_allow_tags_widget', true);
        $check_tag   = get_the_terms(get_the_ID(), ATBDP_TAGS);

        if( ( ! empty( $instance['display_single_tag'] ) && ! is_singular( ATBDP_POST_TYPE ) && ! $check_tag ) || ! $allowWidget)
            return;

		echo wp_kses_post( $args['before_widget'] );

		$title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Tags', 'directorist');
		$widget_title = $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
		echo '<div class="atbd_widget_title">';
		echo wp_kses_post( $widget_title );
		echo '</div>';

        $query_args = array(
            'template'               => !empty( $instance['display_as'] ) ? sanitize_text_field( $instance['display_as'] ) : 'list',
            'parent'                 => !empty( $instance['parent'] ) ? (int) $instance['parent'] : 0,
            'term_id'                => !empty( $instance['parent'] ) ? (int) $instance['parent'] : 0,
            'hide_empty'             => !empty( $instance['hide_empty'] ) ? 1 : 0,
            'orderby'                => !empty( $instance['order_by'] ) ? sanitize_text_field( $instance['order_by'] ) : 'id',
            'order'                  => !empty( $instance['order'] ) ? sanitize_text_field( $instance['order'] ) : 'asc',
            'show_count'             => !empty( $instance['show_count'] ) ? 1 : 0,
            'display_single_tag'     => !empty( $instance['display_single_tag'] ) ? 1 : 0,
            'pad_counts'             => true,
            'immediate_category'     => !empty( $instance['immediate_category'] ) ? 1 : 0,
            'max_number'             => !empty( $instance['max_number'] ) ? $instance['max_number'] : '',
            'active_term_id'         => 0,
            'ancestors'              => array()
        );

        if( $query_args['immediate_category'] ) {

            $term_slug = get_query_var( ATBDP_TAGS );

            if( '' != $term_slug ) {
                $term = get_term_by( 'slug', $term_slug, ATBDP_TAGS );
                $query_args['active_term_id'] = $term->term_id;

                $query_args['ancestors'] = get_ancestors( $query_args['active_term_id'], 'atbdp_tags' );
                $query_args['ancestors'][] = $query_args['active_term_id'];
                $query_args['ancestors'] = array_unique( $query_args['ancestors'] );
            }

        }

        if( 'dropdown' == $query_args['template'] ) {
            $tags = $this->dropdown_tags( $query_args );
        } else {
            $tags = $this->directorist_tags_list( $query_args );
        }

		Helper::get_template( 'widgets/all-tags', compact( 'args', 'instance', 'query_args', 'tags' ) );

		echo wp_kses_post( $args['after_widget'] );
	}

    public function directorist_tags_list( $settings ) {
        if ( $settings['display_single_tag'] ) {
            $terms = get_the_terms(get_the_ID(), ATBDP_TAGS);
            $html = '';

            if ( ! empty( $terms ) ) {
                $html .= '<ul>';
                foreach ( $terms as $term ) {
                    $html .= '<li>';
                    $html .= '<a href="' . \ATBDP_Permalink::atbdp_get_tag_page($term) . '">';
                    $html .= $term->name;
                    $html .= '</a>';
                    $html .= '</li>';
                }
                $html .= '</ul>';
            }
        } else {
            if ( $settings['immediate_category'] &&
				( $settings['term_id'] > $settings['parent'] ) &&
				! in_array( $settings['term_id'], $settings['ancestors'] ) ) {
				return;
            }

            $args = array(
                'taxonomy'     => ATBDP_TAGS,
                'orderby'      => $settings['orderby'],
                'order'        => $settings['order'],
                'hide_empty'   => $settings['hide_empty'],
                'parent'       => !empty($settings['term_id']) ? $settings['term_id'] : '',
                'hierarchical' => !empty($settings['hide_empty']) ? true : false,
                'number'       => !empty($settings['max_number']) ? $settings['max_number'] : ''
            );

            $terms = get_terms( $args );

			if ( is_wp_error( $terms ) ) {
				return;
			}

            $html = '';
			$html .= '<ul>';

			foreach ( $terms as $term ) {
				$settings['term_id'] = $term->term_id;

				$html .= '<li>';
				$html .= '<a href="' . \ATBDP_Permalink::atbdp_get_tag_page( $term ) . '">';
				$html .= $term->name;

				if ( ! empty( $settings['show_count'] ) ) {
					$html .= ' (' . $term->count . ')';
				}

				$html .= '</a>';
				// $html .= $this->directorist_tags_list($settings);
				$html .= '</li>';
			}

			$html .= '</ul>';
        }

        return $html;
    }

    public function dropdown_tags( $settings, $prefix = '' ) {
        $term_slug = get_query_var(ATBDP_TAGS);
        if( $settings['display_single_tag'] ) {
            $terms = get_the_terms(get_the_ID(), ATBDP_TAGS);
            $html = '';
            if (!empty($terms)) {

                foreach ($terms as $term) {
                    $html .= sprintf( '<option value="%s" %s>', $term->slug, selected( $term->slug, $term_slug, false ) );
                    $html .= $prefix . $term->name;
                    $html .= '</option>';
                }

            }
        } else {

            if ( $settings['immediate_category'] &&
				( $settings['term_id'] > $settings['parent'] ) &&
				! in_array( $settings['term_id'], $settings['ancestors'] ) ) {
				return;
            }

            $args = array(
				'taxonomy'     => ATBDP_TAGS,
				'orderby'      => $settings['orderby'],
				'order'        => $settings['order'],
				'hide_empty'   => $settings['hide_empty'],
				'parent'       => !empty($settings['term_id']) ? $settings['term_id'] : '',
				'hierarchical' => !empty($settings['hide_empty']) ? true : false,
				'number'       => !empty($settings['max_number']) ? $settings['max_number'] : ''
			);

            $terms = get_terms( $args );

			if ( is_wp_error( $terms ) ) {
				return;
			}

            $html = '';

			foreach ( $terms as $term ) {
				$settings['term_id'] = $term->term_id;

				$html .= sprintf('<option value="%s" %s>', $term->term_taxonomy_id, selected($term->slug, $term_slug, false));
				$html .= $prefix . $term->name;

				if ( ! empty( $settings['show_count'] ) ) {
					$html .= ' (' . $term->count . ')';
				}

				//$html .= $this->dropdown_tags($settings, $prefix . '&nbsp;&nbsp;&nbsp;');
				$html .= '</option>';
			}
        }

        return $html;
    }
}