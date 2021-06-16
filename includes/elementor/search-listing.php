<?php
/**
 * @author AazzTech
 */

namespace AazzTech\Directorist\Elementor;

use Elementor\Controls_Manager;
use Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Search_Listing extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->az_name = __( 'Search Form', 'directorist' );
		$this->az_base = 'directorist_search_listing';
		parent::__construct( $data, $args );
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
				'id'        => 'show_subtitle',
				'label'     => __( 'Add Element Title & Subtitle?', 'directorist' ),
				'default'   => 'yes',
			),
			array(
				'type'      => Controls_Manager::CHOOSE,
				'id'        => 'title_subtitle_alignment',
				'label'     => __( 'Title/Subtitle Alignment', 'directorist' ),
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'directorist' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'directorist' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'directorist' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .directorist-search-top__title' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .directorist-search-top__subtitle' => 'text-align: {{VALUE}}',
				),
				'condition' => array( 'show_subtitle' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::TEXTAREA,
				'id'        => 'title',
				'label'     => __( 'Search Form Title', 'directorist' ),
				'default'   => __( 'Search here', 'directorist' ),
				'condition' => array( 'show_subtitle' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::TEXTAREA,
				'id'        => 'subtitle',
				'label'     => __( 'Search Form Subtitle', 'directorist' ),
				'default'   => __( 'Find the best match of your interest', 'directorist' ),
				'condition' => array( 'show_subtitle' => array( 'yes' ) ),
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
				'type'      => Controls_Manager::TEXT,
				'id'        => 'search_btn_text',
				'label'     => __( 'Search Button Label', 'directorist' ),
				'default'   => __( 'Search Listing', 'directorist' ),
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'show_more_filter_btn',
				'label'     => __( 'Show More Search Field?', 'directorist' ),
				'default'   => 'yes',
			),
			array(
				'type'      => Controls_Manager::TEXT,
				'id'        => 'more_filter_btn_text',
				'label'     => __( 'More Search Field Button Label', 'directorist' ),
				'default'   => __( 'More Filters', 'directorist' ),
				'condition' => array( 'show_more_filter_btn' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'more_filter_reset_btn',
				'label'     => __( 'Show More Field Reset Button?', 'directorist' ),
				'default'   => 'yes',
				'condition' => array( 'show_more_filter_btn' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::TEXT,
				'id'        => 'more_filter_reset_btn_text',
				'label'     => __( 'More Field Reset Button Label', 'directorist' ),
				'default'   => __( 'Reset Filters', 'directorist' ),
				'condition' => array( 'more_filter_reset_btn' => 'yes', 'show_more_filter_btn' => 'yes' ),
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'more_filter_search_btn',
				'label'     => __( 'Show More Field Search Button?', 'directorist' ),
				'default'   => 'yes',
				'condition' => array( 'show_more_filter_btn' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::TEXT,
				'id'        => 'more_filter_search_btn_text',
				'label'     => __( 'More Field Search Button Label', 'directorist' ),
				'default'   => __( 'Apply Filters', 'directorist' ),
				'condition' => array( 'more_filter_search_btn' => 'yes', 'show_more_filter_btn' => 'yes' ),
			),
			array(
				'type'    => Controls_Manager::SELECT,
				'id'      => 'more_filter',
				'label'   => __( 'More Filter By', 'directorist' ),
				'options' => array(
                    'overlapping' => __('Overlapping', 'directorist'),
                    'sliding'     => __('Sliding', 'directorist'),
                    'always_open' => __('Always Open', 'directorist')
				),
				'default' => 'overlapping',
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'user',
				'label'     => __( 'Show only for logged in user?', 'directorist' ),
				'default'   => 'no',
			),
			array(
				'mode' => 'section_end',
			),
			array(
				'mode'  => 'section_start',
				'id'    => 'sec_style',
				'tab'   => Controls_Manager::TAB_STYLE,
				'label' => __( 'Color', 'directorist' ),
				'condition' => array( 'show_subtitle' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::COLOR,
				'id'        => 'title_color',
				'label'     => __( 'Title', 'directorist' ),
				'default'   => '#51526e',
				'selectors' => array( '{{WRAPPER}} .directorist-search-top__title' => 'color: {{VALUE}}' ),
				'condition' => array( 'show_subtitle' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::COLOR,
				'id'        => 'subtitle_color',
				'label'     => __( 'Subtitle', 'directorist' ),
				'default'   => '#51526e',
				'selectors' => array( '{{WRAPPER}} .directorist-search-top__subtitle' => 'color: {{VALUE}}' ),
				'condition' => array( 'show_subtitle' => array( 'yes' ) ),
			),
			array(
				'mode' => 'section_end',
			),
			array(
				'mode'  => 'section_start',
				'id'    => 'sec_style_type',
				'tab'   => Controls_Manager::TAB_STYLE,
				'label' => __( 'Typography', 'directorist' ),
				'condition' => array( 'show_subtitle' => array( 'yes' ) ),
			),
			array(
				'mode'     => 'group',
				'type'     => \Elementor\Group_Control_Typography::get_type(),
				'id'       => 'title_typo',
				'label'    => __( 'Title', 'directorist' ),
				'selector' => '{{WRAPPER}} .directorist-search-top__title',
			),
			array(
				'mode'     => 'group',
				'type'     => \Elementor\Group_Control_Typography::get_type(),
				'id'       => 'subtitle_typo',
				'label'    => __( 'Subtitle', 'directorist' ),
				'selector' => '{{WRAPPER}} .directorist-search-top__subtitle',
			),
			array(
				'mode' => 'section_end',
			),
		);
		return $fields;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$atts = array(
			'show_title_subtitle'   => $settings['show_subtitle'],
			'search_bar_title'      => $settings['title'],
			'search_bar_sub_title'  => $settings['subtitle'],
			'search_button_text'    => $settings['search_btn_text'],
			'more_filters_button'   => $settings['show_more_filter_btn'],
			'more_filters_text'     => $settings['more_filter_btn_text'],
			'reset_filters_button'  => $settings['more_filter_reset_btn'],
			'apply_filters_button'  => $settings['more_filter_search_btn'],
			'reset_filters_text'    => $settings['more_filter_reset_btn_text'],
			'apply_filters_text'    => $settings['more_filter_search_btn_text'],
			'more_filters_display'  => $settings['more_filter'],
			'logged_in_user_only'   => $settings['user'] ? $settings['user'] : 'no',
		);

		if ( Helper::multi_directory_enabled() ) {
			if ( $settings['type'] ) {
				$atts['directory_type'] = implode( ',', $settings['type'] );
			}
			if ( $settings['default_type'] ) {
				$atts['default_directory_type'] = $settings['default_type'];
			}
		}

		$this->az_run_shortcode( 'directorist_search_listing', $atts );
	}
}