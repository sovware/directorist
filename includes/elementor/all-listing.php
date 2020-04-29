<?php
/**
 * @author AazzTech
 */

namespace AazzTech\Directorist\Elementor;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_All_Listing extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->az_name = __( 'All Listings', 'directorist' );
		$this->az_base = 'directorist_all_listing';
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

	private function az_listing_tags() {
	    $result = array();
	    $tags = get_terms( ATBDP_TAGS );
        foreach ( $tags as $tag ) {
            $result[$tag->slug] = $tag->name;
        }
	    return $result;
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
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'header',
				'label'     => __( 'Show Header?', 'directorist' ),
				'default'   => 'no',
			),
			array(
				'type'      => Controls_Manager::TEXT,
				'id'        => 'header_title',
				'label'     => __( 'All Listing Title', 'directorist' ),
				'default'   => __( 'All Items', 'directorist' ),
				'condition' => array( 'header' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::TEXT,
				'id'        => 'header_sub_title',
				'label'     => __( 'Total Listings Found Title', 'directorist' ),
				'default'   => __( 'Listings Found', 'directorist' ),
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'filter',
				'label'     => __( 'Show Filter Button?', 'directorist' ),
				'default'   => 'no',
				'condition' => array( 'header' => 'yes' ),
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
				'label'   => __( 'Listings Per Row', 'directorist' ),
				'options' => array(
					'5' => __( '5 Items / Row', 'directorist'  ),
					'4' => __( '4 Items / Row', 'directorist'  ),
					'3' => __( '3 Items / Row', 'directorist'  ),
					'2' => __( '2 Items / Row', 'directorist'  ),
				),
				'default' => '3',
				'condition' => array( 'view' => 'grid' ),
			),
			array(
				'type'      => Controls_Manager::NUMBER,
				'id'        => 'listing_number',
				'label'     => __( 'Number of Listings to Show', 'directorist' ),
				'min'       => 1,
				'max'       => 100,
				'step'      => 1,
				'default'   => 6,
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'preview',
				'label'     => __( 'Show Preview Image?', 'directorist' ),
				'default'   => 'yes',
			),
			array(
				'type'     => Controls_Manager::SELECT2,
				'id'       => 'cat',
				'label'    => __( 'Specify Categories', 'directorist' ),
				'multiple' => true,
				'options'  => $this->az_listing_categories(),
			),
			array(
				'type'     => Controls_Manager::SELECT2,
				'id'       => 'tag',
				'label'    => __( 'Specify Tags', 'directorist' ),
				'multiple' => true,
				'options'  => $this->az_listing_tags(),
			),
			array(
				'type'     => Controls_Manager::SELECT2,
				'id'       => 'location',
				'label'    => __( 'Specify Locations', 'directorist' ),
				'multiple' => true,
				'options'  => $this->az_listing_locations(),
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'featured',
				'label'     => __( 'Show Featured Only?', 'directorist' ),
				'default'   => 'no',
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'popular',
				'label'     => __( 'Show Popular Only?', 'directorist' ),
				'default'   => 'no',
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'user',
				'label'     => __( 'Only For Logged In User?', 'directorist' ),
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
		$settings = $this->get_settings();

        $header          = $settings['header'] ? $settings['header'] : 'no';
        $filter          = $settings['filter'] ? $settings['filter'] : 'no';
        $show_pagination = $settings['show_pagination'] ? $settings['show_pagination'] : 'no';
        $cat             = $settings['cat'] ? implode($settings['cat'], ',') : '';
        $tag             = $settings['tag'] ? implode($settings['tag'], ',') : '';
        $location        = $settings['location'] ? implode($settings['location'], ',') : '';
        $featured        = $settings['featured'] ? $settings['featured'] : 'no';
        $popular         = $settings['popular'] ? $settings['popular'] : 'no';
        $user            = $settings['user'] ? $settings['user'] : 'no';
        $preview         = $settings['preview'] ? $settings['preview'] : 'no';

		$shortcode = sprintf( '[directorist_all_listing header="%1$s" header_title="%2$s" header_sub_title="%3$s" advanced_filter="%4$s" view="%5$s" map_height="%6$s" columns="%7$s" listings_per_page="%8$s" show_pagination="%9$s" category="%10$s" tag="%11$s" location="%12$s" featured_only="%13$s" popular_only="%14$s" logged_in_user_only="%15$s" display_preview_image="%16$s" orderby="%17$s" order="%18$s" ]',
			esc_attr( $header ),
			esc_attr( $settings['header_title'] ),
			esc_attr( $settings['header_sub_title'] ),
			esc_attr( $filter ),
			esc_attr( $settings['view'] ),
			esc_attr( $settings['map_height'] ),
			esc_attr( $settings['columns'] ),
			esc_attr( $settings['listing_number'] ),
			esc_attr( $show_pagination ),
			esc_attr( $cat ),
			esc_attr( $tag ),
			esc_attr( $location ),
			esc_attr( $featured ),
			esc_attr( $popular ),
			esc_attr( $user ),
			esc_attr( $preview ),
			esc_attr( $settings['order_by'] ),
			esc_attr( $settings['order_list'] )
		);

		echo do_shortcode( $shortcode );
	}
}