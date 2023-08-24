<?php
/**
 * Directorist Text Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// "date" => [
//     "type" => "date",
//     "label" => "Date",
//     "field_key" => "custom-date",
//     "placeholder" => "",
//     "description" => "",
//     "required" => "",
//     "only_for_admin" => "",
//     "assign_to" => "form",
//     "category" => "",
//     "widget_group" => "custom",
//     "widget_name" => "date",
//     "widget_key" => "date",
//   ],
class Date_Field extends Base_Field {

	public static function get_type() : string {
		return 'date';
	}

	public function validate( $value ) {

	}

	public function sanitize( $value ) {

	}

	public function display( array $attributes = array() ) : void {

	}
}

// Fields_Repository::register( Text_Field::get_type(), new Text_Field() );
