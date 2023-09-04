<?php
/**
 * Directorist Builder Field Abstract class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Base_Field {

	public $type = 'base';

	protected $directory_id = 0;

	protected $can_trash = true;

	protected $props = array();

	protected $errors = array();

	public function __construct( array $props = array() ) {
		$this->props = $props;
	}

	public function set_directory_id( int $id ) {
		$this->directory_id = $id;
	}

	public function get_directory_id() : int {
		return $this->directory_id;
	}

	public function __get( $name ) {
		if ( isset( $this->props[ $name ] ) ) {
			return $this->props[ $name ];
		}
	}

	public function __set( $name, $value ) {
		return $this->props[ $name ] = $value;
	}

	public function __isset( $name ) {
		return isset( $this->props[ $name ] );
	}

	public function get_key() : string {
		return ( string ) $this->field_key;
	}

	public function get_internal_key() : string {
		return ( string ) $this->widget_key;
	}

	public function is_admin_only() : bool {
		return ( bool ) $this->only_for_admin;
	}

	public function is_required() : bool {
		return ( bool ) $this->required;
	}

	public function is_preset() : bool {
		return ( bool ) ( $this->widget_group === 'preset' );
	}

	public function add_error( $message = '' ) {
		if ( ! isset( $this->errors[ $this->get_internal_key() ] ) ) {
			$this->errors[ $this->get_internal_key() ] = array();
		}

		$this->errors[ $this->get_internal_key() ][] = $message;
	}

	public function get_error() {
		return isset( $this->errors[ $this->get_internal_key() ] ) ? implode( ' ', $this->errors[ $this->get_internal_key() ] ) : '';
	}

	public function has_error() {
		return ( ! empty( $this->get_error() ) );
	}

	public function get_value( $posted_data ) {
		return directorist_get_var( $posted_data[ $this->get_internal_key() ] );
	}

	public function is_value_empty( $posted_data ) {
		$value = $this->get_value( $posted_data );
		return ( is_null( $value ) || ( is_string( $value ) && $value === '' ) || ( is_array( $value ) && empty( $value ) ) );
	}

	public function validate( $posted_data ) {
		return true;
	}

	public function sanitize( $posted_data ) {
		return directorist_clean( $posted_data );
	}

	public function get_builder_label() : string {
		return esc_html_x( 'Base Field', 'Builder field label', 'directorist' );
	}

	public function get_builder_icon() : string {
		return 'uil uil-map-pin';
	}
	
	public function get_builder_fields( $directory_manager ) : array {
		return array();
	}

	public function to_builder_array( $directory_manager ) {
		return array(
			'label'    => $this->get_builder_label(),
			'icon'     => $this->get_builder_icon(),
			'options'  => $this->get_builder_fields( $directory_manager ),
			'canTrash' => $this->can_trash,
		);
	}
}
