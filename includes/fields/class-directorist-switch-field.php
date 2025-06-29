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
}

Fields::register( new Switch_Field() );
