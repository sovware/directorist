<?php
/**
 * @author AazzTech
 */

namespace AazzTech\Directorist\Elementor;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Search_Listing extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->az_name = __( 'Search Form', 'directorist' );
		$this->az_base = 'directorist_search_listing';
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
				'id'        => 'show_subtitle',
				'label'     => __( 'Add Element Title & Subtitle?', 'directorist' ),
				'default'   => 'yes',
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
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'text_field',
				'label'     => __( 'Show Text Field?', 'directorist' ),
				'default'   => 'yes',
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'category_field',
				'label'     => __( 'Show Category Field?', 'directorist' ),
				'default'   => 'yes',
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'location_field',
				'label'     => __( 'Show Location Field?', 'directorist' ),
				'default'   => 'yes',
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
				'id'        => 'mm_price',
				'label'     => __( 'Show Min - Max Price Field?', 'directorist' ),
				'default'   => 'yes',
				'condition' => array( 'show_more_filter_btn' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'price_range',
				'label'     => __( 'Show Price Range Field?', 'directorist' ),
				'default'   => 'yes',
				'condition' => array( 'show_more_filter_btn' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'rating',
				'label'     => __( 'Show Rating Field?', 'directorist' ),
				'default'   => 'yes',
				'condition' => array( 'show_more_filter_btn' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'tag',
				'label'     => __( 'Show Tag Field?', 'directorist' ),
				'default'   => 'yes',
				'condition' => array( 'show_more_filter_btn' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'open',
				'label'     => __( 'Show Open Now Field?', 'directorist' ),
				'default'   => 'yes',
				'condition' => array( 'show_more_filter_btn' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'custom_field',
				'label'     => __( 'Show Custom Field Field?', 'directorist' ),
				'default'   => 'yes',
				'condition' => array( 'show_more_filter_btn' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'web',
				'label'     => __( 'Show Website Field?', 'directorist' ),
				'default'   => 'yes',
				'condition' => array( 'show_more_filter_btn' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'email',
				'label'     => __( 'Show Email Field?', 'directorist' ),
				'default'   => 'yes',
				'condition' => array( 'show_more_filter_btn' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'phone',
				'label'     => __( 'Show Phone Number Field?', 'directorist' ),
				'default'   => 'yes',
				'condition' => array( 'show_more_filter_btn' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'fax',
				'label'     => __( 'Show Fax Field?', 'directorist' ),
				'default'   => 'yes',
				'condition' => array( 'show_more_filter_btn' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'address',
				'label'     => __( 'Show Address Field?', 'directorist' ),
				'default'   => 'yes',
				'condition' => array( 'show_more_filter_btn' => array( 'yes' ) ),
			),
			array(
				'type'      => Controls_Manager::SWITCHER,
				'id'        => 'zip',
				'label'     => __( 'Show Zip Field?', 'directorist' ),
				'default'   => 'yes',
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
		);
		return $fields;
	}	

	protected function render() {
		$settings = $this->get_settings_for_display();

		$user = $settings['user'] ? $settings['user'] : 'no';

		$shortcode = sprintf( '[directorist_search_listing show_title_subtitle="%1$s" search_bar_title="%2$s" search_bar_sub_title="%3$s" text_field="%4$s" category_field="%5$s" location_field="%6$s" search_button_text="%7$s" more_filters_button="%8$s" more_filters_text="%9$s" price_min_max_field="%10$s" price_range_field="%11$s" rating_field="%12$s" tag_field="%13$s" open_now_field="%14$s" custom_fields="%15$s" website_field="%16$s" email_field="%17$s" phone_field="%18$s" fax="%19$s" address_field="%20$s" zip_code_field="%21$s" reset_filters_button="%22$s" apply_filters_button="%23$s" reset_filters_text="%24$s" apply_filters_text="%25$s" more_filters_display="%26$s" logged_in_user_only="%27$s" ]', 
			esc_attr( $settings['show_subtitle'] ),
			esc_attr( $settings['title'] ),
			esc_attr( $settings['subtitle'] ),
			esc_attr( $settings['text_field'] ),
			esc_attr( $settings['category_field'] ),
			esc_attr( $settings['location_field'] ),
			esc_attr( $settings['search_btn_text'] ),
			esc_attr( $settings['show_more_filter_btn'] ),
			esc_attr( $settings['more_filter_btn_text'] ),
			esc_attr( $settings['mm_price'] ),
			esc_attr( $settings['price_range'] ),
			esc_attr( $settings['rating'] ),
			esc_attr( $settings['tag'] ),
			esc_attr( $settings['open'] ),
			esc_attr( $settings['custom_field'] ),
			esc_attr( $settings['web'] ),
			esc_attr( $settings['email'] ),
			esc_attr( $settings['phone'] ),
			esc_attr( $settings['fax'] ),
			esc_attr( $settings['address'] ),
			esc_attr( $settings['zip'] ),
			esc_attr( $settings['more_filter_reset_btn'] ),
			esc_attr( $settings['more_filter_search_btn'] ),
			esc_attr( $settings['more_filter_reset_btn_text'] ),
			esc_attr( $settings['more_filter_search_btn_text'] ),
			esc_attr( $settings['more_filter'] ),
			esc_attr( $user )
		);

		echo do_shortcode( $shortcode );
	}
}