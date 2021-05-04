<?php
/**
 * @author AazzTech
 */

namespace AazzTech\Directorist\Elementor;

use Elementor\Controls_Manager;
use Directorist\Helper;

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

	private function az_listing_types() {
		$listing_types = array();
		$all_types = get_terms( [ 'taxonomy'=> ATBDP_TYPE, 'hide_empty' => false ] );

		foreach ( $all_types as $type ) {
			$listing_types[ $type->slug ] = $type->name;
		}
		return $listing_types;
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
				'id'       => 'type',
				'label'    => __( 'Directory Types', 'directorist' ),
				'multiple' => true,
				'options'  => $this->az_listing_types(),
				'condition' => Helper::multi_directory_enabled() ? '' : ['nocondition' => true],
			),
			array(
				'type'     => Controls_Manager::SELECT2,
				'id'       => 'default_type',
				'label'    => __( 'Default Directory Types', 'directorist' ),
				'options'  => $this->az_listing_types(),
				'condition' => Helper::multi_directory_enabled() ? '' : ['nocondition' => true],
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

		$atts = array(
			'header'                => $settings['header'] ? $settings['header'] : 'no',
			'header_title'          => $settings['header_title'],
			'advanced_filter'       => $settings['filter'] ? $settings['filter'] : 'no',
			'view'                  => $settings['view'],
			'map_height'            => $settings['map_height'],
			'columns'               => $settings['columns'],
			'listings_per_page'     => $settings['listing_number'],
			'show_pagination'       => $settings['show_pagination'] ? $settings['show_pagination'] : 'no',
			'category'              => $settings['cat'] ? implode( ',', $settings['cat'] ) : '',
			'tag'                   => $settings['tag'] ? implode( ',', $settings['tag'] ) : '',
			'location'              => $settings['location'] ? implode( ',', $settings['location'] ) : '',
			'featured_only'         => $settings['featured'] ? $settings['featured'] : 'no',
			'popular_only'          => $settings['popular'] ? $settings['popular'] : 'no',
			'logged_in_user_only'   => $settings['user'] ? $settings['user'] : 'no',
			'display_preview_image' => $settings['preview'] ? $settings['preview'] : 'no',
			'orderby'               => $settings['order_by'],
			'order'                 => $settings['order_list'],
		);

		if ( Helper::multi_directory_enabled() ) {
			if ( $settings['type'] ) {
				$atts['directory_type'] = implode( ',', $settings['type'] );
			}
			if ( $settings['default_type'] ) {
				$atts['default_directory_type'] = $settings['default_type'];
			}
		}

		$this->az_run_shortcode( 'directorist_all_listing', $atts );
	}
}