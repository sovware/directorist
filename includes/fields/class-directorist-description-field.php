<?php
/**
 * Directorist Description Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Description_Field extends Textarea_Field {

	public $type = 'description';

	public function sanitize( $posted_data ) {
		if ( $this->__get( 'type' ) === 'wp_editor' ) {
			return wp_kses_post( $this->get_value( $posted_data ) );
		}

		return parent::sanitize( $posted_data );
	}
}

Fields::register( new Description_Field() );
