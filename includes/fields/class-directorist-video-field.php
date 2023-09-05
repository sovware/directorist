<?php
/**
 * Directorist Video Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Video_Field extends Base_Field {

	public $type = 'video';

	public function validate( $posted_data ) {
		$value = $this->get_value( $posted_data );

		if ( ! empty( $value ) && ! wp_http_validate_url( $value ) ) {
			$this->add_error( __( 'Invalid URL.', 'directorist' ) );
		}

		if ( ! directorist_validate_youtube_vimeo_url( $value ) ) {
			$this->add_error( __( 'Vimeo and Youtube video allowed only.', 'directorist' ) );
		}

		if ( $this->has_error() ) {
			return false;
		}

		return true;
	}

	public function sanitize( $posted_data ) {
		return sanitize_url( $this->get_value( $posted_data ) );
	}

	public function get_builder_label() : string {
		return esc_html_x( 'Video', 'Builder field label', 'directorist' );
	}

	public function get_builder_icon() : string {
		return 'uil uil-video';
	}

	public function get_builder_fields( $directory_manager ) : array {
		return array(
			'type' => [
				'type'  => 'hidden',
				'value' => 'text',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'videourl',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Video',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => 'Only YouTube & Vimeo URLs.',
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

Fields::register( new Video_Field() );
