<?php
/**
 * Directorist Text Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// "file" => [
//     "type" => "file",
//     "label" => "File Upload",
//     "field_key" => "custom-file",
//     "file_type" => "image",
//     "file_size" => "2mb",
//     "description" => "",
//     "required" => "",
//     "only_for_admin" => "",
//     "widget_group" => "custom",
//     "widget_name" => "file",
//     "widget_key" => "file",
//   ],
class File_Field extends Base_Field {

	public static function get_type() : string {
		return 'file';
	}

	public function validate( $value ) {

	}

	public function sanitize( $value ) {

	}

	public function get_file_types() {
		return $this->get_prop( 'file_type', 'image' );
	}

	public function get_file_size() {
		return wp_convert_hr_to_bytes( $this->get_prop( 'file_size' ) );
	}

	public function display( array $attributes = array() ) : void {

	}
}

// Fields_Repository::register( Text_Field::get_type(), new Text_Field() );
