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
		$options = $this->options;

		if ( ! is_array( $options ) ) {
			return array();
		}

		return wp_list_pluck( $options, 'option_value' );
	}

	public function validate( $posted_data ) {
		$value = $this->get_value( $posted_data );

		if ( $this->is_required() && $value === '' ) {
			$this->add_error( __( 'This field is required.', 'directorist' ) );

			return false;
		}

		if ( $value !== '' && ! in_array( $value, $this->get_options(), true ) ) {
			$this->add_error( __( 'Invalid selection.', 'directorist' ) );

			return false;
		}

		return true;
	}

	protected function get_value( $posted_data ) {
		return (string) directorist_get_var( $posted_data[ $this->get_key() ], '' );
	}

	public function sanitize( $posted_data ) {
		return sanitize_text_field( $this->get_value( $posted_data ) );
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
