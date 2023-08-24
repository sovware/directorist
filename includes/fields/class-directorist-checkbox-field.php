<?php
/**
 * Directorist Text Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// "checkbox" => [
//     "type" => "checkbox",
//     "label" => "Checkbox",
//     "field_key" => "custom-checkbox",
//     "options" => "",
//     "description" => "",
//     "required" => "",
//     "only_for_admin" => "",
//     "assign_to" => "form",
//     "category" => "",
//     "widget_group" => "custom",
//     "widget_name" => "checkbox",
//     "widget_key" => "checkbox",
//   ],
class Checkbox_Field extends Base_Field {

	public static function get_type() : string {
		return 'checkbox';
	}

	public function get_options() {
		return $this->get_prop( 'options', array() );
	}

	public function validate( $value ) {
		if ( $this->is_required() && ( empty( $value ) || ! is_array( $value ) ) ) {
			return false;
		}

		$options = $this->get_options();
		$options = array_unique( wp_list_pluck( $options, 'option_value' ) );

		return ( count( array_intersect( $value, $options ) ) === count( $value ) );
	}

	public function sanitize( $value ) {
		return directorist_clean( $value );
	}

	public function display( array $attributes = array() ) : void {

	}
}

// Fields_Repository::register( Text_Field::get_type(), new Text_Field() );
