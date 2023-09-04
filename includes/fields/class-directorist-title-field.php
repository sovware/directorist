<?php
/**
 * Directorist Title Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Title_Field extends Text_Field {

	public $type = 'title';

	protected $can_trash = false;
	
	public function get_builder_label() : string {
		return esc_html_x( 'Title', 'Builder field label', 'directorist' );
	}

	public function get_builder_icon() : string {
		return 'las la-text-height';
	}

	public function get_builder_fields( $directory_manager ) : array {
		return array(
			'type' => [
				'type'  => 'hidden',
				'value' => 'text',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'listing_title',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Title',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => '',
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => true,
			],
		);
	}
}

Fields::register( new Title_Field() );
