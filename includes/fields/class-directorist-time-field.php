<?php
/**
 * Directorist Time Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Time_Field extends Base_Field {

	public $type = 'time';

	public function validate( $posted_data ) {
		$value = $this->get_value( $posted_data );
		
		if ( date( 'H:i', strtotime( 'today ' . $value ) ) !== $value ) {
			$this->add_error( __( 'Invalid time.', 'directorist' ) );

			return false;
		}

		return true;
	}
}

Fields::register( new Time_Field() );
