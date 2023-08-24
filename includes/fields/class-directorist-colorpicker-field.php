<?php
/**
 * Directorist Text Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// "color_picker" => [
//     "type" => "color",
//     "label" => "Color",
//     "field_key" => "custom-color-picker",
//     "description" => "",
//     "required" => "",
//     "only_for_admin" => "",
//     "assign_to" => "form",
//     "category" => "",
//     "widget_group" => "custom",
//     "widget_name" => "color_picker",
//     "widget_key" => "color_picker",
//   ],
class ColorPicker_Field extends Base_Field {

	public static function get_type() : string {
		return 'color';
	}

	public function validate( $value ) {

	}

	public function sanitize( $value ) {

	}

	public function display( array $attributes = array() ) : void {

	}
}

// Fields_Repository::register( Text_Field::get_type(), new Text_Field() );
