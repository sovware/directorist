<?php
/**
 * Directorist Switch Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Switch_Field extends Base_Field {

	public $type = 'switch';

	public function sanitize( $posted_data ) {
		return (bool) $this->get_value( $posted_data );
	}

	public function get_builder_label() : string {
		return esc_html_x( 'Switch', 'Builder field label', 'directorist' );
	}

	public function get_builder_icon() : string {
		return 'uil uil-check-square';
	}
}

Fields::register( new Switch_Field() );
