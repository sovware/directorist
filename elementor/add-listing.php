<?php
/**
 * @author AazzTech
 */

namespace AazzTech\Directorist\Elementor;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Add_Listing extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->az_name = __( 'Listing Form', 'directorist' );
		$this->az_base = 'directorist_add_listing';
		parent::__construct( $data, $args );
	}

	protected function render() {

		$shortcode = '[directorist_add_listing]';

		echo do_shortcode( $shortcode );
	}
}