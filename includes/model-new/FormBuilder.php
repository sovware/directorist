<?php
/**
 * @author AazzTech
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Directorist_Form_Builder {

	public static function render_section( $data ) {
		$fields = $data['fields'];
		$fields_html = '';
		foreach ( $data['fields'] as $field ) {
			$fields_html .= self::field_html( $data );
		}

	}

	public static function field_html( $data ) {
		switch ($data['type']) {
			case 'text':
			return atbdp_return_shortcode_template( 'forms/fields/text', compact( $data ) );
			break;
			
			default:
			return '';
			break;
		}
	}
}