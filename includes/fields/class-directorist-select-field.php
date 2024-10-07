<?php
/**
 * Directorist Select Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Select_Field extends Base_Field {

	public $type = 'select';

	public function get_options() {
		$options = $this->options;

		if ( ! is_array( $options ) ) {
			return array();
		}

		return array_map( static function( $option ) {
			return str_replace( '&lt;', '<', $option['option_value'] );
		}, $options );
	}

	public function validate( $posted_data ) {
		$value = $this->get_value( $posted_data );

		if ( ! in_array( $value, $this->get_options(), true ) ) {
			$this->add_error( sprintf( __( '[%s] Invalid value.', 'directorist' ), $value ) );

			return false;
		}

		return true;
	}

	public function sanitize( $posted_data ) {
		return sanitize_text_field( $this->get_value( $posted_data ) );
	}
}

Fields::register( new Select_Field() );
