<?php
/**
 * Directorist Text Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// "time" => [
//     "type" => "time",
//     "label" => "Time",
//     "field_key" => "custom-time",
//     "placeholder" => "",
//     "description" => "",
//     "required" => "",
//     "only_for_admin" => "",
//     "assign_to" => "form",
//     "category" => "",
//     "widget_group" => "custom",
//     "widget_name" => "time",
//     "widget_key" => "time",
//   ],
class Time_Field extends Base_Field {

	public static function get_type() : string {
		return 'time';
	}

	public function validate( $value ) {

	}

	public function sanitize( $value ) {

	}

	public function display( array $attributes = array() ) : void {

	}
}

// Fields_Repository::register( Text_Field::get_type(), new Text_Field() );
