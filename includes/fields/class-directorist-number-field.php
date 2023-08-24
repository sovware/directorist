<?php
/**
 * Directorist Text Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// "number" => [
//     "type" => "number",
//     "label" => "Number",
//     "field_key" => "custom-number",
//     "placeholder" => "",
//     "description" => "",
//     "required" => "",
//     "only_for_admin" => "",
//     "assign_to" => "form",
//     "category" => "",
//     "widget_group" => "custom",
//     "widget_name" => "number",
//     "widget_key" => "number",
//   ],
class Number_Field extends Base_Field {

	public static function get_type() : string {
		return 'number';
	}

	public function validate( $value ) {
		return ( $this->is_required() && ( is_numeric( $value ) || ( is_string( $value ) && $value !== '' ) ) );
	}

	public function sanitize( $value ) {
		return round( (float) $value, 4 );
	}

	public function display( array $attributes = array() ) : void {

	}
}

// Fields_Repository::register( Text_Field::get_type(), new Text_Field() );
