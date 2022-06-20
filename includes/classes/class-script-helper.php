<?php
namespace Directorist;

class Script_Helper {

	public function __call( $name, $arguments ) {
		_deprecated_function( $name, '7.3' );
		return array();
	}

	public static function __callStatic( $name, $arguments ) {
		_deprecated_function( $name, '7.3' );
		return array();
	}
}
