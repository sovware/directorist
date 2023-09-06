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

		if ( ! wp_http_validate_url( $value ) ) {
			$this->add_error( __( 'Invalid video URL.', 'directorist' ) );
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
}

Fields::register( new Video_Field() );
