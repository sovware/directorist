<?php
/**
 * Directorist Color Picker Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Color_Picker_Field extends Base_Field {

	public $type = 'color_picker';

	public function validate( $posted_data ) {
		$value = $this->get_value( $posted_data );

		if ( ! empty( $value ) && sanitize_hex_color( $value ) !== $value ) {
			$this->add_error( __( 'Invalid color code.', 'directorist' ) );

			return false;
		}

		return true;
	}

	public function sanitize( $posted_data ) {
		return sanitize_hex_color( $this->get_value( $posted_data ) );
	}

	public function get_builder_label() : string {
		return esc_html_x( 'Color Picker', 'Builder field label', 'directorist' );
	}

	public function get_builder_icon() : string {
		return 'uil uil-palette';
	}

	public function get_builder_fields( $directory_manager ) : array {
		return array(
			'type' => array(
				'type'  => 'hidden',
				'value' => 'color',
			),
			'field_key' => array(
				'type'  => 'hidden',
				'value' => 'custom-color-picker',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			),
			'label' => array(
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Color',
			),
			'description' => [
				'type'  => 'text',
				'label' => __( 'Description', 'directorist' ),
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
			'assign_to' => $directory_manager->get_assign_to_field(),
			'category'  => $directory_manager->get_category_select_field( [
				'show_if' => [
					'where'      => 'self.assign_to',
					'conditions' => [
						[
							'key'     => 'value',
							'compare' => '=',
							'value'   => 'category'
						],
					],
				],
			] ),
		);
	}
}

Fields::register( new Color_Picker_Field() );
