<?php
/**
 * Directorist Text Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// /"url" => [
//     "type" => "text",
//     "label" => "URL",
//     "field_key" => "custom-url",
//     "placeholder" => "",
//     "target" => "",
//     "description" => "",
//     "required" => "",
//     "only_for_admin" => "",
//     "assign_to" => "form",
//     "category" => "",
//     "widget_group" => "custom",
//     "widget_name" => "url",
//     "widget_key" => "url",
//   ],
class Url_Field extends Base_Field {

	public static function get_type() : string {
		return 'url';
	}

	public function validate( $value ) {
		return ( $this->is_required() && wp_http_validate_url( $this->sanitize( $value ) ) );
	}

	public function sanitize( $value ) {
		return sanitize_url( $value );
	}

	public function display( array $attributes = array() ) : void {

	}
}

// Fields_Repository::register( Text_Field::get_type(), new Text_Field() );
