<?php
/**
 * Directorist Checkbox Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Checkbox_Field extends Base_Field {

	public $type = 'checkbox';

	public function get_options() {
		$options = $this->options;

		if ( ! is_array( $options ) ) {
			return array();
		}

		return wp_list_pluck( $options, 'option_value' );
	}

	public function validate( $posted_data ) {
		$value = $this->get_value( $posted_data );

		if ( $this->is_required() && empty( $value ) ) {
			$this->add_error( __( 'This field is required.', 'directorist' ) );

			return false;
		}

		if ( ( count( array_intersect( $value, $this->get_options() ) ) !== count( $value ) ) ) {
			$this->add_error( __( 'Invalid selection.', 'directorist' ) );

			return false;
		}

		return true;
	}

	public function get_value( $posted_data ) {
		return (array) directorist_get_var( $posted_data[ $this->get_key() ], array() );
	}

	public function sanitize( $posted_data ) {
		return directorist_clean( $posted_data );
	}

	public function get_builder_label() : string {
		return esc_html_x( 'Checkbox', 'Builder field label', 'directorist' );
	}

	public function get_builder_icon() : string {
		return 'uil uil-check-square';
	}

	public function get_builder_fields( $directory_manager ) : array {
		return array(
			'type' => array(
				'type'  => 'hidden',
				'value' => 'checkbox',
			),
			'field_key' => array(
				'type'  => 'hidden',
				'value' => 'custom-checkbox',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			),
			'label' => array(
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Checkbox',
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

Fields::register( new Checkbox_Field() );
