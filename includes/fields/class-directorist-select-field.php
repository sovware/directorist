<?php
/**
 * Directorist Select Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Select_Field extends Base_Field {

	public $type = 'select';

	public function get_options() {
		return $this->options;
	}

	public function validate( $value ) {
		$options = $this->get_options();

		if ( $this->is_required() && ( empty( $options ) || ! is_array( $options ) ) ) {
			return false;
		}

		$values = array_unique( wp_list_pluck( $options, 'option_value' ) );

		return in_array( $value, $values, true );
	}

	public function sanitize( $value ) {
		return sanitize_text_field( $value );
	}

	public function display( array $attributes = array() ) : void {

	}
	public function get_builder_label() : string {
		return esc_html_x( 'Select', 'Builder field label', 'directorist' );
	}

	public function get_builder_icon() : string {
		return 'uil uil-file-check';
	}

	public function get_builder_fields( $directory_manager ) : array {
		return array(
			'type' => array(
				'type'  => 'hidden',
				'value' => 'select',
			),
			'field_key' => array(
				'type'  => 'hidden',
				'value' => 'custom-select',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			),
			'label' => array(
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Select',
			),
			'description' => [
				'type'  => 'text',
				'label' => __( 'Description', 'directorist' ),
				'value' => '',
			],
			'options' => [
				'type'                 => 'multi-fields',
				'label'                => __( 'Options', 'directorist' ),
				'add-new-button-label' => __( 'Add Option', 'directorist' ),
				'options'              => [
					'option_value' => [
						'type'  => 'text',
						'label' => __( 'Value', 'directorist' ),
						'value' => '',
					],
					'option_label' => [
						'type'  => 'text',
						'label' => __( 'Label', 'directorist' ),
						'value' => '',
					],
				]
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

Fields::register( new Select_Field() );
