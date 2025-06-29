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
}

Fields::register( new Textarea_Field() );
