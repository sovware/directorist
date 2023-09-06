<?php
/**
 * Directorist File Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class File_Field extends Base_Field {

	public $type = 'file';

	public function validate( $posted_data ) {
		return true;
	}

	public function get_file_types() {
		return $this->file_type;
	}

	public function get_file_size() {
		return wp_convert_hr_to_bytes( $this->file_size );
	}
}

Fields::register( new File_Field() );
