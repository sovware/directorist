<?php
/**
 * @author AazzTech
 */

namespace AazzTech\Directorist\Elementor;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Author_Profile extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->az_name = __( 'User Profile', 'directorist' );
		$this->az_base = 'directorist_author_profile';
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

		$shortcode = sprintf( '[directorist_author_profile logged_in_user_only="%1$s" ]', esc_attr( $user ) );

		echo do_shortcode( $shortcode );
	}
}