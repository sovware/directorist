<?php
/**
 * @author AazzTech
 */

namespace AazzTech\Directorist\Elementor;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_User_Login extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->az_name = __( 'Login', 'directorist' );
		$this->az_base = 'directorist_user_login';
		parent::__construct( $data, $args );
	}

	protected function render() {

		$shortcode = '[directorist_user_login]';

		echo do_shortcode( $shortcode );
	}
}