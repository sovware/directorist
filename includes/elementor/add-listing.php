<?php
/**
 * @author AazzTech
 */

namespace AazzTech\Directorist\Elementor;

use Elementor\Controls_Manager;
use Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Add_Listing extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->az_name = __( 'Add Listing Form', 'directorist' );
		$this->az_base = 'directorist_add_listing';
		parent::__construct( $data, $args );
	}

	private function az_listing_types() {
		$directories = directorist_get_directories();

		if ( is_wp_error( $directories ) || empty( $directories ) ) {
			return array();
		}

		return wp_list_pluck( $directories, 'name', 'slug' );
	}

	public function az_fields(){
		$fields = array(
			array(
				'mode'    => 'section_start',
				'id'      => 'sec_general',
				'label'   => __( 'General', 'directorist' ),
			),
			array(
				'type'      => Controls_Manager::HEADING,
				'id'        => 'sec_heading',
				'label'     => __( 'This widget works only in Add Listing page. It has no additional elementor settings.', 'directorist' ),
				'condition' => ! directorist_is_multi_directory_enabled() ? '' : ['nocondition' => true],
			),
			array(
				'type'     => Controls_Manager::SELECT2,
				'id'       => 'type',
				'label'    => __( 'Directory Types', 'directorist' ),
				'multiple' => true,
				'options'  => $this->az_listing_types(),
				'condition' => directorist_is_multi_directory_enabled() ? '' : ['nocondition' => true],
			),
			array(
				'mode' => 'section_end',
			),
		);
		return $fields;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$atts = [];

		if ( directorist_is_multi_directory_enabled() ) {
			if ( $settings['type'] ) {
				$atts['directory_type'] = implode( ',', $settings['type'] );
			}
		}

		$this->az_run_shortcode( 'directorist_add_listing', $atts );
	}
}