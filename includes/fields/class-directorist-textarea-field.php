<?php
/**
 * Directorist Textarea Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Textarea_Field extends Base_Field {

	public $type = 'textarea';

	public function validate( $posted_data ) {
		$value = $this->get_value( $posted_data );

		if ( $this->is_required() && $value === '' ) {
			$this->add_error( __( 'This field is required.', 'directorist' ) );

			return false;
		}

		return true;
	}

	public function get_value( $posted_data ) {
		return (string) directorist_get_var( $posted_data[ $this->get_key() ], '' );
	}

	public function sanitize( $posted_data ) {
		return sanitize_textarea_field( $this->get_value( $posted_data ) );
	}
	
	public function get_builder_label() : string {
		return esc_html_x( 'Textarea', 'Builder field label', 'directorist' );
	}

	public function get_builder_icon() : string {
		return 'uil uil-text-fields';
	}

	public function get_builder_fields( $directory_manager ) : array {
		return array(
			'type' => array(
				'type'  => 'hidden',
				'value' => 'textarea',
			),
			'field_key' => array(
				'type'  => 'hidden',
				'value' => 'custom-textarea',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			),
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Textarea',
			],
			'description' => [
				'type'  => 'text',
				'label' => __( 'Description', 'directorist' ),
				'value' => '',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => '',
			],
			'rows' => [
				'type'  => 'number',
				'label' => __( 'Rows', 'directorist' ),
				'value' => 8,
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

Fields::register( new Textarea_Field() );
