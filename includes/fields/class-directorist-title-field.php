<?php
/**
 * Directorist Text Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// "title" => [
//     "widget_group" => "preset",
//     "widget_name" => "title",
//     "type" => "text",
//     "field_key" => "listing_title",
//     "required" => "1",
//     "label" => "Title",
//     "placeholder" => "",
//     "widget_key" => "title",
//   ],
class Title_Field extends Text_Field {

	public static function get_type() : string {
		return 'title';
	}

	public function display( array $attributes = array() ) : void {

	}
}

// Fields_Repository::register( Text_Field::get_type(), new Text_Field() );
