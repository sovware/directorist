<?php
/**
 * @author AazzTech
 */

namespace AazzTech\Directorist\Elementor;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_All_Categories extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->az_name = __( 'Listing Categories', 'directorist' );
		$this->az_base = 'directorist_all_categories';
		parent::__construct( $data, $args );
	}

	private function az_listing_categories() {
	    $result = array();
	    $categories = get_terms( ATBDP_CATEGORY );
        foreach ( $categories as $category ) {
            $result[$category->slug] = $category->name;
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
				'label'   => __( 'Categories Per Row', 'directorist' ),
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
				'label'    => __( 'Specify Categories', 'directorist' ),
				'multiple' => true,
				'options'  => $this->az_listing_categories(),
				'condition' => array( 'order_by' => array( 'slug' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT,
				'id'      => 'order_list',
				'label'   => __( 'Categories Order', 'directorist' ),
				'options' => array(
					'asc'  => __( ' ASC', 'directorist' ),
					'desc' => __( ' DESC', 'directorist' ),
				),
				'default' => 'desc',
			),
			array(
				'type'      => Controls_Manager::NUMBER,
				'id'        => 'number_cat',
				'label'     => __( 'Number of Categories to Show', 'directorist' ),
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

		$shortcode = sprintf( '[directorist_all_categories view="%1$" columns="%2$" cat_per_page="%3$" orderby="%4$" order="%5$" logged_in_user_only="%6$" slug="%7$"]',
			esc_attr( $settings['view'] ),
			esc_attr( $settings['columns'] ),
			esc_attr( $settings['number_cat'] ),
			esc_attr( $settings['order_by'] ),
			esc_attr( $settings['order_list'] ),
			esc_attr( $user ),
			esc_attr( $slug )
		);

		echo do_shortcode( $shortcode );
	}
}