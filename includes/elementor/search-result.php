<?php
/**
 * @author AazzTech
 */

namespace AazzTech\Directorist\Elementor;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Search_Result extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->az_name = __( 'Search Result', 'directorist' );
		$this->az_base = 'directorist_search_result';
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
				'label'     => __( 'Title', 'directorist' ),
				'default'   => 'Search Result',
				'condition' => array( 'header' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::TEXT,
				'id'        => 'header_sub_title',
				'label'     => __( 'Subtitle', 'directorist' ),
				'default'   => 'Total Listing Found',
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
				'condition' => array( 'view' => array( 'grid' ) ),
			),
			array(
				'type'      => Controls_Manager::NUMBER,
				'id'        => 'number',
				'label'     => __( 'Number of Listing to Show:', 'directorist' ),
				'min'       => 1,
				'max'       => 100,
				'step'      => 1,
				'default'   => 6,
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'featured_only',
				'label'     => __( 'Only Featured', 'directorist' ),
				'default'   => 'no',
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'popular_only',
				'label'     => __( 'Only Popular', 'directorist' ),
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
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'show_pagination',
				'label'     => __( 'Show Pagination?', 'directorist' ),
				'default'   => 'no',
			),
			array(
				'mode' => 'section_end',
			),
		);
		return $fields;
	}	

	protected function render() {
		$settings = $this->get_settings_for_display();

        $user          = $settings['user'] ? $settings['user'] : 'no';
        $featured_only = $settings['featured_only'] ? $settings['featured_only'] : 'no';
        $popular_only  = $settings['popular_only'] ? $settings['popular_only'] : 'no';

		$shortcode = sprintf( '[directorist_search_result header="%1$s" header_title="%2$s" header_sub_title="%3$s" view="%4$s" map_height="%5$s" columns="%6$s" listings_per_page="%7$s" show_pagination="%8$s" orderby="%9$s" order="%10$s" featured_only="%11$s" popular_only="%12$s"  logged_in_user_only="%13$s" ]', 
			esc_attr( $settings['header'] ),
			esc_attr( $settings['header_title'] ),
			esc_attr( $settings['header_sub_title'] ),
			esc_attr( $settings['view'] ),
			esc_attr( $settings['map_height'] ),
			esc_attr( $settings['columns'] ),
			esc_attr( $settings['number'] ),
			esc_attr( $settings['show_pagination'] ),
			esc_attr( $settings['order_by'] ),
			esc_attr( $settings['order_list'] ),
			esc_attr( $featured_only ),
			esc_attr( $popular_only ),
			esc_attr( $user )
		);

		echo do_shortcode( $shortcode );
	}
}