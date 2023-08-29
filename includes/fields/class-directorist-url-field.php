<?php
/**
 * Directorist Url Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Url_Field extends Base_Field {

	public $type = 'url';

	public function validate( $value ) {
		return ( $this->is_required() && wp_http_validate_url( $this->sanitize( $value ) ) );
	}

	public function sanitize( $value ) {
		return sanitize_url( $value );
	}

	public function get_builder_label() : string {
		return esc_html_x( 'URL', 'Builder field label', 'directorist' );
	}

	public function get_builder_icon() : string {
		return 'uil uil-link-add';
	}

	public function get_builder_fields( $directory_manager ) : array {
		return array(
			'type' => array(
				'type'  => 'hidden',
				'value' => 'text',
			),
			'field_key' => array(
				'type'  => 'hidden',
				'value' => 'custom-url',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			),
			'label' => array(
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'URL',
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
			'target' => [
				'type'  => 'toggle',
				'label' => __( 'Open in new tab', 'directorist' ),
				'value' => false,
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

Fields::register( new Url_Field() );
