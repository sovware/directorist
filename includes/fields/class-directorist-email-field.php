<?php
/**
 * Directorist Email Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Email_Field extends Base_Field {

	public $type = 'email';

	public function validate( $posted_data ) {
		$value = $this->get_value( $posted_data );

		if ( ! is_email( $value ) ) {
			$this->add_error( __( 'Invalid email address.', 'directorist' ) );

			return false;
		}

		return true;
	}

	public function sanitize( $posted_data ) {
		return sanitize_email( $this->get_value( $posted_data ) );
	}
}

Fields::register( new Email_Field() );
