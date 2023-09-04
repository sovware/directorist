<?php
/**
 * Directorist Tagline Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Tagline_Field extends Text_Field {

	public $type = 'tagline';
	
	public function get_builder_label() : string {
		return esc_html_x( 'Tagline', 'Builder field label', 'directorist' );
	}

	public function get_builder_icon() : string {
		return 'uil uil-text-fields';
	}

	public function get_builder_fields( $directory_manager ) : array {
		return array(
			'type' => [
				'type'  => 'hidden',
				'value' => 'text',
			],
			'field_key' => [
				'type'   => 'hidden',
				'value'  => 'tagline',
				'rules' => [
					'unique' => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Tagline',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => '',
			],
			'required' => [
				'type'  => 'toggle',
				'label'  => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label'  => __( 'Administrative Only', 'directorist' ),
				'value' => false,
			],
		);
	}
}

Fields::register( new Tagline_Field() );
