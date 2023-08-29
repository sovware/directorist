<?php
/**
 * Directorist Text Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Title_Field extends Text_Field {

	public static function get_type() : string {
		return 'title';
	}
	
}

// Fields_Repository::register( Text_Field::get_type(), new Text_Field() );
