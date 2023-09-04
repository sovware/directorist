<?php
/**
 * Directorist Email Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Email_Field extends Base_Field {

	public $type = 'email';

	public function validate( $posted_data ) {
		$value = $this->get_value( $posted_data );

		if ( $this->is_required() && $value === '' ) {
			$this->add_error( __( 'This field is required.', 'directorist' ) );

			return false;
		}

		if ( ! empty( $value ) && ! is_email( $value ) ) {
			$this->add_error( __( 'Invalid email address.', 'directorist' ) );

			return false;
		}

		return true;
	}

	public function get_value( $posted_data ) {
		return (string) directorist_get_var( $posted_data[ $this->get_key() ], '' );
	}

	public function sanitize( $posted_data ) {
		return sanitize_email( $this->get_value( $posted_data ) );
	}

	public function get_builder_label() : string {
		return esc_html_x( 'Email', 'Builder field label', 'directorist' );
	}

	public function get_builder_icon() : string {
		return 'uil uil-envelope';
	}

	public function get_builder_fields( $directory_manager ) : array {
		return array(
			'type' => [
				'type'  => 'hidden',
				'value' => 'email',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'email',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Email',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist'),
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
		);
	}
}

Fields::register( new Email_Field() );
