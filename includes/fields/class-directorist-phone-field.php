<?php
/**
 * Directorist Phone Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Phone_Field extends Text_Field {

	public $type = 'phone';

	public function get_builder_label() : string {
		return esc_html_x( 'Phone', 'Builder field label', 'directorist' );
	}

	public function get_builder_icon() : string {
		return 'uil uil-phone';
	}

	public function get_builder_fields( $directory_manager ) : array {
		return array(
			'type' => [
				'type'  => 'hidden',
				'value' => 'tel',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'phone',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Phone',
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
			'whatsapp' => [
				'type'  => 'toggle',
				'label' => __( 'Link with WhatsApp', 'directorist' ),
				'value' => false,
			],
		);
	}
}

Fields::register( new Phone_Field() );
