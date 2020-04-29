<?php
/**
 * @author AazzTech
 */

namespace AazzTech\Directorist\Elementor;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_All_Locations extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->az_name = __( 'Listing Locations', 'directorist' );
		$this->az_base = 'directorist_all_locations';
		parent::__construct( $data, $args );
	}

	private function az_listing_locations() {
	    $result = array();
	    $locations = get_terms( ATBDP_LOCATION );
        foreach ( $locations as $location ) {
            $result[$location->slug] = $location->name;
        }
	    return $result;
	}

	public function az_fields(){
		$fields = array(
			array(
				'mode'    => 'section_start',
				'id'      => 'sec_general',
				'label'   => __( 'General', 'directorist' ),
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
				'type'    => Controls_Manager::SELECT,
				'id'      => 'columns',
				'label'   => __( 'Locations Per Row', 'directorist' ),
				'options' => array(
					'2' => __( '2 Items / Row', 'directorist'  ),
					'3' => __( '3 Items / Row', 'directorist'  ),
					'4' => __( '4 Items / Row', 'directorist'  ),
					'5' => __( '5 Items / Row', 'directorist'  ),
					'6' => __( '6 Items / Row', 'directorist'  ),
				),
				'default' => '3',
				'condition' => array( 'view' => array( 'grid' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT,
				'id'      => 'order_by',
				'label'   => __( 'Order by', 'directorist' ),
				'options' => array(
                    'id'    => __( 'ID', 'directorist' ),
                    'count' => __( 'Count', 'directorist' ),
                    'name'  => __( 'Name', 'directorist' ),
                    'slug'  => __( 'Slug', 'directorist' ),
				),
				'default' => 'id',
			),
			array(
				'type'     => Controls_Manager::SELECT2,
				'id'       => 'slug',
				'label'    => __( 'Specify Locations', 'directorist' ),
				'multiple' => true,
				'options'  => $this->az_listing_locations(),
				'condition' => array( 'order_by' => array( 'slug' ) ),
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
				'type'      => Controls_Manager::NUMBER,
				'id'        => 'number_loc',
				'label'     => __( 'Number of Locations to Show', 'directorist' ),
				'min'       => 1,
				'max'       => 100,
				'step'      => 1,
				'default'   => 6,
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'user',
				'label'     => __( 'Only For Logged In User?', 'directorist' ),
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

        $user = $settings['user'] ? $settings['user'] : 'no';
        $slug = $settings['slug'] ? implode( $settings['slug'], ',' ) : '';

		$shortcode = sprintf( '[directorist_all_locations view="%1$s" columns="%2$s" loc_per_page="%3$s" orderby="%4$s" order="%5$s" logged_in_user_only="%6$s" slug="%7$s"]',
			esc_attr( $settings['view'] ),
			esc_attr( $settings['columns'] ),
			esc_attr( $settings['number_loc'] ),
			esc_attr( $settings['order_by'] ),
			esc_attr( $settings['order_list'] ),
			esc_attr( $user ),
			esc_attr( $slug )
		);

		echo do_shortcode( $shortcode );
	}
}