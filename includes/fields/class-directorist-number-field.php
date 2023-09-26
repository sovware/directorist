<?php
/**
 * Directorist Number Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Number_Field extends Base_Field {

	public $type = 'number';

	public function validate( $posted_data ) {
		$value = $this->get_value( $posted_data );
		
		if ( ! is_numeric( $value ) ) {
			$this->add_error( __( 'Invalid number.', 'directorist' ) );

			return false;
		}

		return true;
	}

	public function sanitize( $posted_data ) {
		return round( (float) $this->get_value( $posted_data ), 2 );
	}
}

Fields::register( new Number_Field() );
