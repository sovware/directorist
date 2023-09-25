<?php
/**
 * Directorist Date Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Date_Field extends Base_Field {

	public $type = 'date';

	public function validate( $posted_data ) {
		$value = $this->get_value( $posted_data );

		if ( strtotime( $value ) === false ) {
			$this->add_error( sprintf( __( '[%s] Invalid date.', 'directorist' ), $value ) );

			return false;
		}

		return true;
	}

	public function sanitize( $posted_data ) {
		return sanitize_text_field( $this->get_value( $posted_data ) );
	}
}

Fields::register( new Date_Field() );
