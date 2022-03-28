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

    public static function get_search_script_data( $args = [] ) {

        $directory_type = ( is_array( $args ) && isset( $args['directory_type_id'] ) ) ? $args['directory_type_id'] : default_directory_type();
        $directory_type_term_data = [
            'submission_form_fields' => get_term_meta( $directory_type, 'submission_form_fields', true ),
            'search_form_fields' => get_term_meta( $directory_type, 'search_form_fields', true ),
        ];

        /*Internationalization*/
        $category_placeholder    = ( isset( $directory_type_term_data['submission_form_fields']['fields']['category']['placeholder'] ) ) ? $directory_type_term_data['submission_form_fields']['fields']['category']['placeholder'] : __( 'Select a category', 'directorist' );
        $location_placeholder    = ( isset( $directory_type_term_data['submission_form_fields']['fields']['location']['placeholder'] ) ) ? $directory_type_term_data['submission_form_fields']['fields']['location']['placeholder'] : __( 'Select a location', 'directorist' );
        $select_listing_map      = get_directorist_option( 'select_listing_map', 'google' );
        $radius_search_unit      = get_directorist_option( 'radius_search_unit', 'miles' );
        $default_radius_distance = get_directorist_option( 'search_default_radius_distance', 0 );

        if ( 'kilometers' == $radius_search_unit ) {
            $miles = __( ' Kilometers', 'directorist' );
        } else {
            $miles = __( ' Miles', 'directorist' );
        }

        $data = array(
            'i18n_text'   => array(
                'category_selection' => ! empty( $category_placeholder ) ? $category_placeholder : __( 'Select a category', 'directorist' ),
                'location_selection' => ! empty( $location_placeholder ) ? $location_placeholder : __( 'Select a location', 'directorist' ),
                'show_more'          => __( 'Show More', 'directorist' ),
                'show_less'          => __( 'Show Less', 'directorist' ),
                'added_favourite'    => __( 'Added to favorite', 'directorist' ),
                'please_login'          => __( 'Please login first', 'directorist' ),
                'select_listing_map' => $select_listing_map,
                'Miles'              => !empty( $_GET['miles'] ) ? $_GET['miles'] : $miles,
            ),
            'args'                     => $args,
            'directory_type'           => $directory_type,
            'directory_type_term_data' => $directory_type_term_data,
            'ajax_url'                 => admin_url( 'admin-ajax.php' ),
            'miles'                    => !empty( $_GET['miles'] ) ? $_GET['miles'] : $miles,
            'default_val'              => $default_radius_distance,
            'countryRestriction'       => get_directorist_option( 'country_restriction' ),
            'restricted_countries'     => get_directorist_option( 'restricted_countries' ),
            'use_def_lat_long'         => get_directorist_option( 'use_def_lat_long' ),
        );
        return $data;
    }


	public function widget( $args, $instance ) {
        $allowWidget = apply_filters('atbdp_allow_search_widget', true);
        if ( ! $allowWidget ) return;

		echo wp_kses_post( $args['before_widget'] );

		$title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Search', 'directorist');
		echo '<div class="atbd_widget_title">';
		echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];
		echo '</div>';

		Helper::get_template( 'widgets/search-form', compact( 'args', 'instance' ) );

		echo wp_kses_post( $args['after_widget'] );
	}
}