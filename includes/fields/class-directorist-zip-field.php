<?php
/**
 * Directorist Zip Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Zip_Field extends Text_Field {

	public $type = 'zip';

	public function get_builder_label() : string {
		return esc_html_x( 'Zip or Post Code', 'Builder field label', 'directorist' );
	}

	public function get_builder_icon() : string {
		return 'uil uil-map-pin';
	}

	public function get_builder_fields( $directory_manager ) : array {
		return array(
			'type' => [
				'type'  => 'hidden',
				'value' => 'text',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'zip',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Zip or Post Code',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => '',
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Administrative Only', 'directorist' ),
				'value' => false,
			],
		);
	}
}

Fields::register( new Zip_Field() );
