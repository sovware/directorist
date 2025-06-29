<?php
/**
 * @author AazzTech
 */

namespace AazzTech\Directorist\Elementor;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Tag extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->az_name = __( 'Single Tag', 'directorist' );
		$this->az_base = 'directorist_tag';
		parent::__construct( $data, $args );
	}

	public function az_fields(){
		$fields = array(
			array(
				'mode'    => 'section_start',
				'id'      => 'sec_general',
				'label'   => __( 'General', 'directorist' ),
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'header',
				'label'     => __( 'Show Header?', 'directorist' ),
				'default'   => 'yes',
			),
			array(
				'type'      => Controls_Manager::TEXT,
				'id'        => 'header_title',
				'label'     => __( 'All Listing Title', 'directorist' ),
				'default'   => __( 'Total Listings Found:', 'directorist' ),
				'condition' => array( 'header' => array( 'yes' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT,
				'id'      => 'view',
				'label'   => __( 'View As', 'directorist' ),
				'options' => array(
					'grid' => __( 'Grid View', 'directorist' ),
					'list' => __( 'List View', 'directorist' ),
					'map'  => __( 'Map View', 'directorist' ),
				),
				'default' => 'grid',
			),
			array(
				'type'      => Controls_Manager::NUMBER,
				'id'        => 'map_height',
				'label'     => __( 'Map Height', 'directorist' ),
				'min'       => 300,
				'max'       => 1980,
				'default'   => 500,
				'condition' => array( 'view' => array( 'map' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT,
				'id'      => 'columns',
				'label'   => __( 'Locations Per Row', 'directorist' ),
				'options' => array(
					'3' => __( '3 Items / Row', 'directorist'  ),
					'4' => __( '4 Items / Row', 'directorist'  ),
					'5' => __( '5 Items / Row', 'directorist'  ),
				),
				'default' => '3',
			),
			array(
				'type'      => Controls_Manager::NUMBER,
				'id'        => 'number_loc',
				'label'     => __( 'Number of locations to Show', 'directorist' ),
				'min'       => 1,
				'max'       => 100,
				'step'      => 1,
				'default'   => 6,
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'show_pagination',
				'label'     => __( 'Show Pagination?', 'directorist' ),
				'default'   => 'no',
			),
			array(
				'type'    => Controls_Manager::SELECT,
				'id'      => 'order_by',
				'label'   => __( 'Order by', 'directorist' ),
				'options' => array(
					'title' => __( 'Title', 'directorist' ),
					'date'  => __( 'Date', 'directorist' ),
					'price' => __( 'Price', 'directorist' ),
				),
				'default' => 'date',
			),
			array(
				'type'    => Controls_Manager::SELECT,
				'id'      => 'order_list',
				'label'   => __( 'Listings Order', 'directorist' ),
				'options' => array(
					'asc'  => __( ' ASC', 'directorist' ),
					'desc' => __( ' DESC', 'directorist' ),
				),
				'default' => 'desc',
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'user',
				'label'     => __( 'Only For Logged In User?', 'directorist' ),
				'default'   => 'no',
			),
			array(
				'type'      => Controls_Manager::TEXT,
				'id'        => 'redirect_page_url',
				'label'     => __( 'Redirect Page Link', 'directorist' ),
				'default'   => '',
			),
			array(
				'mode' => 'section_end',
			),
		);
		return $fields;
	}	

	protected function render() {
		$settings = $this->get_settings_for_display();

		$user = $settings['user'] ? $settings['user'] : 'no';

		$shortcode = sprintf( '[directorist_tag header="%1$s" header_title="%2$s" view="%3$s" map_height="%4$s" columns="%5$s" listings_per_page="%6$s" show_pagination="%7$s" orderby="%8$s" order="%9$s" redirect_page_url="%10$s" logged_in_user_only="%11$s" ]', 
			esc_attr( $settings['header'] ),
			esc_attr( $settings['header_title'] ),
			esc_attr( $settings['view'] ),
			esc_attr( $settings['map_height'] ),
			esc_attr( $settings['columns'] ),
			esc_attr( $settings['number_loc'] ),
			esc_attr( $settings['show_pagination'] ),
			esc_attr( $settings['order_by'] ),
			esc_attr( $settings['order_list'] ),
			esc_attr( $settings['redirect_page_url'] ),
			esc_attr( $user )
		);

		echo do_shortcode( $shortcode );
	}
}