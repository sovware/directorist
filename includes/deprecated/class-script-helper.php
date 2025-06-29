<?php
/**
 * This class is kept to avoid PHP errors
 * on method calls.
 *
 * @since      7.0
 * @deprecated 7.3.0
 */

namespace Directorist;

class Script_Helper {

	public function __call( $name, $arguments ) {
		_deprecated_function( esc_html( $name ), '7.3' );
		return array();
	}

	public static function __callStatic( $name, $arguments ) {
		_deprecated_function( esc_html( $name ), '7.3' );
		return array();
	}
}
