<?php
/**
 * Directorist Fields Repository class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Fields {

	public static function instance() {

	}

	public function __construct() {
		$this->load_fields();
	}

	public function load_fields() {
		$dir = trailingslashit( ATBDP_INC_DIR . 'fields' );

		require_once $dir . 'class-directorist-base-field.php';
	
		$fields = array(
			'checkbox'    => 'class-directorist-checkbox-field.php',
			'colorpicker' => 'class-directorist-colorpicker-field.php',
			'date'        => 'class-directorist-date-field.php',
			'file'        => 'class-directorist-file-field.php',
			'number'      => 'class-directorist-number-field.php',
			'radio'       => 'class-directorist-radio-field.php',
			'select'      => 'class-directorist-select-field.php',
			'text'        => 'class-directorist-text-field.php',
			'textarea'    => 'class-directorist-textarea-field.php',
			'time'        => 'class-directorist-time-field.php',
			'title'       => 'class-directorist-title-field.php',
			'url'         => 'class-directorist-url-field.php',
		);

		do_action( 'directorist_fields_loaded', $this );
	}


}

return new Fields();
