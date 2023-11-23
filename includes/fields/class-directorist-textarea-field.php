<?php
/**
 * Directorist Textarea Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Textarea_Field extends Base_Field {

	public $type = 'textarea';

	public function sanitize( $posted_data ) {
		return sanitize_textarea_field( $this->get_value( $posted_data ) );
	}

	public function validate( $posted_data ) {
		$chars     = $this->get_value( $posted_data );
		$max_limit = (int) $this->__get( 'max' );
		$length    = function_exists( 'mb_strlen' ) ? mb_strlen( $chars ) : strlen( $chars );

		if ( $max_limit != -1 && $max_limit > 0 && $length > $max_limit ) {
			$this->add_error( sprintf( _n( 'Only %1$s character is allowed.', 'Only %1$s characters are allowed.', $max_limit, 'directorist' ), $max_limit ) );
			return false;
		}

		return true;
	}
}

Fields::register( new Textarea_Field() );
