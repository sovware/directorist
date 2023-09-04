<?php
/**
 * Directorist Time Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Time_Field extends Base_Field {

	public $type = 'time';

	public function validate( $posted_data ) {
		$value = $this->get_value( $posted_data );
		
		if ( ! empty( $value ) && date( 'H:i', strtotime( 'today ' . $value ) ) !== $value ) {
			$this->add_error( __( 'Invalid time.', 'directorist' ) );

			return false;
		}

		return true;
	}

	public function get_builder_label() : string {
		return esc_html_x( 'Time', 'Builder field label', 'directorist' );
	}

	public function get_builder_icon() : string {
		return 'uil uil-clock';
	}

	public function get_builder_fields( $directory_manager ) : array {
		return array(
			'type' => array(
				'type'  => 'hidden',
				'value' => 'time',
			),
			'field_key' => array(
				'type'  => 'hidden',
				'value' => 'custom-time',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			),
			'label' => array(
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Time',
			),
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

Fields::register( new Time_Field() );
