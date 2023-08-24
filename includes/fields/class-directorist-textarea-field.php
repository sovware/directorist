<?php
/**
 * Directorist Text Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// "textarea" => [
//     "type" => "textarea",
//     "label" => "Textarea",
//     "field_key" => "custom-textarea",
//     "rows" => "8",
//     "placeholder" => "",
//     "description" => "",
//     "required" => "",
//     "only_for_admin" => "",
//     "assign_to" => "form",
//     "category" => "",
//     "widget_group" => "custom",
//     "widget_name" => "textarea",
//     "widget_key" => "textarea",
//   ],
class Textarea_Field extends Base_Field {

	public static function get_type() : string {
		return 'textarea';
	}

	public function validate( $value ) {
		return ( $this->is_required() && ! empty( $value ) );
	}

	public function sanitize( $value ) {
		return sanitize_textarea_field( $value );
	}

	public function display( array $attributes = array() ) : void {

	}
}

// Fields_Repository::register( Text_Field::get_type(), new Text_Field() );
