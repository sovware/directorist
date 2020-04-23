<?php
/**
 * @author AazzTech
 */

namespace AazzTech\Directorist\Elementor;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_User_Dashboard extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->az_name = __( 'Dashboard', 'directorist' );
		$this->az_base = 'directorist_user_dashboard';
		parent::__construct( $data, $args );
	}

	protected function render() {

		$shortcode = '[directorist_user_dashboard]';

		echo do_shortcode( $shortcode );
	}
}