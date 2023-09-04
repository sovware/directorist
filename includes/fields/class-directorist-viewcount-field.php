<?php
/**
 * Directorist View Count Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class View_Count_Field extends Number_Field {

	public $type = 'view_count';

	public function sanitize( $posted_data ) {
		return absint( $this->get_value( $posted_data ) );
	}

	public function get_builder_label() : string {
		return esc_html_x( 'View Count', 'Builder field label', 'directorist' );
	}

	public function get_builder_icon() : string {
		return 'uil uil-eye';
	}

	public function get_builder_fields( $directory_manager ) : array {
		return array(
			'type' => [
				'type'  => 'hidden',
				'value' => 'number',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'atbdp_post_views_count',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'View Count',
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
				'value' => true,
			],
		);
	}
}

Fields::register( new View_Count_Field() );
