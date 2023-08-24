<?php
/**
 * Directorist Text Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// "text" => [
//     "type" => "text",
//     "label" => "Text",
//     "field_key" => "custom-text",
//     "placeholder" => "",
//     "description" => "",
//     "required" => "",
//     "only_for_admin" => "",
//     "assign_to" => "form",
//     "category" => "",
//     "widget_group" => "custom",
//     "widget_name" => "text",
//     "widget_key" => "text",
//   ],
class Text_Field extends Base_Field {

	public static function get_type() : string {
		return 'text';
	}

	public function validate( $value ) {
		return ( $this->is_required() && ! empty( $value ) );
	}

	public function sanitize( $value ) {
		return sanitize_text_field( $value );
	}

	public function display( array $attributes = array() ) : void {

	}
}

// Fields_Repository::register( Text_Field::get_type(), new Text_Field() );
