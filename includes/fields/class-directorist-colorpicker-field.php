<?php
/**
 * Directorist Color Picker Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Color_Picker_Field extends Base_Field {

	public $type = 'color_picker';

	public function validate( $posted_data ) {
		$value = $this->get_value( $posted_data );

		if ( sanitize_hex_color( $value ) !== $value ) {
			$this->add_error( sprintf( __( '[%s] Invalid color code.', 'directorist' ), $value ) );

			return false;
		}

		return true;
	}

	public function sanitize( $posted_data ) {
		return sanitize_hex_color( $this->get_value( $posted_data ) );
	}
}

Fields::register( new Color_Picker_Field() );
