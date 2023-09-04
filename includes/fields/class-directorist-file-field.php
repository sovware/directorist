<?php
/**
 * Directorist File Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class File_Field extends Base_Field {

	public $type = 'file';

	public function validate( $posted_data ) {
		return true;
	}

	public function get_value( $posted_data ) {
		return (string) directorist_get_var( $posted_data[ $this->get_key() ], '' );
	}

	public function sanitize( $posted_data ) {
		return $this->get_value( $posted_data );
	}

	public function get_file_types() {
		return $this->file_type;
	}

	public function get_file_size() {
		return wp_convert_hr_to_bytes( $this->file_size );
	}

	public function get_builder_label() : string {
		return esc_html_x( 'File Upload', 'Builder field label', 'directorist' );
	}

	public function get_builder_icon() : string {
		return 'uil uil-file-upload-alt';
	}

	public function get_builder_fields( $directory_manager ) : array {
		return array(
			'type' => array(
				'type'  => 'hidden',
				'value' => 'file',
			),
			'field_key' => array(
				'type'  => 'hidden',
				'value' => 'custom-file',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			),
			'label' => array(
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'File Upload',
			),
			'description' => [
				'type'  => 'text',
				'label' => __( 'Description', 'directorist' ),
				'value' => '',
			],
			'file_type' => [
				'type'        => 'select',
				'label'       => __( 'Select a file type', 'directorist' ),
				'description' => __( 'By selecting a file type you are going to allow your users to upload only that or those type(s) of file.', 'directorist' ),
				'value'       => 'image',
				'options'     => $directory_manager::get_file_upload_field_options(),
			],
			'file_size' => [
				'type'        => 'text',
				'label'       => __( 'File Size', 'directorist' ),
				'description' => __( 'Set maximum file size to upload', 'directorist' ),
				'value'       => '2mb',
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

Fields::register( new File_Field() );
