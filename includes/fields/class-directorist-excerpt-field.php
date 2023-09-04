<?php
/**
 * Directorist Excerpt Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Excerpt_Field extends Textarea_Field {

	public $type = 'excerpt';
	
	public function get_builder_label() : string {
		return esc_html_x( 'Excerpt', 'Builder field label', 'directorist' );
	}

	public function get_builder_icon() : string {
		return 'uil uil-subject';
	}

	public function get_builder_fields( $directory_manager ) : array {
		return array(
			'type' => [
				'type'  => 'hidden',
				'value' => 'textarea',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'excerpt',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Excerpt',
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

Fields::register( new Excerpt_Field() );
