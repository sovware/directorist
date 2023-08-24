<?php
/**
 * Directorist Text Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// "select" => [
//     "type" => "select",
//     "label" => "Select",
//     "field_key" => "custom-select",
//     "options" => "",
//     "description" => "",
//     "required" => "",
//     "only_for_admin" => "",
//     "assign_to" => "form",
//     "category" => "",
//     "widget_group" => "custom",
//     "widget_name" => "select",
//     "widget_key" => "select",
//   ],
class Select_Field extends Base_Field {

	public static function get_type() : string {
		return 'select';
	}

	public function get_options() {
		return $this->get_prop( 'options', array() );
	}

	public function validate( $value ) {
		$options = $this->get_options();

		if ( $this->is_required() && ( empty( $options ) || ! is_array( $options ) ) ) {
			return false;
		}

		$values = array_unique( wp_list_pluck( $options, 'option_value' ) );

		return in_array( $value, $values, true );
	}

	public function sanitize( $value ) {
		return sanitize_text_field( $value );
	}

	public function display( array $attributes = array() ) : void {

	}
}

// Fields_Repository::register( Text_Field::get_type(), new Text_Field() );
