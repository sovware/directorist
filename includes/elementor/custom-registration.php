<?php
/**
 * @author AazzTech
 */

namespace AazzTech\Directorist\Elementor;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Custom_Registration extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->az_name = __( 'Registration', 'directorist' );
		$this->az_base = 'directorist_custom_registration';
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
				'type'      => Controls_Manager::HEADING,
				'id'        => 'sec_heading',
				'label'     => __( 'This widget works only in Registration page. It has no additional elementor settings.', 'directorist' ),
			),
			array(
				'mode' => 'section_end',
			),
		);
		return $fields;
	}

	protected function render() {

		$shortcode = '[directorist_custom_registration]';

		echo do_shortcode( $shortcode );
	}
}