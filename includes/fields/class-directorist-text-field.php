<?php
/**
 * Directorist Text Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Text_Field extends Base_Field {

	public $type = 'text';

	public function sanitize( $posted_data ) {
		return sanitize_text_field( $this->get_value( $posted_data ) );
	}
}

Fields::register( new Text_Field() );
