<?php
/**
 * Directorist View Count Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class View_Count_Field extends Number_Field {

	public $type = 'view_count';

	public function sanitize( $posted_data ) {
		return absint( $this->get_value( $posted_data ) );
	}
}

Fields::register( new View_Count_Field() );
