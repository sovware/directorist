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
		if ( empty( $posted_data[ $this->get_key() ] ) && empty( $posted_data[ $this->get_key() . '_old' ] ) ) {
			return null;
		}

		$new_images = (array) directorist_get_var( $posted_data[ $this->get_key() ], array() );
		$old_images = (array) directorist_get_var( $posted_data[ $this->get_key() . '_old' ], array() );

		$maybe_old_images = array_filter( $new_images, 'is_numeric' );

		if ( count( $maybe_old_images ) > 0 ) {
			$old_images = array_merge( $old_images, $maybe_old_images );
			$new_images = array_diff( $new_images, $maybe_old_images );
		}

		return array(
			'new' => array_filter( $new_images ),
			'old' => array_filter( wp_parse_id_list( $old_images ) ),
		);
	}

	public function validate( $posted_data ) {
		$files      = $this->get_value( $posted_data );
		$old_images = $files['old'];
		$new_images = $files['new'];

		if ( $this->is_required() && empty( $old_images ) && empty( $new_images ) ) {
			$this->add_error( __( 'This field is required.', 'directorist' ) );

			return false;
		}

		if ( $this->get_total_upload_limit() !== 0 && ( ( count( $old_images ) + count( $new_images ) ) > $this->get_total_upload_limit() ) ) {
			$this->add_error( sprintf(
				_n( '%s image allowed only.', '%s images allowed only.', $this->get_total_upload_limit(), 'directorist' ),
				$this->get_total_upload_limit()
			) );

			return false;
		}

		// TODO: use get_attached_file to calculate the old images file size.

		$upload_dir = wp_get_upload_dir();
		$temp_dir   = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . trailingslashit( directorist_get_temp_upload_dir() . DIRECTORY_SEPARATOR . date( 'nj' ) );
		$total_size = 0;

		foreach ( $new_images as $file ) {
			$filepath  = realpath( $temp_dir . $file );

			if ( empty( $file ) || ! $filepath ) {
				continue;
			}

			$filesize  = filesize( $filepath );
			$real_mime = wp_get_image_mime( $filepath );

			if ( ! $real_mime || strpos( $real_mime, 'image' ) === false ) {

				$this->add_error( sprintf(
					__( '[%1$s] invalid file type, only image allowed.', 'directorist' ),
					$file
				) );

				continue;
			}

			if ( $filesize > $this->get_per_image_upload_size() ) {
				$this->add_error( sprintf(
					__( '[%1$s] size exceeded, %2$s is allowed only.', 'directorist' ),
					$file,
					size_format( $this->get_per_image_upload_size() )
				) );
			}

			$total_size += $filesize;

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
