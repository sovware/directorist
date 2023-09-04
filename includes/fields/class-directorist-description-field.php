<?php
/**
 * Directorist Description Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Description_Field extends Textarea_Field {

	public $type = 'description';
	
	public function get_builder_label() : string {
		return esc_html_x( 'Description', 'Builder field label', 'directorist' );
	}

	public function get_builder_icon() : string {
		return 'las la-align-left';
	}

	public function sanitize( $posted_data ) {
		if ( $this->__get( 'type' ) === 'wp_editor' ) {
			return wp_kses_post( $this->get_value( $posted_data ) );
		}

		return parent::sanitize( $posted_data );
	}

	public function get_builder_fields( $directory_manager ) : array {
		return array(
			'type' => [
				'type'    => 'select',
				'label'   => _x( 'Type', 'Builder field type', 'directorist' ),
				'value'   => 'wp_editor',
				'options' => [
					[
						'label' => __( 'Textarea', 'directorist' ),
						'value' => 'textarea',
					],
					[
						'label' => __( 'WP Editor', 'directorist' ),
						'value' => 'wp_editor',
					],
				]
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'listing_content',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Description',
			],
			'placeholder' => [
				'type'    => 'text',
				'label'   => __( 'Placeholder', 'directorist' ),
				'value'   => '',
				'show_if' => [
					'where'      => 'self.type',
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => 'textarea'],
					],
				],
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

Fields::register( new Description_Field() );
