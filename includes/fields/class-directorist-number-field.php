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
		$value     = $this->get_value( $posted_data );
		$min_value = $this->__get('min_value');
		$max_value = $this->__get('max_value');
		
		if ( ! is_numeric( $value ) ) {
			$this->add_error( __( 'Invalid number.', 'directorist' ) );

			return false;
		}

		if ( isset( $min_value, $max_value ) && ( $min_value > $value || ( $max_value > 0 && $max_value < $value ) ) ) {
			$error_message = sprintf( __( 'Value should be between %d and %d', 'directorist' ), $min_value, $max_value );
			$this->add_error( $error_message );

			return false;
		}

		return true;
	}

	public function sanitize( $posted_data ) {
		return round( (float) $this->get_value( $posted_data ), 2 );
	}
}

Fields::register( new Number_Field() );
