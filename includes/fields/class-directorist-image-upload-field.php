<?php
/**
 * Directorist Image Upload Field class.
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Image_Upload_Field extends Base_Field {

	public $type = 'image_upload';

	public function get_value( $posted_data ) {
		if ( empty( $_FILES[ $this->get_key() ] ) ) {
			return array();
		}

		return directorist_clean( wp_unslash( $_FILES[ $this->get_key() ] ) );
	}

	public function validate( $posted_data ) {
		$files = $this->get_value( $posted_data );

		if ( count( $files['name'] ) > $this->get_total_upload_limit() ) {
			$this->add_error( sprintf(
				_n( '%s image allowed only.', '%s images allowed only.', $this->get_total_upload_limit(), 'directorist' ),
				$this->get_total_upload_limit()
			) );

			return false;
		}

		$total_size = 0;

		foreach ( $files['name'] as $key => $value ) {
			if ( strpos( $files['type'][ $key ], 'image' ) === false ) {
				$this->add_error( sprintf(
					__( '[%1$s] Only image allowed.', 'directorist' ),
					$files['name'][ $key ]
				) );

				continue;
			}

			if ( $files['size'][ $key ] > $this->get_per_image_upload_size() ) {
				$this->add_error( sprintf(
					__( '[%1$s] Size exceeded, %2$s is allowed only.', 'directorist' ),
					$files['name'][ $key ],
					size_format( $this->get_per_image_upload_size() )
				) );
			}

			$total_size += $files['size'][ $key ];

			if ( $total_size > $this->get_total_upload_size() ) {
				$this->add_error( sprintf(
					__( 'Total upload size (%s) exceeded.', 'directorist' ),
					size_format( $this->get_total_upload_size() )
				) );

				break;
			}
		}

		if ( $this->has_error() ) {
			return false;
		}

		return true;
	}

	public function get_total_upload_limit() {
		return absint( $this->max_image_limit );
	}

	public function get_total_upload_size() {
		$size_in_mb = round( (float) $this->max_total_image_limit, 2 );
		$unit       = 'MB';

		if ( $size_in_mb < 1 ) {
			$unit = 'KB';
			$size_in_mb = KB_IN_BYTES * $size_in_mb;
		}

		return ( $size_in_mb > 0 ? wp_convert_hr_to_bytes( $size_in_mb . $unit ) : wp_max_upload_size() ); 
	}

	public function get_per_image_upload_size() {
		$size_in_mb = round( (float) $this->max_per_image_limit, 2 );
		$unit       = 'MB';

		if ( $size_in_mb < 1 ) {
			$unit = 'KB';
			$size_in_mb = KB_IN_BYTES * $size_in_mb;
		}

		return ( $size_in_mb > 0 ? wp_convert_hr_to_bytes( $size_in_mb . $unit ) : wp_max_upload_size() ); 
	}
}

Fields::register( new Image_Upload_Field() );
