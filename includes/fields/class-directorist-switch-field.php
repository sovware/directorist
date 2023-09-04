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

	public function validate( $posted_data ) {
		if ( $this->is_required() && ! isset( $posted_data[ $this->get_key() ] ) ) {
			$this->add_error( __( 'This field is required.', 'directorist' ) );

			return false;
		}

		return true;
	}

	public function sanitize( $posted_data ) {
		return (bool) directorist_get_var( $posted_data[ $this->get_key() ] );
	}

	public function get_builder_label() : string {
		return esc_html_x( 'Switch', 'Builder field label', 'directorist' );
	}

	public function get_builder_icon() : string {
		return 'uil uil-check-square';
	}

	public function get_builder_fields( $directory_manager ) : array {
		return $this->fields;
	}
}

Fields::register( new Switch_Field() );
